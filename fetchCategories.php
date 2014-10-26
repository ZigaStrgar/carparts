<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
$id = (int) cleanString($_POST["id"]);
if(!empty(is_numeric($id))){
    getParent($id);
}