<?php include_once './header.php'; ?>
<?php
if ((empty($_SESSION["user_id"]) && !isset($_SESSION["user_id"])) && $_SESSION["email"] != "ziga_strgar@hotmail.com") {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
}
$invoices = Db::queryAll("SELECT * FROM invoices");
?>
<div class="block-flat col-lg-12 top-danger">
    <div class="page-header">
        <h1 class="pull-left">Administracija naročil</h1>
        <span id="advanced-show" class="btn btn-default pull-right" style="margin-top: 20px;">Napredno <i id="advanced-icon" class="icon icon-angle-down"></i></span>
        <div class="clear"></div>
        <div id="advanced" style="display: none;">
            <div class="col-lg-6 col-sm-12">
                Prikaži:
                <select name="filterstatus" class="form-control">
                    <option value="10">Vse</option>
                    <option value="1">Oddane</option>
                    <option value="2">Plačane</option>
                    <option value="3">Čakaje na paket</option>
                    <option value="4">Odposlane</option>
                    <option value="5">Zaključene</option>
                    <option value="6">Zapadle</option>
                    <option value="7">Preklicane</option>
                </select>
            </div>
            <div class="col-lg-6 col-sm-12">
                Iskanje
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-search-1"></i></span>
                    <input type="text" name="filternumber" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <table class="table table-striped table-hover table-bordered">
        <tr class="dont">
            <th>
                # predračuna
            </th>
            <th>
                Skupno plačilo
            </th>
            <th>
                Status
            </th>
            <th width="75">
                Akcije
            </th>
        </tr>
        <?php foreach ($invoices as $invoice) { ?>
            <tr class="invoice<?php echo $invoice["id"]; ?>" data-invoice-status="<?php echo $invoice["status"]; ?>" data-invoice-num="<?php echo date("y", strtotime($invoice["order_date"])) . "-" . $invoice["user_id"] . "-" . $invoice["id"]; ?>">
                <td>
                    <?php echo date("y", strtotime($invoice["order_date"])) . "-" . $invoice["user_id"] . "-" . $invoice["id"]; ?>
                </td>
                <td>
                    <?php $parts = Db::queryAll("SELECT ci.pieces, ci.price FROM cart_invoices ci INNER JOIN parts p ON p.id = ci.part_id WHERE invoice_id = ?", $invoice["id"]); ?>
                    <?php
                    $total = 0;
                    foreach ($parts as $part) {
                        $total = $total + round($part["price"], 2) * $part["pieces"];
                    }
                    ?>
                    <?php echo price($total); ?> €
                </td>
                <td>
                    <select name="status" class="form-control" data-for-invoice="<?php echo $invoice["id"]; ?>">
                        <option <?php
                        if ($invoice["status"] < 2) {
                            echo "selected";
                        }
                        ?> value="1">Oddano</option>
                        <option <?php
                        if ($invoice["status"] == 2) {
                            echo "selected";
                        }
                        ?> value="2">Plačano</option>
                        <option <?php
                        if ($invoice["status"] == 3) {
                            echo "selected";
                        }
                        ?> value="3">Čakanje na paket</option>
                        <option <?php
                        if ($invoice["status"] == 4) {
                            echo "selected";
                        }
                        ?> value="4">Odposlano</option>
                        <option <?php
                        if ($invoice["status"] == 5) {
                            echo "selected";
                        }
                        ?> value="5">Zaključeno</option>
                        <option <?php
                        if ($invoice["status"] == 6) {
                            echo "selected";
                        }
                        ?> value="6">Zapadlo</option>
                        <option <?php
                        if ($invoice["status"] == 7) {
                            echo "selected";
                        }
                        ?> value="7">Preklicano</option>
                    </select>
                </td>
                <td class="text-right">
                    <a class="pull-left" href="./invoice/<?php echo $invoice["id"]; ?>"><i class="glyphicon glyphicon-list"></i></a>
                    <span class="color-danger"><i class="icon icon-remove"></i></span>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<script async>
    $(document).on("change", "select[name=status]", function () {
        $val = $(this).val();
        $for = $(this).attr("data-for-invoice");
        $.ajax({
            url: "changeInvoice.php",
            type: "POST",
            data: {id: $for, val: $val},
            success: function (cb) {
                cb = $.trim(cb);
                cb = cb.split("|");
                if (cb[0] === "success") {
                    alertify.success(cb[1]);
                } else if (cb[0] === "error") {
                    alertify.error(cb[1]);
                }
            }
        })
    });

    $(document).on("change", "select[name=filterstatus]", function () {
        filter();
    });

    $(document).on("keyup", "input[name=filternumber]", function () {
        filter();
    });

    function filter() {
        $status = $("select[name=filterstatus]").val();
        $num = $("input[name=filternumber]").val();
        $("tr:not(.dont)").hide();
        if ($num !== "") {
            if ($status === '10') {
                $("tr[data-invoice-num^=" + $num + "]").show();
            } else {
                $("tr[data-invoice-status=" + $status + "][data-invoice-num^=" + $num + "]").show();
            }
        } else {
            if ($status === '10') {
                $("tr").show();
            } else {
                $("tr[data-invoice-status=" + $status + "]").show();
            }
        }
    }

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
<?php include_once './footer.php'; ?>