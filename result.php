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
//"zgradi" stavek za iskanje v bazi
$searchQuery = "SELECT *, p.id AS pid FROM parts p INNER JOIN models_parts pm WHERE";
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
if (!empty($model) || !empty($type)) {
    $searchQuery .= " GROUP BY p.id";
}
$resultQuery = mysqli_query($link, $searchQuery);
file_logs($searchQuery, $_SERVER["REMOTE_ADDR"], $_SESSION["user_id"]);
if (!empty($number)) {
//Poglej za kataloško številko
    $searchQueryNumber = "SELECT * FROM parts WHERE number = '$number'";
    $resultNumber = mysqli_query($link, $searchQueryNumber);
}
?>
<div class="col-lg-12 block-flat">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">Rezultat iskanja</h3>
            <?php if (!empty($number)) { ?>
                <h4 class="page-header">Rezultati kataloške številke</h4>
                <?php if (mysqli_num_rows($resultNumber) > 0) { ?>
                    <?php while ($part = mysqli_fetch_array($resultNumber)) { ?>
                        <div class="media">
                            <a class="media-left media-middle col-lg-4 col-sm-12" href="/part/<?php echo $part["pid"]; ?>">
                                <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                            </a>
                            <div class="media-body">
                                <a href="/part/<?php echo $part["pid"]; ?>">
                                    <h4 class="media-heading"><?php echo $part["name"]; ?></h4>
                                </a>
                                <?php echo $part["description"]; ?>
                            </div>
                        </div>
                        <br />
                        <hr />
                        <br />
                    <?php } ?>
                <?php } else { ?>
                    <center><h5>Dela s takšno kataloško številko ni v podatkovni bazi!</h5></center>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-12">
            <?php if (!empty($number) && mysqli_num_rows($resultNumber) > 0) { ?>
                <h4 class="page-header">Rezultati iskanja glede na preostale kriterije</h4>
            <?php } ?>
            <?php if (mysqli_num_rows($resultQuery) > 0) { ?>
                <?php while ($part = mysqli_fetch_array($resultQuery)) { ?>
                    <div class="media">
                        <a class="media-left media-middle col-lg-4 col-sm-12" href="/part/<?php echo $part["pid"]; ?>">
                            <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                        </a>
                        <div class="media-body">
                            <a href="/part/<?php echo $part["pid"]; ?>">
                                <h4 class="media-heading"><?php echo $part["name"]; ?></h4>
                            </a>
                            <?php echo $part["description"]; ?>
                        </div>
                    </div>
                    <br />
                    <hr />
                    <br />
                <?php } ?>
            <?php } else { ?>
                <center><h5>Brez uspeha! Ni takšnega dela</h5></center>
            <?php } ?>
        </div>
    </div>
</div>
<?php include_once 'footer.php'; ?>