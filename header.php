<!DOCTYPE html>
<?php
error_reporting(0);
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
        <link href="./css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="./js/bootstrap.js" type="text/javascript"></script>
        <script src="./js/jasny-bootstrap.min.js" type="text/javascript"></script>
        <!--  ALERTIFY PLUGIN  -->
        <link rel="stylesheet" type="text/css" href="./css/alertify.core.css" />
        <link rel="stylesheet" type="text/css" href="./css/alertify.default.css" />
        <script src="./js/alertify.min.js" type="text/javascript"></script>
        <!--  AUTOCOMPLETE SELECT  -->
        <script src="./plugins/autocomplete/jquery.js"></script>
        <script src="./plugins/autocomplete/jq.select-to-autocomplete.js"></script>
        <script src="./plugins/autocomplete/jq-ui-autocomplete.js"></script>
    </head>
    <body>
        <header>
            <div class="navbar navbar-inverse navbar-fixed-top" style="border-radius: 0px;">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php if (!empty($_SESSION["user_id"])) { ?>
                                Pozdravljen, <?php echo $_SESSION["name"] . " " . $_SESSION["surname"]; ?>
                                <a href="editProfile.php"><i class="icon icon-contact-2"></i>&nbsp;Uredi profil</a>
                                <a href="logout.php"><i class="icon icon-logout"></i>&nbsp;Odjava</a>&nbsp;&nbsp;&nbsp; 
                            <?php } else { ?>
                                <a href="login.php"><i class="icon icon-contact"></i>&nbsp;Prijava</a>&nbsp;&nbsp;&nbsp;
                                <a href="registration.php"><i class="icon icon-contact-add-2"></i>&nbsp;Registracija</a>
                            <?php } ?>
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
                        <a class="navbar-brand" href="index.php" style="cursor: default; font-weight: 900;"><span class="color-info">AVTO</span> DELI</a>
                    </div>
                    <div class="navbar-collapse collapse" style="font-size: 18px">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="index.php">Domov</a></li>
                            <li><a href="parts.php">Deli</a></li>
                            <li><a href="search.php">Išči</a></li>
                            <?php if ((!empty($_SESSION["user_id"]) && $_SESSION["logged"] = 1 && $_SESSION["org"] == 1) || $_SESSION["email"] == "ziga_strgar@hotmail.com") { ?>
                                <li><a href="addPart.php">Dodaj del</a></li>
                            <?php } ?>
                            <?php if (!empty($_SESSION["user_id"]) && $_SESSION["email"] == "ziga_strgar@hotmail.com") { ?>
                                <li><a href="addCategory.php">Dodaj kategorijo</a></li>
                            <?php } ?>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </header>
        <div class="container" style="margin-top: 80px;">
            <div class="row">
                <div class="col-lg-12">