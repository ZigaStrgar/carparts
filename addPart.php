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
            ?>
        </p>
    </div>
<?php } ?>
<div id="formType">
    <div class="col-lg-12 block-flat top-warning">
        <h1 class="page-header">Vnesti želim</h1>
        <div class="load-bar loaderpage">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div id="category" class="product-chooser"> 
            <?php
            while ($category = mysqli_fetch_array($resultCategories)) {
                ?>
                <div class="col-lg-2 col-xs-2 col-md-2" style="width: 210px;height: 120px;">
                    <div class="product-chooser-item pci">
                        <center><span class="description"><div style="border-radius: 0px; font-weight: normal; padding: 5px 10px; font-size: 14px;" class="label label-success" id="badge<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></div></span></center>
                        <div class="col-lg-12">
                            <input type="radio" name="type" value="<?php echo $category["id"]; ?>">
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div id="formLoad">

</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        setInterval(function () {
            $width = $("select").width() - 13;
            $(".ui-autocomplete").css({"list-style-type": "none", "width": $width});
        }, 100);
    });
</script>
<script type="text/javascript">
    $(function () {
        $('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function () {
            $('div.product-chooser-item').removeClass('selected');
            $(this).addClass('selected');
            $(this).find('input[type="radio"]').prop("checked", true);
        });
    });
</script>
<script async src="./plugins/autosize/jquery.autosize.min.js"></script>
<script>
    $global = 1;
    $globalimage = 1;

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
        $currentSelected = 0;
    });

    $(document).on("change", "select[name=brand]", function () {
        $id = $(this).attr("id");
        getModels($(this).val(), $id, 0);
    });

    function getModels(id, place, model) {
        $.ajax({
            url: "fetchModels.php",
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

    function addCar() {
        $.ajax({
            url: "addCar.php",
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
            url: "addImage.php",
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

    $(document).on("click", "#back", function () {
        location.reload();
    });

    $(document).on("click", "div.pci", function () {
        $id = $(this).find("input[type=radio]").attr("value");
        $value = $("#badge" + $id).text();
        $.ajax({
            url: "formLoad.php",
            type: "POST",
            data: {id: $id, value: $value},
            beforeSend: function () {
                $(".loaderpage").show();
            },
            success: function (cb) {
                $(".loaderpage").hide();
                $("#formLoad").html(cb);
                $("#formType").hide();
                fetchCategories($id);
                $('.aucp').selectToAutocomplete();
                $("textarea").autosize();
            }
        });
    });

    $(document).on("click", "div.pci2", function () {
        $('div.product-chooser-item').removeClass('selected');
        $(this).addClass('selected');
        $(this).find('input[type=radio]').prop("checked", true);
    });

    $(document).on("click", "input[type=submit]", function () {
        $("#loading").removeClass("hide");
        $(".load-content").append("<h3>Dodajanje dela v teku...</h3>");
    });
    
    $(document).on("keyup", "input", function(){
        var name = $(this).attr("name");
        if($(this).val().length > 0){
            $("span[data-helper-for="+name+"]").text($(this).attr("placeholder"));
        } else {
            $("span[data-helper-for="+name+"]").text("");
        }
    });
</script>
<?php if (!empty($_SESSION["query"]["first"])) { ?>
    <script>
        $(document).ready(function () {
            $id = <?php echo $_SESSION["query"]["first"]; ?>;
            $value = $("#badge" + $id).text();
            $.ajax({
                url: "formLoad.php",
                type: "POST",
                data: {id: $id, value: $value},
                beforeSend: function () {
                    $(".loaderpage").show();
                },
                success: function (cb) {
                    $(".loaderpage").hide();
                    $("#formLoad").html(cb);
                    $("#formType").hide();
                    fetchCategories($id);
                    $('.aucp').selectToAutocomplete();
                    $("textarea").autosize();
                }
            });
        });
    </script>
<?php } ?>
<?php include_once 'footer.php'; ?>