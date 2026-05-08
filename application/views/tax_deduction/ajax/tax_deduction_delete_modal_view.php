
<div class="modal-header <?php echo $payroll_exists ? 'warning-header' : 'failure-header'; ?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('tax_deduction_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <?php 
      if ($payroll_exists)
      { 
    ?>
        <p><?php echo $this->lang->line('tax_deduction_exists_message'); ?></p>
        <ul style="list-style: disc;">
            <li><?php echo $this->lang->line('tax_deduction_delete_reason_payroll_exists'); ?></li>
        </ul>
    <?php 
      } 
      else
      { 
    ?>
        <p><?php echo $this->lang->line('tax_deduction_delete_message') .$tax_deduction->tax_type."?";?></p>
    <?php 
      } 
    ?>

	
</div>
<div class="modal-footer">
    
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <?php if (!$payroll_exists){ ?>

    <form  method="POST" name="deletetaxDeductionForm" id="deletetaxDeductionForm">
      <input type="hidden" name="tax_deduction_id" id="id" value="<?=$tax_deduction->tax_deduction_id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deletetaxDeductionSubmit" id="deletetaxDeductionSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
  <?php } ?>
</div>