<?php include_once 'header.php'; ?>
<?php
$string = $_REQUEST["query"];
$searchQuery = "SELECT * FROM parts WHERE type_id IN (1,2,3,4,5,6,7) AND";
$searchQuery .= " (number = '$string' OR type LIKE '%$string%' OR description LIKE '%$string%')";
$resultQuery = mysqli_query($link, $searchQuery);
?>
<div class="block-flat col-lg-12">
    <h3 class="page-header">Iskalni niz: <?php echo strip_tags($string); ?></h3>
    <?php if (mysqli_num_rows($resultQuery) > 0) { ?>
        <?php while ($part = mysqli_fetch_array($resultQuery)) { ?>
            <?php echo $part["name"]; ?>
        <?php } ?>
    <?php } else { ?>
        <center><h5>Brez uspeha! Ni takšnega dela</h5></center>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>