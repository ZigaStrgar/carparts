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
//TIPI
$queryTypes = "SELECT * FROM types ORDER BY name ASC";
$resultTypes = mysqli_query($link, $queryTypes);
//ZNAMKE
$queryBrands = "SELECT * FROM brands ORDER BY name ASC";
$resultBrands = mysqli_query($link, $queryBrands);
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
        <?php
        while ($category = mysqli_fetch_array($resultCategories)) {
            ?>
            <span id="<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></span>
            <?php
        }
        ?>
    </div>
</div>
<div id="formLoad">

</div>
<script src="./plugins/autosize/jquery.autosize.min.js"></script>
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
            data: {id: id},
            success: function (cb) {
                $("#model").html(cb);
            }
        });
    }

    $(document).on("click", "#back", function () {
        $("#formLoad").hide();
        $("#formType").show();
    });

    $(document).on("click", "#formType > .col-lg-12 > span", function () {
        $id = $(this).attr("id");
        $value = $(this).text();
        $.ajax({
            url: "formLoad.php",
            type: "POST",
            data: {id: $id, value: $value},
            success: function (cb) {
                $("#formLoad").html(cb);
                $("#formType").hide();
            }
        })
    });
</script>
<?php include_once 'footer.php'; ?>