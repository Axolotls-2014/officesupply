<div class="modal-header">
  <h4 class="modal-title">
     Send PDF
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
            Customer Phone
          </label>
          <div class="col-sm-8">
            <input type="text" name="phone" value="<?= isset($phone_number) ? $phone_number : ''; ?>"  class="form-control form-control-sm" id="phone" readonly>
          </div>
        </div> 
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-4 col-form-label">
            Other Phone
          </label>
          <div class="col-sm-8">
             <textarea id="other_mobile_numbers" name="other_mobile_numbers" rows="5" class="form-control form-control-sm" placeholder="Enter mobile numbers separated by commas"></textarea>
             <span class="text-info">Enter 10 digit mobile number without country code. (eg. 98765432XX)</span>
          </div>
        </div> 
      </div>
    </div>
  </div>
</div>

<div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>

  <form method="POST" name="submitCdnPDF" id="submitCdnPDF">

    <input type="hidden" name="credit_note_id" id="credit_noteId" value="<?=$credit_debit_note->cdn_id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="submitPDF" class="btn btn-primary float-right">
      <?=$this->lang->line('submit')?>
    </button>
  </form>

</div>
<!-- 
<script type="text/javascript">
  $(document).ready(function(e){


  });
</script>
 -->
