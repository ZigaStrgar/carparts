<?php
include_once './core/session.php';
$email = cleanString($_POST["email"]);
$pass = cleanString($_POST["password"]);
//Pregled, če so vsi podatki izpolnjeni
if (!empty($email) && !empty($pass)) {
    //Filter za e-poštne naslove
    if (checkEmail($email)) {
        $salt = Db::querySingle("SELECT salt FROM users WHERE email = ?", $email);
        //Hashaj geslo
        $password = passwordHash($pass);
        //Hashaj sol+geslo
        $password = loginHash($salt, $password);
        if (Db::query("SELECT * FROM users WHERE email = ? AND password = ? AND first_login = 1 AND active = 1", $email, $password) == 1) {
            $user = Db::queryOne("SELECT * FROM users WHERE email = ? AND password = ?", $email, $password);
            session_start();
            $_SESSION["email"] = $user["email"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["surname"] = $user["surname"];
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["org"] = $user["org"];
            $_SESSION["logged"] = 1;
            echo "redirect|index.php";
        } else if (Db::query("SELECT * FROM users WHERE email = ? AND password = ? AND first_login = 0 AND active = 1") == 1) {
            $user = Db::queryOne("SELECT * FROM users WHERE email = ? AND password = ?", $email, $password);
            session_start();
            $_SESSION["email"] = $user["email"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["surname"] = $user["surname"];
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["org"] = $user["org"];
            $_SESSION["logged"] = 1;
            echo "redirect|editProfile.php";
        } else if (Db::query("SELECT * FROM users WHERE email = ? AND password = ? AND active = 0") == 1) {
            echo "error|Ta račun ni aktiven!";
        } else {
            echo "error|Uporabnik s takšnim e-poštnim naslovom in geslom ne obstaja!";
        }
    } else {
        echo "error|Napaka v e-poštnem naslovu";
    }
} else {
    echo "error|Napaka podatkov!";
}