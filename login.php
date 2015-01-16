<?php include_once './header.php'; ?>
<div class="block-flat col-lg-12 top-success">
    <h1 class="page-header">Prijava</h1>
    <?php if (!empty($_SESSION["move_me_to"])) { ?>
        <div class="col-lg-12 alert alert-danger alert-fixed-bottom">
            Da bi videli to stran, se najprej prijavite!
        </div>
    <?php } ?>
    <form action="loginCheck.php" method="POST" class="ajaxForm" role="form">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-address-at"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="E-poštni naslov">
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Geslo">
                </div>
            </div>
        </div>
        <?php
        if (!empty($_SESSION["move_me_to"])) {
            ?>
        <input type="hidden" name="redirect" value="<?php echo $_SESSION["move_me_to"]; ?>" />
        <?php unset($_SESSION["move_me_to"]); } ?>
        <br />
        <input type="submit" value="Prijavi me" class="btn btn-flat btn-primary" />
    </form>
</div>
<?php include_once './footer.php'; ?>