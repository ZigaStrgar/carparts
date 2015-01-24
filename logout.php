<?php
ob_start();
include_once './core/session.php';
session_unset();
session_destroy();
?>
<script>
    localStorage.removeItem("logout");
    localStorage.setItem("logout", true);
    javascript:history.go(-1);
</script>