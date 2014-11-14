<?php
include_once './core/functions.php';
include_once './core/session.php';
include_once './core/database.php';
$name = cleanString($_POST["name"]);
$surname = cleanString($_POST["surname"]);
$email = cleanString($_POST["email"]);
$pass = cleanString($_POST["password"]);
$pass2 = cleanString($_POST["password2"]);
//Pregled, če so vsi podatki zapolnjeni
if (!empty($name) && !empty($surname) && !empty($email) && !empty($pass) && !empty($pass2)) {
    //Ujemanje gesl
    if ($pass == $pass2) {
        //Filter za e-poštne naslove
        if (checkEmail($email)) {
            //Zgeneriraj sol
            $salt = createSalt();
            //Hashaj geslo
            $password = passwordHash($pass);
            //Hashaj sol+geslo
            $password = loginHash($salt, $password);
            if (register($name, $surname, $email, $password, $salt, $link) == 1) {
                echo "redirect|login.php";
            } else if (register($name, $surname, $email, $password, $salt, $link) == 2) {
                echo "error|Uporabnik s tem e-poštnim naslovom že obstaja!";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
        } else {
            echo "error|Napaka v e-poštnem naslovu";
        }
    } else {
        echo "error|Gesli se ne ujemata!";
    }
} else {
    echo "error|Napaka podatkov!";
}