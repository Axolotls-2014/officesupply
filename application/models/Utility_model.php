<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Utility_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

  	public function get_countries()
  	{
  		return $this->db->select('*')
                           ->from('countries')
                           ->get()
                           ->result();
  	}

    public function get_country_by_name($country_name)
    {
      return $this->db->select('*')
                      ->from('countries')
                      ->where('name',$country_name)
                      ->get()
                      ->row(); 
    }

    public function get_states($id = null)
    {      
        return $this->db->select('*')
                         ->from('states')
                         ->where('country_id',$id)
                         ->get()
                         ->result();
    }

    public function get_state_by_name($state_name)
    {
      return $this->db->select('*')
                      ->from('states')
                      ->where('name',$state_name)
                      ->get()
                      ->row(); 
    }

    public function get_cities($id = null)
    {      
        return $this->db->select('*')
                         ->from('cities')
                         ->where('state_id',$id)
                         ->get()
                         ->result();
    }

    public function get_city_by_name($city_name)
    {
      return $this->db->select('*')
                      ->from('cities')
                      ->where('name',$city_name)
                      ->get()
                      ->row(); 
    }

    public function get_languages()
    {
        return $this->db->select('*')
                        ->from('language l')
                        ->where('l.status',1)
                        ->get()
                        ->result();
    }

    function get_subscription_records($current_date)
    {
        return $this->db->select('*')
                        ->from('subscription')
                        ->where('from_date <=', $current_date)
                        ->where('to_date >=', $current_date)
                        ->get()
                        ->row();
    }

    public function get_records_by_field($table_name,$field_name,$field_value,$row = true,$check_delete_status = false)
    {
      $this->db->select('*');
      $this->db->from($table_name);
      $this->db->where($field_name,$field_value);

      if($check_delete_status == true)
        $this->db->where('delete_status',0);      

      $query = $this->db->get();

      if($row == false)
        return $query->result();
      else
        return $query->row();
    }

    public function get_records_by_field_value($table_name,$field_name1,$field_name2,$field_value1,$field_value2,$row = true,$check_delete_status = false)
    {
      $this->db->select('*');
      $this->db->from($table_name);
      $this->db->where($field_name1,$field_value1);
      $this->db->where($field_name2,$field_value2);

      if($check_delete_status == true)
        $this->db->where('delete_status',0);      

      $query = $this->db->get();

      if($row == false)
        return $query->result();
      else
        return $query->row();
    }

    function generateRandomString($length = 10) 
    {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }

    public function get_seperator()
    {
      $separator = '';
      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
        $separator = '\\';
      else
        $separator = '/';

      return $separator;
    }

    public function is_update_available()
    {
      $SEPERATOR = $this->utility_model->get_seperator();
      $myfiles = scandir(FCPATH.$SEPERATOR.'assets'.$SEPERATOR.'update');

      if(sizeof($myfiles) > 2)
        return true;
      else
        return false;
    }

    public function get_db()
    {
      $serverName = $_SERVER['SERVER_NAME'];

      // Check if the host name contains "localhost" or "127.0.0.1"
      $isLocalhost = (stripos($serverName, 'localhost') !== false) || (stripos($serverName, '127.0.0.1') !== false);

      if ($isLocalhost) 
      {
        return $this->load->database(get_db_config('localhost','zpsas_system','root',''),TRUE);   
      } 
      else 
      {
        $host_parts = explode('.', $_SERVER['HTTP_HOST']);
        $subdomain  = $host_parts[0];

        $db_credentials = get_db_credentials($subdomain);

        return $this->load->database(get_db_config('localhost',$db_credentials->database_name,$db_credentials->database_user_name,$db_credentials->database_password),TRUE);

      }
    }

    public function is_running_on_server() {
    
      $serverName = $_SERVER['SERVER_NAME'];

      // Check if the host name contains "localhost" or "127.0.0.1"
      $isLocalhost = (stripos($serverName, 'localhost') !== false) || (stripos($serverName, '127.0.0.1') !== false);

      // Return true if running on localhost, false if running on a server
      return $isLocalhost;
    }
    
        	public function get_lead_status()
  	{
  		return $this->db->select('*')
                           ->from('leads_status')
                           ->get()
                           ->result();
  	}


     	public function get_lead_sources()
  	{
  		return $this->db->select('*')
                           ->from('sources')
                           ->get()
                           ->result();
  	}
}
?>
