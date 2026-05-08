<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .card {
        margin: 2px;  /* No margin around the card */
        padding: 2px;  /* No padding inside the card */
        border: none;  /* Remove any border around the card */
    }

    .card-body {
        padding: 0;  /* Remove padding inside the card body */
    }

    .invoice-box {
        width: 100%;  /* Make the invoice box take up the full width of the card */
        padding: 6px;  /* Remove any padding inside the invoice box */
        border: 1px solid #ccc;  /* Add border around the invoice box */
        border-radius: 5px;  /* Optional: rounded corners for the invoice */
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        border-collapse: collapse;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
        border: 1px solid #ccc;  /* Border for table cells */
    }

    .invoice-box table tr.heading td {
        background: #cfd4ed;
        font-weight: bold;
        text-align: center;
        border: 1px solid #999;  /* Border for heading row */
    }

    .invoice-box table tr.details td,
    .invoice-box table tr.item td {
        border: 1px solid #ccc;  /* Borders for item rows */
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .no-border {
        border: none !important;
    }

    .bold {
        font-weight: bold;
    }.w-70
    {
         width: 70%;
    }
</style>



<main class="main-wrapper">
    <div class="main-content">
        <div class="container mt-5 w-70">
            <div class="card shadow">
                <div class="card-body">
                    <div class="invoice-box">

                        <!-- Header section -->
                        <table>
                           <tr style="text-align: center;">
    
        <img src="<?= config_item('base_url2') . '/assets/images/0/' . $company_setting->logo; ?>" style="width: 180px;">
        <p>
            <strong><?= $company_setting->company_name; ?></strong><br>
            <?= $company_setting->address_line1; ?><br>
            <?= $company_setting->address_line2; ?><br>
            GSTIN: <?= $company_setting->gstin; ?>
        </p>
 
</tr>


                            <!-- Billing/Shipping/Transport -->
                            <tr class="heading">
                                <td colspan="2">Bill To</td>
                                <td colspan="2">Ship To</td>
                                <td>Transportation details</td>
                                <td  colspan="3">invoice details</td>
                            </tr>
                            <tr class="details">
                                <td colspan="2">
                                    <?= $customer['customer_company_name'] ?? 'N/A'; ?><br>
                                    <?= $customer['address'] ?? 'N/A'; ?><br>
                                    GSTIN: <?= $customer['gstin'] ?? 'N/A'; ?>
                                </td>
                                <td colspan="2">
                                    <?= $customer['shipping_address'] ?? 'N/A'; ?><br>
                                    <?= $customer['shipping_pincode'] ?? 'N/A'; ?><br>
                                    Delivery Location: <?= $customer['address'] ?? 'N/A'; ?>
                                </td>
                                <td></td>
                                <td  colspan="3">  <strong>Invoice No:</strong> <?= $orders[0]['order_id']; ?><br>
                                    <strong>Invoice Date:</strong> <?= date('d-m-Y'); ?><br>
                                    <strong>Due Date:</strong> <?= date('d-m-Y', strtotime('+15 days')); ?></td>
                            </tr>

                            <!-- Items -->
                            <tr class="heading">
                                <td>S.No</td>
                                <td>Item</td>
                                <td>HSN/SAC</td>
                                <td>Qty</td>
                                <td>Unit Price</td>
                                <td>Taxable Amount</td>
                                <td>GST</td>
                                <td>Final Amt</td>
                            </tr>

                            <?php 
                            $i = 1; $sub_total = 0; $total_gst = 0; $grand_total = 0;
                            foreach ($orders as $order): 
                                $product = $this->db->get_where('product', ['id' => $order['product_id']])->row();
                                $tax = $order['total'] * 0.18;
                                $final_amount = $order['total'] + $tax;
                                $sub_total += $order['total'];
                                $total_gst += $tax;
                                $grand_total += $final_amount;
                            ?>
                            <tr class="item">
                                <td><?= $i++; ?></td>
                                <td><?= $product->name; ?></td>
                                <td><?= $product->hsn; ?></td>
                                <td><?= $order['quantity']; ?></td>
                                <td><?= number_format($order['price'], 2); ?></td>
                                <td><?= number_format($order['total'], 2); ?></td>
                                <td><?= number_format($tax, 2); ?> (18%)</td>
                                <td><?= number_format($final_amount, 2); ?></td>
                            </tr>
                            <?php endforeach; ?>

                            <!-- Totals -->
                            <tr class="bold">
                                <td colspan="5" class="right">Sub Total</td>
                                <td colspan="3"><?= number_format($sub_total, 2); ?></td>
                            </tr>
                            <tr class="bold">
                                <td colspan="5" class="right">Total GST</td>
                                <td colspan="3"><?= number_format($total_gst, 2); ?></td>
                            </tr>
                            <tr class="bold">
                                <td colspan="5" class="right">Grand Total</td>
                                <td colspan="3"><?= number_format($grand_total, 2); ?></td>
                            </tr>
                        </table>

                        <!-- Amount in Words -->
                        <p><strong>Amount in Words:</strong> <?= ucwords(number_format($grand_total, 2)); ?> Rupees Only</p>

                        <!-- Bank and Terms -->
                        <table style="margin-top: 10px;">
                            <tr class="heading">
                                <td>Bank Details</td>
                                <td>Terms & Conditions</td>
                            </tr>
                            <tr class="details">
                                <td>
                                    Bank: STATE BANK OF INDIA<br>
                                    A/C No: 39754828165<br>
                                    IFSC: SBIN0016740<br>
                                    Name: OFFICE SUPPLY SOLUTIONS
                                </td>
                                <td>
                                    Kindly pay as per due date.<br>
                                    Thank you for your business.
                                </td>
                            </tr>
                        </table>

                        <!-- Footer -->
                        <p class="center" style="margin-top: 40px;">For: OFFICE SUPPLY SOLUTIONS<br><br>Authorised Signature</p>
                    </div> <!-- /.invoice-box -->
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
            
            <!-- Buttons -->
            <div class="no-print text-center mt-3">
                <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
                <a href="<?= base_url('Order_requests/download_pdf/' . $orders[0]['order_id']); ?>" class="btn btn-danger">Download PDF</a>
            </div>
        </div> <!-- /.container -->
    </div>
</main>
