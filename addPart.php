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
<div class="col-lg-12 block-flat">
    <?php if(!empty($_SESSION["error"])){ ?>
    <div class="alert alert-danger alert-fixed-bottom">
        <p>
            <?php 
            switch ($_SESSION["error"]){
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
    <h3 class="page-header">Dodajanje avto dela</h3>
    <span class="help-block">Polja označena z <span class="color-danger">*</span> so obvezna!</span>
    <form action="addingPart.php" method="POST" role="form" enctype="multipart/form-data">
        <h4 class="page-header">Tip avtomobila <span class="color-danger">*</span></h4>
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
        <h4 class="page-header">Podatki o delu</h4>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Kataloška številka</span>
                    <input type="text" name="number" class="form-control" placeholder="Vnesi kataloško številko dela" />
                </div>
                <span class="help-block"></span>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Ime dela</span>
                    <input type="text" name="name" class="form-control" placeholder="Vnesi ime dela" />
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
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
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
                <span class="help-block">Primer: 0,99 ali 12345,00 ali 12345</span>
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
        <div class="row">
            <div class="col-md-12">
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
        <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-picture fileinput-exists"></i> <span class="fileinput-filename"></span></div>
        <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Izberi sliko</span><span class="fileinput-exists">Spremeni sliko</span><input name="image" accept="image/*" type="file"></span>
        <a class="input-group-addon btn btn-default fileinput-exists" href="#" data-dismiss="fileinput">Odstrani sliko</a>
        <span class="input-group-addon"><span class="color-danger">*</span></span>
      </div>
                <span class="help-block">Dovoljene so slike s končnicami: PNG, JPG, JEPG, GIF</span>
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
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
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
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
            </div>    
        </div>
        <br />
        <input type="hidden" name="cat" />
        <input type="submit" name="submit" class="btn btn-flat btn-success" value="Dodaj del"/>
    </form>
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
</script>
<?php include_once 'footer.php'; ?>