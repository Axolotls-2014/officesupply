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
                <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('header_reports') ?></a></li>
                <li class="breadcrumb-item active">Tax Invoice Reports</li>
            </ol>
        </div>
    </div>
</section>

 <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="purchaseReportForm" method="POST" action="<?= base_url("client_report/tax_purchase") ?>">
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
                                                <i class="far fa-calendar-alt"></i> Date Range
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
        <i class="fas fa-list"></i> Tax Invoice Report
    </h3>
</div>

<div class="card-body">
<table id="purchaseReportTable" class="table table-bordered table-striped">

<thead>
<tr>
    <th>Sr No</th>
    <th>Date</th>
    <th>Invoice No</th>
    <th>Delivery Via</th>
<th>Docket Number</th>
<th>Delivery Date</th>
<th>Branch Name</th>
<th>Zone</th>
    <th>Party Name</th>
    <th>GSTIN</th>
    <th>Total Amount (<?= $this->session->userdata('currency_symbol') ?>)</th>
    <th>Balance Due (<?= $this->session->userdata('currency_symbol') ?>)</th>
    <th>Due Date</th>
    <th>Payment Status</th>
    <th>Payment Date</th>
    <th>Description</th>
    <!--<th>Delivery Date</th>-->
    <th>Delivery Location</th>
    <th class="no-export">Request Status</th>
    <th class="no-export">Delivery Status</th>
</tr>
</thead>

<tbody>
<?php
$i = 1;
foreach ($purchases as $purchase):

    /* ---------- Party Name ---------- */
    $party_name = '-';
    if ($purchase->level_id == 0) {
        $party_name = $this->db->select('company_name')
            ->where('id', $purchase->company_id)
            ->get('clients_company')
            ->row('company_name');
    } else {
        $branch_company_id = $this->db->select('company_id')
            ->where('id', $purchase->branch_id)
            ->get('clients_branch')
            ->row('company_id');

        if ($branch_company_id) {
            $party_name = $this->db->select('company_name')
                ->where('id', $branch_company_id)
                ->get('clients_company')
                ->row('company_name');
        }
    }

    /* ---------- GSTIN ---------- */
    $customer_gstin = '-';
    if (!empty($purchase->customer_id)) {
        $customer_gstin = $this->db->select('gstin')
            ->where('id', $purchase->customer_id)
            ->get('customer')
            ->row('gstin');
    }

    /* ---------- Balance Due ---------- */
    $received = $this->transaction_model->get_total_transaction_amount(
        $purchase->id,
        SALE_MODULE,
        RECEIPT_TRANSACTION_TYPE
    ) ?? 0;

    $balance_due = ($purchase->total ?? 0) - $received;

    /* ---------- Delivery Date ---------- */
    $delivery_date = '-';
    if (!empty($purchase->courier_date)) {
        $delivery_date = date('d-m-Y', strtotime($purchase->courier_date));
    } elseif (!empty($purchase->physical_date)) {
        $delivery_date = date('d-m-Y', strtotime($purchase->physical_date));
    }

    /* ---------- Delivery Location ---------- */
    $delivery_location = $purchase->vendor_city
        ?? $this->db->select('branch_name')
            ->where('id', $purchase->branch_id)
            ->get('clients_branch')
            ->row('branch_name')
        ?? '-';

    /* ---------- Request Status ---------- */
    $request_status = 'Pending';
    switch ($purchase->order_status) {
        case 'approved': $request_status = 'Approved'; break;
        case 'partially_approved': $request_status = 'Partially Approved'; break;
    }
    
    /* ---------- Customer Name & Mobile (for Description) ---------- */
/* ---------- Customer Name & Phone (for Description) ---------- */
$customer_name = '-';
$customer_phone = '';

