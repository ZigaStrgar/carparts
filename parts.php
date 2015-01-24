<?php include_once 'header.php'; ?>
<?php
//Št. delov v bazi
$count = Db::query("SELECT * FROM parts WHERE deleted = 0");
//Število delov na stran
$perPage = 5;
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
            <div class="media">
                <a class="media-left media-middle col-lg-4 col-sm-12" href="http://<?php echo URL; ?>/part/<?php echo $part["part_id"]; ?>">
                    <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                </a>
                <div class="media-body col-lg-8 col-sm-12">
                    <a href="http://<?php echo URL; ?>/part/<?php echo $part["part_id"]; ?>">
                        <h3 class="media-heading"><?php echo $part["partName"]; ?></h3>
                    </a>
                    <?php echo $part["description"]; ?>
                </div>
            </div>
            <hr />
            <br />
        <?php } ?>
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
<!--  DROPDOWN  -->
<script src="http://<?php echo URL; ?>/plugins/dropdown/jquery.selectBoxIt.min.js"></script>
<script>
    $("select[name=order]").selectBoxIt();

    $(document).on("change", "select[name=order]", function () {
        $("form#order").submit();
    });

    $(document).ready(function () {
        $("#advanced").hide();
    });

    $(document).on("click", "#advanced-show", function () {
        if ($("#advanced-icon").hasClass("icon-angle-down")) {
            $("#advanced-icon").removeClass("icon-angle-down");
            $("#advanced-icon").addClass("icon-angle-up");
            $("#advanced").fadeIn();
        } else {
            $("#advanced-icon").removeClass("icon-angle-up");
            $("#advanced-icon").addClass("icon-angle-down");
            $("#advanced").fadeOut();
        }
    });
</script>
<?php include_once 'footer.php'; ?>