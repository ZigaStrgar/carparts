<a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>"><img src="<?php echo $part["image"] ?>" alt="<?= $part["pname"]; ?>" class="img-responsive"></a>
<?php if ( $part["pieces"] <= 0 ) { ?>
    <figure class="red-ribbon">NI NA ZALOGI</figure>
<?php } ?>
<?php if ( $part["new"] == 1 && $part["pieces"] > 0 ) { ?>
    <figure class="ribbon">NOVO</figure>
<?php } ?>
<div class="caption" style="text-align: left;">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>">
                <h4><?= $part["pname"]; ?></h4>
            </a>
        </div>
        <div class="col-md-12 col-xs-12">
            <label class="price"><?= price($part["price"]) ?> €</label>
        </div>
    </div>
    <div style="margin-bottom: 40px; margin-top: 5px;" class="text-centers">
        <?php
        echo substr(strip_tags($part["description"], "<p>"), 0, 250);
        if ( strlen(strip_tags($part["description"])) > 250 ) {
            echo "...";
        }
        ?>
    </div>
    <div class="row btn-down">
        <?php if ( ! empty($user["id"]) && ! my_part($part["pid"], $user["id"]) ) { ?>
            <div class="col-sm-6 col-xs-6">
                <a href="//<?= URL; ?>/part/<?= $part["pid"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-read-more"></span>
                    <span class="hidden-xs">Podrobnosti</span></a>
            </div>
            <div class="col-sm-6 col-xs-6">
                <span onclick="addToCart(<?= $part["pid"]; ?>)" class="btn btn-success btn-flat btn-product"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="hidden-xs">V košarico</span></span>
            </div>
        <?php } else { ?>
            <?php if ( my_part($part["pid"], $user["id"]) ) { ?>
                <div class="col-sm-6 col-xs-6">
                    <a href="//<?= URL; ?>/part/<?= $part["pid"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-read-more"></span>
                        <span class="hidden-xs">Podrobnosti</span></a>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <a href="//<?= URL; ?>/editPart/<?= $part["pid"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-pencil"></span>
                        <span class="hidden-xs">Uredi</span></a>
                </div>
            <?php } else { ?>
                <div class="col-sm-12 col-xs-12">
                    <a href="//<?= URL; ?>/part/<?= $part["pid"]; ?>" class="btn btn-primary btn-flat btn-product"><span class="icon icon-read-more"></span>
                        <span class="hidden-xs">Podrobnosti</span></a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>