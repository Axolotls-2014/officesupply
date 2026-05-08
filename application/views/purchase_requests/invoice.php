<?php 
  $this->load->view('layout/header');
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
        <section class="content">

<main class="main-wrapper">
    <div class="main-content">
        <div class="container mt-5 w-70" style="max-width:100%; margin:0 auto; float:none;">
            <div class="card shadow">
                <div class="card-body">
                    <div class="invoice-box">
                    <div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center;">
                        <p style="font-size: 20px; font-weight: bold; margin: 0;">Purchase Request</p>
                        <hr>
                        <!-- Optional Logo -->
                         <img src="<?= config_item('base_url2') . '/assets/images/0/' . $company_setting->logo; ?>" style="width: 180px; margin: 10px auto;"> 
                        <h4><?= $company_setting->company_name; ?></h4>
                        <div>
                            <?= $company_setting->address_line1; ?><br>
                            <?= $company_setting->address_line2; ?>
                        </div>
                        <!-- Contact info section -->
                        <div style="display: flex; flex-direction: column; align-items: left; justify-content: left;">
                            <div><strong>Phone:</strong> <?= $company_setting->mobile; ?></div>
                            <div ><strong>Email:</strong> <?= $company_setting->email; ?></div>
                            <div><strong>GSTIN:</strong> <?= $company_setting->gstin; ?></div>
                        </div>
                    </div>
                    <!-- Header section -->
                    <table>
                        <!-- Billing/Shipping/Transport -->
                       <tr class="heading">
                        <td colspan="4" style="width:35%;">Bill To</td>
                        <td colspan="3" style="width:25%;">Ship To</td>
                        <td colspan="3" style="width:20%;">Transportation Details</td>
                        <td colspan="2" style="width:20%;">Purchase Order Details</td>
                    </tr>

                <tr class="details">
                    <!-- Bill To -->
                    <td colspan="4" style="width:35%; vertical-align: top;">
                        <?= $customer->company_name ?? ''; ?><br>
                
                        <?php if ($sale->level_id == 0): ?>
                            Company GST: <?= $customer->gstin ?? 'N/A'; ?><br>
                            Company Address: <?= $customer->billing_details ?? 'N/A'; ?><br>
                        <?php else: ?>
                           
                            <?php
                            $added_by_user = $this->db->get_where('users', ['id' => $sale->added_by])->row();
                            echo htmlspecialchars(($added_by_user->first_name ?? '') . ' ' . ($added_by_user->last_name ?? ''));
                            ?><br>
                
                            Branch Name: <?= $customer->branch ?? 'N/A'; ?><br>
                            Branch GSTIN: <?= $customer->gstin ?? 'N/A'; ?><br>
                            Billing Details: <?= $customer->billing_details ?? 'N/A'; ?><br>
                        <?php endif; ?>
                        <?php if (!empty($order_placed_for)): ?>
    <p><strong>Order Placed By:</strong> <?= htmlspecialchars($order_placed_for) ?></p>
