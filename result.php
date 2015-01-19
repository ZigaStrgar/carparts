<?php include_once 'header.php'; ?>
<?php
//TIPI
if (!empty($_POST["types"])) {
    foreach ($_POST["types"] as $type) {
        $types .= "$type,";
    }
    $types = substr($types, 0, strlen($types) - 1);
} else {
    $types = "1,2,3,4,5,6,7";
}
//Podrobnosti avtomobila
$model = (int) $_POST["model"];
$type = strtolower(cleanString($_POST["type"]));
$year = (int) $_POST["letnik"];
//Podatki dela
$number = cleanString($_POST["number"]);
$partName = strtolower(cleanString($_POST["partname"]));
$category = (int) $_POST["category"];
$price = $_POST["price"];
$price = explode(";", $price);
$min = $price[0];
$max = $price[1];
//"zgradi" stavek za iskanje v bazi
$searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts pm WHERE p.deleted = 0 AND p.price >= $min AND p.price <= $max AND";
//Stavku doda tip avtomobila
if (!empty($types)) {
    $searchQuery .= " p.type_id IN ($types)";
}
//Stavku doda model avtomobila
if (!empty($model)) {
    $searchQuery .= " AND pm.model_id = $model";
}
//Stavku doda leto avtomobila
if (!empty($year)) {
    $searchQuery .= " AND pm.year = $year";
}
//Stavku doda tip modela
if (!empty($type)) {
    $searchQuery .= " AND lower(pm.type) LIKE '%$type%'";
}
//Stavku doda ime dela
if (!empty($partName)) {
    $searchQuery .= " AND lower(p.name) LIKE '%$partName%'";
}
//Stavku doda kategorijo dela
if (!empty($categoryID)) {
    $searchQuery .= " AND p.category_id = $category";
}
//Išče po kategoriji
if (!empty($_GET["category"])) {
    $category = (int) cleanString($_GET["category"]);
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p WHERE p.deleted = 0 AND p.category_id = $category";
}
//Išče po modelu
if (!empty($_GET["model"])) {
    $model = (int) cleanString($_GET["model"]);
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE p.deleted = 0 AND mp.model_id = $model";
}
//Išče po znamki
if (!empty($_GET["brand"])) {
    $brand = (int) cleanString($_GET["brand"]);
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id INNER JOIN models m ON m.id = mp.model_id WHERE p.deleted = 0 AND m.brand_id = $brand";
}
//Išče po tipu model
if (!empty($_GET["type"])) {
    $type = strtolower(cleanString($_GET["type"]));
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE p.deleted = 0 AND lower(mp.type) LIKE '%$type%'";
}
//GROUP BY (odstrani podvajanje podatkov/delov/rezultatov)
$searchQuery .= " GROUP BY p.id";
$results = Db::queryAll($searchQuery);
interest("", $category, $_SESSION["user_id"], $model, $brand);
if (!empty($number)) {
//Poglej za kataloško številko
    $resultNumber = Db::queryAll("SELECT *, id AS pid FROM parts WHERE deleted = 0 AND number = ?", $number);
}
?>
<div class="col-lg-12 block-flat top-info">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Rezultati iskanja</h1>
            <?php if (!empty($number)) { ?>
                <h3 class="page-header">Rezultati kataloške številke</h3>
                <?php if (count($resultNumber) > 0) { ?>
                    <?php foreach ($resultNumber as $part) { ?>
                        <div class="media">
                            <a class="media-left media-middle col-lg-4 col-sm-12" href="http://<?php echo URL; ?>/part/<?php echo $part["pid"]; ?>">
                                <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                            </a>
                            <div class="media-body col-lg-8 col-sm-12">
                                <a href="http://<?php echo URL; ?>/part/<?php echo $part["pid"]; ?>">
                                    <h3 class="media-heading"><?php echo $part["partname"]; ?></h3>
                                </a>
                                <?php echo $part["description"]; ?>
                            </div>
                        </div>
                        <hr />
                        <br />
                    <?php } ?>
                <?php } else { ?>
                    <center><h4>Dela s takšno kataloško številko ni v podatkovni bazi!</h4></center>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-12">
            <?php if (!empty($number) && count($resultNumber) > 0) { ?>
                <h3 class="page-header">Rezultati iskanja glede na preostale kriterije</h3>
            <?php } ?>
            <?php if (count($results) > 0) { ?>
                <?php foreach ($results as $part) { ?>
                    <div class="media">
                        <a class="media-left media-middle col-lg-4 col-sm-12" href="http://<?php echo URL; ?>/part/<?php echo $part["pid"]; ?>">
                            <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                        </a>
                        <div class="media-body col-lg-8 col-sm-12">
                            <a href="http://<?php echo URL; ?>/part/<?php echo $part["pid"]; ?>">
                                <h3 class="media-heading"><?php echo $part["partname"]; ?></h3>
                            </a>
                            <?php echo $part["description"]; ?>
                        </div>
                    </div>
                    <hr />
                    <br />
                <?php } ?>
            <?php } else { ?>
                <center><h4>Brez uspeha! Ni takšnega dela</h4></center>
            <?php } ?>
        </div>
    </div>
</div>
<?php include_once 'footer.php'; ?>