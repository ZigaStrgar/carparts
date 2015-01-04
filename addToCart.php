<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if($_POST){
        $part = (int)cleanString($_POST["part"]);
        $user = $_SESSION["user_id"];
        $querySelect = "SELECT * FROM shop WHERE part_id = $part AND user_id = $user";
        $resultSelect = mysqli_query($link, $querySelect);
        if(mysqli_num_rows($resultSelect) == 1){
            $queryPart = "UPDATE shop SET pieces = pieces + 1 WHERE part_id = $part AND user_id = $user LIMIT 1;";
            if(mysqli_query($link, $queryPart)){
                echo "success|Del uspešno dodan v košarico";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
            file_logs($queryPart, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
        } else {
            $queryPart = "INSERT INTO shop (part_id, user_id) VALUES ($part, $user)";
            if(mysqli_query($link, $queryPart)){
                echo "success|Del uspešno dodan v košarico";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
            file_logs($queryPart, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
        }
    }
}
?>