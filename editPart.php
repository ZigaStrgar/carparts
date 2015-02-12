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
    die();
    exit();
}
$id = (int) $_GET["part"];
if (my_part($id, $_SESSION["user_id"]) && !part_deleted($id)) {
    //DEL
    $part = Db::queryOne("SELECT *, p.id AS pid, p.name AS partname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id INNER JOIN models m ON m.id = mp.model_id WHERE p.id = ? GROUP BY p.id", $id);
//TIPI
    $types = Db::queryAll("SELECT * FROM types ORDER BY name ASC");
//ZNAMKE
    $brands = Db::queryAll("SELECT * FROM brands WHERE visible = 1 ORDER BY name ASC");
//IZBIRA VSEH KATEGORIJ KI NIMAJO KATEGORIJ
    $categories = Db::queryAll("SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC");
    //ZAHTEVEK LOKACIJE
    $location = Db::querySingle("SELECT location FROM categories WHERE id = ?", firstParent($part["category_id"]));
    ?>
    <script src="http://<?php echo URL; ?>/plugins/autocomplete/jquery.js" type="text/javascript"></script>
    <script src="http://<?php echo URL; ?>/plugins/autocomplete/jq.select-to-autocomplete.js" type="text/javascript"></script>
    <script src="http://<?php echo URL; ?>/plugins/autocomplete/jq-ui-autocomplete.js" type="text/javascript"></script>
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
                ?>
            </p>
        </div>
    <?php } ?>
    <div class="col-lg-12 block-flat top-warning">
        <h1 class="page-header">Urejanje dela</h1>
        <span class="help-block">Polja označena z <span class="color-danger">*</span> so obvezna!</span>
        <form action="http://<?php echo URL; ?>/editingPart.php?id=<?php echo $id; ?>" method="POST" role="form" enctype="multipart/form-data">
            <h3 class="page-header">Tip avtomobila <span class="color-danger">*</span></h3>
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-chooser pull-left">
                        <?php foreach ($types as $type) { ?>
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 185px; height: 120px;">
                                <div class="product-chooser-item pci2 <?php
                                if ($type["id"] == $part["type_id"]) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><img src="http://<?php echo URL; ?>/img/<?php echo strtolower($type["name"]) ?>.png" alt="<?php echo $type["name"]; ?> image" width="100"/></center>
                                    <div class="col-lg-12">
                                        <input type="radio" name="types" <?php
                                        if ($type["id"] == $part["type_id"]) {
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
                    if (empty($_SESSION["query_update"]["name"]) && isset($_SESSION["query_update"])) {
                        echo " has-error";
                    }
                    ?>">
                        <span class="input-group-addon">Ime dela</span>
                        <input type="text" name="name" value="<?php
                        if (!empty($_SESSION["query_update"]["name"])) {
                            echo $_SESSION["query_update"]["name"];
                        } else {
                            echo $part["partname"];
                        }
                        ?>" class="form-control" placeholder="Vnesi ime dela" />
                        <span class="input-group-addon"><span class="color-danger">*</span></span>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon">Kataloška številka</span>
                        <input type="text" name="number" value="<?php
                        if (!empty($_SESSION["query_update"]["number"])) {
                            echo $_SESSION["query_update"]["number"];
                        } else {
                            echo $part["number"];
                        }
                        ?>" class="form-control" placeholder="Vnesi kataloško številko dela" />
                    </div>
                    <span class="help-block"></span>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon<?php
                        if (empty($_SESSION["query_update"]["price"]) && isset($_SESSION["query_update"])) {
                            echo " has-error";
                        }
                        ?>">Cena</span>
                        <input type="text" name="price" class="form-control" value="<?php
                        if (!empty($_SESSION["query_update"]["price"])) {
                            echo $_SESSION["query_update"]["price"];
                        } else {
                            echo $part["price"];
                        }
                        ?>" placeholder="Cena dela">
                        <span class="input-group-addon"><i class="icon icon-euro"></i></span>
                        <span class="input-group-addon"><span class="color-danger">*</span></span>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon">Vnesi št. kosov</span>
                        <input type="text" value="<?php
                        if (!empty($_SESSION["query_update"]["pieces"])) {
                            echo $_SESSION["query_update"]["pieces"];
                        } else {
                            echo $part["pieces"];
                        }
                        ?>" name="pieces" class="form-control" placeholder="Vnesi št. kosov dela" />
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Nov del</span>
                        <input type="checkbox" <?php
                        if (($part["new"] == 1 && !isset($_SESSION["query_update"]) || ($_SESSION["query_update"]["new"] == 1))) {
                            echo "checked";
                        }
                        ?> data-on-text="Da" data-off-text="Ne" name="new" data-on-color="success" value="1" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?php if (Db::queryAll("SELECT * FROM categories WHERE category_id = 0 ORDER BY name ASC") > 0) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                            <select name="category" class="form-control">
                                <option selected="selected" disabled="disabled">Kategorija dela</option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category["id"]; ?>" <?php
                                    if (($part["category_id"] == $category["id"] && !isset($_SESSION["query_update"])) || ($_SESSION["query_update"]["first"] == $category["id"] && isset($_SESSION["query_update"]))) {
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
                    <div class="form-group">
                        <textarea name="description" class="form-control" placeholder="Opis dela"><?php
                            if (!empty($_SESSION["query_update"]["description"])) {
                                echo $_SESSION["query_update"]["description"];
                            } else {
                                echo $part["description"];
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
                        if (empty($_SESSION["query_update"]["image"]) && isset($_SESSION["query_update"])) {
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
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                        <img src="<?php
                        if (isset($_SESSION["query_update"])) {
                            echo $_SESSION["query_update"]["image"];
                        } else {
                            echo $part["image"];
                        }
                        ?>" alt="Slika izdelka" />
                    </div>
                    <div class="alert alert-warning">
                        Če pustite polje slike prazno se ohrani zgornja slika!
                    </div>
                </div>
            </div>
            <?php if ($location != 0) { ?>
                <br />
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Lokacija dela <span class="color-danger">*</span> <small>Označite, kje se nahaja del v avtomobilu</small></h3>
                    </div>
                    <?php if ($location == 1) { ?>
                        <div class="product-chooser"> 
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 210px;min-height: 300px;">
                                <div class="product-chooser-item pci3 <?php
                                if ($_SESSION["query_update"]["location"] == 1 && isset($_SESSION["query_update"])) {
                                    echo "selected";
                                } else if ($part["location"] == 1) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><span class="description"><img height="300" src="../img/ff.png" alt="Spredaj" /></span></center>
                                    <div class="col-lg-12">
                                        <input type="radio" name="location" <?php
                                        if ($_SESSION["query_update"]["location"] == 1 && isset($_SESSION["query_update"])) {
                                            echo "selected";
                                        } else if ($part["location"] == 1) {
                                            echo "selected";
                                        }
                                        ?> value="1">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 210px;min-height: 300px;">
                                <div class="product-chooser-item pci3 <?php
                                if ($_SESSION["query_update"]["location"] == 2 && isset($_SESSION["query_update"])) {
                                    echo "selected";
                                } else if ($part["location"] == 2) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><span class="description"><img height="300" src="../img/rf.png" alt="Zadaj" /></span></center>
                                    <div class="col-lg-12">
                                        <input type="radio" name="location" <?php
                                        if ($_SESSION["query_update"]["location"] == 2 && isset($_SESSION["query_update"])) {
                                            echo "selected";
                                        } else if ($part["location"] == 2) {
                                            echo "selected";
                                        }
                                        ?> value="2">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($location == 2) { ?>
                        <div class="product-chooser"> 
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 210px;min-height: 300px;">
                                <div class="product-chooser-item pci3 <?php
                                if ($_SESSION["query_update"]["location"] == 3 && isset($_SESSION["query_update"])) {
                                    echo "selected";
                                } else if ($part["location"] == 3) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><span class="description"><img height="300" src="../img/fl.png" alt="Spredaj levo" /></span></center>
                                    <div class="col-lg-12">
                                        <input type="radio" name="location" <?php
                                        if ($_SESSION["query_update"]["location"] == 3 && isset($_SESSION["query_update"])) {
                                            echo "selected";
                                        } else if ($part["location"] == 3) {
                                            echo "selected";
                                        }
                                        ?> value="3">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 210px;min-height: 300px;">
                                <div class="product-chooser-item pci3 <?php
                                if ($_SESSION["query_update"]["location"] == 4 && isset($_SESSION["query_update"])) {
                                    echo "selected";
                                } else if ($part["location"] == 4) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><span class="description"><img height="300" src="../img/fr.png" alt="Spredaj desno" /></span></center>
                                    <div class="col-lg-12">
                                        <input type="radio" name="location" <?php
                                        if ($_SESSION["query_update"]["location"] == 4 && isset($_SESSION["query_update"])) {
                                            echo "selected";
                                        } else if ($part["location"] == 4) {
                                            echo "selected";
                                        }
                                        ?> value="4">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 210px;min-height: 300px;">
                                <div class="product-chooser-item pci3 <?php
                                if ($_SESSION["query_update"]["location"] == 5 && isset($_SESSION["query_update"])) {
                                    echo "selected";
                                } else if ($part["location"] == 5) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><span class="description"><img height="300" src="../img/rl.png" alt="Zadaj levo" /></span></center>
                                    <div class="col-lg-12">
                                        <input type="radio" name="location" <?php
                                        if ($_SESSION["query_update"]["location"] == 5 && isset($_SESSION["query_update"])) {
                                            echo "selected";
                                        } else if ($part["location"] == 5) {
                                            echo "selected";
                                        }
                                        ?> value="5">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-2 col-md-2" style="width: 210px;min-height: 300px;">
                                <div class="product-chooser-item pci3 <?php
                                if ($_SESSION["query_update"]["location"] == 6 && isset($_SESSION["query_update"])) {
                                    echo "selected";
                                } else if ($part["location"] == 6) {
                                    echo "selected";
                                }
                                ?>">
                                    <center><span class="description"><img height="300" src="../img/rr.png" alt="Zadaj desno" /></span></center>
                                    <div class="col-lg-12">
                                        <input type="radio" name="location" <?php
                                        if ($_SESSION["query_update"]["location"] == 6 && isset($_SESSION["query_update"])) {
                                            echo "selected";
                                        } else if ($part["location"] == 6) {
                                            echo "selected";
                                        }
                                        ?> value="6">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
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
            <?php
            if (empty($_SESSION["query_update"]["models"])) {
                $models = Db::queryAll("SELECT * FROM models_parts WHERE part_id = ? AND old = 0", $id);
                $st = 0;
                foreach ($models as $model) {
                    $brandModel = Db::queryOne("SELECT *, m.id AS model, b.id AS brand FROM models m INNER JOIN brands b ON b.id = m.brand_id WHERE m.id = ?", $model["model_id"]);
                    $resultBrands = Db::queryAll("SELECT * FROM brands WHERE visible = 1 ORDER BY name ASC");
                    ?>
                    <div <?php if ($st != 0) { ?>id="car<?php echo $st; ?>"<?php } ?>>
                        <div class="row">
                            <?php if ($st != 0) { ?>
                                <div class="col-lg-12">
                                    <span onclick="removeCar(<?php echo $st; ?>);" data-toggle="tooltip" data-placement="bottom" title="Odstrani avtomobil" class="color-danger pull-right" style="cursor: pointer; "><i class="icon icon-remove"></i></span>
                                </div>
                            <?php } ?>
                            <br />
                            <div class="col-md-6">
                                <div class="input-group<?php
                                if (empty($_SESSION["query_update"]["models"][$st]) && isset($_SESSION["query_update"])) {
                                    echo " has-error";
                                }
                                ?>">
                                    <span class="input-group-addon">Znamka</span>
                                    <select id="<?php echo $st; ?>" name="brand" placeholder="Znamka" class="form-control aucp" autofocus="autofocus" autocorrect="off" autocomplete="off">
                                        <option selected="selected" disabled="disabled">Vnesi znamko</option>
                                        <?php foreach ($resultBrands as $brand) { ?>
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
                                    <input value="<?php echo $model["type"]; ?>" type="text" name="type[]" class="form-control" />
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Letnik</span>
                                    <input value="<?php echo $model["year"]; ?>" type="text" name="letnik[]" pattern="[0-9]{4}" title="Primer: 2014" class="form-control" />
                                </div>
                            </div>    
                        </div>
                        <br />
                        <hr />
                    </div>
                    <script async>
                        $().ready(function () {
                            getModels(<?php echo $brandModel["brand"] ?>, <?php echo $st; ?>, <?php echo $model["model_id"]; ?>);
                            $global = <?php echo $st; ?> + 1;
                        });
                    </script>
                    <?php $st++; ?>
                <?php } ?>
            <?php } else { ?>
                <?php
                $st = 0;
                foreach ($_SESSION["query_update"]["models"] as $model) {
                    $brandModel = Db::queryOne("SELECT *, m.id AS model, b.id AS brand FROM models m INNER JOIN brands b ON b.id = m.brand_id WHERE m.id = ?", $model);
                    $resultBrands = Db::queryAll("SELECT * FROM brands WHERE visible = 1 ORDER BY name ASC");
                    ?>
                    <div <?php if ($st != 0) { ?>id="car<?php echo $st; ?>"<?php } ?>>
                        <div class="row">
                            <?php if ($st != 0) { ?>
                                <div class="col-lg-12">
                                    <span onclick="removeCar(<?php echo $st; ?>);" data-toggle="tooltip" data-placement="bottom" title="Odstrani avtomobil" class="color-danger pull-right" style="cursor: pointer; "><i class="icon icon-remove"></i></span>
                                </div>
                            <?php } ?>
                            <br />
                            <div class="col-md-6">
                                <div class="input-group<?php
                                if (empty($_SESSION["query_update"]["models"][$st]) && isset($_SESSION["query_update"])) {
                                    echo " has-error";
                                }
                                ?>">
                                    <span class="input-group-addon">Znamka</span>
                                    <select id="<?php echo $st; ?>" name="brand" placeholder="Znamka" class="form-control aucp" autofocus="autofocus" autocorrect="off" autocomplete="off">
                                        <option selected="selected" disabled="disabled">Vnesi znamko</option>
                                        <?php foreach ($resultBrands as $brand) { ?>
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
                                    <input value="<?php echo $_SESSION["query_update"]["type"][$st]; ?>" type="text" name="type[]" class="form-control" />
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Letnik</span>
                                    <input value="<?php echo $_SESSION["query_update"]["years"][$st]; ?>" type="text" name="letnik[]" pattern="[0-9]{4}" title="Primer: 2014" class="form-control" />
                                </div>
                            </div>    
                        </div>
                        <br />
                        <hr />
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
            <script>
                $().ready(function () {
                    $global = <?php echo $st; ?> + 1;
                });
            </script>
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
            <input type="hidden" name="cat" value="<?php
            if (isset($_SESSION["query_update"])) {
                echo $_SESSION["query_update"]["category"];
            } else {
                echo $part["category_id"];
            }
            ?>" />
                   <?php if (isset($_SESSION["query_update"])) { ?>
                <span id="clear" class="btn btn-danger btn-flat">Brisanje predpomnilnika</span>
            <?php } ?>
            <input type="submit" name="submit" class="btn btn-flat btn-success" value="Uredi del"/>
        </form>
    </div>
    <script async src="http://<?php echo URL; ?>/plugins/autosize/jquery.autosize.min.js"></script>
    <script async>
                    $(document).ready(function () {
                        $('.aucp').selectToAutocomplete();
                        fetchCategories(<?php
        if (!empty($_SESSION["query_update"]["category"])) {
            echo $_SESSION["query_update"]["category"];
        } else {
            echo $_POST["id"];
        }
        ?>);
                        $("[name=new]").bootstrapSwitch();
                        $('textarea').wysihtml5({
                            "font-styles": true, //Font styling, e.g. h1, h2, etc
                            "color": false, //Button to change color of font
                            "emphasis": true, //Italics, bold, etc
                            "textAlign": false, //Text align (left, right, center, justify)
                            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers
                            "blockquote": false, //Button to insert quote
                            "link": false, //Button to insert a link
                            "table": false, //Button to insert a table
                            "image": false, //Button to insert an image
                            "video": false, //Button to insert video (YouTube, Vimeo, Metacafe and DailyMotion supported)
                            "html": false //Button which allows you to edit the generated HTML
                        });
                    });
                    $globalimage = 1;
                    function getModels(id, place, model) {
                        $.ajax({
                            url: "http://<?php echo URL; ?>/fetchModels.php",
                            type: "POST",
                            data: {id: id, req: "1", model: model},
                            beforeSend: function () {
                                $(".loadermodel" + place).css({display: "block"});
                            },
                            success: function (cb) {
                                $(".loadermodel" + place).hide();
                                $("#model" + place).html(cb);
                            }
                        });
                    }
                    function fetchCategories(id) {
                        $.ajax({
                            url: "http://<?php echo URL; ?>/fetchCategories.php",
                            type: "POST",
                            data: {id: id},
                            success: function (comeback) {
                                $("#otherCategories").html(comeback);
                            }
                        });
                    }
                    function addCar() {
                        $.ajax({
                            url: "http://<?php echo URL; ?>/addCar.php",
                            type: "POST",
                            data: {global: $global},
                            beforeSend: function () {
                                $(".loadercar").css({display: "block"});
                            },
                            success: function (cb) {
                                $(".aucp").removeClass("aucp");
                                $("#car").append(cb);
                                $global++;
                                $('.aucp').selectToAutocomplete();
                                $(".loadercar").hide();
                            }
                        });
                    }
                    function removeCar(id) {
                        $("#car" + id).remove();
                    }
                    function addImage() {
                        $.ajax({
                            url: "http://<?php echo URL; ?>/addImage.php",
                            type: "POST",
                            data: {global: $globalimage},
                            beforeSend: function () {
                                $(".loaderimage").show();
                            },
                            success: function (cb) {
                                $(".loaderimage").hide();
                                $("#gallery").append(cb);
                                $globalimage++;
                            }
                        });
                    }
                    function removeImage(id) {
                        $("#image" + id).remove();
                    }

                    $(document).on("click", "input[type=submit]", function () {
                        $("#loading").removeClass("hide");
                        $(".load-content").append("<h3>Urejanje dela v teku...</h3>");
                    });

                    $(document).on("click", "div.pci2", function () {
                        $('div.product-chooser-item').removeClass('selected');
                        $(this).addClass('selected');
                        $(this).find('input[type=radio]').prop("checked", true);
                    });

                    $(document).on("change", "select[name=brand]", function () {
                        getModels($(this).val(), $(this).attr("id"), 0);
                    });

                    $(document).on("change", "select[name=category]", function () {
                        $("[name=cat]").val($(this).val());
                    });

                    $(document).on("click", "#clear", function () {
                        $.ajax({
                            url: "../unsetPart.php?method=edit",
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
                        setInterval(function () {
                            $width = $("select").width() - 13;
                            $(".ui-autocomplete").css({"list-style-type": "none", "width": $width});
                        }, 100);
                    });
                    $(document).on("click", "div.pci3", function () {
                        $('div.product-chooser-item').removeClass('selected');
                        $(this).addClass('selected');
                        $(this).find('input[type=radio]').prop("checked", true);
                    });
    </script>
    <?php unset($_SESSION["error"]); ?>
<?php } else { ?>
    <div class="col-lg-12 block-flat">
        <h3 class="text-center">Napaka! Del, ki ga želite urejati niste dodali Vi ali pa je izbrisan!</h3>
    </div>
<?php } ?>
<?php include_once './footer.php'; ?>