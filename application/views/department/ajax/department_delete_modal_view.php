

<div class="modal-header <?php echo $employee ? 'warning-header' : 'failure-header'; ?>">
    <h4 class="modal-title">
        <?php echo $this->lang->line('department_delete'); ?>
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
        <p><?php echo $this->lang->line('department_delete_message_with_employees'); ?></p>
        <ul style="list-style: disc;">
            <li><?php echo $this->lang->line('department_delete_reason_employees_exist'); ?></li>
        </ul>
    <?php 
      } 
      else
      { 
    ?>
        <p><?php echo $this->lang->line('department_delete_message') . $department->department_name . "?"; ?></p>
    <?php 
      } 
    ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <?php echo $this->lang->line('btn_modal_close'); ?>
    </button>
    <?php if (!$employee){ ?>
        <form action="<?php echo base_url('department/delete'); ?>" method="POST" name="deleteDepartmentForm" id="deleteDepartmentForm">
        <input type="hidden" name="department_id" id="id" value="<?=$department->department_id?>">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" name="deleteDepartmentSubmit" id="deleteDepartmentSubmit" class="btn btn-danger">
                <?php echo $this->lang->line('btn_modal_delete'); ?>
            </button>
        </form>
    <?php } ?>
</div>