if (!empty($purchase->customer_id)) {
    $customer = $this->db->select('customer_name, phone')
        ->where('id', $purchase->customer_id)
        ->get('customer')
        ->row();

    if ($customer) {
        $customer_name  = $customer->customer_name ?? '-';
        $customer_phone = $customer->phone ?? '';
    }
}

/* ---------- New Columns ---------- */
// Delivery Via (already in $purchase object from controller)
$delivery_via = $purchase->delivery_via ?? 'N/A';

// Docket Number (already in $purchase object from controller)
$docket_number = $purchase->docket_number ?? 'N/A';

// Delivery Date (using invoice_date from controller)
$delivery_date_formatted = !empty($purchase->delivery_date) ? 
    date('d-m-Y', strtotime($purchase->delivery_date)) : 
    (!empty($purchase->invoice_date) ? date('d-m-Y', strtotime($purchase->invoice_date)) : '-');

// Branch Name (already in $purchase object from controller)
$branch_name = $purchase->branch_name ?? 'N/A';

// Zone (already in $purchase object from controller)
$zone = $purchase->zone ?? 'N/A';

// Payment Date (already in $purchase object from controller)
$payment_date = !empty($purchase->payment_date) ? 
    date('d-m-Y', strtotime($purchase->payment_date)) : '-';

    /* ---------- Delivery Status ---------- */
    $delivery_status = 'Pending';
    switch ($purchase->status) {
        case 'approved': $delivery_status = 'Approved'; break;
        case 'create_invoice': $delivery_status = 'Invoice Created'; break;
        case 'courier': $delivery_status = 'Courier'; break;
        case 'in_transit': $delivery_status = 'In Transit'; break;
        case 'delivered': $delivery_status = 'Delivered'; break;
        case 'awaiting_confirmation': $delivery_status = 'Awaiting Confirmation'; break;
        case 'delivery_confirmed': $delivery_status = 'Delivery Confirmed'; break;
        case 'rejected': $delivery_status = 'Rejected'; break;
    }
?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= date('d-m-Y', strtotime($purchase->invoice_date)) ?></td>

    <td>
        <a href="<?= base_url('Purchase_request/view_tax_invoice/' . base64_encode($purchase->id)) ?>">
            <?= $purchase->reference_no ?? '-' ?>
        </a>
    </td>

 <td><?= htmlspecialchars($delivery_via) ?></td>
    <td><?= htmlspecialchars($docket_number) ?></td>
     <td><?= $delivery_date ?></td>
    <td><?= htmlspecialchars($branch_name) ?></td>
    <td><?= htmlspecialchars($zone) ?></td>
    <td><?= htmlspecialchars($party_name) ?></td>
    <td><?= $customer_gstin ?: '-' ?></td>
    <td><?= number_format($purchase->total ?? 0, 2) ?></td>
    <td><?= number_format($balance_due, 2) ?></td>
    <td><?= !empty($purchase->due_date) ? date('d-m-Y', strtotime($purchase->due_date)) : '-' ?></td>
    <td><?= $balance_due > 0 ? 'Unpaid' : 'Paid' ?></td>
    <!--<td><?= $payment_date ?></td>-->
 

 
<td>
    <?php
    $payment_display = '-';
    if (!empty($purchase->payment_date)) {
        $payment_timestamp = strtotime($purchase->payment_date);
        // Check if it's a valid date (not 1970-01-01)
        if ($payment_timestamp > 0 && date('Y', $payment_timestamp) > 1970) {
            $payment_display = date('d-m-Y', $payment_timestamp);
        }
    }
    echo $payment_display;
    ?>
</td>
   <td>
    <?= trim(
        $customer_name .
        (!empty($customer_name) && !empty($customer_phone) ? ' - ' : '') .
        $customer_phone
    ) ?>
</td>

   
    <td><?= htmlspecialchars($delivery_location) ?></td>
    <td class="no-export"><?= $request_status ?></td>
    <td class="no-export"><?= $delivery_status ?></td>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3fpH0I4qV1Q0bE1k2ecL8uDkyCh8ZpboFhZl7Y3cKxU=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


