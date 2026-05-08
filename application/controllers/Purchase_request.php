<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Purchase_request extends MY_Controller {
	private $pdf;
	public function __construct()
	{
// 	    error_reporting(E_ALL);
//      ini_set('display_errors', 1);
		parent::__construct();
		$this->pdf = new Dompdf();
        $this->load->model('email_template_model');  // Add this
       $this->load->model('email_setup_model');     // Add this
    
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
 ////////sakshi code///////////////
// 	 public function get_approval_history()
//  {
//     $purchase_id = $this->input->post('purchase_id');
    
//     $purchase = $this->db->where('id', $purchase_id)->get('sale_requests')->row();
    
//     if (!$purchase) {
//         echo json_encode(['success' => false, 'message' => 'Purchase not found']);
//         return;
//     }
    
//     $added_by_user = $this->db->select('first_name, last_name, level_id')
//                              ->where('id', $purchase->added_by)
//                              ->get('users')
//                              ->row();
                             
//     if ($purchase->branch_id!=NULL) {
//             $branch = $this->db->select('branch_name')
//                               ->where('id', $purchase->branch_id)
//                               ->get('clients_branch')
//                               ->row();
//             $branch_name = $branch ? $branch->branch_name : 'Unknown';
//         } else {
//             $branch_name = 'Head Office';
//         }

//     $data = [
//         'added_by' => [
//             'name' => $added_by_user ? $added_by_user->first_name . ' ' . $added_by_user->last_name : 'Unknown',
//             'branch' => $branch_name,
//             'date' => date('d-m-Y H:i', strtotime($purchase->created_date))
//         ],
        
//         'approval_levels' => [],
//         'current_status' => $purchase->order_status ? ucfirst(str_replace('_', ' ', $purchase->order_status)) : 'Pending',
//         'final_status' => ($purchase->status === NULL || $purchase->status === 'pending') 
//                             ? 'Pending for OSS' 
//                             : (($purchase->status === 'rejected') ? 'Rejected by OSS' : 'Approved'),
//         'final_status_class' => ($purchase->status === NULL || $purchase->status === 'pending') 
//                             ? 'current_status'     
//                             : (($purchase->status === 'rejected') ? 'rejected' : 'completed') 
//     ];

    
//     $completed_approvals = [];
//     if (!empty($purchase->approval_level)) {
//         $approvals = explode(',', $purchase->approval_level);
        
//         foreach ($approvals as $index => $approval) {
//             if (strpos($approval, '|') !== false) {
//                 list($user_id, $date) = explode('|', $approval);
                
//                 $user = $this->db->select('first_name, last_name')
//                                 ->where('id', $user_id)
//                                 ->get('users')
//                                 ->row();
                
//                 if ($user) {
//                     $completed_approvals[] = [
//                         'level_id' => $this->get_user_level($user_id), 
//                         'title'  => 'Approval Level ' . ($index + 1),
//                         'user'   => $user->first_name . ' ' . $user->last_name,
//                         'date'   => date('d-m-Y H:i', strtotime($date)),
//                         'status' => 'completed'
//                     ];
//                 }
//             }
//         }
//     }
    
//     $approval_hierarchy = $this->get_approval_hierarchy($added_by_user->level_id, $purchase->added_by, $purchase->company_id);
    
//     $level_counter = 1;
//     foreach ($approval_hierarchy as $level_data) {
//         $found_completed = false;
    
//         $approver_names = [];
//         $approver_branches = [];
    
//         foreach ($level_data['approvers'] as $approver_name) {
//             $approver_names[] = $approver_name;
    
//             $user = $this->db->select('clients_branch_id')
//                              ->where("CONCAT(first_name,' ',last_name) = ", $approver_name)
//                              ->get('users')
//                              ->row();
    
//             if ($user) {
//                 if (!empty($user->clients_branch_id)) {
//                     $branch = $this->db->select('branch_name')
//                                       ->where('id', $user->clients_branch_id)
//                                       ->get('clients_branch')
//                                       ->row();
//                     if ($branch) $approver_branches[] = $branch->branch_name;
//                 } else {
//                     $approver_branches[] = 'Head Office';
//                 }
//             } else {
//                 $approver_branches[] = 'Head Office';
//             }
//         }
    
//         $approver_branches = array_unique($approver_branches);
    
//         foreach ($completed_approvals as $completed) {
//             if ($completed['level_id'] == $level_data['level_id']) {
//                 $data['approval_levels'][] = [
//                     'title'  => 'Approval Level ' . $level_counter,
//                     'user'   => $completed['user'],
//                     'branch' => implode(', ', $approver_branches),
//                     'date'   => $completed['date'],
//                     'status' => 'completed'
//                 ];
//                 $found_completed = true;
//                 break;
//             }
//         }
    
//         if (!$found_completed) {
//             $data['approval_levels'][] = [
//                 'title'  => 'Approval Level ' . $level_counter,
//                 'user'   => implode(', ', $approver_names),
//                 'branch' => implode(', ', $approver_branches),
//                 'date'   => null,
//                 'status' => ($level_counter == count($completed_approvals) + 1) ? 'current' : 'pending'
//             ];
//         }
    
//         $level_counter++;
//     }
//     echo json_encode(['success' => true, 'data' => $data]);
// }

// public function get_approval_history()
// {
//     $purchase_id = $this->input->post('purchase_id');

//     $purchase = $this->db->where('id', $purchase_id)->get('sale_requests')->row();

//     if (!$purchase) {
//         echo json_encode(['success' => false, 'message' => 'Purchase not found']);
//         return;
//     }

//     // USER WHO PLACED ORDER
//     $added_by_user = $this->db->select('id, first_name, last_name, level_id, added_by')
//                               ->where('id', $purchase->added_by)
//                               ->get('users')
//                               ->row();

//     // FIND CREATOR OF ORDER USER (THIS IS WHAT YOU WANT TO DISPLAY)
//     $creator = $this->db->select('first_name, last_name')
//                         ->where('id', $added_by_user->added_by)
//                         ->get('users')
//                         ->row();

//     $creator_name = $creator ? $creator->first_name . ' ' . $creator->last_name : 'Unknown';

//     // Branch of purchase
//     if ($purchase->branch_id != NULL) {
//         $branch = $this->db->select('branch_name')
//                           ->where('id', $purchase->branch_id)
//                           ->get('clients_branch')
//                           ->row();
//         $branch_name = $branch ? $branch->branch_name : 'Unknown';
//     } else {
//         $branch_name = 'Head Office';
//     }

//     // BASE DATA
//     $data = [
//         'added_by' => [
//             'name'   => $added_by_user->first_name . ' ' . $added_by_user->last_name,
//             'branch' => $branch_name,
//             'date'   => date('d-m-Y H:i', strtotime($purchase->created_date))
//         ],
//         'approval_levels' => [],
//         'current_status'  => $purchase->order_status 
//                             ? ucfirst(str_replace('_', ' ', $purchase->order_status)) 
//                             : 'Pending',
//         'final_status'    => ($purchase->status === NULL || $purchase->status === 'pending')
//                             ? 'Pending for OSS'
//                             : (($purchase->status === 'rejected') ? 'Rejected by OSS' : 'Approved'),
//         'final_status_class' => ($purchase->status === NULL || $purchase->status === 'pending')
//                             ? 'current_status'
//                             : (($purchase->status === 'rejected') ? 'rejected' : 'completed')
//     ];

//     // COMPLETED APPROVALS
//     $completed_approvals = [];
//     if (!empty($purchase->approval_level)) {
//         $approvals = explode(',', $purchase->approval_level);
//         foreach ($approvals as $index => $approval) {
//             if (strpos($approval, '|') !== false) {
//                 list($user_id, $date) = explode('|', $approval);

//                 $completed_approvals[] = [
//                     'level_id' => $this->get_user_level($user_id),
//                     'date'     => date('d-m-Y H:i', strtotime($date))
//                 ];
//             }
//         }
//     }

//     // HIERARCHY LOADED, BUT WE DO NOT SHOW THEIR NAMES ANYMORE
//     $approval_hierarchy = $this->get_approval_hierarchy(
//         $added_by_user->level_id,
//         $purchase->added_by,
//         $purchase->company_id
//     );

//     // ALWAYS SHOW ONLY CREATOR NAME
//     $level_counter = 1;

//     foreach ($approval_hierarchy as $level_data) {

//         // FIND COMPLETED LEVEL
//         $completed = null;
//         foreach ($completed_approvals as $comp) {
//             if ($comp['level_id'] == $level_data['level_id']) {
//                 $completed = $comp;
//                 break;
//             }
//         }

//         // IF COMPLETED → SHOW CREATOR NAME
//         if ($completed) {
//             $data['approval_levels'][] = [
//                 'title'  => 'Approval Level ' . $level_counter,
//                 'user'   => $creator_name,         // <<< ONLY CREATOR NAME
//                 'branch' => $branch_name,
//                 'date'   => $completed['date'],
//                 'status' => 'completed'
//             ];
//         }
//         // IF NOT COMPLETED → ALSO SHOW CREATOR NAME
//         else {
//             $data['approval_levels'][] = [
//                 'title'  => 'Approval Level ' . $level_counter,
//                 'user'   => $creator_name,         // <<< ONLY CREATOR NAME
//                 'branch' => $branch_name,
//                 'date'   => null,
//                 'status' => ($level_counter == count($completed_approvals) + 1) 
//                                 ? 'current' 
//                                 : 'pending'
//             ];
//         }

//         $level_counter++;
//     }

//     echo json_encode(['success' => true, 'data' => $data]);
// }

// public function get_approval_history()
// {
//     $purchase_id = $this->input->post('purchase_id');

//     $purchase = $this->db->where('id', $purchase_id)->get('sale_requests')->row();

//     if (!$purchase) {
//         echo json_encode(['success' => false, 'message' => 'Purchase not found']);
//         return;
//     }

//     /* ---------------------------------------------------
//       1️⃣ GET USER WHO PLACED THE ORDER
//     --------------------------------------------------- */
//     $added_by_user = $this->db->select('id, first_name, last_name, level_id, added_by')
//                               ->where('id', $purchase->added_by)
//                               ->get('users')
//                               ->row();

//     $placed_by_name = $added_by_user->first_name . ' ' . $added_by_user->last_name;

//     /* ---------------------------------------------------
//       2️⃣ GET CREATOR (added_by of the user)
//     --------------------------------------------------- */
//     $creator = $this->db->select('first_name, last_name')
//                         ->where('id', $added_by_user->added_by)
//                         ->get('users')
//                         ->row();

//     $creator_name = $creator ? $creator->first_name . ' ' . $creator->last_name : 'Unknown';

//     /* ---------------------------------------------------
//       3️⃣ GET BRANCH NAME
//     --------------------------------------------------- */
//     if ($purchase->branch_id != NULL) {
//         $branch = $this->db->select('branch_name')
//                           ->where('id', $purchase->branch_id)
//                           ->get('clients_branch')
//                           ->row();
//         $branch_name = $branch ? $branch->branch_name : 'Unknown';
//     } else {
//         $branch_name = 'Head Office';
//     }

//     /* ---------------------------------------------------
//       4️⃣ BASE DATA
//     --------------------------------------------------- */
//     $data = [
//         'added_by' => [
//             'name'   => $placed_by_name,
//             'branch' => $branch_name,
//             'date'   => date('d-m-Y H:i', strtotime($purchase->created_date))
//         ],
//         'approval_levels' => [],
//         'current_status'  => $purchase->order_status 
//                             ? ucfirst(str_replace('_', ' ', $purchase->order_status)) 
//                             : 'Pending',
//         'final_status'    => ($purchase->status === NULL || $purchase->status === 'pending')
//                             ? 'Pending for OSS'
//                             : (($purchase->status === 'rejected') ? 'Rejected by OSS' : 'Approved'),
//         'final_status_class' => ($purchase->status === NULL || $purchase->status === 'pending')
//                             ? 'current_status'
//                             : (($purchase->status === 'rejected') ? 'rejected' : 'completed')
//     ];

//     /* ---------------------------------------------------
//       5️⃣ PARSE COMPLETED APPROVALS (32|date, 31|date etc.)
//     --------------------------------------------------- */
//     $completed_approvals = [];

//     if (!empty($purchase->approval_level)) {
//         $rows = explode(',', $purchase->approval_level);

//         foreach ($rows as $row) {
//             if (strpos($row, '|') !== false) {

//                 list($uid, $dt) = explode('|', $row);

//                 $completed_approvals[] = [
//                     'user_id'  => (int)$uid,
//                     'level_id' => $this->get_user_level((int)$uid),
//                     'date'     => date('d-m-Y H:i', strtotime($dt))
//                 ];
//             }
//         }
//     }

//     /* ---------------------------------------------------
//       6️⃣ GET APPROVAL HIERARCHY
//     --------------------------------------------------- */
//     $hierarchy = $this->get_approval_hierarchy(
//         $added_by_user->level_id,
//         $purchase->added_by,
//         $purchase->company_id
//     );

//     /* ---------------------------------------------------
//       7️⃣ BUILD FINAL APPROVAL HISTORY VIEW
//     --------------------------------------------------- */
//     $level_counter = 1;

//     foreach ($hierarchy as $level_data) {

//         $level_id = $level_data['level_id'];

//         // FIND COMPLETED ENTRY FOR THIS LEVEL
//         $completed_entry = null;
//         foreach ($completed_approvals as $ca) {
//             if ($ca['level_id'] == $level_id) {
//                 $completed_entry = $ca;
//                 break;
//             }
//         }

//         // IF COMPLETED → SHOW ACTUAL APPROVER
//         if ($completed_entry) {

//             $approver = $this->db->select('first_name,last_name')
//                                  ->where('id', $completed_entry['user_id'])
//                                  ->get('users')->row();

//             $approver_name = $approver 
//                               ? $approver->first_name . ' ' . $approver->last_name 
//                               : 'Unknown';

//             $data['approval_levels'][] = [
//                 'title'  => 'Approval Level ' . $level_counter,
//                 'user'   => $approver_name,
//                 'branch' => $branch_name,
//                 'date'   => $completed_entry['date'],
//                 'status' => 'completed'
//             ];
//         }

//         // NOT COMPLETED → pending/current
//         else {

//             $data['approval_levels'][] = [
//                 'title'  => 'Approval Level ' . $level_counter,
//                 'user'   => 'Waiting Approval',
//                 'branch' => $branch_name,
//                 'date'   => null,
//                 'status' => ($level_counter == count($completed_approvals) + 1) 
//                                 ? 'current' 
//                                 : 'pending'
//             ];
//         }

//         $level_counter++;
//     }

//     echo json_encode(['success' => true, 'data' => $data]);
// }

public function get_approval_history()
{
    $purchase_id = $this->input->post('purchase_id');

    $purchase = $this->db->where('id', $purchase_id)->get('sale_requests')->row();
    $transport = $purchase;
    if (!$purchase) {
        echo json_encode(['success' => false, 'message' => 'Purchase not found']);
        return;
    }

    /* ---------------- Order Placed User ---------------- */
    $added_by_user = $this->db->select('u.id, u.first_name, u.last_name, u.level_id, cb.branch_name')
        ->from('users u')
        ->join('clients_branch cb', 'cb.id = u.clients_branch_id', 'left')
        ->where('u.id', $purchase->added_by)
        ->get()->row();

    if (!$added_by_user) {
        echo json_encode(['success' => false, 'message' => 'User who placed the order not found']);
        return;
    }

    /* ---------------- Purchase Branch ---------------- */
    $purchase_branch = 'Head Office';
    if ($purchase->branch_id) {
        $b = $this->db->select('branch_name')
            ->where('id', $purchase->branch_id)
            ->get('clients_branch')->row();
        if ($b) $purchase_branch = $b->branch_name;
    }

    /* ---------------- Base Data ---------------- */
    $data = [
        'added_by' => [
            'name'   => $added_by_user->first_name . ' ' . $added_by_user->last_name,
            'branch' => $purchase_branch,
            'date'   => date('d-m-Y H:i', strtotime($purchase->created_date))
        ],
        'approval_levels' => [],
        'final_status' => ($purchase->status == 'approved') ? 'Approved by OSS</small>'
                        : (($purchase->status == 'rejected') ? 'Rejected by OSS' : 'Pending for OSS'),
        'final_status_class' => ($purchase->status == 'approved') ? 'completed'
                        : (($purchase->status == 'rejected') ? 'rejected' : 'current')
    ];

    /* ---------------- Delivery Status Timeline ---------------- */
// $delivery_flow = [
//     'pending'               => 'Pending',
//     'approved'              => 'Approved',
//     'create_invoice'        => 'Invoice Created',
//     'courier'               => 'Courier',
//     'in_transit'            => 'In Transit',
//     'delivered'             => 'Delivered',
//     'awaiting_confirmation' => 'Awaiting Confirmation',
//     'delivery_confirmed'    => 'Delivery Confirmed'
// ];

// $current_status = $purchase->status;

// $delivery_timeline = [];
// $found_current = false;

// foreach ($delivery_flow as $key => $label) {

//     if ($key === $current_status) {
//         $delivery_timeline[] = [
//             'title'  => $label,
//             'status' => 'current'
//         ];
//         $found_current = true;
//     }
//     elseif (!$found_current) {
//         $delivery_timeline[] = [
//             'title'  => $label,
//             'status' => 'completed'
//         ];
//     }
    
    
//     else {
//         $delivery_timeline[] = [
//             'title'  => $label,
//             'status' => 'upcoming'
//         ];
//     }
// }

// /* attach to response */
// $data['delivery_timeline'] = $delivery_timeline;/

/* ---------------- Delivery Status Timeline ---------------- */
$delivery_flow = [
    'pending'               => 'Pending',
    'approved'              => 'Approved',
    'create_invoice'        => 'Invoice Created',
    'courier'               => 'Courier',
    'in_transit'            => 'In Transit',
    'delivered'             => 'Delivered',
    'awaiting_confirmation' => 'Awaiting Confirmation',
    'delivery_confirmed'    => 'Delivery Confirmed'
];

$current_status = $purchase->status;

$delivery_timeline = [];
$found_current = false;

foreach ($delivery_flow as $key => $label) {

    // ✅ ALWAYS define step first
    $step = [
        'title'  => $label,
        'status' => 'upcoming'
    ];

    // ✅ status logic
    if ($key === $current_status) {
         if ($current_status === 'delivery_confirmed') {
        $step['status'] = 'completed';
        $found_current = true;
    } else{
        $step['status'] = 'current';
        $found_current = true;}
    } elseif (!$found_current) {
        $step['status'] = 'completed';
    }

    // ✅ attach transport details ONLY for delivery-related steps
    if ($key === 'courier' && !empty($transport->delivery_mode)) {

    if ($transport->delivery_mode === 'courier') {
        $step['meta'] = [
            'mode'    => 'Courier',
            'partner' => $transport->courier_partner,
            'docket'  => $transport->docket_number,
            'date'    => $transport->courier_date,
            'link'    => $transport->tracking_link,
        ];
    }

    if ($transport->delivery_mode === 'physical') {
        $step['meta'] = [
            'mode'   => 'Physical',
            'person' => $transport->physical_person,
            'date'   => $transport->physical_date,
        ];
    }

    if ($transport->delivery_mode === 'vendor') {
        $step['meta'] = [
            'mode'   => 'Vendor',
            'vendor' => $transport->vendor_name,
            'city'   => $transport->vendor_city,
            'via'    => $transport->delivery_via,
            'link'   => $transport->vendor_tracking_link,
        ];
    }
}


    // ✅ PUSH STEP ONCE
    $delivery_timeline[] = $step;
}

/* attach to response */
$data['delivery_timeline'] = $delivery_timeline;



    /* ---------------- Completed Approvals ---------------- */
    $completed = [];
    if (!empty($purchase->approval_level)) {
        $rows = explode(',', $purchase->approval_level);
        foreach ($rows as $row) {
            if (strpos($row, '|') === false) continue; // skip invalid entries
            list($uid, $dt) = explode('|', $row);
            $completed[$this->get_user_level((int)$uid)] = [
                'user_id' => (int)$uid,
                'date'    => date('d-m-Y H:i', strtotime($dt))
            ];
        }
    }

    /* ---------------- Approval Hierarchy ---------------- */
    $hierarchy = $this->get_approval_hierarchy(
        $added_by_user->level_id,
        $purchase->added_by,
        $purchase->company_id
    );

    /* ---------------- Timeline Build ---------------- */
    $level_counter = 1;
    foreach ($hierarchy as $h) {

        if (isset($completed[$h['level_id']])) {

            $approver = $this->db->select('u.first_name,u.last_name,cb.branch_name')
                ->from('users u')
                ->join('clients_branch cb', 'cb.id = u.clients_branch_id', 'left')
                ->where('u.id', $completed[$h['level_id']]['user_id'])
                ->get()->row();

            $data['approval_levels'][] = [
                'title'  => 'Approval Level ' . $level_counter,
                'user'   => $approver->first_name . ' ' . $approver->last_name ?? 'Unknown',
                'branch' => $approver->branch_name ?? $purchase_branch,
                'date'   => $completed[$h['level_id']]['date'] ?? null,
                'status' => 'completed'
            ];
        } else {
            $data['approval_levels'][] = [
                'title'  => 'Approval Level ' . $level_counter,
                'user'   => 'Waiting Approval',
                'branch' => '',
                'date'   => null,
                'status' => 'pending'
            ];
        }

        $level_counter++;
    }

    echo json_encode(['success' => true, 'data' => $data]);
}

	
//  public function get_approval_history()
//  {
//     $purchase_id = $this->input->post('purchase_id');
    
//     $purchase = $this->db->where('id', $purchase_id)->get('sale_requests')->row();
    
//     if (!$purchase) {
//         echo json_encode(['success' => false, 'message' => 'Purchase not found']);
//         return;
//     }
    
//     $added_by_user = $this->db->select('first_name, last_name, level_id')
//                              ->where('id', $purchase->added_by)
//                              ->get('users')
//                              ->row();
                             
//     if ($purchase->branch_id!=NULL) {
//             $branch = $this->db->select('branch_name')
//                               ->where('id', $purchase->branch_id)
//                               ->get('clients_branch')
//                               ->row();
//             $branch_name = $branch ? $branch->branch_name : 'Unknown';
//         } else {
//             $branch_name = 'Head Office';
//         }

//     $data = [
//         'added_by' => [
//             'name' => $added_by_user ? $added_by_user->first_name . ' ' . $added_by_user->last_name : 'Unknown',
//             'branch' => $branch_name,
//             'date' => date('d-m-Y H:i', strtotime($purchase->created_date))
//         ],
        
//         'approval_levels' => [],
//         'current_status' => $purchase->order_status ? ucfirst(str_replace('_', ' ', $purchase->order_status)) : 'Pending',
//         'final_status' => ($purchase->status === NULL || $purchase->status === 'pending') 
//                             ? 'Pending for OSS' 
//                             : (($purchase->status === 'rejected') ? 'Rejected by OSS' : 'Approved'),
//         'final_status_class' => ($purchase->status === NULL || $purchase->status === 'pending') 
//                             ? 'current_status'     
//                             : (($purchase->status === 'rejected') ? 'rejected' : 'completed') 
//     ];

    
//     $completed_approvals = [];
//     if (!empty($purchase->approval_level)) {
//         $approvals = explode(',', $purchase->approval_level);
        
//         foreach ($approvals as $index => $approval) {
//             if (strpos($approval, '|') !== false) {
//                 list($user_id, $date) = explode('|', $approval);
                
//                 $user = $this->db->select('first_name, last_name')
//                                 ->where('id', $user_id)
//                                 ->get('users')
//                                 ->row();
                
//                 if ($user) {
//                     $completed_approvals[] = [
//                         'level_id' => $this->get_user_level($user_id), 
//                         'title'  => 'Approval Level ' . ($index + 1),
//                         'user'   => $user->first_name . ' ' . $user->last_name,
//                         'date'   => date('d-m-Y H:i', strtotime($date)),
//                         'status' => 'completed'
//                     ];
//                 }
//             }
//         }
//     }
    
//     $approval_hierarchy = $this->get_approval_hierarchy($added_by_user->level_id, $purchase->added_by, $purchase->company_id);
//new code...
    
//     $level_counter = 1;
//     foreach ($approval_hierarchy as $level_data) {

//     // Always use only the first approver
//     $first_approver = $level_data['approvers'][0];

//     // Get branch of that approver
//     $user_info = $this->db->select('clients_branch_id')
//                           ->where("CONCAT(first_name,' ',last_name) = ", $first_approver)
//                           ->get('users')
//                           ->row();

//     if ($user_info && !empty($user_info->clients_branch_id)) {
//         $branch = $this->db->select('branch_name')
//                           ->where('id', $user_info->clients_branch_id)
//                           ->get('clients_branch')
//                           ->row();
//         $approver_branch = $branch ? $branch->branch_name : 'Head Office';
//     } else {
//         $approver_branch = 'Head Office';
//     }

//     // Check completed approvals
//     $found_completed = false;
//     foreach ($completed_approvals as $completed) {
//         if ($completed['level_id'] == $level_data['level_id']) {
//             $data['approval_levels'][] = [
//                 'title'  => 'Approval Level ' . $level_counter,
//                 'user'   => $completed['user'],
//                 'branch' => $approver_branch,
//                 'date'   => $completed['date'],
//                 'status' => 'completed'
//             ];
//             $found_completed = true;
//             break;
//         }
//     }

//     // If not completed, show first approver only
//     if (!$found_completed) {
//         $data['approval_levels'][] = [
//             'title'  => 'Approval Level ' . $level_counter,
//             'user'   => $first_approver,
//             'branch' => $approver_branch,
//             'date'   => null,
//             'status' => ($level_counter == count($completed_approvals) + 1) ? 'current' : 'pending'
//         ];
//     }

//     $level_counter++;
// } new code ends here

     
//     echo json_encode(['success' => true, 'data' => $data]);
// }

private function get_user_level($user_id) {
    $user = $this->db->select('level_id')->where('id', $user_id)->get('users')->row();
    return $user ? $user->level_id : null;
}


private function get_approval_hierarchy($placed_by_level, $placed_by_user_id, $company_id) 
{
    $hierarchy = [];
    
    switch($placed_by_level) {
        case 0: 
            $approvers = $this->db->select('first_name, last_name')
                                 ->where('company_id', $company_id)
                                //  ->where('level_id', 0)
                                 ->where('id =', 1)
                                 ->get('users')
                                 ->result();
            $approver_names = [];
            foreach ($approvers as $approver) {
                $approver_names[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names)) {
                $hierarchy[] = [
                    'level_id' => 0,
                    'approvers' => $approver_names
                ];
            }
            break;
            
        case 1: // Needs Level 0 approval
            $approvers = $this->db->select('first_name, last_name')
                                 ->where('company_id', $company_id)
                                 ->where('level_id', 0)
                                 ->get('users')
                                 ->result();
            $approver_names = [];
            foreach ($approvers as $approver) {
                $approver_names[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names)) {
                $hierarchy[] = [
                    'level_id' => 0,
                    'approvers' => $approver_names
                ];
            }
            break;
            
        case 2: // Needs Level 1 → Level 0 approval
            // Level 1 approvers
            $approvers_l1 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 1)
                                  ->get('users')
                                  ->result();
            $approver_names_l1 = [];
            foreach ($approvers_l1 as $approver) {
                $approver_names_l1[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l1)) {
                $hierarchy[] = [
                    'level_id' => 1,
                    'approvers' => $approver_names_l1
                ];
            }
            
            // Level 0 approvers
            $approvers_l0 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 0)
                                  ->get('users')
                                  ->result();
            $approver_names_l0 = [];
            foreach ($approvers_l0 as $approver) {
                $approver_names_l0[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l0)) {
                $hierarchy[] = [
                    'level_id' => 0,
                    'approvers' => $approver_names_l0
                ];
            }
            break;
            
        case 3: // Needs Level 2 → Level 1 → Level 0 approval
            // Level 2 approvers
            $approvers_l2 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 2)
                                  ->get('users')
                                  ->result();
            $approver_names_l2 = [];
            foreach ($approvers_l2 as $approver) {
                $approver_names_l2[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l2)) {
                $hierarchy[] = [
                    'level_id' => 2,
                    'approvers' => $approver_names_l2
                ];
            }
            
            // Level 1 approvers
            $approvers_l1 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 1)
                                  ->get('users')
                                  ->result();
            $approver_names_l1 = [];
            foreach ($approvers_l1 as $approver) {
                $approver_names_l1[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l1)) {
                $hierarchy[] = [
                    'level_id' => 1,
                    'approvers' => $approver_names_l1
                ];
            }
            
            // Level 0 approvers
            $approvers_l0 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 0)
                                  ->get('users')
                                  ->result();
            $approver_names_l0 = [];
            foreach ($approvers_l0 as $approver) {
                $approver_names_l0[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l0)) {
                $hierarchy[] = [
                    'level_id' => 0,
                    'approvers' => $approver_names_l0
                ];
            }
            break;
            
        case 4: // Needs Level 3 → Level 2 → Level 1 → Level 0 approval
            // Level 3 approvers
            $approvers_l3 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 3)
                                  ->get('users')
                                  ->result();
            $approver_names_l3 = [];
            foreach ($approvers_l3 as $approver) {
                $approver_names_l3[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l3)) {
                $hierarchy[] = [
                    'level_id' => 3,
                    'approvers' => $approver_names_l3
                ];
            }
            
            // Level 2 approvers
            $approvers_l2 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 2)
                                  ->get('users')
                                  ->result();
            $approver_names_l2 = [];
            foreach ($approvers_l2 as $approver) {
                $approver_names_l2[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l2)) {
                $hierarchy[] = [
                    'level_id' => 2,
                    'approvers' => $approver_names_l2
                ];
            }
            
            // Level 1 approvers
            $approvers_l1 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 1)
                                  ->get('users')
                                  ->result();
            $approver_names_l1 = [];
            foreach ($approvers_l1 as $approver) {
                $approver_names_l1[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l1)) {
                $hierarchy[] = [
                    'level_id' => 1,
                    'approvers' => $approver_names_l1
                ];
            }
            
            // Level 0 approvers
            $approvers_l0 = $this->db->select('first_name, last_name')
                                  ->where('company_id', $company_id)
                                  ->where('level_id', 0)
                                  ->get('users')
                                  ->result();
            $approver_names_l0 = [];
            foreach ($approvers_l0 as $approver) {
                $approver_names_l0[] = $approver->first_name . ' ' . $approver->last_name;
            }
            if (!empty($approver_names_l0)) {
                $hierarchy[] = [
                    'level_id' => 0,
                    'approvers' => $approver_names_l0
                ];
            }
            break;
    }
    
    return $hierarchy;
}

