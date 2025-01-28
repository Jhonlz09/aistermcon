<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ingreso personal</title>
</head>
<!-- Contenido Header -->
<section id="div_header" class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Horario personal</h1>
            </div>
            <?php if (isset($_SESSION["crear20"]) && $_SESSION["crear20"] === true) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-green">
                        <i class="fa fa-plus"></i> Agregar horario</button>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->
<!-- Main content -->
<section id="div_content" class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12">
                            <div class="row">
                                <div class="col col-p">
                                    <h3 style="white-space:normal;" class="card-title ">Listado de horario</h3>
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
                        <!-- <div class="table-responsive"> -->
                        <table id="tblHorario" cellspacing="0" class="table table-bordered table-striped">
                            <thead>

                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>NOMBRES</th>
                                    <th>Nº DE ORDEN</th>
                                    <th>CLIENTE</th>
                                    <th>FECHA</th>
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!-- </div> -->
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

<section id="div_hor_header" style="display:none" class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h4>
                    <i id="btnReturn" style="cursor:pointer" class="fa-regular fa-circle-arrow-left"></i><span id="text_accion"> Nuevo horario</span>
                </h4>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<section id="div_hor_filter" class="content" style="display: none;">
    <div class="container-fluid">
        <div class="row" style="align-items:flex-start">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mb-2">
                                <label for="input1" class="m-0"><i class="fas fa-person-digging"></i> Obra</label>
                                <input type="text" id="input1" class="form-control">
                            </div>
                            <div class="col-sm-3 mb-2">
                                <label for="input2" class="m-0"><i class="fas fa-calendar"></i> Fecha</label>
                                <input type="date" id="input2" class="form-control">
                            </div>
                            <div class="col-sm-3 mb-2">
                                <label for="input3" class="m-0 text-nowrap"><i class="fas fa-user-helmet-safety"></i> Persona</label>
                                <!-- <input type="text" id="input3" class="form-control"> -->
                                <span data-toggle="modal" data-target="#modal_personal" class="form-control badge bg-gradient-blue d-flex align-content-center align-items-center justify-content-center" style="width:3.5rem"><i class="fa-solid fa-up-right-from-square"></i></span>
                            </div>
                            <div class="col-sm-3 mb-2">
                                <div class="row">
                                    <div class="col-sm ">
                                        <label for="input3" class="m-0 text-nowrap"><i class="fas fa-table-rows"></i> Filas</label>
                                        <input type="text" id="input3" class="form-control">
                                    </div>
                                    <div class="col-auto mt-2">
                                        <button type="button" id="addRow" class="btn btn-sm bg-gradient-green">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</section>
