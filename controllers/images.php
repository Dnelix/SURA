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
            require_once('images/get_image_file.php');      //get specified image (not sure how useful)
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            require_once('images/update_image.php');        //update the specified image data (PATCH doesn't work)
        }
        else if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            require_once('images/delete_image_data.php');   //delete the specified image
        }
        else {
            sendResponse(405, false, 'Request method not allowed');
        }
    }

    else if($_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once('images/get_image_attributes.php');    //get image attributes
    }    
    else if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('images/upload_image.php');            //upload an image for the user
    }

    else {
        sendResponse(405, false, 'Request method not allowed');
    }
}

else {
    sendResponse(404, false, 'INVALID ENPOINT!');
}

?>