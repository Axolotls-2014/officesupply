
<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>User List</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">User List</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h2 class="card-title">User List</h2>

                  <div class="card-tools">
                    <a href="<?= base_url('product/add'); ?>">
                      <button type="button" class="btn btn-primary btn-sm">
                        Add New Product
                      </button>
                    </a>
                  </div>                     

                </div>
                <div class="card-body product_list">
 
                   <script type="text/javascript">
                       
                 
                        function Add() {
                            AddRow($("#name").val(), $("#email").val());
                            $("#name").val("");
                            $("#email").val("");
                        };
                 
                        function AddRow(name, email) {
                            //Get the reference of the Table's TBODY element.
                            var tBody = $("#addrow > TBODY")[0];
                 
                            //Add Row.
                            row = tBody.insertRow(-1);
                 
                            //Add Name cell.
                            var cell = $(row.insertCell(-1));
                            cell.html(name);
                 
                            //Add Email cell.
                            cell = $(row.insertCell(-1));
                            cell.html(email);
                 
                            //Add Button cell.
                            cell = $(row.insertCell(-1));
                            var btnRemove = $("<input />");
                            btnRemove.attr("type", "button");
                            btnRemove.attr("onclick", "Remove(this);");
                            btnRemove.val("Remove");
                            cell.append(btnRemove);
                        };
                 
                        function Remove(button) {
                            //Determine the reference of the Row using the Button.
                            var row = $(button).closest("TR");
                            var name = $("TD", row).eq(0).html();
                            if (confirm("Do you want to delete: " + name)) {
                 
                                //Get the reference of the Table.
                                var table = $("#addrow")[0];
                 
                                //Delete the Table row using it's Index.
                                table.deleteRow(row[0].rowIndex);
                            }
                        };
                  </script>

                  <table id="addrow" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                          <td><input type="text" id="name" /></td>
                          <td><input type="text" id="email" /></td>
                          <td><input type="button" onclick="Add()" value="Add" /></td>
                      </tr>
                    </tfoot>
                    
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>



<?php $this->load->view('layout/footer');?>


  


