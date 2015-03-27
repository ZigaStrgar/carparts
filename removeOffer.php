<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($user["id"])) {
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
} else {
    $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Napaka zahtevka!|3000";
    header("Location: index.php");
}
?>