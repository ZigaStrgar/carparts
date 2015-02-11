<?php

include_once './core/functions.php';
include_once './core/session.php';
include_once './core/db.php';
include_once './core/database.php';
$user = Db::queryOne("SELECT name, surname FROM users WHERE email = ?", $email);
$email = $_POST["email"];
if (checkEmail($email)) {
    if (Db::query("SELECT * FROM users WHERE email = ?", $email) == 1) {
        $salt = Db::querySingle("SELECT salt FROM users WHERE email = ?", $email);
        $rand = randomPassword();
        $password = loginHash($salt, passwordHash($rand));
        if (Db::query("UPDATE users SET reset_password = ?, reset = 1 WHERE email = ?", $password, $email) == 1) {
            require './plugins/mailer/PHPMailerAutoload.php';
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = 0; // Set mailer to use SMTP
            $mail->Host = 'juno.avant.si';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'info@carparts.belita.si';                 // SMTP username
            $mail->Password = 'parts123';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to

            $mail->From = 'info@carparts.belita.si';
            $mail->FromName = 'Avtodeli';
            $mail->addAddress($email, $name . " " . $surname);     // Add a recipient            // Name is optional
            $mail->addReplyTo('info@carparts.belita.si', 'Avtodeli');
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Pozabljeno geslo!';
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
                                                                <a href='http://carparts.belita.si' style='font-size: 22pt; text-decoration: none;'><span class=\"color-info\">AVTO</span><span style='color: #333;'>DELI</span></a>
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
                                                                <span style=\"font-size: 18pt;\">POZABLJENO GESLO</span>
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
<span style='font-size: 14pt;'>Pozdravljen/-a " . $user["name"] . " " . $user["surname"] . "!</span><br />
    <span style='font-size: 12pt;'>Vaše novo geslo je : $rand<br />
    Vaš vpisni e-naslov je: $email</span><br />
<span style='font-size: 9pt;'>Če niste vi zahtevali novega gesla prosimo kliknite spodnji gumb</span>
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
                                                                            <span style=\"color: #ffffff; font-weight: 300;\"> <a style=\"color: #ffffff; text-align:center;text-decoration: none;\" href=\"http://carparts.belita.si/cancle/" . mailHash($email) . "\" tabindex=\"-1\">TO NISEM BIL JAZ</a></span>
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
                $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Mail ni bil poslan. Napaka: " . $mail->ErrorInfo;
                header("Location: http://" . URL . "/resetPassword.php");
            } else {
                $_SESSION["alert"] = "alert alert-success alert-fixed-bottom|Vaše novo geslo je bilo poslano na vaš e-naslov|3000";
                header("Location: http://" . URL . "/login.php");
            }
        } else {
            $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Napaka podatkovne baze";
            header("Location: http://" . URL . "/resetPassword.php");
        }
    } else {
        $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Uporabnik s takšnim e-naslovom ne obstaja!";
        header("Location: http://" . URL . "/resetPassword.php");
    }
} else {
    $_SESSION["alert"] = "alert alert-danger alert-fixed-bottom|Napaka e-naslova!";
    header("Location: http://" . URL . "/resetPassword.php");
}