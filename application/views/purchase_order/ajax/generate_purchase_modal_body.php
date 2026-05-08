<form method="POST" name="generatePurchaseForm" id="generatePurchaseForm" action="<?=base_url('purchase/add_from_purchase_order')?>">

  <div class="modal-header info-header">
    <h4 class="modal-title">Generate Purchase</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <p>Are you sure want to convert purchase ?.</p>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
    <input type="hidden" name="id" id="id" value="<?=$purchase_order->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="generatePurchaseSubmit" id="generatePurchaseSubmit" class="btn btn-info"><?php echo $this->lang->line('submit');?></button>
  </div>
</form>
