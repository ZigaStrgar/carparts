<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($user["id"])) {
    if ($_POST) {
        $offer = (int) cleanString($_POST["offer"]);
        $user = $_SESSION["user_id"];
        $value = (int) cleanString($_POST["value"]);
        $current = Db::querySingle("SELECT pieces FROM cart WHERE id = ? AND user_id = ?", $offer, $user);
        $pieces = Db::querySingle("SELECT pieces FROM parts WHERE id = (SELECT part_id FROM cart WHERE id = ?)", $offer);
        if (!empty($value) && !empty($offer)) {
            if ($value <= $pieces) {
                if ($current != $value) {
                    if (Db::update("cart", array("pieces" => $value), "WHERE id = $offer AND user_id = $user LIMIT 1") == 1) {
                        echo "success|Sprememba uspešna!";
                    } else if (Db::update("cart", array("pieces" => $value), "WHERE id = $offer AND user_id = $user LIMIT 1") == 0) {
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
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}
?>