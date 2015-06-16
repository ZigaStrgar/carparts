<?php

/*
 * UPORABNIK
 */

/**
 * Uporabniški podatki
 *
 * @param $id
 *
 * @return mixed
 */
function user($id) {
    return Db::queryOne("SELECT id, email, location, phone, name, surname FROM users WHERE id = ?", $id);
}

/**
 * Preveri, če ima uporabnik vse zahtevane podatke
 *
 * @param $id
 *
 * @return bool
 */
function checkUser($id) {
    $user = Db::queryOne("SELECT * FROM users WHERE id = ?", $id);
    return ! empty($user["name"]) && ! empty($user["surname"]) && ! empty($user["phone"]) && ! empty($user["location"]) && ! empty($user["city_id"]) && ! empty($user["email"]) ? true : false;
}

/**
 * Uporabniku spremeni geslo
 *
 * @param $password
 * @param $salt
 * @param $user
 *
 * @return bool
 */
function changePassword($password, $salt, $user) {
    //Hashaj sol+geslo
    $password = loginHash($salt, passwordHash($password));
    Db::query("UPDATE users SET password = ?, reset = 0 WHERE id = ?", $password, $user);
    return Db::query("SELECT * FROM users WHERE password = ? AND id = ?", $password, $user) == 1 ? true : false;
}

/**
 * Ustvari nov predračun za uporabnika
 *
 * @param $user
 *
 * @return mixed
 */
function createInvoice($user) {
    $cart = Db::queryAll("SELECT *, c.pieces AS spieces, c.id AS cartnum, p.id AS pid FROM cart c INNER JOIN parts p ON p.id = c.part_id WHERE c.user_id = ?", $user);
    Db::insert("invoices", array("status" => 0, "order_date" => date("Y-m-d H:i:s"), "user_id" => $user, "due_date" => date("Y-m-d", strtotime("+14 day", strtotime(date("Y-m-d"))))));
    $max = Db::getLastId();
    foreach ($cart as $offer) {
        Db::insert("parts_invoices", array("price" => $offer["price"], "pieces" => $offer["spieces"], "part_id" => $offer["pid"], "invoice_id" => $max));
    }
    return $max;
}

/**
 * Vnese kaj si uporabnik ogleduje
 *
 * @param string $part
 * @param string $category
 * @param string $user
 * @param string $model
 * @param string $brand
 *
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


/**
 * Naredi tabelo glede na uporabnikove interese
 *
 * @param        $ip
 * @param string $user
 *
 * @return mixed
 */
