<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($_POST) {
        $part = (int) cleanString($_POST["part"]);
        $user = (int) $_SESSION["user_id"];
        if (!empty($user)) {
            if (Db::query("SELECT * FROM cart WHERE part_id = ? AND user_id = ?", $part, $user) == 1) {
                //DEL JE ŽE V KOŠARICI
                //Število kosov
                $max = Db::querySingle("SELECT pieces FROM parts WHERE id = ?", $part);
                $current = Db::querySingle("SELECT pieces FROM cart WHERE part_id = ? AND user_id = ?", $part, $user);
                if ($current + 1 <= $max) {
                    if (Db::query("UPDATE cart SET pieces = pieces + 1 WHERE part_id = ? AND user_id = ? LIMIT 1", $part, $user) == 1) {
                        echo "success|Del uspešno dodan v košarico|" . countItems($_SESSION["user_id"], $link);
                    } else {
                        echo "error|Napaka podatkovne baze!";
                    }
                } else {
                    echo "error|Maksimalno število kosov že v košarici!";
                }
            } else {
                //DEL ŠE NI V KOŠARICI
                if (Db::insert("cart", array("part_id" => $part, "user_id" => $user)) == 1) {
                    echo "success|Del uspešno dodan v košarico|" . countItems($_SESSION["user_id"], $link);
                } else {
                    echo "error|Napaka podatkovne baze!";
                }
            }
        } else {
            echo "error|Napaka! Niste prijavljeni!";
        }
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}
?>