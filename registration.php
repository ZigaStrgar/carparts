<?php include_once 'header.php'; ?>
<div class="block-flat col-lg-12">
    <h3>Registracija</h3>
    <hr />
    <form action="register.php" method="POST" id="ajaxForm" role="form">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-contact-2"></i></span>
                    <input type="text" class="form-control" name="name" placeholder="Ime">
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-contact-2"></i></span>
                    <input type="text" class="form-control" name="surname" placeholder="Priimek">
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-address-at"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="E-poštni naslov">
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Geslo">
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" name="password2" placeholder="Ponovite geslo">
                </div>
            </div>
        </div>
        <br />
        <input type="hidden" value="login.php" name="redirect" />
        <input type="submit" value="Registriraj me" class="btn btn-flat btn-primary" />
    </form>
</div>
<?php include_once 'footer.php'; ?>