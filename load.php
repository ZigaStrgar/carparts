<?php include_once 'header.php';
?>
<div class="block-flat col-lg-12">
<div class="load-bar">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
</div>
<span>TEST</span>
</div>
<script>
$(document).on("click", "span", function(){
    $("#loading").removeClass("hide");
});
</script>
<?php include_once 'footer.php'; ?>