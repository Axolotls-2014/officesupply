
<div class="modal-header failure-header">
  <h4 class="modal-title">
    <?php echo $this->lang->line('pdc_payment_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <p>
    <?php echo $this->lang->line('pdc_payment_delete_message');?>
  </p>
</div>
<div class="modal-footer">
    
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
   
    <form action="<?php echo base_url('pdc_payment/delete');?>" method="POST" name="deletePDCpaymentForm" id="deletePDCpaymentForm">
      <input type="hidden" name="id" value="<?=$pdc_payment->id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deletePDCpaymentSubmit" id="deletePDCpaymentSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
   
    
</div>