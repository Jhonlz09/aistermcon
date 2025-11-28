<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Perfil</title>
    <style>
        /* Contenedor principal con scroll suave */
        .permisos-wrapper {
            max-height: 60vh;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        /* Fila Base (Item) */
        .permiso-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 1px solid #eaecf4;
            transition: background-color 0.2s;
        }

        .permiso-item:last-child {
            border-bottom: none;
        }

        .permiso-item:hover {
            background-color: #f8f9fc;
        }

        /* --- NIVELES DE JERARQUÍA --- */

        /* Nivel 0: Padre (Estilo Encabezado) */
        .item-nivel-0 {
            background-color: #ffffff;
            margin-top: 10px;
            /* Separación entre bloques */
            border-top: 1px solid #eaecf4;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .item-nivel-0 .module-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.05rem;
        }

        /* Barra lateral de acento para el padre */
        .item-nivel-0 {
            border-left: 5px solid #003163;
            /* Azul AdminLTE */
        }

        /* Nivel 1: Hijo */
        .item-nivel-1 {
            background-color: #fff;
            padding-left: 40px !important;
            /* Indentación limpia */
        }

        .item-nivel-1 .module-name {
            font-weight: 500;
            color: #001f3fb2;
            font-size: 0.95rem;
        }

        /* Borde sutil a la izquierda en lugar de línea conectora */
        .item-nivel-1 {
            border-left: 5px solid #d8e3ef;
        }

        /* Nivel 2: Nieto */
        .item-nivel-2 {
            background-color: #fff;
            padding-left: 70px !important;
        }

        .item-nivel-2 .module-name {
            font-weight: 400;
            color: #858796;
            font-size: 0.9rem;
        }

        .item-nivel-2 {
            border-left: 5px solid #f8f9fc;
        }

        /* --- COLUMNAS FLEXBOX --- */
        .col-nombre {
            width: 40%;
            display: flex;
            align-items: center;
        }

        .col-controles {
            width: 60%;
            display: flex;
            justify-content: space-around;
        }

        .control-box {
            width: 25%;
            display: flex;
            justify-content: center;
        }

        /* Switches (Reutilizando tu SVG pero alineado) */
        .switch-2 {
            margin: 0;
            display: flex;
            cursor: pointer;
        }
    </style>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Perfil</h1>
            </div>
            <?php if (isset($_SESSION["crear38"]) && $_SESSION["crear38"] === true) : ?>
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
                                    <h3 class="card-title ">Listado de perfiles</h3>
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
                        <table id="tblRoles" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>DESCRIPCIÓN</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Perfil</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="id_rol" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="label"><i class="fa-solid fa-signature"></i> Descripción</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
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

