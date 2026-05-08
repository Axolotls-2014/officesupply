<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Due_days extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $this->load->model('due_days_model');
    }

    public function index()
    {
        if(!$this->permission_model->has_permission('list_due_days')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            $this->load->view('due_days/list');    
        }
    }
    
    public function add($id = NULL)
    {
        if(!$this->permission_model->has_permission('add_due_days')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            if($this->input->server('REQUEST_METHOD') === 'POST') {
                $this->form_validation->set_rules("due_day", "Due Days", "required");
                $this->form_validation->set_rules("terms_and_condition", "Terms And Condition", "required");

                if($this->form_validation->run() == FALSE) {
                    $this->load->view('due_days/add');
                } else {
                    $data = array(
                        "due_day" => $this->input->post("due_day"),
                        "terms_and_condition" => $this->input->post("terms_and_condition"),
                        "status" => $this->input->post("status"),
                        "user_id" => $this->session->userdata('user_id')
                    );

                    $this->db->trans_begin();
                    
                    if($id = $this->due_days_model->add_record($data)) {
                        $this->db->trans_commit();

                        if($this->input->is_ajax_request()) {    
                            $response = array(
                                'code' => 1,
                                'id' => $id,
                                'message' => 'Due Day added successfully.',
                                'due_days' => $this->due_days_model->get_records()
                            );
                            echo json_encode($response);
                        } else {
                            $this->session->set_flashdata('success', 'Due Day added successfully');
                            redirect('due_days','refresh');
                        }
                    } else {
                        $this->db->trans_rollback();
                        if($this->input->is_ajax_request()) {    
                            $response = array(
                                'code' => 0,
                                'message' => 'Failed to add Due Day.'
                            );
                            echo json_encode($response);
                        } else {
                            $this->session->set_flashdata('failure', 'Failed to add Due Day.');
                            redirect('due_days','refresh');
                        }
                    }
                }
            } else {
                $data = array();
                if($this->input->is_ajax_request()) {
                    $response = array(
                        'code' => 1,
                        'add_due_day_modal_body' => $this->load->view('due_days/ajax/add_due_day_modal_body', $data, TRUE)
                    );
                    echo json_encode($response);
                } else {
                    $this->load->view('due_days/add', $data);
                }
            }
        }
    }

