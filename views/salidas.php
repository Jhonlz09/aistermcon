<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<head>
    <title>Movimientos</title>
</head>

<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Movimientos</h1>
            </div>
            <?php if ($_SESSION["crear4"]) : ?>
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
                                    <h3 class="card-title ">Listado de movimientos</h3>
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
                        <table id="tblSalidas" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">CÓDIGO</th>
                                    <th>CANTIDAD</th>
                                    <th>UND</th>
                                    <th>DESCRIPCION</th>
                                    <th></th>
                                    <th class="text-center">UTIL.</th>
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

                var groupText = '<div class="d-flex justify-content-between align-items-center "><strong style="cursor:pointer" class="pl-2" >' + group + ' (' + rows.count() + ')</strong><div class="txt-wrap-sm">' + '<form style="display:contents" action="PDF/pdf_salida.php" class="form_pdf" method="POST" autocomplete="off" target="_blank"><input type="hidden" name="id_boleta" class="input_boleta" value=""><button style="color:var(--text-color);font-size:1.55rem;padding-inline:.5rem!important" type="submit" class="btn pt-0 pb-0 btn_pdf"><i class="fas fa-file-pdf"></i></button></form>' + (editar ? '<button id="editS" style="color:var(--text-color);font-size:1.55rem;padding-inline:.5rem!important" class="btn pt-0 pb-0"><i class="fas fa-pen-to-square"></i></button><button id="editR" style="color:var(--text-color);font-size:1.55rem;padding-inline:.5rem!important" class="btn pt-0 pb-0"><i class="fas fa-clipboard-list-check"></i></button>' : '') + (eliminar ? '<button id="eliS" style="color:var(--text-color);font-size:1.4rem;padding-inline:.5rem!important" class="btn pt-0 pb-0"><i class="fas fa-trash-can" ></i></button>' : '') + '</div></div>';

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
                targets: 5,
                visible: false,
            },
            {
                targets: 6,
                className: "text-center",
                visible: true,
                render: function(data, type, row) {
                    let resultado = row.fabricado;
                    let texto = resultado ? 'SI' : 'NO';
                    let className = resultado ? 'text-success': 'text-danger';
                    return `<span style='font-size:1rem' class="${className} font-weight-bold">${texto}</span>`;
                }
            },

            {
                targets: 7,
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

    $('#tblSalidas tbody').on('click', 'tr.dtrg-start strong', function() {
        var name = $(this).closest('tr.dtrg-start').data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        tabla.draw(false);
    });



    $(document).ready(function() {
        let anio = year;
        let mes = month;
        console.log(anio);
        if (!$.fn.DataTable.isDataTable('#tblSalidas')) {
            tabla = $("#tblSalidas").DataTable({
                "ajax": {
                    "url": "controllers/salidas.controlador.php",
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

                handleScroll(b, s, w);

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('s', JSON.stringify(tablaData));
            });
        }
        let accion = 0;
        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-success'),
            form = document.getElementById('formNuevo'),
            form_pdf = document.getElementById('form_pdf'),
            btnNuevo = document.getElementById('btnNuevo');
        // btnPdf = document.getElementById('pdf');
        let input_pdf = document.getElementById('input_boleta');


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


        $('#tblSalidas').on('submit', '.form_pdf', function(event) {
            event.preventDefault(); // Evita el envío predeterminado del formulario

            var id_boleta = tabla.row($(this).closest('tr').next()).data()[8];
            console.log(id_boleta)
            var input_pdf = $(this).find('.input_boleta');
            input_pdf.val(id_boleta);
            console.log(input_pdf.val());

            this.submit(); // Envía el formulario actual
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                const salida = document.getElementById('radio-2');
                first_control.click();
                salida.click();
            });
        }

        $('#tblSalidas').on('click', '#editS', function() {
            id_boleta = tabla.row($(this).closest('tr').next()).data()[7];
            const orden_id = tabla.row($(this).closest('tr').next()).data()[8];
            const conductor = tabla.row($(this).closest('tr').next()).data()[11];
            const entrega = tabla.row($(this).closest('tr').next()).data()[12];
            const fecha_id = tabla.row($(this).closest('tr').next()).data()[10];
            const salida_radio = document.getElementById('radio-2');
            setChange(cboOrden, orden_id)
            setChange(cboConductor, conductor)
            setChange(cboEmpleado, entrega)
            fecha.value = fecha_id;
            salida_radio.value = '4';
            salida_radio.checked = true;
            salida_radio.dispatchEvent(new Event('change'));
            first_control.click();
            tblDetalleSalida.ajax.reload(null, false);
            salida_radio.value = '2';
        });

        $('#tblSalidas').on('click', '#editR', function() {
            id_boleta = tabla.row($(this).closest('tr').next()).data()[7];
            let orden_id = tabla.row($(this).closest('tr').next()).data()[8];
            let cliente = tabla.row($(this).closest('tr').next()).data()[9];
            let fecha_id = tabla.row($(this).closest('tr').next()).data()[10];
            const retorno = document.getElementById('radio-3');
            setChange(cboOrdenActivas, orden_id)
            setChange(cboClientesActivos, cliente)
            fecha.value = fecha_id;
            first_control.click();
            retorno.click();
            tblReturn.ajax.reload(null, false);
        });

        $('#tblSalidas').on('click', '#eliS', function() {
            let boleta = tabla.row($(this).closest('tr').next()).data()[7];
            let src = new FormData();
            src.append('accion', 3);
            src.append('id', boleta);
            confirmarEliminar('la', 'salida', function(r) {
                if (r) {
                    confirmarAccion(src, 'salidas', tabla)
                }
            })
        });

        // $('#tblSalidas tbody').on('click', '.btnEliminar', function() {

        // });

        // document.addEventListener('keydown', function(e) {
        //     if (e.key === "Escape") {
        //         const activeModal = document.querySelector('.modal.show');
        //         if (activeModal) {
        //             $(activeModal).modal('hide');
        //         }
        //     }
        // });

        // $('#tblSalidas tbody').on('click', '.btnEditar', function() {
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