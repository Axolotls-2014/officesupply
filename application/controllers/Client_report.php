<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Client_report extends MY_Controller 
{
	private $pdf;

	public function __construct()
	{
		parent::__construct();
		$this->pdf = new Dompdf();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}

//   public function purchase()
//     {
//         $user_id = $this->session->userdata('user_id');
//         $user_role = $this->db->select('role')->where('id', $user_id)->get('users')->row('role');
    
//         if ($this->input->server('REQUEST_METHOD') === 'POST') {
//             $from_date = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
//             $to_date   = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
    
//             $sql = "SELECT * FROM sale_requests WHERE 1=1";
    
//             // Role-based filtering for client
//             if ($user_role == 'client') {
//                 $added_users = $this->db->select('id')->where('added_by', $user_id)->get('users')->result_array();
//                 $added_user_ids = array_column($added_users, 'id');
//                 $all_ids = array_merge($added_user_ids, [$user_id]);
    
//                 $id_list = implode(",", array_map('intval', $all_ids)); // Sanitize
//                 $sql .= " AND added_by IN ($id_list)";
//             } else {
//                 $sql .= " AND added_by = " . intval($user_id);
//             }
    
//             if (!empty($from_date)) {
//                 $sql .= " AND created_date >= '" . $from_date . "'";
//             }
//             if (!empty($to_date)) {
//                 $sql .= " AND created_date <= '" . $to_date . "'";
//             }
//             $query = $this->db->query($sql);
//             $sale_data['purchases'] = $query->result();
//             $sale_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
//             $sale_data['to_date']   = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
//             $sale_data['company_setting'] = $this->db->get('company_setting')->row();
    
//             $this->load->view('purchase_requests/client_report', $sale_data);
//         } else {
//             if ($user_role == 'client') {
//                 $added_users = $this->db->select('id')->where('added_by', $user_id)->get('users')->result_array();
//                 $added_user_ids = array_column($added_users, 'id');
//                 $all_ids = array_merge($added_user_ids, [$user_id]);
    
//                 $this->db->where_in('added_by', $all_ids);
//                 $data['purchases'] = $this->db->get('sale_requests')->result();
//             } else {
//                 $data['purchases'] = $this->db->where('added_by', $user_id)->get('sale_requests')->result();
//             }
    
//             $data['total_purchase']    = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
//             $data['total_paid_amount'] = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
//             $data['from_date']         = '';
//             $data['to_date']           = '';
    
//             $this->load->view('purchase_requests/client_report', $data);
//         }
//     }

 public function purchase()
 {
    $user_id    = $this->session->userdata('user_id');
    $level_id   = $this->session->userdata('level_id');
    $company_id = $this->session->userdata('company_id');
    $user_role  = $this->db->select('role')->where('id', $user_id)->get('users')->row('role');
    $this->db->where('added_by', $user_id);
    $data['branches'] = $this->db->get('clients_branch')->result();
    $sql = "SELECT * FROM sale_requests WHERE company_id = " . intval($company_id);
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');
        $branch_id = $this->input->post('branch_id');
        $sub_branch_id = $this->input->post('sub_branch_id');
        $from_date = ($from_date == '') ? '' : date('Y-m-d', strtotime($from_date));
        $to_date   = ($to_date == '') ? '' : date('Y-m-d', strtotime($to_date));

        if (!empty($from_date)) {
            $sql .= " AND created_date >= '" . $from_date . "'";
        }
        if (!empty($to_date)) {
            $sql .= " AND created_date <= '" . $to_date . " 23:59:59'";
        }
    }
    
     if ($level_id == 0) { 
        if (!empty($sub_branch_id)) {
            $sql .= " AND branch_id = " . intval($sub_branch_id);
        } elseif (!empty($branch_id)) {
            $sql .= " AND branch_id = " . intval($branch_id);
        }
    
    } elseif ($level_id == 1) { 
        $user_branch_id = $this->session->userdata('branch_id'); 
    
        if (!empty($branch_id)) {
            $sql .= " AND branch_id = " . intval($branch_id);
        } else {
            $sql .= " AND branch_id = " . intval($user_branch_id);
        }
    
    } elseif ($level_id == 2) { 
        $user_branch_id = $this->session->userdata('branch_id');
    
        if (!empty($sub_branch_id)) {
            $sql .= " AND branch_id = " . intval($sub_branch_id);
        } else {
            $sql .= " AND branch_id = " . intval($user_branch_id);
        }
    }


    $query = $this->db->query($sql);
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $sale_data['purchases'] = $query->result();
        $sale_data['from_date'] = (!empty($from_date)) ? date('d-m-Y', strtotime($from_date)) : '';
        $sale_data['to_date']   = (!empty($to_date)) ? date('d-m-Y', strtotime($to_date)) : '';
        $sale_data['company_setting'] = $this->db->get('company_setting')->row();
        $sale_data['branches'] = $data['branches'];
        $sale_data['user_role'] = $user_role;
        $sale_data['post_data'] = $this->input->post();
        $this->load->view('purchase_requests/client_report', $sale_data);
        return;
    } else {
        $data['purchases'] = $query->result();
    }

    $data['total_purchase']    = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
    $data['total_paid_amount'] = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
    $data['from_date']         = '';
    $data['to_date']           = '';
    $data['user_role']         = $user_role;

    $this->load->view('purchase_requests/client_report', $data);
}

 

