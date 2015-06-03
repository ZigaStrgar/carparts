<?php

ob_start();
include_once './core/session.php';
if ($_POST) {
    $name = cleanString($_POST["name"]);
    if(!empty($_POST["description"])){
        $description = smartFilter($_POST["description"]);
    } else {
        $description = smartFilter($_POST["name"]);
    }
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
    if (!empty($_POST["types"])) {
        $types = (int) $_POST["types"]; //Tip: coupe, Karavan, ...
    } else {
        $types = 0;
    }
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
        if (!empty($name) && !empty($image) && !empty($_SESSION["user_id"]) && ($loc_req > 0 && !empty($location) || $loc_req == 0)) {
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
                    if ($_FILES["gallery"]["type"][$st] == "image/png" || $_FILES["gallery"]["type"][$st] == "image/jpg" || $_FILES["gallery"]["type"][$st] == "image/gif" || $_FILES["gallery"]["type"][$st] == "image/jpeg") {
                        $tmp_name = $_FILES["gallery"]["tmp_name"][$st];
                        $key = "xoDACQnwq9TOztQN5CUzEHE4qp7uhEEb";
                        $input = $tmp_name;
                        $ext = end(explode('.', $_FILES["gallery"]["name"][$st]));
                        $output = "uploads/" . $last_id . "_slika_" . $st . "." . $ext;

                        $request = curl_init();
                        curl_setopt_array($request, array(
                            CURLOPT_URL => "https://api.tinypng.com/shrink",
                            CURLOPT_USERPWD => "api:" . $key,
                            CURLOPT_POSTFIELDS => file_get_contents($input),
                            CURLOPT_BINARYTRANSFER => true,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_HEADER => true,
                            CURLOPT_SSL_VERIFYPEER => true
                        ));

                        $response = curl_exec($request);
                        if (curl_getinfo($request, CURLINFO_HTTP_CODE) === 201) {
                            $headers = substr($response, 0, curl_getinfo($request, CURLINFO_HEADER_SIZE));
                            foreach (explode("\r\n", $headers) as $header) {
                                if (substr($header, 0, 10) === "Location: ") {
                                    $request = curl_init();
                                    curl_setopt_array($request, array(
                                        CURLOPT_URL => substr($header, 10),
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_SSL_VERIFYPEER => true
                                    ));
                                    file_put_contents($output, curl_exec($request));
                                    if (Db::insert("images", array("link" => $output, "part_id" => $last_id)) == 1) {
                                        
                                    } else {
                                        $neeew = $st + 1;
                                        $_SESSION["alert"] = "alert alert-danger alert-fixed-bootm|Napaka pri nalaganju slike: " . $neeew . "|2500";
                                    }
                                }
                            }
                        } else {
                            echo "error|" . curl_error($request);
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