<section id="div_hor" class="content" style="display: none;">
    <div class="container-fluid">
        <div class="row" style="align-items:flex-start">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <form id="formHorario">
                            <div class="table-responsive" style="padding:0;border:1px solid #ccc;border-radius: 4px;">
                                <table id="tblPersonH" class="table table-bordered w-100 table-striped">
                                    <thead>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th class="th-green" colspan="4">SUELDO Y SOBRETIEMPO</th>
                                            <th class="th-blue" colspan="6">GASTOS EN OBRA</th>
                                        </tr>
                                        <tr>
                                            <th class="th-orange">NOMBRES</th>
                                            <th class="th-orange">CLIENTE</th>
                                            <th class="th-orange">Nº DE ORDEN</th>
                                            <th class="th-orange">FECHA</th>
                                            <th class="th-green">HORARIO NORMAL</th>
                                            <th class="th-green">HORA SUPLEMENTARIA</th>
                                            <th class="th-green">HORA 100%</th>
                                            <th class="th-green">TOTAL DE HORAS</th>
                                            <th class="th-blue">MATERIAL</th>
                                            <th class="th-blue">TRANSPORTE</th>
                                            <th class="th-blue">ALIMENTACION</th>
                                            <th class="th-blue">HOSPEDAJE</th>
                                            <th class="th-blue">GUARDIANIA</th>
                                            <th class="th-blue">AGUA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- <div class="col-xl-3" style="position:sticky;top:10%">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="d-flex justify-content-around">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio1" name="customRadio" data-tax="0" value="0">
                                            <label for="customRadio1" class="custom-control-label">0%</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio2" name="customRadio" data-tax="5" value="05">
                                            <label for="customRadio2" class="custom-control-label">5%</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio3" name="customRadio" data-tax="<?php echo $_SESSION["iva"]; ?>" value="<?php echo $_SESSION["iva"]; ?>" checked>
                                            <label for="customRadio3" class="custom-control-label">
                                                <?php echo $_SESSION["iva"]; ?>%
                                            </label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Descuento:</span>
                                        <span><input type="text" id="otros" readonly maxlength="6" class="form-control p-0 d-inline" autocomplete="off" spellcheck="false" placeholder="$0.00" oninput="validarNumber(this, /[^0-9.]/g)" style="background-color:#fff;border:none;width:100%;height:auto;max-width:4.8em;text-align:right"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>(IVA <span id="tax"><?php echo $_SESSION["iva"] ?></span>%):</span>
                                        <span id="can_tax">$0.00</span>
                                    </div>

                                    <hr>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total:</span>
                                        <span id="total">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <button type="button" id="btnGuardarSoli" style="margin-bottom:.75rem;background:#3f6791 linear-gradient(180deg, #3f6791, #0b4395) repeat-x; color:#fff" class="btn w-100"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                                <button type="button" id="CerrarSoli" style="border-color:#d6d8df69" class="btn bg-gradient-light w-100"><i class="fas fa-right-from-bracket"></i><span class="button-text"> </span>Cancelar</button>
                            </div>
                        </div>
                    </div> -->
        </div>
    </div>
    </div>
</section>
<!-- Modal -->

