<!-- Control Sidebar -->
<aside class="control-sidebar second-sidebar control-sidebar-light" style="overflow:hidden auto;">
    <div class="p-3 ">
        <div class="row" style="align-items:flex-start">
            <form id="form_ppt" style="display:contents" autocomplete="off" class="needs-validation" novalidate>
                <div class="col-xl-9">
                    <div class="card" id="card_pla">
                        <div class="card-body">
                            <form id="formPla" autocomplete="off" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-6 form-group" style="margin-bottom: 1.4rem;">
                                        <label id="lbl" class="mb-0 combo"><i class="fa-solid fa-pager"></i> Plantilla</label>

                                        <div class="row" style="flex-wrap:nowrap;">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col">

                                                        <select id="cboPlantilla" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                        </select>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>

                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="span-btn d-flex align-items-center justify-content-end" id="div_span" style="padding:.5rem">
                                                            <span class="badge bg-gradient-dark" id="new_pla" title='Nuevo' data-target='#modal-pla' data-toggle='modal'><i class="fa-solid fa-plus"></i></span>
                                                            <span style="display:none" class="badge bg-gradient-dark" id="edit_orden" title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                                            <span style="display:none" class="badge bg-gradient-dark" id="eli_orden" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-6 form-group" style="margin-bottom: 1.4rem;">
                                        <label class="col-form-label combo" for="entrega_ppt">
                                            <i class="fa-solid fa-table-rows"></i> Filas</label>
                                        <div class="row">
                                            <div class="col-12" style="display: flex;align-items:end;">
                                                <span>Agregar</span> <input id="entrega_ppt" autocomplete="off" style="border-bottom: 2px solid var(--select-border-bottom); text-align: center" type="text" class="form-control form-control-sm" required>
                                                <span style="margin-right:.5rem;">Fila(s)</span>
                                                <div class="span-btn d-flex align-items-center justify-content-end" style="padding:0">
                                                    <span class="badge bg-gradient-dark" id="" title='Nuevo'><i class="fa-solid fa-plus"></i></span>
                                                </div>
                                                <div class="invalid-feedback">*Campo obligatorio.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <button type="submit" id="btnFab" style="margin-bottom:1.8rem" class="btn btn-block btn-outline-dark"><i class="fas fa-floppy-disks"></i><span id=" " class="button-text"> Guardar</span></button> -->
                            </form>
                            <!-- <div class="table-responsive">
                                <table class="table" id="tblFab">
                                    <thead style="background:#eef1f3!important">
                                        <tr>
                                            <th>CANTIDAD</th>
                                            <th>DESCRIPCION</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div> -->
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="padding:1.25em">
                            <div class="row">
                                <div class="col">
                                    <div class="input-data">
                                        <label style="font-size:1.15rem;color:var(--select-border-bottom)" for="fecha_ppt"><i class="fas fa-calendar"></i> Fecha</label>
                                        <input style="font-size:1.28rem" autocomplete="off" id="fecha_ppt" type="date" value="<?php echo date('Y-m-d'); ?>" required>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label id="lbl" class="mb-0 combo"><i class="fas fa-stamp"></i> Atención</label>
                                        <select id="cboEmpleadoAtencion" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label id="lbl" class="mb-0 combo"><i class="fas fa-user-tag"></i> Cliente</label>
                                        <select id="cboCliente_ppt" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <label class="col-form-label combo" for="entrega_ppt">
                                        <i class="fas fa-truck-clock"></i> Entrega</label>
                                    <input id="entrega_ppt" autocomplete="off" style="border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" required>
                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label class="col-form-label combo" for="pago_ppt">
                                        <i class="fas fa-money-bill"></i> Forma de pago</label>
                                    <input id="pago_ppt" autocomplete="off" style="border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" required>
                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label class="col-form-label combo" for="descrip">
                                        <i class="fas fa-input-text"></i> Descripción </label>
                                    <input id="descrip" autocomplete="off" style="border-bottom: 2px solid var(--select-border-bottom);" type="text" class="form-control form-control-sm" required>
                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                </div>
                            </div>
                            <div id="formTblPpt">
                                <div class="table-responsive">
                                    <table id="tblppt" class="table table-bordered w-100 table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nº</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-center">ACCIONES</th>
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
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" id="btnGuardarPpt" style="margin-bottom:.75rem;" class="btn btn-success w-100"><i class="fas fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                            <button type="button" id="cerrar_ppt" onclick="second_control.click();" style="border-color:#d6d8df69" class="btn bg-gradient-light w-100"><i class="fas fa-right-from-bracket"></i><span class="button-text"> </span>Cerrar</button>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </form>
        </div>
    </div>
</aside>
<!-- /.control-sidebar -->

