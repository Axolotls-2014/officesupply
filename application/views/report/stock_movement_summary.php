<?php $this->load->view('layout/header'); ?>

<style>
    .filter-card { margin-bottom: 20px; }
    .in-row { background-color: #d4edda !important; }
    .out-row { background-color: #f8d7da !important; }
    .alert-row { background-color: #fff3cd !important; }
    .table-responsive { overflow-x: auto; }
    .sticky-header th { position: sticky; top: 0; background: #343a40; color: white; z-index: 10; }
    .text-red { color: #dc3545 !important; font-weight: bold; }
</style>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb breadcrumb-custom float-sm-left">
                        <li class="breadcrumb-item"><a href="<?=base_url('auth/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Reports</a></li>
                        <li class="breadcrumb-item active">Stock Movement Summary</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary filter-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter"></i> Filter Report</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" class="form-control datepicker" id="from_date" placeholder="DD-MM-YYYY" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" class="form-control datepicker" id="to_date" placeholder="DD-MM-YYYY" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Product</label>
                                        <select class="form-control select2bs4" id="product_id" style="width: 100%;">
                                            <option value="">All Products</option>
                                            <?php foreach($products as $product) { ?>
                                                <option value="<?=$product->pid?>"><?=$product->name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select class="form-control select2bs4" id="warehouse_id" style="width: 100%;">
                                            <option value="">All Branches</option>
                                            <?php foreach($warehouses as $warehouse) { ?>
                                                <option value="<?=$warehouse->id?>"><?=$warehouse->name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <input type="hidden" id="action_type" value="">
                            <button type="button" id="searchBtn" class="btn btn-info">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <button type="button" id="exportCsvBtn" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export CSV
                            </button>
                            <button type="button" id="exportPdfBtn" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button type="button" id="printBtn" class="btn btn-secondary">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    
                    <div id="reportContainer" style="display: none;"></div>
                    <div id="loadingIndicator" class="text-center" style="display: none; padding: 50px;">
                        <i class="fas fa-spinner fa-pulse fa-3x"></i>
                        <h4>Generating Report...</h4>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<script>
$(document).ready(function() {
    // Set default dates
    var today = new Date();
    var thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    $('#from_date').datepicker('setDate', thirtyDaysAgo);
    $('#to_date').datepicker('setDate', today);
    
    function loadReport(action_type) {
        $('#action_type').val(action_type);
        $('#loadingIndicator').show();
        $('#reportContainer').hide();
        
        var formData = {
            'from_date': $('#from_date').val(),
            'to_date': $('#to_date').val(),
            'product_id': $('#product_id').val(),
            'warehouse_id': $('#warehouse_id').val(),
            'action_type': action_type,
            '<?=$this->security->get_csrf_token_name();?>': '<?=$this->security->get_csrf_hash();?>'
        };
        
        $.ajax({
            url: '<?=base_url("report/stock_movement_summary")?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#loadingIndicator').hide();
                
                if(action_type == 'pdf' || action_type == 'export') {
                    var form = $('<form action="<?=base_url("report/stock_movement_summary")?>" method="POST" target="_blank">');
                    $.each(formData, function(key, value) {
                        form.append('<input type="hidden" name="' + key + '" value="' + value + '">');
                    });
                    $('body').append(form);
                    form.submit();
                    form.remove();
                } else {
                    $('#reportContainer').html(response.html).show();
                }
            },
            error: function(xhr, status, error) {
                $('#loadingIndicator').hide();
                Swal.fire('Error', 'Failed to load report: ' + error, 'error');
            }
        });
    }
    
    $('#searchBtn').click(function() { loadReport('search'); });
    $('#exportCsvBtn').click(function() { loadReport('export'); });
    $('#exportPdfBtn').click(function() { loadReport('pdf'); });
    $('#printBtn').click(function() { 
        var printContents = $('#reportContainer').html();
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    });
    
    // Initial load
    loadReport('search');
});
</script>