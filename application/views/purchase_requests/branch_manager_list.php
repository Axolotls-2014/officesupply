<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
               <li class="breadcrumb-item active"><?=$this->lang->line('purchase_list')?></li>
          </ol>
        </div>
      </div>
    </section>
    <!-- Main content -->
    <section class="content">
   
  <?php
    $user_id = $this->session->userdata('user_id');
    $role    = $this->db->select('role')->where('id', $user_id)->get('users')->row('role');
    if ($role == 'client') {
        $sub_users = $this->db->select('id')->where('added_by', $user_id)->get('users')->result_array();
        $user_ids = array_column($sub_users, 'id');
        $user_ids[] = $user_id; 
    
        $total_purchase = $this->db
            ->select_sum('total')
            ->where_in('added_by', $user_ids)
            ->get('sale_requests')
            ->row()
            ->total;
    
        $total_delivered = $this->db
            ->where_in('added_by', $user_ids)
            ->where('order_status', 'approved')
            ->count_all_results('sale_requests');
            
             $total_del = $this->db
            ->where_in('added_by', $user_ids)
            ->where('status', 'delivered')
            ->count_all_results('sale_requests');
            
                 $total_rejected = $this->db
            ->where_in('added_by', $user_ids)
            ->where('order_status', 'rejected')
            ->count_all_results('sale_requests');
    
        $today_purchases = $this->db
            ->select_sum('total')
            ->where_in('added_by', $user_ids)
            ->like('created_date', date('Y-m-d'))
            ->get('sale_requests')
            ->row()
            ->total;
    } else {
        $total_purchase = $this->db
            ->select_sum('total')
            ->where('added_by', $user_id)
            ->get('sale_requests')
            ->row()
            ->total;
    
        $total_delivered = $this->db
            ->where('added_by', $user_id)
            ->where('status', 'approved')
            ->count_all_results('sale_requests');
    
        $today_purchases = $this->db
            ->select_sum('total')
            ->where('added_by', $user_id)
            ->like('created_date', date('Y-m-d'))
            ->get('sale_requests')
            ->row()
            ->total;
    }
    ?>
  <div class="row">
      <div class="col-12 col-sm-12 col-md-3">
       <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
         <div class="info-box-content">
                  <span class="info-box-text">Approved Purchase Orders</span>
                  <span class="info-box-number" style="font-size: 19px">
                    <?= $total_delivered ?>
                  </span>
           </div>
      </div>
    </div>
    
     <div class="col-12 col-sm-12 col-md-3">
       <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
            <div class="info-box-content">
                  <span class="info-box-text">Unpproved Purchase Orders</span>
                  <span class="info-box-number" style="font-size: 19px">
                    <?= $total_rejected ?>
                  </span>
             </div>
       </div>
    </div>
    
    <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-store"></i></span>
         <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('total_purchases')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <!-- <small><?=$this->session->userdata('currency_symbol')?></small> -->
                <?=$this->session->userdata('currency_symbol')?>
                <?=($total_purchase == 0) ? '0.000' : number_format_i($total_purchase)?>
              </span>
            </div>
      </div>
    </div>
    
    <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-piggy-bank"></i></span>
             <div class="info-box-content">
              <span class="info-box-text">Today's Purchases</span>
              <span class="info-box-number" style="font-size: 19px">
                <?= $this->session->userdata('currency_symbol') ?>
                <?= ($today_purchases == 0) ? '0.000' : number_format_i($today_purchases) ?>
              </span>
            </div>
      </div>
    </div>
    
    
    <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Payable Amount</span>
          <span class="info-box-number" style="font-size: 19px">
            <?=$this->session->userdata('currency_symbol')?>
            <!--<?=($total_amount_received == '') ? '0' : number_format_i($total_amount_received)?>-->
          </span>
        </div>
      </div>
    </div>
    
     <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Delivered</span>
          <span class="info-box-number" style="font-size: 19px">
            <?=$this->session->userdata('currency_symbol')?>
            <?=($total_del == '') ? '0' : number_format_i($total_del)?>
          </span>
        </div>
      </div>
    </div>
  </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <?=$this->lang->line('purchase_list')?> 
              ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                    
                <li class="nav-item ml-2">
                    <div class="input-group input-group-sm mt-1 mr-2" style="width: 300px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">From</span>
                        </div>
                        <input type="date" class="form-control" id="from-date" placeholder="Start date">
                        <div class="input-group-prepend">
                            <span class="input-group-text">To</span>
                        </div>
                        <input type="date" class="form-control" id="to-date" placeholder="End date">
                        <div class="input-group-append">
                            <button class="btn btn-default" id="clear-date-filter">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </li>                     
                  <li class="nav-item ml-8">
                    <select class="form-control form-control-sm select2bs4 " name="sales_payment" id="sales_payment" width="100%" class="add-row" placeholder="" >
                      <option value="">Show All PO</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Not Approved</option>
                        <option value="shipping">Shipping</option>
                        <option value="out_for_delivery">Out For Delivery</option>
                        <option value="delivered">Delivered</option>                        

                    </select>
                  </li>

                   <li class="nav-item ml-2">
                    <a class="nav-link active" href="<?=base_url('Purchase_request/add')?>" data-tt="tooltip" title="Click here to Add Purchase"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('purchase_add')?></a>
                  </li>
                </ul>
              </div>
            </div>
            
            <div class="card-body">
              <table id="purchaseReportTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Sr.No</th>
                    <th><?=$this->lang->line('purchase_order_request_no')?></th>
                    <th><?=$this->lang->line('purchase_date')?></th>
                    <th><?=$this->lang->line('purchase_total')?></th>
                    <th>Added From</th>
                    <th>Person</th>
                    <th><?=$this->lang->line('delivery_status')?></th>
                     <th>action</th>
                  </tr>
                </thead>
                 <tbody>
                      <?php $i=1; foreach ($purchases as $purchase):  ?>
                        <tr>
                         <td><?php echo $i++;?></td>
                        <td>
                        <a href="<?= base_url('Purchase_request/view/' . base64_encode($purchase->id)) ?>" class="text-primary fw-bold">
                                <?= $purchase->reference_no ?? '-' ?>
                            </a>
                        </td>
                          <td><?= date('d-m-Y', strtotime($purchase->invoice_date)) ?></td>
                          <td><?= number_format($purchase->total ?? 0, 2) ?></td>
                          
                            <td>
                            <?php if ($purchase->added_by_branch == $role): ?>
                            <span class="text-primary">Self</span>
                                
                           <?php else: ?>
                                <?php
                                    $branch_label = '';
                                    if ($purchase->added_by_branch == 'clients_branch_manager') {
                                        $branch_label = 'Main Branch';
                                    } elseif ($purchase->added_by_branch == 'approval_2') {
                                        $branch_label = 'Sub Branch';
                                    } else {
                                        $branch_label = 'Branch';
                                    }
                            
                                    $branch_name = $this->db
                                        ->select('branch_name')
                                        ->where('id', $purchase->branch_id)
                                        ->get('clients_branch')
                                        ->row('branch_name');
                                ?>
                                <span class="text-success"><?= $branch_label ?> (<?= $branch_name ?>)</span>
                            <?php endif; ?>
                        </td>
                                                <td>
                             <?php if ($purchase->added_by_branch == $role): ?>
                            <span class="text-primary">Self</span>
                                
                           <?php else: ?>
                            <?php
                                $added_by_user = $this->db->get_where('users', ['id' => $purchase->added_by])->row();
                                echo htmlspecialchars($added_by_user->first_name . ' ' . $added_by_user->last_name);
                            ?>
                            <?php endif; ?>
                        </td>
                      <td>
                          <?php
                            switch ($purchase->status) {
                                case NULL:
                                    echo '<span class="badge badge-warning text-uppercase">Pending</span>';
                                    break;
                                     case 'pending':
                                    echo '<span class="badge badge-warning text-uppercase">Pending</span>';
                                    break;
                                case 'approved':
                                    echo '<span class="badge badge-primary text-uppercase">Approved</span>';
                                    break;
                                case 'shipping':
                                    echo '<span class="badge badge-info text-uppercase">Shipping</span>';
                                    break;
                                case 'dispatch':
                                    echo '<span class="badge badge-dark text-uppercase">Dispatch</span>';
                                    break;
                                case 'out_for_delivery':
                                    echo '<span class="badge badge-secondary text-uppercase">Out for Delivery</span>';
                                    break;
                                case 'delivered':
                                    echo '<span class="badge badge-success text-uppercase">Delivered</span>';
                                    break;
                                case 'rejected':
                                    echo '<span class="badge badge-danger text-uppercase">Rejected</span>';
                                    break;
                                default:
                                    echo '<span class="badge badge-light text-uppercase">Unknown</span>';
                                    break;
                                }
                             ?>
                                 <?php if ($purchase->status === 'delivered') : ?>
                                    <a href="<?= base_url('Purchase_request/view_tax_invoice/' . base64_encode($purchase->id)); ?>" 
                                       class="badge badge-lg badge-info text-uppercase mt-1 ml-2">
                                        Tax Invoice
                                    </a>
                                <?php endif; ?>
                                 </td>
                      
                                   <td>
                                   <?php
                                  if ($purchase->order_status == 'approved') {
                                        $txt = 'Approved';
                                        $clr = 'info';
                                        echo '<span class="text-' . $clr . '">' . $txt . '</span>';
                                    }elseif ($purchase->order_status == 'rejected') {
                                        $txt = 'Rejected';
                                        $clr = 'danger';
                                        echo '<span class="text-' . $clr . '">' . $txt . '</span>';
                                    } 
                                    
                                    elseif ($purchase->order_status == 'partially_approved') {
                                        $txt = 'Partially approved';
                                        $clr = 'dark';
                                    }else {
                                        $txt = 'Pending';
                                        $clr = 'info';
                                    }
                                ?>

                           
                              <?php if ($purchase->added_by_branch === 'clients_branch_manager' && $purchase->order_status == 'pending'): ?>
                                    <span class="badge bg-info">Pending From HO</span>
                                 <a href="<?= base_url('Purchase_request/edit/' . base64_encode($purchase->id)) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <?php elseif ($purchase->added_by_branch === 'approval_2'): ?>
                                
                                    <?php if ($purchase->order_status == 'pending'): ?>
                                        <a href="#" class="btn btn-success btn-sm approve-btn" data-id="<?= $purchase->id ?>">Approve</a>
                                        <a href="#" class="btn btn-danger btn-sm open-reject-modal" data-id="<?= $purchase->id ?>">Reject</a>
                                 <a href="<?= base_url('Purchase_request/edit/' . base64_encode($purchase->id)) ?>" class="btn btn-warning btn-sm">Edit</a>

                                        <!--<span class="badge bg-info">Pending</span>-->
                                
                                    <?php elseif ($purchase->order_status == 'partially_approved'): ?>
                                        <span class="badge bg-success">Approved from me</span>
                                    <?php endif; ?>
                                <!-- Show Edit button only if $purchase->status is NULL -->
                          
                                <?php endif; ?>
                           
                            </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align: right;">Grand Total:</th>
                            <th></th>
                            <th colspan="4"></th>
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
<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="rejectForm" method="post" action="<?= base_url('Purchase_request/reject_purchase') ?>">
      <input type="hidden" name="id" id="reject_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Rejection</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to reject this purchase request?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Yes, Reject</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- SweetAlert2 CDN -->
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
      pageLength: 100,  // Default 100 entries
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
                  doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
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
        var table = $('#example').DataTable({
        responsive: true,
        autoWidth: false,
        order: [],
        columnDefs: [
            { 
                targets: [0],
                orderable: false
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            
            // Helper function to convert string to float
            var intVal = function (i) {
                return typeof i === 'string'
                    ? parseFloat(i.replace(/[^\d.-]/g, '')) || 0
                    : typeof i === 'number'
                    ? i
                    : 0;
            };

            // Calculate total over filtered data (column index 3)
            var total = api
                .column(3, { search: 'applied' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer cell
            $(api.column(3).footer()).html(
                '<?=$this->session->userdata('currency_symbol')?>' + 
                total.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );
        }
    });

    // Initialize select2
    $('.select2bs4').select2({ theme: 'bootstrap4' });
    
    // Initialize tooltips
    $('[data-tt="tooltip"]').tooltip({ trigger: 'hover' });

    // Status filter
    $('#sales_payment').on('change', function() {
        var status = $(this).val();
        if (status) {
            table.column(6).search(status).draw(); // Assuming status is in column 6
        } else {
            table.column(6).search('').draw();
        }
    });

    // Date filter change handler
    $('#from-date, #to-date').on('change', function() {
        filterByDateRange();
    });

    // Clear date filter button
    $('#clear-date-filter').click(function() {
        $('#from-date').val('');
        $('#to-date').val('');
        filterByDateRange();
    });

    // Function to filter by date range
    function filterByDateRange() {
        var fromDate = $('#from-date').val();
        var toDate = $('#to-date').val();
        
        // Clear any existing date filters
        $.fn.dataTable.ext.search.pop();
        
        if (fromDate || toDate) {
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var dateStr = data[2]; // Date column (index 2)
                    var dateParts = dateStr.split('-');
                    var rowDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    
                    if (fromDate && toDate) {
                        var minDate = new Date(fromDate);
                        var maxDate = new Date(toDate);
                        return rowDate >= minDate && rowDate <= maxDate;
                    } else if (fromDate) {
                        var minDate = new Date(fromDate);
                        return rowDate >= minDate;
                    } else if (toDate) {
                        var maxDate = new Date(toDate);
                        return rowDate <= maxDate;
                    }
                    return true;
                }
            );
        }
        
        table.draw();
    }
  });
  
  
 $(document).on('click', '.open-reject-modal', function(e) {
  e.preventDefault();
  var id = $(this).data('id');
  $('#reject_id').val(id);
  $('#rejectModal').modal('show');
}); 
  
  
  $(document).on('click', '.approve-btn', function(e) {
  e.preventDefault();
  
  var id = $(this).data('id');

  if (confirm('Are you sure you want to approve this purchase request?')) {
    $.ajax({
      url: '<?= base_url('Purchase_request/approve_from_manager') ?>',
      type: 'POST',
      data: { id: id },
      success: function(response) {
        try {
          var data = JSON.parse(response);
          if (data.success) {
            alert('Purchase request approved successfully.');
            location.reload();
          } else {
            alert('Failed to approve. Please try again.');
          }
        } catch (e) {
          alert('Unexpected error.');
        }
      },
      error: function() {
        alert('Server error. Please try again later.');
      }
    });
  }
});

</script>