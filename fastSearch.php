<?php include_once 'header.php'; ?>
<?php
$string = strtolower($_REQUEST["query"]);
//IS BRAND
$brand = Db::queryAll("SELECT * FROM brands WHERE lower(name) LIKE '%$string%'");
if (count($brand) > 0) {
    $models = getModels($brand[0]["id"]);
}
//IS MODEL
$model = Db::queryOne("SELECT * FROM models WHERE lower(name) LIKE '%$string%'");
if (count($model) > 0) {
    if (empty($models)) {
        $models .= $model["id"];
    } else {
        $models .= "," . $model["id"];
    }
}
//USUAL SEARCH QUERY
$searchQuery = "SELECT *,id AS pid FROM parts WHERE";
$searchQuery .= " (lower(number) = '$string' OR lower(name) LIKE '%$string%' OR lower(description) LIKE '%$string%')";
if (!empty($models)) {
    $searchQuery = "SELECT *,p.id AS pid FROM parts p INNER JOIN models_parts pm ON pm.part_id = p.id WHERE (pm.model_id IN ($models)) GROUP BY p.id";
}
$results = Db::queryAll($searchQuery);
?>
<div class="block-flat col-lg-12">
    <h1 class="page-header">Iskalni niz: <?php echo strip_tags($string); ?></h1>
    <?php if (count($results) > 0) { ?>
        <?php foreach ($results as $part) { ?>
            <div class="media">
                <a class="media-left media-middle col-lg-4 col-sm-12" href="http://<?php echo URL; ?>/part/<?php echo $part["pid"]; ?>">
                    <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                </a>
                <div class="media-body">
                    <a href="http://<?php echo URL; ?>/part/<?php echo $part["pid"]; ?>">
                        <h3 class="media-heading"><?php echo $part["name"]; ?></h3>
                    </a>
                    <?php echo $part["description"]; ?>
                </div>
            </div>
            <br />
            <hr />
        <?php } ?>
    <?php } else { ?>
        <center><h4>Brez uspeha! Ni takšnega dela</h4></center>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>