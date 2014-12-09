<!DOCTYPE html>
<?php
ob_start();
if (strpos("localhost", $_SERVER["HTTP_HOST"]) !== FALSE) {
    define("URL", $_SERVER["HTTP_HOST"] . "/carparts");
} else {
    define("URL", $_SERVER["HTTP_HOST"]);
}
error_reporting(0);
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (($_SESSION["email"] != "ziga_strgar@hotmail.com" && !empty($_SESSION["user_id"])) || empty($_SESSION["user_id"])) {
    user_log($_SERVER["REMOTE_ADDR"], $_SERVER["REQUEST_URI"], $link, $_SESSION["user_id"]);
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Carparts</title>
        <!--  Google Web fonts  -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <!--  ICONS  -->
        <link href="http://<?php echo URL; ?>/css/carparts-font.css" rel="stylesheet" type="text/css" />
        <!--  BOOTSTRAP  -->
        <link href="http://<?php echo URL; ?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="http://<?php echo URL; ?>/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!--  ALERTIFY PLUGIN  -->
        <link rel="stylesheet" type="text/css" href="http://<?php echo URL; ?>/plugins/alertify/alertify.core.css" />
        <link rel="stylesheet" type="text/css" href="http://<?php echo URL; ?>/plugins/alertify/alertify.default.css" />
        <!--  jQuery  -->
        <script async type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!--  AUTOCOMPLETE SELECT  -->
        <script src="http://<?php echo URL; ?>/plugins/autocomplete/jquery.js" type="text/javascript"></script>
        <script src="http://<?php echo URL; ?>/plugins/autocomplete/jq.select-to-autocomplete.js" type="text/javascript"></script>
        <script src="http://<?php echo URL; ?>/plugins/autocomplete/jq-ui-autocomplete.js" type="text/javascript"></script>
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
                        <li><a href="http://<?php echo URL; ?>/index.php"><i class="icon icon-home-1-1"></i> Domov</a></li>
                        <li><a href="http://<?php echo URL; ?>/parts.php"><i class="icon icon-gears-setting"></i> Deli</a></li>
                        <li><a href="http://<?php echo URL; ?>/search.php"><i class="icon icon-search-1"></i> Išči</a></li>
                        <?php if ((!empty($_SESSION["user_id"]) && $_SESSION["logged"] = 1 && $_SESSION["org"] == 1) || $_SESSION["email"] == "ziga_strgar@hotmail.com" || !empty($_SESSION["user_id"])) { ?>
                            <li><a href="http://<?php echo URL; ?>/addPart.php"><i class="icon icon-plus-1"></i> Dodaj del</a></li>
                        <?php } ?>
                        <?php if (!empty($_SESSION["user_id"]) && $_SESSION["email"] == "ziga_strgar@hotmail.com") { ?>
                            <li><a href="http://<?php echo URL; ?>/addCategory.php"><i class="icon icon-tag-fill"></i> Dodaj kategorijo</a></li>
                        <?php } ?>
                        <?php if (!empty($_SESSION["user_id"])) { ?>
                            <li><a href="http://<?php echo URL; ?>/editProfile.php"><i class="icon icon-contact-2"></i> Uredi profil</a></li>
                            <li><a href="http://<?php echo URL; ?>/logout.php"><i class="icon icon-logout"></i> Odjava</a></li>
                        <?php } else { ?>
                            <li><a href="http://<?php echo URL; ?>/login.php"><i class="icon icon-contact"></i> Prijava</a></li>
                            <li><a href="http://<?php echo URL; ?>/registration.php"><i class="icon icon-contact-add-2"></i> Registracija</a></li>
                        <?php } ?>
                        <li><a href="mailto:ziga_strgar@hotmail.com"><i class="icon icon-envelope"></i> Piši mi</a></li>
                    </ul>
                    <?php if (!empty($_SESSION["user_id"])) { ?>
                        <div style='right: 220px; position: absolute;' class="navbar-text">
                            Pozdravljen, <?php echo $_SESSION["name"] . " " . $_SESSION["surname"]; ?>
                        </div>
                    <?php } ?>
                    <div style='right: 5px; position: absolute;' class="navbar-form navbar-left">
                        <div class="form-group">
                            <input type="text" name="fastsearch" class="form-control" id="search" placeholder="Hitro iskanje..." style='display: none;border-radius: 0px;'>
                        </div>
                        <span id="fastSearch" style="top: 10px; position: absolute; right: 20px;color: #777; cursor: pointer;" class="icon icon-search-1"></span>
                    </div>
                </div><!--/.nav-collapse -->
            </div>
        </header>
        <div class="container" style="margin-top: 80px;">
            <div class="row">
                <div class="col-lg-12">