<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

   
    function __construct() {
        parent::__construct();
    }

  public function get_records() {
    $this->db->select('*');
    $this->db->from('sources'); 
  
    $query = $this->db->get(); 
    return $query->result(); 
}
  public function get_status_records()
  {
    $this->db->select('*');
    $this->db->from('leads_status'); 
  
    $query = $this->db->get(); 
    return $query->result(); 
 }
   public function insert_source($data) 
   {
        return $this->db->insert('sources', $data);  
    }
    public function insert_lead_status($data) 
   {
        return $this->db->insert('leads_status', $data);  
    }
    
    public function delete($source_id)
    {
    $this->db->where('id', $source_id);
    return $this->db->delete('sources');
}

  

     public function update_source($source_id, $data)
    {   
        $this->db->where('id',$source_id);
         return $this->db->update('sources',$data);
        
    }
    
    public function delete_lead($source_id)
    {
    $this->db->where('id', $source_id);
    return $this->db->delete('leads_status');
    }

     public function update_lead_status($source_id, $data)
    {   
        $this->db->where('id',$source_id);
         return $this->db->update('leads_status',$data);
        
    }
}