private function get_all_user_ids($current_user_id, $branch_id = null, $sub_branch_id = null)
{
    $user_ids = [$current_user_id];
    $this->db->select('id');
    $this->db->where('added_by', $current_user_id);
    $sub_users = $this->db->get('users')->result_array();
    
    foreach ($sub_users as $user) {
        $user_ids[] = $user['id'];
        
        // Recursively get sub-users of sub-users
        $this->db->select('id');
        $this->db->where('added_by', $user['id']);
        $sub_sub_users = $this->db->get('users')->result_array();
        
        foreach ($sub_sub_users as $sub_user) {
            $user_ids[] = $sub_user['id'];
        }
    }
  
    if ($branch_id) {
        $branch_user_ids = [];
        
        $this->db->select('id');
        $this->db->where('clients_branch_id', $branch_id);
        $branch_users = $this->db->get('users')->result_array();
        
        foreach ($branch_users as $user) {
            $branch_user_ids[] = $user['id'];
            
            $this->db->select('id');
            $this->db->where('added_by', $user['id']);
            $sub_users = $this->db->get('users')->result_array();
            
            foreach ($sub_users as $sub_user) {
                $branch_user_ids[] = $sub_user['id'];
            }
        }
        
        $user_ids = array_intersect($user_ids, $branch_user_ids);
      
    }
    
    if ($sub_branch_id) {
        $sub_branch_user_ids = [];
        
        $this->db->select('id');
        $this->db->where('clients_branch_id', $sub_branch_id);
        $sub_branch_users = $this->db->get('users')->result_array();
        
        foreach ($sub_branch_users as $user) {
            $sub_branch_user_ids[] = $user['id'];
            
            $this->db->select('id');
            $this->db->where('added_by', $user['id']);
            $sub_users = $this->db->get('users')->result_array();
            
            foreach ($sub_users as $sub_user) {
                $sub_branch_user_ids[] = $sub_user['id'];
            }
        }
        
        $user_ids = array_intersect($user_ids, $sub_branch_user_ids);
    }

    return array_unique($user_ids);
}


public function get_sub_branches()
{
    $this->output->set_content_type('application/json');
    $branch_id = $this->input->post('branch_id');
    
    $sub_branches = array();
    
    if ($branch_id) {
            $this->db->where_in('parent_branch', $branch_id);
            $sub_branches = $this->db->get('clients_branch')->result_array();
        }
    
    
    echo json_encode($sub_branches);
}

// public function detailed_invoice_report()
// {
//     $company_id = $this->session->userdata('company_id');

//     $from_date = $this->input->post('from_date');
//     $to_date   = $this->input->post('to_date');

