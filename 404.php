<?php include_once 'header.php'; ?>
<div class='col-lg-12' style="margin-top: 200px;">
    <center>
        <img src="./img/404.png" alt="Error 404 image" width="284" />
        <br />
        <br />
        <br />
        <h1>Ooops, nekaj je šlo narobe ali pa stran ne obstaja!</h1>
        <br />
        <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-flat btn-primary"><i class="icon icon-arrow-left"></i> Pojdi nazaj</a>
    </center>
</div>
<?php include_once 'footer.php'; ?>