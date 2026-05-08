<table class="table table-bordered table-striped" id="purchase-data">
    <thead>
        <tr>
            <th width="2%">Sr.No</th>
            <th>Invoice Date</th>
            <th>Invoice No</th>
            <th>Branch</th>
            <th>Party Name</th>
            <th>Product Name</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Taxable Amount</th>
            <th>Tax</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_taxable_value = 0.0;
        $total_tax_amt       = 0.0;
        $total_grand         = 0.0;
        $i = 1;

        if (!empty($purchases)) {
            foreach ($purchases as $value) {
                // Fetch tax details
                $purchase_tax = $this->purchase_model->get_purchase_tax_individual($value->id);
                $item_tax = 0;
                if ($purchase_tax !== null) {
                    $item_tax = (float)$purchase_tax->igst_tax + (float)$purchase_tax->cgst_tax + (float)$purchase_tax->sgst_tax;
                }

                // Variable handling for different query result keys
                $row_taxable = isset($value->taxable_value) ? $value->taxable_value : $value->total_taxable_value;
                $row_total   = isset($value->subtotal) ? $value->subtotal : $value->total;
                
                // Rate Calculation (Taxable / Qty)
                $calculated_rate = ($value->quantity > 0) ? ($row_taxable / $value->quantity) : 0;

                $total_taxable_value += $row_taxable;
                $total_tax_amt       += $item_tax;
                $total_grand         += $row_total;
        ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= date('d-m-Y', strtotime($value->purchase_date)); ?></td>
                    <td><?= $value->invoice_no; ?></td>
                    <td><?= $value->warehouse_name; ?></td>
                    <td><?= $value->company_name; ?></td>
                    <td><?= $value->product_name; ?></td>
                    <td><?= $value->quantity; ?></td>
                    <td class="text-right"><?= number_format_i($calculated_rate); ?></td>
                    <td class="text-right"><?= number_format_i($row_taxable); ?></td>
                    <td class="text-right"><?= number_format_i($item_tax); ?></td>
                    <td class="text-right"><?= number_format_i($row_total); ?></td>
                </tr>
        <?php
            }
        } else {
            echo '<tr><td colspan="11" class="text-center">No Record Found</td></tr>';
        }
        ?>
    </tbody>
    <tfoot class="footer_data">
        <tr>
            <th colspan="8" class="text-right">Total</th>
            <th class="text-right"><?= number_format_i($total_taxable_value) ?></th>
            <th class="text-right"><?= number_format_i($total_tax_amt) ?></th>
            <th class="text-right"><?= number_format_i($total_grand) ?></th>
        </tr>
    </tfoot>
</table>