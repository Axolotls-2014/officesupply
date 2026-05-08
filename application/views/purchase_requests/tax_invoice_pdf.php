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
    table-layout: auto; /* allow columns to size naturally */
    word-wrap: break-word;
    margin-bottom: 6px;
}

th, td {
    border: 1px solid #999;
    padding: 6px 5px; /* slightly more padding for breathing room */
    text-align: left;
    vertical-align: middle;
    font-size: 10px;
    word-break: break-word;
    white-space: normal; /* allow heading text to wrap */
    line-height: 1.4; /* improve readability of wrapped text */
}

th {
    background-color: #cfd4ed;
    text-align: center;
    font-weight: bold;
    white-space: normal; /* ensures long headings wrap */
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
<div style="font-family: Arial, sans-serif; line-height: 1.6; text-align: center;">
    <p style="font-size: 20px; font-weight: bold; margin: 0;">Tax Invoice</p>
    <hr>
    <h4><?= $company_setting->company_name; ?></h4>
      <?php 
            if (!empty($company_setting->logo)): 
                $logo_path = FCPATH . 'assets/images/0/' . $company_setting->logo;
                if (file_exists($logo_path)):
                    $logo_data = base64_encode(file_get_contents($logo_path));
            ?>
                <img src="data:image/png;base64,<?= $logo_data ?>" 
                     style="width: 120px; height: auto; object-fit: contain;">
            <?php 
                endif;
            endif; 
            ?>
    <div>
        <?= $company_setting->address_line1; ?><br>
        <?= $company_setting->address_line2; ?>
    </div>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div><strong>Phone:</strong> <?= $company_setting->mobile; ?></div>
        <div><strong>Email:</strong> <?= $company_setting->email; ?></div>
        <div><strong>GSTIN:</strong> <?= $company_setting->gstin; ?></div>
    </div>
</div>

<!-- Header section -->
    <table class="items-table">
  <tr class="heading">
    <td colspan="4" style="width:35%;">Bill To</td>
    <td colspan="3" style="width:25%;">Ship To</td>
    <td colspan="3" style="width:20%;">Transportation Details</td>
    <td colspan="2" style="width:20%;">Purchase Order Details</td>
</tr>

<tr class="details">
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
    </td>

    <td colspan="3" style="width:25%; vertical-align: top;">
        Shipping Details: <?= $customer->shipping_details ?? 'N/A'; ?>
    </td>

    <!--<td colspan="3" style="width:20%; vertical-align: top;">-->
    <!--</td>-->
    <td colspan="3" style="vertical-align: top; font-size: 12px;">
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
        <td style="width: 4%; text-align:center;">S.No</td>
        <td style="width: 20%;">Item</td>
        <td style="width: 8%;">Code</td>
        <td style="width: 8%; text-align:center;">Category</td>
        <td style="width: 12%;">Product Remark</td>
        <td style="width: 10%; text-align:center;">HSN/SAC</td>
        <td style="width: 5%; text-align:center;">Qty</td>
        <td style="width: 5%; text-align:center;">Unit</td>
        <td style="width: 8%; text-align:right;">Price/Unit</td>
        <td style="width: 10%; text-align:right;">Taxable Amount</td>
        <td style="width: 8%; text-align:center;">GST</td>
        <td style="width: 10%; text-align:right;">Final Amt</td>
    </tr>
    <?php 
    $i = 1; 
    $sub_total = 0; 
    $total_gst = 0; 
    $grand_total = 0;
     $total_qty += $order->quantity;
                    $total_taxable_value += $order->taxable_value;
                     
                    $total_subtotal += $order->subtotal;
                    $gst_amount = $order->cgst + $order->sgst + $order->igst;
                    $total_gst_amount += $gst_amount;
    
    foreach ($sale_items as $order): 
        $product = $this->db->get_where('product', ['id' => $order->product_id])->row();
        $tax = $order->taxable_value * 0.18; 
        $final_amount = $order->taxable_value * $order->quantity;
        $sub_total += $order->subtotal;
        $total_gst += $tax;
        $grand_total += $final_amount;
        $category = $this->db->get_where('product_category', ['id' => $product->product_category_id])->row();
        $total_qty += $order->quantity;
        $total_taxable_value += $order->taxable_value;
        $total_subtotal += $order->subtotal;
        $gst_amount = $order->cgst + $order->sgst + $order->igst;
        $total_gst_amount += $gst_amount;
    ?>
    <tr class="item">
        <td class="center"><?= $i++; ?></td>
        <td style="width:25%;">
          <?= $order->product_name ?>
         </td>
           <td><?= $product->product_code?></td>
          <td><?= $category->name ?? 'N/A'; ?></td>
        <td><?= $order->product_remark ?? ''; ?></td>
        <td class="center"><?= $product->hsn ?? ''; ?></td>
        <td class="center"><?= $order->quantity; ?></td>
        <td class="center"><?= $order->uom_name; ?></td>
        <td class="right"><?= number_format($order->cost, 2); ?></td>
        <td class="right"><?= number_format($order->taxable_value, 2); ?></td>
        <td style="width:15%;" class="center">
            <?php 
                $hasTax = false;
            if ($order->cgst_tax > 0 || $order->sgst_tax > 0) {
                echo '₹ ' . number_format_i($order->cgst) . ' (' . number_format_i($order->cgst_tax) . '%)<br>';
                echo '₹ ' . number_format_i($order->sgst) . ' (' . number_format_i($order->sgst_tax) . '%)<br>';
                $hasTax = true;
            }
            if ($order->igst_tax > 0) {
                echo '₹ ' . number_format_i($order->igst) . ' (' . number_format_i($order->igst_tax) . '%)' . '<br>';
                $hasTax = true;
            }
            
            if (!$hasTax) {
                echo 'N/A';
            }
            ?>
        </td>
        <td class="right"><?= number_format($order->subtotal, 2); ?></td>
    </tr>
    <?php endforeach; ?>
         <tr class="total" style="font-weight:bold; background-color:#f2f2f2;">
                <td colspan="6" style="text-align:right;">Total</td>
                <td><?= number_format_i($total_qty); ?></td>
                <td class="no-print"></td>
                <td></td>
                <td>₹ <?= number_format_i($total_taxable_value); ?></td>
                <td>₹ <?= number_format_i($total_gst_amount); ?></td>
                <td>₹ <?= number_format_i($total_subtotal); ?></td>
            </tr>
</table>

<!-- Totals -->
<!-- Totals -->
<!-- Totals -->
<table style="table-layout: fixed;">
    <tr class="heading">
        <td colspan="6">Amount in Words</td>
        <td colspan="6">Amounts</td>
    </tr>
    <tr class="details">
        <td colspan="6" class="amount-in-words">
            <?= strtoupper($this->numbertowords->convert_number(round($sale->total))) . ' RUPEES ONLY'; ?>
        </td>
        <td colspan="6" class="amount-values">
            <div class="no-wrap"><strong>Sub Total:</strong> <span style="float: right;">₹ <?= number_format($sub_total, 2); ?></span></div>
            <div class="no-wrap"><strong>Rounded Off:</strong> 
                <span style="float: right;"> ₹
                    <?php
                        $rounded_off = round($sale->total_taxable_value + $sale->total_tax) - ($sale->total_taxable_value + $sale->total_tax);
                        echo number_format_i($rounded_off);
                    ?>
                </span>
            </div>
            <hr style="margin: 0.5rem 0;">
            <div class="no-wrap" style="font-weight: bold; color: #000;">
                Total: <span style="float: right;">₹ <?= number_format(round($sale->total), 2); ?></span>
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
            $tax_summary[$key]['amount'] += $item->cgst;
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
            $tax_summary[$key]['amount'] += $item->sgst;
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
            $tax_summary[$key]['amount'] += $item->igst;
        }
    }

    foreach ($tax_summary as $row) {
        echo '<tr class="details">';
        echo '<td class="center">' . $row['type'] . '</td>';
        echo '<td class="center">₹ ' . number_format($row['taxable_amount'], 2) . '</td>';
        echo '<td class="center">' . number_format($row['rate'], 2) . ' %</td>';
        echo '<td class="center">₹ ' . number_format($row['amount'], 2) . '</td>';
        echo '</tr>';
    }
    ?>
</table>

<!-- Bank and Terms -->
<table>
    <tr class="heading">
        <td>Bank Details</td>
        <td>Terms & Conditions</td>
        <td>For: <?= $company_setting->company_name; ?></td>
    </tr>
    <tr class="details">
        <td>
            Bank: STATE BANK OF INDIA<br>
            A/C No: 39754828165<br>
            IFSC: SBIN0016740<br>
            Name: OFFICE SUPPLY SOLUTIONS
        </td>
        <td class="center">
            <?= $sale->terms_and_condition; ?><br>
        </td>
        <td class="signature-area">
            <?php 
            if ($company_setting->signature != '') {
                $signature_path = './assets/images/' . $cid . '/' . $company_setting->signature; 
                if (file_exists($signature_path)) {
                    $signature_data = file_get_contents($signature_path);
                    $base64_data = base64_encode($signature_data);
                    echo '<img src="data:image/png;base64,'.$base64_data.'" style="width: 100px;">';
                } 
            } else {
                echo '<br/><br/><br/>';
            }
            ?> 
            <br>Authorised Signature
        </td>
    </tr>
</table>

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