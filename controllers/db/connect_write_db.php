<?php

try {
    $writeDB = DBconnect::connectWriteDB();
} 
catch (PDOException $e) {
    responseServerException($e, 'Database connection error');
    exit(); //don't continue the script if there is an error with connection
}

?>