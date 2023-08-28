<?php
    try{
        $jsonData = validateJsonRequest();

        $all_fields = 'title, filetype, filename, refid, userid, updated';
        $columns = explode(', ', $all_fields);
        foreach($columns as $field){ $$field = false; }
        $updated = false;

        $queryFields = "";

        //extra check for filename
        if(isset($jsonData->filename)){
            if(strpos($jsonData->filename, ".") !== false){
                sendResponse(400, false, "Filename cannot contain any dots or file extensions");
            }
        }

        foreach($columns as $field){
            if(isset($jsonData->$field)){ 
                $queryFields .= "{$field} = :{$field}, ";
                $$field = true;
                $updated = true;
            }
        }
        if (!$updated) {
            sendResponse(400, false, 'You have not made any changes');
        }

        $writeDB->beginTransaction();

        $queryFields = rtrim($queryFields, ", ");

        $sql = 'SELECT ' .$all_fields. ', mimetype FROM tbl_uploads WHERE refid = :refid AND userid = :userid AND filename = :imgname';
        $query = $writeDB->prepare($sql);
        $query->bindParam(':refid', $refid, PDO::PARAM_INT);
        $query->bindParam(':userid', $userid, PDO::PARAM_INT);
        $query->bindParam(':imgname', $imageFileName, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();
        if($rowCount === 0){
            if($writeDB->inTransaction()){ $writeDB->rollBack(); }
            sendResponse(404, false, 'Image not found');
        }

        $image = null;
        // create the image using the image model: ($id, $title, $filename, $mimetype, $refid, $folderURL, $filetype, $updated)
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $image = new Image(
                $row['id'],
                $row['title'],
                $row['filename'],
                $row['mimetype'],
                $row['refid'],
                $uploadFolderURL,
                $row['filetype'],
                $row['updated']
            );
        }

        if ($image === null){ sendResponse(404, false, 'Image not created'); }

        //write out the query string. Concatenate queryfields
        //$queryString = "UPDATE tbl_uploads INNER JOIN tbl_users ON tbl_uploads.refid = tbl_users.id SET ".$queryFields." WHERE tbl_uploads.refid = :refid AND tbl_uploads.id = :imgid";
        $queryString = "UPDATE tbl_uploads SET ".$queryFields." WHERE userid = :userid AND refid = :refid AND filename = :filename";
        $query = $writeDB -> prepare($queryString);
        $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
        $query -> bindParam(':refid', $refid, PDO::PARAM_INT);
        $query -> bindParam(':updated', $updated, PDO::PARAM_STR);
        if($title === true){
            $image->setTitle($jsonData->title);
            $upd_title = $image->getTitle();
            $query->bindParam(':title', $upd_title, PDO::PARAM_STR);
        }
        if($filename === true){
            $old_name = $image->getFilename(); //get current name for comparison
            $image->setFilename($jsonData->filename.".".$image->getFileExtension());
            $upd_filename = $image->getFilename();
            $query->bindParam(':filename', $upd_filename, PDO::PARAM_STR);
        }
        if($filetype === true){
            $image->setFiletype($jsonData->filetype);
            $upd_filetype = $image->getFiletype();
            $query->bindParam(':filetype', $upd_filetype, PDO::PARAM_STR);
        }


        if($updated !== true){
            sendResponse(400, false, 'You have not made any changes'); exit();
        }
        
        //execute
        $query -> execute();
        $errorInfo = $query->errorInfo();
        $rowCount = $query->rowCount();
        if($rowCount === 0){
            if($writeDB->inTransaction()){ $writeDB->rollBack(); }
            sendResponse(400, false, 'You have not updated this record', $errorInfo); exit();
        }
        
        //return updated data
        $sql = 'SELECT ' .$all_fields. ', mimetype FROM tbl_uploads WHERE refid = :refid AND userid = :userid AND filename = :imgname';
        $query = $writeDB -> prepare($sql);
        $query->bindParam(':refid', $refid, PDO::PARAM_INT);
        $query->bindParam(':userid', $userid, PDO::PARAM_INT);
        $query->bindParam(':imgname', $imageFileName, PDO::PARAM_STR);
        $query->execute();
        
        $rowCount = $query -> rowCount();
        if($rowCount === 0){
            if($writeDB->inTransaction()){ $writeDB->rollBack(); }
            sendResponse(404, false, 'No record found after update'); exit();
        }
        
        $imageArray = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $image = new Image(
                $row['id'],
                $row['title'],
                $row['filename'],
                $row['mimetype'],
                $row['refid'],
                $uploadFolderURL,
                $row['filetype'],
                $row['updated']
            );
            $imageArray[] = $image -> returnImageAsArray();
        }

        if($filename === true){ // if the filename field was updated
            $image->renameImageFile($old_name, $up_filename);
        }

        $writeDB->commit();

        sendResponse(200, true, "Image Attributes Updated", $imageArray);
    
    }
    catch(ImageException $e){
        if($writeDB->inTransaction()){
            $writeDB->rollBack();
        }
        sendResponse(400, false, $e->getMessage());
    }
    catch(PDOException $e){
        error_log("Database Query Error: ".$e, 0);
        if($writeDB->inTransaction()){
            $writeDB->rollBack();
        }
        responseServerException($e, "Failed to update image attributes");
    }

?>