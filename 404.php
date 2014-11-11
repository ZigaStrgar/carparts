<?php include_once 'header.php'; ?>
<div class='col-lg-offset-3 col-lg-6 col-md-12' style="margin-top: 200px;">
    <center>
        <h1>Ooops, nekaj je šlo narobe!</h1>
        <br />
        <br />
        <img src="./img/404.png" alt="Error 404 image" width="284" />
        <br />
        <br />
        <h5>Stran, ki jo iščeš ne obstaja, je bila preimenovana, zbrisana ali pa mogoče sploh ni obstajala!</h5>
        <br />
        <a href="mailto:ziga_strgar@hotmail.com"><i style="font-size: 30px;" class="icon icon-letter-mail"></i></a>
        <br />
        <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-flat btn-primary"><i class="icon icon-arrow-left"></i> Pojdi nazaj</a>
    </center>
</div>
<?php include_once 'footer.php'; ?>