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

//Define measurement parameters
$measurement_parameters_array = array(
    'neck','top_length','torso','chest','shoulder','arm_length','sleeve_length','wrist','bicep','full_length','bottom_length','inseam_length','waist','hip','laps','flap','knee_length','round_knee','ankle','shoe_size'
); //named exactly as in the DB

if(array_key_exists('customer', $_GET)){
    $custid = intval($_GET['customer']);
    if($custid == '' || !is_numeric($custid)){ 
        sendResponse(400, false, 'Invalid customer Identifier'); 
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once('measurements/get_measurements.php');
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('measurements/add_measurements.php');
    }
    if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        require_once('measurements/update_measurements.php');
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        require_once('measurements/remove_measurements.php');
    }

    sendResponse(401, false, 'Your request cannot be understood');
}

else {
    sendResponse(405, false, 'Invalid Request Method!');
}

?>