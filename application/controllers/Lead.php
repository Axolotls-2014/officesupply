<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

defined('BASEPATH') OR exit('No direct script access allowed');

class Lead extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
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
        
        $data['customer'] = $this->Lead_model->get_records();
        $log_data = array(
            "user_id"     => $this->session->userdata("user_id"),
            "module"      => "lead",
            "user_action" => 0,
            "description" => "User has viewed list of customer."
        );

        $this->log_data_model->add_record($log_data); 

        $this->load->view('lead/list', $data);
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
            $this->form_validation->set_rules('lead_name','Lead name','required');    
            $this->form_validation->set_rules('source','Source','required');    
            $this->form_validation->set_rules('status','Status','required');   
            $this->form_validation->set_rules('date_added','Date','required');    



            if($this->form_validation->run() == FALSE)
            {
               
                $country_id = $this->input->post('country_id');
                $state_id = $this->input->post('state_id');
                $data['countries'] = $this->utility_model->get_countries();
                $data['leads_status'] = $this->utility_model->get_lead_status();
                $data['leads_sources'] = $this->utility_model->get_lead_sources();
                $data['employees']        = $this->employee_model->get_records();

                if($country_id != '')
                {
                    $data['states'] = $this->utility_model->get_states($country_id);
                }

                if($state_id != '')
                {
                    $data['cities'] = $this->utility_model->get_cities($state_id);
                }

                $this->load->view('lead/add', $data);
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

                $customer_name = $this->input->post('lead_name');
                $data = array(
                    "lead_name" => $customer_name,
                    "customer_buyer_designation" => $this->input->post('customer_buyer_designation'),
                    "customer_department" => $this->input->post('customer_department'),
                    "gstin" => $this->input->post('gstin'),
                    "phone" => $this->input->post('phone'),
                    "country_id" => $this->input->post('country_id'),
                    "state_id" => $this->input->post('state_id'),
                    "address" => $this->input->post('address'),
                    "city_id" => $this->input->post('city_id'),
                    "pincode" => $this->input->post('pincode'),
                    "whatsapp_no" => $this->input->post('whatsapp_no'),
                    "whatsapp_country_code" => $this->input->post('whatsapp_country_code'),
                    "source" => $this->input->post('source'),
                    "status" => $this->input->post('status'),
                    "date_added" => $this->input->post('date_added'),
                    "description" => $this->input->post('description'),
                    "remark" => $this->input->post('remark'),
                    "employee_id" => $this->input->post('employee_id'),
					"reffered_by"	=> 	$this->input->post('reffered_by'),
					"reffered_by_contact_no"	=> 	$this->input->post('reffered_by_contact_no'),
					"employee_contact_no"	=> 	$this->input->post('employee_contact_no'),
					"requirement"	=> 	$this->input->post('requirement'),
                    "ledger_id" => $this->ledger_model->add_record(array(
                        "title" => strtoupper($customer_name),
                        "account_group_id" => CUSTOMER_ACCOUNT_GROUP_ID
                    )),
                    "branch_id" => $branch_id, 
                    "added_by" => $user_id 
                );

                if($id = $this->Lead_model->add_record($data))
                {

                    $this->session->set_flashdata('success', $customer_name.' is added successfully');
                    redirect('lead', 'refresh');
                }
                else
                {
                    $this->session->set_flashdata('failure', $customer_name.' failed to add');
                    redirect('lead');
                }
            }
        }
        else
        {
            $company_setting = $this->company_settings_model->get_company_records();
            $data['countries'] = $this->utility_model->get_countries();
            $data['states'] = $this->utility_model->get_states($company_setting->country_id);
            $data['cities'] = $this->utility_model->get_cities($company_setting->state_id);
            $data['employees']        = $this->employee_model->get_records();

            $data['leads_status'] = $this->utility_model->get_lead_status();
            $data['leads_sources'] = $this->utility_model->get_lead_sources();
            $this->load->view('lead/add', $data);
        }
    }
}


