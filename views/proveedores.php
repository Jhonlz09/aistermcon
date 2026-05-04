<?php require_once __DIR__ . "/../utils/database/config.php"; ?>
<head>
    <title>Proveedores</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Proveedores</h1>
            </div>
            <?php if (isset($_SESSION["crear10"]) && $_SESSION["crear10"] === true) : ?>
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
                                <div class="col col-p">
                                    <h3 class="card-title ">Listado de proveedores</h3>
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
                        <table id="tblProveedores" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>RUC/CI</th>
                                    <th>PROVEEDOR</th>
                                    <th>TELÉFONO</th>
                                    <th>DIRECCIÓN</th>
                                    <th>EMAIL</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Rol</span></h4>
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
                                <div class="col-sm-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label class="label"><i class="fa-solid fa-signature"></i> Proveedor</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-data">
                                        <input autocomplete="off" inputmode="numeric" id="ruc" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="ruc" class="label"><i class="fas fa-building-user"></i> RUC/CI</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" inputmode="numeric" id="telefono" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="telefono" class="label"><i class="fas fa-phone"></i> Teléfono</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="correo" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="correo" class="label"><i class="fa-solid fa-envelope"></i> Correo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-data" style="margin-bottom:1rem;">
                                        <input autocomplete="off" id="direccion" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="direccion" class="label"><i class="fas fa-map-location-dot"></i> Dirección</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
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

