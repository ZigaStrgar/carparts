<?php
include_once './core/database.php';
include_once './core/functions.php';
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//ZNAMKE
$queryBrands = "SELECT * FROM brands WHERE visible = 1 ORDER BY name ASC";
$resultBrands = mysqli_query($link, $queryBrands);
$global = (int) $_POST["global"];
?>
<div id="car<?php echo $global; ?>">
    <hr />
    <div class="row">
        <div class="col-lg-12">
            <span onclick="removeCar(<?php echo $global; ?>);" data-toggle="tooltip" data-placement="bottom" title="Odstrani avtomobil" class="color-danger pull-right" style="cursor: pointer; "><i class="icon icon-remove"></i></span>
        </div>
        <br />
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">Znamka</span>
                <select id="<?php echo $global; ?>" name="brand" placeholder="Znamka" class="form-control aucp" autofocus="autofocus" autocorrect="off" autocomplete="off">
                    <option selected="selected" disabled="disabled">Vnesi znamko</option>
                    <?php while ($brand = mysqli_fetch_array($resultBrands)) { ?>
                        <option value="<?php echo $brand["id"]; ?>"><?php echo $brand["name"]; ?></option>
                    <?php } ?>
                </select>
                <span class="input-group-addon"><span class="color-danger">*</span></span>
            </div>
        </div>
        <div id="model<?php echo $global; ?>" class="col-md-6">
            <div class="load-bar loadermodel<?php echo $global; ?>">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">Tip</span>
                <input type="text" name="type[]" class="form-control" />
            </div>
        </div> 
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">Letnik</span>
                <input type="text" name="letnik[]" pattern="[0-9]{4}" title="Primer: 2014" class="form-control" />
            </div>
        </div>    
    </div>
    <br />
</div>
<?php } else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:".$_SERVER["HTTP_REFERER"]);
} ?>