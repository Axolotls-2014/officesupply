<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    </style>
  </head>
  <body>
     
      <center>
        <h3>
           Stock Transfer 
        </h3>
      </center>
       
      <h3 style="float: right">
        <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($transfer->transfer_date))?>
      </h3>
      <br/><br/><br/>                     
      <table width="100%">
        <tr style="font-weight: bolder;">
          <td><?=$this->lang->line('transfer_from_warehouse')?></td>
          <td><?=$this->lang->line('transfer_to_warehouse')?></td>
          <td><?=$this->lang->line('transfer_details')?></td>
        </tr>
        <tr>
          <td>
            <?php 
              $from_warehouse = $this->warehouse_model->get_single_record($transfer->from_warehouse_id);
              // echo $from_warehouse->name.'<br/>';
            ?>
            <address>
              <strong><?=$from_warehouse->name?></strong><br>
              <?=$from_warehouse->address_line1?><br>
              <?=$from_warehouse->address_line2?><br>
              <?=$from_warehouse->city_name.', '.$from_warehouse->state_name.', '.$from_warehouse->country_name?><br>
              <?=$from_warehouse->pincode?><br>
            </address>
          </td>
          <td>
            <?php 
              $to_warehouse = $this->warehouse_model->get_single_record($transfer->to_warehouse_id);
              // echo $to_warehouse->name.'<br/>';
            ?>
              <address>
              <strong><?=$to_warehouse->name?></strong><br>
              <?=$to_warehouse->address_line1?><br>
              <?=$to_warehouse->address_line2?><br>
              <?=$to_warehouse->city_name.', '.$to_warehouse->state_name.', '.$to_warehouse->country_name?><br>
              <?=$to_warehouse->pincode?><br>
            </address>
          </td>
          <td>
            <b>Transfer ID</b> #<?=$transfer->id?><br>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <thead>
          <tr>
            <th><?=$this->lang->line('transfer_sr')?></th>
            <th><?=$this->lang->line('transfer_items')?></th>
            <!-- <th><?=$this->lang->line('transfer_hsn')?></th> -->
            <th><?=$this->lang->line('transfer_qty')?></th>
            <th><?=$this->lang->line('sale_cost')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
            <th><?=$this->lang->line('sale_selling_price')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
            <th><?=$this->lang->line('transfer_price')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
            <!-- <th><?=$this->lang->line('transfer_discount')?> (<?=$this->session->userdata('currency_symbol')?>)</th> -->
            <th><?=$this->lang->line('transfer_uom')?></th>
            <th><?=$this->lang->line('transfer_total_taxable_value')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
            <!-- <th><?=$this->lang->line('transfer_tax')?> (<?=$this->session->userdata('currency_symbol')?>)</th> -->
            <th><?=$this->lang->line('transfer_subtotal')?> (<?=$this->session->userdata('currency_symbol')?>)</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $i = 1;
            $total_quantity = 0;
            $total_price    = 0;
            $total_cost    = 0;
            $total_selling_price    = 0;
            // $total_discount_amount = 0;
            $total_taxable_value = 0;
            // $total_tax = 0;
            $total_subtotal = 0;
            foreach ($transfer_items as $row) 
            {
              $total_quantity         += $row->quantity;
              $total_price            += $row->price;
              $total_cost            += $row->cost;
              $total_selling_price    += $row->selling_price;
              // $total_discount_amount  += $row->discount_amount;
              $total_taxable_value    += $row->taxable_value;
              // $total_tax              += $row->cgst_tax+$row->sgst_tax+$row->igst_tax;
              $total_subtotal         += $row->sub_total;
          ?>
            <tr>
              <td><?=$i++?></td>
              <td><?=$row->product_name.'<br/>'.$row->description?></td>
              <!-- <td><?=$row->hsn?></td> -->
              <td><?=$row->quantity?></td>
              <td><?=number_format_i($row->cost)?></td>
              <td><?=number_format_i($row->selling_price)?></td>
              <td><?=number_format_i($row->price)?></td>
              <!-- <td><?=number_format_i($row->discount_amount)?></td> -->
              <td><?=$row->uom_uom?></td>
              <td><?=number_format_i($row->taxable_value)?></td>
              <!-- <td>
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
              </td> -->
              <td><?=number_format_i($row->sub_total)?></td>
            </tr>
          <?php 
            }
          ?>
            <tr style="font-weight: bolder;">
              <td colspan="2">
                <?=$this->lang->line('total')?>   
              </td>
              <td><?=$total_quantity?></td>
              <td><?=number_format_i($total_cost)?></td>
              <td><?=number_format_i($total_selling_price)?></td>
              <td><?=number_format_i($total_price)?></td>
              <!-- <td><?=number_format_i($total_discount_amount)?></td> -->
              <td></td>
              <td><?=number_format_i($total_taxable_value)?></td>
              <!-- <td><?=number_format_i($total_tax)?></td> -->
              <td><?=number_format_i($total_subtotal)?></td>
            </tr>
            
          
            <tr style="font-weight: bold; ">
              <td colspan="7">
                <?=$this->lang->line('amount_in_words')?>
              </td>
              <td colspan="2" style="text-align: right;">
                <?php echo $this->numbertowords->convert_number(round($transfer->total_taxable_value));?>
              </td>
            </tr>
        </tbody>
      </table>
      <table width="100%">
        <tr style="font-weight: bolder;">
          <td width="50%">
            <?=$this->lang->line('terms_condition')?>
          </td>
        
          <td style="text-align:right;">
          <?=$this->lang->line('certified_message')?>                         
          </td>
        </tr>
        <tr>
          <td>
            <?=$transfer->terms_and_condition?>
          </td>
        
          <td style="text-align:right;">
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