<div class="modal fade" id="modalR">
    <div class="modal-dialog modal-xl modal-rol modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-light">
                <h4 class="modal-title"><i class="fa-solid fa-user-check"></i><span> Permisos</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form id="formPermisos" autocomplete="off">
                    <input type="hidden" id="id_rol_permiso" value="">
                    <div style="background-color:#001f3fcf;" class="d-flex justify-content-between align-items-center text-white p-3 rounded-top">
                        <div style="width: 40%;">MÓDULO</div>
                        <div class="d-flex justify-content-around" style="width: 60%;">
                            <div class="text-center" style="width: 25%"><i class="fas fa-eye"></i> Ver</div>
                            <div class="text-center" style="width: 25%"><i class="fas fa-plus"></i> Crear</div>
                            <div class="text-center" style="width: 25%"><i class="fas fa-pen"></i> Editar</div>
                            <div class="text-center" style="width: 25%"><i class="fas fa-trash"></i> Borrar</div>
                        </div>
                    </div>

                    <div id="permisosContainer" class="permisos-wrapper bg-light">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="btnGuardarRol" class="btn bg-gradient-light"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar38"] || $_SESSION["eliminar38"] ?>';
    var editar = '<?php echo $_SESSION["editar38"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar38"] ?>';
    // Configuración de DataTable
    var configuracionTable = {
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
                    return type === 'display' ? meta.row + 1 : meta.row;
                }
            },
            {
                targets: 2,
                data: "acciones",
                visible: mostrarCol,
                render: function(data, type, row, full, meta) {
                    return `
                        <center style='white-space: nowrap;'>
                            ${editar ? `<button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal' title='Editar'><i class='fa-solid fa-pencil'></i></button>
                            <button type='button' class='btn bg-gradient-gray-dark btnPermiso' data-target='#modalR' data-toggle='modal' title='Permisos'><i class='fas fa-user-unlock'></i></button>` : ""}
                            ${eliminar ? `<button type='button' class='btn bg-gradient-danger btnEliminar' title='Eliminar'><i class='fa fa-trash'></i></button>` : ""}
                        </center>`;
                },
            },
        ],
    };

    $(document).ready(function() {
        cargarModulosDelSistema();
        if (!$.fn.DataTable.isDataTable('#tblRoles')) {
            tabla = $("#tblRoles").DataTable({
                "ajax": {
                    "url": "controllers/roles.controlador.php",
                    "type": "POST",
                    "dataSrc": ''
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                const b = document.body;
                handleScroll(b, b.scrollHeight, window.innerHeight);
            });
        }

        // --- LÓGICA DE MODALES ---
        let accion = 0;
        // Referencias a elementos
        const modalR = $('#modalR'); 
        const id_rol_permiso = $('#id_rol_permiso');

        $('#btnNuevo').on('click', function() {
            accion = 1;
            $('#formNuevo')[0].reset();
            $('#formNuevo').removeClass('was-validated');
            $('.modal-title span').text(' Nuevo Perfil');
            $('.modal-header').removeClass('bg-gradient-blue').addClass('bg-gradient-green');
        });

        // Click en Editar
        $('#tblRoles tbody').on('click', '.btnEditar', function() {
            let row = tabla.row($(this).parents('tr')).data();
            accion = 2;
            $('#id_rol').val(row.id);
            $('#nombre').val(row.nombre);
            $('.modal-title span').text(' Editar Perfil');
            $('.modal-header').removeClass('bg-gradient-green').addClass('bg-gradient-blue');
        });

        $('#tblRoles tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'perfil', function(r) {
                if (r) {
                    confirmarAccion(src, 'roles', tabla)
                }
            });
        });

        // --- GESTIÓN DE PERMISOS (CORE) ---
        
        // 1. Abrir Modal Permisos
        $('#tblRoles tbody').on('click', '.btnPermiso', function() {
            let row = tabla.row($(this).parents('tr')).data();
            let idPerfil = row.id;
            id_rol_permiso.val(idPerfil);

            // Resetear UI
            $('.switch__input').prop('checked', false);
            $('.check-crear, .check-editar, .check-eliminar').prop('disabled', true);
            $('.check-ver').prop('disabled', false);

            // Traer datos
            $.ajax({
                url: "controllers/roles.controlador.php",
                method: "POST",
                data: { 'id_perfil': idPerfil, 'accion': 4 },
                dataType: "json",
                success: function(permisos) {
                    marcarPermisosEnTabla(permisos);
                }
            });
        });

        // 2. EVENTO DELEGADO PARA TOGGLES (NUEVA LÓGICA DE PROPAGACIÓN)
        // Esto reemplaza al 'onchange' inline y maneja todos los casos
        $('#permisosContainer').on('change', '.switch__input', function() {
            handlePermissionChange($(this));
        });

        // 3. Guardar Permisos (AJUSTADO PARA DIVS)
        $('#btnGuardarRol').on('click', function() {
            let datosPermisos = [];
            let idPerfil = id_rol_permiso.val();

            // Iteramos sobre los DIVS .permiso-item
            $('#permisosContainer .permiso-item').each(function() {
                let item = $(this);
                let idModulo = item.data('idmodulo');

                let ver = item.find('.check-ver').is(':checked');
                let crear = item.find('.check-crear').is(':checked');
                let editar = item.find('.check-editar').is(':checked');
                let eliminar = item.find('.check-eliminar').is(':checked');

                if (ver) {
                    datosPermisos.push({
                        id_modulo: idModulo,
                        ver: ver,
                        crear: crear,
                        editar: editar,
                        eliminar: eliminar
                    });
                }
            });

            $.ajax({
                url: "controllers/roles.controlador.php",
                method: "POST",
                data: {
                    'id_perfil': idPerfil,
                    'datos': JSON.stringify(datosPermisos),
                    'accion': 6
                },
                dataType: "json",
                success: function(r) {
                    modalR.modal("hide");
                    mostrarToast(r.status, "Completado", "fa-check", r.m);
                }
            });
        });

        // Guardar Nuevo/Editar Rol
        $('#formNuevo').on("submit", function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            let datos = new FormData();
            datos.append('id', $('#id_rol').val());
            datos.append('nombre', $('#nombre').val().trim().toUpperCase());
            datos.append('accion', accion);
            confirmarAccion(datos, 'roles', tabla, document.getElementById('modal'));
        });
    });

    // --- FUNCIONES AUXILIARES ---

    function cargarModulosDelSistema() {
        $.ajax({
            url: "controllers/roles.controlador.php",
            method: "POST",
            data: { accion: 7 },
            dataType: "json",
            success: function(data) {
                let modulosOrdenados = organizarModulosJerarquia(data);
                renderizarTablaPermisos(modulosOrdenados);
            }
        });
    }

    function organizarModulosJerarquia(modulos) {
        let mapa = {};
        let arbol = [];
        let listaFinal = [];
        modulos.forEach(m => { mapa[m.id] = { ...m, children: [] }; });
        modulos.forEach(m => {
            if (m.id_padre !== null && m.id_padre !== "0" && mapa[m.id_padre]) {
                mapa[m.id_padre].children.push(mapa[m.id]);
            } else {
                arbol.push(mapa[m.id]);
            }
        });
        function aplanar(nodos, nivel) {
            nodos.forEach(nodo => {
                nodo.nivel = nivel;
                listaFinal.push(nodo);
                if (nodo.children.length > 0) aplanar(nodo.children, nivel + 1);
            });
        }
        aplanar(arbol, 0);
        return listaFinal;
    }

    // RENDERIZADO (Sin onchange inline)
    function renderizarTablaPermisos(modulos) {
        let html = '';
        modulos.forEach(modulo => {
            let nivel = modulo.nivel;
            let iconClass = nivel === 0 ? 'fa-lg text-navy mr-2' : (nivel === 1 ? 'fa-sm text-dark-blue mr-2' : 'fa-xs text-muted mr-2');

            html += `
            <div class="permiso-item item-nivel-${nivel}" data-idmodulo="${modulo.id}" data-padre="${modulo.id_padre}" data-nivel="${nivel}">
                <div class="col-nombre">
                    <i class="fas tab-icon ${modulo.icon} ${iconClass}"></i>
                    <span class="module-name">${modulo.modulo}</span>
                </div>
                <div class="col-controles">
                    <div class="control-box">
                        <label class="switch-2">
                            <input class="switch__input check-ver" type="checkbox">
                            <svg class="switch__check" viewBox="0 0 16 16" width="22px" height="22px"><polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" /></svg>
                        </label>
                    </div>
                    <div class="control-box">
                        <label class="switch-2">
                            <input class="switch__input check-crear" type="checkbox" disabled>
                            <svg class="switch__check" viewBox="0 0 16 16" width="22px" height="22px"><polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" /></svg>
                        </label>
                    </div>
                    <div class="control-box">
                        <label class="switch-2">
                            <input class="switch__input check-editar" type="checkbox" disabled>
                            <svg class="switch__check" viewBox="0 0 16 16" width="22px" height="22px"><polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" /></svg>
                        </label>
                    </div>
                    <div class="control-box">
                        <label class="switch-2">
                            <input class="switch__input check-eliminar" type="checkbox" disabled>
                            <svg class="switch__check" viewBox="0 0 16 16" width="22px" height="22px"><polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" /></svg>
                        </label>
                    </div>
                </div>
            </div>`;
        });
        $('#permisosContainer').html(html);
    }

    // MARCAR PERMISOS DESDE BD
    function marcarPermisosEnTabla(permisosUsuario) {
        permisosUsuario.forEach(p => {
            let row = $(`#permisosContainer .permiso-item[data-idmodulo="${p.id_modulo}"]`);
            if (row.length > 0) {
                let chkVer = row.find('.check-ver');
                chkVer.prop('checked', true);
                
                // Activamos inputs de la fila
                handlePermissionChange(chkVer, false); // false = No propagar en carga inicial para optimizar

                if (p.crear == 1 || p.crear === true) row.find('.check-crear').prop('checked', true);
                if (p.editar == 1 || p.editar === true) row.find('.check-editar').prop('checked', true);
                if (p.eliminar == 1 || p.eliminar === true) row.find('.check-eliminar').prop('checked', true);
            }
        });
    }

    // --- NUEVA FUNCIÓN MAESTRA DE PROPAGACIÓN ---
    function handlePermissionChange(checkbox, propagate = true) {
        let row = checkbox.closest('.permiso-item');
        let isChecked = checkbox.is(':checked');
        let currentNivel = parseInt(row.data('nivel'));
        
        // Determinar qué tipo de check se tocó
        let typeClass = '';
        if (checkbox.hasClass('check-ver')) typeClass = '.check-ver';
        else if (checkbox.hasClass('check-crear')) typeClass = '.check-crear';
        else if (checkbox.hasClass('check-editar')) typeClass = '.check-editar';
        else if (checkbox.hasClass('check-eliminar')) typeClass = '.check-eliminar';

        // 1. Gestión de la PROPIA fila
        // Si tocamos "Ver", habilitamos/deshabilitamos los inputs vecinos
        if (typeClass === '.check-ver') {
            let siblingInputs = row.find('input:not(.check-ver)');
            if (isChecked) {
                siblingInputs.prop('disabled', false);
            } else {
                siblingInputs.prop('checked', false).prop('disabled', true);
            }
        }

        // Si no queremos propagar (ej: carga inicial), salimos
        if (!propagate) return;

        // 2. Gestión de DESCENDIENTES (Cascada)
        let nextRows = row.nextAll('.permiso-item');

        nextRows.each(function() {
            let nextRow = $(this);
            let nextNivel = parseInt(nextRow.data('nivel'));

            // Si el nivel es menor o igual, ya no es hijo. Paramos.
            if (nextNivel <= currentNivel) return false;

            // --- APLICAR LÓGICA AL HIJO ---
            
            // Caso A: El padre cambió "Ver"
            if (typeClass === '.check-ver') {
                let childVer = nextRow.find('.check-ver');
                childVer.prop('checked', isChecked);
                
                let childInputs = nextRow.find('input:not(.check-ver)');
                if (isChecked) {
                    childInputs.prop('disabled', false);
                } else {
                    childInputs.prop('checked', false).prop('disabled', true);
                }
            } 
            // Caso B: El padre cambió Crear/Editar/Eliminar
            else {
                // Solo propagamos si el hijo está habilitado (tiene Ver activo)
                if (nextRow.find('.check-ver').is(':checked')) {
                    nextRow.find(typeClass).prop('checked', isChecked);
                }
            }
        });
    }
</script>