<table class="table table-striped">
  <tr>
    <th><?=$this->lang->line('sr_no')?></th>  
    <th><?=$this->lang->line('from_account')?></th>
    <th><?=$this->lang->line('to_account')?></th>
    <th><?=$this->lang->line('transaction_amount')?></th>
    <th><?=$this->lang->line('transaction_date')?></th>
    <th width="20%"><?=$this->lang->line('transaction_action')?></th>
  </tr>
  <?php 
    if($transactions != null)
    {
      $i = 1;
      foreach ($transactions as $value) {
  ?>
  <tr>
    <td><?=$i++?></td>
    <td><?=$value->from_ledger_title?></td>
    <td><?=$value->to_ledger_title?></td>
    <td><?=$value->amount?></td>
    <td><?=date('d-M-Y H:i:s', strtotime($value->created_date))?></td>
    <td>

      <?php 
        if($value->from_account != SALE_LEDGER &&  $value->to_account != TDS_LEDGER)
        {
      ?>

        <a href="<?=base_url('sale/download_payment_receipt/'.base64_encode($sale->id).'?transaction_id='.$value->transaction_id)?>" data-tt="tooltip" title="Download Receipt" class="btn btn-primary btn-xs">
            <i class="fas fa-download"></i>
        </a>

        <a href="#" data-tt="tooltip" title="<?=$this->lang->line('transaction_delete')?>" class="btn btn-danger btn-xs delete_transaction" data-id="<?=$value->transaction_id?>">
          <i class="fas fa-trash"></i>
        </a>

        <span class="delete_transaction_confirmation delete_transaction_confirmation_label" style="display: none"><?=$this->lang->line('are_you_sure')?></span>

        <a href="#" data-tt="tooltip" title="<?=$this->lang->line('yes')?>" class="btn btn-info btn-xs delete_transaction_confirmation delete_transaction_yes" data-transaction_id="<?=$value->transaction_id?>" data-entry_id="<?=$value->entry_id?>" style="display: none">
          <?=$this->lang->line('yes')?>
        </a>

        <a href="#" data-tt="tooltip" title="<?=$this->lang->line('no')?>" class="btn btn-danger btn-xs delete_transaction_confirmation delete_transaction_no" style="display: none">
          <?=$this->lang->line('no')?>
        </a>
      <?php 
        }
      ?>
    </td>
  </tr>
  <?php 
      }
    }
    else
    {
  ?>
  <tr>
    <td colspan="5">No Record(s) are available.</td>
  </tr>
  <?php
    }
  ?>
</table>