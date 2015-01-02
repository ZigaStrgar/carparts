<?php

include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $oldPassword = $_POST["oldpassword"];
    $newPassword = $_POST["password"];
    $newPassword2 = $_POST["password2"];
    if (!empty($oldPassword) && !empty($newPassword) && !empty($newPassword2)) {
        $queryUser = "SELECT password, salt FROM users WHERE id = " . $_SESSION["user_id"];
        $resultUser = mysqli_query($link, $queryUser);
        $user = mysqli_fetch_array($resultUser);
//Hashaj geslo
        $oldpass = passwordHash($oldPassword);
//Hashaj sol+geslo
        $oldpass = loginHash($user["salt"], $oldpass);
        if ($oldpass == $user["password"]) {
            if ($newPassword == $newPassword2) {
                if (changePassword($newPassword, $user["salt"], $_SESSION["user_id"], $link) == true) {
                    echo "success|Geslo uspešno spremenjeno!";
                } else {
                    echo "error|Napaka podatkovne baze!";
                }
            } else {
                echo "error|Novi gesli se ne ujemata!";
            }
        } else {
            echo "error|Vnesite pravo trenutno geslo!";
        }
    } else {
        echo "error|Napaka podatkov!";
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}