<?php include_once 'header.php'; ?>
<?php
if (empty($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'matura') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
    die();
    exit();
}
$user = Db::queryOne("SELECT * FROM users WHERE id = ?", $_SESSION["user_id"]);
$cities = Db::queryAll("SELECT * FROM cities");
?>
<div class="col-lg-12 block-flat top-success">
    <h1 class="page-header">Urejanje profila</h1>
    <?php if ($user["first_login"] == 0) { ?>
        <div class="alert alert-danger">
            Ob prvi prijavi Vas prosimo, da vnesete dodatne nujno potrebne podatke!
        </div>
    <?php } ?>
    <form action="editingprofile.php" method="POST" class="ajaxForm">
        <h3 class="page-header">Urejanje podatkov</h3>
        
            <div class="alert alert-warning">
                <p>Vsi podatki so nujni za uporabo vseh možnosti na strani! <a href="terms.php#privacy">Preberi več o varovanju podatkov</a></p>
            </div>
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <div class="input-group<?php
                if (empty($user["name"])) {
                    echo " has-error";
                }
                ?>">
                    <span class="input-group-addon"><i class="icon icon-contact-2"></i></span>
                    <input type="text" name="name" class="form-control" value="<?php echo $user["name"]; ?>" placeholder="Vnesite ime" />
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="input-group<?php
                if (empty($user["surname"])) {
                    echo " has-error";
                }
                ?>">
                    <span class="input-group-addon"><i class="icon icon-contact-2"></i></span>
                    <input type="text" name="surname" class="form-control" value="<?php echo $user["surname"]; ?>" placeholder="Vnesite priimek" />
                </div>
            </div>
            <div class="col-lg-4 col-xs-12<?php
            if (empty($user["email"])) {
                echo " has-error";
            }
            ?>">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-address-at"></i></span>
                    <input type="email" name="email" readonly disabled class="form-control" value="<?php echo $user["email"]; ?>" placeholder="Vnesite e-poštni naslov" />
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <div class="input-group<?php
                if (empty($user["phone"])) {
                    echo " has-error";
                }
                ?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                    <input type="text" name="telephone" class="form-control bfh-phone" placeholder="Vnesite telefonsko številko" value="<?php echo $user["phone"]; ?>" data-format="+386 dd ddd-ddd">
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="input-group<?php
                if (empty($user["location"])) {
                    echo " has-error";
                }
                ?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    <input type="text" name="location" class="form-control" value="<?php echo $user["location"]; ?>" placeholder="Vnesite naslov" title="Primer: Radmirje 1" />
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="input-group<?php
                if (empty($user["city_id"])) {
                    echo " has-error";
                }
                ?>">
                    <span class="input-group-addon"><i class="icon icon-location"></i></span>
                    <select name="city" class="form-control" autofocus="autofocus" autocorrect="off" autocomplete="off">
                        <option selected="selected" disabled="disabled">Vnesi kraj</option>
                        <?php foreach ($cities as $city) { ?>
                            <option value="<?php echo $city["id"]; ?>" <?php
                            if ($city["id"] == $user["city_id"]) {
                                echo "selected='selected'";
                            }
                            ?>><?php echo $city["number"]; ?> <?php echo $city["name"]; ?></option>
                                <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <br />
        <input type="hidden" name="refresh" value="1" />
        <input type="submit" class="btn btn-flat btn-primary" value="Uredi podatke" />
    </form>
    <br />
    <form action="changePassword.php" method="POST" class="ajaxForm">
        <h3 class="page-header">Spreminjanje gesla</h3>
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" name="oldpassword" class="form-control" placeholder="Vnesite trenutno geslo" />
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Vnesite novo geslo" />
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" name="password2" class="form-control" placeholder="Ponovite novo geslo" />
                </div>
            </div>
        </div>
        <br />
        <input name="clear" type="hidden" value="1" />
        <input type="submit" class="btn btn-flat btn-primary" value="Spremeni geslo" />
    </form>
    <h3 class="page-header">Interesi</h3>
    <form class="ajaxForm" method="POST" action="deleteInterests.php">
        <input type="submit" value="Pobriši interese" class="btn btn-flat btn-danger"/>
    </form>
    <span class="help-block">Pobriše dele iz sekcije "Mogoče vam bo všeč tudi" na prvi strani</span>
</div>
<script type="text/javascript" charset="utf-8">
    (function ($) {
        $(function () {
            $('select').selectToAutocomplete();
        });
    })(jQuery);

    $(document).ready(function () {
        setInterval(function () {
            $width = $("select").width() - 13;
            $(".ui-autocomplete").css({"list-style-type": "none", "width": $width});
        }, 100);
    });
</script>
<?php include_once 'footer.php'; ?>