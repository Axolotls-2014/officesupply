<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="?=base_url('purchase_request/add_branch')" ?>Branches</a></li>
                <li class="breadcrumb-item active">Branch</li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Branch</h3>
               
                <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_warehouse_modal" data-toggle="modal" data-target="#add_warehouse_modal" data-tt="tooltip" title="Click here to Add Warehouse">Add Branch</button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="purchaseReportTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><?=$this->lang->line("warehouse_name")?></th>
                      <th><?=$this->lang->line("warehouse_code")?></th>
                      <th>Company Name</th>
                      <th><?=$this->lang->line("gstin")?></th>
                      <th><?=$this->lang->line("warehouse_description")?></th>
                      <th>State</th>
                      <th>Billing Address</th>
                      <th>Shipping Address</th>
                      
                      <th width="15%"><?=$this->lang->line("warehouse_action")?></th>   
                    </tr>
                  </thead>
                  <tbody>
                        <?php if (!empty($branches)) : ?>
                            <?php foreach ($branches as $branch) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($branch->branch_name) ?></td>
                                    <td><?= htmlspecialchars($branch->code) ?></td>
                                    <td><?= htmlspecialchars($branch->company) ?></td>
                                    <td><?= htmlspecialchars($branch->gstin) ?></td>
                                    <td><?= htmlspecialchars($branch->description) ?></td>
                                     <td><?= htmlspecialchars($branch->address) ?></td>
                                     <td><?= htmlspecialchars($branch->billing_details) ?></td>
                                    <td><?= htmlspecialchars($branch->shipping_details) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-branch-btn"
                                            data-id="<?= $branch->id ?>"
                                            data-name="<?= htmlspecialchars($branch->branch_name) ?>"
                                            data-code="<?= htmlspecialchars($branch->code) ?>"
                                            data-gstin="<?= htmlspecialchars($branch->gstin) ?>"
                                            data-description="<?= htmlspecialchars($branch->description) ?>"
                                            data-address="<?= htmlspecialchars($branch->address) ?>"
                                            data-billing_address="<?= htmlspecialchars($branch->billing_details) ?>"
                                            data-shipping_address="<?= htmlspecialchars($branch->shipping_details) ?>"
                                            data-company="<?= htmlspecialchars($branch->company) ?>"
                                            data-toggle="modal"
                                            data-target="#editBranchModal">Edit</button>
                                     </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">No branches found.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                 </table>
               </div>
             </div>
           </div>
          <!-- /.col -->
         </div>
      <!-- /.row -->
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>
<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editBranchForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="branchId">
                    <div class="form-group">
                        <label for="branchName">Branch Name</label>
                        <input type="text" class="form-control" id="branchName" name="branch_name" required>
                    </div>
                    <div class="form-group">
                        <label for="branchCode">Branch Code</label>
                        <input type="text" class="form-control" id="branchCode" name="code" required>
                    </div>
                    <div class="form-group">
                        <label for="gstin">GSTIN</label>
                        <input type="text" class="form-control" id="gstin" name="gstin" required>
                    </div>
                      <div class="form-group">
                          <label>Company Name</label>
                          <textarea name="company" id="company" class="form-control" placeholder="Enter Company Name"></textarea>
                        </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                        <?php
                    $states = $this->db->get_where('states', ['country_id' => 101])->result();
                    ?>
                    <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label">
                        <?=$this->lang->line('company_setting_state_id')?>
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-sm-4">
                        <select class="form-control form-control-sm select2bs4 field_validation" value="<?=$company_setting->state_id?>" name="state_id" id="state_id" width="100%" placeholder="<?=$this->lang->line('company_setting_state_id')?>">
                          <?php foreach ($states as $value): ?>
                            <option value="<?=$value->name;?>" <?= ($value->name == $company_setting->state_id) ? 'selected' : '' ?>>
                              <?= $value->name;?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <span id="err_state_id" class="error invalid-feedback"></span>
                      </div>
                    </div>
                     <div class="form-group">
                        <label for="description">Billing Address</label>
                        <textarea class="form-control" id="billing_details" name="billing_details"></textarea>
                    </div>
                     <div class="form-group">
                        <label for="description">Shipping Address</label>
                        <textarea class="form-control" id="shipping_details" name="shipping_details"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="example-modal">
 <div class="modal fade" id="add_warehouse_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title">Add Branch</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="<?= base_url('Purchase_request/insert_branch') ?>" method="post">
          <div class="modal-body">

            <div class="form-group">
              <label>Branch Name <span class="text-danger">*</span></label>
              <input type="text" name="branch_name" class="form-control" required placeholder="Enter Branch Name">
            </div>

            <div class="form-group">
              <label>Branch Code <span class="text-danger">*</span></label>
              <input type="text" name="code" class="form-control" required placeholder="Enter Branch Code">
            </div>
            <div class="form-group">
              <label>GSTIN<span class="text-danger">*</span></label>
              <input type="text" name="gstin" class="form-control" required placeholder="Enter GSTIN Code">
            </div>
            
            <div class="form-group">
              <label>Description</label>
              <textarea name="description" class="form-control" placeholder="Enter Description"></textarea>
            </div>

            <div class="form-group">
              <label>Company Name</label>
              <textarea name="company" class="form-control" placeholder="Enter Company Name"></textarea>
            </div>
           <?php
                    $states = $this->db->get_where('states', ['country_id' => 101])->result();
                    ?>
                    <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label">
                        <?=$this->lang->line('company_setting_state_id')?>
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-sm-4">
                   <select class="form-control form-control-sm select2bs4 field_validation"
                        name="state_id"
                        id="state_name"
                        width="100%"
                        placeholder="<?= $this->lang->line('company_setting_state_id') ?>">
                  <?php foreach ($states as $value): ?>
                    <option value="<?= $value->name; ?>"
                            data-id="<?= $value->id; ?>"
                            <?= ($value->name == $company_setting->state_id) ? 'selected' : '' ?>>
                      <?= $value->name; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                
                <input type="hidden" name="state_name" id="state_id_hidden"
                       value="<?php
                          $selected_state = array_filter($states, fn($s) => $s->name == $company_setting->state_id);
                          echo !empty($selected_state) ? reset($selected_state)->id : '';
                       ?>">
                
                <span id="err_state_id" class="error invalid-feedback"></span>

                      </div>
                    </div>
            <div class="form-group">
                <label for="billing_add">Billing Address<span class="text-danger">*</span></label>
                <textarea class="form-control" id="billing_details" name="billing_details" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="shipping_add">Shipping Address<span class="text-danger">*</span></label>
                <textarea class="form-control" id="shipping_details" name="shipping_details" required></textarea>
            </div>

          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Branch</button>
          </div>
        </form>

      </div>
    </div>
 </div>
