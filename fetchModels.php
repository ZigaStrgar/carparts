<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
$id = (int) cleanString($_POST["id"]);
$queryModels = "SELECT * FROM models WHERE brand_id = $id";
$resultModels = mysqli_query($link, $queryModels);
echo "<div class=\"input-group\">
                    <span class=\"input-group-addon\">Model</span>
                    <select name=\"model\" class=\"form-control\">
                        <option value=\"0\" selected=\"selected\"></option>
                        ";
while ($model = mysqli_fetch_array($resultModels)) {
    echo "<option value='".$model["id"]."'>".$model["name"]."</option>";
}
echo "</select>
                </div>";
?>