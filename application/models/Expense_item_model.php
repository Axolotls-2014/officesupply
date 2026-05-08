<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_item_model extends CI_Model {

    var $table = 'expense_view';
    var $column_order = array(
                                'taxable_amount',
                                'igst',
                                'igst_tax',
                                'cgst',
                                'cgst_tax',
                                'sgst',
                                'sgst_tax',
                                'created_date',
                                'expense_id',
                                'product_id',
                                'id'
                              ); //set column field database for datatable orderable
    var $column_search = array(
                                'taxable_amount',
                                'igst',
                                'igst_tax',
                                'cgst',
                                'cgst_tax',
                                'sgst',
                                'sgst_tax',
                                'created_date',
                                'expense_id',
                                'product_id'
                              ); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 


    function __construct() {
      parent::__construct();
    }
  	public function get_records($expense_id = null)
  	{
      $this->db->select('ei.*,p.item_name');
      $this->db->from('expense_items ei');
      $this->db->join('item p','p.id = ei.item_id','left');
      $this->db->join('expense e','e.id = ei.expense_id','left');

      if($expense_id != null)
        $this->db->where('ei.expense_id',$expense_id);

     $query = $this->db->get();
     return $query->result();    
  	}

    public function add_record($data)
    {
      if($this->db->insert('expense_items',$data))
        return  $this->db->insert_id();
      else
        return FALSE;
    }

    public function edit_record($data,$id)
    {   
      $this->db->where('id',$id);
      if($this->db->update('expense_items',$data))
        return true;
      else
        return false;
    }

    public function get_single_record($id)
    {
        $this->db->select('e.i*,p.item_name');
        $this->db->from('expense_items ei');
        $this->db->where('ei.id',$id);

       $query = $this->db->get();
       return $query->row();    
    }

    public function remove_expense_item_records($expense_id)
    {   
        $this->db->where('expense_id',$expense_id);
        if($this->db->delete('expense_items'))
            return true;
        else
            return false;
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
