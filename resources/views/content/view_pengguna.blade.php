<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>{{$title}}</h2>
                <div class="d-flex flex-row-reverse"><button
                        class="btn btn-sm btn-pill btn-outline-primary font-weight-bolder" id="createNewUser"><i
                            class="fas fa-plus"></i>add data </button></div>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="tableUser">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    {{-- <th>No.</th> --}}
                                    <th>No Telpon</th>
                                    <th>Password</th>
                                    <th>Nama</th>
                                    <th>Level</th>
                                    <th>email</th>
                                    <th>Foto</th>
                                    <th style="width:90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                              
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
                <h5 class="modal-title text-white" id="exampleModalLabel">Modal User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUser" name="formUser" enctype="multipart/form-data" action="{{ route('pengguna.store') }}" method="POST">
                    <div class="form-group">

                        <input type="text" name="userid" class="form-control" id="userid" placeholder="No Telp"><br>
                        <input type="pass" name="pass" class="form-control" id="pass" placeholder="Password"><br>
                        <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama"><br>
                        <select name="level" class="form-control" id="level">
                            <option value="-">Pilih Level</option>
                            <option value="3">3</option>
                        </select><br>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email"><br>
                        <input type="file" name="foto" class="form-control" id="foto" placeholder="Foto"><br>
                        <input type="hidden" name="id" id="id" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold" id="saveBtn">Save changes</button>
            </div>
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
            ajax: "{{ route('pengguna.index') }}",
            columns: [
                {
                    data: 'userid',
                    name: 'userid'
                },
                {
                    data: 'pass',
                    name: 'pass'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'level',
                    name: 'level'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'foto',
                    name: 'foto',
                    render: function( data, type, full, meta ) {
                        return "<img src=\"" + data + "\" height=\"50\"/>";
                    }
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
            $('#saveBtn').val("create user");
            $('#id').val('');
            $('#formUser').trigger("reset");
            $('#modal-user').modal('show');
        });
        // initialize btn edit
        $('body').on('click', '.editUser', function () {
            var id = $(this).data('id');
            $.get("{{route('pengguna.index')}}" + '/' + id + '/edit', function (data) {
                $('#saveBtn').val("edit-user");
                $('#modal-user').modal('show');
                $('#userid').val(data.userid);
                $('#pass').val(data.pass);
                $('#nama').val(data.nama);
                $('#level').val(data.level);
                $('#email').val(data.email);
                $('#foto').val(data.foto);
            })
        });
        // initialize btn save
        $('#saveBtn').click(function (e) {
            var formData = new FormData($("#formUser")[0]);            
               var id = $('#id').val();
               var userid = $('#userid').val();
               var pass = $('#pass').val();
               var nama = $('#nama').val();
               var level = $('#level').val();
               var email = $('#email').val();
               var foto = $('#foto').val();
               console.log(id);
               console.log(foto);
            e.preventDefault();
            $(this).html('Save');
            $.ajax({
                data: formData,
                url: "{{ route('pengguna.store') }}",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#formUser').trigger("reset");
                    $('#modal-user').modal('hide');
                    swal_success();
                    table.draw();
                },
                error: function (data) {
                    swal_error();
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        // initialize btn delete
        $('body').on('click', '.deleteUser', function () {
            var id = $(this).data("id");

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
                        url: "{{route('pengguna.index')}}" + '/' + id,
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
