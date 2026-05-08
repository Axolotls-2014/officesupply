<table class="sticky-header-table table table-bordered table-striped" id="transaction-data">
  <thead>
    <tr>
      <th>Voucher Date</th>
      <th>Module</th>
      <th>From Account</th>
      <th>Reference No</th>
      <th>Narration</th>
      <th>Mode</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    <?php

      $total_amount = 0;
      
      if(sizeof($transactions) > 0)
      {
        foreach ($transactions as $value) 
        {
          $total_amount += $value->amount;
    ?>
    <tr>                       
      <td><?php echo $value->voucher_date;?></td>
      <td>
        <?php 
          if($value->module == SALE_MODULE)
            echo 'Sale';
          else if($value->module == PURCHASE_RETURN_MODULE)
            echo 'Purchase Return';
          else if($value->module == SALE_RETURN_MODULE)
            echo 'Sale Return';
          else if($value->module == EXPENSE_MODULE)
            echo 'Expense';
          else if($value->module == BANK_MODULE)
            echo 'Bank';
          else if($value->module == PURCHASE_MODULE)
            echo 'Purchase';
          else if($value->module == CREDIT_DEBIT_NOTE_MODULE)
            echo 'Credit Debit Note';
          else if($value->module == CASH_BANK_MODULE)
            echo 'Cash Bank';
        ?>
      </td>
      <td><?php echo $value->title;?></td>
      <td><?php echo $value->reference_no;?></td>
      <td><?php echo $value->narration;?></td>
      <td>
        <?php
          if($value->mode == 0)
            echo 'Cash';
          else if($value->mode == 1)
            echo 'Credit Card';
          else if($value->mode == 2)
            echo 'Cheque';         
          else if($value->mode == 3)
            echo 'NEFT/RTGS';                            
        ?>
      </td>
      <td><?php echo number_format_i($value->amount);?></td>
    </tr>
    <?php 
        }
      }
      else
      {
    ?>
    <tr>
      <td colspan="7"><?=$this->lang->line('no_records_available')?></td>
    </tr>
    <?php
      }
    ?>
  </tbody>
  <tfoot>
    <tr style="font-size: 16px;font-weight: bolder;">
      <th><?=$this->lang->line('total')?></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th><?=number_format_i($total_amount)?></th>
    </tr>
  </tfoot>
</table>