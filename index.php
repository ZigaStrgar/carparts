<?php include_once 'header.php'; ?>
<div class="block-flat col-lg-12">
    <h3 class="page-header">Domača stran</h3>
    <div class="row">
        <div class="col-lg-8">
            <div class="col-md-6 col-sm-12">
                Del 1
                <img src="" class="bg-primary" alt="Part image" width="300" height="300" />
            </div>
            <div class="col-md-6 col-sm-12">
                Del 2
                <img src="" class="bg-primary" alt="Part image" width="300" height="300" />
            </div>
        </div>
        <div class="col-lg-4">
            <div class="col-lg-12">
                <h4 class="page-header">Iskalnik</h4>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="col-lg-12 bg-info shadow banner" style="height: 50px; padding-top: 5px;">
            <span class="icon icon-search-1 text-info" style="font-size: 24px;"></span> <span class="text-info" style="font-size: 25px;">Iščete določen avto del?</span> <a href="search.php" style="margin-top: 2.5px" class="btn btn-flat btn-info-inverse pull-right">Poišči del</a>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="col-lg-12 bg-success shadow banner" style="height: 50px; padding-top: 5px;">
            <span class="icon icon-euro text-success" style="font-size: 24px;"></span> <span class="text-success" style="font-size: 25px;">Imate odvečen avto del?</span> <a href="registration.php" style="margin-top: 2.5px;" class="btn btn-flat btn-success-inverse pull-right">Pridruži se</a>
        </div>
    </div>
</div>
<br />
<br />
<div class="block-flat col-lg-12">
    <h3 class="page-header">Mogoče vam bo všeč tudi</h3>
</div>
<div id="advert" class="block-flat col-lg-12">
    <h3 class="page-header">Advertisment</h3>
    <img src="" class="bg-primary center-block" alt="Oglas" width="728" height="90"/>
</div>
<div class="block-flat col-lg-12">
    <h3 class="page-header">Zadnji avto deli</h3>
</div>
<script>
    $().ready(function () {
        $ratio = 728 / 90;
        $widthOriginal = $("#advert").width();
        setInterval(function () {
            $width = $("#advert").width();
            if ($width < "740") {
                $width = $width - 20;
                $height = $width / $ratio;
                $("#advert").find("img").css({"width": $width, height: $height});
                //$(".banner").css({height: 100});
            } else if ($width < "250") {
                $("#advert").find("img").css({"width": 234, height: 60});
            }  else {
                $("#advert").find("img").css({"width": 728, height: 90});
            }
        }, 100);
        
        setInterval(function(){
            if($(window).width() < "600"){
                $(".banner").css({height: 100});
            } else {
                $(".banner").css({height: 50});
            }
        }, 100);
    });
</script>
<?php include_once 'footer.php'; ?>