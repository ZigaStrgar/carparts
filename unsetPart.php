<?php

include_once './core/session.php';
$_GET["method"]();

//Pobriše predpomnilnik, ki se ustvari ob napačnem dodajanju dela
function add() {
    unset($_SESSION["query"]);
}

//Pobriše predpomnilnik, ki se ustvari ob napačnem urejanju dela
function edit() {
    unset($_SESSION["query_update"]);
}