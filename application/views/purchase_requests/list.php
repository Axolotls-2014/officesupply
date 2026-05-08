<?php $this->load->view('layout/header');?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
// 🔐 Logged-in user context (REQUIRED for branch logic)
$session_user_id = (int)$this->session->userdata('user_id');

$session_user = $this->db
    ->select('id, role, clients_branch_id')
    ->where('id', $session_user_id)
    ->get('users')
    ->row();

// TRUE if user belongs to a client branch (Tanya case)
$is_branch_user = !empty($session_user->clients_branch_id);
?>

   
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
        
        $user_ids = $visible_user_ids;
        
        $total_purchase = $this->db
            ->select_sum('total')
            ->where_in('added_by', $user_ids)
            ->get('sale_requests')
            ->row()
            ->total;
    
        $total_delivered = $this->db
            ->where_in('added_by', $user_ids)
            ->where('status', 'approved')
            ->count_all_results('sale_requests');
    
        $today_purchases = $this->db
            ->select_sum('total')
            ->where_in('added_by', $user_ids)
            ->like('created_date', date('Y-m-d'))
            ->get('sale_requests')
            ->row()
            ->total;
    }
    ?>
  <div class="row">
    <!--  <div class="col-12 col-sm-12 col-md-3">-->
    <!--   <div class="info-box mb-3">-->
    <!--    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>-->
    <!--     <div class="info-box-content">-->
    <!--              <span class="info-box-text">Approved Purchase Orders</span>-->
    <!--              <span class="info-box-number" style="font-size: 19px">-->
    <!--                <?= $total_delivered ?>-->
    <!--              </span>-->
    <!--       </div>-->
    <!--  </div>-->
    <!--</div>-->
    
    <!-- <div class="col-12 col-sm-12 col-md-3">-->
    <!--  <div class="info-box mb-3">-->
    <!--    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hand-holding-usd"></i></span>-->
    <!--        <div class="info-box-content">-->
    <!--              <span class="info-box-text">Unpproved Purchase Orders</span>-->
    <!--              <span class="info-box-number" style="font-size: 19px">-->
    <!--                <?= $total_rejected ?>-->
    <!--              </span>-->
    <!--         </div>-->
    <!--  </div>-->
    <!--</div>-->
    
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
    
    
    <!--<div class="col-12 col-sm-12 col-md-3">-->
    <!--  <div class="info-box mb-3">-->
    <!--    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>-->
    <!--    <div class="info-box-content">-->
    <!--      <span class="info-box-text">Total Payable Amount</span>-->
    <!--      <span class="info-box-number" style="font-size: 19px">-->
    <!--        <?=$this->session->userdata('currency_symbol')?>-->
    <!--        <?=($total_amount_received == '') ? '0' : number_format_i($total_amount_received)?>-->
    <!--      </span>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>-->
    
    <!-- <div class="col-12 col-sm-12 col-md-3">-->
    <!--  <div class="info-box mb-3">-->
    <!--    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>-->
    <!--    <div class="info-box-content">-->
    <!--      <span class="info-box-text">Total Delivered</span>-->
    <!--      <span class="info-box-number" style="font-size: 19px">-->
    <!--        <?=$this->session->userdata('currency_symbol')?>-->
    <!--        <?=($total_del == '') ? '0' : number_format_i($total_del)?>-->
    <!--      </span>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>-->
    
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
                    <form method="get" class="form-inline mb-3">
    <label for="status" class="mr-2">Filter by Status:</label>

    <select name="status" id="status" class="form-control form-control-sm mr-2">
        <option value="">All</option>

        <option value="pending"
            <?= ($this->input->get('status') === 'pending') ? 'selected' : '' ?>>
            Pending
        </option>

        <option value="approved"
            <?= ($this->input->get('status') === 'approved') ? 'selected' : '' ?>>
            Approved
        </option>

        <option value="create_invoice"
            <?= ($this->input->get('status') === 'create_invoice') ? 'selected' : '' ?>>
            Create Invoice
        </option>

        <option value="courier"
            <?= ($this->input->get('status') === 'courier') ? 'selected' : '' ?>>
            Courier
        </option>

        <option value="in_transit"
            <?= ($this->input->get('status') === 'in_transit') ? 'selected' : '' ?>>
            In Transit
        </option>

        <option value="delivered"
            <?= ($this->input->get('status') === 'delivered') ? 'selected' : '' ?>>
            Delivered
        </option>

        <option value="awaiting_confirmation"
            <?= ($this->input->get('status') === 'awaiting_confirmation') ? 'selected' : '' ?>>
            Awaiting Confirmation
        </option>

        <option value="delivery_confirmed"
            <?= ($this->input->get('status') === 'delivery_confirmed') ? 'selected' : '' ?>>
            Delivery Confirmed
        </option>

        <option value="rejected"
            <?= ($this->input->get('status') === 'rejected') ? 'selected' : '' ?>>
            Rejected
        </option>
    </select>
