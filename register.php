<?php
include_once './core/functions.php';
include_once './core/session.php';
include_once './core/database.php';
if($_POST){
    $name = cleanString($_POST["name"]);
    $surname = cleanString($_POST["surname"]);
    $email = cleanString($_POST["email"]);
    $pass = cleanString($_POST["password"]);
    $pass2 = cleanString($_POST["password2"]);
    //Pregled, če so vsi podatki zapolnjeni
    if(!empty($name) && !empty($surname) && !empty($email) && !empty($pass) && !empty($pass2)){
        //Ujemanje gesl
        if($pass == $pass2){
            //Filter za e-poštne naslove
            if(check_email($email)){
                //Zgeneriraj sol
                $salt = createSalt();
                //Hashaj geslo
                $password = passwordHash($password);
                //Hashaj sol+geslo
                $password = loginHash($salt, $password);
                register($name, $surname, $email, $password, $salt);
            } else {
                echo "Napaka v e-poštnem naslovu";
            }
        } else {
            echo "Gesli se ne ujemata!";
        }
    } else {
        echo "Napaka podatkov!";
    }
}