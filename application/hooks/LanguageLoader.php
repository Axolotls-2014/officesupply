<?php

class LanguageLoader
{

   function initialize() 
   {
       $ci =& get_instance();
       $ci->load->helper('language');
       $siteLang = $ci->session->userdata('site_lang');
       
       if ($siteLang) 
       {
          $ci->lang->load('auth',$siteLang);
          $ci->lang->load('company_setting',$siteLang);
          $ci->lang->load('currency',$siteLang);
          $ci->lang->load('customer',$siteLang);
          $ci->lang->load('discount',$siteLang);
          $ci->lang->load('email_setup',$siteLang);
          $ci->lang->load('expense_category',$siteLang);
          $ci->lang->load('ion_auth',$siteLang);
          $ci->lang->load('message',$siteLang);
          $ci->lang->load('service',$siteLang);
          $ci->lang->load('sidebar_menu',$siteLang);
          $ci->lang->load('sms_setup',$siteLang);
          $ci->lang->load('tax',$siteLang);
          $ci->lang->load('user',$siteLang);
          $ci->lang->load('user_role',$siteLang);
          $ci->lang->load('supplier',$siteLang);
          $ci->lang->load('reports',$siteLang);
          $ci->lang->load('application_settings',$siteLang);
          $ci->lang->load('common',$siteLang);
          $ci->lang->load('dashboard',$siteLang);
          $ci->lang->load('expense',$siteLang);
          $ci->lang->load('gst_return_lang',$siteLang);
          $ci->lang->load('pdf',$siteLang);
          $ci->lang->load('quotation',$siteLang);
          $ci->lang->load('sale',$siteLang);
          $ci->lang->load('log_data',$siteLang);

       } 
       else 
       {
          $ci->lang->load('auth','english');
          $ci->lang->load('company_setting','english');
          $ci->lang->load('currency','english');
          $ci->lang->load('customer','english');
          $ci->lang->load('discount','english');
          $ci->lang->load('email_setup','english');
          $ci->lang->load('expense_category','english');
          $ci->lang->load('ion_auth','english');
          $ci->lang->load('message','english');
          $ci->lang->load('service','english');
          $ci->lang->load('sidebar_menu','english');
          $ci->lang->load('sms_setup','english');
          $ci->lang->load('tax','english');
          $ci->lang->load('user','english');
          $ci->lang->load('user_role','english');
          $ci->lang->load('supplier','english');
          $ci->lang->load('reports','english');
          $ci->lang->load('application_settings','english');
          $ci->lang->load('common','english');
          $ci->lang->load('dashboard','english');
          $ci->lang->load('expense','english');
          $ci->lang->load('gst_return_lang','english');
          $ci->lang->load('pdf','english');
          $ci->lang->load('quotation','english');
          $ci->lang->load('sale','english');
          $ci->lang->load('log_data','english');
       }
   }
}