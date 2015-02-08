<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_SESSION["email"] == "ziga_strgar@hotmail.com") {
    if ($_POST) {
        $id = (int) cleanString($_POST["id"]);
        $status = (int) cleanString($_POST["val"]);
        if (!empty($status) && !empty($id)) {
            if(Db::update("invoices", array("status" => $status), "WHERE id = $id") == 1){
                echo "success|Sprememba uspešna!";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
        } else {
            echo "error|Napaka podatkov!";
        }
    }
}
?>