<?php
include_once './core/database.php';
include_once './core/functions.php';
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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
                            <div class="product-chooser-item pci2 <?php
                            if ($type["id"] == $_SESSION["query"]["types"]) {
                                echo "selected";
                            }
                            ?>">
                                <center><img src="./img/<?php echo strtolower($type["name"]) ?>.png" alt="<?php echo $type["name"]; ?> image" width="100"/></center>
                                <div class="col-lg-12">
                                    <input type="radio" name="types" <?php
                                    if ($type["id"] == $_SESSION["query"]["types"]) {
                                        echo "checked='checked'";
                                    }
                                    ?> value="<?php echo $type["id"]; ?>">
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
                <div class="input-group<?php
                if (empty($_SESSION["query"]["name"]) && isset($_SESSION["query"])) {
                    echo " has-error";
                }
                ?>">
                    <span class="input-group-addon">Ime dela</span>
                    <input type="text" name="name" <?php
                    if (!empty($_SESSION["query"]["name"])) {
                        echo "value='" . $_SESSION["query"]["name"] . "'";
                    }
                    ?> class="form-control" placeholder="Vnesi ime dela" />
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Kataloška številka</span>
                    <input type="text" name="number" <?php
                    if (!empty($_SESSION["query"]["number"])) {
                        echo "value='" . $_SESSION["query"]["number"] . "'";
                    }
                    ?> class="form-control" placeholder="Vnesi kataloško številko dela" />
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="input-group<?php
                if ($_SESSION["error"] == 2 && isset($_SESSION["query"])) {
                    echo " has-error";
                }
                ?>">
                    <span class="input-group-addon">Cena</span>
                    <input type="text" name="price" class="form-control" <?php
                    if (!empty($_SESSION["query"]["price"])) {
                        echo "value='" . $_SESSION["query"]["price"] . "'";
                    }
                    ?> placeholder="Cena dela">
                    <span class="input-group-addon"><i class="icon icon-euro"></i></span>
                    <span class="input-group-addon"><span class="color-danger">*</span></span>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Vnesi št. kosov</span>
                    <input type="text" <?php
                    if (!empty($_SESSION["query"]["pieces"])) {
                        echo "value='" . $_SESSION["query"]["pieces"] . "'";
                    }
                    ?> name="pieces" class="form-control" placeholder="Vnesi št. kosov dela" />
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Nov del</span>
                    <input type="checkbox" <?php if($_SESSION["query"]["new"] == 1) { echo "checked"; } ?> data-on-text="Da" data-off-text="Ne" name="new" data-on-color="success" value="1" />
                </div>
            </div>
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
                    <textarea name="description" class="form-control" placeholder="Opis dela"><?php
                        if (!empty($_SESSION["query"]["description"])) {
                            echo $_SESSION["query"]["description"];
                        }
                        ?></textarea>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group<?php
                    if (empty($_SESSION["query"]["image"]) && isset($_SESSION["query"])) {
                        echo " has-error";
                    }
                    ?>">
                        <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-picture fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                        <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Izberi sliko</span><span class="fileinput-exists">Spremeni sliko</span><input name="image" accept="image/*" type="file"></span>
                        <a class="input-group-addon btn btn-default fileinput-exists" href="#" data-dismiss="fileinput">Odstrani sliko</a>
                        <span class="input-group-addon"><span class="color-danger">*</span></span>
                    </div>
                    <br />
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                </div>
                <span class="help-block">Dovoljene so slike s končnicami: PNG, JPG, JEPG, GIF. Vnešena slika bo prikazna slika izdelka</span>
                <?php if (!empty($_SESSION["query"]["image"]) && isset($_SESSION["query"])) { ?>
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                        <img src="<?php echo $_SESSION["query"]["image"] ?>" alt="Slika izdelka" />
                    </div>
                    <div class="alert alert-warning">
                        Če pustite polje slike prazno se ohrani zgornja slika!
                    </div>
                <?php } ?>
            </div>
        </div>
        <br />
        <div class="page-header">
            <h3>Podatki o avtomobilu <small>En del lahko uporabi več avtomobilov</small></h3>
            <span onClick='addCar()' data-toggle="popover" data-content="Delu dodaj avtomobil" data-placement="left" class='btn btn-flat btn-success pull-right minus30'>Dodaj avtomobil</span>
        </div>
        <div class="load-bar loadercar">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <?php if (empty($_SESSION["query"]["models"])) { ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group<?php
                    if (empty($_SESSION["query"]["models"]) && isset($_SESSION["query"])) {
                        echo " has-error";
                    }
                    ?>">
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
                    <div class="input-group">
                        <span class="input-group-addon">Letnik</span>
                        <input type="text" name="letnik[]" pattern="[0-9]{4}" data-placement="left" data-toggle="popover" data-content="Primer: 2014" class="form-control" />
                    </div>
                </div>    
            </div>
        <?php } else { ?>
            <?php
            $st = 0;
            foreach ($_SESSION["query"]["models"] as $model) {
                $queryBrand = "SELECT *, m.id AS model, b.id AS brand FROM models m INNER JOIN brands b ON b.id = m.brand_id WHERE m.id = " . $model;
                $resultBrand = mysqli_query($link, $queryBrand);
                $brandModel = mysqli_fetch_array($resultBrand);
                $queryBrands = "SELECT * FROM brands WHERE visible = 1 ORDER BY name ASC";
                $resultBrands = mysqli_query($link, $queryBrands);
                ?>
                <div id="car<?php echo $st; ?>">
                    <?php if ($st != 0) { ?>
                        <hr />
                    <?php } ?>
                    <div class="row">
                        <?php if ($st != 0) { ?>
                            <div class="col-lg-12">
                                <span onclick="removeCar(<?php echo $st; ?>);" data-toggle="popover" data-placement="left" data-content="Odstrani avtomobil" class="color-danger pull-right" style="cursor: pointer; "><i class="icon icon-remove"></i></span>
                            </div>
                        <?php } ?>
                        <br />
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Znamka</span>
                                <select id="<?php echo $st; ?>" name="brand" placeholder="Znamka" class="form-control aucp" autofocus="autofocus" autocorrect="off" autocomplete="off">
                                    <option selected="selected" disabled="disabled">Vnesi znamko</option>
                                    <?php while ($brand = mysqli_fetch_array($resultBrands)) { ?>
                                        <option value="<?php echo $brand["id"]; ?>" <?php
                                        if ($brandModel["brand"] == $brand["id"]) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $brand["name"]; ?></option>
                                            <?php } ?>
                                </select>
                                <span class="input-group-addon"><span class="color-danger">*</span></span>
                            </div>
                        </div>
                        <div id="model<?php echo $st; ?>" class="col-md-6">
                            <div class="load-bar loadermodel<?php echo $st; ?>">
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
                                <input value="<?php echo $_SESSION["query"]["type"][$st]; ?>" type="text" name="type[]" class="form-control" />
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Letnik</span>
                                <input value="<?php echo $_SESSION["query"]["years"][$st]; ?>" type="text" name="letnik[]" data-placement="left" data-toggle="popover" data-content="Primer: 2014" pattern="[0-9]{4}" class="form-control" />
                            </div>
                        </div>    
                    </div>
                    <br />
                </div>
                <script async>
                    $().ready(function () {
                        getModels(<?php echo $brandModel["brand"] ?>, <?php echo $st; ?>, <?php echo $model; ?>);
                        $global = <?php echo $st; ?> + 1;
                    });
                </script>
                <?php $st++; ?>
            <?php } ?>
        <?php } ?>
        <br />
        <div id="car">

        </div>
        <br />
        <div class="page-header">
            <h3>Galerija slik</h3>
            <span onClick='addImage()' data-toggle="popover" data-content="Dodaj več slik" data-placement="left" class='btn btn-flat btn-success pull-right minus30'>Dodaj sliko</span>
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
        <?php if (isset($_SESSION["query"])) { ?>
            <span id="clear" class="btn btn-flat btn-danger">Očisti predpomnilnik</span>
        <?php } ?>
        <input type="submit" name="submit" class="btn btn-flat btn-success" value="Dodaj del"/>
    </form>
</div>
<script>
    $(document).on("click", "#clear", function () {
        $.ajax({
            url: "unsetPart.php?method=add",
            type: "POST",
            beforeSend: function () {
                $("#loading").removeClass("hide");
                $(".load-content").append("<h3>Brisanje predpomnilnika v teku...</h3>");
            },
            success: function () {
                location.reload();
            }
        });
    });
    $(document).ready(function () {
        fetchCategories(<?php
        if (!empty($_SESSION["query"]["category"])) {
            echo $_SESSION["query"]["category"];
        } else {
            echo $_POST["id"];
        }
        ?>);
        $("[name=new]").bootstrapSwitch();
    });
</script>
<?php unset($_SESSION["error"]); ?>
<?php } else {
  $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
  header("Location:".$_SERVER["HTTP_REFERER"]);
}
?>