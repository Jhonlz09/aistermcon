<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ordenes</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Orden de trabajo</h1>
            </div>
            <?php if (isset($_SESSION["crear17"]) && $_SESSION["crear17"] === true) : ?>
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
                                <div class="col-md-3">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="orden_nro" class="input-nuevo" type="text" maxlength="9" oninput="formatInputOrden(this, null, false)" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fas fa-ticket"></i> Nro. orden</label>
                                        <div class="invalid-feedback mt-0">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group mb-4">
                                        <label id="lblCO" class="mb-0 combo"><i class="fas fa-user-tag"></i> Cliente</label>
                                        <div class="row">
                                            <div class="col">
                                                <select id="cboClientesOrden" class="cbo modalB form-control select2 select2-success" data-dropdown-css-class="select2-dark" data-placeholder="SELECCIONE" required>
                                                </select>
                                                <div class="invalid-feedback mt-0">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-4" id="div_fecha_new">
                                    <label class="combo" style="line-height:1.2;font-size: 1.15rem;" for="fecha_">
                                        <i class="fas fa-calendar"></i> Fecha de creación</label>
                                    <input id="fecha_new" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                </div>
                                <div class="col-lg col-md-9">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fa-solid fa-input-text"></i> Descripción</label>
                                        <!-- <div class="invalid-feedback">*Campo obligatorio.</div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="combo" style="font-size: 1.15rem;"><i class="fa-solid fa-file-pdf"></i> Archivo</label>
                                    <input type="file" name="fileOrden" id="fileOrden" class="form-control" accept=".pdf">
                                    <div class="ten no-margin">*Debe selecionar un archivo .pdf</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label id="lblP" class="mb-0 combo"><i class="fas fa-user-helmet-safety"></i> Responsable </label>
                                        <div class="row">
                                            <div class="col">
                                                <select id="cboRes" class="form-control select2 select2-success" multiple="multiple" data-dropdown-css-class="select2-dark" style="width: 100%;">
                                                </select>
                                                <!-- <div class="invalid-feedback">*Campo obligatorio</div> -->
                                            </div>
                                        </div>
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

