<table class="table table-bordered table-striped" id="sales-return-data">
        <thead>
            <tr style="background-color: #f8f9fa;">
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
            $i = 1; 
            $t_taxable = 0; $t_cgst = 0; $t_sgst = 0; $t_igst = 0; $t_grand = 0;
            if(!empty($sales_return)): 
                foreach($sales_return as $row): 
                    $t_taxable += (float)$row->taxable_amount;
                    $t_cgst    += (float)$row->total_cgst;
                    $t_sgst    += (float)$row->total_sgst;
                    $t_igst    += (float)$row->total_igst;
                    $t_grand   += (float)$row->total_amount;

                    // Calculation for Status
                    $paid = $this->transaction_model->get_total_transaction_amount($row->return_id, SALE_RETURN_MODULE, PAYMENT_TRANSACTION_TYPE);
                    if($paid >= $row->total_amount) $status = '<span class="badge badge-success">Paid</span>';
                    elseif($paid > 0) $status = '<span class="badge badge-warning">Partial</span>';
                    else $status = '<span class="badge badge-danger">Unpaid</span>';
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= date('d-m-Y', strtotime($row->invoice_date)) ?></td>
                <td>
                    <a href="<?=base_url('sales_return/view/'.base64_encode($row->return_id))?>" target="_blank">
                        <?= $row->invoice_no ?>
                    </a>
                </td>
                <td><?= !empty($row->customer_company_name) ? $row->customer_company_name : $row->customer_name ?></td>
                <td><?= !empty($row->gst_no) ? $row->gst_no : 'N/A' ?></td>
                <td class="text-right"><?= number_format($row->taxable_amount, 2) ?></td>
                <td class="text-right"><?= number_format($row->total_cgst, 2) ?></td>
                <td class="text-right"><?= number_format($row->total_sgst, 2) ?></td>
                <td class="text-right"><?= number_format($row->total_igst, 2) ?></td>
                <td class="text-right"><b><?= number_format($row->total_amount, 2) ?></b></td>
                <td class="text-center"><?= $status ?></td>
            </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="11" class="text-center">No Records Found</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot class="footer_data" style="background-color: #f39c12; color: white;">
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