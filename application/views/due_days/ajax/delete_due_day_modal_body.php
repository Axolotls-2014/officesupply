<form role="form" method="post" name="deleteduedayForm" id="deleteduedayForm">
    <div class="modal-header">
        <h4 class="modal-title">Delete Due Day</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="id" value="<?php echo $due_day->id; ?>">
        <p>Are you sure you want to delete the due day <strong><?php echo $due_day->due_day; ?></strong>?</p>
        <p class="text-danger">This action cannot be undone.</p>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
            value="<?php echo $this->security->get_csrf_hash(); ?>">
        <button type="submit" name="submit" id="deleteduedaySubmit" class="btn btn-danger">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    </div>
</form>