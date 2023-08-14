<div class="flex-row-fluid d-flex flex-center justfiy-content-xl-first p-10">
    <div class="d-flex flex-center p-15 shadow-sm bg-body rounded w-100 w-md-550px mx-auto ms-xl-20">

      <form class="form" novalidate="novalidate" id="new_customer_check" onSubmit="return false;">
        <div class="text-center mb-10">
          <h1 class="text-dark mb-3" id="logtext">First, some basic info</h1>
          <div class="text-gray-400 fw-bold fs-4">If you already have an account, we will let you know.</div>
        </div>

        <input type="hidden" name="tailor" value="<?= $_GET['tid']; ?>" />

        <div class="fv-row mb-5">
          <label class="form-label fw-bolder text-dark fs-6">Your name</label>
          <input class="form-control form-control-solid" type="text" placeholder="" name="fullname" autoComplete="on" />
        </div>
        <div class="fv-row mb-5">
          <label class="form-label fw-bolder text-dark fs-6">Your phone number</label>
          <input class="form-control form-control-solid" type="phone" placeholder="" name="phone" autoComplete="on" />
        </div>
        <div class="fv-row mb-5">
          <label class="form-label fw-bolder text-dark fs-6">Email</label>
          <input class="form-control form-control-solid" type="email" placeholder="" name="email" autoComplete="on" />
        </div>
        <div class="fv-row mb-10">
          <label class="form-label fw-bolder text-dark fs-6">Password</label>
          <input class="form-control form-control-solid" type="password" placeholder="" name="password" autoComplete="on" />
          <div class="text-muted fs-7">With a password you can manage your data anytime</div>
        </div>
      
        <div class="text-center pb-lg-0 pb-8">
          <button type="submit" id="new_customer_submit" class="btn btn-lg btn-primary w-100 mb-5" onClick="checkNewCust()">
            <?= displayLoadingIcon('Let\'s go!'); ?>
          </button>
        </div>
      </form>
    </div>
  </div>

<script>
  function checkNewCust(){
    var web = '<?= $c_website; ?>';
    var tid = '<?= $_GET['tid']; ?>';

    var formID = "#new_customer_check";
    var submitButton = document.querySelector('#new_customer_submit');
    var type = "POST";
    var url = web+"controllers/users.php?type=customer";

    AJAXcall(formID, submitButton, type, url, null, (responseMsg)=>{
      if(responseMsg.status !== 'success'){
        swal_Popup(responseMsg.status, responseMsg.message);
        return false; 
      }
      swal_confirm("We have saved your information but there\'s one more step", "Continue to add measurements", "", false, 'success')
      .then((result) => {
          if (result.isConfirmed) {
              goTo(web+'new?tid='+tid+'&cid='+responseMsg.data["user_id"]);
          } else if (result.isDenied) {
              console.log("Canceled");
          }
      })
      .catch((error) => {
          console.error(error);
      });
    });

  }
</script>
