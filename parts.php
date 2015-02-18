<?php include_once 'header.php'; ?>
<?php
//Št. delov v bazi
$count = Db::query("SELECT * FROM parts WHERE deleted = 0");
//Število delov na stran
$perPage = 30;
//Št. vseh možnih strani - zaokroži navzgor
$pages = ceil($count / $perPage);
//Trenutna stran
$page = (int) $_GET["page"];
if ($page > $pages) {
    $page = $pages;
}
if ($page < 1) {
    $page = 1;
}
//Prvi limit - spodnja meja
$first = ($page - 1) * $perPage;
//Sestavljanje LIMITA za SELECT
$limit = "LIMIT $first, $perPage";
//Nastavljanje razvrstitve
if ($_SESSION["order_by"] != (int) $_POST["order"]) {
    if (!empty($_POST["order"])) {
        $_SESSION["order_by"] = (int) $_POST["order"];
    }
}
//Razvrsti po
if (!isset($_SESSION["order_by"])) {
    $order = "ORDER BY p.id DESC";
} else {
    switch ((int) $_SESSION["order_by"]) {
        case 1:
            $order = "ORDER BY p.id DESC";
            break;
        case 2:
            $order = "ORDER BY p.id ASC";
            break;
        case 3:
            $order = "ORDER BY p.price DESC";
            break;
        case 4:
            $order = "ORDER BY p.price ASC";
            break;
        default:
            $order = "ORDER BY p.id DESC";
            break;
    }
}
$parts = Db::queryAll("SELECT *, p.name AS partName, p.id AS part_id FROM parts p WHERE p.deleted = 0 $order $limit");
?>
<div class="block-flat col-lg-12 top-warning">
    <?php if ($count != 0) { ?>
        <div class="page-header">
            <h1>Deli</h1>
            <form id="order" action="http://<?php echo URL . "/parts/page/$page"; ?>" method="POST">
                <select name="order" class="pull-right dropdown-header dropdown" style="margin-top: -30px;">
                    <option value="1" <?php
                    if (empty($_SESSION["order_by"]) || $_SESSION["order_by"] == 1) {
                        echo "selected='selected'";
                    }
                    ?>>
                        Mlajši naprej
                    </option>
                    <option value="2" <?php
                    if ($_SESSION["order_by"] == 2) {
                        echo "selected='selected'";
                    }
                    ?>>
                        Starejši naprej
                    </option>
                    <option value="3" <?php
                    if ($_SESSION["order_by"] == 3) {
                        echo "selected='selected'";
                    }
                    ?>>
                        Dražji naprej
                    </option>
                    <option value="4" <?php
                    if ($_SESSION["order_by"] == 4) {
                        echo "selected='selected'";
                    }
                    ?>>
                        Cenejši naprej
                    </option>
                </select>
            </form>
            <div class="clear"></div>
        </div>
        <?php foreach ($parts as $part) { ?>
            <div class="col-sm-6 col-xs-6 col-lg-4 col-md-4">
                <div class="thumbnail" >
                    <div class="equal">
                        <img src="<?php echo $part["image"] ?>" alt="<?= $part["name"]; ?>" class="img-responsive">
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
        <div class="clear"></div>
        <nav class="pagination-centered">
            <ul class="pagination">
                <?php if ($page != 1 && $pages > 1) { ?>
                    <li><a href="http://<?php echo URL; ?>/parts/page/1"><i class="icon icon-angle-double-left"></i><span class="sr-only">Prva stran</span></a></li>
                <?php } ?>
                <?php if ($page > 1) { ?>
                    <li><a href="http://<?php echo URL; ?>/parts/page/<?php echo $page - 1; ?>"><span aria-hidden="true" class="icon icon-angle-left"></span><span class="sr-only">Prejšna stran</span></a></li>
                <?php } ?>
                <?php for ($i = $page - 3; $i < $page; $i++) { ?>
                    <?php if ($i > 0) { ?>
                        <li><a href="http://<?php echo URL; ?>/parts/page/<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php } ?>
                <?php } ?>
                <li class="active"><a><?php echo $page; ?></a></li>
                <?php for ($i = $page + 1; $i <= $pages; $i++) { ?>
                    <li><a href="http://<?php echo URL; ?>/parts/page/<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php
                    if ($i >= $page + 2) {
                        break;
                    }
                    ?>
                <?php } ?>
                <?php if ($page + 1 <= $pages) { ?>
                    <li><a href="http://<?php echo URL; ?>/parts/page/<?php echo $page + 1; ?>"><span aria-hidden="true" class="icon icon-angle-right"></span><span class="sr-only">Naslednja stran</span></a></li>
                <?php } ?>
                <?php if ($page != $pages && $page < $pages) { ?>
                    <li><a href="http://<?php echo URL; ?>/parts/page/<?php echo $pages; ?>"><i class="icon icon-angle-double-right"></i><span class="sr-only">Zadnja stran</span></a></li>
                <?php } ?>
            </ul>
        </nav>
    <?php } else { ?>
        <h2 class="text-center">V bazi ni avtodelov!</h2>
    <?php } ?>
</div>
<script>
    $(document).on("change", "select[name=order]", function () {
        $("form#order").submit();
    });

    $(document).ready(function () {
        $("select[name=order]").selectBoxIt();
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