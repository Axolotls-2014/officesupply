<div class="modal-header primary-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('bank_statement_reconcile');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Bank Account</th>
            <th>Account Number</th>
            <th>Cheque No</th>
            <th>Particulars</th>
            <th>Withdraw</th>
            <th>Deposit</th>
            <th>Reconcile Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            foreach ($bank_statement_entries as $entry) {
              
          ?>
          <tr>
            <td>
              <?=$entry->account_name?>
            </td>
            <td>
              <?=$entry->account_number?>
            </td>
            <td>
              <?=$entry->cheque_no?>
            </td>
            <td>
              <?=$entry->particulars?>
            </td>
            <td>
              <?=$entry->withdraw?>
            </td>
            <td>
              <?=$entry->deposit?>
            </td>
            <td>
                <?php 
                    $reconcileStatus = $entry->reconcile_status;
                    // Check the reconcile status and set the badge class accordingly
                    $badgeClass = ($reconcileStatus == 'pending') ? 'badge badge-warning' : 'badge badge-success';
                ?>
                <span class="reconcile-status <?= $badgeClass ?>"><?= $reconcileStatus ?></span>
            </td>
            <td>
              <button class="btn btn-xs btn-primary reconcil_entry" data-bank_statement_entry_id="<?=$entry->id?>">
                <i class="fas fa-sync mr-1"></i>
                Reconcile
              </button>
              <button class="btn btn-xs btn-success save_reconcil_entry d-none" data-bank_statement_entry_id="<?=$entry->id?>">
                <i class="fas fa-save mr-1"></i>
                Save
              </button>
              <button class="btn btn-xs btn-danger cancel_reconcil_entry d-none" data-bank_statement_entry_id="<?=$entry->id?>">
                <i class="fas fa-times mr-1"></i>
                Cancel
              </button>
            </td>
          </tr>
          <?php 
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
</div>