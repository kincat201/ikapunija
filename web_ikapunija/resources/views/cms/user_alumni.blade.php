<?php 
    $head = 'user_alumni'; 
    $title = 'User Alumni';
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
                <h4 id="modal-title" class="modal-title">UserAlumni</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearField()"> 
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_submit" action="javascript:void(0);"> 
                    <input type="hidden" class="form-custom" id="form_id">
                    <div class="form-group ves-custom" id="ves_delete" style="font-size: 1.3rem; text-align: center; margin-bottom: -10px;"> 
                        <label>Are You Sure To Delete This Data ?</label>
                    </div>
                    <div class="form-group ves-custom" id="ves_nama">
                        <label class="d-flex">Nama Alumni <div class="cus_req" id="req_nama">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_nama" placeholder="Enter Nama Alumni">
                    </div>

                    <div class="form-group ves-custom" id="ves_email">
                        <label class="d-flex">Email <div class="cus_req" id="req_email">&nbsp*</div></label>
                        <input type="email" class="form-control form-custom" id="form_email" placeholder="Enter Email" maxlength="100">
                    </div>

                    <div class="form-group ves-custom" id="ves_pass">
                        <label class="d-flex">Password <div class="cus_req" id="req_pass">&nbsp*</div></label>
                        <input type="password" class="form-control form-custom" id="form_pass" placeholder="Enter Password" maxlength="25">
                    </div>

                    <div class="form-group ves-custom" id="ves_conf_pass">
                        <label class="d-flex">Konfirmasi Password <div class="cus_req" id="req_conf_pass">&nbsp*</div></label>
                        <input type="password" class="form-control form-custom" id="form_conf_pass" placeholder="Enter Konfirmasi Password" maxlength="25">
                    </div>

                    <div class="form-group ves-custom" id="ves_kontak">
                        <label class="d-flex">Kontak <div class="cus_req" id="req_kontak">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_kontak" placeholder="Enter Kontak" maxlength="20"> 
                    </div>

                    <div class="form-group ves-custom" id="ves_alamat">
                        <label class="d-flex">Alamat <div class="cus_req" id="req_alamat">&nbsp*</div></label>
                        <textarea id="form_alamat" class="form-control form-custom" ></textarea>
                    </div>

                    <div class="form-group ves-custom" id="ves_angkatan">
                        <label class="d-flex">Angkatan <div class="cus_req" id="req_angkatan">&nbsp*</div></label>
                        <input type="number" class="form-control form-custom" id="form_angkatan" placeholder="Enter Angkatan" min="1900" max="9999">
                    </div>

                    <div class="form-group ves-custom" id="ves_negara">
                        <label class="d-flex">Negara <div class="cus_req" id="req_negara">&nbsp*</div></label>
                        <select class="form-control select2 form-custom" id="form_negara" style="width: 100%;">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <div class="form-group ves-custom" id="ves_hobi">
                        <label class="d-flex">Hobi</label>
                        <input type="text" class="form-control form-custom" id="form_hobi" placeholder="Enter Kontak" maxlength="100">
                    </div>

                    <div class="form-group ves-custom" id="ves_profesi">
                        <label class="d-flex">Profesi <div class="cus_req" id="req_profesi">&nbsp*</div></label>
                        <select class="form-control select2 form-custom" id="form_profesi" onchange="get_prof()" style="width: 100%;">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <div class="form-group ves-custom" id="ves_jabatan">
                        <label class="d-flex">Nama Jabatan <div class="cus_req" id="req_jabatan">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_jabatan" placeholder="Enter Nama Jabatan" maxlength="100">
                    </div>

                    <div class="form-group ves-custom" id="ves_pegawai">
                        <label class="d-flex">Jumlah Pegawai <div class="cus_req" id="req_pegawai">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_pegawai" placeholder="Enter Jumlah Pegawai" maxlength="10">
                    </div>

                    <div class="form-group ves-custom" id="ves_pendapatan">
                        <label class="d-flex">Pendapatan <div class="cus_req" id="req_pendapatan">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_pendapatan" placeholder="Enter Pendapatan" maxlength="50">
                    </div>

                    <div class="form-group ves-custom" id="ves_pp">
                        <label class="d-flex">Foto Profil <div class="cus_req" id="req_pp">&nbsp*</div></label>
                        <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-custom" id="form_pp" onChange="toBase64(this, 'ves_ppImg')" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="form_foto">Pilih Foto</label>
                        </div>
                        <div class="input-group-append">
                            <span id="ves_ppImg" class="input-group-text ves-custom" data-images="#" onclick="openImage(this)" style="cursor: pointer;">Lihat Foto</span>
                        </div>
                        </div>
                    </div>

                    <div class="form-group ves-custom" id="ves_ktp">
                        <label class="d-flex">Foto KTP <div class="cus_req" id="req_ktp">&nbsp*</div></label>
                        <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-custom" id="form_ktp" onChange="toBase64_2(this, 'ves_ktpImg')" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="form_foto">Pilih Foto</label>
                        </div>
                        <div class="input-group-append">
                            <span id="ves_ktpImg" class="input-group-text ves-custom" data-images="#" onclick="openImage(this)" style="cursor: pointer;">Lihat Foto</span>
                        </div>
                        </div>
                    </div>

                    <div class="form-group ves-custom" id="ves_nik">
                        <label class="d-flex">NIK <div class="cus_req" id="req_nik">&nbsp*</div></label>
                        <input type="text" class="form-control form-custom" id="form_nik" placeholder="Enter NIK" maxlength="20">
                    </div>

                    <div class="form-group ves-custom" id="ves_jurusan">
                        <label class="d-flex">Jurusan <div class="cus_req" id="req_jurusan">&nbsp*</div></label>
                        <select class="form-control select2 form-custom" id="form_jurusan" onChange="getProdi();" style="width: 100%;">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <div class="form-group ves-custom" id="ves_prodi">
                        <label class="d-flex">Program Studi <div class="cus_req" id="req_prodi">&nbsp*</div></label>
                        <select class="form-control select2 form-custom" id="form_prodi" style="width: 100%;" disabled>
                            <option value="">Pilih</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearField()">Close</button>
                <button class="btn btn-primary" id="submitModal" data-type="" onclick="submit(this)">Save</button>
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
                        <th>Email</th>
                        <th>Angkatan</th>
                        <th>Jurusan</th>
                        <th>Prodi</th>
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
    var base64Img_2 = [];
    var token = "<?php echo env('APPS_KEY'); ?>";
    var modal = new Array;
    modal['modalTitle'] = 'modal-title';
    modal['modalSubmit'] = 'submitModal';

    //select profesi
    getProfesi();
        
    //select jurusan
    getJurusan();

    //select negara
    getNegara();

    $(function () {
        $('#ves_jabatan').hide(); 
        $('#ves_pegawai').hide(); 

        //bs-file input
        bsCustomFileInput.init();

        //Initialize Select2 Elements
        $('.select2').select2({ theme: 'bootstrap4' });

        //data table
        var export_column = [ 0, 1, 2, 4, 5 ];
        var export_fileName = '<?php echo $title; ?>';

        table = $("#tablesList").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "processing": true,
            "serverSide": true,
            "ajax": "user_alumni",
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false},
                {data: 'nama_alumni'},
                {data: 'email'},
                {data: 'angkatan'},
                {data: 'jurusan_name'},
                {data: 'prodi_name'},
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
        modal['exclude'] = ['ktpImg', 'ppImg', 'delete'];
        modal['exclude_req'] = [];
        $('#form_email').prop('disabled', false);
        modeModal(modal);
    }
 
    function addData() {
        var pp = ktp = '';
        
        if(base64Img.length > 0)
        { pp = base64Img_2[1]; }

        if(base64Img_2.length > 0)
        { ktp = base64Img[1]; }

        var input = {            
            'foto_ktp': ktp,
            'foto_profil': pp,
            'email': $("#form_email").val(),
            'nama_alumni': $("#form_nama").val(),
            'password': $("#form_pass").val(),
            'conf_pass': $("#form_conf_pass").val(),
            'contact': $("#form_kontak").val(),
            'alamat': $("#form_alamat").val(),
            'angkatan': $("#form_angkatan").val(),
            'negara_id': $("#form_negara").val(),
            'hobi': $("#form_hobi").val(),
            'profesi_id': $("#form_profesi").val(),
            'nama_profesi': $("#form_jabatan").val(),
            'jumlah_pegawai': $("#form_pegawai").val(),
            'pendapatan': $("#form_pendapatan").val(),
            'nik': $("#form_nik").val(),
            'jurusan_id': $("#form_jurusan").val(),
            'prodi_id': $("#form_prodi").val(),
            'mode':'admin'
       };

       if(input['profesi_id'] == 1)
       { input['nama_profesi'] = '-'; }

       else
       { input['jumlah_pegawai'] = '-'; }

       if(input['conf_pass'] != input['password'])
       { toastr["error"]('Password dan Konfirmasi Password Tidak Sesuai', "Error"); }

       else
       {
            $.ajax({
                type: "POST",
                url: '<?php echo env('APP_URL').'api/register'; ?>',
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
    }

    function editModal(obj) {
        modal['mode'] = 'Edit';
        modal['exclude'] = ['delete'];
        modal['exclude_req'] = ['conf_pass', 'pass', 'pp', 'ktp'];
        $('#form_email').prop('disabled', true);
        modeModal(modal);
        getDetail(obj);
    }

    function getDetail(obj) {
        var id = $(obj).data('id');
        $.ajax({
            type: "GET",
            url: '<?php echo env('APP_URL').'api/userAlumni/'; ?>'+id,
            headers: {
                "apiToken":"<?php echo env('APPS_KEY'); ?>",
                "email":"<?php echo session('email'); ?>",
                "token":"<?php echo session('token'); ?>",
                "type":"admin",
            },
            dataType:'JSON',
            success: function(data) {
                if(data.Error == true)
                { toastr["error"](data.Message, "Error"); }

                else
                {   
                    if(data.Data.jurusan_id != null)
                    {getProdi(data.Data.jurusan_id);}

                    $("#form_id").val(data.Data.id);
                    $("#form_email").val(data.Data.email);
                    $("#form_nama").val(data.Data.nama_alumni);
                    $("#form_alamat").val(data.Data.alamat);
                    $("#form_kontak").val(data.Data.contact),
                    $("#form_angkatan").val(data.Data.angkatan);
                    $("#form_negara").val(data.Data.negara_id).trigger('change');
                    $("#form_hobi").val(data.Data.hobi);
                    $("#form_profesi").val(data.Data.profesi_id).trigger('change');
                    $("#form_jabatan").val(data.Data.nama_profesi);
                    $("#form_pegawai").val(data.Data.jumlah_pegawai);
                    $("#form_pendapatan").val(data.Data.pendapatan);
                    $("#form_nik").val(data.Data.nik);
                    $("#form_jurusan").val(data.Data.jurusan_id).trigger('change');
                    $("#form_prodi").val(data.Data.prodi_id).trigger('change');
                    $("#ves_ktpImg").data('images', '<?php echo $url."user_alumni/ktp/"; ?>'+data.Data.foto_ktp);
                    $("#ves_ppImg").data('images', '<?php echo $url."user_alumni/profil/"; ?>'+data.Data.foto_profil);
                }
            }
        });
    }
 
    function editData() {
        var pp = ktp = '';
        
        if(base64Img.length > 0)
        { pp = base64Img_2[1]; }

        if(base64Img_2.length > 0)
        { ktp = base64Img[1]; }

        var input = {            
            'foto_ktp': ktp,
            'foto_profil': pp,
            'nama_alumni': $("#form_nama").val(),
            'id': $("#form_id").val(),
            'password': $("#form_pass").val(),
            'conf_pass': $("#form_conf_pass").val(),
            'contact': $("#form_kontak").val(),
            'alamat': $("#form_alamat").val(),
            'angkatan': $("#form_angkatan").val(),
            'negara_id': $("#form_negara").val(),
            'hobi': $("#form_hobi").val(),
            'profesi_id': $("#form_profesi").val(),
            'nama_profesi': $("#form_jabatan").val(),
            'jumlah_pegawai': $("#form_pegawai").val(),
            'pendapatan': $("#form_pendapatan").val(),
            'nik': $("#form_nik").val(),
            'jurusan_id': $("#form_jurusan").val(),
            'prodi_id': $("#form_prodi").val(),
            'mode':'admin'
       };

       if(input['profesi_id'] == 1)
       { input['nama_profesi'] = '-'; }

       else
       { input['jumlah_pegawai'] = '-'; }

       if(input['conf_pass'] != input['password'])
       { toastr["error"]('Password dan Konfirmasi Password Tidak Sesuai', "Error"); }

       $.ajax({
            type: "PUT",
            url: '<?php echo env('APP_URL').'api/updateProfile'; ?>',
            headers: {
                "apiToken":"<?php echo env('APPS_KEY'); ?>",
                "email":"<?php echo session('email'); ?>",
                "token":"<?php echo session('token'); ?>",
                "mode":"admin",
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
            url: '<?php echo env('APP_URL').'api/deleteDeclineAlumni'; ?>',
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
        $("#form_alamat").summernote("code", '');
        base64Img = '';
    }

    function getNegara() {
        $.ajax({
            type: "GET",
            url: 'https://restcountries.eu/rest/v2/all',
            dataType:'JSON',
            async: false,
            success: function(data) {
                var cat = data;
                var select_data = '<option value="">- Pilih Negara -</option>';
                for(var i=0; i<cat.length; i++)
                { select_data += '<option value="'+cat[i].alpha2Code+'">'+cat[i].name+'</option>'; }

                $('#form_negara').html(select_data);
            }
        });
    }

    function getJurusan() {
        $.ajax({
            type: "GET",
            url: '<?php echo env('APP_URL').'api/jurusan'; ?>',
            async : false,
            headers: {
                "apiToken":token,
                "type":"web"
            },
            dataType:'JSON',
            success: function(data) {
                if(data.Error == true)
                { toastr["error"](data.Message, "Error"); console.log('jurusan'); }

                else
                { 
                    var cat = data.Data;
                    var select_data = '<option value="">- Pilih Jurusan -</option>';
                    for(var i=0; i<cat.length; i++)
                    { select_data += '<option value="'+cat[i].id+'">'+cat[i].nama_jurusan+'</option>'; }

                    $('#form_jurusan').html(select_data);
                }
            }
        });
    }

    function getProfesi() {
        $.ajax({
            type: "GET",
            url: '<?php echo env('APP_URL').'api/profesi'; ?>',
            async : false,
            headers: {
                "apiToken":token,
                "type":"web"
            },
            dataType:'JSON',
            success: function(data) {
                if(data.Error == true)
                { toastr["error"](data.Message, "Error"); console.log('profesi'); }

                else
                { 
                    var cat = data.Data;
                    var select_data = '<option value="">- Pilih Profesi -</option>';
                    for(var i=0; i<cat.length; i++)
                    { select_data += '<option value="'+cat[i].id+'">'+cat[i].nama_profesi+'</option>'; }

                    $('#form_profesi').html(select_data);
                }
            }
        });
    }

    function getProdi(data = 0) {
        var jurusan;

        if(data == 0)
        { jurusan = $('#form_jurusan').val(); }

        else
        { jurusan = data; }
        $('#form_prodi').prop("disabled", false);
        $.ajax({
            type: "GET",
            async: false,
            url: '<?php echo env('APP_URL').'api/prodi'; ?>/'+jurusan,
            headers: {
                "apiToken":token,
                "type":"web",
            },
            dataType:'JSON',
            success: function(data) {
                if(data.Error == true)
                { toastr["error"](data.Message, "Error"); }

                else
                { 
                    var cat = data.Data;
                    var select_data = '<option value="">- Pilih Program Studi -</option>';
                    for(var i=0; i<cat.length; i++)
                    { select_data += '<option value="'+cat[i].id+'">'+cat[i].nama_prodi+'</option>'; }

                    $('#form_prodi').html(select_data);
                }
            }
        });
    }
    
    function get_prof() {
        var profesi = $('#form_profesi').val();

        if(profesi == '1')
        { 
            $('#ves_jabatan').hide(); 
            $('#ves_pegawai').show(); 
        }
        
        else
        { 
            $('#ves_jabatan').show(); 
            $('#ves_pegawai').hide(); 
        }
    }

    function toBase64_2(element, obj) {
        var img = element.files[0];
        
        var reader = new FileReader();
        
        reader.onloadend = function() {
            $("#"+obj).data('images',reader.result);
            base64Img_2 = reader.result.split(",");
            $("#"+obj).show();
        }
        reader.readAsDataURL(img);
    }

</script>
@endsection