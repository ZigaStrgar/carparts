<?php

include_once './core/database.php';
include_once './core/session.php';
include_once './core/functions.php';
if ($_POST) {
    $id = (int) cleanString($_GET["id"]);
    $query = "SELECT image FROM parts WHERE id = $id";
    $result = mysqli_query($link, $query);
    $part = mysqli_fetch_array($result);
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
    if ((int) $_POST["new"] == 1) {
        $new = 1;
    } else {
        $new = 0;
    }
    $types = (int) $_POST["types"]; //Tip: coupe, Karavan, ...
    $number = cleanString($_POST["number"]);
    //Ohranitev podatkov ob morebitnem neuspešnem vnosu
    $_SESSION["query_update"]["price"] = price($price);
    $_SESSION["query_update"]["number"] = $number;
    $_SESSION["query_update"]["types"] = $types;
    $_SESSION["query_update"]["name"] = $name;
    $_SESSION["query_update"]["pieces"] = $pieces;
    $_SESSION["query_update"]["description"] = $description;
    $_SESSION["query_update"]["category"] = $category;
    $_SESSION["query_update"]["type"] = $_POST["type"];
    $_SESSION["query_update"]["years"] = $_POST["letnik"];
    $_SESSION["query_update"]["models"] = $_POST["model"];
    $_SESSION["query_update"]["new"] = $new;
    $_SESSION["query_update"]["first"] = firstParent($category, $link);
    if (empty($_FILES["image"]["tmp_name"]) && empty($_SESSION["query_update"]["image"])) {
        $image = $part["image"];
    }
    if (empty($_FILES["image"]["tmp_name"]) && !empty($_SESSION["query_update"]["image"])) {
        $image = $_SESSION["query_update"]["image"];
    }
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
    $_SESSION["query_update"]["image"] = $image;
    if (match_price($price)) {
        if (!empty($name) && !empty($types) && !empty($image)) {
            if (editPart($id, $name, $description, $category, $price, $types, $number, $image, $pieces, $new, $link)) {
                $queryDeleteModels = "DELETE FROM models_parts WHERE part_id = $id";
                if (mysqli_query($link, $queryDeleteModels)) {
                    $st = 0;
                    //Avtomobili na del
                    foreach ($_POST["model"] as $model) {
                        $query = sprintf("INSERT INTO models_parts(model_id, type, year, part_id) VALUES ($model, '%s', " . cleanString($_POST["letnik"][$st]) . ", $id)", mysqli_real_escape_string($link, $_POST["type"][$st]));
                        mysqli_query($link, $query);
                        file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
                        $st++;
                    }
                    //Galerija slik
                    $queryDeleteImages = "DELETE FROM images WHERE part_id = $id";
                    mysqli_query($link, $queryDeleteImages);
                    $st = 0;
                    foreach ($_FILES["gallery"]["tmp_name"] as $img) {
                        $tmp_name = $_FILES["gallery"]["tmp_name"][$st];
                        $ext = end(explode('.', $_FILES["gallery"]["name"][$st]));
                        $new_name = "uploads/" . $id . "_slika_" . $st . "." . $ext;
                        if ($_FILES["gallery"]["type"][$st] == "image/png" || $_FILES["gallery"]["type"][$st] == "image/jpg" || $_FILES["gallery"]["type"][$st] == "image/gif" || $_FILES["gallery"]["type"][$st] == "image/jpeg") {
                            $query = "INSERT INTO images(link, part_id) VALUES ('$new_name', $last_id)";
                            if (mysqli_query($link, $query)) {
                                move_uploaded_file($tmp_name, $new_name);
                            }
                        }
                        file_logs($query, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
                        $st++;
                    }
                    unset($_SESSION["query_update"]);
                    $_SESSION["notif"] = "success|Del uspešno urejen!";
                    header("Location: part/$id");
                }
            } else {
                $_SESSION["error"] = 1;
                header("Location: editPart/$id");
            }
        } else {
            $_SESSION["error"] = 3;
            header("Location: editPart/$id");
        }
    } else {
        $_SESSION["error"] = 2;
        header("Location: editPart/$id");
    }
}
?>