<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($_POST) {
        $offer = (int) cleanString($_POST["id"]);
        $user = $_SESSION["id"];
        if (!empty($offer)) {
            $queryDelete = "DELETE FROM shop WHERE id = $offer LIMIT 1";
            if (mysqli_query($link, $queryDelete)) {
                echo "success|Del uspešno odstranejen iz košarice!";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
        } else {
            echo "error|Napaka podatkov!";
        }
    }
}
?>