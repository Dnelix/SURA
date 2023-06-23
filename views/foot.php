        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script>var hostUrl = "<?= $c_website; ?>";</script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!-- datatables-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/custom/custom.js"></script>
        <script src="assets/js/custom/modals/new-customer.js"></script>
        <script src="assets/js/custom/modals/add_measurements/complete.js"></script>
        <script src="assets/js/custom/modals/add_measurements/add_UB.js"></script>
        <script src="assets/js/custom/modals/add_measurements/add_LB.js"></script>
        <script src="assets/js/custom/modals/add_measurements.js"></script>
        
        <!--begin::Page Level Javascript-->
        <?php 
        if ($curPage == "" || $curPage == $home){
            //Auth pages
            echo '<script src="assets/js/custom/auth/sign-in.js"></script>';
            echo '<script src="assets/js/custom/auth/sign-up.js"></script>';
            echo '<script src="assets/js/custom/auth/password-reset.js"></script>';
            echo '<script src="assets/js/custom/auth/new-password.js"></script>';
        } else if ($curPage == "profile"){
            switch($curSubpage){
                case 'business':
                echo '';
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
            //     <script src="assets/js/custom/modals/upper_body.js"></script>
            //     <script src="assets/js/custom/modals/lower_body.js"></script>
            // ';
        } else if($curPage == "add_customer"){  
            echo '<script src="assets/js/custom/pages/add-customer.js"></script>';
        } else {
            /*Dashboard & others
            echo '<script src="assets/js/widgets.bundle.js"></script>
            <script src="assets/js/custom/widgets.js"></script>
            <script src="assets/js/custom/apps/chat/chat.js"></script>
            <script src="assets/js/custom/intro.js"></script>
            <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
            <script src="assets/js/custom/utilities/modals/create-app.js"></script>
            <script src="assets/js/custom/utilities/modals/create-campaign.js"></script>
            <script src="assets/js/custom/utilities/modals/users-search.js"></script>';*/
        } 
        ?>
        <!-- end::Page Level Javascript-->
        <!--end::Javascript-->
	</body>
</html>