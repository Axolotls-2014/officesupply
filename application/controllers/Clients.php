<?php defined('BASEPATH') OR exit('No direct script access allowed');

class clients extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

    public function index()
  {
    if (!$this->permission_model->has_permission('clients')) {
      $this->load->view('errors/html/error_restricted'); 
    } else {
          $data['countries'] = $this->db->get('countries')->result(); 
      $data['employees'] = $this->db->get('clients_company')->result(); 
      $this->load->view('clients/companies', $data);
    }
  }
  
public function all_users_list()
{
    if (!$this->permission_model->has_permission('clients')) {
        $this->load->view('errors/html/error_restricted');
    } else {
        $allowed_roles = ['client', 'clients_branch_manager', 'approval_2', 'approval_3', 'approval_4'];

        $data['employees'] = $this->db
            ->select('users.*, clients_company.company_name, clients_branch.branch_name')
            ->from('users')
            ->join('clients_company', 'clients_company.id = users.company_id', 'left')
            ->join('clients_branch', 'clients_branch.id = users.clients_branch_id', 'left')
            ->where_in('users.role', $allowed_roles)
            ->get()
            ->result();

        $this->load->view('clients/all_users_list', $data);
    }
}

  public function pricing($company_id)
{
    $data['title'] = 'Edit Company Pricing';
    $data['company'] = $this->db->get_where('clients_company', ['id' => $company_id])->row();

    $data['pricing'] = json_decode($data['company']->pricing ?? '{}', true);
    $data['products'] = $this->db->get('product')->result();
// var_dump($data['pricing'] );die();
    $this->load->view('clients/pricing', $data);
}

// public function save_pricing()
// {
//     $company_id = $this->input->post('company_id');
//     $rawPricing = $this->input->post('pricing');
//     $finalPricing = [];

//     if (is_array($rawPricing)) {
//         foreach ($rawPricing as $productId => $item) {
//             if (isset($item['selected']) && $item['selected'] == '1') {
//                 $finalPricing[$productId] = [
//                     'product_id' => $item['product_id'],
//                     'price'      => $item['price'],
//                       'uom'        => isset($item['uom']) ? $item['uom'] : '' 
//                 ];
//             }
//         }
//     }

//     $this->db->where('id', $company_id);
//     $this->db->update('clients_company', ['pricing' => json_encode($finalPricing)]);

//     $this->session->set_flashdata('success', 'Pricing updated successfully.');
//     redirect('clients');
// }

