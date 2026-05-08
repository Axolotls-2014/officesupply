<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Truncate extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
 public function truncate_tables()
{
    $tables = [
       'credit_debit_note', 'credit_debit_note_items','customer',
        'expense', 'expense_view', 'proforma_invoice',
        'proforma_invoice_items', 'purchase', 'purchase_delivery',
        'purchase_delivery_items', 'purchase_items', 'purchase_order', 'purchase_order_items',
         'purchase_return', 'purchase_return_delivery',
        'purchase_return_delivery_items', 'purchase_return_items', 
         'quotation', 'quotation_items', 'sale', 'sales_return',
        'sales_return_delivery', 'sales_return_delivery_items', 'sales_return_items',
       'sale_delivery', 'sale_delivery_items', 'sale_items',
        'scrap_entry', 'scrap_entry_items', 'scrap_issue', 'scrap_issue_items',
        'scrap_products', 'scrap_receive',
        'scrap_receive_items', 'transaction_detail', 'transaction_header',
        'transfer', 'transfer_items'
    ];

    $this->db->trans_start();

    foreach ($tables as $table) {
        $this->db->truncate($table);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        echo "Error truncating tables.";
    } else {
        echo "All tables truncated successfully.";
    }
}

  public function insert_stock_and_warehouse_products() {
        $warehouse_id   = 1;
        $warehouse_name = 'oss warehouse';

        $this->db->select('p.id as product_id, p.name, u.name');
        $this->db->from('product p');
        $this->db->join('uom u', 'u.id = p.uom_id', 'left');
        $query = $this->db->get();
        $products = $query->result();

        foreach ($products as $row) {
            $quantity        = rand(900, 1100); 
            $product_cost    = rand(90, 120);   
            $selling_price   = 0;
            $batch_no        = 1234;
            $entry_type      = 'insert';
            $delete_status   = 0;
            $product_price   = $product_cost;

            $stock_data = [
                'warehouse_name' => $warehouse_name,
                'product_name'   => $row->name,
                'product_cost'   => $product_cost,
                'product_price'  => $product_price,
                'product_uom'    => $row->name,
                'quantity'       => $quantity,
                'delete_status'  => $delete_status,
                'selling_price'  => $selling_price,
                'batch_no'       => $batch_no,
                'product_id'     => $row->product_id,
                'warehouse_id'   => $warehouse_id
            ];
            $this->db->insert('stock', $stock_data);

            $wh_data = [
                'warehouse_id'    => $warehouse_id,
                'product_id'      => $row->product_id,
                'batch_no'        => $batch_no,
                'cost'            => $product_cost,
                'selling_price'   => $selling_price,
                'price'           => $product_cost,
                'quantity'        => $quantity
            ];
            $this->db->insert('warehouse_products', $wh_data);
        }

        echo "Stock and warehouse_products inserted successfully!";
    }

}
