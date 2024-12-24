<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ingreso personal</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Horario personal</h1>
            </div>
            <?php if (isset($_SESSION["crear20"]) && $_SESSION["crear20"] === true) : ?>
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
                                    <h3 style="white-space:normal;" class="card-title ">Listado de empleados / clientes</h3>
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
                    <div id="card_empleados" class="card-body p-0">
                        <table id="tblEmpleados" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>CÉDULA</th>
                                    <th>NOMBRES</th>
                                    <th>APELLIDOS</th>
                                    <th>TELÉFONO</th>
                                    <th>EMPRESA</th>
                                    <th class="text-center">ROL</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Empleado/Cliente</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal" style="padding-block:1rem .5rem">
                    <input type="hidden" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="nombre" class="label"><i class="fa-solid fa-signature"></i> Nombres</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="apellido" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="apellido" class="label"><i class="fa-solid fa-signature"></i> Apellidos</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-data">
                                                <input autocomplete="off" id="cedula" inputmode="numeric" class="input-nuevo" type="text" oninput="validarNumber(this,/[^0-9]/g,true)" maxlength="10" required>
                                                <div class="line underline"></div>
                                                <label for="cedula" class="label">
                                                    <i class="fa-solid fa-id-card"></i> Cédula</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                                <div class="ten">*Debe contener 10 dígitos</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-data">
                                                <input autocomplete="off" id="celular" inputmode="numeric" class="input-nuevo" type="text" oninput="validarNumber(this,/[^0-9]/g,true)" maxlength="10" required>
                                                <div class="line underline"></div>
                                                <label for="celular" class="label">
                                                    <i class="fa-solid fa-mobile-screen-button"></i> Teléfono</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                                <div class="ten">*Debe contener 10 dígitos</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label id="lblE" class="mb-0 combo"><i class="fas fa-buildings"></i> Empresa</label>
                                        <div class="row">
                                            <div class="col">
                                                <select id="cboEmpresa" class="cbo modalB form-control select2 select2-success" data-dropdown-css-class="select2-dark" data-placeholder="SELECCIONE" required>
                                                </select>
                                                <div id="Empresa" class="invalid-feedback">*Campo obligatorio</div>
                                            </div>
                                            <div class="span-btn cat" style="padding-right:.5rem;">
                                                <span class="new-span badge bg-gradient-dark" data-icon="fa-buildings" data-title='Nueva' data-value="Empresa" data-target='#modalS' data-toggle='modal' title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                                <?php if ($_SESSION["editar20"]) : ?>
                                                    <span style="display:none" class="dis e-span badge bg-gradient-dark" data-icon="fa-buildings" data-value="Empresa" data-target='#modalS' data-toggle='modal' title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                                <?php endif; ?>
                                                <?php if ($_SESSION["eliminar20"]) : ?>
                                                    <span style="display:none" class="dis d-span badge bg-gradient-dark" data-value="Empresa" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label id="lblR" class="mb-0 combo"><i class="fas fa-id-badge"></i> Rol</label>
                                        <div class="row">
                                            <div class="col">
                                                <select id="cboRol" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" data-placeholder="SELECCIONE" required>
                                                </select>
                                                <div class="invalid-feedback">*Campo obligatorio</div>
                                            </div>
                                            <div class="span-btn r" style="padding-right:.5rem;">
                                            <span class="new-span badge bg-gradient-dark" data-icon="fa-id-badge" data-title='Nuevo' data-value="Rol" data-target='#modalS' data-toggle='modal' title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                                <span style="display:none" class="dis e-span badge bg-gradient-dark" data-target='#modalS' data-icon="fa-id-badge" data-value="Rol" data-toggle='modal' title="Editar"><i class="fa-solid fa-pencil"></i></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="div_placas">
                                    <div class="form-group mb-4">
                                        <label id="lblP" class="mb-0 combo"><i class="fas fa-rectangle-barcode"></i> Placa <span style="font-size:70%;">(opcional)</span> </label>
                                        <div class="row">
                                            <div class="col">
                                                <select id="cboPlaca" class="form-control select2 select2-success" multiple="multiple" data-dropdown-css-class="select2-dark" style="width: 100%;">
                                                </select>
                                                <div class="invalid-feedback">*Campo obligatorio</div>
                                            </div>
                                            <div class="span-btn p" style="padding-right:.5rem;">
                                                <span class="new-span badge bg-gradient-dark" data-icon="fa-rectangle-barcode" data-title='Nueva' data-value="Placa" data-target='#modalS' data-toggle='modal' title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<div class="modal fade" id="modalS" style="background-color:#424a51b0;-webkit-backdrop-filter:blur(16px);backdrop-filter:blur(16px);">
    <div class="modal-dialog modal-sm" style="top:20%">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 id="span-title" class="modal-title"><i class="fa-solid "></i><span></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevoS" class="needs-validation" autocomplete="off" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="idS" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="nombreS" class="input-nuevo" type="text" required>
                                <label class="label"></label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardarS" class="btn bg-gradient-green"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
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
        "dom": '<"row"<"col-sm-6 select-filter"><"col-sm-6"p>>t',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
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
                responsivePriority: 1,
            },
            {
                targets: 6,
                className: "text-center",
                render: function(data, type, full, meta) {
                    return "<span class='alert alert-default-primary'>" + data + "</span>";
                }
            },
            {
                targets: 7,
                responsivePriority: 2,
                data: "acciones",
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
    }

    $(document).ready(function() {
        let accion = 0;
        let accion_select = 0;
        let empresa_filter = 0;

        if (!$.fn.DataTable.isDataTable('#tblEmpleados')) {
            tabla = $("#tblEmpleados").DataTable({
                "ajax": {
                    "url": "controllers/empleados.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.accion = accion;
                        data.id_empresa = empresa_filter;
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
                localStorage.setItem('empleados', JSON.stringify(tablaData));
            });
        }

        $('.select-filter').html('<div class="row" id="rowFilter" style="padding:.25rem .55rem .25rem;flex-wrap:nowrap" > <div style="max-width:max-content" class="col-sm-3"><label style="padding-block:.5rem;white-space:nowrap" class="col-form-label" ><i class="fas fa-buildings"></i> Empresa:</label></div> <div class="col-sm"><select id="cboEmpresaFilter" class="cbo form-control select2 select2-dark" data-dropdown-css-class="select2-dark" data-placeholder="TODO"></select> </div>  </div>');
        // $('.select-filter').html('<div class="row"><div class="col-sm-2"><label>Empresa: </label></div><div class="col-sm-10"><select id="cboEmpresaFilter" class="cbo form-control select2 select2-dark" data-dropdown-css-class="select2-dark" data-placeholder="SELECCIONE"></select></div></div>');

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
            btnNuevo = document.getElementById('btnNuevo');


        const id = document.getElementById('id'),
            cedula = document.getElementById('cedula'),
            nombre = document.getElementById('nombre'),
            apellido = document.getElementById('apellido'),
            celular = document.getElementById('celular'),
            cboEmpresa = document.getElementById('cboEmpresa'),
            cboEmpresaFilter = document.getElementById('cboEmpresaFilter'),
            cboRol = document.getElementById('cboRol'),
            cboPlaca = document.getElementById('cboPlaca');

        let datos_empresa;

        cargarCombo('Empresa');

        cargarCombo('EmpresaFilter', '', 10)

        cargarCombo('Placa');

        cargarCombo('Rol');

        $('#cboRol').select2({
            minimumResultsForSearch: -1,
        })

        $('#cboEmpresa').select2({
            minimumResultsForSearch: -1,
        })

        $('#cboPlaca').select2({})

        $('#cboEmpresaFilter').select2({
            width: '100%',
        })

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

        $(".new-span").on('click', function() {
            accion_select = 1;
            name = this.dataset.value;
            const selectE = document.querySelector('#cbo' + name);
            const iconS = this.dataset.icon;
            const titleS = this.dataset.title;
            inputId.value = selectE.value;
            cambiarModal(spanE, ' ' + titleS + ' ' + name, iconElement, iconS, elementsE, 'bg-gradient-blue', 'bg-gradient-green', modalS, 'modal-new', 'modal-change')
            formS.reset();
            formS.classList.remove('was-validated');
        });

        $(".e-span").on('click', function() {
            accion_select = 2;
            name = this.dataset.value;
            const selectE = document.getElementById('cbo' + name);
            const iconS = this.dataset.icon;
            inputId.value = selectE.value;
            inputContent.value = selectE.options[selectE.selectedIndex].textContent;
            cambiarModal(spanE, ' Editar ' + name, iconElement, iconS, elementsE, 'bg-gradient-green', 'bg-gradient-blue', modalS, 'modal-change', 'modal-new')
        });

        formS.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                inputContent.focus();
                return;
            } else {
                const ids = inputId.value;
                const nombre = inputContent.value.trim().toUpperCase();
                const tbl = 'tbl' + name.toLowerCase();
                const data = new FormData();
                data.append('id', ids);
                data.append('nombre', nombre);
                data.append('accion', accion_select);
                data.append('tabla', tbl);
                confirmarAccion(data, 'producto', null, modalS, function(res) {
                    if (res) {
                        cargarCombo(name, ids);
                        cargarCombo('EmpresaFilter', '', 10)
                    }
                });
            }
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                const icon = document.querySelector('.modal-title i');
                cambiarModal(span, ' Nuevo Empleado/Cliente', icon, 'fa-user-plus', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                select.forEach(function(s) {
                    s.classList.remove('select2-warning');
                    s.classList.add('select2-success');
                });
                form.reset();
                form.classList.remove('was-validated');
                setChange(cboEmpresa, 0);
                setChange(cboRol, 0);
                setChange(cboPlaca, []);
                $('.ten').hide();
            });
        }

        $(cboEmpresaFilter).on("change", function() {
            empresa_filter = this.value;
            console.log(empresa_filter);
            accion = 0;
            tabla.ajax.reload(null, false);
        });

        $(cboEmpresa).change(function() {
            estilosSelect2(this, 'lblE')
        });

        $(cboRol).change(function() {
            opcionSelect(this, 'r')
            estilosSelect2(this, 'lblR')
        });

        $(cboPlaca).change(function() {
            estilosSelect2(this, 'lblP', 'multiple')
        });

        $('#tblEmpleados tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_e = e["id"];
            console.log(id_e)
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_e);
            accion = 0;
            confirmarEliminar('este', 'empleado', function(r) {
                if (r) {
                    confirmarAccion(src, 'empleados', tabla, '', function(r) {
                        cargarCombo('Conductor', '', 2);
                        cargarCombo('Despachado', '', 6);
                        cargarCombo('Responsable', '', 7)
                    })
                }
            });
        });

        $('#tblEmpleados tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            const icon = document.querySelector('.modal-title i');
            cambiarModal(span, ' Editar Empleado/Cliente', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
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
            confirmarAccion(datos, 'empleados', tabla, modal, function(r) {
                cargarCombo('Conductor', '', 2);
                cargarCombo('Despachado', '', 6);
                cargarCombo('Responsable', '', 7);
            });
        });
    })
</script>