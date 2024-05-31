<head>
    <title>Cargar</title>
</head>
<script>
    window.scrollTo(0, 0);
</script>

<section class="content-header" style="padding: 1.6rem .55rem;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1>Inventario</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- FILA PARA INPUT FILE -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" id="form_cat" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <label for="fileCat"><span style="margin-right:.5rem">Categorías</span>
                                        <div class="download">
                                            <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_CARGAR_CATEGORIAS.xlsx">
                                                <i class="fas fa-download"></i> Descargar formato
                                            </a>
                                        </div><!-- /.col -->
                                    </label>
                                    <input type="file" name="fileCat" id="fileCat" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>
                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarCat"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> <span style="display: inline-block;">Cargar </span></button>
                                </div>
                            </div>
                        </form>

                        <form method="post" enctype="multipart/form-data" id="form_und" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <label for="fileUnd"><span style="margin-right:.5rem">Unidad</span>
                                        <div class="download">
                                            <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_CARGAR_UNIDADES.xlsx">
                                                <i class="fas fa-download"></i> Descargar formato
                                            </a>
                                        </div><!-- /.col -->
                                    </label>
                                    <input type="file" name="fileUnd" id="fileUnd" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>
                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarUnd"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> <span style="display: inline-block;">Cargar </span></button>
                                </div>
                            </div>

                        </form>

                        <form method="post" enctype="multipart/form-data" id="form_ubi" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <label for="fileUbi"><span style="margin-right:.5rem">Ubicación</span>
                                        <div class="download">
                                            <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_CARGAR_UBICACION.xlsx">
                                                <i class="fas fa-download"></i> Descargar formato
                                            </a>
                                        </div><!-- /.col -->
                                    </label>
                                    <input type="file" name="fileUbi" id="fileUbi" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>
                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarUbi"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> <span style="display: inline-block;">Cargar </span></button>
                                </div>
                            </div>
                        </form>

                        <form method="post" enctype="multipart/form-data" id="form_pro" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <label for="filePro"><span style="margin-right:.5rem">Productos</span>
                                        <div class="download">
                                            <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_CARGAR_PRODUCTOS.xlsx">
                                                <i class="fas fa-download"></i> Descargar formato
                                            </a>
                                        </div><!-- /.col -->
                                    </label>
                                    <input type="file" name="filePro" id="filePro" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>

                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarPro"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i><span style="display:inline-block;"> Cargar</span></button>
                                </div>
                            </div>
                        </form>

                        <form method="post" enctype="multipart/form-data" id="form_inv" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <label for="fileInv"><span style="margin-right:.5rem">Actualizar Inventario</span>
                                        <div class="download">
                                            <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_ACT_INVENTARIO.xlsx">
                                                <i class="fas fa-download"></i> Descargar formato
                                            </a>
                                        </div><!-- /.col -->
                                    </label>
                                    <input type="file" name="fileInv" id="fileInv" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>

                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarInv"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i><span style="display:inline-block;"> Cargar</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- ./ end card-body -->
    </div>
</div><!-- /.container-fluid -->

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1>Proveedores</h1>
            </div><!-- /.col -->
            <div class="col" style="white-space:nowrap">
                <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_CARGAR_PROVEEDORES.xlsx" class="float-sm-right">
                    <i class="fas fa-download"></i> Descargar formato
                </a>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- FILA PARA INPUT FILE -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" id="form_proveedores" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <input type="file" name="fileProveedores" id="fileProveedores" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>
                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarProveedores"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> <span style="display: inline-block;">Cargar </span></button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- ./ end card-body -->
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1>Clientes</h1>
            </div><!-- /.col -->
            <div class="col" style="white-space:nowrap">
                <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_CARGAR_CLIENTES.xlsx" class="float-sm-right">
                    <i class="fas fa-download"></i> Descargar formato
                </a>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- FILA PARA INPUT FILE -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" id="form_clientes" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <input type="file" name="fileClientes" id="fileClientes" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>
                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarClientes"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> <span>Cargar </span></button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- ./ end card-body -->
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1>Empleados</h1>
            </div><!-- /.col -->
            <div class="col" style="white-space:nowrap">
                <a href="controllers/descargar_formato.php?archivo=FORMATO_PARA_CARGAR_EMPLEADOS.xlsx" class="float-sm-right">
                    <i class="fas fa-download"></i> Descargar formato
                </a>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- FILA PARA INPUT FILE -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card ">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" id="form_empleados" class="needs-validation" novalidate>
                            <div class="row align-items-end">
                                <div class="col-md-10 pad-col">
                                    <input type="file" name="fileEmpleados" id="fileEmpleados" class="form-control" accept=".xls, .xlsx" required>
                                    <div class="invalid-feedback no-margin">*Campo requerido.</div>
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>
                                </div>
                                <div class="col-md-2 pad-col">
                                    <button style="width:100%" type="submit" value="Cargar" class="btn btn-primary" id="btnCargarEmpleados"><i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> <span style="display: inline-block;">Cargar </span></button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- ./ end card-body -->
                </div>
            </div>
        </div>
        <!-- FILA PARA IMAGEN DEL GIF -->
        <div id="overlay">
            <div class="loader">
                <img style="width:20%" src="assets/img/loader1.gif" id="img_carga">
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- Modal-->

