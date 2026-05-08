<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_debit_note_model extends CI_Model {

  var $table = 'credit_debit_note';
  var $column_order = array(
                              "cdn_date",
                              "cdn_reference_no",
                              "cdn_note_type",
                              "cdn_ledger_id",
                              "cdn_description",
                              "cdn_taxable_amount",
                              "cdn_tax_amount",
                              "cdn_tax_type",
                              "cdn_tax_id",
                              "cdn_igst",
                              "cdn_igst_tax",
                              "cdn_cgst",
                              "cdn_cgst_tax",
                              "cdn_sgst",
                              "cdn_sgst_tax",
                              "cdn_amount",
                              "cdn_created_date",
                              "cdn_created_by",
                              "cdn_updated_date",
                              "cdn_updated_by",
                              "cdn_delete_status",
                              "cdn_id"
                            ); //set column field database for datatable orderable
  var $column_search = array(
                              "cdn_date",
                              "cdn_reference_no",
                              "cdn_note_type",
                              "cdn_ledger_id",
                              "cdn_description",
                              "cdn_taxable_amount",
                              "cdn_tax_amount",
                              "cdn_tax_type",
                              "cdn_tax_id",
                              "cdn_igst",
                              "cdn_igst_tax",
                              "cdn_cgst",
                              "cdn_cgst_tax",
                              "cdn_sgst",
                              "cdn_sgst_tax",
                              "cdn_amount",
                              "cdn_created_date",
                              "cdn_created_by",
                              "cdn_updated_date",
                              "cdn_updated_by",
                              "cdn_delete_status",
                              "cdn_id"
                          ); //set column field database for datatable searchable 
  var $order = array('cdn_created_date' => 'desc'); // default order 

  function __construct() {
      parent::__construct();
      
  }

  public function get_records()
  {
    $this->db->select('*');
    $this->db->from('credit_debit_note');
    $this->db->where('cdn_delete_status',NOT_DELETED);

    $query = $this->db->get();
    return $query->result();
  }

  public function add_record($data)
  {
    $data['cdn_created_by'] = csession('user_id');
    $data['cdn_created_date'] = date('Y-m-d H:i:s');
    if($this->db->insert('credit_debit_note',$data))
      return  $this->db->insert_id();
    else
      return FALSE;
  }

  public function edit_record($data,$cdn_id)
  {   
    $data['cdn_updated_by'] = csession('user_id');
    $data['cdn_updated_date'] = date('Y-m-d H:i:s');
    
    $this->db->where('cdn_id',$cdn_id);
    if($this->db->update('credit_debit_note',$data))
      return true;
    else
      return false;
  }

  public function get_single_record($cdn_id)
  {
      $this->db->select(
                          'c.*, 
                          l.title as title, 
                          GROUP_CONCAT(DISTINCT s.reference_no ORDER BY s.reference_no) as sale_reference_no, 
                          GROUP_CONCAT(DISTINCT p.reference_no ORDER BY p.reference_no) as purchase_reference_no, 
                      ');
      $this->db->from('credit_debit_note c');
      $this->db->join('ledger l', 'l.id = c.cdn_ledger_id', 'left');
      $this->db->join('sale s', 'FIND_IN_SET(s.id, c.cdn_sale_ids)', 'left');
      $this->db->join('purchase p', 'FIND_IN_SET(p.id, c.cdn_purchase_ids)', 'left');
      $this->db->where('cdn_id', $cdn_id);
      $this->db->group_by('c.cdn_id'); // Group by the main identifier

      $query = $this->db->get();
      return $query->row();
  }

  public function add_item_record($data)
  { 
    if($this->db->insert('credit_debit_note_items',$data))
      return  $this->db->insert_id();
    else
      return FALSE;
  }

  public function get_item_records($credit_debit_note_id)
  { 
    $this->db->select('c.*');
    $this->db->from('credit_debit_note_items c');
    $this->db->where('credit_debit_note_id', $credit_debit_note_id);

    $query = $this->db->get();
    return $query->result();
  }

  public function remove_item_records($credit_debit_note_id)
  {   
      $this->db->where('credit_debit_note_id',$credit_debit_note_id);
      if($this->db->delete('credit_debit_note_items'))
          return true;
      else
          return false;
  }
  
  /************************************ Start Dynamic Datatable function ***********************************/

  private function _get_datatables_query()
  {
    $this->db->select($this->table.'.*,ledger.title as ledger_name');
    $this->db->from($this->table);
    $this->db->join('ledger','ledger.id = '.$this->table.'.cdn_ledger_id','left');

    $this->db->where('cdn_delete_status',NOT_DELETED);

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
    $this->_get_datatables_query();
    return $this->db->count_all_results();
  }

  /*********************************** End Dynamic Datatable function *****************************************/

  public function get_lastest_sequence_number($note_type)
  {
    $this->db->select('*');
    $this->db->from('credit_debit_note');
    $this->db->where('cdn_delete_status',0);
    $this->db->where('cdn_note_type',$note_type);

    $query = $this->db->get();
    $data = $query->result();

    // $prefix = (($note_type == CREDIT_NOTE_TYPE_DEBIT) ? csession('debit_note_prefix')  : (($note_type == CREDIT_NOTE_TYPE_CREDIT) ? csession('credit_note_prefix') : csession('advance_refund_voucher_prefix')));
    return csession('credit_debit_note_prefix').(CREDIT_DEBIT_NOTE_SEQUENCE+sizeof($data)+1);
   // return $prefix.(CREDIT_DEBIT_NOTE_SEQUENCE+sizeof($data)+1);
  }

  public function get_records_by_ledger_id($cdn_ledger_id, $note_type = NULL)
  {
    $this->db->select('*');
    $this->db->from('credit_debit_note');
    $this->db->where('cdn_delete_status',NOT_DELETED);
    $this->db->where('cdn_ledger_id',$cdn_ledger_id);

    if($note_type != NULL)
    {
      $this->db->where('cdn_note_type',$note_type);
    }
    
    $query = $this->db->get();
    return $query->result();
  }
}
?>