</form>

                  </li>
                   <li class="nav-item ml-2">
                    <a class="nav-link active" href="<?=base_url('Purchase_request/add')?>" data-tt="tooltip" title="Click here to Add Purchase"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('purchase_add')?></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <?php 
$level_id = (int) $this->session->userdata('level_id');
?>

<div class="mb-3">
  <label><b>Filter by Department:</b></label>
  <select id="deptFilter" class="form-control form-control-sm" style="width:200px;">
    <option value="">All</option>
    <?php foreach ($departments as $dept): ?>
      <option value="<?= $dept->name ?>">
        <?= $dept->name ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>
  
              <table id="example" class="table table-bordered table-striped">
                 <?php 
                  $level_id = (int) $this->session->userdata('level_id');
                  $dept_id  = (int) $this->session->userdata('dept_id');
                ?>
                <!--//code for filter by departments-->
               
                <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>dept id</th>
                    <th><?=$this->lang->line('purchase_order_request_no')?></th>
                    <th><?=$this->lang->line('purchase_date')?></th>
                    <th><?=$this->lang->line('purchase_total')?></th>
                    <th>Branch</th>
                    <th>Added From</th>
                    <th>Track History</th>
                    <th><?=$this->lang->line('delivery_status')?></th>
                    <th>Action</th>
                  </tr>
                </thead>
                 <tbody>
                      <?php $i=1;
                      foreach ($purchases as $purchase):
                      ?>
                        <tr>
                         <td><?php echo $i++;?></td>
                         <td><?php echo $this->db->query("SELECT d.name FROM users u LEFT JOIN departments d ON u.dept_id = d.id WHERE u.id = {$purchase->added_by}")->row()->name ?? 'Not Assigned'; ?></td>
                      <td>
                        <a href="<?= base_url('Purchase_request/view/' . base64_encode($purchase->id)) ?>" class="text-primary fw-bold">
                             <?= $purchase->reference_no ?? '-' ?>
                            <?php if (!empty($purchase->added_from)): ?>
                                (Behalf placed)
                            <?php endif; ?>
                           
                        </a>
                      </td>
                          <td><?= date('d-m-Y', strtotime($purchase->invoice_date)) ?></td>
                          <td><?= number_format($purchase->total ?? 0, 2) ?></td>
                           <td>
<?php
    // Logged-in user
    $logged_user_id = (int)$session_user_id;

    // Who placed the order
    $placed_by_id = (int)$purchase->added_by;

    // Get order creator branch
    $placed_by_branch_id = $this->db
        ->select('clients_branch_id')
        ->where('id', $placed_by_id)
        ->get('users')
        ->row('clients_branch_id');

    // 🔹 CASE 1: Logged-in user IS a branch user (Tanya)
    if ($is_branch_user) {

        $branch_id = !empty($purchase->branch_id)
            ? $purchase->branch_id
            : $session_user->clients_branch_id;

        $branch_name = $this->db
            ->select('branch_name')
            ->where('id', $branch_id)
            ->get('clients_branch')
            ->row('branch_name');

        echo '<span class="text-success">Branch ('.($branch_name ?? 'Unknown').')</span>';
    }
    // 🔹 CASE 2: Logged-in user NOT branch + true self order
    elseif (
        $placed_by_id === $logged_user_id &&
        empty($purchase->added_from)
    ) {
        echo '<span class="text-primary">Self Order</span>';
    }
    // 🔹 CASE 3: Order placed by a BRANCH USER (Tanya)
    elseif (!empty($placed_by_branch_id)) {

        $branch_name = $this->db
            ->select('branch_name')
            ->where('id', $placed_by_branch_id)
            ->get('clients_branch')
            ->row('branch_name');

        echo '<span class="text-success">Branch ('.($branch_name ?? 'Unknown').')</span>';
    }
    // 🔹 CASE 4: Everything else → NOT a branch order
    else {
        echo '<span class="text-primary">Self Order</span>';
    }
