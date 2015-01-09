<?php
$global = (int) $_POST["global"];
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
?>
<div style="margin: 0 5px;" id="image<?php echo $global; ?>" class="fileinput fileinput-new" data-provides="fileinput">
    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
        <img src="http://placehold.it/190x140" alt="Slika izdelka">
    </div>
    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
    <div>
        <span class="pull-right color-danger" style="cursor: pointer; z-index:99;" data-toggle="popover" data-cotnent="Odstrani sliko" data-placement="left" onclick="removeImage(<?php echo $global; ?>);"><i class="icon icon-remove"></i></span>
        <span class="btn btn-default btn-file"><span class="fileinput-new">Izberi sliko</span><span class="fileinput-exists">Spremeni</span><input attr-id="<?php echo $global; ?>" type="file" name="gallery[]"></span>
        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Odstrani</a>
    </div>
</div>
<?php
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:".$_SERVER["HTTP_REFERER"]);
}
?>