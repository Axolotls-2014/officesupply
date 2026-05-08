<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_field_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function edit_custom_field_records($data) {
      foreach ($data as $field_data) {
          $field_id = $field_data['id'];
          $status = $field_data['field_status'];
  
          $this->db->where('id', $field_id);
          $this->db->update('custom_field_setting', array('field_status' => $status));
      }
      return true; // Return true if the update was successful
  }
  

  public function get_custom_field_records() 
  {
    // Fetch custom fields from the database
    $query = $this->db->get('custom_field_setting');
    return $query->result_array();
  }

  public function get_custom_fields_for_module($module_name = null) 
  {
    if ($module_name) {
        // Fetch custom fields from the database based on the module name
        $this->db->where('module_name', $module_name);
    }
    $query = $this->db->get('custom_field_setting');
    return $query->result_array();
  }

  
}
?>
