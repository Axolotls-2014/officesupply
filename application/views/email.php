<!DOCTYPE html> 
<html lang = "en"> 

   <head> 
      <meta charset = "utf-8"> 
      <title>CodeIgniter Email Example</title> 
   </head>
    
   <body> 
      <form method="post" action="<?php echo base_url('sending_email/send_mail');?>">
        
      <input type = "email" name = "email" required /> 
       <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <input type = "submit" name="submit" value = "SEND MAIL"> 
        
      </form> 
   </body>
    
</html>