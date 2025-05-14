<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ingreso personal</title>
    <!-- <link href="assets/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="assets/plugins/datatables-scroller/css/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet" integrity="sha384-b6V45oYHXYNRRbOBt+gMso4peE+V6GATcho1MZx7ELTjReHmjA8zW2Ap/w0D3+QX" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/scroller/2.2.0/css/scroller.dataTables.min.css" rel="stylesheet" integrity="sha384-fvFMooh85/CFhRcmgNLO/DEXj4/h8h4Fz2s0Wtq2hPU/s7z0rLzrk77ID2JS+YUg" crossorigin="anonymous"> -->
    <!-- <link href="assets/plugins/datatables-keytable/css/keyTable.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
</head>
<!-- Contenido Header -->
<section id="div_header" class="content-header">
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
<section id="div_content" class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <div class="row">
                                <div class="col col-p">
                                    <h3 style="white-space:normal;" class="card-title ">Listado de horario</h3>
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
                        <!-- <div class="table-responsive"> -->
                        <table id="tblHorario" cellspacing="0" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>NOMBRES</th>
                                    <th>Nº DE ORDEN</th>
                                    <th>CLIENTE</th>
                                    <th>FECHA</th>
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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

<section id="div_hor_header" style="display:none" class="content-header">
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
<section id="div_hor_filter" class="content" style="display: none;">
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
                                <input type="search" id="nro_ordenHorario" class="form-control ui-autocomplete-input" placeholder="Ingrese el nro. de orden o cliente" autocomplete="off" style="font-size:1.2rem; border-bottom:2px solid var(--select-border-bottom);" spellcheck="false" data-ms-editor="true">
                                <button class="clear-btn" type="button" id="clearButtonObraH" onclick="clearInput('nro_ordenHorario', this)" style="display:none; position:absolute; right:10px; top:42%;">×</button>
                                <!-- </div> -->

                                <!-- <button class="clear-btn" type="button" id="btnO${uniqueId}" onclick="clearInput('${uniqueId}', this)" style="display:none;top:6%;right:2px">&times;</button> -->

                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>

                            <!-- Elemento 2 -->
                            <div class="col-sm col-md-3  mb-2">
                                <label for="fechaH" class="m-0"><i class="fas fa-calendar"></i> Fecha</label>
                                <input type="date" id="fechaH" class="form-control" value="2025-05-04" style="border-bottom: solid 2px #000;">
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
<section id="div_hor" class="content" style="display: none;">
    <div class="container-fluid">
        <div class="row" style="align-items:flex-start">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0" id="card-hor">
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
                <!-- <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-green"><i class="fas fa-ballot-check"></i> </span>Seleccionar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-right-from-bracket"></i> Cerrar</button>
                </div> -->
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- <script src="assets/plugins/datatables-keytable/js/keyTable.bootstrap4.min.js"></script> -->
<script src="assets/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js" type="text/javascript"></script>

<!-- <script src="assets/plugins/datatables-select/js/dataTables.select.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-select/js/select.bootstrap4.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-scroller/js/dataTables.scroller.min.js" type="text/javascript"></script> -->
<!-- <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/fixedColumns.dataTables.min.js" integrity="sha384-/LxS0b8zEK/HZxykvyTg3o2Ryk2vBESQvW6QMqiUsitINq/Xg5jB4X9KotjCCp3K" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/scroller.dataTables.min.js" integrity="sha384-cCDhK6VsxVGKfl0shwjJr2UXaCzEpxhSnd7C8Uan8yABW71pdY3iaz8aVBklw8uz" crossorigin="anonymous"></script> -->

