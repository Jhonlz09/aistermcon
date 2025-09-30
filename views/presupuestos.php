<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Presupuestos</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Presupuestos</h1>
            </div>
            <?php if (isset($_SESSION["crear16"]) && $_SESSION["crear16"] === true) : ?>
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
<section id="content_table" class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto col-p">
                                    <h3 class="card-title ">Listado de presupuestos</h3>
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
                        <table id="tblPresupuesto" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>NRO. ORDEN</th>
                                    <th>CLIENTE</th>
                                    <th>DESCRIPCION</th>
                                    <th>PVP SIN IVA</th>
                                    <th>PVP CON IVA</th>
                                    <th class="text-center">ESTADO</th>
                                    <th class="text-center"><i class="fas fa-tickets fa-lg"></i> OT</th>
                                    <th class="text-center"><i class="fas fa-money-check-dollar-pen fa-lg"></i> PPTO</th>
                                    <th class="text-center"><i class="fa-solid fa-file-invoice-dollar"></i> OC</th>
                                    <th class="text-center"><i class="fa-solid fa-clipboard-check"></i> AE</th>
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

<!-- <section id="content_editor" style="display:none">
    <textarea id="basic-example"></textarea>

</section> -->

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
                                    <label class="combo m-0" style="font-size: 1.15rem;" for="fecha_">
                                        <i class="fas fa-calendar"></i> Fecha de creación</label>
                                    <input id="fecha_new" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                </div>
                                <div class="col-lg col-md-9">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="desc" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fa-solid fa-input-text"></i> Descripción</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="precioSinIva" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fas fa-circle-dollar"></i> Precio <span style="font-size:60%;color: #666666ff;">(sin iva)</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="precioConIva" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fas fa-circle-dollar"></i> Precio <span style="font-size:60%;color: #666666ff;">(con iva)</span> </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="col-form-label combo" for="nota">
                                        <i class="fas fa-note"></i> Nota</label>
                                    <textarea style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);background-color:#d1d1d1" type="text" class="form-control" id="nota" placeholder="Observaciones..." spellcheck="false" data-ms-editor="true"></textarea>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="combo" style="font-size: 1.15rem;"><i class="fa-solid fa-file-pdf"></i> Archivo</label>
                                    <input type="file" name="fileOrden" id="fileOrden" class="form-control" accept=".pdf">
                                    <div class="ten no-margin">*Debe selecionar un archivo .pdf</div>
                                </div>
                            </div> -->
                            <div class="row">
                                <!-- Columna Orden de Trabajo -->
                                <div class="col-md-6 mb-2">
                                    <label class="combo" style="font-size: 1.15rem;">
                                        <i class="fa-solid fa-file-import"></i> Orden de Trabajo (PDF/Excel)
                                    </label>
                                    <div id="dropzone-orden" class="dropzone dropzone-inline" style="border: 2px dashed #b3b3b3;">
                                        <div class="dz-message">Arrastra aquí o haz clic para subir PDF/Excel</div>
                                    </div>
                                    <small class="text-muted">*Solo archivos PDF o Excel (.pdf, .xls, .xlsx)</small>
                                </div>
                                <!-- Columna Presupuestos -->
                                <div class="col-md-6 mb-2">
                                    <label class="combo" style="font-size:1.15rem;">
                                        <i class="fa-solid fa-file-import"></i> Presupuesto (PDF/Excel)
                                    </label>
                                    <div id="dropzone-presupuesto" class="dropzone dropzone-inline" style="border: 2px dashed #b3b3b3;">
                                        <div class="dz-message">Arrastra aquí o haz clic para subir PDF/Excel</div>
                                    </div>
                                    <small class="text-muted">*Solo archivos PDF o Excel (.pdf, .xls, .xlsx)</small>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Columna Orden de Compra -->
                                <div class="col-md-6 mb-2">
                                    <label class="combo" style="font-size: 1.15rem;">
                                        <i class="fa-solid fa-file-import"></i> Orden de Compra (PDF/IMG)
                                    </label>
                                    <div id="dropzone-oc" class="dropzone dropzone-inline" style="border: 2px dashed #b3b3b3;">
                                        <div class="dz-message">Arrastra aquí o haz clic para subir PDF/IMG</div>
                                    </div>
                                    <small class="text-muted">*Solo archivos PDF o Imagen (.pdf, .jpg, .jpeg, .png)</small>
                                </div>
                                <!-- Columna Acta de Entrega -->
                                <div class="col-md-6 mb-2">
                                    <label class="combo" style="font-size:1.15rem;">
                                        <i class="fa-solid fa-file-import"></i> Acta de entrega (PDF/Word)
                                    </label>
                                    <div id="dropzone-ae" class="dropzone dropzone-inline" style="border: 2px dashed #b3b3b3;">
                                        <div class="dz-message">Arrastra aquí o haz clic para subir PDF/WORD</div>
                                    </div>
                                    <small class="text-muted">*Solo archivos PDF o Imagen (.pdf, .doc, .docx)</small>
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
    var mostrarCol = true;
    var editar = '<?php echo $_SESSION["editar16"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar16"] ?>';
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
                targets: 0,
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
            },
            {
                targets: 5,
                "orderable": false,
                responsivePriority: 2,
                visible: mostrarCol ? true : false,
            },
            {
                targets: 6,
                orderable: false,
                className: "text-center",
                render: function(data, type, row, full, meta) {
                    let clase = data === 'PENDIENTE' ? 'warning' : data === 'NO APROBADO' ? 'danger' : data === 'APROBADO' ? 'success' : 'secondary';
                    let icon = data === 'PENDIENTE' ? 'fa-clock' : data === 'NO APROBADO' ? 'fa-file-xmark' : data === 'APROBADO' ? 'fa-file-check' : 'fa-circle-question';
                    return `<span class='alert alert-default-${clase}'><i class='fas ${icon}'></i> ${data}</span>`;
                }
            },
            {
                targets: 7,
                orderable: false,
                className: "text-center text-nowrap",
                render: function(data, type, row) {
                    const {
                        pdf_pre,
                        xls_pre
                    } = row;

                    const btn = (file, color, icon, title) => {
                        if (file) {
                            return `<a href='/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=presupuestos' target='_blank' style='font-size:1.4rem;padding:3px 6.8px;color:${color}' class='btn btnDescargar' title='${title}'><i class='fas ${icon}'></i></a>`;
                        }

                        return `<span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'><i class='fas ${icon}'></i></span>`;
                    };

                    return (
                        btn(pdf_pre, "#a3161f", "fa-file-pdf", "Pdf") +
                        btn(xls_pre, "#155724", "fa-file-xls", "Excel")
                    );
                }
            },
            {
                targets: 8,
                orderable: false,
                className: "text-center text-nowrap",
                render: function(data, type, row) {
                    const {
                        pdf_ord,
                        xls_ord
                    } = row;
                    const btn = (file, color, icon, title) => {
                        if (file) {
                            return `<a href='/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=ordenes' target='_blank'
                   style='font-size:1.4rem;padding:3px 6.8px;color:${color}'
                   class='btn btnDescargar'
                   title='${title}'>
                    <i class='fas ${icon}'></i>
                </a>`;
                        }
                        return `
            <span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'>
                <i class='fas ${icon}'></i>
            </span>`;
                    };

                    return (
                        btn(pdf_ord, "#a3161f", "fa-file-pdf", "Pdf") +
                        btn(xls_ord, "#155724", "fa-file-xls", "Excel")
                    );
                }
            },
            {
                targets: 9,
                orderable: false,
                render: function(data, type, row) {
                    const {
                        pdf_pre,
                        xls_pre
                    } = row;

                    const btn = (file, color, icon, title) => {
                        if (file) {
                            return `<a href='/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=presupuestos' target='_blank' style='font-size:1.4rem;padding:3px 6.8px;color:${color}' class='btn btnDescargar' title='${title}'><i class='fas ${icon}'></i></a>`;
                        }

                        return `<span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'><i class='fas ${icon}'></i></span>`;
                    };

                    return (
                        btn(pdf_pre, "#a3161f", "fa-file-pdf", "Pdf") +
                        btn(xls_pre, "#155724", "fa-file-xls", "Excel")
                    );
                }
            },
            {
                targets: 10,
                orderable: false,
                render: function(data, type, row) {
                    const {
                        pdf_ae,
                        doc_ae
                    } = row;

                    const btn = (file, color, icon, title) => {
                        if (file) {
                            return `<a href='/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=presupuestos' target='_blank' style='font-size:1.4rem;padding:3px 6.8px;color:${color}' class='btn btnDescargar' title='${title}'><i class='fas ${icon}'></i></a>`;
                        }

                        return `<span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'><i class='fas ${icon}'></i></span>`;
                    };

                    return (
                        btn(pdf_pre, "#a3161f", "fa-file-pdf", "Pdf") +
                        btn(xls_pre, "#004598ff", "fa-file-doc", "Word")
                    );
                }
            },

            {
                targets: 11,
                orderable: false,
                render: function(data, type, row, full, meta) {
                    let botones = '';
                    if (editar) {
                        botones += "<button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button> " + "<div class='btn-group'><button type='button' class='btn bg-gradient-light btnEstado dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' title='Cambiar estado'><i class='fas fa-chart-candlestick'></i></button><div class='dropdown-menu dropdown-menu-right'><a class='dropdown-item estado-opcion' data-estado='APROBADO' href='#'><i class='fas fa-file-check'></i> APROBADO</a><a class='dropdown-item estado-opcion' data-estado='PENDIENTE' href='#'><i class='fas fa-clock'></i> PENDIENTE</a><a class='dropdown-item estado-opcion' data-estado='NO APROBADO' href='#'><i class='fas fa-file-xmark'></i> NO APROBADO</a></div></div>";;
                    }
                    if (eliminar) {
                        botones += " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                            " <i class='fa fa-trash'></i>" +
                            "</button>";
                    }
                    return "<center style='white-space: nowrap;'>" + botones + "</center>";
                }
            },
        ],
    }

    $(document).ready(function() {
        let anio = year;
        let estado_presupuesto = 'null';
        if (!$.fn.DataTable.isDataTable('#tblPresupuesto')) {
            tabla = $("#tblPresupuesto").DataTable({
                "ajax": {
                    "url": "controllers/presupuesto.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.anio = anio;
                        data.id_estado = estado_presupuesto;
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
                localStorage.setItem('presupuesto', JSON.stringify(tablaData));
            });
        }
        let IVA = iva_config / 100;




        // tinymce.init({
        //     selector: 'textarea#basic-example',
        //     height: 500,
        //     license_key: 'gpl',
        //     plugins: [
        //         'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        //         'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        //         'insertdatetime', 'media', 'table', 'help', 'wordcount'
        //     ],
        //     toolbar: 'undo redo | blocks | ' +
        //         'bold italic backcolor | alignleft aligncenter ' +
        //         'alignright alignjustify | bullist numlist outdent indent | ' +
        //         'removeformat | help',
        //     content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        // });



        function getFileIconSVG(file) {
            // SVG PDF
            const pdfSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#de0000ff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="18" font-family="Arial" font-weight="bold">PDF</text></svg>`;
            // SVG Excel
            const excelSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#059500ff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="18" font-family="Arial" font-weight="bold">XLS</text></svg>`;
            const imgSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#004ca3ff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">IMG</text></svg>`;
            const wordSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#0045cfff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">DOC</text></svg>`;
            if (file.type === "application/pdf") {
                return pdfSVG;
            }
            if (
                file.type === "application/vnd.ms-excel" ||
                file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ) {
                return excelSVG;
            }
            if (
                file.type === "application/msword" ||
                file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            ) {
                return wordSVG;
            }
            if (file.type.startsWith("image/")) {
                return null; // Para imágenes, se usará la miniatura real
            }
            return imgSVG;
        }

        Dropzone.autoDiscover = false;
        let drop_ord = new Dropzone("#dropzone-orden", {
            url: "/ruta/para/subir/orden",
            autoProcessQueue: false,
            acceptedFiles: ".pdf,.xls,.xlsx",
            addRemoveLinks: true,
            dictDefaultMessage: "Arrastra aquí o haz clic para subir PDF/Excel",
            init: function() {
                let removeAllFilesCalled = false;

                this.on("addedfile", function(file) {
                    // Busca el contenedor de imagen generado por Dropzone
                    var dzImage = file.previewElement.querySelector('.dz-image');
                    if (dzImage) {
                        dzImage.innerHTML = getFileIconSVG(file);
                    }

                    // Contar archivos por tipo
                    let pdfCount = 0,
                        excelCount = 0;
                    this.files.forEach(f => {
                        if (f.type === "application/pdf") pdfCount++;
                        if (
                            f.type === "application/vnd.ms-excel" ||
                            f.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        ) excelCount++;
                    });

                    // Validar máximo 1 PDF y 1 Excel
                    if ((file.type === "application/pdf" && pdfCount > 1) ||
                        ((file.type === "application/vnd.ms-excel" || file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") && excelCount > 1) ||
                        this.files.length > 2) {
                        this.removeFile(file);
                        alert("Solo puedes subir 1 archivo PDF y 1 archivo Excel por vez.");
                    }
                });

                this.on("removedfile", function(file) {
                    console.log(file.name);
                    if (removeAllFilesCalled) {
                        return; // No realizar ninguna acción con el servidor
                    }


                    if (file.isExisting) {
                        $.ajax({
                            url: 'controllers/presupuesto.controlador.php', // Ruta de tu controlador
                            type: 'POST',
                            data: {
                                accion: 9,
                                id: $('#id').val(),
                                ext: file.name.split('.').pop(), // Extensión del archivo
                                ruta: file.name // Nombre de la imagen a eliminar
                            },
                            success: function(response) {
                                tabla.ajax.reload(null, false); // Recargar la tabla sin resetear la paginación
                                // console.log("Documento eliminada del servidor:", response);
                            }
                        });
                    } else {
                        console.log("Documento no existente en el servidor, eliminada solo del cliente.");
                    }
                });

                this.removeAllFilesWithoutServer = function() {
                    removeAllFilesCalled = true; // Indicamos que se ha llamado a removeAllFiles
                    this.removeAllFiles(true); // Limpiar archivos del contenedor
                    removeAllFilesCalled = false; // Resetear la bandera después de la limpieza
                };
            }
        });

        let drop_pre = new Dropzone("#dropzone-presupuesto", {
            url: "/ruta/para/subir/presupuesto",
            acceptedFiles: ".pdf,.xls,.xlsx",
            autoProcessQueue: false,
            addRemoveLinks: true,
            dictDefaultMessage: "Arrastra aquí o haz clic para subir PDF/Excel",
            init: function() {
                let removeAllFilesCalled = false;

                this.on("addedfile", function(file) {
                    var dzImage = file.previewElement.querySelector('.dz-image');
                    if (dzImage) {
                        dzImage.innerHTML = getFileIconSVG(file);
                    }
                    let pdfCount = 0,
                        excelCount = 0;
                    this.files.forEach(f => {
                        if (f.type === "application/pdf") pdfCount++;
                        if (
                            f.type === "application/vnd.ms-excel" ||
                            f.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        ) excelCount++;
                    });

                    if ((file.type === "application/pdf" && pdfCount > 1) ||
                        ((file.type === "application/vnd.ms-excel" || file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") && excelCount > 1) ||
                        this.files.length > 2) {
                        this.removeFile(file);
                        alert("Solo puedes subir 1 archivo PDF y 1 archivo Excel por vez.");
                    }
                });

                this.on("removedfile", function(file) {
                    // Verificar si se llamó a removeAllFiles, en ese caso no hacer nada en el servidor
                    if (removeAllFilesCalled) {
                        // console.log("Archivo eliminado solo del contenedor, no del servidor.");
                        return; // No realizar ninguna acción con el servidor
                    }

                    // Si el archivo es existente en el servidor, eliminarlo del servidor
                    // if (file.isExisting) {
                    //     $.ajax({
                    //         url: 'controllers/salidas.controlador.php', // Ruta de tu controlador
                    //         type: 'POST',
                    //         data: {
                    //             accion: 9,
                    //             nombre_imagen: file.name // Nombre de la imagen a eliminar
                    //         },
                    //         success: function(response) {
                    //             // console.log("Imagen eliminada del servidor:", response);
                    //         }
                    //     });
                    // } else {
                    //     console.log("Imagen no existente en el servidor, eliminada solo del cliente.");
                    // }
                });


                this.removeAllFilesWithoutServer = function() {
                    removeAllFilesCalled = true; // Indicamos que se ha llamado a removeAllFiles
                    this.removeAllFiles(true); // Limpiar archivos del contenedor
                    removeAllFilesCalled = false; // Resetear la bandera después de la limpieza
                };
            }
        });

        let drop_oc = new Dropzone("#dropzone-oc", {
            url: "/ruta/para/subir/presupuesto",
            acceptedFiles: ".pdf,.jpg,.jpeg,.png, .webp",
            autoProcessQueue: false,
            addRemoveLinks: true,
            dictDefaultMessage: "Arrastra aquí o haz clic para subir PDF/Excel",
            init: function() {
                let removeAllFilesCalled = false;
                this.on("addedfile", function(file) {
                    var dzImage = file.previewElement.querySelector('.dz-image');
                    if (dzImage) {
                        if (file.type === "application/pdf") {
                            dzImage.innerHTML = getFileIconSVG(file);
                        } else if (file.type.startsWith("image/")) {
                            // Mostrar miniatura real de la imagen
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                dzImage.innerHTML = `<img src="${e.target.result}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;" alt="preview">`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            dzImage.innerHTML = getFileIconSVG(file) || "";
                        }
                    }
                    let pdfCount = 0;
                    this.files.forEach(f => {
                        if (f.type === "application/pdf") pdfCount++;
                    });

                    if ((file.type === "application/pdf" && pdfCount > 1)) {
                        this.removeFile(file);
                        alert("Solo puedes subir 1 archivo PDF por vez.");
                    }
                });

                this.on("removedfile", function(file) {
                    // Verificar si se llamó a removeAllFiles, en ese caso no hacer nada en el servidor
                    if (removeAllFilesCalled) {
                        // console.log("Archivo eliminado solo del contenedor, no del servidor.");
                        return; // No realizar ninguna acción con el servidor
                    }
                });


                this.removeAllFilesWithoutServer = function() {
                    removeAllFilesCalled = true; // Indicamos que se ha llamado a removeAllFiles
                    this.removeAllFiles(true); // Limpiar archivos del contenedor
                    removeAllFilesCalled = false; // Resetear la bandera después de la limpieza
                };
            }
        });

        let drop_ae = new Dropzone("#dropzone-ae", {
            url: "/ruta/para/subir/presupuesto",
            acceptedFiles: ".pdf,.doc,.docx",
            autoProcessQueue: false,
            addRemoveLinks: true,
            dictDefaultMessage: "Arrastra aquí o haz clic para subir PDF/Word",
            init: function() {
                let removeAllFilesCalled = false;
                this.on("addedfile", function(file) {
                    var dzImage = file.previewElement.querySelector('.dz-image');
                    if (dzImage) {
                        if (file.type === "application/pdf") {
                            dzImage.innerHTML = getFileIconSVG(file);
                        } else if (
                            file.type === "application/msword" ||
                            file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                            dzImage.innerHTML = getFileIconSVG(file);
                        }
                    }
                    let pdfCount = 0,
                        wordCount = 0;
                    this.files.forEach(f => {
                        if (f.type === "application/pdf") pdfCount++;
                        if (
                            f.type === "application/msword" ||
                            f.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                        ) wordCount++;
                    });

                    if ((file.type === "application/pdf" && pdfCount > 1) ||
                        ((file.type === "application/msword" || file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") && wordCount > 1) ||
                        this.files.length > 2) {
                        this.removeFile(file);
                        alert("Solo puedes subir 1 archivo PDF y 1 archivo Word por vez.");
                    }
                });

                this.on("removedfile", function(file) {
                    // Verificar si se llamó a removeAllFiles, en ese caso no hacer nada en el servidor
                    if (removeAllFilesCalled) {
                        // console.log("Archivo eliminado solo del contenedor, no del servidor.");
                        return; // No realizar ninguna acción con el servidor
                    }
                });


                this.removeAllFilesWithoutServer = function() {
                    removeAllFilesCalled = true; // Indicamos que se ha llamado a removeAllFiles
                    this.removeAllFiles(true); // Limpiar archivos del contenedor
                    removeAllFilesCalled = false; // Resetear la bandera después de la limpieza
                };
            }
        });

        $('.select-filter').html('<div class="row" id="rowFilter" style="padding:.25rem .55rem .25rem;flex-wrap:nowrap"><div style="max-width:max-content" class="col-sm-3"><label style="padding-block:.5rem;white-space:nowrap" class="col-form-label" ><i class="fas fa-shuffle"></i> Estado:</label></div> <div class="col-sm-6"><select id="cboPreEstadoFilter" class="cbo form-control select2 select2-dark" data-dropdown-css-class="select2-dark" data-placeholder="TODO"><option value="null">TODO</option><option value="PENDIENTE">PENDIENTE</option><option value="NO APROBADO">NO APROBADO</option><option value="APROBADO">APROBADO</option> </select> </div></div>');

        let accion = 0;
        const modal = document.getElementById('modal'),
            modal_date = document.getElementById('modal-date'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            icon = document.querySelector('.modal-title i'),
            select = document.querySelectorAll('.modal-body select.select2'),
            btnNuevo = document.getElementById('btnNuevo');

        const id = document.getElementById('id'),
            desc = document.getElementById('desc'),
            orden_nro = document.getElementById('orden_nro'),
            nota = document.getElementById('nota'),
            fecha_new = document.getElementById('fecha_new'),
            cboClienteOrden = document.getElementById('cboClientesOrden'),
            fileInput = document.getElementById('fileOrden'),
            cboPreEstadoFilter = document.getElementById('cboPreEstadoFilter'),
            precioSinIva = document.getElementById("precioSinIva"),
            precioConIva = document.getElementById("precioConIva");

        precioConIva.addEventListener("input", () => {
            if (precioConIva.value !== "") {
                const valor = parseFloat(precioConIva.value);
                precioSinIva.value = (valor / (1 + IVA)).toFixed(2);
            } else {
                precioSinIva.value = "";
            }

        });

        precioSinIva.addEventListener("input", () => {
            if (precioSinIva.value !== "") {
                const valor = parseFloat(precioSinIva.value);
                precioConIva.value = (valor * (1 + IVA)).toFixed(2);
            } else {
                precioConIva.value = "";
            }
        });

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

        $(cboPreEstadoFilter).on("change", function() {
            estado_presupuesto = this.value;
            accion = 0;
            tabla.ajax.reload();
        });

        $(cboClienteOrden).select2({
            width: '100%',
            data: datos_cliente
        })

        $(cboPreEstadoFilter).select2({
            width: '100%',
            minimumResultsForSearch: -1,
        });

        $(cboClienteOrden).change(function() {
            estilosSelect2(this, 'lblCO')
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nuevo Presupuesto ', icon, 'fa-money-check-dollar-pen', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                select.forEach(function(s) {
                    s.classList.remove('select2-warning');
                    s.classList.add('select2-success');
                });
                form.reset();
                precioConIva.disabled = false
                precioSinIva.disabled = false
                desc.disabled = false
                form.classList.remove('was-validated');
                setChange(cboClienteOrden, 0);

                drop_ord.removeAllFilesWithoutServer();
                drop_pre.removeAllFilesWithoutServer();
                drop_oc.removeAllFilesWithoutServer();
                drop_ae.removeAllFilesWithoutServer();
            });
        }

        $('#tblPresupuesto tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('esta', 'presupuesto', function(r) {
                if (r) {
                    confirmarAccion(src, 'presupuesto', tabla, '', function(r) {
                        if (r) {}
                    })
                }
            });
        });

        $('#tblPresupuesto tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            precioConIva.disabled = false
            precioSinIva.disabled = false
            desc.disabled = false
            div_fecha_new.style.display = 'none';
            cambiarModal(span, ' Editar Presupuesto', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            desc.value = row["descripcion"];
            orden_nro.value = row["num_orden"];
            precioConIva.value = row["precio_total"];
            precioSinIva.value = row["precio_iva"];
            setChange(cboClienteOrden, row["id_cliente"]);
            form.classList.remove('was-validated');
            nota.value = row["nota"];
            let datos = new FormData();
            datos.append('accion', 6); // o el valor que corresponda en tu backend
            datos.append('id', id.value); // el id del presupuesto a editar
            cargarFilesDropzone(datos, drop_ord, 'presupuesto', 'ordenes');
            let src = new FormData();
            src.append('accion', 7); // o el valor que corresponda en tu backend
            src.append('id', id.value); // el id del presupuesto a editar
            cargarFilesDropzone(src, drop_pre, 'presupuesto', 'presupuestos');
        });

        $('#tblPresupuesto tbody').on('click', '.estado-opcion', function(e) {
            e.preventDefault();
            const row = obtenerFila(this, tabla);
            const id = row["id"];
            const estado = $(this).data('estado');

            let data = new FormData();
            data.append('id', id);
            data.append('estado', estado);
            data.append('accion', 5);
            confirmarAccion(data, 'presupuesto', tabla, modal, function(r) {
                cargarAutocompletado(function(items) {
                    items_orden = items;
                    $('#nro_orden').autocomplete("option", "source", items);
                    $('#nro_ordenEntrada').autocomplete("option", "source", items);
                    $('#nro_ordenFab').autocomplete("option", "source", items);
                }, null, 'orden', 6)
            }, 800);
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            let datos = obtenerDatosFormulario();
            desc.disabled = desc.value === '';
            precioConIva.disabled = precioConIva.value === '';
            precioSinIva.disabled = precioSinIva.value === '';

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                desc.disabled = false;
                precioConIva.disabled = false;
                precioSinIva.disabled = false;
                return;
            }
            console.log(datos.get('orden_files[0]'));
            if (accion == 2) {

                // confirmarAccion(datos, 'presupuesto', tabla, modal, function(r) {
                //     cargarAutocompletado(function(items) {
                //         items_orden = items;
                //         $('#nro_orden').autocomplete("option", "source", items);
                //         $('#nro_ordenEntrada').autocomplete("option", "source", items);
                //         $('#nro_ordenFab').autocomplete("option", "source", items);
                //     }, null, 'orden', 6)
                // });
            } else {
                fetchOrderId(datos.get('orden'), datos.get('fecha'), function(response) {
                    if (response && response.id_cliente != null) {
                        mostrarConfirmacionExistente(datos, response);
                    } else {
                        confirmarAccion(datos, 'presupuesto', tabla, modal, function(r) {
                            cargarAutocompletado(function(items) {
                                items_orden = items;
                                $('#nro_orden').autocomplete("option", "source", items);
                                $('#nro_ordenEntrada').autocomplete("option", "source", items);
                                $('#nro_ordenFab').autocomplete("option", "source", items);
                            }, null, 'orden', 6)
                        });
                    }
                });
            }
        });

        function obtenerDatosFormulario() {
            const des = desc.value.trim().toUpperCase(),
                ord = orden_nro.value.trim(),
                id_cli = cboClienteOrden.value,
                cli_name = cboClienteOrden.selectedIndex > 0 ? cboClienteOrden.options[cboClienteOrden.selectedIndex].text : '',
                fecha_act = fecha_new.value;
            id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('des', des);
            datos.append('id_cliente', id_cli);
            datos.append('precio_sin_iva', precioSinIva.value === '' ? 0 : parseFloat(precioSinIva.value).toFixed(2));
            datos.append('precio_con_iva', precioConIva.value === '' ? 0 : parseFloat(precioConIva.value).toFixed(2));
            datos.append('nota', nota.value.trim().toUpperCase());
            datos.append('presupuesto', ord);
            datos.append('cliente', cli_name)
            datos.append('fecha', fecha_act);
            drop_ord.getAcceptedFiles().forEach((file, index) => {
                if (!file.isExisting) {
                    datos.append(`orden_files[${index}]`, file, file.name);
                }
            });
            drop_pre.getAcceptedFiles().forEach((file, index) => {
                if (!file.isExisting) {
                    datos.append(`presupuesto_files[${index}]`, file, file.name);
                }
            });
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