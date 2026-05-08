<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('lead')?>"><?=$this->lang->line('header_lead')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('lead_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addCustomerForm" id="addCustomerForm" method="post" action="">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('lead_add')?></h3>
                  <div class="card-tools">
                    
                    <ul class="nav nav-pills ml-auto">

                      <li class="nav-item ml-2">
                        <a class="nav-link active text-white btn btn-secondary" href="<?=base_url('lead')?>" data-tt="tooltip" title="Click here to show lead list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>
                    </ul>
                  
                  </div>
                </div>
                <div class="card-body">
                  <table style="width: 100%;" class="table">
                    <tr>
                      <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Personal Details</th>
                      <th style="text-align: left;background-color: #F5F5F5;font-style: normal;"></th>
                      <th style="text-align: left;background-color: #F5F5F5;font-style: normal;"></th>
                    </tr>
                    <tr>
                      <td style="width: 50%;">

      <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('salesman_name')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('salesman_name')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == ' selected');
            ?>
                <option value="<?=$value->employee_id;?>"<?=$selected;?>>
                    <?= $value->first_name;?>
                </option>
            <?php 
                }
            ?>
          </select>
          <span id="err_employee_id" class="error invalid-feedback"></span> 
        </div>
      </div>
      
             <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('salesman_phone')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
        <input type="text" name="employee_contact_no"
       value="<?= set_value('salesman_phone') ?>"
       class="form-control form-control-sm"
       maxlength="10"
       id="salesman_phone"
       pattern="\d{10}"
       placeholder="<?= $this->lang->line('salesman_phone') ?>"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <span id="err_salesman_phone" class="error invalid-feedback"><?=form_error('salesman_phone');?></span>
                          </div>
                        </div>

             

                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_name')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="lead_name" maxlength="50" value="<?=set_value('lead_name') ?>" class="form-control form-control-sm field_validation" id="lead_name" placeholder="<?=$this->lang->line('customer_name')?>">
                            <span id="err_lead_name" class="error invalid-feedback"><?=form_error('lead_name');?></span>
                          </div>
                        </div>

                   

                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('lead_buyer_designation')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="text" maxlength="50" name="customer_buyer_designation" value="<?=set_value('customer_buyer_designation') ?>" class="form-control form-control-sm" id="customer_buyer_designation" placeholder="<?=$this->lang->line('lead_buyer_designation')?>">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_department')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="text" maxlength="50" name="customer_department" value="<?=set_value('customer_department') ?>" class="form-control form-control-sm" id="customer_department" placeholder="<?=$this->lang->line('customer_department')?>">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('date')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="date" name="date_added" value="<?=set_value('date_added') ?>" class="form-control form-control-sm" id="date_added" placeholder="<?=$this->lang->line('date')?>">
                            <span id="err_date_added" class="error invalid-feedback"><?=form_error('date_added');?></span>

                          </div>
                        </div>

                        
                  
                                            <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('lead_status')?>
                          
                          </label>
                          <div class="col-sm-8">
                            <select class="form-control form-control-sm select2bs4" name="status" id="status_id" width="100%">
                              <option value="">Select Status</option>
                              <?php
                                if(isset($leads_status))
                                {
                                  foreach ($leads_status as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->name;?>" <?php echo set_select('status_id', $value->name); ?>>
                                    <?= $value->name;?>
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                            </select>
                             <span id="err_status" class="error invalid-feedback"><?=form_error('status');?></span> 
                          </div>
                        </div>
                        
                                                                    <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('lead_source')?>
                          
                          </label>
                          <div class="col-sm-8">
                            <select class="form-control form-control-sm select2bs4" name="source" id="source_id" width="100%">
                              <option value="">Select Source</option>
                              <?php
                                if(isset($leads_sources))
                                {
                                  foreach ($leads_sources as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->name;?>" <?php echo set_select('source_id', $value->name); ?>>
                                    <?= $value->name;?>
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                            </select>
                             <span id="err_source" class="error invalid-feedback"><?=form_error('source');?></span> 
                          </div>
                        </div>
                        
                        
                        
            
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_phone')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
<input type="text"
       name="phone"
       value="<?= set_value('phone') ?>"
       class="form-control form-control-sm"
       id="phone"
       placeholder="<?= $this->lang->line('customer_phone') ?>"
       maxlength="10"
       pattern="\d{10}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
       title="Please enter a 10-digit phone number">
                            <span id="err_phone" class="error invalid-feedback"><?=form_error('phone');?></span>
                          </div>
                        </div>

             
                        


                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('whatsapp_no')?>
                          
                          </label>
                          <div class="col-sm-4">
                            
                            <select class="form-control form-control-sm select2bs4 field_validation" name="whatsapp_country_code" id="whatsapp_country_code" width="100%">
                              <?php
                                $company_settings = $this->company_settings_model->get_company_records();
                                $selected_country_id = $company_settings->country_id;
                                $selected_country = $this->utility_model->get_records_by_field('countries','id',$selected_country_id,$row = true,$check_delete_status = false);
                                
                                foreach ($countries as $value) {

                              ?>
                                <option value="<?=$value->phonecode;?>"
                                  <?php 
                                    if($selected_country->phonecode == $value->phonecode)
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
                          <div class="col-sm-4">
<input type="text"
       name="whatsapp_no"
       value="<?= set_value('whatsapp_no') ?>"
       class="form-control"
       id="whatsapp_no"
       placeholder="<?= $this->lang->line('whatsapp_no') ?>"
       maxlength="10"
       pattern="\d{10}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
       title="Please enter a valid 10-digit WhatsApp number">
                          
                          </div>
                        </div>

               <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('reffered_by')?>
                         
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="reffered_by" value="<?=set_value('reffered_by') ?>" class="form-control form-control-sm" id="reffered_by" placeholder="<?=$this->lang->line('reffered_by')?>">
                            <span id="err_reffered_by" class="error invalid-feedback"><?=form_error('reffered_by');?></span>
                          </div>
                        </div> 
                        
                                      <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('reffered_by_contact_no')?>
                         
                          </label>
                          <div class="col-sm-8">
<input type="text"
       name="reffered_by_contact_no"
       value="<?= set_value('reffered_by_contact_no') ?>"
       pattern="\d{10}"
       maxlength="10"
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
       class="form-control form-control-sm"
       id="reffered_by_contact_no"
       placeholder="<?= $this->lang->line('reffered_by_contact_no') ?>">
                            <span id="err_reffered_by_contact_no" class="error invalid-feedback"><?=form_error('reffered_by_contact_no');?></span>
                          </div>
                        </div>  
                        

                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_address')?>
                            
                          </label>
                          <div class="col-sm-8">
                            <input type="text" maxlength="50" name="address" value="<?=set_value('address') ?>" class="form-control form-control-sm" id="address" placeholder="<?=$this->lang->line('customer_address')?>">
                            <span id="err_address" class="error invalid-feedback"><?=form_error('address', '<div class="text-danger">', '</div>');?></span>
                          </div>
                        </div>
             
                  <div class="form-group row">
  <label for="inputRequirement" class="col-sm-4 col-form-label">
    <?=$this->lang->line('requirement')?>
  </label>
  <div class="col-sm-8">
    <textarea name="requirement" class="form-control form-control-sm" maxlength="100" id="requirement" placeholder="requirement"><?=set_value('requirement') ?></textarea>
    <span id="err_requirement" class="error invalid-feedback"><?=form_error('requirement');?></span>
  </div>
</div>  
                <div class="form-group row">
  <label for="inputDescription" class="col-sm-4 col-form-label">
                                  <!--<?=$this->lang->line('Description')?>-->
Visit Of Purchase
    
  </label>
  <div class="col-sm-8">
    <textarea name="description" class="form-control form-control-sm" id="description" maxlength="100" placeholder="Visit Of Purchase"><?=set_value('description') ?></textarea>
    <span id="err_description" class="error invalid-feedback">Visit Of Purchase</span>
  </div>
</div>


                <div class="form-group row">
  <label for="inputDescription" class="col-sm-4 col-form-label">
Remark
  </label>
  <div class="col-sm-8">
    <textarea name="remark" class="form-control form-control-sm" id="remark" maxlength="100" placeholder="Remark"><?=set_value('remark') ?></textarea>
    <span id="err_remark" class="error invalid-feedback">Remark</span>
  </div>
</div>
                      </td>
                      <td style="width: 30%; display:none;">
                        
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_country_id')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            
                            <select class="form-control form-control-sm select2bs4 field_validation" name="country_id" id="country_id" width="100%">
                              <?php
                                $company_settings = $this->company_settings_model->get_company_records();
                                $selected_country_id = $company_settings->country_id;

                                foreach ($countries as $value) {
                              ?>
                                <option value="<?=$value->id;?>" <?php echo set_select('country_id', $value->id, $selected_country_id == $value->id); ?>>
                                  <?= $value->name;?>
                                </option>
                              <?php 
                                }
                              ?>

                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_state_id')?>
                            <span class="text-danger">*</span>  
                          </label>
                          <div class="col-sm-8">
                            <select class="form-control form-control-sm select2bs4 field_validation"  name="state_id" id="state_id" width="100%">
                              <option value="">Select State</option>
                              <?php
                                if(isset($states))
                                {
                                  $company_settings = $this->company_settings_model->get_company_records();
                                  $selected_state_id = $company_settings->state_id;
                                  foreach ($states as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->id;?>" <?php echo set_select('state_id', $value->id,$selected_state_id == $value->id); ?>>
                                    <?= $value->name;?>
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                              
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_city_id')?>
                          
                          </label>
                          <div class="col-sm-8">
                            <select class="form-control form-control-sm select2bs4" name="city_id" id="city_id" width="100%">
                              <option value="">Select City</option>
                              <?php
                                if(isset($cities))
                                {
                                  foreach ($cities as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->id;?>" <?php echo set_select('city_id', $value->id); ?>>
                                    <?= $value->name;?>
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                            </select>
                            <!-- <span id="err_city_id" class="error invalid-feedback"><?=form_error('city_id');?></span> -->
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('lead_address')?>
                            
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="address" value="<?=set_value('address') ?>" class="form-control form-control-sm" id="address" placeholder="<?=$this->lang->line('lead_address')?>">
                            <span id="err_address" class="error invalid-feedback"><?=form_error('address', '<div class="text-danger">', '</div>');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_pincode')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="pincode" value="<?=set_value('pincode') ?>" class="form-control form-control-sm" id="pincode" placeholder="<?=$this->lang->line('customer_pincode')?>">
                            <span id="err_pincode" class="error invalid-feedback"><?=form_error('pincode');?></span>
                          </div>
                        </div>
                      </td>
                      <td style="width: 30%;">
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" name="submit" id="customerSubmit" class="btn btn-info"><?=$this->lang->line('lead_save')?></button>
                  <a href="<?=base_url('lead')?>" class="btn btn-default float-right"><?=$this->lang->line('lead_cancel')?></a>
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


    $('form#addCustomerForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#customerSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#addCustomerForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addCustomerForm #err_"+id).text(field+ " field is required.");
            $('form#addCustomerForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addCustomerForm #err_"+id).text("");
            $('form#addCustomerForm #'+id).removeClass('is-invalid');
            $('form#addCustomerForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#customerSubmit').text('<?=$this->lang->line("lead_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#addCustomerForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addCustomerForm #err_"+id).text(field+ " field is required.");
          $('form#addCustomerForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addCustomerForm #err_"+id).text("");
          $('form#addCustomerForm #'+id).removeClass('is-invalid');
          $('form#addCustomerForm #'+id).addClass('is-valid');
        }
    });


    $('#country_id').change(function(){
      var id = $(this).val();
      var company_state_id    = '<?=$this->company_settings_model->get_company_records()->state_id?>';
       // alert(id);
      $('#state_id').html('<option value="">Select</option>');
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        async: false,
        type: "GET",
        dataType: "JSON",
        
        success: function(data){
         for(i=0;i<data.length;i++){
           $('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
          $('#state_id').val(company_state_id).trigger('change');
        }
      });
    });

    $('#state_id').change(function(){
      var id = $(this).val();
      
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
         url: "<?php echo base_url('utility/get_cities') ?>/"+id,
         async: false,
         type: "GET",
         dataType: "JSON",
         
         success: function(data){
           for(i=0;i<data.length;i++){
             $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
           }
         }
      });
    });
    


    $('#staus_id').change(function(){
      var id = $(this).val();
      
      $.ajax({
         url: "<?php echo base_url('utility/get_lead_status') ?>/",
         async: false,
         type: "GET",
         dataType: "JSON",
         
         success: function(data){
           for(i=0;i<data.length;i++){
             $('#staus_id').append('<option value="' + data[i].name + '">' + data[i].name + '</option>');
           }
         }
      });
    });
    
    
        $('#source_id').change(function(){
      var id = $(this).val();
      
      $.ajax({
         url: "<?php echo base_url('utility/get_lead_sources') ?>/",
         async: false,
         type: "GET",
         dataType: "JSON",
         
         success: function(data){
           for(i=0;i<data.length;i++){
             $('#source_id').append('<option value="' + data[i].name + '">' + data[i].name + '</option>');
           }
         }
      });
    });

  });
</script>

