<?php
include_once './core/session.php';
$id = (int) cleanString($_GET["part"]);
if (my_part($id, $_SESSION["user_id"])) {
    if (Db::update("parts", array("deleted" => 1), "WHERE id = $id LIMIT 1")) {
        $_SESSION["notify"] = "success|Del uspešno izbrisan!";
        header("Location: ../parts.php");
    } else {
        $_SESSION["notify"] = "error|Brisanje dela neuspešno!";
        header("Location: ../part/$id");
    }
} else {
    $_SESSION["notify"] = "error|Ooops, to pa ni vaš del!";
    header("Location: ../part/$id");
}