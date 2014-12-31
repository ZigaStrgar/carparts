<?php

include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
$name = cleanString($_POST["name"]);
$surname = cleanString($_POST["surname"]);
$email = cleanString($_POST["email"]);
$phone = cleanString($_POST["telephone"]);
$location = cleanString($_POST["location"]);
$city = cleanString($_POST["city"]);
$queryUser = "SELECT * FROM users WHERE id = " . $_SESSION["user_id"];
$resultUser = mysqli_query($link, $queryUser);
$user = mysqli_fetch_array($resultUser);
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
$phone = substr(preg_replace("[\ ]", "", $phone), 4,9);
$phone = preg_replace("[\-]", "", $phone);
if (!empty($name) && !empty($surname) && !empty($phone) && !empty($email) && !empty($location) && !empty($city)) {
    if (checkEmail($email)) {
        $updateUser = sprintf("UPDATE users SET name = '%s', surname = '%s', email = '%s', phone = '$phone', location = '%s', city_id = '$city' WHERE id = " . $_SESSION["user_id"], mysqli_real_escape_string($link, $name), mysqli_real_escape_string($link, $surname), mysqli_real_escape_string($link, $email), mysqli_real_escape_string($link, $location));
        if (mysqli_query($link, $updateUser)) {
            $queryUser = "SELECT * FROM users WHERE id = " . $_SESSION["user_id"];
            $resultUser = mysqli_query($link, $queryUser);
            $user = mysqli_fetch_array($resultUser);
            if (!empty($user["location"]) && !empty($user["city_id"]) && !empty($user["phone"]) && !empty($user["name"]) && !empty($user["surname"]) && !empty($user["email"])) {
                $updateUser = "UPDATE users SET first_login = 1 WHERE id = " . $_SESSION["user_id"];
                if (mysqli_query($link, $updateUser)) {
                    echo "success|Podatki uspešno spremenjeni!";
                } else {
                    echo "error|Napaka podatkovne baze!";
                }
            } else {
                echo 'success|Napaka podatkov!';
            }
        } else {
            echo "error|Napaka podatkovne baze!";
        }
    } else {
        echo "error|Napaka pri e-poštnem naslovu!";
    }
} else {
    echo "error|Napaka podatkov!";
}
?>