<?php $this->load->view('layout/header'); ?>

<style>
    .footer_data { font-size: 20px; }
    .dt-buttons .btn { margin-right: 5px; margin-bottom: 5px; }
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dataTables_length { float: left; }
    .dataTables_wrapper .dataTables_info { float: left; }
    .dataTables_wrapper .dataTables_paginate { float: right; }
    .dt-buttons { float: left; margin-bottom: 10px; }
</style>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb breadcrumb-custom float-sm-left">
                        <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                        <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_reports')?></a></li>
                        <li class="breadcrumb-item active"><?=$this->lang->line('purchase_report')?></li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="purchaseReportForm" method="POST" action="<?= base_url("client_report/purchase") ?>">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-funnel-dollar"></i>
                                    <?=$this->lang->line('filter_report')?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label><?=$this->lang->line('select_date_range')?></label>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-block btn-outline-secondary float-right" id="daterange-btn">
                                                <i class="far fa-calendar-alt"></i> 
                                                <span>Select Date Range</span>
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label><?=$this->lang->line('from_date')?></label>
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="<?= $from_date ?>" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label><?=$this->lang->line('to_date')?></label>
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="<?= $to_date ?>" readonly>
                                    </div>

                                    <?php if ($user_role != 'approval_2') { ?>
                                    <div class="col-sm-3">
                                        <label><?=$this->lang->line('warehouse')?></label>
                                        <select class="form-control form-control-sm select2bs4" name="branch_id" id="branch_id">
                                            <option value=""><?=$this->lang->line('select')?></option>
                                            <?php foreach ($branches as $branch) { ?>
                                                <option value="<?=$branch->id;?>" <?= set_select('branch_id', $branch->id); ?>>
                                                    <?= $branch->branch_name;?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Sub Branch</label>
                                        <select class="form-control form-control-sm select2bs4" name="sub_branch_id" id="sub_branch_id" <?= empty($this->input->post('branch_id')) ? 'disabled' : '' ?>>
                                            <option value=""><?= empty($this->input->post('branch_id')) ? 'select branch first' : 'select sub branch' ?></option>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <button type="submit" class="btn btn-info"><?=$this->lang->line('search')?></button>
                            </div>
                        </div>
                    </form>
                    
          <?php $role = $this->session->userdata('level_id'); ?>
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i>
                                <?=$this->lang->line('purchase_report')?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table id="purchaseReportTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th><?=$this->lang->line('purchase_invoice_no')?></th>
                                        <th><?=$this->lang->line('purchase_date')?></th>
                                        <th>Taxable (<?=$this->session->userdata('currency_symbol')?>)</th>
                                        <th>GST (<?=$this->session->userdata('currency_symbol')?>)</th>
                                        <th><?=$this->lang->line('purchase_total')?></th>
                                        <?php if ($role == 0) echo '<th>Added From</th>'; ?>
                                        <th>Request Status</th>
                                        <th><?=$this->lang->line('delivery_status')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1; foreach ($purchases as $purchase): ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><a href="<?= base_url('Purchase_request/view/' . base64_encode($purchase->id)) ?>" class="text-primary fw-bold"><?= $purchase->reference_no ?? '-' ?></a></td>
                                            <td><?= date('d-m-Y', strtotime($purchase->invoice_date)) ?></td>
                                            <td><?= number_format($purchase->total_taxable_value ?? 0, 2) ?></td>
                                            <td><?= number_format($purchase->gst ?? 0, 2) ?></td>
                                            <td><?= number_format($purchase->total ?? 0, 2) ?></td>
                                            <?php if ($role == 0): ?>
                                                <td>
                                                    <?= $purchase->level_id != 0 ? 'Branch (' . $this->db->select('branch_name')->where('id', $purchase->branch_id)->get('clients_branch')->row('branch_name') . ')' : 'Head Office' ?>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php 
                                                switch($purchase->order_status){
                                                    case 'pending': echo '<span class="badge badge-warning">Pending</span>'; break;
                                                    case 'approved': echo '<span class="badge badge-success">Approved</span>'; break;
                                                    case 'partially_approved': echo '<span class="badge badge-info">Partially Approved</span>'; break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                switch($purchase->status){
                                                    case NULL:
                                                    case 'pending': echo '<span class="badge badge-warning">Pending</span>'; break;
                                                    case 'approved': echo '<span class="badge badge-primary">Approved</span>'; break;
                                                    case 'shipping': echo '<span class="badge badge-info">Shipping</span>'; break;
                                                    case 'dispatch': echo '<span class="badge badge-dark">Dispatch</span>'; break;
                                                    case 'out_for_delivery': echo '<span class="badge badge-secondary">Out for Delivery</span>'; break;
                                                    case 'delivered': echo '<span class="badge badge-success">Delivered</span>'; break;
                                                    default: echo '<span class="badge badge-light">Unknown</span>'; break;
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
        </section>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<!-- Additional CSS for daterangepicker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3fpH0I4qV1Q0bE1k2ecL8uDkyCh8ZpboFhZl7Y3cKxU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize Select2
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    // ========== DATERANGEPICKER INITIALIZATION ==========
    // Get date values from PHP with proper fallbacks
    var fromDateStr = "<?= !empty($from_date) ? $from_date : '' ?>";
    var toDateStr = "<?= !empty($to_date) ? $to_date : '' ?>";
    
    // Parse dates safely
    var momentStart = moment();
    var momentEnd = moment();
    
    if (fromDateStr && moment(fromDateStr, 'DD-MM-YYYY').isValid()) {
        momentStart = moment(fromDateStr, 'DD-MM-YYYY');
    }
    if (toDateStr && moment(toDateStr, 'DD-MM-YYYY').isValid()) {
        momentEnd = moment(toDateStr, 'DD-MM-YYYY');
    }
    
    // Initialize daterangepicker
    $('#daterange-btn').daterangepicker({
        autoUpdateInput: false,
        startDate: momentStart,
        endDate: momentEnd,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD-MM-YYYY',
            applyLabel: 'Apply',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            weekLabel: 'W',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    }, function(start, end, label) {
        // Update the input fields when a range is selected
        $('#from_date').val(start.format('DD-MM-YYYY'));
        $('#to_date').val(end.format('DD-MM-YYYY'));
        // Update button text
        $('#daterange-btn span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
    });
    
    // Set initial values in the inputs and button text
    $('#from_date').val(momentStart.format('DD-MM-YYYY'));
    $('#to_date').val(momentEnd.format('DD-MM-YYYY'));
    $('#daterange-btn span').html(momentStart.format('DD-MM-YYYY') + ' - ' + momentEnd.format('DD-MM-YYYY'));
    // ========== END DATERANGEPICKER INITIALIZATION ==========

    // Initialize DataTable
    var table = $('#purchaseReportTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [],
        columnDefs: [{ 
            targets: [0], 
            orderable: false 
        }],
        dom: '<"row"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"l><"col-md-6"p>>',
        buttons: {
            dom: {
                container: {
                    tag: 'div',
                    className: 'dt-buttons btn-group flex-wrap'
                },
                button: {
                    tag: 'button',
                    className: 'btn btn-sm'
                }
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn-success',
                    title: 'Purchase Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn-danger',
                    title: 'Purchase Report',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function (doc) {
                        doc.content[1].table.widths = 
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn-info',
                    title: 'Purchase Report',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', '10pt');
                        $(win.document.body).find('h1')
                            .css('text-align','center')
                            .css('font-size', '14pt');
                    }
                }
            ]
        },
        language: {
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });

    // Branch change handler - Load sub branches
    $('#branch_id').change(function() {
        var branch_id = $(this).val();
        var sub_branch_dropdown = $('#sub_branch_id');
        if(branch_id){
            sub_branch_dropdown.prop('disabled', false).html('<option>Loading...</option>');
            $.post('<?= base_url("client_report/get_sub_branches") ?>', 
                { branch_id: branch_id, <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>' }, 
                function(data){
                    sub_branch_dropdown.empty();
                    if(data.length > 0){
                        sub_branch_dropdown.append('<option value="">Select Sub Branch</option>');
                        $.each(data, function(k,v){
                            sub_branch_dropdown.append('<option value="'+v.id+'">'+v.branch_name+'</option>');
                        });
                    }else{
                        sub_branch_dropdown.append('<option value="">No Sub Branches</option>');
                    }
                    sub_branch_dropdown.select2({ theme:'bootstrap4' });
                }, 'json');
        }else{
            sub_branch_dropdown.prop('disabled', true).html('<option>Select branch first</option>');
        }
    });
});
</script>