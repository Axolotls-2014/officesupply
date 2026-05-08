<?php
  $paid_amount = $this->transaction_model->get_total_transaction_amount($sale->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE);
  $delivered_product_qty = $this->sale_delivery_model->get_total_no_of_quantity_delivered($sale->id);
?>

<div class="modal-header <?php echo ($paid_amount > 0 || $delivered_product_qty > 0) ? 'success-header' : 'failure-header'; ?>">
  <h4 class="modal-title">
    <?= $this->lang->line('upload_documents') ?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<div class="modal-body">
  <p>
    <?= "Upload documents for Sale with Reference No: <strong>" . $sale->reference_no . "</strong>"; ?>
  </p>

  <form id="uploadDocsForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="sale_id" value="<?= $sale->id ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

    <div class="form-group">
      <label for="document_type"><?= $this->lang->line('document_type') ?> <span class="text-danger">*</span></label>
      <select name="document_type" class="form-control" required>
        <option value="">-- Select Document Type --</option>
        <option value="Invoice Receipt">Invoice Receipt</option>
        <option value="Courier Receipt">Courier Receipt</option>
        <option value="Other Documents">Other Documents</option>
      </select>
    </div>

    <div class="form-group" id="otherDocumentTitleGroup" style="display: none;">
      <label for="other_document_title"><?= $this->lang->line('document_title') ?> <span class="text-danger">*</span></label>
      <input type="text" name="other_document_title" class="form-control">
    </div>

    <div class="form-group">
      <label for="document_file"><?= $this->lang->line('choose_file') ?> <span class="text-danger">*</span></label>
      <input type="file" name="document_file" class="form-control" required>
      <small class="form-text text-muted">Allowed formats: PDF, JPG, PNG, DOCX (Max 2MB)</small>
      <div id="file-error" class="text-danger"></div>
    </div>
  </form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?= $this->lang->line('btn_modal_close') ?>
  </button>
  <button type="button" id="uploadDocsSubmit" class="btn btn-primary">
    <?= $this->lang->line('expense_upload_file') ?>
  </button>
</div>

<script>
$(document).ready(function() {
    // Show/hide other document title field based on selection
    $('select[name="document_type"]').change(function() {
        if ($(this).val() === 'Other Documents') {
            $('#otherDocumentTitleGroup').show();
            $('input[name="other_document_title"]').prop('required', true);
        } else {
            $('#otherDocumentTitleGroup').hide();
            $('input[name="other_document_title"]').prop('required', false);
        }
    });
    
    // File validation
    $('input[name="document_file"]').change(function() {
        const file = this.files[0];
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (file) {
            if (!allowedTypes.includes(file.type)) {
                $('#file-error').text('Invalid file type. Please upload PDF, JPG, PNG, or DOCX.');
                $(this).val('');
            } else if (file.size > maxSize) {
                $('#file-error').text('File size exceeds 2MB limit.');
                $(this).val('');
            } else {
                $('#file-error').text('');
            }
        }
    });
});
</script>