<a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>"><img src="<?php echo $part["image"] ?>" alt="<?= $part["pname"]; ?>" class="img-responsive"></a>
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