</div>
<?php $this->load->view('layout/footer'); ?>

<!-- DataTables & export libraries -->
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
      pageLength: 100,  // Default 100 entries
      lengthMenu: [ [10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"] ],
      columnDefs: [{ targets: [0], orderable: false }],
      dom: '<"row mb-2"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"l><"col-md-6"p>>',
      buttons: [
          {
              extend: 'excelHtml5',
              text: '<i class="fas fa-file-excel"></i> Excel',
              className: 'btn btn-success btn-sm me-2 shadow-sm',
              title: 'Branch List'
          },
          {
              extend: 'pdfHtml5',
              text: '<i class="fas fa-file-pdf"></i> PDF',
              className: 'btn btn-danger btn-sm me-2 shadow-sm',
              title: 'Branch List',
              exportOptions: { columns: ':visible' },
              customize: function (doc) {
                  doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
              }
          },
          {
              extend: 'print',
              text: '<i class="fas fa-print"></i> Print',
              className: 'btn btn-info btn-sm shadow-sm',
              title: 'Branch List'
          }
      ],
      language: {
          paginate: {
              previous: '<i class="fas fa-chevron-left"></i>',
              next: '<i class="fas fa-chevron-right"></i>'
          }
      }
  });

  $('.select2bs4').select2({ theme: 'bootstrap4' });

  $('[data-tt="tooltip"]').tooltip({ trigger: 'hover' });

  $('#state_name').on('change select2:select', function() {
      var selectedOption = $(this).find(':selected');
      var stateId = selectedOption.data('id') || '';
      $('#state_id_hidden').val(stateId);
  }).trigger('change');

  $(document).on('click', '.edit-branch-btn', function () {
      $('#branchId').val($(this).data('id'));
      $('#branchName').val($(this).data('name'));
      $('#branchCode').val($(this).data('code'));
      $('#gstin').val($(this).data('gstin'));
      $('#description').val($(this).data('description'));
      $('#state_id').val($(this).data('address')).trigger('change');
      $('#billing_details').val($(this).data('billing_address'));
      $('#shipping_details').val($(this).data('shipping_address'));
      $('#company').val($(this).data('company'));
  });

  $('#editBranchForm').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
          url: '<?= base_url('purchase_request/update') ?>',
          type: 'POST',
          data: $(this).serialize(),
          dataType: 'json',
          success: function(response) {
              if (response.success) {
                  alert(response.message || 'Branch updated successfully!');
                  location.reload();
              } else {
                  alert(response.message || 'Error updating branch.');
                  console.error(response.error);
              }
          },
          error: function(xhr, status, error) {
              alert('AJAX Error: ' + error);
              console.error(xhr.responseText);
          }
      });
  });
});
</script>
