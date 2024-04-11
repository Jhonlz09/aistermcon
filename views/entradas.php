<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<head>
    <title>Entradas</title>
</head>

<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Entradas</h1>
            </div>
            <?php if ($_SESSION["crear3"]) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-success" data-toggle="modal" data-target="#modal">
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
                                    <h3 class="card-title ">Listado de entradas</h3>
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
                        <table id="tblEntradas" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">CÓDIGO</th>
                                    <th>CANTIDAD</th>
                                    <th>UND</th>
                                    <th class="text-center">PRECIO</th>
                                    <th>DESCRIPCION</th>
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

<!-- Modal -->
<!-- <div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Entrada</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="id_empleado" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="cedula" inputmode="numeric" class="input-nuevo" type="text" oninput="validarNumber(this,/[^0-9]/g,true)" maxlength="10" required>
                                <div class="line underline"></div>
                                <label class="label">
                                    <i class="fa-solid fa-id-card"></i> Cédula</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                                <div class="ten">*La cedula debe contener 10 numeros</div>
                            </div>
                            <div class="input-data" style="margin-bottom:2.4em;">
                                <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="label"><i class="fa-solid fa-signature"></i> Nombres</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                            </div>
                            <div style="font-size: 1.33rem;margin-bottom:1rem" class="d-flex align-items-center">
                                <label for="conductor" class="m-0">
                                    <i class="fa-solid fa-steering-wheel"></i> Conductor
                                </label>
                                <label class="switch-2 ml-3">
                                    <input class="switch__input" type="checkbox" id="conductor" onkeydown="toggleWithEnter(event, this)">
                                    <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                        <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                    </svg>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-success"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<script src="assets/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar3"] || $_SESSION["eliminar3"] ?>';
    var editar = '<?php echo $_SESSION["editar3"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar3"] ?>';
    var collapsedGroups = {};

    configuracionTable = {
        "responsive": true,
        "dom": 't',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        paging: false, // Esto deshabilita la paginación
        rowGroup: {
            dataSrc: [6],
            startRender: function(rows, group) {
                var collapsed = !!collapsedGroups[group];

                rows.nodes().each(function(r) {
                    r.style.display = collapsed ? '' : 'none';
                });

                var groupText = '<div class="d-flex justify-content-between align-items-center "><strong style="cursor:pointer" class="pl-2" >' + group + ' (' + rows.count() + ')</strong><div class="txt-wrap-sm">' + (editar ? '<button id="editE" style="color:var(--text-color);font-size:1.55rem;padding-inline:.5rem!important" class="btn pt-0 pb-0"><i class="fas fa-pen-to-square"></i></button> ' : '') + (eliminar ? '<button id="eliE" style="color:var(--text-color);font-size:1.4rem;padding-inline:.5rem!important" class="btn pt-0 pb-0"><i class="fas fa-trash-can"></i></button>' : '') + '</div></div>';
                return $('<tr/>')
                    .append('<td colspan="8">' + groupText + '</td>') // Asegúrate de ajustar el colspan según el número de columnas en tu tabla
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
            },
            {
                targets: 3,
                className: "text-center text-nowrap ",
            },
            {
                targets: 6,
                visible: false,
            },
            // {
            //     targets: 6,
            //     data: "acciones",
            //     visible: mostrarCol ? true : false,
            //     render: function(data, type, row, full, meta) {
            //         return (
            //             "<center style='white-space: nowrap;'>" +
            //             (editar ?
            //                 " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
            //                 " <i class='fa-solid fa-pencil'></i>" +
            //                 "</button>" : "") +
            //             (eliminar ?
            //                 " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
            //                 " <i class='fa fa-trash'></i>" +
            //                 "</button>" : "") +
            //             " </center>"
            //         );
            //     },
            // },
        ],
    }

    $('#tblEntradas tbody').on('click', 'tr.dtrg-start strong', function() {
        var name = $(this).closest('tr.dtrg-start').data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        tabla.draw(false);
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
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;

                // tabla.rows({
                //     page: 'current'
                // }).every(function() {
                //     var groupRow = this.node();
                //     var groupValue = this.data()[4]; // Índice de la columna para obtener el valor del grupo

                //     // Agregar botones HTML al final de la fila de grupo
                //     $(groupRow).find('.dtrg-start').append('<button class="btn btn-primary">Botón 1</button> <button class="btn btn-danger">Botón 2</button>');
                // });

                handleScroll(b, s, w);

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('e', JSON.stringify(tablaData));
            });
        }

        let accion = 0;
        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-success'),
            form = document.getElementById('formNuevo'),
            btnNuevo = document.getElementById('btnNuevo');

        // const id = document.getElementById('id_empleado'),
        //     cedula = document.getElementById('cedula'),
        //     nombre = document.getElementById('nombre'),
        //     conductor = document.getElementById('conductor');

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

        // $(modal).on("shown.bs.modal", () => {
        //     // cedula.focus();
        // });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                const entrada = document.getElementById('radio-1');
                // const icon = document.querySelector('.modal-title i');
                // cambiarModal(span, ' Nuevo Entrada', icon, 'fa-user-plus', elements, 'bg-gradient-blue', 'bg-gradient-success', modal, 'modal-new', 'modal-change')
                // form.reset();
                // form.classList.remove('was-validated');
                control.click();
                entrada.click();
            });
        }

        $('#tblEntradas').on('click', '#editE', function() {
            id_boleta = tabla.row($(this).closest('tr').next()).data()[7];
            const fecha_id = tabla.row($(this).closest('tr').next()).data()[8];
            const proveedor_id = tabla.row($(this).closest('tr').next()).data()[9];
            const entrada_radio = document.getElementById('radio-1');
            setChange(cboProveedores, proveedor_id)
            fecha.value = fecha_id;
            entrada_radio.value = '5';
            entrada_radio.checked = true;
            entrada_radio.dispatchEvent(new Event('change'));
            control.click();
            tblDetalleEntrada.ajax.reload(null, false);
            entrada_radio.value = '1';
        });

        $('#tblEntradas').on('click', '#eliE', function() {
            let boleta = tabla.row($(this).closest('tr').next()).data()[7];
            let src = new FormData();
            src.append('accion', 1);
            src.append('id', boleta);
            confirmarEliminar('la', 'entrada', function(r) {
                if (r) {
                    confirmarAccion(src, 'entradas', tabla)
                }
            })
        });

        // $('#tblEntradas tbody').on('click', '.btnEliminar', function() {
        //     const e = obtenerFila(this, tabla)
        //     accion = 3
        //     const id_e = e["id_empleado"];
        //     const name = 'empleados'
        //     let src = new FormData();
        //     src.append('accion', accion);
        //     src.append('id_empleado', id_e);
        //     confirmarEliminar(name, 'este', 'empleado', tabla, src);
        // });

        // document.addEventListener('keydown', function(e) {
        //     if (e.key === "Escape") {
        //         const activeModal = document.querySelector('.modal.show');
        //         if (activeModal) {
        //             $(activeModal).modal('hide');
        //         }
        //     }
        // });

        // $('#tblEntradas tbody').on('click', '.btnEditar', function() {
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
        //     $('.ten').hide();
        //     const ced = cedula.value.trim(),
        //         nom = nombre.value.trim().toUpperCase(),
        //         con = conductor.checked;
        //     if (!this.checkValidity() || ced.length < 10) {
        //         this.classList.add('was-validated');
        //         ced.length > 0 && $('.ten').show();
        //         return;
        //     }
        //     console.log("Ejecutando el resto del código...");
        //     const id_e = id.value;
        //     let datos = new FormData();
        //     datos.append('id_empleado', id_e);
        //     datos.append('cedula', ced);
        //     datos.append('nombres_empleado', nom);
        //     datos.append('conductor', con);
        //     datos.append('accion', accion);
        //     confirmarAccion(datos, null, 'empleados', modal, tabla)
        // });
    })
</script>