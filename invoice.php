<?php include_once './header.php'; ?>
<?php
$id = (int) $_GET["id"];
if (empty($_SESSION["user_id"]) && !isset($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
}
if (!my_invoice($id, $_SESSION["user_id"])) {
    $_SESSION["notify"] = "error|To ni vaš predračun ali pa ta predračun ne obstaja!";
    header("Location: index.php");
    die();
}
$invoice = Db::queryOne("SELECT * FROM invoices WHERE id = ?", $id);
$parts = Db::queryAll("SELECT *, ci.pieces AS ordered, ci.price AS cena FROM cart_invoices ci INNER JOIN parts p ON p.id = ci.part_id WHERE invoice_id = ?", $invoice["id"]);
?>
<div class="block-flat col-lg-12 top-danger">
    <h1 class="page-header">Predračun <?php echo date("y", strtotime($invoice["order_date"])) . "-" . $_SESSION["user_id"] . "-" . $invoice["id"]; ?></h1>
    <table class="table table-bordered table-striped table-responsive">
        <tr>
            <th>
                Izdelek
            </th>
            <th>
                Količina
            </th>
            <th class="text-right" width="100">
                Cena
            </th>
        </tr>
        <?php foreach ($parts as $part) { ?>
            <tr>
                <td>
                    <?php echo $part["name"]; ?>
                </td>
                <td>
                    <?php echo $part["ordered"]; ?>
                </td>
                <td class="text-right">
                    <?php echo price(round($part["cena"], 2)); ?> €
                </td>
            </tr>
            <?php $total = $total + $part["ordered"] * round($part["cena"], 2); ?>
        <?php } ?>
        <tr>
            <td colspan="3" class="text-right">
                <h4><strong>Skupaj:</strong> <?php echo price($total); ?> €</h4>
            </td>
        </tr>
    </table>
</div>
<?php include_once './footer.php'; ?>