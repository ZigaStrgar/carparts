<?php
include_once './core/database.php';
include_once './core/functions.php';
include_once './core/session.php';
//TIPI
$queryTypes = "SELECT * FROM types ORDER BY name ASC";
$resultTypes = mysqli_query($link, $queryTypes);
//ZNAMKE
$queryBrands = "SELECT * FROM brands WHERE visible = 1 ORDER BY name ASC";
$resultBrands = mysqli_query($link, $queryBrands);
//IZBIRA VSEH KATEGORIJ KI NIMAJO KATEGORIJ
$queryCategories = "SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC";
$resultCategories = mysqli_query($link, $queryCategories);
?>
<script src="http://<?php echo URL; ?>/plugins/autocomplete/jquery.js" type="text/javascript"></script>
<script src="http://<?php echo URL; ?>/plugins/autocomplete/jq.select-to-autocomplete.js" type="text/javascript"></script>
<script src="http://<?php echo URL; ?>/plugins/autocomplete/jq-ui-autocomplete.js" type="text/javascript"></script>
<div class="col-lg-12 block-flat">
    <h1 class="page-header">Dodajanje dela <small><?php echo $_POST["value"]; ?></small></h1>
    <span class="help-block">Polja označena z <span class="color-danger">*</span> so obvezna!</span>
    <form action="addingPart.php" method="POST" role="form" enctype="multipart/form-data">
        <h3 class="page-header">Tip avtomobila <span class="color-danger">*</span></h3>
        <div class="row">
            <div class="col-lg-12">
                <div class="product-chooser pull-left">
                    <?php while ($type = mysqli_fetch_array($resultTypes)) { ?>
                        <div class="col-lg-2 col-xs-2 col-md-2" style="width: 185px; height: 120px;">
                            <div class="product-chooser-item pci2 <?php if($type["id"] == $_SESSION["query"]["types"]) { echo "selected"; } ?>">
                                <center><img src="./img/<?php echo strtolower($type["name"]) ?>.png" alt="<?php echo $type["name"]; ?> image" width="100"/></center>
                                <div class="col-lg-12">
                                    <input type="radio" name="types" <?php if($type["id"] == $_SESSION["query"]["types"]) { echo "checked='checked'"; } ?> value="<?php echo $type["id"]; ?>">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <center><span class="description"><?php echo $type["name"]; ?></span></center>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <br />
        <h3 class="page-header">Podatki o delu</h3>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="input-group<?php if(empty($_SESSION["query"]["name"]) && isset($_SESSION["query"])) { echo " has-error"; } ?>">
                    <span class="input-group-addon">Ime dela</span>
                    <input type="text" name="name" <?php if(!empty($_SESSION["query"]["name"])) { echo "value='".$_SESSION["query"]["name"]."'"; } ?> class="form-control" placeholder="Vnesi ime dela" />
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Kataloška številka</span>
                    <input type="text" name="number" <?php if(!empty($_SESSION["query"]["number"])) { echo "value='".$_SESSION["query"]["number"]."'"; } ?> class="form-control" placeholder="Vnesi kataloško številko dela" />
                </div>
                <span class="help-block"></span>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="input-group<?php if($_SESSION["error"] == 2 || !match_price($_SESSION["query"]["price"]) && isset($_SESSION["query"])) { echo " has-error"; }?>">
                    <span class="input-group-addon">Cena</span>
                    <input type="text" name="price" class="form-control" <?php if(!empty($_SESSION["query"]["price"])) { echo "value='".$_SESSION["query"]["price"]."'"; } ?> placeholder="Cena dela">
                    <span class="input-group-addon"><i class="icon icon-euro"></i></span>
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-thumbnails"></i></span>
                    <input type="text" <?php if(!empty($_SESSION["query"]["pieces"])) { echo "value='".$_SESSION["query"]["pieces"]."'"; } ?> name="pieces" class="form-control" placeholder="Vnesi št. kosov dela" />
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <?php if (mysqli_num_rows($resultCategories) > 0) { ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                        <select name="category" class="form-control">
                            <option selected="selected" disabled="disabled">Kategorija dela</option>
                            <?php while ($category = mysqli_fetch_array($resultCategories)) { ?>
                                <option value="<?php echo $category["id"]; ?>" <?php
                                if ($_POST["id"] == $category["id"]) {
                                    echo "selected='selected'";
                                }
                                ?>><?php echo $category["name"] ?></option>
                                    <?php } ?>
                            <option value="">Drugo</option>
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
                    <textarea name="description" class="form-control" placeholder="Opis dela"><?php if(!empty($_SESSION["query"]["description"])) { echo $_SESSION["query"]["description"]; } ?></textarea>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group<?php if(empty($_SESSION["query"]["image"]) && isset($_SESSION["query"])) { echo " has-error"; } ?>">
                        <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-picture fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                        <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Izberi sliko</span><span class="fileinput-exists">Spremeni sliko</span><input name="image" accept="image/*" type="file"></span>
                        <a class="input-group-addon btn btn-default fileinput-exists" href="#" data-dismiss="fileinput">Odstrani sliko</a>
                        <span class="input-group-addon"><span class="color-danger">*</span></span>
                    </div>
                    <br />
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                </div>
                <span class="help-block">Dovoljene so slike s končnicami: PNG, JPG, JEPG, GIF. Vnešena slika bo prikazna slika izdelka</span>
            </div>
        </div>
        <br />
        <div class="page-header">
            <h3>Podatki o avtomobilu <small>En del lahko uporabi več avtomobilov</small></h3>
            <span onClick='addCar()' class='btn btn-flat btn-success pull-right minus30'>Dodaj avtomobil</span>
        </div>
        <div class="load-bar loadercar">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group <?php if(empty($_SESSION["query"]["models"]) && isset($_SESSION["query"])) { echo " has-error"; }?>">
                    <span class="input-group-addon">Znamka</span>
                    <select id="0" name="brand" placeholder="Znamka" class="form-control aucp" autofocus="autofocus" autocorrect="off" autocomplete="off">
                        <option selected="selected" disabled="disabled">Vnesi znamko</option>
                        <?php while ($brand = mysqli_fetch_array($resultBrands)) { ?>
                            <option value="<?php echo $brand["id"]; ?>"><?php echo $brand["name"]; ?></option>
                        <?php } ?>
                    </select>
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
            </div>
            <div id="model0" class="col-md-6">
                <div class="load-bar loadermodel0">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Tip</span>
                    <input type="text" name="type[]" class="form-control" />
                </div>
            </div> 
            <div class="col-md-6">
                <div class="input-group<?php if(empty($_SESSION["query"]["years"][0]) && isset($_SESSION["query"])) { echo " has-error"; }?>">
                    <span class="input-group-addon">Letnik</span>
                    <input type="text" name="letnik[]" pattern="[0-9]{4}" title="Primer: 2014" class="form-control" />
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
            </div>    
        </div>
        <br />
        <div id="car">

        </div>
        <br />
        <div class="page-header">
            <h3>Galerija slik</h3>
            <span onClick='addImage()' data-toggle="tooltip" title="Dodaj več slik delu" data-placement="bottom" class='btn btn-flat btn-success pull-right minus30'>Dodaj sliko</span>
        </div>
        <span class="help-block">Dovoljene so slike s končnicami: PNG, JPG, JEPG, GIF</span>
        <div class="load-bar loaderimage">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div id="gallery">

        </div>
        <br />
        <input type="hidden" name="cat" />
        <input type="button" id="back" class="btn btn-flat btn-primary" value="Nazaj" />
        <input type="submit" name="submit" class="btn btn-flat btn-success" value="Dodaj del"/>
    </form>
</div>
<script>
    $(document).ready(function () {
        fetchCategories(<?php if(!empty($_SESSION["query"]["category"])) { echo $_SESSION["query"]["category"]; } else { echo $_POST["id"]; } ?>);
    });
</script>
<?php unset($_SESSION["error"]); ?>
