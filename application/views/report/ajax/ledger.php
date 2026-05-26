<?php 
// Initialize variables
$from_date = isset($from_date) ? $from_date : date('Y-m-d');
$to_date = isset($to_date) ? $to_date : date('Y-m-d');
$transaction_details = isset($transaction_details) ? $transaction_details : array();
$opening_balance = isset($opening_balance) ? $opening_balance : 0;
$balance = $opening_balance; // Start with opening balance

// Calculate totals
$total_sale = 0;
$total_purchase = 0;
$total_expense = 0;
$total_expense_payment = 0;
$total_payment_in = 0;
$total_payment_out = 0;

foreach($transaction_details as $transaction) {
    if($transaction->display_type == 'Sale') {
        $total_sale += $transaction->sale_amount;
    } elseif($transaction->display_type == 'Purchase') {
        $total_purchase += $transaction->purchase_amount;
    } elseif($transaction->display_type == 'Expense') {
        $total_expense += $transaction->expense_amount;
    } elseif($transaction->display_type == 'Expense Payment') {
        $total_expense_payment += $transaction->expense_payment_amount;
    } elseif($transaction->display_type == 'Payment-In') {
        $total_payment_in += $transaction->payment_in_amount;
    } elseif($transaction->display_type == 'Payment-Out') {
        $total_payment_out += $transaction->payment_out_amount;
    }
}

