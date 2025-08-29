<?php require_once "../utils/database/config.php";

$id_user = ($_SESSION["s_usuario"]->id == 1) ? true : false;
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
            <?php if (isset($_SESSION["crear3"]) && $_SESSION["crear3"] === true) : ?>
                <div class="col">
                    <button id="btnNuevo" class="btn bg-gradient-green" data-toggle="modal" data-target="#modalI">
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
                                    <h3 class="card-title" style="white-space:nowrap;display: flex;align-items: center;">Listado de productos
                                        <div class="icon-container ml-1">
                                            <input autocomplete="off" style="border:none" type="checkbox" id="check_stock">
                                            <label class="m-0" for="check_stock">
                                                <i style="font-size:1.5rem;" id="stock_icon" class="fas fa-arrow-down-big-small"></i>
                                            </label>
                                        </div>
                                    </h3>
                                </div>
                                <div class="col-sm p-0">
                                    <div class="card-tools">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search icon"></i></span>
                                            <input autocomplete="off" style="border:none" type="search" id="_search" onpaste="return trimPaste(event, this)" oninput="Buscar(tabla,this)" class="form-control float-right" placeholder="Buscar">
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
                                    <th class="text-center">IMAGEN</th>
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
            <div class="modal-header bg-gradient-green">
                <h4 class="modal-title"><i class="fa-solid fa-layer-plus"></i><span> Nuevo Producto</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevo" autocomplete="off" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal" style="padding-block:.5rem .25rem;">
                    <ul class="nav nav-tabs" id="tabInventario" role="tablist" style="margin-bottom:.5rem;">
                        <li class="nav-item">
                            <a class="nav-link active" id="detalles-tab" data-toggle="pill" href="#detalles" role="tab" aria-selected="true">DETALLES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="medidas-tab" data-toggle="pill" href="#medidas" role="tab" aria-selected="false">MEDIDAS</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tabInventarioContent">
                        <div class="tab-pane fade show active" id="detalles" role="tabpanel">
                            <input type="hidden" id="id" value="">
                            <div class="row" style="align-items:flex-start">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="input-data">
                                                <input autocomplete="off" id="codigo" name="codigo" class="input-nuevo" type="text" required>
                                                <label class="label"><i class="fa-solid fa-barcode"></i> Código</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-data">
                                                <input autocomplete="off" id="nombre" class="input-nuevo" type="text" required>
                                                <label class="label"><i class="fa-solid fa-input-text"></i> Descripción</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-6" id="divStockIni">
                                            <div class="input-data">
                                                <?php echo  $id_user ? '' : '<span class="input-group-text" style="position:absolute;top:56%;right:12px"><i class="fas fa-lock"></i></span>'; ?>
                                                <input autocomplete="off" id="stock_ini" name="stock_ini" class="input-nuevo" type="text" <?php echo  $id_user ? '' : 'readonly'; ?> onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)">
                                                <label class="label"><i class="fas fa-boxes-stacked"></i> Cantidad Inicial</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col">
                                            <div class="input-data s1">
                                                <input type="text" id="stock" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" required>
                                                <label class="label"><i class="fas fa-cubes"></i> Cantidad</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class=" input-data" style="margin-bottom:26px">
                                                <input type="text" id="stock_min" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9]/g)" required>
                                                <label style="font-size:21px" id="min" class="label"><i class="fas fa-box"></i> Cantidad Min.</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-data" style="margin-bottom:26px">
                                                <input type="text" id="stock_mal" maxlength="10" inputmode="numeric" autocomplete="off" class="input-nuevo" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9]/g)">
                                                <label class="label"><i class="fas fa-hammer-crash"></i> Dañado</label>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
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
                                                <div id="Categoria" class="invalid-feedback">*Campo obligatorio</div>
                                            </div>
                                            <div class="span-btn cat" style="padding-right:.5rem;">
                                                <span class="new-span badge bg-gradient-dark" data-icon="fa-tags" data-value="Categoria" data-target='#modalS' data-toggle='modal' title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                                <?php if ($_SESSION["editar3"]) : ?>
                                                    <span style="display:none" class="dis e-span badge bg-gradient-dark" data-icon="fa-tags" data-value="Categoria" data-target='#modalS' data-toggle='modal' title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                                <?php endif; ?>
                                                <?php if ($_SESSION["eliminar3"]) : ?>
                                                    <span style="display:none" class="dis d-span badge bg-gradient-dark" data-value="Categoria" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="lblU" class="mb-0 combo"><i class="fas fa-ruler"></i> Unidad</label>
                                        <div class="row">
                                            <div class="col">
                                                <select name="cboUnidad" id="cboUnidad" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                </select>
                                                <div id="Unidad" class="invalid-feedback">*Campo obligatorio</div>
                                            </div>
                                            <div class="span-btn u" style="padding-right:.5rem">
                                                <span class="new-span badge bg-gradient-dark" data-icon="fa-ruler" data-value="Unidad" data-target='#modalS' data-toggle='modal' title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                                <?php if ($_SESSION["editar3"]) : ?>
                                                    <span style="display:none" class="dis e-span badge bg-gradient-dark" data-icon="fa-ruler" data-value="Unidad" data-target='#modalS' data-toggle='modal' title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                                <?php endif; ?>
                                                <?php if ($_SESSION["eliminar3"]) : ?>
                                                    <span style="display:none" class="dis d-span badge bg-gradient-dark" data-value="Unidad" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row flex-column-reverse flex-md-row">
                                        <div class="col-md-6 form-group">
                                            <label class="combo" style="font-size: 1.15rem;"><i class="fas fa-image"></i> Imagen</label>
                                            <input type="file" name="fileImg" id="fileImg" class="form-control" accept=".png, .jpg, .jpeg, .webp">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label id="lblP" class="mb-0 combo"><i class="fa-solid fa-route"></i> Ubicación</label>
                                                <div class="row">
                                                    <div class="col">
                                                        <select name="cboUbicacion" id="cboUbicacion" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                        </select>
                                                        <div id="Ubicacion" class="invalid-feedback">*Campo obligatorio</div>
                                                    </div>
                                                    <div class="span-btn p" style="padding-right:.5rem;">
                                                        <span class="new-span badge bg-gradient-dark" data-icon="fa-route" data-value="Ubicacion" data-target='#modalS' data-toggle='modal'><i class="fa-solid fa-plus"></i></span>
                                                        <?php if ($_SESSION["editar3"]) : ?>
                                                            <span style="display:none" class="dis e-span badge bg-gradient-dark" data-icon="fa-route" data-value="Ubicacion" data-target='#modalS' data-toggle='modal' title="Editar"><i class="fa-solid fa-pencil"></i></span>
                                                        <?php endif; ?>
                                                        <?php if ($_SESSION["eliminar3"]) : ?>
                                                            <span style="display:none" class="dis d-span badge bg-gradient-dark" data-value="Ubicacion" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="medidas" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="number" min="1" id="medidaCantidad" class="form-control" value="1" placeholder="Cantidad">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Alto (m)</label>
                                        <input type="number" step="0.01" min="0" id="medidaAlto" class="form-control" placeholder="Alto">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Ancho (m)</label>
                                        <input type="number" step="0.01" min="0" id="medidaAncho" class="form-control" placeholder="Ancho">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="button" id="btnAgregarMedida" class="btn bg-gradient-green"><i class="fas fa fa-plus"></i><span class="button-text"> </span>Agregar Medida</button>

                                    </div>
                                </div>
                            </div>
                            <ul id="listaMedidasProducto" class="list-group mb-2"></ul>
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
<!-- Modal -->
<div class="modal fade" id="modalH">
    <div class="modal-dialog modal-xl modal-dialog-scrollable ">
        <div class="modal-content">
            <div class="modal-header bg-gradient-light" style="align-items: center;">
                <div class="row">
                    <div class="col-auto" style="padding-block:.2rem">
                        <h4 class="modal-title text-wrap"><i class="fas fa-clock-rotate-left"></i> Historial de producto</h4>
                    </div>
                    <div class="col">
                        <select id="cboAnio" class="form-control select2 select2-dark historial" data-dropdown-css-class="select2-dark">
                        </select>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body " style="padding-block:0rem .5rem;">

                <div class="row sticky-content">
                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between">
                        <div class="mb-2 mb-md-0">
                            <label for="stockInicial" class="font-weight-bold">Stock Inicial:</label>
                            <span id="stockInicial"></span>
                        </div>
                        <div>
                            <label for="descripcionProducto" class="font-weight-bold">Descripción:</label>
                            <span id="descripcionProducto"></span>
                        </div>
                    </div>
                </div>
                <table id="tblHistorial" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>Nº DE ORDEN</th>
                            <th>EMPRESA / PROVEEDOR</th>
                            <th>SALIDA</th>
                            <th>ENTRADA</th>
                            <th>COMPRA</th>
                            <th class="text-center">STOCK</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Modal SELECTS -->
