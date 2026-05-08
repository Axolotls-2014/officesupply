<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_expense')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('supplier')?>"><?=$this->lang->line('supplier_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('supplier_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editSupplierForm" id="editSupplierForm" method="post" action="<?php echo base_url('supplier/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('supplier_edit')?></h3>
                  <div class="card-tools">
                    
                    <ul class="nav nav-pills ml-auto">

                      <li class="nav-item ml-2">
                        <a class="nav-link active text-white btn btn-secondary" href="<?=base_url('supplier')?>" data-tt="tooltip" title="Click here to show supplier list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>
                    </ul>
                  
                  </div>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_company_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="company_name" value="<?=set_value('company_name',$supplier->company_name) ?>" class="form-control form-control-sm field_validation" id="company_name" placeholder="<?=$this->lang->line('company_name')?>"><?=form_error('company_name', '<div class="text-danger">', '</div>');?>
                      <span id="err_company_name" class="error invalid-feedback"><?=form_error('company_name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('supplier_gst_registration_type')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="gst_registration_type" id="gst_registration_type" width="100%" placeholder="<?=$this->lang->line('supplier_gst_registration_type')?>">
                        <option value="1" <?php if($supplier->gst_registration_type == 1) echo ' selected'; ?>><?=$this->lang->line('gst_reg_type_reg')?></option>
                        <option value="0" <?php if($supplier->gst_registration_type == 0) echo ' selected'; ?>><?=$this->lang->line('gst_reg_type_not_reg')?></option>
                        <option value="2" <?php if($supplier->gst_registration_type == 2) echo ' selected'; ?>><?=$this->lang->line('gst_reg_type_composite')?></option>
                      </select>
                      <span id="err_gst_registration_type" class="error invalid-feedback"><?=form_error('gst_registration_type');?></span>
                    </div>
                  </div>
                  <div class="form-group row gstin_row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_gstin')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="gstin" value="<?=set_value('gstin',(($supplier->gst_registration_type == 1) ? $supplier->gstin : '')) ?>" class="form-control form-control-sm" id="gstin" placeholder="<?=$this->lang->line('gstin')?>">
                      <span id="err_gstin" class="error invalid-feedback"><?=form_error('gstin');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('supplier_contact_person_name')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="contact_person_name" value="<?=set_value('contact_person_name',$supplier->contact_person_name) ?>" class="form-control form-control-sm field_validation" id="contact_person_name" placeholder="<?=$this->lang->line('supplier_contact_person_name')?>"><?=form_error('contact_person_name', '<div class="text-danger">', '</div>');?>
                      <span id="err_contact_person_name" class="error invalid-feedback"><?=form_error('contact_person_name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('supplier_contact_person_designation')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="contact_person_designation" value="<?=set_value('contact_person_designation',$supplier->contact_person_designation) ?>" class="form-control form-control-sm field_validation" id="contact_person_designation" placeholder="<?=$this->lang->line('supplier_contact_person_designation')?>"><?=form_error('contact_person_designation', '<div class="text-danger">', '</div>');?>
                      <span id="err_contact_person_designtion" class="error invalid-feedback"><?=form_error('contact_person_designation');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_email')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="email" value="<?php echo $supplier->email;?>" class="form-control form-control-sm" id="email" placeholder="<?=$this->lang->line('supplier_email')?>">
                      <span id="err_email" class="error invalid-feedback"><?=form_error('email');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_phone')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="phone" value="<?php echo $supplier->phone;?>" class="form-control form-control-sm field_validation" id="phone" placeholder="<?=$this->lang->line('supplier_phone')?>">
                      <?=form_error('phone', '<div class="text-danger">', '</div>');?>
                      <span id="err_phone" class="error invalid-feedback"><?=form_error('phone');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_country_id')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      
                      <select class="form-control form-control-sm select2bs4" value="<?php echo $supplier->country_id;?>" name="country_id" id="country_id" width="100%">

                        <?php
                          foreach ($countries as $value) {
                        ?>
                          <option value="<?=$value->id;?>"
                            <?php 
                              if($value->id == $supplier->country_id)
                                echo ' selected';
                            ?>
                          >
                            <?= $value->name;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_state_id')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" value="<?php echo $supplier->state_id;?>"  name="state_id" id="state_id" width="100%">

                         <?php
                          foreach ($states as $value) {
                        ?>
                          <option value="<?=$value->id;?>"
                            <?php
                              if(isset($state_id))
                              {
                                if($state_id == $value->id)
                                {
                                  echo ' selected';
                                }
                              }
                              else
                              {
                                if($supplier->state_id == $value->id)
                                {
                                  echo ' selected';
                                }  
                              }
                               
                            ?>
                          >
                            <?= $value->name;?>
                          </option>
                          
                        <?php 
                          }
                        ?>
                       
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_city_id')?></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" value="<?php echo $supplier->city_id;?>" name="city_id" id="city_id" width="100%">
                       
                        <?php
                          foreach ($cities as $value) {
                        ?>
                          <option value="<?=$value->id;?>"
                            <?php 
                              if(isset($city_id))
                              {
                                if($city_id == $value->id)
                                {
                                  echo ' selected';
                                }
                              }
                              else
                              {
                                if($supplier->city_id == $value->id)
                                {
                                  echo ' selected';
                                }  
                              }
                              
                            ?>
                          >
                            <?= $value->name;?>
                          </option>
                          
                        <?php 
                          }
                        ?>

                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_address')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="address" value="<?php echo set_value('address',$supplier->address);?>" class="form-control form-control-sm field_validation" id="address" placeholder="<?=$this->lang->line('supplier_address')?>"><?=form_error('address', '<div class="text-danger">', '</div>');?>
                      <span id="err_address" class="error invalid-feedback"><?=form_error('address');?></span>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('supplier_website')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="website" value="<?=set_value('website',$supplier->website) ?>" class="form-control form-control-sm" id="website" placeholder="<?=$this->lang->line('website')?>">
                      <span id="err_website" class="error invalid-feedback"><?=form_error('website');?></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('supplier_payment_duration')?>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" name="payment_duration" id="payment_duration" width="100%">
                        <option value="0">Select</option>
                      <?php  
                        for ($i = 1; $i <= 180; $i++) 
                        {
                      ?>
                          <option value="<?=$i;?>"

                            <?php
                              if($supplier->payment_duration == $i){
                                echo ' selected';
                              }
                            ?>

                            >
                            <?=$i .' days';?>
                          </option>
                      <?php
                        }
                      ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-2 col-form-label">
                            <?=$this->lang->line('whatsapp_no')?>
                          
                          </label>
                          <div class="col-sm-2">
                            
                            <select class="form-control form-control-sm select2bs4 field_validation" name="whatsapp_country_code" id="whatsapp_country_code" width="100%">
                              <?php
                                $company_settings = $this->company_settings_model->get_company_records();
                                // Check if customer exists to determine selected country code
                                $selected_country_code = isset($supplier) ? $supplier->whatsapp_country_code : $company_settings->country_id;

                                foreach ($countries as $value) {
                              ?>
                                <option value="<?=$value->phonecode;?>"
                                  <?php 
                                    // Compare phonecode if editing, else compare country_id
                                    if((isset($supplier) && $value->phonecode == $selected_country_code) || (!isset($supplier) && $value->id == $selected_country_code))
                                      echo ' selected';
                                  ?>
                                >
                                  <?= $value->name .' - '.$value->phonecode;?>
                                </option>
                              <?php 
                                }
                              ?>
                            </select>

                                

                          </div>
                          <div class="col-sm-2">
                            <input type="number" name="whatsapp_no" value="<?=set_value('whatsapp_no',$supplier->whatsapp_no) ?>" class="form-control" id="whatsapp_no" placeholder="<?=$this->lang->line('whatsapp_no')?>">
                          
                          </div>
                        </div>
                  
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$supplier->id?>">
                  <button type="submit" name="submit" id="supplierSubmit" class="btn btn-info">Update Supplier</button>
                  <a href="<?=base_url('supplier')?>" class="btn btn-default float-right"><?=$this->lang->line('supplier_cancel')?></a>
                </div>
              </div>
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

    <?php 
      if($supplier->gst_registration_type != 1)
      {
    ?>
        $('.gstin_row').css('display','none');
    <?php
      }
    ?>

    var gstReg = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

    $('form#editSupplierForm').submit(function(e){
       // e.preventDefault();

       var isError = false;
       $('#supplierSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editSupplierForm .field_validation').each(function() {
          
           var id    = $(this).attr('id');
           var value = $(this).val();
           var field = $(this).attr('placeholder');

           if(value==null || value==""){
           $("form#editSupplierForm #err_"+id).text(field+ " field is required.");
             $('form#editSupplierForm #'+id).addClass('is-invalid');
             isError = true;
           }
           else
           {
             $("form#editSupplierForm #err_"+id).text("");
             $('form#editSupplierForm #'+id).removeClass('is-invalid');
             $('form#editSupplierForm #'+id).addClass('is-valid');
           }
      });

      if (!gstReg.test($('form#editSupplierForm #gstin').val()) && $('form#editSupplierForm #gst_registration_type').val() == "1") 
      {
        if($('form#editSupplierForm #gstin').val() == '')
        {
          // $('form#editSupplierForm #gstin').val('');
          $("form#editSupplierForm #err_gstin").text('GSTIN field is required');
          $('form#editSupplierForm #gstin').addClass('is-invalid');
          isError = true;
        }
        else
        {
          // $('form#editSupplierForm #gstin').val('');
          $("form#editSupplierForm #err_gstin").text('Please enter valid GSTIN');
          $('form#editSupplierForm #gstin').addClass('is-invalid');
          isError = true;  
        }
      }
      else
      {
        $("form#editSupplierForm #err_gstin").text("");
        $('form#editSupplierForm #gstin').removeClass('is-invalid');
        $('form#editSupplierForm #gstin').addClass('is-valid');
      }


      if(isError == true)
      {
        $('#supplierSubmit').text('<?=$this->lang->line("supplier_edit")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editSupplierForm .field_validation").on("blur change keyup",  function (event){
         var id    = $(this).attr('id');
         var value = $(this).val();
         var field = $(this).attr('placeholder');
        
         if(value==null || value==""){
           $("form#editSupplierForm #err_"+id).text(field+ " field is required.");
           $('form#editSupplierForm #'+id).addClass('is-invalid');
           return false;
       }
         else{
           $("form#editSupplierForm #err_"+id).text("");
           $('form#editSupplierForm #'+id).removeClass('is-invalid');
           $('form#editSupplierForm #'+id).addClass('is-valid');
         }
     });

    $("form#editSupplierForm #gstin").on("blur keyup change",  function (event){



      if (!gstReg.test($('form#editSupplierForm #gstin').val()) && $('form#editSupplierForm #gst_registration_type').val() == "1") 
      {        
        if($('form#editSupplierForm #gstin').val() == "")
        {
          $("form#editSupplierForm #err_gstin").text("GSTIN field is required.");
          $('form#editSupplierForm #gstin').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#editSupplierForm #err_gstin").text("Please enter valid GSTIN.");
          $('form#editSupplierForm #gstin').addClass('is-invalid');
          return false;
        }
      }
      else
      {
        $("form#editSupplierForm #err_gstin").text("");
        $('form#editSupplierForm #gstin').removeClass('is-invalid');
        $('form#editSupplierForm #gstin').addClass('is-valid');
      }
    });

    $("form#editSupplierForm #gst_registration_type").on("blur keyup change",  function (event){

      if ($('form#editSupplierForm #gst_registration_type').val() == "1") 
      {   
        $('.gstin_row').fadeIn(10);

        if($('form#editSupplierForm #gstin').val() == "")
        {
          $("form#editSupplierForm #err_gstin").text("GSTIN field is required.");
          $('form#editSupplierForm #gstin').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#editSupplierForm #err_gstin").text("Please enter valid GSTIN.");
          $('form#editSupplierForm #gstin').addClass('is-invalid');
          return false;
        }
      }
      else
      {
        $('.gstin_row').fadeOut(10);

        $("form#editSupplierForm #err_gstin").text("");
        $('form#editSupplierForm #gstin').removeClass('is-invalid');
        $('form#editSupplierForm #gstin').addClass('is-valid');
      }
    });

    $('#country_id').change(function(){
      var id = $(this).val();
      $('#state_id').html('<option value="">Select</option>');
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          for(i=0;i<data.length;i++)
          {
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
         success: function(data)
         {
            for(i=0;i<data.length;i++)
            {
              $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
         }
      });
    });

  });
</script>


<script>

  $(document).ready(function(e){
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  });
</script>