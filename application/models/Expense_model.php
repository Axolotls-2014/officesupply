<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_model extends CI_Model {

    var $table = 'expense';
    var $column_order = array('date','expense_category_name','company_name','amount','remarks'); 
    var $column_search = array('date','expense_category_name','company_name','amount','remarks');  
    var $order = array('id' => 'desc'); // default order 


    function __construct() {
        parent::__construct();
        
    }

	public function get_records($limit = null)
 {
   
    $this->db->select('e.*, v.company_name as company_name, ec.name as expense_category_name')
             ->from('expense e')
             ->join('supplier v', 'v.id = e.supplier_id', 'left')
             ->join('expense_category ec', 'ec.id = e.expense_category_id', 'left')
             ->where('e.delete_status', 0);

    
        if ($limit !== null) {
            $this->db->limit($limit);
        }
    
        return $this->db->order_by('e.created_date', 'desc')
                        ->get()
                        ->result();
    }

    public function get_records_by_expense_category_id($expense_category_id)
    {
        return $this->db->select('e.*,v.company_name as company_name,ec.name as expense_category_name')
                         ->from('expense e')
                         ->join('supplier v','v.id = e.supplier_id','left')
                         ->join('expense_category ec','ec.id = e.expense_category_id','left')
                         ->where('e.delete_status',0)
                         ->where('e.expense_category_id',$expense_category_id)
                         ->order_by('e.created_date','desc')
                         ->get()
                         ->result();  
    }

    public function get_records_by_supplier_id($supplier_id)
    {
        return $this->db->select('e.*,v.company_name as company_name,ec.name as expense_category_name')
                         ->from('expense e')
                         ->join('supplier v','v.id = e.supplier_id','left')
                         ->join('expense_category ec','ec.id = e.expense_category_id','left')
                         ->where('e.delete_status',0)
                         ->where('e.supplier_id',$supplier_id)
                         ->order_by('e.created_date','desc')
                         ->get()
                         ->result();  
    }

    public function add_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
         if($this->db->insert('expense',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('expense',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_single_record($expense_id)
    {
        return $this->db->select('
                                    e.*,
                                    v.company_name as company_name,
                                    ec.name as expense_category_name,
                                    v.gst_registration_type,
                                    v.state_id as supplier_state_id,
                                    v.company_name as supplier_company_name
                                ')
                         ->from('expense e')
                         ->join('supplier v','v.id = e.supplier_id','left')
                         ->join('expense_category ec','ec.id = e.expense_category_id','left')
                         ->where('e.delete_status',0)
                         ->where('e.id',$expense_id)
                         ->order_by('e.created_date','desc')                         
                         ->get()
                         ->row();    
    }

    public function get_total_expense_amount($from = null, $to = null)
    {
       $data  = $this->db->select('SUM(total_amount) AS total_amount,SUM(igst_tax) as igst, SUM(cgst_tax) as cgst, SUM(sgst_tax) as sgst, SUM(th.amount) as total_amount_paid')
                         ->from('expense e')
                         ->join('transaction_header th','th.entry_id = e.id','LEFT')
                         ->where('th.type',EXPENSE_TRANSACTION_TYPE)
                         ->where('e.delete_status',0)
                         ->get();

        if($data->num_rows() > 0)
        {
            $res = $data->row_array();
            return $res;
        }
        else
        {
            $res                        = array();
            $res['total_amount']        = 0.0;
            $res['igst']                = 0.0;
            $res['cgst']                = 0.0;
            $res['sgst']                = 0.0;
            $res['total_amount_paid']   = 0.0;

            return $res;
        }
    }

public function expense_by_month_year($year_month, $branch_id = null) {
    $user_id = $this->session->userdata('user_id');
    $udata   = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

    $this->db->select('SUM(total_amount) AS amount')
             ->from('expense e')
             ->where('e.delete_status', 0)
             ->like('e.date', $year_month, 'both')
             ->group_by('DATE_FORMAT(e.date, "%Y-%m")');

    // If branch filter is provided explicitly
    if ($branch_id && $branch_id !== 'all') {
        $this->db->where('e.warehouse_id', $branch_id);
    }

    // Restrict to user's branch if applicable
    if (!empty($udata->branch_id)) {
        $this->db->where('e.warehouse_id', $udata->branch_id);
    }

    return $this->db->get()->result_array();
}


        /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
       /* $this->db->select*/
        $this->db->from($this->table);
 
        $i = 0;
     
        $searchValue = str_replace(' ', '', $_POST['search']['value']); // Remove spaces from the search term

        foreach ($this->column_search as $item) // loop column 
        {
          if($searchValue) // if the search term exists after removing spaces
          {
              if($i === 0) // first loop
              {
                  $this->db->group_start(); // open bracket for OR clauses
                  $this->db->like("REPLACE($item, ' ', '')", $searchValue);
              }
              else
              {
                  $this->db->or_like("REPLACE($item, ' ', '')", $searchValue);
              }
      
              if(count($this->column_search) - 1 == $i) // last loop
                  $this->db->group_end(); // close bracket
          }
          $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
        $user_id    = $this->session->userdata('user_id');
        $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->where('delete_status',0); 
         if($udata->branch_id){
            $this->db->where('warehouse_id', $udata->branch_id); 
        }
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    /*********************************** End Dynamic Datatable function *****************************************/

    public function get_total_expenses() {
      // Assuming there's a column `amount` in `expenses` table representing the expense amount
      $this->db->select_sum('total_amount');
      $this->db->where('delete_status',0);
      $query = $this->db->get('expense');
      return $query->row()->total_amount;  // Assuming `amount` is the column storing expense amounts
  }
    

}
?>
