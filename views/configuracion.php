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
                                    <a class="nav-link w-100 txt-ellipsis" id="vert-tabs-ope-tab" data-toggle="pill" href="#vert-tabs-ope" role="tab" aria-controls="vert-tabs-ope" aria-selected="false"> <i class="tab-icon fas fa-screwdriver-wrench"></i> Operaciones</a>
                                    <a class="nav-link w-100 txt-ellipsis" id="vert-tabs-rrhh-tab" data-toggle="pill" href="#vert-tabs-rrhh" role="tab" aria-controls="vert-tabs-rrhh" aria-selected="false"> <i class="nav-icon fas fa-people-group"></i> R.R.H.H</a>
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
                                                <br>
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
                                                <br>
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
                                                    <label for="inputNroCompra" class="col-sm-6 col-form-label d-flex align-items-center text-wrap">
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
                                    <div class="tab-pane fade" id="vert-tabs-ope" role="tabpanel" aria-labelledby="vert-tabs-ope-tab">
                                        <div class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
                                            <!-- Título principal -->
                                            <h5 style="font-weight:bold;"> <i class="fas fa-tickets nav-icon"></i> Orden de Trabajo</h5>
                                            <hr>
                                            <form id="formConfigOpe" autocomplete="off" class="form-horizontal needs-validation" style="align-items:flex-start" novalidate>
                                                <div id="emailContainer">
                                                    <div class="mb-3">
                                                        <label for="email-1" style="font-weight:bold;text-wrap:wrap"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                                                        <input spellcheck="false" autocomplete="off" type="email" id="email-1" name="email[]" class="border-2 form-control" placeholder="example@aistermcon.com" required>
                                                    </div>
                                                </div>
                                                <!-- Botones para agregar o eliminar correos -->
                                                <div class="form-group">
                                                    <button type="button" id="addEmailButton" class="btn btn-success">
                                                        <i class="fas fa-plus-circle"></i> Agregar correo
                                                    </button>
                                                </div>
                                                <br>
                                                <div class="col-sm-12 p-0">
                                                    <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardarOpe">
                                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-rrhh" role="tabpanel" aria-labelledby="vert-tabs-rrhh-tab">
                                        <div class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
                                            <form id="formConfigRRHH" autocomplete="off" class="form-horizontal needs-validation" style="align-items:flex-start" novalidate>
                                                <div class="form-group row mb-3 justify-content-between">
                                                    <!-- El label ocupa 6 columnas -->
                                                    <label for="inputSbu" class="col-sm-8 mb-2 col-form-label d-flex align-items-center text-wrap">
                                                        <i class="fas fa-sack-dollar mr-2"></i> Sueldo basico unificado
                                                    </label>
                                                    <!-- El input está contenido en un div que ocupa el resto del espacio -->
                                                    <div class="col-sm d-flex justify-content-between align-items-center mb-2">
                                                        $ <input type="text" style="text-align:center"
                                                            oninput="validarNumber(this, /[^0-9.]/g)"
                                                            autocomplete="off"
                                                            maxlength="8"
                                                            value="<?php echo isset($_SESSION['sbu']) ? $_SESSION['sbu'] : ''; ?>"
                                                            class="form-control border-2 w-100"
                                                            id="inputSbu"
                                                            placeholder="Sueldo basico unificado"
                                                            spellcheck="false"
                                                            required>
                                                        <div class="invalid-feedback">*Campo obligatorio.</div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="col-sm-12 p-0">
                                                    <button class="btn btn-primary btn-small w-100 text-center" id="btnGuardarRRHH">
                                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                                    </button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                        <div class="card-body" style="padding:1rem 1.5rem 1.25rem 0.5rem">
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
    $(document).ready(function() {
        fetch('controllers/configuracion.controlador.php')
            .then(response => response.json())
            .then(correos => {
                console.log(correos); // Verifica qué datos recibes

                // Verificar que 'correos' es un array antes de usar .forEach
                if (Array.isArray(correos)) {
                    const emailContainer = document.getElementById('emailContainer');
                    let emailCounter = 1;

                    // Iteramos sobre los correos y los agregamos al formulario
                    correos.forEach((correo, index) => {
                        if (index === 0) {
                            // Si es el primer correo, lo agregamos al primer input
                            const emailInput = emailContainer.querySelector('input[name="email[]"]');
                            emailInput.value = correo; // Asignamos el correo recuperado
                        } else {
                            // Si es un correo adicional, agregamos un nuevo campo dinámico
                            emailCounter++;
                            const emailRow = document.createElement('div');
                            emailRow.className = 'row mb-3';
                            emailRow.id = `email-row-${emailCounter}`;
                            emailRow.innerHTML = `
                        <div class="col">
                            <input spellcheck="false" autocomplete="off" type="email" id="email-${emailCounter}" name="email[]" class="form-control border-2" placeholder="example@aistermcon.com" required value="${correo}">
                        </div>
                        <div class="col-auto text-right">
                            <button type="button" class="btn bg-gradient-danger removeEmailButton">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                            emailContainer.appendChild(emailRow);

                            // Agregar evento para eliminar este campo dinámico
                            emailRow.querySelector('.removeEmailButton').addEventListener('click', function() {
                                emailRow.remove();
                                emailCounter--; // Decrementa el contador
                            });
                        }
                    });
                } else {
                    console.error('Los correos no son un array:', correos);
                }
            })
            .catch(error => {
                console.error('Error al cargar los correos:', error);
            });

        let emailCounter = 1; // Contador para generar IDs únicos

        document.getElementById('addEmailButton').addEventListener('click', function() {
            emailCounter++; // Incrementa el contador

            // Crea un nuevo campo dinámico usando filas y columnas de Bootstrap
            const emailRow = document.createElement('div');
            emailRow.className = 'row mb-3';
            emailRow.id = `email-row-${emailCounter}`;
            emailRow.innerHTML = `
            <div class="col">
                <input spellcheck="false" autocomplete="off" type="email" id="email-${emailCounter}" name="email[]" class="form-control border-2" placeholder="example@aistermcon.com" required>
            </div>
            <div class="col-auto text-right">
                <button type="button" class="btn bg-gradient-danger removeEmailButton">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

            // Agregar el nuevo grupo al contenedor
            document.getElementById('emailContainer').appendChild(emailRow);

            // Agregar evento para eliminar este campo dinámico
            emailRow.querySelector('.removeEmailButton').addEventListener('click', function() {
                emailRow.remove();
                emailCounter--; // Decrementa el contador
            });
        });



        const formConfigD = document.getElementById('formConfigD'),
            formConfigM = document.getElementById('formConfigM'),
            formConfigC = document.getElementById('formConfigC'),
            formConfigG = document.getElementById('formConfigG'),
            formConfigOpe = document.getElementById('formConfigOpe'),
            formConfigRRHH = document.getElementById('formConfigRRHH'),
            formConfigP = document.getElementById('formConfigP');

        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
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
                form: formConfigRRHH,
                fields: [{
                        id: 'inputSbu',
                        key: 'sbu',
                        transform: (element) => element.value.trim()
                    },
                    {
                        key: 'accion',
                        value: 7
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
                confirmarAccion(datos, 'configuracion', null, '', function(r) {
                    if (r && form == formConfigC) {
                        iva_config = r.iva;
                        nro_sec_cotiz = r.sc;
                    }else if (r && form == formConfigRRHH) {
                        sbu_config = parseFloat(r.sbu);
                    }
                });
            });
        });


        formConfigOpe.addEventListener("submit", function(e) {
            e.preventDefault();
            // Obtén los inputs de email una sola vez
            const emailInputs = formConfigOpe.querySelectorAll('input[name="email[]"]');

            // Valida los correos
            const {
                correos,
                correosInvalidos
            } = validarCorreos(emailInputs, regexCorreo);

            if (correosInvalidos.length > 0) {
                // Si hay correos inválidos, muestra el mensaje de error
                const mensaje = correosInvalidos.length === 1 ?
                    `El correo '${correosInvalidos[0]}' no es válido.` :
                    `Los correos '${correosInvalidos.join(', ')}' no son válidos.`;

                mostrarToast('danger', 'Error', 'fa-xmark', mensaje, 8000);
                return;
            }

            // Si los correos son válidos, crear el FormData y agregar los correos
            let datos = new FormData(formConfigOpe); // Agrega todos los datos del formulario
            datos.append('accion', 6); // Usa índices para diferenciarlos
            datos.append('correos', JSON.stringify(correos));

            // Llamar a la función confirmarAccion para enviar los datos al backend
            confirmarAccion(datos, 'configuracion', null, '', function(r) {
                if (r) {

                }
            });
        });


        function validarCorreos(emailInputs, regex) {
            const correos = [];
            const correosInvalidos = [];

            for (const input of emailInputs) {
                const correo = input.value.trim();
                correos.push(correo);

                if (!regex.test(correo)) {
                    correosInvalidos.push(correo);
                }
            }
            return {
                correos,
                correosInvalidos
            };
        }
    });
</script>