<?php include_once 'header.php'; ?>
<?php
//DVA NAKLJUČNA
$queryParts = "SELECT * FROM parts WHERE deleted = 0 ORDER BY RAND() LIMIT 2";
$resultParts = mysqli_query($link, $queryParts);
//ZADNJI DODANI
$queryLastParts = "SELECT * FROM parts WHERE deleted = 0 ORDER BY id DESC LIMIT 4";
$resultLastParts = mysqli_query($link, $queryLastParts);
?>
<div class="block-flat col-lg-12">
    <h1 class="page-header">Domača stran</h1>
    <div class="row">
        <div class="col-lg-8">
            <?php while ($part = mysqli_fetch_array($resultParts)) { ?>
                <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6">
                    <div class="thumbnail">
                        <div class="equal">
                            <a href="/part/<?php echo $part["id"]; ?>"><img src="<?php echo $part["image"] ?>" alt="Part image"></a>
                            <div class="caption">
                                <a href="/part/<?php echo $part["id"]; ?>"><h3><?php echo $part["name"]; ?></h3></a>
                                <p><?php echo substr($part["description"], 0, 150); ?></p>
                            </div>
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
<div class="block-flat col-lg-12">
    <h1 class="page-header">Mogoče vam bo všeč tudi</h1>
</div>
<div class="row">
    <div id="advert" class="col-lg-12">
        <img class="bg-primary center-block" alt="Oglas" width="728" height="90"/>
    </div>
</div>
<br />
<br />
<div class="block-flat col-lg-12">
    <h1 class="page-header">Zadnji avto deli</h1>
    <?php while ($part = mysqli_fetch_array($resultLastParts)) { ?>
        <div class="col-sm-6 col-xs-12 col-lg-3 col-md-3">
            <div class="thumbnail">
                <div class="equal2">
                    <a href="/part/<?php echo $part["id"]; ?>"><img src="<?php echo $part["image"] ?>" alt="Part image"></a>
                    <div class="caption">
                        <a href="/part/<?php echo $part["id"]; ?>"><h3><?php echo $part["name"]; ?></h3></a>
                        <p><?php echo substr($part["description"], 0, 150); ?></p>
                    </div>
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
            if ($(window).width() < "600") {
                $(".banner").css({height: 100});
            } else {
                $(".banner").css({height: 50});
            }
        }, 100);
    });
</script>
<?php include_once 'footer.php'; ?>