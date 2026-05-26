<table class="table table-bordered table-striped m-0" id="expense-data">
        <thead>
            <tr>
                <th>Bill Date</th>
                <th>Expense Category</th>
                <th>Expense Name</th>
                <th>Paid To</th>
                <th>Payment Mode</th>
                <th class="text-right">Taxable Amount(₹)</th>
                <th class="text-right">CGST(₹)</th>
                <th class="text-right">SGST(₹)</th>
                <th class="text-right">IGST(₹)</th>
                <th class="text-right">Total Amount(₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $t_taxable = 0; $t_cgst = 0; $t_sgst = 0; $t_igst = 0; $t_grand = 0;
            $filter_status = $this->input->post('status');

            if (!empty($expenses)) {
                foreach ($expenses as $value) {
                    // 1. Calculate Tax Values
                    $cgst_amt = ($value->amount * (float)$value->cgst) / 100;
                    $sgst_amt = ($value->amount * (float)$value->sgst) / 100;
                    $igst_amt = ($value->amount * (float)$value->igst) / 100;
                    $row_total = (float)$value->total_amount;

                    // 2. Identify Payment Mode Label
                    $modes = [0=>"Cash", 1=>"Card", 2=>"Cheque", 3=>"NEFT/RTGS"];
                    $p_mode = isset($modes[$value->payment_mode]) ? $modes[$value->payment_mode] : "N/A";

                    // 3. Payment Status Logic
                    $paid = round($this->transaction_model->get_total_transaction_amount($value->id, 'E', 'P'), 2);
                    if($paid >= round($row_total, 2)) $curr_status = 'Paid';
                    elseif($paid > 0) $curr_status = 'Partial';
                    else $curr_status = 'Unpaid';

                    // 4. Status Filter Check
                    if (!empty($filter_status) && $filter_status !== $curr_status) continue;

                    // Sum totals for Footer
                    $t_taxable += $value->amount;
                    $t_cgst    += $cgst_amt;
                    $t_sgst    += $sgst_amt;
                    $t_igst    += $igst_amt;
                    $t_grand   += $row_total;
            ?>
                <tr>
                    <td><?= date('d-m-Y', strtotime($value->date)); ?></td>
                    <td><?= $value->expense_category_name; ?></td>
                    <td><?= $value->name; // or $value->description ?></td>
                    <td><?= $value->company_name; ?></td>
                    <td><?= $p_mode; ?></td>
                    <td class="text-right"><?= number_format($value->amount, 2); ?></td>
                    <td class="text-right"><?= number_format($cgst_amt, 2); ?></td>
                    <td class="text-right"><?= number_format($sgst_amt, 2); ?></td>
                    <td class="text-right"><?= number_format($igst_amt, 2); ?></td>
                    <td class="text-right"><strong><?= number_format($row_total, 2); ?></strong></td>
                </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="10" class="text-center">No Record Found</td></tr>';
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
            </tr>
        </tfoot>
    </table>