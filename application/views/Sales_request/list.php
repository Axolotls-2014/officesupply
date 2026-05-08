<?php $this->load->view('layout/header');?>

<?php
$status_flow = [
    'pending'        => 'approved',
    'approved'       => 'create_invoice',
    'create_invoice' => 'courier',
    'courier'        => 'in_transit',
    'in_transit'     => 'delivered',
    'delivered'             => 'awaiting_confirmation', // mail sent to user
    'awaiting_confirmation' => 'delivery_confirmed',    // final confirmation by admin
];

$status_button_text = [
    'pending'        => 'Approve',
    'approved'       => 'Create Invoice',
    'create_invoice' => 'Send to Courier',
    'courier'        => 'Enter Courier Details',
    'in_transit'     => 'Mark Delivered',
    'delivered'              => 'Send Confirmation Mail', // mail sent to user, changes status to awaiting_confirmation
    'awaiting_confirmation'  => 'Delivery Confirmed',     // final confirmation by admin
];
?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('purchase_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">

<?php
$total_purchase  = $this->db->select_sum('total')->get_where('sale_requests')->row()->total;
$total_delivered = $this->db->where('status !=', 'pending')->count_all_results('sale_requests');
$today_purchases = $this->db
    ->select_sum('total')
    ->like('created_date', date('Y-m-d')) 
    ->get('sale_requests')
    ->row()
    ->total;
?>

<!-- Info boxes -->
<div class="row">
  <div class="col-12 col-sm-12 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-store"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Sale Requests</span>
        <span class="info-box-number" style="font-size: 19px">
          <?=$this->session->userdata('currency_symbol')?> <?=($total_purchase == 0) ? '0.000' : number_format_i($total_purchase)?>
        </span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-12 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-piggy-bank"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Today's Sales Requests</span>
        <span class="info-box-number" style="font-size: 19px">
          <?= $this->session->userdata('currency_symbol') ?> <?= ($today_purchases == 0) ? '0.000' : number_format_i($today_purchases) ?>
        </span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-12 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-default elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Approved Sales Orders</span>
        <span class="info-box-number" style="font-size: 19px">
          <?= $total_delivered ?>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <form method="get" class="form-inline mb-3">
          <label for="status" class="mr-2">Filter by Status:</label>
          <select name="status" id="status" class="form-control form-control-sm mr-2">
              <option value="pending" <?= ($this->input->get('status') == 'pending') ? 'selected' : '' ?>>Pending</option>
              <option value="approved" <?= ($this->input->get('status') == 'approved') ? 'selected' : '' ?>>Approved</option>
               <option value="create_invoice" <?= ($this->input->get('status') == 'create_invoice') ? 'selected' : '' ?>>Create Invoice</option>
              <!--<option value="shipping" <?= ($this->input->get('status') == 'shipping') ? 'selected' : '' ?>>Shipping</option>-->
              <!--<option value="dispatch" <?= ($this->input->get('status') == 'dispatch') ? 'selected' : '' ?>>Dispatch</option>-->
              <!--<option value="out_for_delivery" <?= ($this->input->get('status') == 'out_for_delivery') ? 'selected' : '' ?>>Out for Delivery</option>-->
             
              <!--<option value="courier" <?= ($this->input->get('status') == 'courier') ? 'selected' : '' ?>>Enter Courier Details</option>-->
              <option value="courier">Courier (Awaiting Details)</option>
              <option value="in_transit" <?= ($this->input->get('status') == 'in_transit') ? 'selected' : '' ?>>In Transit</option>
               <option value="delivered" <?= ($this->input->get('status') == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                <option value="awaiting_confirmation" <?= ($this->input->get('status') == 'awaiting_confirmation') ? 'selected' : '' ?>>Awaiting Confirmation</option>
               <option value="delivery_confirmed" <?= ($this->input->get('status') == 'delivery_confirmed') ? 'selected' : '' ?>>
                    Delivery Confirmed
                </option>
              <option value="rejected" <?= ($this->input->get('status') == 'rejected') ? 'selected' : '' ?>>Rejected</option>
              <option value="" <?= ($this->input->get('status') == '') ? 'selected' : '' ?>>All</option>
          </select>
        </form>
        <?=$this->lang->line('purchase_list')?> 
        ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
      </div>
      
      <div class="card-body">
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
                <th>Sr.No</th>
                <th><?=$this->lang->line('purchase_invoice_no')?></th>
                <th>Ordered From </th>
                <th><?=$this->lang->line('purchase_date')?></th>
                <th><?="Taxable" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                <th><?="GST" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                <th><?=$this->lang->line('purchase_total')?></th>
                <!--<th>Approved Date</th>-->
                <th>Client Approval Date</th>
                <th>Admin Approval Date</th>
                <th><?=$this->lang->line('delivery_status')?></th>
                <th>Next Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $i = 1; 
              $sum_taxable = 0;
              $sum_tax = 0;
              $sum_total = 0;

              foreach ($purchases as $purchase): 
                $sum_taxable += $purchase->total_taxable_value ?? 0;
                $sum_tax     += $purchase->total_tax ?? 0;
                $sum_total   += $purchase->total ?? 0;
            ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td>
                <a href="<?= base_url('Purchase_request/view_admin_request/' . base64_encode($purchase->id)) ?>" class="text-primary fw-bold">
                    <?= $purchase->reference_no ?? '-' ?>
                </a>
              </td>
              <td>
                <?php
                if ($purchase->level_id == 0) {
                    $user = $this->db->get_where('clients_company', ['id' => $purchase->company_id])->row();
                    echo $user ? "Head Office (" . $user->company_name . ")" : '-';
                } else {
                    $user   = $this->db->get_where('users', ['id' => $purchase->added_by])->row();
                    $branch = $this->db->get_where('clients_branch', ['id' => $purchase->branch_id])->row();
                    $company = $branch ? $this->db->get_where('clients_company', ['id' => $branch->company_id])->row() : null;
                    echo ($company && $branch)
                        ? $company->company_name . " (Branch - " . $branch->branch_name . ")"
                        : '-';
                }
                ?>
              </td>
              <td><?= date('d-m-Y', strtotime($purchase->invoice_date)) ?></td>
              <td><?= number_format($purchase->total_taxable_value ?? 0, 2) ?></td>
              <td><?= number_format($purchase->total_tax ?? 0, 2) ?></td>
              <td><?= number_format($purchase->total ?? 0, 2) ?></td>
              <!--<td><?= !empty($purchase->approved_at) ? date("d-m-Y", strtotime($purchase->approved_at)) : '-' ?></td>-->
              
<td>
<?= !empty($purchase->client_approval_date)
    ? $purchase->client_approval_date
    : '-' ?>
</td>

<td>
<?= !empty($purchase->admin_approval_date)
    ? $purchase->admin_approval_date
    : '-' ?>
</td>


<td>
<?php
$badge_classes = [
    'pending'             => 'warning',
    'approved'            => 'primary',
    'create_invoice'      => 'info',
    'courier'             => 'secondary',
    'in_transit'          => 'info',
    'delivered'           => 'success',
    'delivery_confirmed'  => 'success',
    'awaiting_confirmation' => 'info',
    'rejected'            => 'danger'
];

// $status = $purchase->status ?? 'pending';

// echo '<span class="badge badge-' . ($badge_classes[$status] ?? 'warning') . ' text-uppercase">'
//      . str_replace('_', ' ', $status) .
//      '</span>';
if ($purchase->status === 'rejected') {
    echo '<a href="#" class="view-remark-btn" data-id="'.$purchase->id.'" data-reference="'.$purchase->reference_no.'">
            <span class="badge badge-danger text-uppercase" style="cursor: pointer;">
                <i class="fas fa-eye mr-1"></i> Rejected
            </span>
          </a>';
} else {
    echo '<span class="badge badge-' . ($badge_classes[$purchase->status] ?? 'light') . ' text-uppercase">'
         . str_replace('_', ' ', $purchase->status) .
         '</span>';
}

?>
</td>

<td>
<?php
// Courier handled separately
if ($purchase->status === 'courier') {
    echo '<button class="btn btn-sm btn-primary courier-btn" data-id="'.$purchase->id.'">
            Enter Courier Details
          </button>';
}
// Final locked states
elseif (in_array($purchase->status, ['delivery_confirmed', 'rejected'])) {
    echo '-';
}
// Pending → Allow Approve + Reject
elseif ($purchase->status === 'pending') {
    ?>
    <form method="post" action="<?= base_url('sales_request/update_status') ?>" style="display:inline-block;">
        <input type="hidden" name="order_id" value="<?= $purchase->id ?>">
        <input type="hidden" name="action" value="next">
        <button type="submit" class="btn btn-sm btn-success">
            Approve
        </button>
    </form>

    <!--<form method="post" action="<?= base_url('sales_request/update_status') ?>" style="display:inline-block;">
        <input type="hidden" name="order_id" value="<?= $purchase->id ?>">
        <input type="hidden" name="action" value="reject">
        <button type="submit" class="btn btn-sm btn-danger">
            Reject
        </button>

        
    </form>-->
    <button type="button" class="btn btn-sm btn-danger open-reject-modal" data-id="<?= $purchase->id ?>">
    Reject
</button>
    <?php
}
elseif ($purchase->status === 'awaiting_confirmation') {
    echo '<span class="badge badge-warning">Waiting for user confirmation</span> ';
    ?>
    <form method="post" action="<?= base_url('sales_request/update_status') ?>" style="display:inline-block;">
        <input type="hidden" name="order_id" value="<?= $purchase->id ?>">
        <input type="hidden" name="action" value="next">
        <button type="submit" class="btn btn-sm btn-success">
            Delivery Confirmed
        </button>
    </form>
    <?php
}
// All other progressing statuses → ONLY NEXT BUTTON
else {
    $btn_text = $status_button_text[$purchase->status] ?? 'Next Step';
    ?>
    <form method="post" action="<?= base_url('sales_request/update_status') ?>" style="display:inline-block;">
        <input type="hidden" name="order_id" value="<?= $purchase->id ?>">
        <input type="hidden" name="action" value="next">
        <button type="submit" class="btn btn-sm btn-success">
            <?= $btn_text ?>
        </button>
    </form>
    <?php
}
?>
</td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- View Rejection Remark Modal -->
<div class="modal fade" id="viewRemarkModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="fas fa-times-circle mr-2"></i> Rejection Details
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>Order #<span id="remark_order_no"></span></strong> has been rejected.
        </div>
        <div class="form-group">
          <label><i class="fas fa-comment-dots"></i> Rejection Remark:</label>
          <div class="well bg-light p-3 border rounded" id="rejection_remark_display" style="min-height: 100px; white-space: pre-wrap;">
            Loading...
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Reject Modal with Remark -->
<div class="modal fade" id="rejectRemarkModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?= base_url('sales_request/update_status') ?>" id="rejectRemarkForm">
        <input type="hidden" name="order_id" id="reject_order_id">
        <input type="hidden" name="action" value="reject">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">
            <i class="fas fa-times-circle"></i> Reject Order
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Rejection Remark <span class="text-danger">*</span></label>
            <textarea name="rejection_remarks" id="rejection_remark" class="form-control" rows="4" 
                      placeholder="Please provide reason for rejection..." required></textarea>
            <small class="text-muted">This remark will be shown to the requester.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm Reject</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Courier Details Modal -->
<div class="modal fade" id="courierModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="courierForm" method="post" action="<?= base_url('sales_request/save_courier_details'); ?>">
        <div class="modal-header">
          <h5 class="modal-title">Enter Courier Details</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="order_id" id="order_id">
          <label>Delivery Mode</label>
          <select name="delivery_mode" id="delivery_mode" class="form-control" required>
            <option value="">Select</option>
            <option value="physical">Physical Delivery</option>
            <option value="courier">Courier</option>
            <option value="vendor">Via Vendor</option>
          </select>

          <hr>
          <div id="physical_fields" style="display:none;">
            <label>Date</label>
            <input type="date" name="physical_date" class="form-control">
            <label>Person Name</label>
            <input type="text" name="physical_person" class="form-control">
          </div>

          <div id="courier_fields" style="display:none;">
            <label>Date</label>
            <input type="date" name="courier_date" class="form-control">
            <label>Courier Partner</label>
            <input type="text" name="courier_partner" class="form-control">
            <label>Docket Number</label>
            <input type="text" name="docket_number" class="form-control">
             <label>Tracking / Address Link</label>
            <input
            type="url"
            name="tracking_link"
            class="form-control"
            placeholder="https://example.com/track">
          </div>

          <div id="vendor_fields" style="display:none;">
            <label>Vendor Name</label>
            <input type="text" name="vendor_name" class="form-control">
            <label>City</label>
            <input type="text" name="vendor_city" class="form-control">
            <label>Delivery Via</label>
            <input type="text" name="delivery_via" class="form-control">
            <label>Tracking / Address Link</label>
            <input type="url"
           name="vendor_tracking_link"
           class="form-control"
           placeholder="https://www.google.com/maps?q=Warehouse">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save & Mark In Transit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
 $(document).ready(function() {
  $('#status').on('change', function() {
    $(this).closest('form').submit();
  });

  $('#example').DataTable({
    responsive: true,
    "autoWidth": false,
    "order": [],
    "pageLength": 100,
    "lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
    "columnDefs": [{ "targets": [0], "orderable": false }]
  });

  // Courier modal button click
  $('.courier-btn').on('click', function() {
      var id = $(this).data('id');
      $('#order_id').val(id);
      $('#delivery_mode').val('');
      $('#physical_fields, #courier_fields, #vendor_fields').hide();
       $('input[name="tracking_link"]').val('');
        $('input[name="vendor_tracking_link"]').val('');
      $('#courierModal').modal('show');
  });

  // Show fields based on delivery mode
  $('#delivery_mode').on('change', function() {
      var mode = $(this).val();
      $('#physical_fields, #courier_fields, #vendor_fields').hide();
      if(mode == 'physical') $('#physical_fields').show();
      if(mode == 'courier') $('#courier_fields').show();
      if(mode == 'vendor') $('#vendor_fields').show();
  });

  // Open reject modal and set order id
$(document).on('click', '.open-reject-modal', function() {
    var orderId = $(this).data('id');
    $('#reject_order_id').val(orderId);
    $('#rejection_remark').val('');
    $('#rejectRemarkModal').modal('show');
});

// Validate remark before submitting
$('#rejectRemarkForm').on('submit', function(e) {
    var remark = $('#rejection_remark').val().trim();
    if (remark === '') {
        e.preventDefault();
        alert('Please enter rejection remark');
        return false;
    }
    return true;
});

// View Rejection Remark Modal
$(document).on('click', '.view-remark-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var referenceNo = $(this).data('reference');
    
    $('#remark_order_no').text(referenceNo);
    $('#rejection_remark_display').html('<i class="fas fa-spinner fa-spin"></i> Loading remark...');
    $('#viewRemarkModal').modal('show');
    
    $.ajax({
        url: '<?= base_url("Purchase_request/get_rejection_remark") ?>',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var remarkText = response.remark || 'No remark provided';
                // Convert newlines to <br> for display
                remarkText = remarkText.replace(/\n/g, '<br>');
                $('#rejection_remark_display').html(remarkText);
            } else {
                $('#rejection_remark_display').html('<span class="text-danger">' + (response.message || 'Failed to load remark') + '</span>');
            }
        },
        error: function() {
            $('#rejection_remark_display').html('<span class="text-danger">Server error while loading rejection remark</span>');
        }
    });
});
// View Rejection Remark Modal with role-based badges

});
</script>
