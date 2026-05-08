<?php
// application/views/payment_in/pdf_receipt.php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt - <?= $payment_in->reference_no ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }
        .receipt-box {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .receipt-header h2 {
            margin: 0;
            font-weight: bold;
            font-size: 20px;
        }
        .receipt-header small {
            display: block;
            font-size: 12px;
            margin-top: 5px;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .receipt-table th,
        .receipt-table td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
        }
        .receipt-table th {
            background-color: #cfc6f6;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .invoice-details {
            margin-top: 20px;
        }
        .subheading {
            background-color: #cfc6f6;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="receipt-header">
            <h4><strong>Payment Receipt</strong></h4>
            <h2>OFFICE SUPPLY SOLUTIONS</h2>
            <small>
                KUNDA COLONY HOUSE NO 199 VILLAGE BHANGEL NOIDA-201304<br>
                Phone no.: 9911230730 &nbsp;&nbsp; Email: SUPPLYOFFICESOLUTIONS@GMAIL.COM<br>
                GSTIN: 09DATPM2313C1ZR, State: 09-Uttar Pradesh
            </small>
        </div>

        <table class="receipt-table">
            <tr>
                <th width="50%" class="text-center">Received From</th>
                <th width="50%" class="text-center">Receipt Details</th>
            </tr>
            <tr>
                <td>
                    <strong><?php if(!empty($payment_in->customer_company_name)): ?>
                        <?= $payment_in->customer_company_name ?><br>
                    <?php endif; ?></strong><br>
                    
                    <?php if(!empty($payment_in->customer_name)): ?>
                        <?= $payment_in->customer_name ?><br>
                    <?php endif; ?>
                    
                    <?php if(!empty($payment_in->customer_address)): ?>
                        <?= $payment_in->customer_address ?><br><br>
                    <?php endif; ?>

                    <?php if(!empty($payment_in->customer_gstin)): ?>
                        GSTIN: <?= $payment_in->customer_gstin ?><br><br>
                    <?php else: ?>
                        GSTIN: Not Provided<br>
                    <?php endif; ?>
                    <?php if(!empty($payment_in->customer_state_name)): ?>
                        State: <?= $payment_in->customer_state_code ?>-<?= $payment_in->customer_state_name ?>
                    <?php endif; ?>
                </td>
                <td style="text-align:right">
                    Receipt No. : <?= $payment_in->reference_no ?><br>
                    Date : <?= date('d-m-Y', strtotime($payment_in->payment_date)) ?><br>
                </td>
            </tr>
            <tr class="subheading">
                <td>Amount in words</td>
                <td>Amounts</td>
            </tr>
            <tr>
                <td style="text-align:center">
                    <?= ucwords($this->numbertowords->convert_number((int)$payment_in->amount)) ?> Rupees only
                </td>
                <td>
                    Received<br>
                    <strong><?= $this->session->userdata('currency_symbol') . number_format($payment_in->amount, 2) ?></strong>
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
                
                foreach($distributions as $distribution):
                    $invoice_amount = $distribution->invoice_amount;
                    $this_payment = $distribution->amount;
                    $previously_paid = $invoice_amount - $distribution->due_amount_before;
                    $balance = $invoice_amount - ($previously_paid + $this_payment);
                    
                    $total_invoice_amount += $invoice_amount;
                    $total_previously_paid += $previously_paid;
                    $total_amount_paid += $this_payment;
                    $total_balance += $balance;
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

        <div class="signature">
            <div><strong>For : OFFICE SUPPLY SOLUTIONS</strong></div>
            <div><strong>Authorise Signature</strong></div>
        </div>
    </div>
</body>
</html>