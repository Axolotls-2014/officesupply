
<table class="table table-bordered" id="trancation_entries_table">
  <thead>
    <tr>
      <th colspan="3">
        Search
        <input type="text" class="form-control" name="search" id="searchInput">
      </th>
      <th colspan="5">
        Remarks: 
        <input type="text" class="form-control" name="remarks" value="<?php echo !empty($bank_statement_entry->remarks) ? $bank_statement_entry->remarks : ''; ?>">
      </th>
      <th>
        Date: 
        <input type="text" class="form-control datepicker" name="reconcile_datetime"  value="<?=($bank_statement_entry->reconcile_datetime != '' && $bank_statement_entry->reconcile_datetime != '0000-00-00') ? date('d-m-Y',strtotime($bank_statement_entry->reconcile_datetime)) : date('d-m-Y')?>">
      </th>
    </tr>
    <tr>
      <th>#</th>
      <th>Module</th>
      <th>Transaction Type</th>
      <th>Reference No</th>
      <th>Voucher Date</th>
      <th>Payment Mode</th>
      <th>Cheque No</th>
      <th>Cheque Date</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody id="trancation_entries">
    <?php 
      foreach ($transaction_entries as $entry) 
      { 
    ?>
    <tr>
      <!-- <td>
        <input type="checkbox" data-transaction_id="<?=$entry->transaction_id?>" class="single_transaction_entry">
      </td> -->
      <td>
        <input type="checkbox" 
              data-transaction_id="<?=$entry->transaction_id?>" 
              class="single_transaction_entry" 
              <?= (in_array($entry->transaction_id, explode(',',$bank_statement_entry->transaction_entries))) ? 'checked' : '' ?>>
      </td>
      <td>
        <?php
          if($entry->module == SALE_MODULE)
            echo 'Sale';
          else if($entry->module == SALE_RETURN_MODULE)
            echo 'Sale Return';
          else if($entry->module == EXPENSE_MODULE)
            echo 'Expense';
          else if($entry->module == BANK_MODULE)
            echo 'Bank Account';
          else if($entry->module == PURCHASE_MODULE)
            echo 'Purchase';
          else if($entry->module == PURCHASE_RETURN_MODULE)
            echo 'Purchase Return';
          else if($entry->module == CREDIT_DEBIT_NOTE_MODULE)
            echo 'Credit Debit Note';
          else if($entry->module == CASH_BANK_MODULE)
            echo 'Cash Bank ';
        ?>
      </td>
      <td>
        <?php
          if($entry->type == PAYMENT_TRANSACTION_TYPE)
            echo 'Payment';
          else if($entry->type == RECEIPT_TRANSACTION_TYPE)
            echo 'Receipt';
          else if($entry->type == CONTRA_TRANSACTION_TYPE)
            echo 'Contra';
          else if($entry->type == INCREASE_ADJUSTMENT_TRANSACTION_TYPE)
            echo 'Increase Capital';
          else if($entry->type == REDUCE_ADJUSTMENT_TRANSACTION_TYPE)
            echo 'Decrease Capital';
        ?>
      </td>
      <td>
        <?=$entry->reference_no?>
      </td>
      <td>
        <?=$entry->voucher_date?>
      </td>
      <td>
        <?php 
          if($entry->mode == CASH_MODE)
            echo ' Cash';
          else if($entry->mode == CREDIT_CARD_MODE)
            echo ' Credit Card';
          else if($entry->mode == CHEQUE_MODE)
            echo ' Cheque';
          else if($entry->mode == NEFT_MODE)
            echo ' NEFT/RTGS/Online Transfer'
        ?>
      </td>
      <td>
        <?=$entry->cheque_no?>
      </td>
      <td>
        <?=($entry->cheque_date != '' && $entry->cheque_date != null) ? strtotime('d-m-Y',$entry->cheque_date) : '' ?>
      </td>
      <td>
        <?=$entry->amount?>
      </td>
    </tr>
    <?php 
      }
    ?>
  </tbody>
</table>
    