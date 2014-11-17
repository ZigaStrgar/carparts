<?php include_once 'header.php'; ?>
<?php
$queryParts = "SELECT * FROM parts ORDER BY RAND() LIMIT 2";
$resultParts = mysqli_query($link, $queryParts);
?>
<div class="block-flat col-lg-12">
    <h3 class="page-header">Domača stran</h3>
    <div class="row">
        <div class="col-lg-8">
            <?php while ($part = mysqli_fetch_array($resultParts)) { ?>
                <div class="col-md-6 col-sm-12">
                    <?php echo $part["name"]; ?>
                    <img src="<?php echo $part["image"]; ?>" alt="Part image" width="250" />
                </div>
            <?php } ?>
        </div>
        <div class="col-lg-4">
            <div class="col-lg-12">
                <h4 class="page-header">Iskalnik</h4>
            </div>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">Še neki</span>
                            <input type="text" name="" class="form-control" />
                        </div>
                    </div>
                </div>
                <br />
                <input type="submit" value="Išči" class="btn btn-flat btn-primary" />
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="col-lg-12 bg-info shadow banner" style="height: 50px; padding-top: 5px;">
            <span class="icon icon-search-1 text-info" style="font-size: 24px; vertical-align: -13%;"></span> <span class="text-info" style="font-size: 25px;">Iščete določen avto del?</span> <a href="search.php" style="margin-top: 2.5px" class="btn btn-flat btn-info-inverse pull-right">Poišči del</a>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="col-lg-12 bg-success shadow banner" style="height: 50px; padding-top: 5px;">
            <span class="icon icon-euro text-success" style="font-size: 24px; vertical-align: -13%;"></span> <span class="text-success" style="font-size: 25px;">Imate odvečen avto del?</span> <a href="registration.php" style="margin-top: 2.5px;" class="btn btn-flat btn-success-inverse pull-right">Pridruži se</a>
        </div>
    </div>
</div>
<br />
<br />
<div class="block-flat col-lg-12">
    <h3 class="page-header">Mogoče vam bo všeč tudi</h3>
</div>
<div class="row">
    <div id="advert" class="col-lg-12">
        <img src="" class="bg-primary center-block" alt="Oglas" width="728" height="90"/>
    </div>
</div>
<br />
<br />
<div class="block-flat col-lg-12">
    <h3 class="page-header">Zadnji avto deli</h3>
</div>
<script>
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

        setInterval(function () {
            if ($(window).width() < "600") {
                $(".banner").css({height: 100});
            } else {
                $(".banner").css({height: 50});
            }
        }, 100);
    });
</script>
<?php include_once 'footer.php'; ?>