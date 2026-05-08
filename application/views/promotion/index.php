<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('promotion')?>"><?=$this->lang->line('header_promotion')?></a></li>
                <li class="breadcrumb-item active">Promotion List</li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Promotion</h3>
                <div class="card-tools">

                  <ul class="nav nav-pills ml-auto">
                    <?php 
                      if($this->permission_model->has_permission('add_promotion'))
                      {
                    ?>
                        <li class="nav-item ml-2">
                          <button class="nav-link btn-sm btn-primary text-white add_promotion_modal" data-tt="tooltip" title="Click here to Add Promotion">
                            <i class="far fa-snowflake mr-2"></i>Add
                          </button>
                        </li>
                    <?php
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_permission('global_promotion'))
                      {
                    ?>

                    <li class="nav-item ml-2">
                      <button class="nav-link btn-sm btn-primary text-white add_promotion_modal" data-tt="tooltip" title="Click here to Edit Global Promotion" data-promotion_id="1">
                        <i class="fas fa-cog mr-2"></i>Global Promotion
                      </button>
                    </li>

                    <?php
                      }
                    ?>
                  </ul>
                </div>
               
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="promotion_table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Product</th>
                      <th>Promotion</th>
                      <th>%</th>
                      <th>Configuration</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      <!-- /.row -->
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

  <div class="modal fade" id="add_promotion_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content ">
        
      </div>
    </div>
  </div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
  $(document).ready(function(e){
    /*************************** Start Dynamic Account Group List with Datatables **************************/
    initialize_datatable();
    function initialize_datatable()
    { 
      $('#promotion_table').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('promotion/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 5 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }
    
    /*************************** End Dynamic Account Group List with Datatables ****************************/



    /* add plan start */

    $(document).on('click', ".add_promotion_modal" ,function(event){
      event.preventDefault();
      
      var promotion_id = $(this).data('promotion_id');
      promotion_id = (promotion_id === undefined) ? "" : "/"+promotion_id;

      $.ajax({
        url: "<?=base_url('promotion/add')?>"+promotion_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_promotion_modal').find('.modal-content').html(data.add_promotion_modal_body);
          $('#add_promotion_modal').modal('show');
          reinitialize();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          Swal.fire({
            title: 'FAILURE !!',
            // text: xhr.status + thrownError + ajaxOptions,
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
        }
      });
    });

    /* add plan end */


    /* submit add plan start */

    /* Account Group Form submit Start */


    $(document).on('submit','#addPromotionForm',function(e){
      e.preventDefault();

      var isError = false;
      $('#addPromotionSubmit').text('Please wait...').attr('disabled','disabled');

      $('form#addPromotionForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          // alert(value);

          if(value==null || value==""){
            $("form#addPromotionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addPromotionForm #'+id).addClass('is-invalid');
            $('form#addPromotionForm #'+id).removeClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addPromotionForm #err_"+id).text("").fadeOut('slow');
            $('form#addPromotionForm #'+id).removeClass('is-invalid');
            $('form#addPromotionForm #'+id).addClass('is-valid');
          }
      });

      var promotionDataArray = [];

      $("#promotion_table_data").find('tr').each(function () {

          var tr                  = $(this).closest("tr");
          var promotionData   = {};

          promotionData['quantity']      = tr.find('input[name="quantity"]').val();
          promotionData['free_quantity']      = tr.find('input[name="free_quantity"]').val();

          promotionDataArray.push(JSON.stringify(promotionData));
      });

      if(promotionDataArray.length > 0)
      {
        $('#configuration').val('['+promotionDataArray.join(',')+']');
        // alert(promotionDataArray.join(','));
      }
      else
      {
        isError = true;
        Swal.fire({
          title: 'FAILURE !!',
          text: 'Minimum 1 configuration is required',
          icon: "error",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          customClass: {
              confirmButton: "btn btn-primary"
          }
        });
      }

      if(isError == true)
      {
        $('#addPromotionSubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else 
      {
        var formData = $('#addPromotionForm').serialize();
        
        var promotion_id = $('#add_promotion_modal').find('input[name="promotion_id"]').val();
        promotion_id = (promotion_id == '') ? "" : "/"+promotion_id;

        $.ajax({
          url: "<?php echo base_url('promotion/add')?>"+promotion_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_promotion_modal').modal('hide');
              $('form#addPromotionForm #addPromotionSubmit').text('Save').removeAttr('disabled');

              Swal.fire({
                title: 'SUCCESS !!',
                text: response.message,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#addPromotionForm  #err_"+key).text(value);
              });
              $('form#addPromotionForm #addPromotionSubmit').text('Save').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                title: 'FAILURE !!',
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#addPromotionSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addPromotionForm  .field_validation", function (event){
      var id    = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      
      if(value==null || value==""){
        $("form#addPromotionForm #err_"+id).text(field+ " field is required.");
        return false;
      }
      else{
        $("form#addPromotionForm #err_"+id).text("");
      }
    });

    /* submit add plan end */

    /* delete start */

    /* Delete Account  Start */

    $(document).on('click','.delete-warning',function (e) {
      // e.preventDefault();
      var promotion_id = $(this).data('promotion_id');

      //alert(promotion_id);

      Swal.fire({
        title: "Are you sure?",
        text: "Your will not be able to recover this configuration!",
        // icon: "warning",
        type: "warning",
        buttonsStyling: !1,
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-default"
        },
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showCancelButton: true
      }).then((result) => { 
        // alert("Result: " + JSON.stringify(result));
          /* Read more about isConfirmed, isDenied below */ 
         
          if (result.value === true) 
          {    
            $.ajax({
              url: "<?php echo base_url('promotion/action')?>",
              type: "POST",
              data: {
                'promotion_id':promotion_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              dataType: "JSON",
              success: function(data){
                if (data.code == 1) {
                  Swal.fire({
                    title: 'SUCCESS !!',
                    text: data.message,
                    icon: "success",
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary"
                    }
                  }).then(() => {
                    initialize_datatable();
                  });
                }
                else
                {
                  
                  Swal.fire({
                    title: 'FAILURE !!',
                    text: data.message,
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                  });
                }
              }
            });
          } 
         
      });
    });

    /* Delete Account End */
    

    $(document).on('click','.add_new_configuration',function(e){
      var firstRow = $("#configuration_table tbody tr:first");
      var lastRow = $("#configuration_table tbody tr:last");
      var newRow = firstRow.clone();
      newRow.find("input").val("");
      newRow.find("button").removeClass('d-none');
      newRow.insertAfter(lastRow);
      newRow.find('input:first').focus();
    });

    $(document).on('click','.delete_configuration',function(e){
      $(this).closest('tr').remove();
    });
    
  });
</script>



