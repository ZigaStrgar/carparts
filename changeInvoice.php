<?php

include_once './core/session.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($user["id"])) {
    if ($_POST) {
        $id = (int) cleanString($_POST["id"]); //ID PREDRAČUNA
        $status = (int) cleanString($_POST["val"]); //NOV STATUS PREDPRAČUNA
        if (!empty($id) && !empty($status)) {
            if ($user["email"] == "ziga_strgar@hotmail.com") {
                //PODATKI PREDRAČUNA
                $invoice = Db::queryOne("SELECT * FROM invoices WHERE id = ?", $id);
                if ($invoice["status"] < 2 && $status >= 2 && $status != 7) {
                    //NAROČANI DELI
                    $invoiceParts = Db::queryAll("SELECT part_id, pieces FROM parts_invoices WHERE invoice_id = ?", $id);
                    foreach ($invoiceParts as $invoicePart) {
                        //PODATKI O DELU
                        $part = Db::queryOne("SELECT *, p.id AS pid, u.name AS uname, p.name AS pname FROM parts p INNER JOIN users u ON u.id = p.user_id WHERE p.id = ?", $invoicePart["part_id"]);
                        //POSODOBI ŠT. KOSOV
                        Db::query("UPDATE parts SET pieces = pieces - " . $invoicePart["pieces"] . " WHERE id = ?", $invoicePart["part_id"]);
                        //POŠLJE MAIL LASTNIKU
                        require './plugins/mailer/PHPMailerAutoload.php';
                        $mail = new PHPMailer;
                        $mail->isSMTP();
                        $mail->SMTPDebug = 0;
                        $mail->Host = 'predator2.slovenijanet.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'avtodeli@zigastrgar.com';
                        $mail->Password = 'PartsMatura15';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;
                        $mail->From = 'avtodeli@zigastrgar.com';
                        $mail->FromName = 'Avto deli';
                        $mail->addAddress($part["email"], $part["uname"] . " " . $part["surname"]);
                        $mail->addReplyTo('avtodeli@zigastrgar.com', 'Avto deli');
                        $mail->isHTML(true);
                        $mail->Subject = 'Naročilo dela!';
                        $mail->Body = "<html>
    <head>    <link href=\"http://fonts.googleapis.com/css?family=Open+Sans&amp;subset=latin,latin-ext\" rel=\"stylesheet\" type=\"text/css\"><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><title>Uspešna registracija</title><style type=\"text/css\">
            body{ font-family: 'Open Sans', serif !important; }
            #outlook a {padding:0;}
            body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
            .ExternalClass {width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
            #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
            img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
            a img {border:none;}
            a{ color: inherited; text-decoration: none; font-family: 'Open sans', serif; }
            .image_fix {display:block;}
            p {margin: 0px 0px !important;
            table td {border-collapse: collapse;}
            table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
            table[class=full] { width: 100%; clear: both; }
            @media only screen and (max-width: 640px) {
                a[href^=\"tel\"], a[href^=\"sms\"] {
                    text-decoration: none;
                    color: #ffffff;
                    pointer-events: none;
                    cursor: default;
                }
                .mobile_link a[href^=\"tel\"], .mobile_link a[href^=\"sms\"] {
                    text-decoration: default;
                    color: #ffffff !important;
                    pointer-events: auto;
                    cursor: default;
                }
                table[class=devicewidth] {width: 440px!important;text-align:center!important;}
                table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
                table[class=\"sthide\"]{display: none!important;}
                img[class=\"bigimage\"]{width: 420px!important;height:219px!important;}
                img[class=\"col2img\"]{width: 420px!important;height:258px!important;}
                img[class=\"image-banner\"]{width: 440px!important;height:106px!important;}
                td[class=\"menu\"]{text-align:center !important; padding: 0 0 10px 0 !important;}
                td[class=\"logo\"]{padding:10px 0 5px 0!important;margin: 0 auto !important;}
                img[class=\"logo\"]{padding:0!important;margin: 0 auto !important;}
            }
            @media only screen and (max-width: 480px) {
                a[href^=\"tel\"], a[href^=\"sms\"] {
                    text-decoration: none;
                    color: #ffffff; /* or whatever your want */
                    pointer-events: none;
                    cursor: default;
                }
                .mobile_link a[href^=\"tel\"], .mobile_link a[href^=\"sms\"] {
                    text-decoration: default;
                    color: #ffffff !important; 
                    pointer-events: auto;
                    cursor: default;
                }
                table[class=devicewidth] {width: 280px!important;text-align:center!important;}
                table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
                table[class=\"sthide\"]{display: none!important;}
                img[class=\"bigimage\"]{width: 260px!important;height:136px!important;}
                img[class=\"col2img\"]{width: 260px!important;height:160px!important;}
                img[class=\"image-banner\"]{width: 280px!important;height:68px!important;}

            }
            .color-info{ color:#5bc0de; }
        </style>
    </head><body><div class=\"block\">
        <div class=\"block\">
            <table width=\"100%\" bgcolor=\"#f6f4f5\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tbody>
                    <tr>
                        <td>
                            <div class=\"innerbg\">
                            </div>
                            <table width=\"580\" bgcolor=\"#cccccc\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" class=\"devicewidth\">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table width=\"280\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"left\" class=\"devicewidth\" bgcolor=\"#cccccc\">
                                                <tbody>
                                                    <tr>
                                                        <td width=\"270\">
                                                                <a href='http://matura.zigastrgar.com' style='font-size: 22pt; text-decoration: none;text-align:center;'><span class=\"color-info\">AVTO</span><span style='color: #333;'>DELI</span></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width=\"280\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"right\" class=\"devicewidth\" bgcolor=\"#cccccc\">
                                                <tbody>
                                                    <tr>
                                                        <td width=\"270\" valign=\"middle\" style=\"font-family: 'Open sans', Arial, sans-serif;font-size: 14px; color: #ffffff;line-height: 24px; padding: 10px 0;\" align=\"right\" class=\"menu\">
                                                            <p>
                                                                <a style=\"text-decoration: none; color: #ffffff;\" href=\"#\"></a>
                                                            </p>
                                                        </td>
                                                        <td width=\"20\">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class=\"block\">
            <table width=\"100%\" bgcolor=\"#f6f4f5\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tbody>
                    <tr>
                        <td>
                            <div class=\"innerbg\">
                            </div>
                            <table bgcolor=\"#ffffff\" width=\"580\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" class=\"devicewidth\">
                                <tbody>
                                    <tr>
                                        <td width=\"100%\" height=\"30\">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width=\"540\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"devicewidthinner\">
                                                <tbody>
                                                    <tr>
                                                        <td style=\"font-family: 'Open sans', arial, sans-serif; font-size: 18px; color: #333333; text-align:center;line-height: 20px;\">
                                                            <p>
                                                                <span style=\"font-size: 18pt;\">NAROČILO VAŠEGA DELA</span>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height=\"5\">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style=\"font-family: 'Open sans', arial, sans-serif; font-size: 14px; color: #95a5a6; text-align:center;line-height: 30px;\">
                                                            <a href='http://matura.zigastrgar.com/part/" . $part["pid"] . "'>" . $part["pname"] . "</a> - " . $invoicePart["pieces"] . "x
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width=\"100%\" height=\"10\">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width=\"100%\" height=\"30\">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class=\"block\" style=\"display: block;\">
            <table width=\"100%\" bgcolor=\"#f6f4f5\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tbody>
                    <tr>
                        <td width=\"100%\">
                            <div class=\"innerbg\">
                            </div>
                            <table width=\"580\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" class=\"devicewidth\">
                                <tbody>
                                    <tr>
                                        <td width=\"100%\" height=\"5\">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" valign=\"middle\" style=\"font-family: 'Open sans', arial, sans-serif; font-size: 10px;color: #999999\">
                                            <p>
                                                Če to niste bili vi, pritisnite zgornji gumb
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width=\"100%\" height=\"5\">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </body>
</html>";
                        $mail->CharSet = "UTF-8";
                        if (!$mail->send()) {
                            if(Db::query("UPDATE invoices SET status = ? WHERE id = ?", $status, $id) == 1){
                                echo 'success|Predračun urejen uspešno, obvestilo ni bilo poslano. Napaka: ' . $mail->ErrorInfo;
                            } else {
                                echo 'error|Napaka pri urejanju predračuna in obvestilo ni bilo poslano. Napaka: ' . $mail->ErrorInfo;
                            }
                        } else {
                            if(Db::query("UPDATE invoices SET status = ? WHERE id = ?", $status, $id) == 1){
                                echo 'success|Obvestilo uspešno poslano in predračun uspešno spremenjen!';
                            } else {
                                echo 'error|Napaka pri urejanju predračuna, obvestilo poslano';
                            }
                        }
                    }
                } else {
                    if (Db::query("UPDATE invoices SET status = ? WHERE id = ?", $status, $id) == 1) {
                        echo "success|Predračun uspešno spremenjen!";
                        die();
                    } else {
                        echo "error|Napaka pri zapisu v bazo!";
                        die();
                    }
                }
            } else {
                if ($status == 7) {
                    if (Db::query("UPDATE invoices SET status = ? WHERE id = ?", $status, $id) == 1) {
                        echo "success|Predračun uspešno preklican!";
                        die();
                    } else {
                        echo "error|Napaka pri zapisu v bazo!";
                        die();
                    }
                } else {
                    echo "error|Napačen staus predpračuna!";
                    die();
                }
            }
        } else {
            echo "error|Napaka podatkov!";
            die();
        }
    }
}