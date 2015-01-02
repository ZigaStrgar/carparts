<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $id = (int) cleanString($_POST["id"]);
    $modelID = (int) cleanString($_POST["model"]);
    $queryModels = "SELECT * FROM models WHERE brand_id = $id ORDER BY name ASC";
    $resultModels = mysqli_query($link, $queryModels);
    if ((int) $_POST["req"] == 1) {
        echo "<div class=\"input-group\">
                    <span class=\"input-group-addon\">Model</span>
                    <select name=\"model[]\" class=\"form-control\">
                        <option value=\"0\" selected=\"selected\"></option>
                        ";
        while ($model = mysqli_fetch_array($resultModels)) {
            if ($model["id"] == $modelID) {
                echo "<option selected value='" . $model["id"] . "'>" . $model["name"] . "</option>";
            } else {
                echo "<option value='" . $model["id"] . "'>" . $model["name"] . "</option>";
            }
        }
        echo "</select>
    <span class=\"input-group-addon\"><span class=\"color-danger\">*</span></span>
                </div>";
    } else {
        echo "<div class=\"input-group\">
                    <span class=\"input-group-addon\">Model</span>
                    <select name=\"model\" class=\"form-control\">
                        <option value=\"0\" selected=\"selected\"></option>
                        ";
        while ($model = mysqli_fetch_array($resultModels)) {
            if ($model["id"] == $modelID) {
                echo "<option selected value='" . $model["id"] . "'>" . $model["name"] . "</option>";
            } else {
                echo "<option value='" . $model["id"] . "'>" . $model["name"] . "</option>";
            }
        }
        echo "</select>
                </div>";
    }
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}
?>