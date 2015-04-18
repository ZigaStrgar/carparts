<?php include_once './header.php'; ?>
<?php
if (empty($_SESSION["user_id"]) && !isset($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'matura') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
}
$max = Db::querySingle("SELECT MAX(id) FROM invoices WHERE status = 0 AND user_id = ?", $_SESSION["user_id"]);
if(empty($max)){
    $_SESSION["notify"] = "error|Ni novih naročil!";
    header("Location: parts.php");
}
$cart = Db::queryAll("SELECT *, ci.pieces AS ordered FROM cart_invoices ci INNER JOIN parts p ON p.id = ci.part_id WHERE ci.invoice_id = ?", $max);
?>
<div class="col-lg-12 block-flat top-danger">
    <h1 class="page-header">Končanje nakupa</h1>
    <h3>Naročili ste</h3>
    <table class="table table-responsive table-bordered table-striped table-hover">
        <tr>
            <th>
                Izdelek
            </th>
            <th>
                Kosi
            </th>
        </tr>
    <?php foreach($cart as $offer) { ?>
        <tr>
            <td>
                <?php echo $offer["name"]; ?>
            </td>
            <td>
                <?php echo $offer["ordered"]; ?>
            </td>
        </tr>
    <?php } ?>
        <?php 
        Db::query("DELETE FROM cart WHERE user_id = ?", $_SESSION["user_id"]);
        Db::update("invoices", array("status" => 1), "WHERE id = $max");
        ?>
    </table>
    <h4 class="text-center">Zahvaljujemo se Vam za nakup!</h4>
</div>
<?php include_once './footer.php'; ?>