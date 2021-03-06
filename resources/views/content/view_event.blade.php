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
                                    <th>Nama Event</th>
                                    <th>Tanggal</th>
                                    <th>HTM</th>
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
                <form id="formUser" name="formUser" enctype="multipart/form-data" action="{{ route('event.store') }}" method="POST">
                    <div class="form-group">

                        <input type="text" name="nama_event" class="form-control" id="nama_event" placeholder="Nama Event"><br>
                        <input type="date" name="tanggal" class="form-control" id="tanggal" placeholder="Tanggal"><br>
                        <input type="number" name="htm" class="form-control" id="htm" placeholder="Htm"><br>
                        <input type="file" name="thumbnail" class="form-control" id="thumbnail" placeholder="Foto"><br>
                        <input type="hidden" name="id_event" id="id_event" value="">
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
            ajax: "{{ route('event.index') }}",
            columns: [
                {
                    data: 'nama_event',
                    name: 'nama_event'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'htm',
                    name: 'htm'
                },
                {
                    data: 'thumbnail',
                    name: 'thumbnail',
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
            $('#id_event').val('');
            $('#formUser').trigger("reset");
            $('#modal-user').modal('show');
        });
        // initialize btn edit
        $('body').on('click', '.editUser', function () {
            var id_event = $(this).data('id');
            $.get("{{route('event.index')}}" + '/' + id_event + '/edit', function (data) {
                $('#saveBtn').val("edit-user");
                $('#modal-user').modal('show');
                $('#nama_event').val(data.nama_event);
                $('#tanggal').val(data.tanggal);
                $('#htm').val(data.htm);
                $('#thumbnail').val(data.thumbnail);
            })
        });
        // initialize btn save
        $('#saveBtn').click(function (e) {
            var formData = new FormData($("#formUser")[0]);            
               var id_event = $('#id_event').val();
               var nama_event = $('#nama_event').val();
               var tanggal = $('#tanggal').val();
               var htm = $('#htm').val();
               var thumbnail = $('#thumbnail').val();
            e.preventDefault();
            $(this).html('Save');
            $.ajax({
                data: formData,
                url: "{{ route('event.store') }}",
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
            var id_event = $(this).data("id");

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
                        url: "{{route('event.index')}}" + '/' + id_event,
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
