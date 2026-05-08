<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_setting')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('item')?>"><?=$this->lang->line('header_item')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('item_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="additemForm" id="additemForm" method="post" action="<?=base_url('item/add')?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('item_add')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('item_name')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="item_name" value="<?=set_value('item_name') ?>" class="form-control form-control-sm field_validation" id="item_name" placeholder="<?=$this->lang->line('item_name')?>"><?=form_error('item_name', '<div class="text-danger">', '</div>');?>
                      <span id="err_item_name" class="error invalid-feedback"><?=form_error('item_name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('item_description')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="item_description" value="<?=set_value('item_description') ?>" class="form-control form-control-sm field_validation" id="item_description" placeholder="<?=$this->lang->line('item_description')?>"><?=form_error('item_description', '<div class="text-danger">', '</div>');?>
                      <span id="err_item_description" class="error invalid-feedback"><?=form_error('item_description');?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" name="submit" id="itemSubmit" class="btn btn-info"><?=$this->lang->line('item_save')?></button>
                  <a href="<?=base_url('item')?>" class="btn btn-default float-right"><?=$this->lang->line('item_cancel')?></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">

  $(document).ready(function(e){

    $('form#additemForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#itemSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      
      $('form#additemForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#additemForm #err_"+id).text(field+ " field is required.");
            $('form#additemForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#additemForm #err_"+id).text("");
            $('form#additemForm #'+id).removeClass('is-invalid');
            $('form#additemForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#itemSubmit').text('<?=$this->lang->line("item_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#additemForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#additemForm #err_"+id).text(field+ " field is required.");
          $('form#additemForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#additemForm #err_"+id).text("");
          $('form#additemForm #'+id).removeClass('is-invalid');
          $('form#additemForm #'+id).addClass('is-valid');
        }
    });

  });
</script>

