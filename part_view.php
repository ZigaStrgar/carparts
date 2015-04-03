<a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>"><img src="<?php echo $part["image"] ?>" alt="<?= $part["pname"]; ?>" class="img-responsive"></a>
<?php if ($part["new"] == 1) { ?>
    <figure class="ribbon">NOVO</figure>
<?php } ?>
<div class="caption" style="text-align: left;">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>">
                <h4><?= $part["pname"]; ?></h4>
            </a>
        </div>
        <div class="col-md-12 col-xs-12 price">
            <h4><label class="label label-primary" style="font-size: 16px;"><?= price($part["price"]) ?> €</label></h4>
        </div>
    </div>
    <div style="margin-top: 15px; margin-bottom: 35px;" class="text-centers">
        <?php
            echo substr(strip_tags($part["description"], "<p>"), 0, 250);
            if (strlen(strip_tags($part["description"])) > 250) {
                echo "...";
            }
            ?>
    </div>
    <div class="row btn-down">
        <?php if (!empty($user["id"])) { ?>
            <div class="col-sm-6 col-xs-6">
                <a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-list-unordered"></span> <span class="hidden-xs">Podrobnosti</span></a> 
            </div>
            <div class="col-sm-6 col-xs-6">
                <span onclick="addToCart(<?= $part["pid"]; ?>)" class="btn btn-success btn-flat btn-product"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="hidden-xs">V košarico</span></span>
            </div>
        <?php } else { ?>
            <div class="col-sm-12 col-xs-12">
                <a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-list-unordered"></span> <span class="hidden-xs">Podrobnosti</span></a>
            </div>
        <?php } ?>
    </div>
</div>