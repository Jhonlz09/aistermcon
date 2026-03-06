<?php require_once __DIR__ . "/../utils/database/config.php"; ?>
<head>
    <title>Solicitud de Despacho</title>
    <style>
        .highlight-row {
            animation: highlight 1.5s ease-out;
        }

        @keyframes highlight {
            0% {
                background-color: #93b0ffff;
            }

            100% {
                background-color: transparent;
            }
        }
    </style>
</head>
<!-- Contenido Header -->
<section id="div_header" class="ini-section content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Solicitud de Despacho</h1>
            </div>
            <?php if (isset($_SESSION["crear5"]) && $_SESSION["crear5"] === true): ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-green">
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
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
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto col-p" style="padding-right: .3rem">
                                    <h3 class="card-title">Listado de solic. de despacho</h3>
                                </div>
                                <div class=" col col-sm-auto mr-5">
                                    <select id="cboAnioSol" class="form-control select2 select2-dark"
                                        data-dropdown-css-class="select2-dark">
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <div style="margin-block:.4rem;height:33px;" class="input-group">
                                        <span class="input-group-text" style="height:30px;"><i
                                                class="fas fa-search icon"></i></span>
                                        <input autocomplete="off" style="border:none;" style="height:30px" type="search"
                                            id="_search" oninput="Buscar(tabla,this)" class="form-control float-right"
                                            placeholder="Buscar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table id="tblDespacho" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>N° SOLICITUD</th>
                                    <th>FECHA</th>
                                    <th>OBRA</th>
                                    <th>RESPONSABLE</th>
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
<!-- Formulario oculto al principio -->
<section id="div_sol" class="form-section content" style="display: none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="content-header px-0"
                    style="font-size:1.6rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap">
                    <span>
                        <i id="btnReturn" style="cursor:pointer" class="fa-regular fa-circle-arrow-left"></i><span
                            id="text_accion"> Nueva Solicitud de Despacho</span></span>
                    <span style="color:#cf0202;font-size:76%;font-weight:600">Nro. <span
                            id="nroSolicitud">00001</span></span>
                </div>
                <div class="row" style="align-items:flex-start">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <form id="formDespacho">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label class="col-form-label combo" for="fecha_des">
                                                    <i class="fas fa-calendar"></i> Fecha</label>
                                                <input id="fecha_des" type="date" autocomplete="off" value=""
                                                    style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);"
                                                    class="form-control form-control-sm" required>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="cboOrdenSol">
                                                <i class="fas fa-list-check"></i> Orden</label>
                                            <select id="cboOrdenSol" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                                <option value="">SELECCIONE</option>
                                            </select>
                                        </div> -->
                                        <div class=" col-md-8 form-group m-0 ui-front">
                                            <label class="col-form-label combo" for="nro_orden_sol">
                                                <i class="fas fa-ticket"></i> Orden de trabajo</label>
                                            <input
                                                style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);"
                                                type="search" class="form-control form-control-sm" id="nro_orden_sol"
                                                oninput="formatInputOrden(this)"
                                                placeholder="Ingrese el nro. de orden o cliente" required>
                                            <button class="clear-btn-inp" type="button" id="clearButtonSol"
                                                style="display:none"
                                                onclick="clearInput('nro_orden_sol', this)">&times;</button>
                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="div-productos" class="card" style="position:sticky;top:9%;z-index:6">
                            <div class="card-body" style="padding: 1.06em 1.25em">
                                <div class="row" style="line-height:1;">
                                    <div class="col-lg-12">
                                        <div class="ui-front">
                                            <label class="col-form-label combo" for="inputProductoSol">
                                                <i class="fas fa-arrow-up-a-z"></i> Productos</label>
                                            <input style="border-bottom: 2px solid var(--select-border-bottom);"
                                                type="search" class="form-control form-control-sm" id="inputProductoSol"
                                                placeholder="Ingrese el nombre del producto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <!-- Pestañas para categorías de productos -->
                                <ul class="nav nav-tabs" id="tabCategories" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-materiales" data-toggle="tab"
                                            href="#tabMateriales" role="tab">
                                            <i class="fas fa-box"></i> Materiales <span id="spanMateriales"
                                                class="badge right">0</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-herramientas" data-toggle="tab"
                                            href="#tabHerramientas" role="tab">
                                            <i class="fas fa-tools"></i> Herramientas <span id="spanHerramientas"
                                                class="badge right">0</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-fabricacion" data-toggle="tab" href="#tabFab"
                                            role="tab">
                                            <i class="fas fa-hammer-crash"></i> Fabricacion
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="tabContent">
                                    <!-- Tab Materiales -->
                                    <div class="tab-pane fade show active" id="tabMateriales" role="tabpanel">
                                        <div class="table-responsive"
                                            style="padding:0;border:1px solid #ccc;border-end-start-radius: 8px;border-end-end-radius:8px;">
                                            <table id="tblMateriales" class="table table-bordered w-100 table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Nº</th>
                                                        <th>CÓDIGO</th>
                                                        <th>DESCRIPCIÓN</th>
                                                        <th class="text-center">STOCK</th>
                                                        <th class="text-center">UND</th>
                                                        <th class="text-center">CANT. SOL.</th>
                                                        <th class="text-center">CANT. APRO.</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Tab Herramientas -->
                                    <div class="tab-pane fade" id="tabHerramientas" role="tabpanel">
                                        <div class="table-responsive"
                                            style="padding:0;border:1px solid #ccc;border-end-start-radius: 8px;border-end-end-radius:8px;">
                                            <table id="tblHerramientas"
                                                class="table table-bordered w-100 table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Nº</th>
                                                        <th>CÓDIGO</th>
                                                        <th>DESCRIPCIÓN</th>
                                                        <th class="text-center">STOCK</th>
                                                        <th class="text-center">UND</th>
                                                        <th class="text-center">CANT. SOL.</th>
                                                        <th class="text-center">CANT. APRO.</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Tab Fabricacion -->
                                    <div class="tab-pane fade" id="tabFab" role="tabpanel">
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="min-height: 100px; border: 1px solid #ccc; border-end-start-radius: 8px; border-end-end-radius: 8px;">
                                            <h4 class="text-muted"><i class="fas fa-hammer-crash mr-2"></i>
                                                PRÓXIMAMENTE...</h4>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-3" style="position:sticky;top:10%">
                        <div class="card">
                            <div class="card-body" style="line-height:1.2;">
                                <div class="form-group" style="margin-bottom:1.6rem;display:none">
                                    <label id="lbl" class="mb-0 combo"><i class="fas fa-clipboard-check"></i> Autorizado
                                        por</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <select id="cboAutorizado" class="cbo form-control select2 select2-success"
                                                data-dropdown-css-class="select2-dark">
                                            </select>
                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="lbl" class="mb-0 combo"><i class="fas fa-user-helmet-safety"></i>
                                        Responsable</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <select id="cboResponsableSol"
                                                class="cbo form-control select2 select2-success"
                                                data-dropdown-css-class="select2-dark">
                                            </select>
                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="col-form-label combo" for="inpNotas">
                                        <i class="fas fa-note"></i> Notas</label>
                                    <textarea
                                        style="border-bottom: 2px solid var(--select-border-bottom);background-color:#f6f6f6"
                                        type="text" class="form-control form-control-sm" id="inpNotas"
                                        placeholder="Observaciones..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <button type="button" id="btnGuardarDespacho"
                                    style="margin-bottom:.75rem;background:#3f6791 linear-gradient(180deg, #3f6791, #0b4395) repeat-x; color:#fff"
                                    class="btn w-100"><i class="fas fa-floppy-disk"></i><span class="button-text">
                                    </span>Guardar</button>
                                <button type="button" id="CerrarDespacho" style="border-color:#d6d8df69"
                                    class="btn bg-gradient-light w-100"><i class="fas fa-right-from-bracket"></i><span
                                        class="button-text"> </span>Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.Formulario oculto -->


