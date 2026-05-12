<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Invoice - <?= $purchase->reference_no ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

        /* Column widths matching purchase order style */
        .items-table td:nth-child(1),
        .items-table th:nth-child(1) { width: 4%; text-align: center; }
        .items-table td:nth-child(2),
        .items-table th:nth-child(2) { width: 18%; }
        .items-table td:nth-child(3),
        .items-table th:nth-child(3) { width: 8%; text-align: center; }
        .items-table td:nth-child(4),
        .items-table th:nth-child(4) { width: 7%; text-align: right; }
        .items-table td:nth-child(5),
        .items-table th:nth-child(5) { width: 6%; text-align: right; }
        .items-table td:nth-child(6),
        .items-table th:nth-child(6) { width: 6%; text-align: right; }
        .items-table td:nth-child(7),
        .items-table th:nth-child(7) { width: 6%; text-align: right; }
        .items-table td:nth-child(8),
        .items-table th:nth-child(8) { width: 7%; text-align: right; }
        .items-table td:nth-child(9),
        .items-table th:nth-child(9) { width: 7%; text-align: right; }
        .items-table td:nth-child(10),
        .items-table th:nth-child(10) { width: 7%; text-align: right; }
        .items-table td:nth-child(11),
        .items-table th:nth-child(11) { width: 8%; text-align: center; }
        .items-table td:nth-child(12),
        .items-table th:nth-child(12) { width: 8%; text-align: right; }
        .items-table td:nth-child(13),
        .items-table th:nth-child(13) { width: 8%; text-align: right; }

        .d-none { display: none !important; }

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
        }
    </style>
