<!DOCTYPE html>
<html>
<head>
 <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
        margin: 0;
        padding: 0;
    }

    .invoice-container {
        width: 100%;
        max-width: 100%;
        padding: 8px 15px;
        box-sizing: border-box;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* keeps table inside page */
        word-wrap: break-word;
        margin-bottom: 6px;
    }

    th, td {
        border: 1px solid #999;
        padding: 4px 5px;
        text-align: left;
        vertical-align: top;
        font-size: 10px;
        word-break: break-word;
    }

    th {
        background-color: #cfd4ed;
        text-align: center;
        font-weight: bold;
    }

    .heading td {
        background-color: #cfd4ed;
        font-weight: bold;
        text-align: center;
    }

    .center { text-align: center; }
    .right { text-align: right; }
    .bold { font-weight: bold; }

    img {
        max-width: 200px;
        height: auto;
    }

    /* ⚙️  Adjusted widths — S.No ultra narrow, Product wider */
    .items-table td:nth-child(1),
    .items-table th:nth-child(1) { width: 2.2%; text-align: center; }  /* S.No narrow */
    .items-table td:nth-child(2),
    .items-table th:nth-child(2) { width: 20%; }  /* Product Name */
    .items-table td:nth-child(3),
    .items-table th:nth-child(3) { width: 8%; }   /* Category */
    .items-table td:nth-child(4),
    .items-table th:nth-child(4) { width: 6%; }   /* Code */
    .items-table td:nth-child(5),
    .items-table th:nth-child(5) { width: 9%; }   /* Remark */
    .items-table td:nth-child(6),
    .items-table th:nth-child(6) { width: 7%; }   /* HSN */
    .items-table td:nth-child(7),
    .items-table th:nth-child(7) { width: 5%; }   /* UOM */
    .items-table td:nth-child(8),
    .items-table th:nth-child(8) { width: 5%; }   /* Qty */
    .items-table td:nth-child(9),
    .items-table th:nth-child(9) { width: 8%; }   /* Selling Price */
    .items-table td:nth-child(10),
    .items-table th:nth-child(10) { width: 8%; }  /* Taxable Value */
    .items-table td:nth-child(11),
    .items-table th:nth-child(11) { width: 5%; }  /* Tax */
    .items-table td:nth-child(12),
    .items-table th:nth-child(12) { width: 8%; }  /* Amount */

    @page {
        margin: 10mm 8mm 8mm 8mm; /* compact bottom margin */
    }

    @media print {
        body { font-size: 9px; }

        th, td {
            padding: 2px 3px;
            font-size: 8.5px;
        }

        img {
            max-width: 180px;
        }

        .invoice-container {
            border: none;
            box-shadow: none;
            padding: 0;
            margin: 0;
        }

        .no-print {
            display: none !important;
        }

        html, body {
            height: auto !important;
            overflow: hidden !important;
        }
    }
</style>


</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div style="text-align: center; margin-bottom: 10px; position: relative;">

    <!-- Heading -->
    <p style="font-size: 16px; font-weight: bold; margin: 0;">TAX INVOICE</p>
    <hr>

    <!-- Logo positioned to the RIGHT -->
    <?php 
    if (!empty($company_setting->logo)): 
        $logo_path = FCPATH . 'assets/images/0/' . $company_setting->logo;
        if (file_exists($logo_path)):
            $logo_data = base64_encode(file_get_contents($logo_path));
    ?>
        <img src="data:image/png;base64,<?= $logo_data ?>" 
           style="width: 180px; height: auto; object-fit: contain;
       position: absolute; top: -10px; right: 0; margin-top: -5px;">
    <?php 
        endif;
    endif; 
    ?>

    <!-- Company Name (remains perfectly centered) -->
    <h4 style="margin: 5px 0;">
        <?= htmlspecialchars($company_setting->company_name); ?>
    </h4>

    <div>
        <?= htmlspecialchars($warehouse->address_line1); ?><br>
        <?php if(!empty($warehouse->license_no)): ?>
            License No: <?= htmlspecialchars($warehouse->license_no); ?>
        <?php endif; ?>
    </div>

    <div style="margin: 5px 0;">
        <strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin); ?> | 
        <strong>State:</strong> <?= htmlspecialchars($company_setting->state_name); ?>
    </div>