/**
 * Get branch/user email - using users table
 */
private function get_branch_email($order)
{
    if ($order->level_id == 0) {
        $user = $this->db->select('email')->where('id', $order->added_by)->get('users')->row();
        return $user->email ?? null;
    } else {
        $branch_manager = $this->db->select('email')
            ->where('clients_branch_id', $order->branch_id)
            ->where('role', 'clients_branch_manager')
            ->get('users')->row();
        
        if ($branch_manager && !empty($branch_manager->email)) {
            return $branch_manager->email;
        }
        
        $user = $this->db->select('email')->where('id', $order->added_by)->get('users')->row();
        return $user->email ?? null;
    }
}


/**
 * Get user name (first_name + last_name) for ALL roles
 * Returns the name of the person who placed the order
 */
private function get_branch_name($order)
{
    // Get the user who ORIGINALLY placed the order
    $placed_by_user = $this->db->select('first_name, last_name')
        ->where('id', $order->added_by)
        ->get('users')
        ->row();
    
    // Return first_name + last_name for all users regardless of role
    if ($placed_by_user) {
        return trim($placed_by_user->first_name . ' ' . $placed_by_user->last_name);
    }
    
    return 'Customer';
}

/**
 * Build order items HTML for email
 */
private function build_order_items_html($items)
{
    if (empty($items)) return '<p>No items found</p>';
    
    $html = '<table style="width:100%; border-collapse: collapse;">
                <thead><tr style="background:#f2f2f2;">
                    <th style="border:1px solid #ddd;padding:8px;">Product</th>
                    <th style="border:1px solid #ddd;padding:8px;">Qty</th>
                    <th style="border:1px solid #ddd;padding:8px;">Price</th>
                    <th style="border:1px solid #ddd;padding:8px;">Total</th>
                </tr></thead><tbody>';
    
    foreach ($items as $item) {
        $html .= '<tr>
            <td style="border:1px solid #ddd;padding:8px;">' . htmlspecialchars($item->product_name) . '</td>
            <td style="border:1px solid #ddd;padding:8px;">' . $item->quantity . ' ' . ($item->uom_name ?? '') . '</td>
            <td style="border:1px solid #ddd;padding:8px;">₹' . number_format($item->price ?? 0, 2) . '</td>
            <td style="border:1px solid #ddd;padding:8px;">₹' . number_format($item->subtotal ?? 0, 2) . '</td>
        </tr>';
    }
    $html .= '</tbody></table>';
    return $html;
}

/**
 * Log email notification
 */
private function log_email_notification($order_id, $recipient, $status, $remarks, $delivery_status)
{
    $this->db->query("CREATE TABLE IF NOT EXISTS `email_notification_logs` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `order_id` int(11) NOT NULL,
        `recipient_email` varchar(255) NOT NULL,
        `notification_type` varchar(50) NOT NULL,
        `remarks` text,
        `status` enum('sent','failed') DEFAULT 'sent',
        `sent_at` datetime NOT NULL,
        `sent_by` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    $this->db->insert('email_notification_logs', [
        'order_id' => $order_id,
        'recipient_email' => $recipient,
        'notification_type' => $status,
        'remarks' => $remarks,
        'status' => $delivery_status,
        'sent_at' => date('Y-m-d H:i:s'),
        'sent_by' => $this->session->userdata('user_id')
    ]);
}

/**
 * Send fallback email - using user email
 */
private function send_fallback_status_email($order, $to_email, $status, $remarks, $company_name, $recipient_name = 'User')
{
    $color = ($status == 'approved') ? '#28a745' : '#dc3545';
    $status_upper = ucfirst($status);
    
    $html = '<html><body>
        <div style="max-width:600px;margin:0 auto;padding:20px;">
            <div style="background:' . $color . ';color:white;padding:15px;text-align:center;">
                <h2>Order ' . $status_upper . '</h2>
            </div>
            <div style="padding:20px;">
                <p>Dear ' . $recipient_name . ',</p>
                <p>Order <strong>#' . $order->reference_no . '</strong> has been <strong>' . $status_upper . '</strong>.</p>
                <p><strong>Date:</strong> ' . date('d-m-Y', strtotime($order->invoice_date)) . '</p>
                <p><strong>Total:</strong> ₹' . number_format($order->total, 2) . '</p>
                <div style="background:#fff3cd;padding:10px;margin:15px 0;"><strong>Remarks:</strong><br>' . nl2br(htmlspecialchars($remarks)) . '</div>
            </div>
            <div style="text-align:center;padding:15px;font-size:12px;color:#666;">This is an automated notification from ' . $company_name . '.</div>
        </div>
    </body></html>';
    
    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    if ($email_setup) {
        $this->email->initialize([
            'protocol' => 'smtp',
            'smtp_host' => $email_setup->host,
            'smtp_port' => $email_setup->port,
            'smtp_user' => $email_setup->username,
            'smtp_pass' => $email_setup->password,
            'smtp_crypto' => $email_setup->encryption,
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ]);
    }
    
    $this->email->clear();
    $this->email->from($email_setup->username ?? 'noreply@oss.com', $company_name);
    $this->email->to($to_email);
    $this->email->subject('Order ' . $status_upper . ' - ' . $order->reference_no);
    $this->email->message($html);
    
    return $this->email->send();
}

/**
 * Send status email to branch/user
 */
private function send_status_email($order_id, $status)
{
    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    $user_email = $this->get_branch_email($order);
    if (!$user_email) {
        log_message('error', "No user email found for order #{$order_id}");
        return false;
    }

    $recipient_name = $this->get_branch_name($order);
    $user = $this->db->get_where('users', ['id' => $order->added_by])->row();
    $company = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
    $company_name = $company->company_name ?? 'OSS System';

    $module = ($status == 'approved') ? 'order_approved' : 'order_rejected';
    $remarks = ($status == 'rejected') ? ($order->rejection_remarks ?? 'No specific reason provided.') : 'Your order has been approved.';
    
    $template = $this->email_template_model->get_record_by_module($module);
    
    if (!$template) {
        return $this->send_fallback_status_email($order, $user_email, $status, $remarks, $company_name, $recipient_name);
    }

    $subject = $template->subject;
    $body = $template->mail_header . $template->mail_body . $template->mail_footer;
    $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    $items_html = $this->build_order_items_html($order_items);

    $replacements = [
        '{{reference_no}}' => $order->reference_no,
        '{{recipient_name}}' => $recipient_name,
        '{{company_name}}' => $company_name,
        '{{remarks}}' => nl2br(htmlspecialchars($remarks)),
        '{{order_date}}' => date('d-m-Y', strtotime($order->invoice_date)),
        '{{total_amount}}' => number_format($order->total, 2),
        '{{status}}' => ucfirst($status),
        '{{order_items}}' => $items_html,
        '{{branch_name}}' => $recipient_name
    ];

    foreach ($replacements as $key => $value) {
        $body = str_replace($key, $value, $body);
        $subject = str_replace($key, $value, $subject);
    }

    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    if ($email_setup) {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $email_setup->host,
            'smtp_port' => $email_setup->port,
            'smtp_user' => $email_setup->username,
            'smtp_pass' => $email_setup->password,
            'smtp_crypto' => $email_setup->encryption,
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ];
        $this->email->initialize($config);
    }
    
    $this->email->clear();
    $this->email->from($template->from_email, $template->from_name);
    $this->email->to($user_email);
    
    if (!empty($user->email) && $user->email != $user_email) {
        $this->email->cc($user->email);
    }
    
    $this->email->subject($subject);
    $this->email->message($body);
    
    $sent = $this->email->send();
    $this->log_email_notification($order_id, $user_email, $status, $remarks, $sent ? 'sent' : 'failed');
    
    return $sent;
}


    public function delete_order()
       {
        $id = $this->input->post('id');
    
        if (!$id) {
            $this->session->set_flashdata('error', 'Invalid request.');
            redirect('Purchase_request');
        }
    
        $order = $this->db->get_where('sale_requests', ['id' => $id])->row();
    
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('Purchase_request');
        }
    
        if ($order->status !== NULL) {
            $this->session->set_flashdata('error', 'This order is approved or processed and cannot be deleted.');
            redirect('Purchase_request');
        }
    
        $this->db->trans_start();
    
        $this->db->where('sale_id', $id)->delete('sale_request_items');
        $this->db->where('id', $id)->delete('sale_requests');
    
        $this->db->trans_complete();
      $log_data = [
            "user_id"     => $user_id,
            "module"      => "sale Requests",
            "user_action" => 3,
            "description" => "User Has Deleted purchase request ID: " . $id
        ];
        $this->log_data_model->add_record($log_data);
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to delete the order. Please try again.');
        } else {
            $this->session->set_flashdata('success', 'Order deleted successfully.');
        }
    
        redirect('Purchase_request');
    }

    public function get_users_by_branch()
    {
        $branch_id     = $this->input->post('branch_id');
        $company_id    = $this->session->userdata('company_id');
        $login_user_id = $this->session->userdata('user_id');
    
     $level_users = $this->db->select('id')
        ->from('users')
        ->group_start()
            ->where('level_id', 1)
            ->or_where('level_id', 2)
        ->group_end()
        ->where('company_id', $company_id)
        ->where('clients_branch_id', $branch_id)
        ->get()
        ->result_array();
    
    
        $level1_ids = array_column($level1_users, 'id');
    
        $this->db->select('id, first_name')
            ->from('users')
            ->where('company_id', $company_id)
            ->where('clients_branch_id', $branch_id)
            ->group_start()
                ->where('added_by', $login_user_id);
    
        if (!empty($level1_ids)) {
            $this->db->or_where_in('added_by', $level1_ids);
        }
    
        $this->db->group_end();
        $users = $this->db->get()->result();
        echo json_encode($users);
    }

    public function get_branches_for_user()
    {
        $company_id    = $this->session->userdata('company_id');
        $login_user_id = $this->session->userdata('user_id');
        $level_id      = $this->session->userdata('level_id');
        $branch_id     = $this->session->userdata('clients_branch_id');
    
        $same_level_users = $this->db->select('id')
            ->from('users')
            ->where('company_id', $company_id)
            ->where('level_id', $level_id)
            ->where('clients_branch_id', $branch_id)
            ->get()
            ->result_array();
    
        $same_level_ids = array_column($same_level_users, 'id');
    
        $this->db->select('id, branch_name')
                 ->from('clients_branch')
                 ->where('company_id', $company_id)
                 ->group_start()
                     ->where('added_by', $login_user_id)     // added by current user
                     ->or_where_in('added_by', $same_level_ids) // added by same-level users in same branch/company
                 ->group_end();
    
        $branches = $this->db->get()->result();
    
        echo json_encode($branches);
    }

	public function update_password() 
	{
        $new_password     = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');
    
        if ($new_password !== $confirm_password) {
            $this->session->set_flashdata('error', 'New password and confirm password do not match.');
            redirect('change_password');
            return;
        }
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $user_id         = $this->session->userdata('user_id');
        $this->db->where('id', $user_id);
        $this->db->update('users', ['password' => $hashed_password]);
        $this->session->set_flashdata('success', 'Password updated successfully.');
        redirect('change_password');
   }
   
//     public function purchase_with_cart()
//     {
//         $user_id  = $this->session->userdata('user_id'); 
//         $customer = $this->db->select('pricing')
//                              ->where('id', $user_id) 
//                              ->get('users')
//                              ->row();
    
//         $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];
//         $productArray = [];
//         if (!empty($pricing_data)) {
//             $product_ids = array_keys($pricing_data);
//             $this->db->where_in('id', $product_ids);
//             $query = $this->db->get('product');
    
//             foreach ($query->result() as $product) {
//                 $product->price = $pricing_data[$product->id]['price']; 
    
//                 $uom = $this->db->select('name')->where('id', $product->uom_id)->get('uom')->row();
//                 $product->uom_name = $uom ? $uom->name : 'N/A';
//                 $category = $this->db->select('name')->where('id', $product->product_category_id)->get('product_category')->row();
//                 $product->category_name = $category ? $category->name : 'N/A';
//                 $productArray[] = (array) $product;
//             }
//         }
//         $data['productArray'] = $productArray;
//         $this->load->view('purchase_requests/add_cart', $data);
//   }

/*public function purchase_with_cart()
{
    $user_id = $this->session->userdata('user_id');
    
    // Get user's company
    $user = $this->db->select('company_id')
                    ->where('id', $user_id)
                    ->get('users')
                    ->row();
    
    if (!$user || !$user->company_id) {
        show_error('User has no company assigned');
    }
    
    $company_id = $user->company_id;
    
    // Get company pricing
    $company = $this->db->select('pricing')
                       ->where('id', $company_id)
                       ->get('clients_company')
                       ->row();
    
    if (!$company || empty($company->pricing)) {
        // No pricing data
        $data['productArray'] = [];
        $this->load->view('purchase_requests/add_cart', $data);
        return;
    }
    
    // Decode pricing
    $pricing_data = json_decode($company->pricing, true);
    
    // Get product IDs
    $product_ids = array_keys($pricing_data);
    $int_product_ids = array_map('intval', $product_ids);
    
    // Get products with details
    $this->db->select('p.*, u.name as uom_name, pc.name as category_name')
            ->from('product p')
            ->join('uom u', 'u.id = p.uom_id', 'left')
            ->join('product_category pc', 'pc.id = p.product_category_id', 'left')
            ->where_in('p.id', $int_product_ids)
            ->where('p.delete_status', 0)
            ->order_by('p.name', 'asc');
    
    $query = $this->db->get();
    $productArray = [];
    
    foreach ($query->result_array() as $product) {
        $product_id_str = (string)$product['id'];
        
        // Get price from company pricing
        $price = isset($pricing_data[$product_id_str]['price']) 
                ? (float)$pricing_data[$product_id_str]['price'] 
                : 0.00;
        
        $product['price'] = $price;
        $product['uom_name'] = $product['uom_name'] ?? 'N/A';
        $product['category_name'] = $product['category_name'] ?? 'Uncategorized';
        $product['product_image'] = !empty($product['product_image']) ? $product['product_image'] : 'default_product.jpg';
        
        $productArray[] = $product;
    }
    
    $data['productArray'] = $productArray;
    $this->load->view('purchase_requests/add_cart', $data);
}*/
public function purchase_with_cart()
{
    $user_id = $this->session->userdata('user_id');
    
    // Get user's company
    $user = $this->db->select('company_id')
                    ->where('id', $user_id)
                    ->get('users')
                    ->row();
    
    if (!$user || !$user->company_id) {
        show_error('User has no company assigned');
    }
    
    $company_id = $user->company_id;
    
    // Get company pricing
    $company = $this->db->select('pricing')
                       ->where('id', $company_id)
                       ->get('clients_company')
                       ->row();
    
    if (!$company || empty($company->pricing)) {
        // No pricing data
        $data['productArray'] = [];
        $this->load->view('purchase_requests/add_cart', $data);
        return;
    }
    
    // Decode pricing
    $pricing_data = json_decode($company->pricing, true);
    
    // Get product IDs
    $product_ids = array_keys($pricing_data);
    $int_product_ids = array_map('intval', $product_ids);
    
    // Get products with ALL details including UOM data AND TAX fields
    $this->db->select('
            p.*, 
            u.id as uom_id, 
            u.uom as uom_uom, 
            u.name as uom_name, 
            pc.name as category_name,
            pc.cgst,
            pc.sgst,
            pc.igst,
            pc.tax_id,
            pc.tax_type
        ')
        ->from('product p')
        ->join('uom u', 'u.id = p.uom_id', 'left')
        ->join('product_category pc', 'pc.id = p.product_category_id', 'left')
        ->where_in('p.id', $int_product_ids)
        ->where('p.delete_status', 0)
        ->order_by('p.name', 'asc');
    
    $query = $this->db->get();
    $productArray = [];
    
    foreach ($query->result_array() as $product) {
        $product_id_str = (string)$product['id'];
        
        // Get price from company pricing
        $price = isset($pricing_data[$product_id_str]['price']) 
                ? (float)$pricing_data[$product_id_str]['price'] 
                : 0.00;
        
        $product['price'] = $price;
        $product['uom_id'] = $product['uom_id'] ?? null;
        $product['uom_uom'] = $product['uom_uom'] ?? '';
        $product['uom_name'] = $product['uom_name'] ?? 'N/A';
        $product['category_name'] = $product['category_name'] ?? 'Uncategorized';
        $product['product_image'] = !empty($product['product_image']) ? $product['product_image'] : 'default_product.jpg';
        
        // ✅ ADD TAX FIELDS
        $product['cgst'] = $product['cgst'] ?? 0;
        $product['sgst'] = $product['sgst'] ?? 0;
        $product['igst'] = $product['igst'] ?? 0;
        $product['tax_id'] = $product['tax_id'] ?? null;
        $product['tax_type'] = $product['tax_type'] ?? 'intra';
        
        $productArray[] = $product;
    }
    
    $data['productArray'] = $productArray;
    $this->load->view('purchase_requests/add_cart', $data);
}

public function submit_cart_order()
{
    // Disable error reporting to prevent HTML output
    error_reporting(0);
    ini_set('display_errors', 0);
    
    // Set JSON header
    header('Content-Type: application/json');
    
    try {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            return;
        }

        $user_id    = $this->session->userdata('user_id');
        $company_id = $this->session->userdata('company_id');
        $level_id   = $this->session->userdata('level_id');
        $branch_id  = $this->session->userdata('clients_branch_id');

        // Get cart items from POST
        $cart_items = json_decode($this->input->post('cart_items'), true);
        
        if (empty($cart_items)) {
            echo json_encode([
                'success' => false,
                'message' => 'Cart is empty'
            ]);
            return;
        }

        // Get manager/parent info
        $role = $this->db->select('role, added_by')
            ->where('id', $user_id)
            ->get('users')
            ->row();

        if (!$role) {
            echo json_encode([
                'success' => false,
                'message' => 'User not found'
            ]);
            return;
        }

        $manager_id = $role->added_by;
        $parent_id = null;
        if (!empty($manager_id)) {
            $parent = $this->db->select('added_by')
                ->where('id', $manager_id)
                ->get('users')
                ->row();
            $parent_id = $parent ? $parent->added_by : null;
        }

        // Calculate totals
        $total_taxable_value = 0;
        $total_tax = 0;
        $grand_total = 0;

        foreach ($cart_items as $item) {
            $item_total = $item['price'] * $item['quantity'];
            $total_taxable_value += $item_total;
            
            // Calculate tax
            if (isset($item['tax_type']) && $item['tax_type'] == 'intra') {
                $cgst = (isset($item['cgst']) ? $item['cgst'] : 0) / 100;
                $sgst = (isset($item['sgst']) ? $item['sgst'] : 0) / 100;
                $tax_amount = $item_total * ($cgst + $sgst);
            } else {
                $igst = (isset($item['igst']) ? $item['igst'] : 0) / 100;
                $tax_amount = $item_total * $igst;
            }
            
            $total_tax += $tax_amount;
            $grand_total += ($item_total + $tax_amount);
        }

        // Begin transaction
        $this->db->trans_begin();

        // Determine tax type (intra/inter state)
        if ($level_id == 0) {
            $company = $this->db->select('state')
                ->from('clients_company')
                ->where('id', $company_id)
                ->get()
                ->row();
            $state_id = $company ? $company->state : null;
        } else {
            $branch = $this->db->select('state_id')
                ->from('clients_branch')
                ->where('id', $branch_id)
                ->get()
                ->row();
            $state_id = $branch ? $branch->state_id : null;
        }

        $company_setting = $this->db->select('state')
            ->from('company_setting')
            ->where('id', 1)
            ->get()
            ->row();
        
        $company_state = $company_setting ? $company_setting->state : null;
        $is_intra = ($state_id == $company_state);

        // Insert sale request header
        $sale_data = [
            'invoice_date'        => date('Y-m-d'),
            'warehouse_id'        => 1,
            'customer_id'         => 1,
            'reference_no'        => '',
            'total_taxable_value' => $total_taxable_value,
            'total'               => $grand_total,
            'total_tax'           => $total_tax,
            'total_discount'      => 0,
            'internal_note'       => $this->input->post('internal_note') ?? '',
            'external_note'       => $this->input->post('external_note') ?? '',
            'added_by'            => $user_id,
            'added_by_branch'     => $role->role,
            'branch_id'           => $branch_id,
            'manager_id'          => $manager_id,
            'parent_id'           => $parent_id,
            'company_id'          => $company_id,
            'level_id'            => $level_id,
            'order_status'        => ($level_id == 0 ? 'approved' : 'pending'),
            'status'              => 'pending',
            'created_date'        => date('Y-m-d H:i:s')
        ];

        if (!$this->db->insert('sale_requests', $sale_data)) {
            throw new Exception('Failed to insert sale request');
        }
        
        $sale_request_id = $this->db->insert_id();

        // Update reference number
        $reference_no = 'OSS' . $sale_request_id;
        $this->db->where('id', $sale_request_id)
            ->update('sale_requests', ['reference_no' => $reference_no]);

        // Insert sale request items - using data from cart which already has UOM info
        foreach ($cart_items as $item) {
            $item_total = $item['price'] * $item['quantity'];
            
            // Prepare item data with ALL required fields from cart
            $item_data = [
                'sale_id'         => $sale_request_id,
                'product_id'      => $item['id'],
                'product_name'    => $item['name'],
                'description'     => '',
                'quantity'        => $item['quantity'],
                'cost'            => $item['price'],
                'price'           => $item['price'],
                'taxable_value'   => $item_total,
                'subtotal'        => $item_total,
                'product_remark'  => $item['remark'] ?? '',
                'tax_id'          => $item['tax_id'] ?? null,
                // UOM fields - now coming from cart
                'uom_id'          => $item['uom_id'] ?? null,
                'uom_uom'         => $item['uom_uom'] ?? '',
                'uom_name'        => $item['uom_name'] ?? ''
            ];

            // Add tax details based on state
            if ($is_intra) {
                $item_data['cgst_tax'] = $item['cgst'] ?? 0;
                $item_data['sgst_tax'] = $item['sgst'] ?? 0;
                $item_data['igst_tax'] = 0;
                $item_data['cgst'] = $item['cgst'] ?? 0;
                $item_data['sgst'] = $item['sgst'] ?? 0;
                $item_data['igst'] = 0;
            } else {
                $item_data['igst_tax'] = $item['igst'] ?? 0;
                $item_data['cgst_tax'] = 0;
                $item_data['sgst_tax'] = 0;
                $item_data['igst'] = $item['igst'] ?? 0;
                $item_data['cgst'] = 0;
                $item_data['sgst'] = 0;
            }

            // Insert the item
            if (!$this->db->insert('sale_request_items', $item_data)) {
                $db_error = $this->db->error();
                throw new Exception('Failed to insert sale request item: ' . $db_error['message']);
            }
        }

        // Log activity
        $log_data = [
            "user_id"     => $user_id,
            "module"      => "purchase Request",
            "user_action" => 1,
            "description" => "User added cart purchase (ID: {$sale_request_id})",
            "data"        => json_encode(['cart_items' => $cart_items])
        ];
        $this->db->insert('log_data', $log_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'success' => false,
                'message' => 'Database transaction failed'
            ]);
        } else {
            $this->db->trans_commit();
             $this->session->set_flashdata('success', 'Sale request added successfully.');

            // Return success with redirect URL
            echo json_encode([
                'success' => true,
                'message' => 'Order placed successfully!',
               // 'redirect_url' => base_url('Purchase_request/view/' . base64_encode($sale_request_id))
                   'redirect_url' => base_url('purchase_request')  // ← Redirect to list page

               ]);
        }
        
    } catch (Exception $e) {
        $this->db->trans_rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Exception: ' . $e->getMessage()
        ]);
    }
}
// public function purchase_with_cart()
// {
//     $user_id  = $this->session->userdata('user_id'); 
//     echo '<pre>User ID: '; print_r($user_id); echo '</pre>';

