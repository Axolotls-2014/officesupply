<table class="table table-bordered table-striped" id="purchase-data">
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
    $t_taxable = 0; $t_cgst = 0; $t_sgst = 0; $t_igst = 0; $t_grand = 0;
    $i = 1;
    
    // Capture the status selected by the user
    $filter_status = $this->input->post('status');

    if (!empty($purchases)) {
        foreach ($purchases as $value) {
            // 1. Calculations
            $purchase_tax = $this->purchase_model->get_purchase_tax_individual($value->id);
            $cgst = (float)($purchase_tax->cgst_tax ?? 0);
            $sgst = (float)($purchase_tax->sgst_tax ?? 0);
            $igst = (float)($purchase_tax->igst_tax ?? 0);
            $row_taxable = (float)($value->taxable_value ?? $value->total_taxable_value);
            $row_total   = (float)($value->subtotal ?? $value->total);

            // 2. Determine Payment Status
            $paid_amount = round($this->transaction_model->get_total_transaction_amount($value->id, 'P', 'R'), 2);
            $total_payable = round($row_total, 2);
            
            if($paid_amount >= $total_payable) {
                $current_status = 'Paid';
                $status_badge = '<span class="badge badge-success">Paid</span>';
            } elseif($paid_amount > 0) {
                $current_status = 'Partial';
                $status_badge = '<span class="badge badge-warning">Partial</span>';
            } else {
                $current_status = 'Unpaid';
                $status_badge = '<span class="badge badge-danger">Unpaid</span>';
            }

            // 3. FILTER LOGIC: Skip row if it doesn't match selected status
            if (!empty($filter_status) && $filter_status !== $current_status) {
                continue;
            }

            // 4. ADD TO TOTALS (Only for visible rows)
            $t_taxable += $row_taxable;
            $t_cgst    += $cgst;
            $t_sgst    += $sgst;
            $t_igst    += $igst;
            $t_grand   += $row_total;
    ?>
        <tr>
            <td><?= $i++; ?></td>
            <td><?= date('d-m-Y', strtotime($value->purchase_date)); ?></td>
            <td><?= $value->invoice_no; ?></td>
            <td><?= $value->company_name; ?></td>
            <td><?= !empty($value->gst_no) ? $value->gst_no : 'N/A'; ?></td>
            <td class="text-right"><?= number_format($row_taxable, 2); ?></td>
            <td class="text-right"><?= number_format($cgst, 2); ?></td>
            <td class="text-right"><?= number_format($sgst, 2); ?></td>
            <td class="text-right"><?= number_format($igst, 2); ?></td>
            <td class="text-right"><strong><?= number_format($row_total, 2); ?></strong></td>
            <td class="text-center"><?= $status_badge ?></td>
        </tr>
    <?php
        }
    } 
    
    if($i == 1) { // If no rows were printed
        echo '<tr><td colspan="11" class="text-center">No Record Found for selected status</td></tr>';
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