<?php include_once('views/head.php'); ?>
<!---------------------------------------->
<body id="kt_body" class="bg-body"> 
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-xl-row flex-column-fluid">

        <?php 
            $path = $_SERVER['QUERY_STRING'];
            // $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            // $parts = explode('?', $url);
            // $path = end($parts);

            include_once('views/home/main.php');
            
            if (file_exists('views/home/' .$path. '.php')){
                include_once('views/home/' .$path. '.php');
            }
            else { include_once('views/home/login.php'); }
            
        ?>

        </div>
    </div>

<!---------------------------------------->
<?php include_once('views/foot.php'); ?>