<script>
    var mostrarCol = '<?php echo $_SESSION["editar10"] || $_SESSION["eliminar10"] ?>';
    var editar = '<?php echo $_SESSION["editar10"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar10"] ?>';
    var permisoAsociar = '<?php echo isset($_SESSION["editar10"]) && $_SESSION["editar10"] === true ? "1" : "" ?>';

    configuracionTable = {
        "responsive": true,
        "dom": '<"row"<"col-md-6"B><"col-md-6"p>>t',
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
                targets: 6,
                data: "acciones",
                responsivePriority: 2,
                visible: mostrarCol ? true : false,
                render: function(data, type, row, full, meta) {
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button>" : "") +
                        (permisoAsociar ?
                            " <button type='button' class='btn bg-gradient-info btnCatalogo' title='Catálogo de productos'>" +
                            " <i class='fa-solid fa-boxes-stacked'></i>" +
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
        buttons: [{
                extend: "excelHtml5",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                    search: "applied",
                    order: "applied",
                },
                text: "<i class='fa-regular fa-file-xls fa-xl'style='color: #0a8f00'></i>",
                titleAttr: "Exportar a Excel",
                title: "LISTADO DE PROVEEDORES",
                className: "btn btn-light",
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    let firstRow = true;
                    $('row c[r^="B"]', sheet).each(function() {
                        if (firstRow) {
                            firstRow = false;
                        } else {
                            $(this).attr('s', '50'); // '50' is for left-aligned text and 52 for -right , 51 - center
                        }
                    });
                    firstRow = true;
                    $('row c[r^="D"]', sheet).each(function() {
                        if (firstRow) {
                            firstRow = false;
                        } else {
                            $(this).attr('s', '50'); // '50' is for left-aligned text and 52 for -right , 51 - center
                        }
                    });
                }
            },
            {
                extend: "pdfHtml5",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                    search: "applied",
                    order: "applied",
                },
                text: "<i class='fa-regular fa-file-pdf fa-xl' style='color: #bd0000'></i>",
                titleAttr: "Exportar a PDF",
                className: "btn btn-light",
                title: "LISTADO DE PROVEEDORES",
                customize: function(doc) {
                    var now = new Date();
                    var jsDate = now.getDate() + "/" + (now.getMonth() + 1) + "/" + now.getFullYear();
                    doc.content.splice(0, 1);
                    doc.pageMargins = [40, 90, 40, 50];
                    doc["header"] = function() {
                        return {
                            columns: [{
                                    alignment: "left",
                                    text: "LISTADO DE PROVEEDORES",
                                    fontSize: 14,
                                    margin: [20, 25],
                                },
                                {
                                    alignment: "right",
                                    margin: [20, 0],
                                    text: ["Creado el: ", {
                                        text: jsDate.toString()
                                    }],
                                },

                            ],
                            margin: 20,
                        };
                    };

                    var objLayout = {};
                    objLayout["hLineWidth"] = function(i) {
                        return 1;
                    };
                    objLayout["vLineWidth"] = function(i) {
                        return 0.5;
                    };
                    objLayout["hLineColor"] = function(i) {
                        return "#aaa";
                    };
                    objLayout["vLineColor"] = function(i) {
                        return "#aaa";
                    };
                    doc.content[0].layout = objLayout;
                    doc["footer"] = function(page, pages) {
                        return {
                            columns: [{
                                alignment: "right",
                                text: [
                                    "pag ",
                                    {
                                        text: page.toString()
                                    },
                                    " de ",
                                    {
                                        text: pages.toString()
                                    },
                                ],
                            }, ],
                            margin: [20, 10, 40, 10],
                        };
                    };
                },
            },
            {
                extend: "print",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                    search: "applied",
                    order: "applied"
                },
                oSelectorOpts: {
                    filter: "applied",
                    order: "current"
                },
                text: "<i class='fa fa-print fa-xl'</i>",
                titleAttr: "Imprimir",
                className: "btn btn-light",
                title: "LISTADO DE PROVEEDORES",
            },
            {
                extend: "colvis",
                className: "btn btn-light font-weight-bold",
                columns: [0, 1, 2, 3, 4, 5],
            }
        ]
    }

    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#tblProveedores')) {
            tabla = $("#tblProveedores").DataTable({
                "ajax": {
                    "url": "controllers/proveedores.controlador.php",
                    "type": "POST",
                    "dataSrc": ''
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('proveedores', JSON.stringify(tablaData));
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
            ruc = document.getElementById('ruc'),
            nombre = document.getElementById('nombre'),
            correo = document.getElementById('correo'),
            direccion = document.getElementById('direccion'),
            telefono = document.getElementById('telefono');

        $(modal).on("shown.bs.modal", () => {
            nombre.focus();
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nuevo Proveedor', icon, 'fa-hand-holding-box', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                form.reset();
                form.classList.remove('was-validated');
                correo.disabled = false;
                telefono.disabled = false;
                ruc.disabled = false;

            });
        }

        $('#tblProveedores tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'proveedor', function(r) {
                if (r) {
                    confirmarAccion(src, 'proveedores', tabla, '', function(r) {
                        if (r) {
                            cargarCombo('Proveedores');
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

        $('#tblProveedores tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar Proveedor', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            ruc.value = row["ruc"];
            nombre.value = row["nombre"];
            direccion.value = row["direccion"];
            telefono.value = row["telefono"];
            correo.value = row["correo"];
            correo.disabled = false;
            telefono.disabled = false;
            ruc.disabled = false;
            form.classList.remove('was-validated');
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const nom = nombre.value.trim().toUpperCase(),
                ruc_ = ruc.value.trim(),
                tel = telefono.value.trim(),
                dir = direccion.value.trim().toUpperCase(),
                cor = correo.value.trim();

            telefono.disabled = tel === '';
            ruc.disabled = ruc_ === ''
            correo.disabled = cor === '';

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                correo.disabled = false;
                telefono.disabled = false;
                ruc.disabled = false;
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('ruc', ruc_);
            datos.append('tel', tel);
            datos.append('dir', dir);
            datos.append('correo', cor);
            datos.append('accion', accion);
            confirmarAccion(datos, 'proveedores', tabla, modal, function(r) {
                if (r) {
                    cargarCombo('Proveedores');
                }
            })
        });

        // ===== CATÁLOGO DE PRODUCTOS POR PROVEEDOR =====
        let tblCatalogo;
        let catalogoProveedorId = null;
        let catalogoProveedorNombre = '';

        // Click en botón Catálogo
        $('#tblProveedores tbody').on('click', '.btnCatalogo', function() {
            let row = obtenerFila(this, tabla);
            catalogoProveedorId = row['id'];
            catalogoProveedorNombre = row['nombre'];
            $('#catalogoProveedorNombre').text(catalogoProveedorNombre);
            $('#modalCatalogo').modal('show');
        });

        // Al abrir el modal del catálogo
        $('#modalCatalogo').on('shown.bs.modal', function() {
            // Inicializar o recargar DataTable
            if (tblCatalogo) {
                tblCatalogo.destroy();
            }
            tblCatalogo = $('#tblCatalogoProds').DataTable({
                ajax: {
                    url: 'controllers/proveedor_productos.controlador.php',
                    type: 'POST',
                    data: { accion: 1, id_proveedor: catalogoProveedorId },
                    dataSrc: function(json) {
                        if (json && json.status === 'danger') {
                            mostrarToast('danger', 'Error', 'fa-xmark', json.m);
                            return [];
                        }
                        return json;
                    }
                },
                dom: 't',
                ordering: false,
                paging: false,
                autoWidth: false,
                language: { emptyTable: 'No hay productos asociados a este proveedor' },
                columns: [
                    { data: null, className: 'text-center', render: function(d,t,r,m){ return m.row+1; } },
                    { data: 'nombre_interno' },
                    { data: 'codigo_proveedor', className: 'text-center',
                      render: function(data){ return '<input type="text" class="form-control form-control-sm text-center inp-cod-prov" value="'+(data||'')+'" style="min-width:90px;border-bottom:2px solid var(--select-border-bottom);">'; }
                    },
                    { data: 'nombre_proveedor',
                      render: function(data){ return '<input type="text" class="form-control form-control-sm inp-nom-prov" value="'+(data||'')+'" style="min-width:140px;border-bottom:2px solid var(--select-border-bottom);">'; }
                    },
                    { data: 'precio_referencial', className: 'text-center',
                      render: function(data){ return '$<input type="text" class="form-control form-control-sm text-center d-inline inp-precio-ref" value="'+(parseFloat(data)||0).toFixed(2)+'" inputmode="numeric" onpaste="validarPegado(this, event)" oninput="validarNumber(this,/[^0-9.]/g)" style="width:90px;border-bottom:2px solid var(--select-border-bottom);">'; }
                    },
                    { data: 'ultima_compra', className: 'text-center text-nowrap',
                      render: function(data){ return data || '<span class="text-muted">—</span>'; }
                    },
                    { data: null, className: 'text-center',
                      render: function(){
                        return '<center style="white-space:nowrap">'+
                          '<button type="button" class="btn btn-sm bg-gradient-danger btnDesasociar" title="Desasociar"><i class="fa-solid fa-link-slash"></i></button>'+
                          '</center>';
                      }
                    }
                ]
            });

            // Cargar autocomplete para agregar productos
            cargarAutocompletado(function(items) {
                let searchCatalogo = $('#searchCatalogoProducto');
                if (searchCatalogo.data('ui-autocomplete')) {
                    searchCatalogo.autocomplete('destroy');
                }
                searchCatalogo.val('');
                searchCatalogo.autocomplete({
                    source: function(request, response) {
                        const input = request.term.toLowerCase().trim();
                        const palabras = input.split(/\s+/).filter(p => p.length > 0);
                        let resultados = items.filter(item => {
                            const label = item.label.toLowerCase();
                            return palabras.every(p => label.includes(p));
                        });
                        resultados.sort((a,b) => {
                            const la = a.label.toLowerCase(), lb = b.label.toLowerCase();
                            const pa = la.indexOf(palabras[0]), pb = lb.indexOf(palabras[0]);
                            if(pa===0 && pb!==0) return -1;
                            if(pa!==0 && pb===0) return 1;
                            return pa-pb;
                        });
                        response(resultados);
                    },
                    minLength: 2,
                    autoFocus: true,
                    select: function(event, ui) {
                        // Asociar producto al proveedor
                        $.ajax({
                            url: 'controllers/proveedor_productos.controlador.php',
                            method: 'POST',
                            data: {
                                accion: 2,
                                id_proveedor: catalogoProveedorId,
                                id_producto: ui.item.id,
                                codigo_proveedor: '',
                                nombre_proveedor: '', // se llenará con default del modelo
                                precio_referencial: 0
                            },
                            dataType: 'json',
                            success: function(r) {
                                mostrarToast(r.status,
                                    r.status === 'success' ? 'Completado' : 'Error',
                                    r.status === 'success' ? 'fa-check' : 'fa-xmark',
                                    r.m);
                                if (r.status === 'success') {
                                    tblCatalogo.ajax.reload(null, false);
                                }
                            }
                        });
                        $(this).val('');
                        return false;
                    }
                }).data('ui-autocomplete')._renderItem = function(ul, item) {
                    let cantidad = (item.cantidad||0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    return $('<li>').append(
                        '<div style="padding:4px 6px;"><strong>' + item.label + '</strong> <span style="color:#6c7891">CANT: ' + cantidad + '</span></div>'
                    ).appendTo(ul);
                };
            });
        });

        // Marcar filas como modificadas cuando el usuario edita un input
        $('#tblCatalogoProds').on('input', '.inp-cod-prov, .inp-nom-prov, .inp-precio-ref', function () {
            $(this).closest('tr').addClass('cat-dirty');
        });

        // Guardar solo filas modificadas en una sola petición batch
        function guardarCatalogoBatch() {
            if (!tblCatalogo) return;
            let dirtyRows = $('#tblCatalogoProds tbody tr.cat-dirty');
            if (dirtyRows.length === 0) {
                $('#modalCatalogo').modal('hide');
                return;
            }

            let items = [];
            dirtyRows.each(function () {
                let rowData = tblCatalogo.row(this).data();
                let $tr = $(this);
                items.push({
                    id: rowData.id,
                    codigo_proveedor: $tr.find('.inp-cod-prov').val().trim(),
                    nombre_proveedor: $tr.find('.inp-nom-prov').val().trim(),
                    precio_referencial: $tr.find('.inp-precio-ref').val().trim()
                });
            });

            $.ajax({
                url: 'controllers/proveedor_productos.controlador.php',
                method: 'POST',
                data: { accion: 7, items: JSON.stringify(items) },
                dataType: 'json',
                success: function (r) {
                    mostrarToast(r.status,
                        r.status === 'success' ? 'Completado' : 'Error',
                        r.status === 'success' ? 'fa-check' : 'fa-xmark',
                        r.m);
                    if (r.status === 'success') {
                        dirtyRows.removeClass('cat-dirty');
                        tblCatalogo.ajax.reload(null, false);
                    }
                }
            });
        }

        // Botón Guardar del modal
        $('#btnGuardarCatalogo').on('click', function () {
            guardarCatalogoBatch();
        });

        // Desasociar producto
        $('#tblCatalogoProds').on('click', '.btnDesasociar', function() {
            let row = tblCatalogo.row($(this).closest('tr'));
            let data = row.data();
            confirmarEliminar('esta', 'asociación de producto', function(r) {
                if (r) {
                    $.ajax({
                        url: 'controllers/proveedor_productos.controlador.php',
                        method: 'POST',
                        data: { accion: 3, id: data.id },
                        dataType: 'json',
                        success: function(r) {
                            mostrarToast(r.status,
                                r.status === 'success' ? 'Completado' : 'Error',
                                r.status === 'success' ? 'fa-check' : 'fa-xmark',
                                r.m);
                            if (r.status === 'success') {
                                tblCatalogo.ajax.reload(null, false);
                            }
                        }
                    });
                }
            });
        });
    })
</script>

<!-- Modal Catálogo de Productos por Proveedor -->
<div class="modal fade" id="modalCatalogo">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title"><i class="fa-solid fa-boxes-stacked"></i>
                    Catálogo — <span id="catalogoProveedorNombre"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Buscador para asociar productos -->
                <div class="row mb-3">
                    <div class="col-md-6 ui-front">
                        <label class="col-form-label combo" for="searchCatalogoProducto">
                            <i class="fas fa-magnifying-glass-plus"></i> Asociar producto del inventario
                        </label>
                        <input type="search" class="form-control form-control-sm" id="searchCatalogoProducto"
                            placeholder="Escriba para buscar y asociar..." autocomplete="off"
                            style="border-bottom: 2px solid var(--select-border-bottom);">
                    </div>
                </div>
                <!-- Tabla de productos asociados -->
                <div class="table-responsive">
                    <table id="tblCatalogoProds" class="table table-bordered table-sm table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">Nº</th>
                                <th>PRODUCTO INTERNO</th>
                                <th class="text-center">CÓD. PROVEEDOR</th>
                                <th>NOMBRE COMERCIAL</th>
                                <th class="text-center">PRECIO UNI. REF.</th>
                                <th class="text-center">ÚLT. COMPRA</th>
                                <th class="text-center" style="width:100px">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn bg-gradient-info" id="btnGuardarCatalogo">
                    <i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
