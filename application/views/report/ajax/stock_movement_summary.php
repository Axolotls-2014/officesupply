<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-exchange-alt"></i> Stock Movement Summary Report
            <?php if($from_date && $to_date): ?>
                (<?=date('d-m-Y', strtotime($from_date))?> to <?=date('d-m-Y', strtotime($to_date))?>)
            <?php endif; ?>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="movementTable">
                <thead class="sticky-header">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th>Product Name</th>
                        <th>Branch</th>
                        <th>Opening Qty</th>
                        <th>Alert Qty</th>
                        <th>In Qty</th>
                        <th>Purchase Amount (₹)</th>
                        <th>Out Qty</th>
                        <th>Sale Amount (₹)</th>
                        <th>Closing Stock</th>
                        <th>Closing Value (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sr_no = 1;
                    $total_in_qty = 0;
                    $total_out_qty = 0;
                    $total_purchase_amt = 0;
                    $total_sale_amt = 0;
                    ?>
                    
                    <?php foreach($movements as $row): 
                        $total_in_qty += $row->in_qty;
                        $total_out_qty += $row->out_qty;
                        $total_purchase_amt += $row->purchase_amount;
                        $total_sale_amt += $row->sale_amount;
                        
                        $row_class = '';
                        if($row->closing_qty <= $row->alert_qty && $row->alert_qty > 0) {
                            $row_class = 'alert-row';
                        } elseif($row->in_qty > 0) {
                            $row_class = 'in-row';
                        } elseif($row->out_qty > 0) {
                            $row_class = 'out-row';
                        }
                    ?>
                    <tr class="<?=$row_class?>">
                        <td><?=$sr_no++?></td>
                        <td><?=date('d-m-Y', strtotime($row->transaction_date))?></td>
                        <td><?=$row->transaction_type?></td>
                        <td><?=$row->product_name?></td>
                        <td><?=$row->branch_name ?? '-'?></td>
                        <td><?=number_format($row->opening_qty, 2)?></td>
                        <td class="<?=($row->closing_qty <= $row->alert_qty && $row->alert_qty > 0) ? 'text-red' : ''?>">
                            <?=number_format($row->alert_qty, 2)?>
                        </td>
                        <td><?=number_format($row->in_qty, 2)?></td>
                        <td><?=number_format($row->purchase_amount, 2)?></td>
                        <td><?=number_format($row->out_qty, 2)?></td>
                        <td><?=number_format($row->sale_amount, 2)?></td>
                        <td class="<?=($row->closing_qty <= $row->alert_qty && $row->alert_qty > 0) ? 'text-red' : ''?>">
                            <?=number_format($row->closing_qty, 2)?>
                        </td>
                        <td><?=number_format($row->closing_value, 2)?></td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($movements)): ?>
                    <tr>
                        <td colspan="13" class="text-center">No records found for the selected criteria</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td colspan="7" class="text-right"><strong>TOTALS:</strong></td>
                        <td><strong><?=number_format($total_in_qty, 2)?></strong></td>
                        <td><strong><?=number_format($total_purchase_amt, 2)?></strong></td>
                        <td><strong><?=number_format($total_out_qty, 2)?></strong></td>
                        <td><strong><?=number_format($total_sale_amt, 2)?></strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
.in-row { background-color: #d4edda !important; }
.out-row { background-color: #f8d7da !important; }
.alert-row { background-color: #fff3cd !important; }
.text-red { color: #dc3545 !important; font-weight: bold; }
</style>