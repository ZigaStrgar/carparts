<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($_POST) {
        $id = (int) cleanString($_POST["id"]);
        $status = (int) cleanString($_POST["val"]);
        if ($user["email"] == "ziga_strgar@hotmail.com" || my_invoice($id, $user["id"])) {
            if($status != 7 && $user["email"] != "ziga_strgar@hotmail.com"){
                echo "error|Napačen status predračuna!";
                die();
            }
            if (!empty($status) && !empty($id)) {
                if (Db::update("invoices", array("status" => $status), "WHERE id = $id") == 1) {
                    echo "success|Sprememba uspešna!";
                } else {
                    echo "error|Napaka podatkovne baze!";
                }
            } else {
                echo "error|Napaka podatkov!";
            }
        } else {
            echo "error|Napaka! Ta predračun ni Vaš!";
        }
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}
?>