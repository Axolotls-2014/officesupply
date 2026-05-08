<style>
  .label-with-icon {
      display: inline-flex;
      align-items: center;
  }
  .label-with-icon .icon {
      margin-right: 5px; /* Adjust as needed */
      cursor: pointer;
      color: #D3D3D3;
  }
  .alert {
    position: absolute;
    top: 5;
    right: 0;
    color: blue;
    padding: 5px 10px;
    border-radius: 5px;
    display: none;
    z-index: 1000;
  }
</style>

<form name="addwhatsappTemplateForm" id="addwhatsappTemplateForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($whatsapp_template == '')
          echo $this->lang->line('whatsapp_template_add');
        else
          echo $this->lang->line('whatsapp_template_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <?php
      // Initialize the array to store assigned modules
      $assigned_wm_types = array();

      foreach ($whatsapp_templates as $template) {
          $assigned_wm_types[] = $template->wm_type;
      }
      $assigned_wm_types = array_unique($assigned_wm_types); // Remove duplicates
    ?>

    <div class="form-group row">
        <label for="wm_type" class="col-md-4 required"><?=$this->lang->line('whatsapp_template_type')?></label>
        <div class="col-md-8">
            <select class="form-control field_validation form-control-sm select2" style="width: 100%;" placeholder="<?=$this->lang->line('whatsapp_template_type')?>" id="wm_type" name="wm_type">
                <option value="">Select</option>
                <?php 
                // Retrieve all wm_types
                $wm_type_array = enum_select('whatsapp_template','wm_type');
                
                // Loop through each wm_type
                foreach ($wm_type_array as $wm_type) {
                    // Check if the wm_type is not assigned to any other template or if it matches the wm_type assigned to the whatsapp template being edited
                    $is_assigned = in_array($wm_type, $assigned_wm_types);
                    $is_selected = ($whatsapp_template != null && $wm_type == $whatsapp_template->wm_type);
                    
                    // Display the wm_type in the dropdown
                    if (!$is_selected) {
                        ?>
                        <option value="<?=$wm_type?>" <?=($is_assigned) ? 'disabled' : ''?>><?=clean_e_val($wm_type)?></option>
                        <?php 
                    } else {
                        ?>
                        <option value="<?=$wm_type?>" selected><?=clean_e_val($wm_type)?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <span id="copy-alert" class="alert">Copied!</span>
            <span class="error invalid-feedback" id="err_wm_type"></span>
        </div>
    </div>

    <!-- Container for dynamic fields -->
    <div class="form-group row">
        <label class="col-md-4"><?=$this->lang->line('whatsapp_template_keys')?></label>
        <div class="col-md-8" id="wm_keys_container">
            <!-- Dynamic fields will be appended here -->
        </div>
    </div>

    <div class="form-group row">
        <label for="wm_type" class="col-md-4 required"><?=$this->lang->line('whatsapp_template_message')?></label>
        <div class="col-md-8">
          <textarea type="text" name="wm_message" id="wm_message" rows="20" class="form-control form-control-sm field_validation"  placeholder="<?=$this->lang->line("whatsapp_template_message")?>"><?=($whatsapp_template != null) ? $whatsapp_template->wm_message : '' ?></textarea>
          <span class="error invalid-feedback" id="err_wm_message"></span>
        </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="wm_id" id="wm_id" value="<?=($whatsapp_template != null) ? $whatsapp_template->wm_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addwhatsappTemplateSubmit" name="addwhatsappTemplateSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>

<script>
    $(document).ready(function() {
        $('#wm_type').change(function() {
            var selectedType = $(this).val();
            var wtKeysConfig = <?php echo json_encode($this->config->item('wt_keys')); ?>;
            var keys = wtKeysConfig[selectedType] || [];

            var keysContainer = $('#wm_keys_container');
            keysContainer.empty();

            keys.forEach(function(key, index) {
                var isLast = index === keys.length - 1;
                var span = `
                    <span class="label-with-icon">
                        <i class="fas fa-clipboard-list icon clipboard-icon" data-clipboard-text="{{${key}}}" data-tt="tooltip" title="Copy"></i>
                        <span class="clipboard-text" data-clipboard-text="{{${key}}}" style="cursor:pointer;">{{${key}}}</span>
                    </span>${isLast ? '' : ','} 
                `;
                keysContainer.append(span);
            });
        });

        // Trigger change event on page load if a wm_type is already selected
        $('#wm_type').trigger('change');
    });

    function insertAtCursor(input, textToInsert) {
        var startPos = input.selectionStart;
        var endPos = input.selectionEnd;
        input.value = input.value.substring(0, startPos) + textToInsert + input.value.substring(endPos, input.value.length);
        input.selectionStart = input.selectionEnd = startPos + textToInsert.length;
    }

    $(document).on('click', '.clipboard-text', function(event) {
        event.stopPropagation(); // Prevent event from bubbling up
        var textToCopy = $(this).attr('data-clipboard-text');
        var subjectInput = document.getElementById('wm_message');
        insertAtCursor(subjectInput, textToCopy);
    });

    $(document).on('click', '.clipboard-icon', function(event) {
        event.stopPropagation(); // Prevent event from bubbling up
        var textToCopy = $(this).attr('data-clipboard-text');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(textToCopy).select();
        document.execCommand("copy");
        $temp.remove();

        var $copyAlert = $('#copy-alert');
        $copyAlert.fadeIn().delay(1000).fadeOut();
    });
</script>
