<?php
require_once('DBconnect.php');
require_once('../models/Response.php');
require_once('_constants.php');
require_once('_functions.php');

$sendMail = sendEmail('welcome', 'Welcome to '.$company, 'domainbuy101@gmail.com', 'Dnelix', 'Testing this mail', 'Felix');
sendResponse(200, true, 'Mail has been sent successfully', $sendMail);
?>