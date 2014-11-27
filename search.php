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
//MINIMUM
$min = round($price["min"], -1) - 10;
if ($min < 0) { //NE SME BIT -
    $min = 0;
}
//MAXIMUM
$max = round($price["max"], -1) + 10;
?>
<div class="col-lg-12 block-flat">
    <h3 class="page-header">Iskanje</h3>
    <form action="result.php" method="POST" role="form">
        <h4 class="page-header">Tip avtomobila</h4>
        <span class="help-block">Če ne izbereš nič, bo iskalo med vsemi tipi avtomobilov!</span>
        <div class="row">
            <div class="col-lg-12 form-inline">
                <div class="product-chooser">
                    <?php while ($type = mysqli_fetch_array($resultTypes)) { ?>
                        <div class="col-lg-2" style="width: 185px;">
                            <div class="product-chooser-item">
                                <center><img src="./img/<?php echo strtolower($type["name"]) ?>.png" alt="<?php echo $type["name"]; ?> image" width="100"/></center>
                                <div class="col-lg-12">
                                    <input type="checkbox" name="types[]" value="<?php echo $type["id"]; ?>">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <center><span class="description"><?php echo $type["name"]; ?></span></center>
                        </div>
                    <?php } ?>
                </div>
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
                <div class="price-range">
                    <input id="Slider2" type="slider" name="price" value="<?php echo $min ?>;<?php echo $max ?>" />
                </div>
                <span class="help-block" style="margin-top: 20px;">Cenovni razpon</span>
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
        $min = <?php echo $min ?>;
        $max = <?php echo $max ?>;
        jQuery("#Slider2").slider
                ({
                    from: $min,
                    to: $max,
                    scale: [$min, $max],
                    limits: true,
                    step: 10,
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
<script type="text/javascript">
    $(function () {
        $('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function () {
            if ($(this).hasClass("selected")) {
                $(this).removeClass('selected');
                $(this).find('input[type="checkbox"]').prop("checked", false);
            } else {
                $(this).addClass('selected');
                $(this).find('input[type="checkbox"]').prop("checked", true);
            }
        });
    });
</script>
<style>
    .thumbnail {
        position:relative;
        overflow:hidden;
    }

    .caption {
        position:absolute;
        top:0;
        right:0;
        background:rgba(71, 143, 209, 0.90);
        width:100%;
        height:100%;
        padding:2%;
        display: none;
        text-align:center;
        color:#fff !important;
        z-index:2;
    }

    div.clear
    {
        clear: both;
    }

    div.product-chooser.disabled div.product-chooser-item
    {
        zoom: 1;
        filter: alpha(opacity=60);
        opacity: 0.6;
        cursor: default;
    }

    div.product-chooser div.product-chooser-item{
        padding: 11px;
        cursor: pointer;
        position: relative;
        border: 1px solid #efefef;
        margin-bottom: 5px;
        margin-left: 5px;
        margin-right: 5px;
    }

    div.product-chooser div.product-chooser-item.selected{
        border: 4px solid #428bca;
        background: #fff;
        padding: 8px;
        filter: alpha(opacity=100);
        opacity: 1;
    }

    div.product-chooser div.product-chooser-item img{
        padding: 0;
    }

    div.product-chooser div.product-chooser-item span.title{
        display: block;
        margin: 10px 0 5px 0;
        font-weight: bold;
        font-size: 12px;
    }

    div.product-chooser div.product-chooser-item span.description{
        font-size: 14px;
    }

    div.product-chooser div.product-chooser-item input{
        position: absolute;
        left: 0;
        top: 0;
        visibility:hidden;
    }
</style>
<?php include_once 'footer.php'; ?>