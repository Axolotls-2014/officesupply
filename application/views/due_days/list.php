<?php $this->load->view('layout/header');?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb breadcrumb-custom float-sm-left">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Due Days</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Due Days</h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <?php if($this->permission_model->has_permission('import_due_days')): ?>
                                    <li class="nav-item ml-2">
                                        <a class="nav-link import_due_day_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Import Due Days in Bulk using CSV">
                                            <i class="fas fa-file-import"></i> Import
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if($this->permission_model->has_permission('add_due_days')): ?>
                                    <li class="nav-item ml-2">
                                        <a class="nav-link add_due_day_modal btn-sm btn-primary text-white" href="#" data-toggle="modal" data-target="#add_due_day_modal" data-tt="tooltip" title="Add New Due Day">
                                            <i class="fas fa-plus"></i> Add
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="due_days_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Due Day</th>
                                        <th>Terms and Condition</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>

<!-- Modals -->
<div class="modal fade" id="add_due_day_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="edit_due_day_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Content will be loaded here via AJAX -->
        </div>
    </div>
</div>


<div class="modal fade" id="delete_due_day_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="import_due_day_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#due_days_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo site_url('due_days/ajax_list')?>",
            "type": "POST",
            "data": {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },
        "columns": [
            { "data": "0" },
            { "data": "1" },
            { "data": "2" },
            { "data": "3", "orderable": false }
        ],
        "responsive": true,
        "autoWidth": false,
    });

    // Toast notification setup
    const dueDayToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    // Add Due Day Modal
    $(document).on('click', '.add_due_day_modal', function() {
        $.ajax({
            url: "<?php echo base_url('due_days/add')?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#add_due_day_modal .modal-content').html(data.add_due_day_modal_body);
                $('#add_due_day_modal').modal('show');
            }
        });
    });

$(document).on('click', '.edit_due_day_modal', function() {
    var due_day_id = $(this).data('due_day_id');
    
    $.ajax({
        url: "<?php echo base_url('due_days/edit')?>",
        type: "GET",
        data: { due_day_id: due_day_id },
        success: function(html) {
            $('#edit_due_day_modal .modal-content').html(html);
            $('#edit_due_day_modal').modal('show');
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire('Error', 'Failed to load edit form', 'error');
        }
    });
});





    // Delete Confirmation Modal
    $(document).on('click', '.delete_due_day_modal', function() {
        var due_day_id = $(this).data('due_day_id');
        $.ajax({
            url: "<?php echo base_url('due_days/due_day_delete_confirmation')?>",
            type: "POST",
            data: {
                'due_day_id': due_day_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: "JSON",
            success: function(data) {
                $('#delete_due_day_modal .modal-content').html(data.delete_due_day_modal_body);
                $('#delete_due_day_modal').modal('show');
            }
        });
    });

    // Import Modal
    $(document).on('click', '.import_due_day_modal', function() {
        $.ajax({
            url: "<?php echo base_url('due_days/import_due_days')?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#import_due_day_modal .modal-content').html(data.import_due_day_modal_body);
                $('#import_due_day_modal').modal('show');
            }
        });
    });

    // Form submissions
    $(document).on('submit', '#addduedayForm', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $('#addduedaySubmit').prop('disabled', true).html('Please wait...');
        
        $.ajax({
            url: "<?php echo base_url('due_days/add')?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(response) {
                if(response.code == 1) {
                    $('#add_due_day_modal').modal('hide');
                    table.ajax.reload(null, false);
                    dueDayToast.fire({
                        icon: 'success',
                        title: response.message
                    });
                } else {
                    dueDayToast.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
                $('#addduedaySubmit').prop('disabled', false).html('Submit');
            }
        });
    });

    $(document).on('submit', '#editduedayForm', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $('#editduedaySubmit').prop('disabled', true).html('Please wait...');
        
        $.ajax({
            url: "<?php echo base_url('due_days/edit')?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(response) {
                if(response.code == 1) {
                    $('#edit_due_day_modal').modal('hide');
                    table.ajax.reload(null, false);
                    dueDayToast.fire({
                        icon: 'success',
                        title: response.message
                    });
                } else {
                    dueDayToast.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
                $('#editduedaySubmit').prop('disabled', false).html('Submit');
            }
        });
    });

    $(document).on('submit', '#deleteduedayForm', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $('#deleteduedaySubmit').prop('disabled', true).html('Please wait...');
        
        $.ajax({
            url: "<?php echo base_url('due_days/delete')?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(response) {
                if(response.code == 1) {
                    $('#delete_due_day_modal').modal('hide');
                    table.ajax.reload(null, false);
                    dueDayToast.fire({
                        icon: 'success',
                        title: response.message
                    });
                } else {
                    dueDayToast.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
                $('#deleteduedaySubmit').prop('disabled', false).html('Submit');
            }
        });
    });

    // Form validation
    $(document).on('blur', '.field_validation', function() {
        var id = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value == null || value == "") {
            $("#err_" + id).text(field + " is required.");
            $(this).addClass('is-invalid');
        } else {
            $("#err_" + id).text("");
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
</script>