//old code
  
  
  public function get_company($id)
{
    $this->output->set_content_type('application/json');
    $company = $this->db->get_where('clients_company', ['id' => $id])->row();
    $this->output->set_output(json_encode($company));
}
public function save_pricing()
{
    $company_id = $this->input->post('company_id');
    $pricing_input = $this->input->post('pricing');
    
    
 
    if (!$company_id || !$pricing_input) {
        $this->session->set_flashdata('error', 'Invalid Request');
        redirect('clients');
    }

    $final_pricing = [];

    foreach ($pricing_input as $product_id => $item) {

        // ONLY SAVE IF SELECTED
        if (isset($item['selected']) && $item['selected'] == 1) {

            $price = isset($item['price']) ? $item['price'] : null;
            $uom   = isset($item['uom'])   ? $item['uom']   : '';

            // ALLOW PRICE WITHOUT UOM
            if ($price !== null && $price !== "") {
                $final_pricing[$product_id] = [
                    "product_id" => $product_id,
                    "price"      => $price,
                    "uom"        => $uom   // empty UOM is allowed
                ];
            }
        }
    }

    // SAVE TO DATABASE (JSON ENCODE)
    $this->db->where('id', $company_id)
             ->update('clients_company', [
                 'pricing' => json_encode($final_pricing)
             ]);

    $this->session->set_flashdata('success', 'Pricing updated successfully.');
    redirect('clients/pricing/' . $company_id);
}

 public function save_pricing_ajax()
{
    // Set JSON response header
    $this->output->set_content_type('application/json');
    
    $company_id = $this->input->post('company_id');
    $pricing_data_json = $this->input->post('pricing_data');
    
    if (!$company_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid company ID']);
        return;
    }
    
    if (!$pricing_data_json) {
        echo json_encode(['success' => false, 'message' => 'No pricing data received']);
        return;
    }
    
    // Decode the JSON data
    $pricing_data = json_decode($pricing_data_json, true);
    
    if (!is_array($pricing_data)) {
        echo json_encode(['success' => false, 'message' => 'Invalid pricing data format']);
        return;
    }
    
    // Ensure the pricing column can handle large data
    $this->db->query("ALTER TABLE `clients_company` MODIFY `pricing` LONGTEXT");
    
    // Update the database
    $this->db->where('id', $company_id);
    $result = $this->db->update('clients_company', ['pricing' => json_encode($pricing_data)]);
    
    if ($result) {
        // Verify the update
        $verify = $this->db->get_where('clients_company', ['id' => $company_id])->row();
        $decoded_verify = json_decode($verify->pricing, true);
        
        if (count($pricing_data) == count($decoded_verify)) {
            echo json_encode(['success' => true, 'message' => 'Pricing updated successfully. (' . count($pricing_data) . ' products saved)']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data mismatch! Expected ' . count($pricing_data) . ', saved ' . count($decoded_verify)]);
        }
    } else {
        $error = $this->db->error();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $error['message']]);
    }
}

 public function toggle_status()
{
  if ($this->input->method() !== 'post') {
    show_error('Invalid request method', 405);
    return;
  }

  $id = $this->input->post('id');
  $status = $this->input->post('status');

  if (!is_numeric($id) || !in_array($status, ['0', '1'])) {
    $this->session->set_flashdata('error', 'Invalid status or ID.');
    redirect('clients'); 
    return;
  }
  $updated = $this->db->where('id', $id)->update('users', ['active' => $status]);

  if ($updated) {
    $this->session->set_flashdata('success', 'Manager status updated successfully.');
  } else {
    $this->session->set_flashdata('error', 'Failed to update manager status.');
  }

   redirect('clients/clients_list');
}

public function insert()
{
    $gstin = $this->input->post('gstin', true);
    $exists = $this->db->get_where('clients_company', ['gstin' => $gstin])->num_rows() > 0;

    if ($exists && !empty($gstin)) {
        $this->session->set_flashdata('error', 'GSTIN already exists. Please enter a unique GSTIN.');
        redirect('clients');
        return;
    }

    // Fetch names for billing location
    $country = $this->db->get_where('countries', ['id' => $this->input->post('country_id')])->row();
    $state   = $this->db->get_where('states', ['id' => $this->input->post('state_id')])->row();
    $city    = $this->db->get_where('cities', ['id' => $this->input->post('city_id')])->row();

    // Fetch names for shipping location
    $ship_country = $this->db->get_where('countries', ['id' => $this->input->post('shipping_country_id')])->row();
    $ship_state   = $this->db->get_where('states', ['id' => $this->input->post('shipping_state_id')])->row();
    $ship_city    = $this->db->get_where('cities', ['id' => $this->input->post('shipping_city_id')])->row();

    $data = [
        'company_name'         => $this->input->post('company_name', true),
        'gstin'                => $gstin,

        // Billing
        'country_id'           => $this->input->post('country_id'),
        'country_name'         => $country ? $country->name : null,
        'state_id'             => $this->input->post('state_id'),
        'state'                => $state ? $state->name : null,
        'city_id'              => $this->input->post('city_id'),
        'city_name'            => $city ? $city->name : null,
        'address'              => $this->input->post('address', true),
        'pincode'              => $this->input->post('pincode', true),
        'billing_details'      => $this->input->post('address', true),

        // Shipping
        'shipping_country_id'   => $this->input->post('shipping_country_id'),
        'shipping_country_name' => $ship_country ? $ship_country->name : null,
        'shipping_state_id'     => $this->input->post('shipping_state_id'),
        'shipping_state_name'   => $ship_state ? $ship_state->name : null,
        'shipping_city_id'      => $this->input->post('shipping_city_id'),
        'shipping_city_name'    => $ship_city ? $ship_city->name : null,
        'shipping_address'      => $this->input->post('shipping_address', true),
        'shipping_pincode'      => $this->input->post('shipping_pincode', true),
        'shipping_details'      => $this->input->post('shipping_address', true),
    ];

    if ($this->db->insert('clients_company', $data)) {
        $this->session->set_flashdata('success', 'Company added successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to add company.');
    }
    redirect('clients');
}


