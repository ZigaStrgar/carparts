<!DOCTYPE html>
<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Carparts</title>
        <!--  jQuery  -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!--  Google Web fonts  -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <!--  Core JS file  -->
        <script src="./core/scripts.js" type="text/javascript"></script>
        <!--  ICONS  -->
        <link href="./css/carparts-font.css" rel="stylesheet" type="text/css" />
        <!--  BOOTSTRAP  -->
        <link href="./css/bootstrap-theme.css" rel="stylesheet" type="text/css" />
        <link href="./css/bootstrap.css" rel="stylesheet" type="text/css" />
        <script src="./js/bootstrap.js" type="text/javascript"></script>
    </head>
    <body>
        <header>
            <div class="navbar navbar-inverse navbar-fixed-top" style="border-radius: 0px;">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="login.php"><i class="icon icon-contact"></i>&nbsp;Prijava</a>&nbsp;&nbsp;&nbsp;
                            <a href="registration.php"><i class="icon icon-contact-add-2"></i>&nbsp;Registracija</a>
                            <div class="pull-right">
                                <i class="icon icon-search"></i>&nbsp;<span id="fastSearch" contenteditable="true">Hitro iskanje</span>
                            </div>
                        </div>
                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" style="cursor: default;">AVTO DELI</a>
                    </div>
                    <div class="navbar-collapse collapse" style="font-size: 18px">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="">Domov</a></li>
                            <li><a href="">Deli</a></li>
                            <li><a href="">Išči</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </header>
        <div class="container" style="margin-top: 80px;">
            <div class="row">
                <div class="col-lg-12">