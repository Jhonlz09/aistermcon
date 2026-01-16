<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Informe</title>
</head>

<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Informe</h1>
            </div>
            <?php if (isset($_SESSION["crear7"]) && $_SESSION["crear7"] === true) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-green" data-toggle="modal" data-target="#modal-date">
                        <i class="fa fa-file-lines"></i> Generar informe</button>
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
                                    <h3 class="card-title ">Listado de informe</h3>
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
                        <table id="tblInforme" class="table table-bordered table-striped" style="width:100%">
                            <thead style="white-space:nowrap;">
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">CÓDIGO</th>
                                    <th>NRO. GUIA</th>
                                    <th>F. SALIDA</th>
                                    <th>F. ENTRADA</th>
                                    <th>DESCRIPCION</th>
                                    <th>UND</th>
                                    <th>SALIDA</th>
                                    <th>ENTRADA</th>
                                    <th></th>
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

<!-- Modal Date-->
<div class="modal fade" id="modal-date">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-nuevo">
            <div class="modal-header bg-gradient-green">
                <div class="row">
                    <div class="col-auto" style="padding-block:.2rem">
                        <h4 class="modal-title text-wrap"><i class="fas fa-file-lines"></i> Generar Informe</h4>
                    </div>
                    <div class="col">
                        <select id="cboAnioOrden" class="form-control select2 select2-light" data-dropdown-css-class="select2-dark">
                        </select>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formInforme" method="POST" action="PDF/pdf_informe_orden.php" class="needs-validation" autocomplete="off" target="_blank" novalidate>
                <div class="modal-body" style="padding: 1rem;">
                    <div class="container-fluid">
                        <label for="none" class=" combo">
                            <!-- <div class="d-flex align-items-center" style="font-size:1.15rem;gap:4px"> -->
                            <i class="fas fa-file-pdf"></i> Seleccione un tipo de informe</label>
                        <div class="row">
                            <div class="col d-flex" style="padding-bottom:.75rem;">
                                <div style="background-color:var(--primary-color-light)" class="tabs">
                                    <input type="radio" class="rd-i" id="radio-orden" name="tabs" value="1" checked />
                                    <label class="tab" for="radio-orden"> Translado</label>
                                    <input type="radio" class="rd-i" id="radio-cliente" name="tabs" value="2" />
                                    <label class="tab" for="radio-cliente">Fabricacion</label>
                                    <input type="radio" class="rd-i" id="radio-fecha" name="tabs" value="3" />
                                    <label class="tab" for="radio-fecha">Resumen total</label>
                                    <span class="glider"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3" id="groupOrden">
                                    <label for="none" class="mb-0 combo">
                                        <!-- <div class="d-flex align-items-center" style="font-size:1.15rem;gap:4px"> -->
                                        <i class="fas fa-ticket"></i> Orden de trabajo
                                    </label>
                                    <!-- Selector de Orden -->
                                    <div class="row">
                                        <div class="col">
                                            <select id="cboOrden_i" name="id_orden" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                            </select>
                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Fin container-fluid -->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-green"><i class="fas fa-file-lines"> </i><span class="button-text"> </span>Generar informe</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.Modal Date-->

