<?php include_once 'header.php'; ?>
<?php
$string = $_REQUEST["query"];
//IS BRAND
$brandQuery = "SELECT * FROM brands WHERE name LIKE '%$string%'";
$brandResult = mysqli_query($link, $brandQuery);
if (mysqli_num_rows($brandResult) > 0) {
    $brand = mysqli_fetch_array($brandResult);
    $models = getModels($brand["id"], $link);
}
//IS MODEL
$queryModel = "SELECT id FROM models WHERE name LIKE '%$string%'";
$resultModel = mysqli_query($link, $queryModel);
if (mysqli_num_rows($resultModel) > 0) {
    $model = mysqli_fetch_array($resultModel);
    if (empty($models)) {
        $models .= $model["id"];
    } else {
        $models .= "," . $model["id"];
    }
}
//USUAL SEARCH QUERY
$searchQuery = "SELECT * FROM parts WHERE type_id IN (1,2,3,4,5,6,7) AND";
$searchQuery .= " (number = '$string' OR type LIKE '%$string%' OR description LIKE '%$string%')";
if (!empty($models)) {
    $searchQuery .= " OR (model_id IN ($models))";
}
$resultQuery = mysqli_query($link, $searchQuery);
?>
<div class="block-flat col-lg-12">
    <h3 class="page-header">Iskalni niz: <?php echo strip_tags($string); ?></h3>
    <?php if (mysqli_num_rows($resultQuery) > 0) { ?>
        <?php while ($part = mysqli_fetch_array($resultQuery)) { ?>
            <div class="col-lg-4 col-md-12" style="border: 1px solid grey">
                <img src="<?php echo $part["image"]; ?>" alt="Part image" width="350"/>
                <?php echo $part["name"]; ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <center><h5>Brez uspeha! Ni takšnega dela</h5></center>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>