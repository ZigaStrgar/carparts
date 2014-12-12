<?php include_once 'header.php'; ?>
<?php
$id = cleanString((int) $_GET["id"]);
$queryPart = "SELECT *, t.name AS type_name, b.name AS brand, m.name AS model, p.name AS partname FROM parts p INNER JOIN models_parts mp ON p.id = mp.part_id INNER JOIN models m ON m.id = mp.model_id INNER JOIN brands b ON b.id = m.brand_id INNER JOIN types t ON t.id = p.type_id WHERE p.id = $id";
$resultPart = mysqli_query($link, $queryPart);
$part = mysqli_fetch_array($resultPart);
?>
<div class="block-flat col-lg-12">
    <?php if (mysqli_num_rows($resultPart) > 0) { ?>
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
                    <th colspan="2">
                        Podatki o avtomobilih
                    </th>
                </tr>
                <?php if (!empty($part["year"])) { ?>
                    <tr>
                        <td>
                            Letnik:
                        </td>
                        <td>
                            <?php echo $part["year"] ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } else { ?>
        <h1 class="text-center">Takšen del ne obstaja, ali pa je že prodan!</h1>
    <?php } ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-flat btn-primary">Nazaj</a>
</div>
<?php include_once 'footer.php'; ?>