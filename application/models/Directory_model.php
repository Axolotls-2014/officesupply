<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directory_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function add_record($data)
    {
        if($this->db->insert('directory',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

    public function get_single_record($directory_id)
    {
        return $this->db->get_where('directory', array("id" => $directory_id))->row();
    }

    public function get_single_record_by_name($directory_name)
    {
        return $this->db->get_where('directory', array("dir_name" => $directory_name))->row();
    }

    public function create_directory($directory_name,$parent_directory_array = null)
    {

        if($parent_directory_array == null)
        {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                $dir = dirname(dirname(dirname(__FILE__))).'\\assets\\documents\\'.$directory_name;
            else
                $dir = dirname(dirname(dirname(__FILE__))).'/assets/documents/'.$directory_name;
            

            if(!is_dir($dir))
            {
                mkdir($dir, 0755, true);

                if($id = $this->add_record(array('dir_name'=>$directory_name)))
                    return $id;
                else
                    return false;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $parent_directory   =   $this->get_single_record_by_name($parent_directory_array[sizeof($parent_directory_array)-1]);

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                $dir = dirname(dirname(dirname(__FILE__))).'\\assets\\documents\\'.implode("\\", $parent_directory_array)."\\".$directory_name;
            else
                $dir = dirname(dirname(dirname(__FILE__))).'/assets/documents/'.implode("/", $parent_directory_array)."/".$directory_name;

            if(!is_dir($dir))
            {
                mkdir($dir, 0755, true);


                if($id = $this->add_record(array('dir_name'=>$directory_name,'parent_dir_id'=>$parent_directory->id)))
                    return $id;
                else
                    return false;
            }
            else
            {
                return false;
            }
        }
    }
}
?>
