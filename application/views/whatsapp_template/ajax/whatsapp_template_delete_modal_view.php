<div class="modal-header failure-header">
    <h4 class="modal-title">
        <?php echo $this->lang->line('whatsapp_template_delete'); ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
   
  <p><?php echo $this->lang->line('whatsapp_template_delete_message') . "?"; ?></p>
  
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <?php echo $this->lang->line('btn_modal_close'); ?>
    </button>
   
    <form action="<?php echo base_url('whatsapp_template/delete'); ?>" method="POST" name="deletewhatsappTemplateForm" id="deletewhatsappTemplateForm">
    <input type="hidden" name="wm_id" id="wm_id" value="<?=$whatsapp_template->wm_id?>">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <button type="submit" name="deletewhatsappTemplateSubmit" id="deletewhatsappTemplateSubmit" class="btn btn-danger">
            <?php echo $this->lang->line('btn_modal_delete'); ?>
        </button>
    </form>

</div>
