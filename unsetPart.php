<?php

include_once './core/session.php';
$_GET["method"]();

function add() {
    unset($_SESSION["query"]);
}

function edit() {
    unset($_SESSION["query_update"]);
}