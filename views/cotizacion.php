<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Solicitud de compra</title>
</head>

<!-- Contenido Header -->
<section id="div_header" class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Solicitud de compra</h1>
            </div>
            <?php if (isset($_SESSION["crear4"]) && $_SESSION["crear4"] === true) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-green">
                        <i class="fa fa-plus"></i> Nuevo</button>
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
                                <div class="col-auto col-p" style="padding-right: .3rem">
                                    <h3 class="card-title ">Listado de solic. de compra</h3>
                                </div>
                                <div class=" col col-sm-auto">
                                    <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <div style="margin-block:.4rem;height:33px;" class="input-group">
                                        <span class="input-group-text" style="height:30px;"><i class="fas fa-search icon"></i></span>
                                        <input autocomplete="off" style="border:none;" style="height:30px" type="search" id="_search" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table id="tblCotizacion" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>Nº DE COTIZACIÓN</th>
                                    <th>PROVEEDOR</th>
                                    <th>MOTIVO</th>
                                    <th>FECHA</th>
                                    <th title="Solicitud de cotización PDF" class="text-center"><i class="fas fa-file-signature fa-lg"></i></th>
                                    <th title="Orden de compra PDF" class="text-center"><i class="fas fa-file-invoice-dollar fa-lg"></i></th>
                                    <th title="Subir PDF personalizado" class="text-center"><i class="fa-solid fa-file-arrow-up fa-lg"></i></th>
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

