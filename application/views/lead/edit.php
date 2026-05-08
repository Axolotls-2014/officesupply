<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('lead/list')?>"><?=$this->lang->line('header_lead')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('lead_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editCustomerForm" id="editCustomerForm" method="post" action="<?php echo base_url('lead/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('lead_edit')?></h3>
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
                $attendance_employee_id = $customer ? $customer->salesman : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $attendance_employee_id) ? ' selected' : '';
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
                            <input type="number" name="employee_contact_no" value="<?=set_value('salesman_phone',$customer->employee_contact_no) ?>" class="form-control form-control-sm field_validation" id="employee_contact_no" placeholder="<?=$this->lang->line('salesman_phone')?>">
                            <span id="err_employee_contact_no" class="error invalid-feedback"><?=form_error('salesman_phone');?></span>
                          </div>
                        </div>

         
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_name')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="lead_name" value="<?=set_value('customer_name',$customer->lead_name) ?>" class="form-control form-control-sm field_validation" id="lead_name" placeholder="<?=$this->lang->line('customer_name')?>">
                            <span id="err_lead_name" class="error invalid-feedback"><?=form_error('lead_name');?></span>
                          </div>
                        </div>

                 
                 
                              <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('date')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="date" name="date" value="<?=set_value('date_added',$customer->date_added) ?>" class="form-control form-control-sm" id="date_added" placeholder="<?=$this->lang->line('date')?>">
                          </div>
                        </div>
                 
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('lead_buyer_designation')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="customer_buyer_designation" value="<?=set_value('customer_name',$customer->customer_buyer_designation) ?>" class="form-control form-control-sm" id="customer_buyer_designation" placeholder="<?=$this->lang->line('lead_buyer_designation')?>">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_department')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="customer_department" value="<?=set_value('customer_name',$customer->customer_department) ?>" class="form-control form-control-sm" id="customer_department" placeholder="<?=$this->lang->line('customer_department')?>">
                          </div>
                        </div>
                        
               
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_phone')?>
                         
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="phone" value="<?php echo $customer->phone;?>" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('customer_phone')?>">
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
                                // Check if customer exists to determine selected country code
                                $selected_country_code = isset($customer) ? $customer->whatsapp_country_code : $company_settings->country_id;

                                foreach ($countries as $value) {
                              ?>
                                <option value="<?=$value->phonecode;?>"
                                  <?php 
                                    // Compare phonecode if editing, else compare country_id
                                    if((isset($customer) && $value->phonecode == $selected_country_code) || (!isset($customer) && $value->id == $selected_country_code))
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
                            <input type="number" name="whatsapp_no" value="<?=set_value('whatsapp_no',$customer->whatsapp_no) ?>" class="form-control" id="whatsapp_no" placeholder="<?=$this->lang->line('whatsapp_no')?>">
                          
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
    if(isset($leads_sources)) {
      foreach ($leads_sources as $value) {
  ?>
    <!-- Corrected set_select() usage -->
    <option value="<?=$value->name;?>" <?php echo set_select('source', $value->name); ?>>
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
        
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('reffered_by')?>
                         
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="reffered_by" value="<?php echo $customer->reffered_by;?>" class="form-control form-control-sm" id="reffered_by" placeholder="<?=$this->lang->line('reffered_by')?>">
                            <span id="err_reffered_by" class="error invalid-feedback"><?=form_error('reffered_by');?></span>
                          </div>
                        </div>                
            
       
                               <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('reffered_by_contact_no')?>
                         
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="reffered_by_contact_no" value="<?php echo $customer->reffered_by_contact_no;?>" class="form-control form-control-sm" id="reffered_by_contact_no" placeholder="<?=$this->lang->line('reffered_by_contact_no')?>">
                            <span id="err_reffered_by_contact_no" class="error invalid-feedback"><?=form_error('reffered_by_contact_no');?></span>
                          </div>
                        </div>                      

              <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('lead_address')?>
                            
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="address" value="<?php echo set_value('address',$customer->address);?>" class="form-control form-control-sm" id="address" placeholder="<?=$this->lang->line('lead_address')?>">
                            <span id="err_address" class="error invalid-feedback"><?=form_error('address', '<div class="text-danger">', '</div>');?></span>
                          </div>
              </div>
                        
<div class="form-group row">
  <label for="inputDescription" class="col-sm-4 col-form-label">
        <?=$this->lang->line('requirement')?>
    
  </label>
  <div class="col-sm-8">
    <textarea name="requirement" class="form-control form-control-sm" id="requirement" placeholder="requirement"><?= set_value('requirement', $customer->requirement); ?></textarea>
    <span id="err_requirement" class="error invalid-feedback"><?= form_error('requirement'); ?></span>
  </div>
</div>

 <div class="form-group row">
  <label for="inputDescription" class="col-sm-4 col-form-label">
        <?=$this->lang->line('Description')?>
    
  </label>
  <div class="col-sm-8">
    <textarea name="description" class="form-control form-control-sm" id="description" placeholder="Description"><?= set_value('description', $customer->description); ?></textarea>
    <span id="err_description" class="error invalid-feedback"><?= form_error('description'); ?></span>
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
                                foreach ($countries as $value) {
                              ?>
                                <option value="<?=$value->id;?>"
                                  <?php 
                                    if($value->id == $customer->country_id)
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
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('customer_state_id')?>
                            <span class="text-danger">*</span>  
                          </label>
                          <div class="col-sm-8">
                            <select class="form-control form-control-sm select2bs4 field_validation"  name="state_id" id="state_id" width="100%">
                              <?php
                                if(isset($states))
                                {
                                  foreach ($states as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->id;?>"
                                    <?php
                                      if(isset($state_id))
                                      {
                                        if($state_id == $value->id)
                                          echo ' selected';
                                      } 
                                      else
                                      {
                                        if($value->id == $customer->state_id)
                                          echo ' selected';
                                      }

                                    ?>
                                  >
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
                              <?php
                                if(isset($cities))
                                {
                                  foreach ($cities as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->id;?>"
                                    <?php 
                                      if(isset($city_id))
                                      {
                                        if($city_id == $value->id)
                                          echo ' selected';
                                      }
                                      else
                                      {
                                        if($value->id == $customer->city_id)
                                          echo ' selected';
                                      }
                                    ?>
                                  >
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
                            <?=$this->lang->line('customer_pincode')?>
                          
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="pincode" value="<?php echo set_value('pincode',$customer->pincode);?>" class="form-control form-control-sm" id="pincode" placeholder="<?=$this->lang->line('customer_pincode')?>">
                            <span id="err_pincode" class="error invalid-feedback"><?=form_error('pincode', '<div class="text-danger">', '</div>');?></span>
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
                  <input type="hidden" name="id" value="<?=$customer->id?>">
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

 


    $("form#editCustomerForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
          if(value==null || value==""){
            $("form#editCustomerForm #err_"+id).text(field+ " field is required.");
            $('form#editCustomerForm #'+id).addClass('is-invalid');
            return false;
          }
          else
          {
            $("form#editCustomerForm #err_"+id).text("");
            $('form#editCustomerForm #'+id).removeClass('is-invalid');
            $('form#editCustomerForm #'+id).addClass('is-valid');
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

     $('#shipping_country_id').change(function(){
      var id = $(this).val();
       // alert(id);
      $('#shipping_state_id').html('<option value="">Select</option>');
      $('#shipping_city_id').html('<option value="">Select</option>');
      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
         for(i=0;i<data.length;i++){
           $('#shipping_state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
        }
      });
    });

    $('#shipping_state_id').change(function(){
      var id = $(this).val();
      
      $('#shipping_city_id').html('<option value="">Select</option>');
      $.ajax({
         url: "<?php echo base_url('utility/get_cities') ?>/"+id,
         type: "GET",
         dataType: "JSON",
         success: function(data){
           for(i=0;i<data.length;i++){
             $('#shipping_city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
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
             $('#staus_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
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
             $('#source_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
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