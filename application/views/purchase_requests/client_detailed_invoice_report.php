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
                <li class="breadcrumb-item active">Detailed Tax Invoice Report</li>
            </ol>
        </div>
    </div>
</section>

 <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="purchaseReportForm" method="POST" action="<?= base_url("client_report/detailed_invoice_report") ?>">
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
<th>Expense Month</th>
<th>User ID</th>
<th>User</th>
<th>Phone Number</th>        <!-- NEW -->
<th>Department</th>          <!-- NEW -->
<th>State</th>
<th>City</th>
 <th>Branch Name</th>         <!-- NEW -->
    <th>Branch Code</th>         <!-- NEW -->
<th>Order Date</th>
<th>Order No</th>
<th>APPROVER NAME</th>       <!-- NEW -->
    <th>APPROVER EMAIL</th>      <!-- NEW -->
    <th>APPROVER CONTACT</th>    <!-- NEW -->
    <th>ORDER APPROVAL DATE</th> <!-- NEW -->
    <th>DELIVERY PARTNER</th>    <!-- NEW -->
    <th>DOCKET NUMBER</th>       <!-- NEW -->
    <th>DELIVERED DATE</th>      <!-- NEW -->
<th>Invoice Date</th>
<th>Party Name</th>
<th>Zone</th>
<th>Item Name</th>
<th>Item Code</th>
<th>HSN/SAC</th>
<th>Category</th>
<th>Qty</th>
<th>Unit</th>
<th>Rate</th>
<th>GST %</th>
<th>CGST</th>
<th>SGST</th>
<th>IGST</th>
<th>Tax Amount</th>
<th>Final Amount</th>
</tr>
</thead>


<tbody>
<?php foreach($rows as $r): ?>
<tr>
<td><?= $r->month ?></td>
<td><?= $r->email ?></td>
<td><?= $r->full_name ?></td>
<td><?= $r->phone_number ?? '-' ?></td>                     <!-- NEW -->
<td><?= $r->department ?? '-' ?></td>                       <!-- NEW -->
<td><?= $r->state ?></td>
<td><?= $r->city ?></td>
<td><?= $r->branch_name ?? '-' ?></td>                      <!-- NEW -->
<td><?= $r->branch_code ?? '-' ?></td>                      <!-- NEW -->
<td><?= date('d-m-Y',strtotime($r->order_date)) ?></td>
<td><?= $r->reference_no ?></td>
<td><?= $r->approver_name ?? '-' ?></td>                    <!-- NEW -->
    <td><?= $r->approver_email ?? '-' ?></td>                   <!-- NEW -->
    <td><?= $r->approver_contact ?? '-' ?></td>                 <!-- NEW -->
     <td>
        <?php if (!empty($r->order_approval_date) && $r->order_approval_date != '0000-00-00'): ?>
            <?= date('d-m-Y', strtotime($r->order_approval_date)) ?>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>                                                       <!-- NEW -->
    <td><?= $r->delivery_partner ?? '-' ?></td>                 <!-- NEW -->
    <td><?= $r->docket_number ?? '-' ?></td>                    <!-- NEW -->
    <td>
        <?php if (!empty($r->delivered_date) && $r->delivered_date != 'Not Delivered'): ?>
            <?= date('d-m-Y', strtotime($r->delivered_date)) ?>
        <?php else: ?>
            <?= $r->delivered_date ?? '-' ?>
        <?php endif; ?>
    </td>                 
<td><?= date('d-m-Y',strtotime($r->invoice_date)) ?></td>
<td><?= $r->party_name ?></td>
<td><?= $r->zone ?? '-' ?></td> 
<td><?= $r->product_name ?></td>
<td><?= $r->product_code ?></td>
<td><?= $r->hsn ?></td>
<td><?= $r->category_name ?></td>
<td><?= $r->quantity ?></td>
<td><?= $r->uom_name ?></td>
<td><?= number_format($r->price,2) ?></td>
<td><?= $r->gst_percent ?>%</td>
<td><?= number_format($r->cgst_tax,2) ?></td>
<td><?= number_format($r->sgst_tax,2) ?></td>
<td><?= number_format($r->igst_tax,2) ?></td>
<td><?= number_format($r->tax_amount,2) ?></td>
<td><?= number_format($r->final_amount,2) ?></td>
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
        order: [[6, 'desc']],
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
                    title: 'Detailed Tax Invoice Report',
                    exportOptions: {
                columns: ':visible:not(.no-export)'
            }
                },
//                 {
//     extend: 'excelHtml5',
//     text: '<i class="fas fa-file-excel"></i> Excel',
//     className: 'btn-success',
//     title: 'Tax Invoice Report',
//     exportOptions: {
//         columns: ':visible:not(.no-export)'
//     },
//     customize: function (xlsx) {

//         var sheet = xlsx.xl.worksheets['sheet1.xml'];
//         var lastRow = $('row', sheet).length + 1;

//         $('sheetData', sheet).append(
//             '<row r="'+lastRow+'">'+
//                 '<c t="inlineStr" r="E'+lastRow+'">'+
//                     '<is><t>Total</t></is>'+
//                 '</c>'+
//                 '<c r="F'+lastRow+'" t="n">'+
//                     '<v><?= $total_amount_sum ?? 0 ?></v>'+
//                 '</c>'+
//             '</row>'
//         );
//     }
// },

                     
                
                 
                // {
                //     extend: 'pdfHtml5',
                //     text: '<i class="fas fa-file-pdf"></i> PDF',
                //     className: 'btn-danger',
                //     title: 'Purchase Report',
                //     exportOptions: {
                //         columns: ':visible'
                //     },
                //     customize: function (doc) {
                //         doc.content[1].table.widths = 
                //             Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                //     }
                // },
                
// {
//     extend: 'pdfHtml5',
//     text: '<i class="fas fa-file-pdf"></i> PDF',
//     className: 'btn-danger',
//     title: 'Tax Invoice Report',
//     orientation: 'landscape',
//     pageSize: 'A3',        // 🔥 THIS IS THE KEY
//     exportOptions: {
//         columns: ':visible'
//     },
//     customize: function (doc) {

//         doc.defaultStyle.fontSize = 7;
//         doc.styles.tableHeader.fontSize = 8;

//         var tableBody = doc.content[1].table.body;
//         var colCount = tableBody[0].length;
//         doc.content[1].table.widths = new Array(colCount).fill('*');

//         doc.pageMargins = [10, 10, 10, 10];
//     }
// },


                
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn-info',
                    title: 'Detailed Tax Invoice Report',
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