<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<!-- Control Sidebar -->
<aside class="control-sidebar first-sidebar control-sidebar-light" style="overflow:hidden auto;">
    <div class="p-3 ">
        <div class="row" style="align-items:flex-start">
            <form id="form_guia" style="display:contents" autocomplete="off" class="needs-validation" novalidate>
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body" style="padding:1.25em">

                            <div class="tabs-container">
                                <div class="row" style="align-items:normal">
                                    <div class="col-md-5 d-flex">
                                        <div class="tabs" style="margin-block: 0.5rem 1.8rem ">
                                            <?php if ($_SESSION["crear4"] && !$_SESSION["crear7"]) : ?>
                                                <input type="radio" id="radio-2" name="tabs" value="2" checked />
                                                <label class="tab" for="radio-2">Salida</label>
                                                <input type="radio" id="radio-3" name="tabs" value="3" />
                                                <label class="tab" for="radio-3">Entrada</label>
                                                <span style="width:50%;" class="glider"></span>

                                            <?php endif; ?>

                                            <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>
                                                <script>
                                                    selectedTab = '1';
                                                </script>
                                                <input type="radio" id="radio-1" name="tabs" value="1" checked />
                                                <label class="tab" for="radio-1"> Compra</label>
                                                <span style="width: 100%;transform:translate(0)" class="glider"></span>
                                            <?php endif; ?>

                                            <?php if ($_SESSION["crear7"] && $_SESSION["crear4"]) : ?>
                                                <input type="radio" id="radio-2" name="tabs" value="2" checked />
                                                <label class="tab" for="radio-2">Salida</label>
                                                <input type="radio" id="radio-3" name="tabs" value="3" />
                                                <label class="tab" for="radio-3">Entrada</label>
                                                <input type="radio" id="radio-1" name="tabs" value="1" />
                                                <label class="tab" for="radio-1"> Compra</label>
                                                <span class="glider"></span>
                                            <?php endif; ?>

                                            <!-- <span class="glider"></span> -->
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>

                                            <div id="div_orden" style="display:none;line-height:1">
                                            <?php else : ?>
                                                <div id="div_orden" style="display:block;line-height:1">
                                                <?php endif; ?>
                                                <div class="row">
                                                    <div class="col form-group">
                                                        <label id="lblO" class="mb-0 combo"><i class="fas fa-ticket"></i> Orden</label>
                                                        <select id="cboOrden" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                        </select>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                </div>
                                                <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>
                                                    <div class="form-group" id="div_proveedor" style="display:block;line-height:1">
                                                    <?php else : ?>
                                                        <div class="form-group" id="div_proveedor" style="display:none;line-height:1">
                                                        <?php endif; ?>
                                                        <label id="lblP" class="mb-0 combo"><i class="fas fa-hand-holding-box"></i> Proveedor</label>
                                                        <div class="row">
                                                            <div class="col">
                                                                <select id="cboProveedores" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                </select>
                                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div class="form-group mb-0" id="div_return" style="display:none;line-height:1">
                                                            <div class="row">
                                                                <div class="col-md-6" style="margin-bottom:1.8rem">
                                                                    <label id="lblP" class="mb-0 combo"><i class="fas fa-ticket"></i> Orden</label>
                                                                    <select id="cboPorOrden" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                    </select>
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                                <div class="col-md-6" style="margin-bottom:1.8rem">
                                                                    <label id="lblP" class="mb-0 combo"><i class="fas fa-user-tag"></i> Cliente</label>

                                                                    <select id="cboPorCliente" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                    </select>
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="row" style="line-height:1;">
                                                <div class="col-sm-4" style="margin-bottom:1.8rem;">
                                                    <div class="form-group mb-0">
                                                        <label class="combo" for="fecha">
                                                            <i class="fas fa-calendar"></i> Fecha</label>
                                                        <input id="fecha" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" id="div_retorno" style="display:none">
                                                    <div class="form-group">
                                                        <label class="combo" for="fecha_retorno">
                                                            <i class="fas fa-calendar"></i> Fecha entrada</label>
                                                        <input id="fecha_retorno" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>
                                                    <div class="col" id="div_nrofac" style="display:block;margin-bottom:1.8rem">
                                                    <?php else : ?>
                                                        <div class="col" id="div_nrofac" style="display:none;margin-bottom:1.8rem">
                                                        <?php endif; ?>
                                                        <div class="form-group mb-0">
                                                            <label class="combo" for="nro_fac">
                                                                <i class="fas fa-list-ol"></i> Nro. Factura</label>
                                                            <input id="nro_fac" inputmode="numeric" autocomplete="off" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control" placeholder="Ingrese el nro. de factura" required>
                                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                                        </div>
                                                        </div>
                                                        <!-- <form action="" id="form_salida"> -->
                                                        <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>
                                                            <div class="col-sm-4" id="div_nroguia" style="display:none;margin-bottom:1.8rem">
                                                            <?php else : ?>
                                                                <div class="col-sm-4" id="div_nroguia" style="display:block;margin-bottom:1.8rem">
                                                                <?php endif; ?>
                                                                <div class="form-group mb-0">
                                                                    <label class="combo" for="nro_guia">
                                                                        <i class="fas fa-list-ol"></i> Nro. Guia</label>
                                                                    <input id="nro_guia" maxlength="9" inputmode="numeric" autocomplete="off" style="height:30px;font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control" oninput="validarNumber(this,/[^0-9]/g)" placeholder="Ingrese el nro. de guia" required>
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                                </div>
                                                                <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>
                                                                    <div class="col-sm-4" id="div_conductor" style="display:none;margin-bottom:1.8rem">
                                                                    <?php else : ?>
                                                                        <div class="col-sm-4" id="div_conductor" style="display:block;margin-bottom:1.8rem">
                                                                        <?php endif; ?>
                                                                        <div class="form-group mb-0">
                                                                            <label id="lbl" class="mb-0 combo"><i class="fas fa-steering-wheel"></i> Transportista</label>
                                                                            <select id="cboConductor" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                            </select>
                                                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                        </div>
                                                                        </div>
                                                                        <!-- </form> -->
                                                                    </div>
                                                                    <div class="row" style="line-height:1;" id="div_productos">
                                                                        <div class="col-lg-8">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label combo" for="codProducto">
                                                                                    <i class="fas fa-arrow-up-a-z"></i> Productos</label>
                                                                                <input style="border-bottom: 2px solid var(--select-border-bottom);" type="search" class="form-control form-control-sm" id="codProducto" placeholder="Ingrese el nombre del producto" onkeypress="return evitarEnvio(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label combo" for="codBarras">
                                                                                    <i class="fas fa-barcode"></i> Cod. Barras</label>
                                                                                <input style="border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" id="codBarras" placeholder="Ingrese el código de barras" onkeypress="return evitarEnvio(event)">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>

                                                                <div id="form-1" style="display: block" class="card-body form-container">
                                                                <?php else : ?>
                                                                    <div id="form-1" class="card-body form-container">

                                                                    <?php endif; ?>
                                                                    <div class="table-responsive">
                                                                        <table id="tblIn" class="table table-bordered w-100 table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center">Nº</th>
                                                                                    <th></th>
                                                                                    <th>CANTIDAD</th>
                                                                                    <th>UNIDAD</th>
                                                                                    <th>PRECIO</th>
                                                                                    <th>DESCRIPCION</th>
                                                                                    <th class="text-center">ACCIONES</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    </div>

                                                                    <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>
                                                                        <div id="form-2" class="form-container">
                                                                        <?php else : ?>
                                                                            <div id="form-2" style="display:block;" class="form-container">
                                                                            <?php endif; ?>
                                                                            <div class="table-responsive">
                                                                                <table id="tblOut" class="table table-bordered w-100 table-striped">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="text-center">Nº</th>
                                                                                            <th></th>
                                                                                            <th>CANTIDAD</th>
                                                                                            <th>UNIDAD</th>
                                                                                            <th>DESCRIPCION</th>
                                                                                            <!-- <th class="text-center">UTIL.</th> -->
                                                                                            <th class="text-center">ACCIONES</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            </div>

                                                                            <div id="form-3" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblReturn" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="text-center">Nº</th>
                                                                                                <th>CANTIDAD</th>
                                                                                                <th>UNIDAD</th>
                                                                                                <th>DESCRIPCION</th>
                                                                                                <th>SALIDA</th>
                                                                                                <th>ENTRADA</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>

                                                                            <div id="form-4" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblDetalleSalida" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="text-center">ID</th>
                                                                                                <th class="text-center">CÓDIGO</th>
                                                                                                <th class="text-center">CANTIDAD</th>
                                                                                                <th class="text-center">UNIDAD</th>
                                                                                                <th>DESCRIPCION</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>

                                                                            <div id="form-5" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblDetalleEntrada" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="text-center">ID</th>
                                                                                                <th class="text-center">CÓDIGO</th>
                                                                                                <th class="text-center">CANTIDAD</th>
                                                                                                <th class="text-center">UNIDAD</th>
                                                                                                <th class="text-center">PRECIO</th>
                                                                                                <th>DESCRIPCION</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                    </div>
                                                    <!-- /.card -->
                                                    <div class="col-xl-3">
                                                        <?php if ($_SESSION["crear7"] && !$_SESSION["crear4"]) : ?>
                                                            <div class="card" id="card_person" style="display: none;">
                                                            <?php else : ?>
                                                                <div class="card" id="card_person" style="display: block;">
                                                                <?php endif; ?>
                                                                <div class="card-body" style="line-height:1.2;">
                                                                    <div class="form-group" style="margin-bottom: 1.6rem;">
                                                                        <label id="lbl" class="mb-0 combo"><i class="fa-solid fa-person-carry-box"></i> Despachado por</label>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <select id="cboDespachado" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                                </select>
                                                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label id="lbl" class="mb-0 combo"><i class="fas fa-user-helmet-safety"></i> Responsable</label>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <select id="cboResponsable" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                                </select>
                                                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                            <div class="form-group" style="margin-bottom:1.4rem;">
                                                                                <label class="col-form-label combo" for="codBarras">
                                                                                <i class="fa-solid fa-clipboard-question"></i> Motivo</label>
                                                                                <input style="border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" id="inpMotivo" placeholder="Traslado de herramientas" >
                                                                            </div>
                                                                </div>
                                                                </div>
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <button type="button" id="btnGuardarGuia" style="margin-bottom:.75rem;background:var(--label-star) linear-gradient(180deg, var(--label-star), var(--label-new)) repeat-x;color:#fff" class="btn w-100"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                                                                        <button type="button" id="Cerrar" onclick="first_control.click();" style="border-color:#d6d8df69" class="btn bg-gradient-light w-100"><i class="fas fa-right-from-bracket"></i><span class="button-text"> </span>Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
            </form>
        </div>
    </div>
