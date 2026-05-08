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
    .download-btn-container {
        text-align: center;
        margin: 20px 0;
    }
</style>
<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?=base_url('auth')?>">Home</a></li>
            <li class="breadcrumb-item">Payments In</li>
          </ol>
        </div>
        <div class="col-sm-6">
      <a href="javascript:history.back()" class="btn btn-secondary btn-sm float-right" title="Go Back" style="background-color: #6c757d; border-color: #6c757d; color: white;">
        <i class="fas fa-arrow-left"></i> Back To List
      </a>
    </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
         <div class="col-12">
             
              <?php 
      // Check if document exists and display it
      if (!empty($payment_out->document)):
          $company_settings = $this->company_settings_model->get_company_records();
          $cid = $company_settings->cid;
          $documents = explode(',', $payment_out->document);
      ?>
      <div class="card no-print mb-3">
          <div class="card-header">
              <h3 class="card-title">Attached Documents</h3>
          </div>
          <div class="card-body">
              <div class="row">
                  <div class="col-12">
                      <?php foreach ($documents as $document): 
                          $document = trim($document);
                          if(!empty($document)):
                              $documentPath = base_url('assets/documents/'.$cid.'/payment_out/' . $document);
                      ?>
                      <div class="btn-group attached-file" style="margin-top:5px; margin-left:5px;margin-right:5px;">
                          <a href="<?= $documentPath ?>" class="btn btn-default" target="_blank">
                              <?= $document ?>
                          </a>
                          <a href="<?= $documentPath ?>" class="btn btn-primary" download="<?= $document ?>">
                              <i class="fas fa-download"></i>
                          </a>
                      </div>
                      <?php endif; endforeach; ?>
                  </div>
              </div>
          </div>
      </div>
      <?php endif; ?>
            <!-- Download PDF Button -->
            <div class="download-btn-container">
                <button type="button" class="btn btn-danger" onclick="downloadPDF()">
                    <i class="fas fa-file-pdf"></i> Download as PDF
                </button>
            </div>
            
            <div class="receipt-box" id="receipt-content">
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
                        <strong><?php if(!empty($payment_out->supplier_company_name)): ?>
                            <?= $payment_out->supplier_company_name ?><br>
                        <?php endif; ?></strong><br>
                        
                        <?php if(!empty($payment_out->supplier_address)): ?>
                            <?= $payment_out->supplier_address ?><br><br>
                        <?php endif; ?>
        
        
                        
                        
                        <?php if(!empty($payment_out->supplier_gstin)): ?>
                            GSTIN: <?= $payment_out->supplier_gstin ?><br><br>
                        <?php else: ?>
                        
                        GSTIN: Not Provided<br>
                        <?php endif; ?>
                        <?php if(!empty($payment_out->supplier_state_name)): ?>
                            State: <?= $payment_out->supplier_state_code ?>-<?= $payment_out->supplier_state_name ?>
                        <?php endif; ?>
                    </td>
                    <td style = "text-align:right">
                        Receipt No. : <?= $payment_out->reference_no ?><br>
                        Date : <?= date('d-m-Y', strtotime($payment_out->payment_date)) ?><br>
                          Payment Mode: <?php 
    $modes = ['Cash', 'Credit Card', 'Cheque', 'NEFT/RTGS'];
    echo isset($modes[$payment_out->payment_mode]) ? $modes[$payment_out->payment_mode] : 'N/A';
    ?><br>
    
    <!-- Add NEFT details if applicable -->
    <?php if($payment_out->payment_mode == 3 && !empty($payment_out->utr_number)): ?>
    UTR/Reference: <?= $payment_out->utr_number ?><br>
    Bank: <?= $payment_out->bank_name ?><br>
    <?php endif; ?>
                        <!--Payment Mode: <?= $payment_out->payment_mode ?><br>-->
                        <!--<?php if(!empty($payment_out->notes)): ?>-->
                        <!--    Notes: <?= $payment_out->notes ?><br>-->
                        <!--<?php endif; ?>-->
                    </td>
                </tr>
                <tr class="subheading">
                    <td>Amount in words</td>
                    <td>Amounts</td>
                </tr>
                <tr>
                    <td style="text-align:center">
                      <?= ucwords($this->numbertowords->convert_number((int)$payment_out->amount)) ?> Rupees only
                    </td>
                    <td>
                        Received<br>
                        <strong><?= $this->session->userdata('currency_symbol') . number_format($payment_out->amount, 2) ?></strong>
                    </td>
                </tr>
            </table>
        
            <?php if(!empty($distributions)): ?>
            <div class="invoice-details">
                <h4>Applied to Invoices:</h4>
                <table class="receipt-table">
                    <tr>
                       <!-- <th>PO No</th>-->
                        <th>Vendor Invoice No</th>
                        <th>Invoice Date</th>
                        <th>Invoice Amount</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                    </tr>
                    
                    <?php  $total_amount_paid = 0 ?>
                    
                    <?php foreach($distributions as $distribution):
                       $total_amount_paid += $distribution->amount;
                    ?>
                    <tr>
                        <!--<td><?= $distribution->invoice_no ?></td>-->
                       <!--  <td><?= !empty($distribution->po_number) ? $distribution->po_number : 'N/A' ?></td>-->  <!-- PO Number -->
            <td><?= !empty($distribution->invoice_no) ? $distribution->invoice_no : 'N/A' ?></td>  <!-- Vendor Invoice No -->
                        <td><?= date('d-m-Y', strtotime($distribution->purchase_date)) ?></td>
                        <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format($distribution->invoice_amount, 2) ?></td>
                        <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format($distribution->amount, 2) ?></td>
                        <td class="text-right"><?= $this->session->userdata('currency_symbol') . number_format(($distribution->invoice_amount - $distribution->amount), 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                   <!-- <tr>
                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                        <td class="text-right"><strong><?= $this->session->userdata('currency_symbol') . number_format($total_amount_paid, 2) ?></strong></td>
                        <td></td>
                    </tr>-->
                    <tr>
    <td colspan="3" class="text-right"><strong>Total</strong></td>
    <td class="text-right"><strong><?= $this->session->userdata('currency_symbol') . number_format($total_amount_paid, 2) ?></strong></td>
    <td></td>
</tr>
                </table>
            </div>
            <?php endif; ?>
        
           <!-- <div class="signature">
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

<!-- Include html2pdf library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function downloadPDF() {
    // Get the receipt content element
    const element = document.getElementById('receipt-content');
    
    // Options for PDF generation
    const options = {
        margin: 10,
        filename: 'Payment_Receipt_<?= $payment_out->reference_no ?>.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { 
            scale: 2,
            useCORS: true,
            logging: true
        },
        jsPDF: { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'portrait' 
        }
    };

    // Generate PDF
    html2pdf().set(options).from(element).save();
}

// Alternative method with more customization
function downloadPDFWithCustomization() {
    const element = document.getElementById('receipt-content');
    
    const options = {
        margin: [10, 10, 10, 10], // top, right, bottom, left
        filename: 'Payment_Receipt_<?= $payment_out->reference_no ?>_<?= date('Y-m-d') ?>.pdf',
        image: { 
            type: 'jpeg', 
            quality: 0.98 
        },
        html2canvas: { 
            scale: 2,
            useCORS: true,
            letterRendering: true,
            width: element.scrollWidth,
            height: element.scrollHeight
        },
        jsPDF: { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'portrait',
            compress: true
        },
        pagebreak: { 
            mode: ['avoid-all', 'css', 'legacy'] 
        }
    };

    // Generate and download PDF
    html2pdf().set(options).from(element).save();
}

// You can also use this function to open PDF in new tab instead of downloading
function viewPDF() {
    const element = document.getElementById('receipt-content');
    
    const options = {
        margin: 10,
        filename: 'Payment_Receipt_<?= $payment_out->reference_no ?>.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(options).from(element).outputPdf('bloburl').then(function(pdfUrl) {
        window.open(pdfUrl, '_blank');
    });
}
</script>

<?php 
  $this->load->view('layout/footer');
?>