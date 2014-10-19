<?php include_once './header.php'; ?>
<div class="block-flat col-lg-12">
    <h3>Prijava</h3>
    <hr />
    <form action="loginCheck.php" method="POST" id="ajaxForm" role="form">
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
        <input type="hidden" name="redirect" value="index.php" />
        <br />
        <input type="submit" value="Prijavi me" class="btn btn-flat btn-primary" />
    </form>
</div>
<?php include_once './footer.php'; ?>