<div class="modal fade" id="modal_personal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 class="modal-title"><i class="fas fa-calendar-circle-user"></i><span> Selecciona el personal</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal" style="padding-block:1rem .5rem">
                    <input type="hidden" id="id" value="">
                    <div class="table-responsive">
                        <table id="tblEmpleadoH" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>NOMBRES Y APELLIDOS</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-green"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script>
    var mostrarCol = '<?php echo $_SESSION["editar20"] || $_SESSION["eliminar20"] ?>';
    var editar = '<?php echo $_SESSION["editar20"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar20"] ?>';

    configuracionTable = {
        "responsive": true,
        "dom": 'Ptp',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        searchPanes: {
            cascadePanes: true,
            columns: [1, 2, 3],
            initCollapsed: true,
            threshold: 0.8, // Ajusta este valor según tus necesidades
            dtOpts: {
                select: {
                    style: 'multiple'
                }
            },
        },
        columnDefs: [{
                targets: 0,
                data: "acciones",
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1; // Devuelve el número de fila + 1
                    }
                    return meta.row; // Devuelve el índice de la fila
                }
            },
            {
                targets: 3,
            },
            {
                targets: 5,
                data: 'acciones',
                visible: mostrarCol,
                render: function(data, type, row, full, meta) {
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button>" : "") +
                        (eliminar ?
                            " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                            " <i class='fa fa-trash'></i>" +
                            "</button>" : "") +
                        " </center>"
                    );
                },
            },
        ],
        // initComplete: function() {
        //     var api = this.api();
        //     $('.filterhead', api.table().header()).each(function(i) {
        //         var column = api.column(i);
        //         if (i === 0 || i === api.columns().count() - 1) {
        //             // No agregar ningún elemento en la columna 0 y la última columna
        //             $(this).empty();
        //             return;
        //         }

        //         var inputType = 'text';
        //         if (i === 4) { // Columna de fecha
        //             inputType = 'date';
        //         }

        //         var input = $('<input style="border-bottom-width:2px;padding:0" class="form-control responsive-input" type="' + inputType + '" placeholder="Buscar" />')
        //             .appendTo($(this).empty())
        //             .on('input', function() {
        //                 var val = $.fn.dataTable.util.escapeRegex(
        //                     $(this).val()
        //                 );

        //                 column
        //                     .search(val ? '^' + val + '$' : '', true, false)
        //                     .draw();
        //             });
        //     });
        // }
    }

    $(document).ready(function() {
        let accion = 0;


        if (!$.fn.DataTable.isDataTable('#tblHorario')) {
            tabla = $("#tblHorario").DataTable({
                "ajax": {
                    "url": "controllers/horario.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
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
                localStorage.setItem('horario', JSON.stringify(tablaData));
            });
        }

        tblPerson = $("#tblPersonH").DataTable({
            "ajax": {
                "url": "controllers/inventario.controlador.php",
                "type": "POST",
                "dataSrc": '',
                data: function(data) {
                    data.accion = 12;
                    data.anio = 2025;
                    data.id_producto = 0;
                }
            },
            "dom": 't',
            "responsive": false,
            "pageLength": 100,
            "lengthChange": false,
            "ordering": false,
            "autoWidth": false,
        });


        tblEmpleadoH = $("#tblEmpleadoH").DataTable({
            "ajax": {
                "url": "controllers/combo.controlador.php",
                "type": "POST",
                "dataSrc": '',
                data: function(data) {
                    data.accion = 7;
                }
            },
            "dom": 'ft',
            select: {
                selector: 'td:first-child'
            },
            "lengthChange": false,
            "ordering": false,
            "autoWidth": false,
            "columns": [{
                    "data": null,
                    "defaultContent": '',
                    "className": 'select-checkbox',
                },
                {
                    "data": "nombre"
                },
            ],
            "pageLength": 100,
        });


        // tabla.on('responsive-resize', function(e, datatable, columns) {
        //     // Encontrar el índice de la última columna visible
        //     let lastVisibleColumnIndex = columns.reduce((lastIndex, col, index) => {
        //         return col.responsiveHidden ? lastIndex : index;
        //     }, -1);

        //     // Iterar sobre todas las columnas del encabezado
        //     $('#tblHorario thead tr:first-child th').each(function(index) {
        //         // Si es la última columna visible, asegurarse de que no tenga la clase 'd-none'
        //         // Si no lo es, no hacer nada (para no ocultar las otras columnas)
        //         if (index === lastVisibleColumnIndex) {
        //             $(this).removeClass('d-none');
        //         } else {
        //             $(this).addClass('d-none');
        //         }
        //     });
        // });


        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            div_placa = document.getElementById('div_placa'),
            modalS = document.getElementById('modalS'),
            elementsE = document.querySelectorAll('#modalS .bg-gradient-green'),
            select = document.querySelectorAll('.modal-body select.select2'),
            formS = document.getElementById('formNuevoS'),
            spanE = document.querySelector('#span-title span'),
            iconElement = document.querySelector('#span-title i'),
            inputContent = document.getElementById('nombreS'),
            inputId = document.getElementById('idS'),
            btnNuevo = document.getElementById('btnNuevo'),
            btnReturn = document.getElementById('btnReturn');



        const id = document.getElementById('id'),
            cedula = document.getElementById('cedula'),
            nombre = document.getElementById('nombre'),
            apellido = document.getElementById('apellido'),
            celular = document.getElementById('celular');


        OverlayScrollbars(document.querySelector('.scroll-modal'), {
            autoUpdate: true,
            scrollbars: {
                autoHide: 'leave'
            }
        });

        $(modal).on("shown.bs.modal", () => {
            nombre.focus();
        });

        $(modalS).on("shown.bs.modal", function() {
            inputContent.focus();
        });

        $(modalS).on('hidden.bs.modal', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (scroll) {
                $(body).addClass('modal-open');
                $(body).css('padding-right', '6px');
            }
        });

        btnReturn.addEventListener('click', function() {
            ocultarFormulario();
        });

        function visibleForm(show) {
            let look = show ? 'block' : 'none';
            document.getElementById("div_cot").style.display = "none";
            document.getElementById("div_content").style.display = "block";
            $('#tblCotizacion').DataTable().columns.adjust().draw(false);
            tblSolicitud.clear().draw();
            document.getElementById("div_header").style.display = "block";
        }

        function novisibleForm(show) {
            let look = show ? 'block' : 'none';
            document.getElementById("div_cot").style.display = "none";
            document.getElementById("div_content").style.display = "block";
            $('#tblCotizacion').DataTable().columns.adjust().draw(false);
            tblSolicitud.clear().draw();
            document.getElementById("div_header").style.display = "block";
        }

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                // const icon = document.querySelector('.modal-title i');
                // cambiarModal(span, ' Nuevo Horario', icon, 'fa-calendar-circle-user', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                // select.forEach(function(s) {
                //     s.classList.remove('select2-warning');
                //     s.classList.add('select2-success');
                // });div_hor_filter
                document.getElementById("div_hor_header").style.display = "block";
                document.getElementById("div_hor_filter").style.display = "block";
                document.getElementById("div_hor").style.display = "block";
                document.getElementById("div_content").style.display = "table-column";
                document.getElementById("div_header").style.display = "none";
                // form.reset();
                // form.classList.remove('was-validated');
                // setChange(cboEmpresa, 0);
                // setChange(cboRol, 0);
                // setChange(cboPlaca, []);

            });
        }
        $('#tblHorario tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_e = e["id"];
            console.log(id_e)
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_e);
            accion = 0;
            confirmarEliminar('este', 'horario de empleado', function(r) {
                if (r) {
                    confirmarAccion(src, 'horario', tabla, '', function(r) {
                        cargarCombo('Conductor', '', 2);
                        cargarCombo('Despachado', '', 6);
                        cargarCombo('Responsable', '', 7)
                    })
                }
            });
        });

        $('#tblHorario tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            const icon = document.querySelector('.modal-title i');
            cambiarModal(span, ' Editar Horario', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            select.forEach(function(s) {
                s.classList.remove('select2-success');
                s.classList.add('select2-warning');
            });
            id.value = row["id"];
            nombre.value = row["nombre"];
            cedula.value = row["cedula"];
            apellido.value = row["apellido"];
            celular.value = row["telefono"];
            setChange(cboEmpresa, row["id_empresa"])
            setChange(cboRol, row["id_rol"])
            let arr = convertirArray(row["id_placa"])
            $(cboPlaca).val(arr).trigger('change');
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            $('.ten').hide();
            const ced = cedula.value.trim(),
                nom = nombre.value.trim().toUpperCase(),
                ape = apellido.value.trim().toUpperCase(),
                tel = celular.value.trim(),
                emp = cboEmpresa.value,
                rol = cboRol.value,
                pla = $(cboPlaca).val();


            if (!this.checkValidity() || ced.length < 10 || tel.length < 10) {
                this.classList.add('was-validated');
                if (ced.length > 0 && ced.length < 10) {
                    cedula.parentNode.querySelector(".ten").style.display = "block";
                }
                if (tel.length > 0 && tel.length < 10) {
                    celular.parentNode.querySelector(".ten").style.display = "block";
                }
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('cedula', ced);
            datos.append('nombre', nom);
            datos.append('apellido', ape);
            datos.append('celular', tel);
            datos.append('id_empresa', emp);
            datos.append('id_rol', rol);
            datos.append('id_placa', pla);
            datos.append('accion', accion);
            accion = 0;
            empresa_filter = cboEmpresaFilter.value;
            confirmarAccion(datos, 'horario', tabla, modal, function(r) {
                cargarCombo('Conductor', '', 2);
                cargarCombo('Despachado', '', 6);
                cargarCombo('Responsable', '', 7);
            });
        });
    })
</script>