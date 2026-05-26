<?php $this->load->view('layout/header');?>

<style>
    /* ── Layout ── */
    .content-wrapper { background: #f0f2f5; }

    /* ── Sidebar ── */
    .sidebar-card { border-radius: 0; border: none; box-shadow: none; border-right: 1px solid #e8eaed; }
    .sidebar-container { height: calc(100vh - 160px); overflow-y: auto; overflow-x: hidden; background: #fff; }

    /* Sidebar header toolbar */
    .sidebar-toolbar { display: flex; gap: 8px; align-items: center; padding: 10px 12px; border-bottom: 1px solid #e8eaed; }
    .sidebar-toolbar .dataTables_filter { flex: 1; }
    .sidebar-toolbar .dataTables_filter label { display: flex; align-items: center; margin: 0; }
    .sidebar-toolbar .dataTables_filter input { flex: 1; height: 30px; font-size: 12px; border: 1px solid #dadce0; border-radius: 6px; padding: 0 9px; background: #f8f9fa; color: #3c4043; outline: none; }
    .sidebar-toolbar .dataTables_filter input:focus { border-color: #2563eb; background: #fff; }
    .btn-add-product { height: 30px; padding: 0 11px; font-size: 12px; font-weight: 500; color: #fff; background: #2563eb; border: none; border-radius: 6px; cursor: pointer; white-space: nowrap; }
    .btn-add-product:hover { background: #1d4ed8; }

    /* Sidebar rows */
    #product_sidebar_table thead { display: none; }
    .product-item { padding: 9px 14px; border-bottom: 1px solid #f1f3f4 !important; cursor: pointer; transition: background 0.15s; }
    .product-item:hover { background: #f8f9fa; }
    .product-item.active { background: #eff6ff !important; border-left: 3px solid #2563eb !important; }
    .product-item.active span { color: #1d4ed8; font-weight: 600; }
    .product-item .p-name { font-size: 12.5px; color: #3c4043; }
    .product-item .badge { font-size: 11px; font-weight: 600; padding: 2px 7px; border-radius: 10px; }
    .badge-light { background: #e8f5e9 !important; color: #2e7d32 !important; border: none !important; }
    .badge-danger { background: #fce8e6 !important; color: #c5221f !important; border: none !important; }

    /* ── Summary stat cards ── */
    .stat-card { background: #fff; border-radius: 8px; padding: 12px 14px; border-left: 3px solid #2563eb; margin-bottom: 12px; }
    .stat-card.pur { border-left-color: #6b7280; }
    .stat-card.qty { border-left-color: #0891b2; }
    .stat-card.val { border-left-color: #16a34a; }
    .stat-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #9aa0a6; display: block; }
    .stat-value { font-size: 16px; font-weight: 700; color: #202124; margin-top: 3px; }

    /* ── Ledger table ── */
    #transaction_ledger_table { font-size: 12px; width: 100% !important; }
    #transaction_ledger_table thead th { background: #f8f9fa; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #5f6368; white-space: nowrap; }
    .dataTables_scrollHeadInner, .dataTables_scrollHeadInner table { width: 100% !important; }
    .ledger-scroll-container { height: calc(100vh - 350px); overflow-y: auto; overflow-x: hidden; }

    /* Card refinements */
    .card { border-radius: 8px !important; }
    .card-header { border-bottom: 1px solid #f1f3f4 !important; background: #fff !important; }
    .card-title { font-size: 11px !important; font-weight: 700 !important; text-transform: uppercase; letter-spacing: 0.05em; color: #5f6368 !important; }

    /* Top filter bar */
    .content-header .card { border-radius: 6px !important; border: 1px solid #e8eaed !important; box-shadow: none !important; }
    .form-control-sm { font-size: 12.5px; border-radius: 6px; border-color: #dadce0; background: #f8f9fa; }

    /* Product name heading */
    #h_prod_name { font-size: 15px; font-weight: 600; color: #202124; }

    /* Stock In / Out buttons */
    .btn-success.btn-sm { background: #dcfce7; color: #166534; border: none; font-size: 12px; font-weight: 500; border-radius: 6px; }
    .btn-danger.btn-sm  { background: #fee2e2; color: #991b1b; border: none; font-size: 12px; font-weight: 500; border-radius: 6px; }
    .btn-success.btn-sm:hover { background: #bbf7d0; color: #14532d; }
    .btn-danger.btn-sm:hover  { background: #fecaca; color: #7f1d1d; }
    /* Hide DataTables' auto-generated search box in sidebar */
    #product_sidebar_table_wrapper .dataTables_filter { display: none !important; }
    #product_sidebar_table_wrapper .dataTables_paginate { display: none !important; }
    #product_sidebar_table_wrapper .dataTables_info { display: none !important; }
    /* Remove scrollbar visibility while keeping scroll functionality */
    .sidebar-container::-webkit-scrollbar { width: 0px; background: transparent; }
    .sidebar-container { scrollbar-width: none; -ms-overflow-style: none; }
</style>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header pb-2">
            <div class="card mb-0 shadow-sm border-0">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <select class="form-control form-control-sm select2bs4 filter" id="warehouse_id">
                                <option value="">All Branches</option>
                                <?php foreach ($warehouses as $value): ?>
                                    <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control form-control-sm select2bs4 filter" id="quantity">
                                <option value="<?=QUANTITY_ALL?>">All Quantity</option>
                                <option value="<?=QUANTITY_GREATER_THEN_ZERO?>">Greater than Zero</option>
                                <option value="<?=QUANTITY_ZERO?>">Zero Stock</option>
                                <option value="<?=QUANTITY_NEGATIVE?>">Negative Stock</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <!-- LEFT SIDEBAR -->
                <div class="col-md-3 pr-0">
                    <div class="card sidebar-card h-100 mb-0">
                        <!--<div class="card-header bg-white py-2"><h3 class="card-title text-bold">Products</h3></div>-->
                        <div class="card-header bg-white py-2">
                            <h3 class="card-title text-bold">Products</h3>
                        </div>
                        <div class="sidebar-toolbar">
                            <div class="dataTables_filter">
                                <label><input type="search" placeholder="Search items…" onkeyup="sidebar_table.search(this.value).draw()" /></label>
                            </div>
                            <button class="btn-add-product trigger-dashboard-add">+ Add</button>
                        </div>
                        <div class="card-body p-0 sidebar-container">
                            <table id="product_sidebar_table" class="table mb-0">
                                <thead>
                                    <tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th></tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-md-9">
                    <div id="no_selection" class="text-center p-5 card border-0 shadow-sm" style="margin-top: 50px;">
                        <i class="fas fa-mouse-pointer fa-4x text-light mb-3"></i>
                        <h5 class="text-muted">Select an item from the left to view ledger</h5>
                    </div>

                    <div id="detail_view" style="display:none;">
                        <!-- Header with Buttons -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="h_prod_name" class="font-weight-bold mb-0 text-dark"></h4>
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm add_stock_modal mr-2" id="header_stock_in" data-entry_type="in">
                                    <i class="fas fa-plus-circle mr-1"></i> Stock In
                                </button>
                                <button class="btn btn-danger btn-sm add_stock_modal" id="header_stock_out" data-entry_type="out">
                                    <i class="fas fa-minus-circle mr-1"></i> Stock Out
                                </button>
                            </div>
                        </div>

                        <!-- Summary Stat Cards -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stat-card sale"><span class="stat-label">Sale Price</span><span class="stat-value" id="h_sale_price">0.00</span></div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card pur"><span class="stat-label">Pur. Price</span><span class="stat-value" id="h_pur_price">0.00</span></div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card qty"><span class="stat-label">Stock Balance</span><span class="stat-value text-info" id="h_stock_qty">0</span></div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card val"><span class="stat-label">Total Value</span><span class="stat-value text-success" id="h_stock_val">0.00</span></div>
                            </div>
                        </div>

                        <!-- Ledger Table -->
                        <div class="card border-0 shadow-sm mt-2">
                            <div class="card-header bg-white p-2">
                                <h3 class="card-title text-bold py-1">Transaction History</h3>
                            </div>
                            <div class="card-body p-0" style = "overflow-x: hidden;">
                                <table id="transaction_ledger_table" class="table table-bordered table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Date</th><th>Type</th><th>Ref No</th>
                                            <th>HSN</th><th>UOM</th><th>Alert</th><th>MRP</th>
                                            <th>Pur. Price</th><th>Sale Price</th><th>In Qty</th>
                                            <th>Out Qty</th><th>Closing</th><th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot align="right">
                                        <tr>
                                            <th colspan="11" class="text-right">Balance:</th>
                                            <th id="ft_qty" class="text-info"></th>
                                            <th id="ft_val" class="text-success"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Add this missing block -->
<div class="modal fade" id="add_product_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- The form will be injected here by AJAX -->
        </div>
    </div>
</div>
<!-- Modal Container -->
<div class="modal fade" id="add_stock_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"><div class="modal-content"></div></div>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
var selected_product_id = '';
var sidebar_table, ledger_table;

const productToast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
});

// Sidebar Initialization
function initialize_datatable() {
    if ($.fn.DataTable.isDataTable('#product_sidebar_table')) {
        $('#product_sidebar_table').DataTable().destroy();
    }

    sidebar_table = $('#product_sidebar_table').DataTable({
        "processing": true, "serverSide": true, "paging": false, "searching": true,
        "ordering": true, "order": [[0, "asc"]], "info": false, "lengthChange": false, "autoWidth": false, "pageLength": -1,
        "language": { "search": "", "searchPlaceholder": "Search items..." },
        "ajax": {
            "url": "<?php echo site_url('product/ajax_list') ?>",
            "type": "POST",
            "data": function(d) {
                d.warehouse_id = $('#warehouse_id').val();
                d.quantity = $('#quantity').val();
                d.manage_inventory = $('#manage_inventory').val();
                d.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
            }
        },
        "columnDefs": [
            { "targets": [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], "visible": false },
            { 
                "targets": 2, 
                "visible": true,
                "render": function(data, type, row) {
                    return `<div class="d-flex justify-content-between align-items-center">
                                <span>${data}</span>
                            </div>`;
                }
            }
        ],
        "fnRowCallback": function(nRow) {
            $(nRow).addClass('product-item');
            return nRow;
        }
    });
}

// Ledger Initialization
function load_ledger() {
    if ($.fn.DataTable.isDataTable('#transaction_ledger_table')) {
        $('#transaction_ledger_table').DataTable().destroy();
    }

    ledger_table = $('#transaction_ledger_table').DataTable({
        "processing": true, "serverSide": true, "scrollX": false, "searching": false,
        "ordering": false, "autoWidth": false,
        "ajax": {
            "url": "<?php echo site_url('product/ajax_list_stock') ?>",
            "type": "POST",
            "data": function(d) {
                d.product_id = selected_product_id;
                d.warehouse_id = $('#warehouse_id').val();
                d.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
            }
        },
        "columns": [
            { "data": 0 }, { "data": 1 }, { "data": 2 }, 
            { "data": 5 }, { "data": 6 }, { "data": 7 }, { "data": 8 }, 
            { "data": 9 }, { "data": 10 }, { "data": 11 }, { "data": 12 }, 
            { "data": 13 }, { "data": 14 }
        ],
        "initComplete": function() { this.api().columns.adjust(); },
        "footerCallback": function (row, data, start, end, display) {
            if(data.length > 0) {
                var latestQty = data[0][13];
                var latestVal = data[0][14];
                $('#ft_qty, #h_stock_qty').html(latestQty);
                $('#ft_val, #h_stock_val').html(latestVal);
            } else {
                $('#ft_qty, #ft_val, #h_stock_qty, #h_stock_val').html('0');
            }
        }
    });
}

$(document).ready(function() {
    initialize_datatable();

    // Row click handler
    // $('#product_sidebar_table tbody').on('click', 'tr', function() {
    //     var data = sidebar_table.row(this).data();
    //     if(!data) return;

    //     $('.product-item').removeClass('active');
    //     $(this).addClass('active');

    //     var row_content = $('<div>' + data[0] + '</div>');
    //     selected_product_id = row_content.find('input').attr('data-product_id');
    //     var wp_id = row_content.find('input').attr('data-warehouse_product_id');

    //     $('#header_stock_in, #header_stock_out')
    //         .attr('data-product_id', selected_product_id)
    //         .attr('data-warehouse_product_id', wp_id)
    //         .attr('data-warehouse_id', $('#warehouse_id').val());

    //     $('#no_selection').hide();
    //     $('#detail_view').show();
    //     $('#h_prod_name').html(data[2]);
    //     $('#h_sale_price').html(data[8]);
    //     $('#h_pur_price').html(data[9]);
        
    //     load_ledger();
    // });
    
//      $(document).on('click', ".trigger-dashboard-add", function(e) {
//         e.preventDefault();
//         $.ajax({
//             url: "<?php echo base_url('product_core/dashboard_add_product') ?>",
//             type: "GET",
//             dataType: "JSON",
//             success: function(data) {
//                 // We use the existing modal container in your layout
//                 var modal = $('#add_product_modal'); 
//                 modal.find('.modal-content').html(data.add_product_modal_body);
                
//                 // Change the form ID to ensure it uses OUR new handler
//                 modal.find('form').attr('id', 'dashboardAddProductForm');
                
//                 modal.modal('show');
//                 $('.select2bs4').select2({ theme: 'bootstrap4' });
//             }
//         });
//     });

//     // Handle the submission for this specific flow
//   $(document).on('submit', '#dashboardAddProductForm', function(e) {
//     e.preventDefault();
    
//     var form = $(this);
//     var btn = $('#addProductSubmit');
    
//     // 1. Change button state
//     btn.text('Saving...').attr('disabled', 'disabled');

//     // 2. Prepare Form Data
//     var formData = new FormData(this);
    
//     // 3. MANUALLY ADD CSRF TOKEN (This is the missing piece!)
//     formData.append("<?php echo $this->security->get_csrf_token_name(); ?>", "<?php echo $this->security->get_csrf_hash(); ?>");

//     $.ajax({
//         url: "<?php echo base_url('product_core/dashboard_add_product') ?>",
//         type: "POST",
//         data: formData,
//         processData: false,
//         contentType: false,
//         dataType: "JSON",
//         success: function(response) {
//             if (response.code == 1) {
//                 $('#add_product_modal').modal('hide');
//                 Swal.fire({ icon: 'success', title: 'Success', text: response.message, timer: 2000 });
                
//                 // Reload your sidebar table
//                 if (typeof sidebar_table !== 'undefined') {
//                     sidebar_table.ajax.reload(null, false);
//                 }
//             } else {
//                 Swal.fire({ icon: 'error', title: 'Validation Error', text: response.message });
//                 btn.text('Submit').removeAttr('disabled');
//             }
//         },
//         error: function(xhr, status, error) {
//             // This will tell us if there is a server crash (500 error)
//             console.log(xhr.responseText);
//             Swal.fire({ icon: 'error', title: 'Server Error', text: 'Check console for details' });
//             btn.text('Submit').removeAttr('disabled');
//         }
//     });
// });

$(document).on('click', ".trigger-dashboard-add", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo base_url('product_core/add') ?>", // Original function
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var modal = $('#add_product_modal'); 
                modal.find('.modal-content').html(data.add_product_modal_body);
                
                // Ensure the form uses the original add URL for saving too
                modal.find('form').attr('action', "<?php echo base_url('product_core/add') ?>");
                modal.find('form').attr('id', 'dashboardAddProductForm');
                
                modal.modal('show');
                $('.select2bs4').select2({ theme: 'bootstrap4' });
            }
        });
    });

    // 2. Submit to the ORIGINAL 'add' function
    $(document).on('submit', '#dashboardAddProductForm', function(e) {
        e.preventDefault();
        var btn = $('#addProductSubmit');
        btn.text('Saving...').attr('disabled', 'disabled');

        var formData = new FormData(this);
        // Add CSRF
        formData.append("<?php echo $this->security->get_csrf_token_name(); ?>", "<?php echo $this->security->get_csrf_hash(); ?>");

        $.ajax({
            url: $(this).attr('action'), // Points to product_core/add
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(response) {
                if (response.code == 1) {
                    $('#add_product_modal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message, timer: 2000 });
                    
                    // Reload sidebar
                    if (typeof sidebar_table !== 'undefined') {
                        sidebar_table.ajax.reload(null, false);
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                    btn.text('Submit').removeAttr('disabled');
                }
            }
        });
    });
    
   $('#product_sidebar_table tbody').on('click', 'tr', function() {
    var data = sidebar_table.row(this).data();
    if(!data) return;
    console.log('Raw data[0]:', data[0]);

    $('.product-item').removeClass('active');
    $(this).addClass('active');

    var row_content = $('<div>' + data[0] + '</div>');
    
    // ✅ Target the hidden input specifically, not the first input (checkbox)
    var hidden_input = row_content.find('input[type="hidden"]');
    
    selected_product_id = hidden_input.attr('data-product_id');
    var wp_id = hidden_input.attr('data-warehouse_product_id');
    var warehouse_id = hidden_input.attr('data-warehouse_id');
    if(!warehouse_id || warehouse_id == "0" || warehouse_id == "") {
        warehouse_id = $('#warehouse_id').val(); // Try top filter first
        if(!warehouse_id || warehouse_id == "") warehouse_id = "1"; // Default to 1 if still empty
    }
    
    console.log('warehouse_id:', warehouse_id); // Should now show "1"
 console.log('Hidden inputs found:', hidden_input.length);
    console.log('All inputs found:', row_content.find('input').length);
    console.log('warehouse_product_id:', hidden_input.attr('data-warehouse_product_id'));
    console.log('product_id:', hidden_input.attr('data-product_id'));
    console.log('warehouse_id:', hidden_input.attr('data-warehouse_id'));
    $('#header_stock_in, #header_stock_out')
        .attr('data-product_id', selected_product_id)
        .attr('data-warehouse_product_id', wp_id)
        .attr('data-warehouse_id', warehouse_id);

    $('#no_selection').hide();
    $('#detail_view').show();
    $('#h_prod_name').html(data[2]);
    $('#h_sale_price').html(data[8]);
    $('#h_pur_price').html(data[9]);
    
    load_ledger();
});

    // OPEN MODAL AND FIX EMPTY FIELDS
    // $(document).on('click', ".add_stock_modal" ,function(){
    //     var entry_type = $(this).attr("data-entry_type");
    //     var wp_id = $(this).attr("data-warehouse_product_id");

    //     if(!wp_id || wp_id == "" || wp_id == "undefined") {
    //         alert("Please select an item from the list first.");
    //         return;
    //     }

    //     $.ajax({
    //         url: "<?php echo base_url('stock/add')?>/"+wp_id,
    //         type: "GET",
    //         dataType: "JSON",
    //         success: function(data){
    //             $('#add_stock_modal').find('.modal-content').html(data.add_stock_modal_body);
    //             $('#add_stock_modal').find('#entry_type').val(entry_type);
    //             $('#add_stock_modal').modal('show');
                
    //             // 1. Initialize Select2
    //             $('.select2bs4').select2({ theme: 'bootstrap4' });
                
    //             // 2. FORCE TRIGGER CHANGE: This copies dropdown values to the Name/Unit/Price fields
    //             setTimeout(function(){
    //                 // Trigger dropdown logic
    //                 $('#add_stock_modal').find('#product_id').trigger('change');
    //                 $('#add_stock_modal').find('#warehouse_id').trigger('change');
                    
    //                 // Manually populate text inputs if they remain empty
    //                 var p_name = $('#add_stock_modal').find('#product_id option:selected').text();
    //                 var w_name = $('#add_stock_modal').find('#warehouse_id option:selected').text();
    //                 $('#add_stock_modal').find('#product_name').val(p_name.trim());
    //                 $('#add_stock_modal').find('#warehouse_name').val(w_name.trim());
    //             }, 400);
    //         }
    //     });
    // });
    
   $(document).on('click', ".add_stock_modal", function(){
            var entry_type = $(this).attr("data-entry_type");
            var warehouse_product_id = $(this).attr("data-warehouse_product_id");
            var product_id = $(this).attr("data-product_id");
            var warehouse_id = $(this).attr("data-warehouse_id");
             if (!warehouse_id || warehouse_id == "" || warehouse_id == "0") {
                    warehouse_id = "1"; 
                }
        console.log('entry_type:', entry_type);
    console.log('warehouse_product_id:', warehouse_product_id);
    console.log('product_id:', product_id);
    console.log('warehouse_id:', warehouse_id);
            if(!warehouse_product_id || warehouse_product_id == "" || warehouse_product_id == "undefined") {
                alert("Please select an item from the list first.");
                return;
            }
        
            $.ajax({
                url: "<?php echo base_url('stock/add')?>/"+warehouse_product_id,
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#add_stock_modal').find('.modal-content').html(data.add_stock_modal_body);
                    $('#add_stock_modal').find('#entry_type').val(entry_type);
                    $('#add_stock_modal').find('#warehouse_product_id').val(warehouse_product_id);
        
                    // Filter product dropdown to only selected product
                    $('#add_stock_modal').find('#product_id option').each(function() {
                        if ($(this).val() != product_id) {
                            $(this).remove();
                        }
                    });
        
                    var product_name = $('#add_stock_modal').find('form#addStockForm #product_id option:selected').text();
                    $('#add_stock_modal').find('#product_name').val(product_name.trim());
        
                    // Filter warehouse dropdown to only selected warehouse
                    $('#add_stock_modal').find('form#addStockForm #warehouse_id option').each(function() {
                        if ($(this).val() != warehouse_id) {
                            $(this).remove();
                        }
                    });
        
                    var warehouse_name = $('#add_stock_modal').find('form#addStockForm #warehouse_id option:selected').text();
                    $('#add_stock_modal').find('#warehouse_name').val(warehouse_name.trim());
        
                    // THIS is what fills UOM, price, cost, batch etc.
                    $('#add_stock_modal').find('form#addStockForm #warehouse_product_id').trigger('change');
                    $('#add_stock_modal').find('form#addStockForm #product_id').trigger('change');
        
                    $('.select2bs4').select2({ theme: 'bootstrap4' });
                    $('#add_stock_modal').modal('show');
                }
            });
        });

    $(document).on('submit','#addStockForm',function(e){
        e.preventDefault();
        $('#addStockSubmit').attr('disabled','disabled').text('Processing...');
        $.ajax({
            url: "<?php echo base_url('stock/add')?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(response){
                if(response.code==1) { 
                    $('#add_stock_modal').modal('hide');
                    productToast.fire({ type: 'success', title: response.message });
                    load_ledger();
                    sidebar_table.ajax.reload(null, false);
                } else {
                    $('#addStockSubmit').removeAttr('disabled').text('Submit');
                    alert(response.message);
                }
            }
        });
    });

    $('.filter').change(function() {
        initialize_datatable();
        $('#detail_view').hide();
        $('#no_selection').show();
    });
    
    // ADD THIS to your ledger page script
        // $(document).on('change', 'form#addStockForm #product_id', function() {
        //     var selected = $(this).find(':selected');
            
        //     $('form#addStockForm #product_uom').val(selected.data('product_uom'));
        //     $('form#addStockForm #product_price').val(selected.data('product_price'));
        //     $('form#addStockForm #product_cost').val(selected.data('product_cost'));
        //     $('form#addStockForm #selling_price').val(selected.data('selling_price'));
            
        //     // Handle batch numbers
        //     var batchNumbersString = selected.data('batch_no');
        //     if (batchNumbersString && batchNumbersString.length > 1) {
        //         var batchNumbersArray = batchNumbersString.split(',');
        //         var datalist = $('#batchNumbersDatalist');
        //         datalist.empty();
        //         batchNumbersArray.forEach(function(batch) {
        //             datalist.append('<option value="' + batch.trim() + '">');
        //         });
        //         $('form#addStockForm #batch_no').val(batchNumbersArray[0].trim());
        //     }
            
        //     if($('#entry_type').val() == '<?=WAREHOUSE_STOCK_OUT?>') {
        //         $('#product_quantity').attr('max', selected.data('max_quantity'));
        //     }
        // });
        
      $(document).on('change', 'form#addStockForm #product_id', function() {
            var selected = $(this).find(':selected');
        
            var uom     = selected.data('product_uom');
            var cost    = selected.data('product_cost');
            var mrp     = selected.data('product_price');
            var selling = selected.data('selling_price');
            var batch   = selected.data('batch_no');
            var maxQty  = selected.data('max_quantity');
        
            // ── 1. Log raw option data ──────────────────────────────────────
            console.group('addStockForm #product_id change');
            console.log('Selected option HTML :', selected[0]?.outerHTML);
            console.log('product_uom          :', uom);
            console.log('product_cost         :', cost);
            console.log('product_price (mrp)  :', mrp);
            console.log('selling_price        :', selling);
            console.log('batch_no             :', batch);
            console.log('max_quantity         :', maxQty);
        
            // ── 2. Log fields found in the form ────────────────────────────
            console.log('--- Fields found in form ---');
            console.log('#product_uom exists  :', $('form#addStockForm #product_uom').length > 0);
            console.log('#unit exists         :', $('form#addStockForm #unit').length > 0);
            console.log('#product_cost exists :', $('form#addStockForm #product_cost').length > 0);
            console.log('#cost exists         :', $('form#addStockForm #cost').length > 0);
            console.log('#product_price exists:', $('form#addStockForm #product_price').length > 0);
            console.log('#mrp exists          :', $('form#addStockForm #mrp').length > 0);
            console.log('#selling_price exists:', $('form#addStockForm #selling_price').length > 0);
            console.log('#product_quantity ex :', $('form#addStockForm #product_quantity').length > 0);
            console.log('#batch_no exists     :', $('form#addStockForm #batch_no').length > 0);
        
            // ── 3. Fill fields ─────────────────────────────────────────────
            $('form#addStockForm #product_uom, form#addStockForm #unit').val(uom);
            $('form#addStockForm #product_cost, form#addStockForm #cost').val(cost);
            $('form#addStockForm #product_price, form#addStockForm #mrp').val(mrp);
            $('form#addStockForm #selling_price').val(selling);
        
            // ── 4. Log values actually set ─────────────────────────────────
            console.log('--- Values set in form ---');
            console.log('#product_uom value   :', $('form#addStockForm #product_uom').val());
            console.log('#unit value          :', $('form#addStockForm #unit').val());
            console.log('#product_cost value  :', $('form#addStockForm #product_cost').val());
            console.log('#cost value          :', $('form#addStockForm #cost').val());
            console.log('#product_price value :', $('form#addStockForm #product_price').val());
            console.log('#mrp value           :', $('form#addStockForm #mrp').val());
            console.log('#selling_price value :', $('form#addStockForm #selling_price').val());
        
            // ── 5. Stock Out max quantity ──────────────────────────────────
            if ($('#entry_type').val() == '<?= WAREHOUSE_STOCK_OUT ?>') {
                console.log('Entry type: STOCK OUT — setting max qty to', maxQty);
                $('form#addStockForm #product_quantity').attr('max', maxQty);
            } else {
                console.log('Entry type:', $('#entry_type').val(), '(not stock out)');
            }
        
            // ── 6. Batch Number Logic ──────────────────────────────────────
            if (batch && batch.toString().length > 0) {
                var batches = batch.toString().split(',');
                console.log('Batches found        :', batches);
        
                var datalist = $('#batchNumbersDatalist');
                console.log('#batchNumbersDatalist exists:', datalist.length > 0);
        
                datalist.empty();
                $.each(batches, function(i, b) {
                    datalist.append('<option value="' + b.trim() + '">');
                });
        
                $('form#addStockForm #batch_no').val(batches[0].trim());
                console.log('batch_no set to      :', batches[0].trim());
            } else {
                console.log('No batch data found on selected option');
            }
        
            console.groupEnd();
        });
});
</script>