</head>
<body>
<div class="invoice-container">
    
    <!-- Header Section: PURCHASE INVOICE with Logo -->
    <div style="text-align: center; margin-bottom: 10px; position: relative;">
        <p style="font-size: 16px; font-weight: bold; margin: 0;">TAX PURCHASE UNDER GST</p>
        <p style="font-size: 14px; font-weight: bold; margin: 2px 0;">PURCHASE INVOICE</p>
        <hr>
        
        <?php 
        if (!empty($company_setting->logo)): 
            $cid_logo = $company_setting->cid ?? 0;
            $logo_full_path = FCPATH . 'assets/images/' . $cid_logo . '/' . $company_setting->logo;
            if (file_exists($logo_full_path)):
                $logo_data = base64_encode(file_get_contents($logo_full_path));
        ?>
            <img src="data:image/png;base64,<?= $logo_data ?>" 
                 style="width: 100px; height: auto; object-fit: contain;
                        position: absolute; top: -5px; right: 0;">
        <?php 
            endif;
        endif; 
        ?>
        
        <h4 style="margin: 5px 0;">
            <?= htmlspecialchars($company_setting->company_name); ?>
        </h4>
        
        <div>
            <?= htmlspecialchars($company_setting->address_line1); ?><br>
            <?php if(!empty($company_setting->address_line2)) echo htmlspecialchars($company_setting->address_line2) . '<br>'; ?>
            <?= htmlspecialchars($company_setting->city_name . ', ' . $company_setting->state_name . ', ' . $company_setting->country_name . ' - ' . $company_setting->pincode); ?>
        </div>
        
        <div style="margin: 5px 0;">
            <strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin); ?> | 
            <strong>State:</strong> <?= htmlspecialchars($company_setting->state_name); ?> |
            <strong>Email:</strong> <?= htmlspecialchars($company_setting->email); ?> |
            <strong>Phone:</strong> <?= htmlspecialchars($company_setting->mobile); ?>
        </div>
    </div>
    
    <!-- Company, Supplier and Purchase Details Table (3 column blocks) -->
    <table class="items-table">
        <tr class="heading">
            <td colspan="4">Supplier Details</td>
            <td colspan="4">Purchase Invoice Details</td>
        </tr>
        <tr class="details">
           
            <td colspan="4">
                <strong>Company Name:</strong> <?= htmlspecialchars($supplier_detail->company_name) ?><br>
                <strong>Address:</strong> <?= htmlspecialchars($supplier_detail->address) ?>
                <?= htmlspecialchars($supplier_detail->city_name . ', ' . $supplier_detail->state_name) ?><br>
                <strong>Phone:</strong> <?= htmlspecialchars($supplier_detail->phone) ?><br>
                <strong>Email:</strong> <?= htmlspecialchars($supplier_detail->email) ?><br>
                <strong>GSTIN:</strong> <?= htmlspecialchars($supplier_detail->gstin) ?>
            </td>
            <td colspan="4">
                <!-- <strong>Invoice No:</strong> <?= $purchase->reference_no; ?><br> -->
                <strong>Invoice Date:</strong> <?= date('d-m-Y', strtotime($purchase->purchase_date)); ?><br>
                <strong>Payment Terms:</strong> <?= htmlspecialchars($purchase->payment_terms ?? 'N/A'); ?><br>
                <?php if (!empty($purchase->due_days)): ?>
                <strong>Due Date:</strong> <?= date('d-m-Y', strtotime($purchase->purchase_date . ' + ' . $purchase->due_days . ' days')); ?><br>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    
 
                    
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
<table class="product-table">
    <tr class="heading">
        <td colspan="6">Amount in Words</td>
        <td colspan="3">Amounts</td>
    </tr>
    <tr class="details">
         <td colspan="6" style="text-align: center; vertical-align: middle; padding: 15px 10px;">
            <strong style="font-size: 14px;"><?= strtoupper($this->numbertowords->convert_number(round($purchase->total))) . ' RUPEES ONLY'; ?></strong>
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
    <!-- Tax Summary Table (CGST/SGST/IGST breakdown) -->
    <table>
        <tr class="heading">
            <th>Tax Type</th>
            <th>Taxable Amount (₹)</th>
            <th>Rate (%)</th>
            <th>Tax Amount (₹)</th>
        </tr>
        <?php
        $tax_summary = [];
        foreach ($purchase_items as $item) {
            if ($item->taxable_value > 0) {
                if ($item->cgst_tax > 0) {
                    $key = 'CGST_' . $item->cgst;
                    if (!isset($tax_summary[$key])) $tax_summary[$key] = ['type'=>'CGST','rate'=>$item->cgst,'taxable'=>0,'amount'=>0];
                    $tax_summary[$key]['taxable'] += $item->taxable_value;
                    $tax_summary[$key]['amount'] += $item->cgst_tax;
                }
                if ($item->sgst_tax > 0) {
                    $key = 'SGST_' . $item->sgst;
                    if (!isset($tax_summary[$key])) $tax_summary[$key] = ['type'=>'SGST','rate'=>$item->sgst,'taxable'=>0,'amount'=>0];
                    $tax_summary[$key]['taxable'] += $item->taxable_value;
                    $tax_summary[$key]['amount'] += $item->sgst_tax;
                }
                if ($item->igst_tax > 0) {
                    $key = 'IGST_' . $item->igst;
                    if (!isset($tax_summary[$key])) $tax_summary[$key] = ['type'=>'IGST','rate'=>$item->igst,'taxable'=>0,'amount'=>0];
                    $tax_summary[$key]['taxable'] += $item->taxable_value;
                    $tax_summary[$key]['amount'] += $item->igst_tax;
                }
            }
        }
        
        if(count($tax_summary) > 0) {
            foreach ($tax_summary as $tax_row) {
                echo '<tr class="details">';
                echo '<td class="center">' . $tax_row['type'] . '</td>';
                echo '<td class="right">₹ ' . number_format($tax_row['taxable'], 2) . '</td>';
                echo '<td class="center">' . number_format($tax_row['rate'], 2) . '%</td>';
                echo '<td class="right">₹ ' . number_format($tax_row['amount'], 2) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4" class="center">No Tax Details Available</td></tr>';
        }
        ?>
    </table>
    
    <!-- Terms & Conditions and Signature Section -->
                    <table>
    <tr class="heading">
        <td width="50%"><strong>Bank Details</strong></td>
        <td width="50%" class="text-center"><strong>For: <?= $company_setting->company_name; ?></strong></td>
    </tr>
    <tr class="details">
        <td style="vertical-align: top;">
            <?= nl2br(htmlspecialchars($purchase->bank_detail ?? '')); ?>
        </td>
        <td class="text-center" style="vertical-align: middle; text-align: center;">
            <?php 
            $signature_data = '';
            $company_settings = $this->company_settings_model->get_company_records();
            $cid = $company_settings->cid ?? 0;

            if (!empty($company_setting->signature)) {
                $signature_path = FCPATH . 'assets/images/' . $cid . '/' . $company_setting->signature; 
                if (file_exists($signature_path)) {
                    $signature_data = file_get_contents($signature_path);
                    $base64_data = base64_encode($signature_data);
            ?>
                <div style="text-align: center;">
                    <img src="data:image/png;base64,<?= $base64_data ?>" style="width: 100px; display: block; margin: 0 auto;">
                </div>
            <?php 
                } else {
                    echo '<br/><br/><br/>';
                }
            } else {
                echo '<br/><br/><br/>';
            }
            ?> 
            <br><strong>Authorised Signature</strong>
        </td>
    </tr>
</table>
                    
    
    <!-- Footer note -->
    <div style="font-size: 8px; text-align: center; margin-top: 8px; color: #666;">
        This is a computer generated invoice - valid without signature.
    </div>
</div>
</body>
</html>