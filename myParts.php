<?php include_once "./header.php"; ?>
<?php
if (empty($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
    die();
}
$parts = Db::queryAll("SELECT *, name AS pname, id AS pid FROM parts WHERE deleted = 0 AND user_id = ?", $user["id"]);
?>
    <div class="col-lg-12 block-flat top-warning">
        <h1 class="page-header">Moji deli</h1>
        <?php foreach ($parts as $part): ?>
            <div class="col-sm-6 col-xs-12 col-lg-4 col-md-4">
                <div class="thumbnail">
                    <div class="equal">
                        <?php include 'part_view.php'; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="clear"></div>
    </div>
<script>
    $(document).ready(function () {
        setInterval(function () {
            if($(window).width() > 720) {
                var maxheight = 0;
                $('.equal').each(function () {
                    if ($(this).height() > maxheight) {
                        maxheight = $(this).height();
                    }
                });
                $('.equal').parent().height(maxheight);
            } else {
                $(".equal").parent().css({"height" : "100%"});
            }
        }, 100);
    });
</script>
<?php include_once "./footer.php"; ?>