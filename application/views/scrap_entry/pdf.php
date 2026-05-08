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
          <?=$this->lang->line('header_pack_slip')?> (<?=$pack_slip->reference_no?>)
        </h3>
      </center>
      <h3 style="float: right">
        <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($pack_slip->pack_slip_date))?>
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
            <strong><?=$customer_detail->customer_name?></strong><br>
            <?=$customer_detail->address?><br>
            <?=$customer_detail->city_name.', '.$customer_detail->state_name.', '.$customer_detail->country_name?><br>
            <?=$this->lang->line('phone')?>: <?=$customer_detail->phone?><br>
            <?=$this->lang->line('email')?>: <?=$customer_detail->email?><br>
            <?=$this->lang->line('gstin')?>: <?=$customer_detail->gstin?><br>
          </td>
          <td style="vertical-align: text-top;">
            <b><?=$this->lang->line('pack_slip_reference_no')?></b><?=$pack_slip->reference_no?><br>
            <b><?=$this->lang->line('receipt_voucher_no')?></b><br/>
            <b><?=$this->lang->line('rcm_applicability')?>: </b><?=($pack_slip->rcm == "Y") ? "Yes" : "No"?>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <thead>
          <tr>
            <th><?=$this->lang->line('pack_slip_sr')?></th>
            <th><?=$this->lang->line('pack_slip_items')?></th>
            <th><?=$this->lang->line('pack_slip_tax')?></th>
            <th><?=$this->lang->line('product_expiry_date')?></th>
            <th><?=$this->lang->line('product_pack')?></th>
            <th><?=$this->lang->line('product_packing_type')?></th>
            <th><?=$this->lang->line('product_batch_no')?></th>
            
            
            <th><?=$this->lang->line('product_price')?></th>
            <th><?=$this->lang->line('product_ptd')?></th>
            <th><?=$this->lang->line('pack_slip_qty')?></th>
            <th><?=$this->lang->line('pack_slip_free_qty')?></th>
            <th><?=$this->lang->line('pack_slip_total_qty')?></th>
            <th><?=$this->lang->line('pack_slip_shipper_quantity')?></th>
            <th><?=$this->lang->line('pack_slip_no_of_case')?></th>
            <th><?=$this->lang->line('pack_slip_no_of_box')?></th>
            <th><?=$this->lang->line('scrap_entry_taxable_value')?></th>

          </tr>
        </thead>
        <tbody>
          <?php
            
            $i = 1;
            $total_quantity = 0;
            $total_free_quantity = 0;
            $total_total_quantity = 0;
            $total_case = 0;
            $total_box = 0;
            $total_price    = 0;
            $total_discount_amount = 0;
            $total_taxable_value = 0;
            $total_tax = 0;
            $total_subtotal = 0;
            $total_cost = 0;
            foreach ($pack_slip_items as $row) 
            {
              $total_quantity         += $row->quantity;
              $total_free_quantity    += $row->free_quantity;
              $total_total_quantity   += $row->total_quantity;
              $total_case             += $row->no_of_case;
              $total_box              += $row->no_of_box;
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
              <td>
                <?php 
                  if($company_setting->country_id == $customer_detail->country_id)
                  {
                    if($pack_slip->rcm == 'N')
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
                  }
                  else
                  {
                ?>
                    N/A
                <?php
                  }
                ?>
              </td>
              <td><?=($row->expiry_date != '' && $row->expiry_date != '0000-00-00') ? date('d-m-Y',strtotime($row->expiry_date)) : '' ?></td>
              <td><?=$row->pack?></td>
              <td><?=$row->packing_type?></td>
              <td><?=$row->batch_no?></td>
              
              <td><?=$row->price?></td>
              <td><?=$row->cost?></td>
              <td><?=$row->quantity?></td>
              <td><?=$row->free_quantity?></td>
              <td><?=$row->total_quantity?></td>
              <td><?=$row->case_size?></td>
              <td><?=$row->no_of_case?></td>
              <td><?=$row->no_of_box?></td>
              <td><?=$row->sub_total?></td>
             
            </tr>
          <?php 
            }
          ?>

            <tr style="font-weight: bolder;">
              <th colspan="2">Total</th>
              <th><?=$total_tax?></th>
              <th colspan="5"></th>
              <th><?=number_format($total_cost , 2)?></th>
              <th><?=number_format($total_quantity,2)?></th>
              <th><?=number_format($total_free_quantity,2)?></th>
              <th><?=number_format($total_total_quantity,2)?></th>
            
              <th></th>
              <th><?=$total_case?></th>
              <th><?=$total_box?></th>
              <th><?=$total_subtotal?></th>
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
            <?=$pack_slip->terms_and_condition?>
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
