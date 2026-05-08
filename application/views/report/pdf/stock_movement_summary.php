<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Movement Summary Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .report-title { font-size: 14px; font-weight: bold; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .in-row { background-color: #e8f5e9; }
        .out-row { background-color: #ffebee; }
        .footer { margin-top: 20px; font-size: 8px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name"><?=$company_setting[0]->company_name?></div>
        <div>Stock Movement Summary Report</div>
        <div>Period: <?=date('d-m-Y', strtotime($from_date))?> to <?=date('d-m-Y', strtotime($to_date))?></div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>#</th><th>Date</th><th>Type</th><th>Ref No</th><th>Product</th>
                <th>Branch</th><th>In Qty</th><th>Purchase Amt</th><th>Out Qty</th>
                <th>Sale Amt</th><th>Closing Qty</th><th>Closing Value</th>
            </tr>
        </thead>
        <tbody>
            <?php $sr=1; foreach($movements as $row): ?>
            <tr>
                <td><?=$sr++?></td>
                <td><?=date('d-m-Y', strtotime($row->transaction_date))?></td>
                <td><?=$row->display_type?></td>
                <td><?=$row->reference_no?></td>
                <td><?=$row->product_name?></td>
                <td><?=$row->warehouse_name?></td>
                <td><?=($row->in_out=='IN') ? number_format($row->quantity,2) : '-'?></td>
                <td><?=($row->type=='PURCHASE') ? number_format($row->amount,2) : '-'?></td>
                <td><?=($row->in_out=='OUT') ? number_format($row->quantity,2) : '-'?></td>
                <td><?=($row->type=='SALE') ? number_format($row->amount,2) : '-'?></td>
                <td><strong><?=number_format($row->running_closing_qty,2)?></strong></td>
                <td><strong><?=number_format($row->running_closing_value,2)?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="footer">
        Generated on: <?=date('d-m-Y H:i:s')?>
    </div>
</body>
</html>