</aside>
<!-- /.control-sidebar -->
<div class="modal fade" id="modal-fab">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h4 class="modal-title"><i class="fas fa-screwdriver-wrench"></i><span> Producción</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formProductoFab" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body ">
                    <input type="hidden" id="id_" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-data s1">
                                <input type="text" id="cantidad_fab" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" required>
                                <label class="label"><i class="fa-solid fa-boxes-stacked"></i> Cantidad</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label id="lbl" class="mb-0 combo"><i class="fas fa-ruler"></i> Unidad</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="c" id="cboUnidad_fab" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="nombre_fab" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="barra label">
                                    <i class="fa-solid fa-signature"></i> Descripción</label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                            <!-- <div class="form-group mb-4">
                                <label id="lbl" class="mb-0 combo"><i class="fas fa-receipt"></i> Orden</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="cboOrdenFab" id="cboOrdenFab" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Seleccione una orden</div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" id="tblFab">
                                <thead style="background:#eef1f3!important">
                                    <tr>
                                        <th>NRO</th>
                                        <th>CANTIDAD</th>
                                        <th>DESCRIPCION</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnAgregarFab" class="btn bg-gradient-success"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-consul">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-navy">
                <h4 class="modal-title"><i class="fas fa-screwdriver-wrench"></i><span> Consultar Producción</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formProductoFab" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body ">
                    <input type="hidden" id="id_" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-data s1">
                                <input type="text" id="cantidad_fab" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" required>
                                <label class="label"><i class="fa-solid fa-boxes-stacked"></i> Cantidad</label>
                                <div class="invalid-feedback">*Este campo es requerido.</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label id="lbl" class="mb-0 combo"><i class="fas fa-ruler"></i> Unidad</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="c" id="cboUnidad_fab" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="lbl" class="mb-0 combo"><i class="fas fa-trowel-bricks"></i> Producto</label>
                                <div class="row">
                                    <div class="col-10">
                                        <select id="cboFabricado" class="cbo form-control select2 select2-success flex-grow-1" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                    <div class="span-btn col-2 d-flex align-items-center justify-content-end" id="div_span" style="padding-right:.5rem">
                                        <span class="badge bg-gradient-dark" id="new_orden" title='Nuevo' data-target='#modal-fab' data-toggle='modal'><i class="fa-solid fa-plus"></i></span>
                                        <span style="display:none" class="badge bg-gradient-dark" id="edit_orden" title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                        <span style="display:none" class="badge bg-gradient-dark" id="eli_orden" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" id="tblFabCon">
                                <thead style="background:#eef1f3!important">
                                    <tr>
                                        <th>NRO</th>
                                        <th>CANTIDAD</th>
                                        <th>DESCRIPCION</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnGuardarCon" class="btn bg-gradient-navy"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    const btnGuardarFab = document.getElementById('btnAgregarFab');
    const formFab = document.getElementById('formProductoFab');
    const modal_fab = document.getElementById('modal-fab');
    const cantidad_fab = document.getElementById('cantidad_fab');



    $(modal_fab).on("shown.bs.modal", () => {
        cantidad_fab.focus();
    });


    formFab.addEventListener("submit", function(e) {
        e.preventDefault();
        console.log("entro al foem")
        const cboUnidad = document.getElementById('cboUnidad_fab');
        const nombre_fab = document.getElementById('nombre_fab');

        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }
        let nom = nombre_fab.value.trim().toUpperCase();
        let und = cboUnidad.value;
        let cant = cantidad_fab.value;

        let datos = new FormData();
        datos.append('nombre', nom);
        datos.append('unidad', und);
        datos.append('cantidad', cant);
        datos.append('accion', 9);
        confirmarAccion(datos, 'inventario', null, modal_fab, function(r) {
            cargarAutocompletado();
        })


    })
</script>