public function get_states()
{
    $country_id = $this->input->post('country_id');
    $states = $this->db->get_where('states', ['country_id' => $country_id])->result();
    echo '<option value="">-- Select State --</option>';
    foreach ($states as $s) {
        echo '<option value="'.$s->id.'">'.$s->name.'</option>';
    }
}

public function get_cities()
{
    $state_id = $this->input->post('state_id');
    $cities = $this->db->get_where('cities', ['state_id' => $state_id])->result();
    echo '<option value="">-- Select City --</option>';
    foreach ($cities as $c) {
        echo '<option value="'.$c->id.'">'.$c->name.'</option>';
    }
}

public function update()
{
  $id = $this->input->post('id');
  $data = [
    'company_name' => $this->input->post('company_name', true),
    'gstin' => $this->input->post('gstin', true),
    'billing_details' => $this->input->post('billing_details', true),
    'shipping_details' => $this->input->post('shipping_details', true)
  ];

  if ($this->db->where('id', $id)->update('clients_company', $data)) {
    $this->session->set_flashdata('success', 'Company updated successfully.');
  } else {
    $this->session->set_flashdata('error', 'Failed to update company.');
  }

  redirect('clients');
}

public function clients_list()
{
    if (!$this->permission_model->has_permission('clients')) {
        $this->load->view('errors/html/error_restricted');
    } else {
        $data['employees'] = $this->db
            ->select('users.*, clients_company.company_name')
            ->from('users')
            ->join('clients_company', 'clients_company.id = users.company_id', 'left')
            ->where('users.role', 'client')
            ->get()
            ->result();

        $this->load->view('clients/clients', $data);
    }
}

// public function insert_user()
// {
//     $this->data['companies'] = $this->db->get('clients_company')->result();
//     if ($this->input->post('add_user')) {
//         $tables = $this->config->item('tables', 'ion_auth');
//         $identity_column = $this->config->item('identity', 'ion_auth');

