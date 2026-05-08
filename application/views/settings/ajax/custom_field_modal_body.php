<?php
$custom_fields = $this->custom_field_model->get_custom_fields_for_module($module_name);
// Filter fields for the current module
$module_fields = array_filter($custom_fields, function($field) use ($module_name) {
    return $field['module_name'] === $module_name;
});
?>

<form role="form" method="post" name="customfieldForm" id="customfieldForm">
  <div class="modal-header text-left">
    <h4 class="modal-title">Custom Fields</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    <?php if (!empty($module_fields)) { ?>
        <div class="col-sm-12">
            <div class="row">
                <?php foreach ($module_fields as $field) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-5"><?= $field['field_label'] ?></label>
                            <!-- Hidden input field to store the ID -->
                            <input class="col-sm-7" type="hidden" name="field_id[]" value="<?= $field['id'] ?>">
                            <div class="icheck-primary d-inline">
                                <input type="radio" class="<?= $field['field_name'] ?>" id="<?= $field['field_name'] ?>_status_active_<?= $field['id'] ?>" name="field_status[<?= $field['id'] ?>]" value="active" <?= ($field['field_status'] == 'active') ? 'checked' : '' ?>>
                                <label for="<?= $field['field_name'] ?>_status_active_<?= $field['id'] ?>" style="font-weight: normal;">Active</label>
                            </div>
                            <input type="hidden" name="field_id[]" value="<?= $field['id'] ?>">
                            <div class="icheck-primary d-inline">
                                <input type="radio" class="<?= $field['field_name'] ?>" id="<?= $field['field_name'] ?>_status_inactive_<?= $field['id'] ?>" name="field_status[<?= $field['id'] ?>]" value="inactive" <?= ($field['field_status'] == 'inactive' || !in_array($field['field_name'], array_column($module_fields, 'field_name'))) ? 'checked' : '' ?>>
                                <label for="<?= $field['field_name'] ?>_status_inactive_<?= $field['id'] ?>" style="font-weight: normal;">Inactive</label>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <p>No custom fields found for this module.</p>
    <?php } ?>
  </div>

  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="customfieldSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
