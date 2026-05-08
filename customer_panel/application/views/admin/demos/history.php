 <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
      <!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">Purchase History</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item active" aria-current="page">List</li>
					</ol>
				</nav>
			</div>
		</div>
		<div class="card">
		  <!--<div class="container mt-4">-->
		  <!--    <a href="<?= base_url('demo/category') ?>">-->
    <!--         <button class="btn btn-grd btn-grd-primary px-5 p-2 btn-md w-100 mb-4">Add Categories</button>-->
    <!--         </a>-->
    <!--       </div>-->

			<div class="card-body">
				<div class="table-responsive">
					<table id="example2" class="table table-striped table-bordered">
						<thead>
							<tr>
							    <th>Id</th>
                                <th>Invoice No</th>
                                <th>Purchase Date</th>
                                <th>Total (₹)</th>
                                <th>Delivery Status</th>
                                <th>Action</th>
							</tr>
						</thead>
					 <tbody>
            <?php $id = 1; foreach ($orders as $order): ?>
                <tr>
                    <td><?= $id++; ?></td>
                    <td>
                        <a href="<?= base_url('demo/invoice/' . $order->order_id) ?>" class="text-primary">
                            <?= 'OSS' . $order->order_id; ?>
                        </a>
                    </td>
                    <td><?= date('d-m-Y', strtotime($order->created_at)); ?></td>
                    <td>₹<?= $order->subtotal; ?></td>
                    <td>
            <?php 
                $status = strtolower($order->status);
                $status_classes = [
                    'pending'  => 'badge bg-warning text-dark',
                    'approved' => 'badge bg-primary',
                    'shipping' => 'badge bg-info text-dark',
                    'out for delivery' => 'badge bg-secondary',
                    'delivered'  => 'badge bg-success',
                ];
                $status_class = $status_classes[$status] ?? 'badge bg-light text-dark';
            ?>
            <span class="<?= $status_class; ?>"><?= ucfirst($order->status); ?></span>
           <?php if ($status == 'delivered'): ?>
                <!-- Modal trigger with ID -->
                <a href="javascript:void(0);" id="upload_btn_<?= $order->order_id ?>" class="<?= $status_class; ?>">Upload</a>
            <?php endif; ?>
        </td>
        <td>
            <div class="d-flex justify-content-between">
                <a href="<?= base_url('demo/invoice/' . $order->order_id) ?>" class="share-link" 
                   data-project="<?= base_url('Order_requests/invoice/' . $order->order_id) ?>">
                    <div class="font-11"><i class="lni lni-eye text-info"></i></div>
                </a>
            </div>
        </td>
    </tr>

    <!-- Modal for Upload -->
       <div class="modal fade" id="uploadModal_<?= $order->order_id ?>" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel_<?= $order->order_id ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel_<?= $order->order_id ?>">Upload File for Order <?= $order->order_id ?></h5>
                    <!-- Close button with manual trigger -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal(<?= $order->order_id ?>)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <?php $receipt_types = ['invoice_receipt' => 'Invoice Receipt', 'courier_receipt' => 'Courier Receipt', 'eway_bill_receipt' => 'Eway Bill Receipt']; ?>
                    <?php foreach ($receipt_types as $type_key => $type_label): ?>
                        <form action="<?= base_url('demo/upload/' . $order->order_id) ?>" method="post" enctype="multipart/form-data" class="mb-2">
                            <input type="hidden" name="receipt_type" value="<?= $type_key ?>">
                            <div class="form-group">
                                <label><?= $type_label ?>:</label>
                                <input type="file" class="form-control" name="file" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><?= $type_label ?> Upload</button>
                        </form>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

 <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.delete-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault(); 
          const deleteUrl = `<?= base_url('demo/delete/') ?>${this.getAttribute('data-id')}`;
          Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = deleteUrl; 
            }
          });
        });
      });
    });
</script>

<script>
    $(document).ready(function() {
        // Attach click event listener to each 'Upload' button
        <?php foreach ($orders as $order): ?>
            $('#upload_btn_<?= $order->order_id ?>').on('click', function() {
                // Open the modal for this order
                $('#uploadModal_<?= $order->order_id ?>').modal('show');
            });
        <?php endforeach; ?>
    });
</script>
<script>
    // Close modal manually
    function closeModal(orderId) {
        $('#uploadModal_' + orderId).modal('hide');
    }
</script>
  <!--end main wrapper-->