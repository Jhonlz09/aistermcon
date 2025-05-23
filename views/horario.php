<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ingreso personal</title>
    <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
    <!-- <link href="assets/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="assets/plugins/datatables-scroller/css/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet" integrity="sha384-b6V45oYHXYNRRbOBt+gMso4peE+V6GATcho1MZx7ELTjReHmjA8zW2Ap/w0D3+QX" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/scroller/2.2.0/css/scroller.dataTables.min.css" rel="stylesheet" integrity="sha384-fvFMooh85/CFhRcmgNLO/DEXj4/h8h4Fz2s0Wtq2hPU/s7z0rLzrk77ID2JS+YUg" crossorigin="anonymous"> -->
    <!-- <link href="assets/plugins/datatables-keytable/css/keyTable.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
</head>
<!-- Contenido Header -->
<section id="div_header" class="ini-section content-header ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Horario personal</h1>
            </div>
            <?php if (isset($_SESSION["crear20"]) && $_SESSION["crear20"] === true) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-green">
                        <i class="fa fa-plus"></i> Agregar horario</button>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->
<!-- Main content -->
<section id="div_content" class="ini-section content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header  p-0">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto p-0">
                                    <ul class="nav nav-tabs" style="border-bottom:none;" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a style="padding: .75rem;border-right:1px solid #dfdfdf" class="nav-link active" id="custom-tabs-dia-tab" data-toggle="pill" href="#custom-tabs-dia" role="tab" aria-controls="custom-tabs-dia" aria-selected="true">POR DIA</a>
                                        </li>
                                        <li class="nav-item">
                                            <a style="padding: .75rem;border-left:none" class="nav-link" id="custom-tabs-orden-tab" data-toggle="pill" href="#custom-tabs-orden" role="tab" aria-controls="custom-tabs-orden" aria-selected="false">POR ORDEN</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class=" col col-sm-auto">
                                    <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                    </select>
                                </div>
                                <div class="col-sm-auto input-group-text p-0" style="color:#494c50 !important;">
                                    <span class=""><i class="fas fa-calendar-range"></i></span>
                                    <input autocomplete="off" style="border:none" type="text" id="miRangoFecha" class="form-control" placeholder="Selecciona un rango" />

                                </div>
                                <div class="col-sm p-0">
                                    <div class="card-tools">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search icon"></i></span>
                                            <input autocomplete="off" style="border:none" type="search" id="_search" onpaste="return trimPaste(event, this)" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0" id="card-hor">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-dia" role="tabpanel" aria-labelledby="custom-tabs-dia-tab">
                                <table id="tblHorario" cellspacing="0" class="display table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="th-blue text-center">Nº</th>
                                            <th rowspan="2" class="th-blue">NOMBRES</th>
                                            <th rowspan="2" class="th-blue">Nº DE ORDEN</th>
                                            <th rowspan="2" class="th-blue">CLIENTE</th>
                                            <th colspan="4" class="th-purple">SUELDO Y SOBRETIEMPO</th>
                                            <th colspan="5" class="th-red">PREVISIONES</th>
                                            <th rowspan="2" class="th-green">COSTO MANO OBRA </th>
                                            <th rowspan="2" class="th-green">GASTO EN OBRA</th>
                                        </tr>
                                        <tr>
                                            <th class="th-purple">HN</th>
                                            <th class="th-purple">HS</th>
                                            <th class="th-purple">HE</th>
                                            <th class="th-purple">TOTAL</th>
                                            <th class="th-red">%12.15</th>
                                            <th class="th-red">13ER</th>
                                            <th class="th-red">14TO</th>
                                            <th class="th-red">VAC</th>
                                            <th class="th-red">FR</th>

                                            <th>TOTAL COSTO</th>
                                            <!-- <th class="text-center">ACCIONES</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3"><strong>Total general:</strong></th>
                                            <th colspan="13" id="totalGeneral"></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-orden" role="tabpanel" aria-labelledby="custom-tabs-orden-tab">
                                <table id="tblHorario2" cellspacing="0" class="display table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="th-blue text-center">Nº</th>
                                            <th rowspan="2" class="th-blue">NOMBRES</th>
                                            <th rowspan="2" class="th-blue">Nº DE ORDEN</th>
                                            <th rowspan="2" class="th-blue">CLIENTE</th>
                                            <th colspan="4" class="th-purple">SUELDO Y SOBRETIEMPO</th>
                                            <th colspan="5" class="th-red">PREVISIONES</th>
                                            <th rowspan="2" class="th-green">COSTO MANO OBRA </th>
                                            <th rowspan="2" class="th-green">GASTO EN OBRA</th>
                                        </tr>
                                        <tr>
                                            <th class="th-purple">HN</th>
                                            <th class="th-purple">HS</th>
                                            <th class="th-purple">HE</th>
                                            <th class="th-purple">TOTAL</th>
                                            <th class="th-red">%12.15</th>
                                            <th class="th-red">13ER</th>
                                            <th class="th-red">14TO</th>
                                            <th class="th-red">VAC</th>
                                            <th class="th-red">FR</th>

                                            <th>TOTAL COSTO</th>
                                            <!-- <th class="text-center">ACCIONES</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3"><strong>Total general:</strong></th>
                                            <th colspan="13" id="totalGeneral"></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <!-- </div> -->
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