<script>
    var mostrarCol = '<?php echo $_SESSION["editar5"] || $_SESSION["eliminar5"] ?>';
    var editar = '<?php echo $_SESSION["editar5"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar5"] ?>';
    var aprobar = '<?php echo $_SESSION["aprobar5"] ?>';
    var nc = '<?php echo $_SESSION["sc_desp"] ?? 1; ?>';

    console.log('secuencia', nc);

    configuracionTable = {
        "responsive": true,
        "dom": 'pt',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        columnDefs: [{
            targets: 0,
            data: null,
            className: "text-center",
            render: function (data, type, row, meta) {
                return type === 'display' ? meta.row + 1 : meta.row;
            }
        },

        {
            targets: 6,
            data: "acciones",
            visible: mostrarCol ? true : false,
            render: function (data, type, row, full, meta) {
                if (row.anulado) {
                    return `
                        <center style='white-space: nowrap;'>
                            <span title='Anulado' style='cursor:not-allowed;font-style: italic;' disabled>
                                ANULADO <i class='fas fa-ban'></i>
                            </span>
                        </center>`;
                }

                let color = row.estado ? 'success' : 'yellow';
                let icon = row.estado ? 'eye' : 'pencil';
                let botones = '<center style="white-space: nowrap;">';

                // Botón PDF
                botones += `<button type='button' class='btn bg-gradient-info btnImprimirPDF' title='Imprimir PDF' onclick="window.open('PDF/pdf_solicitud_despacho.php?id=${row.id}', '_blank')">
                                <i class='fas fa-file-pdf'></i>
                            </button> `;

                if (editar) {
                    botones += "<button type='button' class='btn bg-gradient-warning btnEditar' title='Editar'>" +
                        `<i class='fas fa-${icon}'></i>` +
                        "</button> ";
                }
                if (aprobar && !row.estado) {
                    botones += `<button type='button' class='btn bg-gradient-${color} btnAprobar'  title='Aprobar'>` +
                        " <i class='fa fa-clipboard-check'></i>" +
                        "</button> ";
                }
                if (eliminar) {
                    botones += " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                        " <i class='fa fa-trash'></i>" +
                        "</button> ";
                }
                botones += '</center>';
                return botones;
            }
        }
        ],
        "columns": [
            { data: null }, // Index defined in columnDefs
            { data: "num_sol" },
            { data: "fecha" },
            { data: "cliente" },
            { data: "responsable" },
            {
                data: "estado",
                className: "text-center",
                render: function (data, type, row) {

                   if (row.anulado) {
                    let btnReanudar = isSuperAdmin ? "<button type='button' class='btn bg-gradient-danger btnReanudar'  title='Desanular'> <i class='fa fa-ban'></i>" : "<span title='Anulado' style='cursor:not-allowed;font-style: italic;' disabled> ANULADO <i class='fas fa-ban'></i></span>";

                    return `
                        <center style='white-space: nowrap;'>
                            ${btnReanudar} 
                        </button>
                        </center>`;
                }

                    let clase = data ? 'success' : 'warning';
                    let icon = data ? 'file-check' : 'clock';
                    let estado = data ? 'APROBADO' : 'PENDIENTE';
                    // Tooltip con Autorizado por
                    let autorizado = row.autorizado ? row.autorizado.trim() : 'Sin información';
                    let tooltipText = `<strong>Autorizado por:</strong> ${autorizado}`;

                    return `<span style="cursor:pointer" data-toggle="tooltip" title="${tooltipText}" data-html="true" class="alert alert-default-${clase}">
                        <i class="fas fa-${icon}"></i> ${estado}</span>`;
                }
            },
            { data: "acciones", className: "text-center" } // Defined in columnDefs
        ],
        "rowCallback": function (row, data, index) {
            if (data.anulado) {
                $(row).addClass('fila-anulada');
            }
        }
    }

    $(document).ready(function () {
        let scrollPos = 0;
        let anio = year;
        let id_orden_sol
        let isAprobadoActual = false; // Estado de la solicitud actual
        let id_solicitud_edit = null; // ID de la solicitud en edición
        const btnReturn = document.getElementById('btnReturn'),
            btnCerrar = document.getElementById('CerrarDespacho'),
            fecha_des = document.getElementById('fecha_des'),
            formDespacho = document.getElementById('formDespacho'),
            div_sol = document.getElementById('div_sol'),
            div_content = document.getElementById('div_content'),
            inputProductoSol = document.getElementById('inputProductoSol'),
            inpNotas = document.getElementById('inpNotas'),
            nro_orden_sol = document.getElementById('nro_orden_sol'),
            cboResponsableSol = document.getElementById('cboResponsableSol'),
            cboAnioSol = document.getElementById('cboAnioSol')
        btnGuardarDespacho = document.getElementById('btnGuardarDespacho'),
            div_productos = document.getElementById('div-productos'),
            div_header = document.getElementById('div_header');

        $(cboAnioSol).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        });

        setChange(cboAnioSol, anio);

        $(cboAnioSol).on("change", function () {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            anio = a
            tabla.ajax.reload();
        });

        cargarAutocompletado(function (items) {
            $(inputProductoSol).autocomplete({
                // source: items,
                autoFocus: true,
                minLength: 3,
                source: function (request, response) {
                    const input = request.term.toLowerCase().trim();
                    // Detectar patrón "número x número" (dimensiones)
                    const esPatronDimensiones = /^\d+\s*x\s*\d+/i.test(input);
                    let resultados;

                    if (esPatronDimensiones) {
                        // Para patrones como "8 x 1", buscar con regex más estricto
                        const regex = new RegExp(input.replace(/\s+/g, '\\s*'), 'i');
                        resultados = items.filter(item => {
                            const label = item.label.toLowerCase();
                            return regex.test(label);
                        });
                        // Ordenar por relevancia: exactos primero, luego parciales
                        resultados.sort((a, b) => {
                            const labelA = a.label.toLowerCase();
                            const labelB = b.label.toLowerCase();

                            // Extraer los números del input (ej: "8" y "1" de "8 x 1")
                            const numeros = input.match(/\d+/g);
                            if (!numeros || numeros.length < 2) {
                                return labelA.localeCompare(labelB);
                            }

                            const num1 = numeros[0];
                            const num2 = numeros[1];
                            const patronExacto = new RegExp(`\\b${num1}\\s*x\\s*${num2}\\b`, 'i');

                            const matchA = patronExacto.test(labelA);
                            const matchB = patronExacto.test(labelB);

                            // Primero los que coinciden exactamente
                            if (matchA && !matchB) return -1;
                            if (!matchA && matchB) return 1;

                            // Si ambos coinciden exactamente, ordenar alfabéticamente
                            if (matchA && matchB) return labelA.localeCompare(labelB);

                            // Si ninguno coincide exactamente, ordenar por posición
                            const posA = labelA.indexOf(input.replace(/\s+/g, ' '));
                            const posB = labelB.indexOf(input.replace(/\s+/g, ' '));
                            return posA - posB;
                        });
                    } else {
                        // Para búsqueda normal: dividir en palabras
                        const palabras = input.split(/\s+/).filter(p => p.length > 0);
                        resultados = items.filter(item => {
                            const label = item.label.toLowerCase();
                            return palabras.every(palabra => label.includes(palabra));
                        });
                        // Ordenar por posición en label
                        resultados.sort((a, b) => {
                            const labelA = a.label.toLowerCase();
                            const labelB = b.label.toLowerCase();
                            const posA = labelA.indexOf(palabras[0]);
                            const posB = labelB.indexOf(palabras[0]);
                            if (posA === 0 && posB !== 0) return -1;
                            if (posA !== 0 && posB === 0) return 1;
                            return posA - posB;
                        });
                    }
                    response(resultados);
                },
                focus: function () {
                    return false;
                },
                select: function (event, ui) {
                    CargarProductos(ui.item.cod);
                    return false;
                },
            }).on('focus', function () {
                // Mostrar listado al retomar el foco si hay 3+ caracteres
                if ($(this).val().length >= 3) {
                    $(this).autocomplete('search', $(this).val());
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                let cantidad = (item.cantidad || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                const $node = $("<li>");
                const $inner = $("<div style='display:flex;align-items:center;gap:10px;padding:4px 6px;cursor:pointer;'>");
                let $thumb;
                if (item.img) {
                    let imgSrc = `/aistermcon/utils/download.php?&file=${encodeURIComponent(item.img)}&route=products`;
                    $thumb = $("<img/>").attr('src', imgSrc).attr('data-toggle', "modal").attr('data-target', "#modalImagenProducto").attr('data-img-src', imgSrc).attr('data-img-label', item.label).css({
                        width: '48px',
                        height: '48px',
                        objectFit: 'cover',
                        borderRadius: '4px',
                        cursor: 'pointer'
                    });
                    $thumb.on('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        $('#imgProductoModal').attr('src', $(this).attr('data-img-src'));
                        $('#modalImagenTitulo').text($(this).attr('data-img-label'));
                        // Asegura que Bootstrap modal se dispare después de actualizar contenido
                        setTimeout(function () {
                            if (!$('#modalImagenProducto').hasClass('show')) {
                                $('#modalImagenProducto').modal('show');
                            }
                        }, 0);
                    });
                } else {
                    $thumb = $("<div style='width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:4px;background:#f0f0f0;'>").append($("<i style='color:#555;font-size:1.5rem;' class='fas fa-xl fa-image-slash'></i>"));
                }
                const $text = $("<div style='flex:1;min-width:0;'>");
                $text.append($("<div style='font-weight:600;word-wrap:break-word;white-space:normal;line-height:1.4;'>").text(item.label));
                $text.append($("<div style='font-size:0.95rem;color:#6c7891;'>").html("CANTIDAD: <strong class='large-text'>" + cantidad + "</strong>"));
                $inner.append($thumb).append($text);
                $node.append($inner).appendTo(ul);
                return $node;
            };
        });

        $(cboResponsableSol).select2({
            placeholder: 'SELECCIONE',
            width: 'auto',
            data: datos_person
        })

        const onSelectOrdenSol = function (event, ui) {
            nro_orden_sol.value = ui.item.label;
            nro_orden_sol.readOnly = true;
            id_orden_sol = ui.item.cod;
            let clearButtonSol = document.getElementById('clearButtonSol');
            clearButtonSol.style.display = "block";
            nro_orden_sol.focus();
            return false;
        };

        const onSelectOrden = function (event, ui) {
            nro_orden.value = ui.item.label;
            nro_orden.readOnly = true;
            id_orden_sol = ui.item.cod;
            nro_orden.parentNode.querySelector(".ten").style.display = "none";
            clearButton.style.display = "block";
            nro_orden.focus();
            return false;
        };

        cargarAutocompletado(function (items) {
            items_orden = items;
            setupOrdenAutocomplete(nro_orden_sol, items, onSelectOrdenSol);
        }, null, 'orden', 6)

        if (!$.fn.DataTable.isDataTable('#tblDespacho')) {
            tabla = $("#tblDespacho").DataTable({
                "ajax": {
                    "url": "controllers/solicitud_mh.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function (data) {
                        data.anio = anio;
                    },
                },
                ...configuracionTable,
            });

            tabla.on('draw.dt', function () {
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;
                handleScroll(b, s, w);

                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                // console.log('valor nc', nc)
                const nro_despacho = (nc || 0).toString().padStart(6, '0');
                const nro_despacho_formateado = parseInt(nro_despacho, 10).toString().padStart(nro_despacho.length, '0');
                $('#nroSolicitud').text(nro_despacho_formateado);
                $('#text_accion').text(' Nueva solicitud de despacho');
                fecha_des.value = fecha_hoy;
                formDespacho.classList.remove('was-validated');
                scrollPos = window.scrollY || document.documentElement.scrollTop;

                setChange(cboResponsableSol, 0)
                inpNotas.value = '';
                clearButtonSol.click()
                id_orden_sol = null;
                id_solicitud_edit = null;

                // Reset state for new request
                isAprobadoActual = false;
                fecha_des.readOnly = false;
                $(cboResponsableSol).prop('disabled', false);
                inpNotas.readOnly = false;
                btnGuardarDespacho.style.display = '';
                div_productos.style.display = '';

                tablaMateriales.clear();
                tablaHerramientas.clear();

                // Reset columns visibility
                tablaMateriales.column(6).visible(false); // CANT APRO hidden
                tablaMateriales.column(7).visible(true);  // ACCIONES shown
                tablaHerramientas.column(6).visible(false); // CANT APRO hidden
                tablaHerramientas.column(7).visible(true);  // ACCIONES shown

                tablaMateriales.draw();
                tablaHerramientas.draw();
                actualizarConteos();

                // div_sol.style.display = "block";
                // div_content.style.display = "none";
                // div_header.style.display = "none";
                mostrarFormulario();
            });
        }


        function mostrarFormulario() {
            scrollPos = window.scrollY;
            div_sol.style.display = "block";
            div_content.style.display = "none";
            div_header.style.display = "none";
        }

        function ocultarFormulario() {
            div_sol.style.display = "none";
            div_content.style.display = "block";
            tabla.columns.adjust().draw(false);
            div_header.style.display = "block";
            window.scrollTo(0, scrollPos);
        }

        btnReturn.addEventListener('click', function () {
            ocultarFormulario();
        });

        btnCerrar.addEventListener('click', function () {
            ocultarFormulario();
        });


        // Inicializar DataTables para Materiales y Herramientas
        var tablaMateriales = $('#tblMateriales').DataTable({
            "dom": '<"row"<"col-sm-8"B><"col-sm-4"f>>t',
            "lengthChange": false,
            "ordering": false,
            "autoWidth": false,
            "paging": false,
            buttons: [{
                text: "<i class='fa-regular fa-trash-can fa-xl'style='color: #bd0000'></i> Borrar todo",
                className: "btn btn-light text-danger",
                action: function (e, dt, node, config) {
                    dt.clear().draw(); // Esta línea vacía los datos de la tabla
                }
            },
            ],
            "columns": [
                { data: null, className: "text-center", render: function (data, type, row, meta) { return meta.row + 1; } },
                { data: "codigo" },
                {
                    data: "descripcion",
                    render: function (data, type, row) {
                        let $inner = $("<div style='display:flex;align-items:center;gap:10px;'>");
                        let $thumb;
                        if (row.img) {
                            let imgSrc = `/aistermcon/utils/download.php?&file=${encodeURIComponent(row.img)}&route=products`;
                            $thumb = `<img src="${imgSrc}" 
                                data-toggle="modal" data-target="#modalImagenProducto" 
                                data-img-src="${imgSrc}" data-img-label="${data}"
                                style="width:40px;height:40px;object-fit:cover;border-radius:4px;cursor:pointer;min-width:40px"
                                onclick="
                                    event.preventDefault();
                                    event.stopPropagation();
                                    $('#imgProductoModal').attr('src', '${imgSrc}');
                                    $('#modalImagenTitulo').text('${data}');
                                    $('#modalImagenProducto').modal('show');
                                "/>`;
                        } else {
                            $thumb = `<div style='width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:4px;background:#f0f0f0;min-width:40px'>
                                <i style='color:#555;font-size:1.2rem;' class='fas fa-image-slash'></i>
                            </div>`;
                        }
                        return $inner.append($thumb).append(`<div>${data}</div>`).prop('outerHTML');
                    }
                },
                { data: "stock", className: "text-center" },
                { data: "unidad", className: "text-center" },
                {
                    data: "cant_sol",
                    className: "text-center",
                    render: function (data, type, row) {
                        let readonlyAttr = isAprobadoActual ? 'readonly' : '';
                        return `<input type="text" style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.4rem" 
                                class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" 
                                onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                                oninput="validarNumber(this,/[^0-9.]/g)" value="${data}" ${readonlyAttr}>`;
                    }
                },
                {
                    data: "cant_apro",
                    visible: false,
                    className: "text-center",
                    render: function (data, type, row) {
                        let readonlyAttr = isAprobadoActual ? 'readonly' : '';
                        return `<input type="text" style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.4rem" 
                                class="form-control text-center d-inline aprobada" inputmode="numeric" autocomplete="off" 
                                onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                                oninput="validarNumber(this,/[^0-9.]/g)" value="${data}" ${readonlyAttr}>`;
                    }
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        // return `<button class="btn btn-sm btn-danger btnEliminarFila" title="Eliminar"><i class="fas fa-trash"></i></button>`;
                        return `<center>
                            <span class='btnEliminaRowSol text-danger' style='cursor:pointer' data-bs-toggle='tooltip' 
                                data-bs-placement='top' title='Eliminar producto'> 
                                <i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'></i>
                            </span>
                                </center>`;
                    }
                }
            ],
            rowCallback: function (row, data) {
                $(row).attr('data-id', data.id_producto || data.id); // Guardar ID del producto o detalle
            }
        });

        var tablaHerramientas = $('#tblHerramientas').DataTable({
            "dom": '<"row"<"col-sm-8"B><"col-sm-4"f>>t',
            "lengthChange": false,
            "ordering": false,
            "autoWidth": false,
            "paging": false,
            buttons: [{
                text: "<i class='fa-regular fa-trash-can fa-xl'style='color: #bd0000'></i> Borrar todo",
                className: "btn btn-light text-danger",
                action: function (e, dt, node, config) {
                    dt.clear().draw(); // Esta línea vacía los datos de la tabla
                }
            },
            ],
            "columns": [
                { data: null, className: "text-center", render: function (data, type, row, meta) { return meta.row + 1; } },
                { data: "codigo" },
                {
                    data: "descripcion",
                    render: function (data, type, row) {
                        let $inner = $("<div style='display:flex;align-items:center;gap:10px;'>");
                        let $thumb;
                        if (row.img) {
                            let imgSrc = `/aistermcon/utils/download.php?&file=${encodeURIComponent(row.img)}&route=products`;
                            $thumb = `<img src="${imgSrc}" 
                                data-toggle="modal" data-target="#modalImagenProducto" 
                                data-img-src="${imgSrc}" data-img-label="${data}"
                                style="width:40px;height:40px;object-fit:cover;border-radius:4px;cursor:pointer;min-width:40px"
                                onclick="
                                    event.preventDefault();
                                    event.stopPropagation();
                                    $('#imgProductoModal').attr('src', '${imgSrc}');
                                    $('#modalImagenTitulo').text('${data}');
                                    $('#modalImagenProducto').modal('show');
                                "/>`;
                        } else {
                            $thumb = `<div style='width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:4px;background:#f0f0f0;min-width:40px'>
                                <i style='color:#555;font-size:1.2rem;' class='fas fa-image-slash'></i>
                            </div>`;
                        }
                        return $inner.append($thumb).append(`<div>${data}</div>`).prop('outerHTML');
                    }
                },
                { data: "stock", className: "text-center" },
                { data: "unidad", className: "text-center" },
                {
                    data: "cant_sol",
                    className: "text-center",
                    render: function (data, type, row) {
                        let readonlyAttr = typeof isAprobadoActual !== 'undefined' && isAprobadoActual ? 'readonly' : '';
                        return `<input type="text" style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.4rem" 
                                class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" 
                                onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                                oninput="validarNumber(this,/[^0-9.]/g)" value="${data}" ${readonlyAttr}>`;
                    }
                },
                {
                    data: "cant_apro",
                    visible: false,
                    className: "text-center",
                    render: function (data, type, row) {
                        let readonlyAttr = typeof isAprobadoActual !== 'undefined' && isAprobadoActual ? 'readonly' : '';
                        return `<input type="text" style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.4rem" 
                                class="form-control text-center d-inline aprobada" inputmode="numeric" autocomplete="off" 
                                onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                                oninput="validarNumber(this,/[^0-9.]/g)" value="${data}" ${readonlyAttr}>`;
                    }
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        return `<center>
                            <span class='btnEliminaRowSol text-danger' style='cursor:pointer' data-bs-toggle='tooltip' 
                                data-bs-placement='top' title='Eliminar producto'> 
                                <i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'></i>
                            </span>
                                </center>`;
                    }
                }
            ],
            rowCallback: function (row, data) {
                $(row).attr('data-id', data.id_producto || data.id);
            }
        });

        // Solución para DataTables dentro de tabs: 
        // Recalcular el tamaño de las columnas cuando se muestra el tab
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        function CargarProductos(codigo) {

            // Buscar en ambas tablas si ya existe
            let tablas = [tablaMateriales, tablaHerramientas];
            let productoExistente = false;
            let tablaDestino = null;

            tablas.forEach(function (tabla) {
                tabla.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    let data = this.data();
                    if (data.codigo == codigo) {
                        productoExistente = true;

                        // Incrementar cantidad
                        let rowNode = this.node();
                        let inputCantidad = $(rowNode).find('.cantidad');
                        let nuevaCantidad = parseFloat(inputCantidad.val() || 0) + 1;
                        inputCantidad.val(nuevaCantidad);

                        // Actualizar datos en memoria de DataTable (opcional, pero recomendado para consistencia)
                        data.cant_sol = nuevaCantidad;
                        // this.data(data).draw(false); // Si se hace draw completo, se pierde el foco y se 'redibuja' el input. Mejor solo actualizar valor visual y dato interno si es necesario sin redibujar todo
                        // Solo actualizamos el objeto data, DataTables guarda referencia

                        mostrarToast('info', 'Cantidad actualizada', 'fa-plus', 'Se incrementó la cantidad del producto: ' + data.descripcion);
                        $('#inputProductoSol').val('');

                        // Sroll to row
                        rowNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        $(rowNode).addClass('highlight-row');
                        setTimeout(() => $(rowNode).removeClass('highlight-row'), 1500);

                        return false; // Break loop
                    }
                });
                if (productoExistente) return false; // Break forEach
            });

            if (productoExistente) return;

            // Si no existe, cargar de BD
            let datos = new FormData();
            datos.append('accion', 3);
            datos.append('codigo', codigo);

            $.ajax({
                url: "controllers/solicitud_mh.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (respuesta) {
                    if (respuesta && respuesta.id) {
                        if (typeof audio !== 'undefined') audio.play();

                        // Preparar datos para DataTable
                        let nuevoProducto = {
                            id: respuesta.id, // ID del producto
                            codigo: respuesta.codigo,
                            descripcion: respuesta.descripcion,
                            stock: parseFloat(Number(respuesta.stock).toFixed(2)),
                            unidad: respuesta.unidad,
                            cant_sol: 1, // Inicializar en 1
                            cant_apro: 0,
                            img: respuesta.img
                        };

                        let nuevoNode;
                        if (respuesta.id_categoria == 1) {
                            nuevoNode = tablaMateriales.row.add(nuevoProducto).draw(false).node();
                            $('#tab-materiales').tab('show');
                        } else {
                            nuevoNode = tablaHerramientas.row.add(nuevoProducto).draw(false).node();
                            $('#tab-herramientas').tab('show');
                        }

                        // Scroll to new row
                        if (nuevoNode) {
                            nuevoNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            $(nuevoNode).addClass('highlight-row');
                            setTimeout(() => $(nuevoNode).removeClass('highlight-row'), 1500);
                        }

                        mostrarToast('success', 'Producto agregado', 'fa-check', 'Producto agregado correctamente');
                        $('#inputProductoSol').val('');
                        actualizarConteos();

                    } else {
                        mostrarToast('error', 'Error', 'fa-times', 'No se pudo cargar la información del producto');
                    }
                },
                error: function (err) {
                    console.error("Error cargando producto:", err);
                    mostrarToast('error', 'Error', 'fa-times', 'Error al cargar el producto');
                }
            });
        }


        // Evento para eliminar fila de productos (delegado para DataTables)
        $(document).on('click', '.btnEliminaRowSol', function () {
            let tabla = $(this).closest('table').DataTable(); // Obtener la DataTable correspondiente
            tabla.row($(this).closest('tr')).remove().draw(); // Eliminar la fila seleccionada
            actualizarConteos();
        });

        function actualizarConteos() {
            let countMateriales = tablaMateriales.rows().count();
            let countHerramientas = tablaHerramientas.rows().count();
            $('#spanMateriales').html(countMateriales);
            $('#spanHerramientas').html(countHerramientas);
        }

        // Guardar solicitud
        if (btnGuardarDespacho) {
            btnGuardarDespacho.addEventListener('click', function (e) {
                e.preventDefault();
                // Validar campos requeridos
                const fecha = document.getElementById('fecha_des').value;
                const responsable = $(cboResponsableSol).val();

                if (!fecha || !responsable || !id_orden_sol) {
                    mostrarToast('warning', 'Advertencia', 'fa-exclamation-triangle', 'Por favor complete todos los campos obligatorios (Fecha, Orden, Responsable).');
                    return;
                }

                // Recolectar datos de las filas
                let filas = [];

                // Función helper para procesar tablas
                function procesarTabla(tablaInstancia) {
                    tablaInstancia.rows().every(function () {
                        let data = this.data();
                        let rowNode = this.node();

                        // Obtener valores actualizados de los inputs
                        let cant_sol = $(rowNode).find('.cantidad').val() || 0;
                        let cant_apro = $(rowNode).find('.aprobada').val() || 0;

                        if (cant_sol > 0 || cant_apro > 0) {
                            filas.push({
                                id_producto: $(rowNode).attr('data-id'),
                                cant_sol: cant_sol,
                                cant_apro: cant_apro
                            });
                        }
                    });
                }

                procesarTabla(tablaMateriales);
                procesarTabla(tablaHerramientas);

                if (filas.length === 0) {
                    mostrarToast('warning', 'Advertencia', 'fa-exclamation-triangle', 'Debe agregar al menos un producto con cantidad.');
                    return;
                }

                let datos = new FormData();
                if (id_solicitud_edit) {
                    datos.append('accion', 15);
                    datos.append('id_solicitud', id_solicitud_edit);
                } else {
                    datos.append('accion', 14);
                }
                datos.append('id_orden', id_orden_sol);
                datos.append('fecha', fecha);
                datos.append('id_responsable', responsable);
                datos.append('notas', inpNotas.value);
                datos.append('filas', JSON.stringify(filas));

                confirmarEliminar('la', 'solicitud', function (r) {
                    if (r) {


                        let originalText = btnGuardarDespacho.innerHTML;
                        btnGuardarDespacho.disabled = true;
                        btnGuardarDespacho.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        confirmarAccion(datos, 'solicitud_mh', tabla, '', function (r) {
                            btnGuardarDespacho.disabled = false;
                            btnGuardarDespacho.innerHTML = originalText;
                            if (r && r.status === 'success') {
                                console.log('r', r);
                                // Actualizar la secuencia en el frontend
                                if (r.nc) {
                                    console.log('nc', r.nc);
                                    nc = r.nc;
                                }

                                ocultarFormulario();
                                // tabla.ajax.reload(); // updateAll or confirmarAccion already reloads? confirmarAccion reloads if tabla passed.
                                // Limpiar formulario
                                setChange(cboResponsableSol, 0)
                                inpNotas.value = '';
                                fecha_des.value = '';
                                //  nro_orden_sol.value = ''; 
                                clearButtonSol.click()
                                //  $('#nro_orden_sol').prop('readonly', false);
                                //  $('#clearButtonSol').hide();
                                id_orden_sol = null;
                                tablaMateriales.clear().draw();
                                tablaHerramientas.clear().draw();
                                actualizarConteos();
                            }
                        });
                    }
                }, 'guardar', '', 'Sí, guardar');
            });
        }


        // Evento Editar (Delegado)
        $('#tblDespacho tbody').on('click', '.btnEditar', function () {
            let row = obtenerFila(this, tabla);
            let id_solicitud = row["id"];

            // 1. Obtener datos de la solicitud (Header)
            let datos = new FormData();
            datos.append('accion', 1);
            datos.append('id', id_solicitud);

            $.ajax({
                url: "controllers/solicitud_mh.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (respuesta) {
                    if (respuesta) {
                        // Mostrar formulario
                        mostrarFormulario();
                        $('#text_accion').html(' Editar Solicitud de Despacho');
                        $('#nroSolicitud').html(respuesta.num_sol);

                        id_solicitud_edit = id_solicitud;

                        // Llenar campos
                        fecha_des.value = respuesta.fecha;
                        $(cboResponsableSol).val(respuesta.id_responsable).trigger('change');
                        inpNotas.value = respuesta.notas || '';

                        isAprobadoActual = respuesta.estado === true;

                        fecha_des.readOnly = isAprobadoActual;
                        $(cboResponsableSol).prop('disabled', isAprobadoActual);
                        inpNotas.readOnly = isAprobadoActual;

                        if (isAprobadoActual) {
                            btnGuardarDespacho.style.display = 'none';
                            div_productos.style.display = 'none';
                        } else {
                            btnGuardarDespacho.style.display = '';
                            div_productos.style.display = '';
                        }

                        // Toggle columns visibility based on status
                        tablaMateriales.column(6).visible(isAprobadoActual);
                        tablaMateriales.column(7).visible(!isAprobadoActual);
                        tablaHerramientas.column(6).visible(isAprobadoActual);
                        tablaHerramientas.column(7).visible(!isAprobadoActual);

                        // *** Autocomplete Trigger Logic ***
                        id_orden_sol = respuesta.id_orden;
                        let ordenLabel = respuesta.orden_label; // Asegurar que el modelo retorne esto
                        let nro_orden = $("#nro_orden_sol");

                        // Set value and readonly
                        nro_orden.val(ordenLabel);
                        nro_orden.prop('readonly', true);
                        $('#clearButtonSol').show();

                        // Manually trigger select event if autocomplete instance exists
                        if (nro_orden.data("ui-autocomplete")) {
                            let selectedItem = { label: ordenLabel, cod: id_orden_sol };
                            nro_orden.autocomplete("instance")._trigger("select", null, { item: selectedItem });
                        }

                        // 2. Obtener detalles
                        obtenerDetallesSolicitud(id_solicitud);
                    }
                }
            });
        });

        function obtenerDetallesSolicitud(id) {
            let datos = new FormData();
            datos.append('accion', 2);
            datos.append('id', id);

            $.ajax({
                url: "controllers/solicitud_mh.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (respuesta) {
                    // Limpiar tablas primero
                    tablaMateriales.clear();
                    tablaHerramientas.clear();

                    if (respuesta && respuesta.length > 0) {
                        respuesta.forEach(prod => {
                            let nuevoProducto = {
                                id: prod.id_producto,
                                codigo: prod.codigo,
                                descripcion: prod.descripcion,
                                stock: parseFloat(Number(prod.stock).toFixed(2)),
                                unidad: prod.unidad,
                                cant_sol: parseFloat(Number(prod.cant_sol).toFixed(2)),
                                cant_apro: parseFloat(Number(prod.cant_apro).toFixed(2)),
                                img: prod.img
                            };

                            if (prod.categoria === 'MATERIAL') { // Ajustar según los nombres reales de categoría en DB
                                tablaMateriales.row.add(nuevoProducto);
                            } else {
                                tablaHerramientas.row.add(nuevoProducto);
                            }
                        });
                        tablaMateriales.draw();
                        tablaHerramientas.draw();
                        actualizarConteos();
                    }
                }
            });
        }

        $('#tblDespacho tbody').on('click', '.btnEliminar', function () {
            let row = obtenerFila(this, tabla);
            const id_ = row["id"];
            let src = new FormData();
            src.append('accion', 12);
            src.append('id', id_);
            confirmarEliminar('esta', 'solicitud de despacho', function (r) {
                if (r) {
                    confirmarAccion(src, 'solicitud_mh', tabla, '', null);
                }
            });
        });

        // Evento Reanudar/Desanular (Delegado)
        $('#tblDespacho tbody').on('click', '.btnReanudar', function () {
            let row = obtenerFila(this, tabla);
            const id_ = row["id"];
            let src = new FormData();
            src.append('accion', 13);
            src.append('id', id_);
            confirmarEliminar('esta', 'solicitud de despacho', function (r) {
                if (r) {
                    confirmarAccion(src, 'solicitud_mh', tabla, '', null);
                }
            }, 'reanudar', 'La solicitud volverá a estar PENDIENTE.', 'Sí, reanudar');
        });

        // Evento Aprobar (Delegado)
        $('#tblDespacho tbody').on('click', '.btnAprobar', function () {
            let row = obtenerFila(this, tabla);
            const id_ = row["id"];
            const nuevoEstado = !row["estado"];
            let src = new FormData();
            src.append('accion', 10);
            src.append('id', id_);
            src.append('estado', nuevoEstado);

            let accionTexto = nuevoEstado ? 'aprobar' : 'revertir la aprobación de';
            let confirmacionTexto = nuevoEstado ?
                "Se actualizará el estado y las cantidades solicitadas serán aprobadas." :
                "El estado de la solicitud volverá a PENDIENTE.";

            // Reutilizando confirmarEliminar para confirmación genérica
            confirmarEliminar('de', 'solicitud de despacho', function (r) {
                if (r) {
                    confirmarAccion(src, 'solicitud_mh', tabla, '', null);
                }
            }, accionTexto, confirmacionTexto, 'Si, confirmar');
        });
    });
</script>
