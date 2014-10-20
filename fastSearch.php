<?php include_once 'header.php'; ?>
<?php 
$string = cleanString(str_replace(" ", "+", "".$_GET["query"].""));
$array = explode("+", $string);
$string = implode(" ", $array);
?>
<div class="block-flat col-lg-12">
    <h3>Iskalni niz: <?php echo $string; ?></h3>
    <hr />
</div>
<?php include_once 'footer.php'; ?>