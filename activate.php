<?php
include_once './core/functions.php';
include_once './core/session.php';
include_once './core/database.php';
if (strpos("localhost", $_SERVER["HTTP_HOST"]) !== FALSE) {
    define("URL", $_SERVER["HTTP_HOST"] . "/carparts");
} else {
    define("URL", $_SERVER["HTTP_HOST"]);
}
$hash = cleanString($_GET["hash"]);
$queryActivate = sprintf("UPDATE users SET active = 1 WHERE active_hash = '%s'",  mysqli_real_escape_string($link, $hash));
if (mysqli_query($link, $queryActivate)) {
    $_SESSION["notify"] = "success|Aktivacija računa uspešna!";
    header("Location: http://".URL."/login.php");
} else {
    $_SESSION["notify"] = "error|Aktivacija računa neuspešna!";
    header("Location: http://".URL."/index.php");
}