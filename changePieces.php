<?php
include_once './core/session.php';
include_once './core/db.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($_POST) {
        $offer = (int) cleanString($_POST["offer"]);
        $user = $_SESSION["user_id"];
        $value = (int) cleanString($_POST["value"]);
        $current = Db::querySingle("SELECT pieces FROM shop WHERE id = ? AND user_id = ?", $offer, $user);
        $pieces = Db::querySingle("SELECT pieces FROM parts WHERE id = (SELECT part_id FROM shop WHERE id = ?)", $offer);
        if (!empty($value) && !empty($offer)) {
            if ($value <= $pieces) {
                if ($current != $value) {
                    if (Db::update("shop", array("pieces" => $value), "WHERE id = $offer AND user_id = $user LIMIT 1") == 1) {
                        echo "success|Sprememba uspešna!";
                    } else if (Db::update("shop", array("pieces" => $value), "WHERE id = $offer AND user_id = $user LIMIT 1") == 0) {
                        echo "error|Na zalogi samo: " . $pieces;
                    } else {
                        echo "error|Napaka podatkovne baze!";
                    }
                }
            } else {
                echo "error|Na zalogi samo: " . $pieces;
            }
        } else {
            echo "error|Napaka podatkov!";
        }
    }
}
?>