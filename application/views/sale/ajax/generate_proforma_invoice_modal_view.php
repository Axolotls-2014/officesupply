<form method="POST" name="generateInvoiceForm" id="generateInvoiceForm">

  <div class="modal-header info-header">
    <h4 class="modal-title">Generate Invoice</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <p>Are you sure want to convert sale invoice ?.</p>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
    <input type="hidden" name="proforma_invoice_ids" id="proforma_invoice_ids" value="">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="generateInvoiceSubmit" id="generateInvoiceSubmit" class="btn btn-info"><?php echo $this->lang->line('submit');?></button>
  </div>
</form>
<!--<script src="https://erp.apluscrm.in/js/js-calc.js"></script>-->
