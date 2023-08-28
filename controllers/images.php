<?php
require_once('_constants.php');
require_once('_functions.php');
require_once('DBconnect.php');
require_once('../models/Response.php');
require_once('../models/Image.php');

//connect to DB
require_once('db/connect_write_read_db.php');

//check authorization
//require_once('sessions/authenticate.php');
//$ret_userid = checkAuthStatus($writeDB); // run the function and retrieve the returned userid

$uploadFolderURL = "../assets/media/uploads/";

if(array_key_exists('refid', $_GET) && array_key_exists('userid', $_GET)){
    $refid = $_GET['refid'];
    $userid = $_GET['userid'];
    if($refid == '' || !is_numeric($refid) || !is_numeric($userid)){
        sendResponse(400, false, 'Reference ID or User ID cannot be blank and must be numeric');
    }

    if(array_key_exists('imageid', $_GET)){
        $imageid = $_GET['imageid'];
        if($imageid == '' || !is_numeric($imageid)){
            sendResponse(400, false, 'ImageID is invalid');
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get specified image
            require_once('images/get_image_file.php');
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            //update the specified image (PATCH doesn't work)
            require_once('images/update_image.php');
        }
        else if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            //delete the specified image
            require_once('images/delete_image_data.php');
        }
        else {
            sendResponse(405, false, 'Request method not allowed');
        }
    }

    else if($_SERVER['REQUEST_METHOD'] === 'GET') {
        //get image attribute
        require_once('images/get_image_attributes.php');
    }    
    else if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //upload an image for the user
        require_once('images/upload_image.php');
    }

    else {
        sendResponse(405, false, 'Request method not allowed');
    }
}

else {
    sendResponse(404, false, 'INVALID ENPOINT!');
}

?>