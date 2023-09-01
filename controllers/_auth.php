<?php
    //if session is not set, redirect to login
    $externalPages = array("", $home, "new");

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        $userloggedin   = (isset($_SESSION["loggedin"]) ? $_SESSION["loggedin"]:false);
        $logsessionid   = (isset($_SESSION["id"]) ? $_SESSION["id"]:false);
        $loguserid      = (isset($_SESSION["userid"]) ? $_SESSION["userid"]:false);
        $logusername    = (isset($_SESSION["username"]) ? $_SESSION["username"]:false);
        $logtoken       = (isset($_SESSION["accesstoken"]) ? $_SESSION["accesstoken"]:false);
        $logrole        = (isset($_SESSION["role"]) ? $_SESSION["role"]:false);

        if ($curPage == "" && $curPage == $home){
            echo '<script> window.location.href = "dashboard"; </script>';
            exit();
        }

    } else {

        if ($curPage != "" && $curPage != $home && !in_array($curPage, $externalPages) ){
            echo '<script> window.location.href = "'. $home .'"; </script>';
            exit();
        }
    }

?> 