<?php
include_once './core/session.php';
include_once './core/database.php';
include_once './core/functions.php';
$id = (int) cleanString($_POST["id"]);
if(!empty(is_numeric($id))){
    getParent($id);
    $query = "SELECT * FROM categories WHERE category_id = $id";
    $result = mysqli_query($link, $query);
    /*while($row = mysqli_fetch_array($result)){
        echo "<div class=\"col-md-6 col-xs-12\">
            <div class=\"input-group\">
                    <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-tags\"></i></span>
                    <select onselect=\"currentSelect()\" name=\"category\" class=\"form-control selector\">
                      <option selected=\"selected\"></option>
                          <option value=\"".$row["id"]."\">".$row["name"]."</option>
                    </select>
                </div>
                </div>";
    }*/
}