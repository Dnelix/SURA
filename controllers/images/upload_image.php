<?php
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

?>