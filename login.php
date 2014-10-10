<?php include_once 'header.php'; ?>
<form action="loginCheck.php" method="POST" id="ajaxForm">
    <input type="email" name="email" class="form-control" />
    <input type="password" name="password" class="form-control" />
    <input type="hidden" name="redirect" value="index.php" />
    <input type="submit" value="Prijavi me" class="btn btn-flat btn-primary" />
</form>
<?php include_once 'footer.php'; ?>