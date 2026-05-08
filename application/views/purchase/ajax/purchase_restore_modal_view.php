
<div class="modal-header warning-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('restore_purchase');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <p>
		 	 <?php echo "Are you sure want to restore this purchase with Reference No : ".$purchase->reference_no."?";?>
		</p>
</div>
<div class="modal-footer">
    
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
   
    <form action="<?php echo base_url('purchase/restore');?>" name="restorePurchaseForm" id="restorePurchaseForm" method="POST">
      <input type="hidden" name="id" value="<?=$purchase->id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="restorePurchaseSubmit" id="restorePurchaseSubmit" class="btn btn-warning" value="">Restore</button>
    </form>
   
</div>