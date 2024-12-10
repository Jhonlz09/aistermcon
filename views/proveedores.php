<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Proveedores</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Proveedores</h1>
            </div>
            <?php if (isset($_SESSION["crear10"]) && $_SESSION["crear10"] === true) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-green" data-toggle="modal" data-target="#modal">
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
                                    <h3 class="card-title ">Listado de proveedores</h3>
                                </div>
                                <div class="col-sm-8 p-0">
                                    <div class="card-tools">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search icon"></i></span>
                                            <input autocomplete="off" style="border:none" type="search" id="_search" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table id="tblProveedores" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>RUC/CI</th>
                                    <th>PROVEEDOR</th>
                                    <th>TELÉFONO</th>
                                    <th>DIRECCIÓN</th>
                                    <th>EMAIL</th>
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
            <div class="modal-header bg-gradient-green">
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Rol</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fa-solid fa-signature"></i> Proveedor</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-data">
                                        <input autocomplete="off" inputmode="numeric" id="ruc" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="ruc" class="label"><i class="fas fa-building-user"></i> RUC/CI</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" inputmode="numeric" id="telefono" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="telefono" class="label"><i class="fas fa-phone"></i> Teléfono</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="correo" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="correo" class="label"><i class="fa-solid fa-envelope"></i> Correo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-data" style="margin-bottom:1rem;">
                                        <input autocomplete="off" id="direccion" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="direccion" class="label"><i class="fas fa-map-location-dot"></i> Dirección</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-green"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar10"] || $_SESSION["eliminar10"] ?>';
    var editar = '<?php echo $_SESSION["editar10"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar10"] ?>';

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
                targets: 6,
                data: "acciones",
                visible: true,
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
        if (!$.fn.DataTable.isDataTable('#tblProveedores')) {
            tabla = $("#tblProveedores").DataTable({
                "ajax": {
                    "url": "controllers/proveedores.controlador.php",
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
                localStorage.setItem('proveedores', JSON.stringify(tablaData));
            });
        }
        let accion = 0;
        const modal = document.getElementById('modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            icon = document.querySelector('.modal-title i'),
            btnNuevo = document.getElementById('btnNuevo');

        const id = document.getElementById('id'),
            ruc = document.getElementById('ruc'),
            nombre = document.getElementById('nombre'),
            correo = document.getElementById('correo'),
            direccion = document.getElementById('direccion'),
            telefono = document.getElementById('telefono');

        $(modal).on("shown.bs.modal", () => {
            nombre.focus();
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nuevo Proveedor', icon, 'fa-hand-holding-box', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                form.reset();
                form.classList.remove('was-validated');
                correo.disabled = false;
                telefono.disabled = false;
            });
        }

        $('#tblProveedores tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'proveedor', function(r) {
                if (r) {
                    confirmarAccion(src, 'proveedores', tabla, '', function(r) {
                        if (r) {
                            cargarCombo('Proveedores');
                        }
                    });
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

        $('#tblProveedores tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar Proveedor', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            ruc.value = row["ruc"];
            nombre.value = row["nombre"];
            direccion.value = row["direccion"];
            telefono.value = row["telefono"];
            correo.value = row["correo"];
            correo.disabled = false;
            telefono.disabled = false;
            form.classList.remove('was-validated');
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const nom = nombre.value.trim().toUpperCase(),
                ruc_ = ruc.value.trim(),
                tel = telefono.value.trim(),
                dir = direccion.value.trim().toUpperCase(),
                cor = correo.value.trim();
            if (tel === '') {
                telefono.disabled = true;
            }

            if (cor === '') {
                correo.disabled = true;
            }

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('ruc', ruc_);
            datos.append('tel', tel);
            datos.append('dir', dir);
            datos.append('correo', cor);
            datos.append('accion', accion);
            confirmarAccion(datos, 'proveedores', tabla, modal, function(r) {
                if (r) {
                    cargarCombo('Proveedores');
                }
            })
        });
    })
</script>