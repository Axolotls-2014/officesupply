<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account_model extends CI_Model {

    var $table = 'bank_account';
    var $column_order = array('account_name','account_number','bank_name','ifsc','description','opening_balance','closing_balance'); //set column field database for datatable orderable
    var $column_search = array('account_name','account_number','bank_name','ifsc','description','opening_balance','closing_balance'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc');  

    function __construct() {
        parent::__construct();
    }

	public function get_records()
	{
		return $this->db->select('b.*,l.opening_balance,l.closing_balance,l.title as ledger_title')
                         ->from('bank_account b')
                         ->join('ledger l','l.id = b.ledger_id','LEFT')
                         ->get()
                         ->result();
	}

    public function add_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');

      if($this->db->insert('bank_account',$data)){
          return  $this->db->insert_id();
      }
      else{
          return FALSE;
      }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('bank_account',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_record($data,$id)
    {
        $this->db->where('id',$id);

        if($this->db->delete('bank_account'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_single_record($bank_account_id)
    {
        return $this->db->select('b.*,l.opening_balance,l.closing_balance')
                         ->from('bank_account b')
                         ->join('ledger l','l.id = b.ledger_id','LEFT')
                         ->where('b.id',$bank_account_id)
                         ->get()
                         ->row();
    }

    public function is_bank_have_transaction($bank_account_id)
    {
        $transactions = $this->db->select('th.*')
                                 ->from('transaction_header th')
                                 ->where('th.from_account',$bank_account_id)
                                 ->or_where('th.to_account',$bank_account_id)
                                 ->get()
                                 ->result();
        if($transactions == null)
        {
            return false;
        }
        else
        {
            return true;
        }


    }

      /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
       /* $this->db->select*/
        $this->db->from($this->table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
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
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
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


}
?>
