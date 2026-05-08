<form method="POST" name="sendEmailForm" id="sendEmailForm">

  <div class="modal-header">
    <h4 class="modal-title">
      Send Mail
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">

  
    <div class="col-md-12">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              From Name<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="text" name="from_name" value="<?= ($email_template && $email_template->from_name) ? $email_template->from_name : $company_setting->company_name ?>"  class="form-control form-control-sm field_validation" id="from_name" placeholder="From Name" readonly>
              <span id="err_from_name" class="error invalid-feedback"></span>
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              Reply To<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">

              <?php
                $default_email = '';

                // Set the default email to the first email from company_setting->email
                if ($company_setting->email) {
                    $emails = explode(',', $company_setting->email);
                    $default_email = trim($emails[0]);
                }
                ?>

                <input type="email" name="from_mail" value="<?= ($email_template && $email_template->from_email) ? $email_template->from_email : $default_email ?>" class="form-control form-control-sm field_validation" id="from_mail" list="from_email_list" placeholder="From Email" autocomplete="off">

                <datalist id="from_email_list" autocomplete="off">
                    <?php
                    // Add emails from company_setting->email
                    if ($company_setting->email) {
                        $emails = explode(',', $company_setting->email);
                        foreach ($emails as $email) {
                            $email = trim($email); // Trim whitespace
                            if ($email) {
                                ?>
                                <option value="<?= $email ?>">
                                <?php
                            }
                        }
                    }
                    ?>
                </datalist>
                <span id="err_from_mail" class="error invalid-feedback"></span>
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              To Email<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="email" name="to_mail" value="<?=$supplier->email?>"  placeholder="To Email" class="form-control form-control-sm field_validation" id="to_mail">
              <input type="hidden" name="to_name" value="<?=$supplier->company_name?>" placeholder="To Email"  class="form-control form-control-sm field_validation" id="to_name">
              <span id="err_to_mail" class="error invalid-feedback"></span>
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              Cc
            </label>
            <div class="col-sm-8">
              <input type="text" name="to_cc" value="<?= $cc_emails ?>"  class="form-control form-control-sm" id="to_cc">
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              Subject<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">

            <?php 
              $supplier = $this->supplier_model->get_single_record($purchase->supplier_id);
              $search = array("{{reference_no}}", "{{company_name}}");
              $replace = array($purchase->reference_no, $supplier->company_name);
              $subject = $email_template ? str_replace($search, $replace, $email_template->subject) : $purchase->reference_no;
            ?>


            <input type="text" name="subject" value="<?= ($email_template && $email_template->subject) ? $subject : $purchase->reference_no ?>"  class="form-control form-control-sm field_validation" id="subject" placeholder="Subject">
            <span id="err_subject" class="error invalid-feedback"></span>
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              Message<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">

              <?php
                $message_content = '';

                if ($email_template && $email_template->mail_header) 
                {
                  $message_content .= ($email_template && $email_template->mail_header) ? $email_template->mail_header : '';
                 
                }

                if ($email_template && $email_template->mail_body) {
                  $message_content .= ($email_template && $email_template->mail_body) ? $email_template->mail_body : '';
                  
                    //$message_content .= $mail_body . "\n";
                }

                if ($email_template && $email_template->mail_footer) {
                  $message_content .= ($email_template && $email_template->mail_footer) ? $email_template->mail_footer : '';
                    //$message_content .= $mail_footer;
                }
                ?>
                <textarea id="message" name="message" rows="5" class="form-control form-control-sm field_validation summernote" placeholder="Message"><?= $message_content ?></textarea>
           
                <span id="err_message" class="error invalid-feedback"></span>
            
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer">

    <input type="hidden" name="purchase_id" value="<?=$purchase->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="submitEmail" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
   </div>
</form>
