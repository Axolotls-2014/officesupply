<table class="table table-bordered table-striped" id="sale-data">
    <thead>
        <tr>
            <th width="2%">Sr.No</th>
            <th>Invoice Date</th>
            <th>Invoice No</th>
            <th>Customer Name</th>
            <th>GST No</th>
            <th class="text-right">Taxable Amount</th>
            <th class="text-right">CGST</th>
            <th class="text-right">SGST</th>
            <th class="text-right">IGST</th>
            <th class="text-right">Total Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $t_taxable = 0; $t_cgst = 0; $t_sgst = 0; $t_igst = 0; $t_grand = 0;
        $i = 1;

        if(!empty($sales)): 
            foreach($sales as $sale): 
                $t_taxable += (float)$sale->total_taxable_value;
                $t_cgst    += (float)$sale->total_cgst;
                $t_sgst    += (float)$sale->total_sgst;
                $t_igst    += (float)$sale->total_igst;
                $t_grand   += (float)$sale->total_amount;

                // Logic for Status
                $paid = round($this->transaction_model->get_total_transaction_amount($sale->sale_id, 'S', 'R'), 2);
                $total = round($sale->total_amount, 2);
                
                if($paid >= $total) $status = '<span class="badge badge-success">Paid</span>';
                elseif($paid > 0) $status = '<span class="badge badge-warning">Partial</span>';
                else $status = '<span class="badge badge-danger">Unpaid</span>';
        ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= date('d-m-Y', strtotime($sale->invoice_date)) ?></td>
                <td>
                    <a href="<?= base_url('sale/view/' . base64_encode($sale->sale_id)) ?>" target="_blank">
                        <?= $sale->invoice_no ?>
                    </a>
                </td>
                <td><?= !empty($sale->customer_company_name) ? $sale->customer_company_name : $sale->customer_name ?></td>
                <td><?= !empty($sale->gst_no) ? $sale->gst_no : 'N/A' ?></td>
                <td class="text-right"><?= number_format($sale->total_taxable_value, 2) ?></td>
                <td class="text-right"><?= number_format($sale->total_cgst, 2) ?></td>
                <td class="text-right"><?= number_format($sale->total_sgst, 2) ?></td>
                <td class="text-right"><?= number_format($sale->total_igst, 2) ?></td>
                <td class="text-right"><strong><?= number_format($sale->total_amount, 2) ?></strong></td>
                <td class="text-center"><?= $status ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="11" class="text-center">No records found</td></tr>
        <?php endif; ?>
    </tbody>
    <tfoot class="footer_data" style="background-color: #ffc107; font-weight: bold;">
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