<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Products</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
         <div id="id_msg"></div>
         <div class="card-body">
            <table class="table table-bordered" id="ajax-crud-datatable">
               <thead>
                  <tr>
                     <th>Name</th>
                     <th>Price</th>
                     <th>Description</th>                     
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
                    <div id="id_model_msg"></div>
                  <form action="javascript:void(0)" id="ProductForm" name="ProductForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                     <input type="hidden" name="id" id="id">
                     <div class="form-group">
                        <label for="name" class="col-sm-12 control-label">Name *</label>
                        <div class="col-sm-12">
                           <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" maxlength="50" required="">
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="name" class="col-sm-12 control-label">Price *</label>
                        <div class="col-sm-12">
                           <input type="number"  step=".01" min="0" max="1000000" class="form-control" id="product_price" name="product_price" placeholder="Enter product price" required="">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Description *</label>
                        <div class="col-sm-12">
                           <textarea  class="form-control"  id="product_desc" name="product_desc" placeholder="Enter product desc" required=""></textarea>
                        </div>
                     </div>
                  
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-12">
                                <input type="file" name="files[]"  accept=".jpg,.jpeg,.png" id="files" placeholder="Choose files" multiple >
                                <br><span ><strong>Notes :valid extensions are .jpg,.jpeg,.png. All file size must be < 2MB<strong></span>
                            </div>
                        
                        
                    </div>
                    <div id="product_image_html" class='form-group row'></div>

                     <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save">Save changes
                        </button>
                         <button type="button" class="btn btn-primary" id="btn-cancel">Cancel
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
            columns: [
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
        $("#id_model_msg").removeClass("alert alert-success alert-danger");
        $("#id_model_msg").html("");   
        $('#product_image_html').html("");
   
    }

    function editFunc(id) {
        $("#id_model_msg").removeClass("alert alert-success alert-danger");
        $("#id_model_msg").html("");      
        $.ajax({
            type: "POST",
            url: "{{ url('product_edit') }}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {               
                $('#ProductModal').html("Edit Product");
                $('#product-modal').modal('show');
                $('#id').val(res.product.id);
                $('#product_name').val(res.product.product_name);
                $('#product_price').val(res.product.product_price);
                $('#product_desc').val(res.product.product_desc);
                $('#product_image_html').html(res.product_image_data);
            }
        });
    }

    function deleteFunc(id) {
        $("#id_msg").removeClass("alert alert-success alert-danger");
        $("#id_msg").html("");
        if (confirm("Are you sure to delete product?") == true) {
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
                    $("#id_msg").addClass("alert alert-success");
                    $("#id_msg").html("Product successfully deleted");
                    var oTable = $('#ajax-crud-datatable').dataTable();
                    oTable.fnDraw(false);
                }
            });
        }
    }
    $('#ProductForm').submit(function(e) 
    {
         $("#id_msg").removeClass("alert alert-success alert-danger");

        e.preventDefault();
        var formData = new FormData(this);        
        let TotalFiles = $('#files')[0].files.length; //Total files
        let files = $('#files')[0];

        for (let i = 0; i < TotalFiles; i++) 
        {           
            if(files.files[i].size > 2000000) 
            {               
                $("#id_model_msg").addClass("alert alert-danger");
                $('#id_model_msg').html("All files size must be < 2MB");    
                return false;
            }               
        }           

        for (let i = 0; i < TotalFiles; i++) {
            formData.append('files' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);

        $.ajax({
            type: 'POST',
            url: "{{ url('product_store')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: (data) => {
                // data=$.parseJSON(data);                 
                 $("#id_msg").addClass("alert alert-success");
                $("#id_msg").html(data.message);
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

    
    $('#btn-cancel').click(function(e) {
        $("#product-modal").modal('hide');
    });

 

    function delete_image(img)
    {    
        $("#id_model_msg").removeClass("alert alert-success alert-danger");
        $("#id_model_msg").html("");      
        if(confirm("Are you sure to delete image?"))  
        {
            $.ajax({
                type: "GET",
                url: "{{ url('image_delete') }}/"+img,
                
                dataType: 'json',
                success: function(res) {
                    $("#image_"+img).html("");
                    $("#id_model_msg").addClass("alert alert-success");
                    $('#id_model_msg').html(res.message);                                
                    $('#image_'+img).hide();     
                }
            });
        }
        return false;         
    }
</script>
</html>