<section id="div_hor_header" class="form-section content-header" style="display:none">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h4>
                    <i id="btnReturn" style="cursor:pointer" class="fa-regular fa-circle-arrow-left"></i><span id="text_accion"> Nuevo horario</span>
                </h4>
            </div>
            <div class="col-auto">
                <button id="btnGuardarHorario" class="btn bg-gradient-navy mb-1" style="width:12rem;">
                    <i class="fas fa-floppy-disk"></i> Guardar</button>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<section id="div_hor_filter" class="form-section content" style="display: none;">
    <div class="container-fluid">
        <div class="row" style="align-items:flex-start">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Elemento 1 -->
                            <div class="col-sm col-md-4 mb-2">
                                <label for="nro_ordenHorario" class="col-form-label combo">
                                    <i class="fas fa-person-digging"></i> Obra
                                </label>
                                <!-- <div class="position-relative"> -->
                                <input type="search" id="nro_ordenHorario" class="form-control ui-autocomplete-input" placeholder="Nro. de orden o cliente" autocomplete="off" style="font-size:1.2rem; border-bottom:2px solid var(--select-border-bottom);" spellcheck="false" data-ms-editor="true">
                                <button class="clear-btn" type="button" id="clearButtonObraH" onclick="clearInput('nro_ordenHorario', this)" style="display:none; position:absolute; right:10px; top:42%;">×</button>
                                <!-- </div> -->

                                <!-- <button class="clear-btn" type="button" id="btnO${uniqueId}" onclick="clearInput('${uniqueId}', this)" style="display:none;top:6%;right:2px">&times;</button> -->

                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>

                            <!-- Elemento 2 -->
                            <div class="col-sm col-md-3  mb-2">
                                <label for="fechaH" class="m-0"><i class="fas fa-calendar"></i> Fecha</label>
                                <input type="date" id="fechaH" class="form-control" value="<?php echo date('Y-m-d'); ?>" style="border-bottom: solid 2px #000;">
                            </div>

                            <!-- Elemento 3 -->
                            <div class="col-sm col-lg mb-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="sp-e" class="m-0 text-nowrap" style="cursor:pointer;">
                                        <i class="fas fa-user-helmet-safety"></i> Empleado
                                    </label>
                                    <button id="sp-e" data-toggle="modal" data-target="#modal_personal" class="btn bg-gradient-dark" style="width:2rem;height:1.6rem;font-size:0.75rem; display:flex; justify-content:center; align-items:center;">
                                        <i class="fas fa-up-right-from-square"></i>
                                    </button>
                                </div>
                                <label class="font-weight-normal mb-0" for="sp-e" style="cursor:pointer;display:block;height:calc(2.25rem + 2px);line-height: calc(2.25rem + 2px); white-space:nowrap;">
                                    <span class="font-weight-bold" id="selected-person">0</span> seleccionado(s)
                                </label>
                            </div>
                            <!-- Elemento 4 (botones) -->
                            <div class="col-6 col-lg d-flex flex-column justify-content-between mb-1">
                                <button type="button" id="editRow" style="display:none;" class="btn border-2 btn-sm btn-block btn-outline-primary mb-1">
                                    <i class="fas fa-pencil"></i> Editar
                                </button>
                                <button type="button" id="addRow" class="btn btn-sm border-2 btn-block btn-outline-success mb-2">
                                    <i class="fas fa-grid-2-plus"></i> Agregar
                                </button>
                                <button type="button" id="eliRow" style="display:none;" class="m-0 border-2 btn btn-sm btn-block btn-outline-danger">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</section>
<section id="div_hor" class="form-section content" style="display: none;">
    <div class="container-fluid">
        <div class="row" style="align-items:flex-start">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0" id="card-per">
                        <form id="formHorario">
                            <!-- <div class="table-responsive" style="padding:0;border:1px solid #ccc;border-radius: 4px;"> -->
                            <table id="tblPersonH" class="table table-bordered w-100 table-striped table-fix">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="th-orange"><input type="checkbox" id="chkAllP" class="select-all"></th>
                                        <th rowspan="2" class="th-orange">NOMBRES</th>
                                        <th rowspan="2" class="th-orange">OBRA</th>
                                        <th rowspan="2" class="th-orange">FECHA</th>
                                        <th class="th-green" colspan="4">SUELDO Y SOBRETIEMPO</th>
                                        <th class="th-blue" colspan="6">GASTOS EN OBRA</th>
                                        <th rowspan="2" class="th-yellow">JUSTIFICACIÓN</th>
                                    </tr>
                                    <tr>
                                        <th class="th-green">HORARIO NORMAL</th>
                                        <th class="th-green">HORA SUPL.</th>
                                        <th class="th-green">HORA 100%</th>
                                        <th class="th-green">TOTAL HORAS</th>
                                        <th class="th-blue">MATERIAL</th>
                                        <th class="th-blue">TRANSP.</th>
                                        <th class="th-blue">ALIM.</th>
                                        <th class="th-blue">HOSP.</th>
                                        <th class="th-blue">GUARD.</th>
                                        <th class="th-blue">AGUA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <!-- </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="modal_personal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 class="modal-title"><i class="fas fa-calendar-circle-user"></i><span> Selecciona el personal</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div id="div-empleado" class="modal-body " style="padding-block:1rem .5rem">
                    <input type="hidden" id="id" value="">
                    <div id="filtrosRoles" class="row ">
                        <div class="col d-flex flex-wrap justify-content-between align-items-center">
                            <label><input type="checkbox" class="select-all fil-rol" value="1"> Técnico</label>
                            <label><input type="checkbox" class="select-all fil-rol" value="2"> Supervisor</label>
                            <label><input type="checkbox" class="select-all fil-rol" value="3"> Administrativo</label>
                        </div>
                    </div>
                    <!-- <div class="table-responsive"> -->
                    <table id="tblEmpleadoH" class="table table-bordered table-striped table-header">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" id="chkAll" class="select-all"></th>
                                <th>NOMBRES Y APELLIDOS</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- </div> -->
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- <script src="assets/plugins/datatables-keytable/js/keyTable.bootstrap4.min.js"></script> -->
<!-- <script src="assets/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js" type="text/javascript"></script> -->
<script src="assets/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- <script src="assets/plugins/datatables-select/js/dataTables.select.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-select/js/select.bootstrap4.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-scroller/js/dataTables.scroller.min.js" type="text/javascript"></script> -->
<!-- <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/fixedColumns.dataTables.min.js" integrity="sha384-/LxS0b8zEK/HZxykvyTg3o2Ryk2vBESQvW6QMqiUsitINq/Xg5jB4X9KotjCCp3K" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/scroller.dataTables.min.js" integrity="sha384-cCDhK6VsxVGKfl0shwjJr2UXaCzEpxhSnd7C8Uan8yABW71pdY3iaz8aVBklw8uz" crossorigin="anonymous"></script> -->

