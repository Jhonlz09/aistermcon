<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <!-- Google Font: Source Sans Pro -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback'>
    <!-- Font Awesome Icons -->
    <link rel='stylesheet' href='assets/css/icon/fontawesome.min.css'>
    <!-- Theme style -->
    <link rel='stylesheet' href='assets/css/theme/adminlte3.min.css'>
    <!-- Estilo css -->
    <link href='assets/css/estilos.css' rel='stylesheet' type='text/css' />
    <!-- Jquery CSS -->
    <link rel='stylesheet' href='assets/plugins/jquery-ui/jquery-ui.css'>
    <!-- DataTables -->
    <link href='assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css' rel='stylesheet' type='text/css' />
    <link href='assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css' rel='stylesheet' type='text/css' />
    <link href='assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css' rel='stylesheet' type='text/css' />
    <!-- SweetAlert2 -->
    <link rel='stylesheet' href='assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'>
    <!-- Icono ventana -->
    <link rel='icon' href='assets/img/icon.png'>
    <!-- Select2 -->
    <link rel='stylesheet' href='assets/plugins/select2/css/select2.min.css'>
    <link rel='stylesheet' href='assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'>
    <!-- overlayScrollbars -->
    <link rel='stylesheet' href='assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'>

    <link href="assets/plugins/datatables-select/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />


    <!-- jQuery -->
    <script src='assets/plugins/jquery/jquery.min.js'></script>
    <!-- jquery UI -->
    <script src="assets/plugins/jquery-ui/jquery-ui.js"></script>
    <!-- Bootstrap 4 -->
    <script src='assets/plugins/bootstrap/js/bootstrap.bundle.min.js'></script>
    <!-- AdminLTE App -->
    <script src='assets/js/theme/adminlte.min.js'></script>
    <!-- DataTables  & Plugins -->
    <script src='assets/plugins/datatables/jquery.dataTables.min.js' type='text/javascript'></script>
    <script src='assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js' type='text/javascript'></script>
    <script src='assets/plugins/datatables-responsive/js/dataTables.responsive.min.js' type='text/javascript'></script>
    <script src='assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js' type='text/javascript'></script>
    <!-- SweetAlert -->
    <script src='assets/plugins/sweetalert2/sweetalert2.min.js'></script>
    <script src='assets/js/main.js'></script>
    <!-- Select2 -->
    <script src='assets/plugins/select2/js/select2.full.min.js'></script>
    <!-- overlayScrollbars -->
    <script src='assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'></script>

    <script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/plugins/jszip/jszip.min.js"></script>
    <script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- Tabledit -->
    <script src="assets/plugins/jquery-tabledit/tabledit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"></script>
    <script src="assets/plugins/chart.js/ChartDataLabels.min.js"></script>

