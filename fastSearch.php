<?php include_once 'header.php'; ?>
<?php
$string = strtolower(cleanString($_REQUEST["query"]));
//IS BRAND
$brand = Db::queryAll("SELECT * FROM brands WHERE lower(name) LIKE '%$string%'");
if (count($brand) > 0) {
    $models = getModels($brand[0]["id"]);
}
//IS MODEL
$model = Db::queryOne("SELECT * FROM models WHERE lower(name) LIKE '%$string%'");
if (count($model) > 0) {
    if (empty($models)) {
        $models .= $model["id"];
    } else {
        $models .= "," . $model["id"];
    }
}
//USUAL SEARCH QUERY
$searchQuery = "SELECT *, p.id AS pid FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE lower(p.number) LIKE '$string' OR lower(p.name) LIKE '%$string%' OR lower(p.description) LIKE '%$string%' OR lower(mp.type) LIKE '%$string%' OR mp.year = '$string'";
if (!empty($models)) {
    $searchQuery .= " OR mp.model_id IN ($models)";
}
$searchQuery .= " GROUP BY p.id";
$results = Db::queryAll($searchQuery);
?>
<div class="block-flat col-lg-12">
    <h1 class="page-header">Iskalni niz: <?php echo strip_tags($string); ?></h1>
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
        <center><h4><b>Brez uspeha</b>! Iskalni niz ni našel rezultatov!</h4></center>
    <?php } ?>
        <div class="clear"></div>
</div>
<script>
    $(document).ready(function () {
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
