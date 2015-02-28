<?php include_once 'header.php'; ?>
<?php 
if(!empty($_SESSION["user_id"])){
    header("Location: index.php");
}
?>
<div class="block-flat col-lg-12 top-success">
    <h1 class="page-header">Registracija</h1>
    <form action="register.php" method="POST" class="ajaxForm" role="form">
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
                    <input type="password" class="form-control" id="check-me" name="password" placeholder="Geslo">
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" id="check-me2" name="password2" placeholder="Ponovite geslo">
                </div>
            </div>
        </div>
        <br />
        <span class="color-danger" id="message"></span>
        <span class="help-block">S klikom na gumb "Registriraj me" se strinjate s <a href="./terms.php" target="_blank">splošnimi pogoji uporabe</a></span>
        <br />
        <input type="submit" value="Registriraj me" class="btn btn-flat btn-success" />
    </form>
</div>
<script>
    $(document).ready(function () {
        setInterval(function () {
            if ($("#check-me").val().length > 0 || $("#check-me2").val().length > 0) {
                if ($("#check-me").val().length > 6) {
                    if ($("#check-me").val() !== $("#check-me2").val()) {
                        $("#message").text("Gesli se ne ujemata!");
                    } else {
                        $("#message").text("");
                    }
                } else {
                    $("#message").text("Geslo mora vsebovati vsaj 7 znakov!");
                }
            }
        }, 150);
    });
</script>
<?php include_once 'footer.php'; ?>