<div class="modal fade" id="modal-pla">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 class="modal-title"><i class="fa-solid fa-pager"></i><span> Nueva Plantilla</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPlantilla" autocomplete="off" class="needs-validation" novalidate>
                <div class="modal-body scroll-modal">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-data ">
                                <input type="text" id="nombre_pla" autocomplete="off" class="input-nuevo" required>
                                <label class="label"><i class="fas fa-signature"></i> Plantilla</label>
                                <div class="invalid-feedback">*Campo obligatorio.</div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-12">
                            <div class="form-group">
                                <label id="lbl" class="mb-0 combo"><i class="fas fa-ruler"></i> Unidad</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="c" id="cboUnidad_fab" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                        </select>
                                        <div class="invalid-feedback">*Seleccione una unidad</div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="cboEncabezados" class="mb-0 combo"><i class="fa-solid fa-input-text"></i> Encabezado</label>
                                <div class="row">
                                    <div class="col">
                                        <select name="cboEncabezados" id="cboEncabezados" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark">
                                        </select>
                                    </div>
                                    <div class="span-btn d-flex align-items-center justify-content-end" style="padding-right:0">
                                        <span class="badge bg-gradient-dark" id="row_encabezado" title='Agregar'><i class="fa-solid fa-plus"></i></span>
                                        <span style="display:none" class="badge bg-gradient-dark" id="edit_orden" title='Editar'><i class="fa-solid fa-pencil"></i></span>
                                        <span style="display:none" class="badge bg-gradient-dark" id="eli_orden" title='Eliminar'><i class="fa-solid fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="tblPlantilla" class="table table-bordered w-100 table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nro</th>
                                        <th class="text-center">ENCABEZADO</th>
                                        <th class="text-center">TAMAÑO</th>
                                        <th class="text-center">ACCIONES</th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="btnAgregarPla" class="btn bg-gradient-green"><i class="fa-solid fa-floppy-disk"></i><span class="button-text"> </span>Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-right-from-bracket"></i> Cerrar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-encabezado" style="background-color:#424a51b0;backdrop-filter:blur(16px);">
    <div class="modal-dialog modal-sm" style="top:20%">
        <div class="modal-content">
            <div class="modal-header bg-gradient-green">
                <h4 id="span-title" class="modal-title"><i class="fa-solid fa-input-text"></i><span> Nuevo Encabezado</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevoS" class="needs-validation" autocomplete="off" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="id_encabezado" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-data">
                                <input autocomplete="off" id="nombre_encabezado" class="input-nuevo" type="text" required>
                                <label class="label"><i class="fa-solid fa-input-text"></i> Encabezado</label>
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
    $(document).ready(function() {
        cargarCombo('Plantilla');
        cargarCombo('Encabezados');

        const cboplantilla = document.getElementById('cboPlantilla')
        const cboencabezado = document.getElementById('cboEncabezados');

        const btnAgregar_Enc = document.getElementById('row_encabezado'),
            btnAgregar_Pla = document.getElementById('new_pla'),
            btnGuardarPla = document.getElementById('btnAgregarPla'),
            formPlantilla = document.getElementById('formPlantilla'),
            inp_plantilla = document.getElementById('nombre_pla');

        $(cboplantilla).select2({
            placeholder: 'SELECCIONE',
            width: 'auto',
        })

        $(cboencabezado).select2({
            placeholder: 'SELECCIONE',
            width: 'auto',
        })

        let tblPlantilla = $('#tblPlantilla').DataTable({
            "dom": 'pt',
            "responsive": true,
            "lengthChange": false,
            "ordering": false,
            "autoWidth": false,
            "paging": false,
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
                    targets: 2,
                    className: "text-center",
                },
            ],
        });

        $('#tblPlantilla tbody').on('click', '.btnEliminarIn', function() {
            tblPlantilla.row($(this).parents('tr')).remove().draw();
        });

        btnAgregar_Pla.addEventListener('click', () => {
            formPlantilla.reset();
            formPlantilla.classList.remove('was-validated');
            tblPlantilla.clear().draw(); // Esta línea vacía los datos de la tabla

        })

        btnAgregar_Enc.addEventListener('click', () => {
            console.log(cboencabezado.value)
            $.ajax({
                url: "controllers/encabezado.controlador.php",
                method: "POST",
                data: {
                    'accion': 4, //BUSCAR PRODUCTOS POR id_producto
                    'id_encabezado': cboencabezado.value,
                },
                dataType: 'json',
                success: function(respuesta) {
                    tblPlantilla.row.add([
                        ' ',
                        respuesta['nombre'],
                        '<input type="text" style="width:82px;border-bottom-width:2px;padding:0;font-size:1.4rem" class="form-control text-center d-inline tamaño" inputmode="numeric" autocomplete="off" onpaste="validarPegado(this, event)" onkeydown="validarTecla(event,this)" oninput="validarNumber(this,/[^0-9.]/g)" value="20">',
                        "<center>" +
                        "<span class='btnEliminarIn text-danger'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar producto'> " +
                        "<i style='font-size:1.8rem;padding-top:.3rem' class='fa-regular fa-circle-xmark'> </i> " +
                        "</span>" +
                        "</center>",
                        respuesta['id']
                    ]).node().id = "header_" + respuesta['id'];
                    tblPlantilla.draw(false);
                    setChange(cboencabezado, 0);
                }
            });
        })

        formPlantilla.addEventListener("submit", function(e) {
            e.preventDefault()
        });

        btnGuardarPla.addEventListener('click', () => {
            let formData = new FormData();
            let elementosAValidar = [inp_plantilla];
            let isValid = true;
            elementosAValidar.forEach(function(elemento) {
                if (!elemento.checkValidity()) {
                    isValid = false;
                    formPlantilla.classList.add('was-validated');
                }
            });
            if (!isValid) {
                return;
            }
            let clases = ['tamaño'];
            formData.append('nombre_pla', inp_plantilla.value);
            formData.append('accion', 4);
            realizarRegistro(tblPlantilla, formData, clases, 4, 'encabezados');
            $('modal-pla').hide();
        })


    })
</script>