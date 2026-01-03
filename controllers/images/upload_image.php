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
        //$imageFileName          = getUploadFilename($imageName);
        //$filetitle              = $filetype." - New Upload";

        // Check user
        $query = $readDB ->prepare('SELECT id FROM tbl_users WHERE id = :refid');
        $query -> bindParam(':refid', $refid, PDO::PARAM_INT);
        $query -> execute();

        if($query -> rowCount() === 0){
            sendResponse(404, false, 'User not found');
        }

        // create the image using the image model: ($id, $title, $filename, $mimetype, $refid, $folderURL, $filetype, $updated)
        $image = new Image(null, $filetitle, $imageFileName.$fileExt, $imageFileMime, $refid, $uploadFolderURL, $filetype, $updated);
        $title = $image -> getTitle();
        $newFileName = $image -> getFilename();
        $mimetype = $image -> getMimetype();

        // check to ensure image name does not already exist against the user 
        $query = $readDB->prepare('SELECT id FROM tbl_uploads WHERE refid = :refid AND filename = :imagefilename');
        $query -> bindParam (':refid', $refid, PDO::PARAM_INT);
        $query -> bindParam (':imagefilename', $newFileName, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query -> rowCount();
        if($rowCount !== 0){
            sendResponse(409, false, "This file already exists. Update instead");
        }

        // utilize the rollback function to ensure that DB writing only occurs when the image have been successfully uploaded
        $writeDB -> beginTransaction();

        //insert into DB
        $query = $writeDB -> prepare('INSERT INTO tbl_uploads (title, filetype, filename, mimetype, refid, userid, updated) VALUES(:title, :filetype, :filename, :mimetype, :refid, :userid, STR_TO_DATE(:updated, '. $write_dateformat .'))');
        $query -> bindParam(':title', $title, PDO::PARAM_STR);
        $query -> bindParam(':filetype', $filetype, PDO::PARAM_STR);
        $query -> bindParam(':filename', $newFileName, PDO::PARAM_STR);
        $query -> bindParam(':mimetype', $mimetype, PDO::PARAM_STR);
        $query -> bindParam(':refid', $refid, PDO::PARAM_INT);
        $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
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

        $query = $writeDB->prepare('SELECT id, title, filetype, filename, mimetype, refid, updated FROM tbl_uploads WHERE id = :imageid AND refid = :refid');
        $query -> bindParam (':imageid', $lastImageId, PDO::PARAM_INT);
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

?>