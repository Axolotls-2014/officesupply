<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('home') ?></a></li>
            <li class="breadcrumb-item "><a href="<?= base_url('Masters/add') ?>">Add</a></li>
            <li class="breadcrumb-item active">Source</li>
          </ol>
        </div>
      </div>
    </section>


    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Sources</h3>
              
              <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_source_modal" data-toggle="modal" data-target="#add_source_modal" data-tt="tooltip" title="Click here to Add Source">Add Source</button>
              </div>
              
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th width="15%"><?= $this->lang->line("warehouse_action") ?></th>
                  </tr>
                </thead>
               <tbody>
                  <?php if (!empty($sources)): ?>
                    <?php $i = 1; // initialize counter ?>
                    <?php foreach ($sources as $source): ?>
                      <tr> 
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($source->name); ?></td>
                        <td>
                          <button class="btn btn-warning btn-sm edit_source" data-id="<?= $source->id ?>" data-name="<?= $source->name ?>">Edit</button>
                          <button class="btn btn-danger btn-sm delete_source" data-id="<?= $source->id ?>">Delete</button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="3" class="text-center">No data available</td>
                    </tr>
                  <?php endif; ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<!-- Add Source Modal -->
<div class="modal fade" id="add_source_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Source</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
         <form id="addSourceForm"  action="<?= base_url('Masters/add') ?>" method="POST">
             <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
              <div class="modal-body">
                <div class="form-group">
                  <label for="source_name">Source Name</label>
                  <input type="text" class="form-control" id="source_name" name="source_name" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" id="addSourceSubmit" class="btn btn-primary">Add</button>
              </div>
            </form>
    </div>
  </div>
</div>

<!-- Edit Source Modal -->
<div class="modal fade" id="edit_source_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Source</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="editSourceForm" action="<?= base_url('Masters/edit') ?> "  method="POST">
         <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="modal-body">
          <input type="hidden" id="edit_source_id" name="source_id">
          <div class="form-group">
            <label for="edit_source_name">Source Name</label>
            <input type="text" class="form-control" id="edit_source_name" name="source_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="editSourceSubmit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Source Modal -->
<div class="modal fade" id="delete_source_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Source</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this source?</p>
      </div>
      <div class="modal-footer">
          <form id="deleteSourceForm" action="<?= base_url('Masters/delete') ?> "  method="POST">
         <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
     
       <input type="hidden" id="delete_source_id" name="source_id">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function () {

  // Open Add Source Modal
  $(document).on('click', ".add_source_modal", function () {
    $('#add_source_modal').modal('show');
  });

  // Open Edit Source Modal
  $(document).on('click', '.edit_source', function () {
    var sourceId = $(this).data('id');
    var sourceName = $(this).data('name');

    $('#edit_source_id').val(sourceId);
    $('#edit_source_name').val(sourceName);

    $('#edit_source_modal').modal('show');
  });

 
 
  $(document).on('click', '.delete_source', function () {
    var sourceId = $(this).data('id');
    $('#delete_source_id').val(sourceId);

    $('#delete_source_modal').modal('show');
  });



  // Add Source
 $(document).on('click', ".add_source_modal", function () {
      $('#add_source_modal').modal('show');
    });

   
});

</script>
