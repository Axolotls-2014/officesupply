<table class="table table-bordered table-striped" id="purchase-return-data">
    <thead>
        <tr>
            <th width="2%">Sr.No</th>
            <th>Invoice Date</th>
            <th>Invoice No</th>
            <th>Supplier Name</th>
            <th>GST No</th>
            <th class="text-right">Taxable Amount</th>
            <th class="text-right">CGST</th>
            <th class="text-right">SGST</th>
            <th class="text-right">IGST</th>
            <th class="text-right">Total Amount</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $i = 1; 
    $t_taxable = 0; $t_cgst = 0; $t_sgst = 0; $t_igst = 0; $t_grand = 0;
    
    // Capture user selections
    $filter_status = $this->input->post('status');
    $filter_type   = $this->input->post('type');

    if(!empty($purchase_return)): 
        foreach($purchase_return as $row): 
            // 1. Fetch tax components
            $tax_data = $this->purchase_return_model->get_purchase_return_tax_individual($row->return_id);
            $cgst = (float)($tax_data->cgst_tax ?? 0);
            $sgst = (float)($tax_data->sgst_tax ?? 0);
            $igst = (float)($tax_data->igst_tax ?? 0);
            
            $row_total = (float)$row->amount;
            $taxable = $row_total - ($cgst + $sgst + $igst);

            // 2. Determine Payment Status (Paid / Partial / Unpaid)
            // Note: 'PR' is usually the code for Purchase Return modules in transaction tracking
            $paid_amt = round($this->transaction_model->get_total_transaction_amount($row->return_id, 'PR', 'R'), 2);
            $total_payable = round($row_total, 2);
            
            if($paid_amt >= $total_payable && $total_payable > 0) {
                $status_text = 'Paid';
                $status_badge = '<span class="badge badge-success">Paid</span>';
            } elseif($paid_amt > 0) {
                $status_text = 'Partial';
                $status_badge = '<span class="badge badge-warning">Partial</span>';
            } else {
                $status_text = 'Unpaid';
                $status_badge = '<span class="badge badge-danger">Unpaid</span>';
            }

            // 3. APPLY FILTERS
            // Filter by Status
            if (!empty($filter_status) && $filter_status !== $status_text) {
                continue;
            }
            // Filter by Type (Hardcoded to Debit Note as per request)
            if (!empty($filter_type) && $filter_type !== 'Debit Note') {
                continue;
            }

            // 4. ACCUMULATE TOTALS (Only for visible/filtered rows)
            $t_taxable += $taxable;
            $t_cgst    += $cgst;
            $t_sgst    += $sgst;
            $t_igst    += $igst;
            $t_grand   += $row_total;
    ?>
    <tr>
        <td><?= $i++; ?></td>
        <td><?= date('d-m-Y', strtotime($row->date)) ?></td>
        <td>
            <a href="<?=base_url('purchase_return/view/'.base64_encode($row->return_id))?>" target="_blank">
                <?= $row->invoice_no ?>
            </a>
        </td>
        <td><?= $row->party_name ?></td>
        <td><?= !empty($row->gst_no) ? $row->gst_no : 'N/A' ?></td>
        <td class="text-right"><?= number_format($taxable, 2) ?></td>
        <td class="text-right"><?= number_format($cgst, 2) ?></td>
        <td class="text-right"><?= number_format($sgst, 2) ?></td>
        <td class="text-right"><?= number_format($igst, 2) ?></td>
        <td class="text-right"><strong><?= number_format($row_total, 2) ?></strong></td>
        <td class="text-center"><?= $status_badge ?></td>
    </tr>
    <?php endforeach; endif; 
    
    if($i == 1) {
        echo '<tr><td colspan="11" class="text-center">No Records Found for the selected filters</td></tr>';
    }
    ?>
</tbody>
    <tfoot class="footer_data">
        <tr>
            <th colspan="5" class="text-right">Total:</th>
            <th class="text-right"><?= number_format($t_taxable, 2) ?></th>
            <th class="text-right"><?= number_format($t_cgst, 2) ?></th>
            <th class="text-right"><?= number_format($t_sgst, 2) ?></th>
            <th class="text-right"><?= number_format($t_igst, 2) ?></th>
            <th class="text-right"><?= number_format($t_grand, 2) ?></th>
            <th></th>
        </tr>
    </tfoot>
</table>