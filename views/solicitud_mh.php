<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Solicitud de Despacho</title>
</head>

<!-- Contenido Header -->
<section id="div_header" class="ini-section content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Solicitud de Despacho</h1>
            </div>
            <?php if (isset($_SESSION["crear5"]) && $_SESSION["crear5"] === true) : ?>
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
                        <table id="tblDespacho" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>N° SOLICITUD</th>
                                    <th>FECHA</th>
                                    <th>OBRA</th>
                                    <th>RESPONSABLE</th>
                                    <th>AUTORIZADO POR</th>
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
                <div class="content-header px-0" style="font-size:1.6rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap">
                    <span>
                        <i id="btnReturn" style="cursor:pointer" class="fa-regular fa-circle-arrow-left"></i><span id="text_accion"> Nueva Solicitud de Despacho</span></span>
                    <span style="color:#cf0202;font-size:76%;font-weight:600">Nro. <span id="nroSolicitud">00001</span></span>
                </div>
                <div class="row" style="align-items:flex-start">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <form id="formDespacho">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group mb-0">
                                                <label class="col-form-label combo" for="fecha_des">
                                                    <i class="fas fa-calendar"></i> Fecha</label>
                                                <input id="fecha_des" type="date" autocomplete="off" value="" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="cboOrdenSol">
                                                <i class="fas fa-list-check"></i> Orden</label>
                                            <select id="cboOrdenSol" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                                <option value="">SELECCIONE</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="col-form-label combo" for="cboBoleta">
                                                <i class="fas fa-receipt"></i> Nro. Guia</label>
                                            <select id="cboBoleta" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                                <option value="">SELECCIONE</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card" id="div_productos_sol" style="position:sticky;top:2%;z-index:6">
                            <div class="card-body" style="padding: 1.06em 1.25em">
                                <div class="row" style="line-height:1;">
                                    <div class="col-lg-12">
                                        <div class="ui-front">
                                            <label class="col-form-label combo" for="inputProducto">
                                                <i class="fas fa-arrow-up-a-z"></i> Productos</label>
                                            <input style="border-bottom: 2px solid var(--select-border-bottom);" type="search" class="form-control form-control-sm" id="inputProducto" placeholder="Ingrese el nombre del producto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card">
                            <div class="card-body">
                                <!-- Pestañas para categorías de productos -->
                                <ul class="nav nav-tabs" id="tabCategories" role="tablist" style="margin-bottom:1rem;">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-materiales" data-toggle="tab" href="#tabMateriales" role="tab">
                                            <i class="fas fa-box"></i> Materiales
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-herramientas" data-toggle="tab" href="#tabHerramientas" role="tab">
                                            <i class="fas fa-tools"></i> Herramientas
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="tabContent">
                                    <!-- Tab Materiales -->
                                    <div class="tab-pane fade show active" id="tabMateriales" role="tabpanel">
                                        <div class="table-responsive" style="padding:0;border:1px solid #ccc;border-radius: 8px;">
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
                                        <div class="table-responsive" style="padding:0;border:1px solid #ccc;border-radius: 8px;">
                                            <table id="tblHerramientas" class="table table-bordered w-100 table-striped">
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
                                </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-3" style="position:sticky;top:10%">
                        <div class="card">
                            <div class="card-body" style="line-height:1.2;">
                                <div class="form-group" style="margin-bottom: 1.6rem;">
                                    <label id="lbl" class="mb-0 combo"><i class="fas fa-person-carry-box"></i> Despachado por</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <select id="cboDespachado" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                            </select>
                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:1.6rem;">
                                    <label id="lbl" class="mb-0 combo"><i class="fas fa-clipboard-check"></i> Autorizado por</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <select id="cboAutorizado" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                            </select>
                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="lbl" class="mb-0 combo"><i class="fas fa-user-helmet-safety"></i> Responsable</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <select id="cboResponsable" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                            </select>
                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label combo" for="inpMotivo">
                                        <i class="fas fa-note"></i> Notas</label>
                                    <textarea style="border-bottom: 2px solid var(--select-border-bottom);background-color:#f6f6f6" type="text" class="form-control form-control-sm" id="inpMotivo" placeholder="Observaciones..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <button type="button" id="btnGuardarDespacho" style="margin-bottom:.75rem;background:#3f6791 linear-gradient(180deg, #3f6791, #0b4395) repeat-x; color:#fff" class="btn w-100"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                                <button type="button" id="CerrarDespacho" style="border-color:#d6d8df69" class="btn bg-gradient-light w-100"><i class="fas fa-right-from-bracket"></i><span class="button-text"> </span>Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.Formulario oculto -->

