<?php

include_once './core/database.php';
include_once './core/session.php';
include_once './core/functions.php';
if ($_POST) {
    $name = cleanString($_POST["name"]);
    $description = cleanString($_POST["description"]);
    $category = (int) cleanString($_POST["cat"]);
    $user = $_SESSION["user_id"];
    $price = $_POST["price"];
    $price = preg_replace("[\.]", ",", $price); //zamenja "." s ","
    if (strpos($price, ',') === FALSE) {
        $price .= ",00";
    }
    $brand = (int) $_POST["brand"];
    $model = (int) $_POST["model"];
    $year = (int) $_POST["letnik"];
    $type = $_POST["type"]; //Tip: Micra, 318, ...
    $types = (int) $_POST["types"]; //Tip: coupe, Karavan, ...
    $number = $_POST["number"];
    if (match_price($price)) {
        if (!empty($_FILES["image"]["tmp_name"]) && ($_FILES["image"]["type"] == "image/png" || $_FILES["image"]["type"] == "image/jpg" || $_FILES["image"]["type"] == "image/gif" || $_FILES["image"]["type"] == "image/jpeg")) {
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
        if (!empty($name) && !empty($model) && !empty($year) && !empty($types) && !empty($image)) {
            if (addPart($name, $description, $category, $price, $model, $year, $type, $types, $user, $number, $image, $link)) {
                header("Location: parts.php");
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