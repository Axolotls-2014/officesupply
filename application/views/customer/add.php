<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item "><a href="<?=base_url('customer')?>"><?=$this->lang->line('header_customer')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('customer_add')?></li>
          </ol>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <form class="form-horizontal" method="post" action="<?php base_url('customer/add')?>" id="customerForm">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"><?=$this->lang->line('customer_add')?></h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item ml-2">
                      <a class="nav-link active text-white btn btn-secondary" href="<?=base_url('customer')?>" data-tt="tooltip" title="Click here to show customer list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <table style="width: 100%;" class="table">
                  <tr>
                    <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Personal Details</th>
                    <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Billing Details</th>
                    <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Shipping Details ( <input type="checkbox" id="copy_address" name="copy_address"> same as billing details)</th>
                  </tr>
                  <tr>
                    <td style="width: 30%;">
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_company_name')?>
                          <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="customer_company_name" value="<?=set_value('customer_company_name') ?>" class="form-control form-control-sm field_validation" id="customer_company_name" placeholder="<?=$this->lang->line('customer_company_name')?>" required>
                          <span id="err_customer_company_name" class="error invalid-feedback"><?=form_error('customer_company_name');?></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_name')?>
                          <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="customer_name" value="<?=set_value('customer_name') ?>" class="form-control form-control-sm field_validation" id="customer_name" placeholder="<?=$this->lang->line('customer_name')?>" required>
                          <span id="err_customer_name" class="error invalid-feedback"><?=form_error('customer_name');?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          GSTIN
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="gstin" value="<?=set_value('gstin') ?>" class="form-control form-control-sm" id="gstin" placeholder="GSTIN">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_department')?>
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="customer_department" value="<?=set_value('customer_department') ?>" class="form-control form-control-sm" id="customer_department" placeholder="<?=$this->lang->line('customer_department')?>">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_email')?>
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="email" value="<?=set_value('email') ?>" class="form-control form-control-sm" id="email" placeholder="<?=$this->lang->line('customer_email')?>">
                          <span id="err_email" class="error invalid-feedback"><?=form_error('email');?></span>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_phone')?>
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="phone" value="<?=set_value('phone') ?>" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('customer_phone')?>">
                          <span id="err_phone" class="error invalid-feedback"><?=form_error('phone');?></span>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          Password
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="password" value="<?=set_value('password') ?>" class="form-control form-control-sm" id="password" placeholder="password" step="any">
                          <span id="password" class="error invalid-feedback"><?=form_error('password');?></span>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_payment_duration')?>
                        </label>
                        <div class="col-sm-8">
                          <select class="form-control form-control-sm select2bs4" name="payment_duration" id="payment_duration" width="100%">
                            <?php for ($i = 1; $i <= 180; $i++) { ?>
                              <option value="<?=$i;?>"><?=$i .' days';?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group row">

  <!-- FIELD 1 : DUE DAYS DROPDOWN -->
  <label class="col-sm-4 col-form-label">
    Default Due Days
  </label>

  <div class="col-sm-8">

    <select class="form-control form-control-sm"
            id="default_due_days"
            name="default_due_days">

      <option value="">Select Due Days</option>

      <?php foreach($due_days_options as $option): ?>
        <option value="<?= $option->due_day ?>"
                data-terms="<?= htmlspecialchars($option->terms_and_condition) ?>">
          <?= $option->due_day ?> Days
        </option>
      <?php endforeach; ?>

    </select>

  </div>
</div>


<div class="form-group row">

  <!-- FIELD 2 : TERMS TEXT -->
  <label class="col-sm-4 col-form-label">
    Default Payment Terms
  </label>

  <div class="col-sm-8">

    <textarea class="form-control form-control-sm"
              id="default_payment_terms"
              name="default_payment_terms"
              rows="3"
              placeholder="Terms will auto-fill based on Due Days"></textarea>

  </div>
</div>

<input type="hidden"
       name="default_payment_terms"
       id="default_payment_terms">

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
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_country_id')?>
                          <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                          <select class="form-control form-control-sm select2bs4 field_validation" name="country_id" id="country_id" width="100%">
                            <option value="">Select Country</option>
                            <?php
                              $company_settings = $this->company_settings_model->get_company_records();
                              $selected_country_id = $company_settings->country_id;
                              foreach ($countries as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('country_id', $value->id, $selected_country_id == $value->id); ?>>
                                <?= $value->name;?>
                              </option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_state_id')?>
                          <span class="text-danger">*</span>  
                        </label>
                        <div class="col-sm-8">
                          <select class="form-control form-control-sm select2bs4 field_validation" name="state_id" id="state_id" width="100%">
                            <option value="">Select State</option>
                            <?php if(isset($states)) {
                              $company_settings = $this->company_settings_model->get_company_records();
                              $selected_state_id = $company_settings->state_id;
                              foreach ($states as $value) { ?>
                                <option value="<?=$value->id;?>" <?php echo set_select('state_id', $value->id,$selected_state_id == $value->id); ?>>
                                  <?= $value->name;?>
                                </option>
                              <?php }
                            } ?>
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
                            <?php if(isset($cities)) {
                              foreach ($cities as $value) { ?>
                                <option value="<?=$value->id;?>" <?php echo set_select('city_id', $value->id); ?>>
                                  <?= $value->name;?>
                                </option>
                              <?php }
                            } ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">
                          <?=$this->lang->line('customer_address')?>
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="address" value="<?=set_value('address') ?>" class="form-control form-control-sm" id="address" placeholder="<?=$this->lang->line('customer_address')?>">
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
                    
                    <td style="width: 30%; vertical-align: top;">
                      <div id="shipping_addresses_container">
                        <div class="shipping-address-group" data-index="0">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address Name</label>
                            <div class="col-sm-8">
                              <input type="text" name="shipping_address[0][shipping_name]" class="form-control form-control-sm" placeholder="e.g. Main Office, Warehouse">
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Country <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                              <select class="form-control form-control-sm select2bs4 shipping_country" name="shipping_address[0][shipping_country_id]" width="100%">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $value): ?>
                                <option value="<?=$value->id?>" <?=set_select('shipping_country_id', $value->id, $selected_country_id == $value->id)?>>
                                  <?=$value->name?>
                                </option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">State <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                              <select class="form-control form-control-sm select2bs4 shipping_state" name="shipping_address[0][shipping_state_id]" width="100%">
                                <option value="">Select State</option>
                                <?php if(isset($states)): ?>
                                  <?php foreach ($states as $value): ?>
                                  <option value="<?=$value->id?>" <?=set_select('state_id', $value->id, $selected_state_id == $value->id)?>>
                                    <?=$value->name?>
                                  </option>
                                  <?php endforeach; ?>
                                <?php endif; ?>
                              </select>
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">City</label>
                            <div class="col-sm-8">
                              <select class="form-control form-control-sm select2bs4 shipping_city" name="shipping_address[0][shipping_city_id]" width="100%">
                                <option value="">Select City</option>
                                <?php if(isset($cities)): ?>
                                  <?php foreach ($cities as $value): ?>
                                  <option value="<?=$value->id?>" <?=set_select('city_id', $value->id)?>>
                                    <?=$value->name?>
                                  </option>
                                  <?php endforeach; ?>
                                <?php endif; ?>
                              </select>
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                              <textarea name="shipping_address[0][shipping_address]" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Pincode</label>
                            <div class="col-sm-8">
                              <input type="text" name="shipping_address[0][shipping_pincode]" class="form-control form-control-sm">
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <div class="col-sm-8 offset-sm-4">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shipping_address[0][is_default]" value="1">
                                <label class="form-check-label">Set as Default Shipping Address</label>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group row">
                            <div class="col-sm-12 text-right">
                              <button type="button" class="btn btn-danger btn-sm remove-shipping-address" style="display: none;">Remove</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="text-right mt-2">
                        <button type="button" id="add_shipping_address" class="btn btn-info btn-sm">Add Another Shipping Address</button>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              
              <div class="card-footer">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <button type="submit" name="submit" id="customerSubmit" class="btn btn-info"><?=$this->lang->line('customer_save')?></button>
                <a href="<?=base_url('customer')?>" class="btn btn-default float-right"><?=$this->lang->line('customer_cancel')?></a>
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
$(document).ready(function() {
  // Initialize Select2 for country, state, city dropdowns
  function initSelect2(group) {
    group.find('.shipping_country, .shipping_state, .shipping_city').select2({
      theme: 'bootstrap4',
      width: '100%'
    });
  }
  
  $('#default_due_days').on('change', function () {

    var selectedOption = $(this).find('option:selected');

    var terms = selectedOption.data('terms') || '';

    // Fill textarea (editable)
    $('#default_payment_terms').val(terms);

});

  // Initialize Select2 on the first group
  initSelect2($('.shipping-address-group:first'));

  // Add new shipping address block
  $('#add_shipping_address').click(function() {
    var container = $('#shipping_addresses_container');
    var index = $('.shipping-address-group').length;
    var newGroup = $('.shipping-address-group:first').clone();

    // Destroy Select2 and clean DOM
    newGroup.find('select').each(function() {
      if ($(this).hasClass("select2-hidden-accessible")) {
        $(this).select2('destroy');
      }
      $(this).removeAttr('data-select2-id')
             .removeAttr('aria-hidden')
             .removeAttr('tabindex')
             .removeClass('select2-hidden-accessible')
             .show();
      $(this).next('.select2-container').remove();
    });


    // Clear input values
    newGroup.find('input, textarea').val('');
    newGroup.find('[type="checkbox"]').prop('checked', false);

    // Update name attributes with new index
    newGroup.find('[name]').each(function() {
      var name = $(this).attr('name');
      var newName = name.replace(/\[\d+\]/, '[' + index + ']');
      $(this).attr('name', newName);
    });

    // Set data-index and show remove button
    newGroup.attr('data-index', index);
    newGroup.find('.remove-shipping-address').show();

    // Append to container
    container.append(newGroup);

    // Re-initialize Select2 for new group
    initSelect2(newGroup);
  });

  // Remove a shipping address block
  $(document).on('click', '.remove-shipping-address', function() {
    if($('.shipping-address-group').length > 1) {
      $(this).closest('.shipping-address-group').remove();
    }
  });

  // Copy billing address to first shipping address
  $('#copy_address').click(function() {
    if($(this).is(':checked')) {
      var country = $('#country_id').val();
      var state = $('#state_id').val();
      var city = $('#city_id').val();
      var address = $('#address').val();
      var pincode = $('#pincode').val();

      var group = $('.shipping-address-group:first');
      group.find('.shipping_country').val(country).trigger('change');

      // Delay to allow state and city population
      setTimeout(function() {
        group.find('.shipping_state').val(state).trigger('change');
        setTimeout(function() {
          group.find('.shipping_city').val(city).trigger('change');
        }, 400);
      }, 400);

      group.find('[name*="[shipping_address]"]').val(address);
      group.find('[name*="[shipping_pincode]"]').val(pincode);
    }
  });

  // Load states when shipping country changes
  $(document).on('change', '.shipping_country', function() {
    var group = $(this).closest('.shipping-address-group');
    var id = $(this).val();

    var stateSelect = group.find('.shipping_state');
    var citySelect = group.find('.shipping_city');

    stateSelect.html('<option value="">Select State</option>');
    citySelect.html('<option value="">Select City</option>');

    if(id) {
      $.ajax({
        url: "<?=base_url('utility/get_states')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          $.each(data, function(i, state) {
            stateSelect.append('<option value="' + state.id + '">' + state.name + '</option>');
          });
        }
      });
    }
  });

  // Load cities when shipping state changes
  $(document).on('change', '.shipping_state', function() {
    var group = $(this).closest('.shipping-address-group');
    var id = $(this).val();

    var citySelect = group.find('.shipping_city');
    citySelect.html('<option value="">Select City</option>');

    if(id) {
      $.ajax({
        url: "<?=base_url('utility/get_cities')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          $.each(data, function(i, city) {
            citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
          });
        }
      });
    }
  });

  // Billing address country/state/city loading
  $('#country_id').change(function() {
    var id = $(this).val();
    $('#state_id').html('<option value="">Select State</option>');
    $('#city_id').html('<option value="">Select City</option>');

    $.ajax({
      url: "<?=base_url('utility/get_states')?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $.each(data, function(i, state) {
          $('#state_id').append('<option value="' + state.id + '">' + state.name + '</option>');
        });
      }
    });
  });

  $('#state_id').change(function() {
    var id = $(this).val();
    $('#city_id').html('<option value="">Select City</option>');

    $.ajax({
      url: "<?=base_url('utility/get_cities')?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $.each(data, function(i, city) {
          $('#city_id').append('<option value="' + city.id + '">' + city.name + '</option>');
        });
      }
    });
  });

  // jQuery Validate for customer form
  $('#customerForm').validate({
    rules: {
      customer_company_name: "required",
      customer_name: "required",
      country_id: "required",
      state_id: "required",
      'shipping_address[0][shipping_country_id]': "required",
      'shipping_address[0][shipping_state_id]': "required",
      'shipping_address[0][shipping_address]': "required"
    },
    messages: {
      customer_company_name: "Please enter company name",
      customer_name: "Please enter customer name",
      country_id: "Please select country",
      state_id: "Please select state",
      'shipping_address[0][shipping_country_id]': "Please select shipping country",
      'shipping_address[0][shipping_state_id]': "Please select shipping state",
      'shipping_address[0][shipping_address]': "Please enter shipping address"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
