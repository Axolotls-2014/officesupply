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
</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('delivery_challan')?>"><?=$this->lang->line('delivery_challan_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('delivery_challan_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Shortcuts</h3>
            </div>
            <div class="card-body">
              <?php 
                if($this->permission_model->has_permission('convert_to_sale'))
                {
              ?>
                  <a href="<?=base_url('sale/convert_from_delivery_challan/'.base64_encode($delivery_challan->id))?>" class="btn btn-sm bg-success active mr-1">
                    <i class="fas fa-random"></i> <?=$this->lang->line('convert_to_sale')?>
                  </a>
              <?php 
                }
              ?>
              <?php 
                if($this->permission_model->has_permission('pdf_delivery_challan'))
                {
              ?>
                  <a class="btn btn-sm bg-warning active mr-1" href="<?=base_url('delivery_challan/pdf/'.base64_encode($delivery_challan->id))?>" data-tt="" title="Click here to Download delivery challan">
                      <i class="far fa-file-pdf"></i> <?=$this->lang->line('delivery_challan_pdf')?>
                  </a>
              <?php 
                }
              ?>
            </div>
          </div>
          
        <!--  <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('delivery_challan_view')?></h3>
              <div class="card-tools">
                <?php 
                  if($this->permission_model->has_permission('edit_delivery_challan'))
                  {
                ?>
                    <a href="<?=base_url('delivery_challan/edit/'.base64_encode($delivery_challan->id))?>" class="btn btn-info btn-sm  mr-1"  data-tt="tooltip" title="Edit delivery challan">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                <?php 
                  }
                ?>
              </div>
            </div>-->
            
            <div class="card-body">
              <div class="invoice p-3 mb-3">
                <!--<div class="ribbon-wrapper ribbon-sm">-->
                <!--  <div class="ribbon bg-purple">-->
                <!--    <?=$this->lang->line('delivery_challan')?>-->
                <!--  </div>-->
                <!--</div>-->
                <main class="main-wrapper">
                    <div class="main-content">
                        <div class="container mt-5 w-70">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="invoice-box">
                                  <div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center; position: relative;">

                                    <div style="display: flex; align-items: center; justify-content: center; position: relative;">
                                        <p style="font-size: 24px; font-weight: bold; margin: 0; text-transform: uppercase;">Delivery Challan</p>
                                        <?php if(!empty($company_setting->logo)): ?>
                                        <div style="position: absolute; right: 0;">
                                            <img src="<?= base_url('/assets/images/0/' . $company_setting->logo); ?>" 
                                                 style="width: 130px; height: auto; margin-top: -10px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <hr style="margin: 8px 0 12px 0;">
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
                                    <!-- delivery challan and Details -->
                                    <table>
                                        <tr class="heading">
                                            <td colspan="4" style="width:25%;">Bill To</td>
                                            <td colspan="4" style="width:25%;">Ship To</td>
                                            <td colspan="4" style="width:25%;">delivery challan Details</td>
                                        </tr>
                                        <tr class="details">
                                            <td colspan="4">
                                                <?php if(!empty($customer_detail->customer_company_name)): ?>
                                                Company Name: <?= $customer_detail->customer_company_name; ?><br>
                                                <?php endif; ?>
                                                Customer name: <?= $customer_detail->customer_name; ?><br>
                                                Address: <?= $customer_detail->address; ?><br>
                                                <?php if(!empty($customer_detail->gstin)): ?>
                                                GSTIN: <?= $customer_detail->gstin; ?><br>
                                                <?php endif; ?>
                                                <span class="no-print">Email: <?= $customer_detail->email ?><br></span>
                                                <span class="no-print">Mob: <?= $customer_detail->phone ?><br></span>
                                                State: <?= $customer_detail->state_name; ?>
                                            </td>
                                            <!--<td colspan="4">-->
                                            <!--        Address: <?= $customer_detail->shipping_address; ?><br>-->
                                            <!--        Pincode: <?= $customer_detail->shipping_pincode; ?><br>-->
                                            <!--        State: <?= $customer_detail->shipping_state_name; ?>-->
                                            <!--</td>-->
                                            <td colspan="4">
    Address: <?= $delivery_challan->customer_shipping_address ?: '-'; ?><br>
    Pincode: <?= $delivery_challan->customer_shipping_pincode ?: '-'; ?><br>
    City: <?= $shipping_city ? $shipping_city->name : '-'; ?><br>
    State: <?= $shipping_state ? $shipping_state->name : '-'; ?><br>
    Country: <?= $shipping_country ? $shipping_country->name : '-'; ?>
