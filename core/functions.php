<?php

/*
 * DELO Z UPORABNIKOM 
 */

/*
 * V bazo vstavi novega uporabnika
 *
 * @access Javen
 * @param Vsi parametri so tipa string
 * @retrun Ustrezno številko problema oz. uspeha
 */

function register($name, $surname, $email, $password, $salt) {
    $link = mysqli_connect('localhost', 'carparts', '', 'carparts');
    mysqli_query($link, "SET NAMES 'utf8'");
    $query = sprintf("INSERT INTO users (name, surname, email, password, salt) VALUES ('%s', '%s', '%s', '$password', '$salt')", mysqli_real_escape_string($link, $name), mysqli_real_escape_string($link, $surname), mysqli_real_escape_string($link, $email));
    if (mysqli_query($link, $query)) {
        return 1;
    } else {
        if (mysqli_errno($link) == 1062) {
            return 2;
        } else {
            return 3;
        }
    }
}

/*
 * Prijavi uporabnika
 *
 * @access Javen
 * @param Vsi parametri so tipa string
 * @retrun bool
 */
function login($email, $password) {
    $link = mysqli_connect('localhost', 'carparts', '', 'carparts');
    mysqli_query($link, "SET NAMES 'utf8'");
    $query = sprintf("SELECT * FROM users WHERE email = '%s' AND password = '$password'", mysqli_real_escape_string($link, $email));
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        session_start();
        $_SESSION["email"] = $row["email"];
        $_SESSION["name"] = $row["name"];
        $_SESSION["surname"] = $row["surname"];
        $_SESSION["user_id"] = $row["id"];
        if($row["first_login"] == "0"){
            return 2;
        } else {
            return 1;
        }
    } else {
        return 0;
    }
}

/*
 * VARNOST
 */

/*
 * Hasha besedilo, uporabno predvsem za gesla
 *
 * @access Javen
 * @param Parameter je tipa string
 * @return String; Hashano geslo
 */

function passwordHash($password) {
    return hash('sha256', $password);
}

/*
 * Ustvari za vsakega uporabnika "sol", ki služi dodatni varnosti na podlagi katere se potem hash-a geslo
 *
 * @access Javen
 * @return String; "Sol"
 */

function createSalt() {
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 4);
}

/*
 * Geslu doda v naprej zgenerirano "sol" in hasha z sha256
 *
 * @access Javen
 * @return String; Geslo, ki se vpisuje v bazo in preverja ob loginu
 */

function loginHash($salt, $hash) {
    return hash('sha256', $salt . $hash);
}

/*
 * Preveri string, če vsebuje v naprej določene varne znake
 *
 * @access Javen
 * @param String
 * @return String; Očiščen string, odstrani nedovoljene znake
 */

function cleanString($string) {
    return preg_replace('/[^a-zA-Z0-9ČĆŽŠĐčćžđš@!:;?=\'()*\/_|+ .-]/', '', $string);
}

/*
 * Preveri string(email), če je veljaven in ne vsebuje nedovoljenih znakov
 *
 * @access Javen
 * @param String
 * @return String; Očiščen string, odstrani nedovoljene znake
 */

function checkEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}
