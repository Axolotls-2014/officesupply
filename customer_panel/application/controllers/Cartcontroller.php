<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Cartcontroller extends CI_Controller {
    
       public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function add_to_cart()
    {
        $cart_data = $this->session->userdata('cart_data') ?? array();
        
    $prod_id = $this->input->post('product_id');
    $price = $this->input->post('price');
    $quantity = $this->input->post('quantity');

        if (!isset($cart_data[$prod_id])) {
            $cart_data[$prod_id] = [
                'qty'   => $quantity,
                'price' => $price,  
            ];
        }
    
        $cart_data[$prod_id]['qty'];
        $this->session->set_userdata('cart_data', $cart_data);
        redirect('demo');
    }

    public function cart_total_items() {
        $cart = $this->session->userdata('cart');
        $itemCount = 0;
    
        if ($cart) {
            foreach ($cart as $item) {
                $itemCount += $item['quantity']; 
            }
        }
    
        echo json_encode(['itemCount' => $itemCount]);
    }
    
    public function view_cart() {
        $cart = $this->session->userdata('cart');
        $data['cart'] = $cart;
        $this->load->view('demos/add', $data); 
    }
    
    public function cart_total() {
        $cart = $this->session->userdata('cart');
        $total = 0;
    
        if ($cart) {
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity']; 
            }
        }
    
        echo json_encode(['total' => $total]);
    }

    public function add_qty($prod_id = 0) {
        $cart_data = $this->session->userdata('cart_data') ?? array();
        
        if (!isset($cart_data[$prod_id]['qty'])) {
            $cart_data[$prod_id]['qty'] = 0;
        }
        
        $cart_data[$prod_id]['qty']++; 
        $this->session->set_userdata('cart_data', $cart_data);
        
        redirect('demo');
    }
    
    public function remove_qty($prod_id = 0) {
        $cart_data = $this->session->userdata('cart_data') ?? array();
        
        if (isset($cart_data[$prod_id]['qty'])) {
            $cart_data[$prod_id]['qty']--; 
            
            if ($cart_data[$prod_id]['qty'] <= 0) {
                unset($cart_data[$prod_id]);
            }
            
            $this->session->set_userdata('cart_data', $cart_data);
        }
        
        redirect('demo');
    }

       public function clear_cart() {
        $this->session->unset_userdata('cart_data');
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
   }

   public function request_order() {
    $items = $this->input->post('items');

    if (empty($items)) {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
        return;
    }

    $order_id = $this->db->select_max('order_id')->get('order_requests')->row()->order_id + 1;

    $order_data = [];
    $total_order = 0;

    foreach ($items as $item) {
        $product_id = $item['product_id'];
        $qty = $item['qty'];
        $price = $item['price'];
        $total = $qty * $price;

        $order_data[] = [
            'order_id'   => $order_id,
            'product_id' => $product_id,
            'quantity'   => $qty,
            'price'      => $price,
            'status'     => 'pending',
            'total'      => $total,
            'subtotal'   => 0, // temp, will update later
            'user_id'    => $this->session->userdata('admin_id')
        ];

        $total_order += $total;
    }

    foreach ($order_data as &$order) {
        $order['subtotal'] = $total_order;
    }

    $this->db->insert_batch('order_requests', $order_data);

    echo json_encode([
        'status' => 'success',
        'message' => 'Order request submitted',
        'order_id' => $order_id
    ]);
}

}
