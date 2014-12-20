<?php

include_once './core/functions.php';
include_once './core/session.php';
include_once './core/database.php';
$email = cleanString($_POST["email"]);
$pass = cleanString($_POST["password"]);
//Pregled, če so vsi podatki izpolnjeni
if (!empty($email) && !empty($pass)) {
    //Filter za e-poštne naslove
    if (checkEmail($email)) {
        $query = sprintf("SELECT salt FROM users WHERE email = '%s'", mysqli_real_escape_string($link, $email));
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        //Hashaj geslo
        $password = passwordHash($pass);
        //Hashaj sol+geslo
        $password = loginHash($row["salt"], $password);
        if (login($email, $password, $link) == 1) {
            echo "redirect|index.php";
        } else if (login($email, $password, $link) == 2) {
            echo "redirect|editProfile.php";
        } else {
            echo "error|Uporabnik s takšnim e-poštnim naslovom in geslom ne obstaja!";
        }
    } else {
        echo "error|Napaka v e-poštnem naslovu";
    }
} else {
    echo "error|Napaka podatkov!";
}