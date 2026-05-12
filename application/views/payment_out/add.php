<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Add Payment Out</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="paymentOutForm" method="POST" action="<?=base_url('payment_out/add')?>" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Reference No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="reference_no" value="<?=isset($reference_no) ? $reference_no : ''?>" readonly>
                                    </div>
                                    
                                    <label class="col-sm-2 col-form-label">Payment Date*</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control datepicker" name="payment_date" value="<?=date('d-m-Y')?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Supplier*</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="supplier_id" id="supplier_id" required>
                                            <option value="">Select Supplier</option>
                                            <?php foreach($suppliers as $supplier): ?>
                                                <option value="<?=$supplier->id?>"><?=$supplier->company_name?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-danger" id="supplier_due_amount">Due: ₹0.00</small>
                                    </div>
                                    
                                    <label class="col-sm-2 col-form-label">From Account*</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="from_account" id="from_account" required>
                                            <option value="">Select Account</option>
                                            <?php foreach($bank_accounts as $account): ?>
                                                <option value="<?=$account->id?>"><?=$account->title?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Amount*</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" name="amount" id="total_amount" step="0.01" min="0" required>
                                    </div>
                                    <label class="col-sm-2 col-form-label">Payment Mode*</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="payment_mode" id="payment_mode" required>
                                            <option value="0">Cash</option>
                                            <option value="1">Credit Card</option>
                                            <option value="2">Cheque</option>
                                            <option value="3">NEFT/RTGS</option>
                                        </select>
                                    </div>
                                </div>
                                
<div class="form-group row neft-fields" style="display: none;">
    <label class="col-sm-2 col-form-label">UTR Number*</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="utr_number" id="utr_number" placeholder="Enter UTR/Reference Number">
    </div>
    
    <label class="col-sm-2 col-form-label">Bank Name*</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Enter Bank Name">
    </div>
</div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Notes</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                    </div>
                                </div>
                                
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label><?=$this->lang->line('cdn_document')?></label>
                            <div class="input-group input-group-sm">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="upload_document" name="upload_document[]" accept=".pdf" multiple>
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                              </div>
                              
                              <input type="hidden" value="" name="document" id="document">
                            </div>
                            <span id="fileTypeError" class="error-message"></span> 
                        </div>
                      </div>
                    </div>
                                
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
                                <input type="hidden" name="action" id="form_action" value="save">
                                <button type="button" class="btn btn-info" id="link_payment_btn">Link Payment</button>
                                <!--<button type="submit" class="btn btn-primary">Save Payment</button>-->
                                <a href="<?=base_url('payment_out')?>" class="btn btn-default float-right">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="linkPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body" id="modalContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<script>
