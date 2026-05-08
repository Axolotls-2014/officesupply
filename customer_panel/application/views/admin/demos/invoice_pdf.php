<!DOCTYPE html>
<html>
<head>
    <title>Invoice PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        .total-row { font-weight: bold; }
    </style>
</head>
<body>
    <div class="invoice-box">
       <style type="text/css">
    @media print {
        .no-print { display: none; }
    }
    table, tr { border: 1px solid #ccc; }
</style>

<p>&nbsp;</p> 
<div class="container-fluid">
    <div class="row table-responsive">
        <table class="table table-border" align="center" style="max-width: 800px">
            <tr style="background-color: white">
                <td>
                    <img src="<?php echo base_url('/assets/images/0/' . $company_setting->logo); ?>" 
                         alt="Company Logo" 
                         style="width:200px; height:70px;">
                </td>
                <td align="right">
                    <p><strong><?php echo $company_setting->company_name; ?>, </strong><br>
                        <?php echo $company_setting->address_line1; ?><br>
                        <?php echo $company_setting->address_line2; ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <h4><strong>INVOICE</strong></h4>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">
                    <strong>Invoice No:</strong> <?= $orders[0]['order_id'] ?><br>
                    <strong>Invoice Date:</strong> <?= date('Y-m-d') ?><br>
                    <strong>Status:</strong> 
                    <?php if ($total <= 0) { ?>
                        <span style="color: green; font-weight: bold;">PAID</span>
                    <?php } else { ?>
                        <span style="color: red; font-weight: bold;">UNPAID</span>
                    <?php } ?>
                </td>
                <td style="padding-top: 5px;">
                    <strong>Billing & Shipping Address</strong><br/>
                    <?= $this->db->get_where('customer', ['id' => $orders[0]['user_id']])->row()->shipping_address; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table table-striped">
                        <tr>
                            <th>Item Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th align="right">Total</th>
                        </tr>
                        <?php 
                        $total = 0;
                        foreach ($orders as $order) { 
                            $product = $this->db->get_where('product', ['id' => $order['product_id']])->row();
                            $total += $order['total'];
                        ?>
                        <tr>
                            <td><?= $product->name; ?></td>
                            <td><?= $order['quantity']; ?></td>
                            <td><?= $order['price']; ?></td>
                            <td><?= number_format($order['total'], 2); ?></td>
                        </tr>
                        <?php } ?>
                        <tr class="total-row">
                            <td colspan="3"><strong>Total Amount</strong></td>
                            <td><strong><?= number_format($total, 2); ?></strong></td>
                        </tr>
                    </table>
                    <p>* This is an electronically generated invoice, hence no further signature is required.</p>
                </td>
            </tr>
        </table>
    </div>
</div>

    </div>
</body>
</html>
