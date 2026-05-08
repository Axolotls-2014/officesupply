<div class="modal-header failure-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('cdn_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <?php echo "Are you sure want to delete this Credit Debit Note ?";?>
</div>
<div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>

    <form method="POST" name="deleteCreditDebitNoteForm" id="deleteCreditDebitNoteForm">
      <input type="hidden" name="id" id="id" value="<?=$credit_debit_note->cdn_id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deleteCreditDebitNoteSubmit" id="deleteCreditDebitNoteSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
</div>