//     $customer = $this->db->select('pricing')
//                          ->where('id', $user_id) 
//                          ->get('users')
//                          ->row();
//     echo '<pre>Customer Record: '; print_r($customer); echo '</pre>';

//     $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];
//     echo '<pre>Pricing Data: '; print_r($pricing_data); echo '</pre>';

//     $productArray = [];

//     if (!empty($pricing_data)) {
//         $product_ids = array_keys($pricing_data);
//         echo '<pre>Product IDs from Pricing: '; print_r($product_ids); echo '</pre>';

//         $this->db->where_in('id', $product_ids);
//         $query = $this->db->get('product');
//         echo '<pre>Products Query Result: '; print_r($query->result()); echo '</pre>';

//         foreach ($query->result() as $product) {
//             $product->price = $pricing_data[$product->id]['price']; 

//             $uom = $this->db->select('name')->where('id', $product->uom_id)->get('uom')->row();
//             $product->uom_name = $uom ? $uom->name : 'N/A';
//             $category = $this->db->select('name')->where('id', $product->product_category_id)->get('product_category')->row();
//             $product->category_name = $category ? $category->name : 'N/A';
//             $productArray[] = (array) $product;
//         }
//     } else {
//         echo '<p>No pricing data for this user.</p>';
//     }

//     echo '<pre>Final Product Array: '; print_r($productArray); echo '</pre>';

//     $data['productArray'] = $productArray;
//     $this->load->view('purchase_requests/add_cart', $data);
//     exit; // Stop here to see debug output
// }


    public function invoice_receipt($encoded_id)
    {
        $sale_id = $encoded_id;
    
        if (!is_numeric($sale_id)) {
            show_error("Invalid ID.");
        }
    
        if (!empty($_FILES['invoice_receipt']['name'])) {
            $config['upload_path']   = './uploads/receipts/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['max_size']      = 2048;
            $config['file_name']     = 'invoice_' . $sale_id . '_' . time();
    
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }
    
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload('invoice_receipt')) {
                $error = $this->upload->display_errors('', '');
                log_message('error', 'Invoice Upload Error: ' . $error);
                $this->session->set_flashdata('error', $error);
            } else {
                $upload_data = $this->upload->data();
                $file_path = 'uploads/receipts/' . $upload_data['file_name'];
    
                $data = ['invoice_receipt' => $file_path];
                $this->db->where('id', $sale_id);
                $this->db->update('sale_requests', $data);
                $this->session->set_flashdata('success', 'Invoice receipt uploaded successfully.');
             }
           } else {
             $this->session->set_flashdata('error', 'No file selected.');
          }
    
         redirect('Purchase_request/view/');
   }

  
	public function eway_bill()
	{
	    
	}
	
    /*public function edit($id) {
        
        // $user_id = $this->session->userdata('user_id');
        $admin_id = $this->session->userdata('user_id');

        
        $user = $this->db->select('role, state, clients_branch_id')
                         ->from('users')
                         ->where('id', $user_id)
                         ->get()
                         ->row();
        $state_id = null;
        if ($user->role === 'client') {
            $state_id = $user->state;
        } elseif ($user->role === 'clients_branch_manager') {
            $branch = $this->db->select('address')
                               ->from('clients_branch')
                               ->where('id', $user->clients_branch_id)
                               ->get()
                               ->row();
            $state_id = $branch->state ?? null;
        }
        $company_state = $this->db->select('state')
                                  ->from('company_setting')
                                  ->where('id', 1)
                                  ->get()
                                  ->row()
                                  ->state;
    
        $is_intra = $state_id == $company_state;
    
        $admin_id = $this->session->user_id; 
        $role = $this->db->select('role, added_by')
                         ->where('id', $admin_id)
                         ->get('users')
                         ->row();
    
        $customer = $this->db->select('pricing')
                             ->where('id', $admin_id)
                             ->get('users')
                             ->row();
        $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];
     
        $products = [];
        if (!empty($pricing_data)) {
            $product_ids = array_keys($pricing_data);
            $this->db->where_in('id', $product_ids);
            $query = $this->db->get('product');
    
            // foreach ($query->result() as $product) {
                
            //     // $product_id = (int)$product->id;
                
            //     // $product->price = $pricing_data[$product->id]['price']; 
                
            //     $product_id = (int)$product->id;
             
            //     $product->price = $pricing_data[$product->id]['price'];
                
            //     $products[] = $product;
                
            // }
            // var_dump($product_id, $product->price);
            //      die();
            
            foreach ($query->result() as $product) {

            $product_id = (string)$product->id;

        if (isset($pricing_data[$product_id])) {
            $product->price = $pricing_data[$product_id]['price'];
        }
            $products[] = $product;
            
            }
             
        }
        
        $decoded_id = base64_decode($id);
        $existing_purchase = $this->db->where('id', $decoded_id)->get('sale_requests')->row();
    
        if (!$existing_purchase) {
            $this->session->set_flashdata('error', 'Purchase not found');
            redirect('Purchase_request');
        }
        $data['products']         = $products;
        $data['purchase']         = $existing_purchase;
        $data['purchase_items']   = $this->db->where('sale_id', $decoded_id)->get('sale_request_items')->result();
        $data['taxes']            = $this->db->get('tax')->result();
        $data['tax_type']         = $is_intra ? 'intra' : 'inter';
        $data['company_settings'] = $this->db->get('company_setting')->row();
        $data['customers']        = $this->db->get('customer')->result();
        $data['warehouses']       = $this->db->get('warehouse')->result();
        $this->load->view('purchase_requests/edit', $data);
    }*/
/*
public function edit($id) {
    
    $admin_id = $this->session->userdata('user_id');
    
    $user = $this->db->select('role, state, clients_branch_id')
                     ->from('users')
                     ->where('id', $admin_id)
                     ->get()
                     ->row();
    
    $state_id = null;
    if ($user->role === 'client') {
        $state_id = $user->state;
    } elseif ($user->role === 'clients_branch_manager') {
        $branch = $this->db->select('address')
                           ->from('clients_branch')
                           ->where('id', $user->clients_branch_id)
                           ->get()
                           ->row();
        $state_id = $branch->state ?? null;
    }
    
    $company_state = $this->db->select('state')
                              ->from('company_setting')
                              ->where('id', 1)
                              ->get()
                              ->row()
                              ->state;
    
    $is_intra = $state_id == $company_state;
    
    $admin_id = $this->session->user_id; 
    $role = $this->db->select('role, added_by')
                     ->where('id', $admin_id)
                     ->get('users')
                     ->row();
    
    $customer = $this->db->select('pricing')
                         ->where('id', $admin_id)
                         ->get('users')
                         ->row();
    $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];
    
    $products = [];
    if (!empty($pricing_data)) {
        $product_ids = array_keys($pricing_data);
        $this->db->where_in('id', $product_ids);
        $query = $this->db->get('product');
        
        foreach ($query->result() as $product) {
            $product_id = (string)$product->id;
            if (isset($pricing_data[$product_id])) {
                $product->price = $pricing_data[$product_id]['price'];
            }
            $products[] = $product;
        }
    }
    
    $decoded_id = base64_decode($id);
    $existing_purchase = $this->db->where('id', $decoded_id)->get('sale_requests')->row();
    
    if (!$existing_purchase) {
        $this->session->set_flashdata('error', 'Purchase not found');
        redirect('Purchase_request');
    }
    
    // ✅ NEW CODE: Fetch additional information for display
    $added_by_user = $this->db->get_where('users', ['id' => $existing_purchase->added_by])->row();
    
    // Get company information
    $company = $this->db->get_where('clients_company', ['id' => $existing_purchase->company_id])->row();
    
    // Get branch information if exists
    $branch = null;
    if (!empty($existing_purchase->branch_id)) {
        $branch = $this->db->get_where('clients_branch', ['id' => $existing_purchase->branch_id])->row();
    }
    
    // Build customer information array
    $data['customer_info'] = (object)[
        'company_name'      => $company->company_name ?? 'N/A',
        'user_name'         => trim(($added_by_user->first_name ?? '') . ' ' . ($added_by_user->last_name ?? '')),
        'branch_name'       => $branch->branch_name ?? 'N/A',
        'branch_gstin'      => $branch->gstin ?? 'N/A',
        'billing_details'   => $branch->billing_details ?? ($company->address ?? 'N/A'),
        'phone'             => $added_by_user->phone ?? 'N/A',
        'email'             => $added_by_user->email ?? 'N/A',
        'user_role'         => $added_by_user->role ?? 'N/A',
    ];
    
    $data['products']         = $products;
    $data['purchase']         = $existing_purchase;
    $data['purchase_items']   = $this->db->where('sale_id', $decoded_id)->get('sale_request_items')->result();
    $data['taxes']            = $this->db->get('tax')->result();
    $data['tax_type']         = $is_intra ? 'intra' : 'inter';
    $data['company_settings'] = $this->db->get('company_setting')->row();
    $data['customers']        = $this->db->get('customer')->result();
    $data['warehouses']       = $this->db->get('warehouse')->result();
    
    $this->load->view('purchase_requests/edit', $data);
}*/
public function edit($id) {
    
    $admin_id = $this->session->userdata('user_id');
    
    $user = $this->db->select('role, state, clients_branch_id')
                     ->from('users')
                     ->where('id', $admin_id)
                     ->get()
                     ->row();
    
    $state_id = null;
    if ($user->role === 'client') {
        $state_id = $user->state;
    } elseif ($user->role === 'clients_branch_manager') {
        $branch = $this->db->select('address')
                           ->from('clients_branch')
                           ->where('id', $user->clients_branch_id)
                           ->get()
                           ->row();
        $state_id = $branch->state ?? null;
    }
    
    $company_state = $this->db->select('state')
                              ->from('company_setting')
                              ->where('id', 1)
                              ->get()
                              ->row()
                              ->state;
    
    $is_intra = $state_id == $company_state;
    
    $role = $this->db->select('role, added_by')
                     ->where('id', $admin_id)
                     ->get('users')
                     ->row();
    
    $customer = $this->db->select('pricing')
                         ->where('id', $admin_id)
                         ->get('users')
                         ->row();
    $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];
    
    $products = [];
    if (!empty($pricing_data)) {
        $product_ids = array_keys($pricing_data);
        $this->db->where_in('id', $product_ids);
        $query = $this->db->get('product');
        
        foreach ($query->result() as $product) {
            $product_id = (string)$product->id;
            if (isset($pricing_data[$product_id])) {
                $product->price = $pricing_data[$product_id]['price'];
            }
            $products[] = $product;
        }
    }
    
    $decoded_id = base64_decode($id);
    $existing_purchase = $this->db->where('id', $decoded_id)->get('sale_requests')->row();
    
    if (!$existing_purchase) {
        $this->session->set_flashdata('error', 'Purchase not found');
        redirect('Purchase_request');
    }
    
    // ✅ FETCH CUSTOMER DETAILS FROM SALE REQUEST
    $customer_details = null;
    
    // Try to get customer from sale_requests if customer_id exists
    if (!empty($existing_purchase->customer_id)) {
        $customer_details = $this->db->get_where('customer', ['id' => $existing_purchase->customer_id])->row();
    } 
    // If no customer_id, try to get from user who placed the order
    else if (!empty($existing_purchase->added_by)) {
        $placed_by_user = $this->db->get_where('users', ['id' => $existing_purchase->added_by])->row();
        
        if ($placed_by_user && !empty($placed_by_user->company_id)) {
            // Get company details
            $company = $this->db->get_where('clients_company', ['id' => $placed_by_user->company_id])->row();
            
            // Get branch details if exists
            $branch = null;
            if (!empty($existing_purchase->branch_id)) {
                $branch = $this->db->get_where('clients_branch', ['id' => $existing_purchase->branch_id])->row();
            }
            
            // Create customer object from company/branch data
            $customer_details = (object)[
                'customer_name' => $branch->branch_name ?? $company->company_name ?? 'N/A',
                'gstin' => $branch->gstin ?? $company->gstin ?? 'N/A',
                'email' => $placed_by_user->email ?? 'N/A',
                'phone' => $placed_by_user->phone ?? 'N/A',
                'address' => $branch->address ?? $company->address ?? 'N/A',
                'city_name' => $branch->city_name ?? $company->city_name ?? 'N/A',
                'state_name' => $branch->state_name ?? $company->state_name ?? 'N/A',
                'country_name' => $branch->country_name ?? $company->country_name ?? 'N/A',
                'pincode' => $branch->pincode ?? $company->pincode ?? 'N/A',
            ];
        }
    }
    
    // If still no customer details, try to get from user table directly
    if (!$customer_details && !empty($existing_purchase->added_by)) {
        $user_details = $this->db->get_where('users', ['id' => $existing_purchase->added_by])->row();
        if ($user_details) {
            $customer_details = (object)[
                'customer_name' => trim($user_details->first_name . ' ' . $user_details->last_name),
                'gstin' => $user_details->gstin ?? 'N/A',
                'email' => $user_details->email ?? 'N/A',
                'phone' => $user_details->phone ?? 'N/A',
              
            ];
        }
    }
    
    // ✅ Fetch user who placed the order
    $placed_by_user = $this->db->get_where('users', ['id' => $existing_purchase->added_by])->row();
    
    // ✅ Fetch company information
    $company = $this->db->get_where('clients_company', ['id' => $existing_purchase->company_id])->row();
    
    // ✅ Fetch branch information
    $branch = null;
    if (!empty($existing_purchase->branch_id)) {
        $branch = $this->db->get_where('clients_branch', ['id' => $existing_purchase->branch_id])->row();
    }
    
    // ✅ Get branch manager/contact person details
    $branch_manager = null;
    if (!empty($existing_purchase->branch_id)) {
        $branch_manager = $this->db
            ->select('first_name, last_name, phone, email')
            ->where('clients_branch_id', $existing_purchase->branch_id)
            ->where('role', 'clients_branch_manager')
            ->get('users')
            ->row();
    }
    
    // ✅ Build branch contact details
    $data['branch_details'] = (object)[
        'branch_name'        => $branch->branch_name ?? 'N/A',
        'contact_person'     => $branch_manager ? trim($branch_manager->first_name . ' ' . $branch_manager->last_name) : 'N/A',
        'contact_no'         => $branch_manager->phone ?? ($placed_by_user->phone ?? 'N/A'),
        'email'              => $branch_manager->email ?? ($placed_by_user->email ?? 'N/A'),
        'address'            => $branch->address ?? $company->address ?? 'N/A',
        'gstin'              => $branch->gstin ?? $company->gstin ?? 'N/A',
    ];
    
    // ✅ Build customer personal details
    $data['customer_details'] = (object)[
        'customer_name' => $customer_details->customer_name ?? 'N/A',
        'gstin' => $customer_details->gstin ?? 'N/A',
        'email' => $customer_details->email ?? 'N/A',
        'phone' => $customer_details->phone ?? 'N/A',
       
    ];
    
    // ✅ Build order information with company name and user name
    $data['order_info'] = (object)[
        'po_no'              => $existing_purchase->reference_no,
        'po_date'            => date('d-m-Y', strtotime($existing_purchase->invoice_date)),
        'status'             => $existing_purchase->status,
        'order_status'       => $existing_purchase->order_status,
        'total'              => $existing_purchase->total,
        'total_taxable_value'=> $existing_purchase->total_taxable_value,
        'total_tax'          => $existing_purchase->total_tax,
        'company_name'       => $company->company_name ?? 'N/A',  // ✅ Company Name
        'user_name'          => trim(($placed_by_user->first_name ?? '') . ' ' . ($placed_by_user->last_name ?? '')),  // ✅ User Name
    ];
    
    $data['products']         = $products;
    $data['purchase']         = $existing_purchase;
    $data['purchase_items']   = $this->db->where('sale_id', $decoded_id)->get('sale_request_items')->result();
    $data['taxes']            = $this->db->get('tax')->result();
    $data['tax_type']         = $is_intra ? 'intra' : 'inter';
    $data['company_settings'] = $this->db->get('company_setting')->row();
    $data['customers']        = $this->db->get('customer')->result();
    $data['warehouses']       = $this->db->get('warehouse')->result();
    $data['level_id']         = $this->session->userdata('level_id');
    
    $this->load->view('purchase_requests/edit', $data);
}
public function update_purchase_order($id) {
    $decoded_id = base64_decode($id);

    $existing_purchase = $this->db->where('id', $decoded_id)->get('sale_requests')->row();
    if (!$existing_purchase) {
        $this->session->set_flashdata('error', 'Purchase request not found');
        redirect('Purchase_request');
    }

    $admin_id = $this->session->userdata('user_id');
    $user = $this->db->select('role, state, clients_branch_id')
                     ->from('users')
                     ->where('id', $admin_id)
                     ->get()
                     ->row();

    $state_id = null;
    if ($user->role === 'client') {
        $state_id = $user->state;
    } elseif ($user->role === 'clients_branch_manager') {
        $branch = $this->db->select('address')
                           ->from('clients_branch')
                           ->where('id', $user->clients_branch_id)
                           ->get()
                           ->row();
        $state_id = $branch->state ?? null;
    }

    $company_state = $this->db->select('state')
                              ->from('company_setting')
                              ->where('id', 1)
                              ->get()
                              ->row()
                              ->state;

    $is_intra = $state_id == $company_state;
    $is_client = ($user->role === 'client');
    $clients_branch = $this->db->select('clients_branch_id')
                               ->where('id', $admin_id)
                               ->get('users')
                               ->row();
    $role = $this->db->select('role, added_by')
                     ->where('id', $admin_id)
                     ->get('users')
                     ->row();

    $clients_branch_id = $clients_branch->clients_branch_id ?? null;

    $sale_data = [
        'invoice_date'        => date('Y-m-d'),
        'warehouse_id'        => 1,
        'customer_id'         => 1,
        'reference_no'        => $this->input->post('reference_no'),
        'total_taxable_value' => $this->input->post('total_taxable_value'),
        'total'               => $this->input->post('total'),
        'total_tax'           => $this->input->post('total_tax'),
        'total_discount'      => $this->input->post('total_discount'),
        'internal_note'       => $this->input->post('internal_note'),
        'external_note'       => $this->input->post('external_note'),
        'status'              => $existing_purchase->status,
        // 'order_status'        => $existing_purchase->order_status,
    ];

    $sale_items_array = explode('|', $this->input->post('purchase_items'));

    $this->db->trans_begin();

    $this->db->where('id', $decoded_id)->update('sale_requests', $sale_data);

    // Get existing items
    $existing_items = $this->db->where('sale_id', $decoded_id)->get('sale_request_items')->result();
    $existing_map = [];
    foreach ($existing_items as $row) {
        $existing_map[$row->product_id] = $row;
    }

    $incoming_product_ids = [];

    foreach ($sale_items_array as $item_json) {
        $item = (array)json_decode($item_json);
        $product_id = $item['product_id'];
        $incoming_product_ids[] = $product_id;

        $product = $this->db->select('uom_id')->where('id', $product_id)->get('product')->row();
        $uom = $product && $product->uom_id ? $this->db->select('uom, name')->where('id', $product->uom_id)->get('uom')->row() : null;

        $item_data = [
            'sale_id'         => $decoded_id,
            'product_id'      => $product_id,
            'product_name'    => $item['product_name'],
            'description'     => $item['description'],
            'quantity'        => $item['quantity'],
            'cost'            => $item['cost'],
            'price'           => $item['price'],
            'discount_id'     => $item['discount_id'],
            'discount_type'   => $item['discount_type'],
            'discount_value'  => $item['discount_value'],
            'discount_amount' => $item['discount_amount'],
            'taxable_value'   => $item['taxable_value'],
            'tax_type'        => $item['tax_type'],
            'subtotal'        => $item['sub_total'],
            'mfg_date'        => !empty($item['mfg_date']) ? $item['mfg_date'] : null,
            'expiry_date'     => !empty($item['expiry_date']) ? $item['expiry_date'] : null,
            'batch_no'        => !empty($item['batch_no']) ? $item['batch_no'] : null,
            'free_quantity'   => !empty($item['free_quantity']) ? $item['free_quantity'] : 0,
            'uom_id'          => $product->uom_id ?? null,
            'uom_uom'         => $uom->uom ?? '',
            'uom_name'        => $uom->name ?? ''
        ];

        $tax_id = $this->db->select('product_category.tax_id')
                           ->from('product')
                           ->join('product_category', 'product.product_category_id = product_category.id', 'left')
                           ->where('product.id', $product_id)
                           ->get()
                           ->row('tax_id');
        $item_data['tax_id'] = $tax_id;

        if (!empty($tax_id)) {
            $tax = $this->db->select('igst, cgst, sgst')->where('id', $tax_id)->get('tax')->row();
            $tax_amount = $item['sub_total'] - $item['taxable_value'];

            if ($is_intra) {
                $item_data['cgst_tax'] = $tax->cgst;
                $item_data['sgst_tax'] = $tax->sgst;
                $item_data['cgst']     = $tax_amount / 2;
                $item_data['sgst']     = $tax_amount / 2;
                $item_data['igst_tax'] = 0;
                $item_data['igst']     = 0;
            } else {
                $item_data['igst_tax'] = $tax->igst;
               // $item_data['igst']     = $tax_amount;
                   $item_data['igst'] = $tax_amount;  // ← This is correct
 
               $item_data['cgst_tax'] = 0;
                $item_data['sgst_tax'] = 0;
                $item_data['cgst']     = 0;
                $item_data['sgst']     = 0;
            }
        } else {
            $item_data['igst_tax'] = 0;
            $item_data['cgst_tax'] = 0;
            $item_data['sgst_tax'] = 0;
            $item_data['igst']     = 0;
            $item_data['cgst']     = 0;
            $item_data['sgst']     = 0;
        }

        if (isset($existing_map[$product_id])) {
            $this->db->where('id', $existing_map[$product_id]->id)->update('sale_request_items', $item_data);
        } else {
            $this->db->insert('sale_request_items', $item_data);
        }
    }

    foreach ($existing_map as $product_id => $existing_item) {
        if (!in_array($product_id, $incoming_product_ids)) {
            $this->db->where('id', $existing_item->id)->delete('sale_request_items');
        }
    }

    if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('failure', 'Failed to update purchase request: ' . $this->db->error()['message']);
    } else {
        $this->db->trans_commit();
        $this->session->set_flashdata('edited_purchase_id', $decoded_id);

        $this->session->set_flashdata('success', 'Purchase request updated successfully.');
    }
   // redirect('Purchase_request');
        redirect('Purchase_request/edit/' . $id);

    }

