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
    require_once('projects/create_project.php');
}

if(array_key_exists('tailor', $_GET)){
    $tailorid = $_GET['tailor'];
    if($tailorid == '' || !is_numeric($tailorid)){ sendResponse(400, false, 'Invalid tailor Identifier'); }

    if(array_key_exists('customer', $_GET)){
        $custid = $_GET['customer'];
        if($custid == '' || !is_numeric($custid)){ sendResponse(400, false, 'Invalid customer Identifier'); }
        
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once('projects/list_customer_projects.php');
        }
        sendResponse(401, false, 'Your request cannot be understood');
    }

    if(array_key_exists('pid', $_GET)){
        $pid = $_GET['pid'];
        if($pid == '' || !is_numeric($pid)){ sendResponse(400, false, 'Invalid project Identifier'); }

        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once('projects/get_project_record.php');
        }
        if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $jsonData = validateJsonRequest();
        
            if (isset($jsonData->status)){
                require_once('projects/update_project_status.php');
            } else if (isset($jsonData->title) && isset($jsonData->start)){
                require_once('projects/update_project_data.php');
            } else if (isset($jsonData->income)){
                require_once('projects/update_financials.php');
            } else {
                sendResponse(401, false, 'Invalid PATCH request');
            }
        }
        if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            require_once('projects/delete_project.php'); 
        }
        sendResponse(401, false, 'Invalid request for this project');
    }

    //else get all projects for a tailor
    else if($_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once("projects/list_projects.php");
    }

    sendResponse(401, false, 'Invalid request for the available parameters');
}

else {
    sendResponse(405, false, 'Invalid Request Method!');
}

?>