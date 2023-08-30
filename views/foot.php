        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script>var hostUrl = "<?= $c_website; ?>";</script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!-- Custom globals -->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/custom/custom.js"></script>
        <!-- Modals -->
        <script src="assets/js/custom/modals/new-customer.js"></script>
        <script src="assets/js/custom/modals/add_measurements/complete.js"></script>
        <script src="assets/js/custom/modals/add_measurements/add_UB.js"></script>
        <script src="assets/js/custom/modals/add_measurements/add_LB.js"></script>
        <script src="assets/js/custom/modals/add_measurements.js"></script>
        
        <!--begin::Page Level Javascript-->
        <?php 
        if ($curPage == "" || $curPage == $home){
            if(array_key_exists('signup', $_GET)){
                echo '<script src="assets/js/custom/auth/sign-up.js"></script>';
            } else if(array_key_exists('password_reset', $_GET)){
                echo '<script src="assets/js/custom/auth/password-reset.js"></script>';
            } else if(array_key_exists('new_password', $_GET)){
                echo '<script src="assets/js/custom/auth/new-password.js"></script>';
            } else {
                echo '<script src="assets/js/custom/auth/sign-in.js"></script>';
            }
        } else if ($curPage == "profile"){
            switch($curSubpage){
                case 'business':
                echo '<script src="assets/js/custom/pages/profile/business.js"></script>';
                break;
                case 'security':
                echo '';
                break;
                case 'plans':
                echo '';
                break;
                case 'referrals':
                echo '';
                break;
                default:
                echo '<script src="assets/js/custom/pages/profile/profile-details.js"></script>';
                echo '<script src="assets/js/custom/pages/profile/signin-methods.js"></script>';
                echo '<script src="assets/js/custom/pages/profile/deactivate-account.js"></script>';
                echo '<script src="assets/js/custom/pages/profile/activate-account.js"></script>';
            }
        } else if($curPage == "customers" && empty($_GET['cid'])){
            echo '<script src="assets/js/custom/pages/dataTablesInit.js"></script>';
        } else if($curPage == "add_customer"){  
            echo '<script src="assets/js/custom/pages/add-customer.js"></script>';
        } else if($curPage == "add_project"){
            echo '<script src="assets/js/custom/pages/add-project.js"></script>';
        } else if($curPage == "new"){
            if(array_key_exists('login', $_GET)){
                echo '<script src="assets/js/custom/auth/sign-in.js"></script>';
            } else {
                echo '<script src="assets/js/custom/auth/customer_reg.js"></script>';
            }
        }
        ?>
	</body>
</html>