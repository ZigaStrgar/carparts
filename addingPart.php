<?php

include_once './core/database.php';
include_once './core/session.php';
include_once './core/functions.php';
if ($_POST) {
    $name = cleanString($_POST["name"]);
    $description = cleanString($_POST["description"]);
    $category = (int) cleanString($_POST["category"]);
    $user = $_SESSION["user_id"];
    $price = $_POST["price"];
    $price = preg_replace("[\,]", ".", $price); //zamenja "," s "."
    if (strpos($price, '.') === TRUE) {
        $new_price = explode(".", $price);
        if(strlen($new_price[1]) == 1){
            $price .= "0";
        }
        if(strlen($new_price[1]) > 2){
            $price = $new_price[0].".".substr($new_price[1], 0, 2);
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
        if (!empty($name) && !empty($types) && !empty($image)) {
            if (addPart($name, $description, $category, $price, $types, $user, $number, $image, $pieces, $link)) {
                $selectPart = "SELECT max(id) FROM parts WHERE user_id = $user LIMIT 1";
                $resultPart = mysqli_query($link, $selectPart);
                $part = mysqli_fetch_array($resultPart);
                $last_id = $part["max(id)"];
                $st = 0;
                //Avtomobili na del
                foreach ($_POST["model"] as $model) {
                    $query = sprintf("INSERT INTO models_parts(model_id, type, year, part_id) VALUES ($model, '%s', " . $_POST["letnik"][$st] . ", $last_id)", mysqli_real_escape_string($link, $_POST["type"][$st]));
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