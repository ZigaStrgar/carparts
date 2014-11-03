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
    if (!empty($name) && !empty($description) && !empty(is_numeric($category))) {
        if (match_number($price)) {
            if(addPart($name, $description, $category, $user)){
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