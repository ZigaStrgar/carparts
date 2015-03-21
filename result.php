<?php include_once 'header.php'; ?>
<?php
//TIPI
if (!empty($_POST["types"])) {
    foreach ($_POST["types"] as $type) {
        $types .= "$type,";
    }
    $types = substr($types, 0, strlen($types) - 1);
} else {
    $types = "1,2,3,4,5,6,7";
}
//Podrobnosti avtomobila
$model = $_POST["model"][0];
$type = strtolower(cleanString($_POST["type"]));
if (!empty($_POST["letnik"])) {
    $year = (int) $_POST["letnik"];
}
$brand = (int) $_POST["brand"];
//CENA
$price = Db::queryOne("SELECT MAX(price) AS max, MIN(price) AS min FROM parts WHERE deleted = 0");
$min_real = round($price["min"], -1) - 10;
if ($min_real < 0) {
    $min_real = 0;
}
$max_real = round($price["max"], -1) + 10;
//Podatki dela
$number = cleanString($_POST["number"]);
$partName = strtolower(cleanString($_POST["partname"]));
$category = (int) $_POST["category"];
if (!empty($_POST["price"])) {
    $price = $_POST["price"];
    $price = explode(";", $price);
    $min = $price[0];
    $max = $price[1];
} else {
//MINIMUM
    $min = round($price["min"], -1) - 10;
    if ($min < 0) { //NE SME BIT -
        $min = 0;
    }
//MAXIMUM
    $max = round($price["max"], -1) + 10;
}
//"zgradi" stavek za iskanje v bazi
if (!empty($min) && !empty($max)) {
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id INNER JOIN models m ON m.id = mp.model_id WHERE p.deleted = 0 AND p.price >= $min AND p.price <= $max ";
} else {
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id INNER JOIN models m ON m.id = mp.model_id WHERE p.deleted = 0 ";
}
//Stavku doda tip avtomobila
if (!empty($types) && !empty($_POST["types"])) {
    $searchQuery .= "AND p.type_id IN ($types)";
}
//Stavku doda model avtomobila
if (!empty($model)) {
    $searchQuery .= " AND mp.model_id = $model";
}
//Stavku doda leto avtomobila
if (!empty($year)) {
    $searchQuery .= " AND pm.year = $year";
}
//Stavku doda tip modela
if (!empty($type)) {
    $searchQuery .= " AND lower(pm.type) LIKE '%$type%'";
}
//Stavku doda ime dela
if (!empty($partName)) {
    $searchQuery .= " AND lower(p.name) LIKE '%$partName%'";
}
//Stavku doda kategorijo dela
if (!empty($category)) {
    $cats = getSubcategories($category);
    $category .= "," . implode(",", $cats);
    $searchQuery .= " AND p.category_id IN ($category)";
}
//Stavku doda znamko avtomobila
if (!empty($brand)) {
    $searchQuery .= " AND m.brand_id = $brand";
}
//Išče po kategoriji
if (!empty($_GET["category"])) {
    $category = (int) cleanString($_GET["category"]);
    $cats = getSubcategories($category);
    if (!empty($cats)) {
        $category .= "," . implode(",", $cats);
    }
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p WHERE p.deleted = 0 AND p.category_id IN ($category)";
}
//Išče po modelu
if (!empty($_GET["model"])) {
    $model = (int) cleanString($_GET["model"]);
    $brand = getBrand($model);
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE p.deleted = 0 AND mp.model_id = $model";
}
//Išče po znamki
if (!empty($_GET["brand"])) {
    $brand = (int) cleanString($_GET["brand"]);
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id INNER JOIN models m ON m.id = mp.model_id WHERE p.deleted = 0 AND m.brand_id = $brand";
}
//Išče po tipu modela
if (!empty($_GET["type"])) {
    $type = strtolower(cleanString($_GET["type"]));
    $searchQuery = "SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE p.deleted = 0 AND lower(mp.type) LIKE '%$type%'";
}
//GROUP BY (odstrani podvajanje podatkov/delov/rezultatov)
$searchQuery .= " GROUP BY p.id";
$results = Db::queryAll($searchQuery);
interest("", $category, $_SESSION["user_id"], $model, $brand);
if (!empty($number)) {
//Poglej za kataloško številko
    $resultNumber = Db::queryAll("SELECT *, id AS pid FROM parts WHERE deleted = 0 AND number = ?", $number);
}
//ZA PONOVNO ISKANJE
//TIPI
$types_out = Db::queryAll("SELECT * FROM types ORDER BY name ASC");
$types_check = explode(",", $types);
//ZNAMKE
$brands = Db::queryAll("SELECT * FROM brands WHERE visible = 1 ORDER BY name ASC");
//KATEGORIJE
$categories = Db::queryAll("SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC");
?>
<h1 class="hide">Rezultati iskanja</h1>
<div class="block-flat top-info col-lg-12">
    <h1 class="page-header">Iskanje<span class="btn btn-default btn-flat pull-right show-button">Iskanje <i class="icon icon-angle-down" id="icon"></i></span></h1>
    <div class="col-lg-12 show-content" style="display: none;">
        <form role="form" action="" method="POST">
            <h3 class="page-header">Tip avtomobila</h3>
            <span class="help-block">Če ne izbereš nič, bo iskalo med vsemi tipi avtomobilov!</span>
            <div class="row">
                <div class="col-lg-12 form-inline">
                    <div class="product-chooser">
                        <?php foreach ($types_out as $type_type) { ?>
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 185px; height: 120px;">
                                <div class="product-chooser-item <?php
                                if (in_array($type_type["id"], $types_check)) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><img src="http://<?php echo URL; ?>/img/<?php echo strtolower($type_type["name"]) ?>.png" alt="<?php echo $type_type["name"]; ?> image" width="100"/></center>
                                    <div class="col-lg-12">
                                        <input type="checkbox" <?php
                                        if (in_array($type_type["id"], $types_check)) {
                                            echo "checked";
                                        }
                                        ?> name="types[]" value="<?php echo $type_type["id"]; ?>">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <center><span class="description"><?php echo $type_type["name"]; ?></span></center>
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
                        <select id="0" name="brand" placeholder="Znamka" class="form-control aucp" autocorrect="off" autocomplete="off">
                            <option value="0" selected="selected"></option>
                            <?php foreach ($brands as $brand_s) { ?>
                                <option <?php
                                if ($brand == $brand_s["id"]) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $brand_s["id"]; ?>"><?php echo $brand_s["name"]; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div id="model0" class="col-md-6">
                    <div class="load-bar loadermodel0">
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
                        <input type="text" name="type" value="<?php echo $type ?>" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Letnik</span>
                        <input type="text" name="letnik" pattern="[0-9]{4}" value="<?php echo $year; ?>" title="Primer: 2014" class="form-control" />
                    </div>
                </div>    
            </div>
            <h3 class="page-header">Podatki o delu</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Kataloška številka</span>
                        <input type="text" name="number" value="<?php echo $number; ?>" class="form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">Ime dela</span>
                        <input type="text" name="partname" value="<?php echo $partName; ?>" class="form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="visible-sm visible-xs"><br /></div>
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
</div>
<div class="col-lg-12 block-flat top-info">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Rezultati iskanja</h1>
            <?php if (!empty($number)) { ?>
                <h3 class="page-header">Rezultati kataloške številke</h3>
                <?php if (count($resultNumber) > 0) { ?>
                    <?php foreach ($resultNumber as $part) { ?>
                        <div class="col-sm-6 col-xs-6 col-lg-4 col-md-4">
                            <div class="thumbnail">
                                <div class="equal">
                                    <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>"><img src="<?php echo $part["image"] ?>" alt="<?= $part["name"]; ?>" class="img-responsive"></a>
                                    <?php if ($part["new"] == 1) { ?>
                                        <figure class="ribbon">NOVO</figure>
                                    <?php } ?>
                                    <div class="caption">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-6">
                                                <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>">
                                                    <h4><?= $part["name"]; ?></h4>
                                                </a>
                                            </div>
                                            <div class="col-md-6 col-xs-6 price">
                                                <h4><label class="text-primary"><?= price($part["price"]) ?> €</label></h4>
                                            </div>
                                        </div>
                                        <p><?php
                                            echo substr(strip_tags($part["description"]), 0, 100);
                                            if (strlen(strip_tags($part["description"])) > 100) {
                                                echo "...";
                                            }
                                            ?></p>
                                        <div class="row btn-down">
                                            <?php if (!empty($user["id"])) { ?>
                                                <div class="col-sm-6 col-xs-6">
                                                    <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-list-unordered"></span> <span class="hidden-xs">Podrobnosti</span></a> 
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <span onclick="addToCart(<?= $part["id"]; ?>)" class="btn btn-success btn-flat btn-product"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="hidden-xs">V košarico</span></span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-12 col-xs-12">
                                                    <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-list-unordered"></span> <span class="hidden-xs">Podrobnosti</span></a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <center><h4>Dela s takšno kataloško številko ni v podatkovni bazi!</h4></center>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-12">
            <?php if (!empty($number)) { ?>
                <h3 class="page-header">Rezultati iskanja glede na preostale kriterije</h3>
            <?php } ?>
            <?php if (count($results) > 0) { ?>
                <?php foreach ($results as $part) { ?>
                    <div class="col-sm-6 col-xs-6 col-lg-4 col-md-4">
                        <div class="thumbnail">
                            <div class="equal">
                                <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>"><img src="<?php echo $part["image"] ?>" alt="<?= $part["name"]; ?>" class="img-responsive"></a>
                                <?php if ($part["new"] == 1) { ?>
                                    <figure class="ribbon">NOVO</figure>
                                <?php } ?>
                                <div class="caption">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>">
                                                <h4><?= $part["name"]; ?></h4>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-xs-6 price">
                                            <h4><label class="text-primary"><?= price($part["price"]) ?> €</label></h4>
                                        </div>
                                    </div>
                                    <p><?php
                                        echo substr(strip_tags($part["description"]), 0, 100);
                                        if (strlen(strip_tags($part["description"])) > 100) {
                                            echo "...";
                                        }
                                        ?></p>
                                    <div class="row btn-down">
                                        <?php if (!empty($user["id"])) { ?>
                                            <div class="col-sm-6 col-xs-6">
                                                <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-list-unordered"></span> <span class="hidden-xs">Podrobnosti</span></a> 
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <span onclick="addToCart(<?= $part["id"]; ?>)" class="btn btn-success btn-flat btn-product"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="hidden-xs">V košarico</span></span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-sm-12 col-xs-12">
                                                <a href="http://<?= URL; ?>/part/<?= $part["id"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-list-unordered"></span> <span class="hidden-xs">Podrobnosti</span></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <center><h4>Brez uspeha! Dela, ki bi ustrezal vnešenim podatkom ni v bazi!</h4></center>
            <?php } ?>
        </div>
    </div>
</div>
<?php
if (empty($model)) {
    $model = 0;
}
?>
<?php if (!empty($brand)) { ?>
    <script>
        $(document).ready(function () {
            getModels(<?php echo $brand; ?>, 0, <?php echo $model; ?>);
        })
    </script>
<?php } ?>
<script async>
    $(document).on("click", ".show-button", function () {
        if ($(".show-button").children().hasClass("icon-angle-down")) {
            $(".show-content").show();
            $("#icon").removeClass("icon-angle-down");
            $("#icon").addClass("icon-angle-up");
        } else {
            $(".show-content").fadeOut();
            $("#icon").removeClass("icon-angle-up");
            $("#icon").addClass("icon-angle-down");
        }
    });

    $(window).load(function () {
        $min_real = <?php echo $min_real ?>;
        $max_real = <?php echo $max_real ?>;
        jQuery("input[type=slider]").slider
                ({
                    from: $min_real,
                    to: $max_real,
                    scale: [$min_real, $max_real],
                    limits: true,
                    step: 10,
                    dimension: '€'
                });
        $('.aucp').selectToAutocomplete();
        setInterval(function () {
            $width = $("select").width() - 52;
            $(".ui-autocomplete").css({"list-style-type": "none", "width": $width});
        }, 100);
    });

    $(document).on("change", "select[name=brand]", function () {
        $id = $(this).attr("id");
        getModels($(this).val(), $id, 0);
    });

    function getModels(id, place, model) {
        $.ajax({
            url: "http://<?= URL; ?>/fetchModels.php",
            type: "POST",
            data: {id: id, req: "0", model: model},
            beforeSend: function () {
                $(".loadermodel" + place).css({display: "block"});
            },
            success: function (cb) {
                $(".loadermodel" + place).hide();
                $("#model" + place).html(cb);
            }
        });
    }

    function fetchCategories(id) {
        $.ajax({
            url: "http://<?= URL; ?>/fetchCategories.php",
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
        setInterval(function () {
            var maxheight = 0;
            $('.equal').each(function () {
                if ($(this).height() > maxheight) {
                    maxheight = $(this).height();
                }
            });
            $('.equal').parent().height(maxheight);
        });
    });

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
    
    function addToCart(part) {
        $.ajax({
            url: "http://<?= URL; ?>/addToCart.php",
            type: "POST",
            data: {part: part},
            success: function (cb) {
                cb = $.trim(cb);
                cb = cb.split("|");
                if (cb[0] === "error") {
                    alertify.error(cb[1]);
                }
                if (cb[0] === "success") {
                    alertify.success(cb[1]);
                    $("#cartNum").text(cb[2]);
                }
            }
        });
    }
</script>
<?php include_once 'footer.php'; ?>