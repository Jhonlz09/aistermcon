<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ingreso personal</title>
    <script src="assets/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js" type="text/javascript"></script>
    <script src="assets/plugins/moment/moment-with-locales.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
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
            <div class="col-sm">
                <div class="row">
                    <div class="col-6">
                        <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="cboMeses1" id="cboMeses1" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                            <option value="null">TODO</option>
                            <!-- <option data-hide='1' style="display:none;padding:0;" value="0"></option> -->
                        </select>
                    </div>

                </div>

            </div>
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
                                <div class="col-auto pl-0">
                                    <ul class="nav nav-tabs" style="border-bottom:none;" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a style="padding: .75rem;border-right:1px solid #dfdfdf" class="nav-link active" id="custom-tabs-dia-tab" data-toggle="pill" href="#custom-tabs-dia" role="tab" aria-controls="custom-tabs-dia" aria-selected="true">POR DIA</a>
                                        </li>
                                        <li class="nav-item">
                                            <a style="padding: .75rem;border-left:none" class="nav-link" id="custom-tabs-orden-tab" data-toggle="pill" href="#custom-tabs-orden" role="tab" aria-controls="custom-tabs-orden" aria-selected="false">GASTOS</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-3" style="padding-block:.4rem;">
                                    <div class="input-group-text" style="background:#eceef1;color:#494c50!important;padding-inline:.5rem">
                                        <span><i class="fas fa-calendar-range"></i></span>
                                        <input autocomplete="off" style="border:none" type="text" id="miRangoFecha" readonly class="form-control" placeholder="Selecciona un rango" />
                                    </div>

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
                                            <th colspan="3" class="th-green">GENERAL</th>
                                            <!-- <th rowspan="2" class="th-green">GASTO EN OBRA</th> -->
                                            <th rowspan="2" class="th-yellow">ACCIONES</th>
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
                                            <th class="th-green">MANO OBRA</th>
                                            <th class="th-green">GASTO OBRA</th>
                                            <th class="th-green">TOTAL COSTO</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4"><strong>Total general:</strong></th>
                                            <th colspan="12" style="color:#208520" id="totalGeneral"></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-orden" role="tabpanel" aria-labelledby="custom-tabs-orden-tab">
                                <table id="tblGastos" cellspacing="0" class="display table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="th-orange text-center">Nº</th>
                                            <th rowspan="2" class="th-orange">NOMBRES</th>
                                            <th rowspan="2" class="th-orange">Nº DE ORDEN</th>
                                            <th rowspan="2" class="th-orange">CLIENTE</th>
                                            <th class="th-blue" colspan="7">GASTOS EN OBRA</th>
                                        </tr>
                                        <tr>
                                            <th class="th-blue">MATERIAL</th>
                                            <th class="th-blue">TRANSP.</th>
                                            <th class="th-blue">ALIM.</th>
                                            <th class="th-blue">HOSP.</th>
                                            <th class="th-blue">GUARD.</th>
                                            <th class="th-blue">AGUA</th>
                                            <th style="color:#1f3853" class="th-dark-blue">TOTAL</th>
                                            <!-- <th class="text-center">ACCIONES</th> -->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3"><strong>Total general:</strong></th>
                                            <th colspan="8" id="totalGeneralGastos"></th>
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
                                <input type="search" id="nro_ordenHorario" class="form-control ui-autocomplete-input" placeholder="Nro. de orden o cliente" autocomplete="off" style="font-size:1.2rem; border-bottom:2px solid var(--select-border-bottom);" oninput="formatInputOrden(this)" spellcheck="false" data-ms-editor="true">
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
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h4 class="modal-title"><i class="fas fa-calendar-circle-user"></i><span> Editar Horario</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditar" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal" style="padding-block:1rem .5rem">
                    <input type="hidden" id="id_horario" value="">
                    <div class="col-md-12 pb-3">
                        <div class="row">
                            <div class="col-md-4 ui-front">
                                <label class="col-form-label combo ml-1" for="empleado_edit">
                                    <i class="fas fa-user-helmet-safety"></i> Empleado</label>
                                <input
                                    type="search"
                                    class="form-control empleado"
                                    id="empleado_edit"
                                    autocomplete="off"
                                    placeholder="Empleado"
                                    value="">
                                <button class="clear-btn" type="button" onclick="clearInput('empleado_edit', this)" style="display:none;top:40%;">&times;</button>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                            <div class="col-md-4">
                                <div class="ui-front" style="z-index:inherit;position:relative">
                                    <label class="col-form-label combo" for="orden_edit">
                                        <i class="fas fa-person-digging"></i> Obra</label>
                                    <input
                                        type="search"
                                        class="form-control obra"
                                        id="orden_edit"
                                        oninput="formatInputOrden(this)"
                                        placeholder="Nro. de orden o cliente"
                                        autocomplete="off"
                                        value="">
                                    <button class="clear-btn" type="button" id="btnOrden_edit" onclick="clearInput('orden_edit', this)" style="display:none;top:40%">&times;</button>
                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_edit" class="m-0"><i class="fas fa-calendar"></i> Fecha</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="fecha_edit"
                                    value="">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tblHorarioE" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="th-green" colspan="4">SUELDO Y SOBRETIEMPO</th>
                                    <th class="th-blue" colspan="6">GASTOS EN OBRA</th>
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
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardarE" class="btn bg-gradient-blue"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- <script src="assets/plugins/datatables-keytable/js/keyTable.bootstrap4.min.js"></script> -->
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
        dom: 'Ptp',
        lengthChange: false,
        ordering: false,
        autoWidth: false,
        paging: true,
        pageLength: 100,
        scroller: true,
        fixedColumns: {
            leftColumns: 2,
            rightColumns: 1,
        },
        scrollX: true,
        scrollY: 'calc(100vh - 455px)',
        rowGroup: {
            dataSrc: 4,
            startRender: function(rows, group) {
                const collapsed = !!collapsedGroups[group];
                const nodeList = rows.nodes().toArray();

                // Aplicar clase 'collapsedrow' según el estado del grupo
                nodeList.forEach(node => {
                    node.classList.toggle('collapsedrow', collapsed);
                });

                // Calcular total
                const total = rows
                    .data()
                    .toArray()
                    .reduce((acc, row) => {
                        const num = parseFloat(
                            String(row.total_costo).replace(/[^0-9.-]+/g, '')
                        );
                        return acc + (isNaN(num) ? 0 : num);
                    }, 0);
                const totalStr = total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Construir contenido del grupo
                //         const actionButtons = `
                // ${editar ? '<button class="btn pt-0 pb-0 btn-row"><i class="fas fa-pen-to-square"></i></button>' : ''}
                // ${eliminar ? '<button class="btn pt-0 pb-0 btn-row"><i class="fas fa-trash-can"></i></button>' : ''}`;
                const actionButtons = ""
                const groupText = `<div style="cursor:pointer" class="group-header d-flex align-items-center">
                                    <div class="group-title sticky-start">
                                        <strong style="padding-left:.5rem">${group} (${rows.count()} personas) - Total: $${totalStr}</strong>
                                    </div>
                                    <div class="txt-wrap-sm sticky-end">
                                        ${actionButtons}
                                    </div>
                                </div>`;

                return $('<tr/>')
                    .append(`<td class="pr-0 pl-0" style="padding:.7rem" colspan="17">${groupText}</td>`)
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
                data: null,
                className: "text-center",
                defaultContent: '' // lo llenaremos luego en el evento draw
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
                visible: true,
            },
            {
                targets: 16,
                data: null,
                visible: mostrarCol == 1 ? true : false,
                render: function(data, type, row, full, meta) {
                    return "<center style='white-space:nowrap'>" +
                        (editar ?
                            "<button class='btn border-2 btn-sm btn-outline-primary btnEditar mr-1' data-toggle='modal' data-target='#modal' title='Editar'>" +
                            "<i class='fa-solid fa-pencil'></i></button>" : ""
                        ) +
                        (eliminar ?
                            "<button class='border-2 btn btn-sm btn-outline-danger btnEliminar' title='Eliminar'>" +
                            "<i class='fa fa-trash'></i></button>" : ""
                        ) +
                        "</center>";
                }
            },

        ],
        footerCallback: function(row, data, start, end, display) {
            const api = this.api();
            // Suma la columna 15 (ajusta el índice si hace falta)
            const total = api.column(15, {
                    search: 'applied'
                })
                .data()
                .reduce((acc, value) => {
                    // Limpia el string: quita todo lo que no sea dígito, punto o signo menos
                    const num = parseFloat(
                        String(value).replace(/[^0-9.-]+/g, '')
                    );
                    return acc + (isNaN(num) ? 0 : num);
                }, 0);

            // Formatea el total de vuelta a money
            const totalStr = total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Escribe en el footer
            const footer = $(api.table().footer());
            footer.find('#totalGeneral').html('$' + totalStr);
        }
    };

    $('#tblHorario tbody').on('click', 'tr.dtrg-start', function() {
        const $this = $(this);
        const name = $this.data('name');
        const wasCollapsed = collapsedGroups[name];
        collapsedGroups[name] = !wasCollapsed;

        tabla.draw(false);

        tabla.columns.adjust();
        if (tabla.fixedHeader) {
            tabla.fixedHeader.adjust();
        }
    });


    // Reiniciar contadores en cada draw

    $(document).ready(function() {
        let accion = 0;
        let anio = year;
        let mes = month;
        let id_horario_editar = 0;
        let start = moment([anio, mes - 1, 1]);
        let end = moment(start).endOf('month');

        function initDateRange(startD, endD) {

            const minDate = moment([anio, 0, 1]);
            const maxDate = moment([anio, 11, 31]);
            $('#miRangoFecha').daterangepicker({
                locale: {
                    format: 'D MMM YY',
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
                startDate: startD,
                endDate: endD,
                minDate: minDate,
                maxDate: maxDate
            }, function(startSelected, endSelected) {
                start = startSelected;
                end = endSelected;
                $(cboMeses1).val('0').trigger('change');
                tabla.ajax.reload();
            });
        }
        initDateRange(start, end);
        const container = document.getElementById('div-empleado'); // el div padre de tu tabla
        const container2 = document.getElementById('card-per');
        const container3 = document.getElementById('custom-tabs-dia'); // el div padre de tu tabla
        const container4 = document.getElementById('custom-tabs-orden');

        const nro_ordenHorario = document.getElementById('nro_ordenHorario');
        const fechaH = document.getElementById('fechaH');

        let datos_em = datos_person.map(item => ({
            label: item.text,
            value: item.text,
            cod: item.id
        }));

        function toggleCustomFilterButtons() {
            $('.dtsp-searchPane').each(function() {
                const $pane = $(this);
                const hasSelected = $pane.find('.dtsp-selected').length > 0;
                const $collapseBtn = $pane.find('.dtsp-collapseButton');

                // Quitar botón anterior si existe
                $pane.find('.custom-filter-icon').remove();

                if (hasSelected) {
                    // Crear botón personalizado con funcionalidad
                    const $customBtn = $(`<button type="button" class="btn btn-light custom-filter-icon" title="Limpiar filtro">
                            <i class="fas fa-filter fa-sm text-primary"></i>
                            </button>`);

                    // Al hacer hover: cambia el ícono a "X"
                    $customBtn.hover(
                        function() {
                            $(this).find('i').removeClass('fa-filter fa-sm').addClass('fa-xmark fa-lg');
                        },
                        function() {
                            $(this).find('i').removeClass('fa-xmark fa-lg').addClass('fa-filter fa-sm');
                        }
                    );

                    // Al hacer clic: limpia el filtro del panel actual
                    $customBtn.on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // 🔒 Detiene la propagación

                        // Llamar a .clearButton pero simulando click sin eventos que suban
                        const $clearBtn = $pane.find('.clearButton');
                        if ($clearBtn.length) {
                            // Crea un evento click sin burbujeo
                            const clickEvent = new MouseEvent("click", {
                                bubbles: false, // 🔑 esto evita la propagación ascendente
                                cancelable: true
                            });
                            $clearBtn[0].dispatchEvent(clickEvent);
                        }
                    });

                    // Insertar el botón justo después del de colapso
                    $collapseBtn.parent().append($customBtn);
                }
            });
        }


        $('#tblHorario').on('searchPanes.dt draw.dt', function() {
            toggleCustomFilterButtons();

        });

        $('#tblGastos').on('searchPanes.dt draw.dt', function() {
            toggleCustomFilterButtons();
        });

        const fecha_edit = document.getElementById('fecha_edit');
        const empleadosMap = new Map(datos_em.map(item => [item.cod, item]));
        const ordenesMap = new Map(items_orden.map(item => [item.cod, item]));

        $(cboAnio).select2({
            width: '100%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        });

        setChange(cboAnio, anio);

        // $('#miRangoFecha').on('apply.daterangepicker', function(ev, picker) {
        //     const fechaInicio = picker.startDate.format('YYYY-MM-DD');
        //     const fechaFin = picker.endDate.format('YYYY-MM-DD');

        //     console.log("Inicio:", fechaInicio, "Fin:", fechaFin); // verificación

        //     // // Enviar a tu backend por AJAX
        //     // $.ajax({
        //     //     url: 'ruta/tu-backend.php', // ajusta la URL
        //     //     method: 'POST',
        //     //     data: {
        //     //         fecha_inicio: fechaInicio,
        //     //         fecha_fin: fechaFin
        //     //     },
        //     //     success: function(response) {
        //     //         console.log('Datos recibidos:', response);
        //     //         // Aquí puedes recargar tu tabla o hacer algo con los datos
        //     //     },
        //     //     error: function(xhr, status, error) {
        //     //         console.error('Error:', error);
        //     //     }
        //     // });
        // });

        $(cboMeses1).select2({
            minimumResultsForSearch: -1,
            width: '100%',
            data: datos_meses,
            placeholder: 'SELECCIONE',
            // allowClear: true,
            // templateResult: function(data) {
            //     if (data.id === 13) {
            //         // Opción oculta en el menú desplegable
            //         return null;
            //     }
            //     return data.text;
            // },
            // templateSelection: function(data) {
            //     return data.text;
            // }
        });
        setChange(cboMeses1, mes);

        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            if (mes == null) {
                start = moment([a, 0, 1]);
                end = moment(start).endOf('year');
            } else {
                start = moment([a, mes - 1, 1]);
                end = moment(start).endOf('month');
            }
            anio = a;
            // console.log('anio', anio);

            // start = moment([anio, mes - 1, 1]);
            // end = moment(start).endOf('month');

            const picker = $('#miRangoFecha').data('daterangepicker');
            if (picker) {
                picker.remove();
            }
            // reaplica todas las opciones necesarias
            initDateRange(start, end);
            // recarga tu tabla
            tabla.ajax.reload(function() {
                tabla.searchPanes.rebuildPane();
                $('.dtsp-titleRow').remove();
            }, false);
            tblGastos.ajax.reload(function() {
                tblGastos.searchPanes.rebuildPane();
                $('.dtsp-titleRow').remove();
            }, false);
        });

        $(cboMeses1).on("change", function() {
            let m = this.value;
            console.log('m', m);
            if (m == '') {
                return;
            }
            console.log('mes', mes);
            if (m == 'null') {
                mes = null;
                start = moment([anio, 0, 1]);
                end = moment(start).endOf('year');
            } else {
                mes = m;
                start = moment([anio, mes - 1, 1]);
                end = moment(start).endOf('month');
            }
            anio = cboAnio.options[cboAnio.selectedIndex].text;
            const picker = $('#miRangoFecha').data('daterangepicker');
            if (picker) {
                picker.remove();
            }
            initDateRange(start, end);

            tabla.ajax.reload(function() {
                tabla.searchPanes.rebuildPane();
                $('.dtsp-titleRow').remove();
            }, false);

            tblGastos.ajax.reload(function() {
                tblGastos.searchPanes.rebuildPane();
                $('.dtsp-titleRow').remove();
            }, false);
        });

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
                        data.start = start.format('YYYY-MM-DD');
                        data.end = end.format('YYYY-MM-DD');
                    }
                },
                // stateSave: true,
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                let currentGroup = null;
                let counter = 0;

                tabla.rows({
                    page: 'current',
                    search: 'applied'
                }).every(function() {
                    const row = this.node();
                    const data = this.data();
                    const group = data[4]; // usa el índice correcto de tu agrupación

                    // Cuando cambia el grupo, reiniciamos el contador
                    if (group !== currentGroup) {
                        currentGroup = group;
                        counter = 1;
                    } else {
                        counter++;
                    }

                    // Escribimos el número en la primera celda de esta fila
                    $('td:eq(0)', row).html(counter);
                });
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

        let tblHorarioE = $("#tblHorarioE").DataTable({
            "dom": 't',
            "lengthChange": false,
            "ordering": false,
            autoWidth: false,
            ajax: {
                url: 'controllers/horario.controlador.php',
                type: 'POST',
                dataSrc: '',
                data: function(data) {
                    data.accion = 2;
                    data.id = id_horario_editar;
                }
            },
            paging: false,
            columnDefs: [{
                    targets: 0,
                    className: "text-center d-flex justify-content-center",
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
                    targets: 1,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `
                    <div class="d-flex justify-content-center align-items-center" style="height:100%;">
                            <input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        
                        class="form-control hs text-center"
                        value="${data || ''}">
                        </div>`;
                        }
                        return data;
                    }
                },
                {
                    targets: 2,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `
                                    <div class="d-flex justify-content-center align-items-center" style="height:100%;">

                            <input
                        style="width:5rem"
                        type="text"
                        autocomplete="off"
                        spellcheck="false"
                        oninput="validarNumber(this,/[^0-9.]/g)"
                        onpaste="validarPegado(this, event)"
                        inputmode="numeric"
                        maxlength="4"
                        class="form-control h100 text-center"
                        value="${data || ''}">
                         </div>`;
                        }
                        return data;
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<span class="totalh" style="width:5rem">${data || '8'}</span>`;
                        }
                        return data;
                    }
                },
                {
                    targets: 4,
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
                    targets: 5,
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
                    targets: 6,
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
                    targets: 7,
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
                        class="form-control guard text-center d-inline"
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
                        class="form-control agua text-center d-inline"
                        value="${data || ''}">`;
                        }
                        return data;
                    }
                },
                // {
                //     targets: 13,
                //     className: "text-center",
                //     data: null,
                //     defaultContent: `
                //         <div class="row">
                //             <div class="col-12">
                //                 <select class="cbo form-control select2 select2-success just" data-dropdown-css-class="select2-dark">
                //                     <option value="0">N/A</option>
                //                     <option value="1">LIBRE</option>
                //                     <option value="2">FALTA</option>
                //                     <option value="3">IESS</option>
                //                     <option value="4">VACACIONES</option>
                //                     <option value="5">FERIADO</option>
                //                     <option value="6">SAB</option>
                //                     <option value="7">DOM</option>
                //                 </select>
                //             </div>
                //         </div>`,
                // }
            ],
            // createdRow: function(row, data, dataIndex) {
            //     const $row = $(row);
            //     const idEmpleado = data.id_empleado || null; // ← aquí está el ID que pasaste

            //     // Aplicar autocompletado al campo de empleado
            //     const $inputEmpleado = $row.find(".empleado");
            //     aplicarAutocomplete($inputEmpleado, datos_em, empleadosMap, idEmpleado, true);

            //     const idOrden = data.id_orden || null;
            //     // Aplicar autocompletado al campo de obra
            //     const $inputObra = $row.find(".obra");
            //     // console.log('selectedItemOrden', selectedItemOrden);
            //     aplicarAutocomplete($inputObra, items_orden, ordenesMap, idOrden, true);
            // }
        });

        let tblGastos = $("#tblGastos").DataTable({
            "dom": 'Ptp',
            "lengthChange": false,
            "ordering": false,
            autoWidth: false,
            paging: true,
            pageLength: 100,
            scrollY: 'calc(100vh - 425px)',
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
            "ajax": {
                "url": "controllers/horario.controlador.php",
                "type": "POST",
                "dataSrc": '',
                data: function(data) {
                    data.accion = 6;
                    data.start = start.format('YYYY-MM-DD');
                    data.end = end.format('YYYY-MM-DD');
                }
            },
            columnDefs: [{
                targets: 0,
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1; // Devuelve el número de fila + 1
                    }
                    return meta.row; // Devuelve el índice de la fila
                }
            }],
            footerCallback: function(row, data, start, end, display) {
                const api = this.api();
                // Suma la columna 15 (ajusta el índice si hace falta)
                const total = api.column(10, {
                        search: 'applied'
                    })
                    .data()
                    .reduce((acc, value) => {
                        // Limpia el string: quita todo lo que no sea dígito, punto o signo menos
                        const num = parseFloat(
                            String(value).replace(/[^0-9.-]+/g, '')
                        );
                        return acc + (isNaN(num) ? 0 : num);
                    }, 0);
                // Formatea el total de vuelta a money
                const totalStr = total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Escribe en el footer
                const footer = $(api.table().footer());
                footer.find('#totalGeneralGastos').html('$' + totalStr);
            }
        });


        let tblPerson = $("#tblPersonH").DataTable({
            "dom": 't',
            "lengthChange": false,
            "ordering": false,
            fixedColumns: {
                leftColumns: 2,
                rightColumns: 1
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            paging: false,
            scrollCollapse: true,
            scrollX: true,
            scrollY: 'calc(100vh - 375px)',
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

        // $('#tblHorarioE').on('draw.dt', function() {
        //     tblHorarioE.row.add({
        //         0: '',
        //         1: '',
        //         2: '',
        //         3: '',
        //         4: '',
        //         5: '',
        //         6: '',
        //         7: '',
        //         8: '',
        //         9: '',
        //         10: '',
        //         11: '',
        //         12: ''
        //     }).draw(false);
        // });


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

        let tblEmpleadoH = $("#tblEmpleadoH").DataTable({
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
            scrollY: "55vh",
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

        const observer4 = new ResizeObserver(() => {
            tblGastos.columns.adjust();

        });

        observer4.observe(container4);


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

        $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            let target = $(e.target).attr("href"); // Por ejemplo: #custom-tabs-orden
            // Ajustar DataTable de la pestaña activa
            if (target === '#custom-tabs-dia') {
                tblHorario.columns.adjust().draw();
                tblHorario.searchPanes.rebuildPane();
            } else if (target === '#custom-tabs-orden') {
                // tblGastos.columns.adjust().draw();
                tblGastos.searchPanes.rebuildPane();
                $('.dtsp-titleRow').remove();
            }
        });

        $('#tblHorarioE').on('input keydown', '.hn, .hs, .h100', function() {
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
            form = document.getElementById('formNuevo'),
            formEditar = document.getElementById('formEditar'),
            inputId = document.getElementById('id_horario'),
            btnNuevo = document.getElementById('btnNuevo'),
            btnReturn = document.getElementById('btnReturn');

        const scrollBody = $('#tblHorario').closest('.dataTables_scroll').find('.dataTables_scrollBody')[0];
        const $scrollBody = $('#tblHorario').closest('.dataTables_scroll').find('.dataTables_scrollBody');

        let scrollPos = 0;
        let scrollPosition = 0;

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
            const id = e["id"];
            scrollPosition = scrollBody.scrollTop;

            let src = new FormData();
            src.append('accion', 4);
            src.append('id', id);
            confirmarEliminar('este', 'horario de empleado', function(r) {
                if (r) {
                    confirmarAccion(src, 'horario', null, '', function(r) {
                        if (r) {
                            tabla.ajax.reload(function() {
                                $scrollBody.scrollTop(scrollPosition);
                                // Ajusta el delay si es necesario
                            }, false);
                        }
                    }, 4000)
                }
            });
        });

        $('#tblHorario tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            scrollPosition = scrollBody.scrollTop;

            id_horario_editar = row["id"];
            inputId.value = id_horario_editar || '';
            const idEmpleado = row["id_empleado"] || null;
            const idOrden = row["id_orden"] || null;
            const fecha_horario = row["fecha_val"] || null;

            fecha_edit.value = fecha_horario || '';
            // console.log('idEmpleado', empleado_edit, orden_edit);
            tblHorarioE.ajax.reload(null, false);
            aplicarAutocomplete($('#empleado_edit'), datos_em, empleadosMap, idEmpleado, true);
            aplicarAutocomplete($('#orden_edit'), items_orden, ordenesMap, idOrden, true);
        });

        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            const rows = tblHorarioE.rows().nodes();
            const datos = [];
            const fecha = fecha_edit.value || '';
            const id_empleado = $('#empleado_edit').attr('data-id') || null;
            const id_obra = $('#orden_edit').attr('data-id') || null;
            $(rows).each(function() {
                const $row = $(this);
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
                    id_horario: id_horario_editar,
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
            console.log("Datos a editar:", datos);
            // 👉 Aquí haces el envío AJAX
            if (datos.length > 0) {
                $.ajax({
                    url: 'controllers/horario.controlador.php', // Cambia a tu ruta real
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        accion: 3,
                        registros: JSON.stringify(datos) // Importante: lo mandas como string normal
                    },
                    success: function(resp) {
                        const isSuccess = resp.status === "success";
                        mostrarToast(resp.status,
                            isSuccess ? "Completado" : "Error",
                            isSuccess ? "fa-check" : "fa-xmark",
                            resp.m);
                        if (isSuccess) {
                            $('#modal').modal('hide');
                        }
                        if (tabla) {
                            tabla.ajax.reload(function() {
                                $scrollBody.scrollTop(scrollPosition);
                                // Ajusta el delay si es necesario
                            }, false); // Recargar tabla si es necesario
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
        })

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