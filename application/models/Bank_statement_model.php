<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_statement_model extends CI_Model {

    var $table = 'bank_statement_view';
    var $column_order = array(
                              'account_name',
                              'account_number',
                              'bank_name',
                              'statement',
                              'reconcile_status',
                              'created_date',
                              'created_by',
                              'updated_date',
                              'updated_by',
                              'bank_account_id',
                              'bank_statement_id',
                              'delete_status'
                            ); //set column field database for datatable orderable
    var $column_search = array(
                              'account_name',
                              'account_number',
                              'bank_name',
                              'statement',
                              'reconcile_status',
                              'created_date',
                              'created_by',
                              'updated_date',
                              'updated_by',
                              'bank_account_id',
                              'bank_statement_id',
                              'delete_status'
                            ); //set column field database for datatable searchable 
    var $order = array('bank_statement_id' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
        
    }

	public function get_records()
	{
		return $this->db->select('*')
                         ->from('bank_statement')
                         ->where('delete_status',0)
                         ->get()
                         ->result();
	}

  public function add_record($data)
  {
    $data['created_date'] = date('Y-m-d H:i:s');
    
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('bank_statement',$data)){
        return  $this->db->insert_id();
    }
    else{
        return FALSE;
    }
  }
  

  public function edit_record($data,$id)
  {   
    $data['updated_date'] = date('Y-m-d H:i:s');
    $data['updated_by']   = $this->session->userdata('user_id');

      $this->db->where('id',$id);
      if($this->db->update('bank_statement',$data)){
          return true;
      }
      else{
          return false;
      }
  }

  public function delete_record($data,$id)
  {
      $this->db->where('id',$id);

      if($this->db->delete('bank_statement'))
      {
          return true;
      }
      else
      {
          return false;
      }
  }

  public function get_single_record($bank_statement_id)
  {
      return $this->db->get_where('bank_statement', array("id" => $bank_statement_id))->row();
  }

    

       /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
       /* $this->db->select*/
      //  $this->db->select($this->table.'.*,ledger.title as from_account_name,l.title as to_account_name');
      //   $this->db->from($this->table);
      //   $this->db->join('ledger','ledger.id = '.$this->table.'.from_account_id','left');
      //   $this->db->join('ledger l','ledger l.id = '.$this->table.'.to_account_id','left');

      //   $this->db->where('delete_status',NOT_DELETED);
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
        $this->db->where('delete_status',0);    
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

    // public function get_lastest_sequence_number()
    // {
    //   $this->db->select('*');
    //   $this->db->from('bank_statement');
    //   // $this->db->where('delete_status',0);

    //   $query = $this->db->get();
    //   $data = $query->result();

    //   return BANK_PAYMENT_PREFIX.(bank_statement_SEQUENCE+sizeof($data)+1);
    // }


}
?>
