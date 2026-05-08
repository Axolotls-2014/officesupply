<style>
  .error-message {
    color: red;
  }
  
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
<form role="form" method="post" name="addemailTemplateForm" id="addemailTemplateForm">
  <div class="modal-header text-left">
    <h4 class="modal-title">
      <?php 
        if($email_template == '')
          echo $this->lang->line('email_template_add');
        else
          echo $this->lang->line('email_template_edit');
      ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("email_template_template_name")?>
      </label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm field_validation" name="template_name" id="template_name" value="<?=($email_template != null) ? $email_template->template_name : '' ?>" placeholder="<?=$this->lang->line("email_template_template_name")?>">  
        <span id="err_template_name" class="error invalid-feedback"></span>
      </div>
    </div>  
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("email_template_from_name")?>
      </label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm field_validation" name="from_name" id="from_name" value="<?=($email_template != null) ? $email_template->from_name : '' ?>" placeholder="<?=$this->lang->line("email_template_from_name")?>">  
        <span id="err_from_name" class="error invalid-feedback"></span>
      </div>
    </div>  

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("email_template_from_email")?>
      </label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm field_validation" name="from_email" id="from_email" value="<?=($email_template != null) ? $email_template->from_email : '' ?>" placeholder="<?=$this->lang->line("email_template_from_email")?>">  
        <span id="err_from_email" class="error invalid-feedback"></span>
      </div>
    </div>  

  

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line("email_template_subject")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm field_validation" name="subject" id="subject" value="<?=($email_template != null) ? $email_template->subject : '' ?>" placeholder="<?=$this->lang->line("email_template_subject")?>"> 
        <span class="text-blue">
          <span class="label-with-icon">
            <i class="fas fa-clipboard-list icon clipboard-icon" data-clipboard-text="{{reference_no}}" data-tt="tooltip" title="Copy"></i>
            <span class="clipboard-text" data-clipboard-text="{{reference_no}}" style="cursor:pointer;">{{reference_no}}</span>
          </span>, 
          <span class="label-with-icon">
            <i class="fas fa-clipboard-list icon clipboard-icon" data-clipboard-text="{{recipient_name}}" data-tt="tooltip" title="Copy"></i>
            <span class="clipboard-text" data-clipboard-text="{{recipient_name}}" style="cursor:pointer;">{{recipient_name}}</span>
          </span>, 
          <span class="label-with-icon">
            <i class="fas fa-clipboard-list icon clipboard-icon" data-clipboard-text="{{company_name}}" data-tt="tooltip" title="Copy"></i>
            <span class="clipboard-text" data-clipboard-text="{{company_name}}" style="cursor:pointer;">{{company_name}}</span>
          </span>
        </span>
        <span id="copy-alert" class="alert">Copied!</span>


       
      </div>
    </div>  
   
    <?php
      // Initialize the array to store assigned modules
      $assigned_modules = array();

      foreach ($email_templates as $template) {
          $assigned_modules[] = $template->module;
      }
      $assigned_modules = array_unique($assigned_modules); // Remove duplicates
    ?>

    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-3 col-form-label">
            <?=$this->lang->line("email_template_module")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-9">
            <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('email_template_module')?>" id="module" name="module">
                <option value="">Select</option>
                <?php 
                  // Retrieve all modules
                  $module_array = enum_select('email_template','module');
                  
                  // Loop through each module
                  foreach ($module_array as $module) {
                      // Check if the module is not assigned to any other template or if it matches the module assigned to the email template being edited
                      $is_assigned = in_array($module, $assigned_modules);
                      $is_selected = ($email_template != null && $module == $email_template->module);
                      
                      // Display the module in the dropdown
                      if (!$is_selected) {
                          ?>
                          <option value="<?=$module?>" <?=($is_assigned) ? 'disabled' : ''?>><?=clean_e_val($module)?></option>
                          <?php 
                      }else {
                        ?>
                        <option value="<?=$module?>" selected><?=clean_e_val($module)?></option>
                        <?php
                    }
                  }
                ?>
            </select>
            <span id="err_module" class="error invalid-feedback"><?=form_error('module');?></span>
        </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("email_template_mail_header")?>
      </label>
      <div class="col-sm-9">
        <textarea type="text" name="mail_header" id="mail_header" rows="4" class="form-control form-control-sm summernote"  placeholder="<?=$this->lang->line("email_template_mail_header")?>"><?=($email_template != null) ? $email_template->mail_header : '' ?></textarea>
        
      </div>
    </div>  

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line("email_template_mail_body")?>
      </label>
      <div class="col-sm-9">
        <textarea  name="mail_body" id="mail_body" rows="10" class="form-control form-control-sm field_validation summernote" placeholder="<?=$this->lang->line("email_template_mail_body")?>"><?=($email_template != null) ? $email_template->mail_body : '' ?></textarea>
        
      </div>
    </div>
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line("email_template_mail_footer")?>
      </label>
      <div class="col-sm-9">
        <textarea type="text" name="mail_footer" id="mail_footer" rows="10" class="form-control form-control-sm summernote"  placeholder="<?=$this->lang->line("email_template_mail_footer")?>"><?=($email_template != null) ? $email_template->mail_footer : '' ?></textarea>
        
      </div>
    </div> 
   

  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" id="id" value="<?=($email_template != null) ? $email_template->id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addEmailTemplateSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>

