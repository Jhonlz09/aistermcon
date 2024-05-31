<!DOCTYPE html>
<html lang='es' style="background-color:#f4f6f9">

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <!-- Google Font: Source Sans Pro -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback'>
    <!-- Font Awesome Icons -->
    <link rel='stylesheet' href='assets/css/icon/icon.min.css'>
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
    <script src='assets/js/main.js'></script>

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
    <script defer src='assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js' type='text/javascript'></script>
    <script defer src='assets/plugins/datatables-responsive/js/dataTables.responsive.min.js' type='text/javascript'></script>
    <script defer src='assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js' type='text/javascript'></script>
    <!-- SweetAlert -->
    <script defer src='assets/plugins/sweetalert2/sweetalert2.min.js'></script>
    <!-- Select2 -->
    <script defer src='assets/plugins/select2/js/select2.full.min.js'></script>
    <!-- overlayScrollbars -->
    <script src='assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'></script>

    <script defer src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script defer src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script defer src="assets/plugins/jszip/jszip.min.js"></script>
    <script defer src="assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script defer src="assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script defer src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script defer src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script defer src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- Tabledit -->
    <script defer src="assets/plugins/jquery-tabledit/tabledit.min.js"></script>
    <script defer src="assets/plugins/chart.js/Chart.min.js"></script>
    <script defer src="assets/plugins/chart.js/ChartDataLabels.min.js"></script>

