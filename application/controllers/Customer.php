<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
			error_reporting(E_ALL);
ini_set('display_errors', 1);
		}
	}
	
	public function index()
  {
    if(!$this->permission_model->has_permission('list_customer'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        $user_id = $this->session->userdata('user_id');
        
        $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

        if ($udata && $udata->branch_id) {
            $this->db->where('branch_id', $udata->branch_id);
        }
        
		
        $data['customer'] = $this->customer_model->get_records();
       
        // $log_data = array(
        //     "user_id"     => $this->session->userdata("user_id"),
        //     "module"      => "customer",
        //     "user_action" => 0,
        //     "description" => "User has viewed list of customer."
        // );

        // $this->log_data_model->add_record($log_data); 

        $this->load->view('customer/list', $data);
    }
}


// public function add()
// {
//     if(!$this->permission_model->has_permission('add_customer'))
//     {
//         $this->load->view('errors/html/error_restricted'); 
//     }
//     else
//     {
//         if($this->input->server('REQUEST_METHOD') === 'POST')
//         {
//             $this->form_validation->set_rules('customer_name','Customer name','required');    
//             $this->form_validation->set_rules('customer_company_name','Customer company name','required');    
//             $this->form_validation->set_rules('country_id','Country','required');
//             $this->form_validation->set_rules('state_id','State','required');
//             $this->form_validation->set_rules('email', 'Email', 'required|valid_email'); 
//             $this->form_validation->set_rules('phone', 'Phone', 'required|numeric|min_length[10]');
//             $this->form_validation->set_rules('pincode', 'Pincode', 'numeric|min_length[6]|max_length[6]');
          
//             if($this->form_validation->run() == FALSE)
//             {
               
//                 $country_id = $this->input->post('country_id');
//                 $state_id = $this->input->post('state_id');
//                 $data['countries'] = $this->utility_model->get_countries();

//                 if($country_id != '')
//                 {
//                     $data['states'] = $this->utility_model->get_states($country_id);
//                 }

//                 if($state_id != '')
//                 {
//                     $data['cities'] = $this->utility_model->get_cities($state_id);
//                 }

//                 $this->load->view('customer/add', $data);
//             }
//             else
//             {
//               $user_id = $this->session->userdata('user_id');
// 				$query = $this->db->select('branch_id')
// 								  ->from('users')
// 								  ->where('id', $user_id)
// 								  ->get();
// 				$user_data = $query->row();
// 				$branch_id = $user_data->branch_id ?? null;

//                 $customer_name = $this->input->post('customer_name');
//                 $data = array(
//                     "customer_name" => $customer_name,
//                     "customer_company_name" => $this->input->post('customer_company_name'),
//                     "customer_department" => $this->input->post('customer_department'),
//                     "payment_duration" => $this->input->post('payment_duration'),
//                     "email" => $this->input->post('email'),
//                     "phone" => $this->input->post('phone'),
//                     "country_id" => $this->input->post('country_id'),
//                     "state_id" => $this->input->post('state_id'),
//                     "address" => $this->input->post('address'),
//                     "city_id" => $this->input->post('city_id'),
//                     "pincode" => $this->input->post('pincode'),
//                     "shipping_address" => $this->input->post('shipping_address'),
//                     "shipping_city_id" => $this->input->post('shipping_city_id'),
//                     "shipping_state_id" => $this->input->post('shipping_state_id'),
//                     "shipping_country_id" => $this->input->post('shipping_country_id'),
//                     "shipping_pincode" => $this->input->post('shipping_pincode'),
//                     "whatsapp_no" => $this->input->post('whatsapp_no'),
//                     "whatsapp_country_code" => $this->input->post('whatsapp_country_code'),
//                     "ledger_id" => $this->ledger_model->add_record(array(
//                         "title" => strtoupper($customer_name),
//                         "account_group_id" => CUSTOMER_ACCOUNT_GROUP_ID
//                     )),
//                     "branch_id" => $branch_id, 
//                     "added_by" => $user_id 
//                 );

//                 if($id = $this->customer_model->add_record($data))
//                 {
//                     $this->session->set_flashdata('success', $customer_name.' is added successfully');
//                     redirect('customer', 'refresh');
//                 }
//                 else
//                 {
//                     $this->session->set_flashdata('failure', $customer_name.' failed to add');
//                     redirect('customer');
//                 }
//             }
//         }
//         else
//         {
//             $company_setting = $this->company_settings_model->get_company_records();
//             $data['countries'] = $this->utility_model->get_countries();
//             $data['states'] = $this->utility_model->get_states($company_setting->country_id);
//             $data['cities'] = $this->utility_model->get_cities($company_setting->state_id);
//             $this->load->view('customer/add', $data);
//         }
//     }
// }
public function ajax_add()
{
    if(!$this->permission_model->has_permission('add_customer'))
    {
        echo json_encode(['code' => 0, 'message' => 'Permission denied']);
        return;
    }
    
    $this->form_validation->set_rules('customer_name', 'Customer name', 'required');
    $this->form_validation->set_rules('customer_company_name', 'Customer company name', 'required');
    $this->form_validation->set_rules('phone', 'Phone');
    $this->form_validation->set_rules('country_id', 'Country');
    $this->form_validation->set_rules('state_id', 'State', 'required');
    $this->form_validation->set_rules('address', 'Address', 'required');
    $this->form_validation->set_rules('pincode', 'Pincode', 'required|exact_length[6]|numeric');
    $this->form_validation->set_rules('shipping_address[0][shipping_address]', 'Shipping Address', 'required');
    $this->form_validation->set_rules('shipping_address[0][shipping_country_id]', 'Shipping Country', 'required');
    $this->form_validation->set_rules('shipping_address[0][shipping_state_id]', 'Shipping State', 'required');
    $this->form_validation->set_rules('shipping_address[0][shipping_pincode]', 'Shipping Pincode', 'required|exact_length[6]|numeric');
    
    if($this->form_validation->run() == FALSE)
    {
        $errors = [];
        foreach($_POST as $key => $value) {
            if(form_error($key)) {
                $errors[$key] = form_error($key, '', '');
            }
        }
        // Handle array fields
        if(form_error('shipping_address[0][shipping_address]')) {
            $errors['shipping_address'] = form_error('shipping_address[0][shipping_address]', '', '');
        }
        if(form_error('shipping_address[0][shipping_pincode]')) {
            $errors['shipping_pincode'] = form_error('shipping_address[0][shipping_pincode]', '', '');
        }
        
        echo json_encode(['code' => 0, 'errors' => $errors]);
        return;
    }
    
    $user_id = $this->session->userdata('user_id');
    $query = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get();
    $user_data = $query->row();
    $branch_id = $user_data->branch_id ?? null;
    
    $customer_name = $this->input->post('customer_name');
    
    // Get names for billing
    $country = $this->db->get_where('countries', ['id' => $this->input->post('country_id')])->row();
    $state = $this->db->get_where('states', ['id' => $this->input->post('state_id')])->row();
    $city = $this->db->get_where('cities', ['id' => $this->input->post('city_id')])->row();
    
    // Create ledger
    $ledger_data = array(
        "title" => strtoupper($customer_name),
        "account_group_id" => CUSTOMER_ACCOUNT_GROUP_ID
    );
    $this->db->insert('ledger', $ledger_data);
    $ledger_id = $this->db->insert_id();
    
    // Prepare customer data
    $data = array(
        "customer_name" => $customer_name,
        "customer_company_name" => $this->input->post('customer_company_name'),
        "gstin" => $this->input->post('gstin'),
        "customer_department" => $this->input->post('customer_department'),
        "payment_duration" => $this->input->post('payment_duration'),
        "email" => $this->input->post('email'),
        "phone" => $this->input->post('phone'),
        "country_id" => $this->input->post('country_id'),
        "country_name" => $country ? $country->name : null,
        "state_id" => $this->input->post('state_id'),
        "state_name" => $state ? $state->name : null,
        "city_id" => $this->input->post('city_id'),
        "city_name" => $city ? $city->name : null,
        "address" => $this->input->post('address'),
        "pincode" => $this->input->post('pincode'),
        "whatsapp_no" => $this->input->post('whatsapp_no'),
        "whatsapp_country_code" => $this->input->post('whatsapp_country_code'),
        "branch_id" => $branch_id,
        "added_by" => $user_id,
        "ledger_id" => $ledger_id,
        "created_date" => date('Y-m-d H:i:s')
    );
    
    $this->db->insert('customer', $data);
    $customer_id = $this->db->insert_id();
    
    if($customer_id) {
        // Handle shipping address
        $shipping = $this->input->post('shipping_address')[0];
        
        $shipping_country = $this->db->get_where('countries', ['id' => $shipping['shipping_country_id']])->row();
        $shipping_state = $this->db->get_where('states', ['id' => $shipping['shipping_state_id']])->row();
        $shipping_city = $this->db->get_where('cities', ['id' => $shipping['shipping_city_id']])->row();
        
        $shipping_data = array(
            'customer_id' => $customer_id,
            'shipping_name' => $shipping['shipping_name'] ?? 'Default Address',
            'shipping_country_id' => $shipping['shipping_country_id'],
            'shipping_country_name' => $shipping_country ? $shipping_country->name : null,
            'shipping_state_id' => $shipping['shipping_state_id'],
            'shipping_state_name' => $shipping_state ? $shipping_state->name : null,
            'shipping_city_id' => $shipping['shipping_city_id'],
            'shipping_city_name' => $shipping_city ? $shipping_city->name : null,
            'shipping_address' => $shipping['shipping_address'],
            'shipping_pincode' => $shipping['shipping_pincode'],
            'is_default' => 1
        );
        
        $this->db->insert('customer_shipping_addresses', $shipping_data);
        
        // Update customer with default shipping info
        $this->db->where('id', $customer_id);
        $this->db->update('customer', [
            'shipping_country_id' => $shipping['shipping_country_id'],
            'shipping_country_name' => $shipping_country ? $shipping_country->name : null,
            'shipping_state_id' => $shipping['shipping_state_id'],
            'shipping_state_name' => $shipping_state ? $shipping_state->name : null,
            'shipping_city_id' => $shipping['shipping_city_id'],
            'shipping_city_name' => $shipping_city ? $shipping_city->name : null,
            'shipping_address' => $shipping['shipping_address'],
            'shipping_pincode' => $shipping['shipping_pincode']
        ]);
        
        echo json_encode(['code' => 1, 'message' => $customer_name . ' added successfully']);
    } else {
        echo json_encode(['code' => 0, 'message' => 'Failed to add customer']);
    }
}
public function add()
	{
		if(!$this->permission_model->has_permission('add_customer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
			 //   echo "<pre>";
			 //   print_r($this->input->post());
			 //   echo "</pre>";
			 //   die;
			    
				$this->form_validation->set_rules('customer_name','Customer name','required');	
				$this->form_validation->set_rules('customer_company_name','Customer company name','required');	
				// $this->form_validation->set_rules('phone','Phone','required');
				$this->form_validation->set_rules('country_id','Country','required');
				$this->form_validation->set_rules('state_id','State','required');
				
				$this->form_validation->set_rules('shipping_address[0][shipping_address]', 'Shipping Address', 'required');
                $this->form_validation->set_rules('shipping_address[0][shipping_country_id]', 'Shipping Country', 'required');
                $this->form_validation->set_rules('shipping_address[0][shipping_state_id]', 'Shipping State', 'required');
				// $this->form_validation->set_rules('city_id','City','required');
				// $this->form_validation->set_rules('address','Address','required');
				// $this->form_validation->set_rules('pincode','Pincode','required');
				// $this->form_validation->set_rules('shipping_city_id','Shipping City','required');
				// $this->form_validation->set_rules('shipping_address','Address','required');
				// $this->form_validation->set_rules('shipping_pincode','Pincode','required');
				if($this->form_validation->run()==FALSE)
				{
					$country_id  			= $this->input->post('country_id');
					$state_id  				= $this->input->post('state_id');
					
					/*$shipping_country_id  = $this->input->post('shipping_country_id');
					$shipping_state_id  				= $this->input->post('shipping_state_id');*/
					$data['countries'] 		= $this->utility_model->get_countries();

					if($country_id != '')
					{
						$data['states'] 	= $this->utility_model->get_states($country_id);
					}

					if($state_id != '')
					{
						$data['cities'] 	= $this->utility_model->get_cities($state_id);
					}

					$this->load->view('customer/add',$data);
				}
				else
				{
				    $user_id = $this->session->userdata('user_id');
				    $query = $this->db->select('branch_id')
								  ->from('users')
								  ->where('id', $user_id)
								  ->get();
				   $user_data = $query->row();
				   $branch_id = $user_data->branch_id ?? null;
				   $customer_name 	= 	$this->input->post('customer_name');
				   $customer_company_name 	= 	$this->input->post('customer_company_name');
				   $gstin 	= 	$this->input->post('gstin');
				   $customer_department 	= 	$this->input->post('customer_department');

			
					$email 			= 	$this->input->post('email');
					$phone 			= 	$this->input->post('phone');
					$country_id 	= 	$this->input->post('country_id');
					$state_id 		= 	$this->input->post('state_id');
					$address 		= 	$this->input->post('address');
					$pincode 		= 	$this->input->post('pincode');
					$city_id 		= 	$this->input->post('city_id');
					$shipping_address 	= 	$this->input->post('shipping_address');
					$shipping_city_id 	= 	$this->input->post('shipping_city_id');
					$shipping_state_id 	= 	$this->input->post('shipping_state_id');
					$shipping_country_id 	= 	$this->input->post('shipping_country_id');
					$shipping_pincode 	= 	$this->input->post('shipping_pincode');
					$payment_duration = 	$this->input->post('payment_duration');
    			     
    			       $password 				= $this->input->post('password');
                      $whatsapp_no 	= 	$this->input->post('whatsapp_no');
                      $whatsapp_country_code 	= 	$this->input->post('whatsapp_country_code');

					$ledger_data = array(
																"title" 						=> strtoupper($customer_name),
																"account_group_id"	=> CUSTOMER_ACCOUNT_GROUP_ID
															);
					
					$ledger_id = $this->ledger_model->add_record($ledger_data);

					$data			=	array(
												"customer_name"			=>	$customer_name,
												"customer_company_name"			=>	$customer_company_name,
												"gstin"			=>	$gstin,
												
												"customer_department"			=>	$customer_department,
												"payment_duration"	=>	$payment_duration,
												"default_due_days"      => $this->input->post('default_due_days'),
                                                "default_payment_terms" => $this->input->post('default_payment_terms'),
												"email"			=>	$email,
												"phone"			=>	$phone,
												"country_id"	=>	$country_id,
												"state_id"		=>	$state_id,
												"address"		=>	$address,
												"city_id"		=>	$city_id,
												"pincode"		=>	$pincode,
												// "shipping_address"		=>	$shipping_address,
												// "shipping_city_id"		=>	$shipping_city_id,
												// "shipping_state_id"		=>	$shipping_state_id,
												// "shipping_country_id"	=>	$shipping_country_id,
												// "shipping_pincode"		=>	$shipping_pincode,
												"branch_id" => $branch_id, 
                                                "added_by" => $user_id,
                                                "whatsapp_no"		=>	$whatsapp_no,
                                                "whatsapp_country_code"		=>	$whatsapp_country_code,
                                                "password"             =>	$password,
                        						"ledger_id"	=> 	$ledger_id
                        						);
					if($id = $this->customer_model->add_record($data))
					{
					    
					     $shipping_addresses = $this->input->post('shipping_address');
					     
                        if (!empty($shipping_addresses)) {
                            foreach ($shipping_addresses as $index => $address) {
                                $is_default = isset($address['is_default']) && $address['is_default'] == 1 ? 1 : 0;
                                
                                $shipping_data = array(
                                    'customer_id' => $id,
                                    'shipping_name' => $address['shipping_name'] ?? 'Address ' . ($index + 1),
                                    'shipping_country_id' => $address['shipping_country_id'],
                                    'shipping_state_id' => $address['shipping_state_id'],
                                    'shipping_city_id' => $address['shipping_city_id'],
                                    'shipping_address' => $address['shipping_address'],
                                    'shipping_pincode' => $address['shipping_pincode'],
                                    'is_default' => $is_default
                                );
                                
                                $this->customer_model->add_shipping_address($shipping_data);
                        
                                // If it's the default address, update customer table too
                                if ($is_default) {
                                    $default_shipping_data = array(
                                        'shipping_country_id' => $address['shipping_country_id'],
                                        'shipping_state_id'   => $address['shipping_state_id'],
                                        'shipping_city_id'    => $address['shipping_city_id'],
                                        'shipping_address'    => $address['shipping_address'],
                                        'shipping_pincode'    => $address['shipping_pincode']
                                    );
                                    $this->customer_model->edit_record($default_shipping_data, $id);
                                }
                            }
                        }

					    
						$entered_customer = $this->customer_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "customer",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"			=> json_encode((array)$entered_customer),
											"description" 	=> $customer_name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);

						if($this->input->is_ajax_request())
						{	
							$response 							= array();
							$response['code'] 			= 1;
							$response['id'] 				= $id;
							$response['message']		= $customer_name.' is added successfully.';
							$response['customers']	= $this->customer_model->get_records();
							$response['customer'] 	= $this->customer_model->get_single_record($id);

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', $customer_name.' is added successfully');
							redirect('customer','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request())
						{	
							$response 						= array();
							$response['code'] 				= 0;
							$response['message']			= $customer_name.' is failed to add.';
						
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', $customer_name.' is failed to add');
							redirect('customer',$data);
						}
					}
				}
			}
			else
			{
				$company_setting        = $this->company_settings_model->get_company_records();
				$data['countries'] 		= $this->utility_model->get_countries();
				$data['states']         = $this->utility_model->get_states($company_setting->country_id);
				$data['cities']         = $this->utility_model->get_cities($company_setting->state_id);
				 $data['products'] = $this->db->get('product')->result();
				  $data['due_days_options'] = $this->db
    ->where('status', 1)
    ->where('delete_status', 0)
    ->order_by('due_day', 'ASC')
    ->get('due_days')
    ->result();


				$this->load->view('customer/add',$data);
			}
		}
	}
	
public function edit($id = null)
{
    if(!$this->permission_model->has_permission('edit_customer'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            // echo "<pre>";
            // print_r($this->input->post());
            // echo "</pre>";
            // die;
            
            $id = $this->input->post('id');
            $old_customer = $this->customer_model->get_single_record($id);

            $this->form_validation->set_rules('customer_name','Customer name','required');    
            $this->form_validation->set_rules('customer_company_name','Customer Company name','required');            
            $this->form_validation->set_rules('country_id','Country','required');
            $this->form_validation->set_rules('state_id','State','required');
            $this->form_validation->set_rules('shipping_address[0][shipping_address]', 'Shipping Address', 'required');
            $this->form_validation->set_rules('shipping_address[0][shipping_country_id]', 'Shipping Country', 'required');
            $this->form_validation->set_rules('shipping_address[0][shipping_state_id]', 'Shipping State', 'required');
          
            if($this->form_validation->run()==FALSE)
            {
                $country_id          = $this->input->post('country_id');
                $state_id              = $this->input->post('state_id');
                $data['countries']     = $this->utility_model->get_countries();
                $data['customer']     = $this->customer_model->get_single_record($id);

                if($country_id != '')
                {
                    $data['states']     = $this->utility_model->get_states($country_id);
                }

                if($state_id != '')
                {
                    $data['cities']     = $this->utility_model->get_cities($state_id);
                }

                $this->load->view('customer/edit',$data);
            }
            else
            {
               
                $customer_name     = $this->input->post('customer_name');
                $customer_company_name = $this->input->post('customer_company_name');
                $gstin = $this->input->post('gstin');
                $customer_department = $this->input->post('customer_department');
                $email             = $this->input->post('email');
                $phone             = $this->input->post('phone');
                $country_id     = $this->input->post('country_id');
                $state_id         = $this->input->post('state_id');
                $address         = $this->input->post('address');
                $pincode         = $this->input->post('pincode');
                $city_id         = $this->input->post('city_id');
                $payment_duration = $this->input->post('payment_duration');
                $whatsapp_no     = $this->input->post('whatsapp_no');
                $whatsapp_country_code = $this->input->post('whatsapp_country_code');
                $password        = $this->input->post('password');
                $default_due_days = $this->input->post('default_due_days');
$default_payment_terms = $this->input->post('default_payment_terms');

                $data = array(
                    "customer_name"          => $customer_name,
                    "customer_company_name"  => $customer_company_name,
                    "gstin"                  => $gstin,
                    "customer_department"    => $customer_department,
                    "payment_duration"      => $payment_duration,
                    "email"                 => $email,
                    "phone"                 => $phone,
                    "country_id"            => $country_id,
                    "state_id"              => $state_id,
                    "address"              => $address,
                    "city_id"               => $city_id,
                    "pincode"               => $pincode,
                    "whatsapp_no"           => $whatsapp_no,
                    "whatsapp_country_code" => $whatsapp_country_code,
                    "password"              => $password,
                    "default_due_days"       => $default_due_days,
    "default_payment_terms"  => $default_payment_terms
                );
                
                if($this->customer_model->edit_record($data, $id))
                {
                
$shipping_addresses = $this->input->post('shipping_address');

$default_address_data = null;

if (!empty($shipping_addresses)) {
    foreach ($shipping_addresses as $index => $address) {
        $shipping_data = array(
            'customer_id' => $id,
            'shipping_name' => $address['shipping_name'] ?? 'Address ' . ($index + 1),
            'shipping_country_id' => $address['shipping_country_id'],
            'shipping_state_id' => $address['shipping_state_id'],
            'shipping_city_id' => $address['shipping_city_id'],
            'shipping_address' => $address['shipping_address'],
            'shipping_pincode' => $address['shipping_pincode'],
            'is_default' => isset($address['is_default']) ? 1 : 0
        );

        if (isset($address['is_default'])) {
            $default_address_data = $shipping_data;
        }

        if (!empty($address['id'])) {
            $this->customer_model->update_shipping_address($address['id'], $shipping_data);
        } else {
            $this->customer_model->add_shipping_address($shipping_data);
        }
    }

    // After processing, update default address in customer table
    if (!empty($default_address_data)) {
        // ✅ Get names of country, state, and city
        $country = $this->db->get_where('countries', ['id' => $default_address_data['shipping_country_id']])->row();
        $state   = $this->db->get_where('states', ['id' => $default_address_data['shipping_state_id']])->row();
        $city    = $this->db->get_where('cities', ['id' => $default_address_data['shipping_city_id']])->row();

        $this->db->where('id', $id);
        $this->db->update('customer', array(
            'shipping_country_id'    => $default_address_data['shipping_country_id'],
            'shipping_country_name'  => $country ? $country->name : null,
            'shipping_state_id'      => $default_address_data['shipping_state_id'],
            'shipping_state_name'    => $state ? $state->name : null,
            'shipping_city_id'       => $default_address_data['shipping_city_id'],
            'shipping_city_name'     => $city ? $city->name : null,
            'shipping_address'       => $default_address_data['shipping_address'],
            'shipping_pincode'       => $default_address_data['shipping_pincode'],
        ));
    }
}

                    $entered_customer = $this->customer_model->get_single_record($id);

                    $ledger_data = array(
                        "title" => strtoupper($customer_name)
                    );

                    $this->ledger_model->edit_record($ledger_data, $entered_customer->ledger_id);

                    $log_data = array(
                        "user_id"       => $this->session->userdata("user_id"),
                        "module"        => "customer",
                        "user_action"   => 2,
                        "b_data"        => json_encode((array)$old_customer),
                        "data"          => json_encode((array)$entered_customer),
                        "description"   => $customer_name.' successfully updated.'
                    );
                    $this->log_data_model->add_record($log_data);

                    $this->session->set_flashdata('success', $customer_name.' is updated successfully');
                    redirect('customer','refresh');
                }
                else
                {
                    $this->session->set_flashdata('failure', $customer_name.' is failed to update');
                    redirect('customer','refresh');
                }
            }
        }
        else
        {
            $id = base64_decode($id);

            if($id != null)
            {
                $data['customer'] = $this->customer_model->get_single_record($id);

                if($data['customer'] != null)
                {
                    $data['countries'] = $this->utility_model->get_countries();
                    if($data['customer']->country_id != '')
                        $data['states'] = $this->utility_model->get_states($data['customer']->country_id);

                    if($data['customer']->state_id != '')
                        $data['cities'] = $this->utility_model->get_cities($data['customer']->state_id);
                    
                    $data['products'] = $this->db->get('product')->result();
                    $data['shipping_addresses'] = $this->customer_model->get_shipping_addresses($id);
                    $data['due_days_options'] = $this->db
    ->where('status', 1)
    ->where('delete_status', 0)
    ->get('due_days')
    ->result();
                    
                    $this->load->view('customer/edit',$data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                    redirect('customer');
                }
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                redirect('customer');
            }
        }
    }
}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_customer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);

			$customer = $this->customer_model->get_single_record($id);

			if($this->customer_model->edit_record($data,$id))
			{
				 $log_data = array(
			                      "user_id"     => $this->session->userdata("user_id"),
			                      "module"      => "customer",
			                      "user_action" => 3,
			                      "data"        => json_encode($data),
			                      "description" => $customer->customer_name.' deleted successfully.'
			                    );
      	$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', $customer->customer_name.' is deleted successfully');
				redirect('customer','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $customer->customer_name.' is failed to delete');
				redirect('customer','refresh');
			}
		}
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('view_customer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 	= base64_decode($id);

			if($id != null)
			{
				$data['customer'] 		= $this->customer_model->get_single_record($id);

				if($data['customer'] != null)
				{
					$data['sales']			= $this->sale_model->get_sale_records_by_customer_id($id);
          $data['user'] 			= $this->ion_auth_model->user($data['customer']->user_id)->row();
					$data['proforma_invoices']			= $this->proforma_invoice_model->get_proforma_invoice_records_by_customer_id($id);
					$data['sales_return']			= $this->sales_return_model->get_sales_return_records_by_customer_id($id);
					$data['quotations']			= $this->quotation_model->get_quotation_records_by_customer_id($id);
					$data['shipping_addresses'] = $this->customer_model->get_shipping_addresses($id);
					$this->load->view('customer/view',$data);
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('customer','refresh');
				}
				
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('customer','refresh');
			}
		}
	}

function get_record_details()
{
    $customer_id = $this->input->post('customer_id');
    $data['customer'] = $this->customer_model->get_single_record($customer_id);
    $data['company_setting'] = $this->company_settings_model->get_company_records();    

    // Get all shipping addresses for this customer
    $data['shipping_addresses'] = $this->db->get_where('customer_shipping_addresses', ['customer_id' => $customer_id])->result();
    
    $data['futureDate'] = '';

    if($data['customer']->payment_duration == 0)
    {
        $data['futureDate']  = date('d-m-Y', strtotime($data['company_setting']->payment_duration_for_sale.' days')); 
    }
    else
    {
        $data['currentDate'] = date('d-m-Y');
        $data['futureDate']  = date('d-m-Y', strtotime('+'.$data['customer']->payment_duration.' days', strtotime($data['currentDate'])));
    }

    $data['proforma_invoices'] = $this->proforma_invoice_model->get_proforma_invoice_records_by_customer_id($customer_id);
    
    echo json_encode($data);
}

	public function import_customer()
	{
		if(!$this->permission_model->has_permission('import_customer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$filename = $this->upload_model->do_single_upload($_FILES['csvfile']);	
				$file_path = './assets/import/'.$filename;

				$query = sprintf(
													"
														LOAD DATA LOCAL INFILE '%s' INTO TABLE customer 
								            FIELDS TERMINATED BY ',' 
								            LINES TERMINATED BY '\r\n'
								            IGNORE 1 LINES (customer_name, gstin, email, phone, country_name, state_name, city_name, address, pincode, shipping_country_name, shipping_state_name, shipping_city_name, shipping_address, shipping_pincode);
							           	", 
							          addslashes($file_path));

				if($number = $this->db->query($query))
				{
					$no_imported_customers = $this->db->affected_rows();

					$imported_customers = $this->db->query('SELECT * FROM `customer` `c` ORDER BY id DESC LIMIT '.$no_imported_customers)->result();

					foreach ($imported_customers as $value) {
						$ledger_data = array(
																"title" 						=> strtoupper($value->customer_name),
																"account_group_id"	=> CUSTOMER_ACCOUNT_GROUP_ID
															);
					
						$ledger_id = $this->ledger_model->add_record($ledger_data);			

						$data = array(
													"ledger_id" => $ledger_id,
													"user_id" => $this->session->userdata('user_id'),
													"country_id" => $this->utility_model->get_country_by_name($value->country_name)->id,
													"state_id" => $this->utility_model->get_state_by_name($value->state_name)->id,
													"city_id" => $this->utility_model->get_city_by_name($value->city_name)->id,
													"shipping_country_id" => $this->utility_model->get_country_by_name($value->shipping_country_name)->id,
													"shipping_state_id" => $this->utility_model->get_state_by_name($value->shipping_state_name)->id,
													"shipping_city_id" => $this->utility_model->get_city_by_name($value->shipping_city_name)->id
												);

						$this->customer_model->edit_record($data, $value->id);
					}

					$this->session->set_flashdata('success', $no_imported_customers.' Customers are imported successfully');
					redirect('customer','refresh');
				}
				else
				{
					$this->session->set_flashdata('success', $no_imported_customers.' Customers are failed to import.');
					redirect('customer','refresh');
				}
			}
			else
			{
				$data = array();
				if($this->input->is_ajax_request()) 
				{
					$response 									= array();
			  	$response['code']  					= 1;
					$response['import_customer_modal_body'] = $this->load->view('customer/ajax/import_customer_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->customer_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

        //view Button
        if($this->permission_model->has_permission('view_customer'))
				{	
        	$table_body .=	'
                            <a href="'.base_url('customer/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="'.$this->lang->line('customer_view').'">
                              <i class="fas fa-eye"></i> View
                            </a>
                          ';
        }

				// Edit Button        
        if($this->permission_model->has_permission('edit_customer'))
				{	
					$table_body .= '
                            <a href="'.base_url('customer/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" title="'.$this->lang->line('customer_edit').'">
                              <i class="fas fa-edit"></i> Edit
                            </a>      
                          ';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_customer'))
				{	
					$table_body .= '
														<a href="#"  data-toggle="modal" data-target="#delete_customer" data-tt="tooltip" title="'.$this->lang->line('customer_delete').'" class="btn btn-danger btn-xs delete_customer" data-customer_id="'.$item->id.'">
                              <i class="fas fa-trash"></i> Delete
                            </a>
													';
				}

			

				/* End Action column buttons*/

        $row = array();
       
        $row[] = $item->customer_name;
        $row[] = $item->gstin;
        $row[] = $item->email;
        $row[] = $item->phone;
        $row[] = $item->country_name;
        $row[] = $item->state_name;
        $row[] = $item->address;
        $row[] = $item->city_name;

				$row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->customer_model->count_all(),
                    "recordsFiltered" => $this->customer_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function customer_delete_confirmation()
  {
  	$customer_id 					= $this->input->post('customer_id');
  	$data['customer'] 		= $this->customer_model->get_single_record($customer_id);

  	$response 					= array();
  	$response['customer_delete_modal_body'] 	= $this->load->view('customer/ajax/customer_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

  public function create_login($customer_id = null)
	{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
			
				
				$this->load->config('ion_auth', TRUE);

				$identity_column = $this->config->item('identity', 'ion_auth');

				// Get the 'tables' configuration from Ion Auth
				$tables 				= $this->config->item('tables', 'ion_auth');
				//$this->form_validation->set_rules("email","Email","required");
				$this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[' . $tables['users'] . '.email]');
				$this->form_validation->set_rules("password","Password","required");
				
				if($this->form_validation->run()==FALSE)
				{
					$response = array(
							'code' => 0,
							'errors' => $this->form_validation->error_array() // Get validation errors
					);
					echo json_encode($response);
				
				}
				else
				{	
					$customer_id = $this->input->post("customer_id");
	
					$first_name  = $this->input->post("first_name");
					$last_name    = $this->input->post("last_name");
					
					$email 			= $this->input->post("email");
					$identity   = ($identity_column === 'email') ? $email : $this->input->post('identity');
					
					$phone 			= $this->input->post("phone");
					$password 	= $this->input->post("password");

					$customer_user_role = $this->utility_model->get_records_by_field('groups','name',CUSTOMER_GROUP_NAME,$row = true,$check_delete_status = false);
					$customer_group_id = 0;

					if($customer_user_role == null)
						$customer_group_id = $this->ion_auth->create_group('customer','Customer');
					else
						$customer_group_id = $customer_user_role->id;

					$group[]    = $customer_group_id;
				
					$user_data  = array(
													"first_name" 	=> $first_name,
													"last_name" 	=> $last_name,
													"phone" 			=> $phone
												);
	
					$this->db->trans_begin();
					
					if($id = $this->ion_auth_model->register($identity, $password, $email,  $user_data, $group))
					{
					

						$user_info = $this->ion_auth_model->user($id)->row();
						$data = array('user_id' => $user_info->id,'email' => $email);

						$active = array("active" 	=> ACCOUNT_STATUS_ACTIVE);

						$this->ion_auth_model->update($id,$active);
						$this->customer_model->edit_record($data,$customer_id);

						$company_setting						= $this->company_settings_model->get_company_records();	

						if (!empty($user_info)) 
						{
								$data['username'] = $user_info->{$identity_column};
								$data['plain_password'] = $this->input->post("password");

								$mail_data 											= array();
					
								$mail_data['From'] 							= 'zivaansolutions@gmail.com';
								$mail_data['FromName'] 					= $company_setting->company_name;
								$mail_data['AddReplyTo'] 				= 'zivaansolutions@gmail.com';
								$mail_data['ConfirmReadingTo']	= 'zivaansolutions@gmail.com';
								$mail_data['To']								= $email;
							
								$mail_data['Subject'] 					= 'Your account credentials';
								$mail_data['Body'] 							= $this->load->view('customer/email_view', $data, true);

								// print_r($mail_data);
								// exit;

						
			
								$response = array();
			
								if($email != '')
								{
									// if($this->email_model->send_user_mail($mail_data))
									// {
										$response['code'] 			= 1;
										$response['message'] 		= 'User account created successfully';
										echo json_encode($response);
									// }
									// else
									// {
									// 	$response['code'] 			= 0;
									// 	$response['message'] 		= 'Failed to send activation mail. Please activate user manually.';
									// 	echo json_encode($response);
									// }	
								}
								else
								{
									$response['code'] 				= 0;
									$response['message'] 			= 'Failed to create User account';
									echo json_encode($response);
								}

						}

						// commit transaction
						$this->db->trans_commit();
	
						// if($this->input->is_ajax_request())
						// {	
						// 	$response 											= array();
						// 	$response['code'] 							= 1;
						// 	$response['id'] 								= $id;
						// 	$response['message']						= 'User account created successfully.';
						// 	echo json_encode($response);
						// }
						// else
						// {
						// 	$this->session->set_flashdata('success', 'User account created successfully.');
						// 	redirect('customer','refresh');
						// }
					}
					else
					{
						//log_message('error', 'Ion Auth Registration Error: ' . $this->ion_auth_model->errors());
						// rollback transaction
						$this->db->trans_rollback();
	
						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 0;
							$response['message']						= 'Failed to create User account';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Failed to create User account');
							redirect('customer','refresh');
						}
						
					}
				}
	
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($customer_id);
					
					$data['customer'] 	= $this->customer_model->get_single_record($id);
					$data['user'] 			= $this->ion_auth_model->user($data['customer']->user_id)->row();

		
          $response 									= array();
			  	$response['code']  					= 1;
					$response['create_login_modal_body'] = $this->load->view('customer/ajax/create_login_modal_body',$data,TRUE);
					
			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['customer'] 		= $this->customer_model->get_single_record($id);

					if($data['customer'] != null)
					{

						$this->load->view('customer/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('customer/');
					}
				}
				
			}
	
	}

  
	public function resend_password()
	{
		$user_id          = $this->input->post('user_id');

		$user_info        = $this->ion_auth_model->user($user_id)->row();

		$company_setting	= $this->company_settings_model->get_company_records();	

		if (!empty($user_info)) 
		{
				$data['username'] = $user_info->username;

				// Generate a random password
				$random_password = $this->generateRandomString();
				$data['plain_password'] = $random_password;

				// print_r($data['plain_password']);

				$mail_data 											= array();
	
				$mail_data['From'] 							= 'zivaansolutions@gmail.com';
				$mail_data['FromName'] 					= $company_setting->company_name;
				$mail_data['AddReplyTo'] 				= 'zivaansolutions@gmail.com';
				$mail_data['ConfirmReadingTo']	= 'zivaansolutions@gmail.com';
				$mail_data['To']								= $user_info->email;
			
				$mail_data['Subject'] 					= 'Your account credentials';
				$mail_data['Body'] 							= $this->load->view('customer/password_view', $data, true);
 
				$response = array();
				
				
				// print_r($new_passwordb);
				// exit;

				if($user_info->email != '')
				{	
					$identity 			= $user_info->{$this->config->item('identity', 'ion_auth')};
					if($this->email_model->send_user_mail($mail_data) && $this->ion_auth->reset_password($identity, $data['plain_password']))
					{
						$response['code'] 					= 1;
						// $response['message'] 		= 'User account created successfully';
						echo json_encode($response);
					}
					else
					{
						$response['code'] 			= 0;
						// $response['message'] 		= 'Failed to create User account';
						echo json_encode($response);
					}	
				}
				else
				{
					$response['code'] 				= 0;
					$response['message'] 			= 'Failed to update password';
					echo json_encode($response);
				}

		}

		
	}

	// Function to generate a random string
	function generateRandomString($length = 8)
	{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
	}

  public function disable_login()
	{
		$user_id = $this->input->post('user_id');

		$data = array('active' => ACCOUNT_STATUS_INACTIVE);

		if( $this->ion_auth_model->update($user_id, $data))
		{
			$response['code'] 			= 1;
			//$response['message'] 		= 'User account created successfully';
			echo json_encode($response);
		}
		else
		{
			$response['code'] 			= 0;
			//$response['message'] 		= 'Failed to create User account';
			echo json_encode($response);
		}	
	
	}

	public function enable_login()
	{
		$user_id = $this->input->post('user_id');

		$data = array('active' => ACCOUNT_STATUS_ACTIVE);

		if( $this->ion_auth_model->update($user_id, $data))
		{
			$response['code'] 			= 1;
			//$response['message'] 		= 'User account created successfully';
			echo json_encode($response);
		}
		else
		{
			$response['code'] 			= 0;
			//$response['message'] 		= 'Failed to create User account';
			echo json_encode($response);
		}	
	
	}
	
	public function get_billing_details() {
    $customer_id = $this->input->post('customer_id');
    $data = $this->customer_model->get_billing_details($customer_id);
    
    if ($data) {
        $html = '<table class="table table-bordered">';
        $html .= '<tr><th>Name</th><td>' . $data->customer_name . '</td></tr>';
        $html .= '<tr><th>GSTIN</th><td>' . ($data->gstin ? $data->gstin : 'N/A') . '</td></tr>';
        $html .= '<tr><th>Email</th><td>' . $data->email . '</td></tr>';
        $html .= '<tr><th>Phone</th><td>' . $data->phone . '</td></tr>';
        $html .= '<tr><th>Address</th><td>' . $data->address . ', ' . $data->city_name . ', ' . $data->state_name . ', ' . $data->country_name . ' - ' . $data->pincode . '</td></tr>';
        $html .= '</table>';
        echo $html;
    } else {
        echo '<p class="text-danger">No billing details found</p>';
    }
}

public function get_shipping_details() {
    $customer_id = $this->input->post('customer_id');
    $data = $this->customer_model->get_shipping_details($customer_id);
    
    if ($data) {
        $html = '<table class="table table-bordered">';
        $html .= '<tr><th>Address Name</th><td>' . $data->shipping_name . '</td></tr>';
        $html .= '<tr><th>Address</th><td>' . $data->shipping_address . ', ' . $data->city_name . ', ' . $data->state_name . ', ' . $data->country_name . ' - ' . $data->shipping_pincode . '</td></tr>';
        $html .= '<tr><th>Status</th><td>' . ($data->is_default ? '<span class="badge badge-success">Default</span>' : '') . '</td></tr>';
        $html .= '</table>';
        echo $html;
    } else {
        echo '<p class="text-danger">No shipping details found</p>';
    }
}

public function get_existing_shipping_addresses() {
    $customer_id = $this->input->post('customer_id');
    $addresses = $this->customer_model->get_existing_shipping_addresses($customer_id);
    
    if ($addresses) {
        $html = '<div class="list-group">';
        foreach ($addresses as $address) {
            $html .= '<div class="list-group-item">';
            $html .= '<h5>' . $address->shipping_name . ' ' . ($address->is_default ? '<span class="badge badge-success">Default</span>' : '') . '</h5>';
            $html .= '<p>' . $address->shipping_address . ', ' . $address->city_name . ', ' . $address->state_name . ', ' . $address->country_name . ' - ' . $address->shipping_pincode . '</p>';
            $html .= '<div class="btn-group btn-group-sm">';
            $html .= '<button class="btn btn-primary select-shipping-address" data-id="' . $address->id . '">Select</button>';
            if (!$address->is_default) {
                $html .= '<button class="btn btn-secondary set-default-address" data-id="' . $address->id . '">Set as Default</button>';
            }
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        echo $html;
    } else {
        echo '<p class="text-muted">No saved shipping addresses found</p>';
    }
}

public function add_shipping_address() {
    $data = $this->input->post();
    $data['created_date'] = date('Y-m-d H:i:s');
    
    if ($this->customer_model->add_shipping_address($data)) {
        if (isset($data['is_default']) && $data['is_default']) {
            $this->customer_model->set_default_shipping_address($this->db->insert_id(), $data['customer_id']);
        }
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false));
    }
}

public function set_default_shipping_address() {
    $address_id = $this->input->post('address_id');
    $customer_id = $this->input->post('customer_id');
    
    if ($this->customer_model->set_default_shipping_address($address_id, $customer_id)) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false));
    }
}

public function select_shipping_address() {
    $address_id = $this->input->post('address_id');
    $customer_id = $this->input->post('customer_id');
    
    // Here you would typically update the sale record with the selected shipping address
    // For now, we'll just return success
    
    echo json_encode(array('success' => true));
}
		
}
