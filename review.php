<?php include_once './header.php'; ?>
<div class="stepwizard">
    <div class="stepwizard-row">
        <div class="stepwizard-step">
            <a href="cart.php" type="button" class="btn btn-success btn-circle"><i class="icon icon-shopping-cart"></i></a>
            <p>Košarica</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-primary btn-circle"><i class="icon icon-checklist"></i></button>
            <p>Pregled</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle" disabled="disabled"><i class="icon icon-credit-card-2"></i></button>
            <p>Konec</p>
        </div> 
    </div>
</div>
<div class="col-lg-12 block-flat top-danger">
    <h1 class="page-header">Pregled naročila</h1>
    <a href="cart.php" class="btn btn-flat btn-default"><i class="icon icon-arrow-line-left"></i> Nazaj v košarico</a>
    <a href="" class="btn btn-flat btn-success pull-right">Končaj naročilo <i class="icon icon-credit-card"></i></a>
</div>
<?php include_once './footer.php'; ?>