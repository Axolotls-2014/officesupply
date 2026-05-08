<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        .receipt-box {
            max-width: 900px;
            margin: 20px auto;
            border: 1px solid #333;
            padding: 20px;
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #000;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .receipt-header h2 {
            margin: 0;
            font-weight: bold;
        }
        .receipt-header small {
            display: block;
            font-size: 13px;
            margin-top: 3px;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .receipt-table th,
        .receipt-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            vertical-align: top;
        }
        .receipt-table th {
            background-color: #cfc6f6;
            font-weight: bold;
            text-align: left;
        }
        .receipt-table .subheading {
            background-color: #cfc6f6;
            text-align: center;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
       /* .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }*/
        .invoice-details {
            margin-top: 20px;
        }
        .invoice-details th {
            background-color: #cfc6f6;
        }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center; position: relative;">
            <!-- Logo on SAME LINE as "PAYMENT RECEIPT" -->
            <div style="display: flex; align-items: center; justify-content: center; position: relative; height: 40px;">
                <h4 style="font-size: 22px; font-weight: bold; margin: 0;">PAYMENT RECEIPT</h4>
        
                <?php if(!empty($company_setting->logo)): 
                    $image_path = './assets/images/0/' . $company_setting->logo;
                    if (file_exists($image_path)) {
                        $image_data = file_get_contents($image_path);
                        $base64_data = base64_encode($image_data);
                ?>
                <div style="position: absolute; right: 0; top: -10px;">
                    <img src="data:image/png;base64,<?=$base64_data?>" style="width: 130px; height: auto;">
                </div>
                <?php } endif; ?>
            </div>
        
            <hr style="margin: 8px 0 12px 0;">
        
            <!-- Company Info -->
            <h3 style="margin: 6px 0; font-size: 18px;"><?= htmlspecialchars($company_setting->company_name); ?></h3>
        
            <div>
                <?= htmlspecialchars($company_setting->address_line1); ?><br>
            </div>
            
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <?php if(!empty($company_setting->gstin)): ?>
                <div><strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin); ?></div>
                <?php endif; ?>
                <div><strong>State:</strong> <?= htmlspecialchars($company_setting->state_name); ?></div>
            </div>
        </div>

        <table class="receipt-table">
            <tr>
                <th width="50%" class="text-center">Received From</th>
                <th width="50%" class="text-center">Receipt Details</th>
            </tr>
            <tr>
                <td>
                    <strong><?php if(!empty($customer_detail->customer_company_name)): ?>
                        <?= $customer_detail->customer_company_name ?><br>
                    <?php endif; ?></strong><br>
                    
                    <?php if(!empty($customer_detail->customer_name)): ?>
                        <?= $customer_detail->customer_name ?><br>
                    <?php endif; ?>
                    
                    <?php if(!empty($customer_detail->address)): ?>
                        <?= $customer_detail->address ?><br><br>
                    <?php endif; ?>
                    
                    <?php if(!empty($customer_detail->gstin)): ?>
                        GSTIN: <?= $customer_detail->gstin ?><br><br>
                    <?php else: ?>
                        GSTIN: Not Provided<br>
                    <?php endif; ?>
                    
                    <?php if(!empty($customer_detail->state_name)): ?>
                        State: <?= $customer_detail->state_name ?>
                    <?php endif; ?>
                </td>
                <td style="text-align:right">
                    Receipt No.: <?= $transaction->reference_no ?><br>
                    Date: <?= date('d-m-Y', strtotime($transaction->voucher_date)) ?><br>
                    
                    <?php 
                    $payment_modes = [
                        0 => 'Cash',
                        1 => 'Credit Card',
                        2 => 'Cheque',
                        3 => 'NEFT / RTGS'
                    ];
                    $payment_mode_value = $transaction->mode;
                    $payment_mode_text = isset($payment_modes[$payment_mode_value]) ? $payment_modes[$payment_mode_value] : '';
                    ?>
                    Payment Mode: <?= $payment_mode_text ?><br>
                    
                    <?php if(!empty($transaction->notes)): ?>
                        Notes: <?= $transaction->notes ?><br>
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="subheading">
                <td>Amount in words</td>
                <td>Amounts</td>
            </tr>
            <tr>
                <td style="text-align:center">
                    <?= ucwords($this->numbertowords->convert_number((int)$transaction->amount)) ?> Rupees only
                </td>
                <td>
                    <?php if(!empty($transaction->is_wallet_payment) && $transaction->is_wallet_payment): ?>
                        Added to Wallet<br>
                        <strong><?= $this->session->userdata('currency_symbol') . number_format($transaction->amount, 2) ?></strong>
                    <?php else: ?>
                        Total Paid<br>
                        <strong><?= $this->session->userdata('currency_symbol') . number_format($transaction->amount, 2) ?></strong>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <?php if(!empty($distributions)): ?>
        <div class="invoice-details">
            <h4>Applied to Invoices:</h4>
            <table class="receipt-table">
                <tr>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th class="text-right">Invoice Amount</th>
                    <th class="text-right">Previously Paid</th>
                    <th class="text-right">Due Amount</th>
                    <th class="text-right">This Payment</th>
                    <th class="text-right">Balance</th>
                </tr>
                
                <?php 
                $total_invoice_amount = 0;
                $total_previously_paid = 0;
                $total_amount_paid = 0;
                $total_balance = 0;
                $total_wallet_used = 0;
                $total_cash_received = 0;
                
                foreach($distributions as $distribution):
                    $invoice_amount = $distribution->invoice_amount;
                    $this_payment = $distribution->amount;
                    $previously_paid = $invoice_amount - $distribution->due_amount_before;
                    $balance = $invoice_amount - ($previously_paid + $this_payment);
                    
                    $total_invoice_amount += $invoice_amount;
                    $total_previously_paid += $previously_paid;
                    $total_amount_paid += $this_payment;
                    $total_balance += $balance;
                    
                    // Calculate wallet and cash
                    $total_wallet_used += $distribution->wallet_amount ?? 0;
                    $total_cash_received += $distribution->cash_amount ?? 0;
                ?>
                <tr>
                    <td><?= $distribution->invoice_no ?></td>
                    <td><?= date('d-m-Y', strtotime($distribution->invoice_date)) ?></td>
                    <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format($invoice_amount, 2) ?></td>
                    <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format($previously_paid, 2) ?></td>
                    <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format($distribution->due_amount_before, 2) ?></td>
                    <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format($this_payment, 2) ?></td>
                    <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format($balance, 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total</strong></td>
                    <td class="text-right"><strong><?= $this->session->userdata('currency_symbol') . number_format($total_invoice_amount, 2) ?></strong></td>
                    <td class="text-right"><strong><?= $this->session->userdata('currency_symbol') . number_format($total_previously_paid, 2) ?></strong></td>
                    <td class="text-right"><strong><?= $this->session->userdata('currency_symbol') . number_format(($total_invoice_amount - $total_previously_paid), 2) ?></strong></td>
                    <td class="text-right"><strong><?= $this->session->userdata('currency_symbol') . number_format($total_amount_paid, 2) ?></strong></td>
                    <td class="text-right"><strong><?= $this->session->userdata('currency_symbol') . number_format($total_balance, 2) ?></strong></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Payment Summary -->
        <div style="margin-top: 20px;">
            <strong>Payment Summary:</strong><br>
            <?php if(!empty($transaction->is_wallet_payment) && $transaction->is_wallet_payment): ?>
                <span>Added to Wallet: <?= $this->session->userdata('currency_symbol') . number_format($transaction->amount, 2) ?></span>
            <?php else: ?>
                <?php if($total_cash_received > 0): ?>
                <span>From Cash Received: <?= $this->session->userdata('currency_symbol') . number_format($total_cash_received, 2) ?></span><br>
                <?php endif; ?>
                <?php if($total_wallet_used > 0): ?>
                <span>From Advance: <?= $this->session->userdata('currency_symbol') . number_format($total_wallet_used, 2) ?></span><br>
                <?php endif; ?>
                <span>Total Payment: <?= $this->session->userdata('currency_symbol') . number_format($transaction->amount, 2) ?></span>
            <?php endif; ?>
        </div>
        
      
<!--<table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
    <tr style="height: 80px;">
        <td style="width: 50%; text-align: left; vertical-align: bottom; border: none; padding: 0;">
            <strong>For: <?= $company_setting->company_name ?></strong>
        </td>

        <td style="width: 50%; text-align: right; vertical-align: bottom; border: none; padding: 0;">
            <?php if(!empty($company_setting->signature)): 
                $signature_path = './assets/images/0/' . $company_setting->signature;
                if (file_exists($signature_path)) {
                    $signature_data = file_get_contents($signature_path);
                    $base64_data = base64_encode($signature_data);
            ?>
            <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px; height: auto; display: inline-block; margin-bottom: 4px;"><br>
            <?php } endif; ?>
            <strong>Authorized Signature</strong>
        </td>
    </tr>
</table>-->

<!-- Signature Section - Right Aligned -->
<div style="margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px; text-align: right;">
    <div style="margin-bottom: 15px;">
        <strong>For : <?= !empty($company_setting->company_name) ? htmlspecialchars($company_setting->company_name) : '_________________________'; ?></strong>
    </div>
    <div style="display: inline-block; text-align: center;">
        <?php 
        // Check if signature exists and display it
        if (!empty($company_setting->signature)) {
            $signature_path = './assets/images/' . $company_setting->cid . '/' . $company_setting->signature;
            if (file_exists($signature_path)) {
                $signature_data = file_get_contents($signature_path);
                $base64_data = base64_encode($signature_data);
                echo '<img src="data:image/png;base64,' . $base64_data . '" style="width: 120px; margin-bottom: 5px;"><br>';
            } else {
                echo '<br><br><br>';
            }
        } else {
            echo '<br><br><br>';
        }
        ?>
        <div><strong>Authorised Signature</strong></div>
    </div>
</div>
</body>
</html>