<div class="modal fade" id="modalS" style="background-color:#424a51b0;-webkit-backdrop-filter:blur(16px);backdrop-filter:blur(16px);">
    <div class="modal-dialog modal-sm" style="top:20%">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
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

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body p-0" style="display:contents;">
                <img id="modalImage" src="" alt="Imagen completa" class="img-fluid" style="max-width:100%;height:auto;max-height:85vh;object-fit:contain;">
            </div>
        </div>
    </div>
</div>
<!-- Modal Medidas -->
<div class="modal fade" id="modalMedidas" tabindex="-1" aria-labelledby="modalMedidasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title" id="modalMedidasLabel"><i class="fas fa-ruler-combined"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-3 pb-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                            </div>
                            <input style="border-bottom-width:1px;" type="number" min="1" id="modalMedidaCantidad" class="form-control" placeholder="Cantidad">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-arrows-up-down"></i></span>
                            </div>
                            <input style="border-bottom-width:1px;" type="number" step="0.01" min="0" id="modalMedidaAlto" class="form-control" placeholder="Alto (m)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-arrows-left-right"></i></span>
                            </div>
                            <input style="border-bottom-width:1px;" type="number" step="0.01" min="0" id="modalMedidaAncho" class="form-control" placeholder="Ancho (m)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btnAgregarMedidaModal" class="btn bg-gradient-info w-100">
                            <i class="fas fa-plus"></i> Agregar medida
                        </button>
                    </div>
                </div>
                <ul id="listaMedidas" class="list-group">
                    <!-- Aquí se cargan las medidas -->
                </ul>
                <div id="sinMedidas" class="text-center text-muted mt-2" style="display:none;">
                    No hay medidas registradas para este producto.
                </div>


            </div>
        </div>
    </div>
