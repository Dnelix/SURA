<?php
require_once('_constants.php');
require_once('_functions.php');
require_once('../models/Response.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../models/mail/phpmailer/src/Exception.php';
require '../models/mail/phpmailer/src/PHPMailer.php';
require '../models/mail/phpmailer/src/SMTP.php';

//Retrieve JSON. Format = (type, subject, to_mail, to_name='', message='', sender='')
$jsonData = validateJsonRequest();
if(isset($jsonData->subject) && isset($jsonData->to_mail)){
    $subject    = $jsonData->subject;
    $to_mail    = $jsonData->to_mail;
    $type       = (isset($jsonData->type) ? $jsonData->type : '');
    $to_name    = (isset($jsonData->to_name) ? $jsonData->to_name : '');
    $message    = (isset($jsonData->message) ? $jsonData->message : '');
    $sender     = (isset($jsonData->sender) ? $jsonData->sender : $company);
} else {
    sendResponse(400, false, 'Invalid data in the JSON body. Unable to proceed');
    exit();
}

$from       = $c_email;
$from_name  = ((isset($sender) && $sender!=='') ? $sender : $company);
$bcc        = ($to_mail === $c_email) ? '' : $c_email; //optional
$year       = date('Y');

// insert HTML email templates and update dynamic contents
if($type == 'old'){
    $email_image = $c_website.'assets/media/patterns/header-bg-dark.png';
    $htmlFile = file_get_contents('_email/welcome.php');
} else if ($type == 'welcome'){
    $email_image = $c_website.'assets/media/email/icon-positive-vote-3.png';
    $htmlFile = file_get_contents('_email/new.php');
} else if ($type == 'general'){
    $email_image = $c_website.'assets/media/email/icon-positive-vote-2.png';
    $htmlFile = file_get_contents('_email/general.php');
} else {
    $email_image = $c_website.'assets/media/email/icon-positive-vote-1.png';
    $htmlFile = file_get_contents('_email/general.php');
}

//replace the defined strings within the mail template with the corresponding variables (Aside from the braces, {strings} and variables must be identical)
$htmlBody = $htmlFile;

$items_to_replace = ['color_pri', 'color_sec', 'to_name', 'subject', 'message', 'sender', 'company', 'getStarted_link', 'activation_link', 'privacypolicy_link', 'c_tagline', 'c_shortsite', 'c_website', 'c_description', 'c_phone', 'c_email', 'svg_verifiedicon', 'email_image', 'logo_image', 'year', 'c_linkedin', 'c_facebook', 'c_twitter', 'c_whatsapp'];
foreach ($items_to_replace as $item){
    $placeholder = '{$' . $item . '}';
    $variable = $$item;
    $htmlBody = str_replace($placeholder, $variable, $htmlBody);
}

$noHtml = "You are getting this message because your mail client does not support HTML messages, hence you cannot receive emails from {$company}. Kindly update your email to avoid missing out on exciting offers.";

$mail = new PHPMailer(true);
try{
    //Server settings
    $mail->SMTPDebug  = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $SMTP_Host;                             //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication. Set to false if you don't want to use username and password
    $mail->Username   = $SMTP_Username;                         //SMTP username
    $mail->Password   = $SMTP_Password;                         //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
    $mail->Port       = $SMTP_Port;                             //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`or 465 for ENCRYPTION_SMTPS
    
    //Recipients
    $mail->setFrom($from, $from_name);
    $mail->addAddress($to_mail, $to_name);                      //Add a recipient. Name is optional. You can also add multiple recipients
    //$mail->addAddress('ellen@example.com');
    $mail->addReplyTo($from, $from_name);
    //$mail->addCC('cc@example.com');
    $mail->addBCC($bcc);
    
    //Attachments
    // $mail->addAttachment('images/phpmailer_mini.png');       //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');       //Optional name

    //Content
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = $subject;
    //$mail->Body    = $htmlBody;                               //HTML file
    $mail->msgHTML($htmlBody, __DIR__);                         //Read HTML from external file, convert referenced images to embedded. Convert HTML into a basic plain-text alternative body
    $mail->AltBody = $noHtml;
    
    $responseData = array();
    $responseData['type'] = $type;
    $responseData['subject'] = $subject;
    $responseData['from'] = "{$from_name} ({$from})";
    $responseData['to'] = "{$to_name} ({$to_mail})";
    //$responseData['message'] = $htmlBody;

    if(!$mail->send()) { //echo $mail->ErrorInfo; 
        sendResponse(401, false, 'Mail sending failed!', $mail->ErrorInfo);
    } else {
        sendResponse(200, true, 'Mail has been sent successfully', $responseData);
    }
}
catch(Exception  $e){
    $msg = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
    sendResponse(400, false, $msg);
}

?>