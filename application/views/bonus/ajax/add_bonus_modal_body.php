<form  name="addBonusForm" id="addBonusForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($bonus == '')
          echo $this->lang->line('bonus_add');
        else
          echo $this->lang->line('bonus_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     
    <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('tax_deduction_employee_id')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('tax_deduction_employee_id')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                $bonus_employee_id = $bonus ? $bonus->employee_id : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $bonus_employee_id) ? ' selected' : '';
            ?>
                <option value="<?=$value->employee_id;?>"<?=$selected;?>>
                    <?= $value->first_name;?>
                </option>
            <?php 
                }
            ?>
          </select>
          <span id="err_employee_id" class="error invalid-feedback"></span> 
        </div>
      </div>
  

      <div class="form-group row">
        <label for="bonus_type" class="col-md-4 required"><?=$this->lang->line('bonus_type')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="bonus_type" id="bonus_type" value="<?=($bonus != null) ? $bonus->bonus_type : '' ?>" placeholder="<?=$this->lang->line('bonus_type')?>">  
          <span id="err_bonus_type" class="error invalid-feedback"></span> 
        </div>
      </div>

      <div class="form-group row">
        <label for="bonus_date" class="col-md-4 required"><?=$this->lang->line('bonus_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker field_validation" name="bonus_date" id="bonus_date" value="<?=($bonus != null && $bonus->bonus_date != '' && $bonus->bonus_date != '0000-00-00') ? date('d-m-Y', strtotime($bonus->bonus_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('bonus_date')?>" autocomplete="off">
          <span id="err_bonus_date" class="error invalid-feedback"></span> 

        </div>
      </div>

      <div class="form-group row">
        <label for="amount" class="col-md-4"><?=$this->lang->line('bonus_amount')?></label>
        <div class="col-md-8">
          <input type="number" class="form-control form-control-sm" name="amount" id="amount" value="<?=($bonus != null) ? $bonus->amount : 0 ?>" placeholder="<?=$this->lang->line('bonus_amount')?>">  
        </div>
      </div>

   
  </div>
  <div class="modal-footer">
    <input type="hidden" name="bonus_id" id="bonus_id" value="<?=($bonus != null) ? $bonus->bonus_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addBonusSubmit" name="addBonusSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>