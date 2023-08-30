<?php
    if (isset($_GET['tid']) && !empty($_GET['tid'])){ // for now, you must have a tailor ID to register
        if (isset($_GET['cid']) && !empty($_GET['cid'])){
            $infoTitle = 'Provide or update your measurements';
            $infoText = 'Take your measurements or find someone to help you <br> Use the hints against each measurement if you need guidance.';
            $infoImage = 'assets/media/illustrations/sigma-1/8.png';
            $infoLinks = '<a href="'.$signup_link.'">LOGIN</a> &nbsp; | &nbsp; <a href="#">HOW IT WORKS</a>';
            
        } else {
            $infoTitle = 'Hi there, your tailor needs your help';
            $infoText = 'Use the simple form below to provide your details.';
            $infoImage = 'assets/media/illustrations/sigma-1/8.png';
            $infoLinks = '<a href="?login">LOGIN</a> &nbsp; | &nbsp; <a href="#">HOW IT WORKS</a>';
        }
    } else if (array_key_exists('login', $_GET)){
        $infoTitle = 'Hi there, your tailor needs your help';
        $infoText = 'Login to manage your information. Ask your tailor for a link if you don\'t have an account';
        $infoImage = 'assets/media/illustrations/sigma-1/8.png';
        $infoLinks = '<a href="history.back();">Go Back</a> &nbsp;';
    } else {
        $infoTitle = $infoImage = $infoLinks = $infoText = "undefined";
    }
?>

<div class="d-flex flex-column flex-center flex-lg-row-fluid">
    <div class="d-flex align-items-start flex-column p-5 p-lg-15">
        <a href="home" class="mb-15">
            <img alt="Logo" src="assets/media/logos/logo.png" class="h-40px" />
        </a>
        <h1 class="text-dark fs-2x mb-3"><?= $infoTitle; ?></h1>
        <div class="fw-bold fs-4 text-gray-400 mb-10">
            <?= $infoText; ?>
        </div>
        <img src="<?= $infoImage; ?>" alt="" class="h-250px h-lg-350px" />

        <div class="fs-4 text-gray-400 mb-2"><i>Powered by <span class="fw-bold"><?= $c_shortsite; ?></span></i></div>

        <div class="fw-bold fs-4 text-gray-400 mb-10"><?= $infoLinks; ?></div>

    </div>
</div>