<?php endif; ?>
                    </td>
                
                    <td colspan="3" style="width:25%; vertical-align: top;">
                        Shipping Details: <?= $customer->shipping_details ?? 'N/A'; ?>
                    </td>
                
                    <td colspan="3" style="width:20%; vertical-align: top;">
                        <?php if(!empty($sale->delivery_mode)): ?>
        <strong>Delivery Mode:</strong> <?= ucfirst($sale->delivery_mode); ?><br>

        <?php if($sale->delivery_mode == 'physical'): ?>
            <?php if(!empty($sale->physical_date)): ?>
                <strong>Date:</strong> <?= date('d-m-Y', strtotime($sale->physical_date)); ?><br>
            <?php endif; ?>
            <?php if(!empty($sale->physical_person)): ?>
                <strong>Person:</strong> <?= $sale->physical_person; ?><br>
            <?php endif; ?>

        <?php elseif($sale->delivery_mode == 'courier'): ?>
            <?php if(!empty($sale->courier_date)): ?>
                <strong>Date:</strong> <?= date('d-m-Y', strtotime($sale->courier_date)); ?><br>
            <?php endif; ?>
            <?php if(!empty($sale->courier_partner)): ?>
                <strong>Courier:</strong> <?= $sale->courier_partner; ?><br>
            <?php endif; ?>
            <?php if(!empty($sale->docket_number)): ?>
                <strong>Docket No:</strong> <?= $sale->docket_number; ?><br>
            <?php endif; ?>

        <?php elseif($sale->delivery_mode == 'vendor'): ?>
            <?php if(!empty($sale->vendor_name)): ?>
                <strong>Vendor:</strong> <?= $sale->vendor_name; ?><br>
            <?php endif; ?>
            <?php if(!empty($sale->vendor_city)): ?>
                <strong>City:</strong> <?= $sale->vendor_city; ?><br>
            <?php endif; ?>
            <?php if(!empty($sale->delivery_via)): ?>
                <strong>Delivery Via:</strong> <?= $sale->delivery_via; ?><br>
            <?php endif; ?>
        <?php endif; ?>

    <?php else: ?>
        N/A
    <?php endif; ?>
                    </td>
                
                    <td colspan="2" style="width:20%; vertical-align: top;">
                        <?php if ($sale->sale_invoice_no != NULL) { ?>
                            <strong>Invoice No:</strong> <?= $sale->sale_invoice_no; ?><br>
                            <strong>Invoice Date:</strong> <?= date('d-m-Y', strtotime($sale->approved_at)); ?><br>
                        <?php } ?>
                        <strong>Purchase Req No:</strong> <?= $sale->reference_no; ?><br>
                        <strong>Purchase Req Date:</strong> <?= date('d-m-Y', strtotime($sale->invoice_date)); ?><br>
                    </td>
                </tr>
                    </table>
                      <!-- Items -->
                        <table>
                            <tr class="heading">
                                <td >S.No</td>
                                <td style="width:25%;">Item</td>
                                <td>Code</td>
                                <td style="width:15%;">Category</td>
                                <td>Remark</td>
                                <td>HSN/SAC</td>
                                <td>Qty</td>
                                <td>Unit</td>
                                <td>Price/Unit</td>
                                <td>Taxable Amount</td>
                                <td style="width:15%;">GST</td>
                                <td>Final Amt</td>
                            </tr>
                            <?php 
                                $i = 1; 
                                $sub_total = 0; 
                                $total_gst = 0; 
                                $grand_total = 0;
                                $total_qty = 0;
                                $total_taxable_value = 0;
                                $total_tax = 0;
                                $total_subtotal = 0;
                                $total_gst_amount = 0;
                            
                            foreach ($sale_items as $order): 
                                $product = $this->db->get_where('product', ['id' => $order->product_id])->row();
                                // $tax = $order->taxable_value * 0.18; 
                                $final_amount = $order->taxable_value * $order->quantity;
                                $sub_total += $order->subtotal;
                                // $total_gst += $tax;
                                $grand_total += $final_amount;
                                $category = $this->db->get_where('product_category', ['id' => $product->product_category_id])->row();
                                 
                                $total_qty += $order->quantity;
                                $total_taxable_value += $order->taxable_value;
                                 
                                $total_subtotal += $order->subtotal;
                                // $gst_amount = $order->cgst + $order->sgst + $order->igst;
                                // $total_gst_amount += $gst_amount;
                            ?>
                            <tr class="item">
                                <td><?= $i++; ?></td>
                              <td style="width:25%;">
                                <?= $order->product_name ?>
                            </td>
                            <td><?= $product->product_code?></td>
                             <td><?= $category->name ?? 'N/A'; ?></td>
                                <td><?= $order->product_remark ?? ''; ?></td>
                                <td><?= $product->hsn ?? ''; ?></td>
                                <td><?= $order->quantity; ?></td>
                                <td><?= $order->uom_name; ?></td>
                                <td><?= number_format($order->cost, 2); ?></td>
                                <td><?= number_format($order->taxable_value, 2); ?></td>
                                <td style="width:15%;">
<?php
$gst_amount = 0;

