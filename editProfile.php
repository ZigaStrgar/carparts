<?php include_once 'header.php'; ?>
<?php
if (empty($_SESSION["user_id"])) {
    $_SESSION["move_me_to"] = "editProfile.php";
    header("Location: login.php");
}
$queryUser = "SELECT * FROM users WHERE id = " . $_SESSION["user_id"];
$resultUser = mysqli_query($link, $queryUser);
$user = mysqli_fetch_array($resultUser);
$queryCities = "SELECT * FROM cities";
$resultCities = mysqli_query($link, $queryCities);
?>
<div class="col-lg-12 block-flat">
    <h1 class="page-header">Urejanje profila</h1>
    <?php if ($user["first_login"] == 0) { ?>
        <div class="alert alert-danger">
            Ob prvi prijavi Vas prosimo, da vnesete dodatne nujno potrebne podatke!
        </div>
    <?php } ?>
    <form action="editingprofile.php" method="POST" class="ajaxForm">
        <h3 class="page-header">Urejanje podatkov</h3>
        <div class="alert alert-warning">
            <p>Vsi podatki so nujni za uporabo vseh možnosti na strani!</p>
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
                    <input type="email" name="email" class="form-control" value="<?php echo $user["email"]; ?>" placeholder="Vnesite e-poštni naslov" />
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
                        <?php while ($city = mysqli_fetch_array($resultCities)) { ?>
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
        <input type="submit" class="btn btn-flat btn-primary" value="Spremeni geslo" />
    </form>
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