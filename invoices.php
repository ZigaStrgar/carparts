<?php include_once './header.php'; ?>
<?php
if (empty($_SESSION["user_id"]) && !isset($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
}
$invoices = Db::queryAll("SELECT * FROM invoices i WHERE i.user_id = ?", $_SESSION["user_id"]);
?>
<div class="block-flat col-lg-12 top-danger">
    <h1 class="page-header">Moja naročila</h1>
    <table class="table table-bordered table-striped">
        <tr>
            <th width="150">
                Št. predračuna
            </th>
            <th>
                Izdelki
            </th>
            <th width="150">
                Plačilo
            </th>
            <th width="100">
                Rok plačila
            </th>
            <th width="100">
                Status
            </th>
            <th width="75">
                Akcije
            </th>
        </tr>
        <?php foreach ($invoices as $invoice) { ?>
            <tr data-invoice-id="<?= $invoice["id"]; ?>">
                <td>
                    <?php echo date("y", strtotime($invoice["order_date"])) . "-" . $_SESSION["user_id"] . "-" . $invoice["id"]; ?>
                </td>
                <td>
                    <?php
                    $parts = Db::queryAll("SELECT *, ci.price AS cena, ci.pieces AS ordered FROM parts_invoices ci INNER JOIN parts p ON p.id = ci.part_id WHERE ci.invoice_id = ?", $invoice["id"]);
                    $total = 0;
                    $name = array();
                    foreach ($parts as $part) {
                        $name[] .= $part["name"];
                        $total = $total + $part["cena"] * $part["ordered"];
                    }
                    //$name = substr($name, 0, strlen($name)-1);
                    echo implode(", ", $name);
                    ?>
                </td>
                <td>
                    <?php echo price($total) . " €"; ?>
                </td>
                <td>
                    <?php echo date("d. m. Y", strtotime($invoice["due_date"])); ?>
                </td>
                <td>
                    <?php
                    switch ($invoice["status"]) {
                        case 0:
                        case 1:
                            echo "<span class='label label-warning rem" . $invoice["id"] . "'>Oddano</span>";
                            break;
                        case 2:
                            echo "<span class='label label-success'>Plačano</span>";
                            break;
                        case 3:
                            echo "<span class='label label-primary'>Čakanje na paket</span>";
                            break;
                        case 4:
                            echo "<span class='label label-primary'>Odposlano</span>";
                            break;
                        case 5:
                            echo "<span class='label label-success'>Zaključeno</span>";
                            break;
                        case 6:
                            echo "<span class='label label-danger'>Zapadlo</span>";
                            break;
                        case 7:
                            echo "<span class='label label-danger'>Preklicano</span>";
                            break;
                        default:
                            echo "<span class='label label-default'>Neznano</span>";
                            break;
                    }
                    ?>
                </td>
                <td class="text-right">
                    <a class="pull-left" href="./invoice/<?php echo $invoice["id"]; ?>"><i class="glyphicon glyphicon-list"></i></a>
                    <?php
                    if ($invoice["status"] < 2) {
                        ?>
                        <span class="color-danger" onClick="cancleInvoice(<?= $invoice["id"]; ?>)" style="cursor: pointer;"><i class="icon icon-remove"></i></span>
                            <?php
                        }
                        ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<script async>
    function cancleInvoice(id) {
        $.ajax({
            url: "http://<?= URL; ?>/changeInvoice.php",
            type: "POST",
            data: {val: 7, id: id},
            success: function (cb) {
                cb = $.trim(cb);
                cb = cb.split("|");
                if (cb[0] === "success") {
                    $(".rem" + id).removeClass("label-warning");
                    $(".rem" + id).addClass("label-danger");
                    $(".rem" + id).text("Preklicano");
                    alertify.success("Predračun uspešno preklican");
                } else if (cb[0] === "error") {
                    alertify.error(cb[1]);
                }
            }
        });
    }
</script>
<?php include_once './footer.php'; ?>