<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detalles</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="accordion">
                    <div class="card card-success">
                        <div style="padding-block:.5rem" class="card-header">
                            <h4 class="card-title w-100">
                                <a id="filasExitosas" class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                                </a>
                            </h4>
                        </div>
                    </div>
                    <div class="card card-danger">
                        <div style="padding-block:.5rem" class="card-header">
                            <h4 class="card-title w-100">
                                <a id="filasNo" class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <dl class="row" style="font-size: 1.2em">
                                    <dt class="pt-0 col-10">Filas con campos vacios:</dt>
                                    <dd id="filaVacia" class="pt-0 col-2"></dd>
                                    <dt class="pt-0 col-10">Filas repetidas/existentes:</dt>
                                    <dd id="filaRep" class="pt-0 col-2"></dd>
                                    <dt class="pt-0 col-10">Filas con campos incorrectos:</dt>
                                    <dd id="filaInc" class="pt-0 col-2"></dd>
                                    <div id='details'>
                                        <dt class="pt-0 pb-2 col-12 offset-12">A continuacion se detallan los campos no encontrados:</dt>
                                        <dt class="pt-0 col-4">Categorias</dt>
                                        <dd id="filaCat" class="pt-0 col-8"></dd>
                                        <dt class="pt-0 col-4">Unidades</dt>
                                        <dd id="filaUnd" class="pt-0 col-8"></dd>
                                        <dt class="pt-0 col-4">Ubicacion</dt>
                                        <dd id="filaUbi" class="pt-0 col-8"></dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>
    $(document).ready(function() {
        const form_pro = document.getElementById('form_pro'),
            form_inv = document.getElementById('form_inv'),
            form_cat = document.getElementById('form_cat'),
            form_und = document.getElementById('form_und'),
            form_ubi = document.getElementById('form_ubi'),
            form_proveedores = document.getElementById('form_proveedores'),
            form_clientes = document.getElementById('form_clientes'),
            form_empleados = document.getElementById('form_empleados');

        const filasExitosas = document.getElementById('filasExitosas'),
            filasNo = document.getElementById('filasNo'),
            filaVacia = document.getElementById('filaVacia'),
            filaRep = document.getElementById('filaRep'),
            filaInc = document.getElementById('filaInc'),
            filaCat = document.getElementById('filaCat'),
            filaUnd = document.getElementById('filaUnd'),
            filaUbi = document.getElementById('filaUbi');

        const overlay = document.getElementById('overlay'),
            details = document.getElementById('details');

        form_pro.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('filePro');
            submitForm(this, val, 1, true);
        });

        form_inv.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('fileInv');
            submitForm(this, val, 8,false, 'actualiza');
        });

        form_cat.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('fileCat');
            submitForm(this, val, 2);

        });

        form_und.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('fileUnd');
            submitForm(this, val, 3);
        });

        form_ubi.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('fileUbi');
            submitForm(this, val, 4);
        });

        form_proveedores.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('fileProveedores');
            submitForm(this, val, 5);
        });

        form_clientes.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('fileClientes');
            submitForm(this, val, 6);
        });

        form_empleados.addEventListener("submit", function(e) {
            e.preventDefault();
            let val = document.getElementById('fileEmpleados');
            submitForm(this, val, 7);
        });

        function submitForm(form, input, action, detail = false,  opcion='inserta') {
            /*===================================================================*/
            //VALIDAR QUE SE SELECCIONE UN ARCHIVO
            /*===================================================================*/
            let expReg = /\.(xls|xlsx)$/i;
            let msg = form.querySelector('.ten');
            msg.style.display = 'none';
            let valor = input.value;
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            if (!expReg.test(valor)) {
                msg.style.display = 'block';
                return;
            }

            let datos = new FormData($(form)[0]);
            datos.append('accion', action);
            overlay.style.display = 'block';
            console.log(datos)
            $.ajax({
                url: "controllers/carga.controlador.php",
                type: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(src) {
                    handleResponse(input, src, form, detail, opcion);
                },
            });
        }

        function handleResponse(inpfile, src, form, detail, opcion) {
            inpfile.value = '';
            form.classList.remove('was-validated');
            filasExitosas.textContent = 'Filas '+opcion+'das correctamente: ' + src.registrados;
            filasNo.textContent = 'Filas no '+opcion+'das: ' + src.noRegistrados;
            filaVacia.textContent = src.vacios;
            filaRep.textContent = src.repetidos;
            filaInc.textContent = src.incorrectos;
            if (detail) {
                let categoriaNo = src.categoriaNoRegistrada;
                let unidadNo = src.unidadNoRegistrada;
                let ubicacionNo = src.ubicacionNoRegistrada;
                details.style.display = 'contents'
                filaCat.textContent = categoriaNo.length > 0 ? categoriaNo.join(', ') : 'NINGUN(O)';
                filaUnd.textContent = unidadNo.length > 0 ? unidadNo.join(', ') : 'NINGUN(O)';
                filaUbi.textContent = ubicacionNo.length > 0 ? ubicacionNo.join(', ') : 'NINGUN(O)';
            } else {
                details.style.display = 'none'
            }
            if (src.registrados > 0) {
                let txt = '';
                if (src.noRegistrados > 0) {
                    txt = 'No se pudieron '+opcion+'r ' +
                        src.noRegistrados +
                        ' fila(s)'
                }
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Se "+opcion+"ron " +
                        src.registrados +
                        " fila(s) correctamente",
                    text: txt,
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Ver detalles",
                    cancelButtonText: "Cerrar",
                }).then((r) => {
                    if (r.value) {
                        $('#modal-default').modal('show');
                    }
                });
            } else {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "No se pudo "+opcion+"r ninguna fila",
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Ver detalles",
                    cancelButtonText: "Cerrar"
                }).then((r) => {
                    if (r.value) {
                        $('#modal-default').modal('show');
                    }
                });
            }
            overlay.style.display = 'none';
        }
    });
</script>