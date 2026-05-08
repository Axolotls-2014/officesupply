<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_records($limit = null)
    {
        if($limit == null)
        {
            return $this->db->select('
                                    s.*,
                                    t.tax_name,
                                    t.igst,
                                    t.cgst,
                                    t.sgst
                                ')
                         ->from('service s')
                         ->join('tax t','t.id = s.tax_id','left')
                         ->where('s.delete_status',0)
                         ->get()
                         ->result();
        }
        else
        {
            return $this->db->select('
                                    s.*,
                                    t.tax_name,
                                    t.igst,
                                    t.cgst,
                                    t.sgst
                                ')
                         ->from('service s')
                         ->join('tax t','t.id = s.tax_id','left')
                         ->where('s.delete_status',0)
                         ->limit($limit)
                         ->order_by('s.created_date','desc')
                         ->get()
                         ->result();
        }
        
    }

    
    public function search_record($search_term = null)
    {
        if($search_term != null)
        {
            return $this->db->select('s.id,s.name')
                            ->from('service s')
                            ->join('tax t','t.id = s.tax_id','left')
                            ->where('s.delete_status',0)
                            ->like('s.name', $search_term)
                            ->get()
                            ->result();
        }
      
    }

    public function add_record($data)
    {
         if($this->db->insert('service',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('service',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_single_record($service_id)
    {
        return $this->db->select('
                                    s.*,
                                    t.tax_name,
                                    t.igst,
                                    t.cgst,
                                    t.sgst
                                ')
                         ->from('service s')
                         ->join('tax t','t.id = s.tax_id','left')
                         ->where('s.id',$service_id)
                         ->get()
                         ->row();
    }

    public function get_countries()
    {
        return $this->db->select('*')
                         ->from('countries')
                         ->get()
                         ->result();
    }

    public function get_states($id = null)
    {      
        return $this->db->select('*')
                         ->from('states')
                         ->where('country_id',$id)
                         ->get()
                         ->result();
    }

     public function get_cities($id = null)
    {      
        return $this->db->select('*')
                         ->from('cities')
                         ->where('state_id',$id)
                         ->get()
                         ->result();
    }

}
?>
