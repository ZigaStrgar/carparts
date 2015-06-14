<?php

include_once './core/session.php';
$category = (int) $_POST["category"];
$location = Db::querySingle("SELECT location FROM categories WHERE id = ?", $category);
$part["location"] = $_POST["selected"];
if ($location != 0):
    echo "<br />
                <div class='row'>
                    <div class='col-lg-12'>
                        <h3 class='page-header'>Lokacija dela <span class='color-danger'>*</span> <small>Označite, kje se nahaja del v avtomobilu</small></h3>";
    echo "<div class=\"product-chooser\">";
    if ($location == 1):
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>";
        echo "<div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 1 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 1 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 1) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/ff.png' alt='Spredaj' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 1 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 1 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 1) {
            echo "selected";
        }
        echo " value='1'>
                                    </div>
                                    <div class='clear'></div>
                                </div>
                            </div>
                            <div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 2 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 2 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 2) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/rf.png' alt='Zadaj' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 2 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 2 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 2) {
            echo "selected";
        }
        echo " value='2'>
                                    </div>
                                    <div class='clear'></div>
                                </div>
                            </div>";
    endif;
    if ($location == 2):
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 3 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 3 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 3) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/fl.png' alt='Spredaj levo' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 3 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 3 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 3) {
            echo "selected";
        }
        echo " value='3'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 4 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 4 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 4) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/fr.png' alt='Spredaj desno' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 4 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 4 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 4) {
            echo "selected";
        }
        echo " value='4'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 5 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 5 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 5) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/rl.png' alt='Zadaj levo' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 5 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 5 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 5) {
            echo "selected";
        }
        echo " value='5'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 6 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 6 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 6) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/rr.png' alt='Zadaj desno' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 6 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 6 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 6) {
            echo "selected";
        }
        echo " value='6'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
    endif;
    if ($location == 3):
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 3 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 3 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 3) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/fl.png' alt='Spredaj levo' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 3 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 3 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 3) {
            echo "selected";
        }
        echo " value='3'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 4 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 4 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 4) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/fr.png' alt='Spredaj desno' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 4 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 4 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 4) {
            echo "selected";
        }
        echo " value='4'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
    endif;
    if ($location == 4):
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 5 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 5 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 5) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/rl.png' alt='Zadaj levo' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 5 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 5 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 5) {
            echo "selected";
        }
        echo " value='5'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
        echo "<div class='col-lg-2 col-xs-2 col-md-2' style='width: 210px;min-height: 300px;'>
                                <div class='product-chooser-item pci3 ";
        if (($_SESSION["query"]["location"] == 6 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 6 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 6) {
            echo "selected";
        }
        echo "'>
                                    <center><span class='description'><img height='300' src='http://".URL."/img/rr.png' alt='Zadaj desno' /></span></center>
                                    <div class='col-lg-12'>
                                        <input type='radio' name='location'";
        if (($_SESSION["query"]["location"] == 6 && isset($_SESSION["query"])) || ($_SESSION["query_update"]["location"] == 6 && isset($_SESSION["query_update"]["location"])) || $part["location"] == 6) {
            echo "selected";
        }
        echo " value='6'>
                                    </div>
                                    <div class='clear'></div>
                                </div></div>";
    endif;
    echo "</div>"
    . "</div>"
    . "</div>";
endif;
