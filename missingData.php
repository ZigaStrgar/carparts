<?php include_once 'header.php'; ?>
<?php
if (empty($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
}
$queryUser = "SELECT * FROM users WHERE id = " . $_SESSION["user_id"];
$resultUser = mysqli_query($link, $queryUser);
$user = mysqli_fetch_array($resultUser);
$queryCities = "SELECT * FROM cities";
$resultCities = mysqli_query($link, $queryCities);
//ČE RES NIMA VSEH PODATKOV
if ($user["first_login"] != 0) {
    header("Location: editprofile.php");
}
?>
<div class="col-lg-12 block-flat">
    <h1 class="page-header">Manjkajoči podatki</h1>
    <?php if ($user["first_login"] == 0) { ?>
        <div class="alert alert-danger">
            Ob prvi prijavi Vas prosimo, da vnesete dodatne nujno potrebne podatke!
        </div>
    <?php } ?>
    <form action="editingprofile.php" method="POST" id="ajaxForm">
        <div class="row">
            <?php if (empty($user["name"])) { ?>
                <div class="col-lg-4 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon icon-contact-2"></i></span>
                        <input type="text" name="name" class="form-control" value="<?php echo $user["name"]; ?>" placeholder="Vnesite ime" />
                    </div>
                </div>
            <?php } ?>
            <?php if (empty($user["surname"])) { ?>
                <div class="col-lg-4 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon icon-contact-2"></i></span>
                        <input type="text" name="surname" class="form-control" value="<?php echo $user["surname"]; ?>" placeholder="Vnesite priimek" />
                    </div>
                </div>
            <?php } ?>
            <?php if (empty($user["email"])) { ?>
                <div class="col-lg-4 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon icon-address-at"></i></span>
                        <input type="email" name="email" class="form-control" value="<?php echo $user["email"]; ?>" placeholder="Vnesite e-poštni naslov" />
                    </div>
                </div>
            <?php } ?>
            <?php if (empty($user["phone"])) { ?>
                <div class="col-lg-4 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i> +386</span>
                        <input type="text" name="telephone" class="form-control" placeholder="Vnesite telefonsko številko" title="Primer: 41 202 710" />
                    </div>
                </div>
            <?php } ?>
            <?php if (empty($user["location"])) { ?>
                <div class="col-lg-4 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon icon-location"></i></span>
                        <input type="text" name="location" class="form-control" placeholder="Vnesite naslov" title="Primer: Radmirje 1" />
                    </div>
                </div>
            <?php } ?>
            <?php if (empty($user["city_id"])) { ?>
                <div class="col-lg-4 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                        <select name="city" class="form-control" autofocus="autofocus" autocorrect="off" autocomplete="off">
                            <option selected="selected" disabled="disabled">Vnesi kraj</option>
                            <?php while($city = mysqli_fetch_array($resultCities)){ ?>
                            <option value="<?php echo $city["id"]; ?>"><?php echo $city["number"]; ?> <?php echo $city["name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
        </div>
        <br />
        <input type="submit" class="btn btn-primary btn-flat" value="Uredi podatke" />
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