<?php
require_once('_constants.php');
require_once('_functions.php');
require_once('DBconnect.php');
require_once('../models/Response.php');
require_once('../models/Image.php');

//connect to DB
require_once('db/connect_write_read_db.php');

// Handle CORS request methods
if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); //options is always allowed. Include other request mthds that apply
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Max-Age: 86400'); // cache for 24 hours

    sendResponse(200, true, '');
}

// $ret_userid = checkAuthStatus($writeDB);
// sendResponse(405, false, $ret_userid); exit();

//keep track of all columns in the db_table that can potentially be retrieved/updated
$all_fields = 'id, owner, name, description, photo, phone, email, website, country, state, city, address, measure_unit, currency, t_income, t_expenses';

if(array_key_exists('userid', $_GET)){
    $userid = $_GET['userid'];

    if($userid == '' || !is_numeric($userid)){
        sendResponse(400, false, 'User ID not allowed');
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('business/create_business.php');
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once('business/get_business_record.php');
    }

    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        //if request is DELETE: delete a user given id
    }

    if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        // $authID = checkAuthStatus($writeDB, $logtoken);
        // if ($authID !== $userid){ sendResponse(400, false, 'Unauthorized access!'); exit(); }
        require_once('business/update_biz_data.php');
    }

    sendResponse(401, false, 'Your request cannot be understood');

}

//else get all users
else if (empty($_GET) && $_SERVER['REQUEST_METHOD'] === 'GET'){
    require_once("business/list_businesses.php");
}

else {
    sendResponse(405, false, 'Invalid Request Method!');
}

?>