<?php $this->load->view('layout/header'); ?>

<style type="text/css">
    .footer_data{
        font-size: 20px;
    }
    .warning-data{
        background-color: #FF7F50 !important;
    }
</style>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb breadcrumb-custom float-sm-left">
                        <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                        <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_stock_value')?></a></li>
                        <li class="breadcrumb-item active"><?=$this->lang->line('closing_stock_report')?></li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="saleReportForm" name="saleReportForm" method="POST" action="<?php echo base_url("report/closing_stock") ?>">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-funnel-dollar"></i>
                                    <?=$this->lang->line('filter_report')?>
                                </h3>
                            </div>
                            <div class="card-body"> 
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label><?=$this->lang->line('select_date_range')?></label>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-block btn-outline-secondary float-right" id="daterange-btn">
                                                <i class="far fa-calendar-alt"></i> Date Range
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('from_date')?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="<?=$from_date?>" style="z-index:999 !important" readonly="readonly">
                                                <span class="input-group-append">
                                                    <span class="input-group-text form_date_cancel text-danger" style="cursor:pointer;"><i class="fas fa-times"></i></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('to_date')?></label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="<?=($to_date != null) ? date('d-m-Y', strtotime($to_date)) : ''?>" style="z-index:999 !important" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="product_id"><?=$this->lang->line('product_product')?></label>
                                            <select class="form-control form-control-sm select2bs4" name="pid" id="pid" width="100%">
                                                <option value="">All</option>
                                                <?php
                                                    if($products != null) {
                                                        foreach ($products as $value) {
                                                ?>
                                                    <option value="<?=$value->pid;?>">
                                                        <?= $value->name;?>
                                                    </option>
                                                <?php 
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="action_type" id="action_type" value="">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <button type="submit" name="submit" id="submitSaleReport" class="btn btn-info"><?=$this->lang->line('search')?></button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?=$this->lang->line('closing_stock_report')?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool bt-danger" id="export-button">
                                    <i class="fas fa-file-export"></i>
                                    <?=$this->lang->line('export')?>
                                </button>
                            </div> 
                        </div>
                        <div class="card-body expense_list">
                            <div class="table-responsive">
                                <table class="sticky-header-table table table-bordered table-striped" id="stock-value-data">
                                    <thead>
                                        <tr>
                                            <th><?=$this->lang->line('sr_no')?></th>
                                            <th>Product category</th>
                                            <th>Product Desc</th>
                                            <th><?=$this->lang->line('product_name')?></th>
                                            <th>HSN</th>
                                            <th>UOM</th>
                                            <th>Pur</th>
                                            <th>Pur vl</th>
                                            <th>Sale</th>
                                            <th>Sale vl</th>
                                            <th>Stock In</th>
                                            <th>Stock In vl</th>
                                            <th>Stock Out</th>
                                            <th>Stock Out vl</th>
                                            <th>Cl Stock</th>
                                            <th>Cl Stock Value P. Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stock-value-body">
    <?php
        if ($report_data != null && sizeof($report_data) > 0) {
            foreach ($report_data as $row) {
    ?>
        <tr data-pid="<?=$row['pid']?>">
            <td><?=$row['sr_no']?></td>
            <td><?=$row['product_category_name']?></td>
            <td><?=$row['description']?></td>
            <td><?=$row['name']?></td>
            <td><?=$row['hsn']?></td>
            <td><?=$row['uom_name']?></td>
            <td><?=number_format($row['pur'], 2)?></td>
            <td><?=number_format($row['pur_vl'], 2)?></td>
            <td><?=number_format($row['sale'], 2)?></td>
            <td><?=number_format($row['sale_vl'], 2)?></td>
            <td><?=number_format($row['stock_in'], 2)?></td>
            <td><?=number_format($row['stock_in_vl'], 2)?></td>
            <td><?=number_format($row['stock_out'], 2)?></td>
            <td><?=number_format($row['stock_out_vl'], 2)?></td>
            <td><?=number_format($row['closing_stock'], 2)?></td>
            <td><?=number_format($row['closing_stock_cost_value'], 2)?></td>
        </tr>
    <?php 
            }
        } else {
            echo '<tr><td colspan="16" class="text-center">No Record Found.</td></tr>';
        }
    ?>
</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Opening qty</th>
                                        <th>Opening Value</th>
                                        <th>Closing qty</th>
                                        <th>Closing Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=number_format($total_opening_quantity, 2)?></td>
                                        <td><?=number_format($total_opening_cost, 2)?></td>
                                        <td><?=number_format($total_closing_quantity, 2)?></td>
                                        <td><?=number_format($total_closing_cost, 2)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer'); ?>

<script>
$(document).ready(function () {
    $(document).on('click', "#submitSaleReport", function(e){
        Swal.fire({
            title: "Message",
            html: "Please wait <br> while we are generating report for you.<br>",
            showConfirmButton: false
        });
    });
    
    $("#export-button").click(function () {
        var data = [];
        var headers = [];
        $("#stock-value-data thead th").each(function () {
            headers.push('"' + $(this).text() + '"');
        });
        data.push(headers);
        
        $("#stock-value-data tbody tr").each(function () {
            var rowData = [];
            $(this).find("td").each(function () {
                var cellData = $(this).text().trim();
                rowData.push('"' + cellData + '"');
            });
            data.push(rowData);
        });
        
        var csvContent = "data:text/csv;charset=utf-8,\n";
        data.forEach(function (rowArray) {
            var row = rowArray.join(",");
            csvContent += row + "\n";
        });
        
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "ClosingStock.csv");
        document.body.appendChild(link);
        link.click();
    });

    $('#daterange-btn').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function (start, end) {
        $('#from_date').val(start.format('DD-MM-Y'));
        $('#to_date').val(end.format('DD-MM-Y'));
    });

    $('#pid').on('change', function () {
        var selectedProductId = $(this).val();
        if (selectedProductId) {
            $('#stock-value-data tbody tr').hide();
            $('#stock-value-data tbody tr[data-pid="' + selectedProductId + '"]').show();
        } else {
            $('#stock-value-data tbody tr').show();
        }
    });

    $('.form_date_cancel').on('click', function (e){
        $('#from_date').val('');
    });
});
</script>