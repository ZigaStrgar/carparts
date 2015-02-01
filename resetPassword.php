<?php include_once './header.php'; ?>
<div class="col-lg-12 block-flat top-success">
    <h1 class="page-header">Obnavljanje gesla</h1>
    <div class="alert alert-warning">
        Ob kliku na gumb se vam bo ponastavilo geslo. To novo geslo boste dobili na e-naslov in ga boste uporabili ob naslednji prijavi!
    </div>
    <form action="resetPass.php" method="POST">
        <div class="input-group">
            <span class="input-group-addon"><i class="icon icon-address-at"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Vnesite vaš e-naslov" />
        </div>
        <br />
        <input type="submit" name="submit" value="Ponastavi geslo" class="btn btn-flat btn-success" />
    </form>
</div>
<?php include_once './footer.php'; ?>