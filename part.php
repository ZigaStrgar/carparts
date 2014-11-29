<?php include_once 'header.php'; ?>
<?php
$id = cleanString((int) $_GET["id"]);
$queryPart = "SELECT *, t.name AS type_name, b.name AS brand, m.name AS model, p.name AS partname FROM parts p INNER JOIN models m ON p.model_id = m.id INNER JOIN brands b ON b.id = m.brand_id INNER JOIN types t ON t.id = p.type_id WHERE p.id = $id";
$resultPart = mysqli_query($link, $queryPart);
$part = mysqli_fetch_array($resultPart);
?>
<div class="block-flat col-lg-12">
    <?php if (mysqli_num_rows($resultPart) > 0) { ?>
        <div class="page-header">
            <h3><?php echo $part["partname"]; ?></h3>
            <p class="breadcrumb"> 
                <?php echo $part["type_name"] . "&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;" . $part["brand"] . "&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;" . $part["model"]; ?><?php if (!empty($part["type"])) {
                echo "&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;" . $part["type"];
            } ?>
            </p>
        </div>
        <div class="col-lg-4">
            <img src="<?php echo $part["image"]; ?>" class="img-responsive" alt="Part image" />
        </div>
        <div class="col-lg-8">
            <table class="table table-condensed">
                <tr>
                    <td colspan="2">
                        <?php echo $part["description"]; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cena:
                    </td>
                    <td>
                        <?php echo $part["price"]; ?>€
                    </td>
                </tr>
                <tr>
                    <td>
                        Kataloška številka:
                    </td>
                    <td>
                        <?php echo $part["number"] ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php } else { ?>
        <h3 class="text-center">Takšen del ne obstaja, ali pa je že prodan!</h3>
<?php } ?>
</div>
<?php include_once 'footer.php'; ?>