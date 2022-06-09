<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>{{$title}}</h2>
                <div class="d-flex flex-row-reverse"><button
                        class="btn btn-sm btn-pill btn-outline-primary font-weight-bolder" id="createNewUser"><i
                            class="fas fa-plus"></i>Add data </button></div>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="tableUser">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    {{-- <th>No.</th> --}}
                                    <th>Id Kategori</th>
                                    <th>Id Sub Kategori</th>
                                    <th>Kategori</th>
                                    <th>Sub Kategori</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Harga</th>
                                    <th>Foto</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th style="width:90px;">Action</th>
                                </tr>
                                
                            </thead>
                            <tbody class="text-center">
                                {{-- @foreach ($users as $r_users)
                                    <tr>
                                <td>{{$r_users->id}}</td>
                                <td>{{$r_users->name}}</td>
                                <td>{{$r_users->email}}</td>
                                <td>{{$r_users->level}}</td>
                                <td>
                                    <div class="btn btn-success editUser" data-id="{{$r_users->id}}">Edit</div>
                                    <div class="btn btn-danger deleteUser" data-id="{{$r_users->id}}">Delete</div>
                                </td>
                                </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal-->
<div class="modal fade" id="modal-user" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUser" name="formUser" enctype="multipart/form-data" action="{{ route('produk.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="number" name="idkategori" class="form-control" id="idkategori" placeholder="Id Kategori"><br>
                        <input type="number" name="idsubkategori" class="form-control" id="idsubkategori" placeholder="Id Sub Kategori"><br>
                        <select name="kategori" class="form-control" id="kategori">
                            <option value="-">Kategori</option>
                            <option value="1">Pewangi</option>
                            <option value="2">Sabun Cuci</option>
                        </select><br>
                        <select name="subkategori" class="form-control" id="subkategori">
                            <option value="-">Sub Kategori</option>
                            <option value="1">Pewangi Pakaian</option>
                            <option value="2">Sabun Cuci Baju</option>
                        </select><br>
                        <input type="text" name="judul" class="form-control" id="judul" placeholder="Judul"><br>
                        <input type="text" name="deskripsi" class="form-control" id="deskripsi" placeholder="Deskripsi"><br>
                        <input type="number" name="harga" class="form-control" id="harga" placeholder="Harga"><br>
                        <input type="file" name="thumbnail" class="form-control" id="thumbnail" placeholder="Foto"><br>
                        <input type="number" name="st" class="form-control" id="st" placeholder="Jumlah"><br>
                        <input type="text" name="satuan" class="form-control" id="satuan" placeholder="Satuan"><br>
                        <input type="hidden" name="user_id" id="user_id" value="">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary font-weight-bold" id="saveBtn">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>



@push('scripts')
<script>
    $('document').ready(function () {
        // success alert
        function swal_success() {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1000
            })
        }
        // error alert
        function swal_error() {
            Swal.fire({
                position: 'centered',
                icon: 'error',
                title: 'Something goes wrong !',
                showConfirmButton: true,
            })
        }
        // table serverside
        var table = $('#tableUser').DataTable({
            processing: false,
            serverSide: true,
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ],
            ajax: "{{ route('produk.index') }}",
            columns: [{
                    data: 'idkategori',
                    name: 'idkategori'
                },
                {
                    data: 'idsubkategori',
                    name: 'subkategori'
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'subkategori',
                    name: 'subkategori'
                },
                {
                    data: 'judul',
                    name: 'judul'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                {
                    data: 'harga',
                    name: 'harga'
                },
                {
                    data: 'thumbnail',
                    name: 'thumbnail'
                },
                {
                    data: 'st',
                    name: 'st'
                },
                {
                    data: 'satuan',
                    name: 'satuan'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        
        // csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // initialize btn add
        $('#createNewUser').click(function () {
            $('#saveBtn').val("tambah produk");
            $('#user_id').val('');
            $('#formUser').trigger("reset");
            $('#modal-user').modal('show');
        });
        // initialize btn edit
        $('body').on('click', '.editUser', function () {
            var user_id = $(this).data('id');
            $.get("{{route('produk.index')}}" + '/' + user_id + '/edit', function (data) {
                $('#saveBtn').val("edit-user");
                $('#modal-user').modal('show');
                $('#user_id').val(data.id);
                $('#idkategori').val(data.idkategori);
                $('#idsubkategori').val(data.idsubkategori);
                $('#kategori').val(data.kategori);
                $('#subkategori').val(data.subkategori);
                $('#judul').val(data.judul);
                $('#deskripsi').val(data.deskripsi);
                $('#harga').val(data.harga);
                // $('#thumbnail').val(data.thumbnail);
                $('#st').val(data.st);
                $('#satuan').val(data.satuan);
            })
        });

        $(function(){
            $('#formUser').on('submit', function(e){
                e.preventDefault();
                
                var form = this;
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){

                    },
                    success:function(data){
                        if(data.code == 0 ){
                            swal_error();
                            $('#saveBtn').html('Save Changes');
                        }
                        else{
                            $('#formUser').trigger("reset");
                            $('#modal-user').modal('hide');
                            swal_success();
                            table.draw();
                        }
                    }
                });
            })
        });
        // initialize btn save
        // $('#saveBtn').click(function (e) {
        //        var idkategori = $('#idkategori').val();
        //        var idsubkategori = $('#idsubkategori').val();
        //        var kategori = $('#kategori').val();
        //        var subkategori = $('#subkategori').val();
        //        var judul = $('#judul').val();
        //        var deskripsi =$('#deskripsi').val();
        //        var harga = $('#harga').val();
        //        var thumbnail = $('#thumbnail').val();
        //        var st = $('#st').val();
        //        var satuan = $('#satuan').val();
        //     // console.log(foto);
        //     e.preventDefault();
        //     $(this).html('Save');
        //     $.ajax({
                
        //         data: $('#formUser').serialize(),
        //         url: "{{ route('produk.store') }}",
        //         type: "POST",
        //         data: {
        //             idkategori:idkategori,
        //             idsubkategori:idsubkategori,
        //             kategori:kategori,
        //             subkategori:subkategori,
        //             judul:judul,
        //             deskripsi:deskripsi,
        //             harga:harga,
        //             thumbnail:thumbnail,
        //             st:st,
        //             satuan:satuan,
        //         },
        //         success: function (data) {
        //             console.log(data);
        //             $('#formUser').trigger("reset");
        //             $('#modal-user').modal('hide');
        //             swal_success();
        //             table.draw();

        //         },
        //         error: function (data) {
        //             console.log(data);
        //             swal_error();
        //             $('#saveBtn').html('Save Changes');
        //         }
        //     });

        // });
        // initialize btn delete
        $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data("id");

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{route('produk.store')}}" + '/' + user_id,
                        success: function (data) {
                            swal_success();
                            table.draw();
                        },
                        error: function (data) {
                            swal_error();
                        }
                    });
                }
            })
        });

        // statusing


    });

</script>
@endpush