public function update_purchase($id) {
    $decoded_id = base64_decode($id);
    $this->form_validation->set_rules('invoice_no', 'Invoice No', 'trim|required');
    $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'trim|required');
    
    if ($this->form_validation->run() == TRUE) {
        $sale_request_data = array(
            'reference_no' => $this->input->post('invoice_no'),
            'invoice_date' => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
            'total'        => $this->input->post('total'),
            'total_taxable_value' => $this->input->post('total_taxable_value'),
            'total_tax' => $this->input->post('total_tax'),
            'external_note' => $this->input->post('external_note'),
            'terms_and_condition' => $this->input->post('terms_and_condition'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $decoded_id)->update('sale_requests', $sale_request_data);
        
        $this->db->where('sale_request_id', $decoded_id)->delete('sale_requests_items');
        
        $items = explode('|', $this->input->post('purchase_items'));
        foreach($items as $item) {
            if(!empty($item)) {
                $item_data = json_decode($item, true);
                
                $item_insert = array(
                    'sale_request_id' => $decoded_id,
                    'product_id'      => $item_data['product_id'],
                    'product_name'    => $item_data['product_name'],
                    'quantity'        => $item_data['quantity'],
                    'cost'            => $item_data['cost'],
                    'price'           => $item_data['price'],
                    'selling_price'   => $item_data['selling_price'],
                    'taxable_value'   => $item_data['taxable_value'],
                    'discount_id'     => $item_data['discount_id'],
                    'discount_type'   => $item_data['discount_type'],
                    'discount_value'  => $item_data['discount_value'],
                    'discount_amount' => $item_data['discount_amount'],
                    'tax_id'          => $item_data['tax_id'],
                    'igst'            => $item_data['igst'],
                    'igst_tax'        => $item_data['igst_tax'],
                    'cgst'            => $item_data['cgst'],
                    'cgst_tax'        => $item_data['cgst_tax'],
                    'sgst'            => $item_data['sgst'],
                    'sgst_tax'        => $item_data['sgst_tax'],
                    'subtotal'        => $item_data['subtotal'],
                    'free_quantity'   => $item_data['free_quantity']
                );
                
                $this->db->insert('sale_requests_items', $item_insert);
            }
        }
        $this->session->set_flashdata('success', 'Purchase updated successfully');
        redirect('Purchase_request');
    } else {
        $this->edit($id);
    }
}
	
public function view_admin_request($id)
{
    echo "View Admin Request: " . $id; // Debugging line
    if ($id != null) {
        $id = base64_decode($id);
        if (empty($id)) {
            $this->session->set_flashdata('failure', 'Invalid or broken URL.');
            redirect('purchase_request', 'refresh');
        }

        $sale = $this->db->get_where('sale_requests', ['id' => $id])->row();

       if ($sale != null) {
    $data['sale'] = $sale;

    $this->db->where('sale_id', $sale->id);
    $data['sale_items'] = $this->db->get('sale_request_items')->result();

    if ($sale->level_id == 0) {
        $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
       $user   = $this->db->get_where('users', ['id' => $sale->added_by])->row();
        $data['customer'] = (object)[
            'first_name' => $user->first_name ?? '',
            'last_name'  => $user->last_name ?? '',
            'phone'      => $user->phone ?? 'N/A',
            'company_name' => $company->company_name ?? 'N/A',
            'gstin'        => $company->gstin ?? 'N/A',
            // 'address'      => $company->address ?? 'N/A',
            'billing_details'  => $company->address ?? 'N/A',
            'shipping_details' => $company->shipping_address ?? 'N/A',
        ];

    } else {
        $branch = $this->db->get_where('clients_branch', ['id' => $sale->branch_id])->row();
        $user   = $this->db->get_where('users', ['id' => $sale->added_by])->row();

        $company_name = '';
        if (!empty($user->company_id)) {
            $company = $this->db->get_where('clients_company', ['id' => $user->company_id])->row();
            $company_name = $company->company_name ?? '';
        }

        $data['customer'] = (object)[
             'first_name'      => $user->first_name ?? '',
            'last_name'        => $user->last_name ?? '',
            'phone'            => $user->phone ?? 'N/A',
            'company_name'     => ($company->company_name ?? ''),
            'company_gst'      => $company->gstin ?? 'N/A',
            'company_add'      => $company->address ?? 'N/A',
            'gstin'            => $branch->gstin ?? 'N/A',
            'address'          => $user->address ?? 'N/A',
            'branch'           => $branch->branch_name  ?? 'N/A',
            'billing_details'  => $branch->billing_details ?? 'N/A',
            'shipping_details' => $branch->shipping_details ?? 'N/A',
        ];
    }
            $data['company_setting'] = $this->company_settings_model->get_company_records();
           
            $this->load->view('Sales_request/invoice', $data);
        } else {
            $this->session->set_flashdata('failure', 'You have tried to access a broken or unauthorized URL.');
            redirect('purchase_request', 'refresh');
        }
    } else {
        $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
        redirect('purchase_request', 'refresh');
    }
}


public function get_product_tax_details() 
{  
    $product_id = $this->input->post('product_id');
    $user_id    = $this->session->userdata('user_id'); 

    $user = $this->db->select('role, state, clients_branch_id')
                     ->from('users')
                     ->where('id', $user_id)
                     ->get()
                     ->row();

    $state_id = null;
    if ($user->role === 'client') {
        $state_id = $user->state;
    } elseif ($user->role === 'clients_branch_manager') {
        $branch = $this->db->select('address')
                           ->from('clients_branch')
                           ->where('id', $user->clients_branch_id)
                           ->get()
                           ->row();
        $state_id = $branch->state ?? null;
    }

    $company_state = $this->db->select('state')
                              ->from('company_setting')
                              ->where('id', 1)
                              ->get()
                              ->row()
                              ->state;

    $is_intra = $state_id == $company_state;

  $product = $this->db->select('
        p.id as product_id, 
        p.name, 
        p.uom_id, 
        p.price, 
        pc.tax_id, 
        pc.cgst, 
        pc.sgst, 
        pc.igst
    ')
    ->from('product p')
    ->join('product_category pc', 'p.product_category_id = pc.id')
    ->where('p.id', $product_id)
    ->get()
    ->row_array();


         if ($product) {
             $price = 0;
if (isset($pricing_data[$product_id]['price'])) {
    $price = (float)$pricing_data[$product_id]['price'];
}

$result = [
    'status' => 'success',
    'product' => [
        'id'       => $product['product_id'],
        'name'     => $product['name'],
        'uom_id'   => $product['uom_id'],
        'price'    => $price,   // ✅ company-wise price
        'tax_id'   => $product['tax_id'],
        'cgst'     => $is_intra ? $product['cgst'] : 0,
        'sgst'     => $is_intra ? $product['sgst'] : 0,
        'igst'     => !$is_intra ? $product['igst'] : 0,
        'tax_type' => $is_intra ? 'intra' : 'inter',
    ],
];
        // $result = [
        //     'status' => 'success',
        //     'product' => [
        //         'id' => $product['product_id'],
        //         'name' => $product['name'],
        //         'uom_id' => $product['uom_id'],
        //         'price' => $product['price'],
        //         'tax_id' => $product['tax_id'],
        //         'cgst' => $is_intra ? $product['cgst'] : 0,  // Only show CGST for intra-state
        //         'sgst' => $is_intra ? $product['sgst'] : 0,  // Only show SGST for intra-state
        //         'igst' => !$is_intra ? $product['igst'] : 5, // Only show IGST for inter-state
        //         'tax_type' => $is_intra ? 'intra' : 'inter', // Tax type indicator
        //     ],
        // ];
    }
        else {
                $result = ['status' => 'error', 'message' => 'Product not found'];
            }
        
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    
    public function reject_purchase()
    {
    $id = $this->input->post('id');

    if (!$id) {
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect('purchase_request');
    }

    $this->db->where('id', $id)->update('sale_requests', ['order_status' => 'rejected','status'=> 'rejected' ]);
   $log_data = [
            "user_id"     => $user_id,
            "module"      => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Rejected purchase request ID: " . $id
        ];
        $this->log_data_model->add_record($log_data);
    if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', 'Purchase request rejected successfully.');
    } else {
        $this->session->set_flashdata('error', 'Could not reject the request. It might already be rejected.');
    }

    redirect('Sales_request');
    }
    
 /* public function reject_purchase_req()
    {
    $id = $this->input->post('id');

    if (!$id) {
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect('purchase_request');
    }

    $this->db->where('id', $id)->update('sale_requests', ['order_status' => 'rejected']);
    $log_data = [
            "user_id"     => $user_id,
            "module"      => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Rejected purchase request ID: " . $id
        ];
        $this->log_data_model->add_record($log_data);
    if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', 'Purchase request rejected successfully.');
    } else {
        $this->session->set_flashdata('error', 'Could not reject the request. It might already be rejected.');
    }

    redirect('Sales_request');
    }*/
    
    public function get_rejection_remark()
{
    $id = $this->input->post('id');
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        return;
    }
    
    $purchase = $this->db->select('rejection_remarks, reference_no')
                         ->where('id', $id)
                         ->get('sale_requests')
                         ->row();
    
    if ($purchase) {
        echo json_encode([
            'success' => true,
            'remark' => $purchase->rejection_remarks ?? 'No remark provided',
            'reference_no' => $purchase->reference_no
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Purchase not found']);
    }
}

/*public function reject_purchase_req()
{
    $id = $this->input->post('id');
    $rejection_remark = $this->input->post('rejection_remark');
    
    if (!$id) {
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect('Purchase_request');
    }
    
    if (empty($rejection_remark)) {
        $this->session->set_flashdata('error', 'Please provide a reason for rejection.');
        redirect('Purchase_request');
    }
    
    $update_data = [
        'order_status' => 'rejected',
        'status' => 'rejected',
        'rejection_remarks' => $rejection_remark,
        'rejected_at' => date('Y-m-d H:i:s'),
        'rejected_by' => $this->session->userdata('user_id')
    ];
    
    $this->db->where('id', $id)->update('sale_requests', $update_data);
    
    $log_data = [
        "user_id" => $this->session->userdata('user_id'),
        "module" => "sale Requests",
        "user_action" => 1,
        "description" => "User Has Rejected purchase request ID: " . $id . " with remark: " . $rejection_remark
    ];
    $this->log_data_model->add_record($log_data);
    
    if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', 'Purchase request rejected successfully.');
    } else {
        $this->session->set_flashdata('error', 'Could not reject the request. It might already be rejected.');
    }
    
    redirect('Purchase_request');
}*/

/*public function reject_purchase_req()
{
    // Set JSON header for AJAX requests
    if ($this->input->is_ajax_request()) {
        header('Content-Type: application/json');
    }
    
    $id = $this->input->post('id');
    $rejection_remark = $this->input->post('rejection_remark');
    
    // Debug logging (remove in production)
    error_log("Reject request - ID: $id, Remark: $rejection_remark");
    
    if (!$id) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request. No ID provided.']);
            return;
        }
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect('Purchase_request');
        return;
    }
    
    if (empty($rejection_remark)) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Please provide a reason for rejection.']);
            return;
        }
        $this->session->set_flashdata('error', 'Please provide a reason for rejection.');
        redirect('Purchase_request');
        return;
    }
    
    // Check if the purchase request exists
    $purchase = $this->db->select('id, status, order_status')
                         ->where('id', $id)
                         ->get('sale_requests')
                         ->row();
    
    if (!$purchase) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Purchase request not found.']);
            return;
        }
        $this->session->set_flashdata('error', 'Purchase request not found.');
        redirect('Purchase_request');
        return;
    }
    
    // Check if already rejected
    if ($purchase->order_status === 'rejected') {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'This purchase request is already rejected.']);
            return;
        }
        $this->session->set_flashdata('error', 'This purchase request is already rejected.');
        redirect('Purchase_request');
        return;
    }
    
    // Begin transaction
    $this->db->trans_begin();
    
    try {
        $update_data = [
            'order_status' => 'rejected',
            'status' => 'rejected',
            'rejection_remarks' => $rejection_remark,
           // 'rejected_at' => date('Y-m-d H:i:s'),
            // 'rejected_by' => $this->session->userdata('user_id')
        ];
        
        $this->db->where('id', $id)->update('sale_requests', $update_data);
        
        if ($this->db->affected_rows() == 0) {
            throw new Exception('No rows affected. Update may have failed.');
        }
        
        // Log the rejection
        $log_data = [
            "user_id" => $this->session->userdata('user_id'),
            "module" => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Rejected purchase request ID: " . $id . " with remark: " . $rejection_remark
        ];
        $this->log_data_model->add_record($log_data);
        
        $this->db->trans_commit();
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => true, 'message' => 'Purchase request rejected successfully.']);
            return;
        }
        
        $this->session->set_flashdata('success', 'Purchase request rejected successfully.');
        redirect('Purchase_request');
        
    } catch (Exception $e) {
        $this->db->trans_rollback();
        error_log("Rejection error: " . $e->getMessage());
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            return;
        }
        
        $this->session->set_flashdata('error', 'Could not reject the request. Error: ' . $e->getMessage());
        redirect('Purchase_request');
    }
}
  
public function approve_purchase() {
    $id = $this->input->post('id');

    if (!$id) {
        echo json_encode(['success' => false]);
        return;
    }

    $user_id  = $this->session->userdata('user_id');
    $level_id = $this->session->userdata('level_id');
    $today    = date('Y-m-d H:i:s'); 

    $existing = $this->db->select('approval_level')
                         ->where('id', $id)
                         ->get('sale_requests')
                         ->row();

    $approval_array = [];
    if (!empty($existing->approval_level)) {
        $approval_array = explode(',', $existing->approval_level);
    }

    $alreadyApproved = false;
    foreach ($approval_array as $entry) {
        list($uid, $date) = explode('|', $entry);
        if ($uid == $user_id) {
            $alreadyApproved = true;
            break;
        }
    }

    if (!$alreadyApproved) {
        $approval_array[] = $user_id . '|' . $today;
    }

    $approval_string = implode(',', $approval_array);

    if ($level_id == 1 || $level_id == 2 || $level_id == 3) {
        $update_data = [
            'approved_by'     => $user_id,
            'approval_level'  => $approval_string,
            'order_status'    => 'partially_approved',
            'status'          => 'pending'
        ];
    } elseif ($level_id == 0) {
        $update_data = [
            'approved_by'     => $user_id,
            'approval_level'  => $approval_string,
            'order_status'    => 'approved',
            'status'          => 'pending'
        ];
    } else {
        $update_data = [];
    }

    if (!empty($update_data)) {
        $this->db->where('id', $id)->update('sale_requests', $update_data);
    }

    if ($this->db->affected_rows() > 0) {
        $log_data = [
            "user_id"     => $user_id,
            "module"      => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Approved purchase request ID: " . $id
        ];
        $this->log_data_model->add_record($log_data);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
*/
public function reject_purchase_req()
{
    if ($this->input->is_ajax_request()) {
        header('Content-Type: application/json');
    }
    
    $id = $this->input->post('id');
    $rejection_remark = $this->input->post('rejection_remark');
    
    if (!$id) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request. No ID provided.']);
            return;
        }
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect('Purchase_request');
        return;
    }
    
    if (empty($rejection_remark)) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Please provide a reason for rejection.']);
            return;
        }
        $this->session->set_flashdata('error', 'Please provide a reason for rejection.');
        redirect('Purchase_request');
        return;
    }
    
    $purchase = $this->db->select('id, status, order_status')
                         ->where('id', $id)
                         ->get('sale_requests')
                         ->row();
    
    if (!$purchase) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Purchase request not found.']);
            return;
        }
        $this->session->set_flashdata('error', 'Purchase request not found.');
        redirect('Purchase_request');
        return;
    }
    
    if ($purchase->order_status === 'rejected') {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'This purchase request is already rejected.']);
            return;
        }
        $this->session->set_flashdata('error', 'This purchase request is already rejected.');
        redirect('Purchase_request');
        return;
    }
    
    // Get rejector details (current user)
    $rejector_id = $this->session->userdata('user_id');
    $rejector = $this->db->select('first_name, last_name, role, level_id')
                         ->where('id', $rejector_id)
                         ->get('users')
                         ->row();
    
    $rejector_name = $rejector ? ($rejector->first_name . ' ' . $rejector->last_name) : 'System';
    $rejector_role = $rejector->role ?? 'Admin';
    $rejector_level = $rejector->level_id ?? 0;
    
    $this->db->trans_begin();
    
    try {
        $update_data = [
            'order_status' => 'rejected',
            'status' => 'rejected',
            'rejection_remarks' => $rejection_remark,
            'rejected_by_id' => $rejector_id,
            'rejected_by_name' => $rejector_name,
            'rejected_by_role' => $rejector_role,
            'rejected_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $id)->update('sale_requests', $update_data);
        
        if ($this->db->affected_rows() == 0) {
            throw new Exception('No rows affected. Update may have failed.');
        }
        
        // Send rejection email with all details
        $email_sent = $this->send_status_email_with_rejector($id, 'rejected', $rejector_name, $rejector_role, $rejector_level, $rejection_remark);
        
        $log_data = [
            "user_id" => $this->session->userdata('user_id'),
            "module" => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Rejected purchase request ID: " . $id . " by " . $rejector_name . " (Level: " . $rejector_level . ") with remark: " . $rejection_remark . " | Email sent: " . ($email_sent ? 'Yes' : 'No')
        ];
        $this->log_data_model->add_record($log_data);
        
        $this->db->trans_commit();
        
        $message = $email_sent ? 'Purchase request rejected successfully. Notification sent with rejection details.' : 'Purchase request rejected but email notification failed.';
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => true, 'message' => $message]);
            return;
        }
        
        $this->session->set_flashdata('success', $message);
        redirect('Purchase_request');
        
    } catch (Exception $e) {
        $this->db->trans_rollback();
        error_log("Rejection error: " . $e->getMessage());
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            return;
        }
        
        $this->session->set_flashdata('error', 'Could not reject the request. Error: ' . $e->getMessage());
        redirect('Purchase_request');
    }
}
/**
 * Send rejection email with rejector information
 */
private function send_status_email_with_rejector($order_id, $status, $rejector_name, $rejector_role, $rejector_level, $rejection_remark)
{
    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    $user_email = $this->get_branch_email($order);
    if (!$user_email) {
        log_message('error', "No user email found for order #{$order_id}");
        return false;
    }

    $recipient_name = $this->get_branch_name($order);
    $user = $this->db->get_where('users', ['id' => $order->added_by])->row();
    $company = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
    $company_name = $company->company_name ?? 'OSS System';

    // Determine rejection level text
    $level_text = '';
    if ($rejector_level == 0) {
        $level_text = 'Head Office / Admin';
    } elseif ($rejector_level == 1) {
        $level_text = 'Level 1 - Manager';
    } elseif ($rejector_level == 2) {
        $level_text = 'Level 2 - Senior Manager';
    } elseif ($rejector_level == 3) {
        $level_text = 'Level 3 - Director';
    } else {
        $level_text = 'Rejecting Authority';
    }
    
    $status_text = 'REJECTED';
    $remarks = $rejection_remark;
    
    $template = $this->email_template_model->get_record_by_module('order_rejected');
    
    if (!$template) {
        return $this->send_fallback_rejection_email($order, $user_email, $recipient_name, $company_name, $rejector_name, $level_text, $rejector_role, $remarks);
    }

    $subject = $template->subject;
    $body = $template->mail_header . $template->mail_body . $template->mail_footer;
    $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    $items_html = $this->build_order_items_html($order_items);

    $rejection_info = '<p><strong>Note:</strong> This order has been rejected. Please review the rejection reason above and contact support for assistance.</p>';

    $replacements = [
        '{{reference_no}}' => $order->reference_no,
        '{{recipient_name}}' => $recipient_name,
        '{{company_name}}' => $company_name,
        '{{remarks}}' => nl2br(htmlspecialchars($remarks)),
        '{{order_date}}' => date('d-m-Y', strtotime($order->invoice_date)),
        '{{total_amount}}' => number_format($order->total, 2),
        '{{status}}' => $status_text,
        '{{order_items}}' => $items_html,
        '{{branch_name}}' => $recipient_name,
        '{{approver_name}}' => $rejector_name,
        '{{approver_level}}' => $level_text,
        '{{approver_role}}' => $rejector_role,
        '{{approval_date}}' => date('d-m-Y H:i:s'),
        '{{next_approval_info}}' => $rejection_info
    ];

    foreach ($replacements as $key => $value) {
        $body = str_replace($key, $value, $body);
        $subject = str_replace($key, $value, $subject);
    }

    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    if ($email_setup) {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $email_setup->host,
            'smtp_port' => $email_setup->port,
            'smtp_user' => $email_setup->username,
            'smtp_pass' => $email_setup->password,
            'smtp_crypto' => $email_setup->encryption,
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ];
        $this->email->initialize($config);
    }
    
    $this->email->clear();
    $this->email->from($template->from_email, $template->from_name);
    $this->email->to($user_email);
    
    if (!empty($user->email) && $user->email != $user_email) {
        $this->email->cc($user->email);
    }
    
    $this->email->subject($subject);
    $this->email->message($body);
    
    $sent = $this->email->send();
    $this->log_email_notification($order_id, $user_email, $status, $remarks, $sent ? 'sent' : 'failed');
    
    return $sent;
}

/**
 * Send fallback rejection email with rejector info
 */
