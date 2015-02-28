<?php include_once './header.php'; ?>
<?php
if (empty($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
}
$error = 0;
$cart_offers = Db::queryAll("SELECT *, s.pieces AS spieces, p.pieces AS parts, s.id AS oid,p.id AS pid FROM cart s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = ?", $_SESSION["user_id"]);
?>
<div class="stepwizard">
    <div class="stepwizard-row">
        <div class="stepwizard-step">
            <button type="button" class="btn btn-primary btn-circle"><i class="icon icon-shopping-cart"></i></button>
            <p>Košarica</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle" disabled="disabled"><i class="icon icon-checklist"></i></button>
            <p>Pregled</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle" disabled="disabled"><i class="icon icon-credit-card-2"></i></button>
            <p>Konec</p>
        </div> 
    </div>
</div>
<div class="block-flat col-lg-12 top-danger">
    <h1 class="page-header">Košarica</h1>
    <table class="table table-bordered table-striped table-hover col-lg-12">
        <?php if (count($cart_offers) > 0) { ?>
            <thead>
                <tr>
                    <th class="col-xs-5">Ime dela</th>
                    <th class="col-xs-3">Št. kosov</th>
                    <th class="col-xs-1">Zaloga</th>
                    <th class="col-xs-2">Cena</th>
                    <th>Brisanje</th>
                </tr>
            </thead>
        <?php } ?>
        <tbody>
            <?php if (Db::query("SELECT *, s.pieces AS spieces, p.pieces AS parts, s.id AS oid FROM cart s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = ?", $_SESSION["user_id"]) > 0) { ?>
                <?php foreach ($cart_offers as $offer) { ?>
                    <tr class="offer<?php echo $offer["oid"]; ?> <?php
                    if ($offer["deleted"] != 0) {
                        echo "danger";
                        $error++;
                    }
                    ?>" <?php
            if ($offer["deleted"] != 0) {
                echo "data-has-error='" . $offer["oid"] . "'";
            }
            ?>>
                        <td><a href="http://<?php echo URL; ?>/part/<?php echo $offer["pid"] ?>" target="_blank"><?php echo $offer["name"]; ?></a></td>
                        <td><input class="form-control" type="text" name="pieces" data-offer-max="<?php echo $offer["parts"] ?>" data-offer-id="<?php echo $offer["oid"] ?>" value="<?php echo $offer["spieces"] ?>" placeholder="Vnesite št. kosov"/></td>
                        <td><?php echo $offer["parts"] ?></td>
                        <td><?php echo price($offer["price"]) ?> €</td>
                        <td><i class="icon icon-remove color-danger pull-right" style="cursor: pointer;" onClick="removeOffer(<?php echo $offer["oid"] ?>)" data-placement="left" data-toggle="popover" data-content="Odstrani iz košarice"></i></td>
                    </tr>
    <?php } ?>
                <tr>
                    <td colspan="5" class="text-right">
                        <h4><b>Skupaj: <span id="price"></span> €</b></h4>
                    </td>
                </tr>
<?php } else { ?>
                <tr>
                    <td colspan="4">
                        <h4>Košarica je prazna!</h4>
                    </td>
                </tr>
    <?php } ?>
        </tbody>
    </table>
       <?php if (countItems($_SESSION["user_id"]) != 0) { ?>
        <a href="review.php" id="next" class="btn btn-flat btn-success pull-right <?php
       if ($error > 0) {
           echo "disabled";
       }
       ?>">Naprej na pregled naročila <i class="icon icon-arrow-line-right"></i></a>
<?php } ?>
    <div class="clear"></div>
</div>
<script>
    $(document).ready(function () {
        updatePrice();
        $error = <?php echo $error; ?>;
        <?php if($error > 0) { ?>
            alertify.alert("Eden izmed delov, ki ga imate v košarici je izbrisan!");
        <?php } ?>
    });

    $(document).on("keyup", "[name=pieces]", function () {
        if ($(this).val() !== "" && $.isNumeric($(this).val())) {
            $max = parseInt($(this).attr("data-offer-max"));
            $val = parseInt($(this).val());
            if ($val > $max) {
                $(this).val($(this).attr("data-offer-max"));
            }
            changePieces($(this).attr("data-offer-id"), $(this).val());
        }
    });



    function updatePrice() {
        $.ajax({
            url: "calcPrice.php",
            success: function (cb) {
                $("#price").html($.trim(cb));
            }
        });
    }

    function changePieces(offer, value) {
        $.ajax({
            url: "changePieces.php",
            type: "POST",
            data: {offer: offer, value: value},
            success: function (cb) {
                cb = $.trim(cb);
                cb = cb.split("|");
                if (cb[0] === "error") {
                    alertify.error(cb[1]);
                }
                if (cb[0] === "success") {
                    alertify.success(cb[1]);
                }
                updatePrice();
            }
        });
    }

    function removeOffer(id) {
        $.ajax({
            url: "removeOffer.php",
            type: "POST",
            data: {id: id},
            success: function (cb) {
                cb = $.trim(cb);
                cb = cb.split("|");
                if (cb[0] === "success") {
                    if ($(".offer" + id).attr("data-has-error") == id) {
                        $error--;
                    }
                    $(".offer" + id).remove();
                    updatePrice();
                    alertify.success(cb[1]);
                    $val = parseInt($("#cartNum").text());
                    $val--;
                    $("#cartNum").text($val);
                    if ($error === 0) {
                        $("#next").removeClass("disabled");
                    }
                }
                if (cb[0] === "error") {
                    alertify.error(cb[1]);
                }
            }
        });
    }
</script>
<?php include_once './footer.php'; ?>