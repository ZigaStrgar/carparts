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
$searchQuery = "SELECT * FROM parts WHERE";
if (!empty($types)) {
    $searchQuery .= " type_id IN ($types)";
}
if (!empty($model)) {
    $searchQuery .= " AND model_id = $model";
}
if (!empty($year)) {
    $searchQuery .= " AND year = $year";
}
if (!empty($type)) {
    $searchQuery .= " AND lower(type) LIKE '%$type%'";
}
if (!empty($partName)) {
    $searchQuery .= " AND lower(name) LIKE '%$partName%'";
}
if (!empty($categoryID)) {
    $searchQuery .= " AND category_id = $categoryID";
}
$resultQuery = mysqli_query($link, $searchQuery);
if (!empty($number)) {
//Poglej za kataloško številko
    $searchQueryNumber = "SELECT * FROM parts WHERE number = '$number'";
    $resultNumber = mysqli_query($link, $searchQueryNumber);
}
?>
<div class="col-lg-12 block-flat">
    <?php echo $searchQueryNumber . "<br />" . $searchQuery; ?>
    <h3 class="page-header">Rezultat iskanja</h3>
    <?php if (!empty($number)) { ?>
        <h4 class="page-header">Rezultati kataloške številke</h4>
        <?php if (mysqli_num_rows($resultNumber) > 0) { ?>
            <?php while ($part = mysqli_fetch_array($resultNumber)) { ?>
                <?php echo $part["name"]; ?>
            <?php } ?>
        <?php } else { ?>
            <center><h5>Dela s takšno kataloško številko ni v podatkovni bazi!</h5></center>
        <?php } ?>
    <?php } ?>
    <?php if (!empty($number) && mysqli_num_rows($resultNumber) > 0) { ?>
        <h4 class="page-header">Rezultati iskanja glede na preostale kriterije</h4>
    <?php } ?>
    <?php if (mysqli_num_rows($resultQuery) > 0) { ?>
        <?php while ($part = mysqli_fetch_array($resultQuery)) { ?>
            <?php echo $part["name"]; ?>
        <?php } ?>
    <?php } else { ?>
        <center><h5>Brez uspeha! Ni takšnega dela</h5></center>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>