<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Edit Payment <?=$payment->reference_no?></h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="paymentInForm" method="POST" action="<?=base_url('payment_in/edit/'.base64_encode($payment->id))?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Receipt No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="<?=$payment->reference_no?>" readonly>
                                    </div>
                                    
                                    <label class="col-sm-2 col-form-label">Payment Date*</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control datepicker" name="payment_date" value="<?=date('d-m-Y', strtotime($payment->payment_date))?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Customer*</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="customer_id" id="customer_id" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach($customers as $customer): ?>
                                                <option value="<?=$customer->id?>" 
                                                    <?=($customer->id == $payment->customer_id) ? 'selected' : ''?>
                                                    data-wallet="<?=$customer->wallet_balance ?? 0?>">
                                                    <?=$customer->customer_name?>
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
                                                <option value="<?=$account->id?>" <?=($account->id == $payment->to_account) ? 'selected' : ''?>>
                                                    <?=$account->title?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Received Amount*</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control payment-amount-input" name="amount" id="total_amount" 
                                            value="<?=$payment->amount?>" step="0.01" min="0" required>
                                    </div>
                                    <label class="col-sm-2 col-form-label">Payment Mode*</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="payment_mode" id="payment_mode" required>
                                            <option value="0" <?=($payment->payment_mode == 0) ? 'selected' : ''?>>Cash</option>
                                            <option value="1" <?=($payment->payment_mode == 1) ? 'selected' : ''?>>Credit Card</option>
                                            <option value="2" <?=($payment->payment_mode == 2) ? 'selected' : ''?>>Cheque</option>
                                            <option value="3" <?=($payment->payment_mode == 3) ? 'selected' : ''?>>NEFT/RTGS</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Notes</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="notes" rows="2"><?=$payment->notes?></textarea>
                                    </div>
                                </div>
                                
                                <?php if(!$payment->is_wallet_payment && !empty($payment->distributions)): ?>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h5>Previously Linked Invoices</h5>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Wallet Used</th>
                                                    <th>Cash Applied</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($payment->distributions as $dist): ?>
                                                    <tr>
                                                        <td><?=$dist->sale->reference_no?></td>
                                                        <td><?=date('d-m-Y', strtotime($dist->sale->invoice_date))?></td>
                                                        <td><?=number_format($dist->amount, 2)?></td>
                                                        <td><?=number_format($dist->wallet_amount, 2)?></td>
                                                        <td><?=number_format($dist->cash_amount, 2)?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Transaction History -->
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h5>Transaction History</h5>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Amount</th>
                                                    <th>From Account</th>
                                                    <th>To Account</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($payment->transactions as $transaction): 
                                                    $details = $this->payment_in_model->get_transaction_details($transaction->transaction_id);
                                                    $from_acc = $this->ledger_model->get_single_record($transaction->from_account);
                                                    $to_acc = $this->ledger_model->get_single_record($transaction->to_account);
                                                ?>
                                                    <tr>
                                                        <td><?=date('d-m-Y', strtotime($transaction->voucher_date))?></td>
                                                        <td><?=$transaction->type?></td>
                                                        <td><?=number_format($transaction->amount, 2)?></td>
                                                        <td><?=$from_acc ? $from_acc->title : 'N/A'?></td>
                                                        <td><?=$to_acc ? $to_acc->title : 'N/A'?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
                                <input type="hidden" name="action" id="form_action" value="<?=$payment->is_wallet_payment ? 'save' : 'link_payment'?>">
                                <?php if(!$payment->is_wallet_payment): ?>
                                    <button type="button" class="btn btn-info" id="link_payment_btn">Update Linked Payment</button>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="<?=base_url('payment_in')?>" class="btn btn-default float-right">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal for linked payments -->
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
    
    // Trigger change event on page load
    $('#customer_id').trigger('change');
    
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