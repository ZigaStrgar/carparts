<?php include_once 'header.php'; ?>
<?php
if ($_SESSION["logged"] != 1) {
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
$queryCategories = "SELECT * FROM categories WHERE category_id = 0";
$resultCategories = mysqli_query($link, $queryCategories);
?>
<div class="col-lg-12 block-flat">
    <h3 class="page-header">Dodajanje avto dela</h3>
    <form action="addingPart.php" method="POST">
        <h4 class="page-header">Tip avtomobila</h4>
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group">
                    <?php while ($type = mysqli_fetch_array($resultTypes)) { ?>
                        <?php echo $type["name"]; ?><input style="margin: 0 10px 0 5px;" type="radio" name="types" value="<?php echo $type["id"]; ?>" />
                    <?php } ?>
                </div>
            </div>
        </div>
        <br />
        <h4 class="page-header">Podatki o avtomobilu</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Znamka</span>
                    <select name="brand" placeholder="Znamka" class="form-control">
                        <option value="0" selected="selected"></option>
                        <?php while ($brand = mysqli_fetch_array($resultBrands)) { ?>
                            <option value="<?php echo $brand["id"]; ?>"><?php echo $brand["name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div id="model" class="col-md-6">

            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Tip</span>
                    <input type="text" name="type" class="form-control" />
                </div>
            </div> 
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Letnik</span>
                    <input type="text" name="letnik" pattern="[0-9]{4}" title="Primer: 2014" class="form-control" />
                </div>
            </div>    
        </div>
        <h4 class="page-header">Podatki o delu</h4>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Kataloška številka</span>
                    <input type="text" name="number" class="form-control" placeholder="Vnesi kataloško številko dela" />
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Ime dela</span>
                    <input type="text" name="name" class="form-control" placeholder="Vnesi ime dela" />
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Cena</span>
                    <input type="text" name="price" class="form-control" title="1-5,0-2 številk" placeholder="Cena dela">
                    <span class="input-group-addon"><i class="icon icon-euro"></i></span>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <?php if (mysqli_num_rows($resultCategories) > 0) { ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                        <select name="category" class="form-control">
                            <option selected="selected"></option>
                            <?php while ($category = mysqli_fetch_array($resultCategories)) { ?>
                                <option value="<?php echo $category["id"]; ?>"><?php echo $category["name"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php } ?>
            </div>
        </div>
        <br />
        <div id="otherCategories" class="row">

        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-addon">Opis dela</span>
                    <textarea name="description" class="form-control" placeholder="Opis dela"></textarea>
                </div>
            </div>
        </div>
        <br />
        <input type="submit" name="submit" class="btn btn-flat btn-success" value="Dodaj del"/>
    </form>
</div>
<script src="./plugins/autosize/jquery.autosize.min.js"></script>
<script>
    $(document).on("submit", "form", function () {
        $name = $("input[name=name]").val(); //Ime izdelka
        $desc = $("textarea[name=description]").val(); //Opis
        $price = $("input[name=price]").val(); //Cena
        $types = $("input[name=types]").val(); //Tip avtomobila
        $type = $("input[name=type]").val(); //Tip
        $number = $("input[name=number").val(); //Kataloška številka
        $year = $("input[name=letnik]").val(); //Letnik
        $.ajax({
            url: "addingPart.php",
            type: "POST",
            data: {category: $currentSelected, name: $name, description: $desc, price: $price, types: $types, type: $type, model: $currentModel, number: $number, year: $year},
            success: function (comeback) {
                comeback = $.trim(comeback);
                if (comeback === "success") {
                    alertify.success("Uspešno dodan v podatkovno bazo!");
                } else {
                    alertify.log(comeback);
                }
            }
        });
        return false;
    });

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
</script>
<?php include_once 'footer.php'; ?>