?>
</td>




                       <td>
                            <?php 
                                if (!empty($purchase->added_by)) {
                                    $user = $this->db->select('first_name,last_name')
                                                     ->where('id', $purchase->added_by)
                                                     ->get('users')
                                                     ->row();
                        
                                    if (!empty($purchase->added_from)) {
                                        $added_from_user = $this->db->select('first_name,last_name')
                                                                    ->where('id', $purchase->added_from)
                                                                    ->get('users')
                                                                    ->row();
                        
                                        echo ($user ? $user->first_name . ' ' . $user->last_name : '-') 
                                             . " (Behalf placed for " 
                                             . ($added_from_user ? $added_from_user->first_name . ' ' . $added_from_user->last_name : '-') 
                                             . ")";
                                    } else {
                                        echo $user ? $user->first_name . ' ' . $user->last_name : '-';
                                    }
                        
                                } else {
                                    echo '-';
                                }
                            ?>
                        </td>
                        <td>
                          <button class="btn btn-info btn-sm track-btn" 
                                  data-id="<?= $purchase->id ?>" 
                                  data-toggle="tooltip" 
                                  title="View Approval History">
                            <i class="fas fa-history mr-1"></i> Track
                          </button>
                        </td>
                        <td>
                              <?php
                               switch ($purchase->status) {

    case NULL:
    case 'pending':
        echo '<span class="badge badge-warning text-uppercase">Pending</span>';
        break;

    case 'approved':
        echo '<span class="badge badge-primary text-uppercase">Approved</span>';
        break;

    case 'create_invoice':
        echo '<span class="badge badge-info text-uppercase">Invoice Created</span>';
        break;

    case 'courier':
        echo '<span class="badge badge-secondary text-uppercase">Courier</span>';
        break;

    case 'in_transit':
        echo '<span class="badge badge-info text-uppercase">In Transit</span>';
        break;

    case 'delivered':
        echo '<span class="badge badge-success text-uppercase">Delivered</span>';
        break;
        
     case 'awaiting_confirmation':
        echo '<span class="badge badge-warning text-uppercase">Awaiting Confirmation</span>';
        break;

    case 'delivery_confirmed':
        echo '<span class="badge badge-success text-uppercase">Delivery Confirmed</span>';
        break;

   /* case 'rejected':
        echo '<span class="badge badge-danger text-uppercase">Rejected</span>';
        break;*/

        case 'rejected':
    echo '<a href="#" class="view-remark-btn" data-id="'.$purchase->id.'" data-reference="'.$purchase->reference_no.'">
            <span class="badge badge-danger text-uppercase" style="cursor: pointer;">
                <i class="fas fa-eye mr-1"></i> Rejected
            </span>
          </a>';
    break;

    default:
        echo '<span class="badge badge-light text-uppercase">Unknown</span>';
        break;
}