private function send_fallback_rejection_email($order, $to_email, $recipient_name, $company_name, $rejector_name, $level_text, $rejector_role, $remarks)
{
    $html = '<html><body>
        <div style="max-width:600px;margin:0 auto;padding:20px;border:1px solid #ddd;border-radius:10px;">
            <div style="background:#dc3545;color:white;padding:15px;text-align:center;border-radius:5px 5px 0 0;">
                <h2>Order REJECTED</h2>
            </div>
            <div style="padding:20px;">
                <p>Dear ' . $recipient_name . ',</p>
                <p>Your order <strong>#' . $order->reference_no . '</strong> has been <strong style="color:#dc3545;">REJECTED</strong>.</p>
                
                <div style="background:#e8f4f8;padding:15px;border-radius:5px;margin:15px 0;">
                    <h4>Rejection Details:</h4>
                    <p><strong>Rejected By:</strong> ' . $rejector_name . '</p>
                    <p><strong>Rejection Level:</strong> ' . $level_text . '</p>
                    <p><strong>Role:</strong> ' . $rejector_role . '</p>
                    <p><strong>Rejection Date:</strong> ' . date('d-m-Y H:i:s') . '</p>
                </div>
                
                <div style="background:#fff3cd;border-left:4px solid #ffc107;padding:10px;margin:15px 0;">
                    <strong>Rejection Reason:</strong><br>' . nl2br(htmlspecialchars($remarks)) . '
                </div>
                
                <p><strong>Order Date:</strong> ' . date('d-m-Y', strtotime($order->invoice_date)) . '</p>
                <p><strong>Total Amount:</strong> ₹' . number_format($order->total, 2) . '</p>
                
                <div style="background:#f8d7da;padding:10px;margin:15px 0;border-left:4px solid #dc3545;">
                    <strong>Note:</strong> This order has been rejected. Please contact support for assistance.
                </div>
            </div>
            <div style="text-align:center;padding:15px;font-size:12px;color:#666;border-top:1px solid #ddd;">
                This is an automated notification from ' . $company_name . '.
            </div>
        </div>
    </body></html>';
    
    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    if ($email_setup) {
        $this->email->initialize([
            'protocol' => 'smtp',
            'smtp_host' => $email_setup->host,
            'smtp_port' => $email_setup->port,
            'smtp_user' => $email_setup->username,
            'smtp_pass' => $email_setup->password,
            'smtp_crypto' => $email_setup->encryption,
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ]);
    }
    
    $this->email->clear();
    $this->email->from($email_setup->username ?? 'noreply@oss.com', $company_name);
    $this->email->to($to_email);
    $this->email->subject('Order REJECTED - ' . $order->reference_no);
    $this->email->message($html);
    
    return $this->email->send();
}
public function reject_purchase_req_old()
{
    if ($this->input->is_ajax_request()) {
        header('Content-Type: application/json');
    }
    
    $id = $this->input->post('id');
    $rejection_remark = $this->input->post('rejection_remark');
    
    if (!$id) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request. No ID provided.']);
            return;
        }
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect('Purchase_request');
        return;
    }
    
    if (empty($rejection_remark)) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Please provide a reason for rejection.']);
            return;
        }
        $this->session->set_flashdata('error', 'Please provide a reason for rejection.');
        redirect('Purchase_request');
        return;
    }
    
    $purchase = $this->db->select('id, status, order_status')
                         ->where('id', $id)
                         ->get('sale_requests')
                         ->row();
    
    if (!$purchase) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Purchase request not found.']);
            return;
        }
        $this->session->set_flashdata('error', 'Purchase request not found.');
        redirect('Purchase_request');
        return;
    }
    
    if ($purchase->order_status === 'rejected') {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'This purchase request is already rejected.']);
            return;
        }
        $this->session->set_flashdata('error', 'This purchase request is already rejected.');
        redirect('Purchase_request');
        return;
    }
    
    $this->db->trans_begin();
    
    try {
        $update_data = [
            'order_status' => 'rejected',
            'status' => 'rejected',
            'rejection_remarks' => $rejection_remark,
        ];
        
        $this->db->where('id', $id)->update('sale_requests', $update_data);
        
        if ($this->db->affected_rows() == 0) {
            throw new Exception('No rows affected. Update may have failed.');
        }
        
        // Send rejection email
        $email_sent = $this->send_status_email($id, 'rejected');
        
        $log_data = [
            "user_id" => $this->session->userdata('user_id'),
            "module" => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Rejected purchase request ID: " . $id . " with remark: " . $rejection_remark . " | Email sent: " . ($email_sent ? 'Yes' : 'No')
        ];
        $this->log_data_model->add_record($log_data);
        
        $this->db->trans_commit();
        
        $message = $email_sent ? 'Purchase request rejected successfully. Notification sent.' : 'Purchase request rejected but email notification failed.';
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => true, 'message' => $message]);
            return;
        }
        
        $this->session->set_flashdata('success', $message);
        redirect('Purchase_request');
        
    } catch (Exception $e) {
        $this->db->trans_rollback();
        error_log("Rejection error: " . $e->getMessage());
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            return;
        }
        
        $this->session->set_flashdata('error', 'Could not reject the request. Error: ' . $e->getMessage());
        redirect('Purchase_request');
    }
}
/*public function approve_purchase() {
    $id = $this->input->post('id');

    if (!$id) {
        echo json_encode(['success' => false]);
        return;
    }

    $user_id  = $this->session->userdata('user_id');
    $level_id = $this->session->userdata('level_id');
    $today    = date('Y-m-d H:i:s'); 

    $existing = $this->db->select('approval_level')
                         ->where('id', $id)
                         ->get('sale_requests')
                         ->row();

    $approval_array = [];
    if (!empty($existing->approval_level)) {
        $approval_array = explode(',', $existing->approval_level);
    }

    $alreadyApproved = false;
    foreach ($approval_array as $entry) {
        list($uid, $date) = explode('|', $entry);
        if ($uid == $user_id) {
            $alreadyApproved = true;
            break;
        }
    }

    if (!$alreadyApproved) {
        $approval_array[] = $user_id . '|' . $today;
    }

    $approval_string = implode(',', $approval_array);

    if ($level_id == 1 || $level_id == 2 || $level_id == 3) {
        $update_data = [
            'approved_by'     => $user_id,
            'approval_level'  => $approval_string,
            'order_status'    => 'partially_approved',
            'status'          => 'pending'
        ];
    } elseif ($level_id == 0) {
        $update_data = [
            'approved_by'     => $user_id,
            'approval_level'  => $approval_string,
            'order_status'    => 'approved',
            'status'          => 'pending'
        ];
    } else {
        $update_data = [];
    }

    if (!empty($update_data)) {
        $this->db->where('id', $id)->update('sale_requests', $update_data);
        
        // Send approval email when level 0 approves
        if ($level_id == 0) {
            $this->send_status_email($id, 'approved');
        }
    }

    if ($this->db->affected_rows() > 0) {
        $log_data = [
            "user_id"     => $user_id,
            "module"      => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Approved purchase request ID: " . $id
        ];
        $this->log_data_model->add_record($log_data);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
*/

public function approve_purchase() {
    $id = $this->input->post('id');

    if (!$id) {
        echo json_encode(['success' => false]);
        return;
    }

    $user_id  = $this->session->userdata('user_id');
    $level_id = $this->session->userdata('level_id');
    $today    = date('Y-m-d H:i:s'); 
    
    // Get approver details
    $approver = $this->db->select('first_name, last_name, role, level_id')
                         ->where('id', $user_id)
                         ->get('users')
                         ->row();
    
    $approver_name = $approver->first_name . ' ' . $approver->last_name;
    $approver_role = $approver->role;
    $approver_level = $level_id;

    $existing = $this->db->select('approval_level')
                         ->where('id', $id)
                         ->get('sale_requests')
                         ->row();

    $approval_array = [];
    if (!empty($existing->approval_level)) {
        $approval_array = explode(',', $existing->approval_level);
    }

    $alreadyApproved = false;
    foreach ($approval_array as $entry) {
        list($uid, $date) = explode('|', $entry);
        if ($uid == $user_id) {
            $alreadyApproved = true;
            break;
        }
    }

    if (!$alreadyApproved) {
        $approval_array[] = $user_id . '|' . $today;
    }

    $approval_string = implode(',', $approval_array);

    if ($level_id == 1 || $level_id == 2 || $level_id == 3) {
        $update_data = [
            'approved_by'     => $user_id,
            'approval_level'  => $approval_string,
            'order_status'    => 'partially_approved',
            'status'          => 'pending'
        ];
    } elseif ($level_id == 0) {
        $update_data = [
            'approved_by'     => $user_id,
            'approval_level'  => $approval_string,
            'order_status'    => 'approved',
            'status'          => 'pending'
        ];
    } else {
        $update_data = [];
    }

    if (!empty($update_data)) {
        $this->db->where('id', $id)->update('sale_requests', $update_data);
        
        // Send email with approver information
        $email_sent = $this->send_status_email_with_approver($id, 'approved', $approver_name, $approver_role, $approver_level);
    }

    if ($this->db->affected_rows() > 0) {
        $log_data = [
            "user_id"     => $user_id,
            "module"      => "sale Requests",
            "user_action" => 1,
            "description" => "User Has Approved purchase request ID: " . $id . " by " . $approver_name . " (Level: " . $level_id . ")"
        ];
        $this->log_data_model->add_record($log_data);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
/**
 * Send status email with approver information
 */
private function send_status_email_with_approver_old($order_id, $status, $approver_name, $approver_role, $approver_level)
{
    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    $user_email = $this->get_branch_email($order);
    if (!$user_email) {
        log_message('error', "No user email found for order #{$order_id}");
        return false;
    }

    $recipient_name = $this->get_branch_name($order);
    $user = $this->db->get_where('users', ['id' => $order->added_by])->row();
    $company = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
    $company_name = $company->company_name ?? 'OSS System';

    // Determine approval level text
    $level_text = '';
    $status_text = '';
    
    if ($approver_level == 0) {
        $level_text = 'Head Office / Admin';
        $status_text = 'FULLY APPROVED';
        $status_color = '#28a745';
    } elseif ($approver_level == 1) {
        $level_text = 'Level 1 - Manager';
        $status_text = 'PARTIALLY APPROVED (Level 1)';
        $status_color = '#ffc107';
    } elseif ($approver_level == 2) {
        $level_text = 'Level 2 - Senior Manager';
        $status_text = 'PARTIALLY APPROVED (Level 2)';
        $status_color = '#ffc107';
    } elseif ($approver_level == 3) {
        $level_text = 'Level 3 - Director';
        $status_text = 'PARTIALLY APPROVED (Level 3)';
        $status_color = '#ffc107';
    } else {
        $level_text = 'Unknown Level';
        $status_text = 'Approved';
        $status_color = '#28a745';
    }
    
    $remarks = "Your order has been " . strtolower($status_text) . " by {$approver_name} ({$level_text}).";
    
    $template = $this->email_template_model->get_record_by_module('order_approved');
    
    if (!$template) {
        return $this->send_fallback_approval_email($order, $user_email, $recipient_name, $company_name, $approver_name, $level_text, $status_text);
    }

    $subject = $template->subject;
    $body = $template->mail_header . $template->mail_body . $template->mail_footer;
    $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    $items_html = $this->build_order_items_html($order_items);

    // Get next approval level info if partially approved
    $next_approval_info = '';
    if ($approver_level == 1) {
        $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Senior Manager (Level 2).</p>';
    } elseif ($approver_level == 2) {
        $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Director (Level 3).</p>';
    } elseif ($approver_level == 3) {
        $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Head Office (Level 0) for final approval.</p>';
    } else {
        $next_approval_info = '<p><strong>Status:</strong> Your order has been fully approved and will now be processed.</p>';
    }

    $replacements = [
        '{{reference_no}}' => $order->reference_no,
        '{{recipient_name}}' => $recipient_name,
        '{{company_name}}' => $company_name,
        '{{remarks}}' => nl2br(htmlspecialchars($remarks)),
        '{{order_date}}' => date('d-m-Y', strtotime($order->invoice_date)),
        '{{total_amount}}' => number_format($order->total, 2),
        '{{status}}' => $status_text,
        '{{order_items}}' => $items_html,
        '{{branch_name}}' => $recipient_name,
        '{{approver_name}}' => $approver_name,
        '{{approver_level}}' => $level_text,
        '{{approver_role}}' => $approver_role,
        '{{approval_date}}' => date('d-m-Y H:i:s'),
        '{{next_approval_info}}' => $next_approval_info
    ];

    foreach ($replacements as $key => $value) {
        $body = str_replace($key, $value, $body);
        $subject = str_replace($key, $value, $subject);
    }

    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    if ($email_setup) {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $email_setup->host,
            'smtp_port' => $email_setup->port,
            'smtp_user' => $email_setup->username,
            'smtp_pass' => $email_setup->password,
            'smtp_crypto' => $email_setup->encryption,
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ];
        $this->email->initialize($config);
    }
    
    $this->email->clear();
    $this->email->from($template->from_email, $template->from_name);
    $this->email->to($user_email);
    
    if (!empty($user->email) && $user->email != $user_email) {
        $this->email->cc($user->email);
    }
    
    $this->email->subject($subject);
    $this->email->message($body);
    
    $sent = $this->email->send();
    $this->log_email_notification($order_id, $user_email, $status, $remarks, $sent ? 'sent' : 'failed');
    
    return $sent;
}
/**
 * Send status email with approver information (Handles ALL levels)
 */
private function send_status_email_with_approver($order_id, $status, $approver_name, $approver_role, $approver_level)
{
    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    $user_email = $this->get_branch_email($order);
    if (!$user_email) {
        log_message('error', "No user email found for order #{$order_id}");
        return false;
    }

    $recipient_name = $this->get_branch_name($order);
    $user = $this->db->get_where('users', ['id' => $order->added_by])->row();
    $company = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
    $company_name = $company->company_name ?? 'OSS System';

    // Get the next pending level
    $next_level = $this->get_next_pending_level_for_purchase($order);
    
    // Determine approval level text and status based on ROLE and LEVEL
    $level_text = '';
    $status_text = '';
    $status_color = '#ffc107'; // Default yellow for partial
    
    // ============================================================
    // CASE 1: SUPER ADMIN (Final Approval)
    // ============================================================
    if ($approver_role == 'super_admin') {
        $level_text = 'Super Admin';
        $status_text = 'FULLY APPROVED';
        $status_color = '#28a745';
    }
    // ============================================================
    // CASE 2: CLIENT (Branch Admin) - Needs Super Admin approval
    // ============================================================
    elseif ($approver_role == 'client') {
        $level_text = 'Client / Branch Admin';
        // Check if Super Admin has already approved
        $super_admin_approved = $this->is_super_admin_approved($order);
        if ($super_admin_approved) {
            $status_text = 'FULLY APPROVED';
            $status_color = '#28a745';
        } else {
            $status_text = 'PARTIALLY APPROVED (Client Level)';
            $status_color = '#ffc107';
        }
    }
    // ============================================================
    // CASE 3: CLIENT BRANCH MANAGER (Level 1)
    // ============================================================
    elseif ($approver_role == 'clients_branch_manager' || $approver_level == 1) {
        $level_text = 'Level 1 - Branch Manager';
        $status_text = 'PARTIALLY APPROVED (Level 1)';
        $status_color = '#ffc107';
    }
    // ============================================================
    // CASE 4: APPROVAL LEVEL 2 (Senior Manager)
    // ============================================================
    elseif ($approver_level == 2) {
        $level_text = 'Level 2 - Senior Manager';
        $status_text = 'PARTIALLY APPROVED (Level 2)';
        $status_color = '#ffc107';
    }
    // ============================================================
    // CASE 5: APPROVAL LEVEL 3 (Director)
    // ============================================================
    elseif ($approver_level == 3) {
        $level_text = 'Level 3 - Director';
        $status_text = 'PARTIALLY APPROVED (Level 3)';
        $status_color = '#ffc107';
    }
    // ============================================================
    // CASE 6: Head Office Admin (Level 0)
    // ============================================================
    elseif ($approver_level == 0) {
        $level_text = 'Head Office / Admin';
        // Check if Super Admin is pending
        if ($this->is_super_admin_pending($order)) {
            $status_text = 'PARTIALLY APPROVED (Head Office)';
            $status_color = '#ffc107';
        } else {
            $status_text = 'FULLY APPROVED';
            $status_color = '#28a745';
        }
    }
    // ============================================================
    // DEFAULT
    // ============================================================
    else {
        $level_text = 'Approver';
        $status_text = 'Approved';
        $status_color = '#28a745';
    }
    
    $remarks = "Your order has been " . strtolower($status_text) . " by {$approver_name} ({$level_text}).";
    
    $template = $this->email_template_model->get_record_by_module('order_approved');
    
    if (!$template) {
        return $this->send_fallback_approval_email($order, $user_email, $recipient_name, $company_name, $approver_name, $level_text, $status_text);
    }

    $subject = $template->subject;
    $body = $template->mail_header . $template->mail_body . $template->mail_footer;
    $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    $items_html = $this->build_order_items_html($order_items);

    // ============================================================
    // NEXT APPROVAL INFO based on current approver
    // ============================================================
    $next_approval_info = '';
    
    if ($approver_role == 'super_admin') {
        $next_approval_info = '<p><strong>Status:</strong> Your order has been fully approved by Super Admin and will now be processed.</p>';
    }
    elseif ($approver_role == 'client') {
        if ($this->is_super_admin_approved($order)) {
            $next_approval_info = '<p><strong>Status:</strong> Your order has been fully approved and will now be processed.</p>';
        } else {
            $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Super Admin for final approval.</p>';
        }
    }
    elseif ($approver_role == 'clients_branch_manager' || $approver_level == 1) {
        $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Client / Branch Admin.</p>';
    }
    elseif ($approver_level == 2) {
        $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Head Office (Level 0).</p>';
    }
    elseif ($approver_level == 3) {
        $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Level 2 - Senior Manager.</p>';
    }
    elseif ($approver_level == 0) {
        if ($this->is_super_admin_pending($order)) {
            $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Super Admin for final approval.</p>';
        } else {
            $next_approval_info = '<p><strong>Status:</strong> Your order has been fully approved and will now be processed.</p>';
        }
    }

    $replacements = [
        '{{reference_no}}' => $order->reference_no,
        '{{recipient_name}}' => $recipient_name,
        '{{company_name}}' => $company_name,
        '{{remarks}}' => nl2br(htmlspecialchars($remarks)),
        '{{order_date}}' => date('d-m-Y', strtotime($order->invoice_date)),
        '{{total_amount}}' => number_format($order->total, 2),
        '{{status}}' => $status_text,
        '{{order_items}}' => $items_html,
        '{{branch_name}}' => $recipient_name,
        '{{approver_name}}' => $approver_name,
        '{{approver_level}}' => $level_text,
        '{{approver_role}}' => $approver_role,
        '{{approval_date}}' => date('d-m-Y H:i:s'),
        '{{next_approval_info}}' => $next_approval_info
    ];

    foreach ($replacements as $key => $value) {
        $body = str_replace($key, $value, $body);
        $subject = str_replace($key, $value, $subject);
    }

    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    if ($email_setup) {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $email_setup->host,
            'smtp_port' => $email_setup->port,
            'smtp_user' => $email_setup->username,
            'smtp_pass' => $email_setup->password,
            'smtp_crypto' => $email_setup->encryption,
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ];
        $this->email->initialize($config);
    }
    
    $this->email->clear();
    $this->email->from($template->from_email, $template->from_name);
    $this->email->to($user_email);
    
    if (!empty($user->email) && $user->email != $user_email) {
        $this->email->cc($user->email);
    }
    
    $this->email->subject($subject);
    $this->email->message($body);
    
    $sent = $this->email->send();
    $this->log_email_notification($order_id, $user_email, $status, $remarks, $sent ? 'sent' : 'failed');
    
    return $sent;
}

/**
 * Check if Super Admin has already approved this order
 */
private function is_super_admin_approved($order)
{
    if (!empty($order->approval_level)) {
        $approvals = explode(',', $order->approval_level);
        foreach ($approvals as $approval) {
            if (strpos($approval, '|') !== false) {
                list($user_id, $date) = explode('|', $approval);
                $user = $this->db->select('role')->where('id', $user_id)->get('users')->row();
                if ($user && $user->role == 'super_admin') {
                    return true;
                }
            }
        }
    }
    return false;
}

/**
 * Check if Super Admin approval is pending (exists but not approved yet)
 */
private function is_super_admin_pending($order)
{
    // First check if Super Admin already approved
    if ($this->is_super_admin_approved($order)) {
        return false;
    }
    
    // Check if Super Admin exists in the system
    $super_admin_exists = $this->db->select('id')
        ->where('role', 'super_admin')
        ->get('users')
        ->num_rows() > 0;
    
    return $super_admin_exists;
}

/**
 * Send fallback approval email with approver info
 */
private function send_fallback_approval_email($order, $to_email, $recipient_name, $company_name, $approver_name, $level_text, $status_text)
{
    $status_color = ($status_text == 'FULLY APPROVED') ? '#28a745' : '#ffc107';
    
    $html = '<html><body>
        <div style="max-width:600px;margin:0 auto;padding:20px;border:1px solid #ddd;border-radius:10px;">
            <div style="background:' . $status_color . ';color:white;padding:15px;text-align:center;border-radius:5px 5px 0 0;">
                <h2>' . $status_text . '</h2>
            </div>
            <div style="padding:20px;">
                <p>Dear ' . $recipient_name . ',</p>
                <p>Your order <strong>#' . $order->reference_no . '</strong> has been approved.</p>
                
                <div style="background:#e8f4f8;padding:15px;border-radius:5px;margin:15px 0;">
                    <h4>Approval Details:</h4>
                    <p><strong>Approved By:</strong> ' . $approver_name . '</p>
                    <p><strong>Approval Level:</strong> ' . $level_text . '</p>
                    <p><strong>Approval Date:</strong> ' . date('d-m-Y H:i:s') . '</p>
                </div>
                
                <p><strong>Order Date:</strong> ' . date('d-m-Y', strtotime($order->invoice_date)) . '</p>
                <p><strong>Total Amount:</strong> ₹' . number_format($order->total, 2) . '</p>
                
                <div style="background:#fff3cd;padding:10px;margin:15px 0;border-left:4px solid #ffc107;">
                    <strong>Status Update:</strong> Your order has been approved by ' . $approver_name . ' at ' . $level_text . ' level.
                </div>
            </div>
            <div style="text-align:center;padding:15px;font-size:12px;color:#666;border-top:1px solid #ddd;">
                This is an automated notification from ' . $company_name . '.
            </div>
        </div>
    </body></html>';
    
    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    if ($email_setup) {
        $this->email->initialize([
            'protocol' => 'smtp',
            'smtp_host' => $email_setup->host,
            'smtp_port' => $email_setup->port,
            'smtp_user' => $email_setup->username,
            'smtp_pass' => $email_setup->password,
            'smtp_crypto' => $email_setup->encryption,
            'mailtype' => 'html',
            'charset' => 'utf-8'
        ]);
    }
    
    $this->email->clear();
    $this->email->from($email_setup->username ?? 'noreply@oss.com', $company_name);
    $this->email->to($to_email);
    $this->email->subject('Order ' . $status_text . ' - ' . $order->reference_no);
    $this->email->message($html);
    
    return $this->email->send();
}

   public function approve_from_manager() {
    $id = $this->input->post('id');

    if (!$id) {
        echo json_encode(['success' => false]);
        return;
    }

    $this->db->where('id', $id)->update('sale_requests', ['order_status' => 'partially_approved']);

    if ($this->db->affected_rows() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
   }
   
public function insert_branch_manager()
{
    $level_id   = trim((string)$this->session->userdata('level_id'));
    $company_id = $this->session->userdata('company_id');
    $this->data['title'] = $this->lang->line('create_user_heading');
    $this->data['warehouses'] = $this->db->get('warehouse')->result();
    $tables = $this->config->item('tables', 'ion_auth');
    $identity_column = $this->config->item('identity', 'ion_auth');
    $this->data['identity_column'] = $identity_column;
    $email    = strtolower($this->input->post('email'));
    $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
    $password = $this->input->post('password');
    $user_id = $this->session->userdata('user_id');
    $current_user = $this->db->select('role')->where('id', $user_id)->get('users')->row();

    if ($current_user) {
        switch ($current_user->role) {
            case 'clients_branch_manager':
                $new_user_role = 'approval_2';
                break;
            case 'approval_2':
                $new_user_role = 'approval_3';
                break;
            default:
                $new_user_role = 'clients_branch_manager';
        }
    } else {
        $new_user_role = 'clients_branch_manager';
    }
    $user = $this->db->select('pricing, role, added_by')->where('id', $user_id)->get('users')->row();
    $additional_data = [
        'first_name'        => $this->input->post('first_name'),
        'last_name'         => $this->input->post('last_name'),
        'phone'             => $this->input->post('phone'),
        'role'              => $new_user_role,
        'clients_branch_id' => $this->input->post('branch_id'),
        'pricing'           => $user->pricing,
        'added_by'          => $user_id,
        'parent_id'         => $user->added_by,
        'dept_id'           => $this->input->post('department_id'),
        'designation'       => $this->input->post('designation'),
        'company_id'        => $company_id,
        'level_id'          => $level_id + 1
    ];

    $group = [];
    $group[] = $this->input->post('role_id');

    // Register user
    $new_user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group);

    if ($new_user_id)
    {
        /* ---------------- NEW ADDITIONS START ---------------- */

        // 1️⃣ Insert in Ledger Table
        $ledger_data = [
            'title'            => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
            'account_group_id' => 4,
            'opening_balance'  => 0,
            'closing_balance'  => 0
        ];
        $this->db->insert('ledger', $ledger_data);
        $ledger_id = $this->db->insert_id();

        $branch_id = $this->input->post('branch_id');
        $branch = $this->db->get_where('clients_branch', ['id' => $branch_id])->row();

        if ($branch) {
            $customer_data = [
                'customer_name'              => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                'gstin'                      => $branch->gstin,
                'email'                      => $email,
                'password'                   => '',
                'phone'                      => $this->input->post('phone'),
                'country_id'                 => '101',
                'country_name'               => 'India',
                'state_id'                   => $branch->state_id, 
                'state_name'                 => $branch->address,
                'city_id'                    => '',
                'city_name'                  => '',
                'address'                    => $branch->billing_details,
                'pincode'                    => '',
                'shipping_country_id'        => '101',
                'shipping_country_name'      => 'India',
                'shipping_state_id'          => $branch->state_id,
                'shipping_state_name'        => $branch->address,
                'shipping_city_id'           => '',
                'shipping_city_name'         => '',
                'shipping_address'           => $branch->shipping_details,
                'shipping_pincode'           => '',
                'ledger_id'                  => $ledger_id,
                'user_id'                    => $new_user_id,
                'delete_status'              => 0,
                'customer_company_name'      => $branch->company,
                'customer_buyer_name'        => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                'customer_buyer_designation' => $this->input->post('designation'),
                'customer_department'        => $this->input->post('department_id'),
                'added_by'                   => $this->session->userdata('user_id'),
                'is_client'                  => 1,
                'whatsapp_country_code'      => 91,
                'payment_duration'           => 1,
                'company_id'                 => $branch->company_id,
            ];
            $this->db->insert('customer', $customer_data);
        }

        /* ---------------- NEW ADDITIONS END ---------------- */

        $this->session->set_flashdata('success', $this->ion_auth->messages());
        redirect("Purchase_request/add_branch_manager", 'refresh');
    }
    else
    {
        $this->data['message'] = $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message');
        $this->data['first_name'] = [
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => $this->input->post('first_name'),
        ];
        $this->data['gstin'] = [
            'name'  => 'gstin',
            'id'    => 'gstin',
            'type'  => 'text',
            'value' => $this->input->post('gstin'),
        ];
        $this->data['last_name'] = [
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'value' => $this->input->post('last_name'),
        ];
        $this->data['identity'] = [
            'name'  => 'identity',
            'id'    => 'identity',
            'type'  => 'text',
            'value' => $this->input->post('identity'),
        ];
        $this->data['email'] = [
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'text',
            'value' => $this->input->post('email'),
        ];
        $this->data['company'] = [
            'name'  => 'company',
            'id'    => 'company',
            'type'  => 'text',
            'value' => $this->input->post('company'),
        ];
        $this->data['phone'] = [
            'name'  => 'phone',
            'id'    => 'phone',
            'type'  => 'text',
            'value' => $this->input->post('phone'),
        ];
        $this->data['password'] = [
            'name'  => 'password',
            'id'    => 'password',
            'type'  => 'password',
            'value' => $this->input->post('password'),
        ];
        $this->data['password_confirm'] = [
            'name'  => 'password_confirm',
            'id'    => 'password_confirm',
            'type'  => 'password',
            'value' => $this->input->post('password_confirm'),
        ];
        $this->data['user_roles'] = $this->ion_auth->groups()->result();
        $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'manager_list', $this->data);
    }
}


