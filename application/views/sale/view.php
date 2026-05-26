<?php 
  $this->load->view('layout/header');

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date   = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);

  $lr_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'lr_no', 'active',$row = true,$check_delete_status = false);

  $lr_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'lr_date', 'active',$row = true,$check_delete_status = false);

  $carrier = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'carrier', 'active',$row = true,$check_delete_status = false);

  $ewaybill_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'ewaybill_no', 'active',$row = true,$check_delete_status = false);

  $batch_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'batch_no', 'active',$row = true,$check_delete_status = false);

  
?>
<style>
header, footer {
    text-align: center;
    margin-bottom: 20px;
}

.company-info, .client-info {
    margin-bottom: 20px;
}

footer {
    font-size: 0.9em;
    color: #555;
}
</style>
<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('sale');?>"><?=$this->lang->line('sale_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('sale_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <?php 
            if ($sale->document != NULL)
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
                          $documents = explode(',', $sale->document);
                          $company_settings 	= $this->company_settings_model->get_company_records();
                          $cid = $company_settings->cid;
                          
                          foreach ($documents as $document) {

                              $documentPath = base_url('assets/documents/'.$cid.'/sale/' . trim($document));
                              
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
            </div>
            <div class="card-body">

              <?php 
                if($this->permission_model->has_permission('pdf_download_sale'))
                {
              ?>
             <!-- <a href="<?=base_url('sale/pdf/'.base64_encode($sale->id))?>" class="btn bg-orange btn-sm  mr-1"  title="Download Invoice">
                <i class="far fa-file-pdf"></i> Download Invoice
              </a>-->  
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('manage_sale_delivery'))
                {
              ?>
              <a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn bg-maroon btn-sm open_delivery_modal  mr-1"  title="Enter Product Delivery" data-sale_id="<?=$sale->id?>"> 
                <i class="fas fa-shipping-fast"></i> Delivery Details
              </a>  
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('manage_transaction'))
                {
              ?>
                  <a href="#" data-target="#transaction-modal" data-toggle="modal"  class="btn bg-purple btn-sm  mr-1" title="Enter Payment" data-sale_id="<?=$sale->id?>"> 
                    <i class="fas fa-rupee-sign"></i> Enter Payment
                  </a>
              <?php 
                }
              ?>

              <?php 
                if($this->permission_model->has_permission('download_ewaybill_json'))
                {
              ?>
                  <a href="#" data-target="#generate_ewaybill_modal" data-toggle="modal"  class="btn bg-gray btn-sm  mr-1" title="Generate Ewaybill" data-sale_id="<?=$sale->id?>">
                    <i class="fas fa-shipping-fast"></i> Generate Ewaybill
                  </a>
              <?php
                }
              ?>

            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title no-print"><?=$this->lang->line('sale_view')?></h3>
              <div class="card-tools no-print">
              <?php 

                $delivered_product_qty = $this->sale_delivery_model->get_total_no_of_quantity_delivered($sale->id);
                $paid_amount = $this->transaction_model->get_total_transaction_amount($sale->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount(null,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

                if($this->permission_model->has_permission('edit_sale') || $this->permission_model->has_permission('edit_all_sale'))
                { 
                  if($paid_amount > 0 || $delivered_product_qty > 0)
                  {
                ?>
                <?php 
                  }
                  else
                  {
                ?>
                <?php 
                  }
                }
                ?>
              </div>
             
            </div>
            <div class="card-body">
              <div class="invoice p-3 mb-3">
                <?php 
                  $paid_amount  = round($this->transaction_model->get_total_transaction_amount($sale->id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE));
                  $total        = round($sale->total);
                ?>
                <div class="ribbon-wrapper ribbon-sm no-print">
                  <div class="ribbon 
                    <?php 
                      if($paid_amount >= $total)
                      {
                        echo 'bg-success';
                      }
                      else if($paid_amount < $total && $paid_amount > 0)
                      {
                        echo 'bg-warning';
                      }
                      else
                      {
                        echo 'bg-danger'; 
                      }
                    ?>
                  ">
                    <?php
                      if($paid_amount >= $total)
                      {
                        echo $this->lang->line('paid');
                      }
                      else if($paid_amount < $total && $paid_amount > 0)
                      {
                        echo $this->lang->line('partially_paid');
                      }
                      else
                      {
                        echo $this->lang->line('unpaid'); 
                      }
                    ?>
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

/* Bank & Signature row */
.bank-signature-row td {
    min-height: 140px !important;
    height: 140px !important;
    padding: 16px 18px !important;
    font-size: 14px;
    line-height: 1.8;
}

.bank-signature-row td:first-child {
    width: 60%;
    vertical-align: top;
}

.bank-signature-row td:last-child {
    width: 40%;
    vertical-align: bottom;
    text-align: center;
}

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
    .bank-signature-row td {
        min-height: 140px !important;
        height: 140px !important;
    }
}

@media (max-width: 1280px) {
    body { font-size: 13px; }
    .invoice-box table td {
        padding: 5px 6px;
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
                        <p style="font-size: 24px; font-weight: bold; margin: 0;">TAX INVOICE</p>
                
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
                        <?= htmlspecialchars($company_setting->address_line1); ?><br>
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
                            <td colspan="3" style="width:25%;">Bill To</td>
                            <td colspan="3" style="width:25%;">Ship To</td>
                            <td colspan="3" style="width:25%;">Invoice Details</td>
                            <td colspan="3" style="width:25%;">Transport Details</td>
                        </tr>
                        <tr class="details">
                            <td colspan="3">
                                Company Name: <?= $customer_detail->customer_company_name; ?><br>
                                Customer name :<?= $customer_detail->customer_name; ?><br>
                                Address :<?= $customer_detail->address; ?><br>
                                GSTIN: <?= $customer_detail->gstin; ?><br>
                                <span class="no-print">Email: <?= $customer_detail->email ?><br></span>
                                <span class="no-print">Mob: <?= $customer_detail->phone ?><br></span>
                                State: <?= $customer_detail->state_name; ?>
                            </td>
                         <td colspan="3">
                            <?php if(isset($shipping_address)): ?>
                                Shipping Name: <?= $shipping_address->shipping_name; ?><br>
                                Address: <?= $shipping_address->shipping_address; ?><br>
                                Pincode: <?= $shipping_address->shipping_pincode; ?><br>
                                State: <?= $shipping_address->state_name; ?><br>
                                City: <?= $shipping_address->city_name; ?>
                            <?php else: ?>
                                Address: <?= $customer_detail->shipping_address; ?><br>
                                Pincode: <?= $customer_detail->shipping_pincode; ?><br>
                                State: <?= $customer_detail->shipping_state_name; ?>
                            <?php endif; ?>
                        </td>
                        <td colspan="3">
                            Invoice No: <?= $sale->reference_no; ?><br>
                            Invoice Date: <?= date('d-m-Y', strtotime($sale->invoice_date)); ?><br>
                            Payment Terms: <?= htmlspecialchars($sale->payment_terms); ?><br>
                            Due Date: <?= date('d-m-Y', strtotime($sale->invoice_date . ' + ' . $sale->due_days . ' days')); ?><br>
                          <?php 
    // Handle PO Date - Check if exists and valid
    $po_date = '';
    if(!empty($sale->purchase_order_date) && $sale->purchase_order_date != '0000-00-00') {
        $po_timestamp = strtotime($sale->purchase_order_date);
        if($po_timestamp !== false && $po_timestamp > 0) {
            $po_date = date('d-m-Y', $po_timestamp);
        }
    }
    ?>
    
    <?php if(!empty($po_date)): ?>
        PO Date: <?= $po_date; ?><br>
    <?php endif; ?>
                            <?php if(!empty($sale->purchase_order_no)): ?>
                                PO No: <?= htmlspecialchars($sale->purchase_order_no); ?><br>
                            <?php endif; ?>
                         
                        <?php if( $sale->converted == 1): ?>
                            PO Request No: <?= htmlspecialchars($sale->po_no); ?><br>
                            PO Request Date: <?= date('d-m-Y', strtotime($sale->po_date)); ?><br>
                        <?php endif; ?>
                            
                        </td>
                        
                        <td colspan="3">
    <?php if (!empty($transport) && !empty($transport->delivery_mode)): ?>

        <strong>Delivery Mode:</strong> <?= ucfirst($transport->delivery_mode); ?><br>

        <?php if ($transport->delivery_mode === 'courier'): ?>
            Courier Date: <?= date('d-m-Y', strtotime($transport->courier_date)); ?><br>
            Courier Partner: <?= htmlspecialchars($transport->courier_partner); ?><br>
            Docket Number: <?= htmlspecialchars($transport->docket_number); ?><br>

        <?php elseif ($transport->delivery_mode === 'physical'): ?>
            Delivery Date: <?= date('d-m-Y', strtotime($transport->physical_date)); ?><br>
            Delivered By: <?= htmlspecialchars($transport->physical_person); ?><br>

        <?php elseif ($transport->delivery_mode === 'vendor'): ?>
            Vendor Name: <?= htmlspecialchars($transport->vendor_name); ?><br>
            Vendor City: <?= htmlspecialchars($transport->vendor_city); ?><br>
            Delivery Via: <?= htmlspecialchars($transport->delivery_via); ?><br>
        <?php endif; ?>

    <?php else: ?>
        <em>Transportation details not updated yet.</em>
    <?php endif; ?>
</td>
                        </tr>
                    </table>
                    
                    <!-- Items Table -->
                <table class="product-table">
                    <tr class="heading">
                        <td>S.No</td>
                        <td>Name of Product</td>
                        <td>Category</td>
                        <td>Code</td>
                        <td>Remark</td>
                        <td>HSN</td>
                        <td>UOM</td>
                        <td>Qty</td>
                        <td>Selling Price</td>
                        <td>Taxable Value</td>
                        <td>Tax (Rate)</td>
                        <td>Amount</td>
                    </tr>
                    <?php 
                     $i = 1;
                    $total_qty = 0;
                    $total_taxable_value = 0;
                    $total_tax = 0;
                    $total_subtotal = 0;
                   foreach ($sale_items as $row):
                        $tax_amount = $row->cgst_tax + $row->sgst_tax + $row->igst_tax;

                        if ($row->igst_tax > 0) {
                            $tax_rate  = $row->igst;
                            $tax_label = 'IGST';
                        } elseif ($row->cgst_tax > 0 || $row->sgst_tax > 0) {
                            $tax_rate  = $row->cgst + $row->sgst;
                            $tax_label = 'GST';
                        } else {
                            $tax_rate  = 0;
                            $tax_label = 'GST';
                        }
         
                        $product = $this->db->get_where('product', ['id' => $row->product_id])->row();
                        $category = $this->db->get_where('product_category', ['id' => $product->product_category_id])->row();
                        
                        $total_qty += $row->quantity;
                        $total_taxable_value += $row->taxable_value;
                        $total_tax += $tax_amount;
                        $total_subtotal += $row->sub_total;
                    ?>
                    <tr class="item">
                        <td><?= $i++; ?></td>
                        <td><?= $row->product_name; ?></td>
                        <td><?= $category->name ?? 'N/A'; ?></td>
                        <td><?= $product->product_code?></td>
                        <td><?= $row->product_remark ?: 'N/A'; ?></td>
                        <td><?= $row->hsn; ?></td>
                        <td><?= $row->uom_uom; ?></td>
                        <td><?= $row->quantity; ?></td>
                        <td>₹ <?= number_format_i($row->selling_price); ?></td>
                        <td>₹ <?= number_format_i($row->taxable_value); ?></td>
                        <td>
                            ₹ <?= number_format_i($tax_amount); ?>
                            (<?= number_format_i($tax_rate); ?>%)
                        </td>
                        <td>₹ <?= number_format_i($row->sub_total); ?></td>
                    </tr>
                    <?php endforeach; ?>
                
                    <!-- Freight Charges -->
                    <!--<?php if ($sale->additional_cost_type): ?>-->
                    <!--<tr class="item <?= $sale->additional_cost_type == 'internal' ? 'no-print' : '' ?>">-->
                    <!--    <td><?= $i++; ?></td>-->
                    <!--    <td>Freight Charges (<?= ucfirst($sale->additional_cost_type); ?>)</td>-->
                    <!--    <td></td>-->
                    <!--    <td></td>-->
                    <!--    <td>Transport charges</td>-->
                    <!--    <td></td>-->
                    <!--    <td></td>-->
                    <!--    <td>1.00</td>-->
                    <!--    <td>₹ <?= number_format_i($row->freight_selling_price); ?></td>-->
                    <!--    <td>₹ <?= number_format_i($row->freight_taxable_value); ?></td>-->
                    <!--    <td>₹ <?= number_format_i($row->freight_tax_amount); ?> (<?= number_format_i($row->freight_tax_rate); ?>%)</td>-->
                    <!--    <td>₹ <?= number_format_i($row->freight_sub_total); ?></td>-->
                    <!--</tr>-->
                    <!--<?php endif; ?>-->
                    
                   

                    <!-- FINAL TOTAL ROW -->
                    <!--<tr class="total" style="font-weight:bold; background-color:#f2f2f2;">-->
                    <!--    <td colspan="7" style="text-align:right;">Total</td>-->
                    <!--    <td><?= number_format_i($total_qty); ?></td>-->
                    <!--    <td></td>-->
                    <!--    <td>₹ <?= number_format_i($total_taxable_value); ?></td>-->
                    <!--    <td>₹ <?= number_format_i($total_tax); ?></td>-->
                    <!--    <td>₹ <?= number_format_i($total_subtotal); ?></td>-->
                    <!--</tr>-->
                    
                    <!-- Freight Charges -->
                    <?php if ($sale->additional_cost_type): 
                        // ADD THIS LOGIC HERE to update the totals for the table summary
                        $total_qty += 1; 
                        $total_taxable_value += $sale->freight_taxable_value;
                        $total_tax += $sale->freight_tax_amount;
                        $total_subtotal += $sale->freight_sub_total;
                    ?>
                    <tr class="item <?= $sale->additional_cost_type == 'internal' ? 'no-print' : '' ?>">
                        <td><?= $i++; ?></td>
                        <td>Freight Charges (<?= ucfirst($sale->additional_cost_type); ?>)</td>
                        <td></td>
                        <td></td>
                        <td>Transport charges</td>
                        <td></td>
                        <td></td>
                        <td>1.00</td>
                        <td>₹ <?= number_format_i($sale->freight_selling_price); ?></td>
                        <td>₹ <?= number_format_i($sale->freight_taxable_value); ?></td>
                        <td>₹ <?= number_format_i($sale->freight_tax_amount); ?> (<?= number_format_i($sale->freight_tax_rate); ?>%)</td>
                        <td>₹ <?= number_format_i($sale->freight_sub_total); ?></td>
                    </tr>
                    <?php endif; ?>
                    
                    <!-- FINAL TOTAL ROW (Now uses the updated values above) -->
                    <tr class="total" style="font-weight:bold; background-color:#f2f2f2;">
                        <td colspan="7" style="text-align:right;">Total</td>
                        <td><?= number_format_i($total_qty); ?></td>
                        <td></td>
                        <td>₹ <?= number_format_i($total_taxable_value); ?></td>
                        <td>₹ <?= number_format_i($total_tax); ?></td>
                        <td>₹ <?= number_format_i($total_subtotal); ?></td>
                    </tr>
                </table>
                
                <!-- Summary Section -->
                <table>
                    <tr class="heading">
                        <td colspan="6">Amount in Words</td>
                        <td colspan="3">Amounts</td>
                    </tr>
                    <tr class="details">
                        <td colspan="6" style="text-align: center; vertical-align: middle;">
                            <?= strtoupper($this->numbertowords->convert_number(round($sale->total))) . ' RUPEES ONLY'; ?>
                        </td>
                        <td colspan="3">
                            <!-- Product Sub Total Only -->
                            <div><strong>Sub Total:</strong> <span style="float:right;">₹ <?= number_format_i($total_taxable_value); ?></span></div>
                            
                            <!-- Product Tax Only -->
                            <?php if ($total_tax > 0): ?>
                                <div><strong>Tax:</strong> <span style="float:right;">₹ <?= number_format_i($total_tax); ?></span></div>
                            <?php endif; ?>
                
                            <!-- Dedicated Freight Row (Shown separately, not added to Sub Total) -->
                            <?php if ($sale->additional_cost_type): ?>
                                <div><strong>Freight (<?= ucfirst($sale->additional_cost_type); ?>):</strong> 
                                    <span style="float:right;">₹ <?= number_format_i($sale->freight_taxable_value); ?></span>
                                </div>
                                <!-- Show Freight Tax separately if it exists -->
                                <?php if ($sale->freight_tax_amount > 0): ?>
                                    <div><strong>Freight Tax:</strong> 
                                        <span style="float:right;">₹ <?= number_format_i($sale->freight_tax_amount); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                
                            <?php 
                            $rounded_off = round($sale->total) - $sale->total;
                            if ($rounded_off != 0): ?>
                                <div><strong>Rounded Off:</strong> <span style="float:right;">₹ <?= number_format_i($rounded_off); ?></span></div>
                            <?php endif; ?>
                            <hr>
                            <div style="font-weight: bold; font-size: 16px;">Total: <span style="float:right;">₹ <?= number_format_i(round($sale->total)); ?></span></div>
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
                        foreach ($sale_items as $item) {
                            $applied_taxes = [];
                            if ($item->cgst_tax > 0) {
                                $applied_taxes[] = ['type' => 'CGST', 'rate' => $item->cgst_rate, 'amount' => $item->cgst_tax];
                            }
                            if ($item->sgst_tax > 0) {
                                $applied_taxes[] = ['type' => 'SGST', 'rate' => $item->sgst_rate, 'amount' => $item->sgst_tax];
                            }
                            if ($item->igst_tax > 0) {
                                $applied_taxes[] = ['type' => 'IGST', 'rate' => $item->igst_rate, 'amount' => $item->igst_tax];
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
                            <td style="width:60%;">Bank Details</td>
                            <td style="width:40%;" class="text-center">For: <?= $company_setting->company_name; ?></td>
                        </tr>
                        <tr class="details bank-signature-row">
                            <td style="width:60%; min-height:140px; height:140px; vertical-align:top; padding:16px 18px;">
                                <?= $sale->bank_detail; ?>
                            </td>
                            <td style="width:40%; min-height:140px; height:140px; vertical-align:bottom; text-align:center; padding:16px 18px;">
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
                    <p><strong>General Remark:</strong> <?= $sale->external_note ?? 'N/A' ?> </p>
                    </div> <!-- /.invoice-box -->
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
            
        <!-- Buttons -->
            <div class="no-print text-center mt-3">
                <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
                <a href="<?= base_url('sale/pdf/'.base64_encode($sale->id)); ?>" class="btn btn-danger">Download PDF</a>
                <?php if (!empty($sale->box_qr_data)): ?>
                  <!--  <a href="<?= base_url('sale/download_qr_codes/'.base64_encode($sale->id)); ?>" class="btn btn-info">
                        <i class="fas fa-download"></i> Download QR Codes
                    </a>-->
                <?php endif; ?>
            </div>  
        </div> <!-- /.container -->
    </div>
</main>



                <!-- this row will not appear when printing -->
                <div class="row no-print">
                  <div class="col-12">
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<div class="transaction-modal">
  <div class="modal fade" id="transaction-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('sale_transaction');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <div class="card card-secondary">
              <div class="card-header saleDetail light-secondary-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#saleDetail" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('sale_view')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="saleDetail" class="panel-collapse in collapse" style="">
                <div class="card-body sale_detail  m-0 p-0">
                </div>
              </div>
            </div>
            <div class="card card-danger addTransactionCard">
              <div class="card-header addTransaction light-failure-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                    <?=$this->lang->line('sale_payment')?>
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
                    <input type="hidden" name="transaction_type" value="<?=RECEIPT_TRANSACTION_TYPE?>">
                    <input type="hidden" name="entry_id" id="sale_id" value="">
                    <input type="hidden" name="warehouse_id" value="<?= $warehouse->id ?>">
                    <input type="hidden" name="module" value="<?=SALE_MODULE?>">
                    <input type="hidden" name="from_account" id="from_account" value="">
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
                    <?=$this->lang->line('sale_previous_transaction')?>
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
            <?php echo $this->lang->line('sale_delivery');?>
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
                        <?=$this->lang->line('sale_delivery_add')?>
                        <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                      </a>
                    </h5>
                  </div>
                  <div id="addDeliveryDetail" class="panel-collapse in collapse" style="">
                    <div class="card-body add_delivery_detail">
                    
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="delivery_items" value="">
                      <input type="hidden" name="sale_id" value="">
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
                    <?=$this->lang->line('previous_sale_delivery')?>
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
  <div class="modal fade" id="sale_edit">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
    </div>
  </div>
</div>

<!-- Generate Ewaybill Modal -->
<div id="generate_ewaybill_modal" class="modal">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header" style="background-color: white;">
              <h5 class="modal-title">Generate Ewaybill</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
             <div id="calc-wrapper">
              <div id="calculator">
                <a href="<?php echo base_url("sale/ewaybill/".base64_encode($sale->id)); ?>" data-type="2" style="font-size:24px; border-radius: 5px; width:45%;" class="btn btn-primary">Download Json</a>
              </div>
            </div>
          </div>
      </div>
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

    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var sale_id = $(e.relatedTarget).data('sale_id');

      if (!(typeof sale_id === 'undefined')) {

        $('#sale_id').val(sale_id);
            
        $.ajax({
          url: "<?php echo base_url('sale/view')?>/"+sale_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){

            $(".sale_detail").html(data.sale_view);
            $(".add_transaction").html(data.add_transaction);
            $("#from_account").val(data.from_account);
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
              $('#transactionEntries').collapse("hide");
              $('#saleDetail').collapse("show");  
            }
            else
            {
              $('#transactionEntries').collapse("hide");
              $('#addTransaction').collapse("hide");
              $('#saleDetail').collapse("show");  
            }
          }
        });
      }


    });

    $(document).on('hide.bs.modal','#transaction-modal' ,function (e) {
      
      if($('#is_there_change_in_transaction').val() == "true")
      {
        location.reload();
      }
      
    });

    $('#delivery-modal').on('show.bs.modal', function (e) {

      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_id').val(sale_id);
      $('#addNewDeliveryDetail').data('sale_id',sale_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('sale/view_delivery')?>",
          type: "POST",
          data: {
            'sale_id':sale_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            if(data.is_all_products_delivered == true)
            {
              $('#addNewDeliveryDetail').css('display','none');
            }
            else
            {
              $('#addNewDeliveryDetail').css('display','block'); 
            }  

            $(".delivery_entries").html(data.delivery_transaction);
            $(".sale_delivery_detail").html(data.sale_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);

            
          }
        });  
      }      
    });

    $(document).on('hide.bs.modal','#delivery-modal' ,function (e) {
  
      if($('#is_there_change_in_delivery_transaction').val() == "true")
      {
        location.reload();
      }
         
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    $(document).on('click', '.view_delivery_transaction', function (event) {
      $('.view_delivery_transaction_detail').css('display','none');
      $(this).parents('tr').next('tr').toggle(200);
    });

    $(document).on('click','#addNewDeliveryDetail',function(e){

      $('.addDeliveryDetailCard').toggle(200);

      var sale_id = $('#addDeliveryDetailForm').find('input[name="sale_id"]').val();

      $.ajax({
        url: "<?php echo base_url('sale/add_sale_delivery')?>/"+sale_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".add_delivery_detail").html(data.add_delivery_detail);
  
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

    $(document).on('click','#addDeliveryDetailSubmit',function(e){
      e.preventDefault();

      var deliveryDataArray = [];

      $("#product_delivery_item_body").find('tr').each(function () {

        var tr                      = $(this).closest("tr");
        var deliveryData            = {};

        deliveryData['product_id']            = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['warehouse_product_id']  = tr.find('input[name="warehouse_product_id"]').val();
        deliveryData['quantity']              = tr.find('input[name="quantity"]').val();
        deliveryData['cost']                  = tr.find('input[name="quantity"]').data('cost');
        deliveryData['price']                 = tr.find('input[name="quantity"]').data('price');
        deliveryData['selling_price']         = tr.find('input[name="quantity"]').data('selling_price');

        deliveryDataArray.push(JSON.stringify(deliveryData));
      });

      $('#addDeliveryDetailForm').find('input[name="delivery_items"]').val(deliveryDataArray.join('|'));
      $('#addDeliveryDetailForm').trigger('submit');
    });

    $(document).on('submit','#addDeliveryDetailForm',function(e){
      e.preventDefault();

      var isError = false;

      $('form#addDeliveryDetailForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');
          var name  = $(this).attr('name');
          
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
        var sale_id                 = $('form#addDeliveryDetailForm').find('input[name="sale_id"]').val(); 

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('sale/add_sale_delivery')?>",
            type: "POST",
            data: DeliveryDetailFormData,
            dataType: "JSON",
            success: function(data){

              if(data.code == 1)
              {
                $('#addDeliveryDetailSubmit').text('Submit').removeClass('disabled');
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sale_id':sale_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

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
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);

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
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(1);

      var $this                 = $(this);
      var sale_delivery_id  = $(this).data('sale_delivery_id');
      var sale_id           = $('#addDeliveryDetailForm').find('input[name="sale_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('sale/delete_sale_delivery')?>",
          type: "POST",
          data: {
            'sale_delivery_id':sale_delivery_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_delivery_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sale_id':sale_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    $(".delivery_entries").html(data.delivery_transaction);
                    $(".sale_delivery_detail").html(data.sale_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);
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
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.saleDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#saleDetail').collapse("show");
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

    $('body').on('click', '#addTransactionSubmit', function(e) {
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
        var sale_id = $('#sale_id').val();

        $('#addTransactionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled')

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: transactionFormData,
          dataType: "JSON",
          success: function(data){

            $(".transaction_entries").html(data.previous_transaction);

            $('#addTransactionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            $('#is_there_change_in_transaction').val('true');

            $.ajax({
              url: "<?php echo base_url('sale/view')?>/"+sale_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $(".sale_detail").html(data.sale_view);
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
                  $('#transactionEntries').collapse("show");
                  $('#saleDetail').collapse("hide");  
                }
                else
                {
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#saleDetail').collapse("hide");  
                }
              }
            });
          }
        });
      } 

    });

    $(document).on("blur change keyup", "form#addTransactionForm .field_validation" , function (event){
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
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(1);

      var $this           = $(this);
      var transaction_id  = $(this).data('transaction_id');
      var sale_id         = $(this).data('entry_id');

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
            $('#is_there_change_in_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view')?>/"+sale_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".sale_detail").html(data.sale_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#to_account").val(data.to_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      $('#transactionEntries').collapse("show");
                      $('#saleDetail').collapse("hide");  
                    }
                    else
                    {
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#saleDetail').collapse("show");  
                    }
                  }
                });  
              }

            },500);
          }
        });
      }, 500);
    });

    $(document).on('show.bs.modal','#sale_edit', function (e) {
      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_edit').find('#id').val(sale_id);

      $.ajax({
        url: "<?php echo base_url('sale/sale_edit_confirmation')?>",
        type: "POST",
        data:{
          'sale_id': sale_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#sale_edit').find('.modal-content').html(data.sale_edit_modal_body);
        }
      });
    });

  })
</script>