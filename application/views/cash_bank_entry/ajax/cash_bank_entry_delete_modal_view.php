
<div class="modal-header failure-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('cash_bank_entry_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<p><?php echo $this->lang->line('cash_bank_entry_delete_message') .str_replace('_', ' ', $cash_bank_entry->voucher_type)."?";?></p>
</div>
<div class="modal-footer">
    
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <form action="<?php echo base_url('cash_bank_entry/delete');?>" method="POST" name="deletecashBankEntryForm" id="deletecashBankEntryForm">
    <input type="hidden" name="id" value="<?=$cash_bank_entry->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="deletecashBankEntrySubmit" id="deletecashBankEntrySubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
  </form>
</div>