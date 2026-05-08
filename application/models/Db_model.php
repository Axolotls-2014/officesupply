<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Db_model extends CI_Model
{
    public function id_filter($filter, $table) {
        $count = $this->count_all($table, "id = '$filter'");
        if($count == 0){
            $status = FALSE;
        }else{
            $status = $filter;
        }
        return $status;
    }
    
    public function select($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC')->limit(1);
        $result = $this->db->get()->row();
        return $result->$data;
    }

    public function select_multi($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC')->limit(1);
        $result = $this->db->get()->row();
        return $result;
    }

    public function count_all($table, $where = "1=1")
    {
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    public function sum($data, $table, $where = "1=1")
    {
        $this->db->select_sum($data);
        $this->db->where($where);
        $this->db->from($table);
        $result = $this->db->get()->row();
        return $result->$data + 0;
    }
    
    public function select_all($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC');
        $result = $this->db->get()->result();
        return $result;
    }

    public function generator($lenth)
    {
        $number=array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","U","V","T","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
    
        for($i=0; $i<$lenth; $i++)
        {
            $rand_value=rand(0,34);
            $rand_number=$number["$rand_value"];
        
            if(empty($con))
            { 
            $con=$rand_number;
            }
            else
            {
            $con="$con"."$rand_number";}
        }
        return $con;
    }
}