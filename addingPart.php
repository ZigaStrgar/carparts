<?php
include_once './core/database.php';
include_once './core/session.php';
include_once './core/functions.php';
if ($_POST) {
    $name = cleanString($_POST["name"]);
    $description = cleanString($_POST["description"]);
    $category = (int) cleanString($_POST["category"]);
    $user = $_SESSION["user_id"];
    $price = $_POST["price"];
    $price = preg_replace("[,]", ".", $price); //zamenja "," s "."
    $brand = (int)$_POST["brand"];
    $model = (int)$_POST["model"];
    $year = (int)$_POST["year"];
    $type = (int)$_POST["type"]; //Tip: Micra, 318, ...
    $types = (int)$_POST["types"]; //Tip: coupe, Karavan, ...
    if (!empty($name) && !empty($category) && !empty($brand) && !empty($model) && !empty($year) && !empty($type) && !empty($types)) {
        if (match_number($price)) {
            if(addPart($name, $description, $category, $price, $brand, $model, $year, $type, $types, $user, $link)){
                echo "success";
            } else {
                echo "Napaka podatkovne baze!";
            }
        } else {
            echo "Napačen format cene!";
        }
    } else {
        echo "Napaka podatkov";
    }
}
?>