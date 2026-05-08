<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Debit Note / Purchase Return - <?= $purchase_return->reference_no ?></title>
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

        /* Column widths (aligned with purchase order style) */
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
        .items-table th:nth-child(9) { width: 8%; text-align: right; }
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
    
    <!-- Header Section: DEBIT NOTE with Logo (same style as Purchase Order) -->
    <div style="text-align: center; margin-bottom: 10px; position: relative;">
        <p style="font-size: 16px; font-weight: bold; margin: 0;">DEBIT NOTE INVOICE</p>
        <hr>
        
        <?php 
        if (!empty($company_setting->logo)): 
            $cid_logo = $company_setting->cid ?? 0;
            $logo_full_path = FCPATH . 'assets/images/' . $cid_logo . '/' . $company_setting->logo;
            if (file_exists($logo_full_path)):
                $logo_data = base64_encode(file_get_contents($logo_full_path));
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
            <?= htmlspecialchars($company_setting->address_line2); ?><br>
            <?= htmlspecialchars($company_setting->city_name . ', ' . $company_setting->state_name . ', ' . $company_setting->country_name); ?><br>
            <?php if(!empty($company_setting->license_no)): ?>
                License No: <?= htmlspecialchars($company_setting->license_no); ?>
            <?php endif; ?>
        </div>
        
        <div style="margin: 5px 0;">
            <strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin); ?> | 
            <strong>State:</strong> <?= htmlspecialchars($company_setting->state_name); ?> |
            <strong>Email:</strong> <?= htmlspecialchars($company_setting->email); ?> |
            <strong>Phone:</strong> <?= htmlspecialchars($company_setting->mobile); ?>
        </div>
    </div>
    
    <!-- Company, Supplier and Return Details Table (3 blocks) -->
    <table class="items-table">
        <tr class="heading">
            <td colspan="4">Supplier Details</td>
            <td colspan="4">Purchase Return Details</td>
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
                <strong>Return No:</strong> <?= $purchase_return->reference_no; ?><br>
                <strong>Return Date:</strong> <?= date('d-m-Y', strtotime($purchase_return->purchase_return_date)); ?><br>
                <strong>Vendor Invoice No:</strong> <?= htmlspecialchars($purchase_return->invoice_no ?? '—'); ?><br>
                <strong>Payment Terms:</strong> <?= htmlspecialchars($purchase_return->payment_terms ?? 'N/A'); ?><br>
                <?php if (!empty($purchase_return->due_days)): ?>
                <strong>Due Date:</strong> <?= date('d-m-Y', strtotime($purchase_return->invoice_date . ' + ' . $purchase_return->due_days . ' days')); ?><br>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    
    <!-- Items Table (same column structure as Purchase Order) -->
    <table class="items-table">
        <tr class="heading">
            <th>S.No</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Code</th>
            <th>HSN</th>
            <th>UOM</th>
            <th>Qty</th>
            <th>Return Cost</th>
            <th>Disc.</th>
            <th>Taxable Value</th>
            <th>Tax (Rate)</th>
            <th>Amount</th>
        </tr>
        <?php 
        $i = 1;
        $total_quantity = 0;
        $total_taxable_value = 0;
        $total_tax = 0;
        $total_subtotal = 0;
        $total_cgst_tax = 0;
        $total_sgst_tax = 0;
        $total_igst_tax = 0;
        
        foreach ($purchase_return_items as $row):
            $tax_amount = $row->cgst_tax + $row->sgst_tax + $row->igst_tax;
            $tax_rate = $row->taxable_value > 0 ? round(($tax_amount / $row->taxable_value) * 100, 2) : 0;
            
            $total_quantity += $row->quantity;
            $total_taxable_value += $row->taxable_value;
            $total_tax += $tax_amount;
            $total_subtotal += $row->subtotal;
            $total_cgst_tax += $row->cgst_tax;
            $total_sgst_tax += $row->sgst_tax;
            $total_igst_tax += $row->igst_tax;
            
            $category_name = $row->category_name ?? 'N/A';
            $product_code = $row->product_code ?? 'N/A';
        ?>
        <tr class="item">
            <td class="center"><?= $i++; ?></td>
            <td><?= htmlspecialchars($row->product_name); ?><br><small><?= htmlspecialchars($row->description); ?></small></td>
            <td><?= htmlspecialchars($category_name); ?></td>
            <td><?= htmlspecialchars($product_code); ?></td>
            <td class="center"><?= $row->hsn ?: '-'; ?></td>
            <td class="center"><?= $row->uom_uom ?: '-'; ?></td>
            <td class="right"><?= number_format($row->quantity, 2); ?></td>
            <td class="right">₹ <?= number_format($row->cost, 2); ?></td>
            <td class="right">₹ <?= number_format($row->discount_amount, 2); ?></td>
            <td class="right">₹ <?= number_format($row->taxable_value, 2); ?></td>
            <td class="center">₹ <?= number_format($tax_amount, 2); ?> (<?= $tax_rate ?>%)</td>
            <td class="right">₹ <?= number_format($row->subtotal, 2); ?></td>
        </tr>
        <?php endforeach; ?>
        
        <!-- Grand Total row summary inside same table for alignment -->
        <tr style="background-color: #f5f5f5; font-weight:bold;">
            <td colspan="6" class="right bold">Totals →</td>
            <td class="right bold"><?= number_format($total_quantity, 2); ?></td>
            <td class="right">—</td>
            <td class="right">—</td>
            <td class="right bold">₹ <?= number_format($total_taxable_value, 2); ?></td>
            <td class="right bold">₹ <?= number_format($total_tax, 2); ?></td>
            <td class="right bold">₹ <?= number_format($total_subtotal, 2); ?></td>
        </tr>
    </table>
    
    <!-- Amount in Words & Totals Section (same two-column approach) -->
    <table>
        <tr class="heading">
            <td colspan="6">Amount in Words</td>
            <td colspan="3">Amounts Summary</td>
        </tr>
        <tr class="details">
            <td colspan="6" style="text-align: center; text-transform: uppercase;">
                <?php 
                $grand_rounded = round($purchase_return->total_taxable_value + $purchase_return->total_tax);
                echo strtoupper($this->numbertowords->convert_number($grand_rounded)) . ' RUPEES ONLY'; 
                ?>
            </td>
            <td colspan="3" style="line-height: 1.6; vertical-align: top;">
                <div style="display: flex; justify-content: space-between;">
                    <strong>Sub Total:</strong> <span>₹ <?= number_format($total_taxable_value, 2); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong>Tax Amount:</strong> <span>₹ <?= number_format($total_tax, 2); ?></span>
                </div>
                <?php 
                $rounded_off = $grand_rounded - ($purchase_return->total_taxable_value + $purchase_return->total_tax);
                if(abs($rounded_off) > 0): ?>
                <div style="display: flex; justify-content: space-between;">
                    <strong>Rounded Off:</strong> <span>₹ <?= number_format($rounded_off, 2); ?></span>
                </div>
                <?php endif; ?>
                <hr>
                <div style="display: flex; justify-content: space-between; font-weight: bold;">
                    <strong>Total (₹):</strong> 
                    <span>₹ <?= number_format($grand_rounded, 2); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong>Paid Amount:</strong> 
                    <span>₹ <?= number_format($this->transaction_model->get_total_transaction_amount($purchase_return->id, PURCHASE_RETURN_MODULE, RECEIPT_TRANSACTION_TYPE), 2); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; color: #1e466e;">
                    <strong>Balance Due:</strong> 
                    <span>₹ <?= number_format($grand_rounded - $this->transaction_model->get_total_transaction_amount($purchase_return->id, PURCHASE_RETURN_MODULE, RECEIPT_TRANSACTION_TYPE), 2); ?></span>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Tax Summary Table (CGST/SGST/IGST) -->
    <table>
        <tr class="heading">
            <th>Tax Type</th>
            <th>Taxable Amount (₹)</th>
            <th>Rate (%)</th>
            <th>Tax Amount (₹)</th>
        </tr>
        <?php
        $tax_summary = [];
        foreach ($purchase_return_items as $item) {
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
        foreach ($tax_summary as $tax_row) {
            echo '<tr class="details">';
            echo '<td class="center">' . $tax_row['type'] . '</td>';
            echo '<td class="right">₹ ' . number_format($tax_row['taxable'], 2) . '</td>';
            echo '<td class="center">' . number_format($tax_row['rate'], 2) . '%</td>';
            echo '<td class="right">₹ ' . number_format($tax_row['amount'], 2) . '</td>';
            echo '</tr>';
        }
        if(empty($tax_summary)) {
            echo '<tr><td colspan="4" class="center">No Tax Details</td></tr>';
        }
        ?>
    </table>
    
    <!-- Terms & Conditions + Signature + Bank Details (combined layout) -->
  <table>
                                    <tr class="heading">
                                        <td>Bank Details</td>
                                        <td class="text-center">For: <?= $company_setting->company_name; ?></td>
                                    </tr>
                                    <tr class="details">
                                        <td>
                                            <?= $purchase_return->bank_detail; ?>
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
    
    <!-- footer note (optional) -->
    <div style="font-size: 9px; text-align: center; margin-top: 8px; color: #555;">
        This is a system generated Debit Note – valid without signature.
    </div>
</div>
</body>
</html>