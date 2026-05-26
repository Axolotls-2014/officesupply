<?php 
  $this->load->view('layout/header');
  ?>
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
    .signature {
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
    }
    .invoice-details {
        margin-top: 20px;
    }
    .invoice-details th {
        background-color: #cfc6f6;
    }
     @media print {
        .no-print, 
        .main-sidebar, 
        .main-header, 
        .content-header, 
        .breadcrumb, 
        footer {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        .receipt-box {
            border: none !important;
            margin: 0 auto !important;
            padding: 0 !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .receipt-table th {
            background-color: #cfc6f6 !important;
            -webkit-print-color-adjust: exact;
        }
    }
     .receipt-box {
        max-width: 900px;
        margin: 0px auto; /* Changed from 20px auto to 0px auto */
        border: 1px solid #333;
        padding: 20px;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        color: #000;
        background-color: #fff;
    }
    
    /* Reduce padding in the AdminLTE header */
    .content-header {
        padding: 10px 0.5rem !important;
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 5px; /* Reduced from 10px */
    }
</style>
<div class="wrapper">
  <div class="content-wrapper">
    <!--<section class="content-header">-->
    <!--  <div class="row mb-2">-->
    <!--    <div class="col-sm-12">-->
    <!--      <ol class="breadcrumb breadcrumb-custom float-sm-left">-->
    <!--        <li class="breadcrumb-item"><a href="<?=base_url('auth')?>">Home</a></li>-->
    <!--        <li class="breadcrumb-item">Payments In</li>-->
    <!--      </ol>-->
    <!--    </div>-->
      <!--  <div class="col-sm-6">-->
      <!--<a href="javascript:history.back()" class="btn btn-secondary btn-sm float-right" title="Go Back" style="background-color: #6c757d; border-color: #6c757d; color: white;">-->
      <!--  <i class="fas fa-arrow-left"></i> Back To List-->
      <!--</a>-->
    <!--   <div class="col-sm-6">-->
    <!--      <div class="float-right no-print">-->
            <!-- Print Button -->
    <!--        <button onclick="window.print()" class="btn btn-primary btn-sm mr-1">-->
    <!--          <i class="fas fa-print"></i> Print-->
    <!--        </button>-->
            
            <!-- Download PDF Button -->
    <!--        <a href="<?= base_url('payment_in/generate_pdf/'.base64_encode($payment_in->id)) ?>" class="btn btn-danger btn-sm mr-1">-->
    <!--          <i class="far fa-file-pdf"></i> Download PDF-->
    <!--        </a>-->
    
            <!-- Back Button -->
    <!--        <a href="javascript:history.back()" class="btn btn-secondary btn-sm" title="Go Back">-->
    <!--          <i class="fas fa-arrow-left"></i> Back To List-->
    <!--        </a>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!--  </div>-->
    <!--</section>-->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <!-- Breadcrumbs on the left (Half width) -->
          <div class="col-sm-6">
            <ol class="breadcrumb breadcrumb-custom float-sm-left">
              <li class="breadcrumb-item"><a href="<?=base_url('auth')?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?=base_url('payment_in')?>">Payments In</a></li>
              <li class="breadcrumb-item active">View</li>
            </ol>
          </div>
          
          <!-- Buttons on the right (Half width) -->
          <div class="col-sm-6">
            <div class="float-right no-print">
              <button onclick="window.print()" class="btn btn-primary btn-sm mr-1">
                <i class="fas fa-print"></i> Print
              </button>
              
              <a href="<?= base_url('payment_in/generate_pdf/'.base64_encode($payment_in->id)) ?>" class="btn btn-danger btn-sm mr-1">
                <i class="far fa-file-pdf"></i> Download PDF
              </a>
      
              <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back To List
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
         <div class="col-12">
            <div class="receipt-box">
           <div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center; position: relative;">

                <div style="display: flex; align-items: center; justify-content: center; position: relative;">
                    <h4 style="font-size: 22px; font-weight: bold; margin: 0;">PAYMENT RECEIPT</h4>
            
                    <?php if(!empty($company_setting->logo)): ?>
                    <div style="position: absolute; right: 0;">
                        <img src="<?= base_url('/assets/images/0/' . $company_setting->logo); ?>" 
                             style="width: 130px; height: auto; margin-top: -10px;">
                    </div>
                    <?php endif; ?>
                </div>
            
                <hr style="margin: 8px 0 12px 0;">
            
                <!-- Company Info -->
                <h3 style="margin: 6px 0; font-size: 18px;">  <?= htmlspecialchars($company_setting->company_name); ?></h3>
            
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
                    <td style = "text-align:right">
                        Receipt No. : <?= $payment_in->reference_no ?><br>
                        Date : <?= date('d-m-Y', strtotime($payment_in->payment_date)) ?><br>
                        <!--Payment Mode: <?= $payment_in->payment_mode ?><br>-->
                        <!--<?php if(!empty($payment_in->notes)): ?>-->
                        <!--    Notes: <?= $payment_in->notes ?><br>-->
                        <!--<?php endif; ?>-->
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
                    <!-- Replace the Amounts section in the receipt-table -->
                    <td>
                        <?php if($payment_in->is_wallet_payment): ?>
                            Added to Wallet<br>
                            <strong><?= $this->session->userdata('currency_symbol') . number_format($payment_in->amount, 2) ?></strong>
                        <?php else: ?>
                            Total Paid<br>
                            <strong><?= $this->session->userdata('currency_symbol') . number_format($payment_in->amount, 2) ?></strong>
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
<!-- Add this after the "Applied to Invoices" section -->
<div class="row mt-3">
    <div class="col-md-6">
        <div class="receipt-table" style="padding: 10px; border: 1px solid #ccc;">
            <strong>Payment Summary:</strong><br>

            <?php if($payment_in->is_wallet_payment): ?>
                <span>Added to Wallet: <?= $this->session->userdata('currency_symbol') . number_format($payment_in->amount, 2) ?></span>
            <?php else: ?>
                <?php 
                $total_wallet_used = 0;
                $total_cash_received = 0;
                foreach($distributions as $dist) {
                    $total_wallet_used += $dist->wallet_amount;
                    $total_cash_received += $dist->cash_amount;
                }

                // Logic to define the label based on Payment Mode
                $mode = $payment_in->payment_mode;
                if($mode == '0' || $mode == 'Cash') {
                    $mode_label = "Cash Received";
                } elseif($mode == '2' || $mode == 'Cheque') {
                    $mode_label = "Cheque Received";
                } elseif($mode == '3' || $mode == 'NEFT / Online Transfer') {
                    $mode_label = "NEFT Received";
                } else {
                    $mode_label = "Amount Received";
                }
                ?>

                <span>From <?= $mode_label ?>: <?= $this->session->userdata('currency_symbol') . number_format($total_cash_received, 2) ?></span><br>
                <span>From Advance: <?= $this->session->userdata('currency_symbol') . number_format($total_wallet_used, 2) ?></span><br>
                <span><strong>Total Payment:</strong> <?= $this->session->userdata('currency_symbol') . number_format($payment_in->amount, 2) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
        
          <!--  <div class="signature">
                <div><strong>For : OFFICE SUPPLY SOLUTIONS</strong></div>
                <div><strong>Authorise Signature</strong></div>
            </div>-->
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
        </div>
        </div>
      </div>
      </section>
      
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>





