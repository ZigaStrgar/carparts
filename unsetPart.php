<?php

include_once './core/session.php';
$_GET["method"]();

//Pobriše predpomnilnik, ki se ustvari ob napaki pri dodajanju dela
function add() {
    unset($_SESSION["query"]);
}

//Pobriše predpomnilnik, ki se ustvari ob napaki pri urejanju dela
function edit() {
    unset($_SESSION["query_update"]);
}