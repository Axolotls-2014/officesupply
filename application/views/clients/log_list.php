<?php $this->load->view('layout/header'); ?>

<!-- DataTables CSS (in header or before table) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Logs List</li>
      </ol>
    </section>

    <section class="content mt-3">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Logs List</h3>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="example" class="table table-bordered table-striped">
             <thead>
                    <tr>
                      <th>User Name</th>
                      <th width="15%">Module</th>   
                      <th width="15%">description</th> 
                      <th>Date</th>
                    </tr>
                  </thead>

                   <tbody>
  <?php if (!empty($brands)) {
    foreach ($brands as $brand) { ?>
      <tr>
      <td><?= $this->db->select('first_name, last_name')->get_where('users', ['id' => $brand->user_id])->row('first_name') . ' ' . $this->db->select('first_name, last_name')->get_where('users', ['id' => $brand->user_id])->row('last_name') ?></td>
        <td>
        <?= htmlspecialchars($brand->module) ?>
        </td>
        <td><?= htmlspecialchars($brand->description) ?></td>
         <td><?= htmlspecialchars($brand->date_created) ?></td>
      </tr>
  <?php }
  } else { ?>
      <tr>
        <td colspan="2" class="text-center">No logs found.</td>
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


<?php $this->load->view('layout/footer'); ?>

<!-- Required Scripts at the bottom -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
  $('.edit-brand-btn').click(function () {
    const id = $(this).data('id');
    const name = $(this).data('name');
    $('#edit_brand_id').val(id);
    $('#edit_brand_name').val(name);
  });
});

</script>
