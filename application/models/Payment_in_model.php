<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_in_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // public function get_payment_in_records($customer_id = null) {
    //     $this->db->select('pi.*, c.customer_name');
    //     $this->db->from('payment_in pi');
    //     $this->db->join('customer c', 'c.id = pi.customer_id');
        
    //     if($customer_id) {
    //         $this->db->where('pi.customer_id', $customer_id);
    //     }
        
    //     $this->db->order_by('pi.payment_date', 'DESC');
    //     return $this->db->get()->result();
    // }
    
    public function get_payment_in_records($customer_id = null) {
    // $this->db->select('pi.*, 
    //     CONCAT(
    //         c.customer_name,
    //         CASE 
    //             WHEN c.customer_company_name IS NOT NULL 
    //                  AND c.customer_company_name != "" 
    //             THEN CONCAT(" (", c.customer_company_name, ")")
    //             ELSE ""
    //         END
    //     ) AS customer_name'
    // );
      $this->db->select('pi.*, 
        c.customer_company_name AS customer_name'  // Only fetch company name
    );
    
    $this->db->from('payment_in pi');
    $this->db->join('customer c', 'c.id = pi.customer_id');
    
    if($customer_id) {
        $this->db->where('pi.customer_id', $customer_id);
    }
    
    $this->db->order_by('pi.payment_date', 'DESC');
            $this->db->order_by('pi.id', 'DESC'); // Add secondary sorting by ID

    return $this->db->get()->result();
}


    // In your Payment_in_model.php
    public function get_payment_in_single_record($id) {
        return $this->db->select('pi.*, c.customer_name, c.customer_company_name, 
                                c.address as customer_address, c.gstin as customer_gstin, 
                                s.name as customer_state_name, s.state_code as customer_state_code, 
                                c.ledger_id')
                     ->from('payment_in pi')
                     ->join('customer c', 'c.id = pi.customer_id')
                     ->join('states s', 's.id = c.state_id', 'left') // Fixed join condition
                     ->where('pi.id', $id)
                     ->get()
                     ->row();
    }

    public function add_payment_in_record($data) {
        $this->db->insert('payment_in', $data);
        return $this->db->insert_id();
    }

 public function get_payment_distributions($payment_in_id) {
    return $this->db->select('pid.*, s.reference_no as invoice_no, s.invoice_date, 
                            s.total as invoice_amount, 
                            (s.total - COALESCE((
                                SELECT SUM(pid2.amount) 
                                FROM payment_in_distribution pid2 
                                WHERE pid2.sale_id = s.id AND pid2.payment_in_id != pid.payment_in_id
                            ), 0)) as due_amount_before')
                 ->from('payment_in_distribution pid')
                 ->join('sale s', 's.id = pid.sale_id')
                 ->where('pid.payment_in_id', $payment_in_id)
                 ->get()
                 ->result();
}

    public function add_payment_distribution($data) {
        return $this->db->insert('payment_in_distribution', $data);
    }

    public function get_customer_unpaid_invoices($customer_id) {
        $this->db->select('s.id, s.reference_no, s.invoice_date, s.total, 
                          (s.total - COALESCE((
                              SELECT SUM(t.amount) 
                              FROM transaction_header t 
                              WHERE t.entry_id = s.id AND t.module = "S" AND t.type = "R"
                          ), 0)) as due_amount');
        $this->db->from('sale s');
        $this->db->where('s.customer_id', $customer_id);
        $this->db->where('s.delete_status', 0);
        $this->db->having('due_amount >', 0);
        return $this->db->get()->result();
    }
}