</div>

        <!-- Invoice and Transport Details -->
         <table class="items-table">
            <tr class="heading">
                <td colspan="3">Bill To</td>
                <td colspan="3">Ship To</td>
                <td colspan="3">Invoice Details</td>
                <td colspan="3">Transport Details</td>
            </tr>
            <tr class="details">
                <td colspan="3">
                    Company Name: <?= $customer_detail->customer_company_name; ?><br>
                    Customer name: <?= $customer_detail->customer_name; ?><br>
                    Address: <?= $customer_detail->address; ?><br>
                    <?php if(!empty($customer_detail->gstin)): ?>
                        GSTIN: <?= $customer_detail->gstin; ?><br>
                    <?php endif; ?>
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
                                <!-- Fallback if no shipping address is set -->
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
                    <?php if(!empty($sale->purchase_order_date)): ?>
                        PO Date: <?= date('d-m-Y', strtotime($sale->purchase_order_date)); ?><br>
                    <?php endif; ?>
                    <?php if(!empty($sale->purchase_order_no)): ?>
                        PO No: <?= htmlspecialchars($sale->purchase_order_no); ?><br>
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
        <table>
            <tr class="heading">
                <td>S.No</td>
                <td>Name of Product</td>
                <td>Category</td>
                <td>Code</td>
                <td>Remark</td>
                <td>HSN Code</td>
                <td>UOM</td>
                <td>Qty</td>
                <td>Selling Price</td>
                <td>Taxable Value</td>
                <td>TAX (Rate)</td>
                <td>Amount</td>
            </tr>
            <?php 
            $i = 1;
            $total_quantity = 0;
            $total_price = 0;
            $total_discount_amount = 0;
            $total_taxable_value = 0;
            $total_tax = 0;
            $total_subtotal = 0;

            foreach ($sale_items as $row) {
                $total_quantity += $row->quantity;
                $total_price += $row->selling_price;
                $total_taxable_value += $row->taxable_value;
                
                // Calculate tax amount and percentage
                $tax_amount = $row->cgst_tax + $row->sgst_tax + $row->igst_tax;
                $tax_rate = ($row->taxable_value > 0) ? round(($tax_amount / $row->taxable_value) * 100, 2) : 0;
                
                $total_tax += $tax_amount;
                $total_subtotal += $row->sub_total;
                $product = $this->db->get_where('product', ['id' => $row->product_id])->row();
                $category = $this->db->get_where('product_category', ['id' => $product->product_category_id])->row();
            ?>
            <tr class="item">
                <td><?= $i++; ?></td>
                <td><?= $row->product_name; ?></td>
                <td><?= $category->name ?? 'N/A'; ?></td>
                 <td><?= $product->product_code?></td>
                <td><?= !empty($row->product_remark) ? $row->product_remark : 'N/A'; ?></td>
                <td><?= $row->hsn; ?></td>
                <td><?= $row->uom_uom; ?></td>
                <td><?= $row->quantity; ?></td>
                <td>₹ <?= number_format_i($row->selling_price); ?></td>
                <td>₹ <?= number_format_i($row->taxable_value); ?></td>
                <td>
                    ₹ <?= number_format_i($tax_amount) ?> 
                    (<?= $tax_rate ?>%)
                </td>
                <td>₹ <?= number_format_i($row->sub_total); ?></td>
            </tr>
            <?php } ?>
            
            <!-- Freight Charges -->
            <?php if($sale->additional_cost_type == 'external'): ?>
            <tr class="item">
                <td><?= $i++; ?></td>
                <td>Freight Charges (External)</td>
                <td></td>
                 <td></td>
                <td>Transport charges</td>
                <td>N/A</td>
                <td>N/A</td>
                <td>1</td>
                <td>₹ <?= number_format_i($sale->freight_selling_price); ?></td>
                <td>₹ <?= number_format_i($sale->freight_taxable_value); ?></td>
                <td>
                    ₹ <?= number_format_i($sale->freight_tax_amount); ?> 
                    (<?= $sale->freight_tax_rate; ?>%)
                </td>
                <td>₹ <?= number_format_i($sale->freight_sub_total); ?></td>
            </tr>
            <?php 
                // Add freight values to totals
                $total_taxable_value += $sale->freight_taxable_value;
                 $total_tax += $sale->freight_tax_amount;
                $total_subtotal += $sale->freight_sub_total;
            endif; ?>
        </table>
        
        <!-- Totals -->
        <table>
            <tr class="heading">
                <td colspan="6">Amount in Words</td>
                <td colspan="3">Amounts</td>
            </tr>
            <tr class="details">
                <td colspan="6" style="text-align: center;">
                    <?= strtoupper($this->numbertowords->convert_number(round($sale->total))) . ' RUPEES ONLY'; ?>
                </td>
                <td colspan="3" style="line-height: 1.6; vertical-align: top;">
                    <div style="display: flex; justify-content: space-between;">
                        <strong>Sub Total:</strong> <span style="text-align: right;"><?= '₹ ' . number_format_i($total_taxable_value); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <strong>Tax:</strong> <span style="text-align: right;"><?= '₹ ' . number_format_i($total_tax); ?></span>
                    </div>

                    <!--<?php if($sale->additional_cost_type == 'external' && $sale->additional_cost_amount > 0): ?>-->
                    <!--    <div style="display: flex; justify-content: space-between;">-->
                    <!--        <strong>Freight Charges (18%):</strong> -->
                    <!--        <span style="text-align: right;">+ ₹ <?= number_format_i($sale->additional_cost_amount); ?></span>-->
                    <!--    </div>-->
                    <!--<?php endif; ?>-->

                    <!--<div style="display: flex; justify-content: space-between;">
                        <strong>Rounded Off:</strong> 
                        <span style="text-align: right;">
                            ₹ <?php
                                $rounded_off = round($sale->total) - ($total_taxable_value + $total_tax);
                                echo number_format_i($rounded_off);
                            ?>
                        </span>
                    </div>-->

                    <hr>
                    <div style="display: flex; justify-content: space-between; font-weight: bold; color: #000;">
                        <strong>Total:</strong> 
                        <span style="text-align: right;">₹ <?= number_format_i(round($sale->total)); ?></span>
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
                        foreach ($sale_items as $item) {
                            // Determine which taxes actually apply (amount > 0)
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
                    
                            // Only process taxes that were actually applied
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
                <!--<td>Terms & Conditions</td>-->
                <td class="text-center">For: <?= $company_setting->company_name; ?></td>
            </tr>
            <tr class="details">
                <td>
                    <?= $sale->bank_detail; ?>
                    <?php if(!empty($payment->ifsc_code)): ?>
                        <br>IFSC Code: <?= htmlspecialchars($payment->ifsc_code); ?>
                    <?php endif; ?>
                </td>
                <!--<td>-->
                <!--    <?= $sale->payment_terms ?? 'N/A' ?>-->
                <!--</td>-->
                <td class="text-center">
                    <?php 
                    $signature_data = '';
                    $company_settings = $this->company_settings_model->get_company_records();
                    $cid = $company_settings->cid;

                    if ($company_setting->signature != '') {
                        $signature_path = FCPATH . 'assets/images/' . $cid . '/' . $company_setting->signature; 
                        if (file_exists($signature_path)) {
                            $signature_data = file_get_contents($signature_path);
                            $base64_data = base64_encode($signature_data);
                    ?>
                        <img src="data:image/png;base64,<?= $base64_data ?>" style="width: 80px;">
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
<!-- QR Code -->
<!--<?php if(!empty($qr_code_path)): ?>-->
<!--<div style="text-align: right; margin-top: 10px;">-->
<!--    <span>Scan to view invoice:</span>-->
<!--    <img src="<?= $qr_code_path ?>" style="width: 60px; height: auto;">-->
<!--</div>-->
<!--<?php endif; ?>-->
        
        <!-- General Remark -->
        <p><strong>General Remark:</strong> <?= $sale->external_note ?? 'N/A' ?> </p>
    </div>
</body>
</html>