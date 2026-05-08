<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('auth/users')?>"><?=$this->lang->line('header_users')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('user_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('user_list')?></h3>
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item ml-2">
                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBranchManagerModal">
                      <i class="fas fa-user mr-2"></i> Add Branch Manager
                    </button>
                  </li>
                </ul>
              </div>
              
            </div>
            <div class="card-body">
              <table id="purchaseReportTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Branch Name</th>
                    <th>Branch Code</th>
                    <th><?=$this->lang->line('user_first_name')?></th>
                    <th><?=$this->lang->line('user_username')?></th>
                    <th><?=$this->lang->line('user_email')?></th>
                    <th><?=$this->lang->line('user_phone')?></th>
                    <th><?=$this->lang->line('designation')?></th>
                    <th><?=$this->lang->line('department')?></th>
                    <th><?=$this->lang->line('user_status')?></th>
                    <th><?=$this->lang->line('user_action')?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach ($users as $user) 
                    {
                  ?>
                  <tr>  
                    <td><?php echo $this->db->query("SELECT branch_name FROM clients_branch WHERE id = {$user->clients_branch_id}")->row()->branch_name; ?></td>
                    <td><?php echo $this->db->query("SELECT code FROM clients_branch WHERE id = {$user->clients_branch_id}")->row()->code; ?></td>
                    <td><?php echo $user->first_name;?></td>
                    <td><?php echo $user->username;?></td>
                    <td><?php echo $user->email;?></td>
                    <td><?php echo $user->phone;?></td>
                    <td><?php echo $user->designation;?></td>
                  <td><?php echo $this->db->get_where('departments', ['id' => $user->dept_id])->row()->name ?? ''; ?></td>

                    <td>
                      <?php
                        if(($user->active == 1)){
                      ?>
                          <a href="<?=base_url('purchase_request/deactivate/'.$user->id)?>" class="change_status" data-tt="tooltip" title="Click here to Deactivate User"><span class="badge badge-success">Active</span></a>
                      <?php
                        }
                        else
                        {
                      ?>
                          <a href="<?=base_url('purchase_request/activate/'.$user->id)?>" class="change_status" data-tt="tooltip" title="Click here to Activate User"><span class="badge badge-danger">Inactive</span></a>
                      <?php
                        }
                      ?>
                    
                    </td>
                    <td>
                      <a href="#" class="btn btn-info btn-xs edit-branch-manager" data-id="<?php echo $user->id; ?>">
                        <i class="fas fa-edit"></i>
                      </a>
                    </td>
                    
                  </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Branch Name</th>
                    <th>Branch Code</th>
                    <th><?=$this->lang->line('user_first_name')?></th>
                    <th><?=$this->lang->line('user_username')?></th>
                    <th><?=$this->lang->line('user_email')?></th>
                    <th><?=$this->lang->line('user_phone')?></th>
                    <th><?=$this->lang->line('designation')?></th>
                    <th><?=$this->lang->line('department')?></th>
                    <th><?=$this->lang->line('user_status')?></th>
                    <th><?=$this->lang->line('user_action')?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- Modal: Add Branch Manager -->
<div class="modal fade" id="addBranchManagerModal" tabindex="-1" role="dialog" aria-labelledby="addManagerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="<?= base_url('purchase_request/insert_branch_manager') ?>" method="post" id="branchManagerForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addManagerLabel">Add Branch Manager</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        
        
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Branch Name</label>
              <select name="branch_id" class="form-control" required>
                <option value="">Select Branch</option>
                <?php foreach($clients_branch as $branch): ?>
                  <option value="<?= $branch->id ?>"><?= $branch->branch_name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>First Name</label>
              <input type="text" name="first_name" class="form-control" required>
            </div>
          </div>
        
          <!-- Continue with other fields in rows of two -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Last Name</label>
              <input type="text" name="last_name" class="form-control" required>
            </div>
              <div class="form-group col-md-6">
              <label>Phone</label>
              <input type="text" name="phone" class="form-control">
            </div>
          </div>
        
          <div class="form-row">
          
            <div class="form-group col-md-6">
              <label>Email</label>
              <input type="text" name="email" class="form-control" required>
            </div>
             <div class="form-group col-md-6">
              <label>Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
          </div>
        
           <div class="form-row">
           <div class="form-group col-md-6">
              <label>Department</label>
              <select name="department_id" id="department_id" class="form-control" required>
                  <option value="">Select Department</option>
                  <?php 
                  $departments = $this->db->get('departments')->result(); 
                  foreach ($departments as $dept) {
                      echo '<option value="'.$dept->id.'">'.htmlspecialchars($dept->name).'</option>';
                  }
                  ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label>Designation</label>
              <input type="designation" name="designation" class="form-control" required>
            </div>
          </div>
        
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
          </div>
        
          <input type="hidden" name="role_id" value="26">
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Manager</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
        
      </div>
    </form>
  </div>
</div>


<!-- Modal: Edit Branch Manager -->
<div class="modal fade" id="editBranchManagerModal" tabindex="-1" role="dialog" aria-labelledby="editManagerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="<?= base_url('purchase_request/update_branch_manager') ?>" method="post" id="editBranchManagerForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editManagerLabel">Edit Branch Manager</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        
        <div class="modal-body" id="editBranchManagerModalBody">
          <!-- Content will be loaded via AJAX -->
          <div class="text-center p-4">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Loading manager details...</p>
          </div>
        </div>

        <div class="modal-footer">
          <input type="hidden" name="user_id" id="edit_user_id">
          <button type="submit" class="btn btn-success">Update Manager</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php $this->load->view('layout/footer');?>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    var table = $('#purchaseReportTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [],
        pageLength: 100,
        lengthMenu: [ [10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"] ],
        columnDefs: [{ targets: [0], orderable: false }],
        dom: '<"row mb-2"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"l><"col-md-6"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm me-2 shadow-sm',
                title: 'Branch Manager List'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm me-2 shadow-sm',
                title: 'Branch Manager List',
                exportOptions: { columns: ':visible' },
                customize: function (doc) {
                    doc.content[1].table.widths = 
                        Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm shadow-sm',
                title: 'Branch Manager List'
            }
        ],
        language: {
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });
}); 
</script>
<script>
  $(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timerProgressBar: true,
      timer: 3000
    });

    // <?php if($this->session->flashdata('success')) { ?>
    //     Toast.fire({
    //       type: 'success',
    //       title: '<?=$this->session->flashdata('success')?>'
    //     });
    // <?php } ?>

    // <?php if($this->session->flashdata('message')) { ?>
    //     Toast.fire({
    //       icon: 'error',
    //       title: '<?=$this->session->flashdata('message')?>'
    //     });
    // <?php } ?>

  });

  $(document).on('click','.change_status',function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var message = ''; 

        if($(this).find('span').hasClass('badge-success'))
          message = 'Are you sure want to deactivate the user.';
        else
          message = 'Are you sure want to activate the user.';

        Swal.fire({
          title: "Are you sure?",
          text: message,
          icon: "warning",
          type: "warning",
          buttonsStyling: !1,
          customClass: {
              confirmButton: "btn btn-danger",
              cancelButton: "btn btn-default"
          },
          confirmButtonText: "Yes, do it!",
          closeOnConfirm: false,
          showCancelButton: true
        }).then((result) => {  
            /* Read more about isConfirmed, isDenied below */  
            if (result.value) 
            {    
              $.ajax({
                url: href,
                type: "GET",
                dataType: "JSON",
                success: function(data){
                  if(data.code == 1)
                  {
                    Swal.fire({
                      title: 'SUCCESS !!',
                      text: data.message,
                      icon: "success",
                      buttonsStyling: !1,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                          confirmButton: "btn btn-primary"
                      }
                    });
                    location.reload();
                  }
                  else
                  {
                    Swal.fire({
                      title: 'FAILURE !!',
                      text: data.message,
                      icon: "error",
                      buttonsStyling: !1,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                          confirmButton: "btn btn-primary"
                      }
                    });
                  }
                }
              });
            } 
        });
      });

