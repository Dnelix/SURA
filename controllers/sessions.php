<?php
require_once('DBconnect.php');
require_once('../models/Response.php');
require_once('_constants.php');
require_once('_functions.php');

//connect to DB
require_once('db/connect_write_read_db.php'); // we mostly use the master (write) DB for authentication checks

// necessary endpoints:
// /sessions -> To create a session or login (POST)
// /sessions/id -> To delete a session or logout (DELETE) given id
// /sessions/id -> To update or refresh a session (PATCH) given id

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require_once('sessions/create_session.php');
}

// if there's a specified ID, go ahead and check if it is a PATCH or DELETE request and perform operations accordingly
if(array_key_exists('sessionid', $_GET)){
    
    $sessionid = $_GET['sessionid'];

    //ensure id is valid
    if($sessionid === '' || !is_numeric($sessionid)){
        $message = (($sessionid === '') ? 'Session ID cannot be empty' : false);
        $message .= (!is_numeric($sessionid) ? 'Session ID must be an integer' : false);
        sendResponse(400, false, $message);
        exit(); 
    }
    
    //validate authorization
    /*if(!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) < 1){
        $message = (!isset($_SERVER['HTTP_AUTHORIZATION']) ? 'Access token is missing from the header' : false);
        $message .= ((strlen($_SERVER['HTTP_AUTHORIZATION']) < 1) ? 'Access token cannot be blank' : false);
        sendResponse(400, false, $message);
        exit(); 
    }
    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];*/

    #####Try storing access token in the request and retrieving it from there.
    $jsonData = validateJsonRequest();
    $accesstoken = $jsonData->accesstoken;

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        // get details of a particular session (if necessary)
    }
    
    // if delete    
    if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
        require_once('sessions/delete_session.php');
    }

    // if update
    else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
        require_once('sessions/update_session.php');
    }

    //else invalid
    else{
        sendResponse(405, false, 'Invalid session GET request');
    }
}

else if(array_key_exists('userid', $_GET)){
    $userid = $_GET['userid'];
    if($userid === '' || !is_numeric($userid)){
        $message = (($userid === '') ? 'User ID cannot be empty' : false);
        $message .= (!is_numeric($userid) ? 'User ID must be an integer' : false);
        sendResponse(400, false, $message);
        exit(); 
    }

    require_once('sessions/list_user_sessions.php');
}

//else invalid request
else{
    sendResponse(405, false, 'Invalid request method');
}