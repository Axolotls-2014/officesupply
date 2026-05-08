<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_template_model extends CI_Model {

  var $table = 'email_template';
  var $column_order = array( 
                              'template_name',
                              'from_name',
                              'from_email',
                              // 'to_cc',
                              'module',
                              'subject',
                              'mail_header',
                              'mail_body',
                              'mail_footer',
                              'created_by',
                              'created_date',
                              'updated_by',
                              'updated_date',
                              'delete_status'
                          ); //set column field database for datatable orderable
  var $column_search = array(
                              'template_name',
                              'from_name',
                              'from_email',
                              // 'to_cc',
                              'module',
                              'subject',
                              'mail_header',
                              'mail_body',
                              'mail_footer',
                              'created_by',
                              'created_date',
                              'updated_by',
                              'updated_date',
                              'delete_status'
                          ); //set column field database for datatable searchable 
  var $order = array('id' => 'desc'); // default order 

  function __construct() {
    parent::__construct();
  }

  public function get_records()
	{
		$this->db->select('*');
    $this->db->from('email_template');
    $this->db->where('delete_status',0);

    $query = $this->db->get();
    return $query->result();
	}

  public function add_record($data)
  {
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('email_template',$data)){
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
    if($this->db->update('email_template',$data)){
      return $id;
    }
    else{
      return FALSE;
    }
  }

 

  public function get_single_record($id)
  {
    $this->db->select('*');
    $this->db->from('email_template');
    $this->db->where('id',$id);

    $query = $this->db->get();
    return $query->row();
    
  }

  public function get_record_by_module($module)
  {
      return $this->db->select('*')
                       ->from('email_template')
                      ->where('module',$module)
                      ->get()
                      ->row();   
  }

  /************************************ Start Dynamic Datatable function ***********************************/

  private function _get_datatables_query()
  {
      /* $this->db->select*/
    $this->db->from($this->table);
    $this->db->where('delete_status',0);    

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

  public function is_module_exists($module_name, $email_template_id = null) 
  {
    $this->db->where('module', $module_name);
    if ($email_template_id !== null) 
    {
        $this->db->where('id !=', $email_template_id);
    }
    return $this->db->get('email_template')->num_rows() > 0;
  }

}
?>
