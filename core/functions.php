<?php

/*
 * DELO Z UPORABNIKOM 
 */

/*
 * Uporabniku spremeni geslo
 *
 * @param string, string, int, string
 * @retrun bool
 */

function changePassword($password, $salt, $user) {
    $password = passwordHash($password);
    //Hashaj sol+geslo
    $password = loginHash($salt, $password);
    if (Db::update("users", array("password" => $password), "WHERE id = $user;")) {
        return true;
    } else {
        return false;
    }
}

/*
 * Preveri, če ima uporabnik vse zahtevane podatke
 * 
 * @param int, string
 * @return bool
 */

function checkUser($id) {
    $user = Db::queryOne("SELECT * FROM users WHERE id = ?", $id);
    if (!empty($user["name"]) && !empty($user["surname"]) && !empty($user["phone"]) && !empty($user["location"]) && !empty($user["city_id"]) && !empty($user["email"])) {
        return true;
    } else {
        return false;
    }
}

/*
 * Vrne koliko izdelkov ima uporabnik v košarici
 * 
 * @param int, string
 * @return int
 */

function countItems($id) {
    return Db::query("SELECT * FROM shop WHERE user_id = ?", $id);
}

function mailHash($mail) {
    return md5($mail);
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

function user_log($ip, $url, $agent, $user = '') {
    Db::insert("logs", array("IP" => $ip, "page" => $url, "date" => date("Y-m-d H:i:s"), "agent" => $agent, "user_id" => $user));
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
    fwrite(fopen("user_logs.txt", 'a'), "$query&?$ip&?$agent&?" . date("Y-m-d H:i:s") . "&?$user\n");
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

function getParent($id, $table = '') {
    $row = Db::queryOne("SELECT * FROM categories WHERE id = ?", $id);
    $table[] = $row;
    if ($row["category_id"] == '0') {
        $table = array_reverse($table);
        foreach ($table as $vrsta) {
            $result2 = Db::queryAll("SELECT * FROM categories WHERE category_id = ?", $vrsta["id"]);
            if (Db::query("SELECT * FROM categories WHERE category_id = ?", $vrsta["id"]) > 0) {
                echo "
                <div class=\"col-md-6 col-xs-12\">
                    <div class=\"input-group\">
                    <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-tags\"></i></span>
                    <select name=\"category\" class=\"form-control\">";
                echo "<option selected='selected'></option>";
                foreach ($result2 as $row2) {
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
        getParent($row["category_id"], $table);
    }
}

/*
 * Dobi prvega/najvišjega starša kategorije
 * 
 * @param int, string
 * @return int
 */

function firstParent($id) {
    $row = Db::queryOne("SELECT * FROM categories WHERE id = ?", $id);
    if ($row["category_id"] == 0) {
        return $row["id"];
    } else {
        return firstParent($row["id"]);
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
 * @param string, string, int, float, int, int, string, string, int, int
 * @retrun bool
 */

function addPart($name, $desc, $category, $price, $types, $user, $number, $image, $pieces, $new) {
    if (Db::insert("parts", array("name" => $name, "description" => $desc, "category_id" => $category, "price" => $price, "type_id" => $types, "user_id" => $user, "number" => $number, "image" => $image, "pieces" => $pieces, "new" => $new, "created" => date("Y-m-d H:i:s"), "edited" => date("Y-m-d H:i:s"))) == 1) {
        return true;
    } else {
        return false;
    }
}

/*
 * V bazi popravi del
 * @param int, string, string, int, float, int, string, int, int, string, int, int
 * @return bool
 */

function editPart($id, $name, $desc, $category, $price, $types, $number, $image, $pieces, $new) {
    if (Db::update("parts", array("name" => $name, "description" => $desc, "category_id" => $category, "price" => $price, "type_id" => $types, "number" => $number, "edited" => date("Y-m-d H:i:s"), "image" => $image, "pieces" => $pieces, "new" => $new), "WHERE id = $id") == 1) {
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

function getModels($id) {
    $result = Db::queryAll("SELECT id FROM models WHERE brand_id = ?", $id);
    foreach ($result AS $row) {
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
    $price = preg_replace("[\,]", ".", $price); //zamenja "," s "."
    if (strpos($price, '.') !== FALSE) {
        $new_price = explode(".", $price);
        if (strlen($new_price[1]) == 1) {
            $price .= "0";
        }
        if (strlen($new_price[1]) > 2) {
            $price = $new_price[0] . "." . substr($new_price[1], 0, 2);
        }
    }
    if (strpos($price, '.') === FALSE) {
        $price .= ".00";
    }
    $price = preg_replace("[\.]", ",", $price);
    $new = explode(",", $price);
    $first = strrev(implode(".", str_split(strrev($new[0]), 3)));
    $price = $first.",".$new[1];
    return $price;
}

/*
 * Sprejme ID kategorije nato pa vse skupaj shrani v tabelo
 * 
 * @param int, string, array
 * @return array
 */

function categoryParents($id, $table) {
    $cat = Db::queryOne("SELECT id, name, category_id FROM categories WHERE id = ?", $id);
    $table[] = $cat;
    if ($cat["category_id"] == 0) {
        $table = array_reverse($table);
        $cn = count($table);
        $m = 0;
        foreach ($table AS $category) {
            if ($m === $cn) {
                echo "<li><a href='../result/category/" . $category["id"] . "'>" . $category["name"] . "</a></li>";
            } else {
                echo "<li><a href='../result/category/" . $category["id"] . "'>" . $category["name"] . "</a></li>";
            }
            $m ++;
        }
    } else {
        $table = categoryParents($cat["category_id"], $table);
    }
}

/*
 * Pogleda, če je to moj del
 * @param int, int, string 
 * @return bool
 */

function my_part($part, $user) {
    if (Db::query("SELECT * FROM parts WHERE id = ? AND user_id = ?", $part, $user) == 1) {
        return true;
    } else {
        return false;
    }
}

/*
 * Pogleda če je del že izbrisan 
 * @param int, string
 * @return bool
 */

function part_deleted($part) {
    if (Db::query("SELECT * FROM parts WHERE id = ? AND deleted = 0", $part) != 1) {
        return true;
    } else {
        return false;
    }
}

/*
 * Vnese kaj si uporabnik ogleduje
 * @param int[opcijsko], int[opcijsko], int[opcijsko], int[opcijsko], int[opcijsko]
 * @return bool
 */

function interest($part = "", $category = "", $user = "", $model = "", $brand = "") {
    $ip = $_SERVER["REMOTE_ADDR"];
    if (Db::insert("interests", array("part_id" => $part, "category_id" => $category, "user_id" => $user, "model_id" => $model, "brand_id" => $brand, "ip" => $ip, "visited" => date("Y-m-d H:i:s"))) == 1) {
        return true;
    } else {
        return false;
    }
}

/*
 * Naredi tabelo glede na uporabnikove interese
 * @params string, string, int[opcijsko]
 * @return array
 */

function likes($ip, $user = "") {
    if (empty($user)) {
        $where = "ip = '$ip'";
    } else {
        $where = "user_id = $user";
    }
    $category = Db::queryOne("SELECT COUNT(*), category_id FROM interests WHERE $where AND category_id != 0 GROUP BY category_id ORDER BY COUNT(*) DESC LIMIT 1");
    $model = Db::queryOne("SELECT COUNT(*), model_id FROM interests WHERE $where AND model_id != 0 GROUP BY category_id ORDER BY COUNT(*) DESC LIMIT 1");
    $brand = Db::queryOne("SELECT COUNT(*), brand_id FROM interests WHERE $where AND brand_id != 0 GROUP BY category_id ORDER BY COUNT(*) DESC LIMIT 1");
    if (!empty($brand["brand_id"])) {
        $likes["brand"] = array("count" => $brand["COUNT(*)"], "id" => $brand["brand_id"]);
    }
    if (!empty($model["model_id"])) {
        $likes["model"] = array("count" => $model["COUNT(*)"], "id" => $model["model_id"]);
    }
    if (!empty($category["category_id"])) {
        $likes["category"] = array("count" => $category["COUNT(*)"], "id" => $category["category_id"]);
    }
    return $likes;
}

/*
 * Uredi tabelo
 * @params array, string[key], SORT_ORDER
 * @return array
 */

function array_sort($array, $on, $order = SORT_ASC) {
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

/*
 * Vrne ceno delov v košarici
 * @param int
 * @return string
 */

function calcPrice($user) {
    $offers = Db::queryAll("SELECT *, s.pieces AS spieces FROM shop s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = ?", $user);
    $total = 0;
    foreach ($offers as $offer) {
        $total = $total + $offer["spieces"] * $offer["price"];
    }
    return $total;
}
