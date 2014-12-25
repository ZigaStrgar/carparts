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
$categoryID = (int) $_POST["category"];
$price = $_POST["price"];
$price = explode(";", $price);
$min = $price[0];
$max = $price[1];
//"zgradi" stavek za iskanje v bazi
$searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts pm WHERE p.price >= $min AND p.price <= $max AND";
if (!empty($types)) {
    $searchQuery .= " p.type_id IN ($types)";
}
if (!empty($model)) {
    $searchQuery .= " AND pm.model_id = $model";
}
if (!empty($year)) {
    $searchQuery .= " AND pm.year = $year";
}
if (!empty($type)) {
    $searchQuery .= " AND lower(pm.type) LIKE '%$type%'";
}
if (!empty($partName)) {
    $searchQuery .= " AND lower(p.name) LIKE '%$partName%'";
}
if (!empty($categoryID)) {
    $searchQuery .= " AND p.category_id = $categoryID";
}
if(!empty($_GET["category"])){
    $category = (int) cleanString($_GET["category"]);
    $searchQuery = "SELECT *, id AS pid, p.name AS partname FROM parts WHERE category_id = $category";
}
if(!empty($_GET["model"])){
    $model = (int) cleanString($_GET["model"]);
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE mp.model_id = $model";
}
if(!empty($_GET["brand"])){
    $brand = (int) cleanString($_GET["brand"]);
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id INNER JOIN models m ON m.id = mp.model_id WHERE m.brand_id = $brand";
}
if(!empty($_GET["type"])){
    $type = strtolower(cleanString($_GET["type"]));
    $searchQuery = "SELECT *, p.id, p.name AS partname AS pid FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE lower(mp.type) LIKE '%$type%'";
}
$searchQuery .= " GROUP BY p.id";
$resultQuery = mysqli_query($link, $searchQuery);
file_logs($searchQuery, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
if (!empty($number)) {
//Poglej za kataloško številko
    $searchQueryNumber = "SELECT *, id AS pid FROM parts WHERE number = '$number'";
    $resultNumber = mysqli_query($link, $searchQueryNumber);
}
?>
<div class="col-lg-12 block-flat">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Rezultati iskanja</h1>
            <?php if (!empty($number)) { ?>
                <h3 class="page-header">Rezultati kataloške številke</h3>
                <?php if (mysqli_num_rows($resultNumber) > 0) { ?>
                    <?php while ($part = mysqli_fetch_array($resultNumber)) { ?>
                        <div class="media">
                            <a class="media-left media-middle col-lg-4 col-sm-12" href="/part/<?php echo $part["pid"]; ?>">
                                <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                            </a>
                            <div class="media-body col-lg-8 col-sm-12">
                                <a href="/part/<?php echo $part["pid"]; ?>">
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
            <?php if (!empty($number) && mysqli_num_rows($resultNumber) > 0) { ?>
                <h3 class="page-header">Rezultati iskanja glede na preostale kriterije</h3>
            <?php } ?>
            <?php if (mysqli_num_rows($resultQuery) > 0) { ?>
                <?php while ($part = mysqli_fetch_array($resultQuery)) { ?>
                    <div class="media">
                        <a class="media-left media-middle col-lg-4 col-sm-12" href="/part/<?php echo $part["pid"]; ?>">
                            <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                        </a>
                        <div class="media-body col-lg-8 col-sm-12">
                            <a href="/part/<?php echo $part["pid"]; ?>">
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