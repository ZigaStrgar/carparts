<footer>
    <div class="row footer">
        <div class="col-lg-4 col-sm-6">
            <h5 class="page-header">Navigacija</h5>
            <ul class="nav">
                <li><a href="http://<?= URL; ?>/index.php">Domov</a></li>
                <li><a href="http://<?= URL; ?>/parts.php">Deli</a></li>
                <li><a href="http://<?= URL; ?>/search.php">Išči</a></li>
                <?php if (!empty($_SESSION["user_id"])) { ?>
                    <li><a href="http://<?= URL; ?>/cart.php">Košarica</a></li>
                    <li><a href="http://<?= URL; ?>/addPart.php">Dodaj del</a></li>
                    <li><a href="http://<?= URL; ?>/invoices.php">Moja naročila</a></li>
                    <li><a href="http://<?= URL; ?>/myParts.php">Moji deli</a></li>
                    <li><a href="http://<?= URL; ?>/editProfile.php">Uredi profil</a></li>
                    <?php if (!empty($_SESSION["user_id"]) && $user["email"] == "ziga_strgar@hotmail.com") { ?>
                        <li><a href="http://<?= URL; ?>/addCategory.php">Dodaj kategorijo</a></li>
                    <?php } ?>
                    <li><a href="http://<?= URL; ?>/logout.php">Odjava</a></li>
                <?php } else { ?>
                    <li><a href="http://<?= URL; ?>/login.php">Prijava</a></li>
                    <li><a href="http://<?= URL; ?>/login.php#register">Registracija</a></li>
                <?php } ?>
                <li><a href="http://<?= URL; ?>/cookies.php">Piškoti</a></li>
                <li><a href="http://<?= URL; ?>/terms.php">Pogoji uporabe</a></li>
            </ul>
        </div>
        <div class="col-lg-4 col-sm-6">
            <h5 class="page-header">Kontakt</h5>
            Žiga Strgar<br/>
            Ter 69<br/>
            3333 Ljubno ob Savinji<br/>
            +386 41-202/710<br/>
            <a href="mailto:ziga_strgar@hotmail.com">ziga_strgar@hotmail.com</a>
        </div>
        <div class="col-lg-4 col-sm-6">
            <h5 class="page-header">Izdelava</h5>

            <p class="text-center">
                Žiga Strgar © 2014 - <?php echo date("Y"); ?>
                <br/>
                Stran je del maturitetne naloge<br/>
                <a href="https://www.facebook.com/ziga.strgar" target="_blank" class="btn-facebook btn-socialno"><i
                        class="icon icon-facebook"></i></a>
                <a href="https://www.twitter.com/ZigaStrgar" target="_blank" class="btn-twitter btn-socialno"><i
                        class="icon icon-twitter"></i></a>
                <a href="https://www.linkedin.com/profile/view?id=315194262" target="_blank"
                   class="btn-linkedin btn-socialno"><i class="icon icon-linkedin"></i></a>
                <a href="https://www.github.com/ZigaStrgar" target="_blank" class="btn-github btn-socialno"><i
                        class="icon icon-social-github"></i></a>
                <a href="mailto:ziga_strgar@hotmail.com" target="_blank" class="btn-socialno"><i
                        class="icon icon-letter-mail"></i></a>
            </p>
        </div>
    </div>
</footer>
</div>
</div>
</div>
<div id="cookies" style="display: none;">
    Stran uporablja piškote
    <div class="cookie-buttons">
        <span class="btn btn-flat btn-success accept">Sprejmi</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
            class="btn btn-danger btn-flat decline">Zavrni</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
            href="http://<?= URL; ?>/cookies.php" class="color-info no-hover">Preberi več</a>
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
<div id="totop">
    <i class="icon icon-angle-left"></i>
