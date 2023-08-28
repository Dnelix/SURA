<?php
    try{
        $jsonImageAttributes    = validateFileAttributes();
        $filetype               = isset($jsonImageAttributes->type) ? $jsonImageAttributes->type : null;
        $filetitle              = isset($jsonImageAttributes->title) ? $jsonImageAttributes->title : null;
        $imageFieldName         = isset($jsonImageAttributes->filename) ? $jsonImageAttributes->filename : null;
        $imageFileName          = isset($jsonImageAttributes->filename) ? $jsonImageAttributes->filename : null;
        $updated                = date($dateformat);

        if(empty($userid) || empty($refid) || empty($imageFieldName)){
            sendResponse(404, false, 'Key attributes missing from the request');
        }

        $imageFileDetails       = getUploadFileDetails($imageFieldName);
        $imageFileMime          = $imageFileDetails['imgsize']['mime'];
        $imageName              = $imageFileDetails['imgdata']['name'];
        $fileExt                = getUploadFileExtension($imageName);

        // Check reference
        $query = $readDB ->prepare('SELECT id FROM tbl_users WHERE id = :refid');
        $query -> bindParam(':refid', $refid, PDO::PARAM_INT);
        $query -> execute();

        if($query -> rowCount() === 0){
            sendResponse(404, false, 'Reference not found');
        }

        //confirm image exists
        $all_fields = 'id, title, filetype, filename, mimetype, refid, userid, updated';

        $sql = 'SELECT ' .$all_fields. ' FROM tbl_uploads WHERE id = :imageid AND refid = :refid AND userid = :userid';
        $query = $writeDB->prepare($sql);
        $query->bindParam(':imageid', $imageid, PDO::PARAM_INT);
        $query->bindParam(':refid', $refid, PDO::PARAM_INT);
        $query->bindParam(':userid', $userid, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();
        if($rowCount === 0){
            if($writeDB->inTransaction()){ $writeDB->rollBack(); }
            sendResponse(404, false, 'Record not found');
        }

        // create the image using the image model: ($id, $title, $filename, $mimetype, $refid, $folderURL, $filetype, $updated)
        $image = new Image($imageid, $filetitle, $imageFileName.$fileExt, $imageFileMime, $refid, $uploadFolderURL, $filetype, $updated);
        $title = $image -> getTitle();
        $newFileName = $image -> getFilename();
        $mimetype = $image -> getMimetype();

        // utilize the rollback function to ensure that DB writing only occurs when the image have been successfully uploaded
        $writeDB -> beginTransaction();

        //insert into DB
        $set_fields = 'title=:title, filetype=:filetype, filename=:filename, mimetype=:mimetype, updated=STR_TO_DATE(:updated, '. $write_dateformat .')';
        $query = $writeDB -> prepare('UPDATE tbl_uploads SET '. $set_fields .' WHERE id = :imageid AND refid = :refid AND userid = :userid');
        $query -> bindParam(':title', $title, PDO::PARAM_STR);
        $query -> bindParam(':filetype', $filetype, PDO::PARAM_STR);
        $query -> bindParam(':filename', $newFileName, PDO::PARAM_STR);
        $query -> bindParam(':mimetype', $mimetype, PDO::PARAM_STR);
        $query -> bindParam(':updated', $updated, PDO::PARAM_STR);
        $query -> bindParam(':imageid', $imageid, PDO::PARAM_INT);
        $query -> bindParam(':refid', $refid, PDO::PARAM_INT);
        $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
        $query -> execute();

        $rowCount = $query->rowCount();
        if ($rowCount === 0){
            if($writeDB->inTransaction()){
                $writeDB -> rollback(); //rollback if there's a database write activity going on before this failure.
            }
            sendResponse(500, false, "Failed to upload image!");
        }

        $query = $writeDB->prepare('SELECT ' .$all_fields. ' FROM tbl_uploads WHERE id = :imageid AND refid = :refid');
        $query -> bindParam (':imageid', $imageid, PDO::PARAM_INT);
        $query -> bindParam (':refid', $refid, PDO::PARAM_INT);
        $query -> execute();

        $rowCount = $query -> rowCount();
        If ($rowCount === 0){
            if($writeDB->inTransaction()){
                $writeDB->rollback();
            }
            sendResponse(500, false, 'Failed to retrieve image data after upload. Try again');
        }

        // else return the image details from the query
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

        //call the function to upload image (Written in the image model)
        $image->saveImageFile($imageFileDetails['imgdata']['tmp_name']);

        // everything okay? commit the db updates
        $writeDB->commit();
        sendResponse(201, true, "Image updated successfully", $imageArray, false);
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

?>