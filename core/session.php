<?php
session_start();
error_reporting(0);
if (strpos("localhost", $_SERVER["HTTP_HOST"]) !== FALSE) {
    define("URL", $_SERVER["HTTP_HOST"] . "/carparts");
} else {
    define("URL", $_SERVER["HTTP_HOST"]);
}
include_once './core/database.php';
include_once './core/functions.php';
if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){
    $user = user((int) $_SESSION["user_id"]);
}
?>