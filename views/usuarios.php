<?php require_once "../utils/database/config.php";?>

<head>
    <title>Usuarios</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Usuarios</h1>
            </div>
            <?php if (isset($_SESSION["crear37"]) && $_SESSION["crear37"] === true) : ?>
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
                                    <h3 class="card-title ">Listado de usuarios</h3>
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
                        <table id="tblUsuarios" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>NOMBRES</th>
                                    <th>USUARIO</th>
                                    <th class="text-center">PERFIL</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Usuario</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal">
                    <input type="hidden" id="id_usuario" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="data-nombre" class="input-data">
                                <input autocomplete="off" id="nombres" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="barra label">
                                    <i class="fa-solid fa-signature"></i> Nombres</label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                            <div id="data-usuario" class="input-data">
                                <input autocomplete="off" id="usuario" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="barra label"><i class="fa-solid fa-user"></i> Nombre de usuario</label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                            <div id="data-clave" class="input-data">
                                <input autocomplete="off" id="clave" class="input-nuevo" type="password" oninput="validarClave(this, sub)" required>
                                <div class="line underline"></div>
                                <label class="barra label"><i class="fa-solid fa-lock"></i> Contraseña</label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                <div id="ten" class="ten">*La contraseña debe contener 6 caracteres minimos</div>

                            </div>
                            <div id="data-clave_con" class="input-data">
                                <input autocomplete="off" id="clave_con" class="input-nuevo" type="password" oninput="validarConf(this, sub)" required>
                                <div class="line underline"></div>
                                <label class="barra label"><i class="fa-solid fa-lock"></i> Confirmar contraseña</label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                <div id="c" class="ten">*Las contraseñas no coinciden</div>
                            </div>
                            <div id="data-rol" class="form-group mb-4">
                                <label id="lbl" class="mb-0 combo"><i class="fa-solid fa-id-card-clip"></i> Perfil</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="cboPerfil" id="cboPerfil" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio</div>
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
    var mostrarCol = '<?php echo $_SESSION["editar37"] || $_SESSION["eliminar37"] ?>';
    var editar = '<?php echo $_SESSION["editar37"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar37"] ?>';

    configuracionTable = {
        "dom": 'pt',
        "responsive": true,
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
                className: "text-center",
                render: function(data, type, full, meta) {
                    return "<span class='alert alert-default-primary'>" + data + "</span>";
                }
            },
            {
                targets: 4,
                data: "acciones",
                visible: mostrarCol,
                render: function(data, type, row, full, meta) {
                    if (row.id === 1) { // Verifica si es la primera fila
                        return (
                            "<center style='white-space: nowrap;'>" +
                            " <button type='button' class='btn bg-gradient-warning btnEditarSup' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button>" +
                            " <button type='button' class='btn bg-gradient-gray-dark btnClaveSup' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-key'></i>" +
                            "</button>" +
                            " </center>"
                        );
                    } else {
                        return (
                            "<center style='white-space: nowrap;'>" +
                            (editar ?
                                " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                                " <i class='fa-solid fa-pencil'></i>" +
                                "</button>" +
                                " <button type='button' class='btn bg-gradient-gray-dark btnClave' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                                " <i class='fa-solid fa-key'></i>" +
                                "</button>" : "") +
                            (eliminar ?
                                " <button type='button' class='btn bg-gradient-danger btnEliminar'  title='Eliminar'>" +
                                " <i class='fa fa-trash'></i>" +
                                "</button>" : "") +
                            " </center>"
                        );
                    }
                },
            },
        ],
    }

    cargarCombo('Perfil');
    OverlayScrollbars(document.querySelector('.scroll-modal'), {
        autoUpdate: true,
        scrollbars: {
            autoHide: 'leave'
        }
    });

    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#tblUsuarios')) {
            tabla = $("#tblUsuarios").DataTable({
                "ajax": {
                    "url": "controllers/usuarios.controlador.php",
                    "type": "POST",
                    "dataSrc": ''
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', () => {
                const b = document.body;
                const s = b.scrollHeight;
                const w = window.innerHeight;

                handleScroll(b, s, w);

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('usuarios', JSON.stringify(tablaData));
            });
        }
        let accion = 0;
        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            icon = document.querySelector('.modal-title i'),
            form = document.getElementById('formNuevo'),
            btnNuevo = document.getElementById('btnNuevo');

        const data_nom = document.getElementById('data-nombre'),
            data_usu = document.getElementById('data-usuario'),
            data_cla = document.getElementById('data-clave'),
            data_cla_con = document.getElementById('data-clave_con'),
            data_rol = document.getElementById('data-rol');

        const id = document.getElementById('id_usuario'),
            nombres = document.getElementById('nombres'),
            usuario = document.getElementById('usuario'),
            clave = document.getElementById('clave'),
            clave_ = document.getElementById('clave_con'),
            rol = document.getElementById('cboPerfil');


        $(rol).select2({
            placeholder: 'SELECCIONE',
            minimumResultsForSearch: -1,
            width: '100%',
        })

        $(rol).change(function() {
            estilosSelect2(this, 'lbl');
        });

        $(modal).on("shown.bs.modal", () => {
            accion === 4 ? clave.focus() : nombres.focus();
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                let dataElements = [data_nom, data_usu, data_cla, data_cla_con, data_rol];
                displayModal(dataElements, 'block', 'block', 'block', 'block', 'block')
                // data_usu.style.marginBottom = '3.3em';
                rol.classList.remove('select2-warning');
                rol.classList.add('select2-success');
                cambiarModal(span, ' Nuevo Usuario', icon, 'fa-user-plus', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                rol.required=true;
                form.reset();
                $(rol).val(0).trigger("change");
                $(".ten").hide()
                sub = false;
                form.classList.remove('was-validated');
            });
        }

        $('#tblUsuarios tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            let dataElements = [data_nom, data_usu, data_cla, data_cla_con, data_rol];
            displayModal(dataElements,'block', 'block', 'none', 'none', 'block')
            // data_usu.style.marginBottom = '30px';
            rol.classList.remove('select2-success');
            rol.classList.add('select2-warning');
            clave.value = 'noedit';
            clave_.value = 'noedit';
            cambiarModal(span, ' Editar Usuario', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombres.value = row["nombres"];
            usuario.value = row["nombre_usuario"];
            setChange(rol, row["id_perfil"]);
        });

        $('#tblUsuarios tbody').on('click', '.btnEditarSup', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            let dataElements = [data_nom, data_usu, data_cla, data_cla_con, data_rol];
            displayModal(dataElements, 'block', 'block', 'none', 'none', 'none')
            // data_usu.style.marginBottom = '30px';
            rol.classList.remove('select2-success');
            rol.classList.add('select2-warning');
            clave.value = 'noedit';
            clave_.value = 'noedit';
            rol.required = false;
            cambiarModal(span, ' Editar Usuario', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombres.value = row["nombres"];
            usuario.value = row["nombre_usuario"];
            setChange(rol, row["id_perfil"]);
        });

        $('#tblUsuarios tbody').on('click', '.btnClave', function() {
            let row = obtenerFila(this, tabla);
            let dataElements = [data_nom, data_usu, data_cla, data_cla_con, data_rol];
            accion = 4;
            sub = false;
            form.classList.remove('was-validated');
            clave.value = '';
            clave_.value = '';
            displayModal(dataElements,'none', 'none', 'block', 'block', 'none')
            rol.classList.remove('select2-success');
            rol.classList.add('select2-warning');

            cambiarModal(span, ' Restablecer Contraseña', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombres.value = row["nombres"];
            usuario.value = row["nombre_usuario"];
            setChange(rol, row["id_perfil"]);
        });

        $('#tblUsuarios tbody').on('click', '.btnClaveSup', function() {
            let row = obtenerFila(this, tabla);
            let dataElements = [data_nom, data_usu, data_cla, data_cla_con, data_rol];
            accion = 4;
            sub = false;
            rol.required =false;
            form.classList.remove('was-validated');
            clave.value = '';
            clave_.value = '';
            displayModal(dataElements,'none', 'none', 'block', 'block', 'none')
            rol.classList.remove('select2-success');
            rol.classList.add('select2-warning');

            cambiarModal(span, ' Restablecer Contraseña', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombres.value = row["nombres"];
            usuario.value = row["nombre_usuario"];
            setChange(rol, row["id_perfil"]);
            
        });

        $('#tblUsuarios tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_u = e["id"];
            const name = 'usuarios'
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_u);
            confirmarEliminar('este', 'usuario', function(r){
                if(r){
                    confirmarAccion(src, 'usuarios', tabla)
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

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            sub = true;
            const nom = nombres.value.trim().toUpperCase(),
                usu = usuario.value.trim(),
                cla = clave.value,
                cla_ = clave_.value,
                id_rol = rol.value;

            if (this.checkValidity() && cla.length > 5 && cla === cla_) {
                const id_u = id.value;
                let datos = new FormData();
                datos.append('id', id_u);
                datos.append('nombres', nom);
                datos.append('nombre_usuario', usu);
                datos.append('clave', cla);
                datos.append('rol', id_rol);
                datos.append('accion', accion);
                confirmarAccion(datos, 'usuarios', tabla, modal)
                // nom.length > 0 && $('.ten').show();
            } else {
                this.classList.add('was-validated');
                validarClave(clave, sub)
                validarConf(clave_, sub)
            }
        });

       
    })

    function validarConf(input, sub) {
        if (sub) {
            const con = document.getElementById('clave');
            const pass = input.value === con.value;
            $("#c").toggle(input.value.length > 0 && !pass);
        }
    }
</script>