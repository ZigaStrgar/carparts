<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $oldPassword = $_POST["oldpassword"];
    $newPassword = $_POST["password"];
    $newPassword2 = $_POST["password2"];
    if (!empty($oldPassword) && !empty($newPassword) && !empty($newPassword2)) {
        $data = Db::queryOne("SELECT password, salt, reset_password, reset FROM users WHERE id = ?", $_SESSION["user_id"]);
//Hashaj geslo
        $oldpass = passwordHash($oldPassword);
//Hashaj sol+geslo
        $oldpass = loginHash($data["salt"], $oldpass);
        if ($data["reset"] == 1) {
            if ($oldpass == $data["reset_password"]) {
                if ($newPassword == $newPassword2) {
                    if (changePassword($newPassword, $data["salt"], $_SESSION["user_id"]) == true) {
                        echo "success|Geslo uspešno spremenjeno!";
                    } else {
                        echo "error|Napaka podatkovne baze!";
                    }
                } else {
                    echo "error|Novi gesli se ne ujemata!";
                }
            } else {
                echo "error|Vnesite pravo trenutno geslo!";
            }
        } else {
            if ($oldpass == $data["password"]) {
                if ($newPassword == $newPassword2) {
                    if (changePassword($newPassword, $data["salt"], $_SESSION["user_id"]) == true) {
                        echo "success|Geslo uspešno spremenjeno!";
                    } else {
                        echo "error|Napaka podatkovne baze!";
                    }
                } else {
                    echo "error|Novi gesli se ne ujemata!";
                }
            } else {
                echo "error|Vnesite pravo trenutno geslo!";
            }
        }
    } else {
        echo "error|Napaka podatkov!";
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}