public function add_task()
{
    // Check user permissions
    if (!$this->permission_model->has_permission('add_customer')) {
        echo json_encode(['status' => 'error', 'message' => 'Permission denied']);
        return;
    } else {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Set form validation rules for adding a task
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('priority', 'Priority', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');

            if ($this->form_validation->run() == FALSE) {
                // Return validation errors as JSON
                echo json_encode(['status' => 'error', 'message' => validation_errors()]);
                return;
            } else {
                // Get user ID from session
                $user_id = $this->session->userdata('user_id');

                // Prepare data for insertion
                $task_data = array(                  
                    "lead_id" => $this->input->post('lead_id'),
                    "title" => $this->input->post('title'),
                    "description" => $this->input->post('description'),
                    "start_date" => $this->input->post('start_date'),
                    "due_date" => $this->input->post('due_date'),
                    "priority" => $this->input->post('priority'),
                    "status" => $this->input->post('status'),
                    "created_date" => date('Y-m-d H:i:s'), // Current timestamp
                    "updated_date" => date('Y-m-d H:i:s'), // Current timestamp
                    "added_by" => $user_id
                );

                // Directly insert data into the 'tasks' table
                $insert_result = $this->db->insert('lead_tasks', $task_data);

                if ($insert_result) {
                    echo json_encode(['status' => 'success', 'message' => 'Task added successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add task.']);
                }
            }
        } else {
            // Return a response if it's not a POST request
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
    }
}

public function update_task()
{
    // Check user permissions (e.g., for editing tasks)
    if (!$this->permission_model->has_permission('add_customer')) {
        echo json_encode(['status' => 'error', 'message' => 'Permission denied']);
        return;
    } else {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Set form validation rules for updating a task
            $this->form_validation->set_rules('task_id', 'Task ID', 'required|numeric');
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('priority', 'Priority', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');

            if ($this->form_validation->run() == FALSE) {
                // Return validation errors as JSON
                echo json_encode(['status' => 'error', 'message' => validation_errors()]);
                return;
            } else {
                // Get user ID from session
                $user_id = $this->session->userdata('user_id');

                // Get task ID
                $task_id = $this->input->post('task_id');

                // Prepare data for updating the task
                $task_data = array(
                    "title" => $this->input->post('title'),
                    "description" => $this->input->post('description'),
                    "start_date" => $this->input->post('start_date'),
                    "due_date" => $this->input->post('due_date'),
                    "priority" => $this->input->post('priority'),
                    "status" => $this->input->post('status'),
                    "updated_date" => date('Y-m-d H:i:s'), // Current timestamp
                );

                // Update the task in the 'tasks' table
                $this->db->where('task_id', $task_id);
                $update_result = $this->db->update('lead_tasks', $task_data);

                if ($update_result) {
                    echo json_encode(['status' => 'success', 'message' => 'Task updated successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update task.']);
                }
            }
        } else {
            // Return a response if it's not a POST request
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
    }
}



public function add_reminder() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Set validation rules for the form fields
            $this->form_validation->set_rules('reminder_time', 'Reminder Time', 'required');
            $this->form_validation->set_rules('note', 'Note', 'required');

            if ($this->form_validation->run() == FALSE) {
                // Send JSON response with validation errors
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
                echo json_encode($response);
                return;
            } else {
                // Prepare data for insertion
                $data = array(
                    'reminder_time' => $this->input->post('reminder_time'),
                    'note' => $this->input->post('note'),
                    'created_by' => $this->session->userdata('user_id'), // Ensure user ID is in session
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'lead_id' => $this->input->post('lead_id') // Assuming lead ID is passed to the form
                );

             $insert_result = $this->db->insert('task_reminders', $data);

                if ($insert_result) {
                    echo json_encode(['status' => 'success', 'message' => 'Reminder added successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add Reminder.']);
                }
                
            }
        }
    }
    
    public function update_reminder() {
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        // Set form validation rules
        $this->form_validation->set_rules('reminder_time', 'Reminder Time', 'required');
        $this->form_validation->set_rules('note', 'Note', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        } else {
            // Get data from POST
            $reminder_id = $this->input->post('id');
            $reminder_data = [
                'reminder_time' => $this->input->post('reminder_time'),
                'note' => $this->input->post('note'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Update reminder in the database
            $this->db->where('reminder_id', $reminder_id);
            $update_result = $this->db->update('task_reminders', $reminder_data);

            if ($update_result) {
                echo json_encode(['status' => 'success', 'message' => 'Reminder updated successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update reminder.']);
            }
        }
    }
}

    
    public function add_followup() {
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        // Set form validation rules
        $this->form_validation->set_rules('followup_date', 'Follow-up Date', 'required');
        $this->form_validation->set_rules('followup_notes', 'Notes', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('reminder', 'Reminder Date', 'required');
        $this->form_validation->set_rules('lead_id', 'Lead ID', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            // Return validation errors as JSON response
            $response = array(
                'status' => 'error',
                'message' => validation_errors()
            );
            echo json_encode($response);
            return;
        } else {
            // Prepare data for insertion
            $data = array(
                'lead_id' => $this->input->post('lead_id'),
                'followup_date' => $this->input->post('followup_date'),
                'followup_notes' => $this->input->post('followup_notes'),
                'status' => $this->input->post('status'),
                'reminder' => $this->input->post('reminder'),
                'created_by' => $this->session->userdata('user_id'), // Ensure user ID is in session

            );

            // Insert data into the database
            $insert_result = $this->db->insert('lead_followups', $data);

            if ($insert_result) {
                echo json_encode(['status' => 'success', 'message' => 'Follow-up added successfully!']);
            } else {
                // Capture and log database error details
                $error_message = $this->db->error();
                log_message('error', 'Database error: ' . print_r($error_message, true));
                
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add follow-up. Please try again.',
                    'error_detail' => $error_message
                ]);
            }
        }
    }
}

