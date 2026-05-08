<!DOCTYPE html>
<html>
  <head>
    <style>
      body{
        font-size: 10px;
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
        padding: 8px;
      }
    </style>
  </head>
  <body>
      <?php 
        $image_data = '';
        if($company_setting->logo != '')
        {
          $image_data = file_get_contents(base_url().'assets/images/'.$company_setting->logo);
          $base64_data = base64_encode($image_data);
      ?>

      <center>
        <img src="data:image/png;base64,<?=$base64_data?>" style="width: 200px;">
      </center>

      <?php 
        }
      ?>
      <center>
        <h3>
          <?=$this->lang->line('tax_invoice_under_gst')?> <br/>
          <?=$this->lang->line('delivery_challan')?>
        </h3>
      </center>
      <h3 style="float: right">
        <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($sale->invoice_date))?>
      </h3>
      <br/><br/>                     
      <table style="width:100%">
        <tr class="table_header">
          <th style="width: 30%;"><?=$this->lang->line('supplier')?></th>
          <th style="width: 30%;"><?=$this->lang->line('bill_to')?></th>
          <th style="width: 30%;"><?=$this->lang->line('invoice_details')?></th>
        </tr>
        <tr>
          <td>
            <strong><?=$company_setting->company_name?></strong><br>
            <?=$company_setting->address_line1?><br>
            <?=$company_setting->address_line2?><br>
            <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
            <?=$company_setting->pincode?><br>
            <?=$this->lang->line('phone')?>: <?=$company_setting->mobile?><br>
            <?=$this->lang->line('email')?>: <?=$company_setting->email?><br>
            <strong><?=$this->lang->line('gstin_of_supplier')?>: <?=$company_setting->gstin?></strong><br>
          </td>
          <td style="vertical-align: text-top;">
            <strong><?=$customer_detail->customer_name?></strong><br>
            <?=$customer_detail->address?><br>
            <?=$customer_detail->city_name.', '.$customer_detail->state_name.', '.$customer_detail->country_name?><br>
            <?=$this->lang->line('phone')?>: <?=$customer_detail->phone?><br>
            <?=$this->lang->line('email')?>: <?=$customer_detail->email?><br>
            <?=$this->lang->line('gstin')?>: <?=$customer_detail->gstin?><br>
          </td>
          <td style="vertical-align: text-top;">
            <b><?=$this->lang->line('sale_invoice_no')?></b><?=$sale->reference_no?><br>
            <b><?=$this->lang->line('receipt_voucher_no')?></b><br/>
            <b><?=$this->lang->line('rcm_applicability')?>: </b><?=($sale->rcm == "Y") ? "Yes" : "No"?>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <thead>
          <tr>
            <th><?=$this->lang->line('sale_sr')?></th>
            <th><?=$this->lang->line('sale_items')?></th>
            <th><?=$this->lang->line('sale_hsn')?></th>
            <th><?=$this->lang->line('sale_qty')?></th>
            <th><?=$this->lang->line('sale_price')?></th>
            <th><?=$this->lang->line('sale_discount')?></th>
            <th><?=$this->lang->line('sale_uom')?></th>
            <th><?=$this->lang->line('sale_total_taxable_value')?></th>
            <th><?=$this->lang->line('sale_tax')?></th>
            <th><?=$this->lang->line('sale_subtotal')?></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $paid_amount  = round($this->transaction_model->get_total_transaction_amount($sale->id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE)); 
            $i = 1;
            $total_quantity = 0;
            $total_price    = 0;
            $total_discount_amount = 0;
            $total_taxable_value = 0;
            $total_tax = 0;
            $total_subtotal = 0;
            foreach ($sale_items as $row) 
            {
              $total_quantity         += $row->quantity;
              $total_price            += $row->price;
              $total_discount_amount  += $row->discount_amount;
              $total_taxable_value    += $row->taxable_value;
              $total_tax              += $row->cgst_tax+$row->sgst_tax+$row->igst_tax;
              $total_subtotal         += $row->sub_total;
          ?>
            <tr style="font-size: 10px;">
              <td><?=$i++?></td>
              <td><?=$row->product_name.'<br/>'.$row->description?></td>
              <td><?=$row->hsn?></td>
              <td><?=$row->quantity?></td>
              <td><?=number_format_i($row->price)?></td>
              <td><?=number_format_i($row->discount_amount)?></td>
              <td><?=$row->uom_uom?></td>
              <td><?=number_format_i($row->taxable_value-$row->discount_amount)?></td>
              <td>
                <?php 
                  if($company_setting->country_id == $customer_detail->country_id)
                  {
                    if($sale->rcm == 'N' && $company_setting->gstin != '')
                    {
                      if($company_setting->state_id == $customer_detail->state_id)
                      {
                ?>
                        <?=$this->lang->line('cgst')?> : <?=number_format_i($row->cgst_tax)?>
                        (<?=$row->cgst?>%)
                        <br><?=$this->lang->line('sgst')?> : <?=number_format_i($row->sgst_tax)?>
                        (<?=$row->sgst?>%)
                <?php
                      }
                      else
                      {
                ?>
                        <?=$this->lang->line('igst')?> : <?=number_format_i($row->igst_tax)?>
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
                  }
                  else
                  {
                ?>
                    N/A
                <?php
                  }
                ?>
              </td>
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
              <td><?=number_format_i($total_price)?></td>
              <td><?=number_format_i($total_discount_amount)?></td>
              <td></td>
              <td><?=number_format_i($total_taxable_value-$total_discount_amount)?></td>
              <td><?=number_format_i($total_tax)?></td>
              <td><?=number_format_i($total_subtotal)?></td>
            </tr>
            <tr style="font-weight: bolder;">
              <td colspan="9">
                <?=$this->lang->line('rounded_off')?> 
              </td>
              <td>
                <?php 
                  if((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)) > 0)
                  {
                    echo number_format_i((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)));
                  }
                  else
                  {
                    echo number_format_i((round($sale->total_taxable_value+$sale->total_tax) - ($sale->total_taxable_value+$sale->total_tax)));
                  }
                ?>
              </td>
            </tr>
             <tr style="font-weight: bolder;">
              <td colspan="9">
                <?=$this->lang->line('sale_tds')?>
              </td>
              <td>
                <?php 
                  echo number_format_i($sale->tds);
                ?>
              </td>
            </tr>
            <tr style="font-weight: bolder;">
              <td colspan="9">
                 <?=$this->lang->line('paid_amount')?>
              </td>
              <td>
                <?php echo number_format_i($paid_amount)?>
              </td>
            </tr>
            <tr style="font-weight: bolder;">
              <td colspan="9">
                 <?=$this->lang->line('balance_receivable')?>
              </td>
              <td>
                <?php echo number_format_i(round($sale->total_taxable_value+$sale->total_tax-$paid_amount));?>
              </td>
            </tr>
            <tr style="font-weight: bold; ">
              <td colspan="5">
                <?=$this->lang->line('amount_in_words')?>
              </td>
              <td colspan="5" style="text-align: right">
                <?php echo $this->numbertowords->convert_number(round($sale->total_taxable_value+$sale->total_tax-$paid_amount));?>
              </td>
            </tr>
        </tbody>
      </table>
      <table style="width: 100%;">
        <tr class="table_header">
          <td colspan="6" style="font-weight: bolder;"> <?=$this->lang->line('bank_detail')?></td>
          <td colspan="3" style="font-weight: bolder;"> <?=$this->lang->line('remarks')?></td>
        </tr>
        <tr>
          <td colspan="6">
            <?=$sale->bank_detail?>
          </td>
          <td colspan="3">
            <?=$sale->external_note?>
          </td>
        </tr>
        <tr class="table_header">
          <td colspan="6" style="font-weight: bolder;"><?=$this->lang->line('terms_condition')?></td>
          <td colspan="3" style="font-weight: bolder;"><?=$this->lang->line('certified_messages')?></td>
        </tr>
        <tr>
          <td colspan="6">
            <?=$sale->terms_and_condition?>
          </td>
          <td colspan="3">
            <?=$this->lang->line('for')?>, <?=strtoupper($company_setting->company_name)?><br/><br/><br/>
            <h3><?=$this->lang->line('signatory')?></h3>
          </td>
        </tr>
      </table>
     
  </body>
</html>
