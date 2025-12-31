<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Ingreso personal</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Ingreso personal</h1>
            </div>
            <?php if (isset($_SESSION["crear32"]) && $_SESSION["crear32"] === true) : ?>
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
                                    <h3 style="white-space:normal;" class="card-title ">Listado de personal</h3>
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
                        <table id="tblPersonal" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>CÉDULA</th>
                                    <th>NOMBRES</th>
                                    <th>APELLIDOS</th>
                                    <th>FECHA INICIO TRABAJO</th>
                                    <th>FECHA CORTE CONTRATO</th>
                                    <th>SUELDO</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Personal</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" enctype="multipart/form-data" class="needs-validation" novalidate>
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

                                        <div class="col-sm-6 form-group">
                                            <label id="lblSueldo" class="combo mb-0 d-flex justify-content-between" for="sueldo">
                                                <span><i class="fas fa-sack-dollar"></i> Sueldo</span>
                                                <label class="d-flex align-items-center mb-0" for="isSbu" style="color:#5b5b5b;gap:.3rem;font-size:.9rem;"> SBU
                                                    <label class="switch-2" style="font-size:.8rem">
                                                        <input id="isSbu" class="switch__input" type="checkbox" onkeydown="toggleWithEnter(event, this)">
                                                        <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                            <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11"></polyline>
                                                        </svg>
                                                    </label>
                                                </label>

                                            </label>
                                            <input id="sueldo" type="text" oninput="validarNumber(this,/[^0-9.]/g)" maxlength="8" autocomplete="off" value="" style="height:38px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label class="combo" style="line-height:.8" for="fecha_ini">
                                                <i class="fas fa-calendar"></i> Fecha de inicio</label>
                                            <input id="fecha_ini" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="height:36px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label class="combo" style="line-height:.8" for="fecha_cor">
                                                <i class="fas fa-calendar"></i> Fecha de corte</label>
                                            <input id="fecha_cor" type="date" autocomplete="off" value="" style="height:36px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label id="lblR" class="combo" style="font-size:1.15rem"><i class="fas fa-id-badge"></i> Rol</label>
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
                                <div class="col-md-6 mb-3">
                                    <label class="combo" style="font-size: 1.15rem;"><i class="fa-solid fa-file-pdf"></i> Pdf cédula</label>
                                    <input type="file" name="fileCedula" id="fileCedula" class="form-control" accept=".pdf">
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
    var mostrarCol = '<?php echo $_SESSION["editar32"] || $_SESSION["eliminar32"] ?>';
    var editar = '<?php echo $_SESSION["editar32"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar32"] ?>';

    configuracionTable = {
        "responsive": true,
        "dom": '<"row"<"col-md-6"B><"col-md-6"p>>t',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        columnDefs: [{
                targets: 0,
                data: null,
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
            },
            {
                targets: 7,
                className: "text-center",
                render: function(data, type, full, meta) {
                    return "<span class='alert alert-default-primary'>" + data + "</span>";
                }
            },
            {
                targets: 8,
                responsivePriority: 2,
                data: null,
                visible: mostrarCol ? true : false,
                render: function(data, type, row, full, meta) {
                    // let ruta = true;
                    let ruta = row.ruta ?? '';
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
                        (ruta !== '' ?
                            " <a href='/aistermcon/utils/download.php?file=" + encodeURIComponent(ruta) + "&route=cedula_personal" + "'target='_blank' style='font-size:1.56rem;padding:3px 8px' class='btn btnDescargar' title='PDF Cédula'>" +
                            " <i class='fas fa-file-user'></i>" +
                            "</a>" :
                            " <span style='font-size:1.4rem;padding:3px 4px;cursor:not-allowed; color:darkgrey' class='btn' >" +
                            " <i class='fas fa-file-slash'></i>" +
                            "</span>") +
                        " </center>"
                    );
                },
            },
        ],
        buttons: [{
                extend: "excelHtml5",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                    search: "applied",
                    order: "applied",
                },
                text: "<i class='fa-regular fa-file-xls fa-xl'style='color: #0a8f00'></i>",
                titleAttr: "Exportar a Excel",
                title: "LISTADO DE PERSONAL",
                className: "btn btn-light",
            },
            {
                extend: "pdfHtml5",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                    search: "applied",
                    order: "applied",
                },
                text: "<i class='fa-regular fa-file-pdf fa-xl' style='color: #bd0000'></i>",
                titleAttr: "Exportar a PDF",
                className: "btn btn-light",
                title: "LISTADO DE PERSONAL",
                customize: function(doc) {
                    var now = new Date();
                    var jsDate = now.getDate() + "/" + (now.getMonth() + 1) + "/" + now.getFullYear();
                    doc.content.splice(0, 1);
                    doc.pageMargins = [40, 90, 40, 50];
                    doc["header"] = function() {
                        return {
                            columns: [{
                                    alignment: "left",
                                    text: "LISTADO DE PERSONAL",
                                    fontSize: 14,
                                    margin: [20, 25],
                                },
                                {
                                    alignment: "right",
                                    margin: [20, 0],
                                    text: ["Creado el: ", {
                                        text: jsDate.toString()
                                    }],
                                },

                            ],
                            margin: 20,
                        };
                    };

                    var objLayout = {};
                    objLayout["hLineWidth"] = function(i) {
                        return 1;
                    };
                    objLayout["vLineWidth"] = function(i) {
                        return 0.5;
                    };
                    objLayout["hLineColor"] = function(i) {
                        return "#aaa";
                    };
                    objLayout["vLineColor"] = function(i) {
                        return "#aaa";
                    };
                    doc.content[0].layout = objLayout;

                    doc["footer"] = function(page, pages) {
                        return {
                            columns: [{
                                alignment: "right",
                                text: [
                                    "pag ",
                                    {
                                        text: page.toString()
                                    },
                                    " de ",
                                    {
                                        text: pages.toString()
                                    },
                                ],
                            }, ],
                            margin: [20, 10, 40, 10],
                        };
                    };
                },
            },
            {
                extend: "print",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                    search: "applied",
                    order: "applied"
                },
                oSelectorOpts: {
                    filter: "applied",
                    order: "current"
                },
                text: "<i class='fa fa-print fa-xl'</i>",
                titleAttr: "Imprimir",
                className: "btn btn-light",
                title: "LISTADO DE PERSONAL",
            },
            {
                extend: "colvis",
                className: "btn btn-light font-weight-bold",
            }
        ]
    }

    $(document).ready(function() {
        let accion = 0;

        if (!$.fn.DataTable.isDataTable('#tblPersonal')) {
            tabla = $("#tblPersonal").DataTable({
                "ajax": {
                    "url": "controllers/personal.controlador.php",
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
                localStorage.setItem('personal', JSON.stringify(tablaData));
            });
        }
        let ruta_ced = '';
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
            inputFile = document.getElementById('fileCedula');

        const id = document.getElementById('id'),
            cedula = document.getElementById('cedula'),
            nombre = document.getElementById('nombre'),
            apellido = document.getElementById('apellido'),
            fecha_ini = document.getElementById('fecha_ini'),
            fecha_cor = document.getElementById('fecha_cor'),
            sueldo = document.getElementById('sueldo'),
            cboRol = document.getElementById('cboRol');


        cargarCombo('Rol');

        $('#cboRol').select2({
            minimumResultsForSearch: -1,
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

        isSbu.addEventListener('change', function() {
            // const sueldoInput = document.getElementById('sueldo');
            if (this.checked) {
                sueldo.value = sbu_config;
                sueldo.setAttribute('readonly', true);
            } else {
                sueldo.removeAttribute('readonly');
                sueldo.value = ''; // Opcional: elimina esta línea si quieres conservar el valor anterior
            }
        });

        sueldo.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value === sbu_config) {
                isSbu.checked = true;
                this.setAttribute('readonly', true);
            } else {
                isSbu.checked = false;
                this.removeAttribute('readonly');
            }
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                const icon = document.querySelector('.modal-title i');
                cambiarModal(span, ' Nuevo Personal', icon, 'fa-user-plus', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                select.forEach(function(s) {
                    s.classList.remove('select2-warning');
                    s.classList.add('select2-success');
                });
                form.reset();
                form.classList.remove('was-validated');
                setChange(cboRol, 0);
                $('.ten').hide();
            });
        }

        $('#tblPersonal tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3;
            const id_e = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_e);
            accion = 0;
            confirmarEliminar('este', 'empleado', function(r) {
                if (r) {
                    confirmarAccion(src, 'personal', tabla, '', null)
                }
            });
        });

        $('#tblPersonal tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            const icon = document.querySelector('.modal-title i');
            cambiarModal(span, ' Editar Personal', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            select.forEach(function(s) {
                s.classList.remove('select2-success');
                s.classList.add('select2-warning');
            });
            form.reset();
            form.classList.remove('was-validated');
            id.value = row["id"];
            nombre.value = row["nombre"];
            cedula.value = row["cedula"];
            apellido.value = row["apellido"];
            ruta_ced = row["ruta"] ?? '';
            
            if (row["issbu"]==true) {
                isSbu.click();
            }else {
                sueldo.removeAttribute('readonly');
            } 
            // console.log(row["issbu"])
            // isSbu.checked = row["issbu"] ?? false;
            setChange(cboRol, row["id_rol"])
            fecha_ini.value = convertirFecha(row["fecha_ini"]) ?? '';
            fecha_cor.value = convertirFecha(row["fecha_cor"]) ?? '';
            sueldo.value = parseFloat(row["sueldo"].replace(/[$,]/g, ''));
            $('.ten').hide();
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            $('.ten').hide();

            const file = inputFile.files[0];
            let datos = new FormData();
            // Validar que el archivo es .pdf
            if (file && file.type !== "application/pdf") {
                mostrarToast(
                    'warning',
                    'Advertencia',
                    'fa-triangle-exclamation',
                    'El archivo insertado no es valido, por favor inserta un archivo .pdf', 3000
                )
                return;
            } else if (file && file.type == "application/pdf") {
                datos.append('fileCedula', file);
            }

            const ced = cedula.value.trim(),
                nom = nombre.value.trim().toUpperCase(),
                ape = apellido.value.trim().toUpperCase(),
                f_i = fecha_ini.value,
                f_c = fecha_cor.value,
                sue = sueldo.value;

            if (!this.checkValidity() || ced.length < 10) {
                this.classList.add('was-validated');
                if (ced.length > 0 && ced.length < 10) {
                    cedula.parentNode.querySelector(".ten").style.display = "block";
                }
                return;
            }

            datos.append('id', id.value);
            datos.append('cedula', ced);
            datos.append('nombre', nom);
            datos.append('apellido', ape);
            datos.append('fecha_ini', f_i);
            datos.append('fecha_cor', f_c);
            datos.append('sueldo', sue);
            datos.append('ruta', ruta_ced);
            datos.append('isSbu', isSbu.checked);
            datos.append('accion', accion);
            confirmarAccion(datos, 'personal', tabla, modal, function(r) {})
        });

        function convertirFecha(fecha) {
            if (!fecha) {
                return null;
            }
            let [day, month, year] = fecha.split('/');
            return `${year}-${month}-${day}`;
        }
    })
</script>