<?php include_once 'header.php'; ?>
<?php
$queryParts = "SELECT *, p.name AS partName FROM parts p INNER JOIN models m ON m.id = p.model_id INNER JOIN brands b ON b.id = m.brand_id INNER JOIN types t ON t.id = p.type_id";
$resultParts = mysqli_query($link, $queryParts);
?>
<div class="block-flat col-lg-12">
    <div class="page-header"><h3>Deli</h3></div>
    <?php while ($part = mysqli_fetch_array($resultParts)) { ?>
        <div class="col-lg-4 col-md-12" style="border: 1px solid grey">
            <img src="<?php echo $part["image"]; ?>" alt="Part image" width="350"/>
            <?php echo $part["partName"]; ?>
        </div>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>