<!DOCTYPE html>
<html>
<head>
    <title>Listado de series</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.17/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.17/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body style="background-color: #9ef7a3;">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="col-md-12">
                        <h3 class="card-title">Listado de series
                            <a class="btn btn-success ml-5" href="javascript:void(0)" id="createNewItem"> Añadir nueva serie</a>
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered data-table">
                        <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nombre</th>
                            <th>Género</th>
                            <th>Pais de origen</th>
                            <th> Distribuidora</th>
                            <th> Episodios </th>
                            <th width="15%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="ajaxModel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading"></h4>
                            </div>
                            <div class="modal-body">
                                <form id="ItemForm" name="ItemForm" class="form-horizontal">
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label">Nombre</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Introduce el nombre" value="" maxlength="50" required="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Género</label>
                                        <div class="col-sm-12">
                                            <textarea id="genre" name="genre" required="" placeholder="Introduce el género" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Pais de origen</label>
                                        <div class="col-sm-12">
                                            <textarea id="origin_country" name="origin_country" required="" placeholder="Introduce el pais de origen" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Distribuidora</label>
                                        <div class="col-sm-12">
                                            <textarea id="distributor" name="distributor" required="" placeholder="Introduce su distribuidora" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Episodios</label>
                                        <div class="col-sm-12">
                                            <textarea id="episodes" name="episodes" required="" placeholder="Introduce el nª de capítulos" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Guardar cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('listadoSeries.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'genre', name: 'genre'},
                {data: 'origin_country', name: 'origin_country'},
                {data: 'distributor', name: 'distributor'},
                {data: 'episodes', name: 'episodes'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#createNewItem').click(function () {
            $('#saveBtn').val("create-Item");
            $('#id').val('');
            $('#ItemForm').trigger("reset");
            $('#modelHeading').html("Añadir una serie");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editItem', function () {
            var id = $(this).data('id');
            $.get("{{ route('listadoSeries.index') }}" +'/' + id +'/edit', function (data) {
                $('#modelHeading').html("Editar serie");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#genre').val(data.genre);
                $('#origin_country').val(data.origin_country);
                $('#distributor').val(data.distributor);
                $('#episodes').val(data.episodes);

            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Cargando...');

            $.ajax({
                data: $('#ItemForm').serialize(),
                url: "{{ route('listadoSeries.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {

                    $('#ItemForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Guardar cambios');
                }
            });
        });

        $('body').on('click', '.deleteItem', function () {

            var id = $(this).data("id");
            confirm("Confirma si deseas borrar la serie");

            $.ajax({
                type: "DELETE",
                url: "{{ route('listadoSeries.store') }}"+'/'+id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

    });
</script>
</html>
