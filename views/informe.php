<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

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
            <?php if ($_SESSION["crear8"]) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-date">
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
                                <div style="padding-right: .3rem" class="col-auto col-p">
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
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="col">
                            <div style="margin-block:.4rem;height:33px;" class="input-group">
                                <span class="input-group-text" style="height:30px;"><i class="fas fa-search icon"></i></span>
                                <input autocomplete="off" style="border:none;" style="height:30px" type="text" id="_search" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
                            </div>
                        </div>
                        <table id="tblInforme" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">CÓDIGO</th>
                                    <th>DESCRIPCION</th>
                                    <th>UND</th>
                                    <th>SALIDA</th>
                                    <th></th>
                                    <th>ENTRADA</th>
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
            <div class="modal-header bg-gradient-success">
                <h4 class="modal-title"><i class="fas fa-file-lines"> </i> Generar Informe</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formInforme" method="POST" action="controllers/pdf_informe.php" class="needs-validation" autocomplete="off" target="_blank" novalidate>
                <div class="modal-body" style="padding:1rem 1rem 0rem 1rem">
                    <input type="hidden" name="variable" id="variable" value="">
                    <input type="hidden" name="id_usuario" id="id_usuario" value="">
                    <div class="row">
                        <div class="col d-flex" style="padding-bottom:.75rem;">
                            <div style="background-color:var(--select-hover)" class="tabs">
                                <input type="radio" class="rd-i" id="radio-orden" name="tabs" value="1" checked />
                                <label class="tab" for="radio-orden"> Orden</label>
                                <input type="radio" class="rd-i" id="radio-cliente" name="tabs" value="2" />
                                <label class="tab" for="radio-cliente">Cliente</label>
                                <input type="radio" class="rd-i" id="radio-fecha" name="tabs" value="3" />
                                <label class="tab" for="radio-fecha">Fecha</label>
                                <span class="glider"></span>
                            </div>
                        </div>
                        <div class="col col-sm-6" id="col_group">
                            <div class="form-group" id="groupCliente" style="display:none">
                                <label class="mb-0 combo"><i class="fas fa-user-tag"></i> Cliente</label>
                                <div class="row">
                                    <div class="col">
                                        <select id="cboCliente_i" name="cliente" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group " id="groupOrden">
                                <label id="lblO" class="mb-0 combo"><i class="fas fa-receipt"></i> Orden</label>
                                <div class="row">
                                    <div class="col">
                                        <!-- <form id="form_orden" action=""> -->
                                        <select id="cboOrden_i" name="orden" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                        <!-- </form> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="row_date" style="display:none">
                        <div class="col">
                            <div class="input-data">
                                <label style="font-size:1.15rem" for="desde"><i class="fas fa-calendar"></i> Desde</label>
                                <input style="font-size:1.28rem" autocomplete="off" id="desde" name="desde" type="date" value="">
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-data">
                                <label style="font-size:1.15rem" for="hasta"><i class="fas fa-calendar"></i> Hasta</label>
                                <input style="font-size:1.28rem" autocomplete="off" id="hasta" name="hasta" type="date" value="">
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-success"><i class="fas fa-file-lines"> </i><span class="button-text"> </span>Generar informe</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.Modal Date-->

<script src="assets/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>


