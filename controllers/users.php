<?php
require_once('_constants.php');
require_once('_functions.php');
require_once('DBconnect.php');
require_once('../models/Response.php');

//connect to DB
require_once('db/connect_write_read_db.php');

// Handle CORS request methods
if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); //options is always allowed. Include other request mthds that apply
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Max-Age: 86400'); // cache for 24 hours

    sendResponse(200, true, '');
}

//neccessary endpoints:
// /users -> to create a user (POST) && to list all users (GET)
// /users/id -> to list a user detail (GET) && delete a user (DELETE) && update a user detail (PATCH)

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('users/create_user.php');
}

if(array_key_exists('reset', $_GET) && isset($_GET['data'])){
    $resetUserData = $_GET['data'];     //data can be email or username

    if(!empty($resetUserData) && $_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once('users/reset/send_reset_link.php'); exit();
    } else if (!empty($resetUserData) && $_SERVER['REQUEST_METHOD'] === 'PATCH'){
        require_once('users/reset/reset_userpassword.php'); exit();
    } else {
        sendResponse(401, false, 'Invalid request method or parameters');
    }
}

//check auth status here (user will not be logged in before self registration)

if(array_key_exists('userid', $_GET)){
    $userid = $_GET['userid'];

    if($userid == '' || !is_numeric($userid)){
        sendResponse(400, false, 'User ID not allowed');
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        //Get a user detail given id
        require_once('users/get_user_record.php');
    }

    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        //if request is DELETE: delete a user given id
    }

    if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        //if request is PATCH: update a user detail given id
        $jsonData = validateJsonRequest();
        
        if (isset($jsonData->fullname) && isset($jsonData->phone)){
            require_once('users/update_userinfo.php');
        } else if (isset($jsonData->username) && isset($jsonData->confirmuserpassword)){
            require_once('users/update_username.php');
        } else if (isset($jsonData->emailaddress) && isset($jsonData->confirmemailpassword)){
            require_once('users/update_useremail.php');
        } else if (isset($jsonData->currentpassword) && isset($jsonData->newpassword) && isset($jsonData->confirmpassword)){
            require_once('users/update_userpassword.php');
        } else if (isset($jsonData->deactivate)){
            require_once('users/update_deactivate.php');
        } else if (isset($jsonData->activate)){
            require_once('users/update_activate.php');
        } else {
            sendResponse(401, false, 'Invalid PATCH request');
        }
    }

    sendResponse(401, false, 'Your request cannot be understood');

}

//else get all users
else if (empty($_GET) && $_SERVER['REQUEST_METHOD'] === 'GET'){
    require_once("users/list_users.php");
}

else {
    sendResponse(405, false, 'Invalid Request Method!');
}

?>