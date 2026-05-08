<div class="modal-content">
    <form role="form" method="post" name="addWarehouseForm" id="addWarehouseForm">
        <div class="modal-header text-left">
            <h4 class="modal-title">Add Branch</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- Your existing fields -->
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">
                                Name<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="name" value="" class="form-control form-control-sm field_validation" id="name" placeholder="Name">
                                <span id="err_name" class="error invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-sm-4 col-form-label">
                                Code<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="code" value="" class="form-control form-control-sm field_validation" id="code" placeholder="Code">
                                <span class="error text-danger" id="err_code"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-sm-4 col-form-label">
                                Description<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="description" value="" class="form-control form-control-sm field_validation" id="description" placeholder="Description">
                                <span id="err_description" class="error invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">
                                Default<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="radio" name="is_default" value="no" checked=""> NO
                                <input type="radio" name="is_default" value="yes"> YES
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="license_no" class="col-sm-4 col-form-label">
                                License No.
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="license_no" value="" class="form-control form-control-sm" id="license_no" placeholder="License no.">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">
                                Is Head Office<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="radio" name="is_head_office" value="yes"> YES
                                <input type="radio" name="is_head_office" value="no" checked> NO
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <!-- Country Dropdown -->
                        <div class="form-group row">
                            <label for="country_id" class="col-sm-4 col-form-label">
                                Country<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm select2bs4 field_validation" name="country_id" id="country_id">
                                    <option value="">Select Country</option>
                                    <?php 
                                    if(isset($countries) && !empty($countries)):
                                        foreach ($countries as $country):
                                            // Set India (ID 101) as selected by default
                                            $selected = ($country->id == 101) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $country->id ?>" <?= $selected ?>><?= $country->name ?></option>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </select>
                                <span id="err_country_id" class="error invalid-feedback"></span>
                            </div>
                        </div>

                        <!-- State Dropdown -->
                        <div class="form-group row">
                            <label for="state_id" class="col-sm-4 col-form-label">
                                State<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm select2bs4 field_validation" name="state_id" id="state_id">
                                    <option value="">Select State</option>
                                    <?php 
                                    if(isset($states) && !empty($states)):
                                        foreach ($states as $state):
                                            // Set Uttar Pradesh (ID 38) as selected by default
                                            $selected = ($state->id == 38) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $state->id ?>" <?= $selected ?>><?= $state->name ?></option>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </select>
                                <span id="err_state_id" class="error invalid-feedback"></span>
                            </div>
                        </div>

                        <!-- City Dropdown -->
                        <div class="form-group row">
                            <label for="city_id" class="col-sm-4 col-form-label">
                                City<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm select2bs4 field_validation" name="city_id" id="city_id">
                                    <option value="">Select City</option>
                                    <?php 
                                    if(isset($cities) && !empty($cities)):
                                        foreach ($cities as $city):
                                    ?>
                                        <option value="<?= $city->id ?>"><?= $city->name ?></option>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </select>
                                <span id="err_city_id" class="error invalid-feedback"></span>
                            </div>
                        </div>

                        <!-- Address Fields -->
                        <div class="form-group row">
                            <label for="address_line1" class="col-sm-4 col-form-label">
                                Address Line1
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="address_line1" value="" class="form-control form-control-sm" id="address_line1" placeholder="Address Line1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address_line2" class="col-sm-4 col-form-label">
                                Address Line2
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="address_line2" value="" class="form-control form-control-sm" id="address_line2" placeholder="Address Line2">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pincode" class="col-sm-4 col-form-label">
                                Pincode
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="pincode" value="" class="form-control form-control-sm" id="pincode" placeholder="Pincode">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" name="submit" id="addWarehouseSubmit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize Select2
    $('#add_warehouse_modal #country_id, #add_warehouse_modal #state_id, #add_warehouse_modal #city_id').select2({
        theme: 'bootstrap4',
        width: '100%',
        dropdownParent: $('#add_warehouse_modal')
    });

    // Load cities for default state (Uttar Pradesh - ID 38)
    function loadCitiesForDefaultState() {
        var state_id = $('#add_warehouse_modal #state_id').val();
        if (state_id) {
            $.ajax({
                url: '<?php echo base_url("utility/get_cities"); ?>/' + state_id,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    var citySelect = $('#add_warehouse_modal #city_id');
                    citySelect.html('<option value="">Select City</option>');
                    
                    if(data && data.length > 0) {
                        $.each(data, function(key, city) {
                            citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                    }
                    
                    citySelect.select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        dropdownParent: $('#add_warehouse_modal')
                    });
                }
            });
        }
    }

    // Load cities when modal opens (for default state)
    $('#add_warehouse_modal').on('shown.bs.modal', function() {
        loadCitiesForDefaultState();
    });

    // Country change event - Load states
    $(document).off('change', '#add_warehouse_modal #country_id');
    $(document).on('change', '#add_warehouse_modal #country_id', function() {
        var country_id = $(this).val();
        var stateSelect = $('#add_warehouse_modal #state_id');
        var citySelect = $('#add_warehouse_modal #city_id');
        
        stateSelect.html('<option value="">Select State</option>');
        citySelect.html('<option value="">Select City</option>');
        
        if (country_id) {
            $.ajax({
                url: '<?php echo base_url("utility/get_states"); ?>/' + country_id,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    if(data && data.length > 0) {
                        $.each(data, function(key, state) {
                            stateSelect.append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                    }
                    
                    stateSelect.select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        dropdownParent: $('#add_warehouse_modal')
                    });
                    
                    // If India is selected, set default state to Uttar Pradesh (ID 38)
                    if(country_id == 101) {
                        $('#add_warehouse_modal #state_id').val(38).trigger('change');
                    }
                }
            });
        } else {
            stateSelect.select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#add_warehouse_modal')
            });
        }
    });

    // State change event - Load cities
    $(document).off('change', '#add_warehouse_modal #state_id');
    $(document).on('change', '#add_warehouse_modal #state_id', function() {
        var state_id = $(this).val();
        var citySelect = $('#add_warehouse_modal #city_id');
        
        citySelect.html('<option value="">Select City</option>');
        
        if (state_id) {
            $.ajax({
                url: '<?php echo base_url("utility/get_cities"); ?>/' + state_id,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    if(data && data.length > 0) {
                        $.each(data, function(key, city) {
                            citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                    }
                    
                    citySelect.select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        dropdownParent: $('#add_warehouse_modal')
                    });
                }
            });
        } else {
            citySelect.select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#add_warehouse_modal')
            });
        }
    });
    
    // Trigger country change to load states with default selection
    setTimeout(function() {
        if ($('#add_warehouse_modal #country_id').val()) {
            $('#add_warehouse_modal #country_id').trigger('change');
        }
    }, 100);
});
</script>