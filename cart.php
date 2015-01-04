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
$queryCart = "SELECT *, s.pieces AS spieces, p.pieces AS parts, s.id AS oid FROM shop s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = " . $_SESSION["user_id"];
$resultCart = mysqli_query($link, $queryCart);
?>
<div class="block-flat col-lg-12">
    <h1 class="page-header">Košarica</h1>
    <table class="table table-bordered table-striped table-hover col-lg-12">
        <?php if (mysqli_num_rows($resultCart) > 0) { ?>
            <thead>
                <tr>
                    <th class="col-xs-6">Ime dela</th>
                    <th class="col-xs-3">Št. kosov</th>
                    <th class="col-xs-2">Cena</th>
                    <th>Akcija</th>
                </tr>
            </thead>
        <?php } ?>
        <tbody>
            <?php if (mysqli_num_rows($resultCart) > 0) { ?>
                <?php while ($offer = mysqli_fetch_array($resultCart)) { ?>
                    <tr class="offer<?php echo $offer["oid"]; ?>">
                        <td><?php echo $offer["name"]; ?></td>
                        <td><input class="form-control" type="text" name="pieces" data-offer-id="<?php echo $offer["oid"] ?>" value="<?php echo $offer["spieces"] ?>" placeholder="Vnesite št. kosov"/></td>
                        <td><?php echo price($offer["price"]) ?> €</td>
                        <td><i class="icon icon-remove color-danger" style="cursor: pointer;" onClick="removeOffer(<?php echo $offer["oid"] ?>)" data-placement="left" data-toggle="popover" data-content="Odstrani iz košarice"></i></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="4" class="text-right">
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
</div>
<script>
    $(document).ready(function () {
        updatePrice();
    });

    $(document).on("keyup", "[name=pieces]", function () {
        if($(this).val() !== "" && $.isNumeric($(this).val())){
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
    
    function removeOffer(id){
        $.ajax({
           url: "removeOffer.php",
           type: "POST",
           data: {id: id},
           success: function(cb){
               cb = $.trim(cb);
               cb = cb.split("|");
               if(cb[0] === "success"){
                   $(".offer"+id).remove();
                   updatePrice();
                   alertify.success(cb[1]);
               }
               if(cb[0] === "error"){
                   alertify.error(cb[1]);
               }
           }
        });
    }
</script>
<?php include_once './footer.php'; ?>