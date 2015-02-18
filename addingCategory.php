<?php

include_once './core/session.php';
if ($_POST) {
    if ($user["email"] == "ziga_strgar@hotmail.com") {
        $name = cleanString($_POST["name"]);
        $category = (int) $_POST["cat"];
        $location = (int) $_POST["location"];
        if (!empty($name) && is_numeric($category)) {
            if (Db::insert("categories", array("name" => $name, "category_id" => $category, "location" => $location)) == 1) {
                $_SESSION["notify"] = "success|Kategorija uspešno dodana!";
                header("Location: addCategory.php");
            } else {
                $_SESSION["notify"] = "error|Napaka podatkovne baze!";
                header("Location: addCategory.php");
            }
        } else {
            $_SESSION["notify"] = "error|Napaka podatkov!";
            header("Location: addCategory.php");
        }
    } else {
        $_SESSION["notify"] = "error|Hmm, ne bo šlo takole :)";
        header("Location: index.php");
    }
}
?>