///////////sakshi code////////////////
//          public function add_branch_manager()
//     {
//         $user_id           = $this->session->userdata('user_id'); 
//         $company_id        = trim((string)$this->session->userdata('company_id')); 
//         $level_id          = trim((string)$this->session->userdata('level_id')); 
//         $clients_branch_id = $this->session->userdata('clients_branch_id'); 
//         $this->db->select('id');
//         $this->db->from('users');
//         $this->db->where('company_id', $company_id);
//         $this->db->where('clients_branch_id', $clients_branch_id);
//         $this->db->where('level_id', $level_id);
//         $user_query = $this->db->get();
//         $user_ids   = array_column($user_query->result_array(), 'id');
    
//         $branch_ids = array();
//         if (!empty($user_ids)) {
//             $this->db->select('id');
//             $this->db->from('clients_branch');
//             $this->db->where_in('added_by', $user_ids);
//             $branch_query = $this->db->get();
//             $branches     = $branch_query->result();
//             $branch_ids   = array_column($branches, 'id');
//         }
//         $this->db->from('users');
//         $this->db->where('company_id', $company_id);
        
//         if ($level_id !== '' && $level_id !== null) {
//             $next_level = (string)((int)$level_id + 1);
//             $this->db->where('level_id', $next_level);
//         } 
        
//         if (!empty($branch_ids)) {
//             $this->db->where_in('clients_branch_id', $branch_ids);
//         } elseif ($level_id != 0) {
//             $this->db->where('clients_branch_id', null);
//         }
//         $query         = $this->db->get();
//         $data['users'] = $query->result();
    
//       if ($level_id == 0) {
//         $this->db->where('company_id', $company_id);
//         $this->db->where('level_id', 0);

//         $branch_query = $this->db->get('clients_branch');
//         $data['clients_branch'] = $branch_query->result();
//     } elseif ($level_id == 1 || $level_id == 2 || $level_id == 3) {
        
//         $this->db->select('id');
//         $this->db->from('users');
//         $this->db->where('clients_branch_id', $clients_branch_id);
//         $user_query = $this->db->get();
//         $user_ids = array_column($user_query->result_array(), 'id');
//         $branch_ids = array();
//     if (!empty($user_ids)) {
//         $this->db->select('*');
//         $this->db->from('clients_branch');
//         $this->db->where_in('added_by', $user_ids);
//         $this->db->where('company_id', $company_id);
//         $this->db->where('level_id', $level_id); 
//         $branch_query = $this->db->get();
//         $data['clients_branch'] = $branch_query->result();
//     } else {
//         $data['clients_branch'] = [];
//     }

//     } else {
//         $data['clients_branch'] = []; 
//     }
//         $this->load->view('purchase_requests/manager_list', $data);
//   }

public function add_branch_manager()
{
    $session_user_id = $this->session->userdata('user_id');

    // USERS created by this session user
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('added_by', $session_user_id);
    $data['users'] = $this->db->get()->result();

    // BRANCHES created by this session user
    $this->db->select('*');
    $this->db->from('clients_branch');
    $this->db->where('added_by', $session_user_id);
    $data['clients_branch'] = $this->db->get()->result();

    $this->load->view('purchase_requests/manager_list', $data);
}


//   public function add_branch_manager()
//     {
//         $user_id           = $this->session->userdata('user_id'); 
//         $company_id        = trim((string)$this->session->userdata('company_id')); 
//         $level_id          = trim((string)$this->session->userdata('level_id')); 
//         $clients_branch_id = $this->session->userdata('clients_branch_id'); 
//         $this->db->select('id');
//         $this->db->from('users');
//         $this->db->where('company_id', $company_id);
//         $this->db->where('clients_branch_id', $clients_branch_id);
//         $this->db->where('level_id', $level_id);
//         $user_query = $this->db->get();
//         $user_ids   = array_column($user_query->result_array(), 'id');
    
//         $branch_ids = array();
//         if (!empty($user_ids)) {
//             $this->db->select('id');
//             $this->db->from('clients_branch');
//             $this->db->where_in('added_by', $user_ids);
//             $branch_query = $this->db->get();
//             $branches     = $branch_query->result();
//             $branch_ids   = array_column($branches, 'id');
//         }
        
        
//         //old code
//         // $this->db->from('users');
//         // $this->db->where('company_id', $company_id);
        
//         // if ($level_id !== '' && $level_id !== null) {
//         //     $next_level = (string)((int)$level_id + 1);
//         //     $this->db->where('level_id', $next_level);
//         // } 
        
//         // if (!empty($branch_ids)) {
//         //     $this->db->where_in('clients_branch_id', $branch_ids);
//         // } elseif ($level_id != 0) {
//         //     $this->db->where('clients_branch_id', null);
//         // }
//         // $query         = $this->db->get();
//         // $data['users'] = $query->result();
        
//         //NEW CODEE
//         $this->db->from('users');
//         $this->db->where('company_id', $company_id);
        
//         // Get users added by the current user
//         $this->db->where('added_by', $user_id);
        
//         if ($level_id !== '' && $level_id !== null) {
//             $next_level = (string)((int)$level_id + 1);
//             $this->db->where('level_id', $next_level);
//         }
        
//         $query = $this->db->get();
//         $data['users'] = $query->result();
        
        

    
//       if ($level_id == 0) {
//         $this->db->where('company_id', $company_id);
//         $this->db->where('level_id', 0);
//         $branch_query = $this->db->get('clients_branch');
//         $data['clients_branch'] = $branch_query->result();
//     } elseif ($level_id == 1 || $level_id == 2 || $level_id == 3) {
//         $this->db->select('id');
//         $this->db->from('users');
//         $this->db->where('clients_branch_id', $clients_branch_id);
//         $user_query = $this->db->get();
//         $user_ids = array_column($user_query->result_array(), 'id');
    
//         $branch_ids = array();
//     if (!empty($user_ids)) {
//         $this->db->select('*');
//         $this->db->from('clients_branch');
//         $this->db->where_in('added_by', $user_ids);
//         $this->db->where('company_id', $company_id);
//         $this->db->where('level_id', $level_id); 
//         $branch_query = $this->db->get();
//         $data['clients_branch'] = $branch_query->result();
//     } else {
//         $data['clients_branch'] = [];
//     }

//     } else {
//         $data['clients_branch'] = []; 
//     }

//         $this->load->view('purchase_requests/manager_list', $data);
//   }

    public function get_branch_manager_details() {
    $user_id = $this->input->post('user_id');
    $user = $this->db->get_where('users', ['id' => $user_id])->row();

    // 🔹 fetch branch list (same logic as add_branch_manager)
    $company_id = $this->session->userdata('company_id');
    $level_id   = $this->session->userdata('level_id');
    $clients_branch_id = $this->session->userdata('clients_branch_id');

    if ($level_id == 0) {
        $this->db->where('company_id', $company_id);
        $this->db->where('level_id', 0);
        $branch_query = $this->db->get('clients_branch');
        $clients_branch = $branch_query->result();
    } elseif (in_array($level_id, [1,2,3])) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('clients_branch_id', $clients_branch_id);
        $user_query = $this->db->get();
        $user_ids = array_column($user_query->result_array(), 'id');

        if (!empty($user_ids)) {
            $this->db->select('*');
            $this->db->from('clients_branch');
            $this->db->where_in('added_by', $user_ids);
            $this->db->where('company_id', $company_id);
            $this->db->where('level_id', $level_id); 
            $branch_query = $this->db->get();
            $clients_branch = $branch_query->result();
        } else {
            $clients_branch = [];
        }
    } else {
        $clients_branch = [];
    }

    // 🔹 build HTML form
    $html = '
   

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="'.$user->first_name.'" required>
        </div>
        <div class="form-group col-md-6">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="'.$user->last_name.'" required>
        </div>            
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="'.$user->phone.'">
        </div>
        <div class="form-group col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="'.$user->email.'" required>
        </div>            
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="'.$user->username.'" required>
        </div>
        <div class="form-group col-md-6">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="'.$user->department.'" required>
        </div>            
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Designation</label>
            <input type="text" name="designation" class="form-control" value="'.$user->designation.'" required>
        </div>
    </div>
 <div class="form-row">
        <div class="form-group col-md-6">
            <label>Branch Name</label>
            <select name="branch_id" class="form-control" required>
                <option value="">Select Branch</option>';
                foreach($clients_branch as $branch) {
                    $selected = ($branch->id == $user->clients_branch_id) ? 'selected' : '';
                    $html .= '<option value="'.$branch->id.'" '.$selected.'>'.$branch->branch_name.'</option>';
                }
    $html .= '</select>
        </div>
    </div>
    <input type="hidden" name="user_id" value="'.$user->id.'">
    <input type="hidden" name="role_id" value="26">';

    echo $html;
}


  

    public function update_branch_manager() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('branch_id', 'Branch', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }
        
        $user_id = $this->input->post('user_id');
        $data = [
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'username' => $this->input->post('username'),
            'phone' => $this->input->post('phone'),
            'department' => $this->input->post('department'),
            'designation' => $this->input->post('designation'),
            'clients_branch_id' => $this->input->post('branch_id'),
        ];
        
        
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        
        echo json_encode([
            'success' => true,
            'message' => 'Branch manager updated successfully'
        ]);
    }


// public function add_branch()
// {
//         $user_id           = $this->session->userdata('user_id'); 
//         $company_id        = trim((string)$this->session->userdata('company_id')); 
//         $level_id          = trim((string)$this->session->userdata('level_id')); 
//         $clients_branch_id = $this->session->userdata('clients_branch_id'); 
    
//         $this->db->select('id');
//         $this->db->from('users');
//         $this->db->where('company_id', $company_id);
//         $this->db->where('clients_branch_id', $clients_branch_id);
//         $this->db->where('level_id', $level_id);
//         $user_query = $this->db->get();
//         $user_ids   = array_column($user_query->result_array(), 'id');
    
//         $branch_ids = array();
//         if (!empty($user_ids)) {
//             $this->db->select('id');
//             $this->db->from('clients_branch');
//             $this->db->where_in('added_by', $user_ids);
//             $branch_query = $this->db->get();
//             $branches     = $branch_query->result();
//             $branch_ids   = array_column($branches, 'id');
//         }
//         $this->db->from('users');
//         $this->db->where('company_id', $company_id);
        
//         if ($level_id !== '' && $level_id !== null) {
//             $next_level = (string)((int)$level_id + 1);
//             $this->db->where('level_id', $next_level);
//         } 
        
//         if (!empty($branch_ids)) {
//             $this->db->where_in('clients_branch_id', $branch_ids);
//         } elseif ($level_id != 0) {
//             $this->db->where('clients_branch_id', null);
//         }
        
//         $query         = $this->db->get();
//         $data['users'] = $query->result();
    
//       if ($level_id == 0) {
//     $this->db->where('company_id', $company_id);
//     $this->db->where('level_id', 0);
//     $branch_query = $this->db->get('clients_branch');
//     $data['branches'] = $branch_query->result();

// } elseif ($level_id == 1 || $level_id == 2 || $level_id == 3) {
//     $this->db->select('id');
//     $this->db->from('users');
//     $this->db->where('clients_branch_id', $clients_branch_id);
//     $user_query = $this->db->get();
//     $user_ids = array_column($user_query->result_array(), 'id');

//     $branch_ids = array();
//     if (!empty($user_ids)) {
//         $this->db->select('*');
//         $this->db->from('clients_branch');
//         $this->db->where_in('added_by', $user_ids);
//         $this->db->where('company_id', $company_id);
//         $this->db->where('level_id', $level_id); 
//         $branch_query = $this->db->get();
//         $data['branches'] = $branch_query->result();
//     } else {
//         $data['branches'] = [];
//     }

// } else {
//     $data['branches'] = []; 
// }
//     $this->load->view('purchase_requests/branch_list', $data);
// }


public function add_branch()
{
    $user_id           = $this->session->userdata('user_id'); 
    $company_id        = trim((string)$this->session->userdata('company_id')); 
    $level_id          = trim((string)$this->session->userdata('level_id')); 
    $clients_branch_id = $this->session->userdata('clients_branch_id'); 

    // =======================
    // FETCH USERS (existing code kept same)
    // =======================
    $this->db->select('id');
    $this->db->from('users');
    $this->db->where('company_id', $company_id);
    $this->db->where('clients_branch_id', $clients_branch_id);
    $this->db->where('level_id', $level_id);
    $user_query = $this->db->get();
    $user_ids   = array_column($user_query->result_array(), 'id');

    $branch_ids = array();
    if (!empty($user_ids)) {
        $this->db->select('id');
        $this->db->from('clients_branch');
        $this->db->where_in('added_by', $user_ids);
        $branch_query = $this->db->get();
        $branches     = $branch_query->result();
        $branch_ids   = array_column($branches, 'id');
    }

    // =======================
    // FETCH USERS FOR NEXT LEVEL (kept same)
    // =======================
    $this->db->from('users');
    $this->db->where('company_id', $company_id);
    
    if ($level_id !== '' && $level_id !== null) {
        $next_level = (string)((int)$level_id + 1);
        $this->db->where('level_id', $next_level);
    } 
    
    if (!empty($branch_ids)) {
        $this->db->where_in('clients_branch_id', $branch_ids);
    } elseif ($level_id != 0) {
        $this->db->where('clients_branch_id', null);
    }
    
    $query         = $this->db->get();
    $data['users'] = $query->result();

    // ======================================================
    // FIXED: SHOW ONLY BRANCHES CREATED BY THIS USER (added_by = user_id)
    // ======================================================

    $this->db->select('*');
    $this->db->from('clients_branch');
    $this->db->where('added_by', $user_id);            // ← IMPORTANT LINE
    $this->db->where('company_id', $company_id);
    $data['branches'] = $this->db->get()->result();

    // If user has no branches
    if (empty($data['branches'])) {
        $data['branches'] = [];
    }

    $this->load->view('purchase_requests/branch_list', $data);
}


public function update()
{
    $id = $this->input->post('id');
    $data = array(
        'branch_name' => $this->input->post('branch_name'),
        'code' => $this->input->post('code'),
        'gstin' => $this->input->post('gstin'),
        'description' => $this->input->post('description'),
        'company' => $this->input->post('company'),
        'billing_details' => $this->input->post('billing_details'),
        'shipping_details' => $this->input->post('shipping_details'),
        'address' => $this->input->post('state_id'), 
    );
    
    $this->db->where('id', $id); 
    $this->db->update('clients_branch', $data);

    if ($this->db->affected_rows() > 0) {
        echo json_encode(['success' => true, 'message' => 'Branch updated successfully']);
    } else {
        // Add error information
        echo json_encode([
            'success' => false, 
            'message' => 'Update failed',
            'error' => $this->db->error()
        ]);
    }
}

public function insert_branch()
{
    $company_id        = trim((string)$this->session->userdata('company_id')); 
    $level_id          = trim((string)$this->session->userdata('level_id')); 
    $user_id = $this->session->userdata('user_id');
    $role = $this->session->userdata('role'); 
    $this->db->select('clients_branch_id');
    $this->db->where('id', $user_id);
    $user_query = $this->db->get('users');
    $user_data = $user_query->row();
    $gstin = $this->input->post('gstin');
    $this->db->where('gstin', $gstin);
    $existing = $this->db->get('clients_branch')->row();
        $data = array(
            'branch_name'      => $this->input->post('branch_name'),
            'code'             => $this->input->post('code'),
            'description'      => $this->input->post('description'),
            'added_by'         => $user_id,
            'parent_branch'    => $user_data->clients_branch_id,
            'gstin'            => $gstin,
            'billing_details'  => $this->input->post('billing_details'),
            'shipping_details' => $this->input->post('shipping_details'),
            'company'          => $this->input->post('company'),
            'address'          => $this->input->post('state_id'),
            'state_id'         => $this->input->post('state_name'),
            'company_id'       => $company_id,
            'added_by_role'    => $role,
            'level_id'         => $level_id
        );
    	$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "Branch",
                  "user_action" => 1,
                  "description"	=> "User has added New Branch-{$this->input->post('branch_name')}."
                );
        $this->log_data_model->add_record($log_data);
