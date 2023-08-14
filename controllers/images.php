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


//------------IMAGE UPLOAD Function--------------------
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

function uploadImageRoute($readDB, $writeDB, $ref_id, $ret_userid){
    global $dateformat, $write_dateformat, $uploadPath;
    try{
        $filetype               = 'image';
        $jsonImageAttributes    = validateImageAttributes();
        $imageFileDetails       = getImageFileDetails();
        $imageFileMime          = $imageFileDetails['imgsize']['mime'];
        $fileExt                = getImageExtension($imageFileMime);
        $updated                = date($dateformat);

        // Check user
        $query = $readDB ->prepare('SELECT id FROM tbl_users WHERE id = :ref_id');
        $query -> bindParam(':ref_id', $ref_id, PDO::PARAM_INT);
        $query -> execute();

        if($query -> rowCount() === 0){
            sendResponse(404, false, 'User not found');
        }

        // create the image using the image model: ($id, $title, $filename, $mimetype, $ref_id, $updated)
        $image = new Image(null, $jsonImageAttributes->title, $jsonImageAttributes->filename.$fileExt, $imageFileMime, $ref_id, $updated);
        $title = $image -> getTitle();
        $newFileName = $image -> getFilename();
        $mimetype = $image -> getMimetype();

        // check to ensure image name does not already exist against the user 
        $query = $readDB->prepare('SELECT tbl_uploads.id FROM tbl_uploads, tbl_users WHERE tbl_uploads.ref_id = tbl_users.id AND tbl_users.id = :ref_id AND tbl_uploads.filename = :imagefilename');
        $query -> bindParam (':ref_id', $ref_id, PDO::PARAM_INT);
        $query -> bindParam (':imagefilename', $newFileName, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query -> rowCount();
        if($rowCount !== 0){
            sendResponse(409, false, "A file with the same filename already exists. Please rename.");
        }

        // utilize the rollback function to ensure that DB writing only occurs when the image have been successfully uploaded
        $writeDB -> beginTransaction();

        //insert into DB
        $query = $writeDB -> prepare('INSERT INTO tbl_uploads (title, filetype, filename, mimetype, ref_id, updated) VALUES(:title, :filetype, :filename, :mimetype, :ref_id, STR_TO_DATE(:updated, '. $write_dateformat .'))');
        $query -> bindParam(':title', $title, PDO::PARAM_STR);
        $query -> bindParam(':filetype', $filetype, PDO::PARAM_STR);
        $query -> bindParam(':filename', $newFileName, PDO::PARAM_STR);
        $query -> bindParam(':mimetype', $mimetype, PDO::PARAM_STR);
        $query -> bindParam(':ref_id', $ref_id, PDO::PARAM_INT);
        $query -> bindParam(':updated', $updated, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query->rowCount();
        if ($rowCount === 0){
            if($writeDB->inTransaction()){
                $writeDB -> rollback(); //rollback if there's a database write activity going on before this failure.
            }
            sendResponse(500, false, "Failed to upload image!");
        }

        // get last insert id to retrieve the info
        $lastImageId = $writeDB -> lastInsertId();

        $query = $writeDB->prepare('SELECT tbl_uploads.id, tbl_uploads.title, tbl_uploads.filetype, tbl_uploads.filename, tbl_uploads.mimetype, tbl_uploads.ref_id, tbl_uploads.updated FROM tbl_uploads, tbl_users WHERE tbl_uploads.id = :imageid AND tbl_users.id = :ref_id AND tbl_uploads.ref_id = tbl_users.id');
        $query -> bindParam (':imageid', $lastImageId, PDO::PARAM_INT);
        $query -> bindParam (':ref_id', $ref_id, PDO::PARAM_INT);
        $query -> execute();

        $rowCount = $query -> rowCount();
        If ($rowCount === 0){
            if($writeDB->inTransaction()){
                $writeDB->rollback();
            }
            sendResponse(500, false, 'Failed to retrieve image attributes after upload. Try again');
        }

        // else return the image details from the query
        $imageArray = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $image = new Image(
                $row['id'],
                $row['title'],
                $row['filename'],
                $row['mimetype'],
                $row['ref_id'],
                $row['updated']
            );

            $imageArray[] = $image -> returnImageAsArray();
        }

        //call the function to upload image (Written in the image model)
        $image->saveImageFile($imageFileDetails['imgdata']['tmp_name']);

        // everything okay? commit the db updates
        $writeDB->commit();
        sendResponse(201, true, "Image uploaded successfully", $imageArray, false);
    }
    catch (PDOException $e){
        error_log("Database Query Error: ".$e, 0);
        if($writeDB->inTransaction()){
            $writeDB->rollback(); //rollback if there's a database write activity going on before this failure.
        }
        responseServerException($e, "There was an error with this upload");
    }
    catch (ImageException $e){
        if($writeDB->inTransaction()){
            $writeDB->rollback();
        }
        sendResponse(500, false, $e->getMessage());
    }
}


//tasks/1/images/5/attributes
if(array_key_exists('refid', $_GET) && array_key_exists('imageid', $_GET) && array_key_exists($attr_name, $_GET)){
    $ref_id = $_GET['refid'];
    $imageid = $_GET['imageid'];
    $attributes = $_GET[$attr_name];
    if($imageid == '' || !is_numeric($imageid) || $ref_id == '' || !is_numeric($ref_id)){
        sendResponse(400, false, 'ImageID or Task ID cannot be blank and must be numeric');
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        //get specified image attribute
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'PATCH'){
        //update the specified image attribute
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
        uploadImageRoute($readDB, $writeDB, $ref_id, $usr_id);
    }
    else {
        sendResponse(405, false, 'Request method not allowed');
    }
}

else {
    sendResponse(404, false, 'INVALID ENPOINT!');
}

?>