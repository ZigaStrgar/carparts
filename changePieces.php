<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($_POST) {
        $offer = (int) cleanString($_POST["offer"]);
        $user = $_SESSION["user_id"];
        $value = (int) cleanString($_POST["value"]);
        if (!empty($value) && !empty($offer)) {
            $queryUpdate = "UPDATE shop SET pieces = $value WHERE id = $offer AND user_id = $user LIMIT 1";
            if (mysqli_query($link, $queryUpdate)) {
                echo "success|Sprememba uspešna!";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
        } else {
            echo "error|Napaka podatkov!";
        }
    }
}
?>