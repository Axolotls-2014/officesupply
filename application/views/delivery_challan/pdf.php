<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Challan - <?= $delivery_challan->reference_no ?></title>
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
        max-width: 80px;
        height: auto;
         
    }

    /* ⚙️  Adjusted widths — S.No ultra narrow, Product wider */
    .items-table td:nth-child(1),
    .items-table th:nth-child(1) { width: 2.2%; text-align: center; }  /* S.No narrow */
    .items-table td:nth-child(2),
    .items-table th:nth-child(2) { width: 23%; }  /* Product Name */
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
    .items-table th:nth-child(12) { width: 6%; }  /* Amount */

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
            max-width: 55px;
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
    <div class="invoice-box">
        <!-- Header Section -->
       <div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center; position: relative;">
            <div style="display: inline-block; position: relative; width: 100%;">
            <p style="font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase;">
                Delivery Challan
            </p>

        <?php 
        if (!empty($company_setting->logo)): 
            $logo_path = FCPATH . 'assets/images/0/' . $company_setting->logo;
            if (file_exists($logo_path)):
                $logo_data = base64_encode(file_get_contents($logo_path));
        ?>
            <img src="data:image/png;base64,<?= $logo_data ?>" 
                 style="width: 120px; height: auto; object-fit: contain; position:absolute; right:0; top:0;">
        <?php 
            endif;
        endif; 
        ?>
    </div>

                <hr style="margin: 8px 0 12px 0;">
                <h4 style="margin: 6px 0; font-size: 18px; text-align: center;">
                <?= htmlspecialchars($company_setting->company_name); ?>
                </h4>

            <!--<hr style="margin: 8px 0 12px 0;">-->
            <!--<h4 style="margin: 6px 0; font-size: 18px;"><?= htmlspecialchars($company_setting->company_name); ?></h4>-->
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

        <!-- FIXED: delivery challan and Details with equal column widths -->
          <table class="items-table">
            <tr class="heading">
                <td>Bill To</td>
                <td>Ship To</td>
                <td>Delivery Challan Details</td>
            </tr>
            <tr class="details">
                <td style="width: 33.33%;">
                    <?php if(!empty($customer_detail->customer_company_name)): ?>
                    Company Name: <?= $customer_detail->customer_company_name; ?><br>
                    <?php endif; ?>
                    Customer name: <?= $customer_detail->customer_name; ?><br>
                    Address: <?= $customer_detail->address; ?><br>
                    <?php if(!empty($customer_detail->gstin)): ?>
                    GSTIN: <?= $customer_detail->gstin; ?><br>
                    <?php endif; ?>
                    State: <?= $customer_detail->state_name; ?>
                </td>
                <!--<td style="width: 33.33%;">-->
                <!--    Address: <?= $customer_detail->shipping_address; ?><br>-->
                <!--    Pincode: <?= $customer_detail->shipping_pincode; ?><br>-->
                <!--    State: <?= $customer_detail->shipping_state_name; ?>-->
                <!--</td>-->
                 <td style="width: 33.33%;">
    Address: <?= $delivery_challan->customer_shipping_address ?: '-'; ?><br>
    Pincode: <?= $delivery_challan->customer_shipping_pincode ?: '-'; ?><br>
    City: <?= $shipping_city ? $shipping_city->name : '-'; ?><br>
    State: <?= $shipping_state ? $shipping_state->name : '-'; ?><br>
    Country: <?= $shipping_country ? $shipping_country->name : '-'; ?>
</td>
                <td style="width: 33.33%;">
                    Delivery Challan No: <?= $delivery_challan->reference_no; ?><br>
                    Delivery Challan Date: <?= date('d-m-Y', strtotime($delivery_challan->invoice_date)); ?><br>
                    <?php if(!empty($delivery_challan->payment_terms)): ?>
                    Payment Terms: <?= htmlspecialchars($delivery_challan->payment_terms); ?><br>
                    Due Days: <?= htmlspecialchars($delivery_challan->due_days); ?> Days<br>
                    Due date: <?= htmlspecialchars($delivery_challan->due_date); ?><br>
                    <?php endif; ?>
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
                <td>₹ <?= number_format_i($row->selling_price); ?></td>
                <td>₹ <?= number_format_i($row->taxable_value); ?></td>
                <td>₹ <?= number_format_i($tax_amount); ?> (<?= $tax_rate ?>%)</td>
                <td>₹ <?= number_format_i($row->sub_total); ?></td>
            </tr>
            <?php endforeach; ?>
             <!-- Freight Charges -->
            <?php if ($delivery_challan->additional_cost_type): ?>
            <tr class="item">
                <td><?= $i++; ?></td>
                <td>Freight Charges (<?= ucfirst($delivery_challan->additional_cost_type); ?>)</td>
                <td></td>
                <td>Transport charges</td>
                <td></td>
                <td></td>
                <td>1.00</td>
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
            <table>
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
                        $signature_path = FCPATH . 'assets/images/' . $cid . '/' . $company_setting->signature; 
                        if (file_exists($signature_path)) {
                            $signature_data = base64_encode(file_get_contents($signature_path));
                    ?>
                        <img src="data:image/png;base64,<?= $signature_data ?>" style="width: 100px;">
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
            <span>Scan to view delivery challan:</span>
            <?php 
            $qr_path = FCPATH . 'uploads/qr_codes/' . $delivery_challan->qr; 
            if (file_exists($qr_path)): 
                $qr_data = base64_encode(file_get_contents($qr_path));
            ?>
                <img src="data:image/png;base64,<?= $qr_data ?>" style="width: 80px; height: auto;">
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- General Remark -->
        <p><strong>General Remark:</strong> <?= $delivery_challan->external_note ?? 'N/A' ?> </p>
    </div>
</body>
</html>