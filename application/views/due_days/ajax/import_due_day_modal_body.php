<form role="form" method="post" name="importduedayForm" id="importduedayForm" enctype="multipart/form-data">
    <div class="modal-header">
        <h4 class="modal-title">Import Due Days</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="csvfile">CSV File</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="csvfile" name="csvfile" accept=".csv">
                    <label class="custom-file-label" for="csvfile">Choose file</label>
                </div>
            </div>
            <small class="form-text text-muted">
                Download <a href="<?php echo base_url('assets/sample_files/due_days_sample.csv'); ?>" download>sample CSV file</a> for reference.
            </small>
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="update_due_day" name="update_due_day" value="1">
                <label for="update_due_day" class="custom-control-label">Update existing due days</label>
            </div>
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="create_due_day" name="create_due_day" value="1">
                <label for="create_due_day" class="custom-control-label">Create new if already exists</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
            value="<?php echo $this->security->get_csrf_hash(); ?>">
        <button type="submit" name="submit" id="importduedaySubmit" class="btn btn-primary">Import</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Show file name when selected
    $('#csvfile').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>