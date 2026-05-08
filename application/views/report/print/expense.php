<style type="text/css">
  .footer_data{
    font-size: 10px;
    background-color: #dee2e6;
  }
</style>
<table width="100%" style="text-align: center;font-size: 10px;">
  <tr>
    <td>
      <?=$company_setting->company_name?>
    </td>
  </tr>
  <tr>
    <td>
      <?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name.'. - '.$company_setting->pincode ?>
    </td>
  </tr>
  <tr>
    <td>
      <?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').'.: '.$company_setting->mobile.'. '?>
    </td>
  </tr>
  <tr>
    <td>
      <?=$this->lang->line('expense_report')?>
    </td>
  </tr>
  <tr>
    <td>
     <?=$this->lang->line('print_from_date').': '.$from_date.' - '.$this->lang->line('print_to_date').': '.$to_date?>
    </td>
  </tr>
</table>
<table  border="1" style="text-align: center;font-size: 10px" width="100%" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th><?=$this->lang->line('expense_date')?></th>
      <th><?=$this->lang->line('expense_expense_category')?></th>
      <th><?=$this->lang->line('expense_supplier')?></th>
      <th><?=$this->lang->line('expense_amount')?></th>
      <th><?=$this->lang->line('expense_cgst')?></th>
      <th><?=$this->lang->line('expense_sgst')?></th>
      <th><?=$this->lang->line('expense_igst')?></th>
      <th><?=$this->lang->line('expense_total_amount')?></th>
    </tr>
  </thead>
  <tbody>
    <?php

      $total_amount     = 0;
      $total_cgst       = 0;
      $total_sgst       = 0;
      $total_igst       = 0;
      $total_t_amount   = 0;

      if(sizeof($expenses) > 0)
      {
        foreach ($expenses as $value) 
        {
          $cgst           = ($value->amount*$value->cgst)/100;
          $sgst           = ($value->amount*$value->sgst)/100;
          $igst           = ($value->amount*$value->igst)/100;

          $total_amount   += $value->amount;
          $total_cgst     += $cgst;
          $total_sgst     += $sgst;
          $total_igst     += $igst;
          $total_t_amount += $value->total_amount;
    ?>
    <tr>                        
      <td><?php echo date('d-m-Y', strtotime($value->date));?></td>
      <td><?php echo $value->expense_category_name;?></td>
      <td><?php echo $value->company_name;?></td>
      <td><?php echo number_format_i($value->amount);?></td>
      <td><?php echo number_format_i($cgst);?></td>
      <td><?php echo number_format_i($sgst);?></td>
      <td><?php echo number_format_i($igst);?></td>
      <td><?php echo number_format_i($value->total_amount);?></td>
    </tr>
    <?php  
        }
      }
      else
      {
    ?>
    <tr>
      <td colspan="8"><?=$this->lang->line('no_records_available')?>/td>
    </tr>
    <?php
      }
    ?>
  </tbody>
  <tfoot  class="bg-gray disabled footer_data">
    <tr>
      <th colspan="3"></th>
      <th><?=number_format_i($total_amount)?></th>
      <th><?=number_format_i($total_cgst)?></th>
      <th><?=number_format_i($total_sgst)?></th>
      <th><?=number_format_i($total_igst)?></th>
      <th><?=number_format_i($total_t_amount);?>
    </tr>
  </tfoot>
</table> <script>window.print();</script>