<!-- Formulario oculto al principio -->
<section id="div_cot" class="content" style="display: none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="content-header px-0" style="font-size:1.6rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap">
                    <span>
                        <i id="btnReturn" style="cursor:pointer" class="fa-regular fa-circle-arrow-left"></i><span id="text_accion"> Nueva solicitud de compra</span></span>
                    <span style="color:#cf0202;font-size:76%;font-weight:600">Nro. <span id="nroSolicitud">00001</span></span>
                </div>
                <div class="row" style="align-items:flex-start">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <form id="formCotizacion">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label id="lblP" class="col-form-label combo"><i class="fas fa-hand-holding-box"></i> Proveedor</label>
                                            <div class="row">
                                                <div class="col">
                                                    <select id="cboProve" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                    </select>
                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="comprador"><i class="fas fa-hand-holding-dollar"></i> Comprador</label>
                                            <input type="text" value="MERCY BELTRAN" id="comprador" style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);" class="form-control form-control-sm">
                                        </div>
                                        <!-- <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="aprobado"><i class="fas fa-handshake"></i> Aprobado por</label>
                                            <input type="text" value="ABELARDO MUÑOZ" id="aprobado" style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);border-radius:0.25rem;" class="form-control form-control-sm">
                                        </div> -->
                                        <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="fecha_sol">
                                                <i class="fas fa-calendar"></i> Fecha</label>
                                            <input id="fecha_sol" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm">
                                            <!-- <div class="invalid-feedback">*Campo obligatorio.</div> -->
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="ruc">
                                                <i class="fas fa-building-user"></i> RUC</label>
                                            <input style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);border-radius:0.25rem;" type="text" class="form-control form-control-sm" id="ruc" readonly>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                                            <input type="tel" id="telefono" style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);border-radius:0.25rem;" class="form-control form-control-sm" readonly>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="dir"><i class="fas fa-map-location-dot"></i> Dirección</label>
                                            <input style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);border-radius:0.25rem;" type="text" class="form-control form-control-sm" id="dir" readonly>
                                        </div>

                                    </div>
                                    <!-- </div> -->
                                    <!-- <div class="card-body"> -->
                                    <div class="table-responsive" style="padding:0;border:1px solid #ccc;border-radius: 8px;">
                                        <table id="tblSolicitud" class="table table-bordered w-100 table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Nº</th>
                                                    <th class="text-center">CANT.</th>
                                                    <th>UND</th>
                                                    <th>DESCRIPCION</th>
                                                    <th>P. TOTAL</th>
                                                    <th class="text-nowrap">P. UNIT.</th>
                                                    <th class="text-center">ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <input />
                                                    </td>
                                                    <td>
                                                        <input />
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <input />
                                                    </td>
                                                    <td></td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="margin-top:1.4rem; display: flex; align-items: center; gap: 12px;">
                                        <input type="text" id="rowCount" style="width:4rem;border-bottom: 2px solid var(--select-border-bottom)" class="form-control text-center" oninput="validarNumber(this,/[^0-9]/g)" maxlength="2" inputmode="numeric" autocomplete="off" oninput="validarCantidad(this)" value="1">
                                        <button type="button" id="addRow" class="btn bg-gradient-green">
                                            <i class="fas fa-table-rows"></i> Agregar filas
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3" style="position:sticky;top:10%">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="d-flex justify-content-around">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio1" name="customRadio" data-tax="0" value="0">
                                            <label for="customRadio1" class="custom-control-label">0%</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio2" name="customRadio" data-tax="5" value="05">
                                            <label for="customRadio2" class="custom-control-label">5%</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio3" name="customRadio" data-tax="<?php echo $_SESSION["iva"]; ?>" value="<?php echo $_SESSION["iva"]; ?>" checked>
                                            <label for="customRadio3" class="custom-control-label">
                                                <?php echo $_SESSION["iva"]; ?>%
                                            </label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>(IVA <span id="tax"><?php echo $_SESSION["iva"] ?></span>%):</span>
                                        <span id="can_tax">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Otros:</span>
                                        <span>$0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total:</span>
                                        <span id="total">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <button type="button" id="btnGuardarSoli" style="margin-bottom:.75rem;background:#3f6791 linear-gradient(180deg, #3f6791, #0b4395) repeat-x; color:#fff" class="btn w-100"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                                <button type="button" id="CerrarSoli" style="border-color:#d6d8df69" class="btn bg-gradient-light w-100"><i class="fas fa-right-from-bracket"></i><span class="button-text"> </span>Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-light">
                <h4 class="modal-title"><i class="fas fa-upload"></i><span> Subir PDF</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPdf" autocomplete="off" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="combo" style="font-size: 1.15rem;"><i class="fa-solid fa-file-pdf"></i> Archivo</label>
                            <input type="file" name="filePdf" id="filePdf" class="form-control" accept=".pdf" required>
                            <div class="mt-0 invalid-feedback">*Debe selecionar un archivo .pdf</div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn bg-gradient-light"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar6"] || $_SESSION["eliminar6"] ?>';
    var editar = '<?php echo $_SESSION["editar6"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar6"] ?>';

    configuracionTable = {
        "responsive": true,
        "dom": 'pt',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        fixedColumns: true,

        columnDefs: [{
                targets: 0, // Enumeración automática
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return type === 'display' ? meta.row + 1 : meta.row;
                }
            },
            {
                targets: 3, // Índice de la columna con texto largo
                className: 'partir-texto' // Asignar la clase personalizada
            },
            {
                targets: 5,
                className: "text-center",
                render: function(data, type, row, full, meta) {
                    const getUrl = `PDF/pdf_cotizacion.php?id=${row.id}&cotizacion=1`; // Construye la URL con ambos parámetros
                    return data === false ?
                        '<span style="padding:.375rem .75rem;border-radius:.25rem;font-size:1.4rem;cursor:no-drop;color:#721c24;"><i class="fa-solid fa-file-xmark"></i></span>' :
                        `<span style="padding:.375rem .75rem;font-size:1.4rem;cursor:pointer;border-radius:.25rem;color:#155724" onclick="window.open('${getUrl}', '_blank');"><i class="fas fa-file-check"></i></span>`;
                }
            },
            {
                targets: 6,
                className: "text-center",
                render: function(data, type, row, full, meta) {
                    const getUrl = `PDF/pdf_cotizacion.php?id=${row.id}&cotizacion=0`; // Construye la URL con ambos parámetros
                    return data === false ?
                        '<span style="padding:.375rem .75rem;border-radius:.25rem;font-size:1.4rem;cursor:no-drop;color:darkgrey"><i class="fas fa-file-xmark"></i></span>' :
                        `<span style="padding:.375rem .75rem;font-size:1.4rem;cursor:pointer;border-radius:.25rem;color:#155724" onclick="window.open('${getUrl}', '_blank');"><i class="fas fa-file-check"></i></span>`;
                }
            },
            {
                targets: 7,
                className: "text-center",
                render: function(data) {
                    return data ?
                        `<a style="font-size:1.4rem;cursor:pointer;border-radius:.25rem;color:#155724" href="/aistermcon/utils/download.php?file=${encodeURIComponent(data)}&route=presupuesto_proveedor" target="_blank" class="btn btnDescargar" title="PDF de proveedor" style="font-size:1.4rem;padding:3px 6.8px">
                            <i class="fas fa-file-check"></i>
                        </a>` :
                        `<span class="btn" style="font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey">
                        <i class="fas fa-file-xmark"></i>
                        </span>`;
                }
            },
            {
                targets: 8,
                data: "acciones",
                visible: true,
                render: function(data, type, row, full, meta) {
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar'  title='Editar'>" +
                            " <i class='fas fa-pencil'></i>" +
                            "</button>" +
                            " <button type='button' class='btn bg-gradient-light btnUpload' data-target='#modal' data-toggle='modal'  title='Subir PDF'>" +
                            " <i class='fas fa-upload'></i>" +
                            "</button>" :
                            "") +
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

        let tblSolicitud;
        let anio = year;
        let accion_cotiz = 0;
        let nro_cotiz = 0;

        if (!$.fn.DataTable.isDataTable('#tblCotizacion')) {
            tabla = $("#tblCotizacion").DataTable({
                "ajax": {
                    "url": "controllers/cotizacion.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.anio = anio;
                    }
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;
                handleScroll(b, s, w);

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('cotizacion', JSON.stringify(tablaData));
            });
        }

        tblSolicitud = $('#tblSolicitud').DataTable({
            "dom": '<"row"<"col-sm-6"B><"col-sm-6"p>>t',
            "responsive": false,
            "lengthChange": false,
            "ordering": false,
            "autoWidth": false,
            "paging": false,
            "ajax": {
                "url": "controllers/cotizacion.controlador.php",
                "type": "POST",
                "dataSrc": '',
                data: function(d) {
                    d.accion = accion_cotiz;
                    d.nro_cotiz = nro_cotiz;
                }
            },
            columnDefs: [{
                    targets: 0, // Enumeración automática
                    data: null,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        return type === 'display' ? meta.row + 1 : meta.row;
                    }
                },
                {
                    targets: 1, // Input para cantidad
                    className: "text-center",
                    render: function(data, type, row) {
                        return `<input type="text" class="form-control text-center cantidad" value="${data || 1}" 
                            style="width:72px;border-bottom-width:2px;padding:0;font-size:1.2rem" maxlength="6"
                            inputmode="numeric" onfocus="selecTexto(this)" autocomplete="off" oninput="validarNumber(this,/[^0-9.]/g)">`;
                    }
                },
                {
                    targets: 2, // Select para unidad
                    className: "text-center",
                    render: function(data, type, row) {
                        return `<select class="form-control select2 id_unidad" data-dropdown-css-class="select2-dark" required>
                            </select>`;
                    }
                },
                {
                    targets: 3, // Input para descripción
                    render: function(data, type, row) {
                        return `<input type="text" class="form-control descripcion" value="${data || ''}" 
                            style="width:100%;border-bottom-width:2px;padding:0;font-size:1.1rem" 
                            autocomplete="off" onfocus="selecTexto(this)" spellcheck="false">`;
                    }
                },
                {
                    targets: 4, // Input para precio total
                    className: "text-center text-nowrap",
                    render: function(data, type, row) {
                        return `$<input type="text" class="form-control text-center d-inline precio_final" value="${data || ''}" 
                            style="width:82px;border-bottom-width:2px;padding:0;font-size:1.2rem" maxlength="8"
                            inputmode="numeric" onfocus="selecTexto(this)" autocomplete="off" oninput="validarNumber(this,/[^0-9.]/g)" onpaste="validarPegado(this, event)">`;
                        //     `$<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.2rem" class="form-control text-center d-inline precio" maxlength="8" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" oninput="validarNumber(this,/[^0-9.]/g)" value="">`,
                    }
                },
                {
                    targets: 5, // Precio unitario
                    className: "text-center",
                    render: function(data, type, row) {
                        return `<span class="precio_uni">${data || '$0.00'}</span>`;
                    }
                },
                {
                    targets: 6, // Botón de acciones
                    className: "text-center",
                    render: function(data, type, row) {
                        return `<center>
                                <span class='btnBorrar text-danger' style='cursor:pointer;' data-bs-toggle='tooltip' title='Eliminar producto'>
                                    <i class='fa-regular fa-circle-xmark' style='font-size:1.8rem;padding-top:.3rem'></i>
                                </span>
                            </center>`;
                    }
                }
            ],
            createdRow: function(row, data, dataIndex) {
                const selectElement = $(row).find('.id_unidad');
                cargarOpcionesSelect(selectElement, data.id_unidad);

            },
            buttons: [{
                text: "<i class='fa-regular fa-trash-can fa-xl'style='color: #bd0000'></i> Borrar todo",
                className: "btn btn-light text-danger",
                action: function(e, dt, node, config) {
                    if (accion == 2) {
                        // var ids = [];
                        // dt.rows().every(function() {
                        //     var data = this.data();
                        //     ids.push(data.id); // Asumiendo que 'id' es la columna con el ID único de cada fila
                        // });

                        let data = new FormData();
                        data.append('id', id_cotiz);

                        data.append('accion', 7);
                        confirmarEliminar('este listado de', 'solic. de compra', function(r) {
                            if (r) {
                                confirmarAccion(data, 'cotizacion', tblSolicitud, '', function(r) {
                                    if (r) {
                                        tabla.ajax.reload(null, false);
                                        $('#subtotal').text('$0.00');
                                        $('#can_tax').text('$0.00');
                                        $('#total').text('$0.00');
                                        subtotal = 0;
                                    }
                                });
                            }
                        });
                    } else {
                        confirmarEliminar('este listado de', 'solic. de compra', function(r) {
                            if (r) {
                                dt.clear().draw();
                                agregarFila();
                                actualizarSubtotal();
                            }
                        }, '');
                    }
                }
            }]
        });

        let accion = 0;
        let subtotal = 0;
        let text_tax;
        let impuestos = 0;
        let total = 0;
        let id_pdf;
        let id_cotiz;
        let subtotalOriginal;
        let ivaOriginal;
        let iva = <?php echo $_SESSION["iva"]; ?>;

        const modal = document.getElementById('modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            formPdf = document.getElementById('formPdf'),
            filePdf = document.getElementById('filePdf'),
            form_cotizacion = document.getElementById('formCotizacion'),
            icon = document.querySelector('.modal-title i'),
            btnAddRow = document.getElementById('addRow'),
            btnReturn = document.getElementById('btnReturn'),
            btnCerrar = document.getElementById('CerrarSoli'),
            btnGuardarSoli = document.getElementById('btnGuardarSoli'),
            btnNuevo = document.getElementById('btnNuevo'),
            radios = document.querySelectorAll('input[name="customRadio"]'),
            radio = document.getElementById('customRadio3');


        const direccion = document.getElementById('dir'),
            ruc = document.getElementById('ruc'),
            cboProve = document.getElementById('cboProve'),
            tax = document.getElementById('tax'),
            comprador = document.getElementById('comprador'),
            fecha_sol = document.getElementById('fecha_sol'),
            telefono = document.getElementById('telefono');

        // $(modal).on("shown.bs.modal", () => {
        //     nombre.focus();
        // });

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


        $(cboProve).select2({
            placeholder: 'SELECCIONE',
            width: 'auto',
            data: datos_prove
        });

        setChange(cboProve, 0);

        $(cboProve).on("change", function() {
            const id_prove = this.value;
            if (id_prove !== '') {
                $.ajax({
                    url: "controllers/cotizacion.controlador.php",
                    method: "POST",
                    data: {
                        id_prove: id_prove,
                        accion: 4
                    },
                    dataType: "json",
                    success: function(r) {
                        ruc.value = r['ruc'];
                        direccion.value = r['direccion'];
                        telefono.value = r['telefono'];
                    }
                });
            }

        });

        radios.forEach(r => {
            r.addEventListener('change', function() {
                const selectedValue = this.value;
                text_tax = this.dataset.tax;
                // Obtener el valor seleccionado
                tax.textContent = text_tax; // Actualizar el texto del span
                iva = selectedValue;
                console.log(selectedValue)
                actualizarIva();
            });
        });


        function ocultarFormulario() {

            document.getElementById("div_cot").style.display = "none";
            document.getElementById("div_content").style.display = "block";
            $('#tblCotizacion').DataTable().columns.adjust().draw(false);
            tblSolicitud.clear().draw();
            document.getElementById("div_header").style.display = "block";

        }

        btnReturn.addEventListener('click', function() {
            ocultarFormulario();
        });

        btnCerrar.addEventListener('click', function() {
            ocultarFormulario();
        });

        btnAddRow.addEventListener('click', function() {
            const rowCountInput = document.getElementById('rowCount');
            const rowCount = parseInt(rowCountInput.value, 10) || 1; // Cantidad de filas a agregar

            if (accion == 2) {
                let data = new FormData()
                data.append('id_cotizacion', id_cotiz);
                data.append('filasCantidad',rowCount)
                data.append('accion', 10);
                confirmarAccion(data, 'cotizacion', tblSolicitud, '', function(r) {
                            if (r) {
                                // tblSolicitud.ajax.reload(function(r) {
                                //     actualizarSubtotal(true);
                                // }, false);
                            }
                        });
                
            } else {
                for (let i = 0; i < rowCount; i++) {
                    agregarFila(); // Llama a tu función de agregar fila
                }

                // Resetear el valor del input a 1 (opcional)
                rowCountInput.value = 1;
            }

        });

        function agregarFila() {
            let nuevaFila = ['', '1', 1, '', '', '$0.00', ''];
            let rowNode = tblSolicitud.row.add(nuevaFila).draw(false).node();
        }

        function cargarOpcionesSelect(selectElement, value) {
            selectElement.select2({
                data: datos_uni,
                minimumResultsForSearch: -1,
                width: '110%',
            });

            if (value) {
                selectElement.val(value).trigger('change');
            }
        }

        $('#tblSolicitud tbody').on('click', '.btnBorrar', function() {
            if (accion == 2) {
                const e = obtenerFila(this, tblSolicitud)
                const id_ = e["id"];
                let data = new FormData();
                data.append('id', id_);
                data.append('id_cotizacion', id_cotiz);
                data.append('accion', 8);
                confirmarEliminar('esta', 'fila', function(r) {
                    if (r) {
                        confirmarAccion(data, 'cotizacion', null, '', function(r) {
                            if (r) {
                                tblSolicitud.ajax.reload(function(r) {
                                    actualizarSubtotal(true);
                                }, false);
                            }
                        });
                    }
                });

            } else {
                tblSolicitud.row($(this).parents('tr')).remove().draw();
                actualizarSubtotal();
            }
        });

        $('#tblSolicitud').on('input keydown paste', '.cantidad, .precio_final', function() {
            let $row = $(this).closest('tr');
            let cantidad = parseFloat($row.find('.cantidad').val()) || 0;
            let precio = parseFloat($row.find('.precio_final').val()) || 0;
            let pre_uni = 0; // Valor por defecto

            if (cantidad !== 0 && precio !== 0) {
                // Si ambos valores son distintos de cero, realizamos la operación
                pre_uni = precio / cantidad;
            }

            // Mostrar el precio unitario
            $row.find('.precio_uni').text('$' + pre_uni.toFixed(2));
            actualizarSubtotal();
        });

        function actualizarSubtotal(database = false) {
            let precio_total = 0;
            // Itera sobre todas las filas y suma los precios finales
            $('#tblSolicitud tbody tr').each(function() {
                let precio = parseFloat($(this).find('.precio_final').val()) || 0;
                // let cantidad = parseFloat($(this).find('.cantidad').val()) || 0;
                precio_total += precio; // Sumar el precio total de cada fila
            });
            console.log('precio de esto es ', precio_total);
            subtotal = precio_total;
            let subFormateado = precio_total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Actualizar el subtotal en el card
            $('#subtotal').text('$' + subFormateado);

            actualizarIva(database)
        }

        function actualizarIva(database) {
            let porcentaje = parseFloat("0." + iva); // Asegurarse de que porcentaje sea un número decimal válido
            console.log(subtotal);
            // subtotal = parseFloat(subtotal.replace(/[$,]/g, '')) || 0;
            subtotal = parseFloat((subtotal || '0').toString().replace(/[$,]/g, '')) || 0;

            // subtotal = parseFloat(subtotal) || 0;
            impuestos = subtotal * porcentaje;

            let iva_formateado = impuestos.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            $('#can_tax').text('$' + iva_formateado);

            total = subtotal + impuestos;

            let total_formateado = total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            $('#total').text('$' + total_formateado);
            if (database) {
                let datos = new FormData();
                datos.append('subtotal', subtotal);
                datos.append('iva', iva);
                datos.append('impuesto', impuestos);
                datos.append('total', total);
                datos.append('id_cotizacion', id_cotiz);
                datos.append('accion', 9);
                confirmarAccion(datos, 'cotizacion', tabla, '', null, 0, false)
            }
        }

        $('#tblCotizacion tbody').on('click', '.btnUpload', function() {
            let row = obtenerFila(this, tabla);
            accion = 3;
            id_pdf = row["id"];
            formPdf.classList.remove('was-validated');
            filePdf.value = '';
        });

        document.addEventListener('keydown', function(event) {
            const isInputOrSelect = event.target.tagName === 'INPUT' || $(event.target).hasClass('select2-selection');

            if (isInputOrSelect && event.target.closest('table')) {
                let currentElement = event.target.tagName === 'INPUT' ?
                    event.target :
                    $(event.target).closest('.select2-container').prev('select')[0]; // Referencia al select original

                // Verificar si el menú Select2 está abierto
                const select2Open = $(currentElement).data('select2')?.isOpen();

                if (select2Open) {
                    // Prevenir movimiento con todas las flechas si el menú está abierto
                    if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(event.key)) {
                        event.stopPropagation();
                        event.preventDefault();
                        return;
                    }
                }

                let currentCell = currentElement.closest('td');
                let currentRow = currentCell.closest('tr');
                let currentTable = currentRow.closest('table');

                let rowIndex = currentRow.rowIndex;
                let cellIndex = currentCell.cellIndex;

                switch (event.key) {
                    case 'ArrowRight':
                        if (shouldMoveFocus(event, currentElement, 'end')) {
                            event.preventDefault();
                            moveFocus(currentTable, rowIndex, cellIndex + 1);
                        }
                        break;
                    case 'ArrowLeft':
                        if (shouldMoveFocus(event, currentElement, 'start')) {
                            event.preventDefault();
                            moveFocus(currentTable, rowIndex, cellIndex - 1);
                        }
                        break;
                    case 'ArrowDown':
                        if (!select2Open) {
                            event.preventDefault();
                            moveFocus(currentTable, rowIndex + 1, cellIndex);
                        }
                        break;
                    case 'ArrowUp':
                        if (!select2Open) {
                            event.preventDefault();
                            moveFocus(currentTable, rowIndex - 1, cellIndex);
                        }
                        break;
                }
            }
        });

        function shouldMoveFocus(event, element, position) {
            if (element.tagName === 'INPUT') {
                let cursorPosition = element.selectionStart;
                let inputLength = element.value.length;
                return (position === 'start' && cursorPosition === 0) ||
                    (position === 'end' && cursorPosition === inputLength);
            }

            if (element.tagName === 'SELECT') {
                return event.key === 'ArrowRight' || event.key === 'ArrowLeft'; // Permitir navegación horizontal solo si no es select2 abierto
            }

            return false;
        }

        function moveFocus(table, nextRowIndex, nextCellIndex) {
            let nextRow = table.rows[nextRowIndex];
            if (nextRow) {
                let nextCell = nextRow.cells[nextCellIndex];
                if (nextCell) {
                    let nextElement = nextCell.querySelector('input, select');

                    if (nextElement) {
                        if ($(nextElement).hasClass('select2-hidden-accessible')) {
                            // Poner foco en Select2 sin desplegar el menú
                            $(nextElement).select2('focus');
                        } else {
                            nextElement.focus();
                        }
                    }
                }
            }
        }

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                // cambiarModal(span, ' Nuevo solic. compra', icon, 'fa-hand-holding-box', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                // form.reset();
                // form.classList.remove('was-validated');
                // correo.disabled = false;
                // telefono.disabled = false;
                const nro_cotizacion = tabla.cell(0, 1).data(); // '00013'
                const nro_cotizacion_formateado = (parseInt(nro_cotizacion, 10) + 1).toString().padStart(nro_cotizacion.length, '0');
                $('#nroSolicitud').text(nro_cotizacion_formateado);
                $('#text_accion').text(' Nueva solicitud de compra');
                fecha_sol.value = fecha_hoy;
                setChange(cboProve, 0);
                radio.click();
                agregarFila();
                $('#subtotal').text('$0.00');
                $('#can_tax').text('$0.00');
                $('#total').text('$0.00');
                form_cotizacion.classList.remove('was-validated');
                ruc.value = '';
                direccion.value = '';
                telefono.value = '';
                subtotal= 0;

                document.getElementById("div_cot").style.display = "block";
                document.getElementById("div_content").style.display = "table-column";
                document.getElementById("div_header").style.display = "none";

                // document.getElementById("tblCotizacion").style.display = "none";
            });
        }

        $('#tblCotizacion tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'solic. compra', function(r) {
                if (r) {
                    confirmarAccion(src, 'cotizacion', tabla, '', function(r) {});
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

        $('#tblCotizacion tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            radios.forEach(function(r) {
                r.checked = false; // Esto desmarca el botón de radio
            });
            iva = row[13];
            ivaOriginal = iva; // subtotal = parseFloat((subtotal || '0').toString().replace(/[$,]/g, '')) || 0;
            subtotalOriginal = parseFloat((row[11] || '0').toString().replace(/[$,]/g, '')) || 0;
            // subtotalOriginal = ;
            subtotal = row[11] || '$0.00';
            impuestos = row[12];
            total = row[14] || '$0.00';
            $('#total').text(total);

            id_cotiz = row[0];
            const num_co = row[1],
                proveedor = row[9],
                fecha_solicitud = row[4];


            $('#subtotal').text(subtotal);
            $('#can_tax').text(impuestos);
            if (isNaN(parseInt(iva, 10))) {
                radio.click();
            } else {
                $('#tax').text(parseInt(iva, 10));
            }
            $('#text_accion').text(' Editar solicitud de compra');
            $('#nroSolicitud').text(num_co);
            setChange(cboProve, proveedor);
            const [dia, mes, anio] = fecha_solicitud.split('/');

            // Formatear a 'YYYY-MM-DD'
            const fecha_formateada = `${anio}-${mes.padStart(2, '0')}-${dia.padStart(2, '0')}`;

            // Asignar al value de un input de tipo fecha
            fecha_sol.value = fecha_formateada;
            accion_cotiz = 1;
            nro_cotiz = id_cotiz;
            tblSolicitud.ajax.reload(null, false);


            document.getElementById("div_cot").style.display = "block";
            document.getElementById("div_content").style.display = "table-column";
            document.getElementById("div_header").style.display = "none";
        });

        $('#tblSolicitud').on('draw.dt', function() {
            tblSolicitud.rows().every(function(rowIdx) {
                let data = this.data(); // Obtener los datos originales
                // console.log('Datos de la fila:', data); // Verificar qué datos se están asignando
                $(this.node()).data('original', JSON.stringify(data)); // Guardar en el atributo `data-original`
            });
        });

        btnGuardarSoli.addEventListener("click", function(e) {
            e.preventDefault();
            let elementosAValidar = [fecha_sol, comprador, cboProve];
            let isValid = true;
            let isCotizacion = false;

            elementosAValidar.forEach(function(elemento) {
                if (!elemento.checkValidity()) {
                    isValid = false;
                    form_cotizacion.classList.add('was-validated');
                }
            });
            if (!isValid) {
                return;
            }

            if (accion == 1) {
                let clases = ['cantidad', 'id_unidad', 'descripcion'];
                let accion_cot = 9;
                let formData = new FormData();

                if (subtotal !== 0) {
                    console.log('subtotal en solic compra', subtotal)
                    clases.push('precio_final');
                    formData.append('subtotal', subtotal);
                    formData.append('iva', iva);
                    formData.append('impuesto', impuestos);
                    formData.append('total', total);
                    accion_cot = 10;
                }

                formData.append('proveedor', cboProve.value);
                formData.append('comprador', comprador.value);
                formData.append('fecha', fecha_sol.value);
                // formData.append('motivo', motivo);
                formData.append('accion', accion_cot);
                realizarRegistroCotizacion(tblSolicitud, formData, clases);

            } else if (accion == 2) {
                let filasActualizadas = [];

                tblSolicitud.rows().every(function(index) {
                    let row = tblSolicitud.row(index);
                    let originalData = JSON.parse($(row.node()).data('original')); // Datos originales
                    let nuevaData = {}; // Objeto para almacenar los datos actuales

                    // Obtén los valores actuales de las clases editables
                    ['cantidad', 'id_unidad', 'descripcion', 'precio_final'].forEach(clase => {
                        let input = row.node().querySelector('.' + clase);
                        nuevaData[clase] = input ? input.value.trim() : ''; // Aseguramos que no haya espacios innecesarios
                    });

                    // Comparación robusta de datos
                    let haCambiado = Object.keys(nuevaData).some(key => {
                        let valorOriginal = originalData[key] ? originalData[key].toString().trim() : '';
                        let valorNuevo = nuevaData[key] ? nuevaData[key].toString().trim() : '';

                        // Asegurar que los valores sean iguales sin importar si son números o cadenas
                        if (key === 'precio_final' || key === 'cantidad') {
                            valorOriginal = parseFloat(valorOriginal) || 0; // Convertir a número
                            valorNuevo = parseFloat(valorNuevo) || 0; // Convertir a número
                        }

                        // console.log('valor original', valorOriginal)
                        // console.log('valor nuevo', valorNuevo)

                        return valorOriginal !== valorNuevo;
                    });

                    if (haCambiado) {
                        nuevaData.id = originalData.id; // Agrega el identificador único de la fila
                        filasActualizadas.push(nuevaData); // Agregar solo las filas modificadas
                    }
                });

                if (filasActualizadas.length === 0 && (iva === ivaOriginal)) {
                    mostrarToast('info', 'Sin cambios', 'fa-info', 'No hay cambios para guardar.');
                    console.log('No hay cambios:', filasActualizadas);
                    return;
                } else if (filasActualizadas.length > 0) {
                    let data = new FormData();
                    console.log('comparacion entre subtotal', subtotal);
                    console.log('subtotal original', subtotalOriginal);
                    if (subtotal !== subtotalOriginal) {
                        data.append('subtotal', subtotal);
                        data.append('iva', iva);
                        data.append('impuesto', impuestos);
                        data.append('total', total);
                        data.append('isPrecio', true);
                    }
                    data.append('isPrecio', false);
                    data.append('id_cotizacion', id_cotiz)
                    data.append('filas', JSON.stringify(filasActualizadas));
                    data.append('accion', 6);
                    confirmarAccion(data, 'cotizacion', tabla, '', function(r) {
                        ocultarFormulario();
                    });
                } else if (iva !== ivaOriginal) {
                    let dato = new FormData();
                    console.log('comparacion entre iva', iva);
                    console.log('iva original', ivaOriginal);
                    dato.append('subtotal', subtotal);
                    dato.append('iva', iva);
                    dato.append('impuesto', impuestos);
                    dato.append('total', total);
                    dato.append('id_cotizacion', id_cotiz)
                    dato.append('accion', 9);
                    confirmarAccion(dato, 'cotizacion', tabla, '', function(r) {
                        ocultarFormulario();
                    });
                }
            }

            function realizarRegistroCotizacion(table, formData, clases, header = 'productos') {
                let count = table.rows().count(); // Obtener el número total de filas
                let valid = true; // Flag para verificar si todas las filas son válidas
                let mensajeMostrado = false; // Bandera para asegurarse que el mensaje solo se muestre una vez


                // Verificar si hay filas en la tabla
                if (count === 0) {
                    mostrarToast('danger', "Error", "fa-xmark", 'No hay ' + header + ' en el listado');
                    return;
                }

                // Validar que todas las filas tengan el campo 'descrip' no vacío
                table.rows().eq(0).each(function(index) {
                    let row = table.row(index); // Obtener la fila actual
                    let descripcion = row.node().querySelector('.descripcion'); // Obtener el campo 'descrip'

                    // Si la descripción está vacía, marcar como inválido y detener la iteración
                    if (!descripcion || descripcion.value.trim() === '') {
                        valid = false;

                        // Mostrar el mensaje solo una vez
                        if (!mensajeMostrado) {
                            mostrarToast('danger', "Error", "fa-xmark", 'La descripción esta vacía en la fila ' + (index + 1));
                            mensajeMostrado = true; // Cambiar la bandera para evitar que se muestre más de una vez
                        }
                        return false; // Detener la iteración
                    }
                });
                if (!valid) {
                    return; // Detener la ejecución de la función
                }
                // Si alguna fila no es válida, no proceder con el registro
                Swal.fire({
                    title: "¿Estás seguro que deseas guardar los datos?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, Guardar",
                    cancelButtonText: "Cancelar",
                    timer: 5000,
                    timerProgressBar: true,
                }).then((result) => {
                    if (result.value) {
                        let arr = []; // Arreglo para almacenar los valores de las filas
                        // Recorrer todas las filas y agregar los datos a 'arr'
                        table.rows().eq(0).each(function(index) {
                            let row = table.row(index);
                            let valores = clases.reduce((obj, clase) => {
                                let inputElement = row.node().querySelector('.' + clase);
                                obj[clase] = inputElement ? inputElement.value : '';
                                return obj;
                            }, {});

                            // Agregar los valores de la fila a 'arr'
                            arr.push(valores);
                        });
                        console.log(arr)
                        // Agregar los datos de 'arr' a formData
                        formData.append('arr', JSON.stringify(arr));

                        // Enviar los datos con AJAX
                        $.ajax({
                            url: "controllers/registro.controlador.php",
                            method: "POST",
                            data: formData,
                            cache: false,
                            dataType: "json",
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                let isSuccess = response.status === "success";
                                mostrarToast(response.status,
                                    isSuccess ? "Completado" : "Error",
                                    isSuccess ? "fa-check" : "fa-xmark",
                                    response.m);

                                if (isSuccess) {
                                    ocultarFormulario();
                                }
                                if (tabla) {
                                    tabla.ajax.reload(null, false); // Recargar tabla si es necesario
                                }
                            }
                        });
                    }
                });
            }
        });

        formPdf.addEventListener("submit", function(e) {
            e.preventDefault();
            let datos = new FormData();
            const file = filePdf.files[0];
            if (file && file.type !== "application/pdf") {
                mostrarToast('warning', 'Advertencia', 'fa-triangle-exclamation',
                    'El archivo insertado no es valido, por favor inserta un archivo .pdf',
                    3000)
                return;
            } else if (file && file.type == "application/pdf") {
                datos.append('id_pdf', id_pdf);
                datos.append('filePdf', file);
                datos.append('accion', 5)
            }
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            confirmarAccion(datos, 'cotizacion', tabla, modal, function(r) {});
        });
    });
</script>