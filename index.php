<?php include_once 'header.php'; ?>
<div class="block-flat col-lg-12">
    <h3 class="page-header">Domača stran</h3>
    <div class="row">
        <div class="col-lg-8">
            <div class="col-md-6 col-sm-12">
                Del 1
                <img src="http://ts1.mm.bing.net/th?id=HN.608055592932609756&pid=1.7" class="bg-primary" alt="Part image" width="300" height="300" />
            </div>
            <div class="col-md-6 col-sm-12">
                Del 2
                <img src="http://www.nakupovanje.net/media/cache/dunlop-235-45r17-94y-sp-sport-maxx-rt-letna-pnevmatika-408357b77baa487569e2e9db5802ca56.jpeg" class="bg-primary" alt="Part image" width="300" height="300" />
            </div>
        </div>
        <div class="col-lg-4">
            <div class="col-lg-12">
                <h4 class="page-header">Iskalnik</h4>
            </div>
            <form>
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
                            <input type="text" name="number" class="form-control" />
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">Še neki</span>
                            <input type="text" name="number" class="form-control" />
                        </div>
                    </div>
                </div>
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