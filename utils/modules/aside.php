<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<link rel='stylesheet' href="assets/plugins/dropzone/dropzone.min.css">
<script src='assets/plugins/dropzone/dropzone.min.js'></script>

<!-- Control Sidebar -->
<aside class="control-sidebar first-sidebar control-sidebar-light" style="overflow:hidden auto;">
    <div class="p-3 ">
        <div class="row" style="align-items:flex-start">
            <form id="form_guia" style="display:contents" autocomplete="off" class="needs-validation" novalidate>
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body" style="padding: 1.06em 1.25em">
                            <div class="tabs-container">
                                <div class="row" style="align-items:normal">
                                    <div class="col-md-6 d-flex">
                                        <div class="tabs" style="margin-block: 0.5rem 1.8rem ">
                                            <?php if ($_SESSION["crear4"] && !$_SESSION["crear9"]) : ?>
                                                <input type="radio" id="radio-2" name="tabs" value="2" checked />
                                                <label class="tab" for="radio-2">Salida</label>
                                                <input type="radio" id="radio-3" name="tabs" value="3" />
                                                <label class="tab" for="radio-3">Entrada</label>
                                                <input type="radio" id="radio-7" name="tabs" value="7" />
                                                <label class="tab" for="radio-7">Fabricacion</label>
                                                <span style="width:33.3%;" class="glider"></span>
                                            <?php endif; ?>
                                            <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
                                                <script>
                                                    selectedTab = '1';
                                                </script>
                                                <input type="radio" id="radio-1" name="tabs" value="1" checked />
                                                <label class="tab" for="radio-1"> Compra</label>
                                                <span style="width: 100%;transform:translate(0)" class="glider"></span>
                                            <?php endif; ?>
                                            <?php if ($_SESSION["crear9"] && $_SESSION["crear4"]) : ?>
                                                <input type="radio" id="radio-2" name="tabs" value="2" checked />
                                                <label class="tab" for="radio-2">Salida</label>
                                                <input type="radio" id="radio-3" name="tabs" value="3" />
                                                <label class="tab" for="radio-3">Entrada</label>
                                                <input type="radio" id="radio-1" name="tabs" value="1" />
                                                <label class="tab" for="radio-1"> Compra</label>
                                                <input type="radio" id="radio-7" name="tabs" value="7" />
                                                <label class="tab" for="radio-7">Fabricacion</label>
                                                <span style="width:25%" class="glider"></span>
                                            <?php endif; ?>

                                            <!-- <span class="glider"></span> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
                                            <div id="div_orden" style="display:none;line-height:1">
                                            <?php else : ?>
                                                <div id="div_orden" style="display:block;line-height:1">
                                                <?php endif; ?>
                                                <div class="row">
                                                    <div class=" col-sm form-group ui-front">
                                                        <label class="col-form-label combo" for="nro_orden">
                                                            <i class="fas fa-ticket"></i> Orden de trabajo</label>
                                                        <input style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);" type="search" class="form-control form-control-sm" id="nro_orden" oninput="formatInputOrden(this)" placeholder="Ingrese el nro. de orden o cliente" required>
                                                        <button class="clear-btn" type="button" id="clearButton" style="display:none" onclick="clearInput('nro_orden', this)">&times;</button>
                                                        <div class="ten invalid-feedback">*Campo obligatorio.</div>
                                                        <!-- <div class="ten">*Seleccione.</div> -->
                                                    </div>
                                                </div>
                                                </div>
                                                <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
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
                                                                <div class="col-sm ui-front" style="margin-bottom:1.8rem">
                                                                    <label class="col-form-label combo" for="nro_ordenEntrada">
                                                                        <i class="fas fas fa-ticket"></i> Orden de trabajo</label>
                                                                    <input style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);" type="search" class="form-control form-control-sm" id="nro_ordenEntrada" oninput="formatInputOrden(this)" placeholder="Ingrese el nro. de orden o cliente">
                                                                    <button class="clear-btn" type="button" id="clearButtonEntrada" style="display:none" onclick="clearInput('nro_ordenEntrada', this)">&times;</button>
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-0" id="div_fab" style="display:none;line-height:1">
                                                            <div class="row">
                                                                <div class="col-sm ui-front" style="margin-bottom:1.8rem">
                                                                    <label class="col-form-label combo" for="nro_ordenFab">
                                                                        <i class="fas fas fa-ticket"></i> Orden de trabajo</label>
                                                                    <input style="font-size:1.2rem;border-bottom:2px solid var(--select-border-bottom);" type="search" class="form-control form-control-sm" id="nro_ordenFab" oninput="formatInputOrden(this)" placeholder="Ingrese el nro. de orden o cliente" required>
                                                                    <button class="clear-btn" type="button" id="clearButtonFab" style="display:none" onclick="clearInput('nro_ordenFab', this)">&times;</button>
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="row" style="line-height:1;">
                                                <div id="div_fecha" class="col-sm-4" style="margin-bottom:1.8rem;">
                                                    <div class="form-group mb-0">
                                                        <label class="col-form-label combo" for="fecha">
                                                            <i class="fas fa-calendar"></i> Fecha inicio</label>
                                                        <input id="fecha" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" id="div_retorno" style="display:none">
                                                    <div class="form-group">
                                                        <label class="col-form-label combo" for="fecha_retorno">
                                                            <i class="fas fa-calendar"></i> Fecha fin</label>
                                                        <input id="fecha_retorno" type="date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" class="form-control form-control-sm" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <div id="card_nro_guiaFab" style="display:none;" class="col-sm form-group">
                                                    <label for="none" class="mb-0 combo d-flex justify-content-between align-items-center flex-wrap w-100" for="nro_guiaFab">
                                                        <div class="d-flex align-items-center" style="font-size:1.15rem;gap:4px">
                                                            <i class="fas fa-ticket"></i> Nro. guia
                                                        </div>
                                                        <div class="d-flex flex-wrap align-items-center" style="font-size: 60%;">
                                                            <label for="isTrasFab" class="col-form-label text-nowrap" style="cursor:pointer;color:#616c7a; font-size: 140%;">
                                                                <i class="fa-solid fa-truck-ramp-box"></i> Traslado de fabricación
                                                            </label>
                                                            <label class="switch-2 ml-2" for="isTrasFab" style="font-size: 112%;">
                                                                <input class="switch__input" type="checkbox" id="isTrasFab" onkeydown="toggleWithEnter(event, this)" checked>
                                                                <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                                    <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11"></polyline>
                                                                </svg>
                                                            </label>
                                                        </div>
                                                    </label>
                                                    <input id="nro_guiaFab" maxlength="9" inputmode="numeric" autocomplete="off" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" oninput="validarNumber(this,/[^0-9]/g)" placeholder="Ingrese el nro. de guia" required>
                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                </div>
                                                <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
                                                    <div class="col" id="div_nrofac" style="display:block;margin-bottom:1.8rem">
                                                    <?php else : ?>
                                                        <div class="col" id="div_nrofac" style="display:none;margin-bottom:1.8rem">
                                                        <?php endif; ?>
                                                        <div class="form-group mb-0" id="card_nro_fac">
                                                            <label class="col-form-label combo" for="nro_fac">
                                                                <i class="fas fa-list-ol"></i> Nro. Factura</label>
                                                            <input id="nro_fac" inputmode="numeric" autocomplete="off" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" placeholder="Ingrese el nro. de factura" required>
                                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                                        </div>
                                                        </div>
                                                        <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
                                                            <div class="col-sm-4" id="div_nroguia" style="display:none;margin-bottom:1.8rem">
                                                            <?php else : ?>
                                                                <div class="col-sm-4" id="div_nroguia" style="display:block;margin-bottom:1.8rem">
                                                                <?php endif; ?>
                                                                <div id="card_nro_guia" class="form-group mb-0">
                                                                    <label class="col-form-label combo" for="nro_guia">
                                                                        <i class="fas fa-list-ol"></i> Nro. Guia</label>
                                                                    <input id="nro_guia" maxlength="9" inputmode="numeric" autocomplete="off" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" oninput="validarNumber(this,/[^0-9]/g)" placeholder="Ingrese el nro. de guia">
                                                                </div>
                                                                <div style="display:none;" id="card_nro_guiaE" class="form-group mb-0">
                                                                    <label class="col-form-label combo" for="nro_guia">
                                                                        <i class="fas fa-list-ol"></i> Nro. Guia</label>
                                                                    <input id="nro_guiaEntrada" maxlength="9" inputmode="numeric" autocomplete="off" style="font-size:1.2rem;border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" oninput="validarNumber(this,/[^0-9]/g)" placeholder="Ingrese el nro. de guia">
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                                </div>
                                                                <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
                                                                    <div class="col-sm-4" id="div_conductor" style="display:none;margin-bottom:1.8rem">
                                                                    <?php else : ?>
                                                                        <div class="col-sm-4" id="div_conductor" style="display:block;margin-bottom:1.8rem">
                                                                        <?php endif; ?>
                                                                        <div id="card_conductor" class="form-group mb-0">
                                                                            <label id="lbl" class="mb-0 combo"><i class="fas fa-steering-wheel"></i> Transportista</label>
                                                                            <select id="cboConductor" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                            </select>
                                                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                        </div>
                                                                        <div style="display: none;" id="card_conductorE" class="form-group mb-0">
                                                                            <label id="lbl" class="mb-0 combo"><i class="fas fa-steering-wheel"></i> Transportista</label>
                                                                            <select id="cboConductorEntrada" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                            </select>
                                                                            <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                        </div>
                                                                        </div>
                                                                        <!-- </form> -->
                                                                    </div>
                                                                    <div class="row" style="line-height:1;" id="div_productos">
                                                                        <div class="col-lg-8">
                                                                            <div class="form-group ui-front">
                                                                                <label class="col-form-label combo" for="codProducto">
                                                                                    <i class="fas fa-arrow-up-a-z"></i> Productos</label>
                                                                                <input style="border-bottom: 2px solid var(--select-border-bottom);" type="search" class="form-control form-control-sm" id="codProducto" placeholder="Ingrese el nombre del producto">
                                                                            </div>
                                                                            <div class="col-md-9">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label combo" for="codBarras">
                                                                                    <i class="fas fa-barcode"></i> Cod. Barras</label>
                                                                                <input inputmode='text' style="border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" id="codBarras" placeholder="Ingrese el código de barras" onkeypress="return evitarEnvio(event)">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="div_prod_fab" class="mb-2" style="display:none;">
                                                                        <div class='row'>
                                                                            <div class="col-auto">
                                                                                <label class="mb-0" for="fab-1" style="font-weight:bold;text-wrap:nowrap;font-size:1.15rem;"><i class="fas fa-hammer-crash"></i> Productos fabricado</label>
                                                                            </div>
                                                                            <div class="col">
                                                                                <span id="addProFab" class="btn btn-sm btn-outline-warning">
                                                                                    <i class="fas fa-plus-circle"></i> Agregar
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
                                                                <div id="form-1" style="display: block" class="card-body form-container">
                                                                <?php else : ?>
                                                                    <div id="form-1" class="card-body form-container">
                                                                    <?php endif; ?>
                                                                    <div class="table-responsive">
                                                                        <table id="tblCompra" class="table table-bordered w-100 table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center">Nº</th>
                                                                                    <th></th>
                                                                                    <th>CANTIDAD</th>
                                                                                    <th>UNIDAD</th>
                                                                                    <th>P. UNIT.</th>
                                                                                    <th>P. TOT.</th>
                                                                                    <th>IVA</th>
                                                                                    <th>P. FINAL</th>
                                                                                    <th>DESCRIPCION</th>
                                                                                    <th class="text-center">ACCIONES</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    </div>
                                                                    <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
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
                                                                                            <th class="text-center">ACCIONES</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            </div>
                                                                            <div id="form-6" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblReturn" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="text-center">Nº</th>
                                                                                                <th>DESCRIPCION</th>
                                                                                                <th>UNIDAD</th>
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
                                                                                    <table id="tblDetalleCompra" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="text-center">ID</th>
                                                                                                <th class="text-center">CÓDIGO</th>
                                                                                                <th class="text-center">CANTIDAD</th>
                                                                                                <th class="text-center">UNIDAD</th>
                                                                                                <th class="text-center">PRECIO UNIT.</th>
                                                                                                <th>DESCRIPCION</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div id="form-3" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblIn" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <th class="text-center">Nº</th>
                                                                                            <th></th>
                                                                                            <th>CANTIDAD</th>
                                                                                            <th>UNIDAD</th>
                                                                                            <th>DESCRIPCION</th>
                                                                                            <th class="text-center">ACCIONES</th>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div id="form-7" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblProdFab" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nº</th>
                                                                                                <th class="text-center">CANTIDAD</th>
                                                                                                <th class="text-center">UNIDAD</th>
                                                                                                <th class="text-nowrap">PRODUCTO FABRICADO</th>
                                                                                                <th class="text-center">ACCIONES</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div id="form-8" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblDetalleFab" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nº</th>
                                                                                                <th class="text-center">CANTIDAD</th>
                                                                                                <th class="text-center">UNIDAD</th>
                                                                                                <th class="text-nowrap">PRODUCTO FABRICADO</th>
                                                                                                <th class="text-center">ACCIONES</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div id="form-9" class="form-container">
                                                                                <div class="table-responsive">
                                                                                    <table id="tblDetalleFabEntrada" class="table table-bordered w-100 table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nº</th>
                                                                                                <th class="text-nowrap">PRODUCTO FABRICADO</th>
                                                                                                <th class="text-center">UNIDAD</th>
                                                                                                <th class="text-center">SALIDA</th>
                                                                                                <th class="text-center">ENTRADA</th>
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
                                                        <?php if ($_SESSION["crear9"] && !$_SESSION["crear4"]) : ?>
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
                                                                    <div class="form-group" style="margin-bottom:1.6rem;">
                                                                        <label id="lbl" class="mb-0 combo"><i class="fas fa-clipboard-check"></i> Autorizado por</label>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <select id="cboAutorizado" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                                                                </select>
                                                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label id="lbl" class="mb-0 combo"><i class="fas fa-user-helmet-safety"></i> Responsable</label>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <select id="cboResponsable" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                                                                </select>
                                                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-form-label combo" for="inpMotivo">
                                                                            <i class="fas fa-clipboard-question"></i> Motivo</label>
                                                                        <textarea style="border-bottom: 2px solid var(--select-border-bottom);background-color:#f6f6f6" type="text" class="form-control form-control-sm" id="inpMotivo" placeholder="Traslado de herramientas"></textarea>
                                                                    </div>
                                                                    <div class="form-group" style="margin-bottom:.8rem;">
                                                                        <label class="col-form-label combo mb-2" for="inp">
                                                                            <i class="fa-solid fa-images"></i> Evidencia fotográfica</label>
                                                                        <div class="dropzone">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <button type="button" id="btnGuardarGuia" style="margin-bottom:.75rem;background:var(--label-star) linear-gradient(180deg, var(--label-star), var(--label-new)) repeat-x;color:#fff" class="btn w-100"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                                                                        <button type="button" id="Cancelar" style="margin-bottom:.75rem;display:none" onclick="limpiar(this);" class="btn bg-gradient-navy w-100"><i class="fas fa-xmark"></i><span class="button-text"> </span>Cancelar </button>
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
    <div class="modal-dialog modal-lg modal-rol modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h4 class="modal-title"><i class="fas fa-screwdriver-wrench"></i><span> Agregar Producto a Producción</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formFab" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal">
                    <input type="hidden" id="id_" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom:1.5rem;">
                                <label id="lbl" class="mb-0 combo"><i class="fas fa-trowel-bricks"></i> Producto</label>
                                <div class="row">
                                    <div class="col">
                                        <select id="cboFabricado" class="cbo form-control select2 select2-success flex-grow-1" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                    <div class="span-btn fab" style="padding-right:.5rem">
                                        <!-- <span style="display: ;" class="badge bg-gradient-dark" id="new_fab" title='Nuevo' data-target='#modal-fab' data-toggle='modal'><i class="fa-solid fa-plus"></i></span> -->
                                        <span style="display:none" class="dis badge bg-gradient-dark" data-value="Fab" data-target='#modal-new-fab' data-toggle='modal' id="edit_fab" title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                        <span style="display:none" class="dis badge bg-gradient-dark" data-value="Fab" id="eli_fab" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tblFab">
                                <thead style="background:#a7b2c1!important">
                                    <tr>
                                        <th class="text-center">Nº</th>
                                        <th></th>
                                        <th>CANTIDAD</th>
                                        <th>UND</th>
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
                    <button type="submit" id="btnAgregarFab" class="btn bg-gradient-blue"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-new-fab">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 id="title-fab" class="modal-title"><i class="fas fa-screwdriver-wrench"></i><span> Nuevo Producto en Producción</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formFabNew" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body ">
                    <input type="hidden" id="id_" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="nombre_fab" class="input-nuevo" type="text" required>
                                <div class="line underline"></div>
                                <label class="barra label">
                                    <i class="fa-solid fa-signature"></i> Descripción</label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                        </div>
                    </div>
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
                                <label class="mb-0 combo"><i class="fas fa-ruler"></i> Unidad</label>
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
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnAgregarFabNew" class="btn bg-gradient-green"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
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
    const formFab = document.getElementById('formFab'),
        formFabCon = document.getElementById('formFabCon'),
        formFabNew = document.getElementById('formFabNew');
    const modal_fab = document.getElementById('modal-new-fab');

    const nombre_fab = document.getElementById('nombre_fab'),
        cboUnidad_fab = document.getElementById('cboUnidad_fab'),
        cantidad_fab = document.getElementById('cantidad_fab');

    const cboFab = document.getElementById('cboFabricado'),
        cboFabCon = document.getElementById('cboFabricadoCon');

    const edit_fab = document.getElementById('edit_fab'),
        eli_fab = document.getElementById('eli_fab')

    const title_fab = document.querySelector('#title-fab span'),
        icon_fab = document.querySelector('.modal-title i'),
        elements_fab = document.querySelectorAll('#modal-new-fab .bg-gradient-green');

    const select_fab = formFabNew.querySelectorAll('.modal-body select.select2');
    const drop_element = document.querySelector(".dropzone");

    let id_e = 0;
    let accion_fab = 0;
    let id_producto_fab = 0;

    $(modal_fab).on("shown.bs.modal", () => {
        nombre_fab.focus();
    });

    Dropzone.autoDiscover = false;

    var dropzone = new Dropzone(".dropzone", {
        url: "/ruta/para/subir/archivo",
        autoProcessQueue: false,
        previewsContainer: null,
        acceptedFiles: ".jpg,.png,.jpeg,.webp", // Tipos de archivos permitidos
        addRemoveLinks: true,
        dictDefaultMessage: "Arrastra tus imagenes aquí o haz clic para subir",
        init: function() {
            const dzInstance = this;
            let removeAllFilesCalled = false; // Bandera para saber si se llamó a removeAllFiles

            // Evento para manejar errores
            dzInstance.on("error", function(file, errorMessage) {
                console.error("Error al subir el archivo:", errorMessage);
            });

            // Evento para éxito
            dzInstance.on("success", function(file, response) {
                console.log("Archivo subido exitosamente:", response);
            });

            dzInstance.on("removedfile", function(file) {
                // Verificar si se llamó a removeAllFiles, en ese caso no hacer nada en el servidor
                if (removeAllFilesCalled) {
                    // console.log("Archivo eliminado solo del contenedor, no del servidor.");
                    return; // No realizar ninguna acción con el servidor
                }

                // Si el archivo es existente en el servidor, eliminarlo del servidor
                if (file.isExisting) {
                    $.ajax({
                        url: 'controllers/salidas.controlador.php', // Ruta de tu controlador
                        type: 'POST',
                        data: {
                            accion: 9,
                            nombre_imagen: file.name // Nombre de la imagen a eliminar
                        },
                        success: function(response) {
                            // console.log("Imagen eliminada del servidor:", response);
                        }
                    });
                } else {
                    console.log("Imagen no existente en el servidor, eliminada solo del cliente.");
                }
            });

            // Función para limpiar los archivos del contenedor sin afectar al servidor
            dzInstance.removeAllFilesWithoutServer = function() {
                removeAllFilesCalled = true; // Indicamos que se ha llamado a removeAllFiles
                dzInstance.removeAllFiles(true); // Limpiar archivos del contenedor
                removeAllFilesCalled = false; // Resetear la bandera después de la limpieza
            };
        }
    });

    // Para limpiar los archivos del contenedor sin eliminarlos del servidor


    dropzone.on("sending", function(file, xhr, formData) {
        if (file.isExisting) {
            xhr.abort(); // Cancelar el envío de imágenes precargadas
        }
    });



    let tblFabCon = $('#tblFabCon').DataTable({
        "responsive": true,
        "dom": 'pt',
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        "ajax": {
            "url": "controllers/inventario.controlador.php",
            "type": "POST",
            "dataSrc": '',
            data: function(data) {
                data.accion = 11;
                data.id_producto_fab = id_producto_fab;
            }
        },
        columnDefs: [{
                targets: 0,
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1;
                    }
                    return meta.row;
                }
            },
            {
                targets: 1,
                className: "text-center ",
            },
            {
                targets: 2,
                className: "text-center ",
            },
        ],
    });

    $(edit_fab).on('click', function() {
        accion_fab = 10;
        let data_fab = cboFab.options[cboFab.selectedIndex].dataset;
        id_e = cboFab.value;
        // let orden_id = data_fab.orden;
        let cant_id = data_fab.cant;
        let und_id = data_fab.und;
        let name_id = data_fab.name;

        nombre_fab.value = name_id
        cantidad_fab.value = cant_id
        // setChange(cboOrdenFab, orden_id);
        setChange(cboUnidad_fab, und_id);

        cambiarModal(title_fab, ' Editar Producto en Producción', icon_fab, 'fa-pen-to-square', elements_fab, 'bg-gradient-green', 'bg-gradient-blue', modal_fab, 'modal-change', 'modal-new')

        select_fab.forEach(function(s) {
            s.classList.remove('select2-success');
            s.classList.add('select2-warning');
        });

        // Muestra el valor en la consola
        // name = this.dataset.value;
        // const selectE = document.getElementById('cbo' + name);
        // const iconS = this.dataset.icon;

        // inputId.value = selectE.value;
        // inputContent.value = selectE.options[selectE.selectedIndex].textContent;
        // cambiarModal(spanE, ' Editar ' + name, iconElement, iconS, elementsE, 'bg-gradient-green', 'bg-gradient-blue', modalS, 'modal-change', 'modal-new')
    });

    $(eli_fab).on('click', function() {
        accion_fab = 3;
        id_e = cboFab.value;
        // const id_val = document.getElementById('cbo' + name).value;
        // const tbl = 'tbl' + name.toLowerCase();
        let src = new FormData();
        src.append('accion', accion_fab);
        src.append('id', id_e);
        confirmarEliminar('este', 'producto fabricado', function(res) {
            if (res) {
                confirmarAccion(src, 'inventario', null, '', function(res) {
                    cargarAutocompletado();
                    cargarComboFabricado();
                    cargarCombo('FabricadoCon', '', 9);

                })
            }
        });
    });

    $(cboFab).change(function() {
        opcionSelect(this, 'fab')
    });


    $(cboFabCon).change(function() {
        if (this.value !== '')
            id_producto_fab = this.value;
        tblFabCon.ajax.reload(null, false)

    });



    formFabNew.addEventListener("submit", function(e) {
        e.preventDefault();
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }
        let nom = nombre_fab.value.trim().toUpperCase();
        let und = cboUnidad_fab.value;
        let cant = cantidad_fab.value;
        // let id_orden = cboOrdenFab.value;

        let datos = new FormData();
        datos.append('id_e', id_e);
        datos.append('nombre', nom);
        datos.append('unidad', und);
        datos.append('cantidad', cant);
        // datos.append('id_orden', id_orden);
        datos.append('accion', accion_fab);
    })

    formFab.addEventListener("submit", function(e) {
        e.preventDefault();
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }
        let formData = new FormData();
        let clases = ['cantidad'];
        formData.append('id_producto_fab', cboFab.value);
        formData.append('accion', 7);
        realizarRegistro(tblFab, formData, clases);
    })
</script>