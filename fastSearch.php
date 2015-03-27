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
                        <?php include 'part_view.php'; ?>
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
