<?php

/*
 * DELO Z UPORABNIKOM 
 */

/*
 * V bazo vstavi novega uporabnika
 *
 * @param Vsi parametri so tipa string
 * @retrun Ustrezno številko problema oz. uspeha
 */

function register($name, $surname, $email, $password, $salt, $link) {
    $query = sprintf("INSERT INTO users (name, surname, email, password, salt) VALUES ('%s', '%s', '%s', '$password', '$salt')", mysqli_real_escape_string($link, $name), mysqli_real_escape_string($link, $surname), mysqli_real_escape_string($link, $email));
    file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
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
 * @param Vsi parametri so tipa string
 * @retrun bool
 */

function login($email, $password, $link) {
    $query = sprintf("SELECT * FROM users WHERE email = '%s' AND password = '$password'", mysqli_real_escape_string($link, $email));
    file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        session_start();
        $_SESSION["email"] = $row["email"];
        $_SESSION["name"] = $row["name"];
        $_SESSION["surname"] = $row["surname"];
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["org"] = $row["org"];
        $_SESSION["logged"] = 1;
        if ($row["first_login"] == "0") {
            return 2;
        } else {
            return 1;
        }
    } else {
        return 0;
    }
}

/*
 * Uporabniku spremeni geslo
 *
 * @param string, string, int, string
 * @retrun bool
 */

function changePassword($password, $salt, $user, $link) {
    $password = passwordHash($špassword);
    //Hashaj sol+geslo
    $password = loginHash($salt, $password);
    $updatePassword = "UPDATE users SET password = '$password' WHERE id = " . $user;
    if (mysqli_query($link, $updatePassword)) {
        return true;
    } else {
        return false;
    }
}

/*
 * VARNOST
 */

/*
 * Hasha besedilo, uporabno predvsem za gesla
 *
 * @param Parameter je tipa string
 * @return String; Hashano geslo
 */

function passwordHash($password) {
    return hash('sha256', $password);
}

/*
 * Ustvari za vsakega uporabnika "sol", ki služi dodatni varnosti na podlagi katere se potem hash-a geslo
 *
 * @return String; "Sol"
 */

function createSalt() {
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 4);
}

/*
 * Geslu doda v naprej zgenerirano "sol" in hasha z sha256
 *
 * @return String; Geslo, ki se vpisuje v bazo in preverja ob loginu
 */

function loginHash($salt, $hash) {
    return hash('sha256', $salt . $hash);
}

/*
 * Preveri string, če vsebuje v naprej določene varne znake
 *
 * @param String
 * @return String; Očiščen string, odstrani nedovoljene znake
 */

function cleanString($string) {
    return preg_replace('/[^a-zA-Z0-9ČĆŽŠĐčćžđš@!:;?=\'\,()*\/_|+\.-] /', '', $string);
}

/*
 * Preveri string(email), če je veljaven in ne vsebuje nedovoljenih znakov
 *
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

/*
 * Zapiše v tabelo s katerega IP-ja dostopa uporabnik na katero stran.
 *
 * @param String, string, string, string, int[opcijski]
 * @return null
 */

function user_log($ip, $url, $link, $agent, $user = '') {
    $query = "INSERT INTO logs(IP, page, date, agent, user_id) VALUES ('$ip', '$url', NOW(), '$agent', '$user')";
    mysqli_query($link, $query);
}

/*
 * Zapiše v datoteko query, ki se naj izvede v bazi.
 *
 * @param String, string, int[opcijski]
 * @return null
 */

function file_logs($query, $ip, $agent, $user = '') {
    if (!file_exists("user_logs.txt")) {
        $ourFileHandle = fopen("user_logs.txt", 'w') or die("can't open file");
        fclose($ourFileHandle);
    }
    fwrite(fopen("user_logs.txt", 'a'), "$query;$ip;$agent;$user\n");
    fclose("user_logs.txt");
}

/*
 * 
 * KATEGORIJE
 * 
 */

/*
 * Izpisuje dropdown menuje z podkategorijami izbrane kategorije
 *
 * @param int, array[opcijski]
 * @echo Dropdowns
 */