</td>
                                            <td colspan="4">
                                                Delivery Challan No: <?= $delivery_challan->reference_no; ?><br>
                                                Delivery Challan Date: <?= date('d-m-Y', strtotime($delivery_challan->invoice_date)); ?><br>
                                                <?php if(!empty($delivery_challan->payment_terms)): ?>
                                                Payment Terms: <?= htmlspecialchars($delivery_challan->payment_terms); ?><br>
                                                Due Days :<?= htmlspecialchars($delivery_challan->due_days); ?>Days<br>
                                                Due date :<?= htmlspecialchars($delivery_challan->due_date); ?><br>
                                                <?php endif; ?>
                                                <!--Valid Until: <?= date('d-m-Y', strtotime($delivery_challan->invoice_date . ' + ' . $delivery_challan->valid_days . ' days')); ?><br>-->
                                                <?php if(!empty($delivery_challan->purchase_order_date)): ?>
                                                    PO Date: <?= date('d-m-Y', strtotime($delivery_challan->purchase_order_date)); ?><br>
                                                <?php endif; ?>   
                                                <?php if(!empty($delivery_challan->purchase_order_no)): ?>
                                                    PO No: <?= htmlspecialchars($delivery_challan->purchase_order_no); ?><br>
                                                <?php endif; ?>
                                                <?php if(!empty($delivery_challan->transport_mode)): ?>
                                                Transport Mode: <?= htmlspecialchars($delivery_challan->transport_mode); ?><br>
                                                <?php endif; ?>
                                                <?php if (!empty($delivery_challan->vehicle_no)): ?>
                                                Vehicle No: <?= htmlspecialchars($delivery_challan->vehicle_no); ?><br>
                                                <?php endif; ?>
                                                <?php if (!empty($delivery_challan->lut_no)): ?>
                                                LUT No: <?= htmlspecialchars($delivery_challan->lut_no); ?>
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
                                            <td>Remark</td>
                                            <td>HSN</td>
                                            <td>UOM</td>
                                            <td>Qty</td>
                                            <td class="no-print">Purchase Cost</td>
                                            <td>Selling Price</td>
                                            <td>Taxable Value</td>
                                            <td>Tax (Rate)</td>
                                            <td>Amount</td>
                                        </tr>
                                        <?php 
                                        $i = 1;
                                        $total_taxable_value = 0;
                                        $total_tax = 0;
                                        $total_subtotal = 0;
                                        foreach ($delivery_challan_items as $row):
                                            $tax_amount = $row->cgst_tax + $row->sgst_tax + $row->igst_tax;
                                            $tax_rate = $row->taxable_value > 0 ? round(($tax_amount / $row->taxable_value) * 100, 2) : 0;
                                            $total_taxable_value += $row->taxable_value;
                                            $total_tax += $tax_amount;
                                            $total_subtotal += $row->sub_total;
                                            $product = $this->db->get_where('product', ['id' => $row->product_id])->row();
                                            $category = $this->db->get_where('product_category', ['id' => $product->product_category_id])->row();
                                        ?>
                                        <tr class="item">
                                            <td><?= $i++; ?></td>
                                            <td><?= $row->product_name; ?></td>
                                            <td><?= $category->name ?? 'N/A'; ?></td>
                                            <td><?= $row->product_remark ?: 'N/A'; ?></td>
                                            <td><?= $row->hsn; ?></td>
                                            <td><?= $row->uom_uom; ?></td>
                                            <td><?= $row->quantity; ?></td>
                                            <td class="no-print">₹ <?= number_format_i($row->purchase_cost); ?></td>
                                            <td>₹ <?= number_format_i($row->selling_price); ?></td>
                                            <td>₹ <?= number_format_i($row->taxable_value); ?></td>
                                            <td>₹ <?= number_format_i($tax_amount); ?> (<?= $tax_rate ?>%)</td>
                                            <td>₹ <?= number_format_i($row->sub_total); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                         <!-- Freight Charges -->
                                        <?php if ($delivery_challan->additional_cost_type): ?>
                                        <tr class="item <?= $delivery_challan->additional_cost_type == 'internal' ? 'no-print' : '' ?>">
                                            <td><?= $i++; ?></td>
                                            <td>Freight Charges (<?= ucfirst($delivery_challan->additional_cost_type); ?>)</td>
                                            <td></td>
                                            <td>Transport charges</td>
                                            <td></td>
                                            <td></td>
                                            <td>1.00</td>
                                            <td class="no-print"></td>
                                            <td>₹ <?= number_format_i($row->freight_selling_price); ?></td>
                                            <td>₹ <?= number_format_i($row->freight_taxable_value); ?></td>
                                            <td>₹ <?= number_format_i($row->freight_tax_amount); ?> (<?= number_format_i($row->freight_tax_rate); ?>%)</td>
                                            <td>₹ <?= number_format_i($row->freight_sub_total); ?></td>
                                        </tr>
                                        <?php endif; ?>                                        
                                    </table>
                                    
                                    <!-- Summary Section -->
                                    <table>
                                        <tr class="heading">
                                            <td colspan="6">Amount in Words</td>
                                            <td colspan="3">Amounts</td>
                                        </tr>
                                        <tr class="details">
                                            <td colspan="6" style="text-align: center;">
                                                <?= strtoupper($this->numbertowords->convert_number(round($delivery_challan->total))) . ' RUPEES ONLY'; ?>
                                            </td>
                                            <td colspan="3">
                                                <div><strong>Sub Total:</strong> <span style="float:right;">₹ <?= number_format_i($delivery_challan->total_taxable_value); ?></span></div>
                                                <?php if ($total_tax): ?>
                                                    <div><strong>Tax:</strong> <span style="float:right;">₹ <?= number_format_i($delivery_challan->total_tax); ?></span></div>
                                                <?php endif; ?>
                                                <hr>
                                                <div style="font-weight: bold;">Total: <span style="float:right;">₹ <?= number_format_i(round($delivery_challan->total)); ?></span></div>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                <!-- Profit Summary -->
                                    <?php 
                                    $total_selling_price = $delivery_challan->total;
                                    $gross_profit_loss = $total_selling_price - $delivery_challan->total_purchase_cost;
                                    $profit_margin = ($delivery_challan->total_purchase_cost > 0) ? ($gross_profit_loss / $delivery_challan->total_purchase_cost) * 100 : 0;
                                    ?>
                                    <?php if ($delivery_challan->total_purchase_cost > 0 || $total_selling_price > 0): ?>
                                        <table class="no-print">
                                            <tr class="heading"><td colspan="4"><strong>Profit/Loss Summary</strong></td></tr>
                                            <tr class="details">
                                                <td colspan="2"><strong>Purchase Cost:</strong></td>
                                                <td colspan="2" style="text-align: right;">₹ <?= number_format_i($delivery_challan->total_purchase_cost); ?></td>
                                            </tr>
                                          
                                            <tr class="details">
                                                <td colspan="2"><strong>Selling Price:</strong></td>
                                                <td colspan="2" style="text-align: right;">₹ <?= number_format_i($total_selling_price); ?></td>
                                            </tr>
                                            <tr class="details">
                                                <td colspan="2"><strong>Gross <?= $gross_profit_loss >= 0 ? 'Profit' : 'Loss'; ?>:</strong></td>
                                                <td colspan="2" style="text-align: right; color:<?= $gross_profit_loss >= 0 ? 'green' : 'red' ?>;">₹ <?= number_format_i($gross_profit_loss); ?></td>
                                            </tr>
                                            <tr class="details">
                                                <td colspan="2"><strong>Profit Margin:</strong></td>
                                                <td colspan="2" style="text-align: right; color:<?= $profit_margin >= 0 ? 'green' : 'red' ?>;"><?= number_format_i($profit_margin, 2); ?>%</td>
                                            </tr>
                                        </table>
                                    
                                    <?php endif; ?>                                    
                                    
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
                                        foreach ($delivery_challan_items as $item) {
                                            // Only process if taxable value exists
                                            if ($item->taxable_value > 0) {
                                                // Group taxes by type and rate
                                                $taxes = [
                                                    'CGST' => ['rate' => $item->cgst_rate, 'amount' => $item->cgst_tax],
                                                    'SGST' => ['rate' => $item->sgst_rate, 'amount' => $item->sgst_tax],
                                                    'IGST' => ['rate' => $item->igst_rate, 'amount' => $item->igst_tax]
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
                                                        
                                                        // Only add taxable amount for this tax type if it's actually applied
                                                        $tax_summary[$key]['taxable_amount'] += $item->taxable_value;
                                                        $tax_summary[$key]['amount'] += $tax['amount'];
                                                    }
                                                }
                                            }
                                        }
                                    
                                        // Display the tax summary
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
                                            <td>Bank Details</td>
                                            <td class="text-center">For: <?= $company_setting->company_name; ?></td>
                                        </tr>
                                        <tr class="details">
                                            <td>
                                                <?= $delivery_challan->bank_detail; ?>
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
                                    
                                    <!-- QR Code -->
                                    <?php if(!empty($delivery_challan->qr)): ?>
                                    <div style="text-align: right; margin-top: 10px;">
                                        <span>Scan to view invoice:</span>
                                        <img src="<?= base_url('uploads/qr_codes/' . $delivery_challan->qr) ?>" style="width: 80px; height: auto;">
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- General Remark -->
                                    <p><strong>General Remark:</strong> <?= $delivery_challan->external_note ?? 'N/A' ?> </p>
                                    </div> <!-- /.invoice-box -->
                                </div> <!-- /.card-body -->
                            </div> <!-- /.card -->
                            
                            <!-- Buttons -->
                            <div class="no-print text-center mt-3">
                                <button onclick="window.print()" class="btn btn-primary">Print delivery challan</button>
                                <a href="<?= base_url('delivery_challan/pdf/'.base64_encode($delivery_challan->id)); ?>" class="btn btn-danger">Download PDF</a>
                            </div>
                        </div> <!-- /.container -->
                    </div>
                </main>

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                  <div class="col-12">
                    <!-- Optional print button can be added here -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
  $(document).ready(function(e){
    // Any JavaScript specific to delivery challan view can be added here
  });
</script>