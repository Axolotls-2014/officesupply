<?php $this->load->view('layout/header');?>

  <style>
    .normal_font{
      font-weight:normal !important;
    }
  </style>
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <!-- <li class="breadcrumb-item active"><a href="#"><?=$this->lang->line('header_custom_field')?></a></li> -->
                <li class="breadcrumb-item active"><?=$this->lang->line('header_custom_field')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" name="productSettingForm" id="productSettingForm" method="post" enctype="multipart/form-data">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="pt-2 px-3">
                                <h3 class="card-title"><?= $this->lang->line('header_custom_field') ?></h3>
                            </li>
                            <?php 
                            // Fetch distinct module names
                            $distinct_modules = array_unique(array_column($custom_fields, 'module_name'));

                            sort($distinct_modules);
                            
                            // Iterate over distinct module names
                            foreach($distinct_modules as $index => $module_name) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" data-toggle="pill" href="#custom-tabs-module-<?= $module_name ?>" role="tab" aria-controls="custom-tabs-module-<?= $module_name ?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                                <?= strtoupper($module_name) ?>
                                </a>
                            </li>
                            <?php 
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <?php 
                            // Iterate over distinct module names
                            foreach($distinct_modules as $index => $module_name) {
                            ?>
                            <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="custom-tabs-module-<?= $module_name ?>" role="tabpanel" aria-labelledby="custom-tabs-module-<?= $module_name ?>-tab">
                                <?php 
                                // Filter fields for the current module
                                $module_fields = array_filter($custom_fields, function($field) use ($module_name) {
                                    return $field['module_name'] === $module_name;
                                });
                                
                                // Iterate over fields for the current module
                                foreach($module_fields as $field) {
                                ?>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?= $field['field_label'] ?></label>
                                    <div class="col-sm-9">
                                        <!-- Hidden input field to store the ID -->
                                        <input type="hidden" name="field_id[]" value="<?= $field['id'] ?>">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" class="<?= $field['field_name'] ?>" id="<?= $field['field_name'] ?>_status_active" name="field_status[<?= $field['id'] ?>]" value="active" <?= ($field['field_status'] == 'active') ? 'checked' : '' ?>>
                                            <label for="<?= $field['field_name'] ?>_status_active" class="normal_font">
                                                Active
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline ml-4">
                                            <input type="radio" class="<?= $field['field_name'] ?>" id="<?= $field['field_name'] ?>_status_inactive" name="field_status[<?= $field['id'] ?>]" value="inactive" <?= ($field['field_status'] == 'inactive' || !in_array($field['field_name'], array_column($module_fields, 'field_name'))) ? 'checked' : '' ?>>
                                            <label for="<?= $field['field_name'] ?>_status_inactive" class="normal_font">
                                                Inactive
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                }
                                ?>
                            </div>
                            <?php 
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
                        <button type="submit" name="submit" id="companysettingSubmit" class="btn btn-info" data-tt="tooltip" title="<?= $this->lang->line('click_save') ?>"><?= $this->lang->line('company_setting_save') ?></button>
                    </div>
                </div>
                <!-- /.card -->
            </form>
        </div>
    </div>
</section>

  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">

  $(document).ready(function(e){

    var gstReg = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

    $('form#productSettingForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#companysettingSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#productSettingForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#productSettingForm #err_"+id).text(field+ " field is required.");
            $('form#productSettingForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#productSettingForm #err_"+id).text("");
            $('form#productSettingForm #'+id).removeClass('is-invalid');
            $('form#productSettingForm #'+id).addClass('is-valid');
          }

          if (!gstReg.test($('form#productSettingForm #gstin').val()) && $('form#productSettingForm #gst_registration_type').val() == 1) 
          {
            if($('form#productSettingForm #gstin').val() == '')
            {
              // $('form#productSettingForm #gstin').val('');
              $("form#productSettingForm #err_gstin").text('GSTIN field is required');
              $('form#productSettingForm #gstin').addClass('is-invalid');
              isError = true;
            }
            else
            {
              // $('form#productSettingForm #gstin').val('');
              $("form#productSettingForm #err_gstin").text('Please enter valid GSTIN');
              $('form#productSettingForm #gstin').addClass('is-invalid');
              isError = true;  
            }
          }
          else
          {
            
            $("form#productSettingForm #err_gstin").text("");
            $('form#productSettingForm #gstin').removeClass('is-invalid');
            $('form#productSettingForm #gstin').addClass('is-valid');
          }
      });


      if(isError == true)
      {
         $('#companysettingSubmit').text('<?=$this->lang->line("company_setting_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#productSettingForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#productSettingForm #err_"+id).text(field+ " field is required.");
          $('form#productSettingForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#productSettingForm #err_"+id).text("");
          $('form#productSettingForm #'+id).removeClass('is-invalid');
          $('form#productSettingForm #'+id).addClass('is-valid');
        }
    });

    $("form#productSettingForm #gstin").on("blur keyup change",  function (event){

      if (!gstReg.test($('form#productSettingForm #gstin').val()) && $('form#productSettingForm #gst_registration_type').val() == 1) 
      {        
        if($('form#productSettingForm #gstin').val() == "")
        {
          $("form#productSettingForm #err_gstin").text("GSTIN field is required.");
          $('form#productSettingForm #gstin').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#productSettingForm #err_gstin").text("Please enter valid GSTIN.");
          $('form#productSettingForm #gstin').addClass('is-invalid');
          return false;
        }
      }
      else
      {
        $("form#productSettingForm #err_gstin").text("");
        $('form#productSettingForm #gstin').removeClass('is-invalid');
        $('form#productSettingForm #gstin').addClass('is-valid');
      }
    });

  });
</script>


<script>
  
  $(document).ready(function(e){
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

    $('#country_id').change(function(){
      var id = $(this).val();
       // alert(id);
      $('#state_id').html('<option value="">Select</option>');
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
           url: "<?php echo base_url('utility/get_states') ?>/"+id,
           type: "GET",
           dataType: "JSON",
           success: function(data){
             for(i=0;i<data.length;i++){
               $('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
             }
           }
         });
    });

    $('#state_id').change(function(){
      var id = $(this).val();
      
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
           url: "<?php echo base_url('utility/get_cities') ?>/"+id,
           type: "GET",
           dataType: "JSON",
           success: function(data){
             for(i=0;i<data.length;i++){
               $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
             }
           }
         });
    });
  });
</script>
