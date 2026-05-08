<!-- Add this CSS after your existing style block -->
<style>
  /* Chrome, Safari, Edge */
  input[type=number]::-webkit-inner-spin-button, 
  input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }
  
  /* Country code display styling */
  .country-code-prefix {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: transparent;
    pointer-events: none;
  }
  
  .phone-input-wrapper {
    position: relative;
  }
  
  .phone-input-wrapper input {
    padding-left: 50px !important;
  }
</style>

<div class="example-modal">
  <div class="modal fade" id="add_supplier_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
       <!-- <form role="form" method="post" name="addSupplierForm" id="addSupplierForm">-->
<form role="form" method="post" name="addSupplierForm" id="addSupplierForm" onsubmit="return false;">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('supplier_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_company_name')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="company_name" value="<?=set_value('company_name') ?>" class="form-control form-control-sm field_validation" id="company_name" placeholder="<?=$this->lang->line('supplier_company_name')?>"><?=form_error('company_name', '<div class="text-danger">', '</div>');?>
                <span id="err_company_name" class="error invalid-feedback"><?=form_error('company_name');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_gst_registration_type')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4 field_validation" name="gst_registration_type" id="gst_registration_type" width="100%" placeholder="<?=$this->lang->line('supplier_gst_registration_type')?>">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <option value="1"><?=$this->lang->line('gst_reg_type_reg')?></option>
                  <option value="0"><?=$this->lang->line('gst_reg_type_not_reg')?></option>
                  <option value="2"><?=$this->lang->line('gst_reg_type_composite')?></option>
                </select>
                <span id="err_gst_registration_type" class="error invalid-feedback"><?=form_error('gst_registration_type');?></span>
              </div>
            </div>
            <div class="form-group row gstin_row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_gstin')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="text" name="gstin" value="<?=set_value('gstin') ?>" class="form-control form-control-sm" id="gstin" placeholder="<?=$this->lang->line('supplier_gstin')?>">
                <span id="err_gstin" class="error invalid-feedback"><?=form_error('gstin', '<div class="text-danger">', '</div>');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_contact_person_name')?>
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-sm-6">
                <input type="text" name="contact_person_name" value="<?=set_value('contact_person_name') ?>" class="form-control form-control-sm" id="contact_person_name" placeholder="<?=$this->lang->line('supplier_contact_person_name')?>">
                <!-- <span id="err_contact_person_name" class="error invalid-feedback"><?=form_error('contact_person_name');?></span> -->
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_contact_person_designation')?>
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-sm-6">
                <input type="text" name="contact_person_designation" value="<?=set_value('contact_person_designation') ?>" class="form-control form-control-sm" id="contact_person_designation" placeholder="<?=$this->lang->line('supplier_contact_person_designation')?>">
                <!-- <span id="err_contact_person_designtion" class="error invalid-feedback"><?=form_error('contact_person_designation');?></span> -->
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_email')?>
              </label>
              <div class="col-sm-6">
                <input type="text" name="email" value="<?=set_value('email') ?>" class="form-control form-control-sm" id="email" placeholder="<?=$this->lang->line('supplier_email')?>">
                <span id="err_email" class="error invalid-feedback"><?=form_error('email');?></span>
              </div>
            </div>
            
             <!--bank details-->
                   <div class="form-group row">
                    <label for="inputEmail3"  class="col-sm-4 col-form-label">
                   Bank Name
                    </label>
                    <div class="col-sm-6">
                      <input type="text" name="bank_name" value="<?=set_value('bank_name') ?>" class="form-control form-control-sm" id="bank_name" placeholder="Bank Name">
                      <span id="err_bank" class="error invalid-feedback"><?=form_error('bank_name');?></span>
                    </div>
                  </div>
                  
                  
                   <div class="form-group row">
                    <label for="inputEmail3"  class="col-sm-4 col-form-label">
                      IFSC Number
                    </label>
                    <div class="col-sm-6">
                      <input type="text" name="ifsc" value="<?=set_value('ifsc') ?>" class="form-control form-control-sm" id="ifsc" placeholder="IFSC">
                      <span id="err_ifsc" class="error invalid-feedback"><?=form_error('ifsc');?></span>
                    </div>
                  </div>
                  
                   <div class="form-group row">
                    <label for="inputEmail3"  class="col-sm-4 col-form-label">
                    Account Number
                    </label>
                    <div class="col-sm-6">
                      <input type="text" name="account_number" value="<?=set_value('account_number') ?>" class="form-control form-control-sm" id="account_number" placeholder="account_number">
                      <span id="err_account" class="error invalid-feedback"><?=form_error('account_number');?></span>
                    </div>
                  </div>
                  
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_phone')?>
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-sm-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text phone-country-code-display" id="phone_country_code_display">+91</span>
                  </div>
                  <input type="number" name="phone" value="<?=set_value('phone') ?>" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('supplier_phone')?>">
                </div>
                <input type="hidden" name="phone_country_code" id="phone_country_code" value="91">
                <!-- <span id="err_phone" class="error invalid-feedback"><?=form_error('phone');?></span> -->
              </div>
            </div>
           
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_country_id')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4 field_validation" name="country_id" id="country_id" placeholder="<?=$this->lang->line('supplier_country_id')?>" width="100%">
                  <option value=""><?=$this->lang->line('select')?></option>
                </select>
                <span id="err_country_id" class="error invalid-feedback"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_state_id')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4 field_validation" name="state_id" id="state_id" placeholder="<?=$this->lang->line('supplier_state_id')?>" width="100%">
                  <option value=""><?=$this->lang->line('select')?></option>
                </select>
                <span id="err_state_id" class="error invalid-feedback"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('supplier_city_id')?></label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4" name="city_id" id="city_id" width="100%">
                  <option value=""><?=$this->lang->line('select')?></option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('supplier_address')?>
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-sm-6">
                <input type="text" name="address" value="<?=set_value('address') ?>" class="form-control form-control-sm" id="address" placeholder="<?=$this->lang->line('supplier_address')?>"><?=form_error('address', '<div class="text-danger">', '</div>');?>
                <!-- <span id="err_address" class="error invalid-feedback"><?=form_error('address', '<div class="text-danger">', '</div>');?></span> -->
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('supplier_website')?></label>
              <div class="col-sm-6">
                <input type="text" name="website" value="<?=set_value('website') ?>" class="form-control form-control-sm" id="website" placeholder="<?=$this->lang->line('supplier_website')?>">
                <span id="err_website" class="error invalid-feedback"><?=form_error('website');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('supplier_payment_duration')?></label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4" name="payment_duration" id="payment_duration" width="100%">                  
                <?php  
                  for ($i = 1; $i <= 180; $i++) 
                  {
                ?>
                    <option value="<?=$i;?>">
                      <?=$i .' days';?>
                    </option>
                <?php
                  }
                ?>
                </select>
              </div>
            </div>

            <!-- Updated WhatsApp Field with Auto Country Code -->
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">
                <?=$this->lang->line('whatsapp_no')?>
              </label>
              <div class="col-sm-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="whatsapp_country_code_display">+91</span>
                  </div>
                  <input type="number" name="whatsapp_no" value="<?=set_value('whatsapp_no') ?>" class="form-control" id="whatsapp_no" placeholder="<?=$this->lang->line('whatsapp_no')?>">
                </div>
                <input type="hidden" name="whatsapp_country_code" id="whatsapp_country_code_hidden" value="91">
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

