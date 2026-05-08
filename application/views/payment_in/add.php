<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Add Payment</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="paymentInForm" method="POST" action="<?=base_url('payment_in/add')?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Receipt No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="reference_no" value="<?=isset($reference_no) ? $reference_no : ''?>" readonly>
                                    </div>
                                    
                                    <label class="col-sm-2 col-form-label">Payment Date*</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control datepicker" name="payment_date" value="<?=date('d-m-Y')?>" required>
                                    </div>
                                </div>
                                
                                   <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Customer*</label>
                                        <div class="col-sm-4">
                                       <select class="form-control select2" name="customer_id" id="customer_id" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach($customers as $customer): ?>
                                                <option value="<?= $customer->id ?>" data-wallet="<?= $customer->wallet_balance ?? 0 ?>">
                                                    <?= $customer->customer_name ?>
                                                    <?php if (!empty($customer->customer_company_name)): ?>
                                                        (<?= $customer->customer_company_name ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                            <small class="text-danger" id="customer_due_amount">Due: ₹0.00</small>
                                            <small class="text-success" id="customer_wallet_balance">Advance: ₹0.00</small>
                                        </div>
                                    
                                    <label class="col-sm-2 col-form-label">To Account*</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="to_account" id="to_account" required>
                                            <option value="">Select Account</option>
                                            <?php foreach($bank_accounts as $account): ?>
                                                <option value="<?=$account->id?>"><?=$account->title?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Received Amount*</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control payment-amount-input" name="amount" id="total_amount" step="0.01" min="0" required>
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
                              
                              <input type="hidden" value="" name="document[]" id="document">
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
                                <button type="submit" class="btn btn-primary">Save to Wallet</button>
                                <a href="<?=base_url('payment_in')?>" class="btn btn-default float-right">Cancel</a>
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
    
    // Customer change event
    $('#customer_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var walletBalance = selectedOption.data('wallet') || 0;
        
        // Display wallet balance
        $('#customer_wallet_balance').text('Advance: ₹' + parseFloat(walletBalance).toFixed(2));
        
        var customer_id = $(this).val();
        if(customer_id) {
            $.ajax({
                url: '<?=base_url("payment_in/get_customer_due_amount")?>',
                type: 'POST',
                data: {
                    customer_id: customer_id,
                    '<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
                },
                dataType: 'json',
                success: function(response) {
                    $('#customer_due_amount').text('Due: ₹' + parseFloat(response.due_amount).toFixed(2));
                }
            });
        } else {
            $('#customer_due_amount').text('Due: ₹0.00');
            $('#customer_wallet_balance').text('Advance: ₹0.00');
        }
    });
    
    // Trigger change event on page load if a customer is already selected
    if($('#customer_id').val()) {
        $('#customer_id').trigger('change');
    }
    
    // Link payment button click
    $('#link_payment_btn').click(function(e) {
        e.preventDefault();
        if(!validateForm()) return;
        
        $('#form_action').val('link_payment');
        $.ajax({
            url: $('#paymentInForm').attr('action'),
            type: 'POST',
            data: $('#paymentInForm').serialize(),
            success: function(response) {
                $('#modalContent').html(response);
                $('#linkPaymentModal').modal('show');
            }
        });
    });
    
    function validateForm() {
        // Form validation logic
        return true;
    }
});
</script>