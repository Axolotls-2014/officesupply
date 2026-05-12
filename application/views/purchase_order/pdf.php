<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - <?= $purchase_order->reference_no ?></title>
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
            table-layout: fixed;
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
            max-width: 70px;
            height: auto;
        }

        /* Column widths */
        .items-table td:nth-child(1),
        .items-table th:nth-child(1) { width: 5%; text-align: center; }
        .items-table td:nth-child(2),
        .items-table th:nth-child(2) { width: 20%; }
        .items-table td:nth-child(3),
        .items-table th:nth-child(3) { width: 10%; }
        .items-table td:nth-child(4),
        .items-table th:nth-child(4) { width: 8%; }
        .items-table td:nth-child(5),
        .items-table th:nth-child(5) { width: 9%; text-align: center; }
        .items-table td:nth-child(6),
        .items-table th:nth-child(6) { width: 6%; text-align: center; }
        .items-table td:nth-child(7),
        .items-table th:nth-child(7) { width: 7%; text-align: right; }
        .items-table td:nth-child(8),
        .items-table th:nth-child(8) { width: 7%; text-align: right; }
        .items-table td:nth-child(9),
        .items-table th:nth-child(9) { width: 8%; text-align: center; }
        .items-table td:nth-child(10),
        .items-table th:nth-child(10) { width: 10%; text-align: right; }
        .items-table td:nth-child(11),
        .items-table th:nth-child(11) { width: 10%; text-align: center; }
        .items-table td:nth-child(12),
        .items-table th:nth-child(12) { width: 10%; text-align: right; }

        @page {
            margin: 10mm 8mm 8mm 8mm;
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
    <div class="invoice-container">
        
        <!-- Header Section (Matching Sale PDF style) -->
        <div style="text-align: center; margin-bottom: 10px; position: relative;">
            <p style="font-size: 16px; font-weight: bold; margin: 0;">PURCHASE ORDER</p>
            <hr>
            
            <?php 
            if (!empty($company_setting->logo)): 
                $logo_path = FCPATH . 'assets/images/0/' . $company_setting->logo;
                if (file_exists($logo_path)):
                    $logo_data = base64_encode(file_get_contents($logo_path));
            ?>
                <img src="data:image/png;base64,<?= $logo_data ?>" 
                     style="width: 120px; height: auto; object-fit: contain;
                            position: absolute; top: -10px; right: 0; margin-top: 10px;">
            <?php 
                endif;
            endif; 
            ?>
            
            <h4 style="margin: 5px 0;">
                <?= htmlspecialchars($company_setting->company_name); ?>
            </h4>
            
            <div>
                <?= htmlspecialchars($company_setting->address_line1); ?><br>
                <?php if(!empty($warehouse_detail->license_no)): ?>
                    License No: <?= htmlspecialchars($warehouse_detail->license_no); ?>
                <?php endif; ?>
            </div>
            
            <div style="margin: 5px 0;">
                <strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin); ?> | 
                <strong>State:</strong> <?= htmlspecialchars($company_setting->state_name); ?>
            </div>
        </div>
        
        <!-- Company, Supplier and PO Details Table -->
        <table class="items-table">
            <tr class="heading">
                <td colspan="4">Company Details</td>
                <td colspan="4">Supplier Details</td>
                <td colspan="4">Purchase Order Details</td>
            </tr>
            <tr class="details">
                <td colspan="4">
                    <strong><?= htmlspecialchars($company_setting->company_name) ?></strong><br>
                    <?= htmlspecialchars($company_setting->address_line1) ?><br>
                    <?= htmlspecialchars($company_setting->address_line2) ?><br>
                    <?= htmlspecialchars($company_setting->city_name . ', ' . $company_setting->state_name) ?><br>
                    <strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin) ?>
                </td>
                <td colspan="4">
                    <strong><?= htmlspecialchars($supplier_detail->company_name) ?></strong><br>
                    <?= htmlspecialchars($supplier_detail->address) ?><br>
                    <?= htmlspecialchars($supplier_detail->city_name . ', ' . $supplier_detail->state_name) ?><br>
                    <strong>Phone:</strong> <?= htmlspecialchars($supplier_detail->phone) ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($supplier_detail->email) ?><br>
                    <strong>GSTIN:</strong> <?= htmlspecialchars($supplier_detail->gstin) ?>
                </td>
                <td colspan="4">
                    <strong>PO No:</strong> <?= $purchase_order->reference_no; ?><br>
                    <strong>PO Date:</strong> <?= date('d-m-Y', strtotime($purchase_order->purchase_order_date)); ?><br>
                    <strong>Payment Terms:</strong> <?= htmlspecialchars($purchase_order->payment_terms); ?><br>
                    <?php if (!empty($purchase_order->due_date)): ?>
                        <strong>Due Date:</strong> <?= date('d-m-Y', strtotime($purchase_order->due_date)); ?><br>
                    <?php endif; ?>
                    <strong>Delivery Date:</strong> <?= date('d-m-Y', strtotime($purchase_order->delivery_date)); ?>
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
        <!-- Totals Section -->
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
                <th>Tax Type</th>
                <th>Taxable Amount (₹)</th>
                <th>Rate (%)</th>
                <th>Tax Amount (₹)</th>
            </tr>
            <?php
            $tax_summary = [];
            foreach ($purchase_order_items as $item) {
                if ($item->taxable_value > 0) {
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
            }
            
            // Add freight tax if exists
            if ($purchase_order->additional_cost_type == 'external' && $purchase_order->freight_tax_amount > 0) {
                if ($purchase_order->freight_cgst > 0) {
                    $key = 'CGST_' . $purchase_order->freight_cgst;
                    if (!isset($tax_summary[$key])) {
                        $tax_summary[$key] = ['type' => 'CGST', 'rate' => $purchase_order->freight_cgst, 'taxable_amount' => 0, 'amount' => 0];
                    }
                    $tax_summary[$key]['taxable_amount'] += $purchase_order->freight_taxable_value;
                    $tax_summary[$key]['amount'] += $purchase_order->freight_cgst_tax;
                }
                if ($purchase_order->freight_sgst > 0) {
                    $key = 'SGST_' . $purchase_order->freight_sgst;
                    if (!isset($tax_summary[$key])) {
                        $tax_summary[$key] = ['type' => 'SGST', 'rate' => $purchase_order->freight_sgst, 'taxable_amount' => 0, 'amount' => 0];
                    }
                    $tax_summary[$key]['taxable_amount'] += $purchase_order->freight_taxable_value;
                    $tax_summary[$key]['amount'] += $purchase_order->freight_sgst_tax;
                }
                if ($purchase_order->freight_igst > 0) {
                    $key = 'IGST_' . $purchase_order->freight_igst;
                    if (!isset($tax_summary[$key])) {
                        $tax_summary[$key] = ['type' => 'IGST', 'rate' => $purchase_order->freight_igst, 'taxable_amount' => 0, 'amount' => 0];
                    }
                    $tax_summary[$key]['taxable_amount'] += $purchase_order->freight_taxable_value;
                    $tax_summary[$key]['amount'] += $purchase_order->freight_igst_tax;
                }
            }
            
            foreach ($tax_summary as $row) {
                echo '<tr class="details">';
                echo '<td class="center">' . $row['type'] . '</td>';
                echo '<td class="center">₹ ' . number_format($row['taxable_amount'], 2) . '</td>';
                echo '<td class="center">' . number_format($row['rate'], 2) . '%</td>';
                echo '<td class="center">₹ ' . number_format($row['amount'], 2) . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
        
        <!-- Terms and Signature -->
        <table>
            <tr class="heading">
                <td>Terms & Conditions</td>
                <td class="text-center">For: <?= $company_setting->company_name; ?></td>
            </tr>
            <tr class="details">
                <td style="vertical-align: top;">
                  <?= nl2br($purchase_order->terms_and_condition ?: 'N/A'); ?>
                </td>
                <td class="text-center" style="vertical-align: bottom;">
                    <?php 
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
                        echo '';
                    }
                    ?> 
                    <br>Authorised Signature
                </td>
            </tr>
        </table>
        
    </div>
</body>
</html>