<button type="button" name="submit" id="supplierSubmit" class="btn btn-primary"><?php echo $this->lang->line('submit');?></button>            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  // Regular expression for GSTIN format
  var gstReg = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

  const SupplierToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 10000
  });

  var countriesData = [];
  var isSelect2Initialized = false;

  // Function to initialize Select2
  function initSupplierSelect2() {
    if(isSelect2Initialized) return;
    
    $('#add_supplier_modal #country_id').select2('destroy');
    $('#add_supplier_modal #state_id').select2('destroy');
    $('#add_supplier_modal #city_id').select2('destroy');
    $('#add_supplier_modal #gst_registration_type').select2('destroy');
    $('#add_supplier_modal #payment_duration').select2('destroy');
    
    $('#add_supplier_modal #country_id').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $('#add_supplier_modal'),
      placeholder: 'Search Country',
      allowClear: true
    });
    
    $('#add_supplier_modal #state_id').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $('#add_supplier_modal'),
      placeholder: 'Search State',
      allowClear: true
    });
    
    $('#add_supplier_modal #city_id').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $('#add_supplier_modal'),
      placeholder: 'Search City',
      allowClear: true
    });
    
    $('#add_supplier_modal #gst_registration_type').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $('#add_supplier_modal'),
      placeholder: 'Select Option',
      allowClear: true
    });
    
    $('#add_supplier_modal #payment_duration').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $('#add_supplier_modal'),
      placeholder: 'Select Duration',
      allowClear: true
    });
    
    isSelect2Initialized = true;
  }

  // When modal is shown
  $('#add_supplier_modal').on('shown.bs.modal', function() {
    isSelect2Initialized = false;
    initSupplierSelect2();
    
    $("#addSupplierForm #company_name").focus();

    var company_country_id = '<?=$this->company_settings_model->get_company_records()->country_id?>';
    var countrySelect = $('#add_supplier_modal #country_id');
    
    if(countrySelect.find('option').length <= 1) {
      $.ajax({
        url: "<?php echo base_url('utility/get_countries') ?>/",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          countriesData = data;
          countrySelect.empty();
          countrySelect.append('<option value="">Select Country</option>');
          
          for(var i=0; i<data.length; i++) {
            countrySelect.append('<option value="' + data[i].id + '" data-phonecode="' + data[i].phonecode + '">' + data[i].name + '</option>');
          }
          
          countrySelect.val(company_country_id);
          countrySelect.trigger('change.select2');
        },
        error: function() {
          console.log('Error loading countries');
        }
      });
    }
  });

  // When modal is hidden
  $('#add_supplier_modal').on('hidden.bs.modal', function() {
    isSelect2Initialized = false;
    $('.gstin_row').hide();
    $('#addSupplierForm')[0].reset();
    
    $('form#addSupplierForm .error.invalid-feedback').each(function() {
      $(this).text('');
    });
    
    $('form#addSupplierForm .form-control').each(function() {
      $(this).removeClass('is-invalid is-valid');
    });
    
    $('#phone_country_code_display').text('+91');
    $('#phone_country_code').val('91');
    $('#whatsapp_country_code_display').text('+91');
    $('#whatsapp_country_code_hidden').val('91');
    
    var stateSelect = $('#add_supplier_modal #state_id');
    var citySelect = $('#add_supplier_modal #city_id');
    
    stateSelect.empty().append('<option value="">Select State</option>');
    citySelect.empty().append('<option value="">Select City</option>');
    
    stateSelect.trigger('change.select2');
    citySelect.trigger('change.select2');
  });

  // Field validation
  $("#addSupplierForm .field_validation").on("blur change keyup", function() {
    var id = $(this).attr('id');
    var value = $(this).val();
    var field = $(this).attr('placeholder');
    
    if(value == null || value == "") {
      $("#err_"+id).text(field + " field is required.");
      $("#"+id).addClass('is-invalid').removeClass('is-valid');
      return false;
    } else {
      $("#err_"+id).text("");
      $("#"+id).addClass('is-valid').removeClass('is-invalid');
    }
  });

  // GSTIN validation
  $("#addSupplierForm #gstin").on("blur keyup change", function() {
    if ($('#gst_registration_type').val() == 1) {
      if ($(this).val() == "") {
        $("#err_gstin").text("GSTIN field is required.");
        $(this).addClass('is-invalid').removeClass('is-valid');
      } else if (!gstReg.test($(this).val())) {
        $("#err_gstin").text("Please enter valid GSTIN.");
        $(this).addClass('is-invalid').removeClass('is-valid');
      } else {
        $("#err_gstin").text("");
        $(this).addClass('is-valid').removeClass('is-invalid');
      }
    }
  });

  // GST Registration Type change
  $("#addSupplierForm #gst_registration_type").on("change", function() {
    if ($(this).val() == 1) {
      $('.gstin_row').show();
      if ($('#gstin').val() == "") {
        $("#err_gstin").text("GSTIN field is required.");
        $('#gstin').addClass('is-invalid');
      } else if (!gstReg.test($('#gstin').val())) {
        $("#err_gstin").text("Please enter valid GSTIN.");
        $('#gstin').addClass('is-invalid');
      }
    } else {
      $('.gstin_row').hide();
      $("#err_gstin").text("");
      $('#gstin').removeClass('is-invalid is-valid');
    }
  });

  // Country change event
  $(document).on('change', '#add_supplier_modal #country_id', function() {
    var id = $(this).val();
    var selectedOption = $(this).find('option:selected');
    var phonecode = selectedOption.data('phonecode');
    
    if(phonecode) {
      $('#phone_country_code_display').text('+' + phonecode);
      $('#phone_country_code').val(phonecode);
      $('#whatsapp_country_code_display').text('+' + phonecode);
      $('#whatsapp_country_code_hidden').val(phonecode);
    }

    var stateSelect = $('#add_supplier_modal #state_id');
    var citySelect = $('#add_supplier_modal #city_id');
    
    stateSelect.empty();
    stateSelect.append('<option value="">Select State</option>');
    citySelect.empty();
    citySelect.append('<option value="">Select City</option>');
    
    if(id) {
      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          for(var i=0; i<data.length; i++) {
            stateSelect.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
          stateSelect.trigger('change.select2');
        }
      });
    } else {
      stateSelect.trigger('change.select2');
    }
  });

  // State change event
  $(document).on('change', '#add_supplier_modal #state_id', function() {
    var id = $(this).val();
    var citySelect = $('#add_supplier_modal #city_id');
    
    citySelect.empty();
    citySelect.append('<option value="">Select City</option>');
    
    if(id) {
      $.ajax({
        url: "<?php echo base_url('utility/get_cities') ?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          for(var i=0; i<data.length; i++) {
            citySelect.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
          citySelect.trigger('change.select2');
        }
      });
    } else {
      citySelect.trigger('change.select2');
    }
  });

  // ============ MAIN SUBMIT HANDLER ============
  $('#supplierSubmit').click(function(e) {
    e.preventDefault();
    console.log("Submit button clicked"); // Debug log
    
    var isError = false;
    
    // Validate required fields
    $('form#addSupplierForm .field_validation').each(function() {
      var id = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      
      if(value == null || value == "") {
        $("#err_"+id).text(field + " field is required.");
        $("#"+id).addClass('is-invalid').removeClass('is-valid');
        isError = true;
      } else {
        $("#err_"+id).text("");
        $("#"+id).addClass('is-valid').removeClass('is-invalid');
      }
    });

    // GSTIN validation
    if ($('#gst_registration_type').val() == 1) {
      if ($('#gstin').val() == "") {
        $("#err_gstin").text('GSTIN field is required');
        $('#gstin').addClass('is-invalid');
        isError = true;
      } else if (!gstReg.test($('#gstin').val())) {
        $("#err_gstin").text('Please enter valid GSTIN');
        $('#gstin').addClass('is-invalid');
        isError = true;
      } else {
        $("#err_gstin").text("");
        $('#gstin').removeClass('is-invalid').addClass('is-valid');
      }
    }

    if(isError == true) {
      $('html, body').animate({
        scrollTop: $('.is-invalid:first').offset().top - 100
      }, 500);
      return false;
    }
    
    // AJAX submission
    var formData = $('#addSupplierForm').serialize();
    $('#supplierSubmit').text('Please Wait...').prop('disabled', true);
    
    $.ajax({
      url: '<?php echo base_url("supplier/add") ?>',
      type: 'POST',
      dataType: 'json',
      data: formData,
      success: function(response) {
        console.log("Response:", response); // Debug log
        if(response.code == 1) {
          $('#add_supplier_modal').modal('hide');
          
          SupplierToast.fire({
            icon: 'success',
            title: response.message
          });
          
          // Update supplier dropdown
          if(response.suppliers && response.suppliers.length) {
            $('#supplier_id', window.parent.document).empty();
            $('#supplier_id').empty();
            $('#supplier_id').append('<option value="">Select</option>');
            for(var i = 0; i < response.suppliers.length; i++) {
              $('#supplier_id').append('<option value="' + response.suppliers[i].id + '">' + response.suppliers[i].company_name + '</option>');
            }
            $('#supplier_id').val(response.id).trigger('change');
          }
          
          // Reset form
          $('#addSupplierForm')[0].reset();
          
        } else {
          SupplierToast.fire({
            icon: 'error',
            title: response.message
          });
        }
        $('#supplierSubmit').text('Submit').prop('disabled', false);
      },
      error: function(xhr, status, error) {
        console.log('Error:', error);
        SupplierToast.fire({
          icon: 'error',
          title: 'Something went wrong! Please try again.'
        });
        $('#supplierSubmit').text('Submit').prop('disabled', false);
      }
    });
  });
  
});
</script>