
<div class="modal-header <?php echo $payroll_exists ? 'warning-header' : 'failure-header'; ?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('bonus_delete');?>
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
        <p><?php echo $this->lang->line('bonus_exists_message'); ?></p>
        <ul style="list-style: disc;">
            <li><?php echo $this->lang->line('bonus_delete_reason_payroll_exists'); ?></li>
        </ul>
    <?php 
      } 
      else
      { 
    ?>
        <p><?php echo $this->lang->line('bonus_delete_message') .$bonus->bonus_type."?";?></p>
    <?php 
      } 
    ?>

	
</div>
<div class="modal-footer">
    
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <?php if (!$payroll_exists){ ?>
    <form  method="POST" name="deleteBonusForm" id="deleteBonusForm">
      <input type="hidden" name="bonus_id" id="id" value="<?=$bonus->bonus_id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deleteBonusSubmit" id="deleteBonusSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
  <?php } ?>
</div>