?>
                              <!--<?php if ($purchase->status === 'approved') : ?>-->
                              <!--   <a href="<?= base_url('Purchase_request/view_tax_invoice/' . base64_encode($purchase->id)); ?>" -->
                              <!--    class="badge badge-lg badge-info text-uppercase mt-1 ml-2" title="View Tax Invoice">-->
                              <!--   <i class="fa fa-file-invoice"></i>-->
                              <!--</a>-->
                              <!--<?php endif; ?>-->
                              
                              <?php if (!empty($purchase->status) && !in_array($purchase->status, ['pending','rejected'])) : ?>

                                <a href="<?= base_url('Purchase_request/view_tax_invoice/' . base64_encode($purchase->id)); ?>" 
                                   class="badge badge-lg badge-info text-uppercase mt-1 ml-2" 
                                   title="View Tax Invoice">
                                    <i class="fa fa-file-invoice"></i>
                                </a>
                            <?php endif; ?>

                            </td>
                        <!--<td>-->
                            <?php
                                if ($purchase->order_status == 'approved') {
                                    $txt = 'Approved By OSS';
                                    $clr = 'info';
                                } elseif ($purchase->order_status == 'rejected') {
                                    $txt = 'Rejected';
                                    $clr = 'danger';
                                } 
                                  elseif ($purchase->order_status == 'partially_approved') {
                                        $txt = 'Partially approved';
                                        $clr = 'dark';
                                    }else {
                                    $txt = 'Pending';
                                    $clr = 'warning';
                                }
                            ?>
                        <td>
                         <?php
                            $session_user_id    = $this->session->userdata('user_id');
                            $session_level_id   = (int)$this->session->userdata('level_id');
                            // Who placed this order
                            $placed_by_id       = (int)$purchase->added_by;
                            $placed_by_level_id = (int)$this->db->select('level_id')->where('id', $placed_by_id)->get('users')->row('level_id');
                            
                            // Determine if Approve button should show
                            $show_approve_btn = false;
                            
                            // Rule 1: If it's self-placed order → no approve button
                         if ($placed_by_id !== $session_user_id) {
        
                           // Rule 2: Normal flow
                            if ($placed_by_level_id === 2 && $session_level_id === 1 && $purchase->order_status === 'pending') {
                                $show_approve_btn = true;
                            }
                            if ($placed_by_level_id === 1 && $session_level_id === 0 && $purchase->order_status === 'pending') {
                                $show_approve_btn = true;
                            }
                        
                            // Rule 3: If placed by level 2 → directly to level 0 (when already approved by level 1)
                            if ($placed_by_level_id === 2 && $session_level_id === 0 && $purchase->order_status === 'partially_approved') {
                                $show_approve_btn = true;
                            }
                        
                            // Rule 4: If placed by level 2 → allow level 0 to approve/reject directly (even if not touched by level 1 yet)
                            if ($placed_by_level_id === 2 && $session_level_id === 0 && $purchase->order_status === 'pending') {
                                $show_approve_btn = true;
                            }
                        
                            // ✅ Rule 5: If placed by level 3 → goes to level 2 first
                            if ($placed_by_level_id === 3 && $session_level_id === 2 && $purchase->order_status === 'pending') {
                                $show_approve_btn = true;
                            }
                        
                            // ✅ Rule 6: If placed by level 3 → allow level 1 to approve once level 2 has approved (partially_approved)
                            if ($placed_by_level_id === 3 && $session_level_id === 1 && $purchase->order_status === 'partially_approved') {
                                $show_approve_btn = true;
                            }
                        
                            // ✅ Rule 7: If placed by level 3 → allow level 0 to approve once level 1 has approved
                            if ($placed_by_level_id === 3 && $session_level_id === 0 && $purchase->order_status === 'partially_approved') {
                                $show_approve_btn = true;
                              }
                            }
                            if ($purchase->order_status === 'approved') {
                                echo '<span class="badge badge-info">Approved By HO</span>';
                            } elseif ($purchase->order_status === 'rejected') {
                                echo '<span class="badge badge-danger">Rejected BY HO</span>';
                            } elseif ($purchase->order_status === 'partially_approved') {
                                echo '<span class="badge badge-dark">Partially Approved</span>';
                            } else {
                                echo '<span class="badge badge-warning">Pending</span>';
                            }
                            
                            if ($show_approve_btn) {
                                ?>
                                <a href="#" class="btn btn-success btn-sm approve-btn" data-id="<?= $purchase->id ?>">Approve</a>
                                <a href="#" class="btn btn-danger btn-sm open-reject-modal" data-id="<?= $purchase->id ?>">Reject</a>
                                <?php
                            }
                            
                           if (!($purchase->status == 'approved' || $purchase->status == 'rejected'||$purchase->order_status === 'rejected')) {
                                if ($session_level_id === 0) {
                                    ?>
                                    <a href="<?= base_url('Purchase_request/edit/' . base64_encode($purchase->id)) ?>" 
                                       class="btn btn-warning btn-sm">Edit</a>
                                    <?php
                                } else {
                                    if ($purchase->order_status === 'pending') {
                                        ?>
                                        <a href="<?= base_url('Purchase_request/edit/' . base64_encode($purchase->id)) ?>" 
                                           class="btn btn-warning btn-sm">Edit</a>
                                        <?php
                                    }
                                }
                            }
                            $show_delete_btn = false;
                            if ($session_level_id === 0 && $purchase->status == 'pending' || $purchase->status === NULL) {
                                $show_delete_btn = true;
                            } elseif ($session_level_id === 1 && ($purchase->status === 'pending' || $purchase->status === NULL)) {
                                $show_delete_btn = true;
                            } elseif ($session_level_id === 2 && $purchase->status === 'pending' || $purchase->status === NULL) {
                                $show_delete_btn = true;
                            }
                            elseif ($session_level_id === 3 && $purchase->status === 'pending' || $purchase->status === NULL) {
                                $show_delete_btn = true;
                            }
                            if ($show_delete_btn) {
                                ?>
                                <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?= $purchase->id ?>">
                                    Delete
                                </a>
                                <?php
                            }
                        ?>
                        </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3" style="text-align: right;">Grand Total:</th>
                        <th></th>
                        <th colspan="5"></th>
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
<!-- Reject Confirmation Modal with Remark Field -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="rejectForm" method="post" action="<?= base_url('Purchase_request/reject_purchase_req') ?>">
      <input type="hidden" name="id" id="reject_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Rejection</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to reject this purchase request?</p>
          <div class="form-group mt-3">
            <label for="rejection_remark">Rejection Remark <span class="text-danger">*</span></label>
            <textarea class="form-control" id="rejection_remark" name="rejection_remark" rows="3" placeholder="Please provide reason for rejection..."></textarea>
            <small class="text-muted">This remark will be visible to the user</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Yes, Reject</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Track History Modal -->
