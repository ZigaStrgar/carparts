<?php
include_once './core/functions.php';
include_once './core/session.php';
include_once './core/database.php';
$id = (int) cleanString($_GET["part"]);
if (my_part($id, $_SESSION["user_id"], $link)) {
    $queryDelete = "UPDATE parts SET deleted = 1 WHERE id = $id LIMIT 1";
    if (mysqli_query($link, $queryDelete)) {
        $_SESSION["notify"] = "success|Del uspešno izbrisan!";
        header("Location: ../parts.php");
    } else {
        $_SESSION["notify"] = "error|Brisanje dela neuspešno!";
        header("Location: ../part/$id");
    }
} else {
    header("Location: ../404.php");
}