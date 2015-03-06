<?php
include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $id = (int) cleanString($_POST["id"]);
    $modelID = (int) cleanString($_POST["model"]);
    $models = Db::queryAll("SELECT * FROM models WHERE brand_id = ? ORDER BY name ASC", $id);
    $req = (int) $_POST["req"];
    echo "<div class=\"input-group\">
                    <span class=\"input-group-addon\">Model</span>
                    <select name=\"model[]\" class=\"form-control\">
                        <option value=\"0\" selected=\"selected\"></option>
                        ";
    foreach ($models as $model) {
        if ($model["id"] == $modelID) {
            echo "<option selected value='" . $model["id"] . "'>" . $model["name"] . "</option>";
        } else {
            echo "<option value='" . $model["id"] . "'>" . $model["name"] . "</option>";
        }
    }
    echo "</select>";
    if ($req == 1) {
        echo "<span class=\"input-group-addon\"><span class=\"color-danger\">*</span></span>";
    }
    echo "</div>";
} else {
    $_SESSION["notify"] = "error|Ogled datoteke ni mogoč!";
    header("Location:" . $_SERVER["HTTP_REFERER"]);
}
?>