</div>
<!--  Core JS files needed for basic services  -->
<script async src="http://<?= URL; ?>/core/scripts.min.js" type="text/javascript"></script>
<script src="http://<?= URL; ?>/js/bootstrap.min.js" type="text/javascript"></script>
<script async src="http://<?= URL; ?>/plugins/alertify/alertify.min.js" type="text/javascript"></script>
<?php if ($_SERVER["PHP_SELF"] == "/part.php" || $_SERVER["PHP_SELF"] == "/matura/part.php") : ?>
    <script async src="http://<?= URL; ?>/plugins/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
<?php endif; ?>
<?php if ($_SERVER['PHP_SELF'] == "/search.php" || $_SERVER['PHP_SELF'] == "/result.php" || $_SERVER['PHP_SELF'] == "/matura/search.php" || $_SERVER['PHP_SELF'] == "/matura/result.php") { ?>
    <!--  JQUERY PRICE SLIDER  -->
    <script async src="http://<?= URL; ?>/plugins/js-slider/tmpl.js" type="text/javascript"></script>
    <script async src="http://<?= URL; ?>/plugins/js-slider/draggable-0.1.js" type="text/javascript"></script>
    <script async src="http://<?= URL; ?>/plugins/js-slider/jshashtable-2.1_src.js" type="text/javascript"></script>
    <script async src="http://<?= URL; ?>/plugins/js-slider/jquery.slider.js" type="text/javascript"></script>
    <script async src="http://<?= URL; ?>/plugins/js-slider/jquery.dependClass-0.1.js" type="text/javascript"></script>
    <script async src="http://<?= URL; ?>/plugins/js-slider/jquery.numberformatter-1.2.3.js" type="text/javascript"></script>
<?php } ?>
<?php if ($_SERVER['PHP_SELF'] == "/editProfile.php" || $_SERVER['PHP_SELF'] == "/matura/editProfile.php") { ?>
    <!--  PHONE FORM/INPUT HELPER  -->
    <script async src="http://<?= URL; ?>/js/bootstrap-formhelpers-phone.min.js" type="text/javascript"></script>
<?php } ?>
<?php if ($_SERVER['PHP_SELF'] == "/parts.php" || $_SERVER['PHP_SELF'] == "/matura/parts.php") { ?>
    <!--  DROPDOWN  -->
    <script src="http://<?= URL; ?>/plugins/dropdown/jquery.selectBoxIt.min.js"></script>
<?php } ?>
<?php if ($_SERVER['PHP_SELF'] == "/formLoad.php" || $_SERVER['PHP_SELF'] == "/matura/formLoad.php" || $_SERVER['PHP_SELF'] == "/editPart.php" || $_SERVER['PHP_SELF'] == "/matura/editPart.php" || $_SERVER['PHP_SELF'] == "/addPart.php" || $_SERVER['PHP_SELF'] == "/matura/addPart.php") { ?>
    <!--  JASNY  -->
    <script async src="http://<?= URL; ?>/js/jasny-bootstrap.min.js" type="text/javascript"></script>
    <!--  BOOTSTRAP SWITCH  -->
    <script async src="http://<?= URL; ?>/plugins/switch/bootstrap-switch.min.js"></script>
    <!--  WYSIHTML5  -->
    <script async src="http://<?= URL; ?>/plugins/wysihtml/wysihtml5-toolbar.min.js"></script>
    <script async src="http://<?= URL; ?>/plugins/wysihtml/bootstrap3-wysihtml5.js"></script>
<?php } ?>
<?php if ($_SERVER['PHP_SELF'] == "/part.php" || $_SERVER['PHP_SELF'] == "/matura/part.php") { ?>
    <!--  LIGHTBOX GALLERY  -->
    <script async src="http://<?= URL; ?>/plugins/bgal/ekko-lightbox.min.js"></script>
<?php } ?>
<script>
    $(document).ready(function () {
        if (localStorage.getItem("cookies") !== null) {
            if (localStorage.getItem("cookies") == 1) {
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

                ga('create', 'UA-38737220-2', 'auto');
                ga('send', 'pageview');
            }
        }
    });
</script>
</body>
</html>