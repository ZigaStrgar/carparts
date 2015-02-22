<?php
include_once './core/session.php';
session_unset();
session_destroy();
?>
<script>
    localStorage.removeItem("logout");
    localStorage.setItem("logout", true);
    window.history.back(-1);
</script>