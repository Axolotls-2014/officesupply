<?php $this->load->view('layout/header');?>
<style type="text/css">
  .image-container {
     height: 200px;
    /* Center the content both horizontally and vertically */
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Optionally, you can add additional styles for the image itself */
  .image-container img {
    max-width: 50%;
    max-height: 50%;
    /* Set the image to align to the middle of the container vertically */
    vertical-align: middle;
  }
</style>
<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
  <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

            <li class="breadcrumb-item "><a href="#">View Product</a></li>
            <li class="breadcrumb-item active">View  Product</li>
          </ol>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Product</h3>
            </div>
            <div class="card-body">
              <div class="card-tools">

                <table class="table table-bordered">

                    <tr>
                      <td rowspan="11">
                        <div class="image-container">
                          <?php 
                            if($product->product_image != '')
                            {
                          ?>
                              <img src="<?php echo base_url();?>assets/product_images/<?php echo $product->product_image;?>" height="200px" width="200px">
                          <?php
                            }
                            else
                            {
                          ?>
                              <img src="<?php echo base_url();?>assets/images/download.jpg" id="imagePreview" height="200px" width="200px">
                          <?php
                            }
                          ?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>PID</strong> : <?=$product->pid?></td>
                      <td><strong>Product Name</strong> : <?=$product->name?></td>
                      <td><strong>Product Category</strong> :
                      <?php
                        foreach ($product_categories as $value) {
                      ?>
                          <?php 
                            if($value->id == $product->product_category_id){
                              echo $value->name.' GST - '.$value->igst.'% ';
                            }
                          ?>
                      <?php 
                        }
                      ?>

                      </td>
                     
                    </tr>
                    <tr>
                      <td><strong>Cost</strong> : <?=$product->cost?></td>
                      <td><strong>MRP</strong> : <?=$product->price?></td>
                      <td><strong>Selling Price</strong> : <?=$product->selling_price?></td>
                    </tr>
                    <tr>
                      <td><strong>HSN</strong> : <?=$product->hsn?></td>
                      <td><strong>Alert Quantity</strong> : <?=$product->alert_quantity?></td>
                      <td>
                          <strong>Unit of Measure</strong> :
                          <?php
                            foreach ($uoms as $value) {
                          ?>
                              <?php 
                                if(isset($uom_id))
                                {
                                  if($uom_id == $value->id)
                                  {
                                      echo $value->name.' ('.$value->uom.')';
                                  }
                                }
                                else
                                {
                                  if($value->id == $product->uom_id)
                                  {
                                      echo $value->name.' ('.$value->uom.')';
                                  }
                                }
                              ?>
                          <?php 
                              }
                          ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Status</strong> :
                        <?php 
                          if($product->status == PRODUCT_STATUS_ACTIVE){
                            echo ucfirst(PRODUCT_STATUS_ACTIVE);
                          }
                        ?>
                    
                        <?php 
                          if($product->status == PRODUCT_STATUS_INACTIVE)
                            echo ucfirst(PRODUCT_STATUS_INACTIVE);
                        ?>
                              
                      </td>
                     
                     
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Description</strong> <br>
                         <?=$product->description?>
                        </td>
                        
                    </tr>

                </table>
                 
              </div>
            </div>
          </div>
        </div>
      </div>

       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Warehouse Products</h3>
            </div>
            <div class="card-body">
              <div class="card-tools">

                <table class="table table-bordered">



                  <tr>
                    <th>Product Name</th>
                  
                    <th>Batch No</th>
                   
                    <th> Purchase Price</th>
                    <th>MRP</th>
                    <th>Selling Price</th>
                   
                    <th>Quantity</th>
                    
                  </tr>

                  <?php

                    foreach($warehouse_products as $value)
                    {

                  ?>

                  <tr>
                    <td><?=$value->product_name?></td>
                  
                    <td><?=$value->batch_no?></td>
                  
                    <td><?=$value->cost?></td>
                    <td><?=$value->price?></td>
                    <td><?=$value->selling_price?></td>
                   
                    <td><?=$value->quantity?></td>
                    
                  </tr>

                  <?php
                    }
                  ?>


                </table>
                 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php $this->load->view('layout/footer');?>