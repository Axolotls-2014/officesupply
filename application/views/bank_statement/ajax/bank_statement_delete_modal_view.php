<?php
  $bank_statement_entries = $this->bank_statement_entries_model->get_records($bank_statement->id);

  // Check if any entry has reconcile status other than 'pending'
    $allow_deletion = true;
    foreach ($bank_statement_entries as $entry) {
        if ($entry->reconcile_status != BANK_RECONCILE_STATUS_PENDING) {
            $allow_deletion = false;
            break;
        }
    }
?>

<div class="modal-header <?php echo $allow_deletion ? 'failure-header' : 'warning-header'; ?>">

  <h4 class="modal-title">
     <?php echo $this->lang->line('bank_statement_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <p>
    <?php
      if (!$allow_deletion) 
      {
       echo "Some of the bank statement entries are already reconciled so you can not delete this bank statement.";
      } 
      else 
      {
        echo $this->lang->line('bank_statement_delete_message')." bank statement ?";
      }

   ?> 
  </p>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <?php
    if ($allow_deletion) 
    {
  ?>
    <form method="POST" name="deleteBankStatementForm" id="deleteBankStatementForm">
      <!-- warehouse product id -->
      <input type="hidden" name="id" id="id" value="<?=$bank_statement->id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deleteBankStatementSubmit" id="deleteBankStatementSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
  <?php
    }
  ?>
</div>