//     echo "<pre>";
//     print_r($data);
//     echo "</pre>";
//     die; 
        $this->db->insert('clients_branch', $data);
        $this->session->set_flashdata('success', 'Branch added successfully!');
        redirect('purchase_request/add_branch');
  
}


	public function view_invoice($id=null)
	{
    
        if ($id != null)
        {
            $id = base64_decode($id);

            $this->db->where('id', $id);
            $sale = $this->db->get('sale_requests')->row();

            if ($sale != null)
            {
                $data['sale'] = $sale;

                $this->db->where('sale_id', $sale->id);
                $data['sale_items'] = $this->db->get('sale_request_items')->result();

                $data['customer'] = $this->db->get_where('users', ['id' => $this->session->userdata('user_id')])->row();
                $data['company_setting'] = $this->company_settings_model->get_company_records();

                $this->load->view('Sales_request/invoice', $data); 
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access a broken or unauthorized URL.');
                redirect('Sales_request', 'refresh');
            }
        }
        else
        {
            $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
            redirect('Sales_request', 'refresh');
        }
	}
	
	
	public function update_status($id, $new_status)
   {
    $allowed_statuses = ['shipping', 'dispatch', 'out_for_delivery', 'delivered'];

    if (in_array($new_status, $allowed_statuses)) {
        $this->db->where('id', $id);
        $this->db->update('sale_requests', ['status' => $new_status]);

        $this->session->set_flashdata('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $new_status)));
    } else {
        $this->session->set_flashdata('error', 'Invalid status.');
    }

    redirect('Sales_request');
  }


public function approve($id)
{
    $this->db->trans_start();
    $sale_request = $this->db->get_where('sale_requests', ['id' => $id])->row();

    if (!$sale_request) {
        $this->session->set_flashdata('failure', 'Sale request not found.');
        redirect('purchase_request', 'refresh');
        return;
    }
      if ($sale_request->level_id === 0) {
        $customer = $this->db->get_where('customer', ['company_id' => $sale_request->company_id])->row();
    } else {
        $customer = $this->db->get_where('customer', ['user_id' => $sale_request->added_by])->row();
    }

    $this->load->helper('common');
    $reference_no = get_next_sale_main_reference();
    $sale_request_items = $this->db->get_where('sale_request_items', ['sale_id' => $sale_request->id])->result();

    $sale_data = array(
        "invoice_date"                    => date('Y-m-d'),
        "reference_no"                    => $reference_no,
        "warehouse_id"                    => $sale_request->warehouse_id,
        "customer_id"                     => $customer->id,
        "customer_gstin"                  => $sale_request->customer_gstin,
        "customer_shipping_country_id"    => $sale_request->customer_shipping_country_id,
        "customer_shipping_state_id"      => $sale_request->customer_shipping_state_id,
        "customer_shipping_city_id"       => $sale_request->customer_shipping_city_id,
        "customer_shipping_address"       => $sale_request->customer_shipping_address,
        "customer_shipping_pincode"       => $sale_request->customer_shipping_pincode,
        "rcm"                             => $sale_request->rcm,
        "total_taxable_value"             => $sale_request->total_taxable_value,
        "total_discount"                  => $sale_request->total_discount,
        "total_tax"                       => $sale_request->total_tax,
        "total"                           => $sale_request->total,
        "internal_note"                   => nl2br(htmlentities($sale_request->internal_note, ENT_QUOTES, 'UTF-8')),
        "external_note"                   => nl2br(htmlentities($sale_request->external_note, ENT_QUOTES, 'UTF-8')),
        "bank_detail"                     => nl2br(htmlentities($sale_request->bank_detail, ENT_QUOTES, 'UTF-8')),
        "terms_and_condition"             => nl2br(htmlentities($sale_request->terms_and_condition, ENT_QUOTES, 'UTF-8')),
        "user_id"                         => $this->session->userdata('user_id'),
        "po_no"                           => $sale_request->reference_no,
        "po_date"                         => $sale_request->invoice_date,
        "converted"                       => 1
    );

    $ewaybill_dispatch_from = $this->input->post('ewaybill_dispatch_from');
    if (!empty($ewaybill_dispatch_from)) {
        $sale_data['ewaybill_dispatch_from'] = $ewaybill_dispatch_from;
    }

    $ewaybill_mode_of_transportation = $this->input->post('ewaybill_mode_of_transportation');
    if (!empty($ewaybill_mode_of_transportation)) {
        $sale_data['ewaybill_mode_of_transportation'] = $ewaybill_mode_of_transportation;
    }

    $ewaybill_subtype = $this->input->post('ewaybill_subtype');
    if (!empty($ewaybill_subtype)) {
        $sale_data['ewaybill_subtype'] = $ewaybill_subtype;
    }

    $ewaybill_doctype = $this->input->post('ewaybill_doctype');
    if (!empty($ewaybill_doctype)) {
        $sale_data['ewaybill_doctype'] = $ewaybill_doctype;
    }

    $ewaybill_transporter_name = $this->input->post('ewaybill_transporter_name');
    if (!empty($ewaybill_transporter_name)) {
        $sale_data['ewaybill_transporter_name'] = $ewaybill_transporter_name;
    }

    $ewaybill_transporter_gstin = $this->input->post('ewaybill_transporter_gstin');
    if (!empty($ewaybill_transporter_gstin)) {
        $sale_data['ewaybill_transporter_gstin'] = $ewaybill_transporter_gstin;
    }

    $ewaybill_distance_of_transportation = $this->input->post('ewaybill_distance_of_transportation');
    if (!empty($ewaybill_distance_of_transportation)) {
        $sale_data['ewaybill_distance_of_transportation'] = $ewaybill_distance_of_transportation;
    }

    $ewaybill_transporter_doc_no = $this->input->post('ewaybill_transporter_doc_no');
    if (!empty($ewaybill_transporter_doc_no)) {
        $sale_data['ewaybill_transporter_doc_no'] = $ewaybill_transporter_doc_no;
    }

    $ewaybill_vehicle_no = $this->input->post('ewaybill_vehicle_no');
    if (!empty($ewaybill_vehicle_no)) {
        $sale_data['ewaybill_vehicle_no'] = $ewaybill_vehicle_no;
    }

    $ewaybill_vehicle_type = $this->input->post('ewaybill_vehicle_type');
    if (!empty($ewaybill_vehicle_type)) {
        $sale_data['ewaybill_vehicle_type'] = $ewaybill_vehicle_type;
    }

    $lori_date = $this->input->post('lori_date');
    if ($lori_date != '') {
        $sale_data['lori_date'] = date('Y-m-d', strtotime($lori_date));
    }

    $ewaybill_transporter_doc_date = $this->input->post('ewaybill_transporter_doc_date');
    if ($ewaybill_transporter_doc_date != '') {
        $sale_data['ewaybill_transporter_doc_date'] = date('Y-m-d', strtotime($ewaybill_transporter_doc_date));
    }

    $this->db->insert('sale', $sale_data);
    $sale_id = $this->db->insert_id(); 

    foreach ($sale_request_items as $item) {
        $sale_item_data = array(
            'sale_id'           => $sale_id,
            'warehouse_product_id' => $item->warehouse_product_id,
            'product_id'        => $item->product_id,
            'product_name'      => $item->product_name,
            'uom_id'            => $item->uom_id,
            'quantity'          => $item->quantity,
            'cost'              => $item->cost,
            'price'             => $item->price,
            'discount_id'       => $item->discount_id,
            'discount_type'     => $item->discount_type,
            'discount_value'    => $item->discount_value,
            'discount_amount'   => $item->discount_amount,
            'taxable_value'     => $item->taxable_value,
            'tax_id'            => $item->tax_id,
            'igst'                   => $item->igst_tax,
            'igst_rate'              => $item->igst_tax,
            'igst_tax'               => $item->igst,
            'cgst'                   => $item->cgst_tax,
            'cgst_rate'              => $item->cgst,
            'cgst_tax'               => $item->cgst_tax,
            'sgst'                   => $item->sgst_tax,
            'sgst_rate'              => $item->sgst,
            'sgst_tax'               => $item->sgst_tax,
            'tax_type'          => $item->tax_type,
            'sub_total'         => $item->subtotal,
            'mfg_date'          => $item->mfg_date,
            'expiry_date'       => $item->expiry_date,
            'batch_no'          => $item->batch_no,
            "selling_price"     => $item->cost,
            "product_remark"    => $item->product_remark,
        );
        $this->db->insert('sale_items', $sale_item_data);
    }

    // Ledger and transaction handling

    /****************************** Add Sale Transaction Entry *********************************/
    $transaction_header = array(
        "entry_id"            => $sale_id,
        "module"              => SALE_MODULE,
        "type"                => SALE_TRANSACTION_TYPE,
        "amount"              => $sale_request->total,
        "voucher_date"        => $sale_request->invoice_date,
        "from_account"        => SALE_LEDGER,
        "to_account"          => $customer->ledger_id,
        "reference_no"        => $reference_no,
        "warehouse_id"        => $sale_request->warehouse_id
    );

    if ($transaction_id = $this->db->insert('transaction_header', $transaction_header)) {
        // Transaction Detail
        $from_transaction_detail = array(
            "transaction_id"  => $transaction_id,
            "voucher_type"    => 'C',
            "ledger_id"       => SALE_LEDGER,
            "dr_amount"       => $sale_request->total
        );
        $this->db->insert('transaction_detail', $from_transaction_detail);

        $to_transaction_detail = array(
            "transaction_id"  => $transaction_id,
            "voucher_type"    => 'D',
            "ledger_id"       => $customer->ledger_id,
            "cr_amount"       => $sale_request->total
        );
        $this->db->insert('transaction_detail', $to_transaction_detail);
    }

    /****************************** Add TDS Transaction Entry *********************************/

    if ($sale_request->tds > 0) {
        $transaction_header = array(
            "entry_id"            => $sale_id,
            "module"              => SALE_MODULE,
            "type"                => TDS_TRANSACTION_TYPE,
            "amount"              => $sale_request->tds,
            "voucher_date"        => $sale_request->invoice_date,
            "from_account"        => $customer->ledger_id,
            "to_account"          => TDS_LEDGER,
            "reference_no"        => $reference_no,
            "warehouse_id"        => $sale_request->warehouse_id
        );

        if ($transaction_id = $this->db->insert('transaction_header', $transaction_header)) {
            $from_transaction_detail = array(
                "transaction_id"  => $transaction_id,
                "voucher_type"    => 'C',
                "ledger_id"       => $customer->ledger_id,
                "dr_amount"       => $sale_request->tds
            );
            $this->db->insert('transaction_detail', $from_transaction_detail);

            $to_transaction_detail = array(
                "transaction_id"  => $transaction_id,
                "voucher_type"    => 'D',
                "ledger_id"       => TDS_LEDGER,
                "cr_amount"       => $sale_request->tds
            );
            $this->db->insert('transaction_detail', $to_transaction_detail);
        }
    }

    /**************************************************************************************/

    // Add log data
    $log_data = array(
        "user_id"       => $this->session->userdata("user_id"),
        "module"        => "sale",
        "entry_id"      => $sale_id,
        "user_action"   => 1,
        "data"          => json_encode($sale_data),
        "description"   => 'Sale with Reference No. - ' . $reference_no . ' was added successfully.'
    );
    
    $this->db->insert('log_data', $log_data);
        $current_approvals = $this->db->select('approval_level')
            ->where('id', $id)
            ->get('sale_requests')
            ->row();
        
        $approval_array = [];
        if (!empty($current_approvals->approval_level)) {
            $approval_array = explode(',', $current_approvals->approval_level);
        }
        
        $current_user_id = $this->session->userdata('user_id');
        $current_date    = date('Y-m-d H:i:s');
        
        $alreadyApproved = false;
        foreach ($approval_array as $entry) {
            list($uid, $date) = explode('|', $entry);
            if ($uid == $current_user_id) {
                $alreadyApproved = true;
                break;
            }
        }
        
        if (!$alreadyApproved) {
            $approval_array[] = $current_user_id . '|' . $current_date;
        }
        
        $approval_string = implode(',', $approval_array);
        
        $this->db->where('id', $id)
            ->update('sale_requests', [
                'sale_invoice_no' => $reference_no,
                'status'          => 'approved',
                'approved_at'     => $current_date,
                'approved_by'     => $current_user_id,
                'approval_level'  => $approval_string
            ]);
        
        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Purchase request approved with reference number: ' . $reference_no);
        redirect('sales_request', 'refresh');
        }

	public function add_sub_user()
	{
	    $this->load->view('purchase_requests/add_user', $data);
	}
	
    public function activate($id, $code = FALSE)
    {
    $id = (int)$id;
    $user = $this->ion_auth_model->user($id)->row();

    if ($code !== FALSE) {
        $activation = $this->ion_auth->activate($id, $code);
    } else {
        $activation = $this->ion_auth->activate($id);
    }

    if ($activation) {
        if ($this->input->is_ajax_request()) {
            $response = array(
                'code' => 1,
                'message' => $user->first_name . ' ' . $user->last_name . ' is ACTIVATED successfully.'
            );
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('success', $user->first_name . ' ' . $user->last_name . ' is ACTIVATED successfully.');
            redirect('purchase_request/view_sub_user', 'refresh');
        }
    } else {
        if ($this->input->is_ajax_request()) {
            $response = array(
                'code' => 0,
                'message' => $user->first_name . ' ' . $user->last_name . ' failed to ACTIVATE.'
            );
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('failure', $user->first_name . ' ' . $user->last_name . ' failed to ACTIVATE.');
            redirect('purchase_request/view_sub_user', 'refresh');
        }
     }
  }

    public function deactivate($id = NULL)
    {
        $id = (int)$id;
        $user = $this->ion_auth_model->user($id)->row();
    
        if ($this->ion_auth->deactivate($id)) {
            if ($this->input->is_ajax_request()) {
                $response = array(
                    'code' => 1,
                    'message' => $user->first_name . ' ' . $user->last_name . ' is DEACTIVATED successfully.'
                );
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('success', $user->first_name . ' ' . $user->last_name . ' is DEACTIVATED successfully.');
                redirect('purchase_request/view_sub_user', 'refresh');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                $response = array(
                    'code' => 0,
                    'message' => $user->first_name . ' ' . $user->last_name . ' failed to DEACTIVATE.'
                );
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('failure', $user->first_name . ' ' . $user->last_name . ' failed to DEACTIVATE.');
                redirect('purchase_request/view_sub_user', 'refresh');
            }
        }
    }
	public function view_sub_user()
   {
    $user_id = $this->session->userdata('user_id');
    $this->db->where('role', 'sub_user');
    $this->db->where('added_by', $user_id);
    $query = $this->db->get('users');
                   
    $data['sub_users'] = $query->result();

    $this->load->view('purchase_requests/view_user', $data);
  }

	public function insert_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');
	
        $this->data['warehouses'] = $this->db->get('warehouse')->result();
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        // $this->form_validation->set_rules('branch_id', 'Branch', 'required'); // Validation for branch_id

		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required');
		}
		else
		{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|is_unique[' . $tables['users'] . '.email]');
		}
		// $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		// $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
		// $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$email    = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');
            $user_id  = $this->session->userdata('user_id');
			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company_id'),
				'phone'      => $this->input->post('phone'),
				'gstin'      => $this->input->post('gstin'),
				'role'       =>'sub_user',
				'added_by'   => $user_id,
				'address'    =>  $this->input->post('address'),
			];

			$group = array();
			$group[] = $this->input->post('role_id');
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group))
		{
				$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "Branch Manager",
                  "user_action" => 1,
                  "description"	=> "New user added: {$this->input->post('first_name')} {$this->input->post('last_name')}"
                );
            $this->log_data_model->add_record($log_data);
			$this->session->set_flashdata('success', $this->ion_auth->messages());
			redirect("purchase_requests/add_sub_user", 'refresh');
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			];
				$this->data['gstin'] = [
				'name' => 'gstin',
				'id' => 'gstin',
				'type' => 'text',
				'value' => $this->form_validation->set_value('gstin'),
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			];
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			];
			$this->data['company'] = [
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			];

			$this->data['user_roles'] =	$this->ion_auth->groups()->result();

			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'add_user', $this->data);
		}
	}
	
	public function view_requests()
    {
        $data['orders'] = $this->db->get('order_requests')->result_array();
        $this->load->view('purchase/order_requests_view', $data);
    }
    
//     public function index()
//     {
//         $user_id           = $this->session->userdata('user_id');
//         $company_id        = $this->session->userdata('company_id');
//         $clients_branch_id = $this->session->userdata('clients_branch_id');
//         $level_id          = (int) $this->session->userdata('level_id');
//         $session_dept_id   = (int) $this->session->userdata('dept_id');
    
//         $this->db->select('id, added_by, level_id, company_id, clients_branch_id, dept_id');
//         $this->db->from('users');
//         $this->db->where('company_id', $company_id);
//         $all_users = $this->db->get()->result_array();
    
//         $user_ids = $this->get_visible_user_ids($user_id, $all_users, $level_id, $clients_branch_id);
    
//         $from_date      = $this->input->get('from_date');
//         $to_date        = $this->input->get('to_date');
      
//         $this->db->from('sale_requests');
//         $this->db->order_by('id', 'DESC');
    
//         if ($from_date) $this->db->where("DATE(invoice_date) >=", $from_date);
//         if ($to_date)   $this->db->where("DATE(invoice_date) <=", $to_date);
    
//         $this->db->where_in('added_by', $user_ids);
//         $records = $this->db->get()->result();
    
//         $final_records = [];
//         $debug = false;
    
//         foreach ($records as $rec) {
    
//             // get department of the user who added the record
//             $added_by_dept = $this->db->select('dept_id')->where('id', $rec->added_by)->get('users')->row();
//             $added_by_dept_id = $added_by_dept ? (int)$added_by_dept->dept_id : 0;
    
//             // ✅ strict department filtering
//             if (!($level_id == 0 && $session_dept_id == 1)) { 
//                 // only allow if current user's department matches
//                 if ($session_dept_id !== $added_by_dept_id) {
//                     continue; // skip record if dept mismatch
//                 }
//             }
    
//             // Always show orders placed by current user
//             if ((int)$rec->added_by === (int)$user_id) {
//                 if ($debug) { $rec->debug_reason = 'own_order'; }
//                 $final_records[] = $rec;
//                 continue;
//             }
    
//             // Only consider pending orders for approval
//             $is_pending = (is_null($rec->status) || $rec->status === 'pending');
//             if (!$is_pending) continue;
    
//             $next_level = $this->get_next_pending_level_for_purchase($rec);
    
//             if ($next_level !== null && (int)$next_level === $level_id) {
//                 $final_records[] = $rec;
//             }
//         }
//         $data['purchases'] = $final_records;
//         $data['visible_user_ids'] = $user_ids;
//         $data['debug'] = false;
//         $this->load->view('purchase_requests/list', $data);
//   }

public function index()
{
    $user_id = $this->session->userdata('user_id'); // e.g., 3 = Pramod Kumar

    // 1️⃣ Get direct child users
    $child_users = $this->db->select('id')
                            ->where('added_by', $user_id)
                            ->get('users')
                            ->result_array();

    $child_ids = array_column($child_users, 'id');

    // 2️⃣ Include self
    $user_ids = array_merge([$user_id], $child_ids);

    // 3️⃣ Date filters (optional)
    $from_date = $this->input->get('from_date');
    $to_date   = $this->input->get('to_date');
    $status    = $this->input->get('status');

    $this->db->from('sale_requests');
    $this->db->order_by('id', 'DESC');
    $this->db->where_in('added_by', $user_ids);
    
    
    // ✅ STATUS FILTER (same as admin)
    if ($status !== '' && $status !== null) {
        $this->db->where('status', $status);
    }

    if ($from_date) $this->db->where("DATE(invoice_date) >=", $from_date);
    if ($to_date) $this->db->where("DATE(invoice_date) <=", $to_date);

    $records = $this->db->get()->result();
    
    //  $data['departments'] = $this->db
    //     ->select('id, name')
    //     ->get('departments')
    //     ->result();
 $data['departments'] = $this->db->get('departments')->result();
    // 4️⃣ Send to view
    $data['purchases'] = $records;
  $data['visible_user_ids'] = $user_ids; 
    $this->load->view('purchase_requests/list', $data);
}

    private function get_visible_user_ids($user_id, $all_users, $level_id, $branch_id)
    {
        // Special case: level 0 = company head → get ALL users of this company
        if ($level_id == 0) {
            return array_column($all_users, 'id');
        }
    
        $visible = [$user_id];
        // 2. Children of current user → recursive
        foreach ($all_users as $u) {
            if ($u['added_by'] == $user_id) {
                $visible[] = $u['id'];
    
                // Recursively get children of this user
                $visible = array_merge(
                    $visible,
                    $this->get_visible_user_ids($u['id'], $all_users, $u['level_id'], $u['clients_branch_id'])
                );
            }
        }
        return array_unique($visible);
    }

private function get_next_pending_level_for_purchase($purchase)
{
    $placed_by_level = isset($purchase->level_id) ? (int)$purchase->level_id : null;
    $placed_by_user_id = isset($purchase->added_by) ? (int)$purchase->added_by : null;
    $company_id = isset($purchase->company_id) ? $purchase->company_id : null;

    if ($placed_by_level === null) return null;

    // 1) Build approval hierarchy for this purchase (array of ['level_id'=>X,'approvers'=>[...] ])
    $hierarchy = $this->get_approval_hierarchy($placed_by_level, $placed_by_user_id, $company_id);

    if (empty($hierarchy)) {
        // nothing to approve
        return null;
    }

    // 2) Parse completed approvals from approval_level column (store is "userId|date,userId|date,...")
    $completed_levels = [];
    if (!empty($purchase->approval_level)) {
        $approvals = explode(',', $purchase->approval_level);
        foreach ($approvals as $a) {
            $a = trim($a);
            if ($a === '') continue;
            if (strpos($a, '|') !== false) {
                list($user_id_str, $date) = explode('|', $a, 2);
                $user_id_int = (int) $user_id_str;
                if ($user_id_int > 0) {
                    $lvl = $this->get_user_level($user_id_int);
                    if ($lvl !== null) $completed_levels[] = (int)$lvl;
                }
            } else {
                // fallback: if format unexpected but it's numeric
                if (is_numeric($a)) {
                    $lvl = $this->get_user_level((int)$a);
                    if ($lvl !== null) $completed_levels[] = (int)$lvl;
                }
            }
        }
    }

    // unique
    $completed_levels = array_unique($completed_levels);

    // 3) Iterate hierarchy in the same order produced by get_approval_hierarchy()
    foreach ($hierarchy as $level_data) {
        if (!isset($level_data['level_id'])) continue;
        $lvl = (int)$level_data['level_id'];
        if (!in_array($lvl, $completed_levels)) {
            // first not-yet-approved level -> that's the next approver level
            return $lvl;
        }
    }

    // all levels in hierarchy already completed
    return null;
}

//  public function add()
//   {
//     $user_id       = $this->session->userdata('user_id'); 
//     $company_id    = $this->session->userdata('company_id'); 
//     $level_id      = $this->session->userdata('level_id'); 
//     $branch_id     = $this->session->userdata('clients_branch_id');

//     $user = $this->db->select('role, state, clients_branch_id')
//                      ->from('users')
//                      ->where('id', $user_id)
//                      ->get()
//                      ->row();

//     $state_id = null;
//     if ($level_id == 0) {
//         $company = $this->db->select('state')
//                             ->from('clients_company')
//                             ->where('id', $company_id)
//                             ->get()
//                             ->row();
//         $state_id = $company->state ?? null; //maharashtra
//     } else {
//         $branch = $this->db->select('address')
//                           ->from('clients_branch')
//                           ->where('id', $branch_id)
//                           ->get()
//                           ->row();
//         $state_id = $branch->state ?? null;
//     }
//     $company_state = $this->db->select('state')
//                               ->from('company_setting')
//                               ->where('id', 1)
//                               ->get()
//                               ->row()
//                               ->state;

//     $data['company_settings'] = $this->db->get('company_setting')->row();

//     $is_intra = $state_id == $company_state;

//     $admin_id = $this->session->userdata('user_id'); 
//     $role     = $this->db->select('role, added_by')
//                          ->where('id', $admin_id) 
//                          ->get('users')
//                          ->row();

//     $customer = $this->db->select('pricing')
//                          ->where('id', $company_id) 
//                          ->get('clients_company')
//                          ->row();
//     $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];

//     $products = [];
//     if (!empty($pricing_data)) {
//         $product_ids = array_keys($pricing_data);
//         $this->db->select('p.*, pc.cgst, pc.sgst, pc.igst, pc.tax_id');
//         $this->db->from('product p');
//         $this->db->join('product_category pc', 'p.product_category_id = pc.id', 'left');
//         $this->db->where_in('p.id', $product_ids);
//         $query = $this->db->get();

//         foreach ($query->result() as $product) {
            
//             $product_id = (int)$product->id;
             
//             $product->price = $pricing_data[$product->id]['price']; 
//             $product->uom = $pricing_data[(string)$product->id]['uom'] ?? '';
            
//             // var_dump($product_id, $product->price);
        

//             $products[] = $product;
            
//         }
//     }
//     $data['products'] = $products;
//     $clients_branch   =  $this->session->userdata('clients_branch_id');

//     $clients_branch_id = $clients_branch;
//     $manager_id = $role->added_by;
//     $parent_id = null;

//     if (!empty($manager_id)) {
//         $parent_id = $this->db->select('added_by')
//                               ->from('users')
//                               ->where('id', $manager_id)
//                               ->get()
//                               ->row('added_by');
//     }

//     if ($this->input->server('REQUEST_METHOD') === 'POST') {

//         $is_client  = $level_id == 0;
//         $is_manager = in_array($level_id, [1, 2, 3], true);
//         $order_for     = $this->input->post('order_for');      
//         $behalf_user_id = $this->input->post('behalf_user_id');
//         $behalf_branch_id = $this->input->post('behalf_branch_id');
//         if ($order_for === 'behalf' && !empty($behalf_user_id)) {
//           $behalf_user = $this->db->select('clients_branch_id, role, level_id')
//                                     ->from('users')
//                             ->where('id', $behalf_user_id)
//                             ->get()
//                             ->row();

//         $sale_data = [
//             'invoice_date'        => date('Y-m-d'),
//             'warehouse_id'        => 1,
//             'customer_id'         => 1,
//             'reference_no'        => '',
//             'total_taxable_value' => $this->input->post('total_taxable_value'),
//             'total'               => $this->input->post('total'),
//             'total_tax'           => $this->input->post('total_tax'),
//             'total_discount'      => $this->input->post('total_discount'),
//             'internal_note'       => $this->input->post('internal_note'),
//             'external_note'       => $this->input->post('external_note'),
//             'added_by'            => $behalf_user_id,                
//             'added_from'          => $admin_id,                       
//             'added_by_branch'     => $behalf_user->role,
//             'branch_id'           => $behalf_branch_id, 
//             'manager_id'          => $manager_id,
//             'parent_id'           => $parent_id,
//             'company_id'          => $company_id,
//             'level_id'            => $behalf_user->level_id,
//             'order_status'        => 'pending',          
//             'approved_by'         =>  $admin_id
//         ];
    
//     } else {
//         $sale_data = [
//             'invoice_date'        => date('Y-m-d'),
//             'warehouse_id'        => 1,
//             'customer_id'         => 1,
//             'reference_no'        => $this->input->post('invoice_no'),
//             'total_taxable_value' => $this->input->post('total_taxable_value'),
//             'total'               => $this->input->post('total'),
//             'total_tax'           => $this->input->post('total_tax'),
//             'total_discount'      => $this->input->post('total_discount'),
//             'internal_note'       => $this->input->post('internal_note'),
//             'external_note'       => $this->input->post('external_note'),
//             'added_by'            => $admin_id,
//             'added_by_branch'     => $role->role,
//             'branch_id'           => $clients_branch_id,
//             'manager_id'          => $manager_id,
//             'parent_id'           => $parent_id,
//             'company_id'          => $company_id,
//             'level_id'            => $level_id
//         ];
    
//         if ($is_client) {
//             $sale_data['order_status'] = 'approved';
//             $sale_data['status']       = 'pending';
//         } else{
//             $sale_data['order_status'] = 'pending';
//             $sale_data['status']       = NULL;
//         }
//     }
//         $sale_items_array = explode('|', $this->input->post('purchase_items'));
//         $this->db->trans_begin();
//         $this->db->insert('sale_requests', $sale_data);
//         $sale_request_id = $this->db->insert_id();
//         $reference_no = 'OSS' . $sale_request_id;
//         $this->db->where('id', $sale_request_id);
//         $this->db->update('sale_requests', ['reference_no' => $reference_no]);
//         if ($sale_request_id) {
//             foreach ($sale_items_array as $item_json) {
//                 $item = (array)json_decode($item_json);
//                 $item['sale_id'] = $sale_request_id;
//                 $item['product_remark'] = isset($item['product_remark']) ? $item['product_remark'] : '';

//                 $tax_id = $this->db->select('product_category.tax_id')
//                                   ->from('product')
//                                   ->join('product_category', 'product.product_category_id = product_category.id', 'left')
//                                   ->where('product.id', $item['product_id'])
//                                   ->get()
//                                   ->row('tax_id');

//                 $item['tax_id'] = $tax_id;

//                 if (!empty($item['tax_id'])) {
//                     $tax = $this->db->select('igst, cgst, sgst')
//                                     ->where('id', $item['tax_id'])
//                                     ->get('tax')
//                                     ->row();

//                     if ($tax) {
//                         $tax_amount = $item['subtotal'] - $item['taxable_value'];

//                         if ($is_intra) {
//                             $item['cgst_tax'] = $tax->cgst;
//                             $item['sgst_tax'] = $tax->sgst;
//                             $item['cgst']     = $tax_amount / 2;
//                             $item['sgst']     = $tax_amount / 2;
//                             $item['igst_tax'] = 0;
//                             $item['igst']     = 0;
//                         } else {
//                             $item['igst_tax'] = $tax->igst;
//                             $item['igst']     = $tax_amount;
//                             $item['cgst_tax'] = 0;
//                             $item['sgst_tax'] = 0;
//                             $item['cgst']     = 0;
//                             $item['sgst']     = 0;
//                         }
//                     }
//                 } else {
//                     $item['igst_tax'] = 0;
//                     $item['cgst_tax'] = 0;
//                     $item['sgst_tax'] = 0;
//                     $item['igst']     = 0;
//                     $item['cgst']     = 0;
//                     $item['sgst']     = 0;
//                 }

//                 $this->db->insert('sale_request_items', $item);
//             }
            
//             	$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "purchase Request",
//                   "user_action" => 1,
//                   "description" => "User has added a new Purchase Request (ID: {$sale_request_id})."
//                 );
//             $this->log_data_model->add_record($log_data);
//             $this->db->trans_commit();
//             $this->session->set_flashdata('success', 'Sale request added successfully.');
//             redirect('Purchase_request', 'refresh');
//         } else {
//             $this->db->trans_rollback();
//             $this->session->set_flashdata('failure', 'Failed to add sale request.');
//             redirect('Purchase_request', 'refresh');
//         }
//     }

//     $data['tax_type']   = $is_intra ? 'intra' : 'inter';
//     $data['customers']  = $this->db->get('customer')->result(); 
//     $data['warehouses'] = $this->db->get('warehouse')->result();

//     $this->load->view('purchase_requests/add', $data);
// }

