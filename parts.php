<?php include_once 'header.php'; ?>
<?php
$queryParts = "SELECT *, p.name AS partName, p.id AS part_id FROM parts p INNER JOIN models m ON m.id = p.model_id INNER JOIN brands b ON b.id = m.brand_id INNER JOIN types t ON t.id = p.type_id ORDER BY p.id DESC";
$resultParts = mysqli_query($link, $queryParts);
?>
<div class="block-flat col-lg-12">
    <div class="page-header">
        <h3>Deli</h3>
        <select class="pull-right dropdown-header dropdown" style="margin-top: -30px;">
            <option>
                Starejši naprej
            </option>
            <option>
                Mlajši naprej
            </option>
        </select>
    </div>
    <?php while ($part = mysqli_fetch_array($resultParts)) { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-4">
                    <a href="/part/<?php echo $part["part_id"]; ?>">
                        <img src="<?php echo $part["image"]; ?>" alt="Part image" class="img-responsive"/>
                    </a>
                </div>
                <div class="col-lg-8">
                    <a href="/part/<?php echo $part["part_id"]; ?>">
                        <h4><?php echo $part["partName"]; ?></h4>
                    </a>
                    <p><?php echo $part["description"]; ?></p>
                </div>
            </div>
        </div>
        <br />
    <?php } ?>
    <nav class="pagination-centered">
        <ul class="pagination">
            <li><a href="#"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>
        </ul>
    </nav>
</div>
<?php include_once 'footer.php'; ?>