<?php
ob_start();
include_once './core/session.php';
if ($_POST) {
    $name = cleanString($_POST["name"]);
    $description = smartFilter($_POST["description"]);
    $category = (int) cleanString($_POST["category"]);
    $user = $_SESSION["user_id"];
    $price = cleanString($_POST["price"]);
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
    if (!empty($_POST["pieces"])) {
        $pieces = (int) $_POST["pieces"];
    } else {
        $pieces = 1;
    }
    if ((int) $_POST["new"] == 1) {
        $new = 1;
    } else {
        $new = 0;
    }
    $types = (int) $_POST["types"]; //Tip: coupe, Karavan, ...
    $location = (int) $_POST["location"];
    $number = cleanString($_POST["number"]);
    //Ohranjanje podatkov ob neuspehu
    $_SESSION["query"]["price"] = price($price);
    $_SESSION["query"]["number"] = $number;
    $_SESSION["query"]["types"] = $types;
    $_SESSION["query"]["name"] = $name;
    $_SESSION["query"]["pieces"] = $pieces;
    $_SESSION["query"]["description"] = $description;
    $_SESSION["query"]["category"] = $category;
    $_SESSION["query"]["type"] = $_POST["type"];
    $_SESSION["query"]["years"] = $_POST["letnik"];
    $_SESSION["query"]["models"] = $_POST["model"];
    $_SESSION["query"]["location"] = $location;
    $_SESSION["query"]["new"] = $new;
    $loc_req = Db::querySingle("SELECT location FROM categories WHERE id = ?", $_SESSION["query"]["first"]);
    if (empty($_FILES["image"]["tmp_name"]) && !empty($_SESSION["query"]["image"]) && !empty($_SESSION["user_id"])) {
        $image = $_SESSION["query"]["image"];
    }
    if (!empty($_FILES["image"]["tmp_name"]) && ($_FILES["image"]["type"] == "image/png" || $_FILES["image"]["type"] == "image/jpg" || $_FILES["image"]["type"] == "image/gif" || $_FILES["image"]["type"] == "image/jpeg") && !empty($_SESSION["user_id"])) {
        $image = $_FILES["image"]["tmp_name"];
        $url = 'http://imageshack.us/upload_api.php';
        $max_file_size = '5242880';
        $temp = $image;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $post = array(
            "fileupload" => '@' . $temp,
            "key" => "36FGIMNR9a9bcde6689ccf6f7468bb7e54692fab",
            "album" => "carparts",
            "format" => 'json',
            "max_file_size" => $max_file_size,
            "Content-type" => "multipart/form-data",
            "public" => "no",
            "tags" => $user
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        $img = json_decode($response, true);
        $image = $img["links"]["image_link"];
    }
    $_SESSION["query"]["image"] = $image;
    if (match_price($price)) {
        if (!empty($name) && !empty($types) && !empty($image) && !empty($_SESSION["user_id"]) && ($loc_req > 0 && !empty($location) || $loc_req == 0)) {
            if (addPart($name, $description, $category, $price, $types, $user, $number, $image, $pieces, $new, $location) == 1) {
                $last_id = Db::getLastId();
                $st = 0;
                //Avtomobili na del
                foreach ($_POST["model"] as $model) {
                    Db::query("INSERT INTO models_parts (model_id, type, year, part_id, old) VALUES (?, ?, ?, ?,0)", $model, $_POST["type"][$st], $_POST["letnik"][$st], $last_id);
                    $st++;
                }
                //Galerija slik
                $st = 0;
                foreach ($_FILES["gallery"]["tmp_name"] as $img) {
                    $tmp_name = $_FILES["gallery"]["tmp_name"][$st];
                    $ext = end(explode('.', $_FILES["gallery"]["name"][$st]));
                    $new_name = "uploads/" . $id . "_slika_" . $st . "." . $ext;
                    if ($_FILES["gallery"]["type"][$st] == "image/png" || $_FILES["gallery"]["type"][$st] == "image/jpg" || $_FILES["gallery"]["type"][$st] == "image/gif" || $_FILES["gallery"]["type"][$st] == "image/jpeg") {
                        if (Db::insert("images", array("link" => $new_name, "part_id" => $last_id)) == 1) {
                            move_uploaded_file($tmp_name, $new_name);
                        }
                    }
                    $st++;
                }
                unset($_SESSION["query"]);
                $_SESSION["notify"] = "success|Del uspešno dodan!";
                header("Location: part/$last_id");
            } else {
                $_SESSION["error"] = 1;
                header("Location: addPart.php");
            }
        } else {
            $_SESSION["error"] = 3;
            header("Location: addPart.php");
        }
    } else {
        $_SESSION["error"] = 2;
        header("Location: addPart.php");
    }
}
?>