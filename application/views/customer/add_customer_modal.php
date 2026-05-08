<?php 
  $tabindex = 101;
?>  
<style>
.shipping-disabled {
    pointer-events: none;   /* block clicks */
    opacity: 0.6;           /* faded look */
}

.shipping-disabled input,
.shipping-disabled select {
    background-color: #e9ecef !important;
    cursor: not-allowed;
}
</style>
<div class="example-modal">
  <div class="modal fade" id="add_customer_modal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" method="post" name="addCustomerForm" id="addCustomerForm" action="<?=base_url('customer/ajax_add')?>">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('customer_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
           
            <table style="width: 100%;" class="table">
              <tr>
                <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Personal Details</th>
                <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Billing Details</th>
                <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Shipping Details ( <input type="checkbox" id="copy_address" name="copy_address"> same as billing details)</th>
              </tr>
              <tr>
                <td style="width: 30%;">
                  <div class="form-group row">
                    <label for="customer_company_name" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_company_name')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_company_name" value="<?=set_value('customer_company_name') ?>" class="form-control form-control-sm field_validation" id="customer_company_name" placeholder="<?=$this->lang->line('customer_company_name')?>">
                      <span id="err_customer_company_name" class="error invalid-feedback"></span>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="customer_name" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_name')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_name" value="<?=set_value('customer_name') ?>" class="form-control form-control-sm field_validation" id="customer_name" placeholder="<?=$this->lang->line('customer_name')?>">
                      <span id="err_customer_name" class="error invalid-feedback"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="customer_department" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_department')?>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_department" value="<?=set_value('customer_department') ?>" class="form-control form-control-sm" id="customer_department" placeholder="<?=$this->lang->line('customer_department')?>">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_email')?>
                    </label>
                    <div class="col-sm-8">
                      <input type="email" name="email" value="<?=set_value('email') ?>" class="form-control form-control-sm" id="email" placeholder="<?=$this->lang->line('customer_email')?>">
                      <span id="err_email" class="error invalid-feedback"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="phone" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_phone')?>

                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="phone" value="<?=set_value('phone') ?>" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('customer_phone')?>">
                     
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="payment_duration" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_payment_duration')?>
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4" name="payment_duration" id="payment_duration" width="100%">
                        <option value="0">Select</option>
                        <?php for ($i = 1; $i <= 180; $i++) { ?>
                          <option value="<?=$i;?>"><?=$i .' days';?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="whatsapp_no" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('whatsapp_no')?>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" name="whatsapp_country_code" id="whatsapp_country_code" width="100%">
                        <?php
                          $countries = $this->utility_model->get_countries();
                          $company_settings = $this->company_settings_model->get_company_records();
                          $selected_country_id = $company_settings->country_id;
                          foreach ($countries as $value) {
                        ?>
                          <option value="<?=$value->phonecode;?>" <?php echo set_select('whatsapp_country_code', $value->phonecode, $selected_country_id == $value->id); ?>>
                            <?= $value->name .' - '.$value->phonecode;?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <input type="number" name="whatsapp_no" value="<?=set_value('whatsapp_no') ?>" class="form-control" id="whatsapp_no" placeholder="<?=$this->lang->line('whatsapp_no')?>">
                    </div>
                  </div>
                  
                 </td>
                <td style="width: 30%;">
                  <div class="form-group row">
                    <label for="country_id" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_country_id')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="country_id" id="country_id" width="100%">
                        <?php foreach ($countries as $value) { ?>
                          <option value="<?=$value->id;?>"><?= $value->name;?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="state_id" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_state_id')?>
                      <span class="text-danger">*</span>  
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="state_id" id="state_id" width="100%">
                        <option value="">Select State</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="city_id" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_city_id')?>
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4" name="city_id" id="city_id" width="100%">
                        <option value="">Select City</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="address" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_address')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="address" value="<?=set_value('address') ?>" class="form-control form-control-sm field_validation" id="address" placeholder="<?=$this->lang->line('customer_address')?>">
                      <span id="err_address" class="error invalid-feedback"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="pincode" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_pincode')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="pincode" value="<?=set_value('pincode') ?>" class="form-control form-control-sm field_validation" id="pincode" placeholder="<?=$this->lang->line('customer_pincode')?>">
                      <span id="err_pincode" class="error invalid-feedback"></span>
                    </div>
                  </div>
                 </td>
                
                <td style="width: 30%;">
                  <!-- Shipping Address Array Format - Index 0 -->
                  <input type="hidden" name="shipping_address[0][shipping_name]" value="Default Address">
                  
                  <div class="form-group row">
                    <label for="shipping_country_id" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_country_id')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="shipping_address[0][shipping_country_id]" id="shipping_country_id" width="100%">
                        <option value="">Select Country</option>
                        <?php foreach ($countries as $value) { ?>
                          <option value="<?=$value->id;?>"><?= $value->name;?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="shipping_state_id" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_state_id')?>
                      <span class="text-danger">*</span>  
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="shipping_address[0][shipping_state_id]" id="shipping_state_id" width="100%">
                        <option value="">Select State</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="shipping_city_id" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_city_id')?>
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4" name="shipping_address[0][shipping_city_id]" id="shipping_city_id" width="100%">
                        <option value="">Select City</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="shipping_address" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_address')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="shipping_address[0][shipping_address]" value="<?=set_value('shipping_address') ?>" class="form-control form-control-sm field_validation" id="shipping_address" placeholder="<?=$this->lang->line('customer_shipping_address')?>">
                      <span id="err_shipping_address" class="error invalid-feedback"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="shipping_pincode" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_pincode')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="shipping_address[0][shipping_pincode]" value="<?=set_value('shipping_pincode') ?>" class="form-control form-control-sm field_validation" id="shipping_pincode" placeholder="<?=$this->lang->line('customer_shipping_pincode')?>">
                      <span id="err_shipping_pincode" class="error invalid-feedback"></span>
                    </div>
                  </div>

                  <!-- Hidden field for is_default (set to 1 for default address) -->
                  <input type="hidden" name="shipping_address[0][is_default]" value="1">
                 </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" name="submit" id="customerSubmit" tabindex="<?=$tabindex?>" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Country phonecode mapping
    var countryPhonecodeMap = {
        <?php foreach ($countries as $country) {
            echo "'" . $country->id . "': '" . $country->phonecode . "',";
        } ?>
    };
    
    // Load states when country changes
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        if(countryId) {
            $.ajax({
                url: '<?=base_url("utility/get_states")?>',
                type: 'POST',
                data: {country_id: countryId},
                dataType: 'json',
                success: function(response) {
                    $('#state_id').empty().append('<option value="">Select State</option>');
                    $('#city_id').empty().append('<option value="">Select City</option>');
                    $.each(response, function(key, value) {
                        $('#state_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                    $('#state_id').trigger('change');
                }
            });
            
            // Update WhatsApp code
            if(countryPhonecodeMap[countryId]) {
                $('#whatsapp_country_code').val(countryPhonecodeMap[countryId]).trigger('change');
            }
        }
    });
    
    // Load cities when state changes
    $('#state_id').on('change', function() {
        var stateId = $(this).val();
        if(stateId) {
            $.ajax({
                url: '<?=base_url("utility/get_cities")?>',
                type: 'POST',
                data: {state_id: stateId},
                dataType: 'json',
                success: function(response) {
                    $('#city_id').empty().append('<option value="">Select City</option>');
                    $.each(response, function(key, value) {
                        $('#city_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        }
    });
    
    // Load shipping states when shipping country changes
    $('#shipping_country_id').on('change', function() {
        var countryId = $(this).val();
        if(countryId) {
            $.ajax({
                url: '<?=base_url("utility/get_states")?>',
                type: 'POST',
                data: {country_id: countryId},
                dataType: 'json',
                success: function(response) {
                    $('#shipping_state_id').empty().append('<option value="">Select State</option>');
                    $('#shipping_city_id').empty().append('<option value="">Select City</option>');
                    $.each(response, function(key, value) {
                        $('#shipping_state_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        }
    });
    
    // Load shipping cities when shipping state changes
    $('#shipping_state_id').on('change', function() {
        var stateId = $(this).val();
        if(stateId) {
            $.ajax({
                url: '<?=base_url("utility/get_cities")?>',
                type: 'POST',
                data: {state_id: stateId},
                dataType: 'json',
                success: function(response) {
                    $('#shipping_city_id').empty().append('<option value="">Select City</option>');
                    $.each(response, function(key, value) {
                        $('#shipping_city_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        }
    });
    
    // Copy billing to shipping function
   /* function copyBillingToShipping() {
        var billingCountryId = $('#country_id').val();
        var billingStateId = $('#state_id').val();
        var billingCityId = $('#city_id').val();
        var billingAddress = $('#address').val();
        var billingPincode = $('#pincode').val();
        
        $('#shipping_country_id').val(billingCountryId).trigger('change');
        
        setTimeout(function() {
            $('#shipping_state_id').val(billingStateId).trigger('change');
        }, 500);
        
        setTimeout(function() {
            $('#shipping_city_id').val(billingCityId).trigger('change');
        }, 1000);
        
        $('#shipping_address').val(billingAddress);
        $('#shipping_pincode').val(billingPincode);
    }
    */
   // Modified copy function
function copyBillingToShipping() {
    var billingCountryId = $('#country_id').val();
    var billingStateId = $('#state_id').val();
    var billingCityId = $('#city_id').val();
    var billingAddress = $('#address').val();
    var billingPincode = $('#pincode').val();
    
    // Set shipping fields
    $('#shipping_country_id').val(billingCountryId);
    $('#shipping_address').val(billingAddress);
    $('#shipping_pincode').val(billingPincode);
    
    // Manually trigger the country change and handle state/city
    if(billingCountryId) {
        $.ajax({
            url: '<?=base_url("utility/get_states")?>',
            type: 'POST',
            data: {country_id: billingCountryId},
            dataType: 'json',
            async: false, // Make synchronous to ensure order
            success: function(response) {
                $('#shipping_state_id').empty().append('<option value="">Select State</option>');
                $('#shipping_city_id').empty().append('<option value="">Select City</option>');
                $.each(response, function(key, value) {
                    $('#shipping_state_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                });
                
                // Set state after loading
                if(billingStateId) {
                    $('#shipping_state_id').val(billingStateId);
                    
                    // Load cities for the selected state
                    $.ajax({
                        url: '<?=base_url("utility/get_cities")?>',
                        type: 'POST',
                        data: {state_id: billingStateId},
                        dataType: 'json',
                        async: false,
                        success: function(citiesResponse) {
                            $('#shipping_city_id').empty().append('<option value="">Select City</option>');
                            $.each(citiesResponse, function(key, value) {
                                $('#shipping_city_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                            
                            if(billingCityId) {
                                $('#shipping_city_id').val(billingCityId);
                            }
                        }
                    });
                }
            }
        });
    }
}
    // When checkbox is checked/unchecked
    $('#copy_address').change(function() {
        if($(this).is(':checked')) {
            copyBillingToShipping();
        }
    });
    
    // Auto-copy when billing fields change
    $('#country_id, #state_id, #city_id, #address, #pincode').on('change keyup', function() {
        if($('#copy_address').is(':checked')) {
            copyBillingToShipping();
        }
    });
    
    // AJAX Form Submission
    /*$('#addCustomerForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.error').html('');
        $('.field_validation').removeClass('is-invalid');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#customerSubmit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
            },
            success: function(response) {
                if(response.code == 1) {
                    // Success
                    $('#add_customer_modal').modal('hide');
                    toastr.success(response.message);
                    
                    // Reload datatable if exists
                    if($.fn.DataTable.isDataTable('#customer_table')) {
                        $('#customer_table').DataTable().ajax.reload();
                    }
                    
                    // Reset form
                    $('#addCustomerForm')[0].reset();
                } else {
                    // Display validation errors
                    if(response.errors) {
                        $.each(response.errors, function(key, value) {
                            $('#err_' + key).html(value).show();
                            $('#' + key).addClass('is-invalid');
                        });
                    } else {
                        toastr.error(response.message || 'Failed to add customer');
                    }
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred. Please try again.');
                console.log(xhr.responseText);
            },
            complete: function() {
                $('#customerSubmit').prop('disabled', false).html('Submit');
            }
        });
    });*/
});
</script>