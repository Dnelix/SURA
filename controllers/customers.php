<?php
require_once('DBconnect.php');
require_once('../models/Response.php');
require_once('_constants.php');
require_once('_functions.php');

//connect to DB
require_once('db/connect_write_read_db.php');

// Handle CORS request methods
if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); //options is always allowed. Include other request mthds that apply
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Max-Age: 86400'); // cache for 24 hours

    sendResponse(200, true, '');
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('customers/add_customer.php');
}

if(array_key_exists('tailor', $_GET)){
    $tailorid = $_GET['tailor'];
    if($tailorid == '' || !is_numeric($tailorid)){ 
        sendResponse(400, false, 'Invalid tailor Identifier'); 
    }

    if(array_key_exists('customer', $_GET)){
        $custid = $_GET['customer'];
        if($custid == '' || !is_numeric($custid)){ 
            sendResponse(400, false, 'Invalid customer Identifier'); 
        }

        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once('customers/get_customer_record.php');
        }
        if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            //if request is PATCH: update a customer detail/measurement given customerid
            require_once('customers/update_customer_record.php');
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            //if request is DELETE: remove a tailor's customer given tailorid & customerid
            require_once('customers/remove_customer.php'); // simply set tailor ID to 0 for the customer
        }

        sendResponse(401, false, 'Your request cannot be understood');
    }

    //else get all customers for a tailor
    else if($_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once("customers/list_tailor_customers.php");
    }

    sendResponse(401, false, 'Invalid request for the available parameters');
}

else {
    sendResponse(405, false, 'Invalid Request Method!');
}

?>