<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{ 

    public function __construct()
    { 
        parent::__construct(); 
    } 
 
    public function index()
    {
        $data['title']      = 'Login';
        $this->load->view('auth/admin_login', $data);
    }
 
    public function otpLogin()
    {
        $data['title']      = 'Login';
        $this->load->view('auth/otp', $data);
    }

    public function send_otp(){
        $user      = $this->input->post('username');
        if($this->db_model->count_all('customer', array('email' => $user)) == 0){
            $response = array(
                'status'  => 'error',
                'message' => 'Invalid Email'
            );
            echo json_encode($response);
        } else{
            $this->load->library('email');
            $data      = $this->db_model->select_multi("*", 'customer', array('email' => $user));
            $user_name = $data->customer_name;
            $sub       = "Verification For Office supply Managment";
            $cname     = 'Office supply  Managment';
            $otp       = rand(11111, 99999);
            $email     = $user;
            $msg = "
                <html>
                <head>
                <title>Login OTP</title>
                </head>
                <body>
                
                <p>Dear $user_name,</p>
                
                <p>Your OTP for login is: <b style='color: red'>$otp</b></p>
                
                <p>Use this OTP to proceed. Do not share it with anyone.</p>
                
                <p>Best regards,</p>
                <b>$cname</b>
                
                </body>
                </html>
                ";
            $this->email->from('Office supply Solutions', $cname);
            $this->email->to($email);
            $this->email->subject($sub);
            $this->email->message($msg);
            $this->email->send();
            $this->session->set_userdata(array('login_otp' => $otp));
            $response = array(
                'status'  => 'success',
                'message' => 'Otp Sent successfully!'
            );
            echo json_encode($response);
        }
    }
    public function admin()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
    
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status' => 'error',
                'message' => validation_errors()
            );
            echo json_encode($response);
        } else {
            $user     = $this->input->post('username');
            $password = $this->input->post('password');
            $data     = $this->db_model->select_multi("*", 'customer', array('email' => $user));
    
            if ($data && $password == $data->password){
                session_unset();
                $this->session->set_userdata(array(
                    'admin_id'   => $data->id,
                    'email'      => $data->email,
                    'last_ip'    => $data->last_ip,
                    'last_login' => $data->last_login,
                ));
                $data2 = array(
                    'last_ip'    => $this->input->ip_address(),
                    'last_login' => time(),
                );
                $this->db_model->update($data2, 'customer', array('id' => $data->id));
                $response = array(
                    'status' => 'success',
                    'message' => 'Login successful!'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid Username or Password.'
                );
            }
            echo json_encode($response);
        }
    }    

    public function get_phone_number($id)
    {
        $phone = $this->db_model->select('phone', 'customer', array('id' => $id));
    
        if ($phone !== null) {
            echo json_encode(['phone' => $phone]);
        } else {
            echo json_encode(['phone' => 'No phone number found']);
        }
    }
    

}

