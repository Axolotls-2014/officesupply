<div class="example-modal">
  <div class="modal fade" id="add_expense_category">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" method="post" name="exenseCategoryForm" id="expenseCategoryForm" action="<?php echo base_url();?>expense_category/add">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('expense_category_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="exampleInputEmail1">
                <?=$this->lang->line('expense_category_name')?>
                <span class="text-danger">*</span>
              </label>
              <input type="text" name="name" id="name" class="form-control col-12 field_validation" placeholder="Name"><?=form_error('name', '<div class="text-danger">', '</div>');?>
              <span id="err_name" class="error invalid-feedback"></span>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                <?=$this->lang->line('expense_category_description')?>
                <span class="text-danger">*</span>
              </label>
              <input type="text" name="description" id="description" class="form-control col-12 field_validation" placeholder="<?=$this->lang->line('expense_category_description')?>"><?=form_error('description', '<div class="text-danger">', '</div>');?>
              <span id="err_description" class="error invalid-feedback"></span>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="submit" id="rigCategorySubmit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>