</script>

<script type="text/javascript">
  $(document).ready(function() {
    // Handle edit button click
    $(document).on('click', '.edit-branch-manager', function(e) {
      e.preventDefault();
      var userId = $(this).data('id');
      $('#edit_user_id').val(userId);
      
      // Show modal
      $('#editBranchManagerModal').modal('show');
      
      // Load form via AJAX
      $.ajax({
        url: '<?= base_url("purchase_request/get_branch_manager_details") ?>',
        type: 'POST',
        data: {user_id: userId},
        success: function(response) {
          $('#editBranchManagerModalBody').html(response);
        },
        error: function(xhr) {
          $('#editBranchManagerModalBody').html(
            '<div class="alert alert-danger">Failed to load manager details. Please try again.</div>'
          );
        }
      });
    });

    // Handle edit form submission
    $('#editBranchManagerForm').submit(function(e) {
      e.preventDefault();
      
      var formData = $(this).serialize();
      
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
          if(response.success) {
            Swal.fire({
              title: 'Success!',
              text: response.message,
              icon: 'success',
              confirmButtonText: 'OK'
            }).then((result) => {
              if (result.value) {
                $('#editBranchManagerModal').modal('hide');
                location.reload(); // Refresh the page to see changes
              }
            });
          } else {
            Swal.fire({
              title: 'Error!',
              text: response.message,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr) {
          Swal.fire({
            title: 'Error!',
            text: 'An error occurred while updating the manager.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    });
  });
</script>
