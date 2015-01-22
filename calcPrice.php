<?php
include_once './core/session.php';
include_once './core/db.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION["user_id"])) {
    $offers = Db::queryAll("SELECT *, s.pieces AS spieces FROM cart s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = ?", $_SESSION["user_id"]);
    $total = 0;
    foreach($offers as $offer) {
        $total = $total + $offer["spieces"] * $offer["price"]; 
    }
    echo price($total);
}
?>