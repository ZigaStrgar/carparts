<footer>
    <div class="row footer">
        <div class="col-lg-4 col-sm-6">
            <h5 class="page-header">Navigacija</h5>
            <ul class="nav">
                <li><a href="http://<?php echo URL; ?>/index.php">Domov</a></li>
                <li><a href="http://<?php echo URL; ?>/parts.php">Deli</a></li>
                <li><a href="http://<?php echo URL; ?>/search.php">Išči</a></li>
                <?php if ((!empty($_SESSION["user_id"]) && $_SESSION["logged"] = 1 && $_SESSION["org"] == 1) || $_SESSION["email"] == "ziga_strgar@hotmail.com" || !empty($_SESSION["user_id"])) { ?>
                <li><a href="http://<?php echo URL; ?>/addPart.php">Dodaj del</a></li>
                <?php } ?>
                <?php if (!empty($_SESSION["user_id"]) && $_SESSION["email"] == "ziga_strgar@hotmail.com") { ?>
                <li><a href="http://<?php echo URL; ?>/addCategory.php">Dodaj kategorijo</a></li>
                <?php } ?>
                <?php if (!empty($_SESSION["user_id"])) { ?>
                <li><a href="http://<?php echo URL; ?>/editProfile.php">Uredi profil</a></li>
                <li><a href="http://<?php echo URL; ?>/logout.php">Odjava</a></li>
                <?php } else { ?>
                <li><a href="http://<?php echo URL; ?>/login.php">Prijava</a></li>
                <li><a href="http://<?php echo URL; ?>/registration.php">Registracija</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-lg-4 col-sm-6">
            <h5 class="page-header">Kontakt</h5>
            <ul class="nav">
                <li>Žiga Strgar</li>
                <li>Ter 69</li>
                <li>3333 Ljubno ob Savinji</li>
                <li>+386 41 202 710</li>
                <li>ziga_strgar@hotmail.com</li>
            </ul>
        </div>
        <div class="col-lg-4 col-sm-6">
            <h5 class="page-header">Izdelava</h5>
            Žiga Strgar © 2014
            <br />
            Stran je del projektne naloge
        </div>
    </div>
</footer>
</div>
</div>
</div>
<div id="loading" class="hide">
    <div class="load-content">
        <div class="load-bar">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </div>
</div>
<!--  Core JS file  -->
<script async src="http://<?php echo URL; ?>/core/scripts.min.js" type="text/javascript"></script>
<!--  BOOTSTRAP  -->
<script async src="http://<?php echo URL; ?>/js/bootstrap.min.js" type="text/javascript"></script>
<script async src="http://<?php echo URL; ?>/js/jasny-bootstrap.min.js" type="text/javascript"></script>
<!--  ALERTIFY PLUGIN  -->
<script async src="http://<?php echo URL; ?>/plugins/alertify/alertify.min.js" type="text/javascript"></script>
<script async src="http://<?php echo URL; ?>/plugins/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
<!--  JQUERY PRICE SLIDER  -->
<script async src="http://<?php echo URL; ?>/plugins/js-slider/tmpl.min.js" type="text/javascript"></script>
<script async src="http://<?php echo URL; ?>/plugins/js-slider/draggable-0.1.min.js" type="text/javascript"></script>
<script async src="http://<?php echo URL; ?>/plugins/js-slider/jshashtable-2.1_src.min.js" type="text/javascript"></script>
<link href="http://<?php echo URL; ?>/plugins/js-slider/jquery.slider.min.css" rel="stylesheet" type="text/css" />
<script async src="http://<?php echo URL; ?>/plugins/js-slider/jquery.slider.min.js" type="text/javascript"></script>
<script async src="http://<?php echo URL; ?>/plugins/js-slider/jquery.dependClass-0.1.min.js" type="text/javascript"></script>
<script async src="http://<?php echo URL; ?>/plugins/js-slider/jquery.numberformatter-1.2.3.min.js" type="text/javascript"></script>
<!--  BOOTSTRAP SWITCH  -->
<script async src="http://<?php echo URL; ?>/plugins/switch/bootstrap-switch.min.js"></script>
<!--  SLIDE EFECTS  -->
<script src="http://<?php echo URL; ?>/plugins/efects/core.min.js"></script>
<script async src="http://<?php echo URL; ?>/plugins/efects/slide.min.js"></script>
</body>
</html>