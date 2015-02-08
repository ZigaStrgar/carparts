<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($_POST) {
        $offer = (int) cleanString($_POST["id"]);
        if (!empty($offer)) {
            if (Db::query("DELETE FROM cart WHERE id = ? LIMIT 1", $offer) == 1) {
                echo "success|Del uspešno odstranejen iz košarice!";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
        } else {
            echo "error|Napaka podatkov!";
        }
    } else {
        echo "error|Napaka pošiljanja!";
    }
} else{
    echo "error|Napaka zahtevka!";
}
?>