        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script>var hostUrl = "<?= $c_website; ?>";</script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/custom/custom.js"></script>
        <script src="assets/js/custom/modals/new-customer.js"></script>
        <script src="assets/js/custom/modals/new-project.js"></script>
        <script src="assets/js/custom/modals/add_measurements/complete.js"></script>
        <script src="assets/js/custom/modals/add_measurements/add_UB.js"></script>
        <script src="assets/js/custom/modals/add_measurements/add_LB.js"></script>
        <script src="assets/js/custom/modals/add_measurements.js"></script>
        
        <!--begin::Page Level Javascript-->
        <?php 
        if ($curPage == "" || $curPage == $home){
            echo '<script src="assets/js/custom/auth/sign-in.js"></script>';
            echo '<script src="assets/js/custom/auth/sign-up.js"></script>';
            echo '<script src="assets/js/custom/auth/password-reset.js"></script>';
            echo '<script src="assets/js/custom/auth/new-password.js"></script>';
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
        } else if($curPage == "customers"){
            echo '
                <script src="assets/js/custom/pages/dataTablesInit.js"></script>';
        } else if($curPage == "add_customer"){  
            echo '<script src="assets/js/custom/pages/add-customer.js"></script>';
        } else if($curPage == "add_project"){
            echo '<script src="assets/js/custom/pages/add-project.js"></script>';
        }
        ?>
	</body>
</html>