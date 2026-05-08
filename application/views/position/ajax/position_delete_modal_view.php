<div class="modal-header <?php echo $employee ? 'warning-header' : 'failure-header'; ?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('position_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <?php 
      if ($employee)
      { 
    ?>
        <p><?php echo $this->lang->line('position_delete_message_with_employees'); ?></p>
        <ul style="list-style: disc;">
            <li><?php echo $this->lang->line('position_delete_reason_employees_exist'); ?></li>
        </ul>
    <?php 
      } 
      else
      { 
    ?>
        <p><?php echo $this->lang->line('position_delete_message') . $position->position_title . "?"; ?></p>
    <?php 
      } 
    ?>

</div>
<div class="modal-footer">
    
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <?php if (!$employee){ ?>
    <form  method="POST" name="deletePositionForm" id="deletePositionForm">
      <input type="hidden" name="position_id" id="id" value="<?=$position->position_id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deletePositionSubmit" id="deletePositionSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
    <?php } ?>
    
</div>