function likes($ip, $user = "") {
    if (empty($user)) {
        $where = "ip = '$ip'";
    } else {
        $where = "user_id = $user";
    }
    $category = Db::queryOne("SELECT COUNT(*), category_id FROM interests WHERE $where AND category_id != 0 GROUP BY category_id ORDER BY COUNT(*) DESC LIMIT 1");
    $model = Db::queryOne("SELECT COUNT(*), model_id FROM interests WHERE $where AND model_id != 0 GROUP BY model_id ORDER BY COUNT(*) DESC LIMIT 1");
    $brand = Db::queryOne("SELECT COUNT(*), brand_id FROM interests WHERE $where AND brand_id != 0 GROUP BY brand_id ORDER BY COUNT(*) DESC LIMIT 1");
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

/**
 * Nerešeni predračuni
 *
 * @return mixed
 */
function unsolvedInvoices(){
    return Db::query("SELECT * FROM invoices WHERE status = 1");
}


/*
 * DELI 
 */

/**
 * V bazo vstavi nov del
 *
 * @param $name
 * @param $desc
 * @param $category
 * @param $price
 * @param $types
 * @param $user
 * @param $number
 * @param $image
 * @param $pieces
 * @param $new
 * @param $location
 *
 * @return bool
 */
function addPart($name, $desc, $category, $price, $types, $user, $number, $image, $pieces, $new, $location) {
    if (Db::insert("parts", array("name" => $name, "description" => $desc, "category_id" => $category, "price" => $price, "type_id" => $types, "user_id" => $user, "number" => $number, "image" => $image, "pieces" => $pieces, "new" => $new, "created" => date("Y-m-d H:i:s"), "location" => $location, "edited" => date("Y-m-d H:i:s"))) == 1) {
        return true;
    } else {
        return false;
    }
}

/**
 * V bazi popravi del
 *
 * @param $id
 * @param $name
 * @param $desc
 * @param $category
 * @param $price
 * @param $types
 * @param $number
 * @param $image
 * @param $pieces
 * @param $new
 * @param $location
 *
 * @return bool
 */
function editPart($id, $name, $desc, $category, $price, $types, $number, $image, $pieces, $new, $location) {
    if (Db::update("parts", array("name" => $name, "description" => $desc, "category_id" => $category, "price" => $price, "type_id" => $types, "number" => $number, "location" => $location, "edited" => date("Y-m-d H:i:s"), "image" => $image, "pieces" => $pieces, "new" => $new), "WHERE id = $id") == 1) {
        return true;
    } else {
        return false;
    }
}

/*
 * VARNOST
 */

/**
 * Pridobi string (ceno) in pregleda če je pravilno sestavljen
 *
 * @param $number
 *
 * @return bool
 */
function match_price($number) {
    return preg_match('/^(?:0|[1-9]\d*)(?:\.\d{1,2})?$/', trim($number)) ? true : false;
}

/**
 * Pogleda, če je to moj del
 *
 * @param $part
 * @param $user
 *
 * @return bool
 */
function my_part($part, $user) {
    return Db::query("SELECT * FROM parts WHERE id = ? AND user_id = ?", $part, $user) == 1 ? true : false;
}

/**
 * Pogleda če je to moj zahtevek v košarici
 *
 * @param $offer
 * @param $user
 *
 * @return bool
 */
function my_offer($offer, $user) {
    return Db::query("SELECT * FROM cart c INNER JOIN user u ON c.user_id = u.id WHERE u.id = ? AND c.id = ?", $user, $offer) == 1 ? true : false;
}

/**
 * Pogleda, če je to moj predračun
 *
 * @param $invoice
 * @param $user
 *
 * @return bool
 */
function my_invoice($invoice, $user) {
    return Db::query("SELECT * FROM invoices WHERE id = ? AND user_id = ?", $invoice, $user) == 1 ? true : false;
}

/**
 * Pogleda, če obstaja predračun
 *
 * @param $invoice
 *
 * @return bool
 */
function invoice_exist($invoice){
    return Db::query("SELECT * FROM invoices WHERE id = ?", $invoice) == 1 ? true : false;
}

/**
 * Pogleda če je del že izbrisan
 *
 * @param $part
 *
 * @return bool
 */
function part_deleted($part) {
    return Db::query("SELECT * FROM parts WHERE id = ? AND deleted = 0", $part) != 1 ? true : false;
}

/**
 * Vrne hashan mail za aktivacijo računa
 *
 * @param $mail
 *
 * @return string
 */
function mailHash($mail) {
    return md5($mail);
}

/**
 * Hasha besedilo, uporabno predvsem za gesla
 *
 * @param $password
 *
 * @return string
 */
function passwordHash($password) {
    return hash('sha256', $password);
}

/**
 * Ustvari za vsakega uporabnika "sol", ki služi dodatni varnosti na podlagi katere se potem hash-a geslo
 *
 * @return string
 */
function createSalt() {
    return substr(md5(uniqid(rand(), true)), 0, 4);
}

/**
 * Geslu doda v naprej zgenerirano "sol" in hasha z sha256
 *
 * @param $salt
 * @param $hash
 *
 * @return string
 */
function loginHash($salt, $hash) {
    return hash('sha256', $salt . $hash);
}

/**
 * Preveri string, če vsebuje v naprej določene varne znake
 *
 * @param $string
 *
 * @return mixed
 */
function cleanString($string) {
    return strip_tags(preg_replace('/[^a-zA-Z0-9ČĆŽŠĐčćžđš@!:;?=\'\,()*\/_|+\.-] /', '', $string));
}

/**
 * Očisti string (smart filter)
 *
 * @param $string
 *
 * @return string
 */
function smartFilter($string) {
    return strip_tags(stripAttributes($string), "<p><li><ol><ul><h1><h2><h3><h4><h5><h6><span><b><u><i>");
}

/**
 * Izbriše vse dodatne parametre (prepreči XSS)
 *
 * @param $html
 *
 * @return string
 */
function stripAttributes($html) {
    $dom = new DOMDocument;
    $contentPrefix = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
    $contentSuffix = '</body></html>';

    $dom->loadHTML($contentPrefix . $html . $contentSuffix);

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('//@*');
    foreach ($nodes as $node) {
        $node->parentNode->removeAttribute($node->nodeName);
    }
    return $dom->saveHTML();
}

/**
 * Preveri string(email), če je veljaven in ne vsebuje nedovoljenih znakov
 *
 * @param $email
 *
 * @return bool
 */
function checkEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
}

