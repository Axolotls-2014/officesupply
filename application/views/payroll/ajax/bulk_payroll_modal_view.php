<form method="POST" name="bulkPayrollForm" id="bulkPayrollForm">

  <div class="modal-header info-header">
    <h4 class="modal-title">
      <?php echo $this->lang->line('payroll_create');?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <p><?php echo $this->lang->line('payroll_create_message') ."?";?></p>
    <!-- <ul id="selectedEmployeesList">
     
    </ul> -->
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
    
    <input type="hidden" name="employee_ids" id="employee_ids">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="bulkCreatePayrollSubmit" id="bulkCreatePayrollSubmit" class="btn btn-info" value=""><?php echo $this->lang->line('submit');?></button>
  </div>
</form>
