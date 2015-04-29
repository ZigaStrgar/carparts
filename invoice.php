<?php include_once './header.php'; ?>
<?php
$id = (int)$_GET["id"];
if (empty($_SESSION["user_id"]) && !isset($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $path;
    header("Location: ../login.php");
    die();
}
if (!invoice_exist($id)) {
    $_SESSION["notify"] = "error|To ni vaš predračun ali pa ta predračun ne obstaja!";
    header("Location: ../index.php");
    die();
}
if (!my_invoice($id, $_SESSION["user_id"])) {
    if ($user["email"] != "ziga_strgar@hotmail.com") {
        $_SESSION["notify"] = "error|To ni vaš predračun ali pa ta predračun ne obstaja!";
        header("Location: ../index.php");
        die();
    }
}
$invoice = Db::queryOne("SELECT * FROM invoices WHERE id = ?", $id);
$owner = Db::queryOne("SELECT * FROM users WHERE id = ?", $invoice["user_id"]);
$parts = Db::queryAll("SELECT *, ci.pieces AS ordered, ci.price AS cena, p.id AS pid FROM parts_invoices ci INNER JOIN parts p ON p.id = ci.part_id WHERE invoice_id = ?", $invoice["id"]);
?>
    <div class="block-flat col-lg-12 top-danger">
        <h1 class="page-header">
            Predračun <?php echo date("y", strtotime($invoice["order_date"])) . "-" . $owner["id"] . "-" . $invoice["id"]; ?></h1>
        <?php
        if ($user["email"] == "ziga_strgar@hotmail.com") {
            echo "Ime in priimek: ".$owner["name"]. " ".$owner["surname"]."<br />";
            echo "Naslov: ". $owner["location"];
        }
        ?>
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
                        <a href="http://<?= URL; ?>/part/<?= $part["pid"]; ?>" target="_blank"><?php echo $part["name"]; ?></a>
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
        <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-flat btn-primary">Nazaj</a>

        <div class="clear"></div>
    </div>
<?php include_once './footer.php'; ?>