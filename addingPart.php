<?php
include_once './core/database.php';
include_once './core/session.php';
include_once './core/functions.php';
if ($_POST) {
    $name = cleanString($_POST["name"]);
    $description = cleanString($_POST["description"]);
    $category = (int) cleanString($_POST["category"]);
    $user = $_SESSION["user_id"];
    if (!empty($name) && !empty($description) && !empty(is_numeric($category))) {
        if (match_number($_POST["price"])) {
            addPart($name, $description, $category, $user);
        } else {
            echo "Neujemanje cene!";
        }
    } else {
        echo "Napaka podatkov";
    }
}
?>