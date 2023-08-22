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
//unused
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

//useful
function getUploadFileDetails($fileName, $maxFileSize = null){
    $maxFileSize = empty($maxFileSize) ? 5242880 : $maxFileSize; //5mb max size by default

    //check to ensure the right content type is sent
    if(!isset($_SERVER['CONTENT_TYPE']) || strpos($_SERVER['CONTENT_TYPE'], "multipart/form-data; boundary=") === false){
        sendResponse(400, false, "Content type header not set to multipart/form-data with a boundary");
    }
    
    //Perform other validations on the image
    if(!isset($_FILES[$fileName])){
        sendResponse(500, false, 'Ensure the right file is selected');
    }
    if($_FILES[$fileName]['error'] !== 0){
        sendResponse(500, false, 'File upload is unsuccessful: '.$_FILES[$fileName]['error']);
    }
    
    //check image size
    if(isset($_FILES[$fileName]['size']) && $_FILES[$fileName]['size'] > $maxFileSize){
        sendResponse(400, false, 'File size is too large please resize');
    }

    //Get image file details (including mime)
    $imageSizeDetails = getimagesize($_FILES[$fileName]['tmp_name']);
    
    return array(
        'imgdata' => $_FILES[$fileName],
        'imgsize' => $imageSizeDetails
    );
}

function getUploadFilename($fileName) {
    $sanitizedFileName = preg_replace('/[^a-zA-Z0-9_.]/', '', $fileName);
    $fileNameWithoutExtension = pathinfo($sanitizedFileName, PATHINFO_FILENAME);
    $finalFileName = str_replace(' ', '_', $fileNameWithoutExtension);

    return $finalFileName;
}

function getUploadFileExtension($fileName, $allowedArray=null) {
    if($allowedArray === null){
        $allowedArray = array('jpeg', 'jpg', 'gif', 'png'); //image by default
    }

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    if(!in_array($extension, $allowedArray)){
        sendResponse(400, false, "Invalid file type!");
    }

    return ".".$extension;
}
//------------End IMAGE Functions--------------------

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
        require_once('images/delete_image_data.php');
    }
    else {
        sendResponse(405, false, 'Request method not allowed');
    }
}

else if(array_key_exists('refid', $_GET) && array_key_exists('name', $_GET)){
    $ref_id = $_GET['refid'];
    $img_name = $_GET['name'];
    if($ref_id == '' || !is_numeric($ref_id)){
        sendResponse(400, false, 'Reference ID cannot be blank and must be numeric');
    }
    if(empty($img_name)){
        sendResponse(400, false, 'Set a name for the image file');
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