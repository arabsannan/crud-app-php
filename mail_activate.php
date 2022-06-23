<?php

// Import the mailer class
require("../PHPMailer-master/src/PHPMailer.php");
require("../PHPMailer-master/src/SMTP.php");
require('../pages/signup.php');

// SMTP configuration from Mailtrap
$phpmailer = new PHPMailer\PHPMailer\PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = 'smtp.mailtrap.io';
$phpmailer->SMTPAuth = true;
$phpmailer->Port = 2525;
$phpmailer->Username = '4dd88b654c91f8';
$phpmailer->Password = '86d739f78e55de';

// Link code
$activate_link = 'http://localhost/LoginSystem/activate.php?email=' . $_POST['email'] . '&code=' . $uniquid;

$from = 'noreply@dressup.com';
$title = 'DressUp';
$phpmailer->setFrom($from, $title);
$phpmailer->addAddress($_POST['email'], 'Me');
$phpmailer->Subject = 'Account Activation Required!';


// Our HTML setup
$phpmailer->isHTML(TRUE);
$phpmailer->Body = '<html> Hello client, thank you for creating a DressUp account.
               		<p>Please click the following link to activate your account: 
					<a href="' . $activate_link . '">' . $activate_link . '</a></p> 
					</html>';
					
$phpmailer->AltBody = 'Success';

// adding mailing attachment for payment plan
// $mail->addAttachment('//node/paymments.pdf', 'payments.pdf');

// send the thank you message
if(!$phpmailer->send()){
    echo 'Your message could not be develired, try again later';
    echo 'Error: ' . $phpmailer->ErrorInfo;
} else {
    echo 'Your message has been sent successfully.';
}


             // Send activation email/ Email Verification
			// $from    = 'noreply@yourdomain.com';
			// $subject = 'Account Activation Required';
			// $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
			// // Update the activation variable below
			// $activate_link = 'http://yourdomain.com/phplogin/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
			// $message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
			// mail($_POST['email'], $subject, $message, $headers);
			// echo 'Please check your email to activate your account!';
			
			// require "../mail_activate.php";