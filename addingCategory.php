<?php
include_once './core/functions.php';
include_once './core/session.php';
include_once './core/database.php';
if($_POST){
    $name = cleanString($_POST["name"]);
    $category = (int)$_POST["id"];
    $location = (int)$_POST["location"];
    if(!empty($name) && is_numeric($category)){
        if(insertCategory($name, $category, $location, $link) == true){
            $_SESSION["notify"] = "success|Kategorija uspešno dodana!";
            header("Location: addCategory.php");
        } else {
            echo "Napaka podatkovne baze!";
        }
    } else {
        echo "Napaka podatkov!";
    }
}
?>
