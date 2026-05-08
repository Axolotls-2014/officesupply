<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_setting')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('currency/list')?>"><?=$this->lang->line('header_currency')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('currency_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editCurrencyForm" id="editCurrencyForm" method="post" action="<?php echo base_url('currency/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('currency_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('currency_name')?>
                      <span class="text-danger">*</span> 
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$currency->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('currency_name')?>"><?=form_error('name', '<div class="text-danger">', '</div>');?>
                      <span id="err_name" class="error invalid-feedback"><!-- <?=form_error('name');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('currency_symbol')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="symbol" value="<?=set_value('symbol',$currency->symbol) ?>" class="form-control form-control-sm field_validation" id="symbol" placeholder="<?=$this->lang->line('currency_symbol')?>"><?=form_error('symbol', '<div class="text-danger">', '</div>');?>
                      <span id="err_symbol" class="error invalid-feedback"><!-- <?=form_error('symbol');?> --></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$currency->id?>">
                  <button type="submit" name="submit" id="currencySubmit" class="btn btn-info"><?=$this->lang->line('currency_edit')?></button>
                   <a href="<?=base_url('currency')?>" class="btn btn-default float-right"><?=$this->lang->line('currency_cancel')?></a>
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

    $('form#editCurrencyForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#currencySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editCurrencyForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editCurrencyForm #err_"+id).text(field+ " field is required.");
            $('form#editCurrencyForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editCurrencyForm #err_"+id).text("");
            $('form#editCurrencyForm #'+id).removeClass('is-invalid');
            $('form#editCurrencyForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#currencySubmit').text('<?=$this->lang->line("currency_edit")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editCurrencyForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editCurrencyForm #err_"+id).text(field+ " field is required.");
          $('form#editCurrencyForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editCurrencyForm #err_"+id).text("");
          $('form#editCurrencyForm #'+id).removeClass('is-invalid');
          $('form#editCurrencyForm #'+id).addClass('is-valid');
        }
    });

  });
</script>

