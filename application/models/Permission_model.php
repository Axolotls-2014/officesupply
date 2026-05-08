<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Permission_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
    }

    public function get_distinct_module()
    {     
        return $this->db->distinct('module')
                        ->select('p.module') 
                        ->from('permissions p')
                        ->get()
                        ->result(); 
    }

    public function get_permission_records_by_module($module)
    {
        return $this->db->select('p.*') 
                        ->from('permissions p')
                        ->where('p.module',$module)
                        ->where('p.status','active')
                        // ->where('p.plan_code',csession('plan_code'))
                        ->get()
                        ->result(); 
    }

    public function add_permission_to_group($data)
    {
        if($this->db->insert('permission_role',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function remove_permission_from_group_by_group_id($group_id)
    {
        $this->db->where('role_id',$group_id);
        if($this->db->delete('permission_role'))
        {
            return  TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_permission_by_group_id($group_id)
    {
        return $this->db->select('pr.*') 
                        ->from('permission_role pr')
                        ->where('pr.role_id',$group_id)
                        ->get()
                        ->result(); 
    }

    function get_permission_records_by_user($user_id)
    {
      //$plan_code = csession('plan_code');
      // $plan_code = $plan_code_array[0]; // Access the first element of the array
  
      // print_r($plan_code);
      // exit;

        return $this->db->select('p.*')
            ->from('permissions p')
            ->join('permission_role pr', 'pr.permission_id = p.id')
            ->join('groups g', 'g.id = pr.role_id')
            ->join('users_groups ug', 'ug.group_id = g.id')
            ->where('ug.user_id', $user_id)
            // ->where('p.plan_code', $plan_code) // Use the retrieved plan code
             ->where('p.status', 'active')
            ->get()
            ->result();
    }


    function has_permission($permission_name)
    {
        $permission = $this->session->userdata('permission');
        if(!in_array($permission_name, $permission)){
            return false;
        }else{
            return true;
        }
    }

    function has_module_permission($permission_name)
    {
        $permission_m = $this->session->userdata('permission_m');
        if(!in_array($permission_name, $permission_m)){
            return false;
        }else{
            return true;
        }
    }

    function is_permission_exist($permission_id,$group_id)
    {
        return $this->db->select('pr.*') 
                        ->from('permission_role pr')
                        ->where('pr.role_id',$group_id)
                        ->where('pr.permission_id',$permission_id)
                        ->get()
                        ->row(); 
    }
}