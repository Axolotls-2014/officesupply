<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;

class Demo extends CI_Controller
{ 

    public function __construct()
    { 
        parent::__construct(); 
        // $this->load->model('company_settings_model');
        $this->load->helper('url');
        $this->load->library('upload');
    } 
     public function index()
    {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        }
    
        $admin_id = $this->session->admin_id; 
    
        $customer = $this->db->select('pricing')
                             ->where('id', $admin_id) 
                             ->get('customer')
                             ->row();
    
        $pricing_data = (!empty($customer->pricing)) ? json_decode($customer->pricing, true) : [];
    
        $products = [];
        if (!empty($pricing_data)) {
            $product_ids = array_keys($pricing_data);
            $this->db->where_in('id', $product_ids);
            $query = $this->db->get('product');
    
            foreach ($query->result() as $product) {
                $product->price = $pricing_data[$product->id]['price']; 
                $products[] = $product;
            }
        }
        $data['products'] = $products;
        $data['title']    = 'Shop';
        $data['layout']  = 'demos/add.php';
    
        $this->load->view('admin/index', $data);
    }
    
    public function invoice($order_id)
    {
        $data['company_setting'] = $this->db_model->select_all('*', 'company_setting')[0] ?? null;
        $data['orders'] = $this->db->where('order_id', $order_id)->get('order_requests')->result_array();
    
        if (!empty($data['orders'])) {
            $user_id = $data['orders'][0]['user_id'];
            $data['customer'] = $this->db->where('id', $user_id)->get('customer')->row_array();
        } else {
            $data['customer'] = null;
        }
    
        $data['layout'] = 'demos/invoice.php';
        $this->load->view('admin/index', $data);
    }
    
     public function download_pdf($order_id) 
    {
        $this->load->library('pdf'); 

        $data['orders'] = $this->db->get_where('order_requests', ['order_id' => $order_id])->result_array();
        $data['company_setting'] = $this->company_settings_model->get_company_records();

        $html = $this->load->view('purchase/invoice_pdf', $data, true);
        
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream("invoice_$order_id.pdf", ["Attachment" => true]);
    }
    
    public function history()
    {
        $user_id = $this->session->admin_id;

     // Fetch unique orders with additional details
        $data['orders'] = $this->db->select('*')
                                   ->where('user_id', $user_id)
                                   ->group_by('order_id')
                                   ->get('order_requests')
                                   ->result();
    
        // Fetch all products for modals
        $data['order_details'] = $this->db->select('order_requests.*, product.name as product_name, product.product_image, order_requests.quantity, order_requests.total')
                                          ->from('order_requests')
                                          ->join('product', 'order_requests.product_id = product.id', 'left')
                                          ->where('order_requests.user_id', $user_id)
                                          ->get()
                                          ->result();
    
        $data['layout'] = 'demos/history.php';
        $this->load->view('admin/index', $data);
    }
    public function category()
    {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
        $data['users']      = $this->db_model->select_all('*','categories');
        $data['title']      = 'Home';
        $data['layout']     = 'demos/add_categories.php';
        $this->load->view('admin/index', $data);
    }
    
    public function edit($id)
    {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
        $data['users']      = $this->db_model->select_all('*','users');
        $data['data']       = $this->db_model->select_multi('*','demos',array('id' => $id));
        $data['title']      = 'Home';
        $data['layout']     = 'demos/edit.php';
        $this->load->view('admin/index', $data);
    }

    public function info($id = 0)
    {
        $data['data']       = $this->db_model->select_multi('*','demos',array('id' => $id));
        $data['title']      = 'Home';
        $this->load->view('demo_info', $data);
    }

    public function insert() {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
        $this->form_validation->set_rules('demo_name', 'Demo Name', 'required');
        $this->form_validation->set_rules('web_link', 'Website Link', 'required|valid_url');
    
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $response = array(
                'status' => 'error',
                'message' => $errors
            );
            echo json_encode($response);
        } else {
            $data = array(
                'project'          => $this->input->post('demo_name'),
                'web_link'         => $this->input->post('web_link') ?? '',
                'apk_file'         => $this->input->post('apk_file') ?? '',
                'remark'           => $this->input->post('remark') ?? '',
                'admin_link'       => $this->input->post('admin_link') ?? '',
                'admin_username'   => $this->input->post('admin_username') ?? '',
                'admin_password'   => $this->input->post('admin_pass') ?? '',
                'user_link'        => $this->input->post('user_link') ?? '',
                'username'         => $this->input->post('user_username') ?? '',
                'user_password'    => $this->input->post('user_pass') ?? '',
                'type'             => $this->input->post('type'),
                'status'           => 1,
            );
            if (!empty($_FILES['attachment']['name'])) {
                $config['upload_path']   = './uploads/demo/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size']      = 10240; 
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('attachment')) {
                    $upload_data        = $this->upload->data();
                    $data['attachment'] = $upload_data['file_name'];
                } else {
                    $error    = $this->upload->display_errors();
                    $response = array(
                        'status'  => 'error',
                        'message' => $error
                    );
                    echo json_encode($response);
                    return;
                }
            }
    
            if ($this->db->insert('demos', $data)) {
                $response = array(
                    'status'  => 'success',
                    'message' => 'Data saved successfully!'
                );
            } else {
                $response = array(
                    'status'  => 'error',
                    'message' => 'Failed to save data.'
                );
            }
            echo json_encode($response);
        }
    }    

    public function update($id) {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
        $this->form_validation->set_rules('demo_name', 'Demo Name', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $response = array(
                'status' => 'error',
                'message' => $errors
            );
            echo json_encode($response);
        } else {
            $data = array(
                'project'          => $this->input->post('demo_name'),
                'web_link'         => $this->input->post('web_link') ?? '',
                'apk_file'         => $this->input->post('apk_file') ?? '',
                'remark'           => $this->input->post('remark') ?? '',
                'admin_link'       => $this->input->post('admin_link') ?? '',
                'admin_username'   => $this->input->post('admin_username') ?? '',
                'admin_password'   => $this->input->post('admin_pass') ?? '',
                'user_link'        => $this->input->post('user_link') ?? '',
                'username'         => $this->input->post('user_username') ?? '',
                'user_password'    => $this->input->post('user_pass') ?? '',
                'type'             => $this->input->post('type'),
                'status'           => 1,
            );
            
            if (!empty($_FILES['attachment']['name'])) {
                $config['upload_path']   = './uploads/demo/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size']      = 10240;
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('attachment')) {
                    $upload_data        = $this->upload->data();
                    $data['attachment'] = $upload_data['file_name'];
    
                    $old_attachment = $this->db->select('attachment')->get_where('demos', array('id' => $id))->row()->attachment;
                    if ($old_attachment && file_exists('./uploads/demo/' . $old_attachment)) {
                        unlink('./uploads/demo/' . $old_attachment);
                    }
                } else {
                    $error    = $this->upload->display_errors();
                    $response = array(
                        'status'  => 'error',
                        'message' => $error
                    );
                    echo json_encode($response);
                    return;
                }
            }
            
            $this->db->where('id', $id);
            if ($this->db->update('demos', $data)) {
                $response = array(
                    'status'  => 'success',
                    'message' => 'Data updated successfully!'
                );
            } else {
                $response = array(
                    'status'  => 'error',
                    'message' => 'Failed to update data.'
                );
            }
            echo json_encode($response);
        }
    }
    

    public function delete($id)
    {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
        $array = array(
            'status'  => 0,
        );
        $this->db->where('id', $id);
        $this->db->update('demos', $array);
        $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Demo deleted!</div>');
        redirect(site_url('demo/list'));
    }

    public function list()
    {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
        $data['data']       = $this->db_model->select_all('*','demos',array('status' => 1));
        $data['title']      = 'Home';
        $data['layout']     = 'demos/list.php';
        $this->load->view('admin/index', $data);
    }
    
    
     public function categorylist()
    {
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
        $data['data']       = $this->db_model->select_all('*','categories',array('status' => 1));
        $data['title']      = 'Home';
        $data['layout']     = 'demos/list_category.php';
        $this->load->view('admin/index', $data);
    }
    
   public function insert_Category() {
    // Load form helper and URL helper
    $this->load->helper(['form', 'url']);
    $this->load->model('db_model'); // Load your model here

    // Get the posted data
    $name = $this->input->post('name');

    // Prepare data for insertion
    $data = [
        'name' => $name
    ];

    // Insert data into the database
    if ($this->db_model->insert_category($data)) {
        // Success response
        echo json_encode(['status' => 'success', 'message' => 'Category added successfully.']);
    } else {
        // Error response
        echo json_encode(['status' => 'error', 'message' => 'Failed to add category.']);
    }
}
     public function category_list() {
        $this->load->model('db_model');
        $data['data'] = $this->db_model->get_all_domains();
        $this->load->view('demo/category_list', $data);
    }
    
  public function upload($order_id)
{
    $receipt_type = $this->input->post('receipt_type'); // Get type from form

    if (!in_array($receipt_type, ['invoice_receipt', 'courier_receipt', 'eway_bill_receipt'])) {
        $this->session->set_flashdata('error', 'Invalid receipt type.');
        redirect('demo/history/' . $order_id);
        return;
    }

    $config['upload_path']   = './uploads/';  
    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|docx|txt'; 
    $config['max_size']      = 2048; 
    $config['file_name']     = 'order_' . $order_id . '_' . $receipt_type . '_' . time(); 

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file')) {
        $error = $this->upload->display_errors();
        $this->session->set_flashdata('error', $error);
        redirect('demo/history/' . $order_id);
    } else {
        $file_data = $this->upload->data();
        $file_path = '/uploads/' . $file_data['file_name'];

        // Update the appropriate column
        $this->db->where('order_id', $order_id);
        $this->db->update(' order_requests', [
            $receipt_type => $file_path
        ]);

        $this->session->set_flashdata('success', ucfirst(str_replace('_', ' ', $receipt_type)) . ' uploaded successfully!');
        redirect('demo/history/' . $order_id);
    }
}
 

}