<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $id = (int) cleanString($_POST["id"]);
    if (!empty($id)) {
        getParent($id, $link);
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}