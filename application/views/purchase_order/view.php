<?php 
  $this->load->view('layout/header');

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);
  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);
?>
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

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="https://ossdemo.tastytap.in/product_core"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('purchase_order')?>"><?=$this->lang->line('header_purchase_order')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('purchase_order_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <?php 
            if ($purchase_order->document != NULL) {
          ?>
            <div class="card  no-print">
                <div class="card-header">
                    <h3 class="card-title">Attached documents</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-12">
                        <?php
                          $documents = explode(',', $purchase_order->document);
                          $company_settings = $this->company_settings_model->get_company_records();
                          $cid = $company_settings->cid;
                          
                          foreach ($documents as $document) {
                              $documentPath = base_url('assets/documents/'.$cid.'/purchase_order/' . trim($document));
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
          <?php } ?>
          
           <div class="card">
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
            <?php
                $purchase_order_id = $purchase_order->id;
                $generate_purchase = $this->utility_model->get_records_by_field('purchase','purchase_order_id',$purchase_order_id,$row = true,$check_delete_status = true);

              ?>
              <?php
                if($generate_purchase != '')
                {
              ?>
                    <a href="<?=base_url('purchase/view/'.base64_encode($generate_purchase->id))?>" class="btn-sm btn btn-success">
                      <i class="fas fa-eye"></i> <?=$this->lang->line('purchase_view')?>
                    </a>

              <?php
                }
                else 
                {
              ?>
                    <!-- <a href="<?=base_url('purchase/add_from_purchase_order/'.base64_encode($purchase_order->id))?>" class="btn-sm btn btn-success ">
                      <i class="fas fa-random"></i> <?=$this->lang->line('generate_purchase')?>
                    </a> -->
                    <a href="" class="btn-sm btn btn-success generate_purchase" data-purchase_order_id="<?=$purchase_order->id?>">
                      <i class="fas fa-random"></i> <?=$this->lang->line('generate_purchase')?>
                    </a>
              <?php
                }
              ?>


            

          
              <?php
                if($this->permission_model->has_permission('pdf_download_purchase_order'))
                {
              ?> 
                  <a href="<?=base_url('purchase_order/pdf/'.base64_encode($purchase_order->id))?>" class="btn bg-orange btn-sm"  title="Download Purchase Order">
                    <i class="far fa-file-pdf"></i> Download Purchase Order
                  </a>      
              <?php  
                }
                ?>

            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('purchase_order_view')?></h3>
              <?php 
                if($this->permission_model->has_permission('edit_purchase_order'))
                {
              ?>
              <div class="card-tools">
                <?php

                  if($this->permission_model->has_permission('edit_purchase_order') || $this->permission_model->has_permission('edit_all_purchase_order'))
                  { 
                    
                ?>
                      <a href="<?=base_url('purchase_order/edit/'.base64_encode($purchase_order->id))?>" class="btn btn-info btn-sm"  data-tt="tooltip" title="Edit Purchase Order">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                <?php
                  }
                ?>
              </div>
              <?php 
                }
              ?>
            </div>
            
            <div class="card-body">
              <div class="invoice p-3 mb-3">
                <div class="ribbon-wrapper ribbon-sm">
                  <div class="ribbon bg-info">
                    <?=$this->lang->line('purchase_order')?>
                  </div>
                </div>
                
                <main class="main-wrapper">
                    <div class="main-content">
                        <div class="container mt-5 w-70">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="invoice-box">
                                <div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center; position: relative;">

                                    <div style="display: flex; align-items: center; justify-content: center; position: relative;">
                                        <p style="font-size: 24px; font-weight: bold; margin: 0;">PURCHASE ORDER</p>
                                
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
                                    
                                    <!-- Purchase Order and Details -->
                                    <table>
                                        <tr class="heading">
                                            <td colspan="4" style="width:25%;">Company Details</td>
                                            <td colspan="4" style="width:25%;">Supplier Details</td>
                                            <td colspan="4" style="width:25%;">Purchase Order Details</td>
                                        </tr>
                                        <tr class="details">
                                            <td colspan="4">
                                                <strong><?=$company_setting->company_name?></strong><br>
                                                <?=$company_setting->address_line1?><br>
                                                <?=$company_setting->address_line2?><br>
                                                <?=$company_setting->city_name.', '.$company_setting->state_name?><br>
                                                <?=$this->lang->line('phone')?>: <?=$company_setting->mobile?><br>
                                                <?=$this->lang->line('email')?>: <?=$company_setting->email?><br>
                                                <strong><?=$this->lang->line('gstin')?>: <?=$company_setting->gstin?></strong>
                                            </td>
                                            <td colspan="4">
                                                <strong><?=$supplier_detail->company_name?></strong><br>
                                                <?= ($supplier_detail->address != '') ? ($supplier_detail->address.'<br/>') : '' ?>
                                                <?=$supplier_detail->city_name.', '.$supplier_detail->state_name?><br>
                                                <b><?=$this->lang->line('phone')?></b>: <?=$supplier_detail->phone?><br>
                                                <b><?=$this->lang->line('email')?></b>: <?=$supplier_detail->email?><br>
                                                <b><?=$this->lang->line('gstin')?></b>: <?=$supplier_detail->gstin?>
                                            </td>
                                            <td colspan="4">
                                                PO No: <?= $purchase_order->reference_no; ?><br>
                                                PO Date: <?= date('d-m-Y', strtotime($purchase_order->purchase_order_date)); ?><br>
                                                Payment Terms: <?= htmlspecialchars($purchase_order->payment_terms); ?><br>
                                                 <?php if (!empty($purchase_order->due_date)): ?>
        Due Date: <?= date('d-m-Y', strtotime($purchase_order->due_date)); ?><br>
    <?php endif; ?>

                                                <!--Delivery Date: <?= date('d-m-Y', strtotime($purchase_order->delivery_date)); ?><br>-->
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Items Table -->
<!-- Items Table -->
<table class="product-table">
    <tr class="heading">
        <td>S.No</th>
        <td>Product Name</th>
        <td>Category</th>
        <td>Product Code</th>
        <td>HSN</th>
        <td>Qty</th>
        <td>Purchase Price</th>
        <td>MRP</th>
        <td>Discount</th>  <!-- Discount column -->
        <td>UOM</th>
        <td>Taxable Value</th>
        <td>Tax (Rate)</th>
        <td>Amount</th>
    </tr>
    <?php 
    $i = 1;
    $total_taxable_value = 0;
    $total_tax = 0;
    $total_subtotal = 0;
    $total_quantity = 0;
    $total_cost = 0;
    $total_discount = 0;  // Add total discount variable
    
    foreach ($purchase_order_items as $row):
        $tax_amount = $row->cgst_tax + $row->sgst_tax + $row->igst_tax;
        $tax_rate = $row->taxable_value > 0 ? round(($tax_amount / $row->taxable_value) * 100, 2) : 0;
        $total_taxable_value += $row->taxable_value;
        $total_tax += $tax_amount;
        $total_subtotal += $row->subtotal;
        $total_quantity += $row->quantity;
        $total_cost += $row->cost;
        $total_discount += $row->discount_amount;  // Add to total discount
        
        $product = $this->db->get_where('product', ['id' => $row->product_id])->row();
        $category = $this->db->get_where('product_category', ['id' => $product->product_category_id])->row();
        
        // Calculate discount display text
        $discount_display = '₹ 0.00';
        if ($row->discount_amount > 0) {
            if ($row->discount_type == 1) {
                $discount_display = '₹ ' . number_format_i($row->discount_amount) . ' (' . $row->discount_value . '%)';
            } else {
                $discount_display = '₹ ' . number_format_i($row->discount_amount);
            }
        }
        
        // Get MRP value (selling_price from purchase_order_items or price from product)
        $mrp_value = $row->selling_price > 0 ? $row->selling_price : ($product->price ?? 0);
    ?>
    <tr class="item">
        <td><?= $i++; ?></td>
        <td><?= $row->product_name; ?></td>
        <td><?= $category->name ?? 'N/A'; ?></td>
        <td><?= $product->product_code ?? 'N/A'; ?></td>
        <td><?= $row->hsn ?? 'N/A'; ?></td>
        <td><?= $row->quantity; ?></td>
        <td>₹ <?= number_format_i($row->cost); ?></td>
        <td>₹ <?= number_format_i($mrp_value); ?></td>  <!-- MRP column -->
        <td><?= $discount_display; ?></td>  <!-- Discount column -->
        <td><?= $row->uom_uom; ?></td>
        <td>₹ <?= number_format_i($row->taxable_value); ?></td>
        <td>₹ <?= number_format_i($tax_amount); ?> (<?= $tax_rate ?>%)</td>
        <td>₹ <?= number_format_i($row->subtotal); ?></td>
    <tr>
    <?php endforeach; ?>
    
    <!-- Freight Charges -->
    <?php if ($purchase_order->additional_cost_type && $purchase_order->freight_amount > 0): ?>
    <tr class="item">
        <td><?= $i++; ?></td>
        <td><strong>Freight Charges (<?= ucfirst($purchase_order->additional_cost_type); ?>)</strong></td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>1.00</td>
        <td>-</td>
        <td>₹ <?= number_format_i($purchase_order->freight_amount); ?></td>
        <td>-</td>  <!-- Discount column for freight -->
        <td>-</td>  <!-- UOM column for freight -->
        <td>₹ <?= number_format_i($purchase_order->freight_taxable_value ?? 0); ?></td>
        <td><?php if(($purchase_order->freight_tax_rate ?? 0) > 0): ?>₹ <?= number_format_i($purchase_order->freight_tax_amount ?? 0); ?> (<?= number_format_i($purchase_order->freight_tax_rate); ?>%)<?php else: ?>N/A<?php endif; ?></td>
        <td><strong>₹ <?= number_format_i($purchase_order->freight_sub_total ?? $purchase_order->freight_amount); ?></strong></td>
    </tr>
    <?php 
        $total_taxable_value += ($purchase_order->freight_taxable_value ?? 0);
        $total_tax += ($purchase_order->freight_tax_amount ?? 0);
        $total_subtotal += ($purchase_order->freight_sub_total ?? $purchase_order->freight_amount);
        $total_quantity += 1;
    endif; 
    ?>
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
            <strong style="font-size: 14px;"><?= strtoupper($this->numbertowords->convert_number(round($purchase_order->total))) . ' RUPEES ONLY'; ?></strong>
        </td>
        <td colspan="3">
            <!-- Sub Total (before discount) -->
            <div>
                <strong>Sub Total:</strong> 
                <span style="float:right;">
                    ₹ <?= number_format_i($purchase_order->total_taxable_value + $total_discount); ?>
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
            
            <!-- Freight/Internal Charges -->
            <?php if(isset($purchase_order->freight_amount) && $purchase_order->freight_amount > 0): ?>
                <div>
                    <strong>Freight Charges (<?= ucfirst($purchase_order->additional_cost_type ?? 'Transport'); ?>):</strong> 
                    <span style="float:right;">
                        + ₹ <?= number_format_i($purchase_order->freight_amount); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Tax -->
            <?php if ($purchase_order->total_tax > 0): ?>
                <div>
                    <strong>Tax:</strong> 
                    <span style="float:right;">
                        + ₹ <?= number_format_i($purchase_order->total_tax); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Rounded Off (if any) -->
            <?php 
            $rounded_off = round($purchase_order->total) - $purchase_order->total;
            if ($rounded_off != 0): ?>
                <div>
                    <strong>Rounded Off:</strong> 
                    <span style="float:right;">
                        <?= ($rounded_off > 0) ? '+ ' : '- '; ?>₹ <?= number_format_i(abs($rounded_off)); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <hr>
            
            <!-- Grand Total -->
            <div style="font-weight: bold;">
                Total: 
                <span style="float:right;">
                    ₹ <?= number_format_i(round($purchase_order->total)); ?>
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
                                        foreach ($purchase_order_items as $item) {
                                            if ($item->taxable_value > 0) {
                                                $taxes = [
                                                    'CGST' => ['rate' => $item->cgst, 'amount' => $item->cgst_tax],
                                                    'SGST' => ['rate' => $item->sgst, 'amount' => $item->sgst_tax],
                                                    'IGST' => ['rate' => $item->igst, 'amount' => $item->igst_tax]
                                                ];
                                                
                                                foreach ($taxes as $type => $tax) {
                                                    if ($tax['amount'] > 0) {
                                                        $key = $type.'_'.$tax['rate'];
                                                        
                                                        if (!isset($tax_summary[$key])) {
                                                            $tax_summary[$key] = [
                                                                'type' => $type,
                                                                'rate' => $tax['rate'],
                                                                'taxable_amount' => 0,
                                                                'amount' => 0
                                                            ];
                                                        }
                                                        
                                                        $tax_summary[$key]['taxable_amount'] += $item->taxable_value;
                                                        $tax_summary[$key]['amount'] += $tax['amount'];
                                                    }
                                                }
                                            }
                                        }
                                    
                                        foreach ($tax_summary as $row) {
                                            echo '<tr class="details">';
                                            echo '<td class="text-center">' . htmlspecialchars($row['type']) . '</td>';
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
                                            <td>Terms & Conditions</td>
                                            <td class="text-center">For: <?= $company_setting->company_name; ?></td>
                                        </tr>
                                        <tr class="details">
                                            <td>
                                                <?= $purchase_order->terms_and_condition; ?>
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
                                    </div> <!-- /.invoice-box -->
                                   <!-- <?php if (!empty($purchase_order->internal_note)): ?>
                                    <div class="no-print" style="margin-top: 20px; padding: 10px; border-left: 4px solid #888; background: #f9f9f9;">
                                        <strong>Internal Office Note (Private) : </strong>
                                        <?= $purchase_order->internal_note; ?>
                                    </div>-->
                                    <?php endif; ?>
                                     <?php if (!empty($purchase_order->external_note)): ?>
                                    <div style="margin-top: 20px; padding: 10px; border-left: 4px solid #888; background: #f9f9f9;">
                                        <strong>Note to Supplier:</strong>
                                        <?= $purchase_order->external_note; ?>
                                    </div>
                                    <?php endif; ?>
                                </div> <!-- /.card-body -->
                            </div> <!-- /.card -->
                            

                            <!-- Buttons -->
                            <div class="no-print text-center mt-3">
                                <button onclick="window.print()" class="btn btn-primary">Print Purchase Order</button>
                                <a href="<?= base_url('purchase_order/pdf/'.base64_encode($purchase_order->id)); ?>" class="btn btn-danger">Download PDF</a>
                             
            <!-- Back Button in Header - Right Corner -->
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm" title="Go Back" style="background-color: #6c757d; border-color: #6c757d; color: white;">
                 Back To List
            </a>
        
                            </div>
                            
                        </div> <!-- /.container -->
                    </div>
                </main>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="generate_purchase_modal">
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

    $(document).on('click', ".generate_purchase", function(event){
      event.preventDefault();
      
      var purchase_order_id = $(this).data('purchase_order_id');

      $.ajax({
          url: '<?= base_url('purchase/add_from_purchase_order'); ?>/'+purchase_order_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
              $('#generate_purchase_modal').find('.modal-content').html(data.generate_purchase_modal_body);
              $('#generate_purchase_modal').modal('show');
          },
          error: function (xhr, ajaxOptions, thrownError) {
              Swal.fire({
                  title: 'FAILURE !!',
                  icon: "error",
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                      confirmButton: "btn btn-primary"
                  }
              });
          }
      });
      
    });

    // $(document).on('submit', '#generatePurchaseForm', function(e) {
    //     e.preventDefault();

    //     $('#generatePurchaseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled', 'disabled');
    //     var formData = $('#generatePurchaseForm').serialize();

    //     $.ajax({
    //         url: "<?php echo base_url('purchase/add_from_purchase_order')?>",
    //         type: "POST",
    //         data: formData,
    //         dataType: "JSON",
    //         success: function(response) {
    //             if (response.code == 1) {
    //               // alert('in');
    //                 $('#generate_purchase_modal').modal('hide');
    //                 $('form#generatePurchaseForm #generatePurchaseSubmit').text('Submit').removeAttr('disabled');

    //                 // Remove modal backdrop
    //                 //$('.modal-backdrop').remove();

    //                 Swal.fire({
    //                     text: response.message,
    //                     title: 'SUCCESS !!',
    //                     buttonsStyling: !1,
    //                     confirmButtonText: "Ok, got it!",
    //                     customClass: {
    //                         confirmButton: "btn btn-primary"
    //                     },
    //                     timer: 1000
    //                 });

    //                 // Call any additional functions you need to run on success
    //                 // e.g., refreshing a datatable or reloading a section of the page
    //                 initialize_datatable();
    //             } else {
    //                 Swal.fire({
    //                     text: response.message,
    //                     title: 'FAILURE !!',
    //                     buttonsStyling: !1,
    //                     confirmButtonText: "Ok, got it!",
    //                     customClass: {
    //                         confirmButton: "btn btn-primary"
    //                     },
    //                     timer: 1000
    //                 });

    //                 // Call any additional functions you need to run on failure
    //                 initialize_datatable();
    //             }

    //             $('#generatePurchaseSubmit').text('Submit').removeAttr('disabled');
    //         }
    //     });
    // });

  });
</script>

