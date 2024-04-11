<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<head>
    <title>Empleados</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Empleados</h1>
            </div>
            <?php if ($_SESSION["crear5"]) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal">
                        <i class="fa fa-plus"></i> Nuevo</button>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <div class="row">
                                <div class="col col-p">
                                    <h3 class="card-title ">Listado de empleados</h3>
                                </div>
                                <div class="col-sm-8 p-0">
                                    <div class="card-tools">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search icon"></i></span>
                                            <input autocomplete="off" style="border:none" type="text" id="_search" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table id="tblEmpleados" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>CÉDULA</th>
                                    <th>NOMBRES</th>
                                    <th class="text-center">CONDUCTOR</th>
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
<!-- /.Contenido -->

<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Empleado</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="cedula" inputmode="numeric" class="input-nuevo" type="text" oninput="validarNumber(this,/[^0-9]/g,true)" maxlength="10" required>
                                <div class="line underline"></div>
                                <label class="label">
                                    <i class="fa-solid fa-id-card"></i> Cédula</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                                <div class="ten">*La cedula debe contener 10 numeros</div>
                            </div>
                            <div class="input-data" style="margin-bottom:2.4em;">
                                <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="label"><i class="fa-solid fa-signature"></i> Nombres</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                            </div>
                            <div style="font-size: 1.33rem;margin-bottom:1rem" class="d-flex align-items-center">
                                <label for="conductor" class="m-0">
                                    <i class="fa-solid fa-steering-wheel"></i> Conductor
                                </label>
                                <label class="switch-2 ml-3">
                                    <input class="switch__input" type="checkbox" id="conductor" onkeydown="toggleWithEnter(event, this)">
                                    <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                        <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                    </svg>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-success"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar5"] || $_SESSION["eliminar5"] ?>';
    var editar = '<?php echo $_SESSION["editar5"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar5"] ?>';

    configuracionTable = {
        "responsive": true,
        "dom": 'pt',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        columnDefs: [{
                targets: 0,
                data: "acciones",
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1; // Devuelve el número de fila + 1
                    }
                    return meta.row; // Devuelve el índice de la fila
                }
            },
            {
                targets: 3,
                render: function(data, type, full, meta) {
                    if (data == true) {
                        return "<center class='p-1'><i class='fa-solid fa-check fa-xl'></i></center>";
                    } else {
                        return "<center class='p-1'><i class='fa-solid fa-xmark fa-xl'></i></center>";
                    }
                }
            },
            {
                targets: 4,
                data: "acciones",
                visible: mostrarCol,
                render: function(data, type, row, full, meta) {
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button>" : "") +
                        (eliminar ?
                            " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                            " <i class='fa fa-trash'></i>" +
                            "</button>" : "") +
                        " </center>"
                    );
                },
            },
        ],
    }

    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#tblEmpleados')) {
            tabla = $("#tblEmpleados").DataTable({
                "ajax": {
                    "url": "controllers/empleados.controlador.php",
                    "type": "POST",
                    "dataSrc": ''
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;

                handleScroll(b, s, w);

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('empleados', JSON.stringify(tablaData));
            });
        }

        let accion = 0;
        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-success'),
            form = document.getElementById('formNuevo'),
            btnNuevo = document.getElementById('btnNuevo');

        const id = document.getElementById('id'),
            cedula = document.getElementById('cedula'),
            nombre = document.getElementById('nombre'),
            conductor = document.getElementById('conductor');

        $(modal).on("shown.bs.modal", () => {
            cedula.focus();
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                const icon = document.querySelector('.modal-title i');
                cambiarModal(span, ' Nuevo Empleado', icon, 'fa-user-plus', elements, 'bg-gradient-blue', 'bg-gradient-success', modal, 'modal-new', 'modal-change')
                form.reset();
                form.classList.remove('was-validated');
            });
        }

        $('#tblEmpleados tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_e = e["id"];
            const name = 'empleados'
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_e);
            confirmarEliminar('este', 'empleado', function(r){
                if(r){
                    confirmarAccion(src, 'empleados', tabla, '', function(r){
                        cargarCombo('Empleado');
                        cargarCombo('Conductor', '', 2);
                    })
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    $(activeModal).modal('hide');
                }
            }
        });

        $('#tblEmpleados tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            const icon = document.querySelector('.modal-title i');
            cambiarModal(span, ' Editar Empleado', icon, 'fa-pen-to-square', elements, 'bg-gradient-success', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombre.value = row["nombre"];
            cedula.value = row["cedula"];
            conductor.checked = row["conductor"];
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            $('.ten').hide();
            const ced = cedula.value.trim(),
                nom = nombre.value.trim().toUpperCase(),
                con = conductor.checked;
            if (!this.checkValidity() || ced.length < 10) {
                this.classList.add('was-validated');
                ced.length > 0 && $('.ten').show();
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('cedula', ced);
            datos.append('nombre', nom);
            datos.append('conductor', con);
            datos.append('accion', accion);
            confirmarAccion(datos, 'empleados', tabla, modal,function(r){
                        cargarCombo('Empleado');
                        cargarCombo('Conductor', '', 2);
                    })
        });
    })
</script>