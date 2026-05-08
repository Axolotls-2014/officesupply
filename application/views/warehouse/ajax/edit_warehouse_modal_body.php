  
<form role="form" method="post" name="editWarehouseForm" id="editWarehouseForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('warehouse_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
    <h4>
      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
    </h4>
  </div>
  <div class="modal-body">

    <div class="col-md-12">
      <div class="row">
        <div class="col-sm-6">

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_name")?>
              <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="text" name="name" id="name" value="<?=set_value("name",$warehouse->name) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("warehouse_name")?>">
              <span id="err_name" class="error invalid-feedback"></span>
            </div>
          </div>
    
     
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_code")?>
              <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="text" name="code" value="<?=set_value('code',$warehouse->code) ?>" class="form-control form-control-sm field_validation <?=(!empty(form_error('code')) ? 'is-invalid' : '')?>" id="code" placeholder="<?=$this->lang->line('warehouse_code')?>">
              <!-- <span id="err_code" class="error invalid-feedback"><?=(!empty(form_error('code')) ? str_ireplace('<p>','',str_ireplace('</p>','',form_error('code'))) : '')?></span> -->
              <span class="error text-danger" id="err_code"></span>
            </div>
          </div>
                          
          
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_description")?>
              <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="text" name="description" id="description" value="<?=set_value("description",$warehouse->description) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("warehouse_description")?>">
              <span id="err_description" class="error invalid-feedback"></span>
            </div>
          </div>
          
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
             license no.
            </label>
            <div class="col-sm-8">
              <input type="text" name="license_no" id="license_no" value="<?=set_value("license_no",$warehouse->license_no) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="license_no">
              <span id="err_description" class="error invalid-feedback"></span>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line('warehouse_is_default')?>
              <span class="text-danger">*</span> 
            </label>
            <div class="col-sm-6">
              <input type="radio" name="is_default" value="<?=WAREHOUSE_IS_DEFAULT_NO?>" <?=($warehouse->is_default == WAREHOUSE_IS_DEFAULT_NO) ? 'checked' : '' ?>> <?=strtoupper(WAREHOUSE_IS_DEFAULT_NO)?>
              <input type="radio" name="is_default" value="<?=WAREHOUSE_IS_DEFAULT_YES?>" <?=($warehouse->is_default == WAREHOUSE_IS_DEFAULT_YES) ? 'checked' : '' ?>> <?=strtoupper(WAREHOUSE_IS_DEFAULT_YES)?>
              <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
            </div>
          </div>
        </div>

        <div class="col-sm-6">
          
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_country")?><span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4 field_validation" name="country_id" id="country_id" width="100%" placeholder="<?=$this->lang->line('customer_country_id')?>" tabindex="<?=$tabindex++?>">
                <?php
                  foreach ($countries as $value) {
                ?>
                  <option value="<?=$value->id;?>"
                    <?php 
                      if($value->id == $warehouse->country_id)
                        echo ' selected';
                    ?>
                  >
                    <?= $value->name;?>
                  </option>
                <?php 
                  }
                ?>
                </select>
                <span id="err_country_id" class="error invalid-feedback"><?=form_error('country');?></span>
            </div>
          </div> 

          
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_state")?><span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4 field_validation"  name="state_id" id="state_id" width="100%" placeholder="<?=$this->lang->line('customer_state_id')?>" tabindex="<?=$tabindex++?>">
              <?php
                  foreach ($states as $value) {
                ?>
                  <option value="<?=$value->id;?>"
                    <?php 
                      if($value->id == $warehouse->state_id)
                        echo ' selected';
                    ?>
                  >
                    <?= $value->name;?>
                  </option>
                <?php 
                  }
                ?>
                </select>
                <span id="err_state_id" class="error invalid-feedback"><?=form_error('state_id');?></span>
            </div>
          </div> 

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_city")?><span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4 field_validation" name="city_id" id="city_id" width="100%" tabindex="<?=$tabindex++?>">
                <?php
                  if(isset($cities))
                  {
                    foreach ($cities as $value) 
                    {
                ?>
                    <option value="<?=$value->id;?>"
                      <?php 
                        if(isset($city_id))
                        {
                          if($city_id == $value->id)
                            echo ' selected';
                        }
                        else
                        {
                          if($value->id == $warehouse->city_id)
                            echo ' selected';
                        }
                      ?>
                    >
                      <?= $value->name;?>
                    </option>
                <?php 
                    }
                  }
                ?>
                </select>
            </div>
          </div> 

                            
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_address1")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="address_line1" value="<?=set_value("address_line1",$warehouse->address_line1) ?>" class="form-control form-control-sm" id="Address Line1" placeholder="<?=$this->lang->line("warehouse_address1")?>">
            </div>
          </div> 

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_address2")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="address_line2" value="<?=set_value("address_line2",$warehouse->address_line2) ?>" class="form-control form-control-sm" id="Address Line2" placeholder="<?=$this->lang->line("warehouse_address2")?>">
            </div>
          </div> 

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("warehouse_pincode")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="pincode" value="<?=set_value("pincode",$warehouse->pincode) ?>" class="form-control form-control-sm" id="Pincode" placeholder="<?=$this->lang->line("warehouse_pincode")?>">
            </div>
          </div> 
          
        </div>
      </div>
    </div>

   
                    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?=$warehouse->id?>">
    <button type="submit" name="submit" id="editWarehouseSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
