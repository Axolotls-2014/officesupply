<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_category_model extends CI_Model {

    var $table = 'expense_category_view';
    var $column_order = array('name','description','account_group'); //set column field database for datatable orderable
    var $column_search = array('name','description','account_group'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 


    function __construct() {
        parent::__construct();
    }

	public function get_records()
	{
		return $this->db->select('ec.*,ag.group_title AS account_group')
                        ->from('expense_category ec')
                        ->join('ledger l','l.id = ec.ledger_id','LEFT')
                        ->join('account_group ag','ag.id = l.account_group_id','LEFT')
                        ->where('ec.delete_status',0)
                        ->get()
                        ->result();
	}

  public function add_record($data)
  {
    if($this->db->insert('expense_category',$data)){
        return  $this->db->insert_id();
    }
    else{
        return FALSE;
    }
  }

  public function edit_record($data,$id)
  {   
      $this->db->where('id',$id);
      if($this->db->update('expense_category',$data)){
          return true;
      }
      else{
          return false;
      }
  }

  public function get_single_record($expense_category_id)
  {
      return $this->db->select('ec.*,ag.group_title AS account_group')
                      ->from('expense_category ec')
                      ->join('ledger l','l.id = ec.ledger_id','LEFT')
                      ->join('account_group ag','ag.id = l.account_group_id','LEFT')
                      ->where('ec.delete_status',0)
                      ->where('ec.id',$expense_category_id)
                      ->get()
                      ->row();
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


}
?>
