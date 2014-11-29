<?php
include_once './core/database.php';
include_once './core/functions.php';
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
    <h3 class="page-header">Dodajanje avto dela: <?php echo $_POST["value"]; ?></h3>
    <span class="help-block">Polja označena z <span class="color-danger">*</span> so obvezna!</span>
    <form action="addingPart.php" method="POST" role="form" enctype="multipart/form-data">
        <h4 class="page-header">Tip avtomobila <span class="color-danger">*</span></h4>
        <div class="row">
            <div class="col-lg-12">
                <div class="product-chooser">
                    <?php while ($type = mysqli_fetch_array($resultTypes)) { ?>
                        <div class="col-lg-2" style="width: 185px;">
                            <div class="product-chooser-item pci2">
                                <center><img src="./img/<?php echo strtolower($type["name"]) ?>.png" alt="<?php echo $type["name"]; ?> image" width="100"/></center>
                                <div class="col-lg-12">
                                    <input type="radio" name="types" value="<?php echo $type["id"]; ?>">
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
            </div>
            <div class="col-xs-12 col-md-6">
                <?php if (mysqli_num_rows($resultCategories) > 0) { ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                        <select name="category" class="form-control">
                            <option selected="selected" disabled="disabled">Kategorija dela</option>
                            <?php while ($category = mysqli_fetch_array($resultCategories)) { ?>
                                <option value="<?php echo $category["id"]; ?>" <?php if($_POST["id"] == $category["id"]) { echo "selected='selected'"; } ?>><?php echo $category["name"] ?></option>
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
        <input type="button" id="back" class="btn btn-flat btn-primary" value="Nazaj" />
        <input type="submit" name="submit" class="btn btn-flat btn-success" value="Dodaj del"/>
    </form>
</div>
<script>
    $(document).ready(function(){
        fetchCategories(<?php echo $_POST["id"]; ?>);
    });
</script>
<style>
    .thumbnail {
        position:relative;
        overflow:hidden;
    }

    .caption {
        position:absolute;
        top:0;
        right:0;
        background:rgba(71, 143, 209, 0.90);
        width:100%;
        height:100%;
        padding:2%;
        display: none;
        text-align:center;
        color:#fff !important;
        z-index:2;
    }

    div.clear
    {
        clear: both;
    }

    div.product-chooser.disabled div.product-chooser-item
    {
        zoom: 1;
        filter: alpha(opacity=60);
        opacity: 0.6;
        cursor: default;
    }

    div.product-chooser div.product-chooser-item{
        padding: 11px;
        cursor: pointer;
        position: relative;
        border: 1px solid #efefef;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
    }

    div.product-chooser div.product-chooser-item.selected{
        border: 4px solid #5cb85c;
        background: #fff;
        padding: 8px;
        filter: alpha(opacity=100);
        opacity: 1;
    }

    div.product-chooser div.product-chooser-item img{
        padding: 0;
    }

    div.product-chooser div.product-chooser-item span.title{
        display: block;
        margin: 10px 0 5px 0;
        font-weight: bold;
        font-size: 12px;
    }

    div.product-chooser div.product-chooser-item span.description{
        font-size: 12px;
    }

    div.product-chooser div.product-chooser-item input{
        position: absolute;
        left: 0;
        top: 0;
        visibility:hidden;
    }
</style>