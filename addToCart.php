<?php
include_once './core/session.php';
include_once './core/db.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if($_POST){
        $part = (int)cleanString($_POST["part"]);
        $user = $_SESSION["user_id"];
        if(Db::query("SELECT * FROM shop WHERE part_id = ? AND user_id = ?", $part, $user) == 1){
            //DEL JE ŽE V KOŠARICI
            if(Db::query("UPDATE shop SET pieces = pieces + 1 WHERE part_id = ? AND user_id = ? LIMIT 1", $part, $user) == 1){
                echo "success|Del uspešno dodan v košarico|".countItems($_SESSION["user_id"], $link);
            } else {
                echo "error|Napaka podatkovne baze2!";
            }
        } else {
            //DEL ŠE NI V KOŠARICI
            if(Db::insert("shop", array("part_id" => $part, "user_id" => $user)) == 1){
                echo "success|Del uspešno dodan v košarico|".countItems($_SESSION["user_id"], $link);
            } else {
                echo "error|Napaka podatkovne baze!";
            }
        }
    }
}
?>