public function add()
{
    $user_id    = $this->session->userdata('user_id');
    $company_id = $this->session->userdata('company_id');
    $level_id   = $this->session->userdata('level_id');
    $branch_id  = $this->session->userdata('clients_branch_id');


    $user = $this->db->select('role, state, clients_branch_id')
        ->from('users')
        ->where('id', $user_id)
        ->get()
        ->row();

    if ($level_id == 0) {
        $company = $this->db->select('state')
            ->from('clients_company')
            ->where('id', $company_id)
            ->get()
            ->row();
        $state_id = $company->state ?? null;
    } else {
        $branch = $this->db->select('state_id')
            ->from('clients_branch')
            ->where('id', $branch_id)
            ->get()
            ->row();
        $state_id = $branch->state_id ?? null;
    }

    $company_state = $this->db->select('state')
        ->from('company_setting')
        ->where('id', 1)
        ->get()
        ->row()
        ->state;

    $is_intra = ($state_id == $company_state);

    $data['company_settings'] = $this->db->get('company_setting')->row();


    $customer = $this->db->select('pricing')
        ->where('id', $company_id)
        ->get('clients_company')
        ->row();

    $pricing_data = !empty($customer->pricing)
        ? json_decode($customer->pricing, true)
        : [];

    $products = [];
    if (!empty($pricing_data)) {
        $product_ids = array_keys($pricing_data);
        $this->db->select('p.*, pc.cgst, pc.sgst, pc.igst, pc.tax_id');
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.product_category_id = pc.id', 'left');
        $this->db->where_in('p.id', $product_ids);

        foreach ($this->db->get()->result() as $product) {
            $product->price = $pricing_data[$product->id]['price'];
            $product->uom   = $pricing_data[$product->id]['uom'] ?? '';
            $products[] = $product;
        }
    }
    $data['products'] = $products;


    $role = $this->db->select('role, added_by')
        ->where('id', $user_id)
        ->get('users')
        ->row();

    $manager_id = $role->added_by;
    $parent_id  = null;

    if (!empty($manager_id)) {
        $parent_id = $this->db->select('added_by')
            ->where('id', $manager_id)
            ->get('users')
            ->row('added_by');
    }


    if ($this->input->server('REQUEST_METHOD') === 'POST') {

        $order_for        = $this->input->post('order_for');
        $behalf_user_id   = $this->input->post('behalf_user_id');
        $behalf_branch_id = $this->input->post('behalf_branch_id');

       
        $limit_user_id = ($order_for === 'behalf' && $behalf_user_id)
            ? $behalf_user_id
            : $user_id;

        $monthly_limit = (float) $this->db->select('monthly_limit')
            ->where('id', $limit_user_id)
            ->get('users')
            ->row()
            ->monthly_limit;

        $current_month_total = (float) $this->db
            ->select('IFNULL(SUM(total),0) AS total')
            ->from('sale_requests')
            ->where('added_by', $limit_user_id)
            ->where('status !=', 'rejected')
            ->where('MONTH(invoice_date)', date('m'))
            ->where('YEAR(invoice_date)', date('Y'))
            ->get()
            ->row()
            ->total;

        $order_total = (float) $this->input->post('total');

       
        if ($monthly_limit > 0 && ($current_month_total + $order_total) > $monthly_limit) {
            $this->session->set_flashdata(
                'failure',
                'Monthly limit exceeded. Limit: ₹' .
                number_format($monthly_limit, 2) .
                ' | Used: ₹' .
                number_format($current_month_total, 2)
            );
            redirect('Purchase_request/add');
            return;
        }


        if ($order_for === 'behalf' && $behalf_user_id) {

            $behalf_user = $this->db->select('clients_branch_id, role, level_id')
                ->where('id', $behalf_user_id)
                ->get('users')
                ->row();

            $sale_data = [
                'invoice_date'        => date('Y-m-d'),
                'warehouse_id'        => 1,
                'customer_id'         => 1,
                'reference_no'        => '',
                'total_taxable_value' => $this->input->post('total_taxable_value'),
                'total'               => $order_total,
                'total_tax'           => $this->input->post('total_tax'),
                'total_discount'      => $this->input->post('total_discount'),
                'internal_note'       => $this->input->post('internal_note'),
                'external_note'       => $this->input->post('external_note'),
                'added_by'            => $behalf_user_id,
                'added_from'          => $user_id,
                'added_by_branch'     => $behalf_user->clients_branch_id,
                'branch_id'           => $behalf_branch_id,
                'manager_id'          => $manager_id,
                'parent_id'           => $parent_id,
                'company_id'          => $company_id,
                'level_id'            => $behalf_user->level_id,
                 'order_status'        => 'approved',  // Auto-approved
                'status'              => 'pending',
                'approval_level'      => $user_id . '|' . date('Y-m-d H:i:s'),  // ADDED: This line
                 'approved_at'         => date('Y-m-d H:i:s'),
                'approved_by'         => $user_id
                
                
               
            ];

        } else {

            $sale_data = [
                'invoice_date'        => date('Y-m-d'),
                'warehouse_id'        => 1,
                'customer_id'         => 1,
                'reference_no'        => $this->input->post('invoice_no'),
                'total_taxable_value' => $this->input->post('total_taxable_value'),
                'total'               => $order_total,
                'total_tax'           => $this->input->post('total_tax'),
                'total_discount'      => $this->input->post('total_discount'),
                'internal_note'       => $this->input->post('internal_note'),
                'external_note'       => $this->input->post('external_note'),
                'added_by'            => $user_id,
                'added_by_branch'     => $role->role,
                'branch_id'           => $branch_id,
                'manager_id'          => $manager_id,
                'parent_id'           => $parent_id,
                'company_id'          => $company_id,
                'level_id'            => $level_id,
                'order_status'        => ($level_id == 0 ? 'approved' : 'pending'),
                'status'              => 'pending',
                 'approved_at' => date('Y-m-d H:i:s')
                // 'status'       => 'approved'
               
            ];
        }

      
        $this->db->trans_begin();
        $this->db->insert('sale_requests', $sale_data);
        $sale_request_id = $this->db->insert_id();

        $this->db->where('id', $sale_request_id)
            ->update('sale_requests', ['reference_no' => 'OSS' . $sale_request_id]);

      
foreach (explode('|', $this->input->post('purchase_items')) as $item_json) {

    if (empty($item_json)) continue;

    $item = (array) json_decode($item_json, true);

    // ✅ JUST FETCH TAX % (NO PRICE / QTY CHANGE)
    $tax = $this->db->select('pc.tax_id, pc.cgst, pc.sgst, pc.igst')
        ->from('product p')
        ->join('product_category pc', 'p.product_category_id = pc.id', 'left')
        ->where('p.id', $item['product_id'])
        ->get()
        ->row();

    // ✅ ONLY ADD TAX FIELDS
    $item['tax_id'] = $tax->tax_id ?? null;

    if ($is_intra) {
        $item['cgst_tax'] = $tax->cgst ?? 0;
        $item['sgst_tax'] = $tax->sgst ?? 0;
        $item['igst_tax'] = 0;
    } else {
        
        $item['igst_tax'] = $tax->igst ?? 0;
        $item['cgst_tax'] = 0;
        $item['sgst_tax'] = 0;
    }

    // ✅ KEEP EVERYTHING ELSE AS IS
    $item['sale_id'] = $sale_request_id;

    $this->db->insert('sale_request_items', $item);
}


        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Sale request added successfully.');
        redirect('Purchase_request');
    }

    /* ================= VIEW ================= */

    $data['tax_type'] = $is_intra ? 'intra' : 'inter';
    $this->load->view('purchase_requests/add', $data);
}


public function view($id = null)
{
    if ($id === null) {
        $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
        redirect('purchase_request', 'refresh');
    }
    $id = base64_decode($id);
    if (empty($id)) {
        $this->session->set_flashdata('failure', 'Invalid or broken URL.');
        redirect('purchase_request', 'refresh');
    }

    $sale = $this->db->get_where('sale_requests', ['id' => $id])->row();

    if (!$sale) {
        $this->session->set_flashdata('failure', 'You have tried to access a broken or unauthorized URL.');
        redirect('purchase_request', 'refresh');
    }
    
    $data['sale'] = $sale;
    // 🔹 Show "Order Placed By" ONLY for on-behalf orders
$data['order_placed_for'] = null;

if (!empty($sale->added_from)) {
    // added_from = user for whom the order was placed
    $placed_for_user = $this->db
        ->select('first_name, last_name')
        ->where('id', $sale->added_from)
        ->get('users')
        ->row();

    if ($placed_for_user) {
        $data['order_placed_for'] =
            $placed_for_user->first_name . ' ' . $placed_for_user->last_name;
    }
}

    $data['sale_items'] = $this->db->get_where('sale_request_items', ['sale_id' => $sale->id])->result();

    if ($sale->level_id == 0) {
        $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
        $data['customer'] = (object)[
            'company_name'     => $company->company_name ?? '',
            'gstin'            => $company->gstin ?? 'N/A',
            'billing_details'  => $company->address ?? 'N/A',
            'shipping_details' => $company->shipping_address ?? 'N/A',
        ];
    } else {
        $branch = $this->db->get_where('clients_branch', ['id' => $sale->branch_id])->row();
        $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
        $user   = $this->db->get_where('users', ['id' => $sale->added_by])->row();
        $data['customer'] = (object)[
            'first_name'       => $user->first_name ?? '',
            'last_name'        => $user->last_name ?? '',
            'phone'            => $user->phone ?? 'N/A',
            'company_name'     => ($company->company_name ?? ''),
            'company_gst'      => $company->gstin ?? 'N/A',
            'company_add'      => $company->address ?? 'N/A',
            'gstin'            => $branch->gstin ?? 'N/A',
            'address'          => $user->address ?? 'N/A',
            'branch'           => $branch->branch_name  ?? 'N/A',
            'billing_details'  => $branch->billing_details ?? 'N/A',
            'shipping_details' => $branch->shipping_details ?? 'N/A',
        ];
    }

    $data['company_setting'] = $this->company_settings_model->get_company_records();
    $this->load->view('purchase_requests/invoice', $data);
}


// public function view_tax_invoice($id = null)
// {
//       if ($id === null) {
//         $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
//         redirect('purchase_request', 'refresh');
//     }
//     $id = base64_decode($id);
//     if (empty($id)) {
//         $this->session->set_flashdata('failure', 'Invalid or broken URL.');
//         redirect('purchase_request', 'refresh');
//     }

//     $sale = $this->db->get_where('sale_requests', ['id' => $id])->row();

//     if (!$sale) {
//         $this->session->set_flashdata('failure', 'You have tried to access a broken or unauthorized URL.');
//         redirect('purchase_request', 'refresh');
//     }
//     $data['sale'] = $sale;
//     $data['sale_items'] = $this->db->get_where('sale_request_items', ['sale_id' => $sale->id])->result();

//     if ($sale->level_id == 0) {
//         $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
//         $data['customer'] = (object)[
//             'company_name'     => $company->company_name ?? '',
//             'gstin'            => $company->gstin ?? 'N/A',
//             'billing_details'  => $company->address ?? 'N/A',
//             'shipping_details' => $company->shipping_address ?? 'N/A',
//         ];
//     } else {
//         $branch = $this->db->get_where('clients_branch', ['id' => $sale->branch_id])->row();
//         $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
//         $user   = $this->db->get_where('users', ['id' => $sale->added_by])->row();
//         $data['customer'] = (object)[
//              'first_name'       => $user->first_name ?? '',
//             'last_name'        => $user->last_name ?? '',
//             'phone'            => $user->phone ?? 'N/A',
//             'company_name'     => ($company->company_name ?? ''),
//             'company_gst'      => $company->gstin ?? 'N/A',
//             'company_add'      => $company->address ?? 'N/A',
//             'gstin'            => $branch->gstin ?? 'N/A',
//             'address'          => $user->address ?? 'N/A',
//             'branch'           => $branch->branch_name  ?? 'N/A',
//             'billing_details'  => $branch->billing_details ?? 'N/A',
//             'shipping_details' => $branch->shipping_details ?? 'N/A',
//         ];
//     }

//     $data['company_setting'] = $this->company_settings_model->get_company_records();
//       $this->load->view('purchase_requests/tax_invoice', $data);
// }

public function view_tax_invoice($id = null)
{
    $user_role = $this->session->userdata('role');
    if ($user_role == 'client') {
    }else {
        if (!$this->permission_model->has_permission('view_purchase')) { $this->load->view('errors/html/error_restricted'); return; } }
    
    if ($id === null) {
        $this->session->set_flashdata('failure', 'Invalid URL.');
        redirect('client_report/tax_purchase');
    }

    $sale_request_id = base64_decode($id);
    if (!$sale_request_id) {
        $this->session->set_flashdata('failure', 'Invalid invoice.');
        redirect('client_report/tax_purchase');
    }

    // 🔑 Find sale created from this sale request
    $sale = $this->db
        ->where('proforma_invoice_id', $sale_request_id)
        ->get('sale')
        ->row();

    if (!$sale) {
        $this->session->set_flashdata('failure', 'Invoice not generated yet.');
        redirect('client_report/tax_purchase');
    }

    // ✅ Redirect to SAME SALE VIEW (no changes)
    redirect('sale/view/' . base64_encode($sale->id));
}





// 	public function add()
// 	{
// 			if($this->input->server('REQUEST_METHOD') === 'POST')
// 			{
// 				$this->form_validation->set_rules('purchase_date','Invoice Date','required');		
// 				// $this->form_validation->set_rules('warehouse_id','Warehouse','required');
// 				// $this->form_validation->set_rules('supplier_id','Warehouse','required');
// 				// $this->form_validation->set_rules('invoice_no','Invoice No','required');

// 				if($this->form_validation->run()==FALSE)
// 				{
// 					$data['warehouses'] 			= $this->warehouse_model->get_records();
// 					$data['supplier'] 				= $this->supplier_model->get_records();
// 					$data['company_setting']    	= $this->company_settings_model->get_company_records();
				
					 
    
       
		
// 					$this->load->view('purchase/add',$data);
// 				}
// 				else
// 				{
// 					$purchase_date 				= date('Y-m-d', strtotime($this->input->post('purchase_date')));
// 					$reference_no 				= $this->purchase_model->get_lastest_sequence_number();
// 					$warehouse_id 				= $this->input->post('warehouse_id');
// 					$supplier_id 					= $this->input->post('supplier_id');

// 					$supplier 						= $this->supplier_model->get_single_record($supplier_id);
// 					$supplier_gstin 			= $supplier->gstin;

// 					$invoice_no 					= $this->input->post('invoice_no');
// 					$total_taxable_value 	= $this->input->post('total_taxable_value');
// 					$total_discount 			= $this->input->post('total_discount');
// 					$total_tax 						= $this->input->post('total_tax');
// 					$total 								= $this->input->post('total');
// 					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
// 					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
// 					//$bank_detail 				= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
// 					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

// 					$purchase_order_id 		= $this->input->post('purchase_order_id');

// 					$due_date 						= ($this->input->post('due_date') != NULL && $this->input->post('due_date') != '') ? date('Y-m-d',strtotime($this->input->post('due_date'))) : NULL;

// 					$document 						= $this->input->post("document");

//           if (!is_array($document)) {
//             // Convert $document to an array with a single element
//             $document = array($document);
//           }

//           $documents = implode(',', $document);

// 					$purchase_data = array(
// 																"purchase_date"				=> $purchase_date,
// 																"due_date"						=> $due_date,
// 																"reference_no"				=> $reference_no,
// 																"invoice_no"					=> $invoice_no,
// 																"warehouse_id"				=> $warehouse_id,
// 																"supplier_id"					=> $supplier_id,
// 																"supplier_gstin"			=> $supplier_gstin,
// 																"total_taxable_value" => $total_taxable_value,	
// 																"total_tax"						=> $total_tax,
// 																"total_discount" 			=> $total_discount,	
// 																"total" 							=> $total,
// 																"internal_note"				=> $internal_note,
// 																"external_note" 			=> $external_note,
// 																"document" 						=> $documents,
// 																"terms_and_condition" => $terms_and_condition,
// 																"purchase_order_id" 			=> $purchase_order_id,
// 																"user_id" 						=> $this->session->userdata('user_id')
// 															);
// 					// being transaction
// 					$this->db->trans_begin();	

// 					if($id = $this->purchase_model->add_purchase_record($purchase_data))
// 					{
// 						$entered_purchase = $this->purchase_model->get_purchase_single_record($id);
// 						$supplier 				= $this->supplier_model->get_single_record($entered_purchase->supplier_id);

// 						// update the ledgers
							
// 						/****************************** Start Purchase Ledger ***********************************/

// 						$purchase_ledger 						= $this->ledger_model->get_single_record(PURCHASE_LEDGER);

// 						$updated_purchase_ledger 		= array('closing_balance' => ($purchase_ledger->closing_balance+$total));

// 						$this->ledger_model->edit_record($updated_purchase_ledger,$purchase_ledger->id);

// 						/****************************** End Purchase Ledger ***********************************/

// 						/****************************** Start Supplier Ledger ***********************************/

// 						$supplier_ledger 			= $this->ledger_model->get_single_record($supplier->ledger_id);
// 						$updated_supplier_ledger    = array('closing_balance' => ($supplier_ledger->closing_balance+$total));
// 						$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

// 						/****************************** End Supplier Ledger ***********************************/

// 						/****************************** Add Transaction entry *********************************/

// 						$transaction_header = array(
// 																				"entry_id"				=>  $id,
// 																				"module"					=>  PURCHASE_MODULE,
// 																				"type"						=>	PURCHASE_TRANSACTION_TYPE,
// 																				"amount"					=>	$total,
// 																				"voucher_date"		=>	$purchase_date,
// 																				"from_account"		=>	$supplier->ledger_id,
// 																				"to_account"			=>	PURCHASE_LEDGER,
// 																				"reference_no"		=>	$reference_no
// 																			);
// 						if($transaction_id = $this->transaction_model->add_record($transaction_header))
// 						{
// 							$from_transaction_detail = array(
// 																								"transaction_id" 	=> $transaction_id,
// 																								"voucher_type"		=> 'C',
// 																								"ledger_id"				=> $supplier->ledger_id,
// 																								"dr_amount"				=> $total
// 																							);
// 							// transaction detail record
// 							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

// 							$to_transaction_detail = array(
// 																							"transaction_id" 	=> $transaction_id,
// 																							"voucher_type"		=> 'D',
// 																							"ledger_id"				=> PURCHASE_LEDGER,
// 																							"cr_amount"				=> $total
// 																						);
// 							// transaction detail record
// 							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
// 						}

// 						/**************************************************************************************/

// 						$log_data = array(
// 											"user_id" 		=> $this->session->userdata("user_id"),
// 											"module"			=> "purchase",
// 											"entry_id"		=> $id,
// 											"user_action"	=> 1,
// 											"data"				=> json_encode((array)$entered_purchase),
// 											"description" => 'purchase (Reference No. -'  .$reference_no .') is added successfully.'
// 										);
// 						$this->log_data_model->add_record($log_data);

// 						$purchase_items 				= $this->input->post('purchase_items');
// 						$purchase_items_array 	= explode("|", $this->input->post('purchase_items'));

// 						for ($i=0; $i < sizeof($purchase_items_array) ; $i++) { 
									
// 							$temp_purchase_item = (array)json_decode($purchase_items_array[$i]);
// 							$temp_purchase_item['purchase_id']  = $id;

// 							if($temp_purchase_item['expiry_date'] == '' || $temp_purchase_item['expiry_date'] == '0000-00-00')
// 								unset($temp_purchase_item['expiry_date']);
// 							else
// 								$temp_purchase_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_item['expiry_date']));

// 							if($temp_purchase_item['mfg_date'] == '' || $temp_purchase_item['mfg_date'] == '0000-00-00')
// 								unset($temp_purchase_item['mfg_date']);
// 							else
// 								$temp_purchase_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_item['mfg_date']));

// 							$this->purchase_model->add_purchase_item_record($temp_purchase_item);
// 						}

// 						// commit transaction
// 						$this->db->trans_commit();

// 						$this->session->set_flashdata('success',  'Purchase (Reference No. -'.$reference_no.') is added successfully.');
// 						redirect('purchase','refresh');
// 					}
// 					else
// 					{
// 						// rollback transaction
// 						$this->db->trans_rollback();

// 						$this->session->set_flashdata('failure',  'Purchase (Reference No. -'.$reference_no.') is failed to add.');
// 						redirect('purchase','refresh');
// 					}

// 				}
// 			}
// 			else
// 			{
			
// 				$data['warehouses'] 			= $this->warehouse_model->get_records();
// 				$data['supplier'] 				= $this->supplier_model->get_records();
// 				$data['company_setting']    	= $this->company_settings_model->get_company_records();	
				
// 				$this->load->view('purchase_requests/add',$data);
// 			}
// 		}	
	
public function search()
{
    $term = $this->input->post('term');
    $module = $this->input->post('module');
    $company_id = $this->session->userdata('company_id');

    // Get pricing data for this company
    $customer = $this->db->select('pricing')
        ->where('id', $company_id)
        ->get('clients_company')
        ->row();

    $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];

    // If no pricing set, return empty
    if (empty($pricing_data)) {
        echo json_encode([]);
        return;
    }

    // Fetch products that match the term
    $this->db->select('p.*, c.name as product_category_name');
    $this->db->from('product p');
    $this->db->join('product_category c', 'p.product_category_id = c.id', 'left');
    $this->db->like('p.name', $term);
    $query = $this->db->get();
    $all_products = $query->result_array();

    $products = [];
    foreach ($all_products as $product) {
        if (isset($pricing_data[$product['id']]['price'])) {
            $product['custom_price'] = $pricing_data[$product['id']]['price'];
            $products[] = $product; // only keep products with pricing
        }
    }

    echo json_encode($products);
}


public function get_record_detail()
{
    $module = $this->input->post('module');
    // $product_id = $this->input->post('product_id');

$product_id = trim((string) $this->input->post('product_id'));
    $data['product'] = $this->db->select('p.*, c.name as product_category_name, c.tax_type, c.cgst, c.sgst, c.igst, u.name as uom_name')
        ->from('product p')
        ->join('product_category c', 'p.product_category_id = c.id', 'left')
        ->join('uom u', 'p.uom_id = u.id', 'left')
        ->where('p.id', $product_id)
        ->get()
        ->row_array();

    if (empty($data['product'])) {
        echo json_encode(['product' => [], 'discount' => [], 'batch_nos' => []]);
        return;
    }

    $company_id = $this->session->userdata('company_id');
    $customer = $this->db->select('pricing')
        ->where('id', $company_id)
        ->get('clients_company')
        ->row();

    $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];

    if (isset($pricing_data[$product_id]['price'])) {
        // $data['product']['price'] = $pricing_data[$product_id]['price'];
        $data['product']['cost'] = (float) $pricing_data[$product_id]['price'];
$data['product']['price'] = (float) $pricing_data[$product_id]['price']; // optional, safe
    }

$price = 0;

if (isset($pricing_data[$product_id]) && isset($pricing_data[$product_id]['price'])) {
    $price = (float) $pricing_data[$product_id]['price'];
}

// ALWAYS set cost & price
$data['product']['cost']  = $price;
$data['product']['price'] = $price;

    $data['discount'] = $this->discount_model->get_valid_records();

    $data['batch_nos'] = $this->warehouse_products_model->get_batch_nos_by_product_id($product_id);

    echo json_encode($data);
}

// public function get_record_detail()
// {
//     $product_id = (string) $this->input->post('product_id'); // FORCE STRING

//     $data['product'] = $this->db->select('
//             p.*,
//             c.name as product_category_name,
//             c.tax_type,
//             c.cgst,
//             c.sgst,
//             c.igst,
//             u.name as uom_name
//         ')
//         ->from('product p')
//         ->join('product_category c', 'p.product_category_id = c.id', 'left')
//         ->join('uom u', 'p.uom_id = u.id', 'left')
//         ->where('p.id', $product_id)
//         ->get()
//         ->row_array();

//     if (empty($data['product'])) {
//         echo json_encode(['product' => [], 'discount' => []]);
//         return;
//     }

//     // 🔹 DEFAULT PRICE FROM PRODUCT TABLE
//     $data['product']['price'] = (float) $data['product']['cost'];

//     // 🔹 CLIENT PRICING OVERRIDE
//     $company_id = $this->session->userdata('company_id');

//     $customer = $this->db->select('pricing')
//         ->where('id', $company_id)
//         ->get('clients_company')
//         ->row();

//     if (!empty($customer->pricing)) {

//         $pricing_data = json_decode($customer->pricing, true);

//         // 🔥 CRITICAL FIX
//         if (isset($pricing_data[$product_id]) && $pricing_data[$product_id]['price'] !== '') {
//             $data['product']['price'] = (float) $pricing_data[$product_id]['price'];
//         }
//     }

//     $data['discount'] = $this->discount_model->get_valid_records();

//     echo json_encode($data);
// }


 



}
