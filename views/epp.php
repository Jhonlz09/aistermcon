<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Equipo Epps</title>
</head>

<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Equipo Epp</h1>
            </div>
            <?php if (isset($_SESSION["crear4"]) && $_SESSION["crear4"] === true) : ?>
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
                                    <h3 class="card-title ">Listado de equipo epps</h3>
                                </div>
                                <div class=" col col-sm-auto">
                                    <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
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
                        <table id="tblCotizacion" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>CANTIDAD</th>
                                    <th>UND</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>ENTREGADO A</th>
                                    <th>COMPRADOR</th>
                                    <th>FECHA</th>
                                    <th class="text-center">ESTADO</th>
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
<script>
    var mostrarCol = '<?php echo $_SESSION["editar6"] || $_SESSION["eliminar6"] ?>';
    var editar = '<?php echo $_SESSION["editar6"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar6"] ?>';

    configuracionTable = {
        "responsive": true,
        "dom": 'pt',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        columnDefs: [{
                targets: 0,
                data: "acciones",
                className: "text-center",
                render: function(data, type, row, meta) {
                    return type === 'display' ? meta.row + 1 : meta.row;
                }
            },
            {
                targets: 8,
                data: "acciones",
                visible: true,
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
        let anio = year;
        if (!$.fn.DataTable.isDataTable('#tblCotizacion')) {
            tabla = $("#tblCotizacion").DataTable({
                "ajax": {
                    "url": "controllers/cotizacion.controlador.php",
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
                localStorage.setItem('cotizacion', JSON.stringify(tablaData));
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
            correo = document.getElementById('correo'),
            direccion = document.getElementById('direccion'),
            telefono = document.getElementById('telefono');

        $(modal).on("shown.bs.modal", () => {
            nombre.focus();
        });

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nuevo solic. compra', icon, 'fa-hand-holding-box', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                form.reset();
                form.classList.remove('was-validated');
                correo.disabled = false;
                telefono.disabled = false;
            });
        }

        $('#tblCotizacion tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'solic. compra', function(r) {
                if (r) {
                    confirmarAccion(src, 'cotizacion', tabla, '', function(r) {
                        if (r) {
                            cargarCombo('cotizacion');
                        }
                    });
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

        $('#tblCotizacion tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar solic. compra', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombre.value = row["nombre"];
            direccion.value = row["direccion"];
            telefono.value = row["telefono"];
            correo.value = row["correo"];
            correo.disabled = false;
            telefono.disabled = false;
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const nom = nombre.value.trim().toUpperCase(),
                tel = telefono.value.trim(),
                dir = direccion.value.trim().toUpperCase(),
                cor = correo.value.trim();
            if (tel === '') {
                telefono.disabled = true;
            }
            if (cor === '') {
                correo.disabled = true;
            }

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('tel', tel);
            datos.append('dir', dir);
            datos.append('correo', cor);
            datos.append('accion', accion);
            confirmarAccion(datos, 'cotizacion', tabla, modal, function(r) {
                if (r) {
                    cargarCombo('cotizacion');
                }
            })
        });
    })
</script>