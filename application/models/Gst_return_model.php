<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gst_return_model extends CI_Model {

  function __construct() {
      parent::__construct();
      
        $this->db->query('SET SESSION sql_mode = ""');
        $this->db->query('SET SESSION sql_mode =
                            REPLACE(
                                REPLACE(
                                    REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY,", ""),
                                ",ONLY_FULL_GROUP_BY", ""),
                            "ONLY_FULL_GROUP_BY", "")'
                    );
  }
  
  public function cdnr_sales_return_csv()
  {
      $this->db->select('s.*,st.state_code,st.id as customer_state_id,c.gstin as customer_gstin');
      $this->db->from('sales_return s');
      $this->db->join('customer c','c.id = s.customer_id');
      $this->db->join('states st','st.id = c.state_id','left');
    /*  $this->db->where('c.gstin !=','');*/
      $this->db->where('s.delete_status',0);
     /* $this->db->like('s.sales_return_date',$year.'-'.sprintf("%02d", $month));*/
      $query = $this->db->get();
      
      return $query->result();    
  }


  //b2b csv
  function gstr2_csv($year,  $quarter = FALSE, $month = NULL)
  {
    if($quarter == FALSE) 
    {
        $this->db->select('
                          p.supplier_gstin as supplier_gstin,
                          s.company_name as supplier_name,
                          p.reference_no as purchase_order_no,
                          p.invoice_no as invoice_no,
                          p.purchase_date as purchase_date,
                          p.total_taxable_value as total_taxable_value,
                          p.total_discount as total_discount,
                          p.total_tax as total_tax,
                          p.total as total
                      ');
        $this->db->from('purchase p');
        $this->db->join('supplier s','s.id = p.supplier_id','left');
        $this->db->where('p.delete_status',0);
        $this->db->like('p.purchase_date',$year.'-'.sprintf("%02d", $month));
        $this->db->order_by('p.created_date','desc');
        
        $query = $this->db->get();
        return $query->result();                
    }
    else
    {
        $month1;
        $year1;

        $month2;
        $year2;

        $month3;
        $year3;

        if($quarter == 1)
        {
            $month1 = 4;
            $year1  = $year;

            $month2 = 5;
            $year2  = $year;

            $month3 = 6;
            $year3  = $year;
        }
        else if($quarter == 2)
        {
            $month1 = 7;
            $year1  = $year;

            $month2 = 8;
            $year2  = $year;

            $month3 = 9;
            $year3  = $year;   
        }
        else if($quarter == 3)
        {
            $month1 = 10;
            $year1  = $year;

            $month2 = 11;
            $year2  = $year;

            $month3 = 12;
            $year3  = $year;      
        }
        else
        {
            $month1 = 1;
            $year1  = $year;

            $month2 = 2;
            $year2  = $year;

            $month3 = 3;
            $year3  = $year;         
        }

        $this->db->select('
                          p.supplier_gstin as supplier_gstin,
                          s.company_name as supplier_name,
                          p.reference_no as purchase_order_no,
                          p.invoice_no as invoice_no,
                          p.purchase_date as purchase_date,
                          p.total_taxable_value as total_taxable_value,
                          p.total_discount as total_discount,
                          p.total_tax as total_tax,
                          p.total as total
                      ');
        $this->db->from('purchase p');
        $this->db->join('supplier s','s.id = p.supplier_id','left');
        $this->db->where('p.delete_status',0);
        $this->db->like('p.purchase_date',$year.'-'.sprintf("%02d", $month));
        $this->db->order_by('p.created_date','desc');

          $this->db->group_start();
            $this->db->like('p.purchase_date',$year1.'-'.sprintf("%02d", $month1));
            $this->db->or_like('p.purchase_date',$year2.'-'.sprintf("%02d", $month2));
            $this->db->or_like('p.purchase_date',$year3.'-'.sprintf("%02d", $month3));
          $this->db->group_end();

        $query  = $this->db->get();
        return $query->result();
    }

  }

  //b2b csv
  function b2b_sale_csv($year,  $quarter = FALSE, $month = NULL)
  {
    if($quarter == FALSE) 
    {
        $this->db->select('
                            c.gstin as customer_gstin,
                            c.customer_name as customer_name,
                            s.reference_no,
                            s.invoice_date,
                            SUM(si.sub_total),
                            st.name,
                            s.rcm,
                            "Regular" As invoice_type,
                            CASE 
                              WHEN si.igst = 0 THEN si.cgst 
                              ELSE si.igst
                            END AS rate,
                            SUM(si.taxable_value),
                            "0" As cess_amount
                          ',FALSE);
        $this->db->from('sale s');
        $this->db->join('sale_items si','si.sale_id = s.id');
        $this->db->join('customer c','c.id = s.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('s.customer_gstin !=','');
        $this->db->where('s.delete_status',0);
        $this->db->like('s.invoice_date',$year.'-'.sprintf("%02d", $month));
        $this->db->group_by(array("si.igst","si.cgst","si.sgst"));
        $query = $this->db->get();
        
        return $query->result();                
    }
    else
    {
        $month1;
        $year1;

        $month2;
        $year2;

        $month3;
        $year3;

        if($quarter == 1)
        {
            $month1 = 4;
            $year1  = $year;

            $month2 = 5;
            $year2  = $year;

            $month3 = 6;
            $year3  = $year;
        }
        else if($quarter == 2)
        {
            $month1 = 7;
            $year1  = $year;

            $month2 = 8;
            $year2  = $year;

            $month3 = 9;
            $year3  = $year;   
        }
        else if($quarter == 3)
        {
            $month1 = 10;
            $year1  = $year;

            $month2 = 11;
            $year2  = $year;

            $month3 = 12;
            $year3  = $year;      
        }
        else
        {
            $month1 = 1;
            $year1  = $year;

            $month2 = 2;
            $year2  = $year;

            $month3 = 3;
            $year3  = $year;         
        }



        $this->db->select('
                            c.gstin as customer_gstin,
                            c.customer_name as customer_name,
                            s.reference_no,
                            s.invoice_date,
                            SUM(si.sub_total),
                            st.name,
                            s.rcm,
                            "Regular" As invoice_type,
                            CASE 
                              WHEN si.igst = 0 THEN si.cgst 
                              ELSE si.igst
                            END AS rate,
                            SUM(si.taxable_value),
                            "0" As cess_amount
                          ',FALSE);
        $this->db->from('sale s');
        $this->db->join('sale_items si','si.sale_id = s.id');
        $this->db->join('customer c','c.id = s.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('s.customer_gstin !=','');
        $this->db->where('s.delete_status',0);

          $this->db->group_start();
            $this->db->like('s.invoice_date',$year1.'-'.sprintf("%02d", $month1));
            $this->db->or_like('s.invoice_date',$year2.'-'.sprintf("%02d", $month2));
            $this->db->or_like('s.invoice_date',$year3.'-'.sprintf("%02d", $month3));
          $this->db->group_end();

        $this->db->group_by(array("si.igst","si.cgst","si.sgst"));

        $query  = $this->db->get();

        return $query->result();
    }

  }
  //b2cl csv
  function b2cl_sale_csv($year,  $quarter = FALSE, $month = NULL)
  {  
    if($quarter == FALSE) 
    {
        $this->db->select('
                            s.reference_no,
                            s.invoice_date,
                            SUM(si.sub_total),
                            st.name,
                            CASE 
                              WHEN si.igst = 0 THEN si.cgst 
                              ELSE si.igst
                            END AS rate,
                            SUM(si.taxable_value),
                            "0" As cess_amount,
                            " " As ecommerce_gstin
                          ',FALSE);
        $this->db->from('sale s');
        $this->db->join('sale_items si','si.sale_id = s.id');
        $this->db->join('customer c','c.id = s.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('s.customer_gstin','');
          
            $this->db->where('s.total >', 250000);
            $this->db->where('c.state_id !=', $this->session->userdata('company_state_id'));
          
        $this->db->where('s.delete_status',0);
        $this->db->like('s.invoice_date',$year.'-'.sprintf("%02d", $month));
        $this->db->group_by(array("si.igst","si.cgst","si.sgst"));
        $query = $this->db->get();
        
        return $query->result();                
    }
    else
    {
        $month1;
        $year1;

        $month2;
        $year2;

        $month3;
        $year3;

        if($quarter == 1)
        {
            $month1 = 4;
            $year1  = $year;

            $month2 = 5;
            $year2  = $year;

            $month3 = 6;
            $year3  = $year;
        }
        else if($quarter == 2)
        {
            $month1 = 7;
            $year1  = $year;

            $month2 = 8;
            $year2  = $year;

            $month3 = 9;
            $year3  = $year;   
        }
        else if($quarter == 3)
        {
            $month1 = 10;
            $year1  = $year;

            $month2 = 11;
            $year2  = $year;

            $month3 = 12;
            $year3  = $year;      
        }
        else
        {
            $month1 = 1;
            $year1  = $year;

            $month2 = 2;
            $year2  = $year;

            $month3 = 3;
            $year3  = $year;         
        }



        $this->db->select(' 
                            s.reference_no,
                            s.invoice_date,
                            SUM(si.sub_total),
                            st.name,
                            CASE 
                              WHEN si.igst = 0 THEN si.cgst 
                              ELSE si.igst
                            END AS rate,
                            SUM(si.taxable_value),
                            "0" As cess_amount,
                            " " As ecommerce_gstin
                          ',FALSE);
        $this->db->from('sale s');
        $this->db->join('customer c','c.id = s.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('c.gstin','');

          $this->group_start();
            $this->db->where('s.total >', 250000);
            $this->db->where('c.state_id !=', $this->session->userdata('company_state_id'));
          $this->group_end();

          $this->db->group_start();
            $this->db->like('s.invoice_date',$year1.'-'.sprintf("%02d", $month1));
            $this->db->or_like('s.invoice_date',$year2.'-'.sprintf("%02d", $month2));
            $this->db->or_like('s.invoice_date',$year3.'-'.sprintf("%02d", $month3));
          $this->db->group_end();

        $this->db->where('s.delete_status',0);
        $this->db->group_by(array("si.igst","si.cgst","si.sgst"));

        $query  = $this->db->get();

        return $query->result();
    }
  }
  
  //b2cs csv
  function b2cs_sale_csv($year,  $quarter = FALSE, $month = NULL)
  { 
    if($quarter == FALSE) 
    {
        $this->db->select('
                            "OE" As type,
                            st.name,
                            CASE 
                              WHEN si.igst = 0 THEN si.cgst 
                              ELSE si.igst
                            END AS rate,
                            SUM(si.taxable_value),
                            "0" As cess_amount,
                            " " As ecommerce_gstin
                          ',FALSE);
        $this->db->from('sale s');
        $this->db->join('sale_items si','si.sale_id = s.id');
        $this->db->join('customer c','c.id = s.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('s.customer_gstin','');
        $this->db->where('s.total <', 250000);
        $this->db->where('s.delete_status',0);
        $this->db->like('s.invoice_date',$year.'-'.sprintf("%02d", $month));
        $query = $this->db->get();
        
        return $query->result();                
    }
    else
    {
        $month1;
        $year1;

        $month2;
        $year2;

        $month3;
        $year3;

        if($quarter == 1)
        {
            $month1 = 4;
            $year1  = $year;

            $month2 = 5;
            $year2  = $year;

            $month3 = 6;
            $year3  = $year;
        }
        else if($quarter == 2)
        {
            $month1 = 7;
            $year1  = $year;

            $month2 = 8;
            $year2  = $year;

            $month3 = 9;
            $year3  = $year;   
        }
        else if($quarter == 3)
        {
            $month1 = 10;
            $year1  = $year;

            $month2 = 11;
            $year2  = $year;

            $month3 = 12;
            $year3  = $year;      
        }
        else
        {
            $month1 = 1;
            $year1  = $year;

            $month2 = 2;
            $year2  = $year;

            $month3 = 3;
            $year3  = $year;         
        }



        $this->db->select('s.*,st.state_code,st.id as customer_state_id,c.gstin as customer_gstin');
        $this->db->from('sale s');
        $this->db->join('customer c','c.id = s.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('c.gstin','');
         $this->db->where('s.total <', 250000);
        $this->db->where('s.delete_status',0);

          $this->db->group_start();
            $this->db->like('s.invoice_date',$year1.'-'.sprintf("%02d", $month1));
            $this->db->or_like('s.invoice_date',$year2.'-'.sprintf("%02d", $month2));
            $this->db->or_like('s.invoice_date',$year3.'-'.sprintf("%02d", $month3));
          $this->db->group_end();

        $query  = $this->db->get();

        return $query->result();
    }
  }

  //cndr csv
  function cdnr_csv($year,  $quarter = FALSE, $month = NULL)
  { 
    if($quarter == FALSE) 
    {
        $this->db->select('
                            sr.customer_gstin,
                            c.customer_name,
                            sr.reference_no,
                            sr.sales_return_date,
                            "C" As note_type,
                            st.name,
                            "Y" As rcm,
                            "Regular" As note_supply_type,
                            sr.total,
                            CASE 
                              WHEN sri.igst = 0 THEN (sri.cgst+sri.sgst) 
                              ELSE sri.igst
                            END AS rate,
                            SUM(sri.taxable_value),
                            "0" As cess_amount
                          ',FALSE);
        $this->db->from('sales_return sr');
        $this->db->join('sales_return_items sri','sri.sales_return_id = sr.id');
        $this->db->join('customer c','c.id = sr.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('sr.customer_gstin !=','');
        $this->db->where('sr.delete_status',0);
        $this->db->like('sr.sales_return_date',$year.'-'.sprintf("%02d", $month));
        $query = $this->db->get();
        
        return $query->result();                
    }
    else
    {
        $month1;
        $year1;

        $month2;
        $year2;

        $month3;
        $year3;

        if($quarter == 1)
        {
            $month1 = 4;
            $year1  = $year;

            $month2 = 5;
            $year2  = $year;

            $month3 = 6;
            $year3  = $year;
        }
        else if($quarter == 2)
        {
            $month1 = 7;
            $year1  = $year;

            $month2 = 8;
            $year2  = $year;

            $month3 = 9;
            $year3  = $year;   
        }
        else if($quarter == 3)
        {
            $month1 = 10;
            $year1  = $year;

            $month2 = 11;
            $year2  = $year;

            $month3 = 12;
            $year3  = $year;      
        }
        else
        {
            $month1 = 1;
            $year1  = $year;

            $month2 = 2;
            $year2  = $year;

            $month3 = 3;
            $year3  = $year;         
        }



        $this->db->select(' sr.customer_gstin,
                            c.customer_name,
                            sr.reference_no,
                            sr.sales_return_date,
                            "C" As note_type,
                            st.name,
                            "Y" As rcm,
                            "Regular" As note_supply_type,
                            sr.total
                            CASE 
                              WHEN sri.igst = 0 THEN (sri.cgst+sri.sgst) 
                              ELSE sri.igst
                            END AS rate,
                            SUM(sri.taxable_value),
                            "0" As cess_amount',FALSE);
        $this->db->from('sales_return sr');
        $this->db->join('sales_return_items sri','sri.sales_return_id = sr.id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('sr.customer_gstin','');
         $this->db->where('sr.total <', 250000);
        $this->db->where('sr.delete_status',0);

          $this->db->group_start();
            $this->db->like('sr.sales_return_date',$year1.'-'.sprintf("%02d", $month1));
            $this->db->or_like('sr.sales_return_date',$year2.'-'.sprintf("%02d", $month2));
            $this->db->or_like('sr.sales_return_date',$year3.'-'.sprintf("%02d", $month3));
          $this->db->group_end();

        $query  = $this->db->get();

        return $query->result();
    }
  }

  //cdnur csv
  function cdnur_csv($year,  $quarter = FALSE, $month = NULL)
  { 
    if($quarter == FALSE) 
    {
        $this->db->select('
                            "B2CL" as ur_type,
                            sr.reference_no,
                            sr.sales_return_date,
                            "C" As note_type,
                            st.name,
                            sr.total,
                            CASE 
                              WHEN sri.igst = 0 THEN (sri.cgst+sri.sgst) 
                              ELSE sri.igst
                            END AS rate,
                            SUM(sri.taxable_value),
                            "0" As cess_amount
                          ',FALSE);
        $this->db->from('sales_return sr');
        $this->db->join('sales_return_items sri','sri.sales_return_id = sr.id');
        $this->db->join('customer c','c.id = sr.customer_id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('sr.customer_gstin','');
        $this->db->where('sr.total >',250000);
        $this->db->where('sr.delete_status',0);
        $this->db->like('sr.sales_return_date',$year.'-'.sprintf("%02d", $month));

        $query = $this->db->get();
        
        return $query->result();                
    }
    else
    {
        $month1;
        $year1;

        $month2;
        $year2;

        $month3;
        $year3;

        if($quarter == 1)
        {
            $month1 = 4;
            $year1  = $year;

            $month2 = 5;
            $year2  = $year;

            $month3 = 6;
            $year3  = $year;
        }
        else if($quarter == 2)
        {
            $month1 = 7;
            $year1  = $year;

            $month2 = 8;
            $year2  = $year;

            $month3 = 9;
            $year3  = $year;   
        }
        else if($quarter == 3)
        {
            $month1 = 10;
            $year1  = $year;

            $month2 = 11;
            $year2  = $year;

            $month3 = 12;
            $year3  = $year;      
        }
        else
        {
            $month1 = 1;
            $year1  = $year;

            $month2 = 2;
            $year2  = $year;

            $month3 = 3;
            $year3  = $year;         
        }



        $this->db->select(' 
                            "B2CL" as ur_type,
                            sr.reference_no,
                            sr.sales_return_date,
                            "C" As note_type,
                            st.name,
                            sr.total,
                            CASE 
                              WHEN sri.igst = 0 THEN (sri.cgst+sri.sgst) 
                              ELSE sri.igst
                            END AS rate,
                            SUM(sri.taxable_value),
                            "0" As cess_amount',FALSE);
        $this->db->from('sales_return sr');
        $this->db->join('sales_return_items sri','sri.sales_return_id = sr.id');
        $this->db->join('states st','st.id = c.state_id','left');
        $this->db->where('sr.customer_gstin','');
        $this->db->where('sr.total >', 250000);
        $this->db->where('sr.delete_status',0);

          $this->db->group_start();
            $this->db->like('sr.sales_return_date',$year1.'-'.sprintf("%02d", $month1));
            $this->db->or_like('sr.sales_return_date',$year2.'-'.sprintf("%02d", $month2));
            $this->db->or_like('sr.sales_return_date',$year3.'-'.sprintf("%02d", $month3));
          $this->db->group_end();

        $query  = $this->db->get();

        return $query->result();
    }
  }

  public function b2b_sale($year,  $quarter = FALSE, $month = NULL)
  {
      if($quarter == FALSE) 
      {
          $this->db->select('s.*,st.state_code,st.id as customer_state_id,c.gstin as customer_gstin');
          $this->db->from('sale s');
          $this->db->join('customer c','c.id = s.customer_id');
          $this->db->join('states st','st.id = c.state_id','left');
          $this->db->where('c.gstin !=','');
          $this->db->where('s.delete_status',0);
          $this->db->like('s.invoice_date',$year.'-'.sprintf("%02d", $month));
          $query = $this->db->get();
          
          return $query->result();                
      }
      else
      {
          $month1;
          $year1;

          $month2;
          $year2;

          $month3;
          $year3;

          if($quarter == 1)
          {
              $month1 = 4;
              $year1  = $year;

              $month2 = 5;
              $year2  = $year;

              $month3 = 6;
              $year3  = $year;
          }
          else if($quarter == 2)
          {
              $month1 = 7;
              $year1  = $year;

              $month2 = 8;
              $year2  = $year;

              $month3 = 9;
              $year3  = $year;   
          }
          else if($quarter == 3)
          {
              $month1 = 10;
              $year1  = $year;

              $month2 = 11;
              $year2  = $year;

              $month3 = 12;
              $year3  = $year;      
          }
          else
          {
              $month1 = 1;
              $year1  = $year;

              $month2 = 2;
              $year2  = $year;

              $month3 = 3;
              $year3  = $year;         
          }



          $this->db->select('s.*,st.state_code,st.id as customer_state_id,c.gstin as customer_gstin');
          $this->db->from('sale s');
          $this->db->join('customer c','c.id = s.customer_id');
          $this->db->join('states st','st.id = c.state_id','left');
          $this->db->where('c.gstin !=','');
          $this->db->where('s.delete_status',0);

            $this->db->group_start();
              $this->db->like('s.invoice_date',$year1.'-'.sprintf("%02d", $month1));
              $this->db->or_like('s.invoice_date',$year2.'-'.sprintf("%02d", $month2));
              $this->db->or_like('s.invoice_date',$year3.'-'.sprintf("%02d", $month3));
            $this->db->group_end();

          $query  = $this->db->get();

          return $query->result();
      }
  }

  public function b2cs_sale($year,  $quarter = FALSE, $month = NULL)
  {
      if($quarter == FALSE) 
      {
         $this->db->select('si.*,s.invoice_date as invoice_date,st.state_code,st.id as customer_state_id');
          $this->db->from('sale_items si');
          $this->db->join('sale s','s.id = si.sale_id');
        /*  $this->db->join('product s','s.id = si.product_id');*/
          $this->db->join('customer c','c.id = s.customer_id');
          $this->db->join('states st','st.id = c.state_id','left');
          $this->db->where('c.gstin','');
         /* $this->db->where('si.sale_id',$sale_id);*/
          $this->db->where('s.delete_status',0);
          $this->db->like('s.invoice_date',$year.'-'.sprintf("%02d", $month));
          $query = $this->db->get();
          
          return $query->result();                
      }
      else
      {
          $month1;
          $year1;

          $month2;
          $year2;

          $month3;
          $year3;

          if($quarter == 1)
          {
              $month1 = 4;
              $year1  = $year;

              $month2 = 5;
              $year2  = $year;

              $month3 = 6;
              $year3  = $year;
          }
          else if($quarter == 2)
          {
              $month1 = 7;
              $year1  = $year;

              $month2 = 8;
              $year2  = $year;

              $month3 = 9;
              $year3  = $year;   
          }
          else if($quarter == 3)
          {
              $month1 = 10;
              $year1  = $year;

              $month2 = 11;
              $year2  = $year;

              $month3 = 12;
              $year3  = $year;      
          }
          else
          {
              $month1 = 1;
              $year1  = $year;

              $month2 = 2;
              $year2  = $year;

              $month3 = 3;
              $year3  = $year;         
          }



         $this->db->select('si.*,s.invoice_date,st.state_code,st.id as customer_state_id');
          $this->db->from('sale_items si');
          $this->db->join('sale s','s.id = si.sale_id');
       /*   $this->db->join('product s','s.id = si.product_id');*/
          $this->db->join('customer c','c.id = s.customer_id');
          $this->db->join('states st','st.id = c.state_id','left');
           $this->db->where('c.gstin','');
          /*$this->db->where('si.sale_id',$sale_id);*/
          $this->db->where('s.delete_status',0);
            $this->db->group_start();
              $this->db->like('s.invoice_date',$year1.'-'.sprintf("%02d", $month1));
              $this->db->or_like('s.invoice_date',$year2.'-'.sprintf("%02d", $month2));
              $this->db->or_like('s.invoice_date',$year3.'-'.sprintf("%02d", $month3));
            $this->db->group_end();

          $query  = $this->db->get();

          return $query->result();
      }
  }

  public function cdnr_sales_return($year,  $quarter = FALSE, $month = NULL)
  {
      if($quarter == FALSE) 
      {
          $this->db->select('s.*,st.state_code,st.id as customer_state_id,c.gstin as customer_gstin');
          $this->db->from('sales_return s');
          $this->db->join('customer c','c.id = s.customer_id');
          $this->db->join('states st','st.id = c.state_id','left');
          $this->db->where('c.gstin !=','');
          $this->db->where('s.delete_status',0);
          $this->db->like('s.sales_return_date',$year.'-'.sprintf("%02d", $month));
          $query = $this->db->get();
          
          return $query->result();                
      }
      else
      {
          $month1;
          $year1;

          $month2;
          $year2;

          $month3;
          $year3;

          if($quarter == 1)
          {
              $month1 = 4;
              $year1  = $year;

              $month2 = 5;
              $year2  = $year;

              $month3 = 6;
              $year3  = $year;
          }
          else if($quarter == 2)
          {
              $month1 = 7;
              $year1  = $year;

              $month2 = 8;
              $year2  = $year;

              $month3 = 9;
              $year3  = $year;   
          }
          else if($quarter == 3)
          {
              $month1 = 10;
              $year1  = $year;

              $month2 = 11;
              $year2  = $year;

              $month3 = 12;
              $year3  = $year;      
          }
          else
          {
              $month1 = 1;
              $year1  = $year;

              $month2 = 2;
              $year2  = $year;

              $month3 = 3;
              $year3  = $year;         
          }



          $this->db->select('s.*,st.state_code,st.id as customer_state_id,c.gstin as customer_gstin');
          $this->db->from('sales_return s');
          $this->db->join('customer c','c.id = s.customer_id');
          $this->db->join('states st','st.id = c.state_id','left');
          $this->db->where('c.gstin !=','');
          $this->db->where('s.delete_status',0);

            $this->db->group_start();
              $this->db->like('s.sales_return_date',$year1.'-'.sprintf("%02d", $month1));
              $this->db->or_like('s.sales_return_date',$year2.'-'.sprintf("%02d", $month2));
              $this->db->or_like('s.sales_return_date',$year3.'-'.sprintf("%02d", $month3));
            $this->db->group_end();

          $query  = $this->db->get();

          return $query->result();
      }
  }

  public function b2b_sale_items($sale_id)
  {
      return $this->db->select('si.*,s.hsn as hsn')
                      ->from('sale_items si')
                      ->join('product s','s.id = si.product_id')
                      ->where('si.sale_id',$sale_id)
                      ->get()
                      ->result();
  }

  public function cdnr_sales_return_items($sales_return_id)
  {
      return $this->db->select('si.*,s.hsn as hsn')
                      ->from('sales_return_items si')
                      ->join('product s','s.id = si.product_id')
                      ->where('si.sales_return_id',$sales_return_id)
                      ->get()
                      ->result();
  }

  public function b2cs_sale_items($sale_id)
  {
      return $this->db->select('si.*,s.hsn as hsn,st.state_code,st.id as customer_state_id')
                      ->from('sale_items si')
                      ->join('sale sa','sa.id = si.sale_id')
                      ->join('product s','s.id = si.product_id')
                      ->join('customer c','c.id = sa.customer_id')
                      ->join('states st','st.id = c.state_id','left')
                      ->where('si.sale_id',$sale_id)
                      ->get()
                      ->result();
  }


}
?>
