<table class="table table-bordered">
  <tr>
    <th><?=$this->lang->line('bank_account_name')?></th>
    <th><?=$this->lang->line('bank_account_bank_name')?></th>
    <th><?=$this->lang->line('bank_account_ifsc')?></th>
    <th><?=$this->lang->line('bank_account_account_type')?></th>
    <th><?=$this->lang->line('bank_account_opening_balance')?></th>
    <th><?=$this->lang->line('bank_account_closing_balance')?></th>
  </tr>
  <tr>
    <td><?=$bank_account->account_name?></td>
    <td><?=$bank_account->bank_name?></td>
    <td><?=$bank_account->ifsc?></td>
    <td><?=($bank_account->account_type == 0) ? 'Saving' : 'Current'; ?></td>
    <td><?=$bank_account->opening_balance?></td>
    <td><?=$bank_account->closing_balance?></td>
  </tr>
</table>