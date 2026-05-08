<form role="form" method="post" name="addduedayForm" id="addduedayForm">
    <div class="modal-header">
        <h4 class="modal-title">Add Due Day</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="due_day" class="col-sm-4 col-form-label">
                Due Day<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="text" name="due_day" class="form-control form-control-sm field_validation" 
                    id="due_day" placeholder="Due Day">
                <span id="err_due_day" class="error invalid-feedback"></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="terms_and_condition" class="col-sm-4 col-form-label">
                Terms and Condition<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <textarea name="terms_and_condition" class="form-control form-control-sm field_validation" 
                    id="terms_and_condition" placeholder="Terms and Condition"></textarea>
                <span id="err_terms_and_condition" class="error invalid-feedback"></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="status" class="col-sm-4 col-form-label">
                Status<span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <select class="form-control form-control-sm field_validation" name="status" id="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <span id="err_status" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
            value="<?php echo $this->security->get_csrf_hash(); ?>">
        <button type="submit" name="submit" id="addduedaySubmit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>