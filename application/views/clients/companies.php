<?php $this->load->view('layout/header'); ?>

<!-- DataTables CSS (in header or before table) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Companies List</li>
      </ol>
    </section>
    <section class="content mt-3">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Companies List</h3>
            <div class="card-tools">
            <a href="#" class="nav-link btn-primary btn-sm add_product_category_modal text-white" data-toggle="modal" data-target="#add_brand_modal" data-tt="tooltip" title="Click here to Add Product Brand"><i class="fas fa-user-friends mr-2"></i>Add Company</a>
            </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="example" class="table table-bordered table-striped">
              <thead>
                        <tr>
                          <th>Sr. No.</th>
                          <th>Company Name</th>
                          <th>GSTIN</th>
                          <th>Billing Address</th>
                          <th>Shipping Address</th>
                          <th>Status</th>
                          <th width="15%"><?=$this->lang->line("product_category_action")?></th>   
                        </tr>
                      </thead>
                  <tbody>
               <?php if (!empty($employees)) {
                $i = 1;
                foreach ($employees as $brand) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($brand->company_name) ?></td>
                            <td><?= htmlspecialchars($brand->gstin) ?></td>
                            <td><?= htmlspecialchars($brand->billing_details) ?></td>
                            <td><?= htmlspecialchars($brand->shipping_details) ?></td>
                            <td><?= htmlspecialchars($brand->status) ?></td>
                            <td>
                          <a href="#" 
  class="btn btn-sm btn-warning edit-brand-btn" 
  data-id="<?= $brand->id ?>">
  Edit
</a>
                         <a href="<?= base_url('clients/pricing/'.$brand->id) ?>" 
                             class="btn btn-sm btn-info" 
                             title="Edit Pricing">
                             <i class="fas fa-tags"></i> Pricing
                          </a>
                        </td>
                          </tr>
                      <?php }
                      } else { ?>
                          <tr>
                            <td colspan="2" class="text-center">No brands found.</td>
                          </tr>
                      <?php } ?>
                    </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<!-- ADD COMPANY MODAL -->
<div class="modal fade" id="add_brand_modal" tabindex="-1" role="dialog" aria-labelledby="addCompanyLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"> <!-- wider modal -->
    <form method="POST" action="<?= base_url('clients/insert') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
             value="<?= $this->security->get_csrf_hash(); ?>" />
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Company</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body row">
          <div class="form-group col-md-6">
            <label>Company Name</label>
            <input type="text" class="form-control" name="company_name" required>
          </div>
          <div class="form-group col-md-6">
            <label>GSTIN</label>
            <input type="text" class="form-control" name="gstin">
          </div>

          <!-- BILLING DETAILS -->
          <div class="col-12"><h6><b>Billing Address</b></h6><hr></div>
          <div class="form-group col-md-4">
            <label>Country</label>
            <select class="form-control" name="country_id" id="country_id" required>
              <option value="">-- Select Country --</option>
              <?php foreach($countries as $c): ?>
                <option value="<?= $c->id ?>"><?= $c->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>State</label>
            <select class="form-control" name="state_id" id="state_id" required>
              <option value="">-- Select State --</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>City</label>
            <select class="form-control" name="city_id" id="city_id" required>
              <option value="">-- Select City --</option>
            </select>
          </div>
          <div class="form-group col-md-8">
            <label>Address</label>
            <textarea class="form-control" name="address"></textarea>
          </div>
          <div class="form-group col-md-4">
            <label>Pincode</label>
            <input type="text" class="form-control" name="pincode">
          </div>

          <!-- SHIPPING DETAILS -->
          <div class="col-12"><h6><b>Shipping Address</b></h6><hr></div>
          <div class="form-group col-md-4">
            <label>Country</label>
            <select class="form-control" name="shipping_country_id" id="shipping_country_id">
              <option value="">-- Select Country --</option>
              <?php foreach($countries as $c): ?>
                <option value="<?= $c->id ?>"><?= $c->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>State</label>
            <select class="form-control" name="shipping_state_id" id="shipping_state_id">
              <option value="">-- Select State --</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>City</label>
            <select class="form-control" name="shipping_city_id" id="shipping_city_id">
              <option value="">-- Select City --</option>
            </select>
          </div>
          <div class="form-group col-md-8">
            <label>Address</label>
            <textarea class="form-control" name="shipping_address"></textarea>
          </div>
          <div class="form-group col-md-4">
            <label>Pincode</label>
            <input type="text" class="form-control" name="shipping_pincode">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Company</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- EDIT COMPANY MODAL -->
<div class="modal fade" id="edit_brand_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" action="<?= base_url('clients/update') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
      <input type="hidden" name="id" id="edit_company_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Company</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Company Name</label>
            <input type="text" class="form-control" name="company_name" id="edit_company_name" required>
          </div>
          <div class="form-group">
            <label>GSTIN</label>
            <input type="text" class="form-control" name="gstin" id="edit_gstin">
          </div>
          <div class="form-group">
            <label>Billing Address</label>
            <textarea class="form-control" name="billing_details" id="edit_billing_details"></textarea>
          </div>
          <div class="form-group">
            <label>Shipping Address</label>
            <textarea class="form-control" name="shipping_details" id="edit_shipping_details"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update Company</button>
        </div>
      </div>
    </form>
  </div>
</div>


<?php $this->load->view('layout/footer'); ?>

<!-- Required Scripts at the bottom -->

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Init DataTable with export buttons -->
<script>
  $(document).ready(function () {
    $('#example').DataTable({
      dom: 'Bfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      pageLength: 10,
      lengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]
    });
  });
$(document).ready(function () {
  $(document).on('click', '.edit-brand-btn', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
      url: "<?= base_url('clients/get_company') ?>/" + id,
      type: "GET",
      dataType: "json",
      success: function(company) {
        $('#edit_company_id').val(company.id);
        $('#edit_company_name').val(company.company_name);
        $('#edit_gstin').val(company.gstin);
        $('#edit_billing_details').val(company.billing_details);
        $('#edit_shipping_details').val(company.shipping_details);
        $('#edit_brand_modal').modal('show');
      },
      error: function(xhr) {
        alert('Error: ' + xhr.responseText);
      }
    });
  });
});
// On change of country -> load states
$('#country_id').change(function(){
  var country_id = $(this).val();
  $.post("<?= base_url('clients/get_states') ?>", {country_id: country_id}, function(data){
    $('#state_id').html(data);
    $('#city_id').html('<option value="">-- Select City --</option>');
  });
});

$('#state_id').change(function(){
  var state_id = $(this).val();
  $.post("<?= base_url('clients/get_cities') ?>", {state_id: state_id}, function(data){
    $('#city_id').html(data);
  });
});

// Repeat for shipping fields
$('#shipping_country_id').change(function(){
  var country_id = $(this).val();
  $.post("<?= base_url('clients/get_states') ?>", {country_id: country_id}, function(data){
    $('#shipping_state_id').html(data);
    $('#shipping_city_id').html('<option value="">-- Select City --</option>');
  });
});

$('#shipping_state_id').change(function(){
  var state_id = $(this).val();
  $.post("<?= base_url('clients/get_cities') ?>", {state_id: state_id}, function(data){
    $('#shipping_city_id').html(data);
  });
});

</script>
