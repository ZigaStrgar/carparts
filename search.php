<?php include_once 'header.php'; ?>
<?php
//TIPI
$types = Db::queryAll("SELECT * FROM types ORDER BY name ASC");
//ZNAMKE
$brands = Db::queryAll("SELECT * FROM brands ORDER BY name ASC");
//KATEGORIJE
$categories = Db::queryAll("SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC");
//CENA
$price = Db::queryOne("SELECT MAX(price) AS max, MIN(price) AS min FROM parts");
//MINIMUM
$min = round($price["min"], -1) - 10;
if ($min < 0) { //NE SME BIT -
    $min = 0;
}
//MAXIMUM
$max = round($price["max"], -1) + 10;
?>
<div class="col-lg-12 block-flat top-info">
    <h1 class="page-header">Iskanje</h1>
    <form action="result.php" method="POST" role="form">
        <h3 class="page-header">Tip avtomobila</h3>
        <span class="help-block">Če ne izbereš nič, bo iskalo med vsemi tipi avtomobilov!</span>
        <div class="row">
            <div class="col-lg-12 form-inline">
                <div class="product-chooser">
                    <?php foreach ($types as $type) { ?>
                        <div class="col-lg-2 col-xs-2 col-md-2" style="width: 185px; height: 100px;">
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
        <h3 class="page-header">Podatki o avtomobilu</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Znamka</span>
                    <select name="brand" placeholder="Znamka" class="form-control aucp">
                        <option value="0" selected="selected"></option>
                        <?php foreach ($brands as $brand) { ?>
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
        <h3 class="page-header">Podatki o delu</h3>
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
        <h4>Kategorija izdelka</h4>
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                    <select name="category" class="form-control">
                        <option selected="selected"></option>
                        <?php foreach ($categories as $category) { ?>
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
        jQuery("input[type=slider]").slider
                ({
                    from: $min,
                    to: $max,
                    scale: [$min, $max],
                    limits: true,
                    step: 10,
                    dimension: ''
                });
        $('.aucp').selectToAutocomplete();
        setInterval(function () {
            $width = $("select").width() - 52;
            $(".ui-autocomplete").css({"list-style-type": "none", "width": $width});
        }, 100);
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
            data: {id: id, req: "0"},
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

    $(document).on("change", "select[name=category]", function () {
        $currentSelected = $(this).val();
        fetchCategories($currentSelected);
    });

    $(document).ready(function () {
        $currentSelected = 0;
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
<?php include_once 'footer.php'; ?>