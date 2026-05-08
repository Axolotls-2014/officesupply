<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support_client extends MY_Controller {
    
	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	public function index(){
	     $user_id = $this->session->userdata('user_id');
        $user_info = $this->db->select('role, clients_branch_id, company_name')->where('id', $user_id)->get('users')->row();
    if ($user_info) {
        if ($user_info->role == 'super_admin') {
            $query = $this->db->get('client_support');
          
        }
	   else{
	          $this->db->where('added_by', $this->session->userdata('user_id'));
	         $query = $this->db->get('client_support');

	   }
    }
        $data['client_support'] = $query->result();
        $this->load->view('purchase_requests/support_client', $data);
	}
	
	public function insert_ticket() {
        $this->load->helper(['url', 'string']);
        $this->load->library('upload');

        $subject         = $this->input->post('subject');
        $description     = $this->input->post('description');
        $attachment_name = '';

        if (!empty($_FILES['attachment']['name'])) {
            $config['upload_path']   = './uploads/support/';
            $config['allowed_types'] = '*';
            $config['file_name']     = time() . '_' . $_FILES['attachment']['name'];

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->upload->initialize($config);

            if ($this->upload->do_upload('attachment')) {
                $upload_data     = $this->upload->data();
                $attachment_name = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('warning', 'Attachment upload failed. Proceeding without attachment.');
            }
        }

        $data = array(
            'subject'     => $subject,
            'description' => $description,
            'attachment'  => $attachment_name,
            'added_by'    => $this->session->userdata('user_id'),
            'date'        => date('Y-m-d H:i:s')
        );
        $this->db->insert('client_support', $data);

        $this->session->set_flashdata('success', 'Support ticket submitted successfully.');
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    
    
public function update_review() {
    // Get raw POST data
    $input = json_decode(file_get_contents('php://input'), true);  // Parse the JSON

    // Ensure the data is valid
    if (!isset($input['id']) || !isset($input['review'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        return;
    }

    $ticket_id = $input['id'];
    $review = $input['review'];

    // Check if the review is valid
    if (!in_array($review, ['Satisfied', 'Not Satisfied'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid review status']);
        return;
    }

    // Check the current review status from the database
    $current_review = $this->db->select('review')
                               ->where('id', $ticket_id)
                               ->get('client_support')
                               ->row()
                               ->review;

    // Ensure that the review can only be updated if it is currently "Pending"
    if ($current_review !== 'Pending') {
        echo json_encode(['success' => false, 'message' => 'Review can only be updated if the current status is Pending']);
        return;
    }

    // Update the review in the database
    $this->db->where('id', $ticket_id);
    $updated = $this->db->update('client_support', ['review' => $review]);

    if ($updated) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update review']);
    }
}

public function update_comment()
{
    $ticket_id = $this->input->post('ticket_id');
    $comment = $this->input->post('comment');

    // Update the comment in the database
    $this->db->where('id', $ticket_id);
    $this->db->update('client_support', ['comment' => $comment]);

    // Redirect or respond with a success message
    redirect('support_client');
}



}