<script>
    var editar = '<?php echo $_SESSION["editar7"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar7"] ?>';
    var collapsedGroups = {};

    configuracionTable = {
        "responsive": true,
        "dom": 'tp',
        "lengthChange": false,
        "pageLength": 100,
        "ordering": false,
        "autoWidth": false,
        "paging": true,
        "deferRender": true,
        rowGroup: {
            dataSrc: [9],
            startRender: function(rows, group) {
                let collapsed = !!collapsedGroups[group];

                rows.nodes().each(function(r) {
                    $(r).toggleClass('collapsedrow', !collapsed);
                });

                let groupText = `<div class="d-flex justify-content-between align-items-center" style="cursor:pointer">
            <strong class="pl-2">${group} (${rows.count()})</strong>
            <div class="txt-wrap-sm">
                <button type="button" class="btn pt-0 pb-0 btn-row" data-action="pdf" style="font-size:1.55rem;padding-inline:.5rem!important">
                    <i class="fas fa-file-pdf"></i>
                </button>
                <button type="button" class="btn pt-0 pb-0 btn-row" data-action="xls" title="Descargar resumen xls" style="font-size:1.55rem;padding-inline:.5rem!important">
                    <i class="fas fa-file-xls"></i>
                </button>
            </div>
        </div>`;

                return $('<tr/>')
                    .append('<td colspan="9">' + groupText + '</td>') 
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
        },
        columnDefs: [{
                targets: 0,
                data: 'fila',
                className: "text-center",
            },
            {
                targets: 4,
                render: function(data, type, row, meta) {
                    if (data === null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                targets: 7,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data === null) {
                        return '-';
                    } else {
                        return `<span class="text-danger font-weight-bold">${data}</span>`;
                    }
                }
            },
            {
                targets: 8,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data === null) {
                        return '-';
                    } else {
                        return `<span class="text-success font-weight-bold">${data}</span>`;
                    }
                }
            },
            {
                targets: 9,
                visible: false,
            },
        ],
    }

    $('#tblInforme tbody').on('click', 'tr.dtrg-start', function() {
        if ($(event.target).closest('.txt-wrap-sm').length === 0) {
            let scrollPosition = $(window).scrollTop();
            var name = $(this).data('name');
            collapsedGroups[name] = !collapsedGroups[name];
            tabla.draw(false);
            $(window).scrollTop(scrollPosition);
        }
    });

    $(document).ready(function() {
        let anio = year;
        let mes = month;
        const cboOrden_i = document.getElementById('cboOrden_i');
        let fabValue = null;

        $(cboOrden_i).select2({
            placeholder: 'SELECCIONA UNA ORDEN',
            width: '100%',
        });

        cargarCombo('Orden_i', '', 3, false, anio).then(datos_ => {
            $(cboOrden_i).empty();
            $(cboOrden_i).select2({
                placeholder: 'SELECCIONA UNA ORDEN',
                data: datos_,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    return data.html; 
                },
                templateSelection: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    return data.html; 
                }
            });
            setChange(cboOrden_i, 0);
        });

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        });

        $(cboAnioOrden).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        })

        setChange(cboAnioOrden, anio);
        setChange(cboAnio, anio);

        $(cboMeses).select2({
            minimumResultsForSearch: -1,
            width: 'calc(100% + .4vw)',
            data: datos_meses,
        });

        setChange(cboMeses, mes);

        if (!$.fn.DataTable.isDataTable('#tblInforme')) {
            tabla = $("#tblInforme").DataTable({
                "ajax": {
                    "url": "controllers/informe.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(d) {
                        d.anio = anio;
                        d.mes = mes;
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
            });
        }

        const form = document.getElementById('formInforme');
        const btnGuardar = document.getElementById('btnGuardar');
        const tabsIn = document.querySelectorAll('.tabs .rd-i');
        let tabSelectedIn = '1';

        tabsIn.forEach(tab => {
            tab.addEventListener('change', function() {
                tabSelectedIn = this.value;
            });
        });

        btnGuardar.addEventListener('click', function(e) {
            e.preventDefault();
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            // let idOrden = cboOrden_i.value;
            if (tabSelectedIn === '1') {
                form.action = 'PDF/pdf_informe_orden.php';
            } else if (tabSelectedIn === '2') {
                form.action = 'PDF/pdf_informe_orden_fab.php';
            } else if (tabSelectedIn === '3') {
                form.action = 'PDF/pdf_informe_orden_resumen.php';
            }
            form.submit();
        });

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

        $(cboAnioOrden).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            anio = a
            cargarCombo('Orden_i', '', 3, false, anio).then(datos_ => {
                $(cboOrden_i).empty();
                $(cboOrden_i).select2({
                    placeholder: 'SELECCIONA UNA ORDEN',
                    data: datos_,
                    escapeMarkup: function(markup) {
                        return markup; // Permitir HTML
                    },
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }

                        let $option = $(`<div data-valor="${data.fab}">
                            ${data.html}
                        </div>`);

                        return $option;
                    },
                    templateSelection: function(data) {
                        if (!data.id) {
                            return data.text;
                        }

                        return data.html; // Mantener el HTML en la selección
                    }
                });
                setChange(cboOrden_i, 0);
            });
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

        $(cboOrden_i).on("select2:select", function(e) {
            fabValue = e.params.data.fab; // Obtener la propiedad 'fab' directamente del objeto de datos
        });

        $('#tblInforme').on('click', '.btn-row', function(event) {
            const btn = $(this);
            const action = btn.data('action'); // "pdf" o "xls"
            const isPdf = action === 'pdf';
            const formAction = isPdf ? 'PDF/pdf_informe_orden_resumen.php' : 'EXCEL/xls_informe_orden.php';

            // Busca la fila siguiente a la fila del grupo para obtener el id_orden
            const id_orden = tabla.row(btn.closest('tr').next()).data()[11];
            if (!id_orden) return;
            // Crea el formulario dinámico
            const form = $('<form>', {
                action: formAction,
                method: 'POST',
                style: 'display:none'
            });

            if (isPdf) {
                form.attr('target', '_blank'); // Solo aplica target="_blank" si es PDF
            }

            const input = $('<input>', {
                type: 'hidden',
                name: 'id_orden',
                value: id_orden
            });

            form.append(input);
            $('body').append(form);
            form.submit();
            form.remove(); // Limpia el DOM
        });

        $('#tblInforme').on('click', '#eliS', function() {
            let boleta = tabla.row($(this).closest('tr').next()).data()[7];
            let src = new FormData();
            src.append('accion', 3);
            src.append('id', boleta);
            confirmarEliminar('la', 'guia', function(r) {
                if (r) {
                    confirmarAccion(src, 'salidas', tabla);
                }
            })
        });
    })
</script>