<!-- Modal para autorizar -->
<div class="modal fade" id="modalAutorizar">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning">
                <h4 class="modal-title"><i class="fas fa-check-circle"></i> Autorizar Solicitud</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAutorizar" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cboAutorizadoPor"><i class="fas fa-user-check"></i> Autorizado por:</label>
                        <select class="form-control select2" id="cboAutorizadoPor" data-dropdown-css-class="select2-dark" required>
                            <option value="">SELECCIONE</option>
                        </select>
                        <div class="invalid-feedback">*Campo obligatorio.</div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn bg-gradient-light" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
                    <button type="submit" class="btn bg-gradient-warning"><i class="fas fa-check"></i> Autorizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para confirmar despacho -->
<div class="modal fade" id="modalConfirmarDespacho">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title"><i class="fas fa-truck"></i> Confirmar Despacho</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formConfirmarDespacho" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cboDespachoPor"><i class="fas fa-person-dolly"></i> Despachado por:</label>
                        <select class="form-control select2" id="cboDespachoPor" data-dropdown-css-class="select2-dark" required>
                            <option value="">SELECCIONE</option>
                        </select>
                        <div class="invalid-feedback">*Campo obligatorio.</div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn bg-gradient-light" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
                    <button type="submit" class="btn bg-gradient-info"><i class="fas fa-check"></i> Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar5"] || $_SESSION["eliminar5"] ?>';
    var editar = '<?php echo $_SESSION["editar5"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar5"] ?>';
    var nc = '<?php echo $_SESSION["sc_desp"] ?? 1; ?>';

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
                render: function(data, type, row, meta) {
                    return type === 'display' ? meta.row + 1 : meta.row;
                }
            },

            {
                targets: 7,
                data: "acciones",
                visible: mostrarCol ? true : false,
                render: function(data, type, row, full, meta) {
                    let botones = '<center style="white-space: nowrap;">';
                    if ('<?php echo $_SESSION["editar5"] ?? false; ?>') {
                        botones += `<button class="btn btn-sm btn-warning btnEditar" title="Editar"><i class="fas fa-edit"></i></button>`;
                    }
                    if ('<?php echo $_SESSION["eliminar5"] ?? false; ?>') {
                        botones += `<button class="btn btn-sm btn-danger btnEliminar" title="Eliminar"><i class="fas fa-trash"></i></button>`;
                    }
                    botones += `<button class="btn btn-sm bg-gradient-warning btnAutorizar" title="Autorizar"><i class="fas fa-check-circle"></i></button>`;
                    botones += ` <button class="btn btn-sm bg-gradient-info btnConfirmar" title="Confirmar Despacho"><i class="fas fa-truck"></i></button>`;
                    botones += '</center>';
                    return botones;
                }
            }
        ],
        "rowCallback": function(row, data, index) {
            if (data.anulado) {
                $(row).addClass('fila-anulada');
            }
        }
    }

    $(document).ready(function() {
        let scrollPos = 0;
        const btnReturn = document.getElementById('btnReturn'),
            btnCerrar = document.getElementById('CerrarDespacho'),
            fecha_des = document.getElementById('fecha_des'),
            formDespacho = document.getElementById('formDespacho'),
            div_sol = document.getElementById('div_sol'),
            div_content = document.getElementById('div_content'),
            div_header = document.getElementById('div_header');

        // Cargar datos iniciales
        // cargarDatosCombo();
        // Inicializar DataTable principal
        if (!$.fn.DataTable.isDataTable('#tblDespacho')) {
            tabla = $("#tblDespacho").DataTable({
                "ajax": {
                    "url": "controllers/solicitud_mh.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.accion = 0;
                    }
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;
                handleScroll(b, s, w);

                // let tablaData = tabla.rows().data().toArray();
                // localStorage.setItem('despacho', JSON.stringify(tablaData));
            });
        }



        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                console.log('valor nc', nc)
                const nro_despacho = (nc || 0).toString().padStart(4, '0');
                const nro_despacho_formateado = parseInt(nro_despacho, 10).toString().padStart(nro_despacho.length, '0');
                $('#nroSolicitud').text(nro_despacho_formateado);
                $('#text_accion').text(' Nueva solicitud de despacho');
                fecha_des.value = fecha_hoy;
                // setChange(cboProve, 0);
                // radio.click();
                // agregarFila();
                // $('#subtotal').text('$0.00');
                // $('#can_tax').text('$0.00');
                // $('#total').text('$0.00');
                // otros.value = '';
                // otros.readOnly = true;
                formDespacho.classList.remove('was-validated');

                scrollPos = window.scrollY || document.documentElement.scrollTop;

                div_sol.style.display = "block";
                div_content.style.display = "none";
                div_header.style.display = "none";
            });
        }

        function ocultarFormulario() {
            div_sol.style.display = "none";
            div_content.style.display = "block";
            tabla.columns.adjust().draw(false);
            // tblSolicitudMH.clear().draw();
            div_header.style.display = "block";
            window.scrollTo(0, scrollPos);
        }

        btnReturn.addEventListener('click', function() {
            ocultarFormulario();
        });

        btnCerrar.addEventListener('click', function() {
            ocultarFormulario();
        });

        // Botón Nuevo
        // if (document.getElementById('btnNuevo')) {
        //     document.getElementById('btnNuevo').addEventListener('click', function() {
        //         accion_desp = 1;

        //         const nro_solicitud = (nc || 0).toString().padStart(4, '0');
        //         const nro_formateado = parseInt(nro_solicitud, 10).toString().padStart(nro_solicitud.length, '0');
        //         document.getElementById('nroSolicitud').textContent = nro_formateado;
        //         document.getElementById('text_accion').textContent = ' Nueva Solicitud de Despacho';
        //         // Limpiar combos
        //         document.getElementById('cboOrdenSol').value = '';
        //         document.getElementById('cboBoleta').value = '';
        //         document.getElementById('cboResponsable').value = '';
        //         // Limpiar tablas
        //         document.getElementById('tblMateriales').querySelector('tbody').innerHTML = '';
        //         document.getElementById('tblHerramientas').querySelector('tbody').innerHTML = '';
        //         // Crear nueva solicitud
        //         crearNuevaSolicitud();
        //         scrollPos = window.scrollY || document.documentElement.scrollTop;
        //         document.getElementById("div_content").style.display = "none";
        //         document.getElementById("div_header").style.display = "none";
        //         document.getElementById("div_cot").style.display = "block";
        //     });
        // }

        // Crear nueva solicitud

        // Cargar productos agrupados por categoría
        // function cargarProductosPorCategoria() {
        //     $.ajax({
        //         url: "controllers/solicitud_mh.controlador.php",
        //         method: "POST",
        //         dataType: "json",
        //         data: {
        //             accion: 9
        //         },
        //         success: function(respuesta) {
        //             productosAgrupados = {};
        //             respuesta.forEach(grupo => {
        //                 productosAgrupados[grupo.categoria_id] = {
        //                     nombre: grupo.categoria,
        //                     productos: grupo.productos || []
        //                 };
        //             });
        //             poblarTablasProductos();
        //         }
        //     });
        // }

        // Poblar tablas de productos
        // function poblarTablasProductos() {
        //     $.ajax({
        //         url: "controllers/solicitud_mh.controlador.php",
        //         method: "POST",
        //         dataType: "json",
        //         data: {
        //             accion: 2,
        //             id: id_desp
        //         },
        //         success: function(respuesta) {
        //             let materiales = respuesta.filter(p => p.categoria === 'Material');
        //             let herramientas = respuesta.filter(p => p.categoria === 'Herramienta');
        //             poblarTabla('#tblMateriales', materiales);
        //             poblarTabla('#tblHerramientas', herramientas);
        //             // Actualizar contador
        //             let totalFilas = respuesta.length;
        //             document.getElementById('totalFilas').textContent = totalFilas;
        //         }
        //     });
        // }

        // Poblar tabla
        // function poblarTabla(selector, productos) {
        //     let tbody = $(selector + ' tbody');
        //     tbody.empty();
        //     productos.forEach((prod, index) => {
        //         let fila = `<tr data-id="${prod.id}">
        //             <td class="text-center">${index + 1}</td>
        //             <td>${prod.codigo}</td>
        //             <td>${prod.descripcion}</td>
        //             <td class="text-center"><small>${prod.stock}</small></td>
        //             <td class="text-center"><small>${prod.unidad}</small></td>
        //             <td><input type="text" class="form-control text-center cantidad" value="${prod.cant_sol || 0}" 
        //                 style="width:100%;border-bottom-width:2px;padding:0;font-size:1.2rem" maxlength="6"
        //                 inputmode="numeric" onfocus="selecTexto(this)" autocomplete="off" oninput="validarNumber(this,/[^0-9.]/g)"></td>
        //             <td><input type="text" class="form-control text-center aprobada" value="${prod.cant_apro || 0}"
        //                 style="width:100%;border-bottom-width:2px;padding:0;font-size:1.2rem" maxlength="6"
        //                 inputmode="numeric" onfocus="selecTexto(this)" autocomplete="off" oninput="validarNumber(this,/[^0-9.]/g)"></td>
        //             <td class="text-center"><span class="btnEliminarFila text-danger" style="cursor:pointer;"><i class="fa-regular fa-circle-xmark" style="font-size:1.8rem;padding-top:.3rem"></i></span></td>
        //         </tr>`;
        //         tbody.append(fila);
        //     });
        // }
        // Agregar filas
        // if (document.getElementById('addRow')) {
        //     document.getElementById('addRow').addEventListener('click', function() {
        //         $.ajax({
        //             url: "controllers/solicitud_mh.controlador.php",
        //             method: "POST",
        //             dataType: "json",
        //             data: {
        //                 accion: 4,
        //                 id: id_desp,
        //                 filas: 5
        //             },
        //             success: function() {
        //                 poblarTablasProductos();
        //             }
        //         });
        //     });
        // }

        // Eliminar fila
        // $(document).on('click', '.btnEliminarFila', function() {
        //     const id_fila = $(this).closest('tr').data('id');
        //     confirmarEliminar('de esta', 'fila', function(r) {
        //         if (r) {
        //             $.ajax({
        //                 url: "controllers/solicitud_mh.controlador.php",
        //                 method: "POST",
        //                 dataType: "json",
        //                 data: {
        //                     accion: 6,
        //                     id: id_fila
        //                 },
        //                 success: function() {
        //                     poblarTablasProductos();
        //                 }
        //             });
        //         }
        //     });
        // });

        // Guardar solicitud
        if (document.getElementById('btnGuardarDespacho')) {
            document.getElementById('btnGuardarDespacho').addEventListener('click', function(e) {
                e.preventDefault();
                let elementosAValidar = [document.getElementById('cboResponsable')];
                let isValid = true;

                elementosAValidar.forEach(function(elemento) {
                    if (!elemento.checkValidity()) {
                        elemento.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        elemento.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    return;
                }

                // Recolectar datos de las filas
                let filas = [];
                $('#tblMateriales tbody tr, #tblHerramientas tbody tr').each(function() {
                    const cant_sol = $(this).find('.cantidad').val() || 0;
                    const cant_apro = $(this).find('.aprobada').val() || 0;

                    if (cant_sol > 0 || cant_apro > 0) {
                        filas.push({
                            id: $(this).data('id'),
                            cant_sol: cant_sol,
                            cant_apro: cant_apro
                        });
                    }
                });

                let datos = new FormData();
                datos.append('accion', 5);
                datos.append('id', id_desp);
                datos.append('id_responsable', document.getElementById('cboResponsable').value);
                datos.append('filas', JSON.stringify(filas));
                datos.append('isFilas', 'true');

                confirmarAccion(datos, 'solicitud_mh', tabla, '', function(r) {
                    if (r) {
                        cerrarFormulario();
                        tabla.ajax.reload();
                    }
                });
            });
        }

        // Cerrar formulario
        // function cerrarFormulario() {
        //     document.getElementById("div_cot").style.display = "none";
        //     document.getElementById("div_content").style.display = "block";
        //     document.getElementById("div_header").style.display = "block";
        //     window.scrollTo(0, scrollPos);
        // }

        // if (document.getElementById('CerrarDespacho')) {
        //     document.getElementById('CerrarDespacho').addEventListener('click', function() {
        //         cerrarFormulario();
        //     });
        // }

        // if (document.getElementById('btnReturn')) {
        //     document.getElementById('btnReturn').addEventListener('click', function() {
        //         cerrarFormulario();
        //     });
        // }

        // Eventos de tabla principal
        $('#tblDespacho tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion_desp = 2;
            id_desp = row['id'];
            const num_sol = row['num_sol'];
            document.getElementById('nroSolicitud').textContent = num_sol;
            document.getElementById('text_accion').textContent = ' Editar Solicitud de Despacho';

            // Cargar datos
            poblarTablasProductos();

            scrollPos = window.scrollY || document.documentElement.scrollTop;
            document.getElementById("div_content").style.display = "none";
            document.getElementById("div_header").style.display = "none";
            document.getElementById("div_cot").style.display = "block";
        });

        $('#tblDespacho tbody').on('click', '.btnEliminar', function() {
            let row = obtenerFila(this, tabla);
            const id_ = row["id"];
            let src = new FormData();
            src.append('accion', 8);
            src.append('id', id_);
            confirmarEliminar('esta', 'solicitud de despacho', function(r) {
                if (r) {
                    confirmarAccion(src, 'solicitud_mh', tabla, '', null, 0, false);
                }
            }, 'eliminar', '');
        });

        $('#tblDespacho tbody').on('click', '.btnAutorizar', function() {
            let row = obtenerFila(this, tabla);
            id_desp = row['id'];
            document.getElementById('formAutorizar').reset();
            document.getElementById('formAutorizar').classList.remove('was-validated');
            $('#modalAutorizar').modal('show');
        });

        $('#tblDespacho tbody').on('click', '.btnConfirmar', function() {
            let row = obtenerFila(this, tabla);
            id_desp = row['id'];
            document.getElementById('formConfirmarDespacho').reset();
            document.getElementById('formConfirmarDespacho').classList.remove('was-validated');
            $('#modalConfirmarDespacho').modal('show');
        });

        // Autorizar solicitud
        if (document.getElementById('formAutorizar')) {
            document.getElementById('formAutorizar').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    this.classList.add('was-validated');
                    return;
                }

                let datos = new FormData();
                datos.append('accion', 10);
                datos.append('id', id_desp);
                datos.append('id_autorizado', document.getElementById('cboAutorizadoPor').value);

                confirmarAccion(datos, 'solicitud_mh', tabla, '#modalAutorizar', function(r) {
                    if (r) {
                        $('#modalAutorizar').modal('hide');
                    }
                });
            });
        }

        // Confirmar despacho
        if (document.getElementById('formConfirmarDespacho')) {
            document.getElementById('formConfirmarDespacho').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    this.classList.add('was-validated');
                    return;
                }

                let datos = new FormData();
                datos.append('accion', 11);
                datos.append('id', id_desp);
                datos.append('id_despachado', document.getElementById('cboDespachoPor').value);

                confirmarAccion(datos, 'solicitud_mh', tabla, '#modalConfirmarDespacho', function(r) {
                    if (r) {
                        $('#modalConfirmarDespacho').modal('hide');
                    }
                });
            });
        }
    });
</script>