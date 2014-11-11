<?php include_once 'header.php'; ?>
<?php
$queryUser = "SELECT * FORM users WHERE id = " . $_SESSION["user_id"];
$resultUser = mysqli_query($link, $queryUser);
$user = mysqli_fetch_array($resultUser);
//ČE RES NIMA VSEH PODATKOV
if ($user["first_login"] != 0) {
    header("Location: editprofile.php");
}
?>
<div class="col-lg-12 block-flat">
    <h3 class="page-header">Prva prijava</h3>
    <div class="alert alert-danger">
        Ob prvi prijavi Vas prosimo, da vnesete dodatne nujno potrebne podatke!
    </div>
    <form action="editingprofile.php" method="POST">
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i> +386</span>
                    <input type="text" name="telephone" class="form-control" placeholder="Vnesite telefonsko številko" title="Primer: 41 202 710" />
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-location"></i></span>
                    <input type="text" name="location" class="form-control" placeholder="Vnesite naslov" title="Primer: Radmirje 1" />
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    <select name="category" class="form-control" autofocus="autofocus" autocorrect="off" autocomplete="off">
                        <option selected="selected" disabled="disabled">Vnesi kraj</option>
                        <option>Velenje</option>
                        <option>Celje</option>
                    </select>
                </div>
            </div>
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
    
    $(document).ready(function(){
       setInterval(function(){
           $width = $("select").width() - 13;
           $(".ui-autocomplete").css({"list-style-type": "none", "width": $width});
       }, 100); 
    });
</script>
<?php include_once 'footer.php'; ?>