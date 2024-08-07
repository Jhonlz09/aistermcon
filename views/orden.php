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
            <form id="formNuevo" autocomplete="off" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal">
                    <input type="hidden" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="orden_nro" class="input-nuevo" type="text" maxlength="9" oninput="formatInputOrden(this, null, false)" required>
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
                                <div class="col-md-12 mb-4">
                                    <label class="combo" style="font-size: 1.15rem;"><i class="fa-solid fa-chart-candlestick"></i></i> Estado</label>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label style="border-left-width: 1px;" class="btn txt-ellipsis">
                                            <input type="radio" value="0" name="options" id="option_a1" autocomplete="off" checked> <i class="fas fa-clock"></i> En espera
                                        </label>
                                        <label for="option_a2" class="btn txt-ellipsis">
                                            <input type="radio" value="1" name="options" id="option_a2" autocomplete="off"><i class="fas fa-person-digging"></i> En operacion
                                        </label>
                                        <label for="option_a3" class="btn txt-ellipsis">
                                            <input type="radio" value="2" name="options" id="option_a3" autocomplete="off"> <i class="fas fa-check-to-slot"></i> Finalizado
                                        </label>
                                        <label for="option_a4" style="border-right-width: 1px;" class="btn txt-ellipsis">
                                            <input type="radio" value="3" name="options" id="option_a4" autocomplete="off"> <i class="fa-solid fa-money-check-dollar"></i> Facturado
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="combo" style="font-size: 1.15rem;"><i class="fa-solid fa-file-pdf"></i> Archivo</label>
                                    <input type="file" name="fileOrden" id="fileOrden" class="form-control" accept=".pdf">
                                    <div class="ten no-margin">*Debe selecionar un archivo .xls o .xlsx</div>
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

