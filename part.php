<?php include_once 'header.php'; ?>
<?php
$id = cleanString((int) $_GET["id"]);
$queryPart = "SELECT *, t.name AS type_name, p.name AS partname FROM parts p INNER JOIN types t ON t.id = p.type_id WHERE p.id = $id";
$resultPart = mysqli_query($link, $queryPart);
$part = mysqli_fetch_array($resultPart);
$queryPartImages = "SELECT * FROM images WHERE part_id = $id";
$resultPartImages = mysqli_query($link, $queryPartImages);
?>
<div class="block-flat col-lg-12">
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
            <img src="<?php echo $part["image"]; ?>" class="img-responsive" alt="Part image" />
            <div class="clear"></div>
            <small>
                Objavljeno: <time><?php echo date("d. m. Y", strtotime($part["created"])); ?></time>
                <br />
                <?php if ($part["created"] != $part["edited"]) { ?>
                    Zadnjič urejeno: <time><?php echo date("d. m. Y", strtotime($part["edited"])); ?></time>
                <?php } ?>
            </small>
            <div class="clear"></div>
            <?php while($image = mysqli_fetch_array($resultPartImages)) { ?>
            <img src="<?php echo $image["link"] ?>" alt="Part gallery" class="pull-left" style="margin-right: 10px;" width="100" height="100" />
            <?php } ?>
        </div>
        <div class="col-lg-8">
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
                            Cena:
                        </td>
                        <td>
                            <?php echo price($part["price"]) ?>€
                        </td>
                    </tr>
                <?php } ?>
                <?php if (!empty($part["number"])) { ?>
                    <tr>
                        <td>
                            Kataloška številka:
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
                $queryPartModels = "SELECT *, m.name AS model, b.name AS brand FROM models_parts mp INNER JOIN models m ON mp.model_id = m.id INNER JOIN brands b ON b.id = m.brand_id WHERE mp.part_id = $id";
                $resultPartModels = mysqli_query($link, $queryPartModels);
                ?>
                <?php while ($car = mysqli_fetch_array($resultPartModels)) { ?>
                    <tr>
                        <td colspan="2"><?php
            echo $car["brand"] . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $car["model"];
            if (!empty($car["type"])) {
                echo "&nbsp;&nbsp;/&nbsp;&nbsp;" . $car["type"];
            }
                    ?></td>
                    </tr>
                    <?php if (!empty($car["year"])) { ?>
                        <tr>
                            <td>
                                Letnik:
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
    <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-flat btn-primary">Nazaj</a>
</div>
<?php include_once 'footer.php'; ?>