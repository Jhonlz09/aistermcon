<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<head>
    <title>Configuracion</title>
</head>
<script>
    window.scrollTo(0, 0);
</script>
<!-- Contenido Header -->
<section class="content-header stick-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Configuración</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<section class="content scroll">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="padding:.75rem 1.25rem">
                        <h3 class="card-title font-weight-bold">Datos generales</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="formConfig" enctype="multipart/form-data" autocomplete="off" class="row">
                            <h3 class="card-title" style="width: 100%;padding-bottom:1rem"><i class="fas fa-home" style="margin-right: 10px;margin-left:10px;"></i>Inicio</h3>
                            <!-- Columna Izquierda -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inpInstitucion">Institucion</label>
                                    <!-- Aquí puedes colocar tus inputs para la segunda columna -->
                                    <input type="text" class="form-control" id="inpInstitucion" name="inpInstitucion">
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input class="form-control" type="file" name="logo" id="logo" accept=".png, .jpg, .jpeg">
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="cboGrafico">Grafico de Inicio</label>
                                    <select name="cboGrafico" id="cboGrafico" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                        <option value="1">Documentos por mes</option>
                                        <option value="2">Documentos por tipo</option>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <h3 class="card-title" style="width: 100%;padding:1.4rem 0rem 1rem">
                                <i class="fas fa-chart-pie" style="margin-right: 10px;margin-left:10px;"></i>Informe
                            </h3>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inpElb">Elaborado por</label>
                                    <!-- Aquí puedes colocar tus inputs para la segunda columna -->
                                    <input type="text" class="form-control" id="inpElb" name="inpElb">
                                    <label for="inputCar">Cargo</label>
                                    <!-- Aquí puedes colocar tus inputs para la segunda columna -->
                                    <input type="text" class="form-control" id="inputCar" name="inputCar">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inpuApr">Aprobado por</label>
                                    <!-- Aquí puedes colocar tus inputs para la segunda columna -->
                                    <input type="text" class="form-control" id="inpuApr" name="inpuApr">
                                    <label for="inputCar1">Cargo</label>
                                    <!-- Aquí puedes colocar tus inputs para la segunda columna -->
                                    <input type="text" class="form-control" id="inputCar1" name="inputCar1">
                                </div>
                            </div>

                            <!-- Botón de Guardar (Ocupa ambas columnas) -->
                            <div class="col-12">
                                <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardar">
                                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                                </button>
                            </div>

                        </form>
                    </div>
                    <!-- /.card-body -->

                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    // var id_usuario = '<?php echo $_SESSION["s_usuario"]->id_usuario; ?>';
    // console.log(id_usuario);
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            width: '100%',
            minimumResultsForSearch: -1,
            dropdownAutoWidth: false

        })
    })


    $.ajax({
        url: 'ajax/configuracion.ajax.php', // Reemplaza con la URL de tu script de servidor
        method: 'POST',
        dataType: 'json',
        data: {
            'accion': 1,
        },
        success: function(data) {

            console.log(data)
            // // Rellena los campos del formulario con los datos obtenidos
            $('#cboGrafico').val(data[0]['tipo_grafico']).trigger("change");
            $('#inpInstitucion').val(data[0]['institucion']);
            $('#inpElb').val(data[0]['elaboradopor']);
            $('#inputCar').val(data[0]['cargoelaborado']);
            $('#inpuApr').val(data[0]['aprobadopor']);
            $('#inputCar1').val(data[0]['cargoaprobado']);
            // ... Continúa con los demás campos

            // Puedes agregar más campos según sea necesario
        },
    });

    $(document).ready(function() {
        $("#btnGuardar").on('click', function(event) {
            event.preventDefault();
            // Obtener datos de los campos
            var tipoGrafico = $('#cboGrafico').val();
            var institucion = $('#inpInstitucion').val();
            var elaboradoPor = $('#inpElb').val();
            var cargoElaborado = $('#inputCar').val();
            var aprobadoPor = $('#inpuApr').val();
            var cargoAprobado = $('#inputCar1').val();

            // Obtener el archivo del campo de entrada de archivo
            var logoInput = $('#logo')[0];
            var logoFile = logoInput.files[0]; // Aquí obtenemos el archivo

            // Verificar si se seleccionó un archivo
            var accion = logoFile ? 2 : 3;

            var formData = new FormData();
            formData.append('tipo_grafico', tipoGrafico);
            formData.append('institucion', institucion);
            formData.append('logo', logoFile); // Utilizamos 'logo' en lugar de 'rutalogo'
            formData.append('aprobado', aprobadoPor);
            formData.append('elaborado', elaboradoPor);
            formData.append('cargo1', cargoElaborado);
            formData.append('cargo2', cargoAprobado);
            formData.append('accion', accion);


            // Enviar datos mediante Ajax
            $.ajax({
                url: 'ajax/configuracion.ajax.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: response
                    });
                    location.reload()

                    // setTimeout(function() {
                    //     location.reload(true); // El parámetro true forzará la recarga desde el servidor, no desde la caché del navegador
                    // }, 2000);
                },
            });
        });
    });
</script>