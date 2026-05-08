<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_settings_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function edit_record($data)
    {
      if($this->db->update('application_settings',$data))
      {
          return TRUE;
      }
      else
      {
          return FALSE;
      }
    }

    function get_application_records()
    {
      return $this->db->get_where('application_settings')->row();
    }

    function clean_dummy_data()
    {
      $table_array = [
                        'bank_account',
                        'customer',
                        'discount',
                        'expense',
                        'expense_category',
                        'log_data',
                        'product',
                        'product_category',
                        'purchase',
                        'purchase_delivery',
                        'purchase_delivery_items',
                        'purchase_items',
                        'purchase_return',
                        'purchase_return_delivery',
                        'purchase_return_delivery_items',
                        'purchase_return_items',
                        'quotation',
                        'quotation_items',
                        'sale',
                        'sales_return',
                        'sales_return_delivery',
                        'sales_return_delivery_items',
                        'sales_return_items',
                        'sale_delivery',
                        'sale_delivery_items',
                        'sale_items',
                        'supplier',
                        'tax',
                        'transaction_detail',
                        'transaction_header',
                        'warehouse',
                        'warehouse_products'
                    ];

      $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
      
      for ($i=0; $i < sizeof($table_array); $i++) 
      {
        $sql = "TRUNCATE TABLE `".$table_array[$i]."`";
        $this->db->query($sql);
      }

      $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

      // Remove & update all ledger and 
      $this->db->where('id >', 7);
      $this->db->delete('ledger');

      $this->db->update('ledger',array('opening_balance'=>0, 'closing_balance'=>0));
      // $this->db->update('application_settings',array('dummy_data_cleared'=>'yes'));
      return true;
    }

    function restore_database_for_teseting()
    {

      $query = file_get_contents('assets/sample_files/zivaan_pro_testing.sql');

      $sqls = explode(';', $query);
      array_pop($sqls);

      $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

      foreach($sqls as $statement){
          $statment = $statement . ";";
          $this->db->query($statement);   
      }

      $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
      // $this->db->query($query);
      // $this->db->close();

      return true;
    }
    
}
?>