/**
 * Zapiše v tabelo s katerega IP-ja dostopa uporabnik na katero stran.
 *
 * @param        $ip
 * @param        $url
 * @param        $agent
 * @param string $user
 */
function user_log($ip, $url, $agent, $user = '') {
    Db::query("INSERT INTO logs (ip, page, agent, user_id) VALUES (?, ?, ?, ?);", $ip, $url, $agent, $user);
}

/**
 * Zapiše v datoteko query, ki se naj izvede v bazi.
 *
 * @param $query
 */
function file_logs($query) {
    if (!file_exists("user_logs.txt")) {
        $ourFileHandle = fopen("user_logs.txt", 'w') or die("can't open file");
        fclose($ourFileHandle);
    }
    $fp = fopen('user_logs.txt', 'a');
    if ($fp) {
        fwrite($fp, "$query&?" . $_SERVER["REMOTE_ADDR"] . "&?" . $_SERVER["HTTP_USER_AGENT"] . "&?" . date("Y-m-d H:i:s") . "&?" . $_SESSION["user_id"] . "\n");
        fclose($fp);
    }
}

/*
 * TRGOVINA
 */

/**
 * Vrne ceno delov v košarici
 *
 * @param $user
 *
 * @return int
 */
function calcPrice($user) {
    $offers = Db::queryAll("SELECT *, s.pieces AS spieces FROM cart s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = ?", $user);
    $total = 0;
    foreach ($offers as $offer) {
        $total = $total + $offer["spieces"] * $offer["price"];
    }
    return $total;
}

/**
 * Vrne koliko izdelkov ima uporabnik v košarici
 *
 * @param $id
 *
 * @return mixed
 */
function countItems($id) {
    return Db::query("SELECT * FROM cart WHERE user_id = ?", $id);
}

/*
 * KATEGORIJE
 */


/**
 * Izpisuje dropdown menuje z podkategorijami izbrane kategorije
 *
 * @param        $id
 * @param string $table
 *
 * @echo  Dropdowns
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

/**
 * Dobi prvega/najvišjega starša kategorije
 *
 * @param $id
 *
 * @return mixed
 */
function firstParent($id) {
    $row = Db::queryOne("SELECT * FROM categories WHERE id = ?", $id);
    if ($row["category_id"] == 0) {
        return $row["id"];
    } else {
        return firstParent($row["category_id"]);
    }
}

/**
 * Dobi vse podkategorije dane kategorije
 *
 * @param        $category
 * @param string $array
 *
 * @return array|string
 */
function getSubcategories($category, $array = '') {
    $subs = Db::queryAll("SELECT id FROM categories WHERE category_id = ?", $category);
    foreach ($subs as $sub) {
        $array[] = $sub["id"];
        $array = getSubcategories($sub["id"], $array);
    }
    return $array;
}

/*
 * UPORABNE FUNKCIJE
 */

/**
 * Uredi tabelo
 *
 * @param     $array
 * @param     $on
 * @param int $order
 *
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

/**
 * Sprejme ID kategorije nato pa vse skupaj shrani v tabelo
 *
 * @param       $id
 * @param array $table
 */
function categoryParents($id, $table=array()) {
    $cat = Db::queryOne("SELECT id, name, category_id FROM categories WHERE id = ?", $id);
    $table[] = $cat;
    if ($cat["category_id"] == 0) {
        $table = array_reverse($table);
        foreach ($table AS $category) {
            echo "<li><a href='../result/category/" . $category["id"] . "'>" . $category["name"] . "</a></li>";
        }
    } else {
        $table = categoryParents($cat["category_id"], $table);
    }
}

/**
 * Vrne vse ID-je modelov te znamke
 *
 * @param $id
 *
 * @return string
 */
function getModels($id) {
    $result = Db::queryAll("SELECT id FROM models WHERE brand_id = ?", $id);
    foreach ($result AS $row) {
        $str .= $row["id"] . ",";
    }
    return substr($str, 0, strlen($str) - 1);
}

/**
 * Vrne ID znamke
 *
 * @param $id
 *
 * @return mixed
 */
function getBrand($id) {
    return Db::querySingle("SELECT brand_id FROM models WHERE id = ?", $id);
}

/**
 * Vrne ceno, le da zamenja . z , in vstavi . na primerna mesta ter doda decimalke
 *
 * @param $price
 *
 * @return mixed|string
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
    $price = $first . "," . $new[1];
    return $price;
}

/**
 * Generira novo geslo (8 mestno)
 *
 * @return string
 */
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
