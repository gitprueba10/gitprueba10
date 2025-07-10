<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD CON PHP, PDO, AJAX Y DATATABLE</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/estilos.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="container fondo">
        <h1 class="text-center">CRUD CON PHP, PDO, AJAX Y DATATABLE</h1>

        <div class="row mt-4">
            <div class="col-2 offset-10 text-center">
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="botonCrear">
                    <i class="bi bi-plus-circle-fill"></i> Crear
                </button>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="table-responsive">
            <table id="datos_usuario" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Imagen</th>
                        <th>Fecha Creación</th>
                        <th>Editar</th>
                        <th>Borrar</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="formulario" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <label for="nombre">Ingrese el nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                        <br>

                        <label for="apellidos">Ingrese los apellidos</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control" required>
                        <br>

                        <label for="telefono">Ingrese el teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" required>
                        <br>

                        <label for="email">Ingrese el email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <br>

                        <label for="imagen">Seleccione una imagen</label>
                        <input type="file" name="imagen_usuario" id="imagen" class="form-control">
                        <span id="imagen-subida"></span>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_usuario" id="id_usuario">
                        <input type="hidden" name="operacion" id="operacion">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Crear">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <!-- Script DataTable -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datos_usuario').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "obtener_registros.php",
                    type: "POST"
                },
                "columnDefs": [
                    {
                        "targets": [0, 3, 4],
                        "orderable": false
                    }
                ]
            });
        });

        $(document).on('submit', '#formulario', function (event){
            event.preventDefault();
            var nombres = $('#nombre').val();
            var apellidos = $('#apellidos').val();
            var telefono = $('#telefono').val();
            var email = $('#email').val();
            var extension = $('#imagen').val().split('.').pop().toLowerCase();
            var operacion = $('#operacion').val();
            var url = (operacion === 'editar') ? 'editar.php' : 'crear.php';

            if(extension != ''){
                if($.inArray(extension,['gif', 'png', 'jpg','jpeg']) == -1){
                    alert("Formato de imagen invalido");
                    return false;
                }
            }

            if(nombres != '' && apellidos != '' && email != ''){
                $.ajax({
                    url: url,
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        $('#formulario')[0].reset();
                        var modal = bootstrap.Modal.getInstance(document.getElementById('modalUsuario'));
                        if(modal) modal.hide();
                        $('#datos_usuario').DataTable().ajax.reload();
                    }
                });
            } else {
                alert("Algunos campos son obligatorios");
            }
        });

        // Botón borrar
        $(document).on('click', '.borrar', function(){
            var id_usuario = $(this).attr('id');
            if(confirm('¿Estás seguro de que deseas borrar este usuario?')){
                $.ajax({
                    url: 'borrar.php',
                    method: 'POST',
                    data: {id_usuario: id_usuario},
                    success: function(data){
                        alert(data);
                        $('#datos_usuario').DataTable().ajax.reload();
                    }
                });
            }
        });

        // Botón editar (abrir modal y cargar datos)
        $(document).on('click', '.editar', function(){
            var id_usuario = $(this).attr('id');
            $.ajax({
                url: 'obtener_registro.php',
                method: 'POST',
                data: {id_usuario: id_usuario},
                dataType: 'json',
                success: function(data){
                    $('#modalUsuario').modal('show');
                    $('#nombre').val(data.nombre);
                    $('#apellidos').val(data.apellidos);
                    $('#telefono').val(data.telefono);
                    $('#email').val(data.email);
                    $('#id_usuario').val(data.id);
                    $('#operacion').val('editar');
                    $('#action').val('Editar');
                    if(data.imagen != ''){
                        $('#imagen-subida').html('<img src="img/' + data.imagen + '" width="50" class="img-thumbnail" />');
                    } else {
                        $('#imagen-subida').html('');
                    }
                }
            });
        });
    </script>
</body>
</html>
