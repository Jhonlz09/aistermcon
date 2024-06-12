<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ordenes</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Ordenes</h1>
            </div>
            <?php if (isset($_SESSION["crear13"]) && $_SESSION["crear13"] === true) : ?>
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
                                <div class="col-auto col-p">
                                    <h3 class="card-title ">Listado de ordenes</h3>
                                </div>
                                <div class="col col-sm-auto mr-5">
                                    <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                    </select>
                                </div>

                                <div class="col-sm p-0">
                                    <div style="margin-block:.4rem;height:33px;" class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search icon"></i></span>
                                        <input autocomplete="off" style="border:none" type="search" id="_search" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table id="tblOrden" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>NRO. ORDEN</th>
                                    <th>CLIENTE</th>
                                    <th>DESCRIPCION</th>
                                    <th>CREACION</th>
                                    <th>ESTADO</th>
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
                <h4 class="modal-title"><i class="fas fa-ticket"></i><span> Nueva Orden</span></h4>
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
                                <div class="col-md-6">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="orden_nro" class="input-nuevo" type="text" maxlength="6" oninput="formatInputOrden(this, null, false)" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fas fa-ticket"></i> Nro. orden</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label id="lblCO" class="mb-0 combo"><i class="fas fa-user-tag"></i> Cliente</label>
                                        <div class="row">
                                            <div class="col">
                                                <select id="cboClientesOrden" class="cbo modalB form-control select2 select2-success" data-dropdown-css-class="select2-dark" data-placeholder="SELECCIONE" required>
                                                </select>
                                                <div id="Empresa" class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fa-solid fa-input-text"></i> Descripción</label>
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
    var mostrarCol = '<?php echo $_SESSION["editar13"] || $_SESSION["eliminar13"] ?>';
    var editar = '<?php echo $_SESSION["editar13"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar13"] ?>';

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
                targets: 5,
                className: "text-center",
                render: function(data, type, full, meta) {
                    let text = data ? 'EN OPERACIÓN' : 'FINALIZADO';
                    let className = data ? 'alert-default-warning' : 'alert-default-success';
                    return "<span class='alert " + className + "'>" + text + "</span>";
                }
            },
            {
                targets: 6,
                data: "acciones",
                visible: mostrarCol ? true : false,
                render: function(data, type, row, full, meta) {

                    let estado = row.obra_estado;
                    let clase = estado ? 'yellow' : 'success';
                    let iconName = estado ? 'person-digging' : 'check-to-slot';
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button> <button type='button' class='btn bg-gradient-" + clase + " btnEstado'  title='Estado'>" +
                            " <i class='fas fa-" + iconName + "'></i>" +
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
        let anio = year;
        if (!$.fn.DataTable.isDataTable('#tblOrden')) {
            tabla = $("#tblOrden").DataTable({
                "ajax": {
                    "url": "controllers/orden.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.anio = anio;
                    }
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                if ($(window).width() >= 768) { // Verificar si el ancho de la ventana es mayor o igual a 768 píxeles
                    const b = document.body;
                    const s = b.scrollHeight;
                    const w = window.innerHeight;
                    handleScroll(b, s, w);
                }

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('orden', JSON.stringify(tablaData));
            });
        }
        let accion = 0;
        const modal = document.getElementById('modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            icon = document.querySelector('.modal-title i'),
            select = document.querySelectorAll('.modal-body select.select2'),
            btnNuevo = document.getElementById('btnNuevo');

        const id = document.getElementById('id'),
            nombre = document.getElementById('nombre'),
            orden_nro = document.getElementById('orden_nro'),
            cboClienteOrden = document.getElementById('cboClientesOrden');

        $(modal).on("shown.bs.modal", () => {
            orden_nro.focus();
        });

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        })
        setChange(cboAnio, anio);

        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            anio = a
            tabla.ajax.reload();
        });

        $(cboClienteOrden).select2({
            width: '100%',
            data: datos_cliente
        })

        $(cboClienteOrden).change(function() {
            estilosSelect2(this, 'lblCO')
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nueva Orden', icon, 'fa-ticket', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                select.forEach(function(s) {
                    s.classList.remove('select2-warning');
                    s.classList.add('select2-success');
                });
                form.reset();
                form.classList.remove('was-validated');
                nombre.disabled = false;
                setChange(cboClienteOrden, 0);
            });
        }

        $('#tblOrden tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('esta', 'orden', function(r) {
                if (r) {
                    confirmarAccion(src, 'orden', tabla, '', function(r) {
                        if (r) {
                            // cargarCombo('Orden', '', 3, true).then(datos_ => {
                            //     datos_orden = datos_;
                            // });

                            // cargarCombo('PorOrden', '', 5)
                        }
                    })
                }
            });
        });

        // document.addEventListener('keydown', function(e) {
        //     if (e.key === "Escape") {
        //         const activeModal = document.querySelector('.modal.show');
        //         if (activeModal) {
        //             $(activeModal).modal('hide');
        //         }
        //     }
        // });

        $('#tblOrden tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar Orden', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombre.value = row["descripcion"];
            orden_nro.value = row["nombre"];
            setChange(cboClienteOrden, row["id_cliente"]);
            nombre.disabled = false;
            form.classList.remove('was-validated');
            
        });

        $('#tblOrden tbody').on('click', '.btnEstado', function() {
            let row = obtenerFila(this, tabla);
            accion = 5;
            cambiarModal(span, ' Editar Orden', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            const id_ = row["id"];
            const estado = !(row["obra_estado"]);
            let src = new FormData();
            src.append('accion', accion);
            src.append('estado', estado)
            src.append('id', id_);
            confirmarAccion(src, 'orden', tabla, '', function(r) {
                if (r) {
                    // cargarCombo('Orden', '', 3, true).then(datos_ => {
                    //     datos_orden = datos_;
                    // });
                }
            })
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const nom = nombre.value.trim().toUpperCase(),
                ord = orden_nro.value,
                cli = cboClienteOrden.value;

            if (nom === '') {
                nombre.disabled = true;
            }

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                // nombre.disabled = false;
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('id_cliente', cli);
            datos.append('orden', ord);
            datos.append('accion', accion);
            confirmarAccion(datos, 'orden', tabla, modal, function(r) {
                if (r) {
                    // cargarCombo('Orden', '', 3, true).then(datos_ => {
                    //     datos_orden = datos_;
                    // });
                    // cargarCombo('PorOrden', '', 5)


                }
            })
        });
    })
</script>