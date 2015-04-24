<?php

include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($user["id"])) {
    if (Db::query("DELETE FROM interests WHERE user_id = ?", $user["id"]) == 0) {
        echo "success|Interesi uspešno pobrisani!";
    } else {
        echo "error|Interesov ni bilo mogoče izbrisati!";
    }
}