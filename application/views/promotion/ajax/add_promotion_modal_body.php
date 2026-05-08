<form  name="addPromotionForm" id="addPromotionForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?=($promotion_id == 1 && $promotion->product_id == 0 && $promotion_id != null) ? 'Global Promotion' : 'Add Promotion' ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  
  <div class="modal-body">
      <div class="form-group row <?=($promotion_id == 1 && $promotion->customer_id == 0 && $promotion_id != null) ? 'd-none' : '' ?>">
        <label for="customer_id" class="col-md-4 required">Customer</label>
        <div class="col-md-8">
          <select class="form-control form-control-sm select2 field_validation" name="<?=($promotion_id == null) ? 'customer_id[]' : 'customer_id' ?>" id="customer_id" placeholder="Customer">
            <option value="<?=($promotion_id == 1 && $promotion->customer_id == 0 && $promotion_id != null) ? '0' : ''?>"
              <?php
                if($promotion_id == null)
                  echo ' multiple';
              ?>
            >Select Customer</option>
            <?php 
              foreach ($customers as $value) {
            ?>
                <option value="<?=$value->id?>"
                  <?php 
                    if($promotion != null && $promotion->customer_id != 0)
                    {
                      if($value->id == $promotion->customer_id)
                        echo ' selected';
                    }
                  ?>
                >
                  <?=$value->customer_name?>
                </option>
            <?php
              }
            ?>
          </select>
          <span class="error text-danger" id="err_customer_id"></span>
        </div>
      </div>
      <div class="form-group row <?=($promotion_id == 1 && $promotion->product_id == 0 && $promotion_id != null) ? 'd-none' : '' ?>">
        <label for="product_id" class="col-md-4 required">Product</label>
        <div class="col-md-8">
          <select class="form-control form-control-sm select2 field_validation" name="<?=($promotion_id == null) ? 'product_id[]' : 'product_id' ?>" id="product_id" placeholder="Product" 
            <?php
              if($promotion_id == null)
                echo ' multiple';
            ?>
          >
            <option value="<?=($promotion_id != null) ? '0' : '0'?>">All Product</option>
            <?php 
              foreach ($products as $value) {
            ?>
                <option value="<?=$value->id?>"
                  <?php 
                    if($promotion != null && $promotion->promotion_id != 1)
                    {
                      if($value->id == $promotion->product_id)
                        echo ' selected';
                    }
                  ?>
                >
                  <?=$value->name?>
                </option>
            <?php
              }
            ?>
          </select>
          <span class="error text-danger" id="err_product_id"></span>
        </div>
      </div>
      <div class="form-group row">
        <label for="promotion_type" class="col-md-4 required">Default Promotion</label>
        <div class="col-md-8">
          <div class="icheck-primary d-inline">
            <input type="radio" class="form-control" id="promotion_type_quantity" name="promotion_type" value="quantity"
              <?php 
                if($promotion->promotion_type != null && $promotion->promotion_type == PROMOTION_TYPE_QUANTITY)
                  echo ' checked';
              ?>
            >
            <label for="promotion_type_quantity" class="normal_font">
              Quantity
            </label>
          </div>  
          <div class="icheck-primary d-inline">
            <input type="radio" class="form-control" id="promotion_type_percentage" name="promotion_type" value="percentage" 
              <?php 
                if($promotion->promotion_type != null && $promotion->promotion_type == PROMOTION_TYPE_PERCENTAGE)
                  echo ' checked';
              ?>
            >
            <label for="promotion_type_percentage" class="normal_font">
              Percentage
            </label>
          </div>
          
          <span class="error text-danger" id="err_promotion_type"></span>
        </div>
      </div>
      <div class="form-group row">
        <label for="promotion_type" class="col-md-4 required">Percentage</label>
        <div class="col-md-8">
          <input type="number" class="form-control form-control-sm field_validation" name="percentage" id="percentage" placeholder="Percentage" value="<?=$promotion->percentage?>" min="0" max="100" step="any">
          <span class="error text-danger" id="err_percentage"></span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table width="100%" class="table table-striped table-bordered" id="configuration_table">
            <thead>
              <tr>
                <td>Quantity</td>
                <td>Free Quantity</td>
                <td>Action</td>
              </tr>  
            </thead>
            <tbody id="promotion_table_data">
              <?php
                  $configuration_array = json_decode($promotion->configuration, true);
                  
                  for ($i=0; $i < sizeof($configuration_array) ; $i++) 
                  { 
              ?>
                    <tr>
                      <td>
                        <input type="number" name="quantity" class="form-control form-control-sm quantity" value="<?=$configuration_array[$i]['quantity']?>">
                      </td>
                      <td>
                        <input type="number" name="free_quantity" class="form-control form-control-sm free_quantity" value="<?=$configuration_array[$i]['free_quantity']?>">
                      </td>
                      <td>
                        <button type="button" class="btn btn-xs btn-danger <?=($i == 0) ? "d-none" : ""?> delete_configuration">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
              <?php
                  }
              ?>
            </tbody>
            <tfoot>
              <td colspan="2"> 
                <button type="button" class="btn btn-sm btn-block btn-default add_new_configuration">Add</button>
              </td>
              <td></td>
            </tfoot>
          </table>
        </div>
      </div>
      
  </div>
  <div class="modal-footer">
    <input type="hidden" name="configuration" id="configuration" value="">
    <input type="hidden" name="promotion_id" id="promotion_id" value="<?=($promotion_id != '') ? $promotion->promotion_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" name="addPromotionSubmit" id="addPromotionSubmit">Save changes</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>