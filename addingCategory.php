<?php
include_once './core/functions.php';
include_once './core/session.php';
include_once './core/database.php';
if($_POST){
    $name = cleanString($_POST["name"]);
    $category = (int)$_POST["id"];
    if(!empty($name) && is_numeric($category)){
        if(insertCategory($name, $category) == true){
            echo "success";
        } else {
            echo "Napaka podatkovne baze!";
        }
    } else {
        echo "Napaka podatkov!";
    }
}
?>