<script>
    var mostrarCol = '<?php echo $_SESSION["editar20"] || $_SESSION["eliminar20"] ?>';
    var editar = '<?php echo $_SESSION["editar20"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar20"] ?>';
    var collapsedGroups = {};
    configuracionTable = {
        responsive: false,
        dom: 'Ptp',
        lengthChange: false,
        ordering: false,
        autoWidth: true,
        paging: true,
        pageLength: 100,
        scroller: true,
        fixedColumns: {
            leftColumns: 2,
        },
        scrollX: '100%',
        scrollY: 'calc(100vh - 450px)',
        rowGroup: {
            dataSrc: 4,
            startRender: function(rows, group) {
                var collapsed = !!collapsedGroups[group];
                rows.nodes().each(function(r) {
                    $(r).toggleClass('collapsedrow', !collapsed);
                });
                let total = rows.data().toArray().reduce(function(acc, rowData) {
                    // parseFloat por si viene como string; devuelve 0 si no es número
                    return acc + (parseFloat(rowData.total_costo) || 0);
                }, 0);

                let totalStr = total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                var groupText = '<div style="cursor:pointer" class="group-header d-flex align-items-center">' +
                    '<div class="group-title sticky-start">' +
                    '<strong style="padding-left:.5rem">' + group + ' - Total: $' + totalStr + '</strong>' +
                    '</div>' +
                    '<div class="txt-wrap-sm sticky-end">' +
                    (editar ? '<button class="btn pt-0 pb-0 btn-row"><i class="fas fa-pen-to-square"></i></button> ' : '') +
                    (eliminar ? '<button class="btn pt-0 pb-0 btn-row"><i class="fas fa-trash-can"></i></button>' : '') +
                    '</div>' +
                    '</div>';
                // 5) Devolver la fila de grupo con colspan (ajusta a tu número total de columnas)
                return $('<tr/>').
                append('<td class="pr-0 pl-0" colspan="16">' + groupText + '</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
        },
        searchPanes: {
            cascadePanes: true,
            columns: [1, 2, 3], // columnas por las que filtrar
            initCollapsed: true,
            threshold: 0.8,
            dtOpts: {
                select: {
                    style: 'multiple'
                }
            }
        },
        initComplete: function() {
            $('.dtsp-titleRow').remove();
        },
        columnDefs: [{
                targets: 0,
                data: "acciones",
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1;
                    }
                    return meta.row;
                }
            },
            {
                targets: 1,
                data: 'nombres',
                defaultContent: ''
            },
            {
                targets: 2,
                data: 'orden',
                defaultContent: ''
            },
            {
                targets: 3,
                data: 'cliente',
                defaultContent: ''
            },
            {
                targets: 4,
                data: 'hn_val'
            },
            {
                targets: 5,
                data: 'hs_val'
            },
            {
                targets: 6,
                data: 'he_val'
            },
            {
                targets: 7,
                data: 'ht_val'
            },
            {
                targets: 8,
                data: 'adicional_1215'
            },
            {
                targets: 9,
                data: 'decimo_tercer'
            },
            {
                targets: 10,
                data: 'decimo_cuarto'
            },
            {
                targets: 11,
                data: 'vacaciones'
            },
            {
                targets: 12,
                data: 'fondo_reserva'
            },
            {
                targets: 13,
                data: 'costo_mano_obra'
            },
            {
                targets: 14,
                data: 'gasto_en_obra'
            },
            {
                targets: 15,
                data: 'total_costo',
                visible: false,
            },
        ],
        footerCallback: function(row, data, start, end, display) {
            const api = this.api();

            // Obtiene los datos filtrados y visibles de la columna "total_costo" (suponiendo que es la columna 13)
            const total = api
                .column(15, {
                    search: 'applied'
                }) // Asegúrate de usar el índice correcto
                .data()
                .reduce(function(acc, value) {
                    return acc + (parseFloat(value) || 0);
                }, 0);

            // Formatear el total
            const totalStr = total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Escribir en el footer
            const footer = $(api.table().footer());
            footer.find('#totalGeneral').html('$' + totalStr);
        }
        // footerCallback: function(row, data, start, end, display) {
        //     let total = data.reduce(function(acc, row) {
        //         return acc + (parseFloat(row.total_costo) || 0);
        //     }, 0);

        //     let totalStr = total.toLocaleString('en-US', {
        //         minimumFractionDigits: 2,
        //         maximumFractionDigits: 2
        //     });
        //     // console.log('total scr', totalStr);
        //     // Escribe en el <td id="totalGeneral">
        //     $(row).find('td:last').html('$' + totalStr);
        // },
    };

    $('#tblHorario tbody').on('click', 'tr.dtrg-start', function() {
        // if ($(event.target).closest('.txt-wrap-sm').length === 0) {\
        var windowScrollTop = $(window).scrollTop();
        var tableScrollTop = $('#tblHorario_wrapper').scrollTop();
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];

        tabla.rows().invalidate().draw(false); // }
    });



    $(document).ready(function() {
        let accion = 0;
        let anio = year;
        let mes = month;

        let start = moment([anio, mes - 1, 1]);
        let end = moment(start).endOf('month');

        function initDateRange() {
            const start = moment([anio, mes - 1, 1]);
            const end = moment(start).endOf('month');
            const minDate = moment([anio, 0, 1]);
            const maxDate = moment([anio, 11, 31]);

            $('#miRangoFecha').daterangepicker({
                locale: {
                    format: 'D MMM',
                    separator: ' - ',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                    fromLabel: 'Desde',
                    toLabel: 'Hasta',
                    customRangeLabel: 'Personalizado',
                    daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    monthNames: [
                        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ],
                    firstDay: 1
                },
                startDate: start,
                endDate: end,
                minDate: minDate,
                maxDate: maxDate
            });

            // const textoInicial = $('#miRangoFecha').val().toUpperCase();
            // $('#miRangoFecha').val(textoInicial);
        }

        const container = document.getElementById('div-empleado'); // el div padre de tu tabla
        const container2 = document.getElementById('card-per');
        const container3 = document.getElementById('card-hor'); // el div padre de tu tabla
        // el div padre de tu tabla
        const nro_ordenHorario = document.getElementById('nro_ordenHorario');
        const fechaH = document.getElementById('fechaH');

        let datos_em = datos_person.map(item => ({
            label: item.text,
            value: item.text,
            cod: item.id
        }));

        const empleadosMap = new Map(datos_em.map(item => [item.cod, item]));
        const ordenesMap = new Map(items_orden.map(item => [item.cod, item]));
        initDateRange();


        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        });

        setChange(cboAnio, anio);

        $('#miRangoFecha').on('apply.daterangepicker', function(ev, picker) {
            const fechaInicio = picker.startDate.format('YYYY-MM-DD');
            const fechaFin = picker.endDate.format('YYYY-MM-DD');

            console.log("Inicio:", fechaInicio, "Fin:", fechaFin); // verificación

            // // Enviar a tu backend por AJAX
            // $.ajax({
            //     url: 'ruta/tu-backend.php', // ajusta la URL
            //     method: 'POST',
            //     data: {
            //         fecha_inicio: fechaInicio,
            //         fecha_fin: fechaFin
            //     },
            //     success: function(response) {
            //         console.log('Datos recibidos:', response);
            //         // Aquí puedes recargar tu tabla o hacer algo con los datos
            //     },
            //     error: function(xhr, status, error) {
            //         console.error('Error:', error);
            //     }
            // });
        });



        // $(cboMeses).select2({
        //     minimumResultsForSearch: -1,
        //     width: 'calc(100% + .4vw)',
        //     data: datos_meses,
        // });
        // setChange(cboMeses, mes);

        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            anio = a;
            console.log('anio', anio);

            start = moment([anio, mes - 1, 1]);
            end = moment(start).endOf('month');

            const picker = $('#miRangoFecha').data('daterangepicker');
            if (picker) {
                picker.remove();
            }
            // reaplica todas las opciones necesarias
            initDateRange();
            // recarga tu tabla
            tabla.ajax.reload(function() {
                tabla.searchPanes.rebuildPane();
                $('.dtsp-titleRow').remove();
            }, false);
        });

        // $(cboAnio).on("change", function() {
        //     const nuevoAnio = parseInt(this.value);

        //     // mes es el número que ya tienes (1-12)
        //     const start = moment([nuevoAnio, month - 1, 1]);
        //     const end = moment(start).endOf('month');

        //     const picker = $('#miRangoFecha').data('daterangepicker');
        //     picker.setStartDate(start);
        //     picker.setEndDate(end);
        //     picker.minDate = moment([nuevoAnio, 0, 1]);
        //     picker.maxDate = moment([nuevoAnio, 11, 31]);
        //     picker.updateView();
        //     picker.updateCalendars();
        // });

        // $(cboMeses).on("change", function() {
        //     let m = this.value;
        //     if (m == mes) {
        //         return;
        //     }
        //     if (m == 'null') {
        //         mes = null;
        //     } else {
        //         mes = m;
        //     }
        //     anio = cboAnio.options[cboAnio.selectedIndex].text;
        //     tabla.ajax.reload(function() {
        //         tabla.searchPanes.rebuildPane();
        //         $('.dtsp-titleRow').remove();

        //     }, false);
        // });

        clearButtonObraH.addEventListener('click', function() {
            id_orden_horario = null;
        });

        // const observer = new ResizeObserver(() => {
        //     tablaTop.columns.adjust();
        // });

        // const clearButtonObraH = document.getElementById('clearButtonObraH');
        if (!$.fn.DataTable.isDataTable('#tblHorario')) {
            tabla = $("#tblHorario").DataTable({
                "ajax": {
                    "url": "controllers/horario.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.anio = anio;
                        data.mes = null;
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
                localStorage.setItem('horario', JSON.stringify(tablaData));
            });
        }

        tblPerson = $("#tblPersonH").DataTable({
            "dom": 't',
            "lengthChange": false,
            "ordering": false,
            fixedColumns: {
                leftColumns: 2,
                rightColumns: 0
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            paging: false,
            scrollCollapse: true,
            scrollX: true,
            scrollY: '38vh',
            columnDefs: [{
                    targets: 0,
                    "data": null,
                    "defaultContent": '',
                    "className": 'select-checkbox text-center',
                },
                {
                    targets: 1,
                    render: function(data, type, row, meta) {
                        const timestamp = Date.now(); // milisegundos actuales
                        const uniqueId = `empleado_${timestamp}`; // ID único
                        return `<div class="ui-front" style="z-index:99999;position:relative">
                        <input style="width:12em"
                        type="search"
                        class="form-control empleado"
                        id="${uniqueId}"
                        oninput="formatInputOrden(this)"
                        autocomplete="off"
                        placeholder="Empleado"
                        value="${data || ''}">
                        <button class="clear-btn" type="button" onclick="clearInput('${uniqueId}', this)" id="btn${uniqueId}" style="display:none;top:6%;right:2px">&times;</button>
                        <div class="invalid-feedback">*Campo obligatorio.</div>
                        </div>`;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row, meta) {
                        const timestamp = Date.now(); // milisegundos actuales
                        const uniqueId = `obra_${timestamp}`; // ID único
                        return `<div class="ui-front" style="z-index:inherit;position:relative">
                        <input style="width:12rem"
                        type="search"
                        class="form-control obra"
                        id="${uniqueId}"
                        oninput="formatInputOrden(this)"
                        placeholder="Nro. de orden o cliente"
                        autocomplete="off"
                        value="${data || ''}">
                        <button class="clear-btn" type="button" id="btnO${uniqueId}" onclick="clearInput('${uniqueId}', this)" style="display:none;top:6%;right:2px">&times;</button>
                        <div class="invalid-feedback">*Campo obligatorio.</div></div>`;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            // const uniqueId = meta.row + 1;
                            return `<input
                            style="width:10rem"
                            type="date"
                            class="form-control fechaH"
                            value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<input
                        style="width:5rem"
                        autocomplete="off"
                        spellcheck="false"
                        type="text"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control hn text-center"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 5,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        id="id${meta.row + 1}"
                        class="form-control hs text-center"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 6,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control h100 text-center"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 7,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<span class="totalh" style="width:5rem">${data || '8'}</span>`;
                        }
                        return data;
                    }
                },
                {
                    targets: 8,
                    className: "text-center text-nowrap",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `$<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control material text-center d-inline"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 9,
                    className: "text-center text-nowrap",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `$<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control trans text-center d-inline"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 10,
                    className: "text-center text-nowrap",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `$<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control ali text-center d-inline"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 11,
                    className: "text-center text-nowrap",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `$<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control hosp text-center d-inline"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 12,
                    className: "text-center text-nowrap",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `$<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control guard text-center d-inline"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 13,
                    className: "text-center text-nowrap",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `$<input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control agua text-center d-inline"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                {
                    targets: 14,
                    className: "text-center",
                    data: null,
                    defaultContent: `
                        <div class="row">
                            <div class="col-12">
                                <select class="cbo form-control select2 select2-success just" data-dropdown-css-class="select2-dark">
                                    <option value="0">N/A</option>
                                    <option value="1">LIBRE</option>
                                    <option value="2">FALTA</option>
                                    <option value="3">IESS</option>
                                    <option value="4">VACACIONES</option>
                                    <option value="5">FERIADO</option>
                                    <option value="6">SAB</option>
                                    <option value="7">DOM</option>
                                </select>
                            </div>
                        </div>`,
                }
            ],
            createdRow: function(row, data, dataIndex) {
                const $row = $(row);
                const idEmpleado = data._id_person_res || null; // ← aquí está el ID que pasaste

                // Aplicar autocompletado al campo de empleado
                const $inputEmpleado = $row.find(".empleado");
                aplicarAutocomplete($inputEmpleado, datos_em, empleadosMap, idEmpleado, true);

                const idOrden = data._id_orden || null;
                // Aplicar autocompletado al campo de obra
                const $inputObra = $row.find(".obra");
                // console.log('selectedItemOrden', selectedItemOrden);
                aplicarAutocomplete($inputObra, items_orden, ordenesMap, idOrden);
            }

        });


        $('#tblPersonH').on('change', '.cbo', function() {
            const valorSel = $(this).val();
            const textoSel = $(this).find('option:selected').text(); // <-- obtiene el texto visible
            const fila = $(this).closest('tr');

            if (valorSel !== '0') {
                fila.css('background-color', '#fffbe1');
                // Limpiar estilos heredados (si los habías puesto antes como 'inherit')
                fila.find('.empleado, .obra, .fechaH, .select-checkbox, .dtfc-fixed-left, .hn').css('background-color', 'inherit');
                // Desactiva el input .obra y le asigna el texto del <option> seleccionado
                fila.find('.hn').prop('disabled', true).val('');
                fila.find('.obra').prop('disabled', true).val(textoSel);
            } else {
                fila.css('background-color', '');
                fila.find('.empleado, .obra, .fechaH, .select-checkbox, .dtfc-fixed-left, .hn').css('background-color', '');
                fila.find('.obra').prop('disabled', false).val(''); // limpia si vuelves a N/A
                fila.find('.hn').prop('disabled', false).val('8');
            }
        });

        document.querySelector('#tblPersonH').addEventListener('keydown', function(event) {
            const key = event.key;
            const active = document.activeElement;

            if (!active || active.tagName !== 'INPUT') return;

            // Verificar si el autocomplete está abierto
            const isAutocompleteOpen = $(".ui-menu:visible").length > 0;
            if (isAutocompleteOpen) return;

            const td = active.closest('td');
            const tr = td?.parentElement;
            if (!td || !tr) return;

            const cellIndex = Array.from(tr.children).indexOf(td);
            const rowIndex = Array.from(tr.parentElement.children).indexOf(tr);

            const allRows = Array.from(document.querySelectorAll('#tblPersonH tbody tr'));
            const maxRow = allRows.length - 1;
            const maxCell = tr.children.length - 1;

            let nextRowIndex = rowIndex;
            let nextCellIndex = cellIndex;

            switch (key) {
                case 'ArrowRight':
                    nextCellIndex++;
                    break;
                case 'ArrowLeft':
                    nextCellIndex--;
                    break;
                case 'ArrowDown':
                    nextRowIndex++;
                    break;
                case 'ArrowUp':
                    nextRowIndex--;
                    break;
                default:
                    return; // No hacer nada si no es una flecha
            }

            event.preventDefault();

            // Bucle para buscar la siguiente celda válida con input
            while (nextRowIndex >= 0 && nextRowIndex <= maxRow) {
                const row = allRows[nextRowIndex];
                if (!row) break;

                let nextCell = row.children[nextCellIndex];
                if (nextCellIndex < 0 || nextCellIndex > maxCell) break;

                const input = nextCell.querySelector('input');
                if (input) {
                    input.focus();
                    input.select();
                    input.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                        inline: 'center'
                    });
                    return;
                }
                // Si no encontró input, ajusta solo columnas (no avanza fila)
                if (key === 'ArrowRight') nextCellIndex++;
                else if (key === 'ArrowLeft') nextCellIndex--;
                else if (key === 'ArrowDown' || key === 'ArrowUp') break;
            }
        });

        tblEmpleadoH = $("#tblEmpleadoH").DataTable({
            "ajax": {
                "url": "controllers/combo.controlador.php",
                "type": "POST",
                "dataSrc": '',
                data: function(data) {
                    data.accion = 7;
                    data.invertido = true;
                }
            },
            "dom": 'ft',
            "lengthChange": false,
            "ordering": false,
            "paging": false,
            "autoWidth": false,
            scrollY: "50vh",
            scrollX: false,
            scrollCollapse: true,
            "columns": [{
                    "data": null,
                    "defaultContent": '',
                    "className": 'select-checkbox text-center',
                },
                {
                    "data": "nombre"

                },
                {
                    visible: false,
                }
            ],
            select: {
                style: 'multi',
                selector: 'td'
            },
        });

        const observer = new ResizeObserver(() => {
            tblEmpleadoH.columns.adjust();
        });

        observer.observe(container);

        const observer2 = new ResizeObserver(() => {
            tblPerson.columns.adjust();
        });

        observer2.observe(container2);

        const observer3 = new ResizeObserver(() => {
            tabla.columns.adjust();
        });

        observer3.observe(container3);

        let lastSelectedIndex = null;

        $('#tblPersonH tbody').on('click', 'td.select-checkbox', function(e) {
            const row = $(this).closest('tr');
            const currentIndex = tblPerson.row(row).index();

            if (e.shiftKey && lastSelectedIndex !== null && lastSelectedIndex !== currentIndex) {
                // Rango de selección con Shift
                const [start, end] = currentIndex > lastSelectedIndex ? [lastSelectedIndex, currentIndex - 1] : [currentIndex, lastSelectedIndex - 1];
                for (let i = start; i <= end; i++) {
                    tblPerson.row(i).select();
                }
            }
            lastSelectedIndex = currentIndex;
        });

        let lastSelectedIndexE = null;

        $('.select-all').on('change', function() {
            const rolSeleccionado = $(this).val();
            const checked = $(this).is(':checked');
            // Recorre todas las filas de la tabla
            tblEmpleadoH.rows().every(function() {
                const data = this.data();

                if (data.rol == rolSeleccionado) {
                    if (checked) {
                        this.select();
                    } else {
                        this.deselect();
                    }
                }
            });
        });

        $('#tblPersonH').on('input keydown', '.hn, .hs, .h100', function() {
            // Encuentra la fila del input cambiado
            let $row = $(this).closest('tr');
            // Obtiene los valores de cantidad y precio
            let hn = parseFloat($row.find('.hn').val()) || 0;
            let hs = parseFloat($row.find('.hs').val()) || 0;
            let h100 = parseFloat($row.find('.h100').val()) || 0;
            // Calcula el total
            let total_horas = hn + hs + h100;
            $row.find('.totalh').text(total_horas);

        });

        $("#addRow").on("click", function() {
            let dateH = fechaH.value;
            let selectedData = tblEmpleadoH.rows({
                selected: true
            }).data().toArray();

            const filas = [];
            const selectedItemOrden = ordenesMap.get(id_orden_horario) || null;

            if (selectedData.length > 0) {
                for (const empleado of selectedData) {
                    const rowData = ['', '', '', dateH, 8, '', '', '', '', '', '', '', '', ''];
                    rowData._id_person_res = empleado.id;
                    rowData._id_orden = selectedItemOrden;
                    filas.push(rowData);
                }
            } else {
                const rowData = ['', '', '', dateH, 8, '', '', '', '', '', '', '', '', ''];
                rowData._id_orden = selectedItemOrden;
                filas.push(rowData);
            }

            tblPerson.rows.add(filas).draw(false);
            tblEmpleadoH.rows().deselect();
            $('.fil-rol').prop('checked', false);
            $('#selected-person').text(0);
            $('#chkAll').prop('checked', false);
        });


        function aplicarAutocomplete($input, sourceData, dataMap, idSeleccionado = null, search = false) {
            $input.autocomplete({
                source: sourceData,
                autoFocus: true,
                appendTo: "body",
                focus: () => false,
                select: handleAutocompleteSelect,
            });

            if (idSeleccionado != null) {
                let selectedItem;
                if (search) {
                    selectedItem = dataMap.get(idSeleccionado);
                } else {
                    selectedItem = idSeleccionado
                }
                $input.val(selectedItem.label);
                $input.autocomplete("instance")._trigger("select", null, {
                    item: selectedItem
                });
            }
        }

        function handleAutocompleteSelect(event, ui) {
            this.readOnly = true;
            $(this).attr("data-id", ui.item.cod);
            const btn = this.parentElement.querySelector("button");
            if (btn) btn.style.display = "block";
        }

        $('#editRow').on('click', function() {
            const selectedRows = tblPerson.rows({
                selected: true
            });
            const dateH = fechaH.value;
            if (!dateH) {
                alert('Debe seleccionar una fecha válida.');
                return;
            }

            const selectedItem = items_orden.find(item => item.cod === id_orden_horario);
            selectedRows.every(function() {
                const $row = $(this.node());
                // Actualizar campo fecha
                $row.find('td:eq(3) input[type="date"]').val(dateH);
                // Obtener elementos del campo obra
                const $inputObra = $row.find('td:eq(2) input.obra');
                const $btnClear = $row.find('td:eq(2) .clear-btn');

                if (selectedItem) {
                    $inputObra.val(selectedItem.label);
                    $inputObra.autocomplete("instance")._trigger("select", null, {
                        item: selectedItem
                    });
                } else {
                    $inputObra.val('');
                    $btnClear.trigger('click'); // limpia si no hay item válido
                }
                this.deselect();

            });
        });

        $('#eliRow').on('click', function() {
            // confirmarEliminar('esta(s)', 'filas', function(res) {
            //     if (res) {
            let selectedRows = tblPerson.rows({
                selected: true
            });
            selectedRows.remove().draw(false); // Elimina y redibuja la tabla sin reiniciar la paginación
        });

        tblPerson.on('draw', function() {
            let totalRows = tblPerson.rows().count();
            let selectedCount = tblPerson.rows({
                selected: true
            }).count();

            if (totalRows === 0) {
                $('#addRow').show();
                $('#editRow').hide();
                $('#eliRow').hide();
                $('#chkAllP').prop('checked', false);
            }

            if (selectedCount === 0) {
                $('#editRow').hide();
                $('#eliRow').hide();
                $('#addRow').show();

            }

            // if (totalRows === 0) {
            //     $('#addRow').show();
            // } else {
            //     $('#addRow').hide();
            // }
        });

        $('#chkAll').on('click', function() {
            if (this.checked) {
                tblEmpleadoH.rows().select();
            } else {
                tblEmpleadoH.rows().deselect();
            }
        });

        $('#chkAllP').on('click', function() {
            if (this.checked) {
                tblPerson.rows().select();
            } else {
                tblPerson.rows().deselect();
            }
        });

        // Escuchar selección de filas
        tblPerson.on('select deselect', function() {
            const totalRows = tblPerson.rows().count();

            // Total de filas seleccionadas (sin importar el filtro)
            const selectedRows = tblPerson.rows({
                selected: true
            }).count();

            // Solo marcar el checkbox "chkAll" si TODAS las filas (no solo las filtradas) están seleccionadas
            $('#chkAllP').prop('checked', totalRows > 0 && selectedRows === totalRows);

            if (selectedRows > 0) {
                $('#editRow').show();
                $('#eliRow').show();
                $('#addRow').hide();
            } else {
                $('#editRow').hide();
                $('#eliRow').hide();
                $('#addRow').show();
                $('#chkAllP').prop('checked', false);
            }
        });

        let id_orden_horario = 0;

        cargarAutocompletado(function(items) {
            $(nro_ordenHorario).autocomplete({
                source: items,
                minLength: 1,
                autoFocus: true,
                focus: function() {
                    return false;
                },
                select: function(event, ui) {
                    nro_ordenHorario.readOnly = true;
                    id_orden_horario = ui.item.cod;
                    clearButtonObraH.style.display = "block";
                },
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                // let res = item.cantidad.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return $("<li>").append(
                    "<div>" + item.label + "<div class='d-flex justify-content-between align-items-center'><strong class='large-text'>ESTADO: " +
                    item.cantidad + " </strong><span>AÑO: " + item.anio + "</span></div></div>"
                ).appendTo(ul);
            };
        }, null, 'orden', 6);

        tblEmpleadoH.on('deselect', function() {
            $('#chkAll').prop('checked', false);
        });

        tblPerson.on('deselect', function() {
            $('#chkAllP').prop('checked', false);
        });

        tblEmpleadoH.on('select deselect', function() {
            // Total de todas las filas (sin importar el filtro)
            const totalRows = tblEmpleadoH.rows().count();
            // Total de filas seleccionadas (sin importar el filtro)
            const selectedRows = tblEmpleadoH.rows({
                selected: true
            }).count();

            // Solo marcar el checkbox "chkAll" si TODAS las filas (no solo las filtradas) están seleccionadas
            $('#chkAll').prop('checked', totalRows > 0 && selectedRows === totalRows);

            // Actualiza el contador en el span (puedes decidir si quieres que este cuente todas o solo las filtradas)
            $('#selected-person').text(selectedRows);
        });

        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            // div_placa = document.getElementById('div_placa'),
            modalS = document.getElementById('modalS'),
            elementsE = document.querySelectorAll('#modalS .bg-gradient-green'),
            select = document.querySelectorAll('.modal-body select.select2'),
            formS = document.getElementById('formNuevoS'),
            spanE = document.querySelector('#span-title span'),
            iconElement = document.querySelector('#span-title i'),
            inputContent = document.getElementById('nombreS'),
            inputId = document.getElementById('idS'),
            btnNuevo = document.getElementById('btnNuevo'),
            btnReturn = document.getElementById('btnReturn');

        let scrollPos = 0;

        // const id = document.getElementById('id'),
        //     cedula = document.getElementById('cedula'),
        //     nombre = document.getElementById('nombre'),
        //     apellido = document.getElementById('apellido'),
        //     celular = document.getElementById('celular');


        OverlayScrollbars(document.querySelector('.scroll-modal'), {
            autoUpdate: true,
            scrollbars: {
                autoHide: 'leave'
            }
        });

        btnReturn.addEventListener('click', () => showHideFormSection('none', 'block', tblPerson, scrollPos));

        function showHideFormSection(dis1, dis2, dtabla) {
            console.log('showHideFormSection', 'esgto awio');
            document.querySelectorAll('.form-section').forEach(el => {
                el.style.display = dis1;
            });
            document.querySelectorAll('.ini-section').forEach(el => {
                el.style.display = dis2;
            });
            tabla.columns.adjust().draw(false);
            dtabla.clear().draw();
            window.scrollTo(0, scrollPos);
        }

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                showHideFormSection('block', 'none', tblPerson);
                scrollPos = window.scrollY || document.documentElement.scrollTop;

            });
        }

        $('#tblHorario tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_e = e["id"];
            console.log(id_e)
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_e);
            accion = 0;
            confirmarEliminar('este', 'horario de empleado', function(r) {
                if (r) {
                    confirmarAccion(src, 'horario', tabla, '', function(r) {
                        cargarCombo('Conductor', '', 2);
                        cargarCombo('Despachado', '', 6);
                        cargarCombo('Responsable', '', 7)
                    })
                }
            });
        });

        $('#tblHorario tbody').on('click', '.btnEditar', function() {
            // let row = obtenerFila(this, tabla);
            // accion = 2;
            // const icon = document.querySelector('.modal-title i');
            // cambiarModal(span, ' Editar Horario', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            // select.forEach(function(s) {
            //     s.classList.remove('select2-success');
            //     s.classList.add('select2-warning');
            // });
            // id.value = row["id"];
            // nombre.value = row["nombre"];
            // cedula.value = row["cedula"];
            // apellido.value = row["apellido"];
            // celular.value = row["telefono"];
            // setChange(cboEmpresa, row["id_empresa"])
            // setChange(cboRol, row["id_rol"])
            // let arr = convertirArray(row["id_placa"])
            // $(cboPlaca).val(arr).trigger('change');
        });

        $('#btnGuardarHorario').on('click', function() {
            const rows = tblPerson.rows().nodes();
            const datos = [];

            $(rows).each(function() {
                const $row = $(this);
                // Obtener los IDs reales
                const id_empleado = $row.find('.empleado').attr('data-id') || null;

                const fecha = $row.find('.fechaH').val() || '';

                const justificacion = $row.find('.just').val(); // Valor del select

                // Si el campo justificación es diferente de "0" (es decir, hay justificación)
                if (justificacion !== "0") {
                    if (id_empleado === null || fecha === '') {
                        mostrarToast('warning', "Advertencia", "fa-triangle-exclamation",
                            'Por favor completa los campo(s) en las fila(s) a justificar', 4000
                        );
                        return false;
                    } else {
                        datos.push({
                            id_empleado,
                            id_orden: null,
                            fecha,
                            hn: null,
                            hs: null,
                            he: null,
                            material: null,
                            trans: null,
                            ali: null,
                            hosp: null,
                            guard: null,
                            agua: null,
                            justificacion,
                        });
                    }
                    return;
                    // Saltar al siguiente row
                }
                const id_obra = $row.find('.obra').attr('data-id') || null;

                const hn = parseFloat($row.find('.hn').val()) || null;
                const hs = parseFloat($row.find('.hs').val()) || null;
                const he = parseFloat($row.find('.h100').val()) || null;

                const material = parseFloat($row.find('.material').val()) || null;
                const trans = parseFloat($row.find('.trans').val()) || null;
                const ali = parseFloat($row.find('.ali').val()) || null;
                const hosp = parseFloat($row.find('.hosp').val()) || null;
                const guard = parseFloat($row.find('.guard').val()) || null;
                const agua = parseFloat($row.find('.agua').val()) || null;

                // if (id_empleado && id_obra && fecha) {
                datos.push({
                    id_empleado,
                    id_orden: id_obra,
                    fecha,
                    hn,
                    hs,
                    he,
                    material,
                    trans,
                    ali,
                    hosp,
                    guard,
                    agua,
                    justificacion: null
                });
            });
            // Ahora puedes enviar 'datos' al servidor con AJAX como ya vimos
            console.log("Datos a guardar:", datos);
            // 👉 Aquí haces el envío AJAX
            if (datos.length > 0) {
                $.ajax({
                    url: 'controllers/horario.controlador.php', // Cambia a tu ruta real
                    method: 'POST',
                    dataType: 'json',
                    // contentType: 'application/json',
                    data: {
                        accion: 1,
                        registros: JSON.stringify(datos) // Importante: lo mandas como string normal
                    },
                    success: function(resp) {
                        const isSuccess = resp.status === "success";
                        mostrarToast(resp.status,
                            isSuccess ? "Completado" : "Error",
                            isSuccess ? "fa-check" : "fa-xmark",
                            resp.m);

                        if (isSuccess) {
                            showHideFormSection('none', 'block', tblPerson, scrollPos);
                        }
                        if (tabla) {
                            tabla.ajax.reload(null, false); // Recargar tabla si es necesario
                        }
                    },
                });
            } else {
                mostrarToast(
                    'warning',
                    "Advertencia",
                    "fa-triangle-exclamation",
                    'No hay datos para guardar', 4000
                );
            }
        });
    })
</script>