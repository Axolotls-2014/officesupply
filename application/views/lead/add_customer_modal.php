<?php 
  $tabindex = 101;
?>  
<div class="example-modal">
  <div class="modal fade" id="add_customer_modal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" method="post" name="addCustomerForm" id="addCustomerForm">
          <div class="modal-header text-left">
            <h4 class="modal-title"><?php echo $this->lang->line('customer_add');?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4>
              <?php echo $this->lang->line('lbl_cust_delete_modal');?>
            </h4>
          </div>
          <div class="modal-body">
           
            <table style="width: 100%;" class="table">
              <tr>
                <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Personal Details</th>
                <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Billing Details</th>
                <th style="text-align: left;background-color: #F5F5F5;font-style: normal;">Shipping Details ( <input type="checkbox" id="copy_address" name="copy_address"> same as billing details)</th>
              </tr>
              <tr>
                <td style="width: 30%;">

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_company_name')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_company_name" value="<?=set_value('customer_company_name') ?>" class="form-control form-control-sm field_validation" id="customer_company_name" placeholder="<?=$this->lang->line('customer_company_name')?>">
                      <span id="err_customer_company_name" class="error invalid-feedback"><?=form_error('customer_company_name');?></span>
                    </div>
                  </div>
                  

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_name')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_name" value="<?=set_value('customer_name') ?>" class="form-control form-control-sm field_validation" id="customer_name" placeholder="<?=$this->lang->line('customer_name')?>">
                      <span id="err_customer_name" class="error invalid-feedback"><?=form_error('customer_name');?></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_buyer_name')?>
                      
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_buyer_name" value="<?=set_value('customer_buyer_name') ?>" class="form-control form-control-sm" id="customer_buyer_name" placeholder="<?=$this->lang->line('customer_buyer_name')?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_buyer_designation')?>
                      
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_buyer_designation" value="<?=set_value('customer_buyer_designation') ?>" class="form-control form-control-sm" id="customer_buyer_designation" placeholder="<?=$this->lang->line('customer_buyer_designation')?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_department')?>
                      
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="customer_department" value="<?=set_value('customer_department') ?>" class="form-control form-control-sm" id="customer_department" placeholder="<?=$this->lang->line('customer_department')?>">
                    </div>
                        </div>
                  
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_gstin')?>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="gstin" value="<?=set_value('gstin') ?>" class="form-control form-control-sm" id="gstin" placeholder="<?=$this->lang->line('customer_gstin')?>">
                      <span id="err_gstin" class="error invalid-feedback"><?=form_error('gstin');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_email')?>
                    
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="email" value="<?=set_value('email') ?>" class="form-control form-control-sm" id="email" placeholder="<?=$this->lang->line('customer_email')?>">
                      <span id="err_email" class="error invalid-feedback"><?=form_error('email');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_phone')?>
                    
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="phone" value="<?=set_value('phone') ?>" class="form-control form-control-sm" id="phone" placeholder="<?=$this->lang->line('customer_phone')?>">
                      <span id="err_phone" class="error invalid-feedback"><?=form_error('phone');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_payment_duration')?>
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4" name="payment_duration" id="payment_duration" width="100%">
                        <option value="0">Select</option>
                      <?php  
                        for ($i = 1; $i <= 180; $i++) 
                        {
                      ?>
                          <option value="<?=$i;?>">
                            <?=$i .' days';?>
                          </option>
                      <?php
                        }
                      ?>
                    </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('whatsapp_no')?>
                    
                    </label>
                    <div class="col-sm-4">
                      
                      <select class="form-control form-control-sm select2bs4 field_validation" name="whatsapp_country_code" id="whatsapp_country_code" width="100%">
                        <?php
                          $countries 	= $this->utility_model->get_countries();
                          $company_settings = $this->company_settings_model->get_company_records();
                          $selected_country_id = $company_settings->country_id;
                          
                          foreach ($countries as $value) {
                        ?>
                          <option value="<?=$value->phonecode;?>" <?php echo set_select('country_id', $value->id, $selected_country_id == $value->id); ?>>
                            <?= $value->name .' - '.$value->phonecode;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>

                    </div>
                    <div class="col-sm-4">
                      <input type="number" name="whatsapp_no" value="<?=set_value('whatsapp_no') ?>" class="form-control" id="whatsapp_no" placeholder="<?=$this->lang->line('whatsapp_no')?>">
                    </div>
                  </div><?php echo set_select('country_id', $value->id); ?>
                  
                </td>
                <td style="width: 30%;">
                  
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_country_id')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      
                      <select class="form-control form-control-sm select2bs4 field_validation" name="country_id" id="country_id" width="100%">
                        <?php
                          foreach ($countries as $value) {
                        ?>
                          <option value="<?=$value->id;?>" >
                            <?= $value->name;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_state_id')?>
                      <span class="text-danger">*</span>  
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4 field_validation"  name="state_id" id="state_id" width="100%">
                        <?php
                          if(isset($states))
                          {
                            foreach ($states as $value) 
                            {
                        ?>
                            <option value="<?=$value->id;?>" <?php echo set_select('state_id', $value->id); ?>>
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
                      <?=$this->lang->line('customer_city_id')?>
                    
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4" name="city_id" id="city_id" width="100%">
                        <?php
                          if(isset($cities))
                          {
                            foreach ($cities as $value) 
                            {
                        ?>
                            <option value="<?=$value->id;?>" <?php echo set_select('city_id', $value->id); ?>>
                              <?= $value->name;?>
                            </option>
                        <?php 
                            }
                          }
                        ?>
                      </select>
                      <!-- <span id="err_city_id" class="error invalid-feedback"><?=form_error('city_id');?></span> -->
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_address')?>
                      
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="address" value="<?=set_value('address') ?>" class="form-control form-control-sm" id="address" placeholder="<?=$this->lang->line('customer_address')?>">
                      <span id="err_address" class="error invalid-feedback"><?=form_error('address', '<div class="text-danger">', '</div>');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_pincode')?>
                      
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="pincode" value="<?=set_value('pincode') ?>" class="form-control form-control-sm" id="pincode" placeholder="<?=$this->lang->line('customer_pincode')?>">
                      <span id="err_pincode" class="error invalid-feedback"><?=form_error('pincode');?></span>
                    </div>
                  </div>
                </td>
                <td style="width: 30%;">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_country_id')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                      
                      <select class="form-control form-control-sm select2bs4 field_validation" name="shipping_country_id" id="shipping_country_id" width="100%">
                        <?php
                          foreach ($countries as $value) {
                        ?>
                          <option value="<?=$value->id;?>" <?php echo set_select('country_id', $value->id); ?>>
                            <?= $value->name;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_state_id')?>
                      <span class="text-danger">*</span>  
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4 field_validation"  name="shipping_state_id" id="shipping_state_id" width="100%">
                        <?php
                          if(isset($states))
                          {
                            foreach ($states as $value) 
                            {
                        ?>
                            <option value="<?=$value->id;?>" <?php echo set_select('state_id', $value->id); ?>>
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
                      <?=$this->lang->line('customer_shipping_city_id')?>
                     
                    </label>
                    <div class="col-sm-8">
                      <select class="form-control form-control-sm select2bs4" name="shipping_city_id" id="shipping_city_id" width="100%">
                        <?php
                          if(isset($cities))
                          {
                            foreach ($cities as $value) 
                            {
                        ?>
                            <option value="<?=$value->id;?>" <?php echo set_select('city_id', $value->id); ?>>
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
                      <?=$this->lang->line('customer_shipping_address')?>
                       
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="shipping_address" value="<?=set_value('shipping_address') ?>" class="form-control form-control-sm" id="shipping_address" placeholder="<?=$this->lang->line('customer_shipping_address')?>">
                      <span id="err_shipping_address" class="error invalid-feedback"><?=form_error('shipping_address', '<div class="text-danger">', '</div>');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">
                      <?=$this->lang->line('customer_shipping_pincode')?>
                      
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="shipping_pincode" value="<?=set_value('shipping_pincode') ?>" class="form-control form-control-sm" id="shipping_pincode" placeholder="<?=$this->lang->line('customer_shipping_pincode')?>">
                        <span id="err_shipping_pincode" class="error invalid-feedback"><?=form_error('shipping_pincode', '<div class="text-danger">', '</div>');?></span>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="submit" id="customerSubmit" tabindex="<?=$tabindex?>" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>

