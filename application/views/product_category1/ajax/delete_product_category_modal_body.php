<div class="modal-header failure-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('product_category_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <?php echo "Are you sure want to delete this Product_category ?";?>
</div>
<div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>

    <form method="POST" name="deleteProduct_categoryForm" id="deleteProduct_categoryForm">
      <input type="hidden" name="id" id="id" value="<?=$product_category->id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deleteProduct_categorySubmit" id="deleteProduct_categorySubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
</div>