<div class="modal fade" id="trackHistoryModal" tabindex="-1" role="dialog" aria-labelledby="trackHistoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="trackHistoryModalLabel">
          <i class="fas fa-history mr-2"></i> Approval History
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="tracking-container">
          <div class="tracking-timeline" id="trackingTimeline">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- View Rejection Remark Modal -->
<div class="modal fade" id="viewRemarkModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="fas fa-times-circle mr-2"></i> Rejection Details
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>Order #<span id="remark_order_no"></span></strong> has been rejected.
        </div>
        <div class="form-group">
          <label><i class="fas fa-comment-dots"></i> Rejection Remark:</label>
          <div class="well bg-light p-3 border rounded" id="rejection_remark_display" style="min-height: 100px; white-space: pre-wrap;">
            Loading...
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php $this->load->view('layout/footer');?>

<style>
.tracking-container {
  position: relative;
  padding: 20px 0;
}

.tracking-timeline {
  position: relative;
  max-width: 800px;
  margin: 0 auto;
}

.tracking-timeline::before {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  left: 20px;
  width: 2px;
  background: #dee2e6;
}

.timeline-item {
  position: relative;
  margin-bottom: 30px;
  padding-left: 50px;
}

.timeline-marker {
  position: absolute;
  left: 12px;
  top: 0;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #6c757d;
  border: 3px solid #fff;
  z-index: 1;
}

.timeline-item.completed .timeline-marker {
  background: #28a745;
}

.timeline-item.current .timeline-marker {
  background: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
}

.timeline-content {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  border-left: 4px solid #dee2e6;
}

.timeline-item.completed .timeline-content {
  border-left-color: #28a745;
  background: #f0fff4;
}

.timeline-item.current .timeline-content {
  border-left-color: #007bff;
  background: #f0f8ff;
}

.timeline-title {
     font-size: 15px;
  font-weight: 600;
  margin-bottom: 5px;
  color: #495057;
}

.timeline-date {
  font-size: 0.85rem;
  color: #6c757d;
  margin-bottom: 5px;
}

.timeline-user {
  font-size: 13px;
  color: #a029a7;
}

.timeline-user i {
  margin-right: 5px;
  color: #6c757d;
}

.track-btn {
  transition: all 0.3s ease;
  border-radius: 20px;
  padding: 6px 12px;
}

.track-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.approved-badge {
    background-color: #28a745;   /* Green badge */
    color: #fff;
    font-size: 12px;
    font-weight: bold;
    padding: 2px 8px;
    border-radius: 12px;
    margin-left: auto;   /* Push to right */
    display: inline-block;
    float: right;        /* Align at right end */
}
.status-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 10px;
    margin-left: 8px;
    float: right;
}

.status-badge.placed {
    background: #007bff;
    color: #fff;
}

.status-badge.approved {
    background: #28a745;
    color: #fff;
}

.status-badge.pending {
    background: #ffc107;
    color: #000;
}

.status-badge.upcoming {
    background: #6c757d;
    color: #fff;
}
.highlight-row {
    background-color: #ffeb3b !important;
    animation: highlightPulse 1s ease-in-out 3;
    box-shadow: 0 0 10px rgba(255, 235, 59, 0.8);
}

.highlight-row td {
    background-color: #ffeb3b !important;
}

