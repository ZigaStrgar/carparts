<?php include_once 'header.php'; ?>
<?php
//DVA NAKLJUČNA
$randParts = Db::queryAll("SELECT * FROM parts WHERE deleted = 0 ORDER BY RAND() LIMIT 2");
//MORDA VAM BO VŠEČ
$likes = likes($_SERVER["REMOTE_ADDR"], $_SESSION["user_id"]);
$ordered = array_sort($likes, "count", SORT_DESC);
//ZADNJI DODANI
$lastParts = Db::queryAll("SELECT * FROM parts WHERE deleted = 0 ORDER BY id DESC LIMIT 4");
?>
<div class="block-flat col-lg-12 top-primary">
    <h1 class="page-header">Domača stran</h1>
    <div class="row">
        <div class="col-lg-8">
            <?php foreach ($randParts as $part) { ?>
                <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6">
                    <div class="card large">
                        <div class="card-image">
                            <img src="<?php echo $part["image"] ?>">
                            <span class="card-title"><?php echo $part["name"]; ?></span>
                        </div>
                        <div class="card-content">
                            <p><?php
                                echo substr(strip_tags($part["description"]), 0, 100);
                                if (strlen(strip_tags($part["description"])) > 100) {
                                    echo "...";
                                }
                                ?></p>
                        </div>
                        <div class="card-action">
                            <?php if (!empty($_SESSION["user_id"])) { ?>
                                <a title="Preberi več" href="http://<?= URL; ?>/part/<?= $part["id"] ?>"><i class="icon icon-list-unordered left-icon ico"></i></a>
                                <div class="vertical-dev"></div>
                                <a href="javascript:;" title="Dodaj v košarico" onclick="addToCart(<?= $part["id"]; ?>)" class="pull-right"><i class="icon icon-plus-1 ico right-icon"></i></a>
                            <?php } else { ?>
                                <a title="Preberi več" href="http://<?= URL; ?>/part/<?= $part["id"] ?>"><i class="icon icon-list-unordered center-icon ico"></i></a>
                            <?php } ?>
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
                                <input type="text" name="number" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Ime dela</span>
                                <input type="text" name="partname" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <input type="submit" value="Išči" class="btn btn-flat btn-primary" />
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="col-lg-12 bg-success shadow banner custom1">
            <span class="icon icon-euro text-success custom2"></span> <span class="text-success custom3">Imate odvečen avtodel?</span> <a href="registration.php" style="margin-top: 2.5px;" class="btn btn-flat btn-success-inverse pull-right">Pridruži se</a>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="col-lg-12 bg-info shadow banner custom1">
            <span class="icon icon-search-1 text-info custom2"></span> <span class="text-info custom3">Iščete določen avtodel?</span> <a href="search.php" style="margin-top: 2.5px" class="btn btn-flat btn-info-inverse pull-right">Poišči del</a>
        </div>
    </div>
</div>
<br />
<br />
<div class="block-flat col-lg-12 top-primary">
    <h1 class="page-header">Mogoče vam bo všeč tudi</h1>
    <?php
    $max_likes = 8;
    $interest_num = 0;
    foreach ($ordered as $like) {
        $interest_num = $interest_num + $like["count"];
    }
    $percent;
    foreach ($ordered as $key => $val) {
        $percent[$key] = floor($max_likes * ($val["count"] / $interest_num));
    }
    if (!empty($ordered["category"]["id"])) {
        $cat_likes = Db::queryAll("SELECT *, p.id AS pid FROM parts p WHERE p.category_id = ? AND deleted = 0 ORDER BY RAND() LIMIT " . $percent["category"], $ordered["category"]["id"]);
    }
    if (!empty($ordered["model"]["id"])) {
        $model_likes = Db::queryAll("SELECT *, p.id AS pid FROM parts p INNER JOIN models_parts mp ON mp.part_id = p.id WHERE mp.model_id = ? AND deleted = 0 ORDER BY RAND() LIMIT " . $percent["model"], $ordered["model"]["id"]);
    }
    ?>
    <?php foreach ($cat_likes as $part) { ?>
        <div class="col-sm-6 col-xs-12 col-lg-3 col-md-3">
            <div class="card large">
                <div class="card-image">
                    <img src="<?php echo $part["image"] ?>">
                    <span class="card-title"><?php echo $part["name"]; ?></span>
                </div>
                <div class="card-content">
                    <p><?php
                        echo substr(strip_tags($part["description"]), 0, 100);
                        if (strlen(strip_tags($part["description"])) > 100) {
                            echo "...";
                        }
                        ?></p>
                </div>
                <div class="card-action">
                    <?php if (!empty($_SESSION["user_id"])) { ?>
                        <a title="Preberi več" href="http://<?= URL; ?>/part/<?= $part["id"] ?>"><i class="icon icon-list-unordered left-icon ico"></i></a>
                        <div class="vertical-dev"></div>
                        <a href="javascript:;" title="Dodaj v košarico" onclick="addToCart(<?= $part["id"]; ?>)" class="pull-right"><i class="icon icon-plus-1 ico right-icon"></i></a>
                    <?php } else { ?>
                        <a title="Preberi več" href="http://<?= URL; ?>/part/<?= $part["id"] ?>"><i class="icon icon-list-unordered center-icon ico"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php foreach ($model_likes as $part) { ?>
        <div class="col-sm-6 col-xs-12 col-lg-3 col-md-3">
            <div class="card large">
                <div class="card-image">
                    <img src="<?php echo $part["image"] ?>">
                    <span class="card-title"><?php echo $part["name"]; ?></span>
                </div>
                <div class="card-content">
                    <p><?php
                        echo substr(strip_tags($part["description"]), 0, 100);
                        if (strlen(strip_tags($part["description"])) > 100) {
                            echo "...";
                        }
                        ?></p>
                </div>
                <div class="card-action">
                    <a title="Preberi več" href="http://<?= URL; ?>/part/<?= $part["pid"] ?>"><i class="icon icon-list-unordered left-icon ico"></i></a>
                    <div class="vertical-dev"></div>
                    <a title="Dodaj v košarico" href='#' class="pull-right"><i class="icon icon-plus-1 ico right-icon"></i></a>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>
<div class="row">
    <div id="advert" class="col-lg-12">
        <img class="bg-primary center-block" alt="Oglas" width="728" height="90"/>
    </div>
</div>
<br />
<br />
<div class="block-flat col-lg-12 top-primary">
    <h1 class="page-header">Zadnji avto deli</h1>
    <?php foreach ($lastParts as $part) { ?>
        <div class="col-sm-6 col-xs-12 col-lg-3 col-md-3">
            <div class="card large">
                <div class="card-image">
                    <img src="<?php echo $part["image"] ?>">
                    <span class="card-title"><?php echo $part["name"]; ?></span>
                </div>
                <div class="card-content">
                    <p><?php
                        echo substr(strip_tags($part["description"]), 0, 100);
                        if (strlen(strip_tags($part["description"])) > 100) {
                            echo "...";
                        }
                        ?></p>
                </div>
                <div class="card-action">
                    <?php if (!empty($_SESSION["user_id"])) { ?>
                        <a title="Preberi več" href="http://<?= URL; ?>/part/<?= $part["id"] ?>"><i class="icon icon-list-unordered left-icon ico"></i></a>
                        <div class="vertical-dev"></div>
                        <a href="javascript:;" title="Dodaj v košarico" onclick="addToCart(<?= $part["id"]; ?>)" class="pull-right"><i class="icon icon-plus-1 ico right-icon"></i></a>
                    <?php } else { ?>
                        <a title="Preberi več" href="http://<?= URL; ?>/part/<?= $part["id"] ?>"><i class="icon icon-list-unordered center-icon ico"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>
<script type="text/javascript" async>
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