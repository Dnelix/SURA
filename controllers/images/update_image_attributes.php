<?php
    try{
        $jsonData = validateJsonRequest();

        $all_fields = 'title, filename';
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

        $sql = 'SELECT id, title, filetype, filename, mimetype, ref_id FROM tbl_uploads WHERE id = :imgid AND ref_id = :refid';
        $query = $writeDB->prepare($sql);
        $query->bindParam(':imgid', $imageid, PDO::PARAM_INT);
        $query->bindParam(':refid', $ref_id, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();
        if($rowCount === 0){
            if($writeDB->inTransaction()){ $writeDB->rollBack(); }
            sendResponse(404, false, 'Image not found');
        }

        $image = null;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $image = new Image(
                $row['id'],
                $row['title'],
                $row['filename'],
                $row['mimetype'],
                $row['ref_id']
            );
        }

        if ($image === null){ sendResponse(404, false, 'Image not created'); }

        //write out the query string. Concatenate queryfields
        //$queryString = "UPDATE tbl_uploads INNER JOIN tbl_users ON tbl_uploads.ref_id = tbl_users.id SET ".$queryFields." WHERE tbl_uploads.ref_id = :refid AND tbl_uploads.id = :imgid";
        $queryString = "UPDATE tbl_uploads SET ".$queryFields." WHERE ref_id = :refid AND id = :imgid";
        $query = $writeDB -> prepare($queryString);
        $query -> bindParam(':refid', $ref_id, PDO::PARAM_INT);
        $query -> bindParam(':imgid', $imageid, PDO::PARAM_INT);
        if($title === true){
            $image->setTitle($jsonData->title);
            $up_title = $image->getTitle();
            $query->bindParam(':title', $up_title, PDO::PARAM_STR);
        }
        if($filename === true){
            $old_name = $image->getFilename(); //get current name for comparison
            $image->setFilename($jsonData->filename.".".$image->getFileExtension());
            $up_filename = $image->getFilename();
            $query->bindParam(':filename', $up_filename, PDO::PARAM_STR);
        }
        if($title !== true && $filename !== true){
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
        $sql = 'SELECT id, title, filetype, filename, mimetype, ref_id, updated FROM tbl_uploads WHERE id = :imgid AND ref_id = :refid';
        $query = $writeDB -> prepare($sql);
        $query->bindParam(':imgid', $imageid, PDO::PARAM_INT);
        $query->bindParam(':refid', $ref_id, PDO::PARAM_INT);
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
                $row['ref_id'],
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