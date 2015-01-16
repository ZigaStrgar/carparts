<?php

include_once './core/database.php';
include_once './core/session.php';
include_once './core/functions.php';
if ($_POST) {
    $name = cleanString($_POST["name"]);
    $description = cleanString($_POST["description"]);
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
    if (!empty((int) $_POST["pieces"])) {
        $pieces = (int) $_POST["pieces"];
    } else {
        $pieces = 1;
    }
    if((int)$_POST["new"] == 1){
        $new = 1;
    } else {
        $new = 0;
    }
    $types = (int) $_POST["types"]; //Tip: coupe, Karavan, ...
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
    $_SESSION["query"]["new"] = $new;
    $_SESSION["query"]["first"] = firstParent($category, $link);
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
        if (!empty($name) && !empty($types) && !empty($image) && !empty($_SESSION["user_id"])) {
            if (addPart($name, $description, $category, $price, $types, $user, $number, $image, $pieces, $new, $link)) {
                $selectPart = "SELECT max(id) FROM parts WHERE user_id = $user LIMIT 1";
                $resultPart = mysqli_query($link, $selectPart);
                $part = mysqli_fetch_array($resultPart);
                $last_id = $part["max(id)"];
                $st = 0;
                //Avtomobili na del
                foreach ($_POST["model"] as $model) {
                    $query = sprintf("INSERT INTO models_parts(model_id, type, year, part_id) VALUES ($model, '%s', " . cleanString($_POST["letnik"][$st]) . ", $last_id)", mysqli_real_escape_string($link, $_POST["type"][$st]));
                    mysqli_query($link, $query);
                    file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
                    $st++;
                }
                //Galerija slik
                $st = 0;
                foreach ($_FILES["gallery"]["tmp_name"] as $img) {
                    $tmp_name = $_FILES["gallery"]["tmp_name"][$st];
                    $ext = end(explode('.', $_FILES["gallery"]["name"][$st]));
                    $new_name = "uploads/" . $last_id . "_slika_" . $st . "." . $ext;
                    if ($_FILES["gallery"]["type"][$st] == "image/png" || $_FILES["gallery"]["type"][$st] == "image/jpg" || $_FILES["gallery"]["type"][$st] == "image/gif" || $_FILES["gallery"]["type"][$st] == "image/jpeg") {
                        $query = "INSERT INTO images(link, part_id) VALUES ('$new_name', $last_id)";
                        if (mysqli_query($link, $query)) {
                            move_uploaded_file($tmp_name, $new_name);
                        }
                    }
                    file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
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