// Get customer wallet balance if this is a customer ledger
$wallet_balance = 0;
if(isset($ledger_detail) && !empty($ledger_detail)) {
    $this->load->model('customer_model');
    $customer = $this->customer_model->get_single_record_by_ledger_id($ledger_detail->id);
    if($customer) {
        $wallet_balance = $customer->wallet_balance;
    }
}
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped sticky-header-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Txn Type</th>
                <th>Ref No.</th>
                <th>Total</th>
                <th>Received/Paid</th>
                <th>Txn Balance</th>
                <th>Running Balance</th>
            </tr>
        </thead>
        <tbody>
            <!-- Opening Balance Row -->
            
            <?php
             $opening_balance = 0; // Replace with actual calculation
            $balance = $opening_balance;
            ?>
            
            <tr>
                <td><?= date('d/m/Y', strtotime($from_date)) ?></td>
                <td>Opening Balance</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?= number_format($opening_balance, 2) ?></td>
            </tr>
            
            <?php foreach($transaction_details as $transaction): ?>
                <?php
                // Calculate transaction impact
                if($transaction->display_type == 'Sale') {
                     $balance += $transaction->sale_amount;
                    $txn_balance = $transaction->sale_amount;
                    $received_paid = 0;
                } elseif($transaction->display_type == 'Payment-In') {
                    $balance -= $transaction->payment_in_amount;
                    $txn_balance = 0;
                    $received_paid = $transaction->payment_in_amount;
                }
                elseif($transaction->display_type == 'Wallet Payment') {
                    // Wallet payments reduce the balance (since they're applied to invoices)
                    $balance -= $transaction->transaction_amount;
                    $txn_balance = 0;
                    $received_paid = $transaction->transaction_amount;
                } 
                elseif($transaction->display_type == 'Payment-Out') {
                    $balance += $transaction->payment_out_amount;
                    $txn_balance = 0;
                    $received_paid = $transaction->payment_out_amount;
                } elseif($transaction->display_type == 'Purchase') {
                    $balance -= $transaction->purchase_amount;
                    $txn_balance = $transaction->purchase_amount;
                    $received_paid = 0;
                } elseif($transaction->display_type == 'Expense') {
                    $balance -= $transaction->expense_amount;
                    $txn_balance = $transaction->expense_amount;
                    $received_paid = 0;
                } elseif($transaction->display_type == 'Expense Payment') {
                    $balance += $transaction->expense_payment_amount;
                    $txn_balance = 0;
                    $received_paid = $transaction->expense_payment_amount;
                } else {
                    $txn_balance = 0;
                    $received_paid = 0;
                }
                ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($transaction->transaction_date)) ?></td>
                    <td><?= $transaction->display_type ?></td>
                    <td>
                        <?php if($transaction->display_type == 'Sale'): ?>
                            <a href="<?= base_url('sale/view/'.base64_encode($transaction->entry_id)) ?>" class="btn btn-link" title="View Sale">
                                <?= $transaction->reference_no ?>
                            </a>
                        <?php elseif($transaction->display_type == 'Payment-In'): ?>
                            <a href="<?= base_url('payment_in/view/'.base64_encode($transaction->entry_id)) ?>" class="btn btn-link" title="View Payment">
                                <?= $transaction->reference_no ?>
                            </a>
                        <?php elseif($transaction->display_type == 'Payment-Out'): ?>
                            <a href="<?= base_url('payment_out/view/'.base64_encode($transaction->entry_id)) ?>" class="btn btn-link" title="View Payment">
                                <?= $transaction->reference_no ?>
                            </a>
                        <?php elseif($transaction->display_type == 'Purchase'): ?>
                            <a href="<?= base_url('purchase/view/'.base64_encode($transaction->entry_id)) ?>" class="btn btn-link" title="View Purchase">
                                <?= $transaction->reference_no ?>
                            </a>
                        <?php elseif($transaction->display_type == 'Expense'): ?>
                            <a href="<?= base_url('expense/view/'.base64_encode($transaction->entry_id)) ?>" class="btn btn-link" title="View Expense">
                                <?= $transaction->reference_no ?>
                            </a>
                        <?php elseif($transaction->display_type == 'Expense Payment'): ?>
                            <!--<a href="<?= base_url('expense/payment_view/'.base64_encode($transaction->entry_id)) ?>" class="btn btn-link" title="View Expense Payment">-->
                                <?= $transaction->reference_no ?>
                            <!--</a>-->
                        <?php else: ?>
                            <?= $transaction->reference_no ?>
                        <?php endif; ?>
                    </td>
                    <td><?= number_format($transaction->transaction_amount, 2) ?></td>
                    <td><?= number_format($received_paid, 2) ?></td>
                    <td><?= ($txn_balance != 0) ? number_format($txn_balance, 2) : '' ?></td>
                    <td><?= number_format($balance, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Total Row -->
            <tr class="font-weight-bold">
                <td colspan="6" class="text-right">Closing Balance:</td>
                <td><?= number_format($balance, 2) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Wallet Balance Section (only shown if this is a customer ledger) -->
<?php if($wallet_balance != 0): ?>
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Customer Advance Balance</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Current Advance Balance</span>
                        <span class="info-box-number"><?= number_format($wallet_balance, 2) ?></span>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Party Statement Summary -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Party Statement Summary</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Sales</span>
                        <span class="info-box-number"><?= number_format($total_sale, 2) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Purchases</span>
                        <span class="info-box-number"><?= number_format($total_purchase, 2) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Expenses</span>
                        <span class="info-box-number"><?= number_format($total_expense, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-download"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Money-In</span>
                        <span class="info-box-number"><?= number_format($total_payment_in, 2) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-secondary">
                    <span class="info-box-icon"><i class="fas fa-upload"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Money-Out</span>
                        <span class="info-box-number"><?= number_format($total_payment_out + $total_expense_payment, 2) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Receivable</span>
                        <span class="info-box-number"><?= number_format($balance, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Wallet Transactions Summary -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Wallet Transactions</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Current Wallet Balance</span>
                        <span class="info-box-number">₹<?= number_format($wallet_balance, 2) ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            Available for future payments
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Wallet Usage</span>
                        <span class="info-box-number">₹<?= number_format($total_wallet_payments, 2) ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            Used for payments
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Wallet Deposits</span>
                        <span class="info-box-number">₹<?= number_format($total_wallet_deposits, 2) ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            Added to wallet
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(!empty($wallet_transactions)): ?>
        <div class="row mt-4">
            <div class="col-md-12">
                <h4>Wallet Transaction History</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reference No</th>
                                <th class="text-right">Amount</th>
                                <th>Description</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $running_wallet_balance = 0;
                            foreach($wallet_transactions as $txn): 
                                if($txn->type == 'WALLET_ADD') {
                                    $running_wallet_balance += $txn->amount;
                                    $amount_class = 'text-success';
                                    $sign = '+';
                                } else {
                                    $running_wallet_balance -= $txn->amount;
                                    $amount_class = 'text-danger';
                                    $sign = '-';
                                }
                            ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($txn->voucher_date)) ?></td>
                                <td><?= $txn->display_type ?></td>
                                <td>
                                    <?php if(!empty($txn->reference_no)): ?>
                                        <?= $txn->reference_no ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td class="text-right <?= $amount_class ?>">
                                    <?= $sign ?> ₹<?= number_format($txn->amount, 2) ?>
                                </td>
                                <td>
                                    <?php if($txn->type == 'WALLET_ADD'): ?>
                                        Amount deposited to wallet
                                    <?php elseif($txn->type == 'WALLET_PAYMENT'): ?>
                                        Amount used for payment (Invoice: <?= $txn->reference_no ?>)
                                    <?php elseif($txn->type == 'WALLET_DEDUCT'): ?>
                                        Amount deducted from wallet
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    ₹<?= number_format($running_wallet_balance, 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="5" class="text-right"><strong>Current Wallet Balance:</strong></td>
                                <td class="text-right"><strong>₹<?= number_format($wallet_balance, 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>