<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Due_days_model extends CI_Model {

    var $table = 'due_days';
    var $column_order = array('id', 'due_day', 'terms_and_condition', 'status', 'created_at', 'delete_status');
    var $column_search = array('id', 'due_day', 'terms_and_condition', 'status', 'created_at', 'delete_status');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function get_records() {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('delete_status', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function add_record($data) {
        if($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function edit_record($data, $id) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function get_single_record($id) {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $this->db->where('delete_status', 0); // Optional filter
        $query = $this->db->get();
        return $query->row(); // returns null if not found
    }

    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->where('delete_status', 0);

        $i = 0;
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) {
                if($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables() {
        $this->_get_datatables_query();
        if($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        $this->db->where('delete_status', 0);
        return $this->db->count_all_results();
    }
}