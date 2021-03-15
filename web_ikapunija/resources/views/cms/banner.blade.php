<?php 
    $head = 'banner'; 
    $title = 'Banner';
    $url = env('APP_ASSET');
?>
@extends('layout.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1><?php echo $title; ?></h1>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="modal fade" id="modal-crud">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 id="modal-title" class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearField()"> 
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_submit"> 
                    <input type="hidden" class="form-custom" id="form_id">
                    <div class="form-group ves-custom" id="ves_delete" style="font-size: 1.3rem; text-align: center; margin-bottom: -10px;"> 
                        <label>Are You Sure To Delete This Data ?</label>
                    </div>
                    <div class="form-group ves-custom" id="ves_judul">
                        <label class="d-flex">Judul</label>
                        <input type="text" class="form-control form-custom" id="form_judul" placeholder="Enter Judul" maxlength="50">
                    </div>

                    <div class="form-group ves-custom" id="ves_desc_singkat">
                        <label class="d-flex">Deskripsi Singkat</label>
                        <input type="text" class="form-control form-custom" id="form_desc_singkat" placeholder="Enter Deskripsi Singkat">
                    </div>

                    <div class="form-group ves-custom" id="ves_link">
                        <label class="d-flex">Link</label>
                        <input type="text" class="form-control form-custom" id="form_link" placeholder="Enter Review Link">
                    </div>

                    <div class="form-group ves-custom" id="ves_foto">
                        <label class="d-flex">Foto <div class="cus_req" id="req_foto">&nbsp*</div></label>
                        <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-custom" id="form_foto" onChange="toBase64(this, 'ves_fotoImg')" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="form_foto">Pilih Foto</label>
                        </div>
                        <div class="input-group-append">
                            <span id="ves_fotoImg" class="input-group-text ves-custom" data-images="#" onclick="openImage(this)" style="cursor: pointer;">Lihat Foto</span>
                        </div>
                        </div>
                    </div>

                    <div class="form-group ves-custom" id="ves_priority">
                        <label class="d-flex">Priority <div class="cus_req" id="req_priority">&nbsp*</div></label>
                        <input type="number" class="form-control form-custom" id="form_priority" placeholder="Enter Priority" min="1" max="99999">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearField()">Close</button>
                <button type="button" class="btn btn-primary" id="submitModal" data-type="" onclick="submit(this)">Save</button>
            </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <div style="padding: 10px;">
                    <div class="row">
                        <div class="col-6 py-2 px-0">
                            <div class="btn-group float-sm-left ">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-crud" onclick="addModal()">
                                    New &nbsp; <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-6 py-2 px-0">
                            <div class="btn-group float-right ">
                                <button type="button" class="btn btn-info">Export</button>
                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="#" id="saveToPdf">PDF</a>
                                    <a class="dropdown-item" href="#" id="saveToExcel">Excel</a>
                                    <a class="dropdown-item" href="#" id="saveToPrint">Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="tablesList" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi Singkat</th>
                        <th>Banner</th>
                        <th>Link</th>
                        <th>Priority</th>
                        <th>Operation</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card s -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('page_script')
<!-- Page specific script -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    var table;
    var modal = new Array;
    modal['modalTitle'] = 'modal-title';
    modal['modalSubmit'] = 'submitModal';

    $(function () {
        //bs-file input
        bsCustomFileInput.init();

        //data table
        var export_column = [ 0, 1, 2 ];
        var export_fileName = '<?php echo $title; ?>';

        table = $("#tablesList").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "processing": true,
            "serverSide": true,
            "ajax": "banner" ,
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            success: function(data) { console.log('bisa'); },
            columns: [
                {data: 'DT_RowIndex', orderable: false},
                {data: 'judul'},
                {data: 'desc_singkat'},
                {
                    data: 'banner', 
                    orderable: false, 
                    searchable: false,
                    render:function(data, type, row){
                        return '<a href="<?php echo $url.'banner/'; ?>'+row.banner+'" target="_blank">Lihat Banner</a>';
                    }
                },
                {
                    data: 'link', 
                    orderable: false, 
                    searchable: false,
                    render:function(data, type, row){
                        if(row.link == null || row.link == '')
                        { return '-'; }

                        else
                        { return '<a href="'+row.link+'" target="_blank">Menuju Link</a>'; }
                    }
                },
                {data: 'priority'},
                {
                    data: 'id', 
                    orderable: false, 
                    searchable: false,
                    render:function(data, type, row){
                        return ''+
                            '<a  data-toggle="modal" data-target="#modal-crud" href="#" onclick="editModal(this)" data-id="'+row.id+'" style="margin: 0 5px;">'+
                                '<i class="fas fa-edit"></i> </a>'+
                            '<a  data-toggle="modal" data-target="#modal-crud" href="#" href="#" onclick="deleteModal(this)" data-id="'+row.id+'" style="margin: 0 5px;">'+
                                '<i class="fas fa-trash"></i> </a>'+
                        '';
                    }
                },
            ],
            buttons: [
                {  
                    extend: 'excel',
                    title: export_fileName,
                    exportOptions: {
                        columns: export_column
                    } 
                },
                {  
                    extend: 'pdf',
                    title: export_fileName,
                    exportOptions: {
                        columns: export_column
                    }
                },
                {  
                    extend: 'print',
                    title: export_fileName,
                    exportOptions: {
                        columns: export_column
                    }
                }
            ]
        });

        $("#saveToExcel").on("click", function() {
            table.button( '.buttons-excel' ).trigger();
        });

        $("#saveToPdf").on("click", function() {
            table.button( '.buttons-pdf' ).trigger();
        });

        $("#saveToPrint").on("click", function() {
            table.button( '.buttons-print' ).trigger();
        });
    });

    function addModal() {
        modal['mode'] = 'Add';
        modal['exclude'] = ['fotoImg', 'delete'];
        modal['exclude_req'] = ['judul', 'desc_singkat', 'link'];
        modeModal(modal);
    }
 
    function addData() {
       var input = {
            'banner': base64Img[1],
            'judul': $("#form_judul").val(),
            'desc_singkat': $("#form_desc_singkat").val(),
            'priority': $("#form_priority").val(),
            'link': $("#form_link").val() ,
       };

       $.ajax({
            type: "POST",
            url: '<?php echo env('APP_URL').'api/addBanner'; ?>',
            headers: {
                "apiToken":"<?php echo env('APPS_KEY'); ?>",
                "email":"<?php echo session('email'); ?>",
                "token":"<?php echo session('token'); ?>",
            },
            dataType:'JSON',
            data:input,
            success: function(data) {
                if(data.StatusCode == 404)
                { toastr["error"]('Please Fill Missing Field', "Error"); }
                
                else if(data.StatusCode == 400)
                 {window.location.replace("sign_out"); }

                else if(data.Error == true)
                { toastr["error"](data.Message, "Error"); }

                else
                {  
                    table.ajax.reload();
                    toastr["success"](data.Message, "Success"); 
                    $('#modal-crud').modal('hide');
                    clearField();
                }
            }
        });
    }
    
    function editModal(obj) {
        modal['mode'] = 'Edit';
        modal['exclude'] = ['delete'];
        modal['exclude_req'] = ['judul', 'desc_singkat', 'link', 'foto'];
        modeModal(modal);
        getDetail(obj);
    }

    function getDetail(obj) {
        var id = $(obj).data('id');
        $.ajax({
            type: "GET",
            url: '<?php echo env('APP_URL').'api/banner/'; ?>'+id,
            headers: {
                "apiToken":"<?php echo env('APPS_KEY'); ?>",
                "type":"web",
            },
            dataType:'JSON',
            success: function(data) {
                if(data.Error == true)
                { toastr["error"](data.Message, "Error"); }

                else
                { 
                    var cat = '';
                    if(data.Data.category != null)
                    { cat = data.Data.category.id; }

                    $("#form_judul").val(data.Data.judul);
                    $("#form_id").val(id);
                    $("#form_desc_singkat").val(data.Data.desc_singkat);
                    $("#form_priority").val(data.Data.priority);
                    $("#form_link").val(data.Data.link);
                    $("#ves_fotoImg").data('images', '<?php echo $url."banner/"; ?>'+data.Data.banner);
                }
            }
        });
    }
 
    function editData() {
       if(base64Img.length<=0)
       { base64Img[1] = ''; }

       var input = {
            'id': $("#form_id").val(),
            'banner': base64Img[1],
            'judul': $("#form_judul").val(),
            'desc_singkat': $("#form_desc_singkat").val(),
            'priority': $("#form_priority").val(),
            'link': $("#form_link").val() ,
       };

       $.ajax({
            type: "PUT",
            url: '<?php echo env('APP_URL').'api/editBanner'; ?>',
            headers: {
                "apiToken":"<?php echo env('APPS_KEY'); ?>",
                "email":"<?php echo session('email'); ?>",
                "token":"<?php echo session('token'); ?>",
            },
            dataType:'JSON',
            data:input,
            success: function(data) {
                if(data.StatusCode == 404)
                { toastr["error"]('Please Fill Missing Field', "Error"); }

                else if(data.StatusCode == 400)
                 {window.location.replace("sign_out"); }
                
                else if(data.Error == true)
                { toastr["error"](data.Message, "Error"); }

                else
                {  
                    table.ajax.reload();
                    toastr["success"](data.Message, "Success"); 
                    $('#modal-crud').modal('hide');
                    clearField();
                }
            }
        });
    }

    function deleteModal(obj) {
        modal['mode'] = 'Delete';
        modal['exclude'] = [];
        var id = $(obj).data('id');
        $("#form_id").val(id);
        modeModal(modal);        
    }

    function deleteData() {
       if(base64Img.length<=0)
       { base64Img[1] = ''; }

       var input = { 'id': $("#form_id").val(), };

       $.ajax({
            type: "DELETE",
            url: '<?php echo env('APP_URL').'api/delBanner'; ?>',
            headers: {
                "apiToken":"<?php echo env('APPS_KEY'); ?>",
                "email":"<?php echo session('email'); ?>",
                "token":"<?php echo session('token'); ?>",
            },
            dataType:'JSON',
            data:input,
            success: function(data) {
                if(data.StatusCode == 404)
                { toastr["error"]('Please Fill Missing Field', "Error"); }

                else if(data.StatusCode == 400)
                 {window.location.replace("sign_out"); }
                
                else if(data.Error == true)
                { toastr["error"](data.Message, "Error"); }

                else
                {  
                    table.ajax.reload();
                    toastr["success"](data.Message, "Success"); 
                    $('#modal-crud').modal('hide');
                    clearField();
                }
            }
        });
    }

    function clearField() {
        $("#form_submit").trigger("reset");
        $("#form_category").val('').trigger('change');
        $("#form_isi").summernote("code", '');
        base64Img = '';
    }
</script>
@endsection