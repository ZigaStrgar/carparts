<?php include_once 'header.php'; ?>
<?php
$queryConut = "SELECT COUNT(id) FROM parts";
$resultCount = mysqli_query($link, $queryConut);
$count = mysqli_fetch_array($resultCount);
$count = $count["COUNT(id)"];
$perPage = 5;
$pages = ceil($count / $perPage);
$page = (int) $_GET["page"];
if ($page > $pages) {
    $page = $pages;
}
if ($page < 1) {
    $page = 1;
}
$first = ($page - 1) * $perPage;
$limit = "LIMIT $first, $perPage";
if (empty((int) $_POST["order"])) {
    $order = "ORDER BY p.id DESC";
} else {
    switch ((int) $_POST["order"]) {
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
$queryParts = "SELECT *, p.name AS partName, p.id AS part_id FROM parts p INNER JOIN models m ON m.id = p.model_id INNER JOIN brands b ON b.id = m.brand_id INNER JOIN types t ON t.id = p.type_id $order  $limit";
$resultParts = mysqli_query($link, $queryParts);
?>
<div class="block-flat col-lg-12">
    <div class="page-header">
        <h3>Deli</h3>
        <form id="order" action="http://<?php echo URL . "/parts/page/$page"; ?>" method="POST">
            <select name="order" class="pull-right dropdown-header dropdown" style="margin-top: -30px;">
                <option value="1" <?php if(empty($_POST["order"]) || $_POST["order"] == 1) { echo "selected='selected'"; } ?>>
                    Mlajši naprej
                </option>
                <option value="2" <?php if($_POST["order"] == 2) { echo "selected='selected'"; } ?>>
                    Starejši naprej
                </option>
                <option value="3" <?php if($_POST["order"] == 3) { echo "selected='selected'"; } ?>>
                    Dražji naprej
                </option>
                <option value="4" <?php if($_POST["order"] == 4) { echo "selected='selected'"; } ?>>
                    Cenejši naprej
                </option>
            </select>
        </form>
    </div>
    <?php while ($part = mysqli_fetch_array($resultParts)) { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-4">
                    <a href="/part/<?php echo $part["part_id"]; ?>">
                        <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                    </a>
                </div>
                <div class="col-lg-8">
                    <a href="/part/<?php echo $part["part_id"]; ?>">
                        <h4><?php echo $part["partName"]; ?></h4>
                    </a>
                    <p><?php echo $part["description"]; ?></p>
                </div>
            </div>
        </div>
        <hr />
        <br />
    <?php } ?>
    <nav class="pagination-centered">
        <ul class="pagination">
            <?php if ($page > 1) { ?>
                <li><a href="<?php echo $page - 1; ?>"><span aria-hidden="true">&laquo;</span><span class="sr-only">Prejšna stran</span></a></li>
            <?php } ?>
            <?php for ($i = $page - 3; $i < $page; $i++) { ?>
                <?php if ($i > 0) { ?>
                    <li><a href="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            <li class="active"><a><?php echo $page; ?></a></li>
            <?php for ($i = $page + 1; $i <= $pages; $i++) { ?>
                <li><a href="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php
                if ($i >= $page + 2) {
                    break;
                }
                ?>
            <?php } ?>
            <?php if ($page != $pages) { ?>
                <li><a href="<?php echo $page + 1; ?>"><span aria-hidden="true">&raquo;</span><span class="sr-only">Naslednja stran</span></a></li>
                <?php } ?>
        </ul>
    </nav>
</div>
<script>
    $(document).on("change", "select[name=order]", function () {
        $("form#order").submit();
    });
</script>
<?php include_once 'footer.php'; ?>