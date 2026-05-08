

<table class="table table-bordered table-striped" id="sales-return-data">
        <thead>
            <tr>
                <th width="2%">Sr.No</th>
                <th>Type</th>
                <th>Date</th>
                <th>Invoice No</th>
                <th>Party Name</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1; $total_amt = 0;
            if(!empty($sales_return)): 
                foreach($sales_return as $row): 
                    $total_amt += (float)$row->amount;
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><span class="badge badge-warning">Credit Note</span></td>
                <td><?= date('d-m-Y', strtotime($row->date)) ?></td>
                <td>
                    <a href="<?=base_url('sales_return/view/'.base64_encode($row->return_id))?>" target="_blank">
                        <?= $row->invoice_no ?>
                    </a>
                </td>
                <td><?= $row->party_name ?></td>
                <td><?= $row->product_name ?></td>
                <td><?= number_format($row->qty, 2) ?></td>
                <td><?= number_format($row->rate, 2) ?></td>
                <td><?= number_format($row->amount, 2) ?></td>
                <td><?= $row->remarks ?></td>
            </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="10" class="text-center">No Records Found</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot class="footer_data">
            <tr>
                <th colspan="8" class="text-right">Total:</th>
                <th><?= number_format($total_amt, 2) ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>