<script>
    var mostrarCol = '<?php echo $_SESSION["editar4"] || $_SESSION["eliminar4"] ?>';
    var editar = '<?php echo $_SESSION["editar4"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar4"] ?>';
    var collapsedGroups = {};

    configuracionTable = {
        "responsive": true,
        "dom": 't',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        paging: false, // Esto deshabilita la paginación
        rowGroup: {
            dataSrc: [5],
            startRender: function(rows, group) {
                var collapsed = !!collapsedGroups[group];

                rows.nodes().each(function(r) {
                    r.style.display = collapsed ? '' : 'none';
                });

                var partes = group.split("-");

                // Acceder a las partes individuales
                var parte1 = partes[0];
                var parte2 = partes[1];
                var parte3 = partes[2];

                var groupText = '<div class="d-flex justify-content-between align-items-center "><strong style="cursor:pointer" class="pl-2" >' + group + ' (' + rows.count() + ')</strong><div class="txt-wrap-sm">' + '<form style="display:contents" action="controllers/pdf_salida.php" class="form_pdf" method="POST" autocomplete="off" target="_blank"><input type="hidden" name="id_boleta" class="input_boleta" value=""><button style="color:var(--text-color);font-size:1.55rem;padding-inline:.5rem!important" type="submit" class="btn pt-0 pb-0 btn_pdf"><i class="fas fa-file-pdf"></i></button></form>' + (editar ? '<button id="editR" style="color:var(--text-color);font-size:1.55rem;padding-inline:.5rem!important" class="btn pt-0 pb-0"><i class="fas fa-clipboard-list-check"></i></button>' : '') + (eliminar ? '<button id="eliS" style="color:var(--text-color);font-size:1.4rem;padding-inline:.5rem!important" class="btn pt-0 pb-0"><i class="fas fa-trash-can" ></i></button>' : '') + '</div></div>';

                return $('<tr/>')
                    .append('<td colspan="8">' + groupText + '</td>') // Asegúrate de ajustar el colspan según el número de columnas en tu tabla
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
                targets: 3,
                className: "text-center"
            },
            {
                targets: 4,
                className: "text-center"
            },
            {
                targets: 5,
                visible: false,
            },
            {
                targets: 6,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data === null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
        ],


    }

    $('#tblInforme tbody').on('click', 'tr.dtrg-start strong', function() {
        var name = $(this).closest('tr.dtrg-start').data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        tabla.draw(false);
    });

    $(document).ready(function() {
        let anio = year;
        let mes = month;
        // console.log(da)
        $(cboCliente_i).select2({
            placeholder: 'SELECCIONA UN CLIENTE',
            width: '100%',
            data: datos_cliente,
        })
        setChange(cboCliente_i, 0);

        $(cboOrden_i).select2({
            placeholder: 'SELECCIONA UNA ORDEN',
            width: '100%',
            data: datos_orden,
        })
        setChange(cboOrden_i, 0);

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        })

        setChange(cboAnio, anio);

        $(cboMeses).select2({
            minimumResultsForSearch: -1,
            width: 'calc(100% + 1.5rem)',
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
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;

                handleScroll(b, s, w);

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('i', JSON.stringify(tablaData));
            });
        }
        // let accion = 0;
        // const modal = document.querySelector('.modal'),
        //     span = document.querySelector('.modal-title span'),
        //     elements = document.querySelectorAll('.modal .bg-gradient-success'),
        const form = document.getElementById('formInforme');
        //     // form_pdf = document.getElementById('form_pdf'),
        //     btnNuevo = document.getElementById('btnNuevo');

        // btnPdf = document.getElementById('pdf');
        // let input_pdf = document.getElementById('input_boleta');
        // const cboAnio = document.getElementById('cboAnio'),
        //     cboOrden_i = document.getElementById('cboOrden_i'),
        //     cboCliente_i = document.getElementById('cboCliente_i'),
        //     desde = document.getElementById('desde'),
        //     hasta = document.getElementById('hasta');

        const row_date = document.getElementById('row_date');
        const col_group = document.getElementById('col_group');
        const groupOrden = document.getElementById('groupOrden');
        const groupCliente = document.getElementById('groupCliente');

        const tabsIn = document.querySelectorAll('.tabs .rd-i');

        console.log(tabsIn);
        tabsIn.forEach(tab => {
            tab.addEventListener('change', function() {
                let tabSelected = this.value;

                form.classList.remove('was-validated');
                // const selectedForm = document.getElementById(`form-${tabSelected}`);
                // const formContainers = document.querySelectorAll('.form-container');

                // formContainers.forEach(container => {
                //     container.style.display = 'none';
                // });

                // if (selectedForm) {
                //     selectedForm.style.display = 'block';
                // }

                console.log(tabSelected)
                console.log(row_date);

                if (tabSelected === '1') {
                    cboOrden_i.required = true
                    cboCliente_i.required = false
                    desde.required = false
                    hasta.required = false
                    row_date.style.display = 'none';
                    groupOrden.style.display = 'block';
                    groupCliente.style.display = 'none';
                    col_group.style.display = 'block';
                } else if (tabSelected === '2') {
                    cboOrden_i.required = false
                    cboCliente_i.required = true
                    desde.required = true
                    hasta.required = true
                    row_date.style.display = 'flex';
                    groupOrden.style.display = 'none';
                    groupCliente.style.display = 'block';
                    col_group.style.display = 'block';
                } else if (tabSelected === '3') {
                    row_date.style.display = 'flex';
                    col_group.style.display = 'none';
                    cboOrden_i.required = false
                    cboCliente_i.required = false
                    desde.required = true
                    hasta.required = true
                }
            });
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
            // tarjetasInfo(anio);
            // let src = new FormData();
            // src.append('accion', 1);
            // src.append('mes', mes);
            // src.append('anio', anio);
            // actualizarGrafico(src, chartCanvas, barChartData);

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

        // $('#tblInforme').on('submit', '.form_pdf', function(event) {
        //     event.preventDefault(); // Evita el envío predeterminado del formulario

        //     var id_boleta = tabla.row($(this).closest('tr').next()).data()[7];
        //     var input_pdf = $(this).find('.input_boleta');
        //     input_pdf.val(id_boleta);
        //     console.log(input_pdf.val());

        //     this.submit(); // Envía el formulario actual
        // });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                // const salida = document.getElementById('radio-2');
                // control.click();
                // salida.click();
            });
        }

        // $('#tblInforme').on('click', '#editS', function() {
        //     id_boleta = tabla.row($(this).closest('tr').next()).data()[7];
        //     const orden_id = tabla.row($(this).closest('tr').next()).data()[8];
        //     const conductor = tabla.row($(this).closest('tr').next()).data()[11];
        //     const entrega = tabla.row($(this).closest('tr').next()).data()[12];
        //     const fecha_id = tabla.row($(this).closest('tr').next()).data()[10];
        //     const salida_radio = document.getElementById('radio-2');
        //     setChange(cboOrden, orden_id)
        //     setChange(cboConductor, conductor)
        //     setChange(cboEmpleado, entrega)
        //     fecha.value = fecha_id;
        //     salida_radio.value = '4';
        //     salida_radio.checked = true;
        //     salida_radio.dispatchEvent(new Event('change'));
        //     control.click();
        //     tblDetalle.ajax.reload(null, false);
        //     salida_radio.value = '2';
        // });

        // $('#tblInforme').on('click', '#editR', function() {
        //     id_boleta = tabla.row($(this).closest('tr').next()).data()[7];
        //     let orden_id = tabla.row($(this).closest('tr').next()).data()[8];
        //     let cliente = tabla.row($(this).closest('tr').next()).data()[9];
        //     let fecha_id = tabla.row($(this).closest('tr').next()).data()[10];
        //     const retorno = document.getElementById('radio-3');
        //     setChange(cboOrdenActivas, orden_id)
        //     setChange(cboClientesActivos, cliente)
        //     fecha.value = fecha_id;
        //     control.click();
        //     retorno.click();
        //     tblReturn.ajax.reload(null, false);
        // });

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

        // $('#tblInforme tbody').on('click', '.btnEliminar', function() {

        // });

        // document.addEventListener('keydown', function(e) {
        //     if (e.key === "Escape") {
        //         const activeModal = document.querySelector('.modal.show');
        //         if (activeModal) {
        //             $(activeModal).modal('hide');
        //         }
        //     }
        // });

        // $('#tblInforme tbody').on('click', '.btnEditar', function() {
        //     let row = obtenerFila(this, tabla);
        //     accion = 2;
        //     const icon = document.querySelector('.modal-title i');
        //     cambiarModal(span, ' Editar Entrada', icon, 'fa-pen-to-square', elements, 'bg-gradient-success', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
        //     id.value = row["id_empleado"];
        //     nombre.value = row["nombres_empleado"];
        //     cedula.value = row["cedula"];
        //     conductor.checked = row["conductor"];
        // });

        // form.addEventListener("submit", function(e) {
        //     e.preventDefault();
        //     if (!this.checkValidity()) {
        //         this.classList.add('was-validated');
        //         return;
        //     }
        //     this.submit();
        //     //     console.log("Ejecutando el resto del código...");
        //     //     const id_e = id.value;
        //     //     let datos = new FormData();
        //     //     datos.append('id_empleado', id_e);
        //     //     datos.append('cedula', ced);
        //     //     datos.append('nombres_empleado', nom);
        //     //     datos.append('conductor', con);
        //     //     datos.append('accion', accion);
        //     //     confirmarAccion(datos, null, 'empleados', modal, tabla)
        // });
    })
</script>