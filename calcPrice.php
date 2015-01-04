<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $queryOffers = "SELECT *, s.pieces AS spieces FROM shop s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = ". $_SESSION["user_id"];
    $resultOffers = mysqli_query($link, $queryOffers);
    $total = 0;
    while($offer = mysqli_fetch_array($resultOffers)) {
        $total = $total + $offer["spieces"] * $offer["price"]; 
    }
    echo price($total);
}
?>