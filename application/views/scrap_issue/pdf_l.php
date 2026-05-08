<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
     
      <center>
        <h3>
          <?=$this->lang->line('header_scrap_issue')?> (<?=$scrap_issue->reference_no?>)
        </h3>
      </center>
      <h3 style="float: right">
        <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($scrap_issue->scrap_issue_date))?>
      </h3>
      <br/><br/>                     
      <table style="width:100%">
        <tr>
          <th style="width: 25%;"></th>
          <th style="width: 25%;"><?=$this->lang->line('supplier')?></th>
          <th style="width: 25%;"><?=$this->lang->line('bill_to')?></th>
          <th style="width: 25%;"><?=$this->lang->line('invoice_details')?></th>
        </tr>
        <tr>
          <td style="vertical-align: text-top !important;">
          <?php 
              $image_data = '';
              $company_settings 	= $this->company_settings_model->get_company_records();
              $cid = $company_settings->cid;

              if ($company_setting->logo != '') {
                $image_path = './assets/images/'.$cid.'/' . $company_setting->logo; // Adjust the path accordingly
                if (file_exists($image_path)) {
                  $image_data = file_get_contents($image_path);
                  $base64_data = base64_encode($image_data);
            ?>

            <center>
              <img src="data:image/png;base64,<?=$base64_data?>" style="width: 80px; height: 80px !important">
            </center>

            <?php
                } 
              }
            ?>
          </td>
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
            <strong><?=$supplier_detail->company_name?></strong><br>
            <?=$supplier_detail->address?><br>
            <?=$supplier_detail->city_name.', '.$supplier_detail->state_name.', '.$supplier_detail->country_name?><br>
            <?=$this->lang->line('phone')?>: <?=$supplier_detail->phone?><br>
            <?=$this->lang->line('email')?>: <?=$supplier_detail->email?><br>
            <?=$this->lang->line('gstin')?>: <?=$supplier_detail->gstin?><br>
          </td>
          <td style="vertical-align: text-top;">
            <b><?=$this->lang->line('scrap_issue_reference_no')?>: </b><?=$scrap_issue->reference_no?><br>
            <b><?=$this->lang->line('receipt_voucher_no')?></b><br/>
            <b><?=$this->lang->line('rcm_applicability')?>: </b><?=($scrap_issue->rcm == "Y") ? "Yes" : "No"?>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <thead>
          <tr>
            <th><?=$this->lang->line('scrap_issue_sr')?></th>
            <th><?=$this->lang->line('scrap_issue_items')?></th>
            <th><?=$this->lang->line('scrap_issue_warehouse_name')?></th>
            <th><?=$this->lang->line('scrap_issue_tax')?></th>
           
            <th><?=$this->lang->line('product_batch_no')?></th>
            
            
            <th><?=$this->lang->line('product_price')?></th>
            <th><?=$this->lang->line('product_cost')?></th>
            <th><?=$this->lang->line('scrap_issue_qty')?></th>
           
            <th><?=$this->lang->line('scrap_issue_total_qty')?></th>
        
            <th><?=$this->lang->line('scrap_issue_subtotal')?></th>

          </tr>
        </thead>
        <tbody>
          <?php
            
            $i = 1;
            $total_quantity = 0;
            
            $total_total_quantity = 0;
         
            $total_price    = 0;
            $total_discount_amount = 0;
            $total_taxable_value = 0;
            $total_tax = 0;
            $total_subtotal = 0;
            $total_cost = 0;
            $total = 0;
            foreach ($scrap_issue_items as $row) 
            {
              $total_quantity         += $row->quantity;
             
              $total_total_quantity   += $row->total_quantity;
            
              $total_cost             += $row->cost;
              $total_tax              += $row->igst_tax+$row->cgst_tax+$row->sgst_tax;
              $total_subtotal         += $row->sub_total;
          ?>
            <tr style="font-size: 10px;">
              <td><?=$i++?></td>
              <td>
                <?php 
                  $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);


                  $product_description = ($row->description != '') ? '<br/>'.$row->description : '<br/>';
                  $optional = ($row->optional != '') ? '<br/>'.$row->optional.'<br/>' : '<br/>';
                ?>
                <?=$row->product_name.$product_description.$optional?>
              </td>
              <td><?=$row->warehouse_name?></td>
              <td>
                <?php 
                  if($company_setting->country_id == $supplier_detail->country_id)
                  {
                    if($scrap_issue->rcm == 'N')
                    {
                      if($company_setting->state_id == $supplier_detail->state_id)
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
                  }
                  else
                  {
                ?>
                    N/A
                <?php
                  }
                ?>
              </td>
             
              <td><?=$row->batch_no?></td>
              <td><?=$row->price?></td>
              <td><?=$row->cost?></td>
              <td><?=$row->quantity?></td>
              <td><?=$row->total_quantity?></td>
              <td><?=$row->sub_total?></td>
            </tr>
          <?php 
            }
          ?>

            <tr style="font-weight: bolder;">
              <th colspan="3">Total</th>
              <th><?=$total_tax?></th>
              <th colspan="2"></th>
              <th><?=number_format($total_cost , 2)?></th>
              <th><?=number_format($total_quantity,2)?></th>
              
              <th><?=number_format($total_total_quantity,2)?></th>
            
              <th><?=number_format($total_subtotal,2)?></th>
            </tr>  
            
            <tr>
              <td colspan="9"><b>Total Taxable Value</b></td>
              <td><b><?=$scrap_issue->total_taxable_value?><b></td>
            </tr>

            <tr>
              <td colspan="9"><b>Total Amount</b></td>
              <td><b><?=$scrap_issue->total?><b></td>
            </tr>
        </tbody>
      </table>
      <table style="width: 100%;">
        <tr class="table_header">
          <td colspan="6" style="font-weight: bolder;"><?=$this->lang->line('terms_condition')?></td>
          <td colspan="3" style="font-weight: bolder;text-align:right;"><?=$this->lang->line('certified_messages')?></td>
        </tr>
        <tr>
          <td colspan="6">
            <?=$scrap_issue->terms_and_condition?>
          </td>
          <td colspan="3" style="text-align:right">
            <?=$this->lang->line('for')?>, <?=strtoupper($company_setting->company_name)?><br/><br/><br/>

            <?php 
              $signature_data = '';

              $company_settings 	= $this->company_settings_model->get_company_records();
              $cid = $company_settings->cid;

            if($company_setting->signature != '')
            {
              $signature_path = './assets/images/'.$cid.'/' . $company_setting->signature; // Adjust the path accordingly
              if (file_exists($signature_path)) {
                $signature_data = file_get_contents($signature_path);
                $base64_data    = base64_encode($signature_data);
            ?>
              <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;">
            <?php
              } 
              }
              else
              {
                echo '<br/><br/><br/>';
              }
            ?>  

            <h3><?=$this->lang->line('signatory')?></h3>
          </td>
        </tr>
      </table>
     
  </body>
</html>
