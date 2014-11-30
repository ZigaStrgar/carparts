<?php include_once 'header.php'; ?>
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
//IZBIRA VSEH KATEGORIJ KI NIMAJO KATEGORIJ
$queryCategories = "SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC";
$resultCategories = mysqli_query($link, $queryCategories);
?>
<?php if (!empty($_SESSION["error"])) { ?>
    <div class="alert alert-danger alert-fixed-bottom">
        <p>
            <?php
            switch ($_SESSION["error"]) {
                case 1:
                    echo "Napaka podatkovne baze!";
                    break;
                case 2:
                    echo "Napačen format cene!";
                    break;
                case 3:
                    echo "Napaka podatkov! Nekatera polja niso bila izpolnjena!";
                    break;
            }
            unset($_SESSION["error"]);
            ?>
        </p>
    </div>
<?php } ?>
<div id="formType">
    <div class="col-lg-12 block-flat">
        <h3 class="page-header">Vnesti želim</h3>
        <div id="category" class="product-chooser"> 
            <?php
            while ($category = mysqli_fetch_array($resultCategories)) {
                ?>
                <div class="col-lg-12" style="width: 185px;">
                    <div class="product-chooser-item pci">
                        <center><img src="<?php echo $category["image"]; ?>" alt="Category image" /></center>
                        <div class="col-lg-12">
                            <input type="radio" name="type" value="<?php echo $category["id"]; ?>">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <center><span class="description"><div style="border-radius: 0px; padding: 5px 10px; font-size: 14px;" class="label label-success" id="badge<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></div></span></center>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="formLoad">

</div>
<script type="text/javascript">
    $(function () {
        $('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function () {
            $('div.product-chooser-item').removeClass('selected');
            $(this).addClass('selected');
            $(this).find('input[type="radio"]').prop("checked", true);
        });
    });
</script>
<script async src="./plugins/autosize/jquery.autosize.min.js"></script>
<script>
    function fetchCategories(id) {
        $.ajax({
            url: "fetchCategories.php",
            type: "POST",
            data: {id: id},
            success: function (comeback) {
                $("#otherCategories").html(comeback);
            }
        });
    }

    $(document).on("change", "select[name=category]", function () {
        $currentSelected = $(this).val();
        fetchCategories($currentSelected);
        $("input[name=cat]").val($currentSelected);
    });

    $(document).on("change", "select[name=model]", function () {
        $currentModel = $(this).val();
    });

    $(document).ready(function () {
        $("textarea").autosize();
        $currentSelected = 0;
    });

    $(document).on("change", "select[name=brand]", function () {
        getModels($(this).val());
    });

    function getModels(id) {
        $.ajax({
            url: "fetchModels.php",
            type: "POST",
            data: {id: id, req: "1"},
            success: function (cb) {
                $("#model").html(cb);
            }
        });
    }

    $(document).on("click", "#back", function () {
        location.reload();
    });

    $(document).on("click", "div.pci", function () {
        $id = $(this).find("input[type=radio]").attr("value");
        $value = $("#badge" + $id).text();
        $.ajax({
            url: "formLoad.php",
            type: "POST",
            data: {id: $id, value: $value},
            success: function (cb) {
                $("#formLoad").html(cb);
                $("#formType").hide();
                fetchCategories($id);
            }
        });
    });
    $(document).on("click", "div.pci2", function () {
        $('div.product-chooser-item').removeClass('selected');
        $(this).addClass('selected');
        $(this).find('input[type=radio]').prop("checked", true);
    });
</script>
<?php include_once 'footer.php'; ?>