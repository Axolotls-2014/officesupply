<?php 
  $this->load->view('layout/header');
?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="https://ossdemo.tastytap.in/product_core"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('purchase')?>"><?=$this->lang->line('header_purchase')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('purchase_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <?php 
            if ($purchase->document != NULL)
            {
          ?>
            <div class="card no-print">
                <div class="card-header">
                    <h3 class="card-title">Attached documents</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-12">
                        <?php
                          $documents = explode(',', $purchase->document);
                          $company_settings 	= $this->company_settings_model->get_company_records();
                          $cid = $company_settings->cid;
                          
                          foreach ($documents as $document) {
                              $documentPath = base_url('assets/documents/'.$cid.'/purchase/' . trim($document));
                          ?>
                          <div class="btn-group attached-file" style="margin-top:5px; margin-left:5px;margin-right:5px;">
                              <a href="<?= $documentPath ?>" class="btn btn-default"><?= $document ?></a>
                              <a href="<?= $documentPath ?>" class="btn btn-primary" download="<?= trim($document) ?>"><i class="fas fa-download"></i></a>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                </div>
            </div>
          <?php 
            }
          ?>
          
          <div class="card no-print">
            <div class="card-header">
              <h3 class="card-title">Shortcuts</h3>
               <div class="card-tools">
            <!-- Back Button in Header - Right Corner -->
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm" title="Go Back" style="background-color: #6c757d; border-color: #6c757d; color: white;">
                <i class="fas fa-arrow-left"></i> Back To List
            </a>
        </div>

            </div>
            <div class="card-body">
              <?php if($this->permission_model->has_permission('manage_transaction')): ?>
                <a href="#" data-target="#transaction-modal" data-toggle="modal" class="btn bg-purple btn-sm mr-1" data-tt="tooltip" title="Enter Payment" data-purchase_id="<?=$purchase->id?>"> 
                  <i class="fas fa-rupee-sign"></i> Make Payment
                </a>  
              <?php endif; ?>

              <?php if($this->permission_model->has_permission('manage_purchase_delivery')): ?>
                <a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn bg-maroon btn-sm open_delivery_modal mr-1" data-tt="tooltip" title="Enter Product Delivery" data-purchase_id="<?=$purchase->id?>"> 
                  <i class="fas fa-shipping-fast"></i> Delivery Detail
                </a>  
              <?php endif; ?>

             
                <!--<a href="<?=base_url('purchase/pdf/'.base64_encode($purchase->id))?>" class="btn bg-orange btn-sm mr-1" data-tt="tooltip" title="Download Purchase">-->
                <!--  <i class="far fa-file-pdf"></i> Download Purchase-->
                <!--</a>      -->
              
            </div>
          </div>
          
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('purchase_view')?></h3>
              <?php if($this->permission_model->has_permission('edit_purchase')): ?>
              <div class="card-tools  no-print">
                <?php
                  $delivered_product_qty = $this->purchase_delivery_model->get_total_no_of_quantity_delivered($purchase->id);
                  $paid_amount = $this->transaction_model->get_total_transaction_amount($purchase->id, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
       
                  if($delivered_product_qty > 0 || $paid_amount > 0): ?>
                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#purchase_edit" data-tt="tooltip" title="Edit Purchase" data-purchase_id="<?=$purchase->id?>">
                      <i class="fas fa-edit"></i> Edit
                    </a> 
                  <?php else: ?>
                    <a href="<?=base_url('purchase/edit/'.base64_encode($purchase->id))?>" class="btn btn-info btn-sm" data-tt="tooltip" title="Edit Purchase">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                  <?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
            
            <div class="card-body ">
              <div class="invoice p-3 mb-3">
                <?php
                  $purchase_amount = $purchase->total;
                  $purchase_paid_amount = $this->transaction_model->get_total_transaction_amount($purchase->id,PURCHASE_MODULE,PAYMENT_TRANSACTION_TYPE);  
                ?>
                <div class="ribbon-wrapper ribbon-sm  no-print">
                  <div class="ribbon <?= ($purchase_paid_amount == $purchase_amount) ? 'bg-success' : (($purchase_paid_amount < $purchase_amount && $purchase_paid_amount > 0) ? 'bg-warning' : 'bg-danger') ?>">
                    <?= ($purchase_paid_amount >= $purchase_amount) ? $this->lang->line('paid') : (($purchase_paid_amount < $purchase_amount && $purchase_paid_amount > 0) ? $this->lang->line('partially_paid') : $this->lang->line('unpaid')) ?>
                  </div>
                </div>

<style>
body {
    font-family: Arial, sans-serif;
    font-size: 14px;
    background-color: #fff;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 100%;
    width: 100%;
    padding: 10px 20px;
    overflow-x: hidden;
}

.invoice-box {
    width: 100%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #ddd;
    padding: 12px 18px;
    border-radius: 6px;
    box-sizing: border-box;
}

/* Table */
.invoice-box table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    table-layout: auto;
}

.invoice-box table tr.heading td {
    background: #dbe0f8;
    font-weight: bold;
    text-align: center;
    border: 1px solid #999;
    padding: 6px;
}

.invoice-box table tr.item td,
.invoice-box table tr.details td {
    border: 1px solid #ccc;
    padding: 6px 8px;
    vertical-align: top;
}

/* Narrower columns */
.invoice-box table td.category {
    width: 12%;
}
.invoice-box table td.remark {
    width: 15%;
}

/* Text alignments */
.right { text-align: right; }
.center { text-align: center; }
.bold { font-weight: bold; }

/* Profit & Loss Box */
.profit-loss {
    margin-top: 20px;
    border: 1px solid #ccc;
    background-color: #fafafa;
    border-radius: 4px;
    padding: 10px;
}
.profit-loss .heading {
    background-color: #e6e8ff;
    font-weight: bold;
    padding: 6px;
    border-bottom: 1px solid #ccc;
}
.profit-loss .positive { color: green; }
.profit-loss .negative { color: red; }

/* Header & Footer */
header, footer {
    text-align: center;
    margin: 10px 0;
}
footer {
    font-size: 0.9em;
    color: #555;
}

@media print {
    body { font-size: 13px; }
    .invoice-box {
        box-shadow: none;
        border: none;
        padding: 5px;
    }
    .invoice-box table tr.heading td {
        background: #e0e0e0 !important;
        -webkit-print-color-adjust: exact;
    }
}

@media (max-width: 1280px) {
    body { font-size: 13px; }
    .invoice-box table td {
        padding: 5px 6px;
    }
}

@media print {
    /* Hide all non-invoice elements */
    header, 
    footer, 
    .no-print, 
    .card-header,
    .content-header,
    .main-sidebar,
    .navbar,
    .breadcrumb,
    .card-tools,
    button,
    .btn,
    .generate_purchase,
    .example-modal,
    .modal,
    .modal-backdrop {
        display: none !important;
    }
    
    /* Show only invoice content */
    .invoice-box,
    .invoice-box * {
        visibility: visible !important;
    }
    
    /* Ensure invoice takes full width */
    .invoice-box {
        margin: 0;
        padding: 0;
        border: none;
        box-shadow: none;
    }
    
    /* Remove body margins */
    body {
        margin: 0;
        padding: 0;
    }
    
    /* Remove card styling */
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    /* Ensure table borders print */
    .invoice-box table {
        border-collapse: collapse !important;
    }
    
    .invoice-box table td,
    .invoice-box table th {
        border: 1px solid #000 !important;
    }
}
</style>

<main class="main-wrapper">
    <div class="main-content">
        <div class="container mt-5 w-70">
            <div class="card shadow">
                <div class="card-body">
                    <div class="invoice-box">
                   <div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center; position: relative;">

                    <div style="display: flex; align-items: center; justify-content: center; position: relative;">
                        <p style="font-size: 24px; font-weight: bold; margin: 0;">PURCHASE INVOICE</p>
                
                        <?php if(!empty($company_setting->logo)): ?>
                        <div style="position: absolute; right: 0;">
                            <img src="<?= base_url('/assets/images/0/' . $company_setting->logo); ?>" 
                                 style="width: 130px; height: auto; margin-top: -10px;">
                        </div>
                        <?php endif; ?>
                    </div>
                
                    <hr style="margin: 8px 0 12px 0;">
                
                    <!-- Company Info -->
                    <h4 style="margin: 6px 0; font-size: 18px;"><?= htmlspecialchars($company_setting->company_name); ?></h4>
                    <div>
                        <?= htmlspecialchars($warehouse->address_line1); ?><br>
                        <?php if(!empty($warehouse->license_no)): ?>
                        License No: <?= htmlspecialchars($warehouse->license_no); ?>
                        <?php endif; ?>
                    </div>
                
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <?php if(!empty($company_setting->gstin)): ?>
                        <div><strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin); ?></div>
                        <?php endif; ?>
                        <div><strong>State:</strong> <?= htmlspecialchars($company_setting->state_name); ?></div>
                    </div>
                </div>
                    <!-- Invoice and Transport Details -->
                    <table>
                        <tr class="heading">
                            <td colspan="6" style="width:25%;">Supplier Details</td>
                            <td colspan="6" style="width:25%;">Invoice Details</td>
                            <!--<td colspan="3" style="width:25%;">Transport Details</td>-->
                        </tr>
                        <tr class="details">
                            <td colspan="6">
                                Company Name: <?= $supplier_detail->company_name; ?><br>
                                Address: <?= $supplier_detail->address; ?><br>
                                GSTIN: <?= $supplier_detail->gstin; ?><br>
                                <span class="no-print">Email: <?= $supplier_detail->email ?><br></span>
                                <span class="no-print">Mob: <?= $supplier_detail->phone ?><br></span>
                                State: <?= $supplier_detail->state_name; ?>
                            </td>
                            <td colspan="6">
                               <!-- PO No: <?= $purchase->reference_no; ?><br>-->
                                Vendor Invoice No: <?= htmlspecialchars($purchase->invoice_no); ?><br>
                                Vendor Invoice Date: <?= date('d-m-Y', strtotime($purchase->purchase_date)); ?><br>
                                Payment Terms: <?= htmlspecialchars($purchase->payment_terms); ?><br>
                                Due Date: <?= date('d-m-Y', strtotime($purchase->invoice_date . ' + ' . $purchase->due_days . ' days')); ?><br>
                                <?php if(!empty($purchase->purchase_order_date)): ?>
                                    PO Date: <?= date('d-m-Y', strtotime($purchase->purchase_order_date)); ?><br>
                                <?php endif; ?>   
                                <?php if(!empty($purchase->purchase_order_no)): ?>
                                    PO No: <?= htmlspecialchars($purchase->purchase_order_no); ?><br>
                                <?php endif; ?>
                            </td>
                            <!--<td colspan="3">-->
                            <!--    <?php if (!empty($purchase->vehicle_no)): ?>-->
                            <!--    Vehicle No: <?= htmlspecialchars($purchase->vehicle_no); ?><br>-->
                            <!--    <?php endif; ?>-->
                            <!--    <?php if (!empty($purchase->transport_mode)): ?>-->
                            <!--    Transport Mode: <?= htmlspecialchars($purchase->transport_mode); ?><br>-->
                            <!--    <?php endif; ?>-->
                            <!--</td>-->
                        </tr>
                    </table>
                    
                                     <!-- Items Table -->
