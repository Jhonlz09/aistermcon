<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<head>
    <title>Inventario</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Inventario</h1>
            </div>
            <?php if ($_SESSION["crear2"]) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-success" data-toggle="modal" data-target="#modalI">
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
                                    <h3 class="card-title ">Listado de productos</h3>
                                </div>
                                <div class="col-sm-8 p-0">
                                    <div class="card-tools">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search icon"></i></span>
                                            <input autocomplete="off" style="border:none" type="text" id="_search" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table id="tblInventario" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>CÓDIGO</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>CATEGORÍA</th>
                                    <th>UNIDAD</th>
                                    <th>UBICACIÓN</th>
                                    <th>DAÑADO</th>
                                    <th class="text-center">CANTIDAD</th>
                                    <th class="text-center">CANT. DISP.</th>
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
<div class="modal fade" id="modalI">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h4 class="modal-title"><i class="fa-solid fa-layer-plus"></i><span> Nuevo Producto</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal">
                    <input type="hidden" id="id" value="">
                    <div class="row" style="align-items:flex-start">
                        <div class="col-md-6">
                            <div class="input-data">
                                <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                <label class="label"><i class="fa-solid fa-input-text"></i> Descripción</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-data">
                                        <input autocomplete="off" id="codigo" name="codigo" class="input-nuevo" type="text" required>
                                        <label class="label"><i class="fa-solid fa-barcode"></i> Código</label>
                                        <div class="invalid-feedback">*Este campo es requerido.</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-data s1">
                                        <input type="text" id="stock" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" required>
                                        <label class="label"><i class="fa-solid fa-boxes-stacked"></i> Cantidad</label>
                                        <div class="invalid-feedback">*Este campo es requerido.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class=" input-data" style="margin-bottom:26px">
                                        <input type="text" id="stock_min" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" required>
                                        <label style="font-size:21px" id="min" class="label"><i class="fa-solid fa-box-taped"></i> Cantidad Min.</label>
                                        <div class="invalid-feedback">*Este campo es requerido.</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-data" style="margin-bottom:26px">
                                        <input type="text" id="stock_mal" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)">
                                        <label class="label"><i class="fa-solid fa-hammer-crash"></i> Dañado</label>
                                        <div class="invalid-feedback">*Este campo es requerido.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="lblC" class="mb-0 combo"><i class="fa-solid fa-tags"></i> Categoría</label>
                                <div class="row">
                                    <div class="col">
                                        <select id="cboCategoria" class="cbo modalB form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div id="Categoria" class="invalid-feedback">*Seleccione una categoría</div>
                                    </div>
                                    <div class="span-btn cat" style="padding-right:.5rem;">
                                        <span class="new-span badge bg-gradient-dark" data-icon="fa-tags" data-value="Categoria" data-target='#modalS' data-toggle='modal' title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                        <?php if ($_SESSION["editar2"]) : ?>
                                            <span style="display:none" class="dis e-span badge bg-gradient-dark" data-icon="fa-tags" data-value="Categoria" data-target='#modalS' data-toggle='modal' title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                        <?php endif; ?>
                                        <?php if ($_SESSION["eliminar2"]) : ?>
                                            <span style="display:none" class="dis d-span badge bg-gradient-dark" data-value="Categoria" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="lblU" class="mb-0 combo"><i class="fa-solid fa-ruler"></i> Unidad</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="cboUnidad" id="cboUnidad" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div id="Unidad" class="invalid-feedback">*Seleccione una unidad</div>
                                    </div>
                                    <div class="span-btn u" style="padding-right:.5rem">
                                        <span class="new-span badge bg-gradient-dark" data-icon="fa-ruler" data-value="Unidad" data-target='#modalS' data-toggle='modal' title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                        <?php if ($_SESSION["editar2"]) : ?>
                                            <span style="display:none" class="dis e-span badge bg-gradient-dark" data-icon="fa-ruler" data-value="Unidad" data-target='#modalS' data-toggle='modal' title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                        <?php endif; ?>
                                        <?php if ($_SESSION["eliminar2"]) : ?>
                                            <span style="display:none" class="dis d-span badge bg-gradient-dark" data-value="Unidad" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label id="lblP" class="mb-0 combo"><i class="fa-solid fa-route"></i> Ubicación</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="cboUbicacion" id="cboUbicacion" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div id="Ubicacion" class="invalid-feedback">*Seleccione una ubicación</div>
                                    </div>
                                    <div class="span-btn p" style="padding-right:.5rem;">
                                        <span class="new-span badge bg-gradient-dark" data-icon="fa-route" data-value="Ubicacion" data-target='#modalS' data-toggle='modal'><i class="fa-solid fa-plus"></i></span>
                                        <?php if ($_SESSION["editar2"]) : ?>
                                            <span style="display:none" class="dis e-span badge bg-gradient-dark" data-icon="fa-route" data-value="Ubicacion" data-target='#modalS' data-toggle='modal' title="Editar"><i class="fa-solid fa-pencil"></i></span>
                                        <?php endif; ?>
                                        <?php if ($_SESSION["eliminar2"]) : ?>
                                            <span style="display:none" class="dis d-span badge bg-gradient-dark" data-value="Ubicacion" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
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
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Modal SELECTS -->
<div class="modal fade" id="modalS" style="background-color:#424a51b0;backdrop-filter:blur(16px);">
    <div class="modal-dialog" style="top:20%">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h4 id="span-title" class="modal-title"><i class="fa-solid fa-route"></i><span></span></h4>
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
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardarS" class="btn bg-gradient-success"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Select2 -->
<script>
    cargarCombo('Categoria');
    cargarCombo('Unidad');
    cargarCombo('Ubicacion');
    OverlayScrollbars(document.querySelector('.scroll-modal'), {
        autoUpdate: true,
        scrollbars: {
            autoHide: 'leave'
        }
    });
    var mostrarCol = '<?php echo $_SESSION["editar2"] || $_SESSION["eliminar2"] ?>';
    var editar = '<?php echo $_SESSION["editar2"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar2"] ?>';

    configuracionTable = {
        "dom": mostrarCol ? '<"row"<"col-sm-6"B><"col-sm-6"p>>t' : 'pt',
        "responsive": true,
        "pageLength": 5, // Cambia este valor según tus necesidades
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
                targets: 6,
                className: 'text-center',
                visible: false
            },
            {
                targets: 7,
                className: 'text-center',
                visible: false
            },
            {
                targets: 8,
                className: 'text-center',
                render: function(data, type, row) {
                    let resultado = row.stock - row.stock_mal;
                    let stockMin = row.stock_min;
                    let comparacion = resultado < stockMin ? 'text-danger' : resultado > stockMin ? 'text-success' : 'text-info';

                    return `<span style='font-size:1.3rem' class="${comparacion} font-weight-bold">${resultado}</span>`;
                }
            },
            {
                targets: 9,
                data: "acciones",
                visible: mostrarCol ? true : false,
                render: function(data, type, row, full, meta) {
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modalI' data-toggle='modal'  title='Editar'>" +
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
                text: "<i class='fa-regular fa-file-excel fa-xl'style='color: #0a8f00'></i>",
                titleAttr: "Exportar a Excel",
                title: "LISTADO DE PRODUCTOS",
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
                title: "LISTADO DE PRODUCTOS",
                customize: function(doc) {
                    var now = new Date();
                    var jsDate = now.getDate() + "/" + (now.getMonth() + 1) + "/" + now.getFullYear();
                    doc.content.splice(0, 1);
                    doc.pageMargins = [40, 90, 40, 50];
                    doc["header"] = function() {
                        return {
                            columns: [{
                                    alignment: "left",
                                    text: "LISTADO DE PRODUCTOS",
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
        if (!$.fn.DataTable.isDataTable('#tblInventario')) {
            tabla = $("#tblInventario").DataTable({
                "ajax": {
                    "url": "controllers/inventario.controlador.php",
                    "type": "POST",
                    "dataSrc": ''
                },
                ...configuracionTable
            });
            tabla.on('draw.dt', function() {
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;

                handleScroll(b, s, w);

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('inventario', JSON.stringify(tablaData));
            });
        }
        let name;
        let accion = 0;
        const id = document.getElementById('id'),
            codigo = document.getElementById('codigo'),
            descripcion = document.getElementById('nombre'),
            stock = document.getElementById('stock'),
            stock_min = document.getElementById('stock_min'),
            stock_mal = document.getElementById('stock_mal'),
            cboCategoria = document.getElementById('cboCategoria'),
            cboUnidad = document.getElementById('cboUnidad'),
            cboUbicacion = document.getElementById('cboUbicacion');

        const btnNuevo = document.getElementById('btnNuevo'),
            inputId = document.getElementById('idS'),
            icon = document.querySelector('.modal-title i'),
            iconElement = document.querySelector('#span-title i'),
            modalS = document.getElementById('modalS'),
            modalE = document.getElementById('modalI'),
            inputContent = document.getElementById('nombreS'),
            form = document.getElementById('formNuevo'),
            formS = document.getElementById('formNuevoS'),
            span = document.querySelector('.modal-title span'),
            spanE = document.querySelector('#span-title span'),
            elements = document.querySelectorAll('#modalI .bg-gradient-success'),
            elementsE = document.querySelectorAll('#modalS .bg-gradient-success'),
            select = document.querySelectorAll('.modal-body select.select2');

        $(cboCategoria).select2({
            placeholder: 'SELECCIONA UNA OPCION',
            width: '100%',
        });

        $(cboUnidad).select2({
            placeholder: 'SELECCIONA UNA OPCION',
            width: '100%',
        })

        $(cboUbicacion).select2({
            placeholder: 'SELECCIONA UNA OPCION',
            width: '100%',
        })

        $(cboCategoria).change(function() {
            estilosSelect2(this, 'lblC');
            opcionSelect(this, 'cat')
        });

        $(cboUnidad).change(function() {
            estilosSelect2(this, 'lblU')
            opcionSelect(this, 'u')
        });

        $(cboUbicacion).change(function() {
            estilosSelect2(this, 'lblP')
            opcionSelect(this, 'p')
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    $(activeModal).modal('hide');
                }
            }
        });

        $(modalE).on("shown.bs.modal", function() {
            nombre.focus();
        });

        $(modalS).on("shown.bs.modal", function() {
            inputContent.focus();
        });

        $(modalS).on('hidden.bs.modal', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(body).addClass('modal-open');
            $(body).css('padding-right', '6px');
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', function() {
                accion = 1;
                cambiarModal(span, ' Nuevo Producto', icon, 'fa-layer-plus', elements, 'bg-gradient-blue', 'bg-gradient-success', modalE, 'modal-new', 'modal-change')
                select.forEach(function(s) {
                    s.classList.remove('select2-warning');
                    s.classList.add('select2-success');
                });
                form.reset();
                stock_mal.value = '0'
                $(".modal-body .select2").val(0).trigger("change");
                form.classList.remove('was-validated');
            });
        }

        $('#tblInventario tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar Producto', icon, 'fa-pen-to-square', elements, 'bg-gradient-success', 'bg-gradient-blue', modalE, 'modal-change', 'modal-new')
            select.forEach(function(s) {
                s.classList.remove('select2-success');
                s.classList.add('select2-warning');
            });
            id.value = row["id"];
            console.log(id.value)
            codigo.value = row["codigo"];
            descripcion.value = row["descripcion"];
            stock.value = row["stock"];
            stock_min.value = row["stock_min"];
            stock_mal.value = row["stock_mal"];
            setChange(cboCategoria, row["categoria_id"]);
            setChange(cboUnidad, row["unidad_id"]);
            setChange(cboUbicacion, row["percha_id"]);
        });

        $('#tblInventario tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id);
            confirmarEliminar('este', 'producto', function(res) {
                if (res) {
                    confirmarAccion(src, 'inventario', tabla, '', function(res) {
                        cargarAutocompletado();
                    })
                }
            });

        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            const data = new FormData();
            data.append('id', id.value);
            data.append('cod', codigo.value.trim().toUpperCase());
            data.append('des', descripcion.value.trim().toUpperCase());
            data.append('sto', stock.value);
            data.append('st_min', stock_min.value);
            data.append('st_mal', stock_mal.value);
            data.append('cat', cboCategoria.value);
            data.append('uni', cboUnidad.value);
            data.append('ubi', cboUbicacion.value);
            data.append('accion', accion);

            confirmarAccion(data, 'inventario', tabla, modalE, function(res){
                cargarAutocompletado();
            });
        });

        $(".new-span").on('click', function() {
            accion = 1;
            name = this.dataset.value;
            const selectE = document.querySelector('#cbo' + name);
            const iconS = this.dataset.icon;

            inputId.value = selectE.value;
            cambiarModal(spanE, ' Nueva ' + name, iconElement, iconS, elementsE, 'bg-gradient-blue', 'bg-gradient-success', modalS, 'modal-new', 'modal-change')
            formS.reset();
            formS.classList.remove('was-validated');
        });

        $(".e-span").on('click', function() {
            accion = 2;
            name = this.dataset.value;
            const selectE = document.getElementById('cbo' + name);
            const iconS = this.dataset.icon;

            inputId.value = selectE.value;
            inputContent.value = selectE.options[selectE.selectedIndex].textContent;
            cambiarModal(spanE, ' Editar ' + name, iconElement, iconS, elementsE, 'bg-gradient-success', 'bg-gradient-blue', modalS, 'modal-change', 'modal-new')
        });

        $(".d-span").on('click', function() {
            accion = 3;
            name = this.dataset.value;
            const id_val = document.getElementById('cbo' + name).value;
            const tbl = 'tbl' + name.toLowerCase();
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_val);
            src.append('tabla', tbl);
            confirmarEliminar('esta', name, function(res) {
                if (res) {
                    confirmarAccion(src, 'producto', null, '',  function(res) {
                        cargarCombo(name);
                    })
                }
            });
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
                data.append('accion', accion);
                data.append('tabla', tbl);
                confirmarAccion(data, 'producto', null, modalS, function(res) {
                    if (res) {
                        cargarCombo(name, ids);
                    }
                });
            }
        });

        function opcionSelect(select, name) {
            if (select.value !== '') {
                $('.' + name + ' .dis').show();
                return false
            } else {
                $('.' + name + ' .dis').hide();
                return true
            }
        }
    });
</script>