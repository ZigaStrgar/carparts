<?php include_once './header.php'; ?>
<?php
if (empty($_SESSION["user_id"]) && !isset($_SESSION["user_id"])) {
    $path = $_SERVER['REQUEST_URI'];
    $file = basename($path);
    if ($file == 'carparts') {
        $file = 'index.php';
    }
    $_SESSION["move_me_to"] = $file;
    header("Location: login.php");
}
?>
<?php
if (!empty($_SESSION["user_id"])) {
    if (countItems($_SESSION["user_id"]) == 0) {
        $_SESSION["notify"] = "error|Košarica je prazna!";
        header("Location: parts.php");
        die();
    }
    $offers = Db::queryAll("SELECT *, s.pieces AS spieces, p.pieces AS stock FROM cart s INNER JOIN parts p ON p.id = s.part_id WHERE s.user_id = ?", $_SESSION["user_id"]);
    $user = Db::queryOne("SELECT *, u.name AS uname, c.name AS city FROM users u INNER JOIN cities c ON u.city_id = c.id WHERE u.id = ?", $_SESSION["user_id"]);
    $max = createInvoice($_SESSION["user_id"]);
    $invoice = Db::queryOne("SELECT * FROM invoices WHERE id = ?", $max);
    ?>
    <div class="stepwizard">
        <div class="stepwizard-row">
            <div class="stepwizard-step">
                <a href="cart.php" type="button" class="btn btn-success btn-circle"><i class="icon icon-shopping-cart"></i></a>
                <p>Košarica</p>
            </div>
            <div class="stepwizard-step">
                <button type="button" class="btn btn-primary btn-circle"><i class="icon icon-checklist"></i></button>
                <p>Pregled</p>
            </div>
            <div class="stepwizard-step">
                <button type="button" class="btn btn-default btn-circle" disabled="disabled"><i class="icon icon-credit-card-2"></i></button>
                <p>Konec</p>
            </div> 
        </div>
    </div>
    <div class="col-lg-12 block-flat top-danger">
        <h1 class="page-header">Pregled naročila</h1>
        <div class="col-lg-12">
            <div style="margin-top: 30px; margin-bottom: 40px;">
                <div class="invoice-header">
                    <span class="navbar-brand" style="cursor: default !important; font-weight: 900; font-size: 32px; margin-top: 20px;"><span class="color-info">AVTO</span>DELI</span>
                    <div style="color: #999;" class="pull-right invoice-sender text-right">
                        Žiga Strgar<br />
                        Ter 69<br />
                        +386 41 202-710<br />
                        ziga_strgar@hotmail.com
                    </div>
                    <div class="clear"></div>
                    <div class="page-header"></div>
                </div>
                <div class="invoice-content">
                    <div class="invoice-reciever pull-left">
                        <h4>Prejemnik</h4>
                        <?php echo $user["uname"] . " " . $user["surname"]; ?><br />
                        <?php echo $user["location"]; ?><br />
                        <?php echo $user["number"] . " " . $user["city"]; ?>
                    </div>
                    <div class="invoice-info pull-right text-right">
                        <h4>Predračun #<?php echo date("y") . "-" . $_SESSION["user_id"] . "-$max"; ?></h4>
                        <h5>Datum: <?php echo date("d. m. Y"); ?></h5>
                        <h5>Rok plačila: <?php echo date("d. m. Y", strtotime($invoice["due_date"])); ?></h5>
                    </div>
                    <div class='clear'></div>
                    <table style="margin-top: 20px;" class="table table-responsive table-bordered table-striped">
                        <tr>
                            <th>
                                Vrsta blaga
                            </th>
                            <th width="50" class="text-center">
                                Količina
                            </th>
                            <th width="100" class="text-center">
                                Cena na kos
                            </th>
                            <th width="20" class="text-center">
                                DDV
                            </th>
                            <th class="col-xs-2 text-right">
                                Vrednost brez DDV
                            </th>
                        </tr>
                        <?php foreach ($offers as $offer) { ?>
                            <?php if ($offer["deleted"] == 0) { ?>
                                <?php if ($offer["spieces"] <= $offer["stock"]) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $offer["name"]; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $offer["spieces"] ?>
                                        </td>
                                        <td>
                                            <?php echo price($offer["price"]); ?> €
                                        </td>
                                        <td class="text-center">
                                            22%
                                        </td>
                                        <td class="text-right">
                                            <?php echo price(round($offer["price"] * $offer["spieces"] * 0.78, 2)) ?> €
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php
                                    $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Prosimo preverite število naročenih kosov za izdelek: " . $offer["name"];
                                    header("Location: cart.php");
                                    exit;
                                    ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?php
                                $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Ta izdelek je izbrisan in ga je nemogoče kupiti: " . $offer["name"];
                                header("Location: cart.php");
                                exit;
                            }
                            ?>
                        <?php } ?>
                    </table>
                    <div class="col-lg-offset-6 col-lg-6" style="margin-top: 20px;">
                        <table class="col-lg-12 table table-responsive">
                            <tr>
                                <td>
                                    Znesek brez popusta 
                                </td>
                                <td class="text-right">
                                    <?php echo price(round(calcPrice($_SESSION["user_id"]) * 0.78, 2)); ?> €
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Popust
                                </td>
                                <td class="text-right">
                                    -0 €
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    DDV 22%
                                </td>
                                <td class="text-right">
                                    <?php echo price(round(calcPrice($_SESSION["user_id"]) * 0.22, 2)); ?> €
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>SKUPAJ:</b>
                                </td>
                                <td class="text-right">
                                    <?php echo price(calcPrice($_SESSION["user_id"])); ?> €
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="clear"></div>
                <hr />
                <div style="color: #999; margin-top: -20px;" class="invoice-footer">
                    <div class="col-xs-12 pull-left text-center">
                        <div class="col-xs-12">
                            TRR:  01000-0150298797 | IBAN: SI56-0100-0000-0200-097 | BIC: LJBASI2X | ID za DDV: SI15012557
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div id="print-invoice" style="display: none; background: white !important;">
            <div class="container-fluid" style="background: white !important;">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="invoice">
                            <div class="invoice-header">
                                <span class="navbar-brand" style="cursor: default !important; font-weight: 900; font-size: 32px; margin-top: 20px;"><span class="color-info">AVTO</span>DELI</span>
                                <div style="color: #999;" class="pull-right invoice-sender text-right">
                                    Žiga Strgar<br />
                                    Ter 69<br />
                                    +386 41 202-710<br />
                                    ziga_strgar@hotmail.com
                                </div>
                                <div class="clear"></div>
                                <div class="page-header"></div>
                            </div>
                            <div class="invoice-content">
                                <div class="invoice-reciever pull-left">
                                    <h4>Prejemnik</h4>
                                    <?php echo $user["uname"] . " " . $user["surname"]; ?><br />
                                    <?php echo $user["location"]; ?><br />
                                    <?php echo $user["number"] . " " . $user["city"]; ?>
                                </div>
                                <div class="invoice-info pull-right text-right">
                                    <h4>Predračun #<?php echo date("y") . "-" . $_SESSION["user_id"] . "-$max"; ?></h4>
                                    <h5>Datum: <?php echo date("d. m. Y"); ?></h5>
                                    <h5>Rok plačila: <?php echo date("d. m. Y", strtotime($invoice["due_date"])); ?></h5>
                                </div>
                                <div class='clear'></div>
                                <table style="margin-top: 20px;" class="table table-bordered table-striped">
                                    <tr>
                                        <th>
                                            Vrsta blaga
                                        </th>
                                        <th width="50">
                                            Količina
                                        </th>
                                        <th width="120">
                                            Cena na kos
                                        </th>
                                        <th width="20">
                                            DDV
                                        </th>
                                        <th class="col-xs-2 text-right">
                                            Vrednost brez DDV
                                        </th>
                                    </tr>
                                    <?php foreach ($offers as $offer) { ?>
                                        <?php if ($offer["spieces"] <= $offer["stock"]) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $offer["name"]; ?>
                                                </td>
                                                <td>
                                                    <?php echo $offer["spieces"] ?>
                                                </td>
                                                <td>
                                                    <?php echo price($offer["price"]); ?> €
                                                </td>
                                                <td>
                                                    22%
                                                </td>
                                                <td class="text-right">
                                                    <?php echo price(round($offer["price"] * $offer["spieces"] * 0.78, 2)) ?> €
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php
                                            $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Prosimo preverite število naročenih kosov za izdelek: " . $offer["name"];
                                            header("Location: cart.php");
                                            exit();
                                            ?>
                                        <?php } ?>
                                    <?php } ?>
                                </table>
                                <div class="col-xs-offset-6 col-xs-6" style="margin-top: 20px;">
                                    <table class="col-xs-12 table table-responsive">
                                        <tr>
                                            <td>
                                                Znesek brez popusta 
                                            </td>
                                            <td class="text-right">
                                                <?php echo price(round(calcPrice($_SESSION["user_id"]) * 0.78, 2)); ?> €
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Popust
                                            </td>
                                            <td class="text-right">
                                                -0 €
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                DDV 22%
                                            </td>
                                            <td class="text-right">
                                                <?php echo price(round(calcPrice($_SESSION["user_id"]) * 0.22, 2)); ?> €
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>SKUPAJ:</b>
                                            </td>
                                            <td class="text-right">
                                                <?php echo price(calcPrice($_SESSION["user_id"])); ?> €
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <hr />
                            <div style="color: #999; margin-top: -20px;" class="invoice-footer">
                                <div class="col-xs-12 pull-left text-center">
                                    <div style="font-size:0.8em;" class="col-xs-12">
                                        TRR:  01000-0150298797 | IBAN: SI56-0100-0000-0200-097 | BIC: LJBASI2X | ID za DDV: SI15012557
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="cart.php" class="btn btn-flat btn-default"><i class="icon icon-arrow-line-left"></i> Nazaj v košarico</a>
        <a href="finish.php" class="btn btn-flat btn-success pull-right">Končaj naročilo <i class="icon icon-credit-card"></i></a>
        <span id='print' class='btn btn-flat btn-success-inverse pull-right' style='cursor: pointer; margin-right: 10px;'><i class='glyphicon glyphicon-print'></i> Natisni predračun</span>
        <div class="clear"></div>
    </div>
    <script async type="text/javascript">
        function PrintElem(elem)
        {
            Popup($(elem).html());
        }

        function Popup(data)
        {
            var mywindow = window.open('', 'my div', 'height=900,width=1200');
            mywindow.document.write('<html><head><title>Predračun</title>');
            mywindow.document.write('<link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css" /><link href="http://fonts.googleapis.com/css?family=Open+Sans&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">');
            mywindow.document.write('</head><body style="background: white;">');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10

            mywindow.print();
            mywindow.close();

            return true;
        }

        $(document).on("click", "#print", function () {
            PrintElem($("#print-invoice"));
        })
    </script>
<?php } ?>
<?php include_once './footer.php'; ?>