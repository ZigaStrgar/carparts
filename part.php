<?php include_once 'header.php'; ?>
<?php
$id = cleanString((int) $_GET["id"]);
$queryPart = "SELECT *, t.name AS type_name, p.name AS partname FROM parts p INNER JOIN types t ON t.id = p.type_id WHERE p.id = $id AND p.deleted = 0";
$resultPart = mysqli_query($link, $queryPart);
$part = mysqli_fetch_array($resultPart);
$queryPartImages = "SELECT * FROM images WHERE part_id = $id";
$resultPartImages = mysqli_query($link, $queryPartImages);
?>
<div class="block-flat col-lg-12 top-warning">
    <?php if (mysqli_num_rows($resultPart) == 1) { ?>
        <div class="page-header">
            <h1><?php echo $part["partname"]; ?></h1>
            <ol class="breadcrumb">
                <?php
                categoryParents($part["category_id"], $link);
                ?>
            </ol>
        </div>
        <div class="col-lg-4">
            <a href="<?php echo $part["image"]; ?>" data-toggle="lightbox" <?php if (mysqli_num_rows($resultPartImages) > 0) { ?>data-gallery="gal"<?php } ?> data-title="<?php echo $part["partname"]; ?>">
                <img src="<?php echo $part["image"]; ?>" class="img-responsive">
            </a>
            <div class="clear"></div>
            <small>
                Objavljeno: <time><?php echo date("d. m. Y", strtotime($part["created"])); ?></time>
                <br />
                <?php if ($part["created"] != $part["edited"]) { ?>
                    Zadnjič urejeno: <time><?php echo date("d. m. Y", strtotime($part["edited"])); ?></time>
                <?php } ?>
            </small>
            <div class="clear"></div>
            <?php while ($image = mysqli_fetch_array($resultPartImages)) { ?>
                <a href="<?php echo $image["link"]; ?>" data-toggle="lightbox" data-gallery="gal" data-title="<?php echo $part["partname"]; ?>">
                    <img src="<?php echo $image["link"]; ?>" alt="Part gallery" class="pull-left" style="margin-right: 10px;" width="100" height="100" />
                </a>
            <?php } ?>
        </div>
        <div class="col-lg-8">
            <?php if (my_part($id, $_SESSION["user_id"], $link)) { ?>
                <span class="pull-right">
                    <a href="../editPart/<?php echo $id; ?>" class="btn btn-flat btn-primary"><i class="icon icon-pencil"></i> Uredi del</a>
                    <a id="del" class="btn btn-flat btn-danger"><i class="icon icon-remove"></i> Izbriši del</a>
                </span>
                <div class="clear"></div>
                <br />
            <?php } ?>
            <table class="table table-condensed">
                <tr>
                    <th colspan="2">
                        Podatki o delu
                    </th>
                </tr>
                <?php if (!empty($part["description"])) { ?>
                    <tr>
                        <td colspan="2">
                            <?php echo $part["description"]; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (!empty($part["price"])) { ?>
                    <tr>
                        <td>
                            Cena
                        </td>
                        <td>
                            <?php echo price($part["price"]) ?> €
                        </td>
                    </tr>
                <?php } ?>
                <?php if (!empty($part["number"])) { ?>
                    <tr>
                        <td>
                            Kataloška številka
                        </td>
                        <td>
                            <?php echo $part["number"] ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>
                        Število kosov
                    </td>
                    <td>
                        <?php echo $part["pieces"]; ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        Podatki o avtomobilih
                    </th>
                </tr>
                <?php
                $queryPartModels = "SELECT *, m.name AS model, b.name AS brand, m.id AS mid, b.id AS bid FROM models_parts mp INNER JOIN models m ON mp.model_id = m.id INNER JOIN brands b ON b.id = m.brand_id WHERE mp.part_id = $id";
                $resultPartModels = mysqli_query($link, $queryPartModels);
                ?>
                <?php while ($car = mysqli_fetch_array($resultPartModels)) { ?>
                    <tr>
                        <td colspan="2"><?php
                            echo "<a href='../result/brand/" . $car["bid"] . "'>" . $car["brand"] . "</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href='../result/model/" . $car["mid"] . "'>" . $car["model"] . "</a>";
                            if (!empty($car["type"])) {
                                echo "&nbsp;&nbsp;/&nbsp;&nbsp;<a href='../result/type/" . $car["type"] . "'>" . $car["type"] . "</a>";
                            }
                            ?></td>
                    </tr>
                    <?php if (!empty($car["year"])) { ?>
                        <tr>
                            <td>
                                Letnik
                            </td>
                            <td>
                                <?php echo $car["year"] ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
        </div>
    <?php } else { ?>
        <h1 class="text-center">Takšen del ne obstaja, ali pa je že prodan!</h1>
    <?php } ?>
    <div class="clear"></div>
    <hr />
    <?php if (mysqli_num_rows($resultPart) > 0 && !empty($_SESSION["user_id"])) { ?>
        <span onclick="addToCart(<?php echo $id ?>)" class="btn btn-flat btn-success pull-right">Dodaj v košarico</span>
    <?php } ?>
    <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-flat btn-primary">Nazaj</a>
</div>
<script async>
    $(document).on("click", "#del", function () {
        swal({
            title: "Ali ste prepričani?",
            text: "Izdelek bo izbrisan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Da, izbriši del!",
            closeOnConfirm: false,
            cancelButtonText: "Ne!"
        }, function () {
            window.location = "../deletePart/<?php echo $id; ?>";
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function ($) {
        $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function (event) {
            event.preventDefault();
            return $(this).ekkoLightbox({
                onShown: function () {
                    if (window.console) {
                        return console.log('Checking our the events huh?');
                    }
                },
                onNavigate: function (direction, itemIndex) {
                    if (window.console) {
                        return console.log('Navigating ' + direction + '. Current item: ' + itemIndex);
                    }
                }
            });
        });
    });
    function addToCart(part){
        $.ajax({
           url: location.protocol + "//" + location.host +"/addToCart.php",
           type: "POST",
           data: {part: part},
           success: function(cb){
               cb = $.trim(cb);
               cb = cb.split("|");
               if(cb[0] === "error"){
                   alertify.error(cb[1]);
               }
               if(cb[0] === "success"){
                   alertify.success(cb[1]);
                   $("#cartNum").text(cb[2]);
               }
           }
        });
    }
</script>
<?php include_once 'footer.php'; ?>