<script type="text/javascript">
$('#example').DataTable({
  destroy: true,
  dom: 'Bfrtip',
  buttons: [
      {
        extend: 'excelHtml5',
        title: 'User List',
        text: '<i class="fas fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm pill shadow-sm me-2'
      },
    
      {
        extend: 'csvHtml5',
        title: 'User List',
        text: '<i class="fas fa-file-csv"></i> CSV',
        className: 'btn btn-info btn-sm pill shadow-sm me-2'
      },
      {
        extend: 'print',
        title: 'User List',
        text: '<i class="fas fa-print"></i> Print',
        className: 'btn btn-primary btn-sm pill shadow-sm'
      }
    ],
  pageLength: 100
});


$(document).ready(function() {
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
            //     {
            //         extend: 'excelHtml5',
            //         text: '<i class="fas fa-file-excel"></i> Excel',
            //         className: 'btn-success',
            //         title: 'Tax Invoice Report',
            //         exportOptions: {
            //     columns: ':visible:not(.no-export)'
            // }
            //     },
                {
    extend: 'excelHtml5',
    text: '<i class="fas fa-file-excel"></i> Excel',
    className: 'btn-success',
    title: 'Tax Invoice Report',
    exportOptions: {
        columns: ':visible:not(.no-export)'
    },
    customize: function (xlsx) {

        var sheet = xlsx.xl.worksheets['sheet1.xml'];
        var lastRow = $('row', sheet).length + 1;

        $('sheetData', sheet).append(
            '<row r="'+lastRow+'">'+
                '<c t="inlineStr" r="J'+lastRow+'">'+
                    '<is><t>Total</t></is>'+
                '</c>'+
                '<c r="K'+lastRow+'" t="n">'+
                    '<v><?= $total_amount_sum ?? 0 ?></v>'+
                '</c>'+
            '</row>'
        );
    }
},

                     
                
                 
                // {
                //     extend: 'pdfHtml5',
                //     text: '<i class="fas fa-file-pdf"></i> PDF',
                //     className: 'btn-danger',
                //     title: 'Tax Purchase Report',
                //     exportOptions: {
                //         columns: ':visible'
                //     },
                //     customize: function (doc) {
                //         doc.content[1].table.widths = 
                //             Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                //     }
                // },
                
                 {
            extend: 'pdfHtml5',
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'btn-danger',
            title: 'Tax Invoice Report',
            exportOptions: {
                columns: ':visible:not(.no-export)'  // ADD THIS
            },
            customize: function (doc) {
                // Set page orientation and size
                doc.pageOrientation = 'landscape';
                doc.pageSize = 'A4';
                
                // Calculate column widths based on number of columns
                var colCount = doc.content[1].table.body[0].length;
                var colWidths = [];
                for (var i = 0; i < colCount; i++) {
                    colWidths.push((100/colCount) + '%');
                }
                doc.content[1].table.widths = colWidths;
                
                // Add styles
                doc.styles.tableHeader = {
                    fillColor: '#2c3e50',
                    color: '#ffffff',
                    bold: true,
                    fontSize: 10
                };
                
                doc.styles.tableBodyEven = {
                    fillColor: '#f8f9fa'
                };
                
                doc.styles.tableBodyOdd = {
                    fillColor: '#ffffff'
                };
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



   
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    $('#daterange-btn').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1,'days'), moment().subtract(1,'days')],
            'Last 7 Days': [moment().subtract(6,'days'), moment()],
            'Last 30 Days': [moment().subtract(29,'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')]
        },
        startDate: moment('<?= $from_date ?>', 'DD-MM-Y'),
        endDate: moment('<?= $to_date ?>', 'DD-MM-Y')
    }, function(start, end) {
        $('#from_date').val(start.format('DD-MM-Y'));
        $('#to_date').val(end.format('DD-MM-Y'));
    });

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