<?php

include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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
            if (Db::query("SELECT email FROM users WHERE email = ?", $email) == 1) {
                $data = Db::queryOne("SELECT * FROM users WHERE email = ?", $email);
                if ($data["active"] == 1) {
                    if ($data["reset"] == 1) {
                        if (Db::query("SELECT email FROM users WHERE email = ? AND reset_password = ?", $email, $password) == 1) {
                            $_SESSION["user_id"] = $data["id"];
                            $_SESSION["logged"] = 1;
                            if ($data["first_login"] == 0) {
                                echo "redirect|editProfile.php";
                            } else {
                                echo "redirect|index.php";
                            }
                        } else {
                            echo "error|Napačno geslo!";
                        }
                    } else {
                        if (Db::query("SELECT email FROM users WHERE email = ? AND password = ?", $email, $password) == 1) {
                            $_SESSION["user_id"] = $data["id"];
                            $_SESSION["logged"] = 1;
                            if ($data["first_login"] == 0) {
                                echo "redirect|editProfile.php";
                            } else {
                                echo "redirect|index.php";
                            }
                        } else {
                            echo "error|Napačno geslo!";
                        }
                    }
                } else {
                    echo 'error|Račun ni aktiviran!';
                }
            } else {
                echo "error|Uporabnik s tem e-naslovom ne obstaja!";
            }
        } else {
            echo "error|Napaka e-naslova";
        }
    } else {
        echo "error|Napaka podatkov!";
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location: index.php");
}