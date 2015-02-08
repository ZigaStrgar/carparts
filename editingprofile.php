<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $name = cleanString($_POST["name"]);
    $surname = cleanString($_POST["surname"]);
    $email = cleanString($_POST["email"]);
    $phone = cleanString($_POST["telephone"]);
    $location = cleanString($_POST["location"]);
    $city = cleanString($_POST["city"]);
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
            Db::query("UPDATE users SET name = ?, suranme = ?, phone = ?, location = ?, city_id = ? WHERE id = ?", $name, $surname, $phone, $location, $city, $_SESSION["user_id"]);
            if (Db::query("SELECT email FROM users WHERE name = ? AND surname = ? AND phone = ? AND location = ? AND city_id = ? AND id = ?", $name, $surname, $phone, $location, $city, $_SESSION["user_id"]) == 1) {
                $data = Db::queryOne("SELECT * FROM users WHERE id = ?", $_SESSION["user_id"]);
                if (!empty($data["location"]) && !empty($data["city_id"]) && !empty($data["phone"]) && !empty($data["name"]) && !empty($data["surname"]) && !empty($data["email"])) {
                    Db::query("UPDATE users SET first_login = 1 WHERE id = ?", $_SESSION["user_id"]);
                    if (Db::query("SELECT first_login FROM users WHERE first_login = 1 AND id = ?", $_SESSION["user_id"]) == 1) {
                        echo "success|Vsi podatki uspešno spremenjeni!";
                    } else {
                        echo "error|Napaka podatkovne baze!!";
                    }
                } else {
                    echo "success|Podatki uspešno spremenjeni ampak nekateri še vedno manjkajo!";
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
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}
?>