<!-- Items Table -->
<table class="product-table">
    <tr class="heading">
        <td>S.No</th>
        <td>Name of Product</th>
        <td>Category</th>
        <td>Code</th>
        <td>HSN</th>
        <td>UOM</th>
        <td>Qty</th>
        <td>Purchase Cost</th>
        <td>MRP</th>           <!-- ADD MRP COLUMN -->
        <td>Discount</th>      <!-- ADD DISCOUNT COLUMN -->
        <td>Taxable Value</th>
        <td>Tax (Rate)</th>
        <td>Amount</th>
    </tr>
    <?php 
    $i = 1;
    $total_taxable_value = 0;
    $total_tax = 0;
    $total_subtotal = 0;
    $total_qty = 0;
    $total_gst_amount = 0;
    $total_discount = 0;  // Add total discount variable
    
    foreach ($purchase_items as $row):
        $tax_amount = $row->cgst_tax + $row->sgst_tax + $row->igst_tax;
        $tax_rate = $row->taxable_value > 0 ? round(($tax_amount / $row->taxable_value) * 100, 2) : 0;
        $total_tax += $tax_amount;
        $total_subtotal += $row->subtotal;
        $total_discount += $row->discount_amount;  // Add to total discount
        
        $product = $this->db->get_where('product', ['id' => $row->product_id])->row();
        $category = $this->db->get_where('product_category', ['id' => $product->product_category_id])->row();
        $total_qty += $row->quantity;
        $total_taxable_value += $row->taxable_value;
        $total_gst_amount = $total_tax;
        
        // Calculate MRP value (use selling_price from purchase_items or price from product)
        $mrp_value = $row->selling_price > 0 ? $row->selling_price : ($product->price ?? 0);
        
        // Calculate discount display text
        $discount_display = '₹ 0.00';
        if ($row->discount_amount > 0) {
            if ($row->discount_type == 1) {
                $discount_display = '₹ ' . number_format_i($row->discount_amount) . ' (' . $row->discount_value . '%)';
            } else {
                $discount_display = '₹ ' . number_format_i($row->discount_amount);
            }
        }
    ?>
    <tr class="item">
        <td><?= $i++; ?></td>
        <td><?= $row->product_name; ?></td>
        <td><?= $category->name ?? 'N/A'; ?></td>
        <td><?= $product->product_code ?? 'N/A'; ?></td>
        <td><?= $row->hsn ?? 'N/A'; ?></td>
        <td><?= $row->uom_uom; ?></td>
        <td><?= $row->quantity; ?></td>
        <td>₹ <?= number_format_i($row->cost); ?></td>
        <td>₹ <?= number_format_i($mrp_value); ?></td>  <!-- MRP COLUMN -->
        <td><?= $discount_display; ?></td>  <!-- DISCOUNT COLUMN -->
        <td>₹ <?= number_format_i($row->taxable_value); ?></td>
        <td>₹ <?= number_format_i($tax_amount); ?> (<?= $tax_rate ?>%)</td>
        <td>₹ <?= number_format_i($row->subtotal); ?></td>
    </tr>
    <?php endforeach; ?>
    
    <!-- ADD FREIGHT ROW HERE - AFTER PRODUCTS LOOP -->
    <?php if(isset($purchase->freight_amount) && $purchase->freight_amount > 0): ?>
    <tr class="item" style="background-color: #f9f9f9;">
        <td><?= $i++; ?></td>
        <td><strong>Freight Charges (<?= ucfirst($purchase->additional_cost_type ?? 'Transport'); ?>)</strong></td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>1.00</td>
        <td>-</td>
        <td>-</td>  <!-- MRP column for freight -->
        <td>-</td>  <!-- Discount column for freight -->
        <td>₹ <?= number_format_i($purchase->freight_taxable_value ?? 0); ?></td>
        <td><?php if(($purchase->freight_tax_rate ?? 0) > 0): ?>₹ <?= number_format_i($purchase->freight_tax_amount ?? 0); ?> (<?= number_format_i($purchase->freight_tax_rate); ?>%)<?php else: ?>N/A<?php endif; ?></td>
        <td><strong>₹ <?= number_format_i($purchase->freight_sub_total ?? $purchase->freight_amount); ?></strong></td>
    </tr>
    <?php 
        $total_taxable_value += ($purchase->freight_taxable_value ?? 0);
        $total_tax += ($purchase->freight_tax_amount ?? 0);
        $total_subtotal += ($purchase->freight_sub_total ?? $purchase->freight_amount);
        $total_qty += 1;
    endif; 
    ?>
    
    <tr class="total" style="font-weight:bold; background-color:#f2f2f2;">
        <td colspan="6" style="text-align:right;">Total</td>
        <td><?= number_format_i($total_qty); ?></td>
        <td class="no-print"></td>
        <td></td>  <!-- MRP total column (empty) -->
        <td></td>  <!-- Discount total column (empty) -->
        <td>₹ <?= number_format_i($total_taxable_value); ?></td>
        <td>₹ <?= number_format_i($total_gst_amount); ?></td>
        <td>₹ <?= number_format_i($total_subtotal); ?></td>
    </tr>
