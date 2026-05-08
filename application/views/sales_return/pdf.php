<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-size: 8px;
            padding: 0;
            margin: 0;
        }
        tr {
            font-size: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px; 
        }
        th, td {
            border: 1px solid black;
            padding: 1.5px;
            text-align: left;
        }
        .bordered-bottom {
            border-bottom: 2px solid black;
        }
        .text-center {
            text-align: center;
        }
        .full-width-table {
            width: 100%;
        }
        .no-padding {
            padding: 0;
        }
    </style>
</head>
<body>
    <div style="margin: 20px;">

        <!-- Invoice Header -->
        <table class="full-width-table">
            <tr>
                <td class="text-center" colspan="2">
                    <?php 
                        $logo_path = FCPATH . 'assets/images/0/' . $company_setting->logo; 
                        $logo_data = (file_exists($logo_path)) ? base64_encode(file_get_contents($logo_path)) : '';
                    ?>
                    <img src="<?= !empty($logo_data) ? 'data:image/png;base64,' . $logo_data : ''; ?>" style="width: 8%; height: auto;">
                    <h4><?= htmlspecialchars($company_setting->company_name); ?></h4>
                    <h5><?= htmlspecialchars($warehouse->address_line1);?> GSTIN- <?= htmlspecialchars($company_setting->gstin);?></h5>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="2">
                      <h5 class="text-danger">License no :<?= htmlspecialchars($warehouse->license_no); ?></h5>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="2">
                     <h3>
          <?=$this->lang->line('tax_sales_return_under_gst')?> <br/>
          <?=$this->lang->line('sales_return_for_recipient')?>

        </h3>
                </td>
            </tr>
            <tr>
                <td class="bordered-bottom">
                    <p>quotation No: <?= $sales_return->reference_no; ?></p>
                    <p>quotation Date: <?= $sales_return->invoice_date; ?></p>
                    <p>State: <?= $company_setting->state_name; ?></p>
                </td>
                <td class="bordered-bottom">
                    <p>Transportation Mode: <?= htmlspecialchars($invoice->transport_mode); ?></p>
                    <p>Vehicle No: <?= htmlspecialchars($invoice->vehicle_no); ?></p>
                    <p>Mode: <?= htmlspecialchars($invoice->mode); ?></p>
                </td>
            </tr>
        </table>
        
        <!--<hr class="bordered-bottom">-->

        <!-- Receiver and Consignee Details -->
        <table class="full-width-table">
              <tr>
                <td class="text-center">
                   <h4 class="text-center"><b>Details of Receiver/Billed to:</b></h4>
                </td>
                <td class="text-center">
                    <h4 class="text-center"><b>Details of Consignee / Shipped to:</b></h4>
                </td>
            </tr>
            <tr>
                <td class="bordered-bottom" style="width: 50%;">
                    
                    <p>Company Name: <?= $customer_detail->customer_company_name; ?></p>
                    <p>Customer Name: <?= $customer_detail->customer_name ?></p>
                    <p>Address: <?= $customer_detail->address ?></p>
                    <p>GSTIN: <?= $customer_detail->gstin ?></p>
                    <p>Email: <?= $customer_detail->email ?></p>
                    <p>Mob: <?= $customer_detail->phone ?></p>
                    <p>State: <?= $customer_detail->state_name ?></p>
                </td>
                <td class="bordered-bottom" style="width: 50%;">
                  
                    <p>Name: <?= $company_setting->company_name ?></p>
                    <p>Address: <?= $company_setting->address_line1 ?></p>
                   
                    <p>GSTIN: <?= $company_setting->gstin ?></p>
                    <p>Email: <?= $company_setting->email ?></p>
                    <p>Mob: <?= $company_setting->mobile ?></p>
                    <p>State: <?= $company_setting->state_name ?></p>
                </td>
            </tr>
        </table>
        
        <hr class="bordered-bottom">

        <!-- Product Details Table -->
        <table class="table table-bordered full-width-table">
            <thead>
                <tr>
                    <th><?=$this->lang->line('proforma_invoice_sr')?></th>
            <th><?=$this->lang->line('proforma_invoice_items')?></th>
            <th><?=$this->lang->line('proforma_invoice_hsn')?></th>
            <th><?=$this->lang->line('proforma_invoice_qty')?></th>
            <th class="<?= empty($promotion) ? 'd-none' : '' ?>"><?=$this->lang->line('proforma_invoice_free_qty')?></th>
            <th class="<?= empty($batch_no) ? 'd-none' : '' ?>"><?=$this->lang->line('proforma_invoice_batch')?></th>
            <th><?=$this->lang->line('proforma_invoice_selling_price')?></th>
            <!-- <th><?=$this->lang->line('proforma_invoice_price')?></th> -->
            <!--<th class="<?= empty($mfg_date) ? 'd-none' : '' ?>"><?=$this->lang->line('product_mfg_date')?></th>-->
            <!--<th class="<?= empty($expiry_date) ? 'd-none' : '' ?>"><?=$this->lang->line('product_expiry_date')?></th>-->
            <th><?=$this->lang->line('proforma_invoice_discount')?></th>
            <th><?=$this->lang->line('proforma_invoice_uom')?></th>
            <th><?=$this->lang->line('proforma_invoice_total_taxable_value')?></th>
            <th><?=$this->lang->line('proforma_invoice_tax_rate')?></th>

            <?php 
              if($company_setting->country_id == $customer_detail->country_id)
              {
                if($company_setting->state_id == $customer_detail->state_id)
                {
            ?>
                  <th><?=$this->lang->line('cgst')?></th>
                  <th><?=$this->lang->line('sgst')?></th>
            <?php 
                }
                else
                {
            ?>
                  <th><?=$this->lang->line('igst')?></th>
            <?php
                }
              }
              else
              {
            ?>
                <th><?=$this->lang->line('igst')?></th>
            <?php
              }
            ?>

            <th><?=$this->lang->line('proforma_invoice_subtotal')?></th>


                </tr>
            </thead>
           <tbody>
          <?php
           
                
                  $sales_return_amount      = $sales_return->total;
                  $sales_return_paid_amount = $this->transaction_model->get_total_transaction_amount($sales_return->id,SALE_RETURN_MODULE,PAYMENT_TRANSACTION_TYPE);  
            
            $i = 1;
            $total_quantity = 0;
            $total_price    = 0;
            $total_selling_price    = 0;
            $total_discount_amount = 0;
            $total_taxable_value = 0;
            $total_tax = 0;

            $total_cgst_tax = 0;
            $total_sgst_tax = 0;
            $total_igst_tax = 0;
            $total_subtotal = 0;

            $colspan = 16;

            if($company_setting->state_id != $customer_detail->state_id)
            {
              $colspan  = 15;
            }

            if($mfg_date == '')
              $colspan = $colspan-1;

            if($expiry_date == '')
              $colspan = $colspan-1;   

            if($batch_no == '')
              $colspan = $colspan-1;   
            
            if($promotion == '')
              $colspan = $colspan-1;   


            foreach ($sales_return_items as $row) 
            {
              $total_quantity         += $row->quantity;
              $total_price            += $row->price;
              $total_selling_price    += $row->selling_price;
              $total_discount_amount  += $row->discount_amount;
              $total_taxable_value    += $row->taxable_value;
              $total_tax              += $row->cgst_tax+$row->sgst_tax+$row->igst_tax;
              $total_subtotal         += $row->sub_total;

              $total_cgst_tax         += $row->cgst_tax;
              $total_sgst_tax         += $row->sgst_tax;
              $total_igst_tax         += $row->igst_tax;
          ?>
            <tr style="font-size: 10px;">
              <td><?=$i++?></td>
              <td><?=$row->product_name?></td>
              <td><?=$row->hsn?></td>
              <td><?=$row->quantity?></td>
              <td class="<?= empty($promotion) ? 'd-none' : '' ?>"><?=$row->free_quantity?></td>
              <td class="<?= empty($batch_no) ? 'd-none' : '' ?>"><?=$row->batch_no?></td>
              <!--<td><?=number_format_i($row->selling_price)?></td>-->
               <td><?=number_format_i($row->price)?></td> 
              <!--<td class="<?= empty($mfg_date) ? 'd-none' : '' ?>"><?=($row->mfg_date != '' && $row->mfg_date != '0000-00-00') ? date('d-m-Y',strtotime($row->mfg_date)) : ''?></td>-->
              <!--<td class="<?= empty($expiry_date) ? 'd-none' : '' ?>"><?=($row->expiry_date != '' && $row->expiry_date != '0000-00-00') ? date('d-m-Y',strtotime($row->expiry_date)) : ''?></td>-->
              <td><?=number_format_i($row->discount_amount)?></td>
              <td><?=$row->uom_uom?></td>
              <td><?=number_format_i($row->taxable_value-$row->discount_amount)?></td>
               <td>
                <?php 
                  echo ($row->cgst + $row->sgst + $row->igst);
                ?>
              </td>
              <?php 
                if($company_setting->country_id == $customer_detail->country_id)
                { 
                    if($company_setting->state_id == $customer_detail->state_id)
                    {
              ?>
                      <td><?=$row->cgst_tax?></td>
                      <td><?=$row->sgst_tax?></td>
                     
              <?php
                    }
                    else
                    {
              ?>
                      <td><?=$row->igst_tax?></td>
              <?php 
                    }
                }
                else
                {
              ?>
                  <td>N/A</td>
              <?php
                }
              ?>
              <td><?=number_format_i($row->sub_total)?></td>
            </tr>
          <?php 
            }
          ?>
            <tr style="font-weight: bolder;">
              <td colspan="3">
                <?=$this->lang->line('total_amount_due')?>  
              </td>
              <td><?=$total_quantity?></td>
              <td class="<?= empty($promotion) ? 'd-none' : '' ?>"></td>
              <td class="<?= empty($batch_no) ? 'd-none' : '' ?>"></td>
              <td><?=number_format_i($total_selling_price)?></td>
          
              <!--<td class="<?= empty($mfg_date) ? 'd-none' : '' ?>"></td>-->
              <!--<td class="<?= empty($expiry_date) ? 'd-none' : '' ?>"></td>-->
              <td><?=number_format_i($total_discount_amount)?></td>
              <td></td>
              <td><?=number_format_i($total_taxable_value-$total_discount_amount)?></td>
              <td></td>
                <?php 
                if($company_setting->country_id == $customer_detail->country_id)
                { 
                    if($company_setting->state_id == $customer_detail->state_id)
                    {
              ?>
                      <td><?=number_format_i($total_cgst_tax)?></td>
                      <td><?=number_format_i($total_sgst_tax)?></td>
                     
              <?php
                    }
                    else
                    {
              ?>
                     <td><?=number_format_i($total_igst_tax)?></td>
              <?php 
                    }
                }
                else
                {
              ?>
                  <td>N/A</td>
              <?php
                }
              ?>
              <td><?=number_format_i($total_subtotal)?></td>
            </tr>
            <tr style="font-weight: bolder;">
              <td colspan="<?=($colspan+1)?>">
                <?=$this->lang->line('rounded_off')?> 
              </td>
              <td>
                <?php 
                  if((round($sales_return->total_taxable_value+$sales_return->total_tax) - ($sales_return->total_taxable_value+$sales_return->total_tax)) > 0)
                  {
                    echo number_format_i((round($sales_return->total_taxable_value+$sales_return->total_tax) - ($sales_return->total_taxable_value+$sales_return->total_tax)));
                  }
                  else
                  {
                    echo number_format_i((round($sales_return->total_taxable_value+$sales_return->total_tax) - ($sales_return->total_taxable_value+$sales_return->total_tax)));
                  }
                ?>
              </td>
            </tr>
             <tr style="font-weight: bolder;">
              <td colspan="<?=($colspan+1)?>">
                <?=$this->lang->line('proforma_invoice_tds')?>
              </td>
              <td>
                <?php 
                  echo number_format_i($sales_return->tds);
                ?>
              </td>
            </tr>
             <tr style="font-weight: bolder;">
                             <td colspan="<?=($colspan+1)?>">
                              <?=$this->lang->line('balance_payable')?>
                            </td>
                            <td>
                              <?php echo number_format_i(round($sales_return->total_taxable_value+$sales_return->total_tax-$sales_return_paid_amount));?>
                            </td>
                          </tr>
           
            <tr style="font-weight: bold; ">
              <td colspan="<?=($colspan%2 == 0) ? ($colspan/2) : (round($colspan/2) + 1) ?>">
                <?=$this->lang->line('amount_in_words')?>
              </td>
              <td colspan="<?=($colspan+1) ?>" style="text-align: right">
                <?php echo strtoupper($this->numbertowords->convert_number(round($sales_return->total_taxable_value+$sales_return->total_tax-$sales_return_paid_amount))). ' Only';;?>
              </td>
            </tr>
        </tbody>
        
        </table>

        <hr class="bordered-bottom">

        <!-- Payment and Bank Details -->
        <table class="full-width-table">
            <tr>
                <td>
                    <p>Total Rs: <?= number_format_i($total_subtotal) ?></p>
                    <p><?= $sales_return->bank_detail ?></p>
                    <p>IFSC Code: <?= htmlspecialchars($payment->ifsc_code); ?></p>
                </td>
                <td>
                    <p>Paid Rs: <?= number_format_i($sales_return_paid_amount) ?></p>
                    <p>Current Balance: <?= htmlspecialchars($payment->current_balance); ?></p>
                    <p>Total Dues: <?= number_format_i($total_subtotal) ?></p>
                </td>
                <td>
                    <p>Taxable Amount</p>
                    <!--<p>IGST: <?= number_format_i($total_igst) ?></p>-->
                    <!--<p>CGST: <?= number_format_i($total_cgst) ?></p>-->
                    <!--<p>SGST: <?= number_format_i($total_sgst) ?></p>-->
                    <p><b>Total: <?= number_format_i($total_tax) ?></b></p>
                </td>
            </tr>
        </table>

        <!--<hr class="bordered-bottom">-->

        <!-- Footer Section -->
        <table class="full-width-table">
            <tr>
                <td>
                    <p>Company Name & Seal: ________________</p>
                </td>
                <td>
                    <p><?= $company_setting->terms_and_condition ?></p>
                </td>
                <td class="text-center">
                    <p>Signature</p>
                    <?php 
                        $signature_path = FCPATH . 'assets/images/' . $company_setting->signature;
                        if (file_exists($signature_path)): ?>
                            <img src="<?= base_url('assets/images/' . $company_setting->signature); ?>" style="width: 100px;">
                        <?php else: ?>
                            <br/><br/><br/>
                        <?php endif; ?>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>
