<div class="modal-header bg-primary">
    <h5 class="modal-title">Link Payment to Transactions</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row mb-3">
        <div class="col-md-4">
            <strong>Customer:</strong> <?= htmlspecialchars($customer_name) ?>
        </div>
        <div class="col-md-4">
            <strong>Advance Balance:</strong> 
            <span class="text-success">₹<?= number_format($wallet_balance, 2) ?></span>
        </div>
        <div class="col-md-4">
            <strong>Received Amount:</strong> 
            <input type="number" class="form-control form-control-sm d-inline-block payment-amount-input" 
                   id="received_amount" value="<?= $amount ?>" step="0.01" min="0" style="width: 100px;">
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-primary btn-sm" id="auto_link_btn">
                <i class="fas fa-link"></i> Auto Link
            </button>
        </div>
    </div>
    
    <div class="alert alert-info">
        <strong>Total Due Amount:</strong> <span id="display_total_due">₹<?= number_format($total_due_amount, 2) ?></span>
        | <strong>Wallet Balance:</strong> <span id="display_wallet">₹<?= number_format($wallet_balance, 2) ?></span>
        | <strong>Payment Amount:</strong> <span id="display_amount">₹<?= number_format($amount, 2) ?></span>
        | <strong>Difference:</strong> <span id="difference_amount">₹<?= number_format(($wallet_balance + $amount) - $total_due_amount, 2) ?></span>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th width="5%">Select</th>
                    <th>Date</th>
                    <th>Invoice No</th>
                    <th class="text-right">Invoice Amount</th>
                    <th class="text-right">Paid Amount</th>
                    <th class="text-right">Due Amount</th>
                    <th class="text-right">Payment Amount</th>
                    <th class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($invoices as $invoice): 
                    $paid_amount = $invoice->total - $invoice->due_amount;
                ?>
                <tr>
                    <td><input type="checkbox" name="selected_invoices[]" value="<?= $invoice->id ?>" class="invoice_checkbox"></td>
                    <td><?= date('d-m-Y', strtotime($invoice->invoice_date)) ?></td>
                    <td><?= htmlspecialchars($invoice->reference_no) ?></td>
                    <td class="text-right">₹<?= number_format($invoice->total, 2) ?></td>
                    <td class="text-right">₹<?= number_format($paid_amount, 2) ?></td>
                    <td class="text-right">₹<?= number_format($invoice->due_amount, 2) ?></td>
                    <td>
                        <input type="number" name="invoice_amounts[<?= $invoice->id ?>]" 
                               class="form-control form-control-sm payment_amount" 
                               step="0.01" min="0" max="<?= $invoice->due_amount ?>" 
                               value="0" data-due="<?= $invoice->due_amount ?>">
                    </td>
                    <td class="balance text-right">₹<?= number_format($invoice->due_amount, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="alert alert-warning">
                <strong>Unused Amount:</strong> <span id="unused_amount">₹<?= number_format($amount, 2) ?></span>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <strong>Total Applied:</strong> <span id="total_applied">₹0.00</span>
            | <strong>Wallet Used:</strong> <span id="wallet_used">₹0.00</span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="customer_id" value="<?= (int)$customer_id ?>">
    <input type="hidden" name="to_account" value="<?= (int)$to_account ?>">
    <input type="hidden" name="payment_mode" value="<?= (int)$payment_mode ?>">
    <input type="hidden" name="reference_no" value="<?= htmlspecialchars($reference_no) ?>">
    <input type="hidden" name="payment_date" value="<?= htmlspecialchars($payment_date) ?>">
    <input type="hidden" name="notes" value="">
    
    <button type="button" class="btn btn-success" id="done_btn">
        <i class="fas fa-check"></i> Save Payment
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        <i class="fas fa-times"></i> Cancel
    </button>
</div>

<script>
   $(document).ready(function() {
    // Initialize variables
    var initialAmount = parseFloat("<?= $amount ?>") || 0;
    var initialWalletBalance = parseFloat("<?= $wallet_balance ?>") || 0;
    var currentAmount = initialAmount;
    var walletBalance = initialWalletBalance;
    var totalDueAmount = parseFloat(<?= $total_due_amount ?>) || 0;
    
    // Set initial received amount
    $('#received_amount').val(initialAmount.toFixed(2));
    
    // Auto link payment - uses wallet first, then received amount
    $('#auto_link_btn').click(function() {
        var remainingAmount = currentAmount;
        var remainingWallet = walletBalance;
        
        // Reset all checkboxes and amounts
        $('.invoice_checkbox').prop('checked', false).trigger('change');
        $('.payment_amount').val('0').trigger('input');
        
        // Sort by oldest invoices first
        var invoiceRows = $('.invoice_checkbox').closest('tr').get();
        invoiceRows.sort(function(a, b) {
            var dateA = new Date($(a).find('td').eq(1).text().split('-').reverse().join('-'));
            var dateB = new Date($(b).find('td').eq(1).text().split('-').reverse().join('-'));
            return dateA - dateB;
        });
        
        // Process each invoice
        $(invoiceRows).each(function() {
            if(remainingWallet <= 0 && remainingAmount <= 0) return false;
            
            var checkbox = $(this).find('.invoice_checkbox');
            var paymentInput = $(this).find('.payment_amount');
            var dueAmount = parseFloat(paymentInput.data('due'));
            
            // First use wallet balance
            var walletPayment = Math.min(dueAmount, remainingWallet);
            var cashPayment = 0;
            
            // Then use received amount if still due
            if(dueAmount > walletPayment && remainingAmount > 0) {
                cashPayment = Math.min(dueAmount - walletPayment, remainingAmount);
                remainingAmount -= cashPayment;
            }
            
            if(walletPayment > 0 || cashPayment > 0) {
                checkbox.prop('checked', true);
                paymentInput.val((walletPayment + cashPayment).toFixed(2));
                remainingWallet -= walletPayment;
                paymentInput.trigger('input');
            }
        });
        
        updateCalculations();
    });
    
    // Handle checkbox changes
    $(document).on('change', '.invoice_checkbox', function() {
        var row = $(this).closest('tr');
        var paymentInput = row.find('.payment_amount');
        var dueAmount = parseFloat(paymentInput.data('due'));
        
        if($(this).is(':checked')) {
            // Calculate maximum possible payment (wallet + remaining received amount)
            var maxPayment = Math.min(dueAmount, walletBalance + currentAmount);
            paymentInput.val(maxPayment.toFixed(2)).trigger('input');
        } else {
            paymentInput.val('0').trigger('input');
        }
    });
    
    // Handle payment amount changes
    $(document).on('input', '.payment_amount', function() {
        var row = $(this).closest('tr');
        var paymentAmount = parseFloat($(this).val()) || 0;
        var dueAmount = parseFloat($(this).data('due'));
        
        // Validate payment amount
        if(paymentAmount > dueAmount) {
            paymentAmount = dueAmount;
            $(this).val(paymentAmount.toFixed(2));
        }
        
        // Update balance display
        row.find('.balance').text((dueAmount - paymentAmount).toFixed(2));
        updateCalculations();
    });
    
    // Handle received amount changes
    $('#received_amount').on('change', function() {
        var newAmount = parseFloat($(this).val()) || 0;
        if(newAmount >= 0) {
            currentAmount = newAmount;
            updateCalculations();
        } else {
            $(this).val(currentAmount.toFixed(2));
        }
    });
    
    // Update all calculations
    // Update all calculations
function updateCalculations() {
    var totalApplied = 0;
    var walletUsed = 0;
    var cashApplied = 0;
    
    // Calculate total applied amounts
    $('.payment_amount').each(function() {
        var amount = parseFloat($(this).val()) || 0;
        totalApplied += amount;
        
        // Determine how much comes from wallet vs cash
        var remainingWallet = initialWalletBalance - walletUsed;
        var walletPortion = Math.min(amount, remainingWallet);
        walletUsed += walletPortion;
        cashApplied += (amount - walletPortion);
    });
    
    // Calculate unused amounts
    var unusedWallet = initialWalletBalance - walletUsed;
    var unusedReceived = currentAmount - cashApplied;
    var totalUnusedAmount = unusedWallet + unusedReceived;
    
    // Update display values
    $('#total_applied').text(totalApplied.toFixed(2));
    $('#wallet_used').text(walletUsed.toFixed(2));
    $('#unused_amount').text(totalUnusedAmount.toFixed(2));
    
    $('#display_amount').text(currentAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    $('#display_wallet').text(initialWalletBalance.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    $('#display_total_due').text(totalDueAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    $('#difference_amount').text((initialWalletBalance + currentAmount - totalDueAmount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
}
    
    // Save payment handler
    $('#done_btn').click(function() {
        // Validate at least one invoice is selected
        if($('.invoice_checkbox:checked').length === 0) {
            alert('Please select at least one invoice to apply payment');
            return;
        }

        var formData = new FormData();
        formData.append('<?=$this->security->get_csrf_token_name()?>', '<?=$this->security->get_csrf_hash()?>');
        formData.append('customer_id', $('input[name="customer_id"]').val());
        formData.append('amount', currentAmount);
        formData.append('to_account', $('input[name="to_account"]').val());
        formData.append('payment_mode', $('input[name="payment_mode"]').val());
        formData.append('reference_no', $('input[name="reference_no"]').val());
        formData.append('payment_date', $('input[name="payment_date"]').val());
        formData.append('notes', $('input[name="notes"]').val());

        // Add selected invoices and amounts
        $('.invoice_checkbox:checked').each(function() {
            var invoiceId = $(this).val();
            var amount = parseFloat($(this).closest('tr').find('.payment_amount').val()) || 0;
            formData.append('selected_invoices[]', invoiceId);
            formData.append('invoice_amounts['+invoiceId+']', amount);
        });

        // Show loading state
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        // Submit via AJAX
$.ajax({
        url: '<?= base_url("payment_in/save_linked_payment") ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json', // Expect JSON response
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Identify as AJAX request
        },
        success: function(response) {
            // Check if response is valid
            if (typeof response === 'object' && response.status) {
                if(response.status == 'success') {
                    alert(response.message);
                    $('#linkPaymentModal').modal('hide');
                    //window.location.href = '<?= base_url("payment_in/view") ?>/' + response.payment_id;
                                window.location.href = '<?= base_url("payment_in") ?>';

                } else {
                    alert(response.message || 'Error processing payment');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Save Payment');
                }
            } else {
                // Handle invalid JSON response
                console.error('Invalid response format:', response);
                alert('Invalid server response. Please try again.');
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Save Payment');
            }
        },
   error: function(xhr, status, error) {
    // Handle parse error specifically
    if (status === 'parsererror') {
        console.error('JSON Parse Error:', xhr.responseText);
        try {
            // Try to extract error message from HTML response
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = xhr.responseText;
            var errorMsg = tempDiv.textContent || tempDiv.innerText;
            alert('Server Error: ' + errorMsg.substring(0, 200)); // Show first 200 chars
        } catch (e) {
            alert('Error processing payment. Please check console for details.');
        }
    } else {
        console.error('AJAX Error:', status, error, xhr.responseText);
        alert('Error saving payment. Please check console for details.');
    }
    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Save Payment');
}
    });
    });
    
    // Initialize calculations
    updateCalculations();
});
</script>