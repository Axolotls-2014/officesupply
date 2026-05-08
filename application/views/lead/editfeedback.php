<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('lead/feedback_list')?>"><?=$this->lang->line('header_feedback')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('edit_feedback')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editCustomerForm" id="editCustomerForm" method="post" 
            action="<?php echo base_url('lead/editfeedback');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('edit_feedback')?></h3>
                  <div class="card-tools">
                    
                    <ul class="nav nav-pills ml-auto">

                      <li class="nav-item ml-2">
                        <a class="nav-link active text-white btn btn-secondary" href="<?=base_url('lead/feedback_list')?>" data-tt="tooltip" title="Click here to show lead list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
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
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('name')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="name" value="<?=set_value('customer_name',$customer->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('name')?>">
                            <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
                          </div>
                        </div>
                        
                                      <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('address')?>
                            
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="address" value="<?php echo set_value('address',$customer->address);?>" class="form-control form-control-sm" id="address" placeholder="<?=$this->lang->line('address')?>">
                            <span id="err_address" class="error invalid-feedback"><?=form_error('address', '<div class="text-danger">', '</div>');?></span>
                          </div>
              </div>


       <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('phone_no')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="number" name="phone_no" value="<?=set_value('phone_no',$customer->phone_no) ?>" 
                            class="form-control form-control-sm field_validation" id="phone_no" 
                            placeholder="<?=$this->lang->line('phone_no')?>">
                            <span id="err_phone_no" class="error invalid-feedback"><?=form_error('phone_no');?></span>
                          </div>
                        </div>

                              <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('visit_date')?>
                           
                          </label>
                          <div class="col-sm-8">
                            <input type="date" name="visit_date" value="<?=set_value('visit_date',$customer->visit_date) ?>"
                            class="form-control form-control-sm" id="visit_date" 
                            placeholder="<?=$this->lang->line('visit_date')?>">
                          </div>
                        </div>
         
    
                            <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('reference')?>
                         
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="reference" value="<?php echo $customer->reference;?>" 
                            class="form-control form-control-sm" id="reference"
                            placeholder="<?=$this->lang->line('reference')?>">
                            <span id="err_reference" class="error invalid-feedback"><?=form_error('reference');?></span>
                          </div>
                        </div>                
            
                 
         <div class="form-group row">
  <label for="inputreference" class="col-sm-4 col-form-label">
        <?=$this->lang->line('request')?>
    
  </label>
  <div class="col-sm-8">
    <textarea name="request" class="form-control form-control-sm" id="request" placeholder="request">
        <?= set_value('request', $customer->request); ?></textarea>
    <span id="err_request" class="error invalid-feedback"><?= form_error('request'); ?></span>
  </div>
</div>

 <div class="form-group row">
  <label for="inputnote" class="col-sm-4 col-form-label">
        <?=$this->lang->line('note')?>
    
  </label>
  <div class="col-sm-8">
    <textarea name="note" class="form-control form-control-sm" id="note" placeholder="Note">
        <?= set_value('note', $customer->note); ?></textarea>
    <span id="err_note" class="error invalid-feedback"><?= form_error('note'); ?></span>
  </div>
</div>  




<div class="form-group row">
    <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('salesman_name')?></label>
    <div class="col-md-8">
        <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" 
        placeholder="<?=$this->lang->line('salesman')?>" id="salesman" name="salesman[]" multiple="multiple">
            <option value="">Select</option>
            <?php
                // Retrieve the current selected salesman IDs (as an array)
                $selected_salesmen = $customer ? explode(',', $customer->salesman) : [];
                foreach ($employees as $value) {
                    // Check if the current salesman is selected
                    $selected = in_array($value->employee_id, $selected_salesmen) ? ' selected' : '';
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
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('receptionist')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" 
          placeholder="<?=$this->lang->line('receptionist')?>" id="receptionist" name="receptionist">
            <option value="">Select</option>
            <?php
                $attendance_employee_id = $customer ? $customer->receptionist : '';
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
          <span id="err_salesman" class="error invalid-feedback"></span> 
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
                  <button type="submit" name="submit" id="customerSubmit" class="btn btn-info"><?=$this->lang->line('feedback_save')?></button>
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