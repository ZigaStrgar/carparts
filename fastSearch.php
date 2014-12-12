<?php include_once 'header.php'; ?>
<?php
$string = $_REQUEST["query"];
//IS BRAND
$brandQuery = "SELECT * FROM brands WHERE name LIKE '%$string%'";
$brandResult = mysqli_query($link, $brandQuery);
file_logs($brandQuery, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
if (mysqli_num_rows($brandResult) > 0) {
    $brand = mysqli_fetch_array($brandResult);
    $models = getModels($brand["id"], $link);
}
//IS MODEL
$queryModel = "SELECT id FROM models WHERE name LIKE '%$string%'";
$resultModel = mysqli_query($link, $queryModel);
file_logs($queryModel, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
if (mysqli_num_rows($resultModel) > 0) {
    $model = mysqli_fetch_array($resultModel);
    if (empty($models)) {
        $models .= $model["id"];
    } else {
        $models .= "," . $model["id"];
    }
}
//USUAL SEARCH QUERY
$searchQuery = "SELECT *,id AS pid FROM parts WHERE type_id IN (1,2,3,4,5,6,7) AND";
$searchQuery .= " (number = '$string' OR type LIKE '%$string%' OR description LIKE '%$string%')";
if (!empty($models)) {
    $searchQuery = "SELECT *,p.id AS pid FROM parts p INNER JOIN models_parts pm ON pm.part_id = p.id WHERE (pm.model_id IN ($models)) GROUP BY p.id";
}
file_logs($searchQuery, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
$resultQuery = mysqli_query($link, $searchQuery);
?>
<div class="block-flat col-lg-12">
    <h1 class="page-header">Iskalni niz: <?php echo strip_tags($string); ?></h1>
    <?php if (mysqli_num_rows($resultQuery) > 0) { ?>
        <?php while ($part = mysqli_fetch_array($resultQuery)) { ?>
            <div class="media">
                <a class="media-left media-middle col-lg-4 col-sm-12" href="/part/<?php echo $part["pid"]; ?>">
                    <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                </a>
                <div class="media-body">
                    <a href="/part/<?php echo $part["pid"]; ?>">
                        <h3 class="media-heading"><?php echo $part["name"]; ?></h3>
                    </a>
                    <?php echo $part["description"]; ?>
                </div>
            </div>
            <br />
            <hr />
            <br />
        <?php } ?>
    <?php } else { ?>
        <center><h4>Brez uspeha! Ni takšnega dela</h4></center>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>