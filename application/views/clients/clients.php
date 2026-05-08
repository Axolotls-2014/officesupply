<?php $this->load->view('layout/header'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Managers List</li>
      </ol>
    </section>
    <section class="content mt-3">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Managers List</h3>
            <div class="card-tools">
            <a href="#" class="nav-link btn-primary btn-sm add_product_category_modal text-white" data-toggle="modal" data-target="#add_brand_modal" data-tt="tooltip" title="Click here to Add Product Brand"><i class="fas fa-user-friends mr-2"></i>Add Manager</a>
            </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
          <table id="example" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Sr. No.</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Phone</th>
                  <th>Company Name</th>
                  <th>Active</th>
                  <th width="15%"><?=$this->lang->line("product_category_action")?></th>   
                </tr>
              </thead>
          <tbody>
              <?php if (!empty($employees)) {
                $i = 1;
                foreach ($employees as $brand) { ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($brand->first_name . $brand->last_name) ?></td>
                    <td><?= htmlspecialchars($brand->email) ?></td>
                    <td><?= htmlspecialchars($brand->username) ?></td>
                    <td><?= htmlspecialchars($brand->designation) ?></td>
                    <td><?php echo $this->db->get_where('departments', ['id' => $brand->dept_id])->row()->name ?? ''; ?></td>
                    <td><?= htmlspecialchars($brand->phone) ?></td>
                    <td><?= htmlspecialchars($brand->company_name) ?></td>
                    <td>
                      <span class="badge toggle-status <?= $brand->active == 1 ? 'badge-success' : 'badge-danger' ?>" 
                            data-id="<?= $brand->id ?>" 
                            data-status="<?= $brand->active ?>" 
                            style="cursor: pointer;">
                        <?= $brand->active == 1 ? 'Active' : 'Inactive' ?>
                      </span>
                    </td>
                    <td>
                      <a href="#" 
                        class="btn btn-sm btn-warning edit-brand-btn" 
                        data-id="<?= $brand->id ?>" 
                        data-first_name="<?= htmlspecialchars($brand->first_name) ?>" 
                        data-last_name="<?= htmlspecialchars($brand->last_name) ?>" 
                        data-email="<?= htmlspecialchars($brand->email) ?>" 
                        data-username="<?= htmlspecialchars($brand->username) ?>" 
                        data-phone="<?= htmlspecialchars($brand->phone) ?>" 
                        data-company="<?= htmlspecialchars($brand->company_id) ?>"
                        data-status="<?= htmlspecialchars($brand->status) ?>"
                        data-toggle="modal" 
                        data-target="#edit_brand_modal">
                        Edit
                      </a>
                    </td>
                  </tr>
              <?php }
              } ?>
            </tbody>

            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- ADD MANAGER MODAL -->
<div class="modal fade" id="add_brand_modal" tabindex="-1" role="dialog" aria-labelledby="addManagerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('clients/insert_user') ?>" method="post">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
      <input type="hidden" name="add_user" value="1">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Manager</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
         
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="identity" class="form-control" required>
          </div>
          <div class="form-group">
              <label>Designation</label>
              <select name="designation" class="form-control" required>
                <option value="">Select Designation</option>
                <option value="Senior Manager">Senior Manager</option>
                <option value="HOD">HOD</option>
                <option value="Admin">Admin</option>
              </select>
            </div>
            
          <div class="form-group">
              <label>Department</label>
              <select name="department" class="form-control" required>
                <option value="">Select Department</option>
                <?php 
                  $departments = $this->db->get('departments')->result();
                  foreach ($departments as $dept) { 
                    $selected = ($dept->id == $user->dept_id) ? 'selected' : '';
                    echo "<option value='{$dept->id}' $selected>{$dept->name}</option>";
                  }
                ?>
              </select>
            </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
          </div>
           <div class="form-group">
            <label>Company</label>
            <select class="form-control select2" name="company_id" id="add_company_id_select">
              <?php foreach ($this->db->get('clients_company')->result() as $c): ?>
                <option value="<?= $c->id ?>"><?= $c->company_name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add User</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- EDIT MANAGER MODAL -->
<div class="modal fade" id="edit_brand_modal" tabindex="-1" role="dialog" aria-labelledby="editManagerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="<?= base_url('clients/update_user') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
      <input type="hidden" name="id" id="edit_user_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Manager</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
        
          <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" id="edit_email" required>
          </div>
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" id="edit_username" required>
          </div>
          <div class="form-group">
          <label>New Password <small class="text-muted">(Leave blank to keep existing)</small></label>
          <input type="password" class="form-control" name="password" id="edit_password">
        </div>

          <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" name="phone" id="edit_phone">
          </div>
           <div class="form-group">
              <label>Company</label>
              <select class="form-control" name="company_id" id="edit_company_id_select">
    <?php foreach ($this->db->get('clients_company')->result() as $c): ?>
      <option value="<?= $c->id ?>"><?= $c->company_name ?></option>
    <?php endforeach; ?>
</select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
$('#example').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  pageLength: 20,
  lengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]],
  language: {
    emptyTable: "No managers available. Please add one."
  }
});


  $('#add_company_id_select').select2({
    theme: 'bootstrap4',
    dropdownParent: $('#add_brand_modal')
  });

$(document).on('click', '.edit-brand-btn', function () {
    $('#edit_user_id').val($(this).data('id'));
    $('#edit_first_name').val($(this).data('first_name'));
    $('#edit_last_name').val($(this).data('last_name'));
    $('#edit_email').val($(this).data('email'));
    $('#edit_username').val($(this).data('username'));
    $('#edit_phone').val($(this).data('phone'));

    let companyId = $(this).data('company');

    // show company in disabled dropdown
 $('#edit_company_id_select').val(companyId);
});


 $('#edit_brand_modal').on('hidden.bs.modal', function () {
    $('#edit_company_id_select').val(null);
});
  
});
  $(document).on('click', '.toggle-status', function () {
    const badge = $(this);
    const userId = badge.data('id');
    const currentStatus = badge.data('status');
    const newStatus = currentStatus == 1 ? 0 : 1;

    $.ajax({
      url: '<?= base_url("clients/toggle_status") ?>',
      method: 'POST',
      data: {
        id: userId,
        status: newStatus,
        '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
      },
      success: function (response) {
        if (newStatus == 1) {
          badge.removeClass('badge-danger').addClass('badge-success').text('Active');
        } else {
          badge.removeClass('badge-success').addClass('badge-danger').text('Inactive');
        }
        badge.data('status', newStatus);
      },
      error: function () {
        alert('Failed to update status. Please try again.');
      }
    });
  });

</script>



