

<div class="modal-header info-header">
  <h4 class="modal-title">
    <?php echo $this->lang->line('quotation_change_status');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<p><?php echo "Change the Status of this Quotation with Reference No - ".$quotation->reference_no." to "?></p>
  <div class="form-group clearfix">
    <div class="icheck-primary d-inline">
      <input type="radio" id="radioPrimary1" name="quotation_status" value="<?=QUOTATION_STATUS_APPROVED?>">
      <label for="radioPrimary1">
        <?=strtoupper(QUOTATION_STATUS_APPROVED)?>
      </label>
    </div>
    <div class="icheck-primary d-inline">
      <input type="radio" id="radioPrimary2" name="quotation_status" value="<?=QUOTATION_STATUS_REJECTED?>" >
      <label for="radioPrimary2">
        <?=strtoupper(QUOTATION_STATUS_REJECTED)?>
      </label>
    </div>
    <div class="icheck-primary d-inline">
      <input type="radio" id="radioPrimary3" name="quotation_status" value="<?=QUOTATION_STATUS_PENDING?>" >
      <label for="radioPrimary3">
        <?=strtoupper(QUOTATION_STATUS_PENDING)?>
      </label>
    </div>
  </div>
  <span class="error" id="quotation_status"></span>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <form action="<?php echo base_url('quotation/change_status');?>" method="POST" name="changeQuotationStatusForm" id="changeQuotationStatusForm">
    <input type="hidden" name="id" value="<?=$quotation->id?>">
    <input type="hidden" name="change_status" value="">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="changeQuotationStatusSubmit" id="changeQuotationStatusSubmit" class="btn btn-secondary" value=""><?php echo $this->lang->line('quotation_change_status');?></button>
  </form>
</div>