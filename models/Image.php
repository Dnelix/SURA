<?php
 
class ImageException extends Exception {}

Class Image {

    private $_id;
    private $_title;
    private $_filetype;
    private $_filename;
    private $_mimetype;
    private $_refid;
    private $_updated;

    private $_uploadFolderURL;

    //constructor
    public function __construct($id, $title, $filename, $mimetype, $refid, $updated){
        $this->setID($id);
        $this->setTitle($title);
        $this->_filetype = "image";
        $this->setFilename($filename);
        $this->setMimetype($mimetype);
        $this->setRefID($refid);
        $this->setUpdated($updated);
        $this->_uploadFolderURL = "../assets/media/uploads/";
    }

    //getters
    public function getID(){
        return $this->_id;
    }
    public function getTitle(){
        return $this->_title;
    }
    public function getFiletype(){
        return $this->_filetype;
    }
    public function getFilename(){
        return $this->_filename;
    }
    public function getFileExtension(){
        $nameParts = explode(".", $this->_filename);
        $lastElement = count($nameParts) - 1;
        $fileExtension = $nameParts[$lastElement];
        return $fileExtension;
    }
    public function getMimetype(){
        return $this->_mimetype;
    }
    public function getRefID(){
        return $this->_refid;
    }
    public function getUpdated(){
        return $this->_updated;
    }
    public function getUploadFolderURL(){
        return $this->_uploadFolderURL;
    }
    public function getImageURL(){
        global $c_website;
        $httpOrHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://"; // check to see the kind of server in use and build the URL accordingly
        $host = $_SERVER['HTTP_HOST'];
        $url = "/2023/SURA/assets/media/uploads/".$this->getRefID()."/".$this->getFilename();

        return $httpOrHttps.$host.$url;
    }

    //setters
    public function setID($id){
        if($id !== null && (!is_numeric($id) || $id <= 0 || $id > 99999999999 || $this->_id !== null)){
            throw new ImageException("Image ID Error");
        }
        $this->_id = $id;
    }
    public function setTitle($title){
        if(strlen($title) < 1 || strlen($title) > 255){
            throw new ImageException ("Image title error");
        }
        $this->_title = $title;
    }
    public function setFiletype($filetype){
        $this->_filetype = $filetype;
    }
    public function setFilename($filename){
        if(strlen($filename) < 1 || strlen($filename) > 30){
            throw new ImageException("ERROR: Image filename must be between 1 and 30 characters");
        }
        if (preg_match("/^[a-zA-Z0-9_-]+(.jpg|.gif|.png)$/", $filename) != 1){
            throw new ImageException("ERROR: Image filename must be .png/.gif/.jpg ");
        }
        $this->_filename = $filename;
    }
    public function setMimetype($mimetype){
        if(strlen($mimetype) < 1 || strlen($mimetype) > 50){
            throw new ImageException("Image mime-type error");
        }
        $this->_mimetype = $mimetype;
    }
    public function setRefID($refid){
        if($refid !== null && (!is_numeric($refid) || $refid <= 0 || $refid > 999999999 || $this->_refid !== null)){
            throw new ImageException("Associated/reference ID Error");
        }
        $this->_refid = $refid;
    }
    public function setUpdated($updated){
        $this->_updated = $updated;
    }

    //Save image
    public function saveImageFile($tempFileName){
        //$uploadedFilePath = $uploadPath .'/'. $this->getRefID() .'/'. $this->getFilename();
        $uploadedFilePath = $this->getUploadFolderURL().$this->getRefID() . '/' . $this->getFilename();
        // if directory doesn't exist
        if(!is_dir($this->getUploadFolderURL().$this->getRefID())){
            //try creating the directory and check
            if(!mkdir($this->getUploadFolderURL().$this->getRefID())){
                //Doesn't work? Throw an exception
                throw new ImageException("Failed to create image upload folder for reference");
            }
        }

        if(!file_exists($tempFileName)){
            throw new ImageException("Failed to upload image file");
        }

        if(!move_uploaded_file($tempFileName, $uploadedFilePath)){
            throw new ImageException("Failed to upload image file. Please retry");
        }
    }

    //return as array
    public function returnImageAsArray(){
        $image = array();
        $image['id']        = $this -> getID();
        $image['title']     = $this -> getTitle();
        $image['filetype']  = $this -> getFiletype();
        $image['filename']  = $this -> getFilename();
        $image['mimetype']  = $this -> getMimetype();
        $image['refid']     = $this -> getRefID();
        $image['ImageURL']  = $this -> getImageURL();
        $image['updated']   = $this -> getUpdated();

        return $image;
    }

}