<?php
ob_start();
include_once './core/session.php';
if (($_SESSION["email"] != "ziga_strgar@hotmail.com" && !empty($_SESSION["user_id"])) || empty($_SESSION["user_id"])) {
    user_log($_SERVER["REMOTE_ADDR"], $_SERVER["REQUEST_URI"], $_SERVER["HTTP_USER_AGENT"], $_SESSION["user_id"]);
}
if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
    if (!checkUser($_SESSION["user_id"]) && $_SERVER["REQUEST_URI"] != "/editProfile.php" && $_SERVER["REQUEST_URI"] != "/cookies.php" && $_SERVER["REQUEST_URI"] != "/terms.php") {
        header("Location: editProfile.php");
    }
}
?>
<!DOCTYPE html>
<html lang="sl-SI">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="description" content="Kupite ali prodajte rabljene ali nove avtodele.">
        <meta name="keywords" content="avto, deli, avto deli, rabljeni, rabljeni deli, novi deli, novo, novi, rabljeno, rezervno, rezervni deli, avtodeli, avtodele">
        <meta name="author" content="Žiga Strgar">
        <meta name="robots" content="index,follow">
        <title>Avtodeli</title>
        <!--  Google Web fonts  -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <!--  BOOTSTRAP  -->
        <link href="http://<?php echo URL; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="http://<?php echo URL; ?>/css/normalize.min.css" rel="stylesheet" type="text/css" />
        <?php if ($_SERVER['PHP_SELF'] == "/formLoad.php" || $_SERVER['PHP_SELF'] == "/matura/formLoad.php" || $_SERVER['PHP_SELF'] == "/editPart.php" || $_SERVER['PHP_SELF'] == "/matura/editPart.php" || $_SERVER['PHP_SELF'] == "/addPart.php" || $_SERVER['PHP_SELF'] == "/matura/addPart.php") { ?>
            <link href="http://<?php echo URL; ?>/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <!--  ICONS  -->
        <link href="http://<?php echo URL; ?>/css/carparts-font.min.css" rel="stylesheet" type="text/css" />
        <!--  jQuery  -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <?php if ($_SERVER["PHP_SELF"] == "/matura/parts.php" || $_SERVER["PHP_SELF"] == "/parts.php") { ?>
            <script src="http://<?php echo URL; ?>/plugins/autocomplete/jq-ui-autocomplete.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if ($_SERVER["PHP_SELF"] == "/addPart.php" || $_SERVER["PHP_SELF"] == "/editPart.php" || $_SERVER["PHP_SELF"] == "/search.php" || $_SERVER["PHP_SELF"] == "/result.php" || $_SERVER["PHP_SELF"] == "/matura/addPart.php" || $_SERVER["PHP_SELF"] == "/matura/editPart.php" || $_SERVER["PHP_SELF"] == "/matura/search.php" || $_SERVER["PHP_SELF"] == "/matura/result.php" || $_SERVER["PHP_SELF"] == "/matura/editProfile.php" || $_SERVER["PHP_SELF"] == "/editProfile.php") { ?>
            <!--  AUTOCOMPLETE SELECT  -->
            <script src="http://<?php echo URL; ?>/plugins/autocomplete/jquery.min.js" type="text/javascript"></script>
            <script src="http://<?php echo URL; ?>/plugins/autocomplete/jq.select-to-autocomplete.min.js" type="text/javascript"></script>
            <script src="http://<?php echo URL; ?>/plugins/autocomplete/jq-ui-autocomplete.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if ($_SERVER["PHP_SELF"] == "/search.php" || $_SERVER["PHP_SELF"] == "/result.php" || $_SERVER["PHP_SELF"] == "/matura/search.php" || $_SERVER["PHP_SELF"] == "/matura/result.php") { ?>
            <!--  SLIDER  -->
            <link href="http://<?php echo URL; ?>/plugins/js-slider/jquery.slider.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php if (isset($_SESSION["notify"])) { ?>
            <?php $notify = explode("|", $_SESSION["notify"]); ?>
            <script>
                $().ready(function () {
                    alertify.<?php echo $notify[0]; ?>("<?php echo $notify[1]; ?>");
                });
            </script>
            <?php unset($_SESSION["notify"]); ?>
        <?php } ?>
        <header>
            <div class="navbar navbar-fixed-top navbar-default">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span style="color: #777;">MENU</span>
                    </button>
                    <a class="navbar-brand" href="http://<?php echo URL; ?>/index.php"><span class="color-info"><img src="http://<?= URL; ?>/img/logo.jpg" alt="Company logo" height="25" /></a>
                    <p class="navbar-text slogan">Prodaja rabljenih in novih avtodelov</p>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li><a href="http://<?php echo URL; ?>/index.php"><i class="icon icon-home-1-1"></i> Domov</a></li>
                        <li><a href="http://<?php echo URL; ?>/parts.php"><i class="icon icon-gears-setting"></i> Deli</a></li>
                        <li><a href="http://<?php echo URL; ?>/search.php"><i class="icon icon-search-1"></i> Išči</a></li>
                        <?php if (!empty($_SESSION["user_id"])) { ?>
                            <li><a href="http://<?php echo URL; ?>/cart.php" <?php if (countItems($_SESSION["user_id"]) == 0) {
                            echo "";
                        } ?>><i class="icon icon-shopping-cart"></i> Košarica <span class="badge" id="cartNum"><?php echo countItems($_SESSION["user_id"]); ?></span></a></li>
                            <li><a href="http://<?php echo URL; ?>/addPart.php"><i class="icon icon-plus-1"></i> Dodaj del</a></li>
                            <li><a href="http://<?php echo URL; ?>/logout.php"><i class="icon icon-logout"></i> Odjava</a></li>
                        <?php } else { ?>
                            <li><a href="http://<?php echo URL; ?>/login.php"><i class="icon icon-contact"></i> Prijava</a></li>
                            <li><a id="reg-form" href="http://<?php echo URL; ?>/login.php#register"><i class="icon icon-contact-add-2"></i> Registracija</a></li>
                        <?php } ?>
                        <?php if (!empty($_SESSION["user_id"]) && $user["email"] == "ziga_strgar@hotmail.com") { ?>
                            <li><a href="http://<?php echo URL; ?>/addCategory.php"><i class="icon icon-tag-fill"></i> Dodaj kategorijo</a></li>
                    <?php } ?>
                    </ul>
<?php if (!empty($_SESSION["user_id"])) { ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="myName"><?php echo $user["name"] . " " . substr($user["surname"], 0, 1) . "."; ?></span> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="http://<?php echo URL; ?>/invoices.php"><i class="glyphicon glyphicon-list"></i> Moja naročila</a></li>
                                    <li><a href="http://<?= URL; ?>/myParts.php"><i class="icon icon-gear-cog"></i> Moji deli</a></li>
                                    <li class="divider"></li>
                                    <?php if ($user["email"] == "ziga_strgar@hotmail.com") { ?>
                                    <li><a href="http://<?php echo URL; ?>/adminInvoices.php"><i class="icon icon-hand-block"></i> Administracija naročil (<?= unsolvedInvoices() ?>)</a></li>
                                        <li class="divider"></li>
    <?php } ?>
                                    <li><a href="http://<?php echo URL; ?>/editProfile.php"><i class="icon icon-contact-2"></i> Uredi profil</a></li>
                                    <li><a href="http://<?php echo URL; ?>/logout.php"><i class="icon icon-logout"></i> Odjava</a></li>
                                </ul>
                            </li>
                        </ul>
<?php } ?>
                    <div class="navbar-right nav navbar-nav navbar-form">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon icon-search-1"></i></span>
                                <input type="text" id="search" class="form-control" placeholder="Hitro iskanje..." />
                            </div>
                        </div>
                    </div>
                </div><!--/.nav-collapse -->
            </div>
        </header>
        <div class="container" style="margin-top: 80px;">
            <div class="row">
                <div class="col-lg-12">
                        <?php if (isset($_SESSION["alert"])) { ?>
                            <?php $alert = explode("|", $_SESSION["alert"]); ?>
                        <div class="<?php echo $alert[0]; ?>">
                        <?php echo $alert[1]; ?>
                        </div>
    <?php if (!empty($alert[2])) { ?>
                            <script>
                                $(".alert").delay(<?= $alert[2]; ?>).fadeOut();
                            </script>
                        <?php } ?>
    <?php unset($_SESSION["alert"]); ?>
<?php } ?>