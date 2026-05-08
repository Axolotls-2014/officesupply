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

<form role="form" method="POST" name="importProductForm" id="importProductForm" enctype="multipart/form-data" action="<?=base_url('product_core/import_product')?>">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_import_product');?></h4>
    <button type="submit" name="submit" id="importProductSubmit" class="btn btn-primary" style="float: right"><?=$this->lang->line('submit')?></button>
  </div>
  <div class="modal-body">

      <!-- Warehouse Selection - ADD THIS NEW SECTION -->
      <div class="form-group row">
        <label for="warehouse_id" class="col-sm-4 col-form-label">
            Warehouse <span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
            <select class="form-control form-control-sm select2bs4" name="warehouse_id" id="warehouse_id" required>
                <option value="">Select Warehouse</option>
                <?php
                    if(isset($warehouses) && !empty($warehouses)) {
                        foreach ($warehouses as $value) {
                ?>
                    <option value="<?=$value->id;?>">
                        <?= $value->name;?>
                    </option>
                <?php 
                        }
                    }
                ?>
            </select>
            <span id="err_warehouse_id" class="error invalid-feedback"></span>
            <small class="text-muted">Select warehouse where product stock will be added</small>
        </div>
      </div>

      <div class="form-group row d-none">
        <label for="supplier_id" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_supplier")?>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4 " name="supplier_id" id="supplier_id" placeholder="<?=$this->lang->line('product_supplier')?>" width="100%">
            <option value="">Select</option>
            <?php
              if(isset($suppliers) && !empty($suppliers)) {
                  foreach ($suppliers as $value) {
            ?>
              <option value="<?=$value->id;?>">
                <?= $value->company_name;?>
              </option>
            <?php 
                  }
              }
            ?>
          </select>
          <span id="err_supplier_id" class="error invalid-feedback"></span>
        </div>
      </div>

      <div class="form-group row d-none">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("header_product_category")?>
        </label>
        <div class="col-sm-8">
          <?php
            if($this->permission_model->has_permission('add_product_category'))
            { 
          ?>
          <div class="input-group input-group-sm">
            <select class="form-control form-control-sm select2bs4 " name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('header_product_category')?>" width="100%">
              <option value=""><?=$this->lang->line('select')?></option>
              <?php
                if(isset($product_categories) && !empty($product_categories)) {
                    foreach ($product_categories as $value) {
              ?>
                <option value="<?=$value->id;?>">
                  <?= $value->name;?>
                </option>
              <?php 
                    }
                }
              ?>
            </select>
            <span class="input-group-append">
              <button type="button" class="btn btn-info btn-flat add_product_category_modal" data-toggle="modal" data-target="#add_product_category_modal" data-tt="tooltip" title="<?=$this->lang->line('product_category_add')?>"><i class="fas fa-plus"></i></button>
            </span>
          </div>
          <?php
            }
            else
            {
          ?>
          <select class="form-control form-control-sm select2bs4 " name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('header_product_category')?>" width="100%">
            <option value=""><?=$this->lang->line('select')?></option>
            <?php
              if(isset($product_categories) && !empty($product_categories)) {
                  foreach ($product_categories as $value) {
            ?>
              <option value="<?=$value->id;?>">
                <?= $value->name;?>
              </option>
            <?php 
                  }
              }
            ?>
          </select>
          <?php
            } 
          ?>
          <span id="err_product_category_id" class="error invalid-feedback"></span>
        </div>
      </div>

      <div class="form-group row d-none">
        <label for="uom_id" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_uom")?>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%">
            <option value="">Select</option>
            <?php
              if(isset($uoms) && !empty($uoms)) {
                  foreach ($uoms as $value) {
            ?>
              <option value="<?=$value->id;?>">
                <?= $value->uom;?>
              </option>
            <?php 
                  }
              }
            ?>
          </select>
          <span id="err_uom_id" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="form-group row">
        <label for="csvfile" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_csv_file")?>
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
          <input type="checkbox" name="update_product" value="1"> Update only Product<br/>
          <input type="checkbox" name="create_product" value="1"> Create new product though it exist <br/>
        </div>
      </div> 

      <div class="row">
        <div class="col-md-12" style="opacity: 0.7; border-radius: 5px; background-color: #F8E4A4; font-size: 16px;">
          <div class="m-4">
            Download sample file  
            <a href="<?=base_url('assets/sample_files/sample_product_new.csv')?>" target="_blank" class="btn btn-info ml-2 mr-2" style="float:right">Product Sample</a>
            <a href="<?=base_url('product_category/export')?>" target="_blank" class="btn btn-info ml-2 mr-2" style="float:right">Product Category</a>
            <a href="<?=base_url('uom/export')?>" target="_blank" class="btn btn-info ml-2 mr-2" style="float:right">UOM</a>
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

      var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>";
      var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>";

      formData.append(csrfTokenName, csrfTokenValue);

      $.ajax({
          type: 'POST',
          url: '<?php echo base_url('product_core/get_import_product')?>',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            let resultHTML = '<h6 style="margin-top:10px;">Existing Products:</h6> ';
          
            if (response.length > 0) {
                resultHTML += '<table border="1" class="table table-bordered table-striped csv-table">';
                resultHTML += '<tr>';
                resultHTML += '<th> Serial No. </th>';
                resultHTML += '<th> Manage Inventory </th>';
                resultHTML += '<th> Product Code </th>'; // NEW
                resultHTML += '<th> Product Category </th>';
                resultHTML += '<th> Product Name </th>';
                resultHTML += '<th> Product Description </th>';
                resultHTML += '<th> HSN </th>';
                resultHTML += '<th> Cost </th>';
                resultHTML += '<th> Price </th>';
                resultHTML += '<th> Selling Price </th>';
                resultHTML += '<th> Alert Quantity </th>'; // NEW
                resultHTML += '<th> Status </th>'; // NEW
                resultHTML += '</tr>';

                $.each(response, function (index, product) {
                    let serialNumber = index + 1;
                    
                    resultHTML += '<tr>';
                    resultHTML += '<td>' + serialNumber + '</td>';
                    resultHTML += '<td>' + (product.manage_inventory == 1 ? 'Yes' : 'No') + '</td>';
                    resultHTML += '<td>' + (product.product_code ? product.product_code : 'N/A') + '</td>';
                    resultHTML += '<td>' + product.product_category_name + '</td>';
                    resultHTML += '<td>' + product.name + '</td>';
                    resultHTML += '<td>' + (product.description ? product.description : 'N/A') + '</td>';
                    resultHTML += '<td>' + (product.hsn ? product.hsn : 'N/A') + '</td>';
                    resultHTML += '<td>' + product.cost + '</td>';
                    resultHTML += '<td>' + product.price + '</td>';
                    resultHTML += '<td>' + product.selling_price + '</td>';
                    resultHTML += '<td>' + product.alert_quantity + '</td>';
                    resultHTML += '<td>' + (product.status == 1 ? 'Active' : 'Inactive') + '</td>';
                    resultHTML += '</tr>';
                });

                resultHTML += '</table>';
            } else {
                resultHTML += '<p>No matched products found.</p>';
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
        $('.single_product').prop('checked',true);
      else
        $('.single_product').prop('checked',false);
    });
  });
</script>