</head>
<?php if (isset($_SESSION['s_usuario'])) {
?>
    <script>
        var tabla, tblIn, tblReturn, tblDetalleSalida, tblDetalleEntrada;
        var configuracionTable = {};
        let items = [];
        let id_boleta = 0;
        let accion_salida = 4;
        let scroll = false;
        let sub = false;
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth() + 1;
        let datos_cliente = [];
        let datos_orden = [];
        let datos_anio = [];
        for (let i = 2023; i <= year; i++) {
            datos_anio.push({
                id: i,
                text: String(i)
            });
        }

        const datos_meses = [{
                id: 1,
                text: 'ENERO'
            },
            {
                id: 2,
                text: 'FEBRERO'
            },
            {
                id: 3,
                text: 'MARZO'
            },
            {
                id: 4,
                text: 'ABRIL'
            },
            {
                id: 5,
                text: 'MAYO'
            },
            {
                id: 6,
                text: 'JUNIO'
            },
            {
                id: 7,
                text: 'JULIO'
            },
            {
                id: 8,
                text: 'AGOSTO'
            },
            {
                id: 9,
                text: 'SEPTIEMBRE'
            },
            {
                id: 10,
                text: 'OCTUBRE'
            },
            {
                id: 11,
                text: 'NOVIEMBRE'
            },
            {
                id: 12,
                text: 'DICIEMBRE'
            }
        ];
    </script>

    <body class='hold-transition sidebar-mini layout-fixed'>
        <!-- Preloader -->
        <div class='preloader flex-column justify-content-center align-items-center'>
            <img class='animation__shake' src='assets/img/loading.svg' alt='AdminLTELogo' height='80' width='80'>
        </div>
        <div class='wrapper'>
            <?php
            include 'modules/sidebar.php';
            include 'modules/navbar.php';
            ?>
            <!-- Content Wrapper.-->
            <div class='content-wrapper'>
                <?php include_once 'views/' . $_SESSION['s_usuario']->vista; ?>
            </div>

            <?php
            include 'modules/aside.php';
            ?>
        </div>
        <script>
            let selectedTab = '1';
            const body = document.querySelector('body'),
                navbar = body.querySelector('.navbar'),
                sidebar = body.querySelector('.main-sidebar'),
                modeSwitch = body.querySelector('.mode'),
                modeText = body.querySelector('.mode-text'),
                controlSide = body.getElementsByClassName('control-sidebar')[0],
                control = body.querySelector('.ctrl-side'),
                btnSide = document.getElementById('btnSide'),
                setA = body.querySelectorAll('.setA'),
                inputauto = body.querySelector('#codProducto');

            const card_orden = document.getElementById('card_orden');


            setA.forEach((e) => {
                e.addEventListener('click', function() {
                    // const ul = this.nextElementSibling;
                    // if (!ul) {
                    setA.forEach((a) => a.classList.remove('active'));
                    this.classList.add('active');
                    if (body.classList.contains('control-sidebar-slide-open')) {
                        control.click();
                    }
                });
            });

            function cargarContenido(contenedor, contenido, id = '') {
                let tbl = 'tbl' + id;
                let ruta = id.toLowerCase();

                var tablaData = localStorage.getItem(ruta);
                scroll = false;
                $('.' + contenedor).load(contenido, function() {
                    if (tablaData) {
                        // Restaurar los datos de la tabla desde localStorage
                        tabla = $("#" + tbl).DataTable({
                            ...configuracionTable
                        })

                        tabla.on('draw.dt', function() {
                            const b = document.body;
                            const s = b.scrollHeight;
                            const w = window.innerHeight;
                            handleScroll(b, s, w)

                            let Data = tabla.rows().data().toArray();
                            localStorage.setItem(ruta, JSON.stringify(Data));
                        });

                        $('#' + tbl).DataTable().clear().rows.add(JSON.parse(tablaData)).draw();

                        tabla.settings()[0].ajax = {
                            "url": "controllers/" + ruta + ".controlador.php",
                            "type": "POST",
                            "dataSrc": '',
                        };
                        tabla.ajax.reload(null, false);
                        setTimeout(() => {
                            tabla.columns.adjust().responsive.recalc();
                        }, 150);
                    }
                });
            }

            jQuery.ui.autocomplete.prototype._resizeMenu = function() {
                var ul = this.menu.element;
                ul.outerWidth(this.element.outerWidth());
            }

            control.addEventListener('click', () => {
                body.classList.toggle('overflow-body');

                // if(!(body.classList.contains('control-sidebar-slide-open')))
                // else{
                //     body.classList.remove('overflow-body');
                // }
                if (body.classList.contains('control-sidebar-slide-open')) {
                    return;
                } else if (!(body.classList.contains('sidebar-collapse'))) {
                    body.classList.toggle('sidebar-collapse');
                }
            });

            let savedTheme = localStorage.getItem('darkMode');
            if (savedTheme == 'true') {
                aplicarTema();
            }

            modeSwitch.addEventListener('click', () => {
                let dark = aplicarTema();
                savePreference(dark);
            });

            function aplicarTema(chart = null) {
                const chart_theme =
                    body.classList.toggle('dark-mode');
                navbar.classList.toggle('navbar-dark');
                navbar.classList.toggle('navbar-light');
                sidebar.classList.toggle('sidebar-light-lightblue');
                sidebar.classList.toggle('sidebar-dark-navy');
                controlSide.classList.toggle('control-sidebar-light');
                controlSide.classList.toggle('control-sidebar-dark');

                const isDarkMode = body.classList.contains('dark-mode');
                modeText.innerText = isDarkMode ? 'Modo claro' : 'Modo oscuro';
                return isDarkMode;
            }

            function savePreference(isDarkMode) {
                localStorage.setItem('darkMode', isDarkMode);
            }

            tblReturn = $('#tblReturn').DataTable({
                "dom": 'pt',
                "responsive": true,
                "lengthChange": false,
                "ordering": false,
                "autoWidth": false,
                ajax: {
                    url: "controllers/salidas.controlador.php",
                    dataSrc: "",
                    type: "POST",
                    data: function(d) {
                        d.accion = accion_salida;
                        d.boleta = id_boleta;
                    }
                },
                columnDefs: [{
                        targets: 0,
                        data: null,
                        className: "text-center",
                        render: function(data, type, row, meta) {
                            if (type === 'display') {
                                return meta.row + 1;
                            }
                            return meta.row;
                        }
                    },
                    {
                        targets: 1,
                        className: "text-center",
                    },
                    {
                        targets: 2,
                        className: "text-center",
                    },
                    {
                        targets: 4,
                        className: "text-center ",
                    },
                    {
                        targets: 5,
                        className: "text-center",
                        data: null, // Puedes usar "null" si no estás asociando esta columna con un campo específico en tus datos
                        render: function(data, type, row) {
                            let value = (row.retorno === null) ? '' : row.retorno;
                            return '<input value="' + value + '" type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline retorno" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" >';
                        }
                    },
                ],
            });

            tblDetalleSalida = $('#tblDetalleSalida').DataTable({
                "ajax": {
                    "url": "controllers/salidas.controlador.php",
                    "type": "POST",
                    "datatype": 'json',
                    "data": function(d) {
                        d.accion = 7,
                            d.boleta = id_boleta
                    }
                },
                // "dom": 'pt',
                "lengthChange": true,
                "ordering": false,
                "responsive": true,
                "autoWidth": false,
                "paging": false,
                columnDefs: [{
                        targets: 0,
                        data: 'id',
                        className: "text-center",
                    },
                    {
                        targets: 1,
                        className: "text-center",
                        data: 'codigo'
                    },
                    {
                        targets: 2,
                        className: "text-center",
                        data: 'cantidad_salida'
                    },
                    {
                        targets: 3,
                        className: "text-center",
                        data: 'unidad'
                    },
                    {
                        targets: 4,
                        data: 'descripcion'
                    },
                ],
                processing: true,
                serverSide: true,
                dom: 't',
            });

            tblDetalleEntrada = $('#tblDetalleEntrada').DataTable({
                "ajax": {
                    "url": "controllers/entradas.controlador.php",
                    "type": "POST",
                    "datatype": 'json',
                    "data": function(d) {
                        d.accion = 2,
                            d.boleta = id_boleta
                    }
                },
                // "dom": 'pt',
                "lengthChange": true,
                "ordering": false,
                "responsive": true,
                "autoWidth": false,
                "paging": false,
                columnDefs: [{
                        targets: 0,
                        data: 'id',
                        className: "text-center",
                    },
                    {
                        targets: 1,
                        className: "text-center",
                        data: 'codigo'
                    },
                    {
                        targets: 2,
                        className: "text-center",
                        data: 'cantidad_entrada'
                    },
                    {
                        targets: 3,
                        className: "text-center",
                        data: 'unidad'
                    },
                    {
                        targets: 4,
                        data: 'precio'
                    },
                ],
                processing: true,
                serverSide: true,
                dom: 'pt',
            });

            $('#tblDetalleSalida').on('draw.dt', function() {

                $('#tblDetalleSalida').Tabledit({
                    url: 'controllers/Tabledit/acciones_salidas.php',
                    dataType: 'json',
                    columns: {
                        identifier: [0, 'id'],
                        editable: [
                            [1, 'codigo'],
                            [2, 'cantidad_salida']
                        ]
                    },
                    buttons: {
                        edit: {
                            class: 'btn btn-sm btn-default',
                            html: '<span class="text-nowrap"></span>',
                            html: '<i class="fas fa-pen"></i>',
                            action: 'edit'
                        },
                        delete: {
                            class: 'btn btn-sm btn-default',
                            html: '<i class="fas fa-trash"></i>',
                            action: 'delete'
                        }
                    },
                    onSuccess: function(data, textStatus, jqXHR) {
                        if (data.action == 'delete') {
                            // $('#' + data.id_boleta).remove();
                            tblDetalleSalida.ajax.reload(null, false);
                            tabla.ajax.reload(null, false);
                        }
                        if (data.action == 'edit') {

                            tblDetalleSalida.ajax.reload(null, false);
                            tabla.ajax.reload(null, false);
                        }
                    }
                })

                $('#tblDetalleSalida').find('input[name="cantidad_salida"]').attr({
                    'inputmode': 'numeric',
                    'autocomplete': 'off',
                    'onpaste': 'validarPegado(this, event)',
                    'onkeydown': 'validarTecla(event,this)',
                    'oninput': 'validarNumber(this,/[^0-9.]/g)'
                });
            })

            $('#tblDetalleEntrada').on('draw.dt', function() {

                $('#tblDetalleEntrada').Tabledit({
                    url: 'controllers/Tabledit/acciones_entradas.php',
                    dataType: 'json',
                    columns: {
                        identifier: [0, 'id'],
                        editable: [
                            [1, 'codigo'],
                            [2, 'cantidad_entrada']
                        ]
                    },
                    buttons: {
                        edit: {
                            class: 'btn btn-sm btn-default',
                            html: '<span class="text-nowrap"></span>',
                            html: '<i class="fas fa-pen"></i>',
                            action: 'edit'
                        },
                        delete: {
                            class: 'btn btn-sm btn-default',
                            html: '<i class="fas fa-trash"></i>',
                            action: 'delete'
                        }
                    },
                    onSuccess: function(data, textStatus, jqXHR) {
                        if (data.action == 'delete') {
                            tblDetalleEntrada.ajax.reload(null, false);
                            tabla.ajax.reload(null, false);
                        }
                        if (data.action == 'edit') {
                            tblDetalleEntrada.ajax.reload(null, false);
                            tabla.ajax.reload(null, false);
                        }
                    }
                })

                $('#tblDetalleEntrada').find('input[name="cantidad_entrada"]').attr({
                    'inputmode': 'numeric',
                    'autocomplete': 'off',
                    'onpaste': 'validarPegado(this, event)',
                    'onkeydown': 'validarTecla(event,this)',
                    'oninput': 'validarNumber(this,/[^0-9.]/g)'
                });
            })

            const cboClientesActivos = document.getElementById('cboPorCliente'),
                cboOrdenActivas = document.getElementById('cboPorOrden'),
                fecha = document.getElementById('fecha'),
                fecha_retorno = document.getElementById('fecha_retorno');

            $(document).ready(function() {
                const cboOrden = document.getElementById('cboOrden'),
                    form_guia = document.getElementById('form_guia'),
                    cboProveedor = document.getElementById('cboProveedores'),
                    cboCliente = document.getElementById('cboClientes'),
                    cboEmpleado = document.getElementById('cboEmpleado'),
                    cboConductor = document.getElementById('cboConductor'),
                    btnGuia = document.getElementById('btnGuardarGuia');

                $(cboOrden).select2({
                    placeholder: 'SELECCIONA UNA ORDEN',
                    width: '100%',
                })

                $(cboEmpleado).select2({
                    placeholder: 'SELECCIONA UN EMPLEADO',
                    width: '100%',
                })

                $(cboClientesActivos).select2({
                    placeholder: 'POR CLIENTE',
                    width: '100%',
                })
                $(cboOrdenActivas).select2({
                    placeholder: 'POR ORDEN',
                    width: '100%',
                })

                $(cboProveedor).select2({
                    placeholder: 'SELECCIONA UN PROVEEDOR',
                    width: '100%',
                })

                $(cboCliente).select2({
                    placeholder: 'SELECCIONA UN CLIENTE',
                    width: '100%',
                })

                $(cboConductor).select2({
                    placeholder: 'SELECCIONA UN CONDUCTOR',
                    width: '100%',
                })

                cargarCombo('Proveedores');
                cargarCombo('Clientes', '', 1, true)
                    .then(datos_ => {
                        datos_cliente = datos_;
                        console.log(datos_cliente)
                    });

                cargarCombo('Empleado')
                cargarCombo('Conductor', '', 2);
                cargarCombo('Orden', '', 3, true).then(datos_ => {
                    datos_orden = datos_;
                    console.log(datos_orden)
                });
                cargarCombo('PorCliente', '', 4)
                cargarCombo('PorOrden', '', 5)

                tblIn = $('#tblIn').DataTable({
                    "dom": '<"row"<"col-sm-6"B><"col-sm-6"p>>t',
                    "responsive": true,
                    "lengthChange": false,
                    "ordering": false,
                    "autoWidth": false,
                    columnDefs: [{
                            targets: 0,
                            data: null,
                            className: "text-center",
                            render: function(data, type, row, meta) {
                                if (type === 'display') {
                                    return meta.row + 1;
                                }
                                return meta.row;
                            }
                        },
                        {
                            targets: 1,
                            visible: false
                        },
                        {
                            targets: 2,
                            className: "text-center",
                        },
                        {
                            targets: 3,
                            className: "text-center ",
                        },
                        {
                            targets: 4,
                            className: "text-center text-nowrap",
                        },
                    ],
                    buttons: [{
                        text: "<i class='fa-regular fa-trash-can fa-xl'style='color: #bd0000'></i> Borrar todo",
                        className: "btn btn-light text-danger",
                        action: function(e, dt, node, config) {
                            dt.clear().draw(); // Esta línea vacía los datos de la tabla
                        }
                    }]
                });

                tblOut = $('#tblOut').DataTable({
                    "dom": '<"row"<"col-sm-6"B><"col-sm-6"p>>t',
                    "responsive": true,
                    "lengthChange": false,
                    "ordering": false,
                    "autoWidth": false,
                    columnDefs: [{
                            targets: 0,
                            data: null,
                            className: "text-center",
                            render: function(data, type, row, meta) {
                                if (type === 'display') {
                                    return meta.row + 1;
                                }
                                return meta.row;
                            }
                        },
                        {
                            targets: 1,
                            visible: false
                        },
                        {
                            targets: 2,
                            className: "text-center",
                        },
                        {
                            targets: 3,
                            className: "text-center ",
                        },
                        {
                            targets: 4,
                            className: "text-center",
                        },
                    ],
                    buttons: [{
                        text: "<i class='fa-regular fa-trash-can fa-xl'style='color: #bd0000'></i> Borrar todo",
                        className: "btn btn-light text-danger",
                        action: function(e, dt, node, config) {
                            dt.clear().draw(); // Esta línea vacía los datos de la tabla
                        }
                    }]
                });

                $('#tblIn tbody').on('click', '.btnEliminarIn', function() {
                    tblIn.row($(this).parents('tr')).remove().draw();
                });

                $('#tblOut tbody').on('click', '.btnEliminarIn', function() {
                    tblOut.row($(this).parents('tr')).remove().draw();
                });
                //INICIA AUTOCOMPLETAR
                $.ajax({
                    url: "controllers/inventario.controlador.php",
                    method: "POST",
                    data: {
                        'accion': 4
                    },
                    dataType: 'json',
                    success: function(respuesta) {

                        for (let i = 0; i < respuesta.length; i++) {
                            var formattedItem = {
                                label: respuesta[i]['descripcion'],
                                value: respuesta[i]['descripcion'],
                                id: respuesta[i]['id'],
                                cantidad: respuesta[i]['cantidad']
                            };
                            items.push(formattedItem);
                        }

                        $(inputauto).autocomplete({
                            source: items,
                            select: function(event, ui) {
                                CargarProductos(ui.item.id);
                                return false;
                            }
                        }).data("ui-autocomplete")._renderItem = function(ul, item) {
                            return $("<li>")
                                .append("<div>" + item.label + "<strong class='large-text'>CANTIDAD: " + item.cantidad + "</strong></div>")
                                .appendTo(ul);
                        };
                    }
                });

                let action;
                const tabs = document.querySelectorAll('.tabs input');
                const div_orden = document.getElementById('div_orden');
                const div_proveedor = document.getElementById('div_proveedor');
                const div_return = document.getElementById('div_return');
                const div_retorno = document.getElementById('div_retorno');
                const div_entregado = document.getElementById('div_entregado');
                const div_conductor = document.getElementById('div_conductor');
                const div_productos = document.getElementById('div_productos');

                const formOrden = document.getElementById('formOrden'),
                    id_orden = document.getElementById('id_orden'),
                    nro_orden = document.getElementById('num_orden'),
                    span_text = document.getElementById('nom_action');

                const new_orden = document.getElementById('new_orden'),
                    edit_orden = document.getElementById('edit_orden'),
                    eli_orden = document.getElementById('edit_orden');

                document.addEventListener("keydown", function(event) {
                    // Verifica si se presionó la tecla 'g' y la tecla 'ctrl' (o 'command' en Mac)
                    if (event.ctrlKey && (event.key === "g" || event.key === "G")) {
                        // Evita el comportamiento por defecto del navegador
                        event.preventDefault();

                        // Simula el clic en el botón
                        control.click();
                    }
                });


                new_orden.addEventListener('click', () => {
                    card_orden.style.display = 'block'
                    action = 4
                    nro_orden.focus();
                    formOrden.reset();
                    setChange(cboCliente, 0);
                })

                edit_orden.addEventListener('click', () => {
                    card_orden.style.display = 'block'
                    action = 5
                    nro_orden.focus();
                })

                eli_orden.addEventListener('click', () => {
                    action = 6
                })

                formOrden.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const id_o = id_orden.value,
                        num = nro_orden.value.trim(),
                        cli = cboCliente.value;
                    if (!this.checkValidity()) {
                        this.classList.add('was-validated');
                        return;
                    }
                    let datos = new FormData();
                    datos.append('id', id_o);
                    datos.append('num_orden', num);
                    datos.append('id_cliente', cli);
                    datos.append('accion', action)
                    confirmarAccion(datos, 'producto', null, '', function(r) {
                        if (r) {
                            cargarCombo('Orden', r.res, 3, true).then(datos_ => {
                                datos_orden = datos_;
                                console.log(id_o)
                            });
                        }
                    });
                    card_orden.style.display = 'none'
                    formOrden.reset();
                    setChange(cboCliente, 0);
                });

                form_guia.addEventListener("submit", function(e) {
                    e.preventDefault()
                    btnGuia.click();
                });

                btnGuia.addEventListener('click', () => {
                    let formData = new FormData();
                    if (selectedTab === '1') {
                        let elementosAValidar = [fecha, cboProveedor];
                        let isValid = true;
                        elementosAValidar.forEach(function(elemento) {
                            if (!elemento.checkValidity()) {
                                isValid = false;
                                form_guia.classList.add('was-validated');
                            }
                        });
                        if (!isValid) {
                            return;
                        }
                        let clases = ['cantidad', 'precio'];
                        formData.append('proveedor', cboProveedor.value);
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 1);
                        realizarRegistro(tblIn, formData, clases);
                    } else if (selectedTab === '2') {
                        let elementosAValidar = [fecha, cboEmpleado, cboConductor];
                        let isValid = true;

                        elementosAValidar.forEach(function(elemento) {
                            if (!elemento.checkValidity()) {
                                isValid = false;
                                form_guia.classList.add('was-validated');
                            }
                        });

                        if (!isValid) {
                            return;
                        }
                        let clases = ['cantidad'];
                        formData.append('orden', cboOrden.value);
                        formData.append('conductor', cboConductor.value);
                        formData.append('entrega', cboEmpleado.value);
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 2);
                        realizarRegistro(tblOut, formData, clases);
                    } else if (selectedTab === '3') {
                        let clases = ['retorno'];
                        formData.append('boleta', id_boleta);
                        formData.append('fecha_retorno', fecha_retorno.value);
                        formData.append('accion', 3);
                        realizarRegistro(tblReturn, formData, clases, 0);
                    }
                })

                tabs.forEach(tab => {
                    tab.addEventListener('change', function() {
                        selectedTab = this.value;
                        form_guia.classList.remove('was-validated');
                        const selectedForm = document.getElementById(`form-${selectedTab}`);
                        const formContainers = document.querySelectorAll('.form-container');
                        console.log(selectedForm)
                        formContainers.forEach(container => {
                            container.style.display = 'none';
                        });

                        if (selectedForm) {
                            selectedForm.style.display = 'block';
                        }

                        if (selectedTab === '1' || selectedTab === '5') {
                            div_orden.style.display = 'none';
                            div_productos.style.display = 'block';
                            div_proveedor.style.display = 'block';
                            div_conductor.style.display = 'none';
                            div_retorno.style.display = 'none';
                            div_entregado.style.display = 'none';
                            card_orden.style.display = 'none';
                            div_return.style.display = 'none'
                        } else if (selectedTab === '2' || selectedTab === '4') {
                            div_orden.style.display = 'block';
                            div_proveedor.style.display = 'none';
                            div_productos.style.display = 'block';
                            card_orden.style.display = 'none';
                            div_return.style.display = 'none';
                            div_conductor.style.display = 'block';
                            div_retorno.style.display = 'none';
                            div_entregado.style.display = 'block';
                        } else if (selectedTab === '3') {
                            div_orden.style.display = 'none';
                            div_proveedor.style.display = 'none';
                            div_productos.style.display = 'none';
                            div_return.style.display = 'block'
                            card_orden.style.display = 'none';
                            div_conductor.style.display = 'none';
                            div_retorno.style.display = 'block';
                            div_entregado.style.display = 'none';
                        }
                    });
                });

                function CargarProductos(p = "") {

                    if (p != "") {
                        var id_producto = p;
                    } else {
                        var id_producto = $(inputauto).val();
                    }

                    var existingRow = tblIn.row("#producto_" + id_producto);
                    console.log(existingRow)

                    if (selectedTab === '4') {
                        $.ajax({
                            url: "controllers/salidas.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 1, //BUSCAR PRODUCTOS POR id_producto
                                'id_producto': id_producto,
                                'id_boleta': id_boleta
                            },
                            dataType: 'json',
                            success: function(respuesta) {
                                tblDetalleSalida.ajax.reload(null, false)
                                tabla.ajax.reload(null, false);
                                inputauto.value = '';
                            }
                        });
                    } else {
                        if (existingRow.any()) {
                            // Si el id_producto ya está en el DataTable, actualizar la cantidad
                            var rowData = existingRow.data();
                            var cantidadInput = existingRow.node().querySelector('.cantidad');
                            var cantidad = parseFloat(cantidadInput.value) + 1; // Incrementar la cantidad
                            cantidadInput.value = cantidad; // Actualizar el valor en el input
                            $(inputauto).val("");

                            // Puedes hacer otras actualizaciones aquí si es necesario

                            // También puedes enviar estos cambios al servidor si es necesario
                            // ...
                            return;
                        }
                        $.ajax({
                            url: "controllers/inventario.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 5, //BUSCAR PRODUCTOS POR id_producto
                                'id': id_producto
                            },
                            dataType: 'json',
                            success: function(respuesta) {
                                if (respuesta) {
                                    if (selectedTab === '1') {
                                        tblIn.row.add([
                                            '',
                                            respuesta['id'],
                                            '<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="' + respuesta['cantidad'] + '">',
                                            respuesta['nombre'],
                                            '$<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline precio" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="' + respuesta['precio'] + '">',
                                            respuesta['descripcion'],
                                            "<center>" +
                                            "<span class='btnEliminarIn text-danger'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar producto'> " +
                                            "<i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> </i> " +
                                            "</span>" +
                                            "</center>",
                                        ]).node().id = "producto_" + respuesta['id'];
                                        tblIn.draw(false);
                                    } else if (selectedTab === '2') {
                                        tblOut.row.add([
                                            ' ',
                                            respuesta['id'],
                                            '<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="' + respuesta['cantidad'] + '">',
                                            respuesta['nombre'],
                                            respuesta['descripcion'],
                                            "<center>" +
                                            "<span class='btnEliminarIn text-danger'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar producto'> " +
                                            "<i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> </i> " +
                                            "</span>" +
                                            "</center>",
                                        ]).node().id = "producto_" + respuesta['id'];
                                        tblOut.draw(false);
                                    }
                                    $(inputauto).val("");

                                    /*===================================================================*/
                                    //SI LA RESPUESTA ES FALSO, NO TRAE ALGUN DATO
                                    /*===================================================================*/
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: ' El producto no existe o no tiene stock'
                                    });
                                    $(inputauto).val("");
                                    $(inputauto).focus();
                                }
                            }
                        });
                    }
                }

                function realizarRegistro(table, formData, clases, producto = 1) {
                    let count = 0;
                    table.rows().eq(0).each(function(index) {
                        count = count + 1;
                    });

                    if (count > 0) {
                        var arr = [];
                        table.rows().eq(0).each(function(index) {
                            let row = table.row(index);
                            let data = row.data();
                            let id = data[producto];
                            let valores = clases.map(clase => row.node().querySelector('input.' + clase).value);
                            // Agrega los valores al array
                            arr.push(id + "," + valores.join(","));
                            // Agrega al FormData directamente en este ciclo si es necesario
                            formData.append('arr[]', arr[index]);
                        });

                        $.ajax({
                            url: "controllers/registro.controlador.php",
                            method: "POST",
                            data: formData,
                            cache: false,
                            dataType: "json",
                            contentType: false,
                            processData: false,
                            success: function(r) {
                                mostrarToast(r.status, "Completado", "fa-solid fa-check fa-lg", r.m);
                                table.clear().draw();
                                tabla ? tabla.ajax.reload(null, false) : ''
                            }
                        });

                    } else {

                        mostrarToast('danger', "Error", "fa-solid fa-xmark fa-lg", 'No hay productos en el listado');

                    }

                    $("#iptCodigoVenta").focus();

                }
            });
        </script>
    </body>
<?php } else { ?>

    <body class='hold-transition login-page'>
        <?php include 'views/login.php'; ?>
    </body>
<?php } ?>

</html>