<!-- Modal Date-->
<div class="modal fade" id="modal-date">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 class="modal-title title-nuevo"><i class="fas fa-calendar-check"></i> Confirmar fecha</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formDate" class="needs-validation" autocomplete="off" novalidate>
                <div class="modal-body">
                    <div id="" class="col-sm-4">
                        <input type="hidden" id="inp_estado" value="">
                        <input type="hidden" id="id_orden_" value="">
                        <div class="form-group mb-0">
                            <label class="col-form-label combo" for="fecha">
                                <i class="fas fa-calendar"></i> Fecha</label>
                            <input id="fecha_estado" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                            <div class="invalid-feedback">*Campo obligatorio.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnConfirmar" class="btn bg-gradient-green"><i class="fas fa-circle-check"></i><span class="button-text"> Confirmar</span></button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    var mostrarCol = true;
    var editar = '<?php echo $_SESSION["editar13"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar13"] ?>';
    OverlayScrollbars(document.querySelector('.scroll-modal'), {
        autoUpdate: true,
        scrollbars: {
            autoHide: 'leave'
        }
    });


    configuracionTable = {
        "responsive": true,
        "dom": '<"row"<"col-sm-6 select-filter"><"col-sm-6"p>>t',
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
                targets: 4,
                className: "text-center",
                render: function(data, type, row, full, meta) {
                    let estado = row.estado_obra;
                    let fecha_cre = row.fecha;
                    let fecha_ini = row.fecha_ini;
                    let fecha_fin = row.fecha_fin;
                    let fecha_fac = row.fecha_fac;

                    const tooltipText = {
                        0: 'Fecha de creacion: ' + fecha_cre,
                        1: 'Fecha de inicio de operación: ' + fecha_ini,
                        2: 'Fecha de finalización: ' + fecha_fin,
                        3: 'Fecha de facturación: ' + fecha_fac,
                    };

                    let concatenatedTooltipText = Object.values(tooltipText).join('<br>');

                    let clase = estadoClases[estado] || 'default';
                    let icon = estadoIcon[estado] || 'default';
                    let texto = estadoText[estado] || 'default';
                    // let tooltip = tooltipText[estado] || 'default';

                    return `<span class='alert alert-default-${clase}' data-html='true' data-toggle='tooltip' title='${concatenatedTooltipText}'><i class='fas fa-${icon}'></i> ${texto}</span>`;
                }
            },
            {
                targets: 5,
                data: "acciones",
                visible: mostrarCol ? true : false,
                render: function(data, type, row, full, meta) {

                    const estadoClases = {
                        0: 'light',
                        1: 'yellow',
                        2: 'info',
                        3: 'success'
                    };

                    let estado = row.estado_obra;
                    let ruta = row.ruta;
                    let clase = estadoClases[estado];
                    // let btnEstado = estado === '0' ? '' : 'btnEstado';
                    let data_modal = estado == '3' ? '' : "data-target='#modal-date' data-toggle='modal'"
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" : "") +
                        "</button> <button type='button' class='btn bg-gradient-" + clase + " btnEstado' " + data_modal + "   title='Estado'>" +
                        " <i class='fas fa-shuffle'></i>" +
                        "</button>" +
                        (eliminar ?
                            " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                            " <i class='fa fa-trash'></i>" +
                            "</button>" : "") +
                        (ruta !== '' ?
                            " <a href='/aistermcon/utils/download.php?file=" + encodeURIComponent(ruta) + "' target='_blank' style='font-size:1.4rem;padding:3px 8px' class='btn btnDescargar' title='Descargar'>" +
                            " <i class='fas fa-file-pdf'></i>" +
                            "</a>" :
                            " <span style='font-size:1.4rem;padding:3px 8px;cursor:not-allowed; color:darkgrey' class='btn' >" +
                            " <i class='fas fa-file-slash'></i>" +
                            "</span>") +
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
                        data.id_estado = estado_filter;
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
                $('[data-toggle="tooltip"]').tooltip();


                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('orden', JSON.stringify(tablaData));
            });
        }

        $('.select-filter').html('<div class="row" id="rowFilter" style="padding:.25rem .55rem .25rem;flex-wrap:nowrap" > <div style="max-width:max-content" class="col-sm-3"><label style="padding-block:.5rem;white-space:nowrap" class="col-form-label" ><i class="fas fa-shuffle"></i> Estado:</label></div> <div class="col-sm-6"><select id="cboOrdenEstadoFilter" class="cbo form-control select2 select2-dark" data-dropdown-css-class="select2-dark" data-placeholder="TODO"></select> </div>  </div>');

        let accion = 0;

        const modal = document.getElementById('modal'),
            modal_date = document.getElementById('modal-date'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            formDate = document.getElementById('formDate'),
            icon = document.querySelector('.modal-title i'),
            select = document.querySelectorAll('.modal-body select.select2'),
            btnNuevo = document.getElementById('btnNuevo');
        const radios = document.querySelectorAll('input[name="options"]');

        const inp_estado = document.getElementById('inp_estado'),
            id_orden_ = document.getElementById('id_orden_'),
            fecha_estado = document.getElementById('fecha_estado'),
            btnConfirmar = document.getElementById('btnConfirmar');

        // const fechas_estados = document.getElementById('fechas_estados'),
        // const fecha_cre = document.getElementById('fecha_cre'),
        //     fecha_ini = document.getElementById('fecha_ini'),
        //     fecha_fin = document.getElementById('fecha_fin'),
        //     fecha_fac = document.getElementById('fecha_fac');


        const id = document.getElementById('id'),
            nombre = document.getElementById('nombre'),
            orden_nro = document.getElementById('orden_nro'),
            cboClienteOrden = document.getElementById('cboClientesOrden'),
            fileInput = document.getElementById('fileOrden'),
            cboOrdenEstadoFilter = document.getElementById('cboOrdenEstadoFilter');


        $(modal).on("shown.bs.modal", () => {
            orden_nro.focus();
        });

        $(modal_date).on("shown.bs.modal", () => {
            btnConfirmar.focus();
        });

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        });

        setChange(cboAnio, anio);

        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            anio = a
            tabla.ajax.reload();
        });

        $(cboOrdenEstadoFilter).on("change", function() {
            // if (this.value !== 'null'){
            estado_filter = this.value;
            // console.log(estado_filter)
            accion = 0;
            tabla.ajax.reload();
            // }
            estado_filter = 'null';
        });

        $(cboClienteOrden).select2({
            width: '100%',
            data: datos_cliente
        })


        $(cboOrdenEstadoFilter).select2({
            width: '100%',
            minimumResultsForSearch: -1,
        });

        cargarCombo('OrdenEstadoFilter', estado_filter, 11);

        $(cboClienteOrden).change(function() {
            estilosSelect2(this, 'lblCO')
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                radios[0].click();
                // fechas_estados.style.display = 'none';
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

        $('#tblOrden tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            // fechas_estados.style.display = '';
            cambiarModal(span, ' Editar Orden', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombre.value = row["descripcion"];
            // fecha_cre.value = convertirFecha(row["fecha"]);
            orden_nro.value = row["nombre"];
            radios[row["estado_obra"]].click();
            fileInput.value = '';

            setChange(cboClienteOrden, row["id_cliente"]);
            nombre.disabled = false;
            form.classList.remove('was-validated');
        });

        $('#tblOrden tbody').on('click', '.btnEstado', function() {
            let row = obtenerFila(this, tabla);
            accion = 5;
            const estados = {
                0: 1,
                1: 2,
                2: 3,
                3: 0
            };
            const estado = estados[row["estado_obra"]];
            if (estado === 0) {
                let src = new FormData();
                src.append('accion', accion);
                src.append('id', row["id"]);
                src.append('estado', estado);
                src.append('fecha', convertirFecha(row["fecha"]));
                confirmarAccion(src, 'orden', tabla, '', function(r) {
                    if (r) {

                    }
                }, 800);
            } else {
                inp_estado.value = estado;
                id_orden_.value = row["id"];
            }
        });

        $(modal).on("shown.bs.modal", () => {
            nombre.focus();
        });

        formDate.addEventListener("submit", function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            // const estado = !(row["obra_estado"]);
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_orden_.value);
            src.append('estado', inp_estado.value);
            src.append('fecha', fecha_estado.value);
            confirmarAccion(src, 'orden', tabla, modal_date, function(r) {
                if (r) {

                }
            }, 800);

        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            let datos = obtenerDatosFormulario();
            const file = fileInput.files[0];

            // Validar que el archivo es .pdf
            if (file && file.type !== "application/pdf") {
                mostrarToast(
                    'warning',
                    'Advertencia',
                    'fa-triangle-exclamation',
                    'El archivo insertado no es valido, por favor inserta un archivo .pdf', 3000
                )
                return;
            } else if (file && file.type == "application/pdf") {
                datos.append('fileOrden', file);
            }

            if (nombre.value === '') {
                nombre.disabled = true;
            }

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            if (accion == 2) {
                confirmarAccion(datos, 'orden', tabla, modal, function(r) {
                    // Callback para acciones después de confirmarAccion si es necesario
                });
            } else {
                fetchOrderId(datos.get('orden'), function(response) {
                    console.log(response);
                    if (response[0] != null) {
                        confirmarAccion(datos, 'orden', tabla, modal, function(r) {
                            // Callback para acciones después de confirmarAccion si es necesario
                        });
                    } else {
                        mostrarConfirmacionExistente(datos);
                    }
                });
            }
        });

        function obtenerDatosFormulario() {
            const nom = nombre.value.trim().toUpperCase(),
                ord = orden_nro.value,
                id_cli = cboClienteOrden.value,
                cli_name = cboClienteOrden.selectedIndex > 0 ? cboClienteOrden.options[cboClienteOrden.selectedIndex].text : '';


            id_e = id.value;
            let selectedEstado;

            radios.forEach(radio => {
                if (radio.checked) {
                    selectedEstado = radio.value;
                }
            });

            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('id_cliente', id_cli);
            datos.append('orden', ord);
            datos.append('cliente', cli_name)
            datos.append('estado', selectedEstado);
            datos.append('accion', accion);

            return datos;
        }

        function mostrarConfirmacionExistente(datos) {
            Swal.fire({
                title: "Esta orden de trabajo ya existe",
                text: "¿Estás seguro que deseas continuar?",
                icon: "warning",
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmarAccion(datos, 'orden', tabla, modal, function(r) {
                        // Callback para acciones después de confirmarAccion si es necesario
                    });
                }
            });
        }

        function convertirFecha(fecha) {
            // Dividir la fecha y la hora
            let [datePart, timePart] = fecha.split(' ');

            // Dividir los componentes de la fecha
            let [day, month, year] = datePart.split('/');

            // Formatear la fecha como 'YYYY-MM-DD'
            return `${year}-${month}-${day}`;
        }
    })
</script>