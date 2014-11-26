<?php include_once 'header.php'; ?>
<?php
//TIPI
$queryTypes = "SELECT * FROM types ORDER BY name ASC";
$resultTypes = mysqli_query($link, $queryTypes);
//ZNAMKE
$queryBrands = "SELECT * FROM brands ORDER BY name ASC";
$resultBrands = mysqli_query($link, $queryBrands);
//KATEGORIJE
$queryCategories = "SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC";
$resultCategories = mysqli_query($link, $queryCategories);
//CENA
$queryPrice = "SELECT MAX(price) AS max, MIN(price) AS min FROM parts";
$resultPrice = mysqli_query($link, $queryPrice);
$price = mysqli_fetch_array($resultPrice);
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
                        <input value="<?php echo $type["id"]; ?>" type="checkbox" style="margin: 0 10px 0 0;" name="types[]">
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
        <br />
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Tip</span>
                    <input type="text" name="type" class="form-control" />
                </div>
            </div> 
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Letnik</span>
                    <input type="text" name="letnik" pattern="[0-9]{4}" title="Primer: 2014" class="form-control" />
                </div>
            </div>    
        </div>
        <h4 class="page-header">Podatki o delu</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Kataloška številka</span>
                    <input type="text" name="number" class="form-control" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Ime dela</span>
                    <input type="text" name="partname" class="form-control" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Cena</span>
                    <div class="price-range">
                        <input id="Slider2" type="slider" name="price" value="0;10000000" />
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <h5>Kategorija izdelka</h5>
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                    <select name="category" class="form-control">
                        <option selected="selected"></option>
                        <?php while ($category = mysqli_fetch_array($resultCategories)) { ?>
                            <option value="<?php echo $category["id"]; ?>"><?php echo $category["name"] ?></option>
                        <?php } ?>
                    </select>
                </div>  
            </div>
        </div>
        <br />
        <div id="otherCategories" class="row">

        </div>
        <br />
        <div class="row">
            <div class="col-lg-12">
                <input type="submit" class="btn btn-flat btn-success" value="Išči"/>
            </div>
        </div>
    </form>
</div>
<script type='text/javascript'>
    $(window).load(function () {
        jQuery("#Slider2").slider
                ({
                    from: <?php echo $price["min"] ?>,
                    to: <?php echo $price["max"] ?>,
                    scale: [<?php echo $price["min"] ?>, <?php echo $price["max"] ?>],
                    limits: true,
                    step: 50,
                    dimension: ''
                });
    });
</script>
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

    function fetchCategories(id) {
        $.ajax({
            url: "fetchCategories.php",
            type: "POST",
            data: {id: id},
            success: function (comeback) {
                $("#otherCategories").html(comeback);
            }
        });
    }

    jQuery("#Slider2").slider
            (
                    {
                        from: 0,
                        to: 10000000,
                        scale: [0, 10000000],
                        limits: true,
                        step: 100000,
                        dimension: ''
                    }
            );

    $(document).on("change", "select[name=category]", function () {
        $currentSelected = $(this).val();
        fetchCategories($currentSelected);
    });

    $(document).ready(function () {
        $currentSelected = 0;
    });

    jQuery("#slider").slider
            ({
                from: <?php echo $price["min"]; ?>,
                to: <?php echo $price["max"]; ?>,
                scale: [<?php echo $price["min"]; ?>,<?php echo $price["max"]; ?>],
                limits: false,
                step: 10,
                dimension: ''
            });
</script>
<?php include_once 'footer.php'; ?>