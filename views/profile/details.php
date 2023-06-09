<?php
    include_once('details/1-editprofile.php');
    include_once('details/2-signinmethod.php');
    if($userdata->active == "1"){
        include_once('details/3-deactivate.php');
    } else {
        include_once('details/4-activate.php');
    }
?>