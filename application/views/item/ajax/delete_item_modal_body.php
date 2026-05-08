<div class="modal-header failure-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('item_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <?php echo "Are you sure want to delete this Item ?";?>
</div>
<div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>

    <form method="POST" name="deleteItemForm" id="deleteItemForm">
      <input type="hidden" name="id" id="id" value="<?=$item->id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deleteItemSubmit" id="deleteItemSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
</div>