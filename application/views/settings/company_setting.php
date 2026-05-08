<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item active"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('company_setting_header')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="companySettingForm" id="companySettingForm" method="post" enctype="multipart/form-data">
              <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                  <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="pt-2 px-3"><h3 class="card-title"><?=$this->lang->line('company_setting_header')?></h3></li>
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-company-setting-tab" data-toggle="pill" href="#custom-tabs-company-setting" role="tab" aria-controls="custom-tabs-company-setting" aria-selected="false">Details</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-gst-tab" data-toggle="pill" href="#custom-tabs-gst" role="tab" aria-controls="custom-tabs-gst" aria-selected="false">GST</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-bank-details-tab" data-toggle="pill" href="#custom-tabs-bank-details" role="tab" aria-controls="custom-tabs-bank-details" aria-selected="false">Bank Details</a>
                    </li>
                   
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-term-and-condition-tab" data-toggle="pill" href="#custom-tabs-term-and-condition" role="tab" aria-controls="custom-tabs-term-and-condition" aria-selected="true">Terms & Condition</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-logo-and-favicon-tab" data-toggle="pill" href="#custom-tabs-logo-and-favicon" role="tab" aria-controls="custom-tabs-logo-and-favicon" aria-selected="true">Logo & Favicon</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-signature-tab" data-toggle="pill" href="#custom-tabs-signature" role="tab" aria-controls="custom-tabs-signature" aria-selected="true">Signature</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-currency-tab" data-toggle="pill" href="#custom-tabs-currency" role="tab" aria-controls="custom-tabs-currency" aria-selected="true">Currency</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-prefix-tab" data-toggle="pill" href="#custom-tabs-prefix" role="tab" aria-controls="custom-tabs-prefix" aria-selected="true">Prefix</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-payment-duration-tab" data-toggle="pill" href="#custom-tabs-payment-duration" role="tab" aria-controls="custom-tabs-payment-duration" aria-selected="true">Invoice Setting</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-whatsapp-tab" data-toggle="pill" href="#custom-tabs-whatsapp" role="tab" aria-controls="custom-tabs-whatsapp" aria-selected="true">Whatsapp Setting</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-upi-tab" data-toggle="pill" href="#custom-tabs-upi" role="tab" aria-controls="custom-tabs-upi" aria-selected="true">UPI</a>
                    </li>
                   
                    <!-- <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-financial-year-tab" data-toggle="pill" href="#custom-tabs-financial-year" role="tab" aria-controls="custom-tabs-financial-year" aria-selected="true">Financial Year</a>
                    </li> -->
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-company-setting" role="tabpanel" aria-labelledby="custom-tabs-company-setting-tab">
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_name')?><span class="text-danger">*</span></label>
                        
                        <div class="col-sm-4">
                          <input type="text" name="company_name" value="<?=set_value('company_name',$company_setting->company_name) ?>" class="form-control form-control-sm field_validation" id="company_name" placeholder="<?=$this->lang->line('company_setting_name')?>"><?=form_error('company_name', '<div class="text-danger">', '</div>');?>
                          <span id="err_company_name" class="error invalid-feedback"><!-- <?=form_error('company_name');?> --></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_gem_seller_id')?></label>
                        <div class="col-sm-4">
                          <input type="text" name="gem_seller_id" value="<?=set_value('gem_seller_id ',$company_setting->gem_seller_id ) ?>" class="form-control form-control-sm" id="gem_seller_id" placeholder="<?=$this->lang->line('company_setting_gem_seller_id')?>"><?=form_error('gem_seller_id', '<div class="text-danger">', '</div>');?>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_email')?><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <input type="text" name="email" value="<?=set_value('email',$company_setting->email) ?>" class="form-control form-control-sm field_validation" id="email" placeholder="Email"><?=form_error('email', '<div class="text-danger">', '</div>');?>
                          <span class="text-muted">Please enter multiple email addresses separated by commas (,)</span>
                          <span id="err_email" class="error invalid-feedback"><!-- <?=form_error('email');?> --></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_phone')?><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <input type="text" name="mobile" value="<?=set_value('mobile',$company_setting->mobile) ?>" class="form-control form-control-sm field_validation" id="mobile" placeholder="<?=$this->lang->line('company_setting_phone')?>"><?=form_error('mobile', '<div class="text-danger">', '</div>');?>
                          <span id="err_mobile" class="error invalid-feedback"><!-- <?=form_error('mobile');?> --></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_country_id')?><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                         <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->country_id?>" name="country_id" id="country_id" width="100%">

                            <?php
                              foreach ($countries as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                  if($value->id == $company_setting->country_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                              
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_country_id" class="error invalid-feedback"></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          <?=$this->lang->line('company_setting_state_id')?>
                          <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->state_id?>" name="state_id" id="state_id" width="100%" placeholder="<?=$this->lang->line('company_setting_state_id')?>">
                            <?php
                              foreach ($states as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                  if($value->id == $company_setting->state_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                              
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_state_id" class="error invalid-feedback"></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_city_id')?><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->city_id?>" name="city_id" id="city_id" width="100%">
                            <?php
                              foreach ($cities as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                  if($value->id == $company_setting->city_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                              
                            <?php 
                              }
                            ?>
                           
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_address_line1')?></label>
                        <div class="col-sm-4">
                          <input type="text" name="address_line1" value="<?=set_value('address_line1',$company_setting->address_line1) ?>" class="form-control form-control-sm field_validation" id="address_line1" placeholder="<?=$this->lang->line('company_setting_address_line1')?>">
                          <span id="err_address_line1" class="error invalid-feedback"><!-- <?=form_error('address_line1');?> --></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_address_line2')?></label>
                        <div class="col-sm-4">
                          <input type="text" name="address_line2" value="<?=set_value('address_line2',$company_setting->address_line2) ?>" class="form-control form-control-sm" id="address_line2" placeholder="<?=$this->lang->line('company_setting_address_line2')?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_pincode')?><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <input type="text" name="pincode" value="<?=set_value('pincode',$company_setting->pincode) ?>" class="form-control form-control-sm field_validation" id="pincode" placeholder="<?=$this->lang->line('company_setting_pincode')?>"><?=form_error('pincode', '<div class="text-danger">', '</div>');?>
                          <span id="err_pincode" class="error invalid-feedback"><!-- <?=form_error('pincode');?> --></span>
                        </div>
                      </div>

                      <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-2 col-form-label">
                            <?=$this->lang->line('whatsapp_no')?>
                          
                          </label>
                          <div class="col-sm-2">
                            
                            <select class="form-control form-control-sm select2bs4 field_validation" name="whatsapp_country_code" id="whatsapp_country_code" width="100%">
                              <?php
                                //$company_settings = $this->company_settings_model->get_company_records();
                                // Check if customer exists to determine selected country code
                                $selected_country_code = isset($company_setting) ? $company_setting->whatsapp_country_code : $company_setting->country_id;

                                foreach ($countries as $value) {
                              ?>
                                <option value="<?=$value->phonecode;?>"
                                  <?php 
                                    // Compare phonecode if editing, else compare country_id
                                    if((isset($company_setting) && $value->phonecode == $selected_country_code) || (!isset($company_setting) && $value->id == $selected_country_code))
                                      echo ' selected';
                                  ?>
                                >
                                  <?= $value->name .' - '.$value->phonecode;?>
                                </option>
                              <?php 
                                }
                              ?>
                            </select>

                                

                          </div>
                          <div class="col-sm-2">
                            <input type="number" name="whatsapp_no" value="<?=set_value('whatsapp_no',$company_setting->whatsapp_no) ?>" class="form-control" id="whatsapp_no" placeholder="<?=$this->lang->line('whatsapp_no')?>">
                          
                          </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="custom-tabs-gst" role="tabpanel" aria-labelledby="custom-tabs-gst-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_gst_registration_type')?><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm select2bs4 field_validation" name="gst_registration_type" id="gst_registration_type" width="100%" placeholder="<?=$this->lang->line('company_setting_gst_registration_type')?>">
                            <option value="1" <?php if($company_setting->gst_registration_type == 1) echo ' selected'; ?>><?=$this->lang->line('gst_reg_type_reg')?></option>
                            <option value="0" <?php if($company_setting->gst_registration_type == 0) echo ' selected'; ?>><?=$this->lang->line('gst_reg_type_not_reg')?></option>
                            <option value="2" <?php if($company_setting->gst_registration_type == 2) echo ' selected'; ?>><?=$this->lang->line('gst_reg_type_composite')?></option>
                          </select>
                          <span id="err_gst_registration_type" class="error invalid-feedback"><?=form_error('gst_registration_type');?></span>

                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_gstin')?></label>
                        
                        <div class="col-sm-4">
                          <input type="text" name="gstin" value="<?=set_value('gstin',$company_setting->gstin) ?>" class="form-control form-control-sm" id="gstin" placeholder="<?=$this->lang->line('company_setting_gstin').' (Example : 22AAAAA0000A1Z5)'?>">
                          <span id="err_gstin" class="error invalid-feedback"></span>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-bank-details" role="tabpanel" aria-labelledby="custom-tabs-bank-details-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          <?=$this->lang->line('company_setting_bank_detail')?>
                        </label>
                        <div class="col-sm-4">
                          <textarea class="form-control form-control-sm" rows="5" name="bank_detail" id="bank_detail" placeholder="<?=$this->lang->line('company_setting_bank_detail')?>"><?=set_value('bank_detail',str_replace("<br />","",$company_setting->bank_detail)) ?></textarea>
                          <span id="err_pincode" class="error invalid-feedback"></span>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="tab-pane fade" id="custom-tabs-financial-year-setting" role="tabpanel" aria-labelledby="custom-tabs-financial-year-setting-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_financial_year_from')?><span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                         <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->financial_year_from_month?>" name="financial_year_from_month" id="financial_year_from_month" width="50%">
                            <option value=""><?=$this->lang->line('select_year')?></option>
                            <?php
                              for($i = 1; $i <= 12 ; $i++){
                            ?>
                              <option value="<?=$i;?>"
                                <?php 
                                  if($value->id == $i)
                                    echo ' selected';
                                ?>
                              >
                                <?= DateTime::createFromFormat('!m', $i)->format('F');?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_financial_year_from_month" class="error invalid-feedback"></span>
                        </div>
                        <div class="col-sm-2">
                          <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->financial_year_from_year?>" name="financial_year_from_year" id="financial_year_from_year" width="50%">
                            <option value=""><?=$this->lang->line('select_year')?></option>
                            <?php
                              for($i = date('Y')+1; $i > (date('Y')-10) ; $i--){
                            ?>
                              <option value="<?=$i;?>"
                                <?php 
                                  if($value->id == $i)
                                    echo ' selected';
                                ?>
                              >
                                <?= $i;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_financial_year_from_year" class="error invalid-feedback"></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_financial_year_to')?><span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                          <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->financial_year_to_month?>" name="financial_year_to_month" id="financial_year_to_month" width="50%">
                            <option value=""><?=$this->lang->line('select_month')?></option>
                            <?php
                              for($i = 1; $i <= 12 ; $i++){
                            ?>
                              <option value="<?=$i;?>"
                                <?php 
                                  if($value->id == $i)
                                    echo ' selected';
                                ?>
                              >
                                <?= DateTime::createFromFormat('!m', $i)->format('F');?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_financial_year_to_month" class="error invalid-feedback"></span>
                        </div>
                        <div class="col-sm-2">
                          <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->financial_year_to_year?>" name="financial_year_to_year" id="financial_year_to_year" width="50%">
                            <option value=""><?=$this->lang->line('select_year')?></option>
                            <?php
                              for($i = date('Y')+1; $i > (date('Y')-10) ; $i--){
                            ?>
                              <option value="<?=$i;?>"
                                <?php 
                                  if($value->id == $i)
                                    echo ' selected';
                                ?>
                              >
                                <?= $i;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_financial_year_to_year" class="error invalid-feedback"></span>
                        </div>
                      </div>
                    </div> -->
                    <div class="tab-pane fade" id="custom-tabs-term-and-condition" role="tabpanel" aria-labelledby="custom-tabs-term-and-condition-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          <?=$this->lang->line('company_setting_terms_and_condition')?>
                        </label>
                        
                        <div class="col-sm-4">
                          <textarea class="form-control form-control-sm" rows="5" name="terms_and_condition" id="terms_and_condition" placeholder="<?=$this->lang->line('company_setting_terms_and_condition')?>"><?=set_value('terms_and_condition',str_replace("<br />","",$company_setting->terms_and_condition)) ?></textarea>
                          <span id="err_terms_and_condition" class="error invalid-feedback"></span>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-logo-and-favicon" role="tabpanel" aria-labelledby="custom-tabs-logo-and-favicon-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_logo')?></label>
                        <div class="col-sm-4">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="logo" name="logo" >
                            <label class="custom-file-label" for="logo">Choose file</label>
                          </div>
                          <!-- <input type="file" name="logo" class="form-control form-control-sm" id="logo"> -->
                          <?php 
                            //$image_data = '';
                            $company_settings 	= $this->company_settings_model->get_company_records();
                            $cid = $company_settings->cid;

                            if ($company_setting->logo != '') {
                              $image_path = './assets/images/'.$cid.'/' . $company_setting->logo; // Adjust the path accordingly
                              if (file_exists($image_path)) {
                          ?>

                         
                            <img src="<?=$image_path?>" height="100" width="100" style="padding-top: 5px;">
                          
                          <?php 
                              }
                            }
                          ?>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_favicon')?></label>
                        <div class="col-sm-4">
                         <!--  <input type="file" name="favicon" class="form-control form-control-sm" id="favicon"> -->
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="favicon" name="favicon" >
                            <label class="custom-file-label" for="favicon">Choose file</label>
                          </div>
                         
                          <?php 
                            //$image_data = '';
                            $company_settings 	= $this->company_settings_model->get_company_records();
                            $cid = $company_settings->cid;

                            if ($company_setting->favicon != '') {
                              $image_path = './assets/images/'.$cid.'/' . $company_setting->favicon; // Adjust the path accordingly
                              if (file_exists($image_path)) {
                          ?>

                         
                            <img src="<?=$image_path?>" height="100" width="100" style="padding-top: 5px;">
                          
                          <?php 
                              }
                            }
                          ?>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-signature" role="tabpanel" aria-labelledby="custom-tabs-signature-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_signature')?></label>
                        <div class="col-sm-4">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="signature" name="signature"><div></div>
                            <label class="custom-file-label" for="signature">Choose file</label>
                          </div>
                          <!-- <input type="file" name="signature" class="form-control form-control-sm" id="signature"> -->
                          <?php 
                            //$image_data = '';
                            $company_settings 	= $this->company_settings_model->get_company_records();
                            $cid = $company_settings->cid;

                            if ($company_setting->signature != '') {
                              $image_path = './assets/images/'.$cid.'/' . $company_setting->signature; // Adjust the path accordingly
                              if (file_exists($image_path)) {
                          ?>

                         
                            <img src="<?=$image_path?>" height="100" width="100" style="padding-top: 5px;">
                          
                          <?php 
                              }
                            }
                          ?>
                        </div>
                      </div>
                     
                    </div>
                    
                    <div class="tab-pane fade" id="custom-tabs-currency" role="tabpanel" aria-labelledby="custom-tabs-currency-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          <?=$this->lang->line('company_setting_default_currency')?>
                          <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm select2bs4 field_validation" name="currency_id" id="currency_id" width="100%" placeholder="<?=$this->lang->line('company_setting_default_currency')?>">
                            <option value=""><?=$this->lang->line('company_setting_default_currency')?></option>
                            <?php
                              foreach ($currencies as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                  if($value->id == $company_setting->currency_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_currency_id" class="error invalid-feedback"></span>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-prefix" role="tabpanel" aria-labelledby="custom-tabs-prefix-tab">

                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label"><?=$this->lang->line('company_setting_seperator')?></label>
                        <div class="col-sm-4">

                          <select class="form-control form-control-sm select2bs4" name="seperator" id="seperator" width="100%">
                                
                            <option value="<?=SEPERATOR_UNDERSCORE?>"  <?= ($company_setting->seperator === SEPERATOR_UNDERSCORE) ? 'selected' : '' ?>><?=SEPERATOR_UNDERSCORE?> (Underscore)</option>

                            <option value="<?=SEPERATOR_DASH?>" <?= ($company_setting->seperator === SEPERATOR_DASH) ? 'selected' : '' ?>><?=SEPERATOR_DASH?> (Dash)</option>

                            <option value="" <?= ($company_setting->seperator === '') ? 'selected' : '' ?>>(No Seperator)</option>
                          </select>
                        </div>
                      </div>

                      <table style="width: 100%;">
                        <tr>
                          <th></th>
                          <th>Prefix</th>
                          <th>Date Format</th>
                          <th>Sequence</th>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_purchase_order_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="purchase_order_prefix" value="<?=set_value('purchase_order_prefix',$company_setting->purchase_order_prefix) ?>" class="form-control form-control-sm" id="purchase_order_prefix" placeholder="<?=$this->lang->line('company_setting_purchase_order_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="purchase_order_date_format" id="purchase_order_date_format" width="100%">
                                <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->purchase_order_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="purchase_order_sequence" value="<?=set_value('purchase_order_sequence',$company_setting->purchase_order_sequence) ?>" class="form-control form-control-sm" id="purchase_order_sequence" placeholder="<?=$this->lang->line('company_setting_purchase_order_sequence')?>">
                          </td>

                        </tr>
                        <tr>
                          
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_purchase_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="purchase_prefix" value="<?=set_value('purchase_prefix',$company_setting->purchase_prefix) ?>" class="form-control form-control-sm" id="purchase_prefix" placeholder="<?=$this->lang->line('company_setting_purchase_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="purchase_date_format" id="purchase_date_format" width="100%">
                                
                            <option value="<?=DATE_FORMAT_Y_M?>"  <?= ($company_setting->purchase_date_format === DATE_FORMAT_Y_M) ? 'selected' : '' ?>><?=DATE_FORMAT_Y_M?></option>

                            <option value="<?=DATE_FORMAT_YM?>"  <?= ($company_setting->purchase_date_format === DATE_FORMAT_YM) ? 'selected' : '' ?>><?=DATE_FORMAT_YM?></option>

                            <option value="<?=DATE_FORMAT_M_Y?>" <?= ($company_setting->purchase_date_format === DATE_FORMAT_M_Y) ? 'selected' : '' ?>><?=DATE_FORMAT_M_Y?></option>

                            <option value="<?=DATE_FORMAT_MY?>" <?= ($company_setting->purchase_date_format === DATE_FORMAT_MY) ? 'selected' : '' ?>><?=DATE_FORMAT_MY?></option>

                            <option value="<?=DATE_FORMAT_Y_M_D?>" <?= ($company_setting->purchase_date_format === DATE_FORMAT_Y_M_D) ? 'selected' : '' ?>><?=DATE_FORMAT_Y_M_D?></option>

                            <option value="<?=DATE_FORMAT_YMD?>" <?= ($company_setting->purchase_date_format === DATE_FORMAT_YMD) ? 'selected' : '' ?>><?=DATE_FORMAT_YMD?></option>

                            <option value="<?=DATE_FORMAT_D_M_Y?>" <?= ($company_setting->purchase_date_format === DATE_FORMAT_D_M_Y) ? 'selected' : '' ?>><?=DATE_FORMAT_D_M_Y?></option>

                            <option value="<?=DATE_FORMAT_DMY?>" <?= ($company_setting->purchase_date_format === DATE_FORMAT_DMY) ? 'selected' : '' ?>><?=DATE_FORMAT_DMY?></option>
                              
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="purchase_sequence" value="<?=set_value('purchase_sequence',$company_setting->purchase_sequence) ?>" class="form-control form-control-sm" id="purchase_sequence" placeholder="<?=$this->lang->line('company_setting_purchase_sequence')?>">
                          </td>

                        </tr>

                        <tr>
                          
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_purchase_return_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="purchase_return_prefix" value="<?=set_value('purchase_return_prefix',$company_setting->purchase_return_prefix) ?>" class="form-control form-control-sm" id="purchase_return_prefix" placeholder="<?=$this->lang->line('company_setting_purchase_return_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="purchase_return_date_format" id="purchase_return_date_format" width="100%">
                                <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->purchase_return_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>                           
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="purchase_return_sequence" value="<?=set_value('purchase_return_sequence',$company_setting->purchase_return_sequence) ?>" class="form-control form-control-sm" id="purchase_return_sequence" placeholder="<?=$this->lang->line('company_setting_purchase_return_sequence')?>">
                          </td>

                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_quotation_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="quotation_prefix" value="<?=set_value('quotation_prefix',$company_setting->quotation_prefix) ?>" class="form-control form-control-sm" id="quotation_prefix" placeholder="<?=$this->lang->line('company_setting_quotation_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="quotation_date_format" id="quotation_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->quotation_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="quotation_sequence" value="<?=set_value('quotation_sequence',$company_setting->quotation_sequence) ?>" class="form-control form-control-sm" id="quotation_sequence" placeholder="<?=$this->lang->line('company_setting_quotation_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_sale_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="sale_prefix" value="<?=set_value('sale_prefix',$company_setting->sale_prefix) ?>" class="form-control form-control-sm" id="sale_prefix" placeholder="<?=$this->lang->line('company_setting_sale_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="sale_date_format" id="sale_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->sale_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="sale_sequence" value="<?=set_value('sale_sequence',$company_setting->sale_sequence) ?>" class="form-control form-control-sm" id="sale_sequence" placeholder="<?=$this->lang->line('company_setting_sale_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_sale_return_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="sale_return_prefix" value="<?=set_value('sale_return_prefix',$company_setting->sale_return_prefix) ?>" class="form-control form-control-sm" id="sale_return_prefix" placeholder="<?=$this->lang->line('company_setting_sale_return_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="sale_return_date_format" id="sale_return_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->sale_return_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>    
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="sale_return_sequence" value="<?=set_value('sale_return_sequence',$company_setting->sale_return_sequence) ?>" class="form-control form-control-sm" id="sale_return_sequence" placeholder="<?=$this->lang->line('company_setting_sale_return_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_proforma_invoice_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="proforma_invoice_prefix" value="<?=set_value('proforma_invoice_prefix',$company_setting->proforma_invoice_prefix) ?>" class="form-control form-control-sm" id="proforma_invoice_prefix" placeholder="<?=$this->lang->line('company_setting_proforma_invoice_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="proforma_invoice_date_format" id="proforma_invoice_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->proforma_invoice_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="proforma_invoice_sequence" value="<?=set_value('proforma_invoice_sequence',$company_setting->proforma_invoice_sequence) ?>" class="form-control form-control-sm" id="proforma_invoice_sequence" placeholder="<?=$this->lang->line('company_setting_proforma_invoice_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_credit_debit_note_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="credit_debit_note_prefix" value="<?=set_value('credit_debit_note_prefix',$company_setting->credit_debit_note_prefix) ?>" class="form-control form-control-sm" id="credit_debit_note_prefix" placeholder="<?=$this->lang->line('company_setting_credit_debit_note_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="credit_debit_note_date_format" id="credit_debit_note_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->credit_debit_note_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="credit_debit_note_sequence" value="<?=set_value('credit_debit_note_sequence',$company_setting->credit_debit_note_sequence) ?>" class="form-control form-control-sm" id="credit_debit_note_sequence" placeholder="<?=$this->lang->line('company_setting_credit_debit_note_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_bank_payment_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="bank_payment_prefix" value="<?=set_value('bank_payment_prefix',$company_setting->bank_payment_prefix) ?>" class="form-control form-control-sm" id="bank_payment_prefix" placeholder="<?=$this->lang->line('company_setting_bank_payment_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="bank_payment_date_format" id="bank_payment_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->bank_payment_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="bank_payment_sequence" value="<?=set_value('bank_payment_sequence',$company_setting->bank_payment_sequence) ?>" class="form-control form-control-sm" id="bank_payment_sequence" placeholder="<?=$this->lang->line('company_setting_bank_payment_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_cash_payment_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="cash_payment_prefix" value="<?=set_value('cash_payment_prefix',$company_setting->cash_payment_prefix) ?>" class="form-control form-control-sm" id="cash_payment_prefix" placeholder="<?=$this->lang->line('company_setting_cash_payment_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="cash_payment_date_format" id="cash_payment_date_format" width="100%">
                                
                             <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->cash_payment_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="cash_payment_sequence" value="<?=set_value('cash_payment_sequence',$company_setting->cash_payment_sequence) ?>" class="form-control form-control-sm" id="cash_payment_sequence" placeholder="<?=$this->lang->line('company_setting_cash_payment_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_bank_receipt_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="bank_receipt_prefix" value="<?=set_value('bank_receipt_prefix',$company_setting->bank_receipt_prefix) ?>" class="form-control form-control-sm" id="bank_receipt_prefix" placeholder="<?=$this->lang->line('company_setting_bank_receipt_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="bank_receipt_date_format" id="bank_receipt_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->bank_receipt_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="bank_receipt_sequence" value="<?=set_value('bank_receipt_sequence',$company_setting->bank_receipt_sequence) ?>" class="form-control form-control-sm" id="bank_receipt_sequence" placeholder="<?=$this->lang->line('company_setting_bank_receipt_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_cash_receipt_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="cash_receipt_prefix" value="<?=set_value('cash_receipt_prefix',$company_setting->cash_receipt_prefix) ?>" class="form-control form-control-sm" id="cash_receipt_prefix" placeholder="<?=$this->lang->line('company_setting_cash_receipt_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="cash_receipt_date_format" id="cash_receipt_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->cash_receipt_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="cash_receipt_sequence" value="<?=set_value('cash_receipt_sequence',$company_setting->cash_receipt_sequence) ?>" class="form-control form-control-sm" id="cash_receipt_sequence" placeholder="<?=$this->lang->line('company_setting_cash_receipt_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_contra_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="contra_prefix" value="<?=set_value('contra_prefix',$company_setting->contra_prefix) ?>" class="form-control form-control-sm" id="contra_prefix" placeholder="<?=$this->lang->line('company_setting_contra_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="contra_date_format" id="contra_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->contra_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="contra_sequence" value="<?=set_value('contra_sequence',$company_setting->contra_sequence) ?>" class="form-control form-control-sm" id="contra_sequence" placeholder="<?=$this->lang->line('company_setting_contra_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_scrap_issue_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="scrap_issue_prefix" value="<?=set_value('scrap_issue_prefix',$company_setting->scrap_issue_prefix) ?>" class="form-control form-control-sm" id="scrap_issue_prefix" placeholder="<?=$this->lang->line('company_setting_scrap_issue_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="scrap_issue_date_format" id="scrap_issue_date_format" width="100%">
                                
                             <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->scrap_issue_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>                            
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="scrap_issue_sequence" value="<?=set_value('scrap_issue_sequence',$company_setting->scrap_issue_sequence) ?>" class="form-control form-control-sm" id="scrap_issue_sequence" placeholder="<?=$this->lang->line('company_setting_scrap_issue_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_scrap_entry_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="scrap_entry_prefix" value="<?=set_value('scrap_entry_prefix',$company_setting->scrap_entry_prefix) ?>" class="form-control form-control-sm" id="scrap_entry_prefix" placeholder="<?=$this->lang->line('company_setting_scrap_entry_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="scrap_entry_date_format" id="scrap_entry_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->scrap_entry_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="scrap_entry_sequence" value="<?=set_value('scrap_entry_sequence',$company_setting->scrap_entry_sequence) ?>" class="form-control form-control-sm" id="scrap_entry_sequence" placeholder="<?=$this->lang->line('company_setting_scrap_entry_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_scrap_receive_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="scrap_receive_prefix" value="<?=set_value('scrap_receive_prefix',$company_setting->scrap_receive_prefix) ?>" class="form-control form-control-sm" id="scrap_receive_prefix" placeholder="<?=$this->lang->line('company_setting_scrap_receive_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="scrap_receive_date_format" id="scrap_receive_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->scrap_receive_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="scrap_receive_sequence" value="<?=set_value('scrap_receive_sequence',$company_setting->scrap_receive_sequence) ?>" class="form-control form-control-sm" id="scrap_receive_sequence" placeholder="<?=$this->lang->line('company_setting_scrap_receive_sequence')?>">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_employee_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="employee_prefix" value="<?=set_value('employee_prefix',$company_setting->employee_prefix) ?>" class="form-control form-control-sm" id="employee_prefix" placeholder="<?=$this->lang->line('company_setting_employee_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="employee_date_format" id="employee_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->employee_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="employee_sequence" value="<?=set_value('employee_sequence',$company_setting->employee_sequence) ?>" class="form-control form-control-sm" id="employee_sequence" placeholder="Employee Sequence">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_department_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="department_prefix" value="<?=set_value('department_prefix',$company_setting->department_prefix) ?>" class="form-control form-control-sm" id="department_prefix" placeholder="<?=$this->lang->line('company_setting_department_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="department_date_format" id="department_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->department_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="department_sequence" value="<?=set_value('department_sequence',$company_setting->department_sequence) ?>" class="form-control form-control-sm" id="department_sequence" placeholder="Department Sequence">
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 25%;">
                            <label for="inputEmail3" class="col-form-label"><?=$this->lang->line('company_setting_position_prefix')?></label>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="position_prefix" value="<?=set_value('position_prefix',$company_setting->position_prefix) ?>" class="form-control form-control-sm" id="position_prefix" placeholder="<?=$this->lang->line('company_setting_position_prefix')?>">
                          </td>

                          <td style="width: 25%;">
                            <select class="form-control form-control-sm" name="position_date_format" id="position_date_format" width="100%">
                                
                              <?php                                 
                                foreach ($date_formats as $format): ?>
                                    <option value="<?= htmlspecialchars($format) ?>" <?= ($company_setting->position_date_format === $format) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($format) ?>
                                    </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                          <td style="width: 25%;">
                            <input type="text" name="position_sequence" value="<?=set_value('position_sequence',$company_setting->position_sequence) ?>" class="form-control form-control-sm" id="position_sequence" placeholder="Position Sequence">
                          </td>
                        </tr>

                      </table> 
                      

                    </div>

                    <div class="tab-pane fade" id="custom-tabs-payment-duration" role="tabpanel" aria-labelledby="custom-tabs-payment-duration-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          Payment Duration for Sale
                          <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm select2bs4" name="payment_duration_for_sale" id="payment_duration_for_sale" width="100%">
                            <?php  
                              for ($i = 1; $i <= 180; $i++) 
                              {
                            ?>
                                <option value="<?=$i;?>"

                                  <?php
                                    if($company_setting->payment_duration_for_sale == $i){
                                      echo ' selected';
                                    }
                                  ?>

                                  >
                                  <?=$i .' days';?>
                                </option>
                            <?php
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          Payment Duration for Purchase
                          <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm select2bs4" name="payment_duration_for_purchase" id="payment_duration_for_purchase" width="100%">
                            <?php  
                              for ($i = 1; $i <= 180; $i++) 
                              {
                            ?>
                                <option value="<?=$i;?>"

                                  <?php
                                    if($company_setting->payment_duration_for_purchase == $i){
                                      echo ' selected';
                                    }
                                  ?>

                                  >
                                  <?=$i .' days';?>
                                </option>
                            <?php
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-whatsapp" role="tabpanel" aria-labelledby="custom-tabs-whatsapp-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          Instance ID
                          
                        </label>
                        <div class="col-sm-4">
                          <input type="text" name="instance_id" value="<?=set_value('instance_id',$company_setting->instance_id) ?>" class="form-control form-control-sm" id="instance_id" placeholder="Instance ID">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          Access Token
                        </label>
                        <div class="col-sm-4">
                          <input type="text" name="access_token" value="<?=set_value('access_token',$company_setting->access_token) ?>" class="form-control form-control-sm" id="access_token" placeholder="Access token">
                          <span id="err_access_token" class="error invalid-feedback"></span>
                        </div>
                      </div>

                     
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-upi" role="tabpanel" aria-labelledby="custom-tabs-upi-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          UPI status
                        </label>
                        <div class="col-sm-4">
                          <div class="icheck-primary d-inline">
                            <input type="radio" class="upi_status" id="upi_status_no" name="upi_status" value="no" <?=($company_setting->upi_status == 'no') ? 'checked' : ''?>>
                            <label for="upi_status_no" class="normal_font">
                              No
                            </label>
                          </div>
                          <div class="icheck-primary d-inline">
                            <input type="radio" class="upi_status" id="upi_status_yes" name="upi_status" value="yes" <?=($company_setting->upi_status == 'yes') ? 'checked' : ''?>>
                            <label for="upi_status_yes" class="normal_font">
                              Yes
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          UPI
                        </label>
                        <div class="col-sm-4">
                          <input type="text" name="upi_address" value="<?=set_value('upi_address',$company_setting->upi_address) ?>" class="form-control form-control-sm" id="upi_address" placeholder="UPI">
                          <span id="err_upi_address"></span>
                        
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                          Qr type
                        </label>
                        <div class="col-sm-4">
                          <div class="icheck-primary d-inline">
                            <input type="radio" id="qr_type_company_based" name="qr_type" value="company_based" <?=($company_setting->qr_type == 'company_based') ? 'checked' : ''?>>
                            <label for="qr_type_company_based" class="normal_font">
                              Company based
                            </label>
                          </div>
                          <div class="icheck-primary d-inline">
                            <input type="radio" id="qr_type_invoice_based" name="qr_type" value="invoice_based" <?=($company_setting->qr_type == 'invoice_based') ? 'checked' : ''?>>
                            <label for="qr_type_invoice_based" class="normal_font">
                              Invoice based
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">
                        Show QR Code in Proforma Invoice
                        </label>
                        <div class="col-sm-4">
                          <div class="icheck-primary d-inline">
                            <input type="radio" id="show_qr_code_in_proforma_no" name="show_qr_code_in_proforma" value="no" <?=($company_setting->show_qr_code_in_proforma == 'no') ? 'checked' : ''?>>
                            <label for="show_qr_code_in_proforma_no" class="normal_font">
                              No
                            </label>
                          </div>
                          <div class="icheck-primary d-inline">
                            <input type="radio" id="show_qr_code_in_proforma_yes" name="show_qr_code_in_proforma" value="yes" <?=($company_setting->show_qr_code_in_proforma == 'yes') ? 'checked' : ''?>>
                            <label for="show_qr_code_in_proforma_yes" class="normal_font">
                              Yes
                            </label>
                          </div>
                        </div>
                      </div>

                    </div>
                    <!-- <div class="tab-pane fade" id="custom-tabs-financial-year" role="tabpanel" aria-labelledby="custom-tabs-financial-year-tab">
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><?=$this->lang->line('company_setting_financial_year_from')?><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                         <select class="form-control form-control-sm select2bs4 field_validation" name="financial_year" id="financial_year" width="50%">
                            <option value=""><?=$this->lang->line('select_year')?></option>
                            <?php
                              $year = date('Y');
                              for($i = $year; $i >= date('Y')-3 ; $i--){
                            ?>
                              <option value="<?=$i;?>"
                                <?php 
                                  if($company_setting->financial_year == $i){
                                    echo ' selected';
                                  }else if($i == $year){
                                    echo ' selected';
                                  }

                                ?>
                              >
                                <?=($i-1).'-'.$i?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_financial_year" class="error invalid-feedback"></span>
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" name="submit" id="companysettingSubmit" class="btn btn-info" data-tt="tooltip" title="<?=$this->lang->line('click_save')?>"><?=$this->lang->line('company_setting_save')?></button>
                </div>
                <!-- /.card -->
              </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">

  $(document).ready(function(e){

    var gstReg = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

    $('form#companySettingForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#companysettingSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#companySettingForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#companySettingForm #err_"+id).text(field+ " field is required.");
            $('form#companySettingForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#companySettingForm #err_"+id).text("");
            $('form#companySettingForm #'+id).removeClass('is-invalid');
            $('form#companySettingForm #'+id).addClass('is-valid');
          }

          if (!gstReg.test($('form#companySettingForm #gstin').val()) && $('form#companySettingForm #gst_registration_type').val() == 1) 
          {
            if($('form#companySettingForm #gstin').val() == '')
            {
              // $('form#companySettingForm #gstin').val('');
              $("form#companySettingForm #err_gstin").text('GSTIN field is required');
              $('form#companySettingForm #gstin').addClass('is-invalid');
              isError = true;
            }
            else
            {
              // $('form#companySettingForm #gstin').val('');
              $("form#companySettingForm #err_gstin").text('Please enter valid GSTIN');
              $('form#companySettingForm #gstin').addClass('is-invalid');
              isError = true;  
            }
          }
          else
          {
            
            $("form#companySettingForm #err_gstin").text("");
            $('form#companySettingForm #gstin').removeClass('is-invalid');
            $('form#companySettingForm #gstin').addClass('is-valid');
          }
      });


      if(isError == true)
      {
         $('#companysettingSubmit').text('<?=$this->lang->line("company_setting_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#companySettingForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#companySettingForm #err_"+id).text(field+ " field is required.");
          $('form#companySettingForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#companySettingForm #err_"+id).text("");
          $('form#companySettingForm #'+id).removeClass('is-invalid');
          $('form#companySettingForm #'+id).addClass('is-valid');
        }
    });

    $("form#companySettingForm #gstin").on("blur keyup change",  function (event){

      if (!gstReg.test($('form#companySettingForm #gstin').val()) && $('form#companySettingForm #gst_registration_type').val() == 1) 
      {        
        if($('form#companySettingForm #gstin').val() == "")
        {
          $("form#companySettingForm #err_gstin").text("GSTIN field is required.");
          $('form#companySettingForm #gstin').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#companySettingForm #err_gstin").text("Please enter valid GSTIN.");
          $('form#companySettingForm #gstin').addClass('is-invalid');
          return false;
        }
      }
      else
      {
        $("form#companySettingForm #err_gstin").text("");
        $('form#companySettingForm #gstin').removeClass('is-invalid');
        $('form#companySettingForm #gstin').addClass('is-valid');
      }
    });

  });
</script>


<script>
  
  $(document).ready(function(e){
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

    $('#country_id').change(function(){
      var id = $(this).val();
       // alert(id);
      $('#state_id').html('<option value="">Select</option>');
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
           url: "<?php echo base_url('utility/get_states') ?>/"+id,
           type: "GET",
           dataType: "JSON",
           success: function(data){
             for(i=0;i<data.length;i++){
               $('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
             }
           }
         });
    });

    $('#state_id').change(function(){
      var id = $(this).val();
      
      $('#city_id').html('<option value="">Select</option>');
      $.ajax({
           url: "<?php echo base_url('utility/get_cities') ?>/"+id,
           type: "GET",
           dataType: "JSON",
           success: function(data){
             for(i=0;i<data.length;i++){
               $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
             }
           }
         });
    });
  });
</script>
