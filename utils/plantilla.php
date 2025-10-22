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
    <link rel='stylesheet' href='assets/plugins/jquery-ui/jquery-ui.min.css'>
    <!-- DataTables -->
    <link href='assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css' rel='stylesheet' type='text/css' />
    <link href='assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css' rel='stylesheet' type='text/css' />
    <link href='assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css' rel='stylesheet' type='text/css' />
    <link href="assets/plugins/datatables-searchpanes/css/searchPanes.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-scroller/css/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/daterange-vanilla/daterange.min.css" rel="stylesheet">

    <noscript>
        <link href="assets/plugins/datatables-searchpanes/css/searchPanes.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css" type="text/css" />
        <!-- <link rel="stylesheet" href="assets/plugins/datatables-select/css/select.bootstrap4.min.css" /> -->
    </noscript>
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
    <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src='assets/plugins/bootstrap/js/bootstrap.bundle.min.js'></script>
    <!-- AdminLTE App -->
    <script src='assets/js/theme/adminlte.min.js'></script>
    <!-- DataTables  & Plugins -->
    <script src='assets/plugins/datatables-scroller/js/scroller.bootstrap4.min.js'></script>
    <script src='assets/plugins/datatables/jquery.dataTables.min.js' type='text/javascript'></script>
    <script defer src='assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js' type='text/javascript'></script>
    <script defer src='assets/plugins/datatables-responsive/js/dataTables.responsive.min.js' type='text/javascript'></script>
    <script defer src='assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js' type='text/javascript'></script>
    <script defer src='assets/plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js' type='text/javascript'></script>
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
    <script defer src="assets/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>
    <script defer src="assets/plugins/datatables-searchpanes/js/dataTables.searchPanes.min.js" type="text/javascript"></script>
    <script defer src="assets/plugins/datatables-searchpanes/js/searchPanes.bootstrap4.min.js" type="text/javascript"></script>
    <script defer src="assets/plugins/datatables-select/js/dataTables.select.min.js" type="text/javascript"></script>
    <script defer src="assets/plugins/datatables-select/js/select.bootstrap4.min.js" type="text/javascript"></script>
    <!-- Tabledit -->
    <script defer src="assets/plugins/jquery-tabledit/tabledit.min.js"></script>
    <script defer src="assets/plugins/chart.js/Chart.min.js"></script>
    <script defer src="assets/plugins/chart.js/ChartDataLabels.min.js"></script>
    <!-- tinyMCE -->
    <!-- <script defer src="assets/plugins/tinymce/tinymce.min.js"></script> -->