public function edit($id = null)
{
    if(!$this->permission_model->has_permission('edit_due_days')) {
        $this->load->view('errors/html/error_restricted'); 
        return;
    }

    if($this->input->server('REQUEST_METHOD') === 'POST') {
        // Handle POST request (form submission)
        $id = $this->input->post('id');
        $this->form_validation->set_rules("due_day", "Due Day", "required");
        $this->form_validation->set_rules("terms_and_condition", "Terms and Condition", "required");

        if($this->form_validation->run() == FALSE) {
            $response = array(
                'code' => 0,
                'message' => validation_errors()
            );
        } else {
            $data = array(
                "due_day" => $this->input->post("due_day"),
                "terms_and_condition" => $this->input->post("terms_and_condition"),
                "status" => $this->input->post("status"),
                "user_id" => $this->session->userdata('user_id')
            );

            if($this->due_days_model->edit_record($data, $id)) {
                $response = array(
                    'code' => 1,
                    'message' => 'Due Day updated successfully'
                );
            } else {
                $response = array(
                    'code' => 0,
                    'message' => 'Failed to update Due Day'
                );
            }
        }
        echo json_encode($response);
        return;
    }

    // Handle GET request (show form)
    if ($id === null || $id == '') {
        $id = $this->input->get('due_day_id');
    }
    
    $id = base64_decode($id);
    $data['due_day'] = $this->due_days_model->get_single_record($id);
    
    if (!$data['due_day']) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['error' => 'Record not found']);
            return;
        } else {
            show_error("Due Day record not found", 404);
        }
    }

    if ($this->input->is_ajax_request()) {
        $html = $this->load->view('due_days/ajax/edit_due_day_modal_body', $data, TRUE);
        echo $html;
    } else {
        $this->load->view('due_days/list', $data);
    }
}

    public function delete()
    {
        if(!$this->permission_model->has_permission('delete_due_days')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            $id = $this->input->post('id');
            $data = array('delete_status' => 1);

            if($this->due_days_model->edit_record($data, $id)) {
                if($this->input->is_ajax_request()) {    
                    $response = array(
                        'code' => 1,
                        'id' => $id,
                        'message' => 'Due Day deleted successfully.'
                    );
                    echo json_encode($response);
                } else {
                    $this->session->set_flashdata('success', 'Due Day deleted successfully');
                    redirect('due_days','refresh');
                }
            } else {
                if($this->input->is_ajax_request()) {    
                    $response = array(
                        'code' => 0,
                        'message' => 'Failed to delete Due Day.'
                    );
                    echo json_encode($response);
                } else {
                    $this->session->set_flashdata('failure', 'Failed to delete Due Day');
                    redirect('due_days');
                }
            }
        }
    }

    public function import_due_days()
    {
        if(!$this->permission_model->has_permission('import_due_days')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            if($this->input->server('REQUEST_METHOD') === 'POST') {    
                $isUpdateDueDayChecked = $this->input->post('update_due_day') === '1';
                $isUpdateCreateNewIfExistChecked = $this->input->post('create_due_day') === '1';

                $expectedHeaders = ['DueDay', 'TermsAndCondition'];
                $isCSVFileValid = is_csv_valid($_FILES["csvfile"]["tmp_name"], ',', $expectedHeaders);

                if ($isCSVFileValid !== true) {
                    $this->session->set_flashdata('failure', $isCSVFileValid);
                    redirect('due_days','refresh');
                } else {
                    if(is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
                        $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);

                        if(!empty($csvData)) {
                            foreach($csvData as $row) { 
                                $due_day_data = array(
                                    'due_day' => $row['DueDay'],
                                    'terms_and_condition' => $row['TermsAndCondition'],
                                    'user_id' => $this->session->userdata('user_id')
                                );

                                $exist_due_day = $this->utility_model->get_records_by_field('due_days', 'due_day', $row['DueDay'], false, true);

                                if($exist_due_day == null || $isUpdateCreateNewIfExistChecked) {
                                    $this->due_days_model->add_record($due_day_data);
                                } elseif($isUpdateDueDayChecked) {
                                    foreach ($exist_due_day as $value) {
                                        $this->due_days_model->edit_record($due_day_data, $value->id);    
                                    }
                                }
                            }

                            $this->session->set_flashdata('success', 'Due Days imported successfully.');
                            redirect('due_days','refresh');
                        }
                    } else {
                        $this->session->set_flashdata('failure', 'Failed to import Due Days.');
                        redirect('due_days','refresh');
                    }
                }
            } else {
                $data = array();
                if($this->input->is_ajax_request()) {
                    $response = array(
                        'code' => 1,
                        'import_due_day_modal_body' => $this->load->view('due_days/ajax/import_due_day_modal_body', $data, TRUE)
                    );
                    echo json_encode($response);
                } else {
                    $this->load->view('errors/html/error_restricted'); 
                }
            }
        }
    }

    public function ajax_list()
    {
        $list = $this->due_days_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
    
        foreach ($list as $item) {  
            // Action buttons
            $actions = '<div class="btn-group">';
            
            if($this->permission_model->has_permission('edit_due_days')) {    
                $actions .= '<a href="#" class="btn btn-info btn-xs edit_due_day_modal" 
                    data-due_day_id="'.base64_encode($item->id).'" 
                    data-toggle="modal" 
                    data-target="#edit_due_day_modal" 
                    title="Edit Due Day">
                    <i class="fas fa-edit"></i>
                </a>';
            }

            if($this->permission_model->has_permission('delete_due_days')) {    
                $actions .= '<a href="#" data-toggle="modal" data-target="#delete_due_day_modal" data-tt="tooltip" title="Delete Due Day" class="btn btn-danger btn-xs delete_due_day_modal" data-due_day_id="'.$item->id.'">
                              <i class="fas fa-trash"></i>
                            </a>';
            }
            
            $actions .= '</div>';

            // Status
            $status = ($item->status == 1) 
                ? '<span class="badge badge-success">Active</span>' 
                : '<span class="badge badge-danger">Inactive</span>';

            $row = array();
            $row[] = $item->due_day;
            $row[] = $item->terms_and_condition;
            $row[] = $status;
            $row[] = $actions;
            
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->due_days_model->count_all(),
            "recordsFiltered" => $this->due_days_model->count_filtered(),
            "data" => $data,
        );
        
        echo json_encode($output);
    }

    public function due_day_delete_confirmation()
    {
        $due_day_id = $this->input->post('due_day_id');
        $data['due_day'] = $this->due_days_model->get_single_record($due_day_id);

        $response = array(
            'delete_due_day_modal_body' => $this->load->view('due_days/ajax/delete_due_day_modal_body', $data, TRUE)
        );

        echo json_encode($response);
    }
}