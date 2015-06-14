<?php include_once 'header.php'; ?>
<?php
//DVA NAKLJUČNA
$randParts = Db::queryAll("SELECT *, id AS pid, name AS pname FROM parts WHERE deleted = 0 AND pieces > 0 ORDER BY RAND() LIMIT 2");
//MORDA VAM BO VŠEČ
$likes = likes($_SERVER["REMOTE_ADDR"], $_SESSION["user_id"]);
$ordered = array_sort($likes, "count", SORT_DESC);
//ZADNJI DODANI
$lastParts = Db::queryAll("SELECT *, id AS pid, name AS pname FROM parts WHERE deleted = 0 AND pieces > 0 ORDER BY id DESC LIMIT 6");
?>
    <h1 class="hidden">Prodaja rabljenih in novih avtodelov</h1>
    <div class="block-flat col-lg-12 top-primary">
        <div class="row">
            <div class="col-lg-8">
                <?php foreach ($randParts as $part) { ?>
                    <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6">
                        <div class="thumbnail">
                            <div class="equal">
                                <?php include 'part_view.php'; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div class="col-lg-4">
                <div class="col-lg-12">
                    <h3 class="page-header">Iskalnik</h3>

                    <form method="POST" action="result.php">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Kataloška številka</span>
                                    <input type="text" name="number" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Ime dela</span>
                                    <input type="text" name="partname" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <button type="submit" class="btn btn-primary btn-flat btn-product"><span class="icon icon-search-1"></span> Išči</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="col-lg-12 bg-success shadow banner custom1">
                <span class="icon icon-euro text-success custom2"></span> <span class="text-success custom3">Imate odvečen avtodel?</span>
                <a href="login.php#register" style="margin-top: 2.5px;"
                   class="btn btn-flat btn-success-inverse pull-right">Pridruži se</a>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="col-lg-12 bg-info shadow banner custom1">
                <span class="icon icon-search-1 text-info custom2"></span> <span class="text-info custom3">Iščete določen avtodel?</span>
                <a href="search.php" style="margin-top: 2.5px" class="btn btn-flat btn-info-inverse pull-right">Poišči
                    del</a>
            </div>
        </div>
    </div>
    <br/>
    <br/>
    <div class="block-flat col-lg-12 top-primary">
        <h1 class="page-header">Mogoče vam bo všeč tudi</h1>
        <?php
        $max_likes = 9;
        $interest_num = 0;
        $model_likes = array();
        $cat_likes = array();
        foreach ($ordered as $like) { //Sešteje št. interesov
            $interest_num = $interest_num + $like["count"];
        }
        foreach ($ordered as $key => $val) { //Koliko delov določene skupine interesov naj prikaže
            $number = $max_likes * ($val["count"] / $interest_num);
            if($number > 0 && $number < 1){
                $number = 1;
            } else {
                $number = floor($number);
            }
            $max_likes = $max_likes - $number;
            $percent[$key] = $number;
        }
        if (!empty($ordered["category"]["id"])) {
            $cat_likes = Db::queryAll("SELECT *, p.id AS pid, p.name AS pname FROM parts p WHERE p.category_id = ? AND p.deleted = 0 AND p.pieces > 0 ORDER BY RAND() LIMIT " . $percent["category"], $ordered["category"]["id"]);
        }
        $leftover = $max_likes - count($cat_likes);
        foreach ($cat_likes as $cat_like) { //Prepreči ponavjanje delov
            $ids .= $cat_like["pid"] . ",";
        }
        if(!empty($ordered["brand"]["id"]) && $ordered["brand"]["id"] != 0){
            $ordered["model"]["id"] = $ordered["model"]["id"].",".getModels($ordered["brand"]["id"]);
        }
        $ids = substr($ids, 0, strlen($ids) - 1);
        $models_brands = $percent["model"];
        if (!empty($ordered["model"]["id"])) {
            if (!empty($ids)) {
                $model_likes = Db::queryAll("SELECT *, p.id AS pid, p.name AS pname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE mp.model_id IN (".$ordered["model"]["id"].") AND p.deleted = 0 AND p.pieces > 0 AND p.id NOT IN ($ids) ORDER BY RAND() LIMIT " . $percent["model"]);
            } else {
                $model_likes = Db::queryAll("SELECT *, p.id AS pid, p.name AS pname FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE mp.model_id IN (".$ordered["model"]["id"].") AND p.deleted = 0 AND p.pieces > 0 ORDER BY RAND() LIMIT " . $percent["model"]);
            }
        }
        $likes_array = array_merge($cat_likes, $model_likes);
        foreach($likes_array as $like){
            $ids .= ",".$like["pid"];
        }
        if(count($likes_array) < 9){
            if(count($likes_array) % 3 == 1){
                $lim = 2;
            } else if(count($likes_array) % 3 == 2){
                $lim = 1;
            } else {
                $lim = 3;
            }
            $spare = array();
            if(empty($ids)){
                $spare = Db::queryAll("SELECT *, id AS pid, name AS pname FROM parts WHERE deleted = 0 AND pieces > 0 ORDER BY RAND() LIMIT $lim");
            } else {
                $spare = Db::queryAll("SELECT *, id AS pid, name AS pname FROM parts WHERE deleted = 0 AND pieces > 0 AND id NOT IN ($ids) ORDER BY RAND() LIMIT $lim");
            }
            if(count($spare)>0){
                $likes_array = array_merge($likes_array, $spare);
            }
        }
        ?>
        <?php foreach ($likes_array as $part) { ?>
            <div class="col-sm-6 col-xs-12 col-lg-4 col-md-4">
                <div class="thumbnail">
                    <div class="equal2">
                        <?php include 'part_view.php'; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </div>
    <div class="row">
        <div id="advert" class="col-lg-12">
            <img class="bg-primary center-block" src="http://<?= URL; ?>/img/banner.png" alt="Oglas" width="728" height="90"/>
        </div>
    </div>
    <br/>
    <br/>
    <div class="block-flat col-lg-12 top-primary">
        <h1 class="page-header">Zadnji avto deli</h1>
        <?php foreach ($lastParts as $part) { ?>
            <div class="col-sm-6 col-xs-12 col-lg-4 col-md-4">
                <div class="thumbnail">
                    <div class="equal3">
                        <?php include 'part_view.php'; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $ratio = 728 / 90;
            setInterval(function () {
                $width = $("#advert").width();
                if ($width < "740") {
                    $width = $width - 20;
                    $height = $width / $ratio;
                    $("#advert").find("img").css({"width": $width, height: $height});
                } else if ($width < "250") {
                    $("#advert").find("img").css({"width": 234, height: 60});
                } else {
                    $("#advert").find("img").css({"width": 728, height: 90});
                }
                if ($width < "420") {
                    $(".custom1").css({height: 100});
                } else {
                    $(".custom1").css({height: 50});
                }
                var maxheight = 0;
                $('.equal').each(function () {
                    if ($(this).height() > maxheight) {
                        maxheight = $(this).height();
                    }
                });
                $('.equal').parent().height(maxheight);
                var maxheight2 = 0;
                $('.equal2').each(function () {
                    if ($(this).height() > maxheight2) {
                        maxheight2 = $(this).height();
                    }
                });
                $('.equal2').parent().height(maxheight2);
                var maxheight3 = 0;
                $('.equal3').each(function () {
                    if ($(this).height() > maxheight3) {
                        maxheight3 = $(this).height();
                    }
                });
                $('.equal3').parent().height(maxheight3);
            }, 100);
        });

        function addToCart(part) {
            $.ajax({
                url: "http://<?= URL; ?>/addToCart.php",
                type: "POST",
                data: {part: part},
                success: function (cb) {
                    cb = $.trim(cb);
                    cb = cb.split("|");
                    if (cb[0] === "error") {
                        alertify.error(cb[1]);
                    }
                    if (cb[0] === "success") {
                        alertify.success(cb[1]);
                        $("#cartNum").text(cb[2]);
                    }
                }
            });
        }
    </script>
<?php include_once 'footer.php'; ?>