<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function convert_date_format($date) {
        $converted_date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if ($converted_date) {
            return $converted_date->format('d-m-Y hA');
        }
        $converted_date = DateTime::createFromFormat('Y-m-d', $date);
        if ($converted_date) {
            return $converted_date->format('d-m-Y');
        }
        return false;
    }

    public function sendOtpSms($otp, $phone) {
        $fields = array(
            'variables_values' => $otp,
            'route'            => 'otp',
            'numbers'          => $phone,
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://www.fast2sms.com/dev/bulkV2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($fields),
            CURLOPT_HTTPHEADER     => array(
                "authorization: SmsKey",
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return array('status' => 'error', 'message' => $err);
        } else {
            return array('status' => 'success', 'message' => 'OTP has been sent successfully to your mobile number.');
        }
    }

    public function send_message($mobile, $message) {
        $api_url  = 'http://195.201.12.47/wapp/api/send';
        $apikey   = 'b51e85194ff94f12b4c82f51b6a5fc2e';

        $data = array(
            'apikey' => $apikey,
            'mobile' => $mobile,
            'msg'    => $message 
        );

        $ch = curl_init($api_url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);
        $response_data = json_decode($response, true);
        
        $data = array(
            'phone'       => $mobile,        
            'msg'         => $message,        
            'status'      => $response_data['status'],        
            'errormsg'    => $response_data['errormsg'],     
            'statuscode'  => $response_data['statuscode'],    
            'requestid'   => $response_data['requestid'],
            'msgcount'    => $response_data['msgcount'],   
            'msgcost'     => $response_data['msgcost'],  
            'created_at'  => date('Y-m-d H:i:s') 
        );
        $this->db->insert('log', $data);
        
        return $response;
    }

    public function get_name()
    {
        $data     = $this->select_multi('customer_name','customer',array('id' => $this->session->admin_id));
        if ($data) {
            $fullname = $data->customer_name;
            return $fullname;
        } else {
            return null;
        }
    }
    
    public function select($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC')->limit(1);
        $result = $this->db->get()->row(); 
        if ($result) {
            return $result->$data;
        } else {
            return null;
        }
    }
    

    public function select_multi($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC')->limit(1);
        $result = $this->db->get()->row();

        return $result;
    }

    public function update($data, $table, $where = "1=1")
    {
        $this->db->where($where);
        $this->db->update($table, $data);

    }

    public function count_all($table, $where = "1=1")
    {
        $this->db->from($table);
        $this->db->where($where);

        return $this->db->count_all_results();

    }

    public function select_all($data, $table, $where = "1=1", $order = 'id', $sort = 'DESC')
    {
        $this->db->select($data)->from($table)->where($where)->order_by($order, $sort);
        $result = $this->db->get()->result();
        return $result;
    }

    public function sum($data, $table, $where = "1=1")
    {
        $this->db->select_sum($data);
        $this->db->where($where);
        $this->db->from($table);
        $result = $this->db->get()->row();
        return $result->$data + 0;
    }
     public function insert_category($data) {
        return $this->db->insert('categories', $data);
    }
    public function get_all_domains() {
        $query = $this->db->get('categories'); 
        return $query->result(); 
    }
    
    
}
