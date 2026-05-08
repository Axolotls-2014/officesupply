<div class="modal-header <?php echo $employee_entries_exist ? 'warning-header' : 'failure-header'; ?>">
    <h4 class="modal-title">
        <?php echo $this->lang->line('employee_delete'); ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php if ($employee_entries_exist) { ?>
        <p><?php echo $this->lang->line('employee_delete_message_with_entries'); ?></p>
        <ul style="list-style: disc;">
            <li><?php echo $this->lang->line('employee_delete_reason_entries_exist'); ?></li>
        </ul>
    <?php } else { ?>
        <p><?php echo $this->lang->line('employee_delete_message') . $employee->first_name . "?"; ?></p>
    <?php } ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <?php echo $this->lang->line('btn_modal_close'); ?>
    </button>
    <?php if (!$employee_entries_exist) { ?>
        <form method="POST" name="deleteEmployeeForm" id="deleteEmployeeForm">
            <input type="hidden" name="employee_id" id="id" value="<?=$employee->employee_id?>">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" name="deleteEmployeeSubmit" id="deleteEmployeeSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete'); ?></button>
        </form>
    <?php } ?>
</div>