//         $email    = strtolower($this->input->post('email'));
//         $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
//         $password = $this->input->post('password');
//         $additional_data = [
//             'first_name' => $this->input->post('first_name'),
//             'last_name'  => $this->input->post('last_name'),
//             'company_id' => $this->input->post('company_id'),
//             'phone'      => $this->input->post('phone'),
//             'designation'  => $this->input->post('designation'),
//             'department'   => $this->input->post('department'),
//             'role'       =>'client',
//             'level_id'   =>'0'
//         ]; 
//         $group = [25]; 
//         if ($this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {
//             $this->session->set_flashdata('success', 'User added successfully.');
//             redirect('clients/clients_list');
//         } else {
//             $this->session->set_flashdata('error', $this->ion_auth->errors());
//             redirect('clients/clients_list');
//         }
//     }
//       $this->_render_page('clients' . DIRECTORY_SEPARATOR . 'clients', $this->data);
// }

    public function insert_user()
    {
        $this->data['companies'] = $this->db->get('clients_company')->result();
    
        if ($this->input->post('add_user')) {
            $tables = $this->config->item('tables', 'ion_auth');
            $identity_column = $this->config->item('identity', 'ion_auth');
    
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');
    
            $additional_data = [
                'first_name'   => $this->input->post('first_name'),
                'last_name'    => $this->input->post('last_name'),
                'company_id'   => $this->input->post('company_id'),
                'phone'        => $this->input->post('phone'),
                'designation'  => $this->input->post('designation'),
                'dept_id'      => $this->input->post('department'),
                'role'         => 'client',
                'level_id'     => '0'
            ];
    
            $group = [25];
    
            // Register user in Ion Auth
            $user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group);
    
          if ($user_id) {
            $ledger_data = [
                'title'            => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                'account_group_id' => 4,
                'opening_balance'  => 0,
                'closing_balance'  => 0
            ];
            $this->db->insert('ledger', $ledger_data);
            $ledger_id = $this->db->insert_id();
    
            $company_id = $this->input->post('company_id');
            $company = $this->db->get_where('clients_company', ['id' => $company_id])->row();
    
            $customer_data = [
                'customer_name'              => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                'gstin'                      => $company->gstin, 
                'email'                      => $email,
                'password'                   => md5($password), 
                'phone'                      => $this->input->post('phone'),
                'country_id'                 => $company->country_id,
                'country_name'               => $company->country_name,
                'state_id'                   => $company->state_id,
                'state_name'                 => $company->state_name,
                'city_id'                    => $company->city_id,
                'city_name'                  => $company->city_name,
                'address'                    => $company->billing_details,
                'pincode'                    => $company->pincode,
                'shipping_country_id'        => $company->shipping_country_id,
                'shipping_country_name'      => $company->shipping_country_name,
                'shipping_state_id'          => $company->shipping_state_id,
                'shipping_state_name'        => $company->shipping_state_name,
                'shipping_city_id'           => $company->shipping_city_id,
                'shipping_city_name'         => $company->shipping_city_name,
                'shipping_address'           => $company->shipping_address,
                'shipping_pincode'           => $company->shipping_pincode,
                'ledger_id'                  => $ledger_id,
                'user_id'                    => $user_id,
                'delete_status'              => 0,
                'customer_company_name'      => $company->company_name,
                'customer_buyer_name'        => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                'customer_buyer_designation' => $this->input->post('designation'),
                'customer_department'        => $this->input->post('department'),
                'added_by'                   => $this->session->userdata('user_id'),
                'is_client'                  => 1,
                'whatsapp_country_code'      => 91,
                'payment_duration'           => 1,
                'company_id'                 => $company->id,
            ];
            $this->db->insert('customer', $customer_data);
        $this->session->set_flashdata('success', 'User added successfully.');
        redirect('clients/clients_list');
    }
    else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('clients/clients_list');
            }
        }
    
        $this->_render_page('clients' . DIRECTORY_SEPARATOR . 'clients', $this->data);
    }

public function update_user()
{
    $id = $this->input->post('id');

    $data = [
        'first_name' => $this->input->post('first_name', true),
        'last_name'  => $this->input->post('last_name', true),
        'email'      => $this->input->post('email', true),
        'username'   => $this->input->post('username', true),
        'phone'      => $this->input->post('phone', true),
        'company_id' => $this->input->post('company_id', true)
    ];

    $password = $this->input->post('password', true);
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    $this->db->where('id', $id)->update('users', $data);

    $this->session->set_flashdata('success', 'Client updated successfully.');
    redirect('clients/clients_list');
}

public function update_all_user()
{
    $id = $this->input->post('id', true);

    // Basic validation
    if (!$id) {
        $this->session->set_flashdata('error', 'Invalid user ID.');
        redirect('clients/all_users_list');
    }

    // Prepare user data
    $data = [
        'first_name' => $this->input->post('first_name', true),
        'last_name'  => $this->input->post('last_name', true),
        'email'      => $this->input->post('email', true),
        'username'   => $this->input->post('username', true),
        'phone'      => $this->input->post('phone', true),
        'company_id' => $this->input->post('company_id', true),
         'monthly_limit' => $this->input->post('monthly_limit') ?? 0
    ];

    $password = $this->input->post('password', true);
    if (!empty($password)) {
        // Ion Auth automatically hashes the password
        $data['password'] = $password;
    }

    // Use Ion Auth update method
    if ($this->ion_auth->update($id, $data)) {
        $this->session->set_flashdata('success', 'User updated successfully.');
    } else {
        $this->session->set_flashdata('error', $this->ion_auth->errors());
    }
  $this->db->where('id', $id)->update('users', [
    'company_id'    => $this->input->post('company_id', true),
    'monthly_limit' => $this->input->post('monthly_limit') ?? 0
]);

    redirect('clients/all_users_list');
}


}
