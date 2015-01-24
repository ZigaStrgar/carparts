<?php include_once 'header.php' ?>
<?php
if (empty($_SESSION["user_id"]) || $_SESSION["email"] != "ziga_strgar@hotmail.com") {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
    die();
    exit();
}
//IZBIRA VSEH KATEGORIJ KI NIMAJO KATEGORIJ
$categories = Db::queryAll("SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC");
?>
<div class="block-flat col-lg-12">
    <h1 class="page-header">Dodajanje kategorije</h1>
    <form action="addingCategory.php" method="POST" role="form">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                    <input type="text" class="form-control" name="name" placeholder="Ime kategorije">
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                        <select name="category" class="form-control">
                            <option selected="selected"></option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?php echo $category["id"]; ?>"><?php echo $category["name"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
            </div>
        </div>
        <br />
        <div id="otherCategories" class="row">

        </div>
        <br />
        <h4 class="page-header">Zahtevek lokacije dela</h4>
        <div class="row default" style="margin-left: 0px;">
            <div class="col-lg-12 col-md-6">
                <div class="input-group">
                    <input type="radio" name="location" value="0" id="oa" checked>
                    <label for="oa">Brez</label>
                    <input type="radio" name="location" value="1" id="ob">
                    <label for="ob">Polovičen</label>
                    <input type="radio" name="location" value="2" id="oc">
                    <label for="oc">Podroben</label>
                </div>
            </div>
        </div>
        <br />
        <input type="hidden" name="redirect" value="index.php" />
        <br />
        <input type="submit" value="Dodaj kategorijo" class="btn btn-flat btn-success" />
    </form>
</div>
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

    $(document).on("change", "select", function () {
        $currentSelected = $(this).val();
        fetchCategories($currentSelected);
    });

    $(document).ready(function () {
        $currentSelected = 0;
        $('.default').radiosforbuttons();
    });
</script>
<script src="http://<?php echo URL; ?>/plugins/group/jquery.radiosforbuttons.js"></script>
<?php include_once 'footer.php' ?>
