<style>
  
  .csv-table {
    margin: 0 auto; 
    margin-bottom: 15px; 
    margin-top: 15px;
    
  }
  .hide-on-select-all {
      display: none;
      visibility: hidden;
  }

  .table-container {
    overflow-x: auto;
   
    max-width: 100%;
  }

  
  .table-container table {
    min-width: 100%;
  }

</style> 
<form role="form" method="POST" name="importEmployeeForm" id="importEmployeeForm" enctype="multipart/form-data" action="<?=base_url('employee/import_employee')?>">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('employee_import');?></h4>
   
    <button type="submit" name="submit" id="importEmployeeSubmit" class="btn btn-primary" style="float: right"><?=$this->lang->line('submit')?></button>
  </div>
  <div class="modal-body">

      <div class="form-group row">
        <label for="csvfile" class="col-sm-4 col-form-label">
          <?=$this->lang->line("employee_csv_file")?>
        </label>
        <div class="col-sm-8">
          <div class="input-group input-group-sm">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="csvfile" name="csvfile" accept=".csv">
              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
            </div>
          </div>
          <span id="err_csvfile" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="form-group row">
        <label for="csvfile" class="col-sm-4 col-form-label">
          <!-- <?=$this->lang->line("product_update")?> -->
        </label>  
        <div class="col-sm-8">
          <input type="checkbox" name="update_employee" value="1"> Update only Employee<br/>
          <span class="d-none"><input type="checkbox" name="create_employee" value="1"> Create new employee though it exist </span><br/>
        </div>
      </div> 

      <div class="row">
        <div class="col-md-12" style="opacity: 0.7; border-radius: 5px; background-color: #F8E4A4; font-size: 16px;">
          <div class="m-4">
            Download sample file  
            <a href="<?=base_url('assets/sample_files/sample_employee.csv')?>" target="_blank" class="btn btn-info ml-2 mr-2" style="float:right">Employee Sample</a>
            <a href="<?=base_url('department/export_from_import_employee')?>" target="_blank" class="btn btn-info ml-2 mr-2" style="float:right">Department</a>
            <a href="<?=base_url('position/export_from_import_employee')?>" target="_blank" class="btn btn-info ml-2 mr-2" style="float:right">Position </a>
          </div> 
    
        </div>
      </div>

      <div id="result" class="table-container"></div>
  </div>

  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
   
  </div>
</form>


<script>
  $(document).ready(function() {
    $('#csvfile').change(function(event) {

      event.preventDefault();
      const formData = new FormData();
      formData.append('csvfile', $('#csvfile')[0].files[0]);

      //alert(formData);

      var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
      var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

      // Add the CSRF token to the form data
      formData.append(csrfTokenName, csrfTokenValue);

      $.ajax({
          type: 'POST',
          url: '<?php echo base_url('employee/get_import_employee')?>',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {


            let resultHTML = '<h6 style="margin-top:10px;">Existing Employees:</h6> ';
          
             if (response.length > 0) {
                resultHTML += '<table border="1" class="table table-bordered table-striped csv-table">';

           
                resultHTML += '<tr>';
                resultHTML += '<th> Serial No. </th>';
                resultHTML += '<th> First Name </th>';
                resultHTML += '<th> Last Name </th>';
                resultHTML += '<th> Email </th>';
              
                resultHTML += '<th> Salary </th>';
               
                resultHTML += '</tr>';

                $.each(response, function (index, employee) {
                    let serialNumber = index + 1;
                    
                    resultHTML += '<tr>';
                    resultHTML += '<td>' + serialNumber + '</td>';
                    resultHTML += '<td>' + employee.first_name + '</td>';
                    resultHTML += '<td>' + employee.last_name + '</td>';
                    resultHTML += '<td>' + employee.email + '</td>';
                   
                    resultHTML += '<td>' + employee.salary + '</td>';
                    
                  
                    // Add other fields as needed
                    resultHTML += '</tr>';
                });

                resultHTML += '</table>';
            } else {
                resultHTML += '<p>No matched employees found.</p>';
            }

            $('#result').html(resultHTML);

          },
          error: function (error) {
              console.error(error.responseText);
          }
      });
    });

    $(document).on('change', '.selectAll', function() {
      if(this.checked == true)
        $('.single_employee').prop('checked',true);
      else
        $('.single_employee').prop('checked',false);
    });

  
  
});

</script>

  
