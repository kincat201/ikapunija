<?php 
    $head = 'stk'; 
    $sec = 'stk_list';
    $multi = 'yes'; 
    $title = 'Struktur Organisasi';
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
                <h4 id="modal-title" class="modal-title">Struktur Organisasi</h4>
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
                        <label class="d-flex">Nama <div class="cus_req" id="req_judul">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_nama" placeholder="Enter Judul" maxlength="120">
                    </div>

                    <div class="form-group ves-custom" id="ves_jabatan">
                        <label class="d-flex">Jabatan <div class="cus_req" id="req_jabatan">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_nama_jabatan" placeholder="Enter Jabatan" maxlength="120">
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

                    <div class="form-group ves-custom" id="ves_judul">
                        <label class="d-flex">Level Struktur <div class="cus_req" id="req_level">&nbsp*</div></label>
                        <select class="form-control select2 form-custom" id="form_level" style="width: 100%;">
                            <option value="">- Pilih Level -</option>
                        </select>
                    </div>

                    <div class="form-group ves-custom" id="ves_email">
                        <label class="d-flex">Email</label>
                        <input type="email" class="form-control form-custom" id="form_email" placeholder="Enter Email" maxlength="120">
                    </div>

                    <div class="form-group ves-custom" id="ves_fb">
                        <label class="d-flex">Facebook</label>
                        <input type="text" class="form-control form-custom" id="form_fb" placeholder="Enter Facebook" maxlength="120">
                    </div>

                    <div class="form-group ves-custom" id="ves_ig">
                        <label class="d-flex">Instagram</label>
                        <input type="text" class="form-control form-custom" id="form_ig" placeholder="Enter Instagram" maxlength="120">
                    </div>

                    <div class="form-group ves-custom" id="ves_twitter">
                        <label class="d-flex">Twitter</label>
                        <input type="text" class="form-control form-custom" id="form_twitter" placeholder="Enter Twitter" maxlength="120">
                    </div>

                    <div class="form-group ves-custom" id="ves_linkedin">
                        <label class="d-flex">Linkedin</label>
                        <input type="text" class="form-control form-custom" id="form_linkedin" placeholder="Enter Linkedin" maxlength="120">
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
                        <th>Nama</th>
                        <th>Nama Jabatan</th>
                        <th>Foto</th>
                        <th>Level</th>
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

        //Initialize Select2 Elements
        $('.select2').select2({ theme: 'bootstrap4' });

        getLevel();

        //data table
        var export_column = [ 0, 1, 2, 4 ];
        var export_fileName = '<?php echo $title; ?>';

        table = $("#tablesList").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "processing": true,
            "serverSide": true,
            "ajax": "struktur",
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false},
                {data: 'nama'},
                {data: 'nama_jabatan'},
                {
                    data: 'foto', 
                    orderable: false, 
                    searchable: false,
                    render:function(data, type, row){
                        return '<a href="<?php echo $url.'foto_struktur/'; ?>'+row.foto+'" target="_blank">Lihat Foto</a>';
                    }
                },
                {data: 'levelName'},
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
        modal['exclude_req'] = [];
        modeModal(modal);
    }
 
    function addData() {
       var input = {
            'foto': base64Img[1],
            'nama': $("#form_nama").val(),
            'nama_jabatan': $("#form_nama_jabatan").val(),
            'level': $("#form_level").val(),
            'email': $("#form_email").val(),
            'fb': $("#form_fb").val(),
            'ig': $("#form_ig").val(),
            'twitter': $("#form_twitter").val(),
            'linkedin': $("#form_linkedin").val(),
       };

       $.ajax({
            type: "POST",
            url: '<?php echo env('APP_URL').'api/addStruktur'; ?>',
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
        modal['exclude_req'] = ['foto'];
        modeModal(modal);
        getDetail(obj);
    }

    function getDetail(obj) {
        var id = $(obj).data('id');
        $.ajax({
            type: "GET",
            url: '<?php echo env('APP_URL').'api/struktur/'; ?>'+id,
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
                    var detail = data.Data;
                    $("#form_id").val(id);
                    $("#form_nama").val(detail.nama);
                    $("#form_nama_jabatan").val(detail.nama_jabatan);
                    $("#form_level").val(detail.level.id).trigger('change');
                    $("#form_email").val(detail.email);
                    $("#form_fb").val(detail.fb);
                    $("#form_ig").val(detail.ig);
                    $("#form_twitter").val(detail.twitter);
                    $("#form_linkedin").val(detail.linkedin);
                    $("#ves_fotoImg").data('images', '<?php echo $url."struktur_organisasi/"; ?>'+detail.foto);
                }
            }
        });
    }
 
    function editData() {
       if(base64Img.length<=0)
       { base64Img[1] = ''; }

       var input = {
            'id': $("#form_id").val(),
            'foto': base64Img[1],
            'nama': $("#form_nama").val(),
            'nama_jabatan': $("#form_nama_jabatan").val(),
            'level': $("#form_level").val(),
            'email': $("#form_email").val(),
            'fb': $("#form_fb").val(),
            'ig': $("#form_ig").val(),
            'twitter': $("#form_twitter").val(),
            'linkedin': $("#form_linkedin").val(),
       };

       $.ajax({
            type: "PUT",
            url: '<?php echo env('APP_URL').'api/editStruktur'; ?>',
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
            url: '<?php echo env('APP_URL').'api/delStruktur'; ?>',
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
        $("#form_level").val('').trigger('change');
        base64Img = '';
    }

    function getLevel() {
        $.ajax({
            type: "GET",
            url: '<?php echo env('APP_URL').'api/strukturLevel'; ?>',
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
                    var cat = data.Data;
                    var select_data = '<option value="">- Pilih Level-</option>';
                    for(var i=0; i<cat.length; i++)
                    { select_data += '<option value="'+cat[i].id+'">'+cat[i].nama_level+'( '+cat[i].type_level.toUpperCase()+' )</option>'; }

                    $('#form_level').html(select_data);
                }
            }
        });
    }

</script>
@endsection