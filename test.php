<?php
error_reporting(E_ALL);
$id = 1;
include_once './core/db.php';
include_once './core/database.php';
echo $id;
$person = Db::query('SELECT * FROM types WHERE id=?', $id);
print_r($person);