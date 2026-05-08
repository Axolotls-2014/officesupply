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
              <input type="text" name="from_name" value="<?=$company_setting->company_name?>"  class="form-control form-control-sm field_validation" id="from_name" readonly>
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              From Email<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="email" name="from_mail" value="<?=$company_setting->email?>"  class="form-control form-control-sm field_validation" id="from_mail">
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
              <input type="email" name="to_mail" value="<?=$supplier->email?>"  class="form-control form-control-sm field_validation" id="to_mail">
              <input type="hidden" name="to_name" value="<?=$supplier->company_name?>"  class="form-control form-control-sm field_validation" id="to_name">
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
              $supplier = $this->supplier_model->get_single_record($purchase_order->supplier_id);
              $search = array("{{reference_no}}", "{{recipient_name}}","{{company_name}}");
              $replace = array($purchase_order->reference_no, $supplier->company_name,$company_setting->company_name);
              $subject = $email_template ? str_replace($search, $replace, $email_template->subject) : $purchase_order->reference_no;
            ?>




            <input type="text" name="subject" value="<?= ($email_template && $email_template->subject) ? $subject : $purchase_order->reference_no ?>"  class="form-control form-control-sm field_validation" id="subject" placeholder="Subject">
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
              <textarea id="message" name="message" rows="5" class="form-control form-control-sm field_validation" placeholder=""></textarea>
            
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer">

    <input type="hidden" name="purchase_order_id" value="<?=$purchase_order->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="submitEmail" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
   </div>
</form>
