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
         <div id="id_msg"></div>
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
                  
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-12">
                                <input type="file" name="files[]"  accept=".jpg,.jpeg,.png" id="files" placeholder="Choose files" multiple >
                            </div>
                        <span>Notes :valid extensions are .jpg,.jpeg,.png. All file size must be < 2MB</span>
                        <div id="product_image_html">JJJ</div>
                    </div>

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
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

    function delete_image(img)
    {
        //  $.ajax({
        //     type: "GET",
        //     url: "{{ url('image_delete') }}/".img,
            
        //     dataType: 'json',
        //     success: function(res) {
        //         // $('#ProductModal').html("Edit Product");
        //         // $('#product-modal').modal('show');
        //         // $('#id').val(res.id);
        //         // $('#product_name').val(res.product_name);
        //         // $('#product_price').val(res.product_price);
        //         // $('#product_desc').val(res.product_desc);
        //         // $('#product_image_html').html(res.product_image_data);
        //     }
        // });
    }
    $(document).ready(function() {

            
    
     $(".delete_img").on('click', function(event){

        alert(1);
               //alert($(this).data( "image_id"));
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
            if(files.files[i].size > 2000000) {
                alert("All file size must be < 2MB");
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

    $('#multi-file-upload-ajax').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let TotalFiles = $('#files')[0].files.length; //Total files
        let files = $('#files')[0];

        for (let i = 0; i < TotalFiles; i++) 
        {           
            if(files.files[i].size > 2000000) {
                alert("All file size must be < 2MB");
                return false;
            }               
        }   
         

        for (let i = 0; i < TotalFiles; i++) {
            formData.append('files' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);
        $.ajax({
            type: 'POST',
            url: "{{ url('store-multi-file-ajax')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: (data) => {
                this.reset();
                alert('Files has been uploaded using jQuery ajax');
            },
            error: function(data) {
                alert(data.responseJSON.errors.files[0]);
                console.log(data.responseJSON.errors);
            }
        });
    });

</script>
</html>