</table>
                    <!-- Summary Section -->
<!-- Summary Section -->
<!-- Summary Section -->
<table class="product-table">
    <tr class="heading">
        <td colspan="6">Amount in Words</td>
        <td colspan="3">Amounts</td>
    </tr>
    <tr class="details">
        <td colspan="6" style="text-align: center; vertical-align: middle; padding: 15px 10px;">
            <strong style="font-size: 14x;"><?= strtoupper($this->numbertowords->convert_number(round($purchase->total))) . ' RUPEES ONLY'; ?></strong>
        </td>
        <td colspan="3">
            <!-- Sub Total (Products only, before discount) -->
            <div>
                <strong>Sub Total:</strong> 
                <span style="float:right;">
                    ₹ <?= number_format_i(($total_taxable_value - ($purchase->freight_taxable_value ?? 0)) + $total_discount); ?>
                </span>
            </div>
            
            <!-- Total Discount -->
            <?php if ($total_discount > 0): ?>
            <div>
                <strong>Total Discount:</strong> 
                <span style="float:right; color: green;">
                    - ₹ <?= number_format_i($total_discount); ?>
                </span>
            </div>
            <?php endif; ?>
            
            <!-- Internal/Freight Charges (shown separately) -->
            <?php if(isset($purchase->freight_amount) && $purchase->freight_amount > 0): ?>
                <div>
                    <strong>Freight Charges:</strong> 
                    <span style="float:right;">
                        ₹ <?= number_format_i($purchase->freight_amount); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Tax -->
            <?php if ($total_tax > 0): ?>
                <div>
                    <strong>Tax:</strong> 
                    <span style="float:right;">
                        ₹ <?= number_format_i($total_tax); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Rounded Off -->
            <?php 
            $rounded_off = round($purchase->total) - $purchase->total;
            if ($rounded_off != 0): ?>
                <div>
                    <strong>Rounded Off:</strong> 
                    <span style="float:right;">
                        ₹ <?= number_format_i($rounded_off); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <hr>
            
            <!-- Grand Total -->
            <div style="font-weight: bold;">
                Total: 
                <span style="float:right;">
                    ₹ <?= number_format_i(round($purchase->total)); ?>
                </span>
            </div>
         </td>
    </tr>
