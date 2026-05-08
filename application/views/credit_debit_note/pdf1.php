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
    </style>
  </head>
  <body>
      
      
        <table>
          <center>
             <tr>
                <td width="20%">
                  <?php 
                    $image_data = '';
                    if($company_setting->logo != '')
                    {
                      $image_data = file_get_contents(base_url().'assets/images/'.$company_setting->logo);
                      $base64_data = base64_encode($image_data);
                  ?>

                  <center>
                    <img src="data:image/png;base64,<?=$base64_data?>" style="width: 100px;">
                  </center>
                  <?php 
                    }
                  ?>      
                </td>
             </tr> 
             <tr>
               <td width="40%">
                  Company Details : <br/>
                  <strong><?=$company_setting->company_name?></strong><br>
                  <?= ($company_setting->address_line1 != '') ? ($company_setting->address_line1.'<br/>') : '' ?>
                  <?= ($company_setting->address_line2 != '') ? ($company_setting->address_line2.'<br/>') : '' ?>
                  <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
                  <?= ($company_setting->pincode != '') ? ($company_setting->pincode.'<br/>') : '' ?>
                </td>
                <td>
                  <?=$this->lang->line('phone')?>: <?=$company_setting->mobile?><br>
                  <?=$this->lang->line('email')?>: <?=$company_setting->email?><br>
                  <?=$this->lang->line('gstin_of_supplier')?>: <?=$company_setting->gstin?><br>
                  <?=$this->lang->line('company_setting_pan_no')?>: <?=$company_setting->pan_no?><br>
                  <?=$this->lang->line('company_setting_fssai_no')?>: <?=$company_setting->fssai_no?><br>
                  <?=$this->lang->line('company_setting_dl_no')?>: <?=$company_setting->dl_no?><br>
                </td>
             </tr>
          </center>
        </table>
       
        <h3>
          <?=$credit_debit_note->cdn_note_type;?>
        </h3>
      </center>
      <h3 style="float: right">
        <?=$this->lang->line('date')?>: <?=date('d-m-Y', strtotime($credit_debit_note->cdn_date))?>
      </h3>
      <br/><br/>  
      
      <table style="width: 100%;">
        <thead>
          <tr>
            <th><?=$this->lang->line('cdn_reference_no')?></th>
            <th><?=$this->lang->line('cdn_note_type')?></th>
            <th><?=$this->lang->line('cdn_ledger')?></th>
            <th><?=$this->lang->line('cdn_description')?></th>
            <th><?=$this->lang->line('cdn_taxable_amount')?></th>
            <th><?=$this->lang->line('cdn_tax_type')?></th>
            <th><?=$this->lang->line('cdn_igst')?></th>
            <th><?=$this->lang->line('cdn_igst_tax')?></th>
            <th><?=$this->lang->line('cdn_cgst')?></th>
            <th><?=$this->lang->line('cdn_cgst_tax')?></th>
            <th><?=$this->lang->line('cdn_sgst')?></th>
            <th><?=$this->lang->line('cdn_sgst_tax')?></th>
            <th><?=$this->lang->line('cdn_amount')?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?=$credit_debit_note->cdn_reference_no;?></td>
           <!--  <td>
              <?php
                $cdn_type_value = $credit_debit_note->cdn_note_type;
                $cdn_type_value_str = str_replace("enum(", "", $cdn_type_value);
              ?>
            <?=$cdn_type_value_str;?> -->
            <td></td>
                
            <td><?=$credit_debit_note->cdn_ledger_id;?></td>
            <td><?=$credit_debit_note->cdn_description;?></td>
            <td><?=$credit_debit_note->cdn_taxable_amount;?></td>
            <td><?=$credit_debit_note->cdn_tax_type;?></td>
            <td><?=$credit_debit_note->cdn_igst;?></td>
            <td><?=$credit_debit_note->cdn_igst_tax;?></td>
            <td><?=$credit_debit_note->cdn_cgst;?></td>
            <td><?=$credit_debit_note->cdn_cgst_tax;?></td>
            <td><?=$credit_debit_note->cdn_sgst;?></td>
            <td><?=$credit_debit_note->cdn_sgst_tax;?></td>
            <td><?=$credit_debit_note->cdn_amount;?></td>
          </tr>
        </tbody>
      </table>
  </body>
</html>
