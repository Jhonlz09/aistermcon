<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Configuración</title>
</head>
<script>
    window.scrollTo(0, 0);
</script>
<!-- Contenido Header -->
<section class="content-header stick-header" style="padding: 1.6rem .55rem;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Configuración</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->
<section class="content scroll">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="row" style="align-items: flex-start">
                            <div class="col-5 col-sm-3">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active w-100 txt-ellipsis" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true"> <i class="tab-icon fas fa-circle-info"></i> Datos generales</a>
                                    <a class="nav-link w-100 txt-ellipsis" id="vert-tabs-movimiento-tab" data-toggle="pill" href="#vert-tabs-movimiento" role="tab" aria-controls="vert-tabs-movimiento" aria-selected="false"> <i class="tab-icon fas fa-person-dolly"></i> Movimientos</a>
                                    <a class="nav-link w-100 txt-ellipsis" id="vert-tabs-compras-tab" data-toggle="pill" href="#vert-tabs-compras" role="tab" aria-controls="vert-tabs-compras" aria-selected="false"> <i class="tab-icon fas fa-cart-shopping"></i> Compras</a>
                                    <a class="nav-link w-100 txt-ellipsis" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false"> <i class="tab-icon fas fa-clipboard"></i> Guía</a>
                                    <a class="nav-link w-100 txt-ellipsis" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false"><i class="tab-icon fas fa-sliders-up"></i> Preferencias</a>
                                </div>
                            </div>
                            <div class="col-7 col-sm-9">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade active show" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                        <div class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
                                            <form id="formConfigD" enctype="multipart/form-data" autocomplete="off" class="needs-validation form-horizontal" style="align-items:flex-start" novalidate>
                                                <div class="form-group row mb-3">
                                                    <label for="inputEmpresa" style="padding-block:.5rem;max-width:8rem" class="col-sm-3 col-form-label"><i class="fas fa-industry"></i> Empresa</label>
                                                    <div class="col-sm">
                                                        <input type="text" autocomplete="off" value="<?php echo isset($_SESSION["empresa"]) ? $_SESSION["empresa"] : ''; ?>" class="form-control border-2" id="inputEmpresa" placeholder="Empresa" spellcheck="false" data-ms-editor="true" required>
                                                        <div class="invalid-feedback mt-0">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label for="logo" style="padding-block:.5rem;max-width:8rem" class="col-sm-3 col-form-label"><i class="fas  fa-icons"></i> Logo</label>
                                                    <div class="col-sm">
                                                        <input class="form-control border-2" type="file" name="logo" id="logo" accept=".png, .jpg, .jpeg">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="inpE"><i class="fas fa-input-text"></i> Empresa</label>
                                                        <input type="text" class="form-control border-2" id="nombre_empresa" name="inpInstitucion">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="logo"><i class="fas fa-icons"></i> Logo</label>
                                                        <input class="form-control border-2" type="file" name="logo" id="logo" accept=".png, .jpg, .jpeg">
                                                    </div>
                                                </div> -->
                                                <!-- <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="cboGrafico">Grafico de Inicio</label>
                                                    <select name="cboGrafico" id="cboGrafico" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                                        <option value="1">Documentos por mes</option>
                                                        <option value="2">Documentos por tipo</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                                <br>

                                                <!-- <h3 class="card-title" style="width: 100%;padding:1.4rem 0rem 1rem">
                                                <i class="fas fa-chart-pie" style="margin-right: 10px;margin-left:10px;"></i>Informe
                                            </h3> -->

                                                <div class="col-sm-12 p-0">
                                                    <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardarD">
                                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-movimiento" role="tabpanel" aria-labelledby="vert-tabs-movimiento-tab">
                                        <div class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
                                            <form id="formConfigM" autocomplete="off" class="form-horizontal needs-validation" style="align-items:flex-start" novalidate>
                                                <div class="form-group row mb-3">
                                                    <label style="cursor:pointer;padding-block:.5rem;max-width:max-content" for="isEntradaEdit" class="col-sm-3 col-form-label">Permitir ajustes de entradas</label>
                                                    <label class="switch-2 ml-3" for="isEntradaEdit">
                                                        <input class="switch__input" type="checkbox" id="isEntradaEdit" onkeydown="toggleWithEnter(event, this)" <?php echo (isset($_SESSION["entrada_mul"]) && $_SESSION["entrada_mul"] == 1) ? 'checked' : ''; ?>>
                                                        <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                            <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                                        </svg>
                                                    </label>
                                                </div>

                                                <!-- <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="inpE"><i class="fas fa-input-text"></i> Empresa</label>
                                                        <input type="text" class="form-control border-2" id="nombre_empresa" name="inpInstitucion">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="logo"><i class="fas fa-icons"></i> Logo</label>
                                                        <input class="form-control border-2" type="file" name="logo" id="logo" accept=".png, .jpg, .jpeg">
                                                    </div>
                                                </div> -->
                                                <!-- <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="cboGrafico">Grafico de Inicio</label>
                                                    <select name="cboGrafico" id="cboGrafico" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                                        <option value="1">Documentos por mes</option>
                                                        <option value="2">Documentos por tipo</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                                <br>

                                                <!-- <h3 class="card-title" style="width: 100%;padding:1.4rem 0rem 1rem">
                                                <i class="fas fa-chart-pie" style="margin-right: 10px;margin-left:10px;"></i>Informe
                                            </h3> -->

                                                <div class="col-sm-12 p-0">
                                                    <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardarM">
                                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                                    </button>
                                                </div>

                                            </form>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-compras" role="tabpanel" aria-labelledby="vert-tabs-compras-tab">
                                        <div class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
                                            <form id="formConfigC" autocomplete="off" class="form-horizontal needs-validation" style="align-items:flex-start" novalidate>

                                                <div class="form-group row mb-3">
                                                    <!-- El label ocupa 6 columnas -->
                                                    <label for="inputIVA" class="col-sm-6 col-form-label d-flex align-items-center">
                                                        <i class="fas fa-percent mr-2"> </i> IVA
                                                    </label>
                                                    <!-- El input está contenido en un div que ocupa el resto del espacio -->
                                                    <div class="col-sm-6 d-flex justify-content-end">
                                                        <input
                                                            type="text"
                                                            style="max-width: 10rem; text-align: center;"
                                                            oninput="validarNumber(this, /[^0-9]/g)"
                                                            autocomplete="off"
                                                            maxlength="2"
                                                            value="<?php echo isset($_SESSION['iva']) ? $_SESSION['iva'] : ''; ?>"
                                                            class="form-control border-2"
                                                            id="inputIVA"
                                                            placeholder="IVA"
                                                            spellcheck="false"
                                                            required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-3">
                                                    <!-- El label ocupa 6 columnas -->
                                                    <label for="inputNroCompra" class="col-sm-6 col-form-label d-flex align-items-center">
                                                        <i class="fas fa-arrow-up-9-1 mr-1"></i> Nº de orden de compra
                                                    </label>
                                                    <!-- El input está contenido en un div que ocupa el resto del espacio -->
                                                    <div class="col-sm-6 d-flex justify-content-end">
                                                        <input
                                                            type="text"
                                                            style="max-width:10rem;text-align:center;"
                                                            oninput="validarNumber(this, /[^0-9]/g)"
                                                            autocomplete="off"
                                                            maxlength="5"
                                                            value="<?php echo isset($_SESSION["sc_cot"]) ? $_SESSION["sc_cot"] : ''; ?>"
                                                            class="form-control border-2"
                                                            id="inputNroCompra"
                                                            placeholder="Nº de orden de compra"
                                                            spellcheck="false"
                                                            required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="col-sm-12 p-0">
                                                    <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardarC">
                                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                                    </button>
                                                </div>

                                            </form>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                        <div class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
                                            <!-- <div class="card-header p-0">
                                                <label style="font-size:1.15rem;margin-bottom:.4rem; width:100%"><i class="fas fa-clipboard"></i> Guía de Remisión</label>
                                            </div> -->
                                            <form id="formConfigG" autocomplete="off" class="form-horizontal needs-validation" style="align-items:flex-start" novalidate>
                                                <div class="form-group row mb-3">
                                                    <label for="inputEmisor" style="padding-block:.5rem;max-width:8rem;" class="col-sm-3 col-form-label"><i class="fas fa-scroll"></i> Emisor</label>
                                                    <div class="col-sm">
                                                        <input type="text" autocomplete="off" value="<?php echo isset($_SESSION["emisor"]) ? $_SESSION["emisor"] : ''; ?>" class="form-control border-2" id="inputEmisor" placeholder="Emisor" spellcheck="false" data-ms-editor="true" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label for="inputRuc" style="padding-block:.5rem;max-width:8rem;" class="col-sm-3 col-form-label"><i class="fas fa-address-card"></i> Ruc</label>
                                                    <div class="col-sm">
                                                        <input type="text" autocomplete="off" value="<?php echo isset($_SESSION["ruc"]) ? $_SESSION["ruc"] : ''; ?>" class="form-control border-2" id="inputRuc" placeholder="Ruc" spellcheck="false" data-ms-editor="true" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label for="inputMatriz" style="padding-block:.5rem;max-width:8rem;" class="col-sm-3 col-form-label"><i class="fas fa-apartment"></i> Matriz</label>
                                                    <div class="col-sm">
                                                        <input type="text" autocomplete="off" value="<?php echo isset($_SESSION["matriz"]) ? $_SESSION["matriz"] : ''; ?>" class="form-control border-2" id="inputMatriz" placeholder="Matriz" spellcheck="false" data-ms-editor="true" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>

                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label for="inputCorreo1" style="padding-block:.5rem;max-width:8rem;" class="col-sm-3 col-form-label"><i class="fas fa-envelope"></i> Correo</label>
                                                    <div class="col-sm">
                                                        <input type="text" autocomplete="off" value="<?php echo isset($_SESSION["correo1"]) ? $_SESSION["correo1"] : ''; ?>" class="form-control border-2" id="inputCorreo1" placeholder="Correo electronico" spellcheck="false" data-ms-editor="true" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label for="inputTelefono" style="padding-block:.5rem;max-width:8rem;" class="col-sm-3 col-form-label"><i class="fas fa-phone"></i> Teléfono</label>
                                                    <div class="col-sm">
                                                        <input type="text" autocomplete="off" value="<?php echo isset($_SESSION["telefono"]) ? $_SESSION["telefono"] : ''; ?>" class="form-control border-2" id="inputTelefono" placeholder="Teléfono" spellcheck="false" data-ms-editor="true" required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>

                                                <br>

                                                <div class="col-sm-12 p-0">
                                                    <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardarG">
                                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                                    </button>
                                                </div>

                                            </form>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade " id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                                        <div id="card_preferencia" class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
                                            <form id="formConfigP" autocomplete="off" class="needs-validation" style="align-items:flex-start" novalidate>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cboBodeguero" class="mb-0 combo"><i class="fa-solid fa-person-carry-box"></i> Bodeguero</label>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <select id="cboBodeguero" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                    </select>
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cboTransportista" class="mb-0 combo"><i class="fas fa-steering-wheel"></i> Conductor</label>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <select id="cboTransportista" class="cbo form-control select2 select2-success" data-dropdown-css-class="select2-dark" required>
                                                                    </select>
                                                                    <div class="invalid-feedback">*Campo obligatorio.</div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- <div class="form-group row mb-3">
                                                    <label style="cursor:pointer;padding-block:.5rem;max-width:max-content" for="isEntradaEdit" class="col-sm-3 col-form-label"> Activar alerta poco stock</label>
                                                    <label class="switch-2 ml-3" for="isAlert">
                                                        <input class="switch__input" type="checkbox" id="isAlert" onkeydown="toggleWithEnter(event, this)" <?php echo (isset($_SESSION["entrada_mul"]) && $_SESSION["entrada_mul"] == 1) ? 'checked' : ''; ?>>
                                                        <svg class="switch__check" viewBox="0 0 16 16" width="16px" height="16px">
                                                            <polyline class="switch__check-line" fill="none" stroke-dasharray="9 9" stroke-dashoffset="3.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5,8 11,8 11,11" />
                                                        </svg>
                                                    </label>
                                                </div> -->
                                                <br>
                                                <div class="col-sm-12 p-0">
                                                    <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardarC">
                                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                                    </button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<script>
    // var Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 3000
    // });

    // console.log(id_usuario);
    // $(function() {
    //     //Initialize Select2 Elements
    //     $('.select2').select2({
    //         width: '100%',
    //         minimumResultsForSearch: -1,
    //         dropdownAutoWidth: false

    //     })
    // })

    $(document).ready(function() {
        const formConfigD = document.getElementById('formConfigD'),
            formConfigM = document.getElementById('formConfigM'),
            formConfigC = document.getElementById('formConfigC'),
            formConfigG = document.getElementById('formConfigG'),
            formConfigP = document.getElementById('formConfigP');

        const cboBodeguero = document.getElementById('cboBodeguero'),
            cboTransportista = document.getElementById('cboTransportista');

        cargarCombo('Transportista', conductorPorDefecto, 2);
        cargarCombo('Bodeguero', bodegueroPorDefecto, 6);

        $(cboBodeguero).select2({
            placeholder: 'SELECCIONE',
            minimumResultsForSearch: -1,
        })

        $(cboTransportista).select2({
            placeholder: 'SELECCIONE',
            minimumResultsForSearch: -1,
        })

        const forms = [{
                form: formConfigD,
                fields: [{
                        id: 'inputEmpresa',
                        key: 'empresa',
                        transform: (element) => element.value.trim().toUpperCase()
                    },
                    {
                        key: 'accion',
                        value: 1
                    }
                ]
            },
            {
                form: formConfigC,
                fields: [{
                        id: 'inputIVA',
                        key: 'iva',
                        transform: (element) => element.value.trim()
                    },
                    {
                        id: 'inputNroCompra',
                        key: 'nro_cot',
                        transform: (element) => element.value.trim()
                    },
                    {
                        key: 'accion',
                        value: 3
                    }
                ]
            },
            {
                form: formConfigM,
                fields: [{
                        id: 'isEntradaEdit',
                        key: 'isentrada',
                        transform: (element) => element.checked ? 1 : 0
                    },
                    {
                        key: 'accion',
                        value: 2
                    }
                ]
            },
            {
                form: formConfigG,
                fields: [{
                        id: 'inputEmisor',
                        key: 'emisor',
                        transform: (element) => element.value.trim().toUpperCase()
                    },
                    {
                        id: 'inputRuc',
                        key: 'ruc',
                        transform: (element) => element.value.trim()
                    },
                    {
                        id: 'inputMatriz',
                        key: 'dir',
                        transform: (element) => element.value.trim().toUpperCase()
                    },
                    {
                        id: 'inputCorreo1',
                        key: 'correo1',
                        transform: (element) => element.value.trim()
                    },
                    {
                        id: 'inputTelefono',
                        key: 'tel',
                        transform: (element) => element.value.trim()
                    },
                    {
                        key: 'accion',
                        value: 4
                    }
                ]
            },
            {
                form: formConfigP,
                fields: [{
                        id: 'cboBodeguero',
                        key: 'bodeguero',
                        transform: (element) => element.value
                    },
                    {
                        id: 'cboTransportista',
                        key: 'conductor',
                        transform: (element) => element.value
                    },
                    {
                        key: 'accion',
                        value: 5
                    }
                ]
            },
        ];

        forms.forEach(({
            form,
            fields
        }) => {
            form.addEventListener("submit", function(e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    this.classList.add('was-validated');
                    return;
                }

                let datos = new FormData();
                fields.forEach(({
                    id,
                    key,
                    value,
                    transform
                }) => {
                    let fieldValue;
                    const element = document.getElementById(id);
                    if (id) {
                        fieldValue = transform ? transform(element) : element.value;
                    } else {
                        fieldValue = value;
                    }
                    datos.append(key, fieldValue);
                });
                confirmarAccion(datos, 'configuracion', null);
                // if (form == formConfigC) {
                //     setTimeout(function() {
                //         location.reload();
                //     }, 700);
                // }
            });
        });

        // formConfigD.addEventListener("submit", function(e) {
        //     e.preventDefault();
        //     const nom = document.getElementById('inputEmpresa').value.trim().toUpperCase();
        //     const logo = 0;

        //     if (!this.checkValidity()) {
        //         this.classList.add('was-validated');
        //         return;
        //     }
        //     let datos = new FormData();
        //     datos.append('empresa', nom);
        //     datos.append('accion', 1);
        //     confirmarAccion(datos, 'configuracion', null)
        // });

        // formConfigC.addEventListener("submit", function(e) {
        //     e.preventDefault();
        //     const iva = document.getElementById('inputIVA').value.trim();

        //     if (!this.checkValidity()) {
        //         this.classList.add('was-validated');
        //         return;
        //     }
        //     let datos = new FormData();
        //     datos.append('iva', iva);
        //     datos.append('accion', 3);
        //     confirmarAccion(datos, 'configuracion', null)
        // });

        // formConfigM.addEventListener("submit", function(e) {
        //     e.preventDefault();
        //     const ismultientrada = document.getElementById('isEntradaEdit').checked;

        //     if (!this.checkValidity()) {
        //         this.classList.add('was-validated');
        //         return;
        //     }
        //     let datos = new FormData();
        //     datos.append('isentrada', ismultientrada);
        //     datos.append('accion', 2);
        //     confirmarAccion(datos, 'configuracion', null)
        // });


    });
</script>