$(document).ready(function() {
    // Initialize datepicker and select2
    $('.datepicker').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    $('.select2').select2();
    
    
    
    // Supplier change event
    /*$('#supplier_id').change(function() {
        var supplier_id = $(this).val();
        if(supplier_id) {
            $.ajax({
                url: '<?=base_url("payment_out/get_supplier_due_amount")?>',
                type: 'POST',
                data: {
                    supplier_id: supplier_id,
                    '<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
                },
                dataType: 'json',
                success: function(response) {
                    $('#supplier_due_amount').text('Due: ₹' + parseFloat(response.due_amount).toFixed(2));
                }
            });
        } else {
            $('#supplier_due_amount').text('Due: ₹0.00');
        }
    });*/
    // Supplier change event
$('#supplier_id').on('change', function() {
    var supplier_id = $(this).val();
    console.log('Supplier selected:', supplier_id);
    
    if(supplier_id) {
        $('#supplier_due_amount').html('Due: <i class="fa fa-spinner fa-spin"></i> Loading...');
        
        var csrf_token_name = '<?=$this->security->get_csrf_token_name()?>';
        var csrf_token_value = $('input[name="' + csrf_token_name + '"]').val();
        
        $.ajax({
            url: '<?=base_url("payment_out/get_supplier_due_amount")?>',
            type: 'POST',
            data: {
                supplier_id: supplier_id,
                [csrf_token_name]: csrf_token_value
            },
            dataType: 'text', // Change from 'json' to 'text'
            success: function(responseText) {
                console.log('Raw Response:', responseText);
                
                // Try to extract JSON from the response (remove PHP warnings)
                var jsonMatch = responseText.match(/\{.*\}/);
                if(jsonMatch) {
                    try {
                        var response = JSON.parse(jsonMatch[0]);
                        if(response.status == 'success') {
                            $('#supplier_due_amount').html('Due: ₹' + parseFloat(response.due_amount).toFixed(2));
                        } else {
                            $('#supplier_due_amount').html('Due: ₹0.00');
                        }
                    } catch(e) {
                        console.error('JSON Parse Error:', e);
                        $('#supplier_due_amount').html('Due: ₹0.00 (Parse Error)');
                    }
                } else {
                    $('#supplier_due_amount').html('Due: ₹0.00 (No JSON found)');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#supplier_due_amount').html('Due: ₹0.00 (Connection Error)');
            }
        });
    } else {
        $('#supplier_due_amount').html('Due: ₹0.00');
    }
});
    
    $('#payment_mode').change(function() {
    var mode = $(this).val();
    console.log('Payment mode changed to:', mode);
    
    if(mode == '3') { // NEFT/RTGS
        console.log('Showing NEFT fields');
        $('.neft-fields').show();
        $('#utr_number').prop('required', true);
        $('#bank_name').prop('required', true);
    } else {
        console.log('Hiding NEFT fields');
        $('.neft-fields').hide();
        $('#utr_number').prop('required', false);
        $('#bank_name').prop('required', false);
        
        // Clear values when hidden
        $('#utr_number').val('');
        $('#bank_name').val('');
    }
});

// Trigger on page load in case mode is pre-selected
console.log('Initial trigger');
$('#payment_mode').trigger('change');
    
    // Link payment button click
// Link payment button click
// $('#link_payment_btn').click(function(e) {
//     e.preventDefault();
//     if(!validateForm()) return;
    
//     $('#form_action').val('link_payment');
    
//     // Get all form data
//     var formData = $('#paymentOutForm').serialize();
    
//     // DEBUG: Show what's being sent
//     console.log('=== DEBUG: Form Data ===');
//     console.log('Form serialized:', formData);
//     console.log('UTR value:', $('#utr_number').val());
//     console.log('Bank value:', $('#bank_name').val());
//     console.log('Payment mode:', $('#payment_mode').val());
    
//     $.ajax({
//         url: $('#paymentOutForm').attr('action'),
//         type: 'POST',
//         data: formData,
//         success: function(response) {
//             console.log('Modal loaded successfully');
//             $('#modalContent').html(response);
//             $('#linkPaymentModal').modal('show');
//         },
//         error: function(xhr, status, error) {
//             console.error('AJAX Error:', error, xhr.responseText);
//         }
//     });
// });

$('#link_payment_btn').click(function(e) {
    e.preventDefault();
    if(!validateForm()) return;
    
    $('#form_action').val('link_payment');
    
    // Use FormData instead of .serialize() to include files
    var formData = new FormData($('#paymentOutForm')[0]);
    
    // DEBUG: Show what's being sent
    console.log('=== DEBUG: Form Data ===');
    console.log('FormData object created');
    console.log('UTR value:', $('#utr_number').val());
    console.log('Bank value:', $('#bank_name').val());
    console.log('Payment mode:', $('#payment_mode').val());
    console.log('Document value:', $('#document').val());
    
    $.ajax({
        url: $('#paymentOutForm').attr('action'),
        type: 'POST',
        data: formData,
        processData: false, // Important: Don't process the data
        contentType: false, // Important: Don't set content type
        success: function(response) {
            console.log('Modal loaded successfully');
            $('#modalContent').html(response);
            $('#linkPaymentModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error, xhr.responseText);
        }
    });
});
//     // // Link payment button click
    // $('#link_payment_btn').click(function(e) {
    //     e.preventDefault();
    //     if(!validateForm()) return;
        
    //     $('#form_action').val('link_payment');
    //     $.ajax({
    //         url: $('#paymentOutForm').attr('action'),
    //         type: 'POST',
    //         data: $('#paymentOutForm').serialize(),
    //         success: function(response) {
    //             $('#modalContent').html(response);
    //             $('#linkPaymentModal').modal('show');
    //         }
    //     });
    // });
    
    function validateForm() {
        // Form validation logic
        return true;
    }
});

// Add this to your $(document).ready() function, after the existing code
$(document).on('change', '#upload_document', function(e) {
    var allowedExtensions = ["pdf"];
    var files = this.files;
    var filenames = [];

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var fileExtension = file.name.split(".").pop().toLowerCase();
        var errorSpan = $('#fileTypeError');
        var fileInputLabel = $(this).next('.custom-file-label');

        // File name validation
        var fileName = file.name;
        var regex = /^[a-zA-Z0-9_]+(\.[a-zA-Z0-9]+)?$/;

        if (!regex.test(fileName)) {
            Swal.fire({
                title: "Message",
                text: "File name should only contain alphanumeric (a-z0-9) and underscore ( _ ).",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 5000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

            $(this).val("");
            return;
        }

        // File type validation
        if ($.inArray(fileExtension, allowedExtensions) === -1) {
            errorSpan.text("Only PDF files are allowed.");
            $(this).val("");
            fileInputLabel.text("Choose file");
            return;
        }

        fileInputLabel.text(file.name);
        filenames.push(file.name);
    }

    // Update hidden field
    var joinedFilenames = filenames.join(', ');
    $('#document').val(joinedFilenames);

    // Prepare form data for AJAX upload
    var formData = new FormData();
    for (var j = 0; j < files.length; j++) {
        formData.append("upload_document[]", files[j]);
    }

    // Add CSRF token
    var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>";
    var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>";

    formData.append(csrfTokenName, csrfTokenValue);

    // Upload to server
    $.ajax({
        url: "<?php echo base_url('payment_out/upload_documents')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.error) {
                $('#fileTypeError').text(response.error);
            } else if (response.length > 0) {
                var documentValue = response.join(', ');
                $('#document').val(documentValue);
                $('#fileTypeError').text('');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('#fileTypeError').text("Error uploading document");
            console.error("Error:", errorThrown);
        }
    });
});
</script>