//     $from_date_db = $from_date ? date('Y-m-d', strtotime($from_date)) : '';
//     $to_date_db   = $to_date ? date('Y-m-d', strtotime($to_date)) : '';

//     $sql = "
    
//   SELECT
//     DATE_FORMAT(sr.invoice_date,'%b_%Y') AS month,
//     u.email,
//     CONCAT(u.first_name, ' ', u.last_name) AS full_name,

//     -- ✅ SALE-LEVEL SHIPPING (PRIMARY)
//     st_sale.name AS state,
//     ct_sale.name AS city,

//     DATE(sr.created_date) AS order_date,
//     sr.reference_no,
//     DATE(sr.invoice_date) AS invoice_date,
//     cc.company_name AS party_name,

//     sri.product_name,
//     p.product_code,
//     p.hsn,
//     pc.name AS category_name,

//     sri.quantity,
//     sri.uom_name,
//     sri.price,
//     sri.tax_id AS gst_percent,
//     sri.cgst_tax,
//     sri.sgst_tax,
//     sri.igst_tax,
//     (IFNULL(sri.cgst_tax,0) + IFNULL(sri.sgst_tax,0) + IFNULL(sri.igst_tax,0)) AS tax_amount,
//     sri.subtotal AS final_amount

// FROM sale_requests sr
// JOIN sale s ON s.reference_no = sr.reference_no      -- ✅ IMPORTANT
// JOIN sale_request_items sri ON sri.sale_id = sr.id

// LEFT JOIN product p ON p.id = sri.product_id
// LEFT JOIN product_category pc ON pc.id = p.product_category_id
// LEFT JOIN users u ON u.id = sr.added_by
// LEFT JOIN clients_company cc ON cc.id = sr.company_id

// -- ✅ SALE TABLE STATE / CITY
// LEFT JOIN states st_sale ON st_sale.id = s.customer_shipping_state_id
// LEFT JOIN cities ct_sale ON ct_sale.id = s.customer_shipping_city_id

// WHERE sr.company_id = {$company_id}
//   AND sr.status IS NOT NULL
//   AND sr.status != 'rejected'

//     ";

//     if (!empty($from_date_db)) {
//         $sql .= " AND DATE(sr.invoice_date) >= '{$from_date_db}'";
//     }

//     if (!empty($to_date_db)) {
//         $sql .= " AND DATE(sr.invoice_date) <= '{$to_date_db}'";
//     }

//     $sql .= " ORDER BY sr.invoice_date DESC";

//     $data['rows'] = $this->db->query($sql)->result();
//     $data['from_date'] = $from_date;
//     $data['to_date']   = $to_date;

//     $this->load->view('purchase_requests/client_detailed_invoice_report', $data);
// }

