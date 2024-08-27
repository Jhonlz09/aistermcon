<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Compras</title>
    <link href="assets/plugins/datatables-searchpanes/css/searchPanes.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-select/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
</head>

<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Compras</h1>
            </div>
            <?php if (isset($_SESSION["crear7"]) && $_SESSION["crear7"] === true) : ?>
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
                                <div class="col-auto col-p" style="padding-right: .3rem">
                                    <h3 class="card-title ">Listado de compras</h3>
                                </div>
                                <div class=" col col-sm-auto">
                                    <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                    </select>
                                </div>
                                <div class="col-sm-auto">
                                    <select name="cboMeses" id="cboMeses" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                        <option value="null">TODO</option>
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
                        <table id="tblEntradas" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">CÓDIGO</th>
                                    <th>CANTIDAD</th>
                                    <th>UND</th>
                                    <th class="text-center">P. UNIT.</th>
                                    <th class="text-center">P. TOT.</th>
                                    <th class="text-center">IVA</th>
                                    <th class="text-center">P. FINAL</th>
                                    <th>DESCRIPCION</th>
                                    <th></th>
                                    <th>PROVEEDOR</th>
                                    <th>NRO. FACTURA</th>
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
<script src="assets/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>
<script src="assets/plugins/datatables-searchpanes/js/dataTables.searchPanes.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-searchpanes/js/searchPanes.bootstrap4.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-select/js/dataTables.select.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-select/js/select.bootstrap4.min.js" type="text/javascript"></script>
<script>
    var mostrarCol = '<?php echo $_SESSION["editar7"] || $_SESSION["eliminar7"] ?>';
    var editar = '<?php echo $_SESSION["editar7"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar7"] ?>';
    var collapsedGroups = {};

    configuracionTable = {
        "responsive": true,
        "dom": 'Pt',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        searchPanes: {
            cascadePanes: true,
            columns: [8, 10, 11],
            initCollapsed: true,
            threshold: 0.8, // Ajusta este valor según tus necesidades
            dtOpts: {
                select: {
                    style: 'multiple'
                }
            },
        },
        paging: false, // Esto deshabilita la paginación
        rowGroup: {
            dataSrc: [9],
            startRender: function(rows, group) {
                var collapsed = !!collapsedGroups[group];

                rows.nodes().each(function(r) {
                    r.style.visibility = collapsed ? '' : 'collapse';
                });

                var groupText = '<div class="d-flex justify-content-between align-items-center" style="cursor:pointer"><strong style="padding-block:.4rem" class="pl-2" >' + group + ' (' + rows.count() + ')</strong><div class="txt-wrap-sm">' +
                    (editar ? '<button id="editE" style="font-size:1.55rem;padding-inline:.5rem!important" class="btn pt-0 pb-0 btn-row"><i class="fas fa-pen-to-square"></i></button> ' : '') +
                    (eliminar ? '<button id="eliE" style="font-size:1.4rem;padding-inline:.5rem!important" class="btn pt-0 pb-0 btn-row"><i class="fas fa-trash-can"></i></button>' : '') + '</div></div>';

                return $('<tr/>')
                    .append('<td colspan="9">' + groupText + '</td>') // Asegúrate de ajustar el colspan según el número de columnas en tu tabla
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
            // Aquí especificas el índice de la columna para agrupar (indexado desde 0).
        },
        columnDefs: [{
                targets: 0,
                data: 'fila',
                className: "text-center"
            },
            {
                targets: 2,
                className: "text-center",
                responsivePriority: 1
            },
            {
                targets: 3,
                className: "text-center",
                responsivePriority: 2
            },
            {
                targets: 4,
                "render": function(data, type, row) {
                    var numericValue = data.replace('$', '').trim();
                    // Convertir a número flotante y luego a cadena para eliminar ceros innecesarios
                    var formattedValue = parseFloat(numericValue).toString();
                    // Volver a agregar el símbolo '$'
                    return '$ ' + formattedValue;
                }
            },
            {
                targets: 9,
                visible: false,
            },
            {
                targets: 10,
                visible: false,
            },
            {
                targets: 11,
                visible: false,
            },
        ],
        "preDrawCallback": function(settings) {
            // Guardar la posición del scroll antes de redibujar
            console.log("Guardando posición del scroll:", $(window).scrollTop());
            scrollPosition = $(window).scrollTop();
        },
        "drawCallback": function(settings) {
            // Restaurar la posición del scroll después de redibujar
            setTimeout(function() {
                console.log("Restaurando posición del scroll:", scrollPosition);
                $(window).scrollTop(scrollPosition);
            }, 3);
        }
    }

    $('#tblEntradas tbody').on('click', 'tr.dtrg-start', function() {
        if ($(event.target).closest('.txt-wrap-sm').length === 0) {
            var windowScrollTop = $(window).scrollTop();
            var tableScrollTop = $('#tblEntradas_wrapper').scrollTop();
            var name = $(this).data('name');
            collapsedGroups[name] = !collapsedGroups[name];
            tabla.draw(false);
            $(window).scrollTop(windowScrollTop);
            $('#tblEntradas_wrapper').scrollTop(tableScrollTop);
        }
    });

    $(document).ready(function() {
        let anio = year;
        let mes = month;
        if (!$.fn.DataTable.isDataTable('#tblEntradas')) {
            tabla = $("#tblEntradas").DataTable({
                "ajax": {
                    "url": "controllers/entradas.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.anio = anio;
                        data.mes = mes;
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

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('e', JSON.stringify(tablaData));
            });
        }

        let accion = 0;
        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            btnNuevo = document.getElementById('btnNuevo');

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        })
        setChange(cboAnio, anio);

        $(cboMeses).select2({
            minimumResultsForSearch: -1,
            width: 'calc(100% + .4vw)',
            data: datos_meses,
        });
        setChange(cboMeses, mes);

        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            anio = a
            if (cboMeses.value == 'null') {
                mes = null;
            } else {
                mes = cboMeses.value;
            }
            tabla.ajax.reload();
        });

        $(cboMeses).on("change", function() {
            let m = this.value;
            if (m == mes) {
                return;
            }
            if (m == 'null') {
                mes = null;
            } else {
                mes = m;
            }
            anio = cboAnio.options[cboAnio.selectedIndex].text;
            tabla.ajax.reload();
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                const entrada = document.getElementById('radio-1');
                first_control.click();
                entrada.click();
            });
        }

        $('#tblEntradas').on('click', '#editE', function() {
            row = tabla.row($(this).closest('tr').next()).data();
            id_boleta = row[12];
            const fecha_id = row[13];
            const proveedor_id = row[14];
            const factura = row[11];
            const entrada_radio = document.getElementById('radio-1');
            const nro_factura = document.getElementById('nro_fac');
            setChange(cboProveedores, proveedor_id)
            fecha.value = fecha_id;
            nro_factura.value = factura;
            entrada_radio.value = '5';
            entrada_radio.checked = true;
            entrada_radio.dispatchEvent(new Event('change'));
            first_control.click();
            tblDetalleCompra.ajax.reload(null, false);
            // entrada_radio.value = '1';
        });

        $('#tblEntradas').on('click', '#eliE', function() {
            let boleta = tabla.row($(this).closest('tr').next()).data()[12];
            let src = new FormData();
            src.append('accion', 1);
            src.append('id', boleta);
            confirmarEliminar('la', 'entrada', function(r) {
                if (r) {
                    confirmarAccion(src, 'entradas', tabla);
                    cargarAutocompletado();
                }
            })
        });
    })
</script>