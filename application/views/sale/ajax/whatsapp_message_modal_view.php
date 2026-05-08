

<style>
 
  .label-with-icon {
      display: inline-flex;
      align-items: center;
  }
  .label-with-icon .icon {
      margin-right: 5px; /* Adjust as needed */
      cursor: pointer;
      color: #D3D3D3;
  }
  .alert {
    position: absolute;
    top: 5;
    right: 0;
    /* background-color: gray; */
    color: blue;
    padding: 5px 10px;
    border-radius: 5px;
    display: none;
    z-index: 1000;
  }
</style>

<form method="POST" name="sendMessageForm" id="sendMessageForm">
  <div class="modal-header info-header">
    <h4 class="modal-title">
      <?php echo $this->lang->line('send_whatsapp_message');?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <div class="form-group row">
      <label for="wm_type" class="col-md-4 required"><?=$this->lang->line('whatsapp_template_type')?></label>
      <div class="col-md-8">
        <select class="form-control field_validation select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('whatsapp_template_type')?>" id="wm_type" name="wm_type">
          <option value="">Select</option>    
          <option value="sale_confirmation">Sale Confirmation</option>
          <option value="payment_confirmation">Payment Confirmation</option>
          <option value="payment_reminder">Payment Reminder</option>
        </select>
        
        <span class="error invalid-feedback" id="err_wm_type"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="wm_message" class="col-md-4 required"><?=$this->lang->line('whatsapp_template_message')?></label>
      <div class="col-md-8">
        <textarea type="text" name="wm_message" id="wm_message" rows="20" class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line("whatsapp_template_message")?>"></textarea>
        <span class="error invalid-feedback" id="err_wm_message"></span>
      </div>
    </div>
  </div>


  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
    <input type="hidden" name="sale_ids" id="sale_ids" value="" class="form-control form-control-sm">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="sendMessageSubmit" id="sendMessageSubmit" class="btn btn-info" value=""><?php echo $this->lang->line('submit');?></button>
  </div>
</form>
