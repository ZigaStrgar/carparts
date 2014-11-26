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
        <!--  Google Web fonts  -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <!--  ICONS  -->
        <link href="./css/carparts-font.css" rel="stylesheet" type="text/css" />
        <!--  BOOTSTRAP  -->
        <link href="./css/bootstrap-theme.css" rel="stylesheet" type="text/css" />
        <link href="./css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="./css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!--  ALERTIFY PLUGIN  -->
        <link rel="stylesheet" type="text/css" href="./plugins/alertify/alertify.core.css" />
        <link rel="stylesheet" type="text/css" href="./plugins/alertify/alertify.default.css" />
        <!--  jQuery  -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!--  AUTOCOMPLETE SELECT  -->
        <script src="./plugins/autocomplete/jquery.js" type="text/javascript"></script>
        <script src="./plugins/autocomplete/jq.select-to-autocomplete.js" type="text/javascript"></script>
        <script src="./plugins/autocomplete/jq-ui-autocomplete.js" type="text/javascript"></script>
        <!--  JQUERY PRICE SLIDER  -->
        <link href="./plugins/js-slider/jquery.slider.min.css" rel="stylesheet" type="text/css" />
        <script src="./plugins/js-slider/jquery.slider.js" type="text/javascript"></script>
        <script src="./plugins/js-slider/jquery.dependClass-0.1.js" type="text/javascript"></script>
        <script src="./plugins/js-slider/jquery.numberformatter-1.2.3.js" type="text/javascript"></script>
        <script src="./plugins/js-slider/draggable-0.1.js" type="text/javascript"></script>
        <script src="./plugins/js-slider/jshashtable-2.1_src.js" type="text/javascript"></script>
        <script src="./plugins/js-slider/tmpl.js" type="text/javascript"></script>
    </head>
    <body>
        <header>
            <div class="navbar navbar-fixed-top navbar-default">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php" style="cursor: default; font-weight: 900;"><span class="color-info">AVTO</span> DELI</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php"><i class="icon icon-home-1-1"></i> Domov</a></li>
                        <li><a href="parts.php"><i class="icon icon-gears-setting"></i> Deli</a></li>
                        <li><a href="search.php"><i class="icon icon-search-1"></i> Išči</a></li>
                        <?php if ((!empty($_SESSION["user_id"]) && $_SESSION["logged"] = 1 && $_SESSION["org"] == 1) || $_SESSION["email"] == "ziga_strgar@hotmail.com" || !empty($_SESSION["user_id"])) { ?>
                            <li><a href="addPart.php"><i class="icon icon-plus-1"></i> Dodaj del</a></li>
                        <?php } ?>
                        <?php if (!empty($_SESSION["user_id"]) && $_SESSION["email"] == "ziga_strgar@hotmail.com") { ?>
                            <li><a href="addCategory.php"><i class="icon icon-tag-fill"></i> Dodaj kategorijo</a></li>
                        <?php } ?>
                        <?php if (!empty($_SESSION["user_id"])) { ?>
                            <li><a href="editProfile.php"><i class="icon icon-contact-2"></i> Uredi profil</a></li>
                            <li><a href="logout.php"><i class="icon icon-logout"></i> Odjava</a></li>
                        <?php } else { ?>
                            <li><a href="login.php"><i class="icon icon-contact"></i> Prijava</a></li>
                            <li><a href="registration.php"><i class="icon icon-contact-add-2"></i> Registracija</a></li>
                        <?php } ?>
                        <li><a href="mailto:ziga_strgar@hotmail.com"><i class="icon icon-envelope"></i> Piši mi</a></li>
                        <?php if (!empty($_SESSION["user_id"])) { ?>
                            <div style='right: 220px; position: absolute;' class="navbar-text">
                                Pozdravljen, <?php echo $_SESSION["name"] . " " . $_SESSION["surname"]; ?>
                            </div>
                        <?php } ?>
                        <form style='right: 5px; position: absolute;' class="navbar-form navbar-left">
                            <div class="form-group">
                                <input type="text" name="fastsearch" class="form-control" id="search" placeholder="Hitro iskanje..." style='display: none;border-radius: 0px;'>
                            </div>
                            <span id="fastSearch" style="top: 10px; position: absolute; right: 20px;color: #777; cursor: pointer;" class="icon icon-search-1"></span>
                        </form>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </header>
        <div class="container" style="margin-top: 80px;">
            <div class="row">
                <div class="col-lg-12">