<div class="modal-header bg-primary">
    <h5 class="modal-title">Link Payment to Purchases</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="row mb-3">
        <div class="col-md-4">
            <strong>Supplier:</strong> <?= htmlspecialchars($supplier_name) ?>
        </div>
        <div class="col-md-4">
            <strong>Total Due:</strong> 
            <span class="text-danger">₹<?= number_format($total_due_amount, 2) ?></span>
        </div>
        <div class="col-md-4">
            <strong>Payment Amount:</strong> 
            <input type="number" class="form-control form-control-sm d-inline-block" 
                   id="payment_amount" value="<?= $amount ?>" step="0.01" min="0" style="width: 100px;">
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-primary btn-sm" id="auto_link_btn">
                <i class="fas fa-link"></i> Auto Link
            </button>
        </div>
    </div>
    
    <div class="alert alert-info">
        <strong>Total Due Amount:</strong> <span id="display_total_due"><?= number_format($total_due_amount, 2) ?></span>
        | <strong>Payment Amount:</strong> <span id="display_amount"><?= number_format($amount, 2) ?></span>
        | <strong>Difference:</strong> <span id="difference_amount"><?= number_format($amount - $total_due_amount, 2) ?></span>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th width="5%">Select</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Invoice No</th>
                    <th>Total Due</th>
                    <th>Payment Amount</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($invoices as $invoice): ?>
                <tr>
                    <td><input type="checkbox" name="selected_invoices[]" value="<?= $invoice->id ?>" class="invoice_checkbox"></td>
                    <td><?= date('d-m-Y', strtotime($invoice->purchase_date)) ?></td>
                    <td>Purchase</td>
                    <td><?= htmlspecialchars($invoice->invoice_no ) ?></td>
                    <td class="due_amount text-right"><?= number_format($invoice->due_amount, 2) ?></td>
                    <td>
                        <input type="number" name="invoice_amounts[<?= $invoice->id ?>]" 
                               class="form-control form-control-sm payment_amount" 
                               step="0.01" min="0" max="<?= $invoice->due_amount ?>" 
                               value="0" data-due="<?= $invoice->due_amount ?>">
                    </td>
                    <td class="balance text-right"><?= number_format($invoice->due_amount, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="row mt-3">
        <!--<div class="col-md-6">-->
        <!--    <div class="alert alert-warning">-->
        <!--        <strong>Unused Amount:</strong> <span id="unused_amount"><?= number_format($amount, 2) ?></span>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="col-md-6 text-right">
            <strong>Total Applied:</strong> <span id="total_applied">0.00</span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="supplier_id" value="<?= (int)$supplier_id ?>">
    <input type="hidden" name="from_account" value="<?= (int)$from_account ?>">
    <input type="hidden" name="payment_mode" value="<?= (int)$payment_mode ?>">
    <input type="hidden" name="reference_no" value="<?= htmlspecialchars($reference_no) ?>">
    <input type="hidden" name="payment_date" value="<?= htmlspecialchars($payment_date) ?>">
    <input type="hidden" name="notes" value="">
    
    <!-- ADD THESE 2 LINES FOR NEFT/RTGS -->
    <input type="hidden" name="utr_number" value="<?= htmlspecialchars($utr_number ?? '') ?>">
    <input type="hidden" name="bank_name" value="<?= htmlspecialchars($bank_name ?? '') ?>">
    
    <!-- ADD THIS LINE FOR DOCUMENT -->
    <input type="hidden" name="document" value="<?= htmlspecialchars($document ?? '') ?>">
    
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
    var currentAmount = initialAmount;
    var totalDueAmount = parseFloat(<?= $total_due_amount ?>) || 0;
    
    // Set initial payment amount
    $('#payment_amount').val(initialAmount.toFixed(2));
    
    // Auto link payment - distributes payment to oldest invoices first
    $('#auto_link_btn').click(function() {
        var remainingAmount = currentAmount;
        
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
            if(remainingAmount <= 0) return false;
            
            var checkbox = $(this).find('.invoice_checkbox');
            var paymentInput = $(this).find('.payment_amount');
            var dueAmount = parseFloat(paymentInput.data('due'));
            
            var paymentAmount = Math.min(dueAmount, remainingAmount);
            
            if(paymentAmount > 0) {
                checkbox.prop('checked', true);
                paymentInput.val(paymentAmount.toFixed(2));
                remainingAmount -= paymentAmount;
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
            // Calculate maximum possible payment
            var maxPayment = Math.min(dueAmount, currentAmount);
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
    
    // Handle payment amount changes
    $('#payment_amount').on('change', function() {
        var newAmount = parseFloat($(this).val()) || 0;
        if(newAmount >= 0) {
            currentAmount = newAmount;
            updateCalculations();
        } else {
            $(this).val(currentAmount.toFixed(2));
        }
    });
    
    // Update all calculations
    function updateCalculations() {
        var totalApplied = 0;
        
        // Calculate total applied amounts
        $('.payment_amount').each(function() {
            var amount = parseFloat($(this).val()) || 0;
            totalApplied += amount;
        });
        
        // Calculate unused amount
        var unusedAmount = currentAmount - totalApplied;
        
        // Update display values
        $('#total_applied').text(totalApplied.toFixed(2));
        $('#unused_amount').text(unusedAmount.toFixed(2));
        
        $('#display_amount').text(currentAmount.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $('#display_total_due').text(totalDueAmount.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $('#difference_amount').text((currentAmount - totalDueAmount).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }
    
    // Save payment handler
    $('#done_btn').click(function() {
        console.log('=== DEBUG: Save button clicked ===');
        console.log('Document value:', $('input[name="document"]').val());
        console.log('UTR value:', $('input[name="utr_number"]').val());
        console.log('Bank value:', $('input[name="bank_name"]').val());
        console.log('Payment mode:', $('input[name="payment_mode"]').val());
        
        // Validate at least one invoice is selected
        if($('.invoice_checkbox:checked').length === 0) {
            alert('Please select at least one invoice to apply payment');
            return;
        }
        
        // ====== VALIDATION FOR NEFT/RTGS ======
        var paymentMode = $('input[name="payment_mode"]').val();
        var utrNumber = $('input[name="utr_number"]').val();
        var bankName = $('input[name="bank_name"]').val();
        
        console.log('Validation - Mode:', paymentMode, 'UTR:', utrNumber, 'Bank:', bankName);
        
        if(paymentMode == '3') { // NEFT/RTGS
            if(!utrNumber || utrNumber.trim() === '') {
                alert('UTR Number is required for NEFT/RTGS payment');
                return;
            }
            if(!bankName || bankName.trim() === '') {
                alert('Bank Name is required for NEFT/RTGS payment');
                return;
            }
        }
        // ====== END VALIDATION ======

        var formData = new FormData();
        formData.append('<?=$this->security->get_csrf_token_name()?>', '<?=$this->security->get_csrf_hash()?>');
        formData.append('supplier_id', $('input[name="supplier_id"]').val());
        formData.append('amount', currentAmount);
        formData.append('from_account', $('input[name="from_account"]').val());
        formData.append('payment_mode', $('input[name="payment_mode"]').val());
        formData.append('reference_no', $('input[name="reference_no"]').val());
        formData.append('payment_date', $('input[name="payment_date"]').val());
        formData.append('notes', $('input[name="notes"]').val());
        
        // ADD UTR AND BANK FOR NEFT/RTGS
        utrNumber = $('input[name="utr_number"]').val();
        bankName = $('input[name="bank_name"]').val();
        
        console.log('Modal JS - UTR:', utrNumber, 'Bank:', bankName, 'Mode:', $('input[name="payment_mode"]').val());

        if(utrNumber) {
            formData.append('utr_number', utrNumber);
        }
        if(bankName) {
            formData.append('bank_name', bankName);
        }
        
        // ADD DOCUMENT TO FORMDATA
        var documentValue = $('input[name="document"]').val();
        console.log('Document value being sent:', documentValue);
        if(documentValue) {
            formData.append('document', documentValue);
        }
        
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
            url: '<?= base_url("payment_out/save_linked_payment") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Check if response is valid
                if (typeof response === 'object' && response.status) {
                    if(response.status == 'success') {
                        $('#linkPaymentModal').modal('hide');
                       // window.location.href = '<?= base_url("payment_out/view") ?>/' + response.payment_id;
                                    window.location.href = '<?= base_url("payment_out") ?>';

                    } else {
                        alert(response.message || 'Error processing payment');
                        $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Save Payment');
                    }
                } else {
                    console.error('Invalid response format:', response);
                    alert('Invalid server response. Please try again.');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Save Payment');
                }
            },
          
            error: function(xhr, status, error) {
                if (status === 'parsererror') {
                    console.error('JSON Parse Error:', xhr.responseText);
                    try {
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = xhr.responseText;
                        var errorMsg = tempDiv.textContent || tempDiv.innerText;
                        alert('Server Error: ' + errorMsg.substring(0, 200));
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