</head>
<?php if (isset($_SESSION['s_usuario'])) {
?>
    <script>
        // var calendarInstance = null;
        var tabla, tblCompra, tblOut, tblIn, tblReturn, tblDetalleSalida, tblDetalleCompra, tblProdFab, tblDetalleFab;
        var configuracionTable = {};
        let items = [];
        let id_boleta = 0;
        let id_boleta_fab = 0;
        let id_prod_fab = 0;
        let accion_salida = 4;
        let accion_inv = 0;
        let scroll = false;
        let sub = false;
        let items_orden = [];
        let id_orden_guia_salida;
        let id_orden_guia_entrada;
        let id_orden_guia_fab;
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth() + 1;
        const mes = month.toString().padStart(2, '0');
        const dia = now.getDate().toString().padStart(2, '0');
        // moment.locale('es');
        const fecha_hoy = `${year}-${mes}-${dia}`;

        let estado_filter = 'null';
        let datos_cliente = [];
        let datos_uni = [];
        let datos_orden = [];
        let datos_prove = [];
        let datos_anio = [];
        let datos_person = [];
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
            },
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
            const estadoClases = {
                'ESPERA': 'light',
                'OPERACION': 'warning',
                'FINALIZADO': 'info',
                'FACTURADO': 'success',
                'GARANTIA': 'gray-dark'
            };

            const estadoIcon = {
                'ESPERA': 'clock',
                'OPERACION': 'person-digging',
                'FINALIZADO': 'check-to-slot',
                'FACTURADO': 'money-check-dollar',
                'GARANTIA': 'award'
            };

            const body = document.querySelector('body'),
                html = document.querySelector('html'),
                navbar = body.querySelector('.navbar'),
                sidebar = body.querySelector('.main-sidebar'),
                controlSide = body.getElementsByClassName('control-sidebar')[0],
                control = body.querySelectorAll('.ctrl-side'),
                first_control = body.querySelector('#first_control'),
                second_control = document.querySelector('#second_control'),
                btnSide = document.getElementById('btnSide'),
                setA = body.querySelectorAll('.setA'),
                setB = body.querySelectorAll('.setB'),
                inputauto = body.querySelector('#codProducto'),
                inputBarras = body.querySelector('#codBarras');


            // btnSide.addEventListener('click', function() {
            //     setTimeout(function() {
            //         tabla.columns.adjust().draw();
            //     }, 100);

            // });
            // const configu = {
            //     selector: "#autoComplete",
            //     placeHolder: "Search for numero de orden...",
            //     data: {
            //         src: ["00 000", "01 005", "02 021"],
            //         cache: false,
            //     },
            //     resultsList: {
            //         element: (list, data) => {
            //             if (!data.results.length) {
            //                 // Create "No Results" message element
            //                 const message = document.createElement("div");
            //                 // Add class to the created element
            //                 message.setAttribute("class", "no_result");
            //                 // Add message text content
            //                 message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
            //                 // Append message element to the results list
            //                 list.prepend(message);
            //             }
            //         },
            //         noResults: true,
            //     },
            //     resultItem: {
            //         highlight: true,
            //     }
            // }
            // const autoCompleteJS = new autoComplete({
            //     configu
            // });


            // inputBarras.addEventListener('focus', function(event) {
            //     event.target.blur(); // Esto deshabilita el enfoque
            // });


            // document.addEventListener('keydown', function(event) {
            //     if (event.target.id !== 'codBarras') {
            //         // let input = document.getElementById('barcodeInput');
            //         inputBarras.value += event.key;
            //     }
            // });

            const card_fab = document.getElementById('card_fab');
            let nro_sec_cotiz = <?php echo ($_SESSION["sc_cot"] == null) ? 0 : $_SESSION["sc_cot"]; ?>;
            let iva_config = <?php echo $_SESSION["iva"]; ?>;
            let sbu_config = <?php echo $_SESSION["sbu"]; ?>;
            const isEntrada = <?php echo ($_SESSION["entrada_mul"]) ? 1 : 0; ?>;
            const isSuperAdmin = <?php echo ($_SESSION["s_usuario"]->id_perfil == 1) ? 1 : 0; ?>;
            const bodegueroPorDefecto = <?php echo ($_SESSION["bodeguero"] == null) ? 0 : $_SESSION["bodeguero"]; ?>;
            const conductorPorDefecto = <?php echo ($_SESSION["conductor"] == null) ? 0 : $_SESSION["conductor"]; ?>;

            document.addEventListener('click', function() {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "utils/database/save_session.php", true);
                xhr.send();
            });

            function checkSession() {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "utils/database/check_session.php", true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status == 'expired') {
                            // alert('La sesion ha expirado');
                            clearInterval(checkSessionInterval);
                            Swal.fire({
                                title: 'Sesión Terminada',
                                text: 'Su sesión ha terminado. Será redirigido a la página de inicio de sesión.',
                                icon: 'warning',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const btnlogout = document.getElementById('btnlogout')
                                    btnlogout.click();
                                }
                            });
                        }
                    }
                };
                xhr.send();
            }

            // Verificar el estado de la sesión cada 5 minutos (300000 milisegundos)
            checkSessionInterval = setInterval(checkSession, 300000);

            setA.forEach((e) => {
                e.addEventListener('click', function() {
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
                        control.forEach((e) => {
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

                    const parentSetA = this.closest('.sub').querySelector('.setA');
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
                var tablaData = localStorage.getItem(ruta);
                accion_inv = 0;
                scroll = false;
                // console.log('Cargando contenido: ' + id);

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
                        // $('.dtfh-floatingparent').remove(); // Limpia headers flotantes colgados

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

            $(document).on('select2:open', () => {
                setTimeout(() => {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) searchField.focus();
                }, 0);
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
                "dom": 'fpt',
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

            tblDetalleCompra = $('#tblDetalleCompra').DataTable({
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
                        className: "text-center",
                        data: 'precio'
                    },
                ],
                processing: true,
                serverSide: true,
                dom: 'pt',
            });

            $('#tblDetalleSalida').on('draw.dt', function() {
                // console.log("ID BOLETA EN EL DRA ES: " + id_boleta)
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
                        restoreButton: false,
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
                            let isSuccess = data.status === 'success';
                            mostrarToast(
                                data.status,
                                isSuccess ? "Completado" : "Error",
                                isSuccess ? "fa-check fa-lg" : "fa-xmark fa-lg",
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
            });

            $('#tblDetalleCompra').on('draw.dt', function() {
                if (id_boleta != 0) {
                    $('#tblDetalleCompra').Tabledit({
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
                        restoreButton: false,
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
                            },
                            restore: {
                                class: '',
                                html: '',
                                action: 'restore'
                            }
                        },
                        onSuccess: function(data, textStatus, jqXHR) {
                            let isSuccess = data.status === 'success';
                            mostrarToast(
                                data.status,
                                isSuccess ? "Completado" : "Error",
                                isSuccess ? "fa-check fa-lg" : "fa-xmark fa-lg",
                                data.m
                            );
                            // $('#' + data.id_boleta).remove();
                            tblDetalleCompra.ajax.reload(null, false);
                            tabla.ajax.reload(null, false);
                            cargarAutocompletado();
                        }
                    })

                    $('#tblDetalleCompra').find('input[name="cantidad_entrada"]').attr({
                        'inputmode': 'numeric',
                        'autocomplete': 'off',
                        'onpaste': 'validarPegado(this, event)',
                        'onkeydown': 'validarTecla(event,this)',
                        'oninput': 'validarNumber(this,/[^0-9.]/g)'
                    });

                    $('#tblDetalleCompra').find('input[name="precio"]').attr({
                        'inputmode': 'numeric',
                        'autocomplete': 'off',
                        'onpaste': 'validarPegado(this, event)',
                        'onkeydown': 'validarTecla(event,this)',
                        'oninput': 'validarNumber(this,/[^0-9.]/g,false,3)'
                    });
                }

            });

            const cboOrdenActivas = document.getElementById('cboPorOrden'),
                fecha = document.getElementById('fecha'),
                fecha_retorno = document.getElementById('fecha_retorno'),
                motivo = document.getElementById('inpMotivo');

            $(document).ready(function() {
                const nro_guia = document.getElementById('nro_guia'),
                    nro_guiaEntrada = document.getElementById('nro_guiaEntrada'),
                    nro_guiaFab = document.getElementById('nro_guiaFab'),
                    nro_factura = document.getElementById('nro_fac'),
                    nro_orden = document.getElementById('nro_orden'),
                    nro_ordenEntrada = document.getElementById('nro_ordenEntrada'),
                    nro_ordenFab = document.getElementById('nro_ordenFab'),
                    // cboOrdenFab = document.getElementById('cboOrdenFab'),
                    form_guia = document.getElementById('form_guia'),
                    cboProveedor = document.getElementById('cboProveedores'),
                    cboConductor = document.getElementById('cboConductor'),
                    cboConductorEntrada = document.getElementById('cboConductorEntrada'),
                    cboDespachado = document.getElementById('cboDespachado'),
                    cboAutorizado = document.getElementById('cboAutorizado'),
                    cboResponsable = document.getElementById('cboResponsable'),
                    btnGuia = document.getElementById('btnGuardarGuia');
                const audio = document.getElementById("scanner");
                const addProFab = document.getElementById("addProFab")
                const isTrasFab = document.getElementById('isTrasFab');

                isTrasFab.addEventListener('click', function() {
                    nro_guiaFab.disabled = !isTrasFab.checked;
                });

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

                $(cboConductorEntrada).select2({
                    placeholder: 'SELECCIONE',
                })

                $(cboDespachado).select2({
                    placeholder: 'SELECCIONE',
                    minimumResultsForSearch: -1,
                });

                $(cboAutorizado).select2({
                    placeholder: 'SELECCIONE',
                });

                // $(cboResponsable).select2({
                //     placeholder: 'SELECCIONE',
                // });

                cargarCombo('Proveedores', '', 1, true).then(datos_ => {
                    datos_prove = datos_;
                });
                cargarCombo('Unidad', '', 1, true).then(datos_ => {
                    datos_uni = datos_;
                });
                cargarCombo('Clientes', '', 1, true).then(datos_ => {
                    datos_cliente = datos_;
                });

                cargarCombo('Conductor', conductorPorDefecto, 2);
                cargarCombo('ConductorEntrada', conductorPorDefecto, 2);


                cargarCombo('FabricadoCon', '', 9);
                // cargarCombo('Orden', '', 3, true).then(datos_ => {
                //     datos_orden = datos_;

                //     $(cboOrdenFab).select2({
                //         placeholder: 'SELECCIONE',
                //         width: 'auto',
                //         data: datos_orden
                //     })
                // });

                cargarCombo('Despachado', bodegueroPorDefecto, 6);
                cargarCombo('Responsable', '', 7, true).then(datos_ => {
                    datos_person = datos_;
                    $(cboResponsable).select2({
                        placeholder: 'SELECCIONE',
                        width: 'auto',
                        data: datos_person
                    })
                    console.log('Estos son los datos de las personadas', datos_person)

                });

                cargarCombo('Unidad', '', 1, true).then(datos_ => {
                    $('#cboUnidad_fab').select2({
                        placeholder: 'SELECCIONE',
                        width: 'auto',
                        data: datos_
                    })
                })

                tblCompra = $('#tblCompra').DataTable({
                    "dom": '<"row"<"col-sm-6"B><"col-sm-6"p>>t',
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
                        // {
                        //     text: "<i class='fa-regular fa-layer-plus fa-xl'></i> Nuevo Prod.",
                        //     className: "btn btn-light text-success btnNuevoPro",
                        //     action: function(e, dt, node, config) {

                        //     }
                        // },
                        // {
                        //     text: "<i class='fa-regular fa-hammer fa-xl'></i> Agregar a Prod.",
                        //     className: "btn btn-light text-info",
                        //     action: function(e, dt, node, config) {
                        //         tblOut.rows().every(function() {
                        //             let row = $(this.node());
                        //             let rowData = this.data();
                        //             let inputValue = row.find('input.cantidad').val();
                        //             rowData[2] = '<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="' + inputValue + '">',
                        //                 // Agregar la fila con los valores actualizados a tblFab
                        //                 tblFab.row.add([rowData[0], rowData[1], rowData[2], rowData[3], rowData[4], rowData[5]]).draw(false);
                        //         });

                        //         // Opcional: Limpiar todas las filas de tblOut después de transferir
                        //         tblOut.clear().draw();
                        //         $('#modal-fab').modal("show");
                        //     }
                        // },
                        // {
                        //     text: "<i class='fa-regular fa-building-magnifying-glass fa-xl'></i> Consultar Prod.",
                        //     className: "btn btn-light text-dark btnAgregarPro",
                        //     action: function(e, dt, node, config) {
                        //         // formFabCon.reset();
                        //         // formFabCon.classList.remove('was-validated');
                        //         setChange(cboFabricadoCon, 0)
                        //         $('#modal-consul').modal("show");
                        //     }
                        // },
                    ]
                });

                tblIn = $('#tblIn').DataTable({
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
                    }, ]
                });

                tblProdFab = $('#tblProdFab').DataTable({
                    "dom": 'pt',
                    "lengthChange": false,
                    "ordering": false,
                    "autoWidth": false,
                    "paging": false,
                    columnDefs: [{
                            targets: 0,
                            data: null,
                            className: 'dt-control',
                            orderable: false,
                            defaultContent: '',
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
                            render: function(data, type, row) {
                                return `<input type="text" class="form-control text-center cantidad" value="${data || 1}" 
                                style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.2rem" maxlength="6"
                                inputmode="numeric" onfocus="selecTexto(this)" autocomplete="off" oninput="validarNumber(this,/[^0-9.]/g)">`;
                            }
                        },
                        {
                            targets: 2,
                            className: "text-center ",
                            render: function(data, type, row) {
                                return `<select class="form-control select2 id_unidad" data-dropdown-css-class="select2-dark" required>
                                </select>`;
                            }
                        },
                        {
                            targets: 3,
                            render: function(data, type, row) {
                                return `<input type="text" class="form-control descripcion" value="${data || ''}" 
                            style="width:100%;border-bottom-width:2px;margin:auto;font-size:1.1rem" 
                            autocomplete="off" onfocus="selecTexto(this)" spellcheck="false">`;
                            }
                        },
                        {
                            targets: 4,
                            className: "text-center",
                            render: function(data, type, row) {
                                return `<center>
                            <span class='btnEliminaRow text-danger' style='cursor:pointer;' data-bs-toggle='tooltip' 
                            data-bs-placement='top' title='Eliminar producto'> 
                                <i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> 
                                </i> </span>
                            </center>`;
                            }
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {
                        const selectElement = $(row).find('.id_unidad');
                        cargarOpcionesSelect(selectElement, data.id_unidad, '100%');

                    },
                });

                tblDetalleFab = $('#tblDetalleFab').DataTable({
                    "dom": 'pt',
                    "lengthChange": false,
                    "ordering": false,
                    "autoWidth": false,
                    "paging": false,
                    columnDefs: [{
                            targets: 0,
                            data: null,
                            className: 'dt-control',
                            orderable: false,
                            defaultContent: '',
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
                            render: function(data, type, row) {
                                return `<input type="text" class="form-control text-center cantidad" value="${data || 1}" 
                                style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.2rem" maxlength="6"
                                inputmode="numeric" onfocus="selecTexto(this)" autocomplete="off" oninput="validarNumber(this,/[^0-9.]/g)">`;
                            }
                        },
                        {
                            targets: 2,
                            className: "text-center ",
                            render: function(data, type, row) {
                                return `<select class="form-control select2 id_unidad" data-dropdown-css-class="select2-dark" required>
                                </select>`;
                            }
                        },
                        {
                            targets: 3,
                            render: function(data, type, row) {
                                return `<input type="text" class="form-control descripcion" value="${data || ''}" 
                            style="width:100%;border-bottom-width:2px;margin:auto;font-size:1.1rem" 
                            autocomplete="off" onfocus="selecTexto(this)" spellcheck="false">`;
                            }
                        },
                        {
                            targets: 4,
                            visible: false,
                            render: function(data, type, row) {
                                return `<input type="text" class="form-control text-center entrada" value="${data || ''}" 
                            style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.1rem" 
                            autocomplete="off" onfocus="selecTexto(this)" spellcheck="false">`;
                            }
                        },
                        {
                            targets: 5,
                            className: "text-center",
                            render: function(data, type, row) {
                                return `<center>
                            <span class='btnEliminaRowFab text-danger' style='cursor:pointer;' data-bs-toggle='tooltip' 
                            data-bs-placement='top' title='Eliminar producto'> 
                                <i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> 
                                </i> </span>
                            </center>`;
                            }
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {
                        const selectElement = $(row).find('.id_unidad');
                        cargarOpcionesSelect(selectElement, data[2], '100%');
                    },
                });

                // tblDetalleFabEntrada = $('#tblDetalleFabEntrada').DataTable({
                //     "dom": 'pt',
                //     "lengthChange": false,
                //     "ordering": false,
                //     "autoWidth": false,
                //     "paging": false,
                //     "ajax": {
                //         "url": "controllers/salidas.controlador.php",
                //         "type": "POST",
                //         dataSrc: "",
                //         "datatype": 'json',
                //         "data": function(d) {
                //             d.accion = 10;
                //             d.boleta = id_boleta_fab
                //         }
                //     },
                //     columnDefs: [{
                //             targets: 0,
                //             data: null,
                //             className: 'dt-control',
                //             orderable: false,
                //             defaultContent: '',
                //             render: function(data, type, row, meta) {
                //                 if (type === 'display') {
                //                     return meta.row + 1;
                //                 }
                //                 return meta.row;
                //             }
                //         },
                //         {
                //             targets: 1,
                //             data: 'descripcion',
                //         },
                //         {
                //             targets: 2,
                //             className: "text-center ",
                //             data: 'unidad',
                //         },
                //         {
                //             targets: 3,
                //             className: "text-center ",
                //             data: 'salidas',
                //         },
                //         {
                //             targets: 4,
                //             data: 'retorno',
                //             className: "text-center ",
                //             render: function(data, type, row) {
                //                 // Definir el valor del input
                //                 let value = (data === null) ? '' : row.retorno;
                //                 // Definir el HTML del input
                //                 let inputHTML = '<input value="' + value + '" type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline retorno" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" >';
                //                 // Condicional para id_perfil_sistema
                //                 if (isEntrada || isSuperAdmin) {
                //                     return inputHTML;
                //                 } else {
                //                     return row.isentrada ? row.retorno : inputHTML;
                //                 }
                //             }
                //         },
                //     ],
                // });

                addProFab.addEventListener('click', function() {
                    agregarFilaFab();
                });

                function agregarFilaFab() {
                    if (selectedTab == '8') {
                        $.ajax({
                            url: "controllers/fabricacion.controlador.php",
                            method: "POST",
                            data: {
                                accion: 1,
                                id_boleta: id_boleta_fab
                            },
                            dataType: "json",
                            success: function(r) {
                                if (r.status === 'success') {
                                    let nuevaFila = [r.id, '1', '', '', ''];
                                    tblDetalleFab.row.add(nuevaFila).draw(false).node();
                                    mostrarToast(r.status, "Completado", "fa-check fa-lg", r.m)
                                }
                            }
                        });
                    } else {
                        let idUnico = Date.now();
                        let nuevaFila = [idUnico, '1', '', '', ''];
                        let rowNode = tblProdFab.row.add(nuevaFila).draw(false).node();
                    }
                }

                function format(rowData, isEntrada = false) {
                    let textLabel = `<div class="mb-4 ui-front" style="padding-left:8%;">
                    <label class="col-form-label combo" for="search-${rowData}">
                        <i class="fas fa-arrow-up-a-z"></i> Productos
                    </label>
                    <input style="border-bottom: 2px solid var(--select-border-bottom);" 
                        type="search" 
                        class="form-control form-control-sm searchFab" 
                        id="search-${rowData}" 
                        placeholder="Escriba para agregar...">
                    </div>`;
                    let tableHtml = `<table class="table table-hover" id="tbl${rowData}" cellpadding="5" cellspacing="0" border="0" style="padding-left:8%; width:100%;padding-bottom:2%">
                        </table>`;
                    // Concatenar dependiendo de si esEntrada es true o false
                    return (isEntrada ? '' : textLabel) + tableHtml;
                }

                $('#tblProdFab tbody').on('click', 'td.dt-control', function() {
                    let tr = $(this).closest('tr'); // Encuentra la fila más cercana
                    let row = tblProdFab.row(tr); // Obtiene la fila de DataTable
                    let rowData = row.data(); // Obtiene los datos de la fila
                    let idUnico = rowData[0]; // Identificador único
                    let tablaId = `#tbl${idUnico}`; // ID correcto basado en format()
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        // Verificar si la fila ya tiene contenido para evitar recrearla
                        if (!tr.hasClass('loaded')) {
                            row.child(format(idUnico)).show();
                            tr.addClass('loaded'); // Marcamos que ya ha sido cargada
                        } else {
                            row.child.show();
                        }
                        // Verificar si la DataTable ya existe antes de inicializarla
                        if (!$.fn.dataTable.isDataTable(tablaId)) {
                            $(tablaId).DataTable({
                                dom: "t",
                                "ordering": false,
                                "autoWidth": false,
                                paging: false,
                                data: null,
                                columnDefs: [{
                                        targets: 0,
                                        title: "N°",
                                        data: null,
                                        render: function(data, type, row, meta) {
                                            return type === 'display' ? meta.row + 1 : meta.row;
                                        }
                                    },
                                    {
                                        targets: 1,
                                        title: "CODIGO",
                                        visible: false
                                    },
                                    {
                                        targets: 2,
                                        title: "CANT."
                                    },
                                    {
                                        targets: 3,
                                        title: "UND",
                                        className: "text-center"
                                    },
                                    {
                                        targets: 4,
                                        title: "DESCRIPCION",
                                        className: "text-center"
                                    },
                                    {
                                        targets: 5,
                                        title: "ENTRADA",
                                        className: "text-center",
                                        
                                    },
                                    {
                                        targets: 6,
                                        title: "ACCIONES",
                                        className: "text-center"
                                    }
                                ]
                            });
                        }
                        tr.addClass('shown');
                        // Evitar la reinicialización del autocompletado
                        let searchBox = $(`#search-${idUnico}`);
                        if (!searchBox.data("ui-autocomplete")) {
                            cargarAutocompletado(function(items) {
                                searchBox.autocomplete({
                                    source: function(request, response) {
                                        const input = request.term.toLowerCase().trim();
                                        const palabras = input.split(/\s+/);

                                        const resultados = items.filter(item => {
                                            const label = item.label.toLowerCase();
                                            return palabras.every(palabra => label.includes(palabra));
                                        });
                                        response(resultados);
                                    },
                                    minLength: 3,
                                    autoFocus: true,
                                    focus: function() {
                                        return false;
                                    },
                                    select: function(event, ui) {
                                        CargarProductos(ui.item.cod, null, $(tablaId).DataTable());
                                        return false;
                                    }
                                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                                    let res = item.cantidad.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                    return $("<li>").append(
                                        "<div>" + item.label + "<strong class='large-text'>CANTIDAD: " +
                                        res + "</strong></div>"
                                    ).appendTo(ul);
                                };
                            });
                        }
                    }
                });

                $('#tblDetalleFab tbody').on('click', 'td.dt-control', function() {
                    let tr = $(this).closest('tr');
                    let row = tblDetalleFab.row(tr);
                    let rowData = row.data();
                    let idUnico = rowData[0]; // Se asume que el ID del producto está en la segunda columna
                    let tablaId = `#tbl${idUnico}`;
                    id_prod_fab = idUnico;
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        if (!tr.hasClass('loaded')) {
                            row.child(format(idUnico)).show();
                            tr.addClass('loaded');
                            let searchBox = $(`#search-${idUnico}`);
                            // Evitar reinicialización del autocompletado
                            if (!searchBox.data("ui-autocomplete")) {
                                cargarAutocompletado(function(items) {
                                    searchBox.autocomplete({
                                        source: function(request, response) {
                                            const input = request.term.toLowerCase().trim();
                                            const palabras = input.split(/\s+/);

                                            const resultados = items.filter(item => {
                                                const label = item.label.toLowerCase();
                                                return palabras.every(palabra => label.includes(palabra));
                                            });

                                            response(resultados);
                                        },
                                        minLength: 3,
                                        autoFocus: true,
                                        focus: function() {
                                            return false;
                                        },
                                        select: function(event, ui) {
                                            CargarProductos(ui.item.cod, null, $(tablaId).DataTable(), id_prod_fab);
                                            return false;
                                        }
                                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                                        console.log(item);
                                        let res = item.cantidad.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                        return $("<li>").append(
                                            `<div>${item.label} <strong class='large-text'>CANTIDAD: ${res}</strong></div>`
                                        ).appendTo(ul);
                                    };
                                });
                            }

                            let dataTable = $(tablaId).DataTable({
                                dom: "t",
                                paging: false,
                                ordering: false,
                                autoWidth: false,
                                destroy: true, // Asegura que la tabla se reinicializa correctamente 
                                ajax: {
                                    url: "controllers/inventario.controlador.php",
                                    dataSrc: "",
                                    type: "POST",
                                    data: function(d) {
                                        d.accion = 11;
                                        d.id_producto_fab = id_prod_fab;
                                    },
                                    dataSrc: function(json) {
                                        // console.log("✅ Datos cargados:", json);
                                        $(tablaId).data('originalData', json); // Guardamos los datos originales
                                        // console.log($(tablaId).data('originalData'));

                                        return json;
                                    }
                                    // success: function(data) {
                                    //     // Guardar los datos originales
                                    //     // $(tablaId).data('originalData', data); // Guardamos los datos originales
                                    // }
                                },
                                // xhr: function(settings, json) {
                                //     console.log("✅ Datos originales guardados:", json);
                                //     $(tablaId).data('originalData', json); // Guardamos los datos originales
                                //     console.log($(tablaId).data('originalData'));

                                // },
                                columns: [{
                                        title: "N°",
                                        data: null,
                                        render: function(data, type, row, meta) {
                                            return meta.row + 1;
                                        }
                                    },
                                    {
                                        title: "CODIGO",
                                        data: "codigo",
                                        visible: false
                                    },
                                    {
                                        title: "DESCRIPCION",
                                        data: "descripcion"
                                    },
                                    {
                                        title: "UND",
                                        data: "unidad",
                                        className: "text-center"
                                    },

                                    // {
                                    //     title: "SALIDA",
                                    //     data: "cantidad_salida",
                                    //     className: "text-center",
                                    //     render: function(data, type, row) {
                                    //         return `<input type="text" style="width:102px;border-bottom-width:2px;margin:auto;font-size:1.4rem"  
                                    //         class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" 
                                    //         onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                                    //         oninput="validarNumber(this,/[^0-9.]/g)" value="${data}">`;
                                    //     }
                                    // },
                                    {
                                        title: "SALIDA",
                                        data: "cantidad_salida",
                                        className: "text-center",
                                        render: function(data, type, row) {
                                            return `<input type="text" style="width:102px;border-bottom-width:2px;margin:auto;font-size:1.4rem"  
                                            class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" 
                                            onpate="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                                            oninput="validarNumber(this,/[^0-9.]/g)" value="${data}">`;
                                        }
                                    },
                                    {
                                        title: "ENTRADA",
                                        data: "retorno",
                                        className: "text-center",
                                        render: function(data, type, row) {
                                            data = (data === '-') ? '' : data;
                                            return `<input type="text" style="width:102px;border-bottom-width:2px;margin:auto;font-size:1.4rem"  
                                            class="form-control text-center d-inline cantidad_retorno" inputmode="numeric" autocomplete="off" 
                                            onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                                            oninput="validarNumber(this,/[^0-9.]/g)" value="${data}">`;
                                        }
                                    },

                                    {
                                        title: "ACCIONES",
                                        data: null,
                                        className: "text-center",
                                        render: function(data, type, row) {
                                            return `<center>
                                    <span class='btnEliminaRowServer text-danger' style='cursor:pointer;' data-bs-toggle='tooltip' 
                                    data-bs-placement='top' title='Eliminar producto'> 
                                        <i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> 
                                        </i> </span>
                                    </center>`;
                                        }
                                    }
                                ],
                                createdRow: function(row, data, dataIndex) {
                                    $(row).attr('id', 'producto_' + data.codigo);
                                }
                            });
                        } else {
                            row.child.show();
                        }
                        tr.addClass('shown');
                    }
                });

                // $('#tblDetalleFabEntrada tbody').on('click', 'td.dt-control', function() {
                //     let tr = $(this).closest('tr');
                //     let row = tblDetalleFabEntrada.row(tr);
                //     let rowData = row.data();
                //     let idUnico = rowData[9]; // Se asume que el ID del producto está en la segunda columna
                //     let tablaId = `#tbl${idUnico}`;
                //     id_prod_fab = idUnico;
                //     if (row.child.isShown()) {
                //         row.child.hide();
                //         tr.removeClass('shown');
                //     } else {
                //         if (!tr.hasClass('loaded')) {
                //             row.child(format(idUnico, true)).show();
                //             tr.addClass('loaded');
                //             let dataTable = $(tablaId).DataTable({
                //                 dom: "t",
                //                 paging: false,
                //                 ordering: false,
                //                 autoWidth: false,
                //                 destroy: true, // Asegura que la tabla se reinicializa correctamente 
                //                 ajax: {
                //                     url: "controllers/inventario.controlador.php",
                //                     dataSrc: "",
                //                     type: "POST",
                //                     data: function(d) {
                //                         d.accion = 11;
                //                         d.id_producto_fab = id_prod_fab;
                //                     },
                //                     dataSrc: function(json) {
                //                         console.log("✅ Datos cargados:", json);
                //                         $(tablaId).data('originalData', json); // Guardamos los datos originales
                //                         // console.log($(tablaId).data('originalData'));

                //                         return json;
                //                     }
                //                 },
                //                 columns: [{
                //                         title: "N°",
                //                         data: null,
                //                         render: function(data, type, row, meta) {
                //                             return meta.row + 1;
                //                         }
                //                     },
                //                     {
                //                         title: "CODIGO",
                //                         data: "codigo",
                //                         visible: false
                //                     },
                //                     {
                //                         title: "DESCRIPCION",
                //                         data: "descripcion"
                //                     },
                //                     {
                //                         title: "UND",
                //                         data: "unidad",
                //                         className: "text-center"
                //                     },
                //                     {
                //                         title: "SALIDA",
                //                         data: "cantidad_salida",
                //                         className: "text-center",
                //                     },
                //                     {
                //                         title: "ENTRADA",
                //                         data: "retorno",
                //                         className: "text-center",
                //                         render: function(data, type, row) {
                //                             data = (data === '-') ? '' : data;
                //                             return `<input type="text" style="width:102px;border-bottom-width:2px;margin:auto;font-size:1.4rem"  
                //                             class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" 
                //                             onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                //                             oninput="validarNumber(this,/[^0-9.]/g)" value="${data}">`;
                //                         }
                //                     },
                //                 ],
                //             });
                //         } else {
                //             row.child.show();
                //         }
                //         tr.addClass('shown');
                //     }
                // });

                $('#tblOut').on('keydown', 'input.cantidad', moveFocusOnTab);

                $(document).on('click', '.btnEliminaRow', function() {
                    let tabla = $(this).closest('table').DataTable(); // Obtener la DataTable correspondiente
                    tabla.row($(this).closest('tr')).remove().draw(); // Eliminar la fila seleccionada
                });

                $(document).on('click', '.btnEliminaRowFab', function() {
                    const e = obtenerFila(this, tblDetalleFab)
                    const row = $(this);
                    const id = e[0];
                    let src = new FormData();
                    src.append('accion', 2);
                    src.append('id', id);
                    confirmarEliminar('este', 'producto fabricado y los productos utilizados', function(res) {
                        if (res) {
                            confirmarAccion(src, 'fabricacion', null, '', function(res) {
                                if (res) {
                                    cargarAutocompletado();
                                    // console.log('datos', 'eliminando ...');
                                    tblDetalleFab.row(row.closest('tr')).remove().draw();
                                }
                                // Eliminar la fila seleccionada
                            })
                        }
                    });
                });

                $(document).on('click', '.btnEliminaRowServer', function() {
                    let tablaUni = $(this).closest('table').DataTable(); // Obtener la DataTable correspondiente
                    const e = obtenerFila(this, tablaUni)
                    const row = $(this);
                    const id_row_d = e['id'];
                    console.log('id', e);
                    console.log('id', id_row_d);
                    // if(id_row_d == undefined) {
                    //     tablaUni.row($(this).closest('tr')).remove().draw();
                    //     return;
                    // }
                    let src = new FormData();
                    src.append('accion', 4);
                    src.append('id', id_row_d);
                    confirmarEliminar('este', 'producto utilizado', function(res) {
                        if (res) {
                            confirmarAccion(src, 'fabricacion', null, '', function(res) {
                                if (res) {
                                    cargarAutocompletado();
                                    tablaUni.row(row.closest('tr')).remove().draw();
                                }
                            })
                        }
                    });
                });

                // const clearButton = document.getElementById("clearButton"),
                //     clearButtonFab = document.getElementById("clearButtonFab"),
                //     clearButtonEntrada = document.getElementById("clearButtonEntrada");
                // nro_orden.addEventListener("mouseenter", function() {
                //     if (nro_orden.readOnly) {
                //         clearButton.style.display = "block";
                //     }
                // });

                // // Ocultar la X cuando el mouse sale del input
                // nro_orden.addEventListener("mouseleave", function() {
                //     clearButton.style.display = "none";
                // });
                // clearButton.addEventListener("click", function() {
                //     nro_orden.readOnly = false; // Desbloquea el input
                //     nro_orden.value = "";
                //     nro_orden.focus();
                //     clearButton.style.display = "none"; // Oculta la X
                // });

                // clearButtonEntrada.addEventListener("click", function() {
                //     nro_ordenEntrada.readOnly = false; // Desbloquea el input
                //     nro_ordenEntrada.value = "";
                //     nro_ordenEntrada.focus()
                //     clearButtonEntrada.style.display = "none"; // Oculta la X
                // });

                // clearButtonFab.addEventListener("click", function() {
                //     nro_ordenFab.readOnly = false; // Desbloquea el input
                //     nro_ordenFab.value = "";
                //     nro_ordenFab.focus()
                //     clearButtonFab.style.display = "none"; // Oculta la X
                // });

                cargarAutocompletado(function(items) {

                    $(inputauto).autocomplete({
                        // source: items,
                        autoFocus: true,
                        minLength: 3,
                        source: function(request, response) {
                            const input = request.term.toLowerCase().trim();
                            const palabras = input.split(/\s+/);

                            const resultados = items.filter(item => {
                                const label = item.label.toLowerCase();
                                return palabras.every(palabra => label.includes(palabra));
                            });

                            response(resultados);
                        },
                        focus: function() {
                            return false;
                        },
                        select: function(event, ui) {
                            CargarProductos(ui.item.cod);
                            valid_orden = true;
                            return false;
                        },
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                        let res = item.cantidad.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return $("<li>").append(
                            "<div>" + item.label + "<strong class='large-text'>CANTIDAD: " +
                            res + "</strong></div>"
                        ).appendTo(ul);
                    };
                });

                cargarAutocompletado(function(items) {
                    items_orden = items;
                    $(nro_orden).autocomplete({
                        source: items,
                        minLength: 1,
                        autoFocus: true,
                        focus: function() {
                            return false;
                        },
                        select: function(event, ui) {
                            nro_orden.readOnly = true;
                            id_orden_guia_salida = ui.item.cod;
                            nro_orden.parentNode.querySelector(".ten").style.display = "none";
                            clearButton.style.display = "block";
                            nro_orden.focus();
                        },
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                        return $("<li>").append(
                            "<div>" + item.label + "<div class='d-flex justify-content-between align-items-center'><strong class='large-text'>ESTADO: " +
                            item.cantidad + " </strong><span>AÑO: " + item.anio + "</span></div></div>"
                        ).appendTo(ul);
                    };

                    $(nro_ordenEntrada).autocomplete({
                        source: items,
                        minLength: 1,
                        autoFocus: true,
                        focus: function() {
                            return false;
                        },
                        // open: function() {
                        //     // Seleccionar automáticamente el primer elemento al abrir
                        //     $(this).autocomplete("widget").children().first().find(".ui-menu-item-wrapper").addClass("ui-state-active");
                        // },
                        select: function(event, ui) {
                            nro_ordenEntrada.readOnly = true;
                            id_orden_guia_entrada = ui.item.cod;
                            clearButtonEntrada.style.display = "block";
                        },
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                        // let res = item.cantidad.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return $("<li>").append(
                            "<div>" + item.label + "<div class='d-flex justify-content-between align-items-center'><strong class='large-text'>ESTADO: " +
                            item.cantidad + " </strong><span>AÑO: " + item.anio + "</span></div></div>"
                        ).appendTo(ul);
                    };

                    $(nro_ordenFab).autocomplete({
                        source: items,
                        minLength: 1,
                        autoFocus: true,
                        focus: function() {
                            return false;
                        },
                        select: function(event, ui) {
                            nro_ordenFab.readOnly = true;
                            id_orden_guia_fab = ui.item.cod;
                            clearButtonFab.style.display = "block";
                        },
                    }).data("ui-autocomplete")._renderItem = function(ul, item) {
                        // let res = item.cantidad.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return $("<li>").append(
                            "<div>" + item.label + "<div class='d-flex justify-content-between align-items-center'><strong class='large-text'>ESTADO: " +
                            item.cantidad + " </strong><span>AÑO: " + item.anio + "</span></div></div>"
                        ).appendTo(ul);
                    };
                }, null, 'orden', 6)

                let action;
                const tabs = document.querySelectorAll('.tabs input'),
                    btnCancelarTrans = document.getElementById('Cancelar'),
                    div_fecha = document.getElementById('div_fecha'),
                    div_fab = document.getElementById('div_fab'),
                    div_orden = document.getElementById('div_orden'),
                    div_proveedor = document.getElementById('div_proveedor'),
                    div_return = document.getElementById('div_return'),
                    div_retorno = document.getElementById('div_retorno'),
                    div_nroguia = document.getElementById('div_nroguia'),
                    div_conductor = document.getElementById('div_conductor'),
                    div_productos = document.getElementById('div_productos'),
                    div_nrofactura = document.getElementById('div_nrofac');
                // const div_motivo = document.getElementById('div_motivo');
                const div_person = document.getElementById('card_person'),
                    div_prod_fab = document.getElementById('div_prod_fab'),
                    card_nro_guiaFab = document.getElementById('card_nro_guiaFab'),
                    card_nro_guia = document.getElementById('card_nro_guia'),
                    card_nro_guiaEntrada = document.getElementById('card_nro_guiaE'),
                    card_nro_fac = document.getElementById('card_nro_fac'),
                    card_conductor = document.getElementById('card_conductor'),
                    card_conductorEntrada = document.getElementById('card_conductorE');

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
                        realizarRegistro(tblCompra, formData, clases, 1, 'productos', function(r) {
                            if (r) {
                                form_guia.classList.remove('was-validated');
                                limpiar();
                            }
                        });
                    } else if (selectedTab === '2') {
                        let elementosAValidar = [fecha, nro_guia, cboDespachado, cboConductor];
                        let isValid = true;
                        elementosAValidar.forEach(function(elemento) {
                            if (!elemento.checkValidity()) {
                                isValid = false;
                                form_guia.classList.add('was-validated');
                            }
                        });

                        if (!nro_orden.readOnly && nro_orden.value.length > 0) {
                            nro_orden.parentNode.querySelector(".ten").style.display = "block";
                            return;
                        }

                        if (!isValid) {
                            return;
                        }

                        let clases = ['cantidad'];
                        formData.append('orden', id_orden_guia_salida);
                        formData.append('nro_guia', nro_guia.value);
                        formData.append('conductor', cboConductor.value);
                        formData.append('despachado', cboDespachado.value);
                        formData.append('responsable', cboResponsable.value);
                        formData.append('motivo', motivo.value.trim().toUpperCase());
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 2);
                        dropzone.getAcceptedFiles().forEach((file, index) => {
                            if (!file.isExisting) {
                                formData.append(`imagenes[${index}]`, file, file.name);
                            }
                        });
                        realizarRegistro(tblOut, formData, clases, 1, 'productos', function(r) {
                            if (r) {
                                dropzone.removeAllFiles(false);
                                form_guia.classList.remove('was-validated');
                                limpiar();
                                cargarAutocompletado(function(items) {
                                    items_orden = items;
                                    $('#nro_orden').autocomplete("option", "source", items);
                                    $('#nro_ordenEntrada').autocomplete("option", "source", items);
                                    $('#nro_ordenFab').autocomplete("option", "source", items);
                                }, null, 'orden', 6)
                            }
                        });
                    } else if (selectedTab === '3') {
                        let elementosAValidar = [fecha_retorno];
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
                        formData.append('orden', id_orden_guia_entrada);
                        formData.append('nro_guia', nro_guiaEntrada.value);
                        formData.append('conductor', cboConductorEntrada.value);
                        formData.append('responsable', cboResponsable.value);
                        formData.append('despachado', cboDespachado.value);
                        formData.append('motivo', motivo.value.trim().toUpperCase());
                        formData.append('fecha_retorno', fecha_retorno.value);
                        formData.append('fecha', fecha.value);
                        formData.append('accion', 8);
                        realizarRegistro(tblIn, formData, clases, 1, 'productos', function(r) {
                            if (r) {
                                dropzone.removeAllFiles(false);
                                limpiar();
                                cargarAutocompletado(function(items) {
                                    items_orden = items;
                                    $('#nro_orden').autocomplete("option", "source", items);
                                    $('#nro_ordenEntrada').autocomplete("option", "source", items);
                                    $('#nro_ordenFab').autocomplete("option", "source", items);
                                }, null, 'orden', 6)
                            }
                        });
                    } else if (selectedTab === '4') {
                        let elementosAValidar = [fecha, nro_orden, nro_guia, cboDespachado, cboResponsable, cboConductor];
                        let isValid = true;
                        elementosAValidar.forEach(function(elemento) {
                            console.log(elemento);
                            if (!elemento.checkValidity()) {
                                isValid = false;
                                form_guia.classList.add('was-validated');
                            }
                        });
                        if (!isValid) {
                            return;
                        }
                        formData.append('id_boleta', id_boleta);
                        formData.append('orden', id_orden_guia_salida);
                        // formData.append('cliente', cboClientes.value);
                        formData.append('nro_guia', nro_guia.value);
                        formData.append('conductor', cboConductor.value);
                        formData.append('despachado', cboDespachado.value);
                        formData.append('responsable', cboResponsable.value);
                        formData.append('fecha', fecha.value);
                        formData.append('motivo', motivo.value.trim().toUpperCase());
                        formData.append('accion', 4);
                        dropzone.getAcceptedFiles().forEach((file, index) => {
                            if (!file.isExisting) {
                                formData.append(`imagenes[${index}]`, file, file.name);
                            }
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
                                    isSuccess ? "fa-check" : "fa-xmark",
                                    r.m);

                                if (isSuccess) {
                                    tblDetalleSalida.clear().draw();
                                    tabla ? tabla.ajax.reload(null, false) : ''
                                    cargarAutocompletado();
                                    btnCancelarTrans.click();
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
                                    isSuccess ? "fa-check" : "fa-xmark",
                                    r.m);
                                if (isSuccess) {
                                    tblDetalleSalida.clear().draw();
                                    tabla ? tabla.ajax.reload(null, false) : ''
                                    cargarAutocompletado();
                                }
                            }
                        });
                    } else if (selectedTab === '6') {
                        let clases = ['retorno'];
                        formData.append('boleta', id_boleta);
                        formData.append('fecha_retorno', fecha_retorno.value);
                        formData.append('nro_guia', nro_guia.value)
                        formData.append('accion', 3);
                        realizarRegistro(tblReturn, formData, clases, 0, 'productos', function(response) {
                            if (response) {
                                btnCancelarTrans.click();
                            }
                        });
                    } else if (selectedTab === '7') {
                        let elementosAValidar = [fecha, nro_ordenFab, nro_guiaFab];
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
                        tblProdFab.rows().every(function() {
                            let row = this;
                            let tr = $(row.node());
                            let rowData = row.data(); // Obtiene los datos de la fila
                            let idUnico = rowData[0];
                            row.child.show(); // Simplemente la mostramos si ya fue cargada
                        });
                        // Ahoa obtenemos los datos
                        let datosPrincipales = [];
                        let error = false;
                        tblProdFab.rows().every(function() {
                            let rowData = this.data();
                            let idUnico = rowData[0];
                            let tablaSecundariaId = `#tbl${idUnico}`;
                            let filaPrincipal = {
                                id: idUnico,
                                cantidad: $(this.node()).find('.cantidad').val(),
                                unidad: $(this.node()).find('.id_unidad').val(),
                                descripcion: $(this.node()).find('.descripcion').val().trim().toUpperCase(),
                                productos: []
                            };
                            if ($.fn.dataTable.isDataTable(tablaSecundariaId)) {
                                let tablaSecundaria = $(tablaSecundariaId).DataTable();
                                tablaSecundaria.rows().every(function() {
                                    let secData = this.data();
                                    let cantidad = parseFloat($(this.node()).find('.cantidad').val());
                                    let entrada = parseFloat($(this.node()).find('.entrada').val());

                                    if (cantidad < entrada) {
                                        mostrarToast('danger', 'Error', 'fa-xmark',
                                            'La cantidad utilizada no puede ser mayor a la de entrada para el producto "' +
                                            secData[4] + '"');
                                        error = true; // marcar error
                                        return false; // salir del each interno
                                    }
                                    filaPrincipal.productos.push({
                                        codigo: secData[1],
                                        cantidad: cantidad,
                                        entrada: entrada
                                    });
                                });
                            }
                            if (error) return false; // salir del each externo si hubo error
                            datosPrincipales.push(filaPrincipal);
                        });

                        if (error) return;
                        formData.append('datos', JSON.stringify(datosPrincipales));
                        formData.append('orden', id_orden_guia_fab);
                        formData.append('nro_guia', nro_guiaFab.value);
                        formData.append('tras', isTrasFab.checked);
                        formData.append('conductor', cboConductor.value);
                        formData.append('despachado', cboDespachado.value);
                        formData.append('responsable', cboResponsable.value);
                        formData.append('fecha', fecha.value);
                        formData.append('fecha_retorno', fecha_retorno.value);
                        formData.append('motivo', motivo.value.trim().toUpperCase());
                        formData.append('accion', 11);
                        dropzone.getAcceptedFiles().forEach((file, index) => {
                            if (!file.isExisting) {
                                formData.append(`imagenes[${index}]`, file, file.name);
                            }
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
                                    isSuccess ? "fa-check" : "fa-xmark", r.m);
                                if (isSuccess) {
                                    tblProdFab.clear().draw();
                                    tabla ? tabla.ajax.reload(null, false) : ''
                                    cargarAutocompletado();
                                    limpiar();
                                    cargarAutocompletado(function(items) {
                                        items_orden = items;
                                        $('#nro_orden').autocomplete("option", "source", items);
                                        $('#nro_ordenEntrada').autocomplete("option", "source", items);
                                        $('#nro_ordenFab').autocomplete("option", "source", items);
                                    }, null, 'orden', 6)
                                }
                            }
                        });
                    } else if (selectedTab === '8') {
                        let elementosAValidar = [fecha, fecha_retorno, nro_ordenFab, nro_guiaFab];
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

                        tblDetalleFab.rows().every(function() {
                            let row = this;
                            let tr = $(row.node());
                            let rowData = row.data();
                            let idUnico = rowData[0];
                            row.child.show();
                        });

                        // let datosPrincipales = [];
                        let datosModificados = [];
                        // Recorre cada fila de la tabla principal (tblDetalleFab)
                        tblDetalleFab.rows().every(function() {
                            let row = this;
                            let tr = $(row.node());
                            let rowData = row.data();
                            let idUnico = rowData[0]; // Asumimos que el ID está en la primera columna
                            let tablaSecundariaId = `#tbl${idUnico}`;
                            console.log('tablaSecundariaId', tablaSecundariaId)
                            // Crear el objeto principal para cada fila
                            let filaPrincipal = {
                                id: idUnico,
                                cantidad: $(this.node()).find('.cantidad').val(),
                                unidad: $(this.node()).find('.id_unidad').val(),
                                descripcion: $(this.node()).find('.descripcion').val().trim().toUpperCase(),
                                retorno: $(this.node()).find('.retorno').val(),
                                productos: [],
                            };

                            // Obtener los datos originales de la tabla secundaria
                            if ($.fn.dataTable.isDataTable(tablaSecundariaId)) {
                                let tablaSecundaria = $(tablaSecundariaId).DataTable();
                                let datosOriginales = $(tablaSecundariaId).data('originalData'); // Datos originales de la tabla secundaria
                                console.log('datos Originales', datosOriginales)
                                tablaSecundaria.rows().every(function() {
                                    let secData = this.data();
                                    console.log('secData', secData);
                                    let id_prod = secData["id_producto"];
                                    let cantidadModificada = parseFloat($(this.node()).find('.cantidad').val());
                                    let entrada_modificada = parseFloat($(this.node()).find('.cantidad_retorno').val());
                                    console.log('cantidadModificada', cantidadModificada)
                                    console.log('retorno_modificada', entrada_modificada)
                                    filaPrincipal.productos.push({
                                        codigo: secData['id'],
                                        cantidad_old: secData['cantidad_salida'],
                                        entrada_old: secData['retorno'],
                                        cantidad: cantidadModificada,
                                        retorno: entrada_modificada,
                                        id_producto: id_prod,
                                    });

                                });
                            }
                            datosModificados.push(filaPrincipal);
                        });
                        formData.append('datos', JSON.stringify(datosModificados));
                        formData.append('orden', id_orden_guia_fab);
                        formData.append('id_boleta', id_boleta_fab);
                        formData.append('tras', isTrasFab.checked);
                        formData.append('nro_guia', nro_guiaFab.value);
                        formData.append('conductor', cboConductor.value);
                        formData.append('despachado', cboDespachado.value);
                        formData.append('responsable', cboResponsable.value);
                        formData.append('fecha', fecha.value);
                        formData.append('fecha_retorno', fecha_retorno.value);
                        formData.append('motivo', motivo.value.trim().toUpperCase());
                        formData.append('accion', 12);
                        dropzone.getAcceptedFiles().forEach((file, index) => {
                            if (!file.isExisting) {
                                formData.append(`imagenes[${index}]`, file, file.name);
                            }
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
                                    isSuccess ? "fa-check" : "fa-xmark",
                                    r.m);
                                if (isSuccess) {
                                    tblDetalleFab.clear().draw();
                                    tabla ? tabla.ajax.reload(null, false) : '';
                                    cargarAutocompletado();
                                    limpiar();
                                }
                            }
                        });
                    }
                });

                tabs.forEach(tab => {
                    tab.addEventListener('change', function() {
                        selectedTab = this.value;
                        form_guia.classList.remove('was-validated');
                        const selectedForm = document.getElementById(`form-${selectedTab}`);
                        const formContainers = document.querySelectorAll('.form-container');

                        formContainers.forEach(container => {
                            container.style.display = 'none';
                        });

                        if (selectedForm) {
                            selectedForm.style.display = 'block';
                        }
                        if (selectedTab === '1' || selectedTab === '5') {
                            div_orden.style.display = 'none';
                            div_fecha.style.display = 'block'
                            div_productos.style.display = 'flex';
                            div_proveedor.style.display = 'block';
                            div_conductor.style.display = 'none';
                            div_retorno.style.display = 'none';
                            div_nroguia.style.display = 'none';
                            card_nro_guia.style.display = 'none';
                            card_nro_guiaEntrada.style.display = 'none';
                            card_conductor.style.display = 'none';
                            card_conductorEntrada.style.display = 'none';
                            div_return.style.display = 'none';
                            div_person.style.display = 'none';
                            div_fab.style.display = 'none';
                            div_nrofactura.style.display = 'block';
                            card_nro_guiaFab.style.display = 'none';
                            card_nro_fac.style.display = 'block';
                            div_prod_fab.style.display = 'none';
                            if (selectedTab === '1') {
                                btnCancelarTrans.style.display = 'none'
                            } else {
                                btnCancelarTrans.style.display = 'block'
                            }
                        } else if (selectedTab === '2' || selectedTab === '4') {
                            div_orden.style.display = 'block';
                            div_proveedor.style.display = 'none';
                            div_fecha.style.display = 'block'
                            div_productos.style.display = 'flex';
                            div_return.style.display = 'none';
                            div_conductor.style.display = 'block';
                            div_retorno.style.display = 'none';
                            div_nroguia.style.display = 'block';
                            div_fab.style.display = 'none';
                            div_nrofactura.style.display = 'none';
                            div_person.style.display = 'block';
                            card_nro_guia.style.display = 'block';
                            card_nro_guiaEntrada.style.display = 'none';
                            card_conductor.style.display = 'block';
                            card_conductorEntrada.style.display = 'none';
                            card_nro_guiaFab.style.display = 'none';
                            div_prod_fab.style.display = 'none';
                            // div_check_fab.style.display = 'none';

                            if (selectedTab === '2') {
                                btnCancelarTrans.style.display = 'none'
                            } else {
                                btnCancelarTrans.style.display = 'block'
                            }

                        } else if (selectedTab === '3' || selectedTab === '6') {
                            div_orden.style.display = 'none';
                            div_proveedor.style.display = 'none';
                            div_return.style.display = 'block'
                            div_nrofactura.style.display = 'none';
                            div_person.style.display = 'block';
                            div_retorno.style.display = 'block';
                            card_nro_guia.style.display = 'none';
                            card_conductor.style.display = 'none';
                            card_conductorEntrada.style.display = 'block';
                            div_fab.style.display = 'none';
                            div_conductor.style.display = 'block';
                            card_nro_guiaFab.style.display = 'none';
                            div_prod_fab.style.display = 'none';
                            // div_check_fab.style.display = 'none';
                            // div_nroguia.style.display = 'none';
                            if (selectedTab === '3') {
                                btnCancelarTrans.style.display = 'none';
                                div_fecha.style.display = 'block';
                                div_productos.style.display = 'flex';
                                card_nro_guiaEntrada.style.display = 'none';
                                div_nroguia.style.display = 'none';
                            } else {
                                btnCancelarTrans.style.display = 'block';
                                div_productos.style.display = 'none';
                                card_nro_guiaEntrada.style.display = 'block';
                                div_nroguia.style.display = 'block';
                                div_fecha.style.display = 'none';
                            }
                        } else if (selectedTab === '7' || selectedTab === '8' || selectedTab === '9') {
                            div_orden.style.display = 'none';
                            div_fecha.style.display = 'block';
                            div_proveedor.style.display = 'none';
                            div_return.style.display = 'none';
                            div_nroguia.style.display = 'none';
                            div_nrofactura.style.display = 'none';
                            div_person.style.display = 'block';
                            div_retorno.style.display = 'block';
                            card_nro_guia.style.display = 'none';
                            card_conductor.style.display = 'none';
                            card_conductorEntrada.style.display = 'none';
                            div_conductor.style.display = 'none';
                            div_fab.style.display = 'block';
                            div_productos.style.display = 'none';
                            card_nro_fac.style.display = 'none';
                            card_nro_guiaFab.style.display = 'block';
                            div_prod_fab.style.display = 'block';
                            if (selectedTab === '8' || selectedTab === '9') {
                                btnCancelarTrans.style.display = 'block';
                            } else {
                                btnCancelarTrans.style.display = 'none';
                            }
                        }
                    });
                });

                function CargarProductos(p = "", barras = false, tablaUnica = "", id_prod_fab = "") {
                    function manejarRespuesta(r, tblDetalle, tabla) {
                        tblDetalle.ajax.reload(null, false);
                        tabla.ajax.reload(null, false);
                        cargarAutocompletado();
                        inputauto.value = '';
                        let isSuccess = r.status === 'success';
                        mostrarToast(
                            r.status,
                            isSuccess ? "Completado" : "Error",
                            isSuccess ? "fa-check" : "fa-xmark",
                            r.m
                        );
                    }

                    function actualizarCantidad(row) {
                        audio.play();
                        if (barras) inputBarras.disabled = true;
                        var cantidadInput = row.node().querySelector('.cantidad');
                        cantidadInput.value = parseFloat(cantidadInput.value) + 1;
                        audio.onended = function() {
                            if (barras) {
                                inputBarras.disabled = false;
                                inputBarras.focus();
                            }
                            $(inputBarras).val("");
                        };
                    }

                    function agregarFila(respuesta, tabla, ids = respuesta['id']) {
                        let nuevaFila = [
                            '',
                            ids,
                            `<input type="text" style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.4rem" 
                            class="form-control text-center d-inline cantidad" inputmode="numeric" autocomplete="off" 
                            onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                            oninput="validarNumber(this,/[^0-9.]/g)" value="${respuesta['cantidad']}">`,
                            respuesta['nombre']
                        ];
                        if (tabla === tblCompra) {
                            nuevaFila.push('$<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline precio" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g,false,5)" value="0">');
                            nuevaFila.push('<span class="total">$0.00</span>', '<span class="iva">$0.00</span>', '<span class="precio_final">$0.00</span>');
                        }

                        nuevaFila.push(
                            respuesta['descripcion'],
                            `<input type="text" style="width:82px;border-bottom-width:2px;margin:auto;font-size:1.4rem" 
                            class="form-control text-center d-inline entrada" inputmode="numeric" autocomplete="off" 
                            onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" 
                            oninput="validarNumber(this,/[^0-9.]/g)" value="">`,
                            `<center>
                            <span class='btnEliminaRow text-danger' style='cursor:pointer;' data-bs-toggle='tooltip' 
                            data-bs-placement='top' title='Eliminar producto'> 
                                <i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> 
                                </i> </span>
                            </center>`
                        );

                        tabla.row.add(nuevaFila).node().id = "producto_" + respuesta['codigo'];
                        tabla.draw(false);
                        audio.onplay = function() {
                            $(inputauto).val("");
                        };
                        audio.onended = function() {
                            if (barras) {
                                inputBarras.disabled = false;
                                inputBarras.focus();
                            }
                            $(inputBarras).val("");
                        };
                    }

                    if (selectedTab === '4') {
                        $.ajax({
                            url: "controllers/salidas.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 1,
                                'codigo': p,
                                'id_boleta': id_boleta
                            },
                            dataType: 'json',
                            success: function(r) {
                                manejarRespuesta(r, tblDetalleSalida, tabla);
                            }
                        });
                    } else if (selectedTab === '5') {
                        $.ajax({
                            url: "controllers/entradas.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 3,
                                'codigo': p,
                                'id_factura': id_boleta
                            },
                            dataType: 'json',
                            success: function(r) {
                                manejarRespuesta(r, tblDetalleCompra, tabla);
                            }
                        });
                    } else {
                        if (selectedTab == '1') {
                            let existingRow = tblCompra.row("#producto_" + p);
                            if (existingRow.any()) {
                                actualizarCantidad(existingRow);
                                return;
                            }
                        }
                        if (selectedTab == '2') {
                            let existingRowOut = tblOut.row("#producto_" + p);
                            if (existingRowOut.any()) {
                                actualizarCantidad(existingRowOut);
                                return;
                            }
                        }
                        if (selectedTab == '3') {
                            let existingRowIn = tblIn.row("#producto_" + p);
                            if (existingRowIn.any()) {
                                actualizarCantidad(existingRowIn);
                                return;
                            }
                        }
                        if (selectedTab == '7') {
                            let existingRowFab = tablaUnica.row("#producto_" + p);
                            if (existingRowFab.any()) {
                                actualizarCantidad(existingRowFab);
                                return;
                            }
                        }
                        if (selectedTab == '8') {
                            let existingRowFab = tablaUnica.row("#producto_" + p);
                            if (existingRowFab.any()) {
                                actualizarCantidad(existingRowFab);
                                return;
                            }
                        }
                        $.ajax({
                            url: "controllers/inventario.controlador.php",
                            method: "POST",
                            data: {
                                'accion': 4,
                                'id': p
                            },
                            dataType: 'json',
                            success: function(respuesta) {
                                if (respuesta) {
                                    audio.play();
                                    if (barras) inputBarras.disabled = true;
                                    if (selectedTab === '1') {
                                        agregarFila(respuesta, tblCompra);
                                    } else if (selectedTab === '2') {
                                        agregarFila(respuesta, tblOut);
                                    } else if (selectedTab === '3') {
                                        agregarFila(respuesta, tblIn)
                                    } else if (selectedTab === '7') {
                                        agregarFila(respuesta, tablaUnica);
                                    } else if (selectedTab === '8') {
                                        $.ajax({
                                            url: "controllers/fabricacion.controlador.php",
                                            method: "POST",
                                            data: {
                                                'accion': 3,
                                                'id_prod_fab': id_prod_fab,
                                                'id_producto': respuesta['id'],
                                                'id_boleta': id_boleta_fab
                                            },
                                            dataType: 'json',
                                            success: function(res) {
                                                if (res.status === 'success') {
                                                    console.log(res)
                                                    let nuevaFila = {
                                                        id: res.id,
                                                        codigo: respuesta['codigo'],
                                                        cantidad_salida: "1",
                                                        unidad: respuesta['nombre'], // Puedes cambiar el valor según lo necesites
                                                        descripcion: respuesta['descripcion'],
                                                        entrada: "0",
                                                        id_producto: respuesta['id']
                                                    };
                                                    // let nuevaFila = ['', respuesta.id, '', '1', '', ''];
                                                    tablaUnica.row.add(nuevaFila).node().id = "producto_" + respuesta['codigo'];
                                                    tablaUnica.draw(false);

                                                    mostrarToast(res.status, "Completado", "fa-check fa-lg", res.m)
                                                } else {
                                                    mostrarToast('danger', "Error", "fa-xmark fa-lg", res.m)

                                                }
                                            }
                                        });
                                    }
                                } else {
                                    // Manejar el caso en el que la respuesta sea falsa
                                }
                            }
                        });
                    }
                }

                $('#tblCompra').on('input keydown', '.cantidad, .precio', function() {
                    // Encuentra la fila del input cambiado
                    let $row = $(this).closest('tr');
                    // Obtiene los valores de cantidad y precio
                    let cantidad = parseFloat($row.find('.cantidad').val()) || 0;
                    let precio = parseFloat($row.find('.precio').val()) || 0;
                    // Calcula el total
                    let precio_total = cantidad * precio;
                    let iva = precio_total * iva_config / 100
                    let precio_final = precio_total + iva;
                    // Actualiza la columna del total
                    $row.find('.total').text('$' + precio_total.toFixed(2));
                    $row.find('.iva').text('$' + iva.toFixed(2));
                    $row.find('.precio_final').text('$' + precio_final.toFixed(2));
                });
                inputBarras.addEventListener("input", function(event) {
                    event.preventDefault();
                    let codigo = this.value;
                    if (codigo.length >= 5) {
                        CargarProductos(codigo, true)
                    }
                })
            });

            function realizarRegistro(table, formData, clases, producto = 1, header = 'productos', callback = null) {
                let count = 0;
                table.rows().eq(0).each(function(index) {
                    count = count + 1;
                });
                if (count > 0) {
                    var arr = [];
                    Swal.fire({
                        title: "¿Estás seguro que deseas guardar los datos?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sí, Guardar",
                        cancelButtonText: "Cancelar",
                        timer: 5000, // El SweetAlert2 se cerrará automáticamente después de 3 segundos (3000 milisegundos)
                        timerProgressBar: true,
                    }).then((result) => {
                        if (result.value) {
                            table.rows().eq(0).each(function(index) {
                                let row = table.row(index);
                                let data = row.data();
                                let id = data[producto];

                                let valores = clases.map(clase => {
                                    let inputElement = row.node().querySelector('.' + clase);
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
                                        isSuccess ? "fa-check" : "fa-xmark",
                                        r.m);

                                    if (isSuccess) {
                                        table.clear().draw();
                                    }
                                    tabla ? tabla.ajax.reload(null, false) : ''
                                    cargarAutocompletado();

                                    if (typeof callback === 'function') {
                                        callback(isSuccess);
                                    }
                                }
                            });
                        }
                    });
                } else {
                    mostrarToast('danger', "Error", "fa-xmark", 'No hay ' + header + ' en el listado');
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