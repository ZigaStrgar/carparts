<?php
$hash = cleanString($_GET["hash"]);
Db::update("users", array("reset" => 0), "WHERE active_hash = '$hash'");
if(Db::query("SELECT reset FROM users WHERE reset = 0 AND active_hash = ?", hash) == 1){
    $_SESSION["alert"] = "alert alert-success alert-fixed-bottom|Zamenjava uspešno preklicana. Lahko uporabite staro geslo!|3000";
    header("Location: http://".URL."/login.php");
} else {
    $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Preklic novega gesla neuspešen!|3000";
    header("Location: http://".URL."/index.php");
}