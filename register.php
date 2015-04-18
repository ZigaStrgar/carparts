<?php
include_once './core/session.php';
$name = cleanString($_POST["name"]);
$surname = cleanString($_POST["surname"]);
$email = cleanString($_POST["email"]);
$pass = cleanString($_POST["password"]);
$pass2 = cleanString($_POST["password2"]);
//Pregled, če so vsi podatki zapolnjeni
if (!empty($name) && !empty($surname) && !empty($email) && !empty($pass) && !empty($pass2)) {
    //Ujemanje gesl
    if ($pass == $pass2) {
        //Filter za e-poštne naslove
        if (checkEmail($email)) {
            //Zgeneriraj sol
            $salt = createSalt();
            //Hashaj geslo
            $password = passwordHash($pass);
            //Hashaj sol+geslo
            $password = loginHash($salt, $password);
            if (Db::insert("users", array("name" => $name, "surname" => $surname, "email" => $email, "password" => $password, "salt" => $salt, "active_hash" => mailHash($email))) == 1) {
                require './plugins/mailer/PHPMailerAutoload.php';
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->SMTPDebug = 0; // Set mailer to use SMTP
                $mail->Host = 'predator2.slovenijanet.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'avtodeli@zigastrgar.com';                 // SMTP username
                $mail->Password = 'PartsMatura15';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

                $mail->From = 'avtodeli@zigastrgar.com';
                $mail->FromName = 'Avto deli';
                $mail->addAddress($email, $name . " " . $surname);     // Add a recipient            // Name is optional
                $mail->addReplyTo('avtodeli@zigastrgar.com', 'Avto deli');
                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = 'Registracija uspešna!';
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
                                                                <a href='http://matura.zgiastrgar.com' style='font-size: 22pt; text-decoration: none;'><span class=\"color-info\">AVTO</span><span style='color: #333;'>DELI</span></a>
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
                                                            </p>
                                                            <p>
                                                                <span style=\"font-size: 18pt;\">REGISTRACIJA USPEŠNA</span>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height=\"5\">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style=\"font-family: 'Open sans', arial, sans-serif; font-size: 14px; color: #95a5a6; text-align:center;line-height: 30px;\">
                                                            <p>
<span style='font-size: 14pt;'>Pozdravljen/-a $name $surname!</span><br />
    <span style='font-size: 12pt;'>Zahvaljujemo se Vam za registracijo!<br />
    Vaš vpisni e-naslov je: $email</span><br />
<span style='font-size: 10pt;'>Prosimo kliknite na gumb spodaj za aktivacijo računa.</span>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width=\"100%\" height=\"10\">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class=\"buttonbg\">
                                                            </div>
                                                            <table height=\"36\" align=\"center\" valign=\"middle\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tablet-button\" style=\" background-color:#0db9ea;background-clip: padding-box;font-size:13px; font-family:'Open sans', arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;\">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style=\"padding-left:18px; padding-right:18px;font-family:'Open sans', arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; border-radius: 0px;\" width=\"auto\" align=\"center\" valign=\"middle\" height=\"36\">
                                                                            <span style=\"color: #ffffff; font-weight: 300;\"> <a style=\"color: #ffffff; text-align:center;text-decoration: none;\" href=\"http://matura.zgiastrgar.com/activate/" . mailHash($email) . "\" tabindex=\"-1\">AKTIVIRAJ RAČUN</a></span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
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
                                                Če to niste bili vi, ignorirajte to sporočilo
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
                    echo 'error|Mail ni bil poslan. Napaka: ' . $mail->ErrorInfo;
                } else {
                    session_start();
                    $_SESSION["notify"] = "success|Registracija uspešna!\nProsimo poglejte na e-naslov za aktivacijo računa!";
                    echo "redirect|login.php";
                }
            } else if (Db::insert("users", array("name" => $name, "surname" => $surname, "email" => $email, "password" => $password, "salt" => $salt, "active_hash" => mailHash($email))) == 23000) {
                echo "error|Uporabnik s tem e-poštnim naslovom že obstaja!";
            } else {
                echo "error|Napaka podatkovne baze!";
            }
        } else {
            echo "error|Napaka v e-poštnem naslovu";
        }
    } else {
        echo "error|Gesli se ne ujemata!";
    }
} else {
    echo "error|Napaka podatkov!";
}