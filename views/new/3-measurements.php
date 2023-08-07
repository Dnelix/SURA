
<div class="flex-row-fluid d-flex flex-center justfiy-content-xl-first p-10">
    <div class="d-flex flex-center p-15 shadow-sm bg-body rounded w-150 w-md-700px mx-auto ms-xl-20">

      <form class="form" novalidate="novalidate" id="new_cust_measure_form" onSubmit="return false;">
        <div class="text-center mb-10">
          <h1 class="text-dark mb-3" id="logtext">Let's take your measurements</h1>
          <div class="text-gray-400 fw-bold fs-4">We will show any previous data you've provided in the fields below.</div>
        </div>

        <div class="col-md-12">
          <div class="row fv-row">
          <?php
            foreach ($UBmeasures as $data){
              $metadata = $data->metadata;
              $value = empty($data->value)?'':$data->value;
          ?>
              <div class="col-6 mb-8">
                <label class="fs-6 fw-bold form-label">
                  <?= $metadata->label;  ?> 
                  <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="<?= $metadata->tooltip; ?>"></i>
                </label>
                <input type="number" name="<?= $metadata->name;  ?>" class="form-control form-control-lg form-control-solid" placeholder="0.0" value="<?= $value;?>" />
              </div>
          <?php
            }
          ?>
          </div>
        </div>

        <div class="col-md-12">
          <div class="row fv-row">
          <?php
            foreach ($LBmeasures as $data){
              $metadata = $data->metadata;
              $value = empty($data->value)?'':$data->value;
          ?>
              <div class="col-6 mb-8">
                <label class="fs-6 fw-bold form-label">
                  <?= $metadata->label;  ?> 
                  <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="<?= $metadata->tooltip; ?>"></i>
                </label>
                <input type="number" name="<?= $metadata->name;  ?>" class="form-control form-control-lg form-control-solid" placeholder="0.0" value="<?= $value;?>" />
              </div>
          <?php
            }
          ?>
          </div>
        </div>

        <div class="text-center pb-lg-0 pb-8">
          <button type="submit" id="new_cust_measure_submit" class="btn btn-lg btn-primary w-100 mb-5" onClick="NewCustMeasure()">
            <?= displayLoadingIcon('I\'m Done'); ?>
          </button>
        </div>
      </form>
    </div>
  </div>

<script>
  function NewCustMeasure(){
    var web = '<?= $c_website; ?>';
    var cid = '<?= $_GET['cid']; ?>';

    var formID = "#new_cust_measure_form";
    var submitButton = document.querySelector('#new_cust_measure_submit');
    var type = "PATCH";
    var url = web+"controllers/measurements.php?customer="+cid;

    AJAXcall(formID, submitButton, type, url, null, (responseMsg)=>{
      if(responseMsg.status !== 'success'){
        swal_Popup(responseMsg.status, responseMsg.details);
        return false; 
      }
      //else we go to a page that allows us save or add project
    });

  }
</script>