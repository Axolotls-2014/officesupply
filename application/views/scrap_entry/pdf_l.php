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
          <?=$this->lang->line('header_scrap_entry')?> (<?=$scrap_entry->reference_no?>)
        </h3>
      </center>
      <h3 style="float: right">
        <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($scrap_entry->scrap_entry_date))?>
      </h3>
      <br/><br/>                     
      
      <table style="width: 100%;">
      <thead>
        <tr>
          <th><?=$this->lang->line('scrap_entry_sr')?></th>
          <th><?=$this->lang->line('scrap_entry_items')?></th>
          <th><?=$this->lang->line('proforma_invoice_batch')?></th>
          <th><?=$this->lang->line('product_cost')?></th>
          <th><?=$this->lang->line('proforma_invoice_selling_price')?></th>
          <th><?=$this->lang->line('product_price')?></th>
          <th><?=$this->lang->line('proforma_invoice_qty')?></th>
          <th><?=$this->lang->line('scrap_entry_taxable_value')?></th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $i = 1;
          $total_quantity = 0;
          
          $total_price    = 0;
          $total_discount_amount = 0;
          $total_taxable_value = 0;
          
          $total_cost = 0;
          $total_selling_price = 0;
          
          foreach ($scrap_entry_items as $row) 
          {
            $total_quantity         += $row->quantity;
            $total_cost             += $row->cost;
            $total_price            += $row->price;
            $total_selling_price    += $row->selling_price;
            $total_taxable_value    += $row->taxable_value;
          
            
        ?>
          <tr>
            <td><?=$i++?></td>
            <td>
              <?php 
                $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);

                $product_description = ($row->description != '') ? '<br/>'.$row->description : '<br/>';
                $optional = ($row->optional != '') ? '<br/>'.$row->optional.'<br/>' : '<br/>';
              ?>
              <?=$row->product_name.$product_description.$optional?>
            </td>
          
          
            <td><?=$row->batch_no?></td>
            
            <td><?=$row->cost?></td>
            <td><?=$row->selling_price?></td>
            <td><?=$row->price?></td>
            
            <td><?=$row->quantity?></td>
        
            <td><?=$row->taxable_value?></td>
            
          </tr>
        <?php 
          }
        ?>
        <tr>
          <th colspan="3">Total</th>
          
          <th><?=number_format($total_cost , 2)?></th>
          <th><?=number_format($total_selling_price , 2)?></th>
          <th><?=number_format($total_price , 2)?></th>
          <th><?=number_format($total_quantity,2)?></th>
          <th><?=number_format($total_taxable_value,2)?></th>
        </tr>


      </tbody>
      </table>
      <table style="width: 100%;">
        <tr class="table_header">
          <td colspan="4" style="font-weight: bolder;"><?=$this->lang->line('terms_condition')?></td>
          <td colspan="3" style="font-weight: bolder;text-align:right;"><?=$this->lang->line('certified_messages')?></td>
        </tr>
        <tr>
          <td colspan="4">
            <?=$scrap_entry->terms_and_condition?>
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