</div>
<!-- Fin Modal Medidas -->



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


    var mostrarCol = '<?php echo $_SESSION["editar3"] || $_SESSION["eliminar3"] ?>';
    var editar = '<?php echo $_SESSION["editar3"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar3"] ?>';

    configuracionTable = {
        "dom": '<"row"<"col-md-6"B><"col-md-6"p>>t',
        "responsive": true,
        "pageLength": 20, // Cambia este valor según tus necesidades
        "lengthChange": false,
        "ordering": false,
        "autoWidth": false,
        "deferRender": true,
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
                responsivePriority: 1,
                render: function(data, type, row) {
                    let cantidad = row.cantidad_medidas;
                    let span = '';
                    if (cantidad && cantidad > 0) {
                        span = `<span class="badge badge-info right" style="vertical-align:super;font-size:0.65em;cursor:pointer;" data-id="${row.id}" data-nombre="${row.descripcion}">${cantidad}</span>`;
                    }
                    return `<div>${data} ${span}</div>`;
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
                responsivePriority: 3,
                render: function(data, type, row) {
                    let resultado = row.stock - row.stock_mal;
                    let stockMin = row.stock_min;
                    let comparacion = resultado < stockMin ? 'text-danger' : resultado > stockMin ? 'text-success' : 'text-info';
                    let formatR = resultado.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    return `<span style='font-size:1.3rem;text-wrap:nowrap' class="${comparacion} font-weight-bold">${formatR} </span>`;
                }
            },
            {
                targets: 9,
                data: "img",
                responsivePriority: 4,
                className: "text-center",
                render: function(data, type, row) {
                    if (data) {
                        return `<img
                        onclick="openModalImage(this)"
                        data-toggle="modal" 
                        data-target="#imageModal"
                        src="../products/${data}" 
                        class="img-thumbnail" 
                        loading="lazy" style="cursor:pointer;width: 50px; height: 50px;">`;
                    } else {
                        return `<span style="height:50px;width:50px" class="d-inline-flex justify-content-center align-items-center img-thumbnail"><i style="color:#555" class="fas fa-xl fa-image-slash"></i></span>`;
                    }
                }
            },
            {
                targets: 10,
                data: "acciones",
                visible: mostrarCol,
                responsivePriority: 2,
                render: function(data, type, row, full, meta) {
                    return (
                        "<center style='white-space: nowrap;'>" +
                        (editar ?
                            " <button type='button' class='btn bg-gradient-warning btnEditar' data-target='#modalI' data-toggle='modal'  title='Editar'>" +
                            " <i class='fa-solid fa-pencil'></i>" +
                            "</button>" : "") +
                        " <button type='button' class='btn bg-gradient-light btnHistorial' data-target='#modalH' data-toggle='modal'  title='Consultar historial'>" +
                        " <i class='fas fa-clock-rotate-left'></i>" +
                        "</button>" +
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
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            }
        ]
    }

    function openModalImage(element) {
        // Obtener la URL de la imagen desde el atributo data-img-src
        const imgSrc = element.src;

        // Asignar la URL al src de la imagen dentro del modal
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imgSrc;
    }



    $(document).ready(function() {
        let anio = year;
        let id_producto = 0;
        const checkbox = document.getElementById('check_stock');
        const icona = document.getElementById('stock_icon');
        const divStockIni = document.getElementById('divStockIni');

        // Agregar evento de cambio al input
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                accion_inv = 6;
                tabla.ajax.reload();
            } else {
                accion_inv = 0;
                tabla.ajax.reload();
            }
        });

        if (!$.fn.DataTable.isDataTable('#tblInventario')) {
            tabla = $("#tblInventario").DataTable({
                "ajax": {
                    "url": "controllers/inventario.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.accion = accion_inv;
                    }
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                if ($(window).width() >= 768) { // Verificar si el ancho de la ventana es mayor o igual a 768 píxeles
                    const b = document.body;
                    const s = b.scrollHeight + 20;
                    const w = window.innerHeight;
                    console.log(b + ' ' + s + ' hanfle ' + w)
                    handleScroll(b, s, w);
                }
                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('i', JSON.stringify(tablaData));
            });
        }

        tblHistorial = $("#tblHistorial").DataTable({
            "ajax": {
                "url": "controllers/inventario.controlador.php",
                "type": "POST",
                "dataSrc": '',
                data: function(data) {
                    data.accion = 12;
                    data.anio = anio;
                    data.id_producto = id_producto;
                }
            },
            "dom": 't',
            "responsive": false,
            "pageLength": 500,
            "lengthChange": false,
            "ordering": false,
            "autoWidth": false,
            columnDefs: [{
                    targets: 2,
                    render: function(data, type, row) {
                        let resultado = row.producto_util ? '<span class="alert alert-default-dark mb-0"><i class="fas fa-hammer-crash"></i> FAB</span>' : '';
                        return `<div style="display:flex;justify-content:space-between;align-items:center">${data + resultado} </div>`;
                    }
                },
                {
                    targets: 3,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let resultado = row.salida ?? '-';
                        let formatR = resultado.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return `<span style='font-size:1.3rem;text-wrap:nowrap' class="text-danger" font-weight-bold">${formatR} </span>`;
                    }
                }, {
                    targets: 4,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let resultado = row.entrada ?? '-';
                        let formatR = resultado.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return `<span style='font-size:1.3rem;text-wrap:nowrap' class="text-success" font-weight-bold">${formatR} </span>`;
                    }
                },
                {
                    targets: 5,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let resultado = row.compras ?? '-';
                        let formatR = resultado.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return `<span style='font-size:1.3rem;text-wrap:nowrap' class="text-success" font-weight-bold">${formatR} </span>`;
                    }
                },
                {
                    targets: 6,
                    className: 'text-center',
                    responsivePriority: 3,
                    render: function(data, type, row) {
                        let resultado = row.stock ?? 0;
                        let formatR = resultado.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return `<span style='font-size:1.3rem;text-wrap:nowrap' class="text-info" font-weight-bold">${formatR} </span>`;
                    }
                },
            ],
        });

        let name;
        let opcion = '0';
        let imgActual;
        let oldCod;
        const id = document.getElementById('id'),
            codigo = document.getElementById('codigo'),
            descripcion = document.getElementById('nombre'),
            stock = document.getElementById('stock'),
            stock_min = document.getElementById('stock_min'),
            stock_mal = document.getElementById('stock_mal'),
            stock_ini = document.getElementById('stock_ini'),
            cboCategoria = document.getElementById('cboCategoria'),
            cboUnidad = document.getElementById('cboUnidad'),
            fileImg = document.getElementById('fileImg'),
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
            elements = document.querySelectorAll('#modalI .bg-gradient-green'),
            elementsE = document.querySelectorAll('#modalS .bg-gradient-green'),
            select = document.querySelectorAll('#formNuevo .modal-body select.select2');

        $(cboCategoria).select2({
            placeholder: 'SELECCIONE',
            width: '100%',
        });

        $(cboAnio).select2({
            width: '105%',
            data: datos_anio,

            minimumResultsForSearch: -1,
        })

        setChange(cboAnio, anio);

        $(cboUnidad).select2({
            placeholder: 'SELECCIONE',
            width: '100%',
        })

        $(cboUbicacion).select2({
            placeholder: 'SELECCIONE',
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

        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            anio = a
            tblHistorial.ajax.reload();
            // Parámetros POST
            const params = new URLSearchParams();
            params.append('accion', 13);
            params.append('anio', anio);
            params.append('id_producto', id_producto); // Asegúrate de que id_producto esté definido

            // Realizar el fetch
            fetch('controllers/inventario.controlador.php', {
                    method: 'POST',
                    body: params
                })
                .then(response => response.json())
                .then(data => {
                    const stockInicialElement = document.getElementById('stockInicial');
                    if (Array.isArray(data) && data.length > 0) {
                        const stock_ini_r = data[0].stock_ini || 0;
                        let r = stock_ini_r.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        stockInicialElement.textContent = r;
                    } else {
                        stockInicialElement.textContent = '0.00';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        });

        $(document).on('click', '#tblInventario tbody span.badge-info', function() {
            const id_producto = $(this).data('id');
            const nombre = $(this).data('nombre');
            $('#modalMedidasLabel').html(`<i class="fas fa-ruler-combined"></i>  ${nombre}`);
            $('#modalMedidas').modal('show');
            cargarMedidasProducto(id_producto);
            $('#modalMedidas').data('id_producto', id_producto);
        });

        var medidasProducto = []; // Array local para las medidas del producto

        // Función para cargar medidas en el tab de medidas (cuando editas o creas un producto)
        function cargarMedidasTab(id_producto) {
            $.post('controllers/inventario.controlador.php', {
                accion: 21,
                id_producto
            }, function(res) {
                let data = [];
                try {
                    data = JSON.parse(res);
                } catch {}
                medidasProducto = Array.isArray(data) ? data : [];
                actualizarListaMedidas();
            });
        }

        function cargarMedidasProducto(id_producto) {
            $.post('controllers/inventario.controlador.php', {
                accion: 21,
                id_producto
            }, function(res) {
                let data = [];
                try {
                    data = JSON.parse(res);
                } catch {}
                const lista = $('#listaMedidas');
                lista.empty();
                if (Array.isArray(data) && data.length > 0) {
                    $('#sinMedidas').hide();
                    data.forEach(m => {
                        lista.append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <b>Cantidad:</b> ${m.cantidad} | 
                                <b>Alto:</b> ${m.alto}m | 
                                <b>Ancho:</b> ${m.ancho}m | 
                                <b>Área:</b> ${parseFloat(m.area_m2_total).toFixed(2)}m²
                            </span>
                            <button class="btn btn-danger btn-sm btnEliminarMedida" data-id="${m.id}"><i class="fa fa-trash"></i></button>
                        </li>
                    `);
                    });
                } else {
                    $('#sinMedidas').show();
                }
            });
        }

        function actualizarListaMedidas() {
            const lista = $('#listaMedidasProducto');
            lista.empty();
            if (medidasProducto.length > 0) {
                medidasProducto.forEach((m) => {
                    lista.append(`
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <b>Cantidad:</b> ${m.cantidad} | 
                        <b>Alto:</b> ${m.alto}m | 
                        <b>Ancho:</b> ${m.ancho}m | 
                        <b>Área:</b> ${parseFloat(m.area_m2_total).toFixed(2)}m²
                    </span>
                    <button type="button" class="btn btn-danger btn-sm btnEliminarMedidaTab" data-id="${m.id}" title="Eliminar"><i class="fa fa-trash"></i></button>
                </li>
            `);
                });
            } else {
                lista.append(`<li class="list-group-item text-center text-muted">No hay medidas registradas.</li>`);
            }
        }



        $(document).on('click', '.btnEliminarMedidaTab', function() {
            const id_medida = $(this).data('id');
            confirmarEliminar('esta', 'medida', function(res) {
                if (res) {
                    $.post('controllers/inventario.controlador.php', {
                        accion: 24,
                        id_medida
                    }, function() {
                        const id_producto = $('#id').val();
                        cargarMedidasTab(id_producto); // Recarga la lista desde BD
                        accion_inv = 0;
                        tabla.ajax.reload();
                    });
                }
            });
        });

        $(document).on('click', '.btnEliminarMedida', function() {
            const id_medida = $(this).data('id');
            confirmarEliminar('esta', 'medida', function(res) {
                if (res) {
                    $.post('controllers/inventario.controlador.php', {
                        accion: 24,
                        id_medida
                    }, function() {
                        const id_producto = $('#modalMedidas').data('id_producto');
                        cargarMedidasProducto(id_producto);
                        accion_inv = 0;
                        tabla.ajax.reload();
                    });
                }
            });
        });

        $('#btnAgregarMedidaModal').off('click').on('click', function() {
            const id_producto = $('#modalMedidas').data('id_producto');
            const cantidad = parseInt($('#modalMedidaCantidad').val()) || 0;
            const alto = parseFloat($('#modalMedidaAlto').val()) || 0;
            const ancho = parseFloat($('#modalMedidaAncho').val()) || 0;
            if (cantidad > 0 && alto > 0 && ancho > 0) {
                const area_m2_total = cantidad * alto * ancho;
                const medidas = [{
                    cantidad,
                    alto,
                    ancho,
                    area_m2_total
                }];
                $.post('controllers/inventario.controlador.php', {
                    accion: 22,
                    id_producto,
                    medidas: JSON.stringify(medidas)
                }, function(res) {
                    cargarMedidasProducto(id_producto);
                    $('#modalMedidaCantidad').val('');
                    $('#modalMedidaAlto').val('');
                    $('#modalMedidaAncho').val('');
                    accion_inv = 0;
                    tabla.ajax.reload();
                });
            } else {
                alert('Completa todos los campos correctamente.');
            }
        });

        $('#btnAgregarMedida').on('click', function() {
            const id_producto = $('#id').val(); // El id del producto actual
            const cantidad = parseInt($('#medidaCantidad').val()) || 0;
            const alto = parseFloat($('#medidaAlto').val()) || 0;
            const ancho = parseFloat($('#medidaAncho').val()) || 0;
            if (cantidad > 0 && alto > 0 && ancho > 0 && id_producto) {
                const area_m2_total = cantidad * alto * ancho;
                const medidas = [{cantidad,alto,ancho,area_m2_total}];
                $.post('controllers/inventario.controlador.php', {
                    accion: 22,
                    id_producto,
                    medidas: JSON.stringify(medidas)
                }, function(res) {
                    cargarMedidasTab(id_producto); // Recarga la lista desde BD
                    $('#medidaCantidad').val('1');
                    $('#medidaAlto').val('');
                    $('#medidaAncho').val('');
                    accion_inv = 0;
                    tabla.ajax.reload();
                    // Recarga la tabla de inventario
                });
            } else {
                alert('Completa todos los campos correctamente.');
            }
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

        $(modalE).on("hidden.bs.modal", function() {
            if (scroll) {
                const navbar = document.getElementById('navbar-fix');
                $(navbar).css('margin-right', '');
            }
            accion_inv = 0;
        });

        $(modalS).on("shown.bs.modal", function() {
            inputContent.focus();

        });

        $(modalS).on('hidden.bs.modal', function(e) {
            e.preventDefault();
            if (scroll) {
                const navbar = body.querySelector('.navbar')
                $(body).addClass('modal-open');
                $(body).css('padding-right', '6px');
            }
        });

        if (btnNuevo) {
            btnNuevo.addEventListener('click', function() {
                if (scroll) {
                    const navbar = document.getElementById('navbar-fix');
                    $(navbar).css('margin-right', '6px');
                }
                accion_inv = 1;
                medidasProducto = [];
                actualizarListaMedidas();
                cambiarModal(span, ' Nuevo Producto', icon, 'fa-layer-plus', elements, 'bg-gradient-blue', 'bg-gradient-green', modalE, 'modal-new', 'modal-change')
                select.forEach(function(s) {
                    s.classList.remove('select2-warning');
                    s.classList.add('select2-success');
                });
                divStockIni.style.display = 'none';
                form.reset();
                fileImg.value = '';
                stock_mal.value = '0';
                $(".modal-body .select2").val(0).trigger("change");
                form.classList.remove('was-validated');
            });
        }

        $('#tblInventario tbody').on('click', '.btnEditar', function() {
            if (scroll) {
                const navbar = document.getElementById('navbar-fix');
                $(navbar).css('margin-right', '6px');
            }
            let row = obtenerFila(this, tabla);
            accion_inv = 2;
            cargarMedidasTab(row["id"]);
            cambiarModal(span, ' Editar Producto', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modalE, 'modal-change', 'modal-new')
            select.forEach(function(s) {
                s.classList.remove('select2-success');
                s.classList.add('select2-warning');
            });
            id.value = row["id"];
            fileImg.value = '';
            codigo.value = row["codigo"];
            descripcion.value = row["descripcion"];
            stock.value = row["stock"];
            stock_min.value = row["stock_min"];
            stock_mal.value = row["stock_mal"];
            stock_ini.value = row["stock_ini"];
            imgActual = row["img"];
            oldCod = row["codigo"];
            setChange(cboCategoria, row["categoria_id"]);
            setChange(cboUnidad, row["unidad_id"]);
            setChange(cboUbicacion, row["percha_id"]);
            divStockIni.style.display = 'block';
        });

        $('#tblInventario tbody').on('click', '.btnHistorial', function() {
            if (scroll) {
                const navbar = document.getElementById('navbar-fix');
                $(navbar).css('margin-right', '6px');
            }
            let row = obtenerFila(this, tabla);
            let stockInicial = row["stock_ini"] ?? 0;
            stockInicial = stockInicial.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') // Ajusta la clave según tu tabla
            let descripcion = row["descripcion"]; // Ajusta la clave según tu tabla
            id_producto = row["id"]; // ID del producto
            setChange(cboAnio, year);
            // Actualizar los valores en el modal
            document.getElementById('stockInicial').textContent = stockInicial;
            document.getElementById('descripcionProducto').textContent = descripcion;

        });

        $('#tblInventario tbody').on('click', '.btnEliminar', function() {
            const e = obtenerFila(this, tabla)
            accion_inv = 3
            const id = e["id"];
            let src = new FormData();
            src.append('accion', accion_inv);
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
                // console.log('no valido')
                return;
            }

            const data = new FormData();
            const file = fileImg.files[0]; // Obtener el archivo

            // Validar si el archivo es una imagen PNG, JPG o WebP
            if (file && (file.type !== "image/png" && file.type !== "image/jpeg" && file.type !== "image/webp")) {
                mostrarToast(
                    'warning',
                    'Advertencia',
                    'fa-triangle-exclamation',
                    'Por favor inserta una imagen con la extención .png, .jpg o .webp', 3000
                );
                return;
            } else if (file) {
                data.append('fileImg', file); // Si es válido, añadir el archivo al FormData
            }

            data.append('id', id.value);
            data.append('cod', codigo.value.trim().toUpperCase());
            data.append('des', descripcion.value.trim().toUpperCase());
            data.append('sto', stock.value);
            data.append('st_min', stock_min.value);
            data.append('st_ini', stock_ini.value);
            data.append('st_mal', stock_mal.value);
            data.append('cat', cboCategoria.value);
            data.append('uni', cboUnidad.value);
            data.append('ubi', cboUbicacion.value);
            data.append('img', imgActual);
            data.append('oldCod', oldCod);
            data.append('accion', accion_inv);
            data.append('medidas', JSON.stringify(medidasProducto));

            confirmarAccion(data, 'inventario', tabla, modalE, function(res) {
                cargarAutocompletado();
                medidasProducto = [];
                actualizarListaMedidas();
            });
        });

        $(".new-span").on('click', function() {
            opcion = 1;
            name = this.dataset.value;
            const selectE = document.querySelector('#cbo' + name);
            const iconS = this.dataset.icon;
            inputId.value = selectE.value;
            cambiarModal(spanE, ' Nueva ' + name, iconElement, iconS, elementsE, 'bg-gradient-blue', 'bg-gradient-green', modalS, 'modal-new', 'modal-change')
            formS.reset();
            formS.classList.remove('was-validated');
        });

        $(".e-span").on('click', function() {
            opcion = 2;
            name = this.dataset.value;
            const selectE = document.getElementById('cbo' + name);
            const iconS = this.dataset.icon;
            inputId.value = selectE.value;
            inputContent.value = selectE.options[selectE.selectedIndex].textContent;
            cambiarModal(spanE, ' Editar ' + name, iconElement, iconS, elementsE, 'bg-gradient-green', 'bg-gradient-blue', modalS, 'modal-change', 'modal-new')
        });

        $(".d-span").on('click', function() {
            opcion = 3;
            name = this.dataset.value;
            const id_val = document.getElementById('cbo' + name).value;
            const tbl = 'tbl' + name.toLowerCase();
            let src = new FormData();
            src.append('accion', opcion);
            src.append('id', id_val);
            src.append('tabla', tbl);
            confirmarEliminar('esta', name, function(res) {
                if (res) {
                    confirmarAccion(src, 'producto', null, '', function(res) {
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
                const nombre = inputContent.value.trim();
                const tbl = 'tbl' + name.toLowerCase();
                const data = new FormData();
                data.append('id', ids);
                data.append('nombre', nombre);
                data.append('accion', opcion);
                data.append('tabla', tbl);
                confirmarAccion(data, 'producto', null, modalS, function(res) {
                    if (res) {
                        cargarCombo(name, ids);
                    }
                });
            }
        });
    });
</script>