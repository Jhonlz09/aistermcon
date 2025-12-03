<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Pre trabajo</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Pre trabajo</h1>
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
                                    <h3 class="card-title ">Listado de pre trabajo</h3>
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
                        <table id="tblPretrabajo" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>FECHA DE INSPECCIÓN</th>
                                    <th>CLIENTE</th>
                                    <th>DETALLES</th>
                                    <th>ARCHIVOS</th>
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
<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 class="modal-title"><i class="fas fa-building-magnifying-glass"></i><span> Nuevo Pre trabajo</span></h4>
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
                                <div class="col-sm-4 form-group">
                                    <label class="combo m-0" style="font-size:1.15rem;" for="fecha_">
                                        <i class="fas fa-calendar"></i> Fecha de inspeccion</label>
                                    <input id="fecha_inp" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                                    <div class="invalid-feedback">*Campo obligatorio.</div>

                                </div>
                                <div class="col-sm-8 form-group">
                                    <div class="input-data mb-0">
                                        <input autocomplete="off" id="cliente" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fas fa-user-tag"></i> Cliente</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div>
                                        <label class="col-form-label combo" for="detalles">
                                            <i class="fas fa-note"></i> Detalles</label>
                                        <textarea style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);background-color:#d1d1d1" type="text" class="form-control" id="detalles" placeholder="Detalles..." spellcheck="false" data-ms-editor="true"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label class="combo" style="font-size: 1.15rem;">
                                        <i class="fa-solid fa-file-import"></i> Informe de visita de inspeccion a cliente (PDF/IMG)
                                    </label>
                                    <div id="dropzone-vi" class="dropzone dropzone-inline" style="border: 2px dashed #b3b3b3;">
                                        <div class="dz-message">Arrastra aquí o haz clic para subir PDF/IMG</div>
                                    </div>
                                    <small class="text-muted">*Solo archivos PDF o Imagen (.pdf, .jpg, .jpeg, .png)</small>
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
    var mostrarCol = '<?php echo $_SESSION["editar17"] || $_SESSION["eliminar17"] ?>';
    var editar = '<?php echo $_SESSION["editar17"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar17"] ?>';

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
                targets: 1,
                responsivePriority: 3,
            },
            {
                targets: 2,
                responsivePriority: 1,
            },
            {
                targets: 4,
                orderable: false,
                className: "text-center td-archivo",
                render: function(data, type, row) {
                    const pdfArr = parsePgArray(row.pdf_arr);
                    const imgArr = parsePgArray(row.img_arr);
                    // console.log('Archivos PDF:', pdfArr);
                    // console.log('Archivos IMG:', imgArr);
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

                    return btn(pdfArr, pdfIcon, "#a3161f", "PDF") + "  " + btn(imgArr, imgIcon, "#962d96", "Img");
                }
            },
            {
                targets: 5,
                data: "acciones",
                responsivePriority: 2,
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
                    const url = `/aistermcon/utils/show.php?file=${encodeURIComponent(file)}&route=pre_trabajo`;

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
                const url = `/aistermcon/utils/download.php?file=${encodeURIComponent(file)}&route=pre_trabajo`;

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
        if (!$.fn.DataTable.isDataTable('#tblPretrabajo')) {
            tabla = $("#tblPretrabajo").DataTable({
                "ajax": {
                    "url": "controllers/pre_trabajo.controlador.php",
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
            fecha = document.getElementById('fecha_inp'),
            cliente = document.getElementById('cliente'),
            // correo = document.getElementById('correo'),
            detalles = document.getElementById('detalles'),
            telefono = document.getElementById('telefono');

        $(modal).on("shown.bs.modal", () => {
            cliente.focus();
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


        let drop_vi = new Dropzone("#dropzone-vi", {
            url: "/ruta/para/subir/pretrabajo",
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
                            const imageUrl = `/aistermcon/utils/download.php?&file=${encodeURIComponent(file.ruta)}&route=pre_trabajo`;
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
                    if (file.isExisting) eliminarArchivo(file);
                });

                this.removeAllFilesWithoutServer = function() {
                    removeAllFilesCalled = true; // Indicamos que se ha llamado a removeAllFiles
                    this.removeAllFiles(true); // Limpiar archivos del contenedor
                    removeAllFilesCalled = false; // Resetear la bandera después de la limpieza
                };
            }
        });

        function eliminarArchivo(file) {
            $.ajax({
                url: 'controllers/pre_trabajo.controlador.php',
                type: 'POST',
                data: {
                    accion: 5,
                    id: $('#id').val(),
                    ruta: file.ruta,
                    ext: file.name.split('.').pop().toLowerCase(),
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

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nuevo Pre trabajo', icon, 'fa-building-magnifying-glass', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                form.reset();
                form.classList.remove('was-validated');
                drop_vi.removeAllFilesWithoutServer();
            });
        }

        $('#tblPretrabajo tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'pre trabajo', function(r) {
                if (r) {
                    confirmarAccion(src, 'pre_trabajo', tabla, '', function(r) {
                        if (r) {
                            cargarCombo('Pretrabajo');
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

        $('#tblPretrabajo tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar Pre trabajo', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new');
            id.value = row["id"];
            fecha.value = row["fecha_inspeccion"];
            cliente.value = row["cliente"];
            detalles.value = row["detalle"];
            drop_vi.removeAllFilesWithoutServer();
            form.classList.remove('was-validated');
            cargarTodosLosArchivos(id.value);
        });

        function cargarTodosLosArchivos(id) {
            drop_vi.removeAllFilesWithoutServer();
            $.ajax({
                url: 'controllers/pre_trabajo.controlador.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'accion': 4,
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
                        };

                        const type = extMap[ext] || ``;

                        // Simulamos el archivo existente
                        const mockFile = {
                            name: filename,
                            size: 123456, // puedes poner tamaño real si lo deseas
                            type: type,
                            ruta: file.nombre_file,
                            isExisting: true
                        };
                        agregarMockFile(drop_vi, mockFile);
                    });
                },
            });
        }

        function agregarMockFile(drop, mockFile) {
            drop.emit('addedfile', mockFile);
            drop.emit('complete', mockFile);
            drop.files.push(mockFile);
        }

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const cli = cliente.value.trim().toUpperCase(),
                det = detalles.value.trim().toUpperCase(),
                fecha_insp = fecha.value;
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('cliente', cli);
            datos.append('fecha', fecha_insp);
            datos.append('detalles', det);
            drop_vi.getAcceptedFiles().forEach((file, index) => {
                if (!file.isExisting) {
                    datos.append(`pre_trabajo_files[${index}]`, file, file.name);
                }
            });
            datos.append('accion', accion);
            confirmarAccion(datos, 'pre_trabajo', tabla, modal, function(r) {})
        
        });
    })
</script>