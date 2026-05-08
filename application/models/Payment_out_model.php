<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_out_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

  /*  public function get_payment_out_records($supplier_id = null) {
        $this->db->select('po.*, s.company_name as supplier_name');
        $this->db->from('payment_out po');
        $this->db->join('supplier s', 's.id = po.supplier_id');
        
        if($supplier_id) {
            $this->db->where('po.supplier_id', $supplier_id);
        }
        
        $this->db->order_by('po.payment_date', 'DESC');
        return $this->db->get()->result();
    }*/
        public function get_payment_out_records($supplier_id = null) {
        $this->db->select('po.*, s.company_name as supplier_name');
        $this->db->from('payment_out po');
        $this->db->join('supplier s', 's.id = po.supplier_id');
        
        if($supplier_id) {
            $this->db->where('po.supplier_id', $supplier_id);
        }
        
        $this->db->order_by('po.payment_date', 'DESC');
        $this->db->order_by('po.id', 'DESC'); // Add this to show newest first
        
        $query = $this->db->get();
        
        // Debug: Log the query and result count
        log_message('debug', 'Payment Out Query: ' . $this->db->last_query());
        log_message('debug', 'Number of payments found: ' . $query->num_rows());
        
        return $query->result();
    }

    public function get_payment_out_single_record($id) {
        return $this->db->select('po.*, s.company_name, s.company_name as supplier_company_name, 
                                s.address as supplier_address, s.gstin as supplier_gstin, 
                                st.name as supplier_state_name, st.state_code as supplier_state_code, 
                                s.ledger_id')
                     ->from('payment_out po')
                     ->join('supplier s', 's.id = po.supplier_id')
                     ->join('states st', 'st.id = s.state_id', 'left')
                     ->where('po.id', $id)
                     ->get()
                     ->row();
    }

    public function add_payment_out_record($data) {
        $this->db->insert('payment_out', $data);
        return $this->db->insert_id();
    }

    public function edit_payment_out_record($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('payment_out', $data);
    }

    public function delete_payment_out_record($id) {
        $this->db->where('id', $id);
        return $this->db->delete('payment_out');
    }

    // public function get_payment_distributions($payment_out_id) {
    //     return $this->db->select('pod.*, p.reference_no as invoice_no, p.purchase_date, p.total as invoice_amount')
    //                  ->from('payment_out_distribution pod')
    //                  ->join('purchase p', 'p.id = pod.purchase_id')
    //                  ->where('pod.payment_out_id', $payment_out_id)
    //                  ->get()
    //                  ->result();
    // }
    
    public function get_payment_distributions($payment_out_id) {
    return $this->db->select('
        pod.*, 
        p.reference_no as po_number,     
        p.invoice_no,                    
        p.purchase_date, 
        p.total as invoice_amount
    ')
    ->from('payment_out_distribution pod')
    ->join('purchase p', 'p.id = pod.purchase_id')
    ->where('pod.payment_out_id', $payment_out_id)
    ->get()
    ->result();
}

    public function add_payment_distribution($data) {
        return $this->db->insert('payment_out_distribution', $data);
    }
    public function get_supplier_unpaid_invoices($supplier_id) {
    $sql = "SELECT 
                p.id,
                p.reference_no,
                p.invoice_no,
                p.purchase_date,
                p.total,
                COALESCE((
                    SELECT SUM(po.amount) 
                    FROM payment_out_distribution pod 
                    JOIN payment_out po ON po.id = pod.payment_out_id 
                    WHERE pod.purchase_id = p.id AND po.delete_status = 0
                ), 0) as paid_amount
            FROM purchase p
            WHERE p.supplier_id = ? 
            AND p.delete_status = 0
            HAVING p.total > paid_amount
            ORDER BY p.purchase_date ASC";
    
    $query = $this->db->query($sql, array($supplier_id));
    $invoices = $query->result();
    
    // Calculate due amount for each invoice
    foreach($invoices as $invoice) {
        $invoice->due_amount = $invoice->total - $invoice->paid_amount;
    }
    
    return $invoices;
}

    public function get_supplier_unpaid_invoices2604($supplier_id) {
        $this->db->select('p.id, p.reference_no, p.purchase_date, p.total, 
                          (p.total - COALESCE((
                              SELECT SUM(t.amount) 
                              FROM transaction_header t 
                              WHERE t.entry_id = p.id AND t.module = "P" AND t.type = "P"
                          ), 0)) as due_amount');
        $this->db->from('purchase p');
        $this->db->where('p.supplier_id', $supplier_id);
        $this->db->where('p.delete_status', 0);
        $this->db->having('due_amount >', 0);
        return $this->db->get()->result();
    }
}