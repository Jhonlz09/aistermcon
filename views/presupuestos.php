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
                                    <th class="text-center"><i class="fas fa-money-check-dollar-pen fa-lg"></i> PPT</th>
                                    <th class="text-center"><i class="fas fa-tickets fa-lg"></i> OT</th>
                                    <th class="text-center"><i class="fa-solid fa-file-invoice-dollar fa-lg"></i> OC</th>
                                    <th class="text-center"><i class="fa-solid fa-clipboard-check fa-lg"></i> AE</th>
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
                                <div class="col-md-9 form-group mb-4">
                                    <!-- <div class="form-group mb-4"> -->
                                    <label id="lblCO" class="mb-0 combo selected-bor">
                                        <i class="fas fa-user-tag"></i> Cliente
                                    </label>

                                    <div class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
                                        <!-- SELECT2 -->
                                        <div class="flex-grow-1" id="selectClienteContainer">
                                            <select id="cboClientesOrden"
                                                class="cbo modalB form-control select2 select2-success"
                                                data-dropdown-css-class="select2-dark"
                                                data-placeholder="SELECCIONE"
                                                required>
                                            </select>
                                            <div class="invalid-feedback mt-0">*Campo obligatorio.</div>
                                        </div>
                                        <div class="flex-grow-1" id="inputClienteContainer" style="display:none;">
                                            <div class="input-data mb-0" style="height:2.39rem;">
                                                <input autocomplete="off" id="nuevoCliente" class="input-nuevo" type="text" required>
                                                <div class="line underline"></div>
                                                <div class="invalid-feedback mt-0">*Campo obligatorio.</div>
                                            </div>
                                        </div>

                                        <!-- BOTÓN DE CAMBIO -->
                                        <span id="toggleCliente"
                                            class="new-span badge bg-gradient-dark d-flex justify-content-center align-items-center"
                                            style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 4px; cursor: pointer;"
                                            title="Nuevo Cliente">
                                            <i class="fas fa-lg fa-input-text"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- </div> -->
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
                                        <input autocomplete="off" id="precioSinIva" class="input-nuevo" type="text" onpaste="validarPegado(this, event)" oninput="validarNumber(this,/[^0-9.]/g)" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fas fa-circle-dollar"></i> Precio <span style="font-size:60%;color: #666666ff;">(sin iva)</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-data mb-4">
                                        <input autocomplete="off" id="precioConIva" class="input-nuevo" type="text" onpaste="validarPegado(this, event)" oninput="validarNumber(this,/[^0-9.]/g)" required>
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
                            <div class="row">
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
<!-- Modal para ver PDFs -->
<div class="modal fade" id="modalVerPDFs" tabindex="-1" role="dialog" aria-labelledby="modalVerPDFsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title" id="modalVerPDFsLabel"><i class="fa fa-file-pdf"></i> Archivos PDF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenedorPDFs">
                <!-- Aquí se insertarán los iframes de los PDFs -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalVerImg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="carouselPreview" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators"></ol>
                    <div class="carousel-inner"></div>
                    <a class="carousel-control-prev" href="#carouselPreview" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselPreview" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
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
                    let clase = data === 'PENDIENTE' ? 'warning' :
                        data === 'NO APROBADO' ? 'danger' :
                        data === 'APROBADO' ? 'success' : 'secondary';

                    let icon = data === 'PENDIENTE' ? 'clock' :
                        data === 'NO APROBADO' ? 'file-xmark' :
                        data === 'APROBADO' ? 'file-check' : 'circle-question';

                    // Normaliza valores
                    let fecha_ope = row.fecha_ope?.trim() || '';
                    let fecha_fin = row.fecha_fin?.trim() || '';
                    let fecha_fac = row.fecha_fac?.trim() || '';
                    let fecha_gar = row.fecha_gar?.trim() || '';
                    let nota = row.nota_ord?.trim() || '';

                    // Construye solo los que tienen valor
                    const tooltipParts = [];

                    if (fecha_ope) tooltipParts.push(`Fecha de operación: ${fecha_ope}`);
                    if (fecha_fin) tooltipParts.push(`Fecha de finalización: ${fecha_fin}`);
                    if (fecha_fac) tooltipParts.push(`Fecha de facturación: ${fecha_fac}`);
                    if (fecha_gar) tooltipParts.push(`Fecha de garantía: ${fecha_gar}`);
                    if (nota) tooltipParts.push(`<strong>NOTA:</strong> ${nota}`);

                    // Si no hay nada, muestra un mensaje simple
                    const tooltipText = tooltipParts.length > 0 ?
                        tooltipParts.join('<br>') :
                        'Sin información disponible';
                    return `<span style="cursor:pointer" data-toggle="tooltip" title="${tooltipText}"   data-html="true" class="alert alert-default-${clase}">
                        <i class="fas fa-${icon}"></i> ${data}</span>`;
                }

            },
            {
                targets: 7,
                orderable: false,
                className: "text-center td-archivo",
                render: function(data, type, row) {
                    const {
                        pdf_pre,
                        xls_pre
                    } = row;

                    const btn = (file, color, icon, title, target) => {
                        if (file) {
                            return `<a href='/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=presupuestos' target='${target}' style='font-size:1.4rem;padding:3px 6.8px;color:${color}' class='btn btnDescargar' title='${title}'><i class='fas ${icon}'></i></a>`;
                        }
                        return `<span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'><i class='fas ${icon}'></i></span>`;
                    };

                    return (
                        btn(pdf_pre, "#a3161f", "fa-file-pdf", "Pdf", "_blank") +
                        "<br>" +
                        btn(xls_pre, "#155724", "fa-file-xls", "Excel", "")
                    );
                }
            },
            {
                targets: 8,
                orderable: false,
                className: "text-center td-archivo",
                render: function(data, type, row) {
                    const {
                        pdf_ord,
                        xls_ord
                    } = row;
                    const btn = (file, color, icon, title, target) => {
                        if (file) {
                            return `<a href='/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=ordenes' target='${target}' style='font-size:1.4rem;padding:3px 6.8px;color:${color}' class='btn btnDescargar'
                            title='${title}'><i class='fas ${icon}'></i></a>`;
                        }
                        return `<span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'><i class='fas ${icon}'></i></span>`;
                    };
                    return (
                        btn(pdf_ord, "#a3161f", "fa-file-pdf", "Pdf", "_blank") +
                        "<br>" +
                        btn(xls_ord, "#155724", "fa-file-xls", "Excel", "")
                    );
                }
            },
            {
                targets: 9,
                orderable: false,
                className: "text-center td-archivo",
                render: function(data, type, row) {
                    const pdfArr = parsePgArray(row.pdf_oc);
                    const imgArr = parsePgArray(row.img_oc);
                    // Badges
                    const pdfBadge = pdfArr.length > 0 ? `<span class="badge badge-info navbar-badge">${pdfArr.length}</span>` : '';
                    const imgBadge = imgArr.length > 0 ? `<span class="badge badge-info navbar-badge">${imgArr.length}</span>` : '';

                    const pdfIcon = `<i class='fas fa-file-pdf'>${pdfBadge}</i>`;
                    const imgIcon = `<i class='fas fa-file-jpg'>${imgBadge}</i>`;

                    // Función para crear botón
                    const btn = (arr, icon, color, title) => {
                        if (!arr || arr.length === 0) {
                            return `<span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'>${icon}</span>`;
                        }
                        const archivosStr = encodeURIComponent(JSON.stringify(arr));

                        return `<button type="button" 
                    onclick="window.verArchivosModal(JSON.parse(decodeURIComponent('${archivosStr}')))" 
                    style="font-size:1.4rem;padding:3px 4px;width:32.41px;color:${color}" 
                    class="btn btnDescargar" title="${title}">${icon}</button>`;
                    };

                    return btn(pdfArr, pdfIcon, "#a3161f", "PDF") + "<br>" + btn(imgArr, imgIcon, "#962d96", "Img");
                }
            },
            {
                targets: 10,
                orderable: false,
                className: "text-center td-archivo",
                render: function(data, type, row) {
                    const {
                        pdf_ae,
                        doc_ae
                    } = row;

                    const btn = (file, color, icon, title, target) => {
                        if (file) {
                            return `<a href='/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=actas_entrega' target='${target}' style='font-size:1.4rem;padding:3px 6.8px;color:${color}' class='btn btnDescargar' title='${title}'><i class='fas ${icon}'></i></a>`;
                        }
                        return `<span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed;color:darkgrey' class='btn'><i class='fas ${icon}'></i></span>`;
                    };

                    return (
                        btn(pdf_ae, "#a3161f", "fa-file-pdf", "Pdf", '_blank') +
                        "<br>" +
                        btn(doc_ae, "#004598ff", "fa-file-doc", "Word", '')
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
                            "</button> ";
                    }
                    if (eliminar) {
                        botones += " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                            " <i class='fa fa-trash'></i>" +
                            "</button> " + "<div class='btn-group'><button style='padding-inline:.5rem' type='button' class='btn bg-gradient-light btnEstado dropdown-toggle' data-toggle='dropdown' data-display='static' aria-haspopup='true' aria-expanded='false' title='Cambiar estado'><i class='fas fa-chart-candlestick'></i></button><div class='dropdown-menu dropdown-menu-xl-right'><a class='dropdown-item estado-opcion' data-estado='APROBADO' href='#'><i class='fas fa-file-check'></i> APROBADO</a><a class='dropdown-item estado-opcion' data-estado='PENDIENTE' href='#'><i class='fas fa-clock'></i> PENDIENTE</a><a class='dropdown-item estado-opcion' data-estado='NO APROBADO' href='#'><i class='fas fa-file-xmark'></i> NO APROBADO</a></div></div>";
                    }
                    return "<center style='white-space:nowrap'>" + botones + "</center>";
                }
            },
        ],
    }

    window.verArchivosModal = function(archivos) {
        if (!archivos || archivos.length === 0) {
            alert("No hay archivos para mostrar.");
            return;
        }

        // Detecta tipo por extensión del primer archivo
        const ext = archivos[0].toLowerCase().split('.').pop();
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
        const isPdf = ext === 'pdf';

        if (isPdf) {
            // 🔹 Manejo de PDFs con cache para evitar recarga
            const contenedor = document.getElementById('contenedorPDFs');

            // Hash único por conjunto de archivos
            const hash = archivos.join('|');

            // Revisamos si ya existe un contenedor cacheado para estos archivos
            let cachedDiv = document.querySelector(`#contenedorPDFs div[data-hash='${hash}']`);

            if (!cachedDiv) {
                // Si no existe, creamos el contenido
                cachedDiv = document.createElement('div');
                cachedDiv.dataset.hash = hash;
                cachedDiv.classList.add('pdf-group');
                cachedDiv.style.display = 'flex';
                cachedDiv.style.flexWrap = 'wrap';
                cachedDiv.style.gap = '1rem';
                cachedDiv.style.justifyContent = 'center';
                archivos.forEach(file => {
                    const url = `/aistermcon/utils/show.php?file=${encodeURIComponent(file)}&route=orden_compra`;

                    const wrapper = document.createElement('div');
                    wrapper.style.flex = '1 1 45%';
                    wrapper.style.minWidth = '350px';
                    wrapper.style.maxWidth = '600px';
                    wrapper.style.marginBottom = '10px';

                    const iframe = document.createElement('iframe');
                    iframe.src = url;
                    iframe.style.width = '100%';
                    iframe.style.height = '400px';
                    iframe.style.border = '1px solid #ccc';
                    iframe.style.borderRadius = '8px';
                    iframe.frameBorder = '0';

                    const btnDiv = document.createElement('div');
                    btnDiv.style.textAlign = 'center';
                    btnDiv.style.marginTop = '5px';
                    btnDiv.innerHTML = `<a href="${url}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fa fa-external-link"></i> Abrir en nueva pestaña
                                    </a>`;

                    wrapper.appendChild(iframe);
                    wrapper.appendChild(btnDiv);
                    cachedDiv.appendChild(wrapper);
                });

                contenedor.appendChild(cachedDiv);
            }

            // Ocultamos todos los grupos y mostramos solo el seleccionado
            document.querySelectorAll('#contenedorPDFs .pdf-group').forEach(div => div.style.display = 'none');
            cachedDiv.style.display = 'flex';
            $('#modalVerPDFs').modal('show');
        }

        if (isImage) {
            // 🔹 Manejo de imágenes en carrusel
            const indicators = document.querySelector('#carouselPreview .carousel-indicators');
            const inner = document.querySelector('#carouselPreview .carousel-inner');
            indicators.innerHTML = '';
            inner.innerHTML = '';

            archivos.forEach((file, index) => {
                const url = `/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=orden_compra`;

                indicators.innerHTML += `
                <li data-target="#carouselPreview" data-slide-to="${index}" ${index === 0 ? 'class="active"' : ''}></li>
            `;

                inner.innerHTML += `
                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                    <img src="${url}" class="d-block w-100" style="max-height:500px;object-fit:contain;" alt="Imagen ${index+1}">
                </div>
            `;
            });

            $('#modalVerImg').modal('show');
        }
    };
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
                        if (f.type === "application/vnd.ms-excel" || f.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") excelCount++;
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
                        eliminarArchivo(file, 'ordenes');
                    } else {
                        console.log(`Archivo ${file.name} eliminado solo del cliente (no existente en servidor).`);
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
                    console.log(file.name);
                    if (removeAllFilesCalled) {
                        return; // No realizar ninguna acción con el servidor
                    }

                    if (file.isExisting) eliminarArchivo(file, 'presupuestos');
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
            dictDefaultMessage: "Arrastra aquí o haz clic para subir PDF/JPG/PNG",
            init: function() {
                let removeAllFilesCalled = false;
                this.on("addedfile", function(file) {
                    var dzImage = file.previewElement.querySelector('.dz-image');
                    if (dzImage) {
                        if (file.type === "") {
                            const imageUrl = `/aistermcon/utils/download.php?&file=${encodeURIComponent(file.ruta)}&route=orden_compra`;
                            dzImage.innerHTML = `<img src="${imageUrl}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;"alt="preview">`;
                            // dzImage.innerHTML = `<img src="C:/var/www/orden_compra/${file.ruta}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;" alt="preview">`;
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
                    // let pdfCount = 0;
                    // this.files.forEach(f => {
                    //     if (f.type === "application/pdf") pdfCount++;
                    // });

                    // if ((file.type === "application/pdf" && pdfCount > 1)) {
                    //     this.removeFile(file);
                    //     alert("Solo puedes subir 1 archivo PDF por vez.");
                    // }
                });

                this.on("removedfile", function(file) {
                    console.log(file.name);
                    if (removeAllFilesCalled) {
                        return; // No realizar ninguna acción con el servidor
                    }

                    if (file.isExisting) eliminarArchivo(file, 'orden_compra');
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
                        dzImage.innerHTML = getFileIconSVG(file);
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
                    console.log(file.name);
                    if (removeAllFilesCalled) {
                        return; // No realizar ninguna acción con el servidor
                    }

                    if (file.isExisting) eliminarArchivo(file, 'actas_entrega');
                });


                this.removeAllFilesWithoutServer = function() {
                    removeAllFilesCalled = true; // Indicamos que se ha llamado a removeAllFiles
                    this.removeAllFiles(true); // Limpiar archivos del contenedor
                    removeAllFilesCalled = false; // Resetear la bandera después de la limpieza
                };
            }
        });




        $('.select-filter').html('<div class="row" id="rowFilter" style="padding:.25rem .55rem .25rem;flex-wrap:nowrap"><div style="max-width:max-content" class="col-sm-3"><label style="padding-block:.5rem;white-space:nowrap" class="col-form-label" ><i class="fas fa-shuffle"></i> Estado:</label></div> <div class="col-sm-6"><select id="cboPreEstadoFilter" class="cbo form-control select2 select2-dark" data-dropdown-css-class="select2-dark" data-placeholder="TODO"><option value="null">TODO</option><option value="PENDIENTE">PENDIENTE</option><option value="NO APROBADO">NO APROBADO</option><option value="APROBADO">APROBADO</option> </select> </div></div>');

        function eliminarArchivo(file, carpeta = '') {
            $.ajax({
                url: 'controllers/presupuesto.controlador.php',
                type: 'POST',
                data: {
                    accion: 9,
                    id: $('#id').val(),
                    ruta: file.ruta,
                    ext: file.name.split('.').pop().toLowerCase(),
                    carpeta: carpeta,
                    tipo: file.tipo // 👈 viene desde el mockFile
                },
                success: function(resp) {
                    console.log(`🗑️ Archivo eliminado: ${file.name}`, resp);
                    tabla.ajax.reload(null, false);
                },
                error: function(xhr, status, error) {
                    console.error(`❌ Error al eliminar el archivo: ${file.name}`, error);
                }
            });
        }
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

        // 

        // $(modal_date).on("shown.bs.modal", () => {
        //     btnConfirmar.focus();
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

        $('#toggleCliente').on('click', function() {
            const $icon = $(this).find('i');
            const isInputVisible = $('#inputClienteContainer').is(':visible');

            if (isInputVisible) {
                // Modo SELECT
                $('#inputClienteContainer input').val('');
                $('#inputClienteContainer').hide();
                $('#selectClienteContainer').show();
                $icon.removeClass('fa-list-dropdown').addClass('fa-input-text');

                $(this).attr('title', 'Escribir Cliente');
            } else {
                // Modo INPUT
                setChange(cboClienteOrden, 0);
                let labelElement = $("#lblCO");
                console.log(labelElement)
                labelElement.removeClass("selected-bor");
                $('#selectClienteContainer').hide();
                $('#inputClienteContainer').show();
                $('#inputClienteContainer input').trigger('focus');
                $icon.removeClass('fa-input-text').addClass('fa-list-dropdown');
                $(this).attr('title', 'Seleccionar Cliente');
            }
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
                precioConIva.disabled = false;
                precioSinIva.disabled = false;
                // nuevoCliente.disabled = false;
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
            confirmarEliminar('este', 'presupuesto', function(r) {
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
            precioConIva.disabled = false;
            precioSinIva.disabled = false;
            desc.disabled = false;
            cambiarModal(span, ' Editar Presupuesto', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            select.forEach(function(s) {
                s.classList.remove('select2-success');
                s.classList.add('select2-warning');
            });
            id.value = row["id"];
            desc.value = row["descripcion"];
            orden_nro.value = row["num_orden"];
            precioConIva.value = parseFloat((row["precio_total"] ?? '').toString().replace(/[$,]/g, '')) || '';
            precioSinIva.value = parseFloat((row["precio_iva"] ?? '').toString().replace(/[$,]/g, '')) || '';
            setChange(cboClienteOrden, row["id_cliente"]);
            form.classList.remove('was-validated');
            nota.value = row["nota"];
            let src = new FormData();
            src.append('accion', 7); // o el valor que corresponda en tu backend
            src.append('id', id.value); // el id del presupuesto a editar
            cargarTodosLosArchivos(id.value);
        });

        function cargarTodosLosArchivos(id) {
            drop_pre.removeAllFilesWithoutServer();
            drop_ord.removeAllFilesWithoutServer();
            drop_ae.removeAllFilesWithoutServer();
            drop_oc.removeAllFilesWithoutServer();
            $.ajax({
                url: 'controllers/presupuesto.controlador.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'accion': 6,
                    'id': id
                },
                success: function(response) {
                    console.log('📁 Archivos cargados:', response);

                    if (!response.files || !Array.isArray(response.files)) return;
                    response.files.forEach(file => {
                        const filename = file.nombre_file.split('/').pop();
                        const ext = filename.split('.').pop().toLowerCase();

                        // Tipos MIME para que Dropzone 
                        const extMap = {
                            pdf: 'application/pdf',
                            xls: 'application/vnd.ms-excel',
                            xlsx: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            doc: 'application/msword',
                            docx: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        };

                        const type = extMap[ext] || ``;

                        // Simulamos el archivo existente
                        const mockFile = {
                            name: filename,
                            size: 123456, // puedes poner tamaño real si lo deseas
                            type: type,
                            ruta: file.nombre_file,
                            tipo: file.tipo, // 👈 para saber a qué Dropzone pertenece
                            isExisting: true
                        };

                        // Asignamos al Dropzone correcto según 'tipo'
                        switch (file.tipo) {
                            case 'ppt':
                                agregarMockFile(drop_pre, mockFile);
                                break;
                            case 'ord':
                                agregarMockFile(drop_ord, mockFile);
                                break;
                            case 'ae':
                                agregarMockFile(drop_ae, mockFile);
                                break;
                            case 'oc':
                                agregarMockFile(drop_oc, mockFile);
                                break;
                        }
                    });
                },
            });
        }

        function agregarMockFile(drop, mockFile) {
            drop.emit('addedfile', mockFile);
            drop.emit('complete', mockFile);
            drop.files.push(mockFile);
        }

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
            // desc.disabled = desc.value === '';
            // nuevoCliente.disabled = nuevoCliente.value === '';
            // precioConIva.disabled = precioConIva.value === '';
            // precioSinIva.disabled = precioSinIva.value === '';
            let elementosAValidar = [orden_nro];

            if ($('#inputClienteContainer').is(':visible')) {
                elementosAValidar.push(nuevoCliente)
            } else {
                elementosAValidar.push(cboClienteOrden)
            }
            let isValid = true;
            elementosAValidar.forEach(function(elemento) {
                if (!elemento.checkValidity()) {
                    isValid = false;
                    form.classList.add('was-validated');
                }
            });
            if (!isValid) return
            // if (!this.checkValidity()) {
            //     this.classList.add('was-validated');
            //     desc.disabled = false;
            //     precioConIva.disabled = false;
            //     precioSinIva.disabled = false;
            //     // nuevoCliente.disabled = false;
            //     return;
            // }
            let datos = obtenerDatosFormulario();

            if (accion == 2) {
                confirmarAccion(datos, 'presupuesto', tabla, modal, function(r) {
                    // cargarAutocompletado(function(items) {
                    //     items_orden = items;
                    //     $('#nro_orden').autocomplete("option", "source", items);
                    //     $('#nro_ordenEntrada').autocomplete("option", "source", items);
                    //     $('#nro_ordenFab').autocomplete("option", "source", items);
                    // }, null, 'orden', 6)
                });
            } else {
                fetchOrderId(datos.get('presupuesto'), new Date(datos.get('fecha')).getFullYear(), function(response) {
                    console.log('Respuesta de fetchOrderId:', response);
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
                cli_name = cboClienteOrden.selectedIndex >= 0 ? cboClienteOrden.options[cboClienteOrden.selectedIndex].text : '',
                fecha_act = fecha_new.value;
            const isManual = $('#inputClienteContainer').is(':visible');
            id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('des', des);
            datos.append('id_cliente', id_cli);
            datos.append('precio_sin_iva', precioSinIva.value === '' ? 0 : parseFloat(precioSinIva.value).toFixed(2));
            datos.append('precio_con_iva', precioConIva.value === '' ? 0 : parseFloat(precioConIva.value).toFixed(2));
            datos.append('nota', nota.value.trim().toUpperCase());
            datos.append('presupuesto', ord);
            datos.append('fecha', fecha_act);
            if (isManual) {
                datos.append('isManual', isManual);
                datos.append('cliente_manual', nuevoCliente.value);
                datos.append('cliente', nuevoCliente.value)
            }else{
                datos.append('id_cliente', id_cli);
                datos.append('cliente', cli_name)
            }

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

            drop_ae.getAcceptedFiles().forEach((file, index) => {
                if (!file.isExisting) {
                    datos.append(`actas_files[${index}]`, file, file.name);
                }
            });

            drop_oc.getAcceptedFiles().forEach((file, index) => {
                if (!file.isExisting) {
                    datos.append(`orden_compra_files[${index}]`, file, file.name);
                }
            });
            datos.append('accion', accion);
            return datos;
        }

        // function mostrarConfirmacionExistente(datos) {
        //     Swal.fire({
        //         title: "Esta orden de trabajo ya existe",
        //         text: "¿Estás seguro que deseas continuar?",
        //         icon: "warning",
        //         showCancelButton: true,
        //         allowOutsideClick: false,
        //         allowEscapeKey: false,
        //         confirmButtonText: "Sí, continuar",
        //         cancelButtonText: "Cancelar",
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             confirmarAccion(datos, 'orden', tabla, modal, function(r) {
        //                 // Callback para acciones después de confirmarAccion si es necesario
        //             });
        //         }
        //     });
        // }

        function mostrarConfirmacionExistente(datos, cliente) {
            Swal.fire({
                title: `Numero de orden ya existe para '${cliente.nombre}'`,
                text: "¿Estás seguro que deseas continuar?",
                icon: "warning",
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
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