</head>
<?php if (isset($_SESSION['s_usuario'])) {
?>
    <script>
        var tabla, tblIn, tblReturn, tblDetalleSalida, tblDetalleEntrada, tblFab;
        var configuracionTable = {};
        let items = [];
        let id_boleta = 0;
        let accion_salida = 4;
        let accion_inv = 0;
        let scroll = false;
        let sub = false;
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth() + 1;
        let datos_cliente = [];
        let datos_orden = [];
        let datos_anio = [];
        let selectedTab = '2';
        // let datos_und = [];
        for (let i = 2024; i <= year; i++) {
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

    <body class='hold-transition sidebar-mini layout-fixed sidebar-mini-xs layout-navbar-fixed' style="background-color: #f4f6f9;">
        <!-- Preloader -->
        <div class='preloader flex-column justify-content-center align-items-center'>
            <img class='animation__shake' src='assets/img/loading.svg' alt='AdminLTELogo' height='80' width='80'>
        </div>
        <audio id="scanner" src="assets/sounds/scanner-beep.mp3"></audio>
        <div class='wrapper'>
            <?php
            include 'modules/sidebar.php';
            include 'modules/navbar.php';
            ?>
            <!-- Content Wrapper.-->
            <div class='content-wrapper'>
                <?php
                include_once 'views/' . $_SESSION['s_usuario']->vista; ?>
            </div>

            <?php
            include 'modules/aside.php';
            include 'modules/slide.php';
            ?>
        </div>
        <script>
            const body = document.querySelector('body'),
                html = document.querySelector('html'),
                navbar = body.querySelector('.navbar'),
                sidebar = body.querySelector('.main-sidebar'),
                // modeSwitch = body.querySelector('.mode'),
                // modeText = body.querySelector('.mode-text'),
                controlSide = body.getElementsByClassName('control-sidebar')[0],
                control = body.querySelectorAll('.ctrl-side'),
                first_control = body.querySelector('#first_control'),
                second_control = document.querySelector('#second_control'),
                btnSide = document.getElementById('btnSide'),
                setA = body.querySelectorAll('.setA'),
                setB = body.querySelectorAll('.setB'),

                inputauto = body.querySelector('#codProducto'),
                inputBarras = body.querySelector('#codBarras');

            const card_fab = document.getElementById('card_fab');

            const isEntrada = <?php echo ($_SESSION["entrada_mul"]) ? 1 : 0; ?>;

            const isSuperAdmin = <?php echo ($_SESSION["s_usuario"]->id_perfil == 1) ? 1 : 0; ?>;


            // console.log('este es mi id_pergul ' + id_perfil)
            btnSide.addEventListener("click", () => {
                    setTimeout(() => {
                        tabla.columns.adjust().responsive.recalc();
                    }, 200);
            });

            setA.forEach((e) => {
                e.addEventListener('click', function() {
                    // const ul = this.nextElementSibling;
                    // if (!ul) {
                    // Verificar si el elemento clickeado es desplegable
                    const isDropdown = this.nextElementSibling && this.nextElementSibling.classList.contains('nav-treeview');

                    // Si es desplegable, no hacer nada
                    if (isDropdown) {
                        return;
                    }

                    // Remover la clase 'active' de todos los elementos setA
                    setA.forEach((a) => a.classList.remove('active'));
                    // Agregar la clase 'active' al elemento clickeado
                    this.classList.add('active');

                    // Si no es desplegable, remover la clase 'active' de todos los elementos setB
                    if (!isDropdown) {
                        const setB = document.querySelectorAll('.setB');
                        setB.forEach((b) => b.classList.remove('active'));
                    }

                    if (body.classList.contains('control-sidebar-slide-open')) {
                        // second_control.click();
                        // body.classList.remove('control-sidebar-slide-open');
                        // body.classList.remove('overflow-body');
                        // html.classList.remove('overflow-body');
                        control.forEach((e) => {
                            // e.style.display = 'none'
                            if (e.classList.contains('active')) {
                                e.click();
                            }
                        })


                    }
                });
            });


            setB.forEach((e) => {
                e.addEventListener('click', function() {
                    // Remover la clase 'active' de todos los elementos setB
                    setB.forEach((b) => b.classList.remove('active'));
                    // Agregar la clase 'active' al elemento clickeado
                    this.classList.add('active');

                    const parentSetA = this.closest('.menu-open').querySelector('.setA');
                    // Remover la clase 'active' de todos los elementos setA que no contienen al setB clickeado
                    document.querySelectorAll('.setA').forEach((setA) => {
                        if (setA !== parentSetA) {
                            setA.classList.remove('active');
                        }
                    });

                    parentSetA.classList.add('active');

                    if (body.classList.contains('control-sidebar-slide-open')) {
                        control.forEach((e) => {
                            // e.style.display = 'none'
                            if (e.classList.contains('active')) {
                                e.click();
                            }
                        })


                    }

                });
            });

            function cargarContenido(contenedor, contenido, id = '') {
                let tbl = 'tbl' + id;
                let ruta = id.toLowerCase();
                setTimeout(() => {
                    tabla.columns.adjust().responsive.recalc();
                }, 150);
                var tablaData = localStorage.getItem(ruta);
                accion_inv = 0;
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

                    }
                });
            }

            jQuery.ui.autocomplete.prototype._resizeMenu = function() {
                var ul = this.menu.element;
                ul.outerWidth(this.element.outerWidth());
            }
            let isLocked = false;
            let activeControl = null;
            control.forEach((e) => {
                e.addEventListener('click', () => {

                    const clickedControlIsActive = e === activeControl;

                    // Desactivar el control-sidebar activo anteriormente, si existe y no es el mismo que el clic actual
                    if (activeControl && !clickedControlIsActive) {
                        activeControl.classList.remove('active');
                    }

                    // Si el clic actual no es el mismo que el control-sidebar activo, activarlo
                    if (!clickedControlIsActive) {
                        e.classList.add('active');
                        activeControl = e;
                    } else { // Si se hace clic en el mismo control-sidebar, alternar su estado
                        e.classList.toggle('active');
                        activeControl = e.classList.contains('active') ? e : null;
                    }

                    const activeControls = document.querySelectorAll('.ctrl-side.active').length;
                    const isOverflow = activeControls > 0;

                    body.classList.toggle('overflow-body', isOverflow);
                    html.classList.toggle('overflow-body', isOverflow);


                    if (body.classList.contains('control-sidebar-slide-open')) {
                        return;
                    } else if (!(body.classList.contains('sidebar-collapse'))) {
                        body.classList.toggle('sidebar-collapse');
                    }
                })
            });




            // let savedTheme = localStorage.getItem('darkMode');
            // if (savedTheme == 'true') {
            //     aplicarTema();
            // }

            // modeSwitch.addEventListener('click', () => {
            //     let dark = aplicarTema();
            //     savePreference(dark);
            // });

            // function aplicarTema(chart = null) {
            //     const chart_theme =
            //         // body.classList.toggle('dark-mode');
            //         navbar.classList.toggle('navbar-dark');
            //     navbar.classList.toggle('navbar-light');
            //     sidebar.classList.toggle('sidebar-light-lightblue');
            //     sidebar.classList.toggle('sidebar-dark-navy');
            //     controlSide.classList.toggle('control-sidebar-light');
            //     controlSide.classList.toggle('control-sidebar-dark');

            //     const isDarkMode = body.classList.contains('dark-mode');
            //     modeText.innerText = isDarkMode ? 'Modo claro' : 'Modo oscuro';
            //     return isDarkMode;
            // }

            // function savePreference(isDarkMode) {
            //     localStorage.setItem('darkMode', isDarkMode);
            // }

            tblReturn = $('#tblReturn').DataTable({
                "dom": 'pt',
                "responsive": true,
                "lengthChange": false,
                "ordering": false,
                "autoWidth": false,
                "paging": false,
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
                            // Definir el valor del input
                            let value = (row.retorno === null) ? '' : row.retorno;

                            // Definir el HTML del input
                            let inputHTML = '<input value="' + value + '" type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline retorno" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" >';

                            // Condicional para id_perfil_sistema
                            if (isEntrada || isSuperAdmin) {
                                return inputHTML;
                            } else {
                                return row.isentrada ? row.retorno : inputHTML;
                            }
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
                            d.factura = id_boleta
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
                console.log("ID BOLETA EN EL DRA ES: " + id_boleta)
                if (id_boleta != 0) {
                    $('#tblDetalleSalida').Tabledit({
                        url: 'controllers/Tabledit/acciones_salidas.php',
                        dataType: 'json',
                        columns: {
                            identifier: [0, 'id'],
                            editable: [
                                [1, 'codigo'],
                                [2, 'cantidad_salida'],
                            ]
                        },
                        hideIdentifier: true,
                        buttons: {
                            edit: {
                                class: 'btn btn-sm btn-default',
                                html: '<span class="text-nowrap"></span>',
                                html: '<i class="fas fa-pen"></i>',
                                action: 'edit,' + id_boleta
                            },
                            delete: {
                                class: 'btn btn-sm btn-default',
                                html: '<i class="fas fa-trash"></i>',
                                action: 'delete,' + id_boleta
                            }
                        },
                        onSuccess: function(data, textStatus, jqXHR) {
                            let isSuccess = data.status === 'success';
                            mostrarToast(
                                data.status,
                                isSuccess ? "Completado" : "Error",
                                isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
                                data.m
                            );
                            // $('#' + data.id_boleta).remove();
                            tblDetalleSalida.ajax.reload(null, false);
                            tabla.ajax.reload(null, false);
                            cargarAutocompletado();
                        }
                    })

                    $('#tblDetalleSalida').find('input[name="cantidad_salida"]').attr({
                        'inputmode': 'numeric',
                        'autocomplete': 'off',
                        'onpaste': 'validarPegado(this, event)',
                        'onkeydown': 'validarTecla(event,this)',
                        'oninput': 'validarNumber(this,/[^0-9.]/g)'
                    });
                }


            })

            $('#tblDetalleEntrada').on('draw.dt', function() {
                if (id_boleta != 0) {
                    $('#tblDetalleEntrada').Tabledit({
                        url: 'controllers/Tabledit/acciones_entradas.php',
                        dataType: 'json',
                        columns: {
                            identifier: [0, 'id'],
                            editable: [
                                [1, 'codigo'],
                                [2, 'cantidad_entrada'],
                                [4, 'precio']
                            ]
                        },
                        hideIdentifier: true,
                        buttons: {
                            edit: {
                                class: 'btn btn-sm btn-default',
                                html: '<span class="text-nowrap"></span>',
                                html: '<i class="fas fa-pen"></i>',
                                action: 'edit,' + id_boleta
                            },
                            delete: {
                                class: 'btn btn-sm btn-default',
                                html: '<i class="fas fa-trash"></i>',
                                action: 'delete,' + id_boleta
                            }
                        },
                        onSuccess: function(data, textStatus, jqXHR) {
                            let isSuccess = data.status === 'success';
                            mostrarToast(
                                data.status,
                                isSuccess ? "Completado" : "Error",
                                isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
                                data.m
                            );
                            // $('#' + data.id_boleta).remove();
                            tblDetalleEntrada.ajax.reload(null, false);
                            tabla.ajax.reload(null, false);
                            cargarAutocompletado();
                        }
                    })

                    $('#tblDetalleEntrada').find('input[name="cantidad_entrada"]').attr({
                        'inputmode': 'numeric',
                        'autocomplete': 'off',
                        'onpaste': 'validarPegado(this, event)',
                        'onkeydown': 'validarTecla(event,this)',
                        'oninput': 'validarNumber(this,/[^0-9.]/g)'
                    });

                    $('#tblDetalleEntrada').find('input[name="precio"]').attr({
                        'inputmode': 'numeric',
                        'autocomplete': 'off',
                        'onpaste': 'validarPegado(this, event)',
                        'onkeydown': 'validarTecla(event,this)',
                        'oninput': 'validarNumber(this,/[^0-9.]/g)'
                    });
                }

            })

            const cboClientesActivos = document.getElementById('cboPorCliente'),
                cboOrdenActivas = document.getElementById('cboPorOrden'),
                fecha = document.getElementById('fecha'),
                fecha_retorno = document.getElementById('fecha_retorno');

            $(document).ready(function() {
                const nro_guia = document.getElementById('nro_guia'),
                    nro_factura = document.getElementById('nro_fac'),
                    cboOrden = document.getElementById('cboOrden'),
                    cboOrdenFab = document.getElementById('cboOrdenFab'),
                    form_guia = document.getElementById('form_guia'),
                    cboProveedor = document.getElementById('cboProveedores'),
                    cboConductor = document.getElementById('cboConductor'),
                    cboDespachado = document.getElementById('cboDespachado'),
                    cboResponsable = document.getElementById('cboResponsable'),
                    btnGuia = document.getElementById('btnGuardarGuia');
                const formFab = document.getElementById('formFab'),
                    formFabCon = document.getElementById('formFabCon'),
                    formFabNew = document.getElementById('formFabNew');
                const audio = document.getElementById("scanner");



                $(cboOrden).select2({
                    placeholder: 'SELECCIONE',
                    width: 'auto',
                })

                // $(cboEmpleado).select2({
                //     placeholder: 'SELECCIONE',
                //     width: 'auto',
                // })

                $(cboClientesActivos).select2({
                    placeholder: 'SELECCIONE',
                    width: '100%',
                })

                $(cboOrdenActivas).select2({
                    placeholder: 'SELECCIONE',
                    width: 'auto',
                })

                $(cboProveedor).select2({
                    placeholder: 'SELECCIONE',
                    width: 'auto',
                })

                $(cboFab).select2({
                    placeholder: 'SELECCIONE',
                    width: 'auto',
                })

                $(cboFabCon).select2({
                    placeholder: 'SELECCIONE',
                    width: 'auto',
                })

                $(cboConductor).select2({
                    placeholder: 'SELECCIONE',
                })

                $(cboDespachado).select2({
                    placeholder: 'SELECCIONE',
                    minimumResultsForSearch: -1,
                })
                $(cboResponsable).select2({
                    placeholder: 'SELECCIONE',
                })

                cargarCombo('Proveedores');
                cargarCombo('Unidad');
                cargarCombo('Clientes', '', 1, true)
                    .then(datos_ => {
                        datos_cliente = datos_;
                        // console.log(datos_cliente)
                    });

                cargarCombo('Conductor', '', 2);
                cargarComboFabricado();
                cargarCombo('FabricadoCon', '', 9);
                cargarCombo('Orden', '', 3, true).then(datos_ => {
                    datos_orden = datos_;

                    $(cboOrdenFab).select2({
                        placeholder: 'SELECCIONE',
                        width: 'auto',
                        data: datos_orden
                    })

                });
                cargarCombo('Despachado', '', 6);
                cargarCombo('Responsable', '', 7)


                cargarCombo('Unidad', '', 1, true).then(datos_ => {
                    $('#cboUnidad_fab').select2({
                        placeholder: 'SELECCIONE',
                        width: 'auto',
                        data: datos_
                    })
                })

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
                    "dom": '<"row"<"col-sm-8"B><"col-sm-4"p>>t',
                    "responsive": true,
                    "lengthChange": false,
                    "ordering": false,
                    "autoWidth": false,
                    "paging": false,
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
                            targets: 5,
                            className: "text-center",
                        },

                    ],
                    buttons: [{
                            text: "<i class='fa-regular fa-trash-can fa-xl'style='color: #bd0000'></i> Borrar todo",
                            className: "btn btn-light text-danger",
                            action: function(e, dt, node, config) {
                                dt.clear().draw(); // Esta línea vacía los datos de la tabla
                            }
                        },
                        {
                            text: "<i class='fa-regular fa-layer-plus fa-xl'></i> Nuevo Prod.",
                            className: "btn btn-light text-success btnNuevoPro",
                            action: function(e, dt, node, config) {
                                formFabNew.reset();
                                formFabNew.classList.remove('was-validated');
                                accion_fab = 9;
                                setChange(cboOrdenFab, 0);
                                cambiarModal(title_fab, ' Nuevo Producto en Producción', icon_fab, 'fa-pen-to-square', elements_fab, 'bg-gradient-blue', 'bg-gradient-success', modal_fab, 'modal-new', 'modal-change')
                                select_fab.forEach(function(s) {
                                    s.classList.remove('select2-warning');
                                    s.classList.add('select2-success');
                                });
                                $('#modal-new-fab').modal("show");
                            }
                        },
                        {
                            text: "<i class='fa-regular fa-hammer fa-xl'></i> Agregar a Prod.",
                            className: "btn btn-light text-info",
                            action: function(e, dt, node, config) {
                                tblOut.rows().every(function() {
                                    var row = $(this.node());
                                    var rowData = this.data();
                                    // Obtener el valor del input en la tercera columna (índice 2)
                                    var inputValue = row.find('input.cantidad').val();

                                    // Actualizar el valor en rowData
                                    rowData[2] = '<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="' + inputValue + '">',

                                        // Agregar la fila con los valores actualizados a tblFab
                                        tblFab.row.add([
                                            rowData[0],
                                            rowData[1],
                                            rowData[2],
                                            rowData[3],
                                            rowData[4],
                                            rowData[5]
                                        ]).draw(false);
                                });

                                // Opcional: Limpiar todas las filas de tblOut después de transferir
                                tblOut.clear().draw();
                                $('#modal-fab').modal("show");
                            }
                        },
                        {
                            text: "<i class='fas fa-building-magnifying-glass fa-xl'></i> Consultar Prod.",
                            className: "btn btn-light text-dark btnAgregarPro",
                            action: function(e, dt, node, config) {
                                // formFabCon.reset();
                                // formFabCon.classList.remove('was-validated');
                                $('#modal-consul').modal("show");
                            }
                        },
                        // {
                        //     text: "<input type='checkbox' id='checkbox_input'>",
                        //     init: function(dt, node, config) {
                        //         $(document).on('change', '#checkbox_input', function() {
                        //             // Aquí puedes agregar tu lógica para manejar el evento de cambio del checkbox
                        //             if ($(this).is(':checked')) {
                        //                 // Acción cuando el checkbox está marcado
                        //                 console.log('Checkbox marcado');
                        //             } else {
                        //                 // Acción cuando el checkbox está desmarcado
                        //                 console.log('Checkbox desmarcado');
                        //             }
                        //         });
                        //     }
                        // }
                    ]
                });

                // tblOut.on('draw', function() {
                //     $('#tblOut tbody tr').each(function() {
                //         // Desactivar tabindex en la primera columna
                //         $(this).find('td:first-child *').attr('tabindex', '-1');

                //         // Desactivar tabindex en la última columna
                //         // $(this).find('td:last-child *').attr('tabindex', '-1');

                //         // Si deseas desactivar tabindex en otras columnas específicas, ajusta los selectores como sea necesario
                //         // Ejemplo: $(this).find('td:nth-child(3) *').attr('tabindex', '-1'); para la tercera columna
                //     });
                // });

                $('#tblOut').on('keydown', 'input.cantidad', moveFocusOnTab);


                tblFab = $('#tblFab').DataTable({
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
                            visible: false,
                        },
                        {
                            targets: 2,
                            className: "text-center ",
                        },
                        {
                            targets: 3,
                            className: "text-center ",
                        },
                    ],
                    buttons: [{
                        text: "<i class='fa-regular fa-trash-can fa-xl'style='color: #bd0000'></i> Borrar todo",
                        className: "btn btn-light text-danger",
                        action: function(e, dt, node, config) {
                            dt.clear().draw(); // Esta línea vacía los datos de la tabla
                        }
                    }, ]
                });

                // const btnAgregarPro = document.querySelector('.btnAgregarPro');



                $('#tblIn tbody').on('click', '.btnEliminarIn', function() {
                    tblIn.row($(this).parents('tr')).remove().draw();
                });

                $('#tblFab tbody').on('click', '.btnEliminarIn', function() {
                    tblFab.row($(this).parents('tr')).remove().draw();
                });

                // $('#tblOut tbody').on('click', '.btnFab', function() {
                //     // Obtener la fila padre del botón clickeado
                //     var fila = $(this).closest('tr');

                //     // Obtener los datos de la fila
                //     var cantidad = fila.find('.cantidad').val();
                //     var descripcion = fila.find('td:eq(3)').text(); // Cambia el índice según la posición de la columna
                //     // Aquí obtén otros datos que necesites según su posición en la fila

                //     // Crear una nueva fila en tblFab con los datos obtenidos
                //     var nuevaFila = '<tr>' +
                //         '<td class="text-center">' + cantidad + '</td>' +
                //         '<td>' + descripcion + '</td>' +
                //         '<td class="text-center">' +
                //         '<button type="button" class="btnEliminarFab btn btn-danger">Eliminar</button>' +
                //         '</td>' +
                //         '</tr>';

                //     // Agregar la nueva fila a tblFab
                //     $('#tblFab tbody').append(nuevaFila);

                //     // Ocultar la tarjeta de fabricación y eliminar la fila de tblIn
                //     card_fab.style.display = 'block'; // Si estás mostrando la tarjeta de fabricación
                //     tblOut.row($(this).parents('tr')).remove().draw();
                // });

                // $('#tblFab tbody').on('click', '.btnEliminarFab', function() {
                //     // Obtener la fila padre del botón clickeado
                //     var fila = $(this).closest('tr');

                //     // Eliminar la fila de la tabla tblFab
                //     fila.remove();
                // });

                $('#tblOut tbody').on('click', '.btnEliminarIn', function() {
                    tblOut.row($(this).parents('tr')).remove().draw();
                });



                //INICIA AUTOCOMPLETAR
                // $.ajax({
                //     url: "controllers/inventario.controlador.php",
                //     method: "POST",
                //     data: {
                //         'accion': 7
                //     },
                //     dataType: 'json',
                //     success: function(respuesta) {

                //         for (let i = 0; i < respuesta.length; i++) {
                //             var formattedItem = {
                //                 label: respuesta[i]['descripcion'],
                //                 value: respuesta[i]['descripcion'],
                //                 id: respuesta[i]['id'],
                //                 cantidad: respuesta[i]['cantidad']
                //             };
                //             items.push(formattedItem);
                //         }

                //         $(inputauto).autocomplete({
                //             source: items,
                //             select: function(event, ui) {
                //                 CargarProductos(ui.item.id);
                //                 return false;
                //             }
                //         }).data("ui-autocomplete")._renderItem = function(ul, item) {
                //             return $("<li>")
                //                 .append("<div>" + item.label + "<strong class='large-text'>CANTIDAD: " + item.cantidad + "</strong></div>")
                //                 .appendTo(ul);
                //         };
                //     }
                // });

                cargarAutocompletado(function(items) {
                    $(inputauto).autocomplete({
                        source: items,
                        minLength: 5,
                        select: function(event, ui) {
                            CargarProductos(ui.item.cod);
                            return false;
                        },
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                        return $("<li>").append(
                            "<div>" + item.label + "<strong class='large-text'>CANTIDAD: " +
                            item.cantidad + "</strong></div>"
                        ).appendTo(ul);
                    };
                })

                let action;
                const tabs = document.querySelectorAll('.tabs input');
                const div_orden = document.getElementById('div_orden');
                const div_proveedor = document.getElementById('div_proveedor');
                const div_return = document.getElementById('div_return');
                const div_retorno = document.getElementById('div_retorno');
                const div_nroguia = document.getElementById('div_nroguia');
                const div_conductor = document.getElementById('div_conductor');
                const div_productos = document.getElementById('div_productos');
                const div_nrofactura = document.getElementById('div_nrofac');
                const div_person = document.getElementById('card_person');



                // const formOrden = document.getElementById('formOrden'),
                //     id_orden = document.getElementById('id_orden'),
                //     nro_orden = document.getElementById('num_orden'),
                //     span_text = document.getElementById('nom_action');

                // const new_orden = document.getElementById('new_orden'),
                //     edit_orden = document.getElementById('edit_orden'),
                //     eli_orden = document.getElementById('edit_orden');

                document.addEventListener("keydown", function(event) {
                    // Verifica si se presionó la tecla 'g' y la tecla 'ctrl' (o 'command' en Mac)
                    if (event.ctrlKey && (event.key === "g" || event.key === "G")) {
                        // Evita el comportamiento por defecto del navegador
                        event.preventDefault();

                        // Simula el clic en el botón
                        control.click();
                    }
                });


                // new_orden.addEventListener('click', () => {
                //     card_orden.style.display = 'block'
                //     action = 4
                //     nro_orden.focus();
                //     formOrden.reset();
                //     setChange(cboCliente, 0);
                // })

                // edit_orden.addEventListener('click', () => {
                //     card_orden.style.display = 'block'
                //     action = 5
                //     nro_orden.focus();
                // })

                // eli_orden.addEventListener('click', () => {
                //     action = 6
                // })

                // formOrden.addEventListener("submit", function(e) {
                //     e.preventDefault();
                //     const id_o = id_orden.value,
                //         num = nro_orden.value.trim(),
                //         cli = cboCliente.value;
                //     if (!this.checkValidity()) {
                //         this.classList.add('was-validated');
                //         return;
                //     }
                //     let datos = new FormData();
                //     datos.append('id', id_o);
                //     datos.append('num_orden', num);
                //     datos.append('id_cliente', cli);
                //     datos.append('accion', action)
                //     confirmarAccion(datos, 'producto', null, '', function(r) {
                //         if (r) {
                //             cargarCombo('Orden', r.res, 3, true).then(datos_ => {
                //                 datos_orden = datos_;
                //                 console.log(id_o)
                //             });
                //         }
                //     });
                //     card_orden.style.display = 'none'
                //     formOrden.reset();
                //     setChange(cboCliente, 0);
                // });

                form_guia.addEventListener("submit", function(e) {
                    e.preventDefault();
                    btnGuia.click();
                });

                btnGuia.addEventListener('click', () => {
                    let formData = new FormData();
                    if (selectedTab === '1') {
                        let elementosAValidar = [fecha, cboProveedor, nro_factura];
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
                        formData.append('nro_factura', nro_factura.value);
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 1);
                        realizarRegistro(tblIn, formData, clases);
                    } else if (selectedTab === '2') {
                        let elementosAValidar = [fecha, cboOrden, nro_guia, cboDespachado, cboResponsable, cboConductor];
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
                        formData.append('nro_guia', nro_guia.value);
                        formData.append('conductor', cboConductor.value);
                        formData.append('despachado', cboDespachado.value);
                        formData.append('responsable', cboResponsable.value);
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 2);
                        realizarRegistro(tblOut, formData, clases);
                    } else if (selectedTab === '3') {
                        let clases = ['retorno'];
                        formData.append('boleta', id_boleta);
                        formData.append('fecha_retorno', fecha_retorno.value);
                        formData.append('accion', 3);
                        realizarRegistro(tblReturn, formData, clases, 0);
                    } else if (selectedTab === '4') {
                        let elementosAValidar = [fecha, cboOrden, nro_guia, cboDespachado, cboResponsable, cboConductor];
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
                        formData.append('id_boleta', id_boleta);
                        formData.append('orden', cboOrden.value);
                        formData.append('nro_guia', nro_guia.value);
                        formData.append('conductor', cboConductor.value);
                        formData.append('despachado', cboDespachado.value);
                        formData.append('responsable', cboResponsable.value);
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 4);

                        $.ajax({
                            url: "controllers/registro.controlador.php",
                            method: "POST",
                            data: formData,
                            cache: false,
                            dataType: "json",
                            contentType: false,
                            processData: false,
                            success: function(r) {
                                let isSuccess = r.status === "success";

                                mostrarToast(r.status,
                                    isSuccess ? "Completado" : "Error",
                                    isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
                                    r.m);

                                if (isSuccess) {
                                    tblDetalleSalida.clear().draw();
                                    tabla ? tabla.ajax.reload(null, false) : ''
                                    cargarAutocompletado();
                                }
                            }
                        });
                    } else if (selectedTab === '5') {
                        let elementosAValidar = [fecha, cboProveedor, nro_factura];
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
                        formData.append('id_factura', id_boleta);
                        formData.append('proveedor', cboProveedor.value);
                        formData.append('nro_factura', nro_factura.value);
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 5);

                        $.ajax({
                            url: "controllers/registro.controlador.php",
                            method: "POST",
                            data: formData,
                            cache: false,
                            dataType: "json",
                            contentType: false,
                            processData: false,
                            success: function(r) {
                                let isSuccess = r.status === "success";

                                mostrarToast(r.status,
                                    isSuccess ? "Completado" : "Error",
                                    isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
                                    r.m);

                                if (isSuccess) {
                                    tblDetalleSalida.clear().draw();
                                    tabla ? tabla.ajax.reload(null, false) : ''
                                    cargarAutocompletado();
                                }
                            }
                        });
                    }
                })

                tabs.forEach(tab => {
                    tab.addEventListener('change', function() {
                        selectedTab = this.value;
                        if (selectedTab === '1') {
                            tblOut.clear().draw(); // Esta línea vacía los datos de la tabla
                            tblIn.clear().draw();
                        }

                        form_guia.classList.remove('was-validated');
                        const selectedForm = document.getElementById(`form-${selectedTab}`);
                        const formContainers = document.querySelectorAll('.form-container');
                        // console.log(selectedForm)
                        formContainers.forEach(container => {
                            container.style.display = 'none';
                        });

                        if (selectedForm) {
                            selectedForm.style.display = 'block';
                        }

                        if (selectedTab === '1' || selectedTab === '5') {
                            div_orden.style.display = 'none';
                            div_productos.style.display = 'flex';
                            div_proveedor.style.display = 'block';
                            div_conductor.style.display = 'none';
                            div_retorno.style.display = 'none';
                            div_nroguia.style.display = 'none';
                            // card_orden.style.display = 'none';
                            div_return.style.display = 'none';
                            div_person.style.display = 'none';
                            div_nrofactura.style.display = 'block';
                        } else if (selectedTab === '2' || selectedTab === '4') {
                            div_orden.style.display = 'block';
                            div_proveedor.style.display = 'none';
                            div_productos.style.display = 'flex';
                            // card_orden.style.display = 'none';
                            div_return.style.display = 'none';
                            div_conductor.style.display = 'block';
                            div_retorno.style.display = 'none';
                            div_nroguia.style.display = 'block';
                            div_nrofactura.style.display = 'none';
                            div_person.style.display = 'block';

                        } else if (selectedTab === '3') {
                            div_orden.style.display = 'none';
                            div_proveedor.style.display = 'none';
                            div_productos.style.display = 'none';
                            div_return.style.display = 'block'
                            // card_orden.style.display = 'none';
                            div_conductor.style.display = 'block';
                            div_retorno.style.display = 'block';
                            div_nroguia.style.display = 'none';
                            div_nrofactura.style.display = 'none';
                            div_person.style.display = 'block';

                        }
                    });
                });

                function CargarProductos(p = "", barras = false) {

                    let existingRow = tblIn.row("#producto_" + p);
                    let existingRowOut = tblOut.row("#producto_" + p);

                    // var id_producto = p;
                    if (selectedTab === '4') {
                        $.ajax({
                            url: "controllers/salidas.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 1, //BUSCAR PRODUCTOS POR id_producto
                                'codigo': p,
                                'id_boleta': id_boleta
                            },
                            dataType: 'json',
                            success: function(r) {
                                tblDetalleSalida.ajax.reload(null, false)
                                tabla.ajax.reload(null, false);
                                cargarAutocompletado();
                                inputauto.value = '';
                                let isSuccess = r.status === 'success';
                                mostrarToast(
                                    r.status,
                                    isSuccess ? "Completado" : "Error",
                                    isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
                                    r.m
                                );
                            }
                        });
                    } else if (selectedTab === '5') {
                        $.ajax({
                            url: "controllers/entradas.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 3, //BUSCAR PRODUCTOS POR id_producto
                                'codigo': p,
                                'id_factura': id_boleta
                            },
                            dataType: 'json',
                            success: function(r) {
                                tblDetalleEntrada.ajax.reload(null, false)
                                tabla.ajax.reload(null, false);
                                cargarAutocompletado();
                                inputauto.value = '';
                                let isSuccess = r.status === 'success';
                                mostrarToast(
                                    r.status,
                                    isSuccess ? "Completado" : "Error",
                                    isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
                                    r.m
                                );
                            }
                        });
                    } else {
                        if (existingRow.any()) {
                            audio.play();
                            if (barras) {
                                inputBarras.disabled = true;
                            }
                            // Si el id_producto ya está en el DataTable, actualizar la cantidad
                            var rowData = existingRow.data();
                            var cantidadInput = existingRow.node().querySelector('.cantidad');
                            var cantidad = parseFloat(cantidadInput.value) + 1; // Incrementar la cantidad
                            cantidadInput.value = cantidad; // Actualizar el valor en el input
                            audio.onended = function() {
                                if (barras) {
                                    inputBarras.disabled = false;
                                    inputBarras.focus();
                                }
                                $(inputBarras).val("");
                            }
                            return;
                        }

                        if (existingRowOut.any()) {
                            audio.play();
                            var rowData = existingRowOut.data();
                            var cantidadInput = existingRowOut.node().querySelector('.cantidad');
                            var cantidad = parseFloat(cantidadInput.value) + 1; // Incrementar la cantidad
                            cantidadInput.value = cantidad; // Actualizar el valor en el input
                            audio.onended = function() {
                                $(inputBarras).val("");
                            }
                            return;
                            // audio.play = function() {
                            //     // Si el id_producto ya está en el DataTable, actualizar la cantidad

                            // };
                        }
                        $.ajax({
                            url: "controllers/inventario.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 4, //BUSCAR PRODUCTOS POR id_producto
                                'id': p
                            },
                            dataType: 'json',
                            success: function(respuesta) {
                                if (respuesta) {
                                    audio.play();
                                    if (barras) {
                                        inputBarras.disabled = true;
                                    }
                                    if (selectedTab === '1') {
                                        tblIn.row.add([
                                            '',
                                            respuesta['id'],
                                            '<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="' + respuesta['cantidad'] + '">',
                                            respuesta['nombre'],
                                            '$<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline precio" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="0">',
                                            respuesta['descripcion'],
                                            "<center>" +
                                            "<span class='btnEliminarIn text-danger'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar producto'> " +
                                            "<i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> </i> " +
                                            "</span>" +
                                            "</center>",
                                        ]).node().id = "producto_" + respuesta['codigo'];
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
                                        ]).node().id = "producto_" + respuesta['codigo'];
                                        tblOut.draw(false);
                                    }
                                    audio.onplay = function() {
                                        $(inputauto).val("");
                                    }
                                    audio.onended = function() {
                                        if (barras) {
                                            inputBarras.disabled = false;
                                            inputBarras.focus();
                                        }
                                        $(inputBarras).val("");

                                    }
                                    /*===================================================================*/
                                    //SI LA RESPUESTA ES FALSO, NO TRAE ALGUN DATO
                                    /*===================================================================*/
                                } else {
                                    // Toast.fire({
                                    //     icon: 'error',
                                    //     title: ' El producto no existe o no tiene stock'
                                    // });
                                    // $(inputauto).val("");
                                    // $(inputauto).focus();
                                }
                            }
                        });
                    }
                }



                inputBarras.addEventListener("input", function(event) {
                    event.preventDefault();
                    let codigo = this.value;
                    if (codigo.length >= 5) {
                        CargarProductos(codigo, true)
                    }
                })
            });

            function realizarRegistro(table, formData, clases, producto = 1, header = 'productos') {
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

                        let valores = clases.map(clase => {
                            let inputElement = row.node().querySelector('input.' + clase);
                            // Si inputElement es null, retornar data[5]
                            return inputElement ? inputElement.value : data[5];
                        });

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
                            let isSuccess = r.status === "success";

                            mostrarToast(r.status,
                                isSuccess ? "Completado" : "Error",
                                isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
                                r.m);

                            if (isSuccess) {
                                table.clear().draw();
                            }
                            tabla ? tabla.ajax.reload(null, false) : ''
                            cargarAutocompletado();
                        }
                    });

                } else {
                    mostrarToast('danger', "Error", "fa-solid fa-xmark fa-lg", 'No hay ' + header + ' en el listado');
                }
            }
        </script>
    </body>
<?php } else { ?>

    <body class='hold-transition login-page'>
        <?php include 'views/login.php'; ?>
    </body>
<?php } ?>

</html>