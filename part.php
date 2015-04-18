<?php include_once 'header.php'; ?>
<?php
$id = cleanString((int) $_GET["id"]);
$part = Db::queryOne("SELECT *, p.name AS partname FROM parts p WHERE p.id = ? AND p.deleted = 0 AND p.pieces > 0", $id);
$images = Db::queryAll("SELECT * FROM images WHERE part_id = ?", $id);
?>
<div class="block-flat col-lg-12 top-warning">
    <?php if (Db::query("SELECT *, p.name AS partname FROM parts p WHERE p.id = ? AND p.deleted = 0 AND p.pieces > 0", $id) == 1) { ?>
        <?php interest($id, $part["category_id"], $_SESSION["user_id"], "", ""); ?>
        <div class="page-header">
            <h1><?php echo $part["partname"]; ?></h1>
        </div>
        <ol class="breadcrumb">
            <?php
            categoryParents($part["category_id"]);
            ?>
        </ol>
        <div class="col-lg-4">
            <div style="position: relative; overflow: hidden;">
                <?php if ($part["new"] == 1) { ?>
                    <figure class="left-ribbon">NOVO</figure>
                <?php } ?>
                <a href="<?php echo $part["image"]; ?>" data-toggle="lightbox" <?php if (count($images) > 0) { ?>data-gallery="gal"<?php } ?> data-title="<?php echo $part["partname"]; ?>">
                    <img src="<?php echo $part["image"]; ?>" class="img-responsive">
                </a>
            </div>
            <div class="clear"></div>
            <br />
            <?php foreach ($images as $image) { ?>
                <a href="http://<?= URL; ?>/<?php echo $image["link"]; ?>" data-toggle="lightbox" data-gallery="gal" data-title="<?php echo $part["partname"]; ?>">
                    <img src="http://<?= URL; ?>/<?php echo $image["link"]; ?>" alt="Part gallery" class="pull-left" style="margin-right: 10px;" width="100" height="100" />
                </a>
            <?php } ?>
            <div class="clear"></div>
        </div>
        <div class="col-lg-8">
            <?= $part["description"]; ?>
            <br />
            <span class="price"><?= price($part["price"]) . " €"; ?></span>
            <br />
            <?php if (!empty($user["id"])) { ?>
                <?php if (!my_part($id, $user["id"])) { ?>
                    <span onclick="addToCart(<?php echo $id ?>)" class="btn btn-flat btn-success">Dodaj v košarico</span>
                <?php } ?>
                <?php if (my_part($id, $_SESSION["user_id"])) { ?>
                    <span class="pull-right">
                        <a href="../editPart/<?php echo $id; ?>" class="btn btn-flat btn-primary"><i class="icon icon-pencil"></i> Uredi del</a>
                        <a id="del" class="btn btn-flat btn-danger"><i class="icon icon-remove"></i> Izbriši del</a>
                    </span>
                    <div class="clear"></div>
                <?php } ?>
            <?php } ?>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <br />
        <div class="col-lg-12">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Podatki o delu
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        Cena
                                    </td>
                                    <td>
                                        <?= price($part["price"]) ?> €
                                    </td>
                                </tr>
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
                                <?php if ($part["new"] == 1) { ?>
                                    <tr>
                                        <td>
                                            Stanje dela
                                        </td>
                                        <td>
                                            NOVO
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Podatki o avtomobilih
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <?php
                                $resultPartModels = Db::queryAll("SELECT *, m.name AS model, b.name AS brand, m.id AS mid, b.id AS bid FROM models_parts mp INNER JOIN models m ON mp.model_id = m.id INNER JOIN brands b ON b.id = m.brand_id WHERE mp.part_id = ?", $id);
                                ?>
                                <?php foreach ($resultPartModels as $car) { ?>
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
                    </div>
                </div>
                <?php if ($part["location"] != 0) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Lokacija dela
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                                <?php
                                switch ($part["location"]) {
                                    case 1:
                                        echo "<img src='../img/ff.png' height='400' alt='Spredaj' />";
                                        break;
                                    case 2:
                                        echo "<img src='../img/rf.png' height='400' alt='Zadaj' />";
                                        break;
                                    case 3:
                                        echo "<img src='../img/fl.png' height='400' alt='Spredaj levo' />";
                                        break;
                                    case 4:
                                        echo "<img src='../img/fr.png' height='400' alt='Spredaj desno' />";
                                        break;
                                    case 5:
                                        echo "<img src='../img/rl.png' height='400' alt='Zadaj levo' />";
                                        break;
                                    case 6:
                                        echo "<img src='../img/rr.png' height='400' alt='Zadaj desno' />";
                                        break;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <h1 class="text-center">Takšen del ne obstaja, ali pa je že prodan!</h1>
    <?php } ?>
    <div class="clear"></div>
    <hr />
    <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-flat btn-primary">Nazaj</a>
    <?php if ((my_part($id, $_SESSION["user_id"]) && Db::query("SELECT *, p.name AS partname FROM parts p WHERE p.id = ? AND p.deleted = 0 AND p.pieces > 0", $id) == 1) || $user["email"] == "ziga_strgar@hotmail.com") { ?>
        <span class="pull-right">
            <a href="../editPart/<?php echo $id; ?>" class="btn btn-flat btn-primary"><i class="icon icon-pencil"></i> Uredi del</a>
            <a id="del" class="btn btn-flat btn-danger"><i class="icon icon-remove"></i> Izbriši del</a>
        </span>
        <div class="clear"></div>
    <?php } ?>
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
    function addToCart(part) {
        $.ajax({
            url: "../addToCart.php",
            type: "POST",
            data: {part: part},
            success: function (cb) {
                cb = $.trim(cb);
                cb = cb.split("|");
                if (cb[0] === "error") {
                    alertify.error(cb[1]);
                }
                if (cb[0] === "success") {
                    alertify.success(cb[1]);
                    $("#cartNum").text(cb[2]);
                }
            }
        });
    }
</script>
<?php include_once './footer.php'; ?>