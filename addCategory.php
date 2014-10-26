<?php include_once 'header.php' ?>
<?php
//IZBIRA VSEH KATEGORIJ KI NIMAJO KATEGORIJ
$query = "SELECT * FROM categories WHERE category_id = 0";
$result = mysqli_query($link, $query);
?>
<div class="block-flat col-lg-12">
    <h3>Dodajanje kategorije</h3>
    <hr />
    <form action="addingCategory.php" method="POST" role="form">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                    <input type="text" class="form-control" name="name" placeholder="Ime kategorije">
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
                        <select name="category" class="form-control selector">
                            <option selected="selected"></option>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                <option onselect="currentSelect()" value="<?php echo $row["id"]; ?>"><?php echo $row["name"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php } ?>
            </div>
        </div>
        <br />
        <div id="otherCategories" class="row">

        </div>
        <input type="hidden" name="redirect" value="index.php" />
        <br />
        <input type="button" onClick="addCategory()" value="Dodaj kategorijo" class="btn btn-flat btn-primary" />
    </form>
</div>
<script>
    function addCategory() {
        $name = $("input[name=name]").val();
        $.ajax({
            url: "addingCategory.php",
            type: "POST",
            data: {id: $currentSelected, name: $name},
            success: function (comeback) {
                alertify.log(comeback);
            }
        });
    }

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

    /*$("select").change(function(){
     $currentSelected = $(this).val();
     //alert($currentSelected);
     $("#otherCategories").load("fetchCategories.php?id=" + $currentSelected);
     //fetchCategories($currentSelected);
     });*/
</script>
<?php include_once 'footer.php' ?>