<script>
    var mostrarCol = '<?php echo $_SESSION["editar20"] || $_SESSION["eliminar20"] ?>';
    var editar = '<?php echo $_SESSION["editar20"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar20"] ?>';

    configuracionTable = {
        "responsive": true,
        "dom": 'Ptp',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        searchPanes: {
            cascadePanes: true,
            columns: [1, 2, 3],
            initCollapsed: true,
            threshold: 0.8, // Ajusta este valor según tus necesidades
            dtOpts: {
                select: {
                    style: 'multiple'
                }
            },
        },
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
                data: 'acciones',
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
                }
            }
        ]
    }

    $(document).ready(function() {
        let accion = 0;
        const container = document.getElementById('div-empleado'); // el div padre de tu tabla
        const container2 = document.getElementById('card-hor'); // el div padre de tu tabla
        const nro_ordenHorario = document.getElementById('nro_ordenHorario');
        const fechaH = document.getElementById('fechaH');
        let datos_em = datos_person.map(item => ({
            label: item.text,
            value: item.text,
            cod: item.id
        }));

        const empleadosMap = new Map(datos_em.map(item => [item.cod, item]));
        const ordenesMap = new Map(items_orden.map(item => [item.cod, item]));

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
                        placeholder="Ingrese el nro. de orden o cliente"
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
                            const uniqueId = meta.row + 1;
                            return `<input
                            style="width:10rem"
                            type="date"
                            id="fechaH${uniqueId}"
                            class="form-control"
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
                                <select id="cboJust" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
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
                aplicarAutocomplete($inputObra, datos_orden, ordenesMap, idOrden);
            }

        });


        $('#tblPersonH').on('change', '.cbo', function() {
            const valorSeleccionado = $(this).val();
            const fila = $(this).closest('tr');

            if (valorSeleccionado !== '0') {
                fila.css('background-color', '#ffeeba'); // Color de fondo personalizado
            } else {
                fila.css('background-color', ''); // Restablece si vuelve a N/A
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
                filas.push(['', '', '', dateH, 8, '', '', '', '', '', '', '', '', '']);
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


        // $("#addRow").on("click", function() {
        //     const dateH = fechaH.value;
        //     const selectedData = tblEmpleadoH.rows({
        //         selected: true
        //     }).data().toArray(); // ← convierte a array nativo
        //     const filas = [];

        //     // Guarda la orden seleccionada si existe
        //     // if (id_orden_horario) {
        //     //     selectedItemOrden = ordenesMap.get(id_orden_horario) || null;
        //     // }

        //     // Si hay empleados seleccionados
        //     if (selectedData.length > 0) {
        //         for (const empleado of selectedData) {
        //             const rowData = ['', '', '', dateH, 8, '', '', '', '', '', '', '', '', ''];
        //             rowData._id_person_res = empleado.id; // ← inyecta como propiedad oculta
        //             filas.push(rowData);
        //             console.log(rowData);
        //         }
        //     } else {
        //         filas.push(['', '', '', dateH, 8, '', '', '', '', '', '', '', '', '']);
        //     }

        //     // Agregar todas las filas de una vez (más eficiente)
        //     tblPerson.rows.add(filas).draw(false);

        //     // Limpiar selección
        //     tblEmpleadoH.rows().deselect();
        //     $('.fil-rol').prop('checked', false);
        //     $('#selected-person').text(0);
        //     $('#chkAll').prop('checked', false);
        // });



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
        let id_person_res = 0;

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

        $('#tblEmpleadoH tbody').on('click', 'tr', function(e) {
            // if ($(e.target).is('input[type="checkbox"]')) return; // No hacer nada si clic fue directamente sobre el checkbox
            // const checkbox = $(this).find('input[type="checkbox"]')[0];
            // if (checkbox) {
            //     checkbox.click(); // Dispara el evento "change"
            // }
        });

        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            div_placa = document.getElementById('div_placa'),
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



        const id = document.getElementById('id'),
            cedula = document.getElementById('cedula'),
            nombre = document.getElementById('nombre'),
            apellido = document.getElementById('apellido'),
            celular = document.getElementById('celular');


        OverlayScrollbars(document.querySelector('.scroll-modal'), {
            autoUpdate: true,
            scrollbars: {
                autoHide: 'leave'
            }
        });

        // $(modal).on("shown.bs.modal", () => {
        //     nombre.focus();
        // });

        $(modalS).on("shown.bs.modal", function() {
            inputContent.focus();
        });

        $(modalS).on('hidden.bs.modal', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (scroll) {
                $(body).addClass('modal-open');
                $(body).css('padding-right', '6px');
            }
        });

        btnReturn.addEventListener('click', function() {
            ocultarFormulario();
        });

        function visibleForm(show) {
            let look = show ? 'block' : 'none';
            document.getElementById("div_cot").style.display = "none";
            document.getElementById("div_content").style.display = "block";
            $('#tblCotizacion').DataTable().columns.adjust().draw(false);
            tblSolicitud.clear().draw();
            document.getElementById("div_header").style.display = "block";
        }

        function novisibleForm(show) {
            let look = show ? 'block' : 'none';
            document.getElementById("div_cot").style.display = "none";
            document.getElementById("div_content").style.display = "block";
            $('#tblCotizacion').DataTable().columns.adjust().draw(false);
            tblSolicitud.clear().draw();
            document.getElementById("div_header").style.display = "block";
        }

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                // const icon = document.querySelector('.modal-title i');
                // cambiarModal(span, ' Nuevo Horario', icon, 'fa-calendar-circle-user', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                // select.forEach(function(s) {
                //     s.classList.remove('select2-warning');
                //     s.classList.add('select2-success');
                // });div_hor_filter
                document.getElementById("div_hor_header").style.display = "block";
                document.getElementById("div_hor_filter").style.display = "block";
                document.getElementById("div_hor").style.display = "block";
                document.getElementById("div_content").style.display = "table-column";
                document.getElementById("div_header").style.display = "none";
                // form.reset();
                // form.classList.remove('was-validated');
                // setChange(cboEmpresa, 0);
                // setChange(cboRol, 0);
                // setChange(cboPlaca, []);

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

        // btnGuardarHorario.addEventListener("click", function(e) {
        //     e.preventDefault();
        //     let elementosAValidar = [fecha_sol, comprador, cboProve];
        //     let isValid = true;
        //     let isCotizacion = false;

        //     elementosAValidar.forEach(function(elemento) {
        //         if (!elemento.checkValidity()) {
        //             isValid = false;
        //             form_cotizacion.classList.add('was-validated');
        //         }
        //     });
        //     if (!isValid) {
        //         return;
        //     }
        //     if (accion == 1) {
        //         let clases = ['cantidad', 'id_unidad', 'descripcion'];
        //         let accion_cot = 9;
        //         let formData = new FormData();

        //         if (subtotal < 0) {
        //             mostrarToast('danger', 'Error', 'fa-xmark', 'El subtotal no puede ser menor a 0');
        //             return;
        //         }

        //         if (subtotal !== 0) {
        //             console.log('subtotal en solic compra', subtotal)
        //             clases.push('precio_final');
        //             formData.append('subtotal', subtotal);
        //             formData.append('iva', iva);
        //             formData.append('impuesto', impuestos);
        //             formData.append('total', total);
        //             formData.append('descuento', descuento);
        //             accion_cot = 10;
        //         }

        //         formData.append('proveedor', cboProve.value);
        //         formData.append('comprador', comprador.value);
        //         formData.append('fecha', fecha_sol.value);
        //         formData.append('accion', accion_cot);
        //         realizarRegistroCotizacion(tblSolicitud, formData, clases);

        //     } else if (accion == 2) {
        //         let cambiosEnInputs = {
        //             id_prove: compararValores(proveOriginal, cboProve.value),
        //             comprador: compararValores(comOriginal, comprador.value),
        //             fecha: compararValores(fechaOriginal, fecha_sol.value),
        //         };
        //         let hayCambiosEnInputs = Object.values(cambiosEnInputs).some(cambio => cambio);
        //         console.log(hayCambiosEnInputs);
        //         // Verificar cambios en las filas de la tabla
        //         let filasActualizadas = verificarCambiosEnFilas(tblSolicitud);
        //         let cambiosEnFilas = filasActualizadas.length > 0;
        //         // console.log('Cambios en las filas:', {iva, ivaOriginal});
        //         let cambioIva = compararValores(iva, ivaOriginal);
        //         let cambioDescuento = compararValores(descOriginal, otros.value);
        //         if (!hayCambiosEnInputs && !cambiosEnFilas && !cambioIva && !cambioDescuento) {
        //             mostrarToast('info', 'Sin cambios', 'fa-info', 'No hay cambios para guardar.');
        //             console.log('cambios:', {
        //                 filasActualizadas
        //             });
        //             return;
        //         }

        //         // Crear los datos a enviar
        //         let data = new FormData();

        //         // Agregar cambios en los inputs
        //         if (hayCambiosEnInputs) {
        //             data.append('id_prove', cboProve.value);
        //             data.append('comprador', comprador.value);
        //             data.append('fecha', fecha_sol.value);
        //         }

        //         // Agregar cambios en las filas
        //         if (cambiosEnFilas) {
        //             if (filasActualizadas.length > 0) {
        //                 data.append('filas', JSON.stringify(filasActualizadas));
        //             }
        //         }
        //         if (subtotal < 0) {
        //             mostrarToast('danger', 'Error', 'fa-xmark', 'El subtotal no puede ser menor a 0');
        //             return;
        //         } else if (subtotal > 0) {
        //             data.append('estado_orden', true);
        //         }

        //         if (cambioDescuento || cambiosEnFilas || cambioIva) {
        //             data.append('desc', otros.value);
        //             data.append('subtotal', subtotal);
        //             data.append('iva', iva);
        //             data.append('impuesto', impuestos);
        //             data.append('total', total);
        //         }
        //         console.log('data de cambio de descuento:', cambioDescuento);

        //         data.append('id_cotizacion', id_cotiz);
        //         data.append('isFilas', cambiosEnFilas);
        //         data.append('isInputs', hayCambiosEnInputs);
        //         data.append('isIva', cambioIva);
        //         data.append('accion', !cambioDescuento && !cambiosEnFilas && !cambioIva ? 11 : 6);
        //         confirmarAccion(data, 'cotizacion', tabla, '', function(r) {
        //             ocultarFormulario();
        //         });
        //     }

        //     function compararValores(original, nuevo, esNumerico = false) {
        //         console.log('Original: ' + original + ' nuevo: ' + nuevo);
        //         return (original || '').toString().trim() !== (nuevo || '').toString().trim();
        //     }

        //     function verificarCambiosEnFilas(tbl) {
        //         let filasActualizadas = [];
        //         // let filasNuevas = [];

        //         tbl.rows().every(function(index) {
        //             let row = tbl.row(index);
        //             let originalData = $(row.node()).data('original') ? JSON.parse($(row.node()).data('original')) : null;
        //             let nuevaData = {};

        //             // Obtén los valores actuales de las clases editables
        //             ['cantidad', 'id_unidad', 'descripcion', 'precio_final'].forEach(clase => {
        //                 let input = row.node().querySelector('.' + clase);
        //                 nuevaData[clase] = input ? input.value.trim() : '';
        //             });

        //             if (originalData) {
        //                 // Comparar datos
        //                 let haCambiado = Object.keys(nuevaData).some(key => {
        //                     return compararValores(originalData[key], nuevaData[key], key === 'precio_final' || key === 'cantidad');
        //                 });

        //                 if (haCambiado) {
        //                     nuevaData.id = originalData.id; // Incluye el ID único de la fila
        //                     filasActualizadas.push(nuevaData);
        //                 }
        //             }
        //         });

        //         return filasActualizadas;
        //     }

        //     function realizarRegistroCotizacion(table, formData, clases, header = 'productos') {
        //         let count = table.rows().count(); // Obtener el número total de filas
        //         let valid = true; // Flag para verificar si todas las filas son válidas
        //         let mensajeMostrado = false; // Bandera para asegurarse que el mensaje solo se muestre una vez

        //         // Verificar si hay filas en la tabla
        //         if (count === 0) {
        //             mostrarToast('danger', "Error", "fa-xmark", 'No hay ' + header + ' en el listado');
        //             return;
        //         }

        //         // Validar que todas las filas tengan el campo 'descrip' no vacío
        //         table.rows().eq(0).each(function(index) {
        //             let row = table.row(index); // Obtener la fila actual
        //             let descripcion = row.node().querySelector('.descripcion'); // Obtener el campo 'descrip'

        //             // Si la descripción está vacía, marcar como inválido y detener la iteración
        //             if (!descripcion || descripcion.value.trim() === '') {
        //                 valid = false;

        //                 // Mostrar el mensaje solo una vez
        //                 if (!mensajeMostrado) {
        //                     mostrarToast('danger', "Error", "fa-xmark", 'La descripción esta vacía en la fila ' + (index + 1));
        //                     mensajeMostrado = true; // Cambiar la bandera para evitar que se muestre más de una vez
        //                 }
        //                 return false; // Detener la iteración
        //             }
        //         });
        //         if (!valid) {
        //             return; // Detener la ejecución de la función
        //         }
        //         // Si alguna fila no es válida, no proceder con el registro
        //         Swal.fire({
        //             title: "¿Estás seguro que deseas guardar los datos?",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonText: "Sí, Guardar",
        //             cancelButtonText: "Cancelar",
        //             timer: 5000,
        //             timerProgressBar: true,
        //         }).then((result) => {
        //             if (result.value) {
        //                 let arr = []; // Arreglo para almacenar los valores de las filas
        //                 // Recorrer todas las filas y agregar los datos a 'arr'
        //                 table.rows().eq(0).each(function(index) {
        //                     let row = table.row(index);
        //                     let valores = clases.reduce((obj, clase) => {
        //                         let inputElement = row.node().querySelector('.' + clase);
        //                         obj[clase] = inputElement ? inputElement.value : '';
        //                         return obj;
        //                     }, {});
        //                     arr.push(valores);
        //                 });
        //                 formData.append('arr', JSON.stringify(arr));
        //                 // Enviar los datos con AJAX
        //                 $.ajax({
        //                     url: "controllers/registro.controlador.php",
        //                     method: "POST",
        //                     data: formData,
        //                     cache: false,
        //                     dataType: "json",
        //                     contentType: false,
        //                     processData: false,
        //                     success: function(response) {
        //                         let isSuccess = response.status === "success";
        //                         mostrarToast(response.status,
        //                             isSuccess ? "Completado" : "Error",
        //                             isSuccess ? "fa-check" : "fa-xmark",
        //                             response.m);
        //                         if (isSuccess) {
        //                             ocultarFormulario();
        //                             sc = response.sc;
        //                         }
        //                         if (tabla) {
        //                             tabla.ajax.reload(null, false); // Recargar tabla si es necesario
        //                         }
        //                     }
        //                 });
        //             }
        //         });
        //     }
        // });

        $('#btnGuardarHorario').on('click', function() {
            const rows = tblPerson.rows().nodes();
            const datos = [];

            $(rows).each(function() {
                const $row = $(this);

                // Obtener los IDs reales
                const id_empleado = $row.find('.empleado').attr('data-id');
                const id_obra = $row.find('.obra').attr('data-id');

                const fecha = $row.find('input[type="date"]').val()?.trim() || '';

                const hn = parseFloat($row.find('.hn').val()?.trim()) || 0;
                const hs = parseFloat($row.find('.hs').val()?.trim()) || 0;
                const he = parseFloat($row.find('.h100').val()?.trim()) || 0;

                const material = parseFloat($row.find('.material').val()?.trim()) || 0;
                const trans = parseFloat($row.find('.trans').val()?.trim()) || 0;
                const ali = parseFloat($row.find('.ali').val()?.trim()) || 0;
                const hosp = parseFloat($row.find('.hosp').val()?.trim()) || 0;
                const guard = parseFloat($row.find('.guard').val()?.trim()) || 0;
                const agua = parseFloat($row.find('.agua').val()?.trim()) || 0;

                if (id_empleado && id_obra && fecha) {
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
                        agua
                    });
                }
            });

            // Ahora puedes enviar 'datos' al servidor con AJAX como ya vimos
            console.log("Datos a guardar:", datos);

            // 👉 Aquí haces el envío AJAX
            // if (datos.length > 0) {
            //     $.ajax({
            //         url: '/ruta/guardarHorario', // Cambia a tu ruta real
            //         method: 'POST',
            //         contentType: 'application/json',
            //         data: JSON.stringify({
            //             registros: datos
            //         }),
            //         success: function(resp) {
            //             Swal.fire('Guardado', 'Los registros se guardaron correctamente.', 'success');
            //         },
            //         error: function(err) {
            //             console.error(err);
            //             Swal.fire('Error', 'Hubo un problema al guardar.', 'error');
            //         }
            //     });
            // } else {
            //     Swal.fire('Advertencia', 'No hay datos válidos para guardar.', 'warning');
            // }
        });



        // form.addEventListener("submit", function(e) {
        //     e.preventDefault();
        //     $('.ten').hide();
        //     const ced = cedula.value.trim(),
        //         nom = nombre.value.trim().toUpperCase(),
        //         ape = apellido.value.trim().toUpperCase(),
        //         tel = celular.value.trim(),
        //         emp = cboEmpresa.value,
        //         rol = cboRol.value,
        //         pla = $(cboPlaca).val();


        //     if (!this.checkValidity() || ced.length < 10 || tel.length < 10) {
        //         this.classList.add('was-validated');
        //         if (ced.length > 0 && ced.length < 10) {
        //             cedula.parentNode.querySelector(".ten").style.display = "block";
        //         }
        //         if (tel.length > 0 && tel.length < 10) {
        //             celular.parentNode.querySelector(".ten").style.display = "block";
        //         }
        //         return;
        //     }
        //     const id_e = id.value;
        //     let datos = new FormData();
        //     datos.append('id', id_e);
        //     datos.append('cedula', ced);
        //     datos.append('nombre', nom);
        //     datos.append('apellido', ape);
        //     datos.append('celular', tel);
        //     datos.append('id_empresa', emp);
        //     datos.append('id_rol', rol);
        //     datos.append('id_placa', pla);
        //     datos.append('accion', accion);
        //     accion = 0;
        //     empresa_filter = cboEmpresaFilter.value;
        //     confirmarAccion(datos, 'horario', tabla, modal, function(r) {
        //         cargarCombo('Conductor', '', 2);
        //         cargarCombo('Despachado', '', 6);
        //         cargarCombo('Responsable', '', 7);
        //     });
        // });
    })
</script>