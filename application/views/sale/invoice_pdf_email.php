<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h3><?= $company_setting->company_name ?></h3>
<p>
    Invoice No: <?= $sale->reference_no ?><br>
    Date: <?= date('d-m-Y', strtotime($sale->date)) ?>
</p>

<p>
    <strong>Customer:</strong><br>
    <?= $customer_detail->customer_name ?><br>
    <?= $customer_detail->address ?>
</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; foreach ($sale_items as $item): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $item->product_name ?></td>
            <td><?= $item->quantity ?></td>
            <td><?= number_format($item->price, 2) ?></td>
            <td><?= number_format($item->subtotal, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Grand Total: ₹<?= number_format($sale->grand_total, 2) ?></strong></p>

</body>
</html>