function getParent($id, $link, $table = '') {
    $query = "SELECT * FROM categories WHERE id = $id";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $table[] = $row;
    if ($row["category_id"] == '0') {
        $table = array_reverse($table);
        foreach ($table as $vrsta) {
            $query2 = "SELECT * FROM categories WHERE category_id = " . $vrsta["id"];
            $result2 = mysqli_query($link, $query2);
            if (mysqli_num_rows($result2) > 0) {
                echo "
                <div class=\"col-md-6 col-xs-12\">
                    <div class=\"input-group\">
                    <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-tags\"></i></span>
                    <select name=\"category\" class=\"form-control\">";
                echo "<option selected='selected'></option>";
                while ($row2 = mysqli_fetch_array($result2)) {
                    foreach ($table as $check) {
                        if (in_array($row2["id"], $check)) {
                            $selected = 1;
                        }
                    }
                    echo "<option";
                    if ($selected == 1) {
                        echo " selected='selected' ";
                    } echo " value=\"" . $row2["id"] . "\">" . $row2["name"] . "</option>";
                    $selected = 0;
                }
                echo "</select>
                </div>
                </div>";
            }
        }
    } else {
        getParent($row["category_id"], $link, $table);
    }
}

/*
 * Dobi prvega starša kategorije
 * 
 * @param int, string
 * @return int
 */

function firstParent($id, $link){
    $query = "SELECT * FROM categories WHERE id = $id";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    if($row["category_id"] == 0){
        return $row["id"];
    } else {
        return firstParent($row["id"], $link);
    }
}

/*
 * V bazo vstavi novo kategorijo
 *
 * @param string, int
 * @retrun bool
 */

function insertCategory($name, $id, $link) {
    $query = sprintf("INSERT INTO categories (name, category_id) VALUES ('%s', $id)", mysqli_real_escape_string($link, $name));
    file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
    if (mysqli_query($link, $query)) {
        return true;
    } else {
        return false;
    }
}

/*
 * 
 * DELI
 * 
 */

/*
 * V bazo vstavi nov del
 *
 * @param string, string, int, float, int, int, string, int, int, string, int, int, string
 * @retrun bool
 */

function addPart($name, $desc, $category, $price, $types, $user, $number, $image, $pieces, $new, $link) {
    $query = sprintf("INSERT INTO parts (name, description, category_id, price, type_id, user_id, number, created, edited, image, pieces, new) VALUES ('%s', '%s', $category, $price, $types, $user, '%s', NOW(), NOW(), '$image', '$pieces', $new)", mysqli_real_escape_string($link, $name), mysqli_real_escape_string($link, $desc), mysqli_real_escape_string($link, $number));
    file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $user);
    if (mysqli_query($link, $query)) {
        return true;
    } else {
        return false;
    }
}

/*
 * V bazi popravi del
 * @param int, string, string, int, float, int, string, int, int, string, int, int, string
 * @return true
 */

function editPart($id, $name, $desc, $category, $price, $types, $number, $image, $pieces, $new, $link){
    $query = sprintf("UPDATE parts SET name = '%s', description = '%s', category_id = $category, price = '$price', type_id = $types, number = '%s', edited = NOW(), image = '$image', pieces = $pieces, new = $new WHERE id = $id", mysqli_real_escape_string($link, $name), mysqli_real_escape_string($link, $desc), mysqli_real_escape_string($link, $number));
    file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $user);
    if (mysqli_query($link, $query)) {
        return true;
    } else {
        return false;
    }
}

/*
 * Pridobi string (ceno) in pregleda če je pravilno sestavljen
 *
 * @param string
 * @retrun bool
 */

function match_price($number) {
    $number = trim($number);
    if (preg_match('/^(?:0|[1-9]\d*)(?:\.\d{1,2})?$/', $number)) {
        return true;
    } else {
        return false;
    }
}

/*
 * Vrne vse ID-je modelov te znamke
 * 
 * @param int,
 * @return string
 */

function getModels($id, $link) {
    $query = "SELECT id FROM models WHERE brand_id = $id";
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        $str .= $row["id"] . ",";
    }
    $str = substr($str, 0, strlen($str) - 1);
    return $str;
}

/*
 * Vrne ceno, le da zamenja . z ,
 * 
 * @param string
 * @return string
 */

function price($price) {
    return preg_replace("[\.]", ",", $price);
}

/*
 * Sprejme ID kategorije nato pa vse skupaj shrani v tabelo
 * 
 * @param int, string, array
 * @return array
 */

function categoryParents($id, $link, $table) {
    $queryCat = "SELECT id, name, category_id FROM categories WHERE id = $id";
    $resultCat = mysqli_query($link, $queryCat);
    $cat = mysqli_fetch_array($resultCat);
    $table[] = $cat;
    if ($cat["category_id"] == 0) {
        $table = array_reverse($table);
        $cn = count($table);
        $m = 0;
        foreach ($table AS $category) {
            if ($m === $cn) {
                echo "<li><a href='result/category/".$category["id"]."'>" . $category["name"] . "</a></li>";
            } else {
                echo "<li><a href='result/category/".$category["id"]."'>" . $category["name"] . "</a></li>";
            }
            $m ++;
        }
    } else {
        $table = categoryParents($cat["category_id"], $link, $table);
    }
}