</table>
                    <!-- Tax Summary -->
                    <table>
                        <tr class="heading">
                            <td>Tax Type</td>
                            <td>Taxable Amount (₹)</td>
                            <td>Rate (%)</td>
                            <td>Tax Amount (₹)</td>
                        </tr>
                        <?php
                        $tax_summary = [];
                        foreach ($purchase_items as $item) {
                            $applied_taxes = [];
                            if ($item->cgst_tax > 0) {
                                $applied_taxes[] = ['type' => 'CGST', 'rate' => $item->cgst, 'amount' => $item->cgst_tax];
                            }
                            if ($item->sgst_tax > 0) {
                                $applied_taxes[] = ['type' => 'SGST', 'rate' => $item->sgst, 'amount' => $item->sgst_tax];
                            }
                            if ($item->igst_tax > 0) {
                                $applied_taxes[] = ['type' => 'IGST', 'rate' => $item->igst, 'amount' => $item->igst_tax];
                            }
                    
                            foreach ($applied_taxes as $tax) {
                                $key = $tax['type'] . '_' . $tax['rate'];
                                if (!isset($tax_summary[$key])) {
                                    $tax_summary[$key] = [
                                        'type' => $tax['type'],
                                        'rate' => $tax['rate'],
                                        'taxable_amount' => 0,
                                        'amount' => 0,
                                    ];
                                }
                                $tax_summary[$key]['taxable_amount'] += $item->taxable_value;
                                $tax_summary[$key]['amount'] += $tax['amount'];
                            }
                        }
                    
                        foreach ($tax_summary as $row) {
                            echo '<tr class="details">';
                            echo '<td class="text-center">' . $row['type'] . '</td>';
                            echo '<td class="text-center">₹ ' . number_format($row['taxable_amount'], 2) . '</td>';
                            echo '<td class="text-center">' . number_format($row['rate'], 2) . '%</td>';
                            echo '<td class="text-center">₹ ' . number_format($row['amount'], 2) . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    
                    <!-- Bank and Signature -->
                    <table>
                        <tr class="heading">
                            <td>Bank Details</td>
                            <td class="text-center">For: <?= $company_setting->company_name; ?></td>
                        </tr>
                        <tr class="details">
                            <td>
                                <?= $purchase->bank_detail; ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                $signature_data = '';
                                $company_settings = $this->company_settings_model->get_company_records();
                                $cid = $company_settings->cid;

                                if ($company_setting->signature != '') {
                                    $signature_path = './assets/images/' . $cid . '/' . $company_setting->signature; 
                                    if (file_exists($signature_path)) {
                                        $signature_data = file_get_contents($signature_path);
                                        $base64_data = base64_encode($signature_data);
                                ?>
                                    <img src="data:image/png;base64,<?= $base64_data ?>" style="width: 100px;">
                                <?php 
                                    } 
                                } else {
                                    echo '<br/><br/><br/>';
                                }
                                ?> 
                                <br>Authorised Signature
                            </td>
                        </tr>
                    </table>
                    
                    <!-- General Remark -->
                    <p><strong>General Remark:</strong> <?= $purchase->external_note ?? 'N/A' ?> </p>
                    </div> <!-- /.invoice-box -->
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
            
            <!-- Buttons -->
            <div class="no-print text-center mt-3">
                <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
                <a href="<?= base_url('purchase/pdf/'.base64_encode($purchase->id)); ?>" class="btn btn-danger">Download PDF</a>
                <a href="javascript:history.back()" class="btn btn-secondary btn-sm" title="Go Back" style="background-color: #6c757d; border-color: #6c757d; color: white;">
                 Back To List
            </a>
            </div>
        </div> <!-- /.container -->
    </div>
</main>

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                  <div class="col-12">
                    <!-- Print/download buttons if needed -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<div class="transaction-modal">
  <div class="modal fade" id="transaction-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('purchase_transaction');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <div class="card card-secondary">
              <div class="card-header purchaseDetail light-secondary-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#purchaseDetail" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('purchase_view')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="purchaseDetail" class="panel-collapse in collapse" style="">
                <div class="card-body purchase_detail  m-0 p-0">
                </div>
              </div>
            </div>
            <div class="card card-danger addTransactionCard">
              <div class="card-header addTransaction light-failure-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                    <?=$this->lang->line('purchase_payment')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="addTransaction" class="panel-collapse collapse show" style="">
                <form id="addTransactionForm" name="addTransactionForm" method="POST">
                  <div class="card-body add_transaction">
                  </div>
                  <div class="card-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="transaction_type" value="<?=PAYMENT_TRANSACTION_TYPE?>">
                    <input type="hidden" name="entry_id" id="purchase_id" value="">
                    <input type="hidden" name="module" value="<?=PURCHASE_MODULE?>">
                    <input type="hidden" name="to_account" id="to_account" value="">
                    <button type="submit" class="btn btn-info" name="addTransactionSubmit" id="addTransactionSubmit">
                      <?php echo $this->lang->line('submit');?>
                    </button>
                    <button type="reset" class="btn btn-default">
                      <?php echo $this->lang->line('reset');?>
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="card card-success">
              <div class="card-header transactionEntries light-success-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#transactionEntries" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('purchase_previous_transaction')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="transactionEntries" class="panel-collapse collapse">
                <div class="card-body transaction_entries m-0 p-0">
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_there_change_in_transaction" id="is_there_change_in_transaction" value="false">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="delivery-modal">
  <div class="modal fade" id="delivery-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('purchase_delivery');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <form id="addDeliveryDetailForm" name="addDeliveryDetailForm" method="POST">
              <div class="card card-info addDeliveryDetailCard">

                  <div class="card-header light-secondary-header addDeliveryDetail">
                    <h5 class="card-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#addDeliveryDetail" class="collapsed" aria-expanded="false">
                        <?=$this->lang->line('purchase_delivery_add')?>
                        <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                      </a>
                    </h5>
                  </div>
                  <div id="addDeliveryDetail" class="panel-collapse in collapse" style="">
                    <div class="card-body add_delivery_detail">
                    
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="delivery_items" value="">
                      <input type="hidden" name="purchase_id" value="">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      <button type="submit" name="addDeliveryDetailSubmit" id="addDeliveryDetailSubmit" class="btn btn-primary float-right">
                        <?=$this->lang->line('submit')?>
                      </button>
                      <button type="button" name="cancelDeliveryDetail" id="cancelDeliveryDetail" class="btn btn-default">
                        <?=$this->lang->line('cancel')?>
                      </button>
                    </div>
                  </div>
                
              </div>
            </form>
            <div class="card card-success deliveryEntriesCard">
              <div class="card-header deliveryEntries light-success-header">
                <h5 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#deliveryEntries" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('previous_purchase_delivery')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h5>
                <div class="card-tools">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-default" id="addNewDeliveryDetail">Add New Delivery</button>
                  </div>
                </div>
              </div>
              <div id="deliveryEntries" class="panel-collapse collapse">
                <div class="card-body delivery_entries m-0 p-0">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_there_change_in_delivery_transaction" id="is_there_change_in_delivery_transaction" value="false">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="purchase_edit">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
  $(document).ready(function(e){
    $('.print_invoice').click(function(e){
      $('.invoice').printThis();
    });
  });
</script>



<script type="text/javascript">
  $(document).ready(function(e){
    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var purchase_id = $(e.relatedTarget).data('purchase_id');

      if (!(typeof purchase_id === 'undefined')) {

        $('#purchase_id').val(purchase_id);
      
        $.ajax({
          url: "<?php echo base_url('purchase/view')?>/"+purchase_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){

            $(".purchase_detail").html(data.purchase_view);
            $(".add_transaction").html(data.add_transaction);
            $("#to_account").val(data.to_account);
            $(".transaction_entries").html(data.previous_transaction_view);

            $('#transaction-modal').find('input[name="voucher_date"]').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
            });

            if(data.due_amount == 0)
            {
              $('.addTransactionCard').css('display','none');
              // collapse all accordian
              $('#transactionEntries').collapse("hide");
              $('#purchaseDetail').collapse("show");  
            }
            else
            {
              // collapse all accordian
              $('#transactionEntries').collapse("hide");
              $('#addTransaction').collapse("hide");
              $('#purchaseDetail').collapse("show");  
            }
          }
        });
      }
    });

    $(document).on('hide.bs.modal', '#transaction-modal', function (e) {

        if($('#is_there_change_in_transaction').val() == "true")
        {
          location.reload();
        }

    });

    $('#delivery-modal').on('show.bs.modal', function (e) {

      var purchase_id = $(e.relatedTarget).data('purchase_id');
      $('#purchase_id').val(purchase_id);
      $('#addNewDeliveryDetail').data('purchase_id',purchase_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('purchase/view_delivery')?>",
          type: "POST",
          data: {
            'purchase_id':purchase_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            // if all quantity is delivered, hide the add new delivery button
            if(data.is_all_products_delivered == true)
            {
              $('#addNewDeliveryDetail').css('display','none');
            }
            else
            {
              $('#addNewDeliveryDetail').css('display','block'); 
            }  

            // $(".add_delivery_detail").html(data.add_delivery_detail);
            $(".delivery_entries").html(data.delivery_transaction);
            $(".purchase_delivery_detail").html(data.purchase_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val(purchase_id);
          }
        });  
      }      
    });


    $(document).on('hide.bs.modal', '#delivery-modal', function (e) {
   
      if($('#is_there_change_in_delivery_transaction').val() == "true")
      {
        location.reload();
      }
    });


  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.purchaseDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    // View delivery transaction
    $(document).on('click', '.view_delivery_transaction', function (event) {

      $('.view_delivery_transaction_detail').css('display','none');
      $(this).parents('tr').next('tr').toggle(200);
      
    });

    // Add new delivery of purchase
    $(document).on('click','#addNewDeliveryDetail',function(e){

      $('.addDeliveryDetailCard').toggle(200);

      var purchase_id = $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val();

      $.ajax({
        url: "<?php echo base_url('purchase/add_purchase_delivery')?>/"+purchase_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".add_delivery_detail").html(data.add_delivery_detail);
          
          // Initialize the dropdown and date
  
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
  
          $('#delivery-modal').find('input[name="delivery_date"]').datepicker({
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
          });
            
        }
      });

      $('#addDeliveryDetail').collapse('show');
    });

    // Populate delivery data of purchase
    $(document).on('click','#addDeliveryDetailSubmit',function(e){
      e.preventDefault();

      var deliveryDataArray = [];

      $("#product_delivery_item_body").find('tr').each(function () {

        var tr                      = $(this).closest("tr");
        var deliveryData            = {};

        deliveryData['product_id']    = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['warehouse_id']  = tr.find('input[name="quantity"]').data('warehouse_id');
        deliveryData['quantity']      = tr.find('input[name="quantity"]').val();
        deliveryData['batch_no']              = tr.find('input[name="quantity"]').data('batch_no');
        deliveryData['cost']                  = tr.find('input[name="quantity"]').data('cost');
        deliveryData['price']                 = tr.find('input[name="quantity"]').data('price');
        deliveryData['selling_price']         = tr.find('input[name="quantity"]').data('selling_price');
        deliveryData['mfg_date']              = tr.find('input[name="quantity"]').data('mfg_date');
        deliveryData['expiry_date']           = tr.find('input[name="quantity"]').data('expiry_date');

        deliveryDataArray.push(JSON.stringify(deliveryData));
      });

       
      $('#addDeliveryDetailForm').find('input[name="delivery_items"]').val(deliveryDataArray.join('|'));

      $('#addDeliveryDetailForm').trigger('submit');
    });

    // Submit delivery data of purchase
    $(document).on('submit','#addDeliveryDetailForm',function(e){
      e.preventDefault();

      var isError = false;

      $('form#addDeliveryDetailForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');
          var name  = $(this).attr('name');

          // alert(name);
          
          if(value==null || value=="")
          {
            $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
            $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
            $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
          }

          if(name == 'quantity')
          {
            var max = parseInt($(this).attr('max'));
            if(parseInt(value) > max)
            {
              $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
              $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
              isError = true;   
            }
          }


      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var DeliveryDetailFormData  = $('#addDeliveryDetailForm').serialize();  
        var purchase_id             = $('form#addDeliveryDetailForm').find('input[name="purchase_id"]').val(); 

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('purchase/add_purchase_delivery')?>",
            type: "POST",
            data: DeliveryDetailFormData,
            dataType: "JSON",
            success: function(data){


              if(data.code == 1)
              {
                $('#addDeliveryDetailSubmit').text('Submit').removeClass('disabled');
                $.ajax({
                  url: "<?php echo base_url('purchase/view_delivery')?>",
                  type: "POST",
                  data: {
                    'purchase_id':purchase_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    $('.addDeliveryDetailCard').toggle(200);
                    $(".add_delivery_detail").html('');
                    $(".delivery_entries").html(data.delivery_transaction);
                    // $(".purchase_delivery_detail").html(data.purchase_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val(purchase_id);

                    $("#is_there_change_in_delivery_transaction").val("true");
                    
                  }
                });  
              }
            }
          });
        }, 500);  
      }
    });

    $("form#addDeliveryDetailForm").on("change", '.field_validation', function (event){

      var id    = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      var name  = $(this).attr('name');
      
      if(value==null || value==""){
        $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
        $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
        return false;
      }
      else{
        $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
        $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
        $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
      }

      if(name == 'quantity')
      {
        var max = parseInt($(this).attr('max'));
        if(parseInt(value) > max)
        {
          $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
          $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
          return false;  
        }
        else
        {
          $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
          $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
          $('form#addDeliveryDetailForm #'+id).addClass('is-valid');   
        }
      }
    });

    $(document).on('click','#cancelDeliveryDetail',function(e){
      $('.addDeliveryDetailCard').toggle(200);
      $('#addDeliveryDetail').collapse('hide');
      
    });

    $(document).on('click','.delete_delivery_transaction',function(e){
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_delivery_transaction_no', function (event) {
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_delivery_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(1);
      

      var $this                 = $(this);
      var purchase_delivery_id  = $(this).data('purchase_delivery_id');
      var purchase_id           = $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('purchase/delete_purchase_delivery')?>",
          type: "POST",
          data: {
            'purchase_delivery_id':purchase_delivery_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $("#is_there_change_in_delivery_transaction").val("true");

            setTimeout(function(){ 

              if(data.code == 1)
              {
                

                $.ajax({
                  url: "<?php echo base_url('purchase/view_delivery')?>",
                  type: "POST",
                  data: {
                    'purchase_id':purchase_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    // $(".add_delivery_detail").html(data.add_delivery_detail);
                    $(".delivery_entries").html(data.delivery_transaction);
                    $(".purchase_delivery_detail").html(data.purchase_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val(purchase_id);
                  }
                });

                
              }

            },500);
          }
        });
      }, 500);
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('body').on('click', '.transactionEntries', function() {
      $('#transactionEntries').collapse("show");
      $('#addTransaction').collapse("hide");
      $('#purchaseDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#purchaseDetail').collapse("hide");
    });

    $('body').on('click', '.purchaseDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#purchaseDetail').collapse("show");
    });

    $(document).on('click', '.make_payment', function (event) {
      var transaction_amount = $(this).data('transaction_amount');
      $('#transaction_amount').val(transaction_amount);
      $('.addTransaction').trigger('click');
    });

    $('body').on('change', '#payment_mode', function() {
      var payment_mode = $(this).val();

      if(payment_mode == '')
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 0)
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 1)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.credit_card_row').fadeIn();
      }
      else if(payment_mode == 2)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.cheque_row').fadeIn();
      }
      else if(payment_mode == 3)
      {
        $('.transaction_mode_row').fadeOut(10);
      }

      $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
      $('form#addTransactionForm #credit_card_no').removeClass('is-valid');

      $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
      $('form#addTransactionForm #cheque_no').removeClass('is-valid');
    });    

    $('body').on('submit', '#addTransactionForm', function(e) {
      e.preventDefault();
      var isError = false;

      $('form#addTransactionForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addTransactionForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
            $('form#addTransactionForm #'+id).removeClass('is-invalid');
            $('form#addTransactionForm #'+id).addClass('is-valid');
          }
      });

      var closing_balance     = parseFloat($('form#addTransactionForm #from_account').find(':selected').data('closing_balance'));
      var transaction_amount  = parseFloat($('form#addTransactionForm #transaction_amount').val()); 


      if(closing_balance < transaction_amount)
      {
        var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
        var text        = $('form#addTransactionForm #from_account').find(':selected').text();
        
        $("form#addTransactionForm #err_from_account").text("There is not enough balance in "+text).fadeIn('slow');
        $('form#addTransactionForm #from_account').addClass('is-invalid');
        isError = true;
      }
      else if($('form#addTransactionForm #from_account').find(':selected').val() == '' || $('form#addTransactionForm #from_account').find(':selected').val() == null)
      {
        var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');

        $("form#addTransactionForm #err_from_account").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #from_account').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_from_account").text("").fadeOut('slow');
        $('form#addTransactionForm #from_account').removeClass('is-invalid');
        $('form#addTransactionForm #from_account').addClass('is-valid');
      }

      if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
      {
        var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
        $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
        $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
        $('form#addTransactionForm #credit_card_no').addClass('is-valid');
      }

      if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
      {
        var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
        $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #cheque_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
        $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
        $('form#addTransactionForm #cheque_no').addClass('is-valid');
      }

      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var transactionFormData = $('#addTransactionForm').serialize();
        var purchase_id = $('#purchase_id').val();

        $('#addTransactionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled')
        

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: transactionFormData,
          dataType: "JSON",
          success: function(data){

            $(".transaction_entries").html(data.previous_transaction);
            $("#is_there_change_in_transaction").val('true');

            $.ajax({
              url: "<?php echo base_url('purchase/view')?>/"+purchase_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $('#addTransactionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        
                $(".purchase_detail").html(data.purchase_view);
                $(".add_transaction").html(data.add_transaction);
                $("#from_account").val(data.from_account);
                $(".transaction_entries").html(data.previous_transaction_view);

                if(data.due_amount == 0)
                {
                  $('.addTransactionCard').css('display','none');
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#purchaseDetail').collapse("hide");  
                }
                else
                {
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#purchaseDetail').collapse("hide");  
                }
              }
            });
          }
        });
      } 

    });

    $(document).on("blur change keyup","form#addTransactionForm .field_validation",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
          $('form#addTransactionForm #'+id).removeClass('is-invalid');
          $('form#addTransactionForm #'+id).addClass('is-valid');
        }

        var closing_balance     = parseFloat($('form#addTransactionForm #from_account').find(':selected').data('closing_balance'));
        var transaction_amount  = parseFloat($('form#addTransactionForm #transaction_amount').val()); 

        if(closing_balance < transaction_amount)
        {
          var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
          var text        = $('form#addTransactionForm #from_account').find(':selected').text();

          $("form#addTransactionForm #err_from_account").text("There is not enough balance in "+text).fadeIn('slow');
          $('form#addTransactionForm #from_account').addClass('is-invalid');
          return false;
        }
        else if($('form#addTransactionForm #from_account').find(':selected').val() == '' || $('form#addTransactionForm #from_account').find(':selected').val() == null)
        {
          var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
          
          $("form#addTransactionForm #err_from_account").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #from_account').addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addTransactionForm #err_from_account").text("").fadeOut('slow');
          $('form#addTransactionForm #from_account').removeClass('is-invalid');
          $('form#addTransactionForm #from_account').addClass('is-valid');
        }

        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }

        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on("blur change keyup","form#addTransactionForm #credit_card_no",  function (event){
              
        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }
        
    });

    $(document).on("blur change keyup","form#addTransactionForm #cheque_no",  function (event){              
        
        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on('click', '.delete_transaction', function (event) {
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_transaction_no', function (event) {
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(1);
      

      var $this           = $(this);
      var transaction_id  = $(this).data('transaction_id');
      var purchase_id         = $(this).data('entry_id');

      $(this).closest('td').find('.delete_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('transaction/delete')?>",
          type: "POST",
          data: {
            'transaction_id':transaction_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $("#is_there_change_in_transaction").val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('purchase/view')?>/"+purchase_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".purchase_detail").html(data.purchase_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#from_account").val(data.from_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      // collapse all accordian
                      $('#transactionEntries').collapse("show");
                      $('#purchaseDetail').collapse("hide");  
                    }
                    else
                    {
                      // collapse all accordian
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#purchaseDetail').collapse("show");  
                    }
                  }
                });  
              }

            },500);
          }
        });
      }, 500);
    });

    $(document).on('show.bs.modal','#purchase_edit', function (e) {
      var purchase_id = $(e.relatedTarget).data('purchase_id');
      $('#purchase_edit').find('#id').val(purchase_id);

      // alert(purchase_id);

      $.ajax({
        url: "<?php echo base_url('purchase/purchase_edit_confirmation')?>",
        type: "POST",
        data:{
          'purchase_id': purchase_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#purchase_edit').find('.modal-content').html(data.purchase_edit_modal_body);
        }
      });


    });
  })
</script>