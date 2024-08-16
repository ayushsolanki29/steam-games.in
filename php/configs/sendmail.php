<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$credentials = ['admin@steam-games.in', ';89s6#7tvnCXGB', '465', 'steam-games.in', 'mail.steam-games.in'];

$mail = new PHPMailer(true);
function sendCode($email, $subject, $code, $username, $date, $useage)
{
    global $mail;
    global $credentials;
    try {
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->isSMTP();
        $mail->Host       = $credentials['4'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $credentials['0'];
        $mail->Password   = $credentials['1'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $credentials['2'];

        $mail->setFrom($credentials['0'], $credentials['3']);
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $templatePath = "../../assets/mail/$useage";
        $templateContent = file_get_contents($templatePath);
        $templateContent = str_replace("{username}", $username, $templateContent);
        $templateContent = str_replace("{date}", $date, $templateContent);
        $templateContent = str_replace("{code}", "$code", $templateContent);

        $mail->Body = $templateContent;
        $mail->send();

        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
function sendLogindata($email, $subject, $message,  $gamename, $template, $otp = null,)
{
    global $mail;
    global $credentials;

    try {
        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = $credentials['4'];
        $mail->SMTPAuth = true;
        $mail->Username = $credentials['0'];
        $mail->Password = $credentials['1'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $credentials['2'];

        // Set email sender and recipient
        $mail->setFrom($credentials['0'], $credentials['3']);
        $mail->addAddress($email);

        // Configure email content
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $templatePath = "../../assets/mail/$template";
        $templateContent = file_get_contents($templatePath);

        $templateContent = str_replace("{message}", $message, $templateContent);

        $templateContent = str_replace("{gamename}", $gamename, $templateContent);
        if ($otp != null) {
            $templateContent = str_replace("{otp}", $otp, $templateContent);
        }
        $mail->Body = $templateContent;
        $mail->send();

        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
