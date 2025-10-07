<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Movimientos</title>
    <!-- <link href="assets/plugins/datatables-scroller/css/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css"> -->
    <link href="assets/plugins/datatables-searchpanes/css/searchPanes.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="assets/plugins/datatables-select/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
</head>

<!-- Contenido Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Movimientos</h1>
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
                                    <h3 class="card-title ">Listado de movimientos</h3>
                                </div>
                                <div class=" col col-sm-auto">
                                    <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                    </select>
                                </div>
                                <div class="col-sm-auto">
                                    <select name="cboMeses" id="cboMeses" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                        <option value="null">TODO</option>
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
                        <table id="tblSalidas" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>CÓDIGO</th>
                                    <th>DESCRIPCION</th>
                                    <th>UND</th>
                                    <th></th>
                                    <th>SALIDA</th>
                                    <th>ENTRADA</th>
                                    <th>NRO. ORDEN</th>
                                    <th>CLIENTE</th>
                                    <th>NRO. GUIA</th>
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
<div class="modal fade" id="modalMateriales" tabindex="-1" role="dialog" aria-labelledby="modalMaterialesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-yellow d-flex justify-content-between align-items-center">
                <!-- Título a la izquierda -->
                <h5 class="modal-title mb-0 d-flex align-items-center" id="modalMaterialesLabel">
                    <i class="fas fa-hammer-crash mr-2"></i>
                    Productos fabricados
                </h5>
                <!-- Ícono y botón a la derecha -->
                <div class="d-flex align-items-center">
                    <i id="btn_pdf_fab" class="fas fa-file-lines mr-3 fa-xl" style="transition: color 0.3s ease;cursor:pointer" title="Informe de productos fabricados"></i>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div id="accordionMateriales">
                    <!-- Aquí se insertarán los acordeones -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var mostrarCol = '<?php echo $_SESSION["editar4"] || $_SESSION["eliminar4"] ?>';
    var crear = '<?php echo $_SESSION["crear4"] ?>';
    var editar = '<?php echo $_SESSION["editar4"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar4"] ?>';
    var collapsedGroups = {};

    configuracionTable = {
        "responsive": true,
        "dom": 'Ptp',
        "lengthChange": false,
        "pageLength": 100,
        "ordering": false,
        "autoWidth": false,
        "paging": true,
        searchPanes: {
            cascadePanes: true,
            columns: [2, 8, 9],
            initCollapsed: true,
            threshold: 0.8, // Ajusta este valor según tus necesidades
            dtOpts: {
                select: {
                    style: 'multiple'
                }
            },
        },
        initComplete: function() {
            $('.dtsp-titleRow').remove();
        },
        rowGroup: {
            dataSrc: [4],
            startRender: function(rows, group) {
                var collapsed = !!collapsedGroups[group];
                rows.nodes().each(function(r) {
                    $(r).toggleClass('collapsedrow', !collapsed);
                });
                const fabricacion = rows.data().pluck('fab')[0];
                const traslado = rows.data().pluck('tras')[0];
                const texto = traslado ? 'Fabricado' : 'No trasladado';
                const fabricacionSpan = fabricacion ? '<span class="badge bg-fab mr-2">' + texto + '</span>' : '';

                const groupText = '<div class="d-flex justify-content-between align-items-center" style="cursor:pointer">' +
                    '<strong class="pl-2">' + fabricacionSpan + group + '  (' + rows.count() + ')</strong>' +
                    '<div class="txt-wrap-sm">' +
                    (fabricacion ? '<button class="btn btn-row pt-0 pb-0 btn_show"><i class="fas fa-eye"></i></button>' : '') +
                    '<button class="btn btn-row pt-0 pb-0 btn_pdf"><i class="fas fa-file-pdf"></i></button>' +
                    '<button class="btn btn-row pt-0 pb-0 btn_pdf_img"><i class="fas fa-file-image"></i></button>' +
                    (editar ? '<button id="editS" class="btn btn-row pt-0 pb-0"><i class="fas fa-pen-to-square"></i></button>' : '') +
                    (crear ? '<button id="editR" class="btn btn-row pt-0 pb-0"><i class="fas fa-clipboard-list-check"></i></button>' : '') +
                    (eliminar ? '<button id="eliS" class="btn btn-row pt-0 pb-0"><i class="fas fa-trash-can"></i></button>' : '') +
                    '</div></div>';
                return $('<tr/>')
                    .append('<td colspan="8">' + groupText + '</td>') // Ajustar el colspan según el número de columnas
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
        },
        columnDefs: [{
                targets: 0,
                data: 'fila',
                className: "text-center",
            },
            {
                targets: 2,
                responsivePriority: 1
            },
            {
                targets: 3,
                className: "text-center",
                responsivePriority: 2
            },
            {
                targets: 4,
                visible: false,
            },
            {
                targets: 5,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data === null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                targets: 6,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data === null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                targets: 7,
                visible: false,
            }, {
                targets: 8,
                visible: false,
            }, {
                targets: 9,
                visible: false,
            },
        ],
    }


    $('#tblSalidas tbody').on('click', 'tr.dtrg-start', function() {
        if ($(event.target).closest('.txt-wrap-sm').length === 0) {
            let scrollPosition = $(window).scrollTop();
            var name = $(this).data('name');
            collapsedGroups[name] = !collapsedGroups[name];
            tabla.rows().every(function() {
                if (this.child.isShown()) {
                    this.child.hide();
                    $(this.node()).removeClass('parent'); // opcional, para limpiar la clase que indica expansión
                }
            });
            tabla.draw(false)
            $(window).scrollTop(scrollPosition);
        }
    });

    $(document).ready(function() {
        let anio = year;
        let mes = month;
        let id_boleta_pdf;
        if (!$.fn.DataTable.isDataTable('#tblSalidas')) {
            tabla = $("#tblSalidas").DataTable({
                "ajax": {
                    "url": "controllers/salidas.controlador.php",
                    "type": "POST",
                    "dataSrc": '',
                    data: function(data) {
                        data.anio = anio;
                        data.mes = mes;
                    }
                },
                ...configuracionTable
            });

            tabla.on('draw.dt', function() {
                if ($(window).width() >= 768) { // Verificar si el ancho de la ventana es mayor o igual a 768 píxeles
                    const b = document.body;
                    const s = b.scrollHeight + 58;
                    const w = window.innerHeight;

                    handleScroll(b, s, w);
                }

                let tablaData = tabla.rows().data().toArray();
                localStorage.setItem('s', JSON.stringify(tablaData));
            });
        }

        let accion = 0;
        const modal = document.querySelector('.modal'),
            span = document.querySelector('.modal-title span'),
            elements = document.querySelectorAll('.modal .bg-gradient-green'),
            form = document.getElementById('formNuevo'),
            form_pdf = document.getElementById('form_pdf'),
            btnNuevo = document.getElementById('btnNuevo');
        const input_pdf = document.getElementById('input_boleta');
        const btn_pdf_fab = document.getElementById('btn_pdf_fab');

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        });

        setChange(cboAnio, anio);

        $(cboMeses).select2({
            minimumResultsForSearch: -1,
            width: 'calc(100% + .4vw)',
            data: datos_meses,
        });
        setChange(cboMeses, mes);


        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if (a == anio) {
                return;
            }
            anio = a
            if (cboMeses.value == 'null') {
                mes = null;
            } else {
                mes = cboMeses.value;
            }
            tabla.ajax.reload(function() {
                tabla.searchPanes.resizePanes();
            }, false);
        });

        $(cboMeses).on("change", function() {
            let m = this.value;
            if (m == mes) {
                return;
            }
            if (m == 'null') {
                mes = null;
            } else {
                mes = m;
            }

            anio = cboAnio.options[cboAnio.selectedIndex].text;
            tabla.ajax.reload(function() {
                tabla.searchPanes.resizePanes();
            }, false);
        });

        $('#tblSalidas').on('click', '.btn_pdf', function(event) {
            event.preventDefault(); // Evita la acción predeterminada
            let rowData = tabla.row($(this).closest('tr').next()).data();
            let boleta = rowData[10]; // Asumiendo que el índice 10 es el de la boleta
            enviarFormularioPDF('PDF/pdf_guia.php', boleta);
        });

        $('#tblSalidas').on('click', '.btn_pdf_img', function(event) {
            event.preventDefault(); // Evita la acción predeterminada
            let rowData = tabla.row($(this).closest('tr').next()).data();
            let boleta = rowData[10]; // Asumiendo que el índice 10 es el de la boleta
            enviarFormularioPDF('PDF/pdf_guia_img.php', boleta);
        });

        $('#tblSalidas tbody').on('click', '.btn_show', function() {
            const row = tabla.row($(this).closest('tr').next()).data();
            id_boleta_pdf = row[10];
            // console.log(id_boleta_row);
            $('#modalMateriales').modal('show');
            cargarProductosFabricados(id_boleta_pdf);
        });

        btn_pdf_fab.addEventListener('click', function() {
            console.log(id_boleta_pdf);
            enviarFormularioPDF('PDF/pdf_guia_fab.php', id_boleta_pdf);
        });


        function cargarProductosFabricados(id_boleta) {
            fetch('controllers/fabricacion.controlador.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        accion: 5,
                        id_boleta: id_boleta,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    construirAcordeon(data);
                })
                .catch(error => {
                    console.error('Error al obtener los datos:', error);
                });
        }

        $('#modalMateriales').on('hidden.bs.modal', function() {
            document.getElementById("accordionMateriales").innerHTML = '';
        });

        function formatNumber(value) {
            return parseFloat(value).toString(); // quita ceros innecesarios como '1.00' => '1'
        }

        function construirAcordeon(data) {
            const contenedor = document.getElementById("accordionMateriales");
            // contenedor.innerHTML = "";

            contenedor.classList.remove('accordion-animate');
            void contenedor.offsetWidth; // Forzar reflow
            contenedor.classList.add('accordion-animate');

            // Agrupar productos fabricados con sus materiales utilizados
            let grupos = [];
            let grupoActual = null;

            data.forEach(item => {
                if (item.fabricado) {
                    // Iniciar nuevo grupo
                    grupoActual = {
                        nombre: item.descripcion,
                        cantidad: parseFloat(item.tras ? item.salidas : item.retorno) || 0, // Mostrar retorno como cantidad fabricada
                        unidad: item.unidad,
                        materiales: []
                    };
                    grupos.push(grupoActual);
                } else if (grupoActual) {
                    // Añadir como material del último grupo
                    grupoActual.materiales.push({
                        descripcion: item.descripcion,
                        unidad: item.unidad,
                        salida: formatNumber(item.salidas),
                        entrada: item.retorno != null ? formatNumber(item.retorno) : '-',
                        usado: item.retorno == null ? formatNumber(item.salidas) : formatNumber(item.utilizado)
                    });
                }
            });

            // Construir el HTML del acordeón
            grupos.forEach((grupo, index) => {
                const collapseId = `collapseGrupo_${index}`;
                const headingId = `headingGrupo_${index}`;

                const filas = grupo.materiales.length > 0 ?
                    grupo.materiales.map((mat, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${mat.descripcion}</td>
            <td>${mat.unidad}</td>
            <td>${mat.salida}</td>
            <td>${mat.entrada}</td>
            <td>${mat.usado}</td>
        </tr>`).join("") :
                    `<tr><td colspan="6" class="text-center text-muted">No se encontraron materiales utilizados</td></tr>`;
                contenedor.innerHTML += `
            <div class="card">
                <div class="card-header ${index !== 0 ? 'collapsed' : ''}" style='cursor:pointer;padding-block:.5rem'
                    id="${headingId}" data-toggle="collapse" data-target="#${collapseId}"
                    aria-expanded="${index === 0}" aria-controls="${collapseId}">
                    <h5 class="mb-0 w-100">
                        <div class="p-0 col-12 d-flex flex-column flex-md-row justify-content-between">
                            <div class="mb-2 mb-md-0 mr-3 text-nowrap align-items-center">
                                <label class="font-weight-bold mb-0">Cantidad:</label>
                                <span>${parseFloat(grupo.cantidad).toString()} ${grupo.unidad}</span>
                            </div>
                            <div>
                                <span class='font-weight-bold'>${grupo.nombre}</span>
                            </div>
                        </div>
                    </h5>
                </div>
                <div id="${collapseId}" class="collapse ${index == 0 ? 'show' : ''}" aria-labelledby="${headingId}">
                    <div class="card-body p-1">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-gray-dark">
                                <tr>
                                    <th>Nº</th>
                                    <th>Descripción</th>
                                    <th>Unidad</th>
                                    <th>Salida</th>
                                    <th>Entrada</th>
                                    <th>Usado</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${filas}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>`;
            });

            const modalBody = document.querySelector('#modalMateriales .modal-body');
            modalBody.classList.remove('animate-resize');
            void modalBody.offsetWidth; // Forzar reflow
            modalBody.classList.add('animate-resize');
        }

        function enviarFormularioPDF(action, boleta) {
            // Crear el formulario dinámicamente
            var form = document.createElement('form');
            form.action = action;
            form.method = 'POST';
            form.autocomplete = 'off';
            form.target = '_blank';
            // Crear el input oculto para la boleta
            var inputBoleta = document.createElement('input');
            inputBoleta.type = 'hidden';
            inputBoleta.name = 'id_boleta';
            inputBoleta.value = boleta;
            // Añadir el input al formulario
            form.appendChild(inputBoleta);
            // Añadir el formulario al DOM (se añade al body)
            document.body.appendChild(form);
            // Enviar el formulario
            form.submit();
            // Eliminar el formulario del DOM después de enviarlo
            document.body.removeChild(form);
        }

        if (btnNuevo) {
            btnNuevo.addEventListener('click', () => {
                accion = 1;
                const salida = document.getElementById('radio-2');
                first_control.click();
                salida.click();
                dropzone.removeAllFilesWithoutServer();
                dropzone.enable();
                drop_element.classList.remove("dropzone-disabled");
            });
        }

        function obtenerDatosProdFab(id_boleta, tras) {
            // Aquí deberías hacer una llamada AJAX para obtener los datos relacionados con id_boleta
            $.ajax({
                url: 'controllers/salidas.controlador.php', // Cambiar por la URL de tu backend
                type: 'POST',
                data: {
                    boleta: id_boleta,
                    accion: 10
                },
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        tblDetalleFab.clear().draw();
                        response.forEach(item => {
                            let cantidad = tras ? item.salidas : item.retorno;
                            let nuevaFila = [
                                item.id_fab, // ID único de la fila
                                cantidad, // Cantidad
                                item.id_unidad, // Unidad
                                item.descripcion, // Descripción
                                ''
                            ];
                            tblDetalleFab.row.add(nuevaFila).draw(false);
                            console.log('Fila añadida:', item.retorno);
                        });
                        console.log('Datos obtenidos:', response);
                    } else {
                        console.error('No se encontraron datos para el id_boleta:', id_boleta);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los datos:', error);
                }
            });
        }

        $('#tblSalidas').on('click', '#editS', function() {
            let row = tabla.row($(this).closest('tr').next()).data()
            id_boleta = row[10];
            id_boleta_fab = row[10];
            const id_orden = row[11],
                id_cliente = row[12],
                fecha_id = row[13],
                conductor = row[14],
                despachado_id = row[15],
                entrega = row[16],
                fab = row[21],
                tras = row[22],
                orden = row[7],
                cliente = row[8],
                fecha_return = row[23],
                guia = row[17];
            const motivo_text = row[18] === '' ? 'TRANSLADO DE HERRAMIENTAS' : row[18];
            const isfab = fab ? '7' : '2';
            const isfabValue = fab ? '8' : '4';
            const radio = document.getElementById('radio-' + isfab);
            const cancelar = document.getElementById('Cancelar');
            let selectedItem = items_orden.find(item => item.cod === id_orden);
            if (fab) {
                if (selectedItem) {
                    // Asignamos el valor al input de autocompletado
                    $(nro_ordenFab).val(selectedItem.label);
                    // Simulamos la selección del ítem en el autocompletado
                    $(nro_ordenFab)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: selectedItem
                        });
                } else {
                    // Crear un nuevo item con los datos disponibles
                    let nuevoItem = {
                        cod: id_orden,
                        label: `${orden}  ${cliente}`,
                        value: id_orden // Esto depende de cómo lo uses en el autocomplete
                    };
                    $(nro_ordenFab).val(nuevoItem.label);
                    // Simular la selección del nuevo item en el autocompletado
                    $(nro_ordenFab)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: nuevoItem
                        });
                }
                nro_guiaFab.value = guia;
                isTrasFab.disabled = tras;
                isTrasFab.checked = tras;
                isTrasFab.dispatchEvent(new Event('click'));
                fecha_retorno.value = fecha_return == '' ? '' : fecha_return;
                obtenerDatosProdFab(id_boleta, tras);
            } else {
                if (selectedItem) {
                    // Asignamos el valor al input de autocompletado
                    $(nro_orden).val(selectedItem.label);
                    // Simulamos la selección del ítem en el autocompletado
                    $(nro_orden)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: selectedItem
                        });
                } else {
                    // Crear un nuevo item con los datos disponibles
                    let nuevoItem = {
                        cod: id_orden,
                        label: `${orden}  ${cliente}`,
                        value: id_orden // Esto depende de cómo lo uses en el autocomplete
                    };
                    // Asignar el valor al input
                    $(nro_orden).val(nuevoItem.label);
                    $(nro_orden)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: nuevoItem
                        });
                }
                setChange(cboConductor, conductor)
                nro_guia.value = guia;
                tblDetalleSalida.ajax.reload(null, false);
            }
            setChange(cboResponsable, entrega)
            fecha.value = fecha_id;
            motivo.value = motivo_text;
            radio.value = isfabValue;
            radio.checked = true;
            radio.dispatchEvent(new Event('change'));
            cancelar.style.display = 'block'
            first_control.click();

            let src = new FormData();
            src.append('accion', 8);
            src.append('boleta', id_boleta);
            cargarFilesDropzone(src, dropzone, 'salidas', 'guia_img');
            dropzone.enable();
            if (eliminar) {
                drop_element.classList.remove("dropzone-disabled");
            } else {
                drop_element.classList.add("dropzone-disabled");
            }
        });

        

        $('#tblSalidas').on('click', '#editR', function() {
            row = tabla.row($(this).closest('tr').next()).data()
            id_boleta = row[10];
            id_boleta_fab = id_boleta;
            const id_orden = row[11],
                id_cliente = row[12],
                fecha_id = row[13],
                conductor = row[14],
                despachado_id = row[15],
                entrega = row[16],
                guia = row[17],
                fab = row[21],
                tras = row[22],
                fecha_return = row[23],
                orden = row[7],
                cliente = row[8],
                isfab = fab ? '7' : '3',
                isfabValue = fab ? '9' : '6';
            const radio = document.getElementById('radio-' + isfab);
            const motivo_text = row[18] === '' ? 'TRANSLADO DE HERRAMIENTAS' : row[18];
            let selectedItem = items_orden.find(item => item.cod === id_orden);
            if (fab) {
                if (selectedItem) {
                    // Asignamos el valor al input de autocompletado
                    $(nro_ordenFab).val(selectedItem.label);
                    // Simulamos la selección del ítem en el autocompletado
                    $(nro_ordenFab)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: selectedItem
                        });
                } else {
                    // Crear un nuevo item con los datos disponibles
                    let nuevoItem = {
                        cod: id_orden,
                        label: `${orden}  ${cliente}`,
                        value: id_orden // Esto depende de cómo lo uses en el autocomplete
                    };
                    $(nro_ordenFab).val(nuevoItem.label);
                    // Simular la selección del nuevo item en el autocompletado
                    $(nro_ordenFab)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: nuevoItem
                        });
                }
                nro_guiaFab.value = guia;
                tblDetalleFabEntrada.ajax.reload(null, false);
                isTrasFab.checked = tras;
                isTrasFab.dispatchEvent(new Event('click'));
                isTrasFab.disabled = true;
            } else {
                if (selectedItem) {
                    // Asignamos el valor al input de autocompletado
                    $(nro_ordenEntrada).val(selectedItem.label);
                    // Simulamos la selección del ítem en el autocompletado
                    $(nro_ordenEntrada)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: selectedItem
                        });
                } else {
                    // Crear un nuevo item con los datos disponibles
                    let nuevoItem = {
                        cod: id_orden,
                        label: `${orden}  ${cliente}`,
                        value: id_orden // Esto depende de cómo lo uses en el autocomplete
                    };
                    $(nro_ordenEntrada).val(nuevoItem.label);
                    // Simular la selección del nuevo item en el autocompletado
                    $(nro_ordenEntrada)
                        .autocomplete("instance")
                        ._trigger("select", null, {
                            item: nuevoItem
                        });
                }
                setChange(cboConductor, conductor)
                nro_guiaEntrada.value = guia;
                tblReturn.ajax.reload(null, false);
                fecha_retorno.value = fecha_return == '' ? '' : fecha_return;
                let src = new FormData();
                src.append('accion', 8);
                src.append('boleta', id_boleta);
                cargarFilesDropzone(src, dropzone, 'salidas', 'guia_img');
                dropzone.disable();
                document.querySelector(".dropzone").classList.add("dropzone-disabled");
            }
            setChange(cboDespachado, despachado_id)
            setChange(cboResponsable, entrega)
            fecha.value = fecha_id
            motivo.value = motivo_text;
            radio.value = isfabValue;
            radio.checked = true;
            radio.dispatchEvent(new Event('change'));
            first_control.click();
        });

        $('#tblSalidas').on('click', '#eliS', function() {
            const boleta = tabla.row($(this).closest('tr').next()).data()[10];
            let src = new FormData();
            src.append('accion', 3);
            src.append('id', boleta);
            confirmarEliminar('la', 'salida', function(r) {
                if (r) {
                    confirmarAccion(src, 'salidas', tabla);
                    cargarAutocompletado();
                }
            })
        });
    })
</script>