<!DOCTYPE html>
<html>
  <head>
    <style>
      body{
        font-size: 8px;
        padding: 0px;
        margin: 0px;
      }
      tr{
        font-size: 10px;
      }
      table, th, td {
        border-collapse: collapse;
      }

      thead,.table_header{
        background-color: #f2f2f2;
      }

      th, td {
        text-align: left;
        border-bottom: 1px solid #ddd;
        padding: 2px;
      }
      /* Define a class to hide elements during printing */
      @media print {
        .no-print {
          display: none !important;
        }
      }
    </style>
  </head>
  <body>
      
  <table width="100%" border="1" style="border-collapse: collapse;">
    <tr>
      <td style="padding: 8px;text-align:center" width="30%">
      
        <?php 
          $image_data = '';
          if ($company_setting->logo != '') {
            $image_path = './assets/images/' . $company_setting->logo; // Adjust the path accordingly
            if (file_exists($image_path)) {
              $image_data = file_get_contents($image_path);
              $base64_data = base64_encode($image_data);
        ?>

        
        <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;">
        

        <?php
            } 
          }
        ?>
      </td>
      <td style="text-align:center;" width="30%">
        <address>
          <strong><?=$company_setting->company_name?></strong><br/>
          <?= ($company_setting->address_line1 != '') ? ($company_setting->address_line1.'<br/>') : '' ?>
          <?= ($company_setting->address_line2 != '') ? ($company_setting->address_line2.'<br/>') : '' ?>
          <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
          <?= ($company_setting->pincode != '') ? ($company_setting->pincode.'<br/>') : '' ?>
          GST No. : <?=$company_setting->gstin?><br/>
          Contact No:<?=$company_setting->mobile?>, Email ID: <br/>
          <?=$company_setting->email?>
        </address>
      </td>
      <td style="padding: 8px;text-align:center" width="30%">
        <?php 
          $image_data = '';
          if ($company_setting->logo != '') {
            $image_path = './assets/images/' . $company_setting->logo; // Adjust the path accordingly
            if (file_exists($image_path)) {
              $image_data = file_get_contents($image_path);
              $base64_data = base64_encode($image_data);
        ?>

        
        <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;">
        

        <?php
            } 
          }
        ?>
      </td>
    </tr>
    <tr>
      <td style="padding: 8px;text-align:left;">
        <label>Customer Name : </label>    <strong><?=$customer_detail->customer_name?></strong><br/>
        <label>Customer Mob. : </label>  <?=$customer_detail->phone?><br/>
        <!-- <label>Docter Name : </label>  <?=$sale->doctor_name?><br/> -->
      </td>
      <td></td>
      <td style="padding: 8px;text-align:left !important;">
        <label>Invoice No. : </label>  <?=$sale->reference_no?> <br/>
        <label>Date        : </label>  <?=date('d-m-Y', strtotime($sale->invoice_date))?><br/>
        <label>GST No      : </label>  <?=$customer_detail->gstin?><br/>

        <!-- <?=$sale->reference_no?>                           : <label>Invoice No.</label><br/>
        <?=date('d-m-Y', strtotime($sale->invoice_date))?> : <label>Date</label><br/>
        <?=$customer_detail->gstin?>                       : <label>GST No</label><br/> -->
        
      </td>
    </tr>
    
    <tr>
      <td colspan="3">
        <table border="1" width="100%" style="border-top:0;"  cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th style="padding: 2px;text-align:center;">Sr</th>
              <th style="padding: 2px;text-align:center;">Product</th>
              <!-- <th style="padding: 2px;text-align:center;">Pack Size</th> -->
              <th style="padding: 2px;text-align:center;">HSN Code</th>
              <!-- <th style="padding: 2px;text-align:center;">Batch</th>
              <th style="padding: 2px;text-align:center;">Exp</th> -->
              <th style="padding: 2px;text-align:center;">Qty</th>
              <th style="padding: 2px;text-align:center;">Selling Price</th>
              <th style="padding: 2px;text-align:center;">Dis. Amount</th>
              <th style="padding: 2px;text-align:center;">Tax</th>
              <th style="padding: 2px;text-align:center;">Amount(Rs.)</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $i = 1;
              $total_quantity = 0;
              // $total_free_quantity = 0;
              $total_selling_price    = 0;
              $total_discount_amount = 0;
              $total_taxable_value = 0;
              $total_tax = 0;
              $total_subtotal = 0;

              $total_cgst_tax = 0;
              $total_sgst_tax = 0;
              $total_igst_tax = 0;

              foreach ($sale_items as $row) 
              {
                $total_quantity         += $row->quantity;
                // $total_free_quantity    += $row->free_quantity;
                $total_selling_price            += $row->selling_price;
                $total_discount_amount  += $row->discount_amount;
                $total_taxable_value    += $row->taxable_value;
                $total_tax              += $row->cgst_tax+$row->sgst_tax+$row->igst_tax;
                $total_subtotal         += $row->sub_total;

                $total_cgst_tax         += $row->cgst_tax;
                $total_sgst_tax         += $row->sgst_tax;
                $total_igst_tax         += $row->igst_tax;
            ?>
            <tr>
              <td style="padding: 2px;text-align:center;"><?=$i++?></td>
              <td style="padding: 2px;text-align:center;">
              <?php 
                $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);

                $product_description = ($row->description != '') ? '<br/>'.$row->description : '<br/>';
                // $optional = ($row->optional != '') ? '<br/>'.$row->optional.'<br/>' : '<br/>';


              ?>
              <?=$row->product_name?>
              </td>
              <!-- <td style="padding: 2px;text-align:center;"><?=$row->pack?></td> -->
              <td style="padding: 2px;text-align:center;"><?=$row->hsn?></td>
              <!-- <td style="padding: 2px;text-align:center;"><?=($row->batch_no != 'null') ? $row->batch_no : '' ?></td>
              <td style="padding: 2px;text-align:center;"><?=($row->expiry_date != '' && $row->expiry_date != '0000-00-00') ? date('d-m-Y',strtotime($row->expiry_date)) : '' ?></td> -->
              <td style="padding: 2px;text-align:center;"><?=$row->quantity?></td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($row->selling_price)?></td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($row->discount_amount)?></td>
              <td style="padding: 2px;text-align:center;">
                <?php 
                  if($company_setting->country_id == $customer_detail->country_id)
                  {
                      if($company_setting->state_id == $customer_detail->state_id)
                      {
                ?>
                        <?=$this->lang->line('cgst')?> : <?=$row->cgst_tax?>
                        (<?=$row->cgst?>%)
                        <br><?=$this->lang->line('sgst')?> : <?=$row->sgst_tax?>
                        (<?=$row->sgst?>%)
                <?php
                      }
                      else
                      {
                ?>
                        <?=$this->lang->line('igst')?> : <?=$row->igst_tax?>
                        (<?=$row->igst?>%)
                <?php 
                      }
                  }
                  else
                  {
                ?>
                    N/A
                <?php
                  }
                ?>
              </td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($row->sub_total)?></td>
            </tr>
          <?php 
            }
          ?>
            <tr rowspan="4">
              <td colspan="4" style="padding: 2px;text-align:left;border-bottom-style: hidden;">
                <label>Remark : </label>
              </td>
              <td style="padding: 2px;text-align:center;">CGST</td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($total_cgst_tax)?></td>
              <td style="padding: 2px;text-align:left;" colspan="1"><b>Gross Total</b></td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($total_taxable_value)?></td>
            </tr>
            <tr>
              <td style="padding: 2px;text-align:center;border-bottom-style: hidden;" colspan="4"></td>
              <td style="padding: 2px;text-align:center;">SGST</td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($total_sgst_tax)?></td>
              <td style="padding: 2px;text-align:left;" colspan="1"><b>Total Tax</b></td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($total_tax)?></td>
            </tr>
            <tr>
              <td style="padding: 2px;text-align:center;border-bottom-style: hidden;" colspan="4"></td>
              <td style="padding: 2px;text-align:center;">IGST</td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i($total_igst_tax)?></td>
              <td style="padding: 2px;text-align:left;" colspan="1"><b>CESS</b></td>
              <td style="padding: 2px;text-align:center;">0.00</td>
            </tr>
            <!-- <tr>
              <td style="padding: 2px;text-align:center;border-bottom-style: hidden;" colspan="7"></td>
              <td style="padding: 2px;text-align:left;" colspan="3"><b>Round Off</b></td>
              <td style="padding: 2px;text-align:center;"><?=abs($sale->round_off)?></td>
            </tr> -->
            <tr>
              <td style="padding: 2px;text-align:center;" colspan="6"></td>
              <td style="padding: 2px;text-align:left;" colspan="1"><b>Net Amount</b></td>
              <td style="padding: 2px;text-align:center;"><?=number_format_i(($total_taxable_value + $total_tax))?></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td style="padding: 8px;text-align:left;">
        <label><b><u>Terms & Conditions : </u></b></label><br/>
        <?=$company_setting->terms_and_condition?> 
      </td>
      <td></td>
      <td style="padding: 8px;text-align:right;">
      For, <?=strtoupper($company_setting->company_name)?><br/>
      <h5>Authorised Signatory</h5>
      </td>
    </tr>
    <tr class="no-print">
      <td colspan="3">
        <button onclick="window.print()">Print</button>
        
				<!-- Close Button -->
				<button onclick="window.close()">Close</button>
      </td>
    </tr>
  </table>
              
     
  </body>
</html>
