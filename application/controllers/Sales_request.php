<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Sales_request extends MY_Controller {

	private $pdf;
	private $is_customer;

	public function __construct()
	{
		parent::__construct();
		$this->is_customer = $this->customer_model->is_loggedin_user_is_customer();
		$this->pdf = new Dompdf();
        $this->load->model('email_template_model');
		$this->load->model('email_setup_model');

	}
	
 
// public function index()
// {
//     $status = $this->input->get('status');

//     $this->db->from('sale_requests');

//     if ($status !== '' && $status !== null) {
//         $this->db->where('status', $status);
//     }
//     // CORRECTED: disable escaping so "IS NULL" isn't broken by backticks
//     $this->db->order_by('approved_at IS NULL', 'ASC', FALSE); // approved first (IS NULL -> 0 comes first)
//     $this->db->order_by('approved_at', 'DESC', FALSE);        // latest approved_at first
//     $this->db->order_by('id', 'DESC');                        // fallback sort

//     $data['purchases'] = $this->db->get()->result();
//     $data['warehouse'] = $this->warehouse_model->get_records();

//     $log_data = [
//         "user_id"     => $this->session->userdata("user_id"),
//         "module"      => "sale",
//         "user_action" => 0,
//         "description" => "User has viewed filtered list of sale."
//     ];
//     $this->log_data_model->add_record($log_data);

//     $this->load->view('Sales_request/list', $data);
// }


public function index()
{
    $status = $this->input->get('status');
    $session_level_id = (int)$this->session->userdata('level_id');

    $this->db->from('sale_requests');
    
    // Apply status filter only if selected
    if ($status !== '' && $status !== null) {
        $this->db->where('status', $status);
    }

    // REMOVE this filter completely - it was commented but let's ensure it's gone
    // No admin filter - show ALL orders regardless of order_status

    // Sorting
    $this->db->order_by('id', 'DESC'); // Simple sorting by newest first

    $purchases = $this->db->get()->result();

    // Process approval dates (your existing code)
    foreach ($purchases as &$purchase) {
        /* ===============================
           CLIENT / MANAGER APPROVAL DATE
        =============================== */
        $purchase->client_approval_date = null;

        if (!empty($purchase->approval_level)) {
            $entries = explode(',', $purchase->approval_level);
            $entries = array_filter($entries);

            if (!empty($entries)) {
                $first = reset($entries);
                if (strpos($first, '|') !== false) {
                    list($uid, $dt) = explode('|', $first);
                    if (!empty($dt)) {
                        $purchase->client_approval_date = date('d-m-Y H:i', strtotime($dt));
                    }
                }
            }
        }

        /* ===============================
           ADMIN / HO APPROVAL DATE
        =============================== */
        $purchase->admin_approval_date = null;

        if (!empty($purchase->approved_at) && in_array($purchase->status, [
            'approved', 'create_invoice', 'courier', 'in_transit', 
            'delivered', 'awaiting_confirmation', 'delivery_confirmed'
        ])) {
            $purchase->admin_approval_date = date('d-m-Y H:i', strtotime($purchase->approved_at));
        }
    }
    unset($purchase);

    $data['purchases'] = $purchases;
    $data['warehouse'] = $this->warehouse_model->get_records();

    // Log
    $log_data = [
        "user_id"     => $this->session->userdata("user_id"),
        "module"      => "sale",
        "user_action" => 0,
        "description" => "User has viewed filtered list of sale."
    ];
    $this->log_data_model->add_record($log_data);

    $this->load->view('Sales_request/list', $data);
}
 


public function index_old()
{
    $status = $this->input->get('status');
     $session_level_id = (int)$this->session->userdata('level_id');

    $this->db->from('sale_requests');
    
     // 🔥 ADD THIS BLOCK
    if ($session_level_id == 0) {  // Admin
        $this->db->where('order_status !=', 'pending');
    }

    if ($status !== '' && $status !== null) {
        $this->db->where('status', $status);
    }

    // Sorting
    $this->db->order_by('approved_at IS NULL', 'ASC', FALSE); 
    $this->db->order_by('approved_at', 'DESC', FALSE);        
    $this->db->order_by('id', 'DESC');                        

// Sorting
// if ($status === '' || $status === null) {

//     // 1️⃣ Pending first
//     $this->db->order_by(
//         "CASE 
//             WHEN status IS NULL OR status = 'pending' THEN 0
//             ELSE 1
//          END",
//         "ASC",
//         false
//     );

//     // 2️⃣ Pending → latest first (by created date)
//     $this->db->order_by(
//         "CASE 
//             WHEN status IS NULL OR status = 'pending' THEN invoice_date
//             ELSE NULL
//          END",
//         "DESC",
//         false
//     );

//     // 3️⃣ Approved → latest approval first
//     $this->db->order_by(
//         "CASE 
//             WHEN status IS NOT NULL AND status != 'pending' THEN approved_at
//             ELSE NULL
//          END",
//         "DESC",
//         false
//     );

//     // 4️⃣ Safety fallback
//     $this->db->order_by('id', 'DESC');

// } else {

//     // Single status selected
//     $this->db->order_by('id', 'DESC');
// }


    $purchases = $this->db->get()->result();

    /* --------------------------------------------------
       Compute Final Approval Date
       Rule:
       1️⃣ If approved_at exists → use it
       2️⃣ Else → use latest available date from approval_level
       3️⃣ Else → null (Pending)
    -------------------------------------------------- */
    foreach ($purchases as &$purchase) {
         /* ===============================
       CLIENT / MANAGER APPROVAL DATE
       =============================== */
    $purchase->client_approval_date = null;

    if (!empty($purchase->approval_level)) {

        $entries = explode(',', $purchase->approval_level);
        $entries = array_filter($entries);

        // First entry = first approver (manager)
        if (!empty($entries)) {

            $first = reset($entries);

            if (strpos($first, '|') !== false) {
                list($uid, $dt) = explode('|', $first);

                if (!empty($dt)) {
                    $purchase->client_approval_date =
                        date('d-m-Y H:i', strtotime($dt));
                }
            }
        }
    }

    /* ===============================
       ADMIN / HO APPROVAL DATE
       =============================== */
    $purchase->admin_approval_date = null;

   if (
    !empty($purchase->approved_at) &&
    in_array($purchase->status, [
        'approved',
        'create_invoice',
        'courier',
        'in_transit',
        'delivered',
        'awaiting_confirmation',
        'delivery_confirmed'
    ])
) {
    $purchase->admin_approval_date =
        date('d-m-Y H:i', strtotime($purchase->approved_at));
}

        // $purchase->final_approval_date = null;

        // // If OSS Final Approved
        // if (!empty($purchase->approved_at)) {
        //     $purchase->final_approval_date = date('d-m-Y', strtotime($purchase->approved_at));
        // }

        // // Else fallback to last approval from approval_level
        // else if (!empty($purchase->approval_level)) {

        //     $entries = explode(',', $purchase->approval_level);
        //     $entries = array_filter($entries);

        //     // Loop backwards to get last valid approval with date
        //     for ($i = count($entries) - 1; $i >= 0; $i--) {

        //         if (strpos($entries[$i], '|') !== false) {

        //             list($uid, $dt) = explode('|', $entries[$i]);

        //             if (!empty($dt)) {
        //                 $purchase->final_approval_date = date('d-m-Y H:i', strtotime($dt));
        //                 break;
        //             }
        //         }
        //     }
        // }
        // Else remain NULL → Pending
    
}
    unset($purchase);

    $data['purchases'] = $purchases;
    $data['warehouse'] = $this->warehouse_model->get_records();

    // Log
    $log_data = [
        "user_id"     => $this->session->userdata("user_id"),
        "module"      => "sale",
        "user_action" => 0,
        "description" => "User has viewed filtered list of sale."
    ];
    $this->log_data_model->add_record($log_data);

    $this->load->view('Sales_request/list', $data);
}
/**
 * Get branch/user email - using users table
 */
private function get_branch_email($order)
{
    if ($order->level_id == 0) {
        // Head office order - gets email from users table for the user who placed the order
        $user = $this->db->select('email')->where('id', $order->added_by)->get('users')->row();
        return $user->email ?? null;
    } else {
        // Branch order - FIRST tries to get branch manager email
        $branch_manager = $this->db->select('email')
            ->where('clients_branch_id', $order->branch_id)
            ->where('role', 'clients_branch_manager')
            ->get('users')
            ->row();
        
        if ($branch_manager && !empty($branch_manager->email)) {
            return $branch_manager->email;  // ← Branch Manager Email
        }
        
        // Fallback: user who placed the order
        $user = $this->db->select('email')->where('id', $order->added_by)->get('users')->row();
        return $user->email ?? null;
    }
}
/*private function get_branch_name($order)
{
    if ($order->level_id == 0) {
        // Head office order - get company name from clients_company
        $company = $this->db->select('company_name')->where('id', $order->company_id)->get('clients_company')->row();
        return $company->company_name ?? 'Head Office';
    } else {
        // Branch order - get branch manager name or user name
        $branch_manager = $this->db->select('first_name, last_name')
            ->where('clients_branch_id', $order->branch_id)
            ->where('role', 'clients_branch_manager')
            ->get('users')
            ->row();
        
        if ($branch_manager) {
            return $branch_manager->first_name . ' ' . $branch_manager->last_name;
        }
        
        // Fallback: get user name who placed the order
        $user = $this->db->select('first_name, last_name')->where('id', $order->added_by)->get('users')->row();
        return $user ? ($user->first_name . ' ' . $user->last_name) : 'Branch User';
    }
}*/
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
 * Send status email to branch/user with approver information
 */
/**
 * Send status email to branch/user with approver information (for both Approval & Rejection)
 */
private function send_status_email($order_id, $status)
{
    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    // Get approver information (who is approving/rejecting)
    $approver_id = $this->session->userdata('user_id');
    $approver = $this->db->select('first_name, last_name, role, level_id')
                         ->where('id', $approver_id)
                         ->get('users')
                         ->row();
    
    $approver_name = $approver ? ($approver->first_name . ' ' . $approver->last_name) : 'System';
    $approver_role = $approver->role ?? 'Admin';
    $approver_level = $approver->level_id ?? 0;

    // Determine approval level text and status text
    $level_text = '';
    $status_text = '';
    
    if ($status == 'approved') {
        if ($approver_level == 0) {
            $level_text = 'Head Office / Admin';
            $status_text = 'FULLY APPROVED';
        } elseif ($approver_level == 1) {
            $level_text = 'Level 1 - Manager';
            $status_text = 'PARTIALLY APPROVED (Level 1)';
        } elseif ($approver_level == 2) {
            $level_text = 'Level 2 - Senior Manager';
            $status_text = 'PARTIALLY APPROVED (Level 2)';
        } elseif ($approver_level == 3) {
            $level_text = 'Level 3 - Director';
            $status_text = 'PARTIALLY APPROVED (Level 3)';
        } else {
            $level_text = 'Approver';
            $status_text = 'Approved';
        }
    } elseif ($status == 'rejected') {
        // For rejection, get the level text based on who rejected
        if ($approver_level == 0) {
            $level_text = 'Head Office / Admin';
        } elseif ($approver_level == 1) {
            $level_text = 'Level 1 - Manager';
        } elseif ($approver_level == 2) {
            $level_text = 'Level 2 - Senior Manager';
        } elseif ($approver_level == 3) {
            $level_text = 'Level 3 - Director';
        } else {
            $level_text = 'Rejecting Authority';
        }
        $status_text = 'REJECTED';
    }

    // Get user email from users table
    $user_email = $this->get_branch_email($order);
    if (!$user_email) {
        log_message('error', "No user email found for order #{$order_id}");
        return false;
    }

    // Get recipient name
    $recipient_name = $this->get_branch_name($order);
    
    // Get user who placed the order (for additional CC)
    $user = $this->db->get_where('users', ['id' => $order->added_by])->row();
    $company = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
    $company_name = $company->company_name ?? 'OSS System';

    $module = ($status == 'approved') ? 'order_approved' : 'order_rejected';
    
    // Set remarks based on status
    if ($status == 'rejected') {
        $remarks = $order->rejection_remarks ?? 'No specific reason provided.';
    } else {
        $remarks = "Your order has been " . strtolower($status_text) . " by {$approver_name} ({$level_text}).";
    }
    
    $template = $this->email_template_model->get_record_by_module($module);
    
    if (!$template) {
        return $this->send_fallback_status_email($order, $user_email, $status, $remarks, $company_name, $recipient_name, $approver_name, $level_text, $status_text);
    }

    $subject = $template->subject;
    $body = $template->mail_header . $template->mail_body . $template->mail_footer;
    $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    $items_html = $this->build_order_items_html($order_items);

    // Get next approval level info if partially approved (only for approval)
    $next_approval_info = '';
    if ($status == 'approved') {
        if ($approver_level == 1) {
            $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Senior Manager (Level 2).</p>';
        } elseif ($approver_level == 2) {
            $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Director (Level 3).</p>';
        } elseif ($approver_level == 3) {
            $next_approval_info = '<p><strong>Next Step:</strong> Your order will now be reviewed by Head Office (Level 0) for final approval.</p>';
        } elseif ($approver_level == 0) {
            $next_approval_info = '<p><strong>Status:</strong> Your order has been fully approved and will now be processed.</p>';
        }
    } elseif ($status == 'rejected') {
        $next_approval_info = '<p><strong>Note:</strong> This order has been rejected. Please review the rejection reason above and contact support for assistance.</p>';
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
 * Send status email to branch/user
 */
private function send_fallback_status_email($order, $to_email, $status, $remarks, $company_name, $recipient_name = 'User', $approver_name = 'System', $level_text = '', $status_text = 'Approved')
{
    $color = ($status == 'approved') ? '#28a745' : '#dc3545';
    $status_upper = ucfirst($status);
    
    $html = '<html><body>
        <div style="max-width:600px;margin:0 auto;padding:20px;border:1px solid #ddd;border-radius:10px;">
            <div style="background:' . $color . ';color:white;padding:15px;text-align:center;border-radius:5px 5px 0 0;">
                <h2>Order ' . $status_upper . '</h2>
            </div>
            <div style="padding:20px;">
                <p>Dear ' . $recipient_name . ',</p>
                <p>Your order <strong>#' . $order->reference_no . '</strong> has been <strong>' . $status_text . '</strong>.</p>';
    
    // Add rejection remarks if rejected
    if ($status == 'rejected') {
        $html .= '<div style="background:#fff3cd;border-left:4px solid #ffc107;padding:10px;margin:15px 0;">
                    <strong>Rejection Reason:</strong><br>' . nl2br(htmlspecialchars($remarks)) . '
                  </div>';
    }
    
    $html .= '<div style="background:#e8f4f8;padding:15px;border-radius:5px;margin:15px 0;">
                    <h4>Approval/Rejection Details:</h4>
                    <p><strong>' . ucfirst($status) . ' By:</strong> ' . $approver_name . '</p>
                    <p><strong>' . ucfirst($status) . ' Level:</strong> ' . ($level_text ?: 'System') . '</p>
                    <p><strong>Role:</strong> ' . ($approver_role ?? 'Admin') . '</p>
                    <p><strong>Date:</strong> ' . date('d-m-Y H:i:s') . '</p>
                </div>
                
                <p><strong>Order Date:</strong> ' . date('d-m-Y', strtotime($order->invoice_date)) . '</p>
                <p><strong>Total Amount:</strong> ₹' . number_format($order->total, 2) . '</p>';
    
    if ($status == 'approved') {
        $html .= '<div style="background:#d4edda;padding:10px;margin:15px 0;border-left:4px solid #28a745;">
                    <strong>Status Update:</strong> ' . nl2br(htmlspecialchars($remarks)) . '
                  </div>';
    }
    
    $html .= '</div>
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


// public function update_status()
// {
    
   
//     $order_id = $this->input->post('order_id');
//     $action   = $this->input->post('action');

//     if (!$order_id || !$action) {
//         $this->session->set_flashdata('error', 'Invalid request.');
//         redirect($_SERVER['HTTP_REFERER']);
//     }

//     // Fetch current order
//     $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
//     if (!$order) {
//         $this->session->set_flashdata('error', 'Sale request not found.');
//         redirect($_SERVER['HTTP_REFERER']);
//     }

//     // Determine next status
//     $status_flow = [
//         'pending'        => 'approved',
//         'approved'       => 'create_invoice',
//         'create_invoice' => 'courier',
//         'courier'        => 'in_transit',
//         'in_transit'     => 'delivered',
//         'delivered'             => 'awaiting_confirmation', // mail sent to user
//         'awaiting_confirmation' => 'delivery_confirmed',    // final confirmation by admin
//     ];

//     $current_status = $order->status;
//     $next_status    = ($action == 'next' && isset($status_flow[$current_status])) ? $status_flow[$current_status] : null;

//     if ($action == 'reject') {
//         $next_status = 'rejected';
//     }

//     if ($next_status === null) {
//         $this->session->set_flashdata('error', 'Cannot move to next status.');
//         redirect($_SERVER['HTTP_REFERER']);
//     }

//     // If next status is create_invoice, create the sale
//     if ($next_status == 'create_invoice') {

//         $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result_array();

//         if (empty($order_items)) {
//             $this->session->set_flashdata('error', 'No items found to create invoice.');
//             redirect($_SERVER['HTTP_REFERER']);
//         }

//         $sale_id = $this->sale_model->create_sale_from_request($order, $order_items);

//         if (!$sale_id) {
//             $this->session->set_flashdata('error', 'Invoice creation failed.');
//             redirect($_SERVER['HTTP_REFERER']);
//         }
        
//          $this->db->where('id', $order_id)->update('sale_requests', [
//         'status'      => $next_status,
//         'approved_at' => date('Y-m-d H:i:s')
//     ]);

//     // 🔥 REDIRECT TO EDIT SALE
//     redirect('sale/edit/' . base64_encode($sale_id));
//     return; // ⛔ stop further execution
        
//     }

//       // If moving to awaiting_confirmation, send email to user with PDF
//     if ($next_status == 'awaiting_confirmation') {
//         // Update status first
//         $this->db->where('id', $order_id)
//                  ->update('sale_requests', [
//                      'status'      => $next_status,
//                      'approved_at' => date('Y-m-d H:i:s')
//                  ]);

//         // Call your email function
//         $this->send_simple_mail_to_user($order_id);

//         $this->session->set_flashdata('success', 'Mail sent to user for confirmation.');
//         redirect($_SERVER['HTTP_REFERER']);
//     }
//     // Update the status in sale_requests table
//     $this->db->where('id', $order_id);
//     $this->db->update('sale_requests', [
//         'status'      => $next_status,
//         'approved_at' => ($next_status != 'pending') ? date('Y-m-d H:i:s') : null
//     ]);

//     $this->session->set_flashdata('success', 'Status updated successfully.');
//     redirect($_SERVER['HTTP_REFERER']);
// }
  
public function test_email($order_id)
{
    $result = $this->send_confirmation_email_with_invoice($order_id);
    
    if ($result) {
        echo "✅ Email sent! Check rutuja.axolotls@gmail.com";
    } else {
        $log_file = APPPATH . 'logs/log-' . date('Y-m-d') . '.php';
        echo "❌ Email failed! Log:<br><pre>";
        echo file_get_contents($log_file);
        echo "</pre>";
    }
}

 /*private function send_confirmation_email_with_invoice($order_id)
{
    $order    = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    $items    = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    $customer = $this->customer_model->get_single_record($order->customer_id);
    $company  = $this->company_settings_model->get_company_records();

    if (!$customer || !$company) return false;

    // Build Simple Invoice HTML
    $html  = '<html><head><meta charset="UTF-8"></head><body>';
    $html .= '<h2>' . $company->company_name . '</h2>';
    $html .= '<p>Order Ref: ' . $order->reference_no . '</p>';
    $html .= '<p>Customer: ' . $customer->customer_name . '</p>';
    $html .= '<p>Date: ' . date('d M Y', strtotime($order->invoice_date)) . '</p>';
    $html .= '<table border="1" width="100%" cellpadding="5" cellspacing="0">';
    $html .= '<tr><th>#</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Tax</th><th>Total</th></tr>';

    $i = 1;
   foreach ($items as $item) {
    $html .= '<tr>';
    $html .= '<td>' . $i++ . '</td>';
    $html .= '<td>' . ($item->product_name ?? 'N/A') . '</td>';
    $html .= '<td>' . $item->quantity . ' ' . $item->uom_name . '</td>';
    $html .= '<td>Rs.' . number_format($item->price ?? 0, 2) . '</td>';
    $html .= '<td>Rs.' . number_format($item->igst ?? 0, 2) . '</td>';
    $html .= '<td>Rs.' . number_format($item->subtotal ?? 0, 2) . '</td>';
    $html .= '</tr>';
}

    $html .= '</table>';
    $html .= '<p>Subtotal: Rs.' . number_format($order->total_taxable_value, 2) . '</p>';
    $html .= '<p>Tax: Rs.' . number_format($order->total_tax, 2) . '</p>';
    $html .= '<p>Discount: Rs.' . number_format($order->total_discount, 2) . '</p>';
    $html .= '<h3>Grand Total: Rs.' . number_format($order->total, 2) . '</h3>';
    $html .= '<p>Thank you for your business - ' . $company->company_name . '</p>';
    $html .= '</body></html>';

    // Generate PDF - Fresh Dompdf instance
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Arial');

    $pdf = new \Dompdf\Dompdf($options);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();

    $pdf_output = $pdf->output();

    // Check PDF output is not empty
    if (empty($pdf_output)) {
        log_message('error', 'Dompdf generated empty output for order #' . $order_id);
        return false;
    }

    $pdf_dir      = FCPATH . 'uploads/invoices/';
    $pdf_filename = 'invoice_' . $order->reference_no . '_' . time() . '.pdf';
    $pdf_path     = $pdf_dir . $pdf_filename;

    // Create directory if not exists
    if (!is_dir($pdf_dir)) {
        mkdir($pdf_dir, 0755, true);
    }

    file_put_contents($pdf_path, $pdf_output);

    // Verify file was actually written and not empty
    if (!file_exists($pdf_path) || filesize($pdf_path) == 0) {
        log_message('error', 'PDF file empty or not saved: ' . $pdf_path);
        return false;
    }

    // Send Email
    $this->load->library('email');

    $config = [
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.gmail.com',
        'smtp_port'   => 587,
        'smtp_user'   => 'priyanka.saxolotls@gmail.com',
        'smtp_pass'   => 'vzno caep fnxj adwj',
        'smtp_crypto' => 'tls',
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        'crlf'        => "\r\n"
    ];

    $this->email->initialize($config);
    $this->email->clear(true);

    $this->email->from($config['smtp_user'], $company->company_name);
    $this->email->to($customer->email);
    $this->email->subject('Order Confirmation - ' . $order->reference_no);
    $this->email->message("
        Dear {$customer->customer_name},<br><br>
        Your order <strong>#{$order->reference_no}</strong> is ready.<br>
        Please find the invoice <strong>attached</strong> to this email.<br><br>
        Kindly reply to confirm receipt.<br><br>
        Regards,<br>
        <strong>{$company->company_name}</strong>
    ");

    // Attach PDF
    $this->email->attach($pdf_path, 'attachment', $pdf_filename, 'application/pdf');

    $sent = $this->email->send();

    // Delete temp PDF after sending
    if (file_exists($pdf_path)) {
        unlink($pdf_path);
    }

    if ($sent) {
        log_message('info', "Invoice email sent to {$customer->email} for order #{$order->reference_no}");
        return true;
    } else {
        log_message('error', "Invoice email failed: " . $this->email->print_debugger());
        return false;
    }
}*/
private function send_confirmation_email_with_invoice($order_id)
{
    // ✅ REQUIRED (you commented this earlier)
    $order    = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    $items    = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    foreach ($items as &$item) {

    $price = (float)($item->price ?? 0);
    $qty   = (float)($item->quantity ?? 1);

    $total = $price * $qty;

    $item->tax_amount = ($item->subtotal ?? 0) - $total;

    if ($total > 0) {
        $item->tax_percent = ($item->tax_amount / $total) * 100;
    } else {
        $item->tax_percent = 0;
    }
}
    $customer = $this->customer_model->get_single_record($order->customer_id);
    $company  = $this->company_settings_model->get_company_records();

    if (!$customer || !$company) return false;

    // ✅ Prepare data (same as download_pdf1)
    $data['sale'] = $order;
    $data['sale_items'] = $items;

    if ($order->level_id == 0) {

        $companyData = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
        $user = $this->db->get_where('users', ['id' => $order->added_by])->row();

        $data['customer'] = (object)[
            'first_name'       => $user->first_name ?? '',
            'last_name'        => $user->last_name ?? '',
            'company_name'     => $companyData->company_name ?? '',
            'gstin'            => $companyData->gstin ?? 'N/A',
            'billing_details'  => $companyData->address ?? 'N/A',
            'shipping_details' => $companyData->shipping_address ?? 'N/A',
            'email'            => $customer->email ?? ''
        ];

    } else {

        $branch = $this->db->get_where('clients_branch', ['id' => $order->branch_id])->row();
        $companyData = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
        $user = $this->db->get_where('users', ['id' => $order->added_by])->row();

        $data['customer'] = (object)[
            'first_name'       => $user->first_name ?? '',
            'last_name'        => $user->last_name ?? '',
            'phone'            => $user->phone ?? 'N/A',
            'company_name'     => $companyData->company_name ?? '',
            'company_gst'      => $companyData->gstin ?? 'N/A',
            'company_add'      => $companyData->address ?? 'N/A',
            'gstin'            => $branch->gstin ?? 'N/A',
            'address'          => $user->address ?? 'N/A',
            'branch'           => $branch->branch_name ?? 'N/A',
            'billing_details'  => $branch->billing_details ?? 'N/A',
            'shipping_details' => $branch->shipping_details ?? 'N/A',
            'email'            => $customer->email ?? ''
        ];
    }

    $data['company_setting'] = $company;

    // ✅ Load SAME PDF view
    $html = $this->load->view('purchase_requests/invoice_pdf', $data, true);

    // ✅ Generate PDF
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $pdf = new \Dompdf\Dompdf($options);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();

    $pdf_output = $pdf->output();

    if (empty($pdf_output)) {
        log_message('error', 'PDF empty for order #' . $order_id);
        return false;
    }

    // ✅ Save PDF
    $pdf_dir = FCPATH . 'uploads/invoices/';
    if (!is_dir($pdf_dir)) {
        mkdir($pdf_dir, 0755, true);
    }

    $pdf_filename = 'invoice_' . $order->reference_no . '_' . time() . '.pdf';
    $pdf_path = $pdf_dir . $pdf_filename;

    file_put_contents($pdf_path, $pdf_output);

    if (!file_exists($pdf_path) || filesize($pdf_path) == 0) {
        log_message('error', 'PDF not saved: ' . $pdf_path);
        return false;
    }

    // ✅ Send Email
    $this->load->library('email');

    $config = [
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.gmail.com',
        'smtp_port'   => 587,
        'smtp_user'   => 'priyanka.saxolotls@gmail.com',
        'smtp_pass'   => 'vzno caep fnxj adwj',
        'smtp_crypto' => 'tls',
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        'crlf'        => "\r\n"
    ];

    $this->email->initialize($config);
    $this->email->clear(true);

    $this->email->from($config['smtp_user'], $company->company_name);
    $this->email->to($customer->email);

    $this->email->subject('Order Confirmation - ' . $order->reference_no);

    $this->email->message("
        Dear {$customer->customer_name},<br><br>
        Your order <strong>#{$order->reference_no}</strong> is ready.<br>
        Please find the invoice attached.<br><br>
        Regards,<br>
        <strong>{$company->company_name}</strong>
    ");

    // ✅ Attach PDF
    $this->email->attach($pdf_path);

    $sent = $this->email->send();

    // ✅ Delete file
    if (file_exists($pdf_path)) {
        unlink($pdf_path);
    }

    if ($sent) {
        log_message('info', "Invoice sent to {$customer->email}");
        return true;
    } else {
        log_message('error', $this->email->print_debugger());
        return false;
    }
}
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
/**
 * Build order items HTML
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
    // Create table if not exists
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
public function debug_send_status_email($order_id)
{
    echo "<h2>Debugging send_status_email for Order #{$order_id}</h2>";
    
    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) {
        echo "Order not found!";
        return;
    }
    
    echo "<h3>Step 1: Check order items</h3>";
    $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result();
    echo "Found " . count($order_items) . " items<br>";
    
    echo "<h3>Step 2: Build order items HTML</h3>";
    $items_html = $this->build_order_items_html($order_items);
    echo "Items HTML length: " . strlen($items_html) . "<br>";
    
    echo "<h3>Step 3: Get template</h3>";
    $template = $this->email_template_model->get_record_by_module('order_approved');
    if ($template) {
        echo "Template found: " . $template->template_name . "<br>";
        echo "Subject: " . $template->subject . "<br>";
        echo "From Email: " . $template->from_email . "<br>";
    } else {
        echo "Template NOT found!<br>";
        return;
    }
    
    echo "<h3>Step 4: Build email content</h3>";
    $recipient_email = $this->get_branch_email($order);
    $recipient_name = $this->get_branch_name($order);
    $company = $this->db->get_where('clients_company', ['id' => $order->company_id])->row();
    $company_name = $company->company_name ?? 'OSS System';
    $remarks = 'Your order has been approved.';
    
    $replacements = [
        '{{reference_no}}' => $order->reference_no,
        '{{recipient_name}}' => $recipient_name,
        '{{company_name}}' => $company_name,
        '{{remarks}}' => nl2br(htmlspecialchars($remarks)),
        '{{order_date}}' => date('d-m-Y', strtotime($order->invoice_date)),
        '{{total_amount}}' => number_format($order->total, 2),
        '{{status}}' => 'Approved',
        '{{order_items}}' => $items_html,
        '{{branch_name}}' => $recipient_name
    ];
    
    $body = $template->mail_header . $template->mail_body . $template->mail_footer;
    $subject = $template->subject;
    
    foreach ($replacements as $key => $value) {
        $body = str_replace($key, $value, $body);
        $subject = str_replace($key, $value, $subject);
    }
    
    echo "Subject: " . $subject . "<br>";
    echo "Body length: " . strlen($body) . "<br>";
    
    echo "<h3>Step 5: Initialize email</h3>";
    $this->load->library('email');
    $email_setup = $this->email_setup_model->get_email_records();
    
    $config = [
        'protocol' => 'smtp',
        'smtp_host' => $email_setup->host,
        'smtp_port' => $email_setup->port,
        'smtp_user' => $email_setup->username,
        'smtp_pass' => $email_setup->password,
        'smtp_crypto' => $email_setup->encryption,
        'mailtype' => 'html',
        'charset' => 'utf-8',
        'newline' => "\r\n",
        'crlf' => "\r\n"
    ];
    
    $this->email->initialize($config);
    $this->email->clear();
    
    $this->email->from($template->from_email, $template->from_name);
    $this->email->to($recipient_email);
    
    echo "From: " . $template->from_email . "<br>";
    echo "To: " . $recipient_email . "<br>";
    
    $this->email->subject($subject);
    $this->email->message($body);
    
    echo "<h3>Step 6: Try to send</h3>";
    
    if ($this->email->send()) {
        echo "<p style='color:green'>✅ Email sent successfully!</p>";
    } else {
        echo "<p style='color:red'>❌ Email failed!</p>";
        echo "<h4>Error Details:</h4>";
        echo "<pre style='background:#f8f9fa;padding:15px;border:1px solid #ddd;overflow:auto;max-height:500px;'>";
        echo htmlspecialchars($this->email->print_debugger());
        echo "</pre>";
    }
}
public function update_status()
{
    $order_id = $this->input->post('order_id');
    $action   = $this->input->post('action');

    if (!$order_id || !$action) {
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) {
        $this->session->set_flashdata('error', 'Sale request not found.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    $current_status = $order->status;
    
    // Handle Rejection
    if ($action == 'reject') {
        $remark = $this->input->post('rejection_remark');
        
        $update_data = [
            'status' => 'rejected',
            'order_status' => 'rejected',
            'approved_at' => NULL,
            'rejection_remarks' => $remark
        ];
        $this->db->where('id', $order_id)->update('sale_requests', $update_data);
        
        $email_sent = $this->send_status_email($order_id, 'rejected');
        
        if ($email_sent) {
            $this->session->set_flashdata('success', 'Order rejected successfully and email notification sent.');
        } else {
            $this->session->set_flashdata('warning', 'Order rejected but email notification failed.');
        }
        
        redirect('sales_request');
    }
    
    // Handle Next Status
    if ($action == 'next') {
        $status_flow = [
            'pending'        => 'approved',
            'approved'       => 'create_invoice',
            'create_invoice' => 'courier',
            'courier'        => 'in_transit',
            'in_transit'     => 'delivered',
            'delivered'      => 'awaiting_confirmation',
            'awaiting_confirmation' => 'delivery_confirmed',
        ];
        
        if (!isset($status_flow[$current_status])) {
            $this->session->set_flashdata('error', 'Cannot move to next status.');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        $next_status = $status_flow[$current_status];
        
        // Handle Pending -> Approved (with approved_at timestamp)
        if ($current_status == 'pending' && $next_status == 'approved') {
            $update_data = [
                'status' => $next_status,
                'approved_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->where('id', $order_id)->update('sale_requests', $update_data);
            $email_sent = $this->send_status_email($order_id, 'approved');
            
            if ($email_sent) {
                $this->session->set_flashdata('success', 'Order approved successfully and email notification sent.');
            } else {
                $this->session->set_flashdata('warning', 'Order approved but email notification failed.');
            }
            
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        // Special handling for create_invoice
        if ($next_status == 'create_invoice') {
            $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result_array();
            
            if (empty($order_items)) {
                $this->session->set_flashdata('error', 'No items found to create invoice.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            $sale_id = $this->sale_model->create_sale_from_request($order, $order_items);
            
            if (!$sale_id) {
                $this->session->set_flashdata('error', 'Invoice creation failed.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            // ✅ FIXED: Define $update_data
            $update_data = ['status' => $next_status];
            $this->db->where('id', $order_id)->update('sale_requests', $update_data);
            redirect('sale/edit/' . base64_encode($sale_id));
            return;
        }
        
        // Special handling for awaiting_confirmation
        if ($next_status == 'awaiting_confirmation') {
            // ✅ FIXED: Define $update_data
            $update_data = ['status' => $next_status];
            $this->db->where('id', $order_id)->update('sale_requests', $update_data);
            $this->send_confirmation_email_with_invoice($order_id);
            $this->session->set_flashdata('success', 'Mail sent to user for confirmation.');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        // Normal update for other statuses
        $update_data = ['status' => $next_status];
        $this->db->where('id', $order_id)->update('sale_requests', $update_data);
        $this->session->set_flashdata('success', 'Status updated successfully.');
        redirect($_SERVER['HTTP_REFERER']);
    }
}
public function update_status_old4()
{
    $order_id = $this->input->post('order_id');
    $action   = $this->input->post('action');

    if (!$order_id || !$action) {
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) {
        $this->session->set_flashdata('error', 'Sale request not found.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    $current_status = $order->status;
    
   /* if ($action == 'reject') {
        $update_data = [
            'status' => 'rejected',
            'approved_at' => NULL // Clear if previously set
        ];
        $this->db->where('id', $order_id)->update('sale_requests', $update_data);
        $this->session->set_flashdata('success', 'Order rejected successfully.');
        redirect($_SERVER['HTTP_REFERER']);
    }*/

      /* if ($action == 'reject') {
    $remark = $this->input->post('rejection_remarks');
    
    $update_data = [
        'status' => 'rejected',
        'order_status' => 'rejected',
        'approved_at' => NULL,
        'rejection_remarks' => $remark
    ];
    $this->db->where('id', $order_id)->update('sale_requests', $update_data);
    $this->session->set_flashdata('success', 'Order rejected successfully.');
            //redirect($_SERVER['HTTP_REFERER']);

    // Redirect to Sales_request list page
   redirect('sales_request');
}*/
if ($action == 'reject') {
    $remark = $this->input->post('rejection_remarks');
    
    $update_data = [
        'status' => 'rejected',
        'order_status' => 'rejected',
        'approved_at' => NULL,
        'rejection_remarks' => $remark
    ];
    $this->db->where('id', $order_id)->update('sale_requests', $update_data);
    
    // Get recipient email to show in message
    $recipient_email = $this->get_branch_email($order);
    $email_sent = $this->send_status_email($order_id, 'rejected');
    
    if ($email_sent) {
        $this->session->set_flashdata('success', 'Order rejected successfully.');
    } else {
        $this->session->set_flashdata('warning', 'Order rejected.');
    }
    
    redirect('sales_request');
}
    
    if ($action == 'next') {
        $status_flow = [
            'pending'        => 'approved',
            'approved'       => 'create_invoice',
            'create_invoice' => 'courier',
            'courier'        => 'in_transit',
            'in_transit'     => 'delivered',
            'delivered'      => 'awaiting_confirmation',
            'awaiting_confirmation' => 'delivery_confirmed',
        ];
        
        if (!isset($status_flow[$current_status])) {
            $this->session->set_flashdata('error', 'Cannot move to next status.');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        $next_status = $status_flow[$current_status];
        
        // ✅ CRITICAL: Set approved_at ONLY when moving from 'pending' to 'approved'
        // This is when OSS/admin clicks the "Approve" button
        /*if ($current_status == 'pending' && $next_status == 'approved') {
            $update_data = [
                'status' => $next_status,
                'approved_at' => date('Y-m-d H:i:s')
            ];
        } else {
            // For all other status changes, don't touch approved_at
            $update_data = ['status' => $next_status];
        }*/
         if ($current_status == 'pending' && $next_status == 'approved') {
    $update_data = [
        'status' => $next_status,
        'approved_at' => date('Y-m-d H:i:s')
    ];
    
    $this->db->where('id', $order_id)->update('sale_requests', $update_data);
    
    // Get recipient email to show in message
    $recipient_email = $this->get_branch_email($order);
    $email_sent = $this->send_status_email($order_id, 'approved');
    
    if ($email_sent) {
        $this->session->set_flashdata('success', 'Order approved successfully.');
    } else {
        $this->session->set_flashdata('warning', 'Order approved..');
    }
    
    redirect($_SERVER['HTTP_REFERER']);
}
        
        // Special handling for create_invoice
        if ($next_status == 'create_invoice') {
            $order_items = $this->db->get_where('sale_request_items', ['sale_id' => $order_id])->result_array();
            
            if (empty($order_items)) {
                $this->session->set_flashdata('error', 'No items found to create invoice.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            $sale_id = $this->sale_model->create_sale_from_request($order, $order_items);
            
            if (!$sale_id) {
                $this->session->set_flashdata('error', 'Invoice creation failed.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            $this->db->where('id', $order_id)->update('sale_requests', $update_data);
            redirect('sale/edit/' . base64_encode($sale_id));
            return;
        }
        
        // Special handling for awaiting_confirmation
        if ($next_status == 'awaiting_confirmation') {
            $this->db->where('id', $order_id)->update('sale_requests', $update_data);
            //$this->send_simple_mail_to_user($order_id);
          $this->send_confirmation_email_with_invoice($order_id);
            $this->session->set_flashdata('success', 'Mail sent to user for confirmation.');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        // Normal update for other statuses
        $this->db->where('id', $order_id)->update('sale_requests', $update_data);
        $this->session->set_flashdata('success', 'Status updated successfully.');
        redirect($_SERVER['HTTP_REFERER']);
    }
}

 


private function send_simple_mail_to_user($order_id)
{
    // Fetch order & customer
    $order = $this->db->get_where('sale_requests', ['id' => $order_id])->row();
    if (!$order) return false;

    $customer = $this->customer_model->get_single_record($order->customer_id);
    $company  = $this->company_settings_model->get_company_records();

    if (!$customer || !$company) return false;

    // Load Email library
    $this->load->library('email');

    // SMTP config (use your credentials)
    $config = [
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.gmail.com',
        'smtp_port'   => 587,
        'smtp_user'   => 'priyanka.saxolotls@gmail.com', // CHANGE
        'smtp_pass'   => 'vzno caep fnxj adwj',          // CHANGE
        'smtp_crypto' => 'tls',
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        'crlf'        => "\r\n"
    ];

    $this->email->initialize($config);
    $this->email->clear(true);

    // Compose mail
    $this->email->from($config['smtp_user'], $company->company_name);
    $this->email->to('priyankashelke0705@gmail.com');
    $this->email->subject('Order Ready - ' . $order->reference_no);
    $this->email->message(
        "Dear {$customer->customer_name},<br><br>
        Your order #{$order->reference_no} is ready. Please confirm it with {$company->company_name}.<br><br>
        Regards,<br>{$company->company_name}"
    );

    // Send email
    if ($this->email->send()) {
        log_message('info', "Simple mail sent to {$customer->email} for order #{$order->reference_no}");
        return true;
    } else {
        log_message('error', "Simple mail failed: " . $this->email->print_debugger());
        return false;
    }
}


 


public function save_courier_details()
{
//     echo '<pre>';
// print_r($_POST);
// exit;
    // Get the order ID from POST
    $id = $this->input->post('order_id');

    // Get selected delivery mode
    $mode = $this->input->post('delivery_mode');

    // Prepare data array
    $data = [
        'delivery_mode' => $mode,
        'status' => 'in_transit' // mark as in_transit
    ];

    // Physical delivery fields
    if ($mode == 'physical') {
        $data['physical_date'] = $this->input->post('physical_date');
        $data['physical_person'] = $this->input->post('physical_person');
    }

    // Courier fields
    if ($mode == 'courier') {
        $data['courier_date'] = $this->input->post('courier_date');
        $data['courier_partner'] = $this->input->post('courier_partner');
        $data['docket_number'] = $this->input->post('docket_number');
         $data['tracking_link']   = $this->input->post('tracking_link');
    }

    // Vendor fields
    if ($mode == 'vendor') {
        $data['vendor_name'] = $this->input->post('vendor_name');
        $data['vendor_city'] = $this->input->post('vendor_city');
        $data['delivery_via'] = $this->input->post('delivery_via');
        $data['vendor_tracking_link'] = $this->input->post('vendor_tracking_link');
         
    }

    // Update the sale_requests table
    $this->db->where('id', $id);
    $this->db->update('sale_requests', $data);

    // Set flash message
    $this->session->set_flashdata('success', 'Courier details updated successfully.');

    // Redirect back
    redirect($_SERVER['HTTP_REFERER']);
}

// public function test_upload()
// {
//     $path = FCPATH . 'uploads/test.txt';
//     if (file_put_contents($path, 'test')) {
//         echo 'WRITE SUCCESS';
//         unlink($path);
//     } else {
//         echo 'WRITE FAILED';
//     }
// }

	public function add()
	{
		if(!$this->permission_model->has_permission('add_sale'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('invoice_date','Invoice Date','required');		
				$this->form_validation->set_rules('customer_id','Customer','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['customers'] 		    = $this->customer_model->get_records();
					$data['warehouses'] 	    = $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('sale/add',$data);
				}
				else
				{
					$invoice_date 				= date('Y-m-d', strtotime($this->input->post('invoice_date')));
					$warehouse_id 				= $this->input->post('warehouse_id');
					$reference_no 				= $this->sale_model->get_lastest_sequence_number($warehouse_id);
					$customer_id 				= $this->input->post('customer_id');
					$customer 					= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;
					$proforma_invoice_id_array  = $this->input->post('proforma_invoice_id');

			if (!is_array($proforma_invoice_id_array)) {
                $proforma_invoice_id_array = !empty($proforma_invoice_id_array) ? explode(',', $proforma_invoice_id_array) : [];
            }
    
					$proforma_invoice_id                        = implode(",", $proforma_invoice_id_array);
					$customer_shipping_country_id 		    	= $this->input->post('customer_shipping_country_id');
                    $customer_shipping_state_id 				= $this->input->post('customer_shipping_state_id');
                    $customer_shipping_city_id 					= $this->input->post('customer_shipping_city_id');
                    $customer_shipping_address 					= $this->input->post('customer_shipping_address');
                    $customer_shipping_pincode 					= $this->input->post('customer_shipping_pincode');
					$rcm 									    = $this->input->post('rcm');
					$total_taxable_value                    	= $this->input->post('total_taxable_value');
					$tds 									    = $this->input->post('tds');
					$total_discount 			                = $this->input->post('total_discount');
					$total_tax 						            = $this->input->post('total_tax');
					$total 							        	= $this->input->post('total');
					$internal_note 			                	= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 			                	= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$bank_detail 				            	= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition                    	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                    $lori_no 						        	= $this->input->post('lori_no');
					$carrier 						        	= $this->input->post('carrier');
					$vehicle_no 					            = $this->input->post('vehicle_no');
					$ewaybill_no 				            	= $this->input->post('ewaybill_no');
					$additional_case 		                	= $this->input->post('additional_case');
                    $ewaybill_dispatch_from 					= $this->input->post('ewaybill_dispatch_from');
					$ewaybill_mode_of_transportation 			= $this->input->post('ewaybill_mode_of_transportation');
					$ewaybill_subtype 							= $this->input->post('ewaybill_subtype');
					$ewaybill_doctype 							= $this->input->post('ewaybill_doctype');
					$ewaybill_transporter_name 					= $this->input->post('ewaybill_transporter_name');
					$ewaybill_transporter_gstin 				= $this->input->post('ewaybill_transporter_gstin');
					$ewaybill_distance_of_transportation 	    = $this->input->post('ewaybill_distance_of_transportation');
					$ewaybill_transporter_doc_no 				= $this->input->post('ewaybill_transporter_doc_no');
					$ewaybill_vehicle_no 						= $this->input->post('ewaybill_vehicle_no');
					$ewaybill_vehicle_type 						= $this->input->post('ewaybill_vehicle_type');
                    $available_credit 		                    = $this->input->post('available_credit');
					$due_date 						        	= ($this->input->post('due_date') != NULL && $this->input->post('due_date') != '') ? date('Y-m-d',strtotime($this->input->post('due_date'))) : NULL;
					$document 					            	= $this->input->post("document");

          if (!is_array($document))
             {
                $document = array($document);
             }
          
         require_once FCPATH . 'application/libraries/phpqrcode/qrlib.php'; 
         
        $last_sale_id = $this->db->select_max('id')->get('sale')->row()->id;
        
        $new_sale_id = $last_sale_id + 1;
        
        $encoded_id = base64_encode($new_sale_id);
        
        $qr_data = base_url('Qr_sale/view_qr_sale/' . $encoded_id);
        
        $qr_filename = uniqid('qr_') . '.png';
        $qr_path = FCPATH . 'uploads/qr_codes/' . $qr_filename;
        
        if (!is_dir(FCPATH . 'uploads/qr_codes')) {
            if (!mkdir(FCPATH . 'uploads/qr_codes', 0777, true)) {
                log_message('error', 'Failed to create uploads/qr_codes directory');
                exit;
            }
        }
        
        QRcode::png($qr_data, $qr_path, QR_ECLEVEL_L, 10, 2);
        
       if (file_exists($qr_path)) {
    $image_info = getimagesize($qr_path); // Alternative to exif_imagetype()
    
    if ($image_info && $image_info[2] === IMAGETYPE_PNG) { // [2] contains the image type
        log_message('info', "QR Code successfully generated and saved: $qr_path");
        $sale_data['qr'] = $qr_filename;
    } else {
        log_message('error', 'QR Code generation failed or is not a valid image.');
    }
}

          $documents = implode(',', $document);

					$sale_data = array(
										"invoice_date"				    => $invoice_date,
										"due_date"						=> $due_date,
										"reference_no"				    => $reference_no,
										"proforma_invoice_id"	        => $proforma_invoice_id,
										"warehouse_id"	  		        => $warehouse_id,
										"customer_id"					=> $customer_id,
										"customer_gstin"		    	=> $customer_gstin,
										"customer_shipping_country_id"			=> $customer_shipping_country_id,
                                        "customer_shipping_state_id"				=> $customer_shipping_state_id,
                                        "customer_shipping_city_id"					=> $customer_shipping_city_id,
                                        "customer_shipping_address"					=> $customer_shipping_address,
                                        "customer_shipping_pincode"					=> $customer_shipping_pincode,
										"rcm"									=> $rcm,
										"total_taxable_value" => $total_taxable_value,	
										"tds" 								=> $tds,	
										"total_tax"						=> $total_tax,
										"total_discount" 			=> $total_discount,	
										"total" 							=> $total,
										"internal_note"				=> $internal_note,
										"external_note" 			=> $external_note,
										"bank_detail" 				=> $bank_detail,
										"terms_and_condition" => $terms_and_condition,
										"document" 				=> $documents,
                                         "lori_no" 						=> $lori_no,
										"carrier" 						=> $carrier,
										"vehicle_no" 					=> $vehicle_no,
										"ewaybill_no" 				=> $ewaybill_no,
										"additional_case" 		=> $additional_case,
                                        "qr"                    => $sale_data['qr'],
										"user_id" 						=> $this->session->userdata('user_id')
									);


                if (!empty($ewaybill_dispatch_from)) {
                    $sale_data['ewaybill_dispatch_from'] = $ewaybill_dispatch_from;
                }
                
                if (!empty($ewaybill_mode_of_transportation)) {
                    $sale_data['ewaybill_mode_of_transportation'] = $ewaybill_mode_of_transportation;
                }
                
                if (!empty($ewaybill_subtype)) {
                    $sale_data['ewaybill_subtype'] = $ewaybill_subtype;
                }
                
                if (!empty($ewaybill_doctype)) {
                    $sale_data['ewaybill_doctype'] = $ewaybill_doctype;
                }
                
                if (!empty($ewaybill_transporter_name)) {
                    $sale_data['ewaybill_transporter_name'] = $ewaybill_transporter_name;
                }
                
                if (!empty($ewaybill_transporter_gstin)) {
                    $sale_data['ewaybill_transporter_gstin'] = $ewaybill_transporter_gstin;
                }
                
                if (!empty($ewaybill_distance_of_transportation)) {
                    $sale_data['ewaybill_distance_of_transportation'] = $ewaybill_distance_of_transportation;
                }
                
                if (!empty($ewaybill_transporter_doc_no)) {
                    $sale_data['ewaybill_transporter_doc_no'] = $ewaybill_transporter_doc_no;
                }
                
                if (!empty($ewaybill_vehicle_no)) {
                    $sale_data['ewaybill_vehicle_no'] = $ewaybill_vehicle_no;
                }
                
                if (!empty($ewaybill_vehicle_type)) {
                    $sale_data['ewaybill_vehicle_type'] = $ewaybill_vehicle_type;
                }


          $lori_date 				= $this->input->post('lori_date');
          if($lori_date != '')
            $sale_data['lori_date'] = date('Y-m-d',strtotime($lori_date));

          $ewaybill_transporter_doc_date 				= $this->input->post('ewaybill_transporter_doc_date');
          if($ewaybill_transporter_doc_date != '')
            $sale_data['ewaybill_transporter_doc_date'] = date('Y-m-d',strtotime($ewaybill_transporter_doc_date));

					// being transaction
					$this->db->trans_begin();
                    // echo '<pre>';
                    // var_dump($sale_data);die();
					if($id = $this->sale_model->add_sale_record($sale_data))
					{
						for ($i=0; $i < sizeof($proforma_invoice_id_array); $i++) { 
							$this->proforma_invoice_model->edit_proforma_invoice_record(array('sale_id'=>$id),$proforma_invoice_id_array[$i]);	
						}

						$entered_sale    	= $this->sale_model->get_sale_single_record($id);
						$customer 			= $this->customer_model->get_single_record($entered_sale->customer_id);

						// update the ledgers
						
						/****************************** Start Sale Ledger ***********************************/

						$sale_ledger 								= $this->ledger_model->get_single_record(SALE_LEDGER);
						$updated_sale_ledger 				= array('closing_balance' => ($sale_ledger->closing_balance + $total));
						$this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);

						/****************************** End Sale Ledger ***********************************/

						/****************************** Start Customer Ledger ***********************************/

						$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
						$updated_customer_ledger    = array('closing_balance' => ($customer_ledger->closing_balance + $total));
						$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

						/****************************** End Customer Ledger ***********************************/

						/****************************** Start TDS Ledger ***********************************/

						if($tds > 0)
						{
							$tds_ledger 			= $this->ledger_model->get_single_record(TDS_LEDGER);
							$updated_tds_ledger    	= array('closing_balance' => ($tds_ledger->closing_balance + $tds));
							$this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

							$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
							$updated_customer_ledger 		= array('closing_balance' => ($customer_ledger->closing_balance - $tds));
							$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
						}

						
						/****************************** End TDS Ledger ***********************************/

						/****************************** Add Transaction entry *********************************/
                         
						$transaction_header = array(
												"entry_id"				    =>  $id,
												"module"					=>  SALE_MODULE,
												"type"						=>	SALE_TRANSACTION_TYPE,
												"amount"					=>	$total,
												"voucher_date"		        =>	$invoice_date,
												"from_account"	        	=>	SALE_LEDGER,
												"to_account"		     	=>	$customer->ledger_id,
												"reference_no"		        =>	$reference_no,
												"warehouse_id"              =>  $warehouse_id
											);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> SALE_LEDGER,
																								"dr_amount"				=> $total
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> $customer->ledger_id,
																							"cr_amount"				=> $total
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/


						/****************************** Add TDS Transaction entry *********************************/

						if($tds > 0)
						{
							$transaction_header = array(
																					"entry_id"			     	=>  $id,
																					"module"					=>  SALE_MODULE,
																					"type"						=>	TDS_TRANSACTION_TYPE,
																					"amount"					=>	$tds,
																					"voucher_date"		        =>	$invoice_date,
																					"from_account"	        	=>	$customer->ledger_id,
																					"to_account"			    =>	TDS_LEDGER,
																					"reference_no"		        =>	$reference_no,
																					"warehouse_id"              => $warehouse_id
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$from_transaction_detail = array(
																									"transaction_id" 	=> $transaction_id,
																									"voucher_type"		=> 'C',
																									"ledger_id"				=> $customer->ledger_id,
																									"dr_amount"				=> $tds
																								);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

								$to_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'D',
																								"ledger_id"				=> TDS_LEDGER,
																								"cr_amount"				=> $tds
																							);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
							}	
						}
						
						/**************************************************************************************/

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "sale",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_sale),
											"description" => 'Sales (Reference No. -'  .$reference_no .') is added successfully.'
										);
						$this->log_data_model->add_record($log_data);

						$sale_items 						= $this->input->post('sale_items');
						$sale_items_array 			= explode("|", $this->input->post('sale_items'));

						for ($i=0; $i < sizeof($sale_items_array) ; $i++) { 
									
							$temp_sale_item = (array)json_decode($sale_items_array[$i]);
							$temp_sale_item['sale_id']  = $id;

							if($temp_sale_item['expiry_date'] == '' || $temp_sale_item['expiry_date'] == '0000-00-00')
								unset($temp_sale_item['expiry_date']);
							else
								$temp_sale_item['expiry_date'] = date('Y-m-d',strtotime($temp_sale_item['expiry_date']));

							if($temp_sale_item['mfg_date'] == '' || $temp_sale_item['mfg_date'] == '0000-00-00')
								unset($temp_sale_item['mfg_date']);
							else
								$temp_sale_item['mfg_date'] = date('Y-m-d',strtotime($temp_sale_item['mfg_date']));

							if($temp_sale_item['proforma_invoice_idd'] == '')
								unset($temp_sale_item['proforma_invoice_idd']);
							

							$this->sale_model->add_sale_item_record($temp_sale_item);
						}
							$credit_amount = 0;
							$available_credit = ($available_credit < 0) ? abs($available_credit) : $available_credit;

							if($available_credit >= ($total-$tds))
								$this->transaction_model->add_transaction($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE,$total-$tds,$invoice_date,CREDIT_MODE,'','',$customer_ledger->id,'','');	
							else
								$this->transaction_model->add_transaction($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE,$available_credit,$invoice_date,CREDIT_MODE,'','',$customer_ledger->id,'','');	
						
						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Sales (Reference No. -'  .$reference_no .') is added successfully.');
						redirect('sale','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						
						$this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$reference_no .') is failed to add.');
						redirect('sale','refresh');
					}

				}
			}
			else
			{
				$data['countries'] 			= $this->utility_model->get_countries();
				$data['customers'] 			= $this->customer_model->get_records();
				$data['warehouses'] 	    = $this->warehouse_model->get_records();
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
                $data['states'] 		    = $this->utility_model->get_states(101);
				$this->load->view('sale/add',$data);
			}
		}
	}


}

