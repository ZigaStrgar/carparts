<?php

include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
$oldPassword = $_POST["oldpassword"];
$newPassword = $_POST["password"];
$newPassword2 = $_POST["password2"];
$queryUser = "SELECT password, salt FROM users WHERE id = " . $_SESSION["user_id"];
$resultUser = mysqli_query($link, $queryUser);
$user = mysqli_fetch_array($resultUser);
//Hashaj geslo
$oldpass = passwordHash($oldPassword);
//Hashaj sol+geslo
$oldpass = loginHash($user["salt"], $oldpass);
if ($oldpass == $user["password"]) {
    if ($newPassword == $newPassword2) {
        $password = passwordHash($newPassword);
//Hashaj sol+geslo
        $password = loginHash($salt, $password);
        $updatePassword = "UPDATE users SET password = '$password' WHERE id = ".$_SESSION["user_id"];
        if(mysqli_query($link, $updatePassword)){
            echo "success|Geslo uspešno spremenjeno!";
        } else {
            echo "Napaka podatkovne baze!";
        }
    } else {
        echo "Novi gesli se ne ujemata!";
    }
} else {
    echo "Vnesite pravo trenutno geslo!";
}