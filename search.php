<?php include_once 'header.php'; ?>
<?php
//TIPI
$queryTypes = "SELECT * FROM types ORDER BY name ASC";
$resultTypes = mysqli_query($link, $queryTypes);
//ZNAMKE
$queryBrands = "SELECT * FROM brands ORDER BY name ASC";
$resultBrands = mysqli_query($link, $queryBrands);
//MODELI
?>
<div class="col-lg-12 block-flat">
    <h3 class="page-header">Iskanje</h3>
    <form action="result.php" method="POST" role="form">
        <h4 class="page-header">Tip avtomobila</h4>
        <div class="row">
            <div class="col-md-12 form-inline">
                <?php while ($type = mysqli_fetch_array($resultTypes)) { ?>
                    <div class="input-group">
                        <?php echo $type["name"]; ?>
                        <input type="checkbox" style="margin: 0 10px 0 0;" name="types[]">
                    </div>
                <?php } ?>
            </div>
        </div>
        <h4 class="page-header">Podatki o avtomobilu</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Znamka</span>
                    <select name="brand" placeholder="Znamka" class="form-control">
                        <option value="0" selected="selected"></option>
                        <?php while ($brand = mysqli_fetch_array($resultBrands)) { ?>
                            <option value="<?php echo $brand["id"]; ?>"><?php echo $brand["name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div id="model" class="col-md-6">

            </div>
        </div>
        <h4 class="page-header">Podatki o delu</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i>Kataloška #</i></span>
                    <input type="text" name="number" class="form-control" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Ime dela</span>
                    <input type="text" name="number" class="form-control" />
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-12">
                <input type="submit" class="btn btn-flat btn-success" value="Išči"/>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).on("change", "select[name=brand]", function () {
        getModels($(this).val());
    });

    function getModels(id) {
        $.ajax({
            url: "fetchModels.php",
            type: "POST",
            data: {id: id},
            success: function (cb) {
                $("#model").html(cb);
            }
        });
    }
</script>
<?php include_once 'footer.php'; ?>