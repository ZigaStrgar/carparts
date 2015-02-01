<?php

include_once './core/functions.php';
include_once './core/session.php';
include_once './core/db.php';
include_once './core/database.php';
if (strpos("localhost", $_SERVER["HTTP_HOST"]) !== FALSE) {
    define("URL", $_SERVER["HTTP_HOST"] . "/carparts");
} else {
    define("URL", $_SERVER["HTTP_HOST"]);
}
$email = $_POST["email"];
if (checkEmail($email)) {
    if (Db::query("SELECT * FROM users WHERE email = ?", $email) == 1) {
        $salt = Db::querySingle("SELECT salt FROM users WHERE email = ?", $email);
        $rand = randomPassword();
        $password = loginHash($salt, passwordHash($rand));
        if (Db::query("UPDATE users SET reset_password = ? AND reset = 1 WHERE email = ?", $password, $email) == 1) {
            $_SESSION["alert"] = "alert alert-success alert-fixed-bottom|Vaše novo geslo je bilo poslano na vaš e-naslov";
            header("Location: http://" . URL . "/login.php");
        } else {
            $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Napaka podatkovne baze";
            header("Location: http://" . URL . "/resetPassword.php");
        }
    } else {
        $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Uporabnika s takšnim e-naslovom ne obstaja!";
        header("Location: http://" . URL . "/resetPassword.php");
    }
} else {
    $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Napaka e-naslova!";
    header("Location: http://" . URL . "/resetPassword.php");
}