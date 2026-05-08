<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Log_data_model extends CI_Model
{
    function __construct() 
    {
        parent::__construct();
    }
    
    public function get_records()
    {
         return $this->db->select('s.*,CONCAT(u.first_name," ",u.last_name) as user_name')
                         ->from('log_data s')
                         ->join('users u','u.id = s.user_id')
                         ->order_by('s.date_created','desc')
                         ->get()
                         ->result();
    }

    public function get_records_by_entry_id($module,$entry_id)
    {
         return $this->db->select('s.*,CONCAT(u.first_name," ",u.last_name) as user_name')
                         ->from('log_data s')
                         ->join('users u','u.id = s.user_id')
                         ->where('s.module',$module)
                         ->where('s.entry_id',$entry_id)
                         ->order_by('s.date_created','desc')
                         ->get()
                         ->result();
    }

    public function get_any_record($table,$primary_id_name,$id)
    {
        return $this->db->get_where($table,array($primary_id_name=>$id))->row();
    }
    
    public function add_record($data)
    {
        $ip         = $this->getUserIpAddr();
        $browser    = $this->browser_name(); 
        $referer    = $this->get_referer();

        $data['ip_address']     = $ip;
        $data['browser']        = $browser;
        $data['referer_url']    = $referer;

        if($this->db->insert('log_data',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }
    
    public function get_single_record($id)
    {
        return $this->db->get_where('log_data',array('id'=>$id))->row();
    }

    public function edit_record($data,$id)
    {
        $this->db->where('id',$id);
        if($this->db->update('log_data',$data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function delete_record($id)
    {   
        $this->db->where('id',$id);
        if($this->db->delete('log_data')){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    public function getUserIpAddr()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function browser_name()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];

        if (
            strpos(strtolower($ua), 'safari/') &&
            strpos(strtolower($ua), 'opr/')
        ) {
            // Opera
            $res = 'Opera';
        } elseif (
            strpos(strtolower($ua), 'safari/') &&
            strpos(strtolower($ua), 'chrome/')
        ) {
            // Chrome
            $res = 'Chrome';
        } elseif (
            strpos(strtolower($ua), 'msie') ||
            strpos(strtolower($ua), 'trident/')
        ) {
            // Internet Explorer
            $res = 'Internet Explorer';
        } elseif (strpos(strtolower($ua), 'firefox/')) {
            // Firefox
            $res = 'Firefox';
        } elseif (
            strpos(strtolower($ua), 'safari/') &&
            (strpos(strtolower($ua), 'opr/') === false) &&
            (strpos(strtolower($ua), 'chrome/') === false)
        ) {
            // Safari
            $res = 'Safari';
        } else {
            // Out of data
            $res = false;
        }

        return $res;
    }

    public function get_referer()
    {
        if(!isset($_SERVER["HTTP_REFERER"]))
        {
            return "";
        }
        else
        {
            return $_SERVER["HTTP_REFERER"];
        }
    }

        // public function delete_log_records($sql)
        // {
            
        // }
}
?>