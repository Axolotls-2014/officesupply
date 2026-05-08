  
<form role="form" method="post" name="editDiscountForm" id="editDiscountForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('discount_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
    <h4>
      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
    </h4>
  </div>
  <div class="modal-body">
    
     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("discount_name")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="name" id="name" value="<?=set_value("name",$discount->name) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("discount_name")?>">
        <span id="err_name" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("discount_type")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('discount_type')?>" id="type" name="type">
          <option value="">Select</option>
          <option value="0" <?php echo ($discount->type == 0) ? ' selected' : ''; ?>><?=$this->lang->line('discount_type_fixed')?></option>
          <option value="1" <?php echo ($discount->type == 1) ? ' selected' : ''; ?>><?=$this->lang->line('discount_type_percentage')?></option>
        </select>
        <span id="err_type" class="error invalid-feedback"><?=form_error('type');?></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("discount_value")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="value" id="value" value="<?=set_value("value",$discount->value) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("discount_value")?>">
        <span id="err_value" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("discount_valid_from")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="valid_from" id="valid_from" value="<?=set_value("valid_to",date('d-m-Y',strtotime($discount->valid_from))) ?>" class="form-control form-control-sm field_validation datepicker" id="client_contact_person_name" placeholder="<?=$this->lang->line("discount_valid_from")?>" autocomplete="off">
        <span id="err_valid_from" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("discount_valid_to")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="valid_to" id="valid_to" value="<?=set_value("valid_to",date('d-m-Y',strtotime($discount->valid_to))) ?>" class="form-control form-control-sm field_validation datepicker" id="client_contact_person_name" placeholder="<?=$this->lang->line("discount_valid_to")?>" autocomplete="off">
        <span id="err_valid_to" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("discount_description")?>
      </label>
      <div class="col-sm-8">
        <input type="text" name="description" id="description" value="<?=set_value("description",$discount->description) ?>" class="form-control form-control-sm" id="client_contact_person_name" placeholder="<?=$this->lang->line("discount_description")?>">
      </div>
    </div>
                    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?=$discount->id?>">
    <button type="submit" name="submit" id="editDiscountSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>

