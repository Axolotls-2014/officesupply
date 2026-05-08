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
        max-width: 70px;
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
    <div class="invoice-container">
        <!-- Header Section -->
        <div style="font-family: Arial, sans-serif; line-height: 1.6;">

    <!-- Heading + Logo (Centered + Right) -->
    <table style="width:100%; border-collapse: collapse; border:0;">
        <tr>

            <!-- Left Empty Column -->
            <td style="width:33%; border:0;"></td>

            <!-- Center Heading -->
            <td style="width:34%; text-align:center; border:0;">
                <p style="font-size: 16px; font-weight: bold; margin: 0;">
                    PROFORMA INVOICE
                </p>
            </td>

            <!-- Logo on Right -->
            <td style="width:33%; text-align:right; border:0;">
                <?php
                    $logo_path = FCPATH . 'assets/images/' . $company_setting->cid . '/' . $company_setting->logo;
                    if (file_exists($logo_path)) {
                        $logo_base64 = base64_encode(file_get_contents($logo_path));
                        echo '<img src="data:image/png;base64,' . $logo_base64 . '" 
                              style="width:80px; margin-top:-5px;">';
                    }
                ?>
            </td>

        </tr>
    </table>

    <hr>

    <!-- Company Name -->
    <h4 style="margin: 2px 0 0 0; text-align: center;">
        <?= htmlspecialchars($company_setting->company_name); ?>
    </h4>

    <!-- Address -->
    <div style="text-align: center; margin-top: 2px;">
        <?= htmlspecialchars($warehouse->address_line1); ?><br>

        <?php if(!empty($warehouse->license_no)): ?>
            License No: <?= htmlspecialchars($warehouse->license_no); ?>
        <?php endif; ?>
    </div>

    <!-- GST + State -->
    <div style="text-align: center; margin-top: 5px;">
        <strong>GSTIN:</strong> <?= htmlspecialchars($company_setting->gstin); ?> |
        <strong>State:</strong> <?= htmlspecialchars($company_setting->state_name); ?>
    </div>

</div>

        
        <!-- Invoice and Transport Details -->
        <table class="items-table">
            <tr class="heading">
                <td colspan="4">Bill To</td>
                <td colspan="4">Ship To</td>
                <td colspan="4">Invoice Details</td>
            </tr>
            <tr class="details">
                <td colspan="4">
                    Company Name: <?= $customer_detail->customer_company_name; ?><br>
                    Customer name: <?= $customer_detail->customer_name; ?><br>
                    Address: <?= $customer_detail->address; ?><br>
                    <?php if(!empty($customer_detail->gstin)): ?>
                        GSTIN: <?= $customer_detail->gstin; ?><br>
                    <?php endif; ?>
                    State: <?= $customer_detail->state_name; ?>
                </td>
                <td colspan="4">
                    <?php if(isset($shipping_address)): ?>
                        Name: <?= $shipping_address->shipping_name; ?><br>
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
                <td colspan="4">
                    Invoice No: <?= $proforma_invoice->reference_no; ?><br>
                    Invoice Date: <?= date('d-m-Y', strtotime($proforma_invoice->invoice_date)); ?><br>
                    Payment Terms: <?= htmlspecialchars($proforma_invoice->payment_terms); ?><br>
                    <?php if(!empty($proforma_invoice->purchase_order_date)): ?>
                        PO Date: <?= date('d-m-Y', strtotime($proforma_invoice->purchase_order_date)); ?><br>
                    <?php endif; ?>
                    <?php if(!empty($proforma_invoice->purchase_order_no)): ?>
                        PO No: <?= htmlspecialchars($proforma_invoice->purchase_order_no); ?><br>
                    <?php endif; ?>
                    <?php if(!empty($proforma_invoice->vehicle_no)): ?>
                        Vehicle No: <?= htmlspecialchars($proforma_invoice->vehicle_no); ?><br>
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

            foreach ($proforma_invoice_items as $row) {
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
            <?php if($proforma_invoice->additional_cost_type == 'external' && $proforma_invoice->additional_cost_amount > 0): ?>
            <tr class="item">
                <td><?= $i++; ?></td>
                <td>Freight Charges (External)</td>
                <td></td>
                 <td></td>
                <td>Transport charges</td>
                <td>N/A</td>
                <td>N/A</td>
                <td>1</td>
                <td>₹ <?= number_format_i($proforma_invoice->freight_selling_price); ?></td>
                <td>₹ <?= number_format_i($proforma_invoice->freight_taxable_value); ?></td>
                <td>
                    ₹ <?= number_format_i($proforma_invoice->freight_tax_amount); ?> 
                    (<?= $proforma_invoice->freight_tax_rate; ?>%)
                </td>
                <td>₹ <?= number_format_i($proforma_invoice->freight_sub_total); ?></td>
            </tr>
            <?php 
                // Add freight values to totals
                $total_taxable_value += $proforma_invoice->freight_taxable_value;
                $total_tax += $proforma_invoice->freight_tax_amount;
                $total_subtotal += $proforma_invoice->freight_sub_total;
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
                    <?= strtoupper($this->numbertowords->convert_number(round($proforma_invoice->total))) . ' RUPEES ONLY'; ?>
                </td>
                <td colspan="3" style="line-height: 1.6; vertical-align: top;">
                    <div style="display: flex; justify-content: space-between;">
                        <strong>Sub Total:</strong> <span style="text-align: right;"><?= '₹ ' . number_format_i($total_taxable_value); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <strong>Tax:</strong> <span style="text-align: right;"><?= '₹ ' . number_format_i($total_tax); ?></span>
                    </div>
                    <?php if($proforma_invoice->tds > 0): ?>
                    <div style="display: flex; justify-content: space-between;">
                        <strong>TDS:</strong> <span style="text-align: right;"><?= '₹ ' . number_format_i($proforma_invoice->tds); ?></span>
                    </div>
                    <?php endif; ?>

                    <hr>
                    <div style="display: flex; justify-content: space-between; font-weight: bold; color: #000;">
                        <strong>Total:</strong> 
                        <span style="text-align: right;">₹ <?= number_format_i(round($proforma_invoice->total)); ?></span>
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
            foreach ($proforma_invoice_items as $item) {
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
                <td class="text-center">For: <?= $company_setting->company_name; ?></td>
            </tr>
            <tr class="details">
                <td>
                    <?= $proforma_invoice->bank_detail; ?>
                    <?php if(!empty($payment->ifsc_code)): ?>
                        <br>IFSC Code: <?= htmlspecialchars($payment->ifsc_code); ?>
                    <?php endif; ?>
                </td>
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
        <?php if(!empty($proforma_invoice->qr)): ?>
        <div style="text-align: right; margin-top: 10px;">
            <?php 
            $qr_path = FCPATH . 'uploads/qr_codes/' . $proforma_invoice->qr; 
            if (file_exists($qr_path)): 
            ?>
                <span>Scan to view invoice:</span>
                <img src="<?= base_url('uploads/qr_codes/' . $proforma_invoice->qr); ?>" style="width: 60px; height: auto;">
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- General Remark -->
        <p><strong>General Remark:</strong> <?= $proforma_invoice->external_note ?? 'N/A' ?> </p>
    </div>
</body>
</html>