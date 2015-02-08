<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $name = cleanString($_POST["name"]);
    $surname = cleanString($_POST["surname"]);
    $email = cleanString($_POST["email"]);
    $phone = cleanString($_POST["telephone"]);
    $location = cleanString($_POST["location"]);
    $city = cleanString($_POST["city"]);
    $user = Db::queryOne("SELECT * FROM users WHERE id = ?", $_SESSION["user_id"]);
    if (empty($name)) {
        $name = $user["name"];
    }
    if (empty($surname)) {
        $surname = $user["surname"];
    }
    if (empty($email)) {
        $email = $user["email"];
    }
    if (empty($phone)) {
        $phone = $user["phone"];
    }
    if (empty($location)) {
        $location = $user["location"];
    }
    if (empty($city)) {
        $city = $user["city_id"];
    }
    $phone = substr(preg_replace("[\ ]", "", $phone), 4, 9);
    $phone = preg_replace("[\-]", "", $phone);
    if (!empty($name) && !empty($surname) && !empty($phone) && !empty($email) && !empty($location) && !empty($city)) {
        if (checkEmail($email)) {
            if (Db::update("users", array("name" => $name, "surname" => $surname, "email" => $email, "phone" => $phone, "location" => $location, "city_id" => $city), "WHERE id = " . $_SESSION["user_id"]) == 1 || Db::update("users", array("name" => $name, "surname" => $surname, "email" => $email, "phone" => $phone, "location" => $location, "city_id" => $city), "WHERE id = " . $_SESSION["user_id"]) == 0) {
                $user = Db::queryOne("SELECT * FROM users WHERE id = ?", $_SESSION["user_id"]);
                if (!empty($user["location"]) && !empty($user["city_id"]) && !empty($user["phone"]) && !empty($user["name"]) && !empty($user["surname"]) && !empty($user["email"])) {
                    $updateUser = "UPDATE users SET first_login = 1 WHERE id = " . $_SESSION["user_id"];
                    if (Db::update("users", array("first_login" => 1), "WHERE id = " . $_SESSION["user_id"]) == 0) {
                        echo "success|Podatki uspešno spremenjeni!";
                    } else {
                        echo "error|Napaka podatkovne baze!";
                    }
                } else {
                    echo 'success|Napaka podatkov!';
                }
            } else {
                echo "error|Napaka podatkovne baze2!";
            }
        } else {
            echo "error|Napaka pri e-poštnem naslovu!";
        }
    } else {
        echo "error|Napaka podatkov!";
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}
?>