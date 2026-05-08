
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

<form role="form" method="POST" name="importCurrencyForm" id="importCurrencyForm" enctype="multipart/form-data" action="<?=base_url('currency/import_currency')?>">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('import_currency');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

  <div class="form-group row">
        <label for="csvfile" class="col-sm-4 col-form-label">
          <?=$this->lang->line("currency_csv_file")?>
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
          <input type="checkbox" name="update_currency" value="1"> Update only currency<br/>
          <input type="checkbox" name="create_currency" value="1"> Create new currency though it exist <br/>
        </div>
      </div> 

      <div class="row">
        <div class="col-md-12" style="opacity: 0.7; border-radius: 5px; background-color: #F8E4A4; font-size: 16px;">
          <div class="m-4">
            Download sample file  <a href="<?=base_url('assets/sample_files/sample_currency.csv')?>" target="_blank" class="btn btn-info" style="float:right">Click here to Download</a>  
          </div>
        </div>
      </div>

      <div id="result" class="table-container"></div>

  </div>

  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="importCurrencyubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
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
          url: '<?php echo base_url('currency/get_import_currency')?>',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {

            let resultHTML = '<h6 style="margin-top:10px;">Existing Currency:</h6> ';
          
             if (response.length > 0) 
             {
                resultHTML += '<table border="1" class="table table-bordered table-striped csv-table">';

                resultHTML += '<tr>';
                resultHTML += '<th> Serial No. </th>';
                resultHTML += '<th> Currency Name </th>';
                resultHTML += '<th> Currency Symbol </th>';
                resultHTML += '</tr>';

                $.each(response, function (index, currency) {
                    let serialNumber = index + 1;
                    resultHTML += '<tr>';
                    resultHTML += '<td>' + serialNumber + '</td>';
                    resultHTML += '<td>' + currency.name + '</td>';
                    resultHTML += '<td>' + currency.symbol + '</td>';
                    resultHTML += '</tr>';
                });

                resultHTML += '</table>';
            } 
            else 
            {
              resultHTML += '<table border="1" class="table table-bordered table-striped csv-table">';
              resultHTML += '<tr>';
                resultHTML += '<th> Serial No. </th>';
                resultHTML += '<th> Currency Name </th>';
                resultHTML += '<th> Currency Symbol </th>';
                resultHTML += '</tr>';

              resultHTML += '<tr>';
                resultHTML += '<td colspan="3"> No matched currency found. </th>';
               
                resultHTML += '</tr>';
              resultHTML += '</table>';
              //resultHTML += '<p>No matched suppliers found.</p>';
            }

            $('#result').html(resultHTML);

          },
          error: function (error) {
            alert("AJAX Error: " + error); // Show the error message
          }
      });
    });

   
});

</script>
  