if ($order->igst_tax > 0) {
    // IGST
    $gst_amount = ($order->taxable_value * $order->igst_tax) / 100;
    echo '₹ ' . number_format($gst_amount, 2) . ' (' . number_format($order->igst_tax, 2) . '%)';
}
elseif ($order->cgst_tax > 0 || $order->sgst_tax > 0) {
    // CGST
    if ($order->cgst_tax > 0) {
        $cgst_amt = ($order->taxable_value * $order->cgst_tax) / 100;
        echo '₹ ' . number_format($cgst_amt, 2) . ' (' . number_format($order->cgst_tax, 2) . '%)<br>';
        $gst_amount += $cgst_amt;
    }
    // SGST
    if ($order->sgst_tax > 0) {
        $sgst_amt = ($order->taxable_value * $order->sgst_tax) / 100;
        echo '₹ ' . number_format($sgst_amt, 2) . ' (' . number_format($order->sgst_tax, 2) . '%)';
        $gst_amount += $sgst_amt;
    }
}
else {
    echo 'N/A';
}

// ✅ ADD TO TOTAL GST
$total_gst_amount += $gst_amount;
?>
</td>

                            <td><?= number_format($order->subtotal, 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <!-- Totals -->
                                 <tr class="total" style="font-weight:bold; background-color:#f2f2f2;">
                                    <td colspan="6" style="text-align:right;">Total</td>
                                    <td><?= number_format_i($total_qty); ?></td>
                                    <td class="no-print"></td>
                                    <td></td>
                                    <td>₹ <?= number_format_i($total_taxable_value); ?></td>
                                    <td>₹ <?= number_format_i($total_gst_amount); ?></td>
                                    <td>₹ <?= number_format_i($total_subtotal); ?></td>
                                </tr>
                            <table>
                                <!-- Billing/Shipping/Transport -->
                                <tr class="heading">
                                    <td colspan="6" style="width:50%;">Amount in Words</td>
                                    <td colspan="6" style="width:50%;">Amounts</td>
                                </tr>
                                <tr class="details">
                                    <!-- Amount in Words -->
                                    <td colspan="6" style="width:50%; text-align: center;">
                                    <?= strtoupper($this->numbertowords->convert_number(round($sale->total))) . ' RUPEES ONLY'; ?>
                                    </td>
                            
                                    <!-- Amounts -->
                                    <td colspan="6" style="width:50%; font-size: 0.95rem; line-height: 1.6;">
                                        <div><strong>Sub Total:</strong> <span style="float: right;">₹ <?= number_format($sub_total, 2); ?></span></div>
                                        
                                        <div><strong><?= $this->lang->line('rounded_off'); ?>:</strong> 
                                            <span style="float: right;">₹ 
                                                <?php
                                                    $rounded_off = round($sale->total_taxable_value + $sale->total_tax) - ($sale->total_taxable_value + $sale->total_tax);
                                                    echo number_format_i($rounded_off);
                                                ?>
                                            </span>
                                        </div>
                                        <hr style="margin: 0.5rem 0;">
                                        
                                        <div style="font-size: 1.1rem; font-weight: bold; color: #000;">
                                        Total: <span style="float: right;">₹ <?= number_format(round($sale->total), 2); ?></span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%;">
                                <tr class="heading">
                                    <td>Tax Type</td>
                                    <td>Taxable Amount (₹)</td>
                                    <td>Rate (%)</td>
                                    <td>Tax Amount (₹)</td>
                                </tr>
                            
                                <?php
                                $tax_summary = [];
                            
                                foreach ($sale_items as $item) {
                                    if ($item->cgst_tax > 0) {
                                        $key = 'CGST_' . $item->cgst_tax; 
                                        if (!isset($tax_summary[$key])) {
                                            $tax_summary[$key] = [
                                                'type' => 'CGST',
                                                'rate' => $item->cgst_tax,
                                                'taxable_amount' => 0,
                                                'amount' => 0,
                                            ];
                                        }
                                        $tax_summary[$key]['taxable_amount'] += $item->taxable_value;
                                        $tax_summary[$key]['amount'] += ($item->taxable_value * $item->cgst_tax) / 100;
                                        // $tax_summary[$key]['amount'] += $item->cgst;
                                    }
                            
                                    if ($item->sgst_tax > 0) {
                                        $key = 'SGST_' . $item->sgst_tax; 
                                        if (!isset($tax_summary[$key])) {
                                            $tax_summary[$key] = [
                                                'type' => 'SGST',
                                                'rate' => $item->sgst_tax,
                                                'taxable_amount' => 0,
                                                'amount' => 0,
                                            ];
                                        }
                                        $tax_summary[$key]['taxable_amount'] += $item->taxable_value;
                                         $tax_summary[$key]['amount'] += ($item->taxable_value * $item->sgst_tax) / 100;
                                        // $tax_summary[$key]['amount'] += $item->sgst;
                                    }
                            
                                    if ($item->igst_tax > 0) {
                                        $key = 'IGST_' . $item->igst_tax; 
                                        if (!isset($tax_summary[$key])) {
                                            $tax_summary[$key] = [
                                                'type' => 'IGST',
                                                'rate' => $item->igst_tax,
                                                'taxable_amount' => 0,
                                                'amount' => 0,
                                            ];
                                        }
                                        $tax_summary[$key]['taxable_amount'] += $item->taxable_value;
                                        $tax_summary[$key]['amount'] += ($item->taxable_value * $item->igst_tax) / 100;
                                        // $tax_summary[$key]['amount'] += $item->igst;
                                    }
                                }
                            
                                foreach ($tax_summary as $row) {
                                    echo '<tr class="details">';
                                    echo '<td class="text-center">' . $row['type'] . '</td>';
                                    echo '<td class="text-center">₹ ' . number_format($row['taxable_amount'], 2) . '</td>';
                                    echo '<td class="text-center">' . number_format($row['rate'], 2) . ' %</td>';
                                    echo '<td class="text-center">₹ ' . number_format($row['amount'], 2) . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </table>
                            
                            
                                     


        <!-- Bank and Terms -->
        <table>
            <tr class="heading">
                <td>Bank Details</td>
                <td>Terms & Conditions</td>
                <td class="text-center">For: <?= $company_setting->company_name; ?></td>

            </tr>
            <tr class="details">
                <td>
                    Bank: STATE BANK OF INDIA<br>
                    A/C No: 39754828165<br>
                    IFSC: SBIN0016740<br>
                    Name: OFFICE SUPPLY SOLUTIONS
                </td>
                <td class="text-center">
                    <?= $sale->terms_and_condition; ?><br>
                </td>
                 <td class="text-center">
                    <br>
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
        ?> <br>
                    Authorised Signature
                </td>
            </tr>

        </table>
<!-- Product Remarks Section -->
<?php 
$hasRemarks = false;
foreach ($sale_items as $pro) {
    if (!empty($pro->product_remark)) {
        $hasRemarks = true;
        break;
    }
}
?>

<!--<?php if ($hasRemarks): ?>-->
<!--<div style="margin-top: 10px;">-->
<!--    <p><strong>Product Remarks:</strong></p>-->
<!--    <ul style="padding-left: 20px; margin-top: 5px;">-->
<!--        <?php foreach ($sale_items as $pro): ?>-->
<!--            <?php if (!empty($pro->product_remark)): ?>-->
<!--                <li>-->
<!--                    <strong><?= $pro->product_name ?>:</strong> -->
<!--                    <?= $pro->product_remark ?>-->
<!--                </li>-->
<!--            <?php endif; ?>-->
<!--        <?php endforeach; ?>-->
<!--    </ul>-->
<!--</div>-->
<!--<?php endif; ?>-->

<!-- General Remark -->
<p><strong>General Remark:</strong> <?= $sale->external_note ?? 'N/A' ?> </p>

     <!-- Footer -->
                    </div> <!-- /.invoice-box -->
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
            
            
            
            
            
            <!-- Buttons -->
            <div class="no-print text-center mt-6">
                <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
                <!--<a href="<?= base_url('Order_requests/download_pdf/' . $orders[0]['order_id']); ?>" class="btn btn-danger">Download PDF</a>-->
                <a href="<?= base_url('Order_requests/download_pdf1/' . base64_encode($sale->id)); ?>" class="btn btn-danger">Download PDF</a>
            </div>
        </div> <!-- /.container -->
    </div>
</main>
 </section>
    </div>
</div>

<?php $this->load->view('layout/footer');?>
