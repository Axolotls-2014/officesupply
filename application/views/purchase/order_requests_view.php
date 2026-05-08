<?php $this->load->view('layout/header'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?= base_url('auth'); ?>"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item active"><?= $this->lang->line('purchase_list') ?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?= $this->lang->line('purchase_list') ?></h3>
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Invoice</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $grouped_orders = [];
                  foreach ($orders as $order) {
                      $grouped_orders[$order['order_id']][] = $order;
                  }
                  foreach ($grouped_orders as $order_id => $order_items) {
                      $total_amount = array_sum(array_column($order_items, 'total'));
                      $status = $order_items[0]['status'];
                      $user = $this->db->get_where('customer', ['id' => $order_items[0]['user_id']])->row()->customer_name;
                  ?>
                    <tr class="order-row" data-order-id="<?= $order_id ?>" data-status="<?= $status ?>">
                      <td><?= $order_id ?></td>
                      <td><?= $user ?></td>
                      <td><?= $this->session->userdata('currency_symbol') . number_format($total_amount, 2) ?></td>
                      <td><?= $status ?></td>
                      <td>
                        <a href="<?= base_url('Order_requests/invoice/' . $order_id) ?>" class="btn btn-sm btn-primary">View Invoice</a>
                      </td>
                      <td class="action-buttons">
                        <button class="btn btn-sm btn-success order-action approve" data-order-id="<?= $order_id ?>" data-action="approve">Approve</button>
                        <button class="btn btn-sm btn-danger order-action reject" data-order-id="<?= $order_id ?>" data-action="reject">Reject</button>
                        <button class="btn btn-sm btn-warning order-action out-for-delivery" data-order-id="<?= $order_id ?>" data-action="out_for_delivery" style="display: none;">Out For Delivery</button>
                        <button class="btn btn-sm btn-primary order-action delivered" data-order-id="<?= $order_id ?>" data-action="delivered" style="display: none;">Delivered</button>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function() {
      $('#example').DataTable({
          "responsive": true,
          "autoWidth": false,
          "pageLength": 10
      });

      // Initially update the buttons based on the status of the orders
      $(".order-row").each(function() {
          var orderStatus = $(this).data("status");
          var orderId = $(this).data("order-id");
          updateButtons(orderId, orderStatus);  // Call the function to hide/show the buttons based on status
      });

      // Handle Approve/Reject/Out For Delivery/Delivered Actions
     $(".order-action").click(function() {
    var orderId = $(this).data("order-id");
    var action = $(this).data("action");

    $.ajax({
        url: "<?= base_url('Order_requests/update_status') ?>",
        type: "POST",
        data: { order_id: orderId, action: action },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                updateButtons(orderId, action);

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Order status updated to ' + response.new_status,
                    timer: 2000,
                    showConfirmButton: false
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error || 'Failed to update status!',
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Try again!',
            });
        }
    });
});


      function updateButtons(orderId, actionOrStatus) {
          var newStatus = actionOrStatus;

          if (actionOrStatus === 'approve') {
              newStatus = 'Shipping';
          } else if (actionOrStatus === 'reject') {
              newStatus = 'Cancelled';
          } else if (actionOrStatus === 'out_for_delivery') {
              newStatus = 'Out For Delivery';
          } else if (actionOrStatus === 'delivered') {
              newStatus = 'Delivered';
          }

          var statusCell = $(`[data-order-id="${orderId}"]`).find('td:nth-child(4)');
          statusCell.text(newStatus); 

          var actionsCell = $(`[data-order-id="${orderId}"]`).find('.action-buttons');

          actionsCell.find('.order-action').show(); 

          if (newStatus === 'Shipping') {
              actionsCell.find('.approve, .reject').hide();
              actionsCell.find('.out-for-delivery').show(); 
          } else if (newStatus === 'Out For Delivery') {
              actionsCell.find('.approve, .reject, .out-for-delivery').hide(); 
              actionsCell.find('.delivered').show();
          } else if (newStatus === 'Delivered' || newStatus === 'Cancelled') {
              actionsCell.find('.approve, .reject, .out-for-delivery, .delivered').hide(); 
          }
      }
  });
</script>

<?php $this->load->view('layout/footer'); ?>
