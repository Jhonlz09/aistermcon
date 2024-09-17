<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Clientes</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Clientes</h1>
            </div>
            <?php if (isset($_SESSION["crear14"]) && $_SESSION["crear14"] === true) : ?>
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
                                    <h3 class="card-title ">Listado de clientes</h3>
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
                        <table id="tblClientes" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>TIPO</th>
                                    <th>RUC</th>
                                    <th>CÉDULA</th>
                                    <th>CLIENTE</th>
                                    <th>RAZÓN SOCIAL</th>
                                    <th>DIRECCIÓN</th>
                                    <th>TELÉFONO</th>
                                    <th>EMAIL</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Rol</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal">
                    <input type="hidden" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-0 combo"><i class="fas fa-scale-unbalanced"></i> Tipo</label>
                                        <div class="row">
                                            <div class="col">
                                                <select name="c" id="cboTipo" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                    <option value="1">JURÍDICA</option>
                                                    <option value="2">NATURAL</option>
                                                </select>
                                                <div class="invalid-feedback">*Campo obligatorio</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-data">
                                        <input autocomplete="off" inputmode="numeric" id="ruc" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="ruc" class="label"><i class="fas fa-building-user"></i> Ruc</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-data">
                                        <input autocomplete="off" inputmode="numeric" maxlength="10" oninput="validarNumber(this,/[^0-9]/g)" id="ci" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="ci" class="label"><i class="fas fa-id-card"></i> Cédula</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-data">
                                        <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="nombre" class="label"><i class="fas fa-signature"></i> Nombre comercial</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-data">
                                        <input autocomplete="off" id="razon_social" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="razon_social" class="label"><i class="fas fa-input-text"></i> Razón social</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-data">
                                        <input autocomplete="off" inputmode="numeric" id="telefono" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="telefono" class="label"><i class="fas fa-phone"></i> Teléfono</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="direccion" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="direccion" class="label"><i class="fas fa-map-location-dot"></i> Dirección</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="correo" class="input-nuevo" type="text" required>
                                        <div class="line underline"></div>
                                        <label for="correo" class="label"><i class="fa-solid fa-envelope"></i> Correo</label>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardar" class="btn bg-gradient-green"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar14"] || $_SESSION["eliminar14"] ?>';
    var editar = '<?php echo $_SESSION["editar14"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar14"] ?>';

    OverlayScrollbars(document.querySelector('.scroll-modal'), {
        autoUpdate: true,
        scrollbars: {
            autoHide: 'leave'
        }
    });

    configuracionTable = {
        "responsive": true,
        "dom": mostrarCol ? '<"row"<"col-md-6"B><"col-md-6"p>>t' : 'pt',
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
                "targets": 1,
                visible: false,
            },
            {
                "targets": 4,
                responsivePriority: 1,
            },
            {
                "targets": 5,
                visible: false,
            },
            {
                "targets": 7,
                className: '.txt-ellipsis'
            },
            {
                "targets": 8,
                visible: false,
                render: function(data, type, row, meta) {
                    // Dividir los correos por comas
                    var emails = data.split(',');

                    // Crear enlaces de correo electrónico
                    var emailLinks = emails.map(function(email) {
                        return '<a href="mailto:' + email.trim() + '" class="text-info">' + email.trim() + '</a>';
                    });

                    // Unir todos los enlaces con un delimitador, como un espacio o una coma
                    return emailLinks.join(' ');
                }
            },
            {
                targets: 9,
                data: "acciones",
                responsivePriority: 2,
                visible: mostrarCol ? true : false,
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
        buttons: [{
                extend: "excelHtml5",
                exportOptions: {
                    columns: ":visible:not(:last-child)",
                    search: "applied",
                    order: "applied",
                },
                text: "<i class='fa-regular fa-file-xls fa-xl'style='color: #0a8f00'></i>",
                titleAttr: "Exportar a Excel",
                title: "LISTADO DE CLIENTES",
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
                title: "LISTADO DE CLIENTES",
                customize: function(doc) {
                    var now = new Date();
                    var jsDate = now.getDate() + "/" + (now.getMonth() + 1) + "/" + now.getFullYear();
                    doc.content.splice(0, 1);
                    doc.pageMargins = [40, 90, 40, 50];
                    doc["header"] = function() {
                        return {
                            columns: [{
                                    alignment: "left",
                                    text: "LISTADO DE CLIENTES",
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
                title: "LISTADO DE PRODUCTOS",
            },
            {
                extend: "colvis",
                className: "btn btn-light font-weight-bold",
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            }
        ]
    }




    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#tblClientes')) {
            tabla = $("#tblClientes").DataTable({
                "ajax": {
                    "url": "controllers/clientes.controlador.php",
                    "type": "POST",
                    "dataSrc": ''
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
                localStorage.setItem('clientes', JSON.stringify(tablaData));
            });
        }
        let accion = 0;
        const modal = document.getElementById('modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            icon = document.querySelector('.modal-title i'),
            btnNuevo = document.getElementById('btnNuevo');

        const id = document.getElementById('id'),
            nombre = document.getElementById('nombre'),
            ruc = document.getElementById('ruc'),
            ci = document.getElementById('ci'),
            razon = document.getElementById('razon_social'),
            correo = document.getElementById('correo'),
            direccion = document.getElementById('direccion'),
            telefono = document.getElementById('telefono'),

            cboTipo = document.getElementById('cboTipo');




        $(modal).on("shown.bs.modal", () => {
            ruc.focus();
        });


        $('#cboTipo').select2({
            minimumResultsForSearch: -1,
        })

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nuevo Cliente', icon, 'fa-user-tag', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                form.reset();
                form.classList.remove('was-validated');
                setChange(cboTipo, 1)
                ruc.disabled = false
                ci.disabled = false
            });
        }

        $('#tblClientes tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'cliente', function(r) {
                if (r) {
                    confirmarAccion(src, 'clientes', tabla, '', function(r) {
                        if (r) {
                            cargarCombo('Clientes', '', 1)
                            cargarCombo('ClienteEntrada',  '', 1)
                        }
                    })
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    $(activeModal).modal('hide');
                }
            }
        });

        $('#tblClientes tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar Cliente', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            ci.disabled=false;
            id.value = row["id"];
            nombre.value = row["nombre"];
            ruc.value = row["ruc"];
            ci.value = row["cedula"];

            razon.value = row["razon_social"];
            setChange(cboTipo, row["id_tipo"])
            direccion.value = row["direccion"];
            telefono.value = row["telefono"];
            correo.value = row["correo"];
            form.classList.remove('was-validated');

        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const nom = nombre.value.trim().toUpperCase(),
                raz = razon.value.trim().toUpperCase(),
                ruc_ = ruc.value.trim(),
                ced = ci.value.trim(),
                tip = cboTipo.value,
                tel = telefono.value.trim(),
                dir = direccion.value.trim().toUpperCase(),
                cor = correo.value.trim();


            if (ruc_ === '' && ced === '') {
                // No hacer nada y continuar la ejecución fuera del if
            }
            // Si solo ruc_ está vacío, deshabilitar temporalmente ruc
            else if (ruc_ === '') {
                ruc.disabled = true;
            }
            // Si solo ced está vacío, deshabilitar temporalmente ced
            else if (ced === '') {
                ci.disabled = true;
            }

            if (!this.checkValidity()) {
                    this.classList.add('was-validated');
                    return;
                }

           

            let datos = new FormData();
            const id_e = id.value;
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('razon', raz);
            datos.append('id_tipo', tip);
            datos.append('ruc', ruc_);
            datos.append('ced', ced);
            datos.append('tel', tel);
            datos.append('dir', dir);
            datos.append('correo', cor);
            datos.append('accion', accion);
            confirmarAccion(datos, 'clientes', tabla, modal, function(r) {
                if (r) {
                    // cargarCombo('Clientes', '', 1, true).then(datos_ => {
                    //     datos_cliente = datos_;
                    // });
                    cargarCombo('Clientes', '', 1)
                    cargarCombo('ClienteEntrada',  '', 1)
                }
            })

        });
    })
</script>