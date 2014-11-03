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
//IZBIRA VSEH KATEGORIJ KI NIMAJO KATEGORIJ
$query = "SELECT * FROM categories WHERE category_id = 0";
$result = mysqli_query($link, $query);
?>
<div class="col-lg-12 block-flat">
    <h3 class="page-header">Dodajanje avto dela</h3>
    <form action="addingPart.php" method="POST">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Ime dela</span>
                    <input type="text" name="name" class="form-control" placeholder="Vnesi ime dela" />
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                        <select name="category" class="form-control">
                            <option selected="selected"></option>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"] ?></option>
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
            <div class="col-md-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Cena</span>
                    <input type="text" name="price" class="form-control" title="1-5,0-2 številk" placeholder="Cena dela">
                    <span class="input-group-addon"><i class="icon icon-euro"></i></span>
                </div>
            </div>
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
<script>
    $(document).on("submit", "form", function () {
        $name = $("input[name=name]").val();
        $desc = $("textarea[name=description]").val();
        $price = $("input[name=price]").val();
        $.ajax({
            url: "addingPart.php",
            type: "POST",
            data: {category: $currentSelected, name: $name, description: $desc, price: $price},
            success: function (comeback) {
                comeback = $.trim(comeback);
                if(comeback === "success"){
                    alertify.success("Uspešno dodan v podatkovno bazo!");
                } else {
                    alertify.error(comeback);
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

    $(document).on("change", "select", function () {
        $currentSelected = $(this).val();
        fetchCategories($currentSelected);
    });

    $(document).ready(function () {
        $currentSelected = 0;
    });
</script>
<?php include_once 'footer.php'; ?>