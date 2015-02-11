<?php
include_once './core/session.php';
$hash = cleanString($_GET["hash"]);
Db::update("users", array("active" => 1), "WHERE active_hash = '$hash'");
if (Db::query("SELECT active FROM users WHERE active = 1 AND active_hash = ?", $hash) == 1) {
    $_SESSION["notify"] = "success|Aktivacija računa uspešna!";
    header("Location: http://" . URL . "/login.php");
} else {
    $_SESSION["notify"] = "error|Aktivacija računa neuspešna!";
    header("Location: http://" . URL . "/index.php");
}