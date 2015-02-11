<?php
include_once './core/session.php';

$string = "<h1 style='color: blue;'>Test</h1><script>alert('test');</script><span onClick=\"alert(\'ha\');\">Click me</span>";
echo smartFilter($string);