public function update_followup() {
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        // Set form validation rules
        $this->form_validation->set_rules('followup_date', 'Follow-up Date', 'required');
        $this->form_validation->set_rules('followup_notes', 'Follow-up Notes', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('reminder', 'Reminder Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        } else {
            // Get data from POST
            $followup_id = $this->input->post('id');
            $followup_data = [
                'followup_date' => $this->input->post('followup_date'),
                'followup_notes' => $this->input->post('followup_notes'),
                'status' => $this->input->post('status'),
                'reminder' => $this->input->post('reminder'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Update follow-up in the database
            $this->db->where('id', $followup_id);
            $update_result = $this->db->update('lead_followups', $followup_data);

            if ($update_result) {
                echo json_encode(['status' => 'success', 'message' => 'Follow-up updated successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update follow-up.']);
            }
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
				$id = $this->input->post('id');
				$old_customer = $this->Lead_model->get_single_record($id);

				$this->form_validation->set_rules('lead_name','Lead name','required');	
				$this->form_validation->set_rules('country_id','Country','required');
				$this->form_validation->set_rules('state_id','State','required');
	
				if($this->form_validation->run()==FALSE)
				{
					$country_id  			= $this->input->post('country_id');
					$state_id  				= $this->input->post('state_id');
					$data['countries'] 		= $this->utility_model->get_countries();
					$data['customer'] 		= $this->Lead_model->get_single_record($id);
                    $data['leads_status'] = $this->utility_model->get_lead_status();
                    $data['leads_sources'] = $this->utility_model->get_lead_sources();
                    $data['employees']        = $this->employee_model->get_records();

					if($country_id != '')
					{
						$data['states'] 		= $this->utility_model->get_states($country_id);
					}

					if($state_id != '')
					{
						$data['cities'] 		= $this->utility_model->get_cities($state_id);
					}

					$this->load->view('lead/edit',$data);
				}
				else
				{
					

					$customer_name 	= 	$this->input->post('lead_name');

					$customer_buyer_designation 	= 	$this->input->post('customer_buyer_designation');
					$customer_department 	= 	$this->input->post('customer_department');
					$phone 			= 	$this->input->post('phone');
					$country_id 	= 	$this->input->post('country_id');
					$state_id 		= 	$this->input->post('state_id');
					$address 		= 	$this->input->post('address');
					$pincode 		= 	$this->input->post('pincode');
					$city_id 		= 	$this->input->post('city_id');
					$status		= 	$this->input->post('status');
					$source 		= 	$this->input->post('source');
					$employee_id	= 	$this->input->post('employee_id');
					$description	= 	$this->input->post('description');
					$reffered_by	= 	$this->input->post('reffered_by');
					$employee_contact_no	= 	$this->input->post('employee_contact_no');
					$date_added	= 	$this->input->post('date_added');
					$reffered_by_contact_no	= 	$this->input->post('reffered_by_contact_no');
					$requirement	= 	$this->input->post('requirement');
					$remark	= 	$this->input->post('remark');


          $whatsapp_no 	= 	$this->input->post('whatsapp_no');
          $whatsapp_country_code 	= 	$this->input->post('whatsapp_country_code');

					$data			=	array(
												"lead_name"			=>	$customer_name,
												"customer_buyer_designation"			=>	$customer_buyer_designation,
												"customer_department"			=>	$customer_department,
												"date_added"			=>	$date_added,
												"phone"							=>	$phone,
												"country_id"				=>	$country_id,
												"state_id"					=>	$state_id,
												"address"						=>	$address,
												"city_id"						=>	$city_id,
												"pincode"						=>	$pincode,
												 "source" => $source,
                                                 "status" => $status,
                                                "whatsapp_no"		=>	$whatsapp_no,
                                                 "whatsapp_country_code"		=>	$whatsapp_country_code,
                                                "employee_id"		=>	$employee_id,
                                                "description"=>	$description,
                                                "reffered_by"=>	$reffered_by,
                                                "employee_contact_no"=>	$employee_contact_no,
                                                "date_added"=>	$date_added,
                                                "reffered_by_contact_no"=>	$reffered_by_contact_no,
                                                "requirement"=>	$requirement,
                                                "remark"=>	$remark


											);

					
					if($this->Lead_model->edit_record($data,$id))
					{
					    
					    
					       // Check if status is "Converted"
                       if ($status == 'Converted')
                    {
                        // Prepare the customer data
                        $customer_data = array(
                            "customer_name" => $customer_name,
                            "customer_buyer_designation" => $customer_buyer_designation,
                            "customer_department" => $customer_department,
                        
                            "phone" => $phone,
                            "country_id" => $country_id,
                            "state_id" => $state_id,
                            "address" => $address,
                            "city_id" => $city_id,
                            "pincode" => $pincode,
                            "whatsapp_no" => $whatsapp_no,
                            "whatsapp_country_code" => $whatsapp_country_code,
                            "added_by" => $user_id, // Ensure user_id and branch_id are available
                            "branch_id" => $branch_id,
                            "employee_id"=>$employee_id
                        );

                        // Insert into customer table
                        $this->db->insert('customer', $customer_data);

                        if ($this->db->affected_rows() > 0) {
                            // Insert was successful
                            $this->session->set_flashdata('success', 'Lead converted to customer successfully.');
                        } else {
                            // Print the error message directly
                            $error = $this->db->error();
                            log_message('error', 'Customer insert failed: ' . $error['message']);
                            $this->session->set_flashdata('failure', 'Failed to convert lead to customer.');
                        }
                    }

                    
                    
                    
						$entered_customer = $this->Lead_model->get_single_record($id);

			
						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "lead",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_customer),
											"data"			=> json_encode((array)$entered_customer),
											"description" 	=> $customer_name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $customer_name.' is updated successfully');
						redirect('lead','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $customer_name.' is failed to update');
						redirect('lead','refresh');
					}
				}
			}
			else
			{
				$id = base64_decode($id);

				if($id != null)
				{
					$data['customer'] 		= $this->Lead_model->get_single_record($id);

					if($data['customer'] != null)
					{
							$data['countries'] 		= $this->utility_model->get_countries();
							$data['leads_status'] = $this->utility_model->get_lead_status();
                            $data['leads_sources'] = $this->utility_model->get_lead_sources();
                            $data['employees']        = $this->employee_model->get_records();

							if($data['customer']->country_id != '')
								$data['states'] 		= $this->utility_model->get_states($data['customer']->country_id);

							if($data['customer']->state_id != '')
								$data['cities'] 		= $this->utility_model->get_cities($data['customer']->state_id);
							$this->load->view('lead/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('lead');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('lead');
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
				$data['customer'] 		= $this->Lead_model->get_single_record($id);
				if($data['customer'] != null)
				{
					$data['sales']			= $this->sale_model->get_sale_records_by_customer_id($id);
          $data['user'] 			= $this->ion_auth_model->user($data['customer']->user_id)->row();
					$data['proforma_invoices']			= $this->proforma_invoice_model->get_proforma_invoice_records_by_customer_id($id);
					$data['sales_return']			= $this->sales_return_model->get_sales_return_records_by_customer_id($id);
					$data['quotations']			= $this->quotation_model->get_quotation_records_by_customer_id($id);
					$data['tasks']			= $this->Lead_model->get_task_by_lead_id($id);
					$data['reminders']			= $this->Lead_model->get_reminder_by_lead_id($id);
					$data['followups']			= $this->Lead_model->get_followups_by_lead_id($id);
					$this->load->view('lead/view',$data);
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('lead','refresh');
				}
				
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('lead','refresh');
			}
		}
	}

	function get_record_details()
	{
		$customer_id = $this->input->post('customer_id');
		$data['customer'] = $this->customer_model->get_single_record($customer_id);
		$data['company_setting']	= $this->company_settings_model->get_company_records();	

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
    $list 		= $this->lead_model->get_datatables();
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
	
	
	
		public function feedback_list()
{
    if(!$this->permission_model->has_permission('list_customer'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
                        $data['employees']        = $this->employee_model->get_records();

        $user_id = $this->session->userdata('user_id');
        
        $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

        if ($udata && $udata->branch_id) {
            $this->db->where('branch_id', $udata->branch_id);
        }
        
        $data['feedback'] = $this->Lead_model->get_feedback();
        $log_data = array(
            "user_id"     => $this->session->userdata("user_id"),
            "module"      => "lead",
            "user_action" => 0,
            "description" => "User has viewed list of customer."
        );

        $this->log_data_model->add_record($log_data); 

        $this->load->view('lead/feedbacklist', $data);
    }
}


public function addfeedback()
{
    if(!$this->permission_model->has_permission('add_customer'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $this->form_validation->set_rules('name','Lead name','required');    
            $this->form_validation->set_rules('address','Source','required');    
            $this->form_validation->set_rules('phone_no','Status','required');   
            $this->form_validation->set_rules('visit_date','Date','required');    



            if($this->form_validation->run() == FALSE)
            {
               
                $country_id = $this->input->post('country_id');
                $state_id = $this->input->post('state_id');
                $data['countries'] = $this->utility_model->get_countries();
                $data['leads_status'] = $this->utility_model->get_lead_status();
                $data['leads_sources'] = $this->utility_model->get_lead_sources();
                $data['employees']        = $this->employee_model->get_records();

                if($country_id != '')
                {
                    $data['states'] = $this->utility_model->get_states($country_id);
                }

                if($state_id != '')
                {
                    $data['cities'] = $this->utility_model->get_cities($state_id);
                }

                $this->load->view('lead/addfeedback', $data);
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

                $name = $this->input->post('name');
                $data = array(
                    "name" => $name,
                    "address" => $this->input->post('address'),
                    "phone_no" => $this->input->post('phone_no'),
                    "visit_date" => $this->input->post('visit_date'),
                    "reference" => $this->input->post('reference'),
                    "request" => $this->input->post('request'),
                    "note" => $this->input->post('note'),
                    // "salesman" => $this->input->post('salesman'),
                    
                    "receptionist" => $this->input->post('receptionist'));

// Handle the salesman field (convert array to comma-separated string)
$salesman = $this->input->post('salesman');
if (!empty($salesman)) {
    // Convert the array of selected salesmen to a comma-separated string
    $data['salesman'] = implode(',', $salesman);
} else {
    $data['salesman'] = ''; // Optional: handle if no salesman is selected
}

                if($id = $this->Lead_model->add_feedback($data))
                {

                    $this->session->set_flashdata('success', $name.' is added successfully');
                    redirect('lead/feedback_list', 'refresh');
                }
                else
                {
                    $this->session->set_flashdata('failure', $name.' failed to add');
                    redirect('lead/feedback_list');
                }
            }
        }
        else
        {
            $company_setting = $this->company_settings_model->get_company_records();
            $data['countries'] = $this->utility_model->get_countries();
            $data['states'] = $this->utility_model->get_states($company_setting->country_id);
            $data['cities'] = $this->utility_model->get_cities($company_setting->state_id);
            $data['employees']        = $this->employee_model->get_records();

            $data['leads_status'] = $this->utility_model->get_lead_status();
            $data['leads_sources'] = $this->utility_model->get_lead_sources();
            $this->load->view('lead/addfeedback', $data);
        }
    }
}


public function editfeedback($id = null)
	{
		if(!$this->permission_model->has_permission('edit_customer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');
				$old_customer = $this->Lead_model->get_single_record_feedback($id);


				$this->form_validation->set_rules('name','Name','required');	

	
				if($this->form_validation->run()==FALSE)
				{

                    $data['employees']        = $this->employee_model->get_records();

				
					$this->load->view('lead/editfeedback',$data);
				}
				else
				{
					
$salesman = $this->input->post('salesman');  // Get the submitted salesman array

// If salesman array is not empty, convert it to a comma-separated string
if (!empty($salesman)) {
    $salesman = implode(',', $salesman);  // Join the selected salesman IDs into a string
} else {
    $salesman = '';  // If no salesman is selected, set it to an empty string
}

					$name 	= 	$this->input->post('name');
					$address 	= 	$this->input->post('address');
					$phone_no 	= 	$this->input->post('phone_no');
					$visit_date		= 	$this->input->post('visit_date');
					$reference 	= 	$this->input->post('reference');
					$request		= 	$this->input->post('request');
					$note 		= 	$this->input->post('note');
				// 	$salesman 		= 	$this->input->post('salesman');
					$receptionist		= 	$this->input->post('receptionist');



					$data			=	array(
												"name"			=>	$name,
												"address"			=>	$address,
												"phone_no"			=>	$phone_no,
												"visit_date"			=>	$visit_date,
												"reference"							=>	$reference,
												"request"				=>	$request,
												"note"					=>	$note,
												"salesman"=>	$salesman,
												"receptionist"	=>	$receptionist);

					
					if($this->Lead_model->edit_recordfeedback($data,$id))
					{
					  
						$entered_customer = $this->Lead_model->get_single_record_feedback($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "lead",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_customer),
											"data"			=> json_encode((array)$entered_customer),
											"description" 	=> $name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is updated successfully');
						redirect('lead/feedback_list','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to update');
						redirect('lead/feedback_list','refresh');
					}
				}
			}
			else
			{
				$id = base64_decode($id);
				if($id != null)
				{
					$data['customer'] 		= $this->Lead_model->get_single_record_feedback($id);

					if($data['customer'] != null)
					{

                            $data['employees']        = $this->employee_model->get_records();

							$this->load->view('lead/editfeedback',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('lead/feedback_list');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('lead/feedback_list');
				}

			}
		}
	}

		
}
