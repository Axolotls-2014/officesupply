<table class="table table-bordered" id="example2">
  <thead>
    <tr>
      <th><?=$this->lang->line('date')?></th>
      <th><?=$this->lang->line('from_account')?></th>
      <th><?=$this->lang->line('to_account')?></th>
      <th><?=$this->lang->line('transaction_type')?></th>
      <th><?=$this->lang->line('transaction_reference_no')?></th>
      <th><?=$this->lang->line('amount')?></th>
      <?php 
        if($this->permission_model->has_permission('manage_transaction'))
        {
      ?>
      <th width="10%"><?=$this->lang->line('action')?></th>
      <?php
        } 
      ?>
    </tr>  
  </thead>
  <tbody>
    <?php
      if(sizeof($transactions) > 0)
      {
        foreach ($transactions as $value) 
        {
          $from_ledger  = $this->ledger_model->get_single_record($value->from_account);
          $to_ledger    = $this->ledger_model->get_single_record($value->to_account);  
    ?>
    <tr>
      <td><?=date('d-m-Y', strtotime($value->voucher_date))?></td>
      <td>
        <?php 
          if($from_ledger != '')
            echo $from_ledger->title;
        ?>
      </td>
      <td>  
        <?php 
          if($to_ledger != null)
            echo $to_ledger->title;
        ?>
      </td>
      <td><?=($value->voucher_type == 'D') ? '<span class="text-success">Credit</span>' : '<span class="text-danger">Debit</span>';?></td>
      <td><?=$value->reference_no?></td>
      <td><?=$value->amount?></td>
      <?php 
        if($this->permission_model->has_permission('manage_transaction'))
        {
      ?>
      <td>
        
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
      </td>
      <?php 
        }
      ?>
    </tr>
    <?php
        }
      } 
      else
      {
    ?>
    <tr>
      <td colspan="7">
        <?=$this->lang->line('no_records_available')?>
      </td> 
    </tr>
    <?php
      }
    ?>
    
  </tbody>
</table>