public function detailed_invoice_report()
{
    $company_id = $this->session->userdata('company_id');
    $user_id = $this->session->userdata('user_id');
    $level_id = $this->session->userdata('level_id');
    $user_role = $this->db->select('role')->where('id', $user_id)->get('users')->row('role');
    
    // Get branches for dropdown (based on user role from view)
    if ($user_role != 'approval_2') {
        $this->db->where('added_by', $user_id);
        $data['branches'] = $this->db->get('clients_branch')->result();
    } else {
        $data['branches'] = [];
    }
    
    // Get POST data or set defaults
    $from_date = $this->input->post('from_date') ?: date('01-m-Y');
    $to_date = $this->input->post('to_date') ?: date('t-m-Y');
    $branch_id = $this->input->post('branch_id');
    $sub_branch_id = $this->input->post('sub_branch_id');
    
    // Convert dates to database format
    $from_date_db = $from_date ? date('Y-m-d', strtotime($from_date)) : '';
    $to_date_db = $to_date ? date('Y-m-d', strtotime($to_date)) : '';

    $sql = "
SELECT
    DATE_FORMAT(sr.invoice_date,'%b_%Y') AS month,
    u.email,
    CONCAT(u.first_name, ' ', u.last_name) AS full_name,
    u.phone AS phone_number,
    u.department,
    
    -- ✅ SALE TABLE STATE / CITY (from shipping address)
    COALESCE(st_sale.name, 'Not Specified') AS state,
    COALESCE(ct_sale.name, 'Not Specified') AS city,
    
    -- Branch details - using 'code' column (not branch_code)
    COALESCE(cb.branch_name, 'Not Specified') AS branch_name,
    COALESCE(cb.code, 'Not Specified') AS branch_code,
    
    DATE(sr.created_date) AS order_date,
    sr.reference_no,
    
    -- Approver details - from users table via approved_by
    COALESCE(CONCAT(approver.first_name, ' ', approver.last_name), 'Not Approved') AS approver_name,
    COALESCE(approver.email, 'Not Available') AS approver_email,
    COALESCE(approver.phone, 'Not Available') AS approver_contact,
    COALESCE(DATE(sr.approved_at), 'Not Approved') AS order_approval_date,
    
    -- Delivery details - using correct column names from sale_requests
    COALESCE(sr.delivery_via, 'Not Specified') AS delivery_partner,
    COALESCE(sr.docket_number, 'Not Provided') AS docket_number,
    CASE 
        WHEN sr.order_status = 'delivered' AND sr.physical_date IS NOT NULL 
        THEN sr.physical_date 
        ELSE 'Not Delivered' 
    END AS delivered_date,
    
    -- Invoice details
    DATE(sr.invoice_date) AS invoice_date,
    COALESCE(cc.company_name, 'Not Available') AS party_name,
    
    -- Zone from sale table shipping state (using state name as zone)
    COALESCE(st_zone.name, 'Not Specified') AS zone,
    
    -- Item details - FIXED: Use COST column instead of PRICE
    sri.product_name,
    COALESCE(p.product_code, 'N/A') AS product_code,
    COALESCE(p.hsn, 'N/A') AS hsn,
    COALESCE(pc.name, 'N/A') AS category_name,
    sri.quantity,
    sri.uom_name,
    -- FIX: Use sri.cost (which has the actual unit price) instead of sri.price
    sri.cost AS price,
    sri.tax_id AS gst_percent,
    sri.cgst_tax,
    sri.sgst_tax,
    sri.igst_tax,
    sri.taxable_value AS tax_amount,
    sri.subtotal AS final_amount

FROM sale_requests sr
LEFT JOIN sale s ON s.reference_no = sr.reference_no
JOIN sale_request_items sri ON sri.sale_id = sr.id

LEFT JOIN product p ON p.id = sri.product_id
LEFT JOIN product_category pc ON pc.id = p.product_category_id
LEFT JOIN users u ON u.id = sr.added_by
LEFT JOIN clients_company cc ON cc.id = sr.company_id
LEFT JOIN clients_branch cb ON cb.id = sr.branch_id

-- ✅ SALE TABLE STATE / CITY (from shipping address) - now LEFT JOIN since sale is LEFT JOIN
LEFT JOIN states st_sale ON st_sale.id = s.customer_shipping_state_id
LEFT JOIN cities ct_sale ON ct_sale.id = s.customer_shipping_city_id

-- Zone from sale table shipping state
LEFT JOIN states st_zone ON st_zone.id = s.customer_shipping_state_id

-- Approver details - using approved_by from sale_requests
LEFT JOIN users approver ON approver.id = sr.approved_by

WHERE sr.company_id = {$company_id}
    AND sr.status IS NOT NULL
    AND sr.status != 'rejected'
    AND sr.reference_no IS NOT NULL
";


    // Date filter
    if (!empty($from_date_db)) {
        $sql .= " AND DATE(sr.invoice_date) >= '{$from_date_db}'";
    }
    if (!empty($to_date_db)) {
        $sql .= " AND DATE(sr.invoice_date) <= '{$to_date_db}'";
    }
    
    // Branch filtering based on user role (from view logic)
    if ($user_role != 'approval_2') {
        if (!empty($branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($branch_id);
        }
        if (!empty($sub_branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($sub_branch_id);
        }
    } else {
        // For approval_2 users, use their branch from session
        $user_branch_id = $this->session->userdata('branch_id');
        if ($user_branch_id) {
            $sql .= " AND sr.branch_id = " . intval($user_branch_id);
        }
    }
    
    // Level-based filtering (similar to your other methods)
    if ($level_id == 0) { 
        if (!empty($sub_branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($sub_branch_id);
        } elseif (!empty($branch_id) && $branch_id != '0') {
            $sql .= " AND sr.branch_id = " . intval($branch_id);
        }
    } elseif ($level_id == 1) { 
        $user_branch_id = $this->session->userdata('branch_id'); 
        if (!empty($branch_id) && $branch_id != '0') {
            $sql .= " AND sr.branch_id = " . intval($branch_id);
        } elseif (!empty($user_branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($user_branch_id);
        }
    } elseif ($level_id == 2) { 
        $user_branch_id = $this->session->userdata('branch_id');
        if (!empty($sub_branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($sub_branch_id);
        } elseif (!empty($user_branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($user_branch_id);
        }
    }

    $sql .= " ORDER BY sr.invoice_date DESC";

    $data['rows'] = $this->db->query($sql)->result();
    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;
    $data['user_role'] = $user_role;
    $data['branches'] = $data['branches'];
    
    // Calculate totals for Excel export
    $total_amount_sum = 0;
    $total_tax_sum = 0;
    if (!empty($data['rows'])) {
        foreach ($data['rows'] as $row) {
            $total_amount_sum += $row->final_amount;
            $total_tax_sum += $row->tax_amount;
        }
    }
    $data['total_amount_sum'] = $total_amount_sum;
    $data['total_tax_sum'] = $total_tax_sum;

    $this->load->view('purchase_requests/client_detailed_invoice_report', $data);
}
 
 public function tax_purchase()
{
    $user_id    = $this->session->userdata('user_id');
    $level_id   = $this->session->userdata('level_id');
    $company_id = $this->session->userdata('company_id');
    $user_role  = $this->db->select('role')->where('id', $user_id)->get('users')->row('role');
    $this->db->where('added_by', $user_id);
    $data['branches'] = $this->db->get('clients_branch')->result();
    
    // Default values
    $from_date = '';
    $to_date = '';
    $branch_id = '';
    $sub_branch_id = '';
    
    // UPDATED SQL - Getting zone from sale table, not sale_requests
    $sql = "SELECT 
                sr.*,
                -- Required columns
                COALESCE(sr.delivery_via, s.carrier, sr.delivery_mode, 'Not Specified') as delivery_via,
                COALESCE(sr.docket_number, 'Not Provided') as docket_number,
                DATE(sr.invoice_date) as delivery_date,
                cb.branch_name,
                -- CORRECT: Zone from sale table's shipping state
                COALESCE(st.name, 'Not Specified') as zone,
                -- Payment date
                COALESCE(
                    (SELECT MAX(DATE(th.created_date)) 
                     FROM transaction_header th 
                     WHERE th.reference_no = sr.reference_no 
                     AND th.type = 'payment'
                     AND th.module IN ('purchase', 'sale', 'sales')),
                    'Not Paid'
                ) as payment_date,
                -- Additional info
                sr.courier_date,
                sr.physical_date,
                sr.courier_partner,
                -- Shipping state ID for reference
                s.customer_shipping_state_id
            FROM sale_requests sr
            LEFT JOIN clients_branch cb ON cb.id = sr.branch_id
            -- Join with sale table for shipping data
            LEFT JOIN sale s ON s.reference_no = sr.reference_no
            -- Join with states table using SALE table's shipping state
            LEFT JOIN states st ON st.id = s.customer_shipping_state_id
            WHERE sr.company_id = " . intval($company_id) . "
                AND sr.status IS NOT NULL 
                AND sr.status != 'rejected'";
       
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');
        $branch_id = $this->input->post('branch_id');
        $sub_branch_id = $this->input->post('sub_branch_id');
        $from_date = ($from_date == '') ? '' : date('Y-m-d', strtotime($from_date));
        $to_date   = ($to_date == '') ? '' : date('Y-m-d', strtotime($to_date));

        if (!empty($from_date)) {
            $sql .= " AND sr.created_date >= '" . $from_date . "'";
        }
        if (!empty($to_date)) {
            $sql .= " AND sr.created_date <= '" . $to_date . " 23:59:59'";
        }
    }
    
    // ---------- FINAL BRANCH / SUB-BRANCH FILTER ----------
    if ($level_id == 0) { 
        if (!empty($sub_branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($sub_branch_id);
        } elseif (!empty($branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($branch_id);
        }
    } elseif ($level_id == 1) { 
        $user_branch_id = $this->session->userdata('branch_id'); 
        if (!empty($branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($branch_id);
        } else {
            $sql .= " AND sr.branch_id = " . intval($user_branch_id);
        }
    } elseif ($level_id == 2) { 
        $user_branch_id = $this->session->userdata('branch_id');
        if (!empty($sub_branch_id)) {
            $sql .= " AND sr.branch_id = " . intval($sub_branch_id);
        } else {
            $sql .= " AND sr.branch_id = " . intval($user_branch_id);
        }
    }

    // Add GROUP BY clause
    $sql .= " GROUP BY sr.id, sr.delivery_via, sr.docket_number, sr.invoice_date, 
              cb.branch_name, st.name, sr.courier_date, sr.physical_date, 
              sr.courier_partner, s.customer_shipping_state_id
              ORDER BY sr.created_date DESC";
    
    $query = $this->db->query($sql);
    $total_amount_sum = 0;
    foreach ($query->result() as $row) {
        $total_amount_sum += ($row->total ?? 0);
    }
    
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $sale_data['purchases'] = $query->result();
        $sale_data['from_date'] = (!empty($from_date)) ? date('d-m-Y', strtotime($from_date)) : '';
        $sale_data['to_date']   = (!empty($to_date)) ? date('d-m-Y', strtotime($to_date)) : '';
        $sale_data['company_setting'] = $this->db->get('company_setting')->row();
        $sale_data['branches'] = $data['branches'];
        $sale_data['user_role'] = $user_role;
        $sale_data['post_data'] = $this->input->post();
        $sale_data['total_amount_sum'] = $total_amount_sum;
        $this->load->view('purchase_requests/client_tax_report', $sale_data);
        return;
    } else {
        $data['purchases'] = $query->result();
        $data['total_amount_sum'] = $total_amount_sum;
    }

    $data['total_purchase']    = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
    $data['total_paid_amount'] = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
    $data['from_date']         = '';
    $data['to_date']           = '';
    $data['user_role']         = $user_role;

    $this->load->view('purchase_requests/client_tax_report', $data);
}
// public function tax_purchase()
// 	{
// 	$user_id    = $this->session->userdata('user_id');
//     $level_id   = $this->session->userdata('level_id');
//     $company_id = $this->session->userdata('company_id');
//     $user_role  = $this->db->select('role')->where('id', $user_id)->get('users')->row('role');
//     $this->db->where('added_by', $user_id);
//     $data['branches'] = $this->db->get('clients_branch')->result();
//      // Default values
//     $from_date = '';
//     $to_date = '';
//     $branch_id = '';
//     $sub_branch_id = '';
    
//     // $sql = "SELECT * FROM sale_requests 
//     //     WHERE company_id = " . intval($company_id) . "
//     //     AND status IS NOT NULL 
//     //     AND status != ('rejected')";
    
//      $sql = "SELECT 
//                 sr.*,
//                 -- Required columns
//                 sr.delivery_via,
//                 sr.docket_number,
//                 DATE(sr.invoice_date) as delivery_date,
//                 cb.branch_name,
//                 st.name as zone,
//                 -- Payment date from transaction_header
//                 MAX(CASE WHEN th.type = 'payment' THEN DATE(th.created_date) END) as payment_date,
//                 -- Additional info
//                 sr.courier_date,
//                 sr.physical_date,
//                 sr.courier_partner
//             FROM sale_requests sr
//             LEFT JOIN clients_branch cb ON cb.id = sr.branch_id
//             -- Join with states table for zone (using shipping state from sale_requests)
//             LEFT JOIN states st ON st.id = sr.customer_shipping_state_id
//             -- Join with transaction_header for payment date
//             LEFT JOIN transaction_header th ON th.reference_no = sr.reference_no 
//                 AND th.module = 'purchase'  -- Adjust if your module name is different
//             WHERE sr.company_id = " . intval($company_id) . "
//                 AND sr.status IS NOT NULL 
//                 AND sr.status != 'rejected'";
       
//     if ($this->input->server('REQUEST_METHOD') === 'POST') {
//         $from_date = $this->input->post('from_date');
//         $to_date   = $this->input->post('to_date');
//         $branch_id = $this->input->post('branch_id');
//         $sub_branch_id = $this->input->post('sub_branch_id');
//         $from_date = ($from_date == '') ? '' : date('Y-m-d', strtotime($from_date));
//         $to_date   = ($to_date == '') ? '' : date('Y-m-d', strtotime($to_date));

//         if (!empty($from_date)) {
//             $sql .= " AND created_date >= '" . $from_date . "'";
//         }
//         if (!empty($to_date)) {
//             $sql .= " AND created_date <= '" . $to_date . " 23:59:59'";
//         }
// //       if (!empty($from_date)) {
// //     $sql .= " AND invoice_date >= '" . $from_date . "'";
// // }

// // if (!empty($to_date)) {
// //     $sql .= " AND invoice_date <= '" . $to_date . "'";
// // }


//     }
//     // ---------- FINAL BRANCH / SUB-BRANCH FILTER ----------
 

    
//      if ($level_id == 0) { 
//         if (!empty($sub_branch_id)) {
//             $sql .= " AND branch_id = " . intval($sub_branch_id);
//         } elseif (!empty($branch_id)) {
//             $sql .= " AND branch_id = " . intval($branch_id);
//         }
    
//     } elseif ($level_id == 1) { 
//         $user_branch_id = $this->session->userdata('branch_id'); 
    
//         if (!empty($branch_id)) {
//             $sql .= " AND branch_id = " . intval($branch_id);
//         } else {
//             $sql .= " AND branch_id = " . intval($user_branch_id);
//         }
    
//     } elseif ($level_id == 2) { 
//         $user_branch_id = $this->session->userdata('branch_id');
    
//         if (!empty($sub_branch_id)) {
//             $sql .= " AND branch_id = " . intval($sub_branch_id);
//         } else {
//             $sql .= " AND branch_id = " . intval($user_branch_id);
//         }
//     }

// //   echo $sql;
// // die;
//     $query = $this->db->query($sql);
//     $total_amount_sum = 0;
// foreach ($query->result() as $row) {
//     $total_amount_sum += ($row->total ?? 0);
// }
//     if ($this->input->server('REQUEST_METHOD') === 'POST') {
//         $sale_data['purchases'] = $query->result();
//         $sale_data['from_date'] = (!empty($from_date)) ? date('d-m-Y', strtotime($from_date)) : '';
//         $sale_data['to_date']   = (!empty($to_date)) ? date('d-m-Y', strtotime($to_date)) : '';
//         $sale_data['company_setting'] = $this->db->get('company_setting')->row();
//         $sale_data['branches'] = $data['branches'];
//         $sale_data['user_role'] = $user_role;
//         $sale_data['post_data'] = $this->input->post();
//         $sale_data['total_amount_sum'] = $total_amount_sum;
//         $this->load->view('purchase_requests/client_tax_report', $sale_data);
//         return;
//     } else {
//         $data['purchases'] = $query->result();
//           $data['total_amount_sum'] = $total_amount_sum;
//     }

//     $data['total_purchase']    = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
//     $data['total_paid_amount'] = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
//     $data['from_date']         = '';
//     $data['to_date']           = '';
//     $data['user_role']         = $user_role;

//     $this->load->view('purchase_requests/client_tax_report', $data);
// 	}
}

 