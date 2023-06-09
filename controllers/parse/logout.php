<?php
// this page was discontinued
require_once('../DBconnect.php');
require_once('../../models/Response.php');
require_once('../db/connect_write_db.php');
require_once('../_functions.php');

$redirectTo = "../../home";

$sessionid = 2;
$accesstoken = "NDg0MDI4NzVhZGQxZjQ0MDQxM2FjM2FlYTliYjU1YmQ3OTQyNjEyYjAzNTgwYzgzMTY4MDczOTQ2OQ==";
try{
    $query = $writeDB -> prepare('DELETE FROM tbl_sessions WHERE id = :id AND accesstoken = :accesstoken LIMIT 1');
    $query -> bindParam(':id', $sessionid, PDO::PARAM_INT);
    $query -> bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query->rowCount();

    if($rowCount === 0){
        sendResponse(404, false, 'Session not found');
    }

    $returnData = array();
    $returnData['session_id'] = intval($sessionid);

    sendResponse(200, true, 'Successfully logged out', $returnData);

} 
catch(PDOException $e){
    responseServerException($e, 'Unable to logout. Please try again');
}

$_SESSION = array();// unset all session variables
session_destroy(); // destroy the session
header("location: ".$redirectTo); // redirect to login page
//echo '<script>location.href='.$redirectTo.'</script>';

exit;

?>