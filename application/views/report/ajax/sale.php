 <table class="table table-bordered table-striped" id="sale-data">
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
            $total_taxable = 0;
            $total_tax = 0;
            $total_grand = 0;
            $i = 1;

            if(!empty($sales) && count($sales) > 0): 
                foreach($sales as $sale): 
                    // Calculate individual tax for this row
                    // $item_tax = (float)$sale->igst_tax + (float)$sale->cgst_tax + (float)$sale->sgst_tax;
                    
                    // // Identify row values (Handling possible different naming in queries)
                    // $row_taxable = isset($sale->taxable_value) ? $sale->taxable_value : ($sale->total_taxable_value / count($sales)); 
                    // $row_total = isset($sale->sub_total) ? $sale->sub_total : $sale->total;
                    
                     $item_tax = (float)$sale->igst_tax + (float)$sale->cgst_tax + (float)$sale->sgst_tax;
        
                    // 2. Identify Taxable Value
                    $row_taxable = (float)$sale->taxable_value;
                    
                    // 3. FIX: Calculate Total Amount manually (Taxable + Tax)
                    // This ensures it never shows 0 if the other two columns have values
                    $row_total = $row_taxable + $item_tax;
                    
                    // Logic: Rate = Taxable Amount / Quantity
                    $rate = ($sale->quantity > 0) ? ($row_taxable / $sale->quantity) : 0;

                    $total_taxable += $row_taxable;
                    $total_tax     += $item_tax;
                    $total_grand   += $row_total;
            ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= !empty($sale->invoice_date) ? date('d-m-Y', strtotime($sale->invoice_date)) : 'N/A' ?></td>
                    <td>
                        <?php if(!empty($sale->invoice_no) && !empty($sale->sale_id)): ?>
                            <a href="<?= base_url('sale/pdf_request/' . base64_encode($sale->sale_id)) ?>" target="_blank"><?= $sale->invoice_no ?></a>
                        <?php else: ?>
                            <?= $sale->invoice_no ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $sale->warehouse_name ?></td>
                    <td><?= !empty($sale->customer_company_name) ? $sale->customer_company_name : $sale->customer_name ?></td>
                    <td><?= $sale->product_name ?></td>
                    <td><?= number_format($sale->quantity, 2) ?></td>
                    <td class="text-right"><?= number_format($rate, 2) ?></td>
                    <td class="text-right"><?= number_format($row_taxable, 2) ?></td>
                    <td class="text-right"><?= number_format($item_tax, 2) ?></td>
                    <td class="text-right"><?= number_format($row_total, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="11" class="text-center">No sales records found</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot class="footer_data">
            <tr>
                <th colspan="8" class="text-right">Total:</th>
                <th class="text-right"><?= number_format($total_taxable, 2) ?></th>
                <th class="text-right"><?= number_format($total_tax, 2) ?></th>
                <th class="text-right"><?= number_format($total_grand, 2) ?></th>
            </tr>
        </tfoot>
    </table>