<!-- Modal Date-->
<div class="modal fade" id="modal-date">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-light">
                <h4 class="modal-title title-nuevo"><i class="fas fa-calendar-check"></i> Cambiar estado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formDate" class="needs-validation" autocomplete="off" novalidate>
                <div class="modal-body">
                    <div class="col-md-12 mb-4">
                        <label class="combo" style="font-size: 1.15rem;"><i class="fa-solid fa-chart-candlestick"></i></i> Estado</label>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label style="border-left-width: 1px;" class="btn txt-ellipsis">
                                <input type="radio" value="0" name="options" id="option_a1" autocomplete="off"> <i class="fas fa-clock"></i> En espera
                            </label>
                            <label for="option_a2" class="btn txt-ellipsis">
                                <input type="radio" value="1" name="options" id="option_a2" autocomplete="off"><i class="fas fa-person-digging"></i> En operacion
                            </label>
                            <label for="option_a3" class="btn txt-ellipsis">
                                <input type="radio" value="2" name="options" id="option_a3" autocomplete="off"> <i class="fas fa-check-to-slot"></i> Finalizado
                            </label>
                            <label for="option_a4" class="btn txt-ellipsis">
                                <input type="radio" value="3" name="options" id="option_a4" autocomplete="off"> <i class="fas fa-money-check-dollar"></i> Facturado
                            </label>
                            <label for="option_a5" style="border-right-width: 1px;" class="btn txt-ellipsis">
                                <input type="radio" value="4" name="options" id="option_a5" autocomplete="off"> <i class="fas fa-award"></i> Garantía
                            </label>
                        </div>
                    </div>


                    <div class="col-md-12 form-group">
                        <input type="hidden" id="inp_estado" value="">
                        <input type="hidden" id="id_orden_" value="">
                        <div id="div_fecha_cre" class="form-group mb-2">
                            <label class="col-form-label combo" for="fecha">
                                <i class="fas fa-calendar"></i> Fecha de creación</label>
                            <input id="fecha_cre" type="date" autocomplete="off" value="" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                            <div class="invalid-feedback">*Campo obligatorio.</div>
                        </div>
                        <div id="div_fecha_ini" class="form-group mb-2">
                            <label class="col-form-label combo" for="fecha">
                                <i class="fas fa-calendar"></i> Fecha de operación</label>
                            <input id="fecha_ini" type="date" autocomplete="off" value="" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                            <div class="invalid-feedback">*Campo obligatorio.</div>
                        </div>
                        <div id="div_fecha_fin" class="form-group mb-2">
                            <label class="col-form-label combo" for="fecha">
                                <i class="fas fa-calendar"></i> Fecha de finalización</label>
                            <input id="fecha_fin" type="date" autocomplete="off" value="" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                            <div class="invalid-feedback">*Campo obligatorio.</div>
                        </div>
                        <div id="div_fecha_fac" class="form-group mb-2">
                            <label class="col-form-label combo" for="fecha">
                                <i class="fas fa-calendar"></i> Fecha de facturación</label>
                            <input id="fecha_fac" type="date" autocomplete="off" value="" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                            <div class="invalid-feedback">*Campo obligatorio.</div>
                        </div>
                        <div id="div_fecha_gar" class="form-group mb-2">
                            <label class="col-form-label combo" for="fecha">
                                <i class="fas fa-calendar"></i> Fecha de garantía</label>
                            <input id="fecha_gar" type="date" autocomplete="off" value="" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                            <div class="invalid-feedback">*Campo obligatorio.</div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <div>
                            <label class="col-form-label combo" for="nota">
                                <i class="fas fa-note"></i> Nota</label>
                            <textarea style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);background-color:#d1d1d1" type="text" class="form-control" id="nota" placeholder="Observaciones..." spellcheck="false" data-ms-editor="true"></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnConfirmar" class="btn bg-gradient-light"><i class="fas fa-circle-check"></i><span class="button-text"> Confirmar</span></button>
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
    var editar = '<?php echo $_SESSION["editar17"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar17"] ?>';
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
        "ordering": true,
        "autoWidth": false,
        "deferRender": true,
        columnDefs: [{
                // "orderable": false,
                targets: 0,
                data: null,
                // className: "text-center",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1; // Devuelve el número de fila + 1
                    }
                    return meta.row; // Devuelve el índice de la fila
                }
            },
            {
                targets: 1,
                "orderable": true,
            },
            {
                targets: 2,
                "orderable": false,
            },
            {
                targets: 3,
                "orderable": false,
            },
            {
                targets: 3,
                "orderable": false,
            },
            {
                targets: 4,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, full, meta) {
                    let estado = row.estado_obra;
                    let fecha_cre = row.fecha;
                    let fecha_ini = row.fecha_ini === '' ? ' -' : row.fecha_ini;
                    let fecha_fin = row.fecha_fin === '' ? ' -' : row.fecha_fin;
                    let fecha_fac = row.fecha_fac === '' ? ' -' : row.fecha_fac;
                    let fecha_gar = row.fecha_gar === '' ? ' -' : row.fecha_gar;
                    let nota = row.nota ?? ' -'; // Asigna '-' si la nota está vacía

                    const tooltipText = {
                        0: 'Fecha de creacion: ' + fecha_cre,
                        1: 'Fecha de operación: ' + fecha_ini,
                        2: 'Fecha de finalización: ' + fecha_fin,
                        3: 'Fecha de facturación: ' + fecha_fac,
                        4: 'Fecha de garantía: ' + fecha_gar,
                        5: `<strong>NOTA:</strong> ${nota}` // Agrega "NOTA:" en negrita seguido del contenido de `nota`
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
                "orderable": false,
                responsivePriority: 2,
                data: "acciones",
                visible: mostrarCol ? true : false,
                render: function(data, type, row, full, meta) {
                    const estadoClases = {
                        0: 'light',
                        1: 'yellow',
                        2: 'info',
                        3: 'success',
                        4: 'gray-dark'
                    };

                    let estado = row.estado_obra;
                    let ruta = row.ruta;
                    let clase = estadoClases[estado];
                    // let btnEstado = estado === '0' ? '' : 'btnEstado';
                    // let data_modal = estado == '3' ? '' : ""
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" : "") +
                        "</button> <button type='button' class='btn bg-gradient-" + clase + " btnEstado' data-target='#modal-date' data-toggle='modal'   title='Estado'>" +
                        " <i class='fas fa-shuffle'></i>" +
                        "</button>" +
                        (eliminar ?
                            " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                            " <i class='fa fa-trash'></i>" +
                            "</button>" : "") +
                        (ruta !== '' ?
                            " <a href='/aistermcon/utils/download.php?file=" + encodeURIComponent(ruta) + "&route=uploads" + "' target='_blank' style='font-size:1.4rem;padding:3px 6.8px' class='btn btnDescargar' title='PDF'>" +
                            " <i class='fas fa-file-pdf'></i>" +
                            "</a>" :
                            " <span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed; color:darkgrey' class='btn' >" +
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
            fecha_new = document.getElementById('fecha_new'),
            nota = document.getElementById('nota'),
            btnConfirmar = document.getElementById('btnConfirmar');

        // const fechas_estados = document.getElementById('fechas_estados'),
        const fecha_cre = document.getElementById('fecha_cre'),
            fecha_ini = document.getElementById('fecha_ini'),
            fecha_fin = document.getElementById('fecha_fin'),
            fecha_fac = document.getElementById('fecha_fac'),
            fecha_gar = document.getElementById('fecha_gar');

        const div_fecha_new = document.getElementById('div_fecha_new'),
            div_fecha_cre = document.getElementById('div_fecha_cre'),
            div_fecha_ini = document.getElementById('div_fecha_ini'),
            div_fecha_fin = document.getElementById('div_fecha_fin'),
            div_fecha_fac = document.getElementById('div_fecha_fac'),
            div_fehca_gar = document.getElementById('div_fecha_gar')

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

        $('#cboRes').select2({})

        cargarCombo('Res', '', 7)

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
        });

        $(cboClienteOrden).select2({
            width: '100%',
            data: datos_cliente
        })

        console.log(datos_cliente)


        $(cboOrdenEstadoFilter).select2({
            width: '100%',
            minimumResultsForSearch: -1,
        });

        cargarCombo('OrdenEstadoFilter', estado_filter, 11);

        $(cboClienteOrden).change(function() {
            estilosSelect2(this, 'lblCO')
        });



        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                let estado = {
                    0: div_fecha_cre,
                    1: div_fecha_ini,
                    2: div_fecha_fin,
                    3: div_fecha_fac,
                    4: div_fecha_gar
                };

                estado[this.value].style.display = '';

                Object.values(estado).forEach((e, i) => {
                    if (i != this.value) {
                        e.style.display = 'none';
                    }
                });
            });
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                div_fecha_new.style.display = '';
                cambiarModal(span, ' Nueva Orden', icon, 'fa-ticket', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                select.forEach(function(s) {
                    s.classList.remove('select2-warning');
                    s.classList.add('select2-success');
                });
                form.reset();
                form.classList.remove('was-validated');
                // nombre.disabled = false;
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
            div_fecha_new.style.display = 'none';
            cambiarModal(span, ' Editar Orden', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombre.value = row["descripcion"];
            // fecha_cre.value = convertirFecha(row["fecha"]);
            orden_nro.value = row["nombre"];
            fileInput.value = '';

            setChange(cboClienteOrden, row["id_cliente"]);
            form.classList.remove('was-validated');
        });

        $('#tblOrden tbody').on('click', '.btnEstado', function() {
            let row = obtenerFila(this, tabla);
            accion = 5;
            nota.value = row["nota"];
            id_orden_.value = row["id"];
            radios[row["estado_obra"]].click();
            fecha_cre.value = convertirFecha(row["fecha"]);
            fecha_ini.value = convertirFecha(row["fecha_ini"]);
            fecha_fin.value = convertirFecha(row["fecha_fin"]);
            fecha_fac.value = convertirFecha(row["fecha_fac"]);
            fecha_gar.value = convertirFecha(row["fecha_gar"]);
        });

        formDate.addEventListener("submit", function(e) {
            e.preventDefault();
            let estado_obra = radios[0].checked ? 0 : radios[1].checked ? 1 : radios[2].checked ? 2 : radios[3].checked ? 3 : 4;
            const fecha_estado = estado_obra === 0 ? fecha_cre : estado_obra === 1 ? fecha_ini : estado_obra === 2 ? fecha_fin : estado_obra === 3 ? fecha_fac : fecha_gar;

            if (!fecha_estado.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            // const estado = !(row["obra_estado"]);
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_orden_.value);
            src.append('estado', estado_obra);
            src.append('nota', nota.value)
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

            nombre.disabled = nombre.value === '';

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                nombre.disabled = false;
                return;
            }

            if (accion == 2) {
                confirmarAccion(datos, 'orden', tabla, modal, function(r) {
                    // cargarAutocompletado();
                    cargarAutocompletado(function(items) {
                        items_orden = items;
                        $('#nro_orden').autocomplete("option", "source", items);
                        $('#nro_ordenEntrada').autocomplete("option", "source", items);
                        $('#nro_ordenFab').autocomplete("option", "source", items);
                    }, null, 'orden', 6)
                });
            } else {
                fetchOrderId(datos.get('orden'), function(response) {
                    console.log(response);
                    if (response[0] != null) {
                        confirmarAccion(datos, 'orden', tabla, modal, function(r) {
                            // cargarAutocompletado();
                            cargarAutocompletado(function(items) {
                                items_orden = items;
                                $('#nro_orden').autocomplete("option", "source", items);
                                $('#nro_ordenEntrada').autocomplete("option", "source", items);
                                $('#nro_ordenFab').autocomplete("option", "source", items);
                            }, null, 'orden', 6)
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
                cli_name = cboClienteOrden.selectedIndex > 0 ? cboClienteOrden.options[cboClienteOrden.selectedIndex].text : '',
                fecha_act = fecha_new.value;

            id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('id_cliente', id_cli);
            datos.append('orden', ord);
            datos.append('cliente', cli_name)
            datos.append('fecha', fecha_act);
            // datos.append('estado', selectedEstado);
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