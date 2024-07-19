<?php require_once "../utils/database/config.php";?>

<head>
    <title>Perfil</title>
</head>
<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Perfil</h1>
            </div>
            <?php if (isset($_SESSION["crear16"]) && $_SESSION["crear16"] === true) : ?>
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
                                    <h3 class="card-title ">Listado de perfiles</h3>
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
                        <table id="tblRoles" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>DESCRIPCIÓN</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-user-plus"></i><span> Nuevo Perfil</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="id_rol" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="label"><i class="fa-solid fa-signature"></i> Descripción</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
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

<div class="modal fade" id="modalR">
    <div class="modal-dialog modal-xl modal-rol modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-light">
                <h4 class="modal-title"><i class="fa-solid fa-user-check"></i><span> Permisos</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form id="formPermisos" autocomplete="off" class="needs-validation" novalidate>
                    <input type="hidden" id="id" value="">
                    <div class="table-responsive">
                        <table class="table mb-0 m-auto" id="tblPermisos">
                            <thead>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-desktop"></i> Modulos</th>
                                    <th class="text-center"><i class="fa-solid fa-eye"></i> Mostrar</th>
                                    <th class="text-center"><i class="fa-solid fa-plus"></i> Nuevo</th>
                                    <th class="text-center"><i class="fa-solid fa-pen"></i> Editar</th>
                                    <th class="text-center"><i class="fa-solid fa-trash"></i> Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-house"></i> Inicio</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="1" value="1" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="1" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="1" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="1" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-shelves"></i> Inventario</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="3" value="3" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="crearI" name="3" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="editarI" name="3" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="eliminarI" name="3" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-person-dolly"></i> Movimientos</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="verE" name="4" value="4" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="crearE" name="4" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="editarE" name="4" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="eliminarE" name="4" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-chart-pie"></i> Informes</th>
                                    <td class="text-center">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="5" value="5" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="5" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="5" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="5" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-money-check-dollar"></i> Listado de Compras</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="verS" name="7" value="7" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="crearS" name="7" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="editarS" name="7" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="7" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-hand-holding-box"></i> Proveedores</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="verEm" name="8" value="8" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="crearEm" name="8" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="editarEm" name="8" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="eliminarEm" name="8" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-user-helmet-safety"></i> Empleados</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="10" value="10" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="10" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="10" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="10" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-money-check-dollar-pen"></i> Presupuestos</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="verInf" name="12" value="12" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="12" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="12" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="12" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-tickets"></i> Ordenes de trabajo</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="13" value="13" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this, true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="13" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="13" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="13" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-user-tag"></i> Clientes</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="verEm" name="14" value="14" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="crearEm" name="14" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="editarEm" name="14" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" id="eliminarEm" name="14" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>



                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-users"></i> Usuarios</th>
                                    <td class="text-center">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="15" value="15" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="15" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="15" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="15" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-id-card-clip"></i> Roles</th>
                                    <td class="text-center">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="16" value="16" oninput="changeToggle(this)" onkeydown="toggleWithEnter(event, this,true)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="16" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="16" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="16" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-upload"></i> Cargar</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="17" value="17" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="17" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="17" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="17" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class=" "><i class="fas tab-icon fa-users"></i> Configuración</th>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="18" value="18" onkeydown="toggleWithEnter(event, this)">
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="18" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="18" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-center lh-1">
                                        <label class="switch-2">
                                            <input class="switch__input" type="checkbox" name="18" onkeydown="toggleWithEnter(event, this)" disabled>
                                            <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                            </svg>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="btnGuardarRol" class="btn bg-gradient-light"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar16"] || $_SESSION["eliminar16"] ?>';
    var editar = '<?php echo $_SESSION["editar16"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar16"] ?>';

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
                    if (type === 'display') {
                        return meta.row + 1; // Devuelve el número de fila + 1
                    }
                    return meta.row; // Devuelve el índice de la fila
                }
            },
            {
                targets: 2,
                data: "acciones",
                visible: mostrarCol,
                render: function(data, type, row, full, meta) {
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modal' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button>" +
                            " <button type='button' class='btn bg-gradient-gray-dark btnPermiso' data-target='#modalR' data-toggle='modal'  title='Permisos'>" +
                            " <i class='fas fa-user-unlock'></i>" +
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
        if (!$.fn.DataTable.isDataTable('#tblRoles')) {
            tabla = $("#tblRoles").DataTable({
                "ajax": {
                    "url": "controllers/roles.controlador.php",
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
                localStorage.setItem('roles', JSON.stringify(tablaData));
            });
        }
        let accion = 0;
        const modal = document.getElementById('modal'),
            modalP = document.getElementById('modalR'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            formP = document.getElementById('formPermisos'),
            icon = document.querySelector('.modal-title i'),
            btnNuevo = document.getElementById('btnNuevo'),
            btnGuardar = document.getElementById('btnGuardarRol');


        const id = document.getElementById('id'),
            id_rol = document.getElementById('id_rol'),
            nombre = document.getElementById('nombre');
            


        $(modal).on("shown.bs.modal", () => {
            nombre.focus();
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                cambiarModal(span, ' Nuevo Perfil', icon, 'fa-user-plus', elements, 'bg-gradient-blue', 'bg-gradient-green', modal, 'modal-new', 'modal-change')
                form.reset();
                form.classList.remove('was-validated');
            });
        }

        $('#tblRoles tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion = 3
            const id_ = e["id"];
            let src = new FormData();
            src.append('accion', accion);
            src.append('id', id_);
            confirmarEliminar('este', 'perfil', function(r) {
                if (r) {
                    confirmarAccion(src, 'roles', tabla)
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

        $('#tblRoles tbody').on('click', '.btnEditar', function() {
            let row = obtenerFila(this, tabla);
            accion = 2;
            cambiarModal(span, ' Editar Perfil', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
            id.value = row["id"];
            nombre.value = row["nombre"];
        });

        $('#tblRoles tbody').on('click', '.btnPermiso', function() {
            formP.reset();
            let formE = formP.elements;
            for (let i = 0; i < formE.length; i++) {
                if (formE[i].type === 'checkbox') {
                    formE[i].dispatchEvent(new Event('input'));
                }
            }
            let row = obtenerFila(this, tabla);
            id_rol.value = row["id"];
            $.ajax({
                url: "controllers/roles.controlador.php",
                method: "POST",
                data: {
                    'id_perfil': row["id"],
                    'accion': 4
                },
                dataType: "json",
                success: function(r) {
                    r.forEach(function(obj) {
                        let modulo = obj.id_modulo;
                        let checkbox = body.querySelectorAll('[name="' + modulo + '"]');
                        checkbox[0].checked = true;
                        checkbox[0].dispatchEvent(new Event('input'));

                        if (modulo === 1 || modulo === 17 || modulo === 18) {
                            return; // Salta este ciclo del forEach
                        }
                        // Buscar el checkbox correspondiente al id_modulo
                        for (let i = 1; i <= 3; i++) {
                            checkbox[i].checked = obj[i];
                        }
                    });
                },
            });
        });

        btnGuardar.addEventListener("click", () => {
            $.ajax({
                url: "controllers/roles.controlador.php",
                method: "POST",
                data: {
                    'id_perfil': id_rol.value,
                    'accion': 5
                },
                dataType: "json",
                success: function(r) {
                    let name = [1, 3, 4, 5, 7, 8, 10, 12, 13, 14, 15, 16, 17, 18];
                    let data = [];
                    let isBodega = true;
                    let isCompras = true;
                    let isPersonas = true;
                    let isOperaciones = true;

                    name.forEach(function(id) {
                        let checkbox = document.querySelectorAll('[name="' + id + '"]');
                        if (checkbox[0].checked) {
                            if (id === 3 || id === 4 || id === 5) {
                                if (isBodega) {
                                    let obj = {
                                        id_modulo: 2,
                                        crear: true,
                                        editar: true,
                                        eliminar: true
                                    };
                                    isBodega = false;
                                    data.push(obj);
                                }
                            } else if (id === 7 || id === 8) {
                                if (isCompras) {
                                    let obj = {
                                        id_modulo: 6,
                                        crear: true,
                                        editar: true,
                                        eliminar: true
                                    };
                                    isCompras = false;
                                    data.push(obj);
                                }
                            } else if (id === 10) {
                                if (isPersonas) {
                                    let obj = {
                                        id_modulo: 9,
                                        crear: true,
                                        editar: true,
                                        eliminar: true
                                    };
                                    isPersonas = false;
                                    data.push(obj);
                                }
                            } else if (id === 12 || id === 13 || id === 14) {
                                if (isOperaciones) {
                                    let obj = {
                                        id_modulo: 11,
                                        crear: true,
                                        editar: true,
                                        eliminar: true
                                    };
                                    isOperaciones = false;
                                    data.push(obj);
                                }
                            }
                            let obj = {
                                id_modulo: checkbox[0].value,
                                crear: checkbox[1].checked,
                                editar: checkbox[2].checked,
                                eliminar: checkbox[3].checked
                            };

                            data.push(obj);
                        }
                    });
                    $.ajax({
                        url: "controllers/roles.controlador.php",
                        method: "POST",
                        data: {
                            'id_perfil': id_rol.value,
                            'datos': JSON.stringify(data), // Envía todos los datos acumulados en una única solicitud
                            'accion': 6
                        },
                        dataType: "json",
                        success: function(r) {
                            $(modalR).modal("hide");
                            mostrarToast(r.status, "Completado", "fa-check", r.m);
                        }
                    });

                },
            });

        })

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const nom = nombre.value.trim().toUpperCase();
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            const id_e = id.value;
            let datos = new FormData();
            datos.append('id', id_e);
            datos.append('nombre', nom);
            datos.append('accion', accion);
            confirmarAccion(datos, 'roles', tabla, modal)
        });
    })

    function changeToggle(input) {
        const toggle = document.querySelectorAll('[name="' + input.name + '"]');

        toggle.forEach((item, index) => {
            if (!input.checked) {
                item.checked = false;
                item.disabled = index !== 0;
            } else {
                item.disabled = false;
            }
        });
    }
</script>