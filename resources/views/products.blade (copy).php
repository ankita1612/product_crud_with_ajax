<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Products</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
      <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
   </head>
   <body>
      <div class="container mt-2">
         <div class="row">
            <div class="col-lg-12 margin-tb">
               <div class="pull-left">
                  <h2>Product</h2>
               </div>
               <div class="pull-right mb-2">
                  <a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create Product</a>
               </div>
            </div>
         </div>
         @if ($message = Session::get('success'))
         <div class="alert alert-success">
            <p>{{ $message }}</p>
         </div>
         @endif
         <div class="card-body">
            <table class="table table-bordered" id="ajax-crud-datatable">
               <thead>
                  <tr>
                     <th>Id</th>
                     <th>product_name</th>
                     <th>product_price</th>
                     <th>product_des</th>
                     <th>Created at</th>
                     <th>Action</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
      <!-- boostrap product model -->
      <div class="modal fade" id="product-modal" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" id="ProductModal"></h4>
               </div>
               <div class="modal-body">
                  <form action="javascript:void(0)" id="ProductForm" name="ProductForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                     <input type="hidden" name="id" id="id">
                     <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Product Name</label>
                        <div class="col-sm-12">
                           <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product_name" maxlength="50" required="">
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-12">
                           <input type="number" class="form-control" id="product_price" name="product_price" placeholder="Enter product price" required="">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-12">
                           <input type="text" class="form-control" id="product_desc" name="product_desc" placeholder="Enter product desc" required="">
                        </div>
                     </div>
                     <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save">Save changes
                        </button>
                     </div>
                  </form>
               </div>
               <div class="modal-footer"></div>
            </div>
         </div>
      </div>
      <!-- end bootstrap model -->
   </body>
   
   <script type = "text/javascript" >
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#ajax-crud-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('products') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'product_price',
                    name: 'product_price'
                },
                {
                    data: 'product_desc',
                    name: 'product_desc'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
    });

function add() {
    $('#ProductForm').trigger("reset");
    $('#ProductModal').html("Add Product");
    $('#product-modal').modal('show');
    $('#id').val('');
}

function editFunc(id) {
    $.ajax({
        type: "POST",
        url: "{{ url('product_edit') }}",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(res) {
            $('#ProductModal').html("Edit Company");
            $('#product-modal').modal('show');
            $('#id').val(res.id);
            $('#product_name').val(res.product_name);
            $('#product_price').val(res.product_price);
            $('#product_desc').val(res.product_desc);
        }
    });
}

function deleteFunc(id) {
    if (confirm("Delete Record?") == true) {
        var id = id;
        // ajax
        $.ajax({
            type: "POST",
            url: "{{ url('product_delete') }}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                var oTable = $('#ajax-crud-datatable').dataTable();
                oTable.fnDraw(false);
            }
        });
    }
}
$('#ProductForm').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: 'POST',
        url: "{{ url('product_store')}}",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#product-modal").modal('hide');
            var oTable = $('#ajax-crud-datatable').dataTable();
            oTable.fnDraw(false);
            $("#btn-save").html('Submit');
            $("#btn-save").attr("disabled", false);
        },
        error: function(data) {
            console.log(data);
        }
    });
}); 
</script>
</html>