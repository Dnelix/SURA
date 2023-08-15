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


//------------IMAGE UPLOAD Functions--------------------
function validateImageAttributes($attr_name = 'attributes'){
    if(!isset($_POST[$attr_name])){
        sendResponse(400, false, 'Attributes missing from body of request');
    }

    if(!$jsonImageAttributes = json_decode($_POST[$attr_name])){
        sendResponse(400, false, 'Image attributes is not valid JSON');
    }

    //check for image title and filename
    if(!isset($jsonImageAttributes->title) || !isset($jsonImageAttributes->filename) || $jsonImageAttributes->title == '' || $jsonImageAttributes->filename == ''){
        sendResponse(400, false, 'Title and filename fields are mandatory');
    }

    //check the filename string to ensure it doesn't contain a . or the file extension
    if(strpos($jsonImageAttributes->filename, ".") > 0){
        sendResponse(400, false, "Filename should not contain the extension");
    }

    return $jsonImageAttributes;
}

function getImageFileDetails($file_name = 'imagefile'){
    //check to ensure the right content type is sent
    if(!isset($_SERVER['CONTENT_TYPE']) || strpos($_SERVER['CONTENT_TYPE'], "multipart/form-data; boundary=") === false){
        sendResponse(400, false, "Content type header not set to multipart/form-data with a boundary");
    }
    
    //Perform other validations on the image
    if(!isset($_FILES[$file_name]) || $_FILES[$file_name]['error'] !== 0){
        sendResponse(500, false, 'Image file upload unsuccessful. Ensure the right file is selected');
    }
    
    //check image size
    if(isset($_FILES[$file_name]['size']) && $_FILES[$file_name]['size'] > 5242880){ //if larger than 5mb
        sendResponse(400, false, 'Image size cannot be greater than 5MB');
    }

    //Get image file details (including mime)
    $imageSizeDetails = getimagesize($_FILES[$file_name]['tmp_name']);
    
    return array(
        'imgdata' => $_FILES[$file_name],
        'imgsize' => $imageSizeDetails
    );
}

function getImageExtension($imageFileMime){
    $allowedFileTypes = array('image/jpeg', 'image/gif', 'image/png');

    if(!in_array($imageFileMime, $allowedFileTypes)){
        sendResponse(400, false, "Invalid Image file type!");
    }

    $fileExt = "";
    switch ($imageFileMime){
        case "image/jpeg":
            $fileExt = ".jpg";
            break;
        case "image/png":
            $fileExt = ".png";
            break;
        case "image/gif":
            $fileExt = ".gif";
            break;
        default:
            break;
    }

    if(empty($fileExt)){ 
        sendResponse(400, false, "No valid file extension found for image");
    }

    return $fileExt;
}
//------------End IMAGE Functions--------------------

//tasks/1/images/5/attributes
if(array_key_exists('refid', $_GET) && array_key_exists('imageid', $_GET) && array_key_exists('attributes', $_GET)){
    $ref_id = $_GET['refid'];
    $imageid = $_GET['imageid'];
    $attributes = $_GET['attributes'];
    if($imageid == '' || !is_numeric($imageid) || $ref_id == '' || !is_numeric($ref_id)){
        sendResponse(400, false, 'ImageID or Reference ID cannot be blank and must be numeric');
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        //get specified image attribute
        require_once('images/get_image_attributes.php');
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'PATCH'){
        //update the specified image attribute
        require_once('images/update_image_attributes.php');
    }
    else {
        sendResponse(405, false, 'Request method not allowed');
    }
}

//tasks/1/images/5
else if(array_key_exists('refid', $_GET) && array_key_exists('imageid', $_GET)){
    $ref_id = $_GET['refid'];
    $imageid = $_GET['imageid'];
    if($imageid == '' || !is_numeric($imageid) || $ref_id == '' || !is_numeric($ref_id)){
        sendResponse(400, false, 'ImageID or Task ID cannot be blank and must be numeric');
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        //get specified image
        require_once('images/get_image_file.php');
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
        //delete the specified image
    }
    else {
        sendResponse(405, false, 'Request method not allowed');
    }
}

//tasks/1/images
else if(array_key_exists('refid', $_GET) && array_key_exists('userid', $_GET)){
    $ref_id = $_GET['refid'];
    $usr_id = $_GET['userid'];
    if($ref_id == '' || !is_numeric($ref_id) || !is_numeric($usr_id)){
        sendResponse(400, false, 'Reference/usr ID cannot be blank and must be numeric');
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
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