@keyframes highlightPulse {
    0% { background-color: #fff3cd; }
    50% { background-color: #ffeb3b; }
    100% { background-color: #fff3cd; }
}

</style>

<script type="text/javascript">
$(document).ready(function() {
// Check if there's a highlight parameter in the URL
   // Check if there's a highlight parameter in the URL
var urlParams = new URLSearchParams(window.location.search);
var highlightId = urlParams.get('highlight');

console.log('Highlight ID from URL:', highlightId); // Debug

if (highlightId) {
    // The highlightId is already base64 encoded from the URL
    // Don't decode it - use it as is to match the href
    var encodedId = highlightId;
    console.log('Looking for row with encoded ID:', encodedId); // Debug
    
    // Add a small delay to ensure DataTable is fully loaded
    setTimeout(function() {
        var found = false;
        
        $('table tbody tr').each(function() {
            var row = $(this);
            // Look for the link containing the encoded purchase ID
            var link = row.find('a[href*="' + encodedId + '"]');
            
            if (link.length > 0) {
                console.log('Found matching row!'); // Debug
                // Add highlight class to the row
                row.addClass('highlight-row');
                
                // Scroll to the highlighted row
                $('html, body').animate({
                    scrollTop: row.offset().top - 100
                }, 800);
                
                // Remove highlight after 8 seconds
                setTimeout(function() {
                    row.removeClass('highlight-row');
                }, 8000);
                
                found = true;
                return false; // break the loop
            }
        });
        
        if (!found) {
            console.log('No matching row found for encoded ID:', encodedId);
            // Debug: show all hrefs in the table
            console.log('All links in table:', $('table tbody tr a').map(function() {
                return $(this).attr('href');
            }).get());
        }
    }, 500); // Wait 500ms for DataTable to initialize
}
    var table = $('#example').DataTable({
        responsive: true,
        autoWidth: false,
        order: [],
        pageLength: 100,
        lengthMenu: [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        columnDefs: [
            { targets: [0], orderable: false }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var intVal = function (i) {
                return typeof i === 'string'
                    ? parseFloat(i.replace(/[^\d.-]/g, '')) || 0
                    : typeof i === 'number' ? i : 0;
            };
            var total = api
                .column(3, { search: 'applied' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            $(api.column(3).footer()).html(
                '<?=$this->session->userdata('currency_symbol')?>' +
                total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            );
        }
    });

    // ✅ Department Filter
    $('#deptFilter').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        table.column(1) 
             .search(val ? '^' + val + '$' : '', true, false)
             .draw();
    });

    $('.select2bs4').select2({ theme: 'bootstrap4' });
    $('[data-tt="tooltip"]').tooltip({ trigger: 'hover' });

    $(document).on('click', '.track-btn', function(e) {
        e.preventDefault();
        var purchaseId = $(this).data('id');
        loadTrackingHistory(purchaseId);
    });

    function loadTrackingHistory(purchaseId) {
        $.ajax({
            url: '<?= base_url("Purchase_request/get_approval_history") ?>',
            type: 'POST',
            data: { purchase_id: purchaseId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayTrackingHistory(response.data);
                    $('#trackHistoryModal').modal('show');
                } else {
                    Swal.fire('Error', 'Failed to load tracking history', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Server error while loading tracking history', 'error');
            }
        });
    }

    function displayTrackingHistory(data) {
        var timelineHtml = `
            <div class="timeline-item completed">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-title">Order Placed 
                        <span class="status-badge placed">Order Submitter</span>
                    </div>
                    <div class="timeline-user"><i class="fas fa-user"></i> ${data.added_by.name}</div>
                    <div class="timeline-branch"><i class="fas fa-map-marker-alt text-muted"></i> ${data.added_by.branch}</div>
                    <div class="timeline-date"><i class="fas fa-calendar"></i> ${data.added_by.date}</div>
                </div>
            </div>
        `;

        
 
        if (data.approval_levels && data.approval_levels.length > 0) {
            let foundCurrent = false;
            data.approval_levels.forEach(function(level) {
                var statusClass = level.status;
                var userHtml = '';

                if (level.user) {
                    if (level.date) {
                        userHtml = `
                            <div class="timeline-user"><i class="fas fa-user-check"></i> ${level.user} 
                                <span class="status-badge approved">Approved</span>
                            </div>
                            <div class="timeline-branch"><i class="fas fa-map-marker-alt text-muted"></i> ${level.branch}</div>
                            <div class="timeline-date"><i class="fas fa-calendar"></i> ${level.date}</div>
                        `;
                        statusClass = 'completed';
                    } else {
                        if (!foundCurrent) {
                            foundCurrent = true;
                            statusClass = 'current';
                            userHtml = `
                                <div class="timeline-user"><i class="fas fa-users"></i> ${level.user} 
                                    <span class="status-badge pending">Pending at</span>
                                </div>
                                <div class="timeline-branch"><i class="fas fa-map-marker-alt text-muted"></i> ${level.branch}</div>
                                <div class="timeline-date"><i class="fas fa-clock"></i> Waiting for approval</div>
                            `;
                        } else {
                            statusClass = 'secondary';
                            userHtml = `
                                <div class="timeline-user"><i class="fas fa-clock"></i> ${level.user} 
                                    <span class="status-badge upcoming">Upcoming</span>
                                </div>
                                <div class="timeline-branch"><i class="fas fa-map-marker-alt text-muted"></i> ${level.branch}</div>
                                <div class="timeline-date"><i class="fas fa-clock"></i> Upcoming approval</div>
                            `;
                        }
                    }
                }
                timelineHtml += `
                    <div class="timeline-item ${statusClass}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">${level.title}</div>
                            ${userHtml}
                        </div>
                    </div>
                `;
            });
        }
        timelineHtml += `
            <div class="timeline-item ${data.final_status_class}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-title">Final Status from OSS</div>
                    <div class="timeline-user"><i class="fas fa-flag"></i> ${data.final_status}</div>
                </div>
            </div>
        `;
        /* ================= DELIVERY STATUS FLOW ================= */
// if (data.delivery_timeline && data.delivery_timeline.length > 0) {
//     data.delivery_timeline.forEach(function(step) {
//         timelineHtml += `
//             <div class="timeline-item ${step.status}">
//                 <div class="timeline-marker"></div>
//                 <div class="timeline-content">
//                     <div class="timeline-title">${step.title}</div>
//                 </div>
//             </div>
//         `;
//     });
// }
if (data.delivery_timeline && data.delivery_timeline.length > 0) {

    timelineHtml += `
        <div class="timeline-item completed">
            <div class="timeline-marker"></div>
             
        </div>
    `;

    data.delivery_timeline.forEach(function(step) {

        let iconHtml = '';
        if (step.status === 'completed') {
            iconHtml = `<i class="fas fa-check-circle text-success mr-1"></i>`;
        } else if (step.status === 'current') {
            iconHtml = `<i class="fas fa-dot-circle text-primary mr-1"></i>`;
        } else {
            iconHtml = `<i class="far fa-circle text-muted mr-1"></i>`;
        }

    //     timelineHtml += `
    //         <div class="timeline-item ${step.status}">
    //             <div class="timeline-marker"></div>
    //             <div class="timeline-content">
    //                 <div class="timeline-title">
    //                     ${iconHtml} ${step.title}
    //                 </div>
    //             </div>
    //         </div>
    //     `;
    let metaHtml = '';

if (step.meta && step.meta.mode) {

    // if (step.meta.mode === 'Courier') {
    //     metaHtml = `
    //         <div class="timeline-user">
    //             <i class="fas fa-truck"></i> ${step.meta.partner}<br>
    //             <i class="fas fa-barcode"></i> ${step.meta.docket}<br>
    //             <i class="fas fa-calendar"></i> ${step.meta.date}
    //         </div>
    //     `;
    // }

    if (step.meta.mode === 'Courier') {
    metaHtml = `
        <div class="timeline-user">
            <i class="fas fa-truck"></i> ${step.meta.partner}<br>
            <i class="fas fa-barcode"></i> ${step.meta.docket}<br>
            <i class="fas fa-calendar"></i> ${step.meta.date}
            ${step.meta.link ? `
                <br>
                <i class="fas fa-link"></i>
                <a href="${step.meta.link}" target="_blank" class="text-primary">
                    Open Tracking Link
                </a>
            ` : ``}
        </div>
    `;
}
    if (step.meta.mode === 'Physical') {
        metaHtml = `
            <div class="timeline-user">
                <i class="fas fa-user"></i> ${step.meta.person}<br>
                <i class="fas fa-calendar"></i> ${step.meta.date}
            </div>
        `;
    }

    if (step.meta.mode === 'Vendor') {
        metaHtml = `
            <div class="timeline-user">
                <i class="fas fa-building"></i> ${step.meta.vendor}<br>
                <i class="fas fa-map-marker-alt"></i> ${step.meta.city}<br>
                <i class="fas fa-route"></i> ${step.meta.via}
                ${step.meta.link ? `
<br>
<i class="fas fa-link"></i>
<a href="${step.meta.link}" target="_blank" class="text-primary">
    Open Vendor Link
</a>
` : ``
                    
                }
            </div>
            
        `;
    }
    
    
}

timelineHtml += `
    <div class="timeline-item ${step.status}">
        <div class="timeline-marker"></div>
        <div class="timeline-content">
            <div class="timeline-title">
                ${iconHtml} ${step.title}
            </div>
            ${metaHtml}
        </div>
    </div>
`;

    });
}
        $('#trackingTimeline').html(timelineHtml);
    }

    // ✅ Status Filter
    $('#status').on('change', function() {
        var status = $(this).val();
        table.column(7).search(status || '', true, false).draw();
    });

    // ✅ Date Range Filter
    $('#from-date, #to-date').on('change', filterByDateRange);
    $('#clear-date-filter').click(function() {
        $('#from-date, #to-date').val('');
        filterByDateRange();
    });

    function filterByDateRange() {
        var fromDate = $('#from-date').val();
        var toDate = $('#to-date').val();
        $.fn.dataTable.ext.search.pop();

        if (fromDate || toDate) {
            $.fn.dataTable.ext.search.push(function(settings, data) {
                var dateStr = data[2];
                var dateParts = dateStr.split('-');
                var rowDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                if (fromDate && toDate) {
                    return rowDate >= new Date(fromDate) && rowDate <= new Date(toDate);
                } else if (fromDate) {
                    return rowDate >= new Date(fromDate);
                } else if (toDate) {
                    return rowDate <= new Date(toDate);
                }
                return true;
            });
        }
        table.draw();
    }

    // ✅ Reject Modal
   /* $(document).on('click', '.open-reject-modal', function(e) {
        e.preventDefault();
        $('#reject_id').val($(this).data('id'));
        $('#rejectModal').modal('show');
    });*/
    // ✅ Reject Modal with Remark Validation
$(document).on('click', '.open-reject-modal', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#reject_id').val(id);
    $('#rejection_remark').val(''); // Clear previous remark
    $('#rejectModal').modal('show');
});

// ✅ Validate Remark Before Submitting
$('#rejectForm').on('submit', function(e) {
    var remark = $('#rejection_remark').val().trim();
    if (remark === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Remark Required',
            text: 'Please provide a reason for rejection',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }
    return true;
});

    // ✅ Approve Button
    $(document).on('click', '.approve-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to approve this purchase request?')) {
            $.ajax({
                url: '<?= base_url('Purchase_request/approve_purchase') ?>',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        alert(data.success ? 'Purchase request approved successfully.' : 'Failed to approve. Please try again.');
                        if (data.success) location.reload();
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

    // ✅ Delete Button
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete this order?")) {
            var id = $(this).data('id');
            $('<form>', { method: "POST", action: "<?= base_url('Purchase_request/delete_order') ?>" })
                .append($('<input>', { type: "hidden", name: "id", value: id }))
                .appendTo(document.body)
                .submit();
        }
    });

    // View Rejection Remark Modal
$(document).on('click', '.view-remark-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var referenceNo = $(this).data('reference');
    
    $('#remark_order_no').text(referenceNo);
    $('#rejection_remark_display').html('<i class="fas fa-spinner fa-spin"></i> Loading remark...');
    $('#viewRemarkModal').modal('show');
    
    $.ajax({
        url: '<?= base_url("Purchase_request/get_rejection_remark") ?>',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var remarkText = response.remark || 'No remark provided';
                // Convert newlines to <br> for display
                remarkText = remarkText.replace(/\n/g, '<br>');
                $('#rejection_remark_display').html(remarkText);
            } else {
                $('#rejection_remark_display').html('<span class="text-danger">' + (response.message || 'Failed to load remark') + '</span>');
            }
        },
        error: function() {
            $('#rejection_remark_display').html('<span class="text-danger">Server error while loading rejection remark</span>');
        }
    });
});
});
</script>
<script>
    document.getElementById('status').addEventListener('change', function () {
        this.form.submit();
    });
</script>
