<?php require_once "../utils/database/config.php"; ?>

<head>
    <title>Movimientos</title>
    <!-- <link href="assets/plugins/datatables-scroller/css/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css"> -->
    <link href="assets/plugins/datatables-searchpanes/css/searchPanes.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-select/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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


<script src="assets/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>
<script src="assets/plugins/datatables-searchpanes/js/dataTables.searchPanes.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-searchpanes/js/searchPanes.bootstrap4.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-select/js/dataTables.select.min.js" type="text/javascript"></script>
<script src="assets/plugins/datatables-select/js/select.bootstrap4.min.js" type="text/javascript"></script>
<!-- <script src="assets/plugins/datatables-scroller/js/dataTables.scroller.min.js" type="text/javascript"></script> -->
<script>
    var mostrarCol = '<?php echo $_SESSION["editar4"] || $_SESSION["eliminar4"] ?>';
    var crear = '<?php echo $_SESSION["crear4"] ?>';
    var editar = '<?php echo $_SESSION["editar4"] ?>';
    var eliminar = '<?php echo $_SESSION["eliminar4"] ?>';
    var collapsedGroups = {};
    // var alturaDisponible = window.innerHeight - 300; // Ejemplo de cálculo
    // var scrollPosition = 0;

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
        rowGroup: {
            dataSrc: [4],
            startRender: function(rows, group) {
                var collapsed = !!collapsedGroups[group];

                rows.nodes().each(function(r) {
                    $(r).toggleClass('collapsedrow', !collapsed);
                });
                let fabricacion = rows.data().pluck('fab')[0];
                let traslado = rows.data().pluck('tras')[0];
                let textReturn = traslado && fabricacion || !fabricacion ?  '<button id="editR" class="btn btn-row pt-0 pb-0"><i class="fas fa-clipboard-list-check"></i></button>' : '';
                
                let texto = traslado ? 'Fabricado' : 'No trasladado';
                let fabricacionSpan = fabricacion ?
                    '<span class="badge bg-fab mr-2">'+texto+'</span>' :
                    '';
                var groupText = '<div class="d-flex justify-content-between align-items-center" style="cursor:pointer">' +
                    '<strong class="pl-2">' + fabricacionSpan + group + '  (' + rows.count() + ')</strong>' +
                    '<div class="txt-wrap-sm">' +
                    '<button class="btn btn-row pt-0 pb-0 btn_pdf"><i class="fas fa-file-pdf"></i></button>' +
                    '<button class="btn btn-row pt-0 pb-0 btn_pdf_img"><i class="fas fa-file-image"></i></button>' +
                    (editar ? '<button id="editS" class="btn btn-row pt-0 pb-0"><i class="fas fa-pen-to-square"></i></button>' : '') +
                    (crear ?  textReturn : '') +
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
            tabla.draw(false)
            $(window).scrollTop(scrollPosition);
        }
    });

    $(document).ready(function() {
        let anio = year;
        let mes = month;

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
        let input_pdf = document.getElementById('input_boleta');

        $(cboAnio).select2({
            width: '110%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        })
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
            tabla.ajax.reload();
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
            tabla.ajax.reload();
        });


        // $('#tblSalidas').on('click', '.btn_pdf', function(event) {
        //     event.preventDefault(); // Evita la acción predeterminada
        //     // Obtener la fila correspondiente
        //     var rowData = tabla.row($(this).closest('tr').next()).data();
        //     var boleta = rowData[10]; // Asumiendo que el índice 10 es el de la boleta
        //     // console.log(boleta)
        //     // Crear el formulario dinámicamente
        //     var form = document.createElement('form');
        //     form.action = 'PDF/pdf_guia.php';
        //     form.method = 'POST';
        //     form.autocomplete = 'off';
        //     form.target = '_blank';
        //     // Crear el input oculto para la boleta
        //     var inputBoleta = document.createElement('input');
        //     inputBoleta.type = 'hidden';
        //     inputBoleta.name = 'id_boleta';
        //     inputBoleta.value = boleta;
        //     inputBoleta.classList.add('input_boleta');
        //     // Añadir el input al formulario
        //     form.appendChild(inputBoleta);

        //     // Añadir el formulario al DOM (se añade al body)
        //     document.body.appendChild(form);
        //     // Enviar el formulario
        //     form.submit();
        //     // Eliminar el formulario del DOM después de enviarlo
        //     document.body.removeChild(form);
        // });

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

        function obtenerDatosProdFab(id_boleta) {
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
                            let nuevaFila = [
                                item.id_fab, // ID único de la fila
                                item.retorno, // Cantidad
                                item.id_unidad, // Unidad
                                item.descripcion, // Descripción
                                ''];
                            tblDetalleFab.row.add(nuevaFila).draw(false);
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
                }
                nro_guiaFab.value = guia;
                isTrasFab.checked = row[22];
                isTrasFab.dispatchEvent(new Event('click'));

                console.log(row[22])
                obtenerDatosProdFab(id_boleta);
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
            cargarImagenesDropzone(id_boleta);
            dropzone.enable();
            if (eliminar) {
                drop_element.classList.remove("dropzone-disabled");
            } else {
                drop_element.classList.add("dropzone-disabled");
            }
        });

        function cargarImagenesDropzone(id_boleta) {
            $.ajax({
                url: 'controllers/salidas.controlador.php', // Ajusta esta URL a tu controlador PHP
                type: 'POST',
                "dataSrc": '',
                data: {
                    'boleta': id_boleta,
                    'accion': 8
                },
                success: function(response) {
                    dropzone.removeAllFilesWithoutServer();
                    // Limpia los archivos existentes
                    response = JSON.parse(response);
                    response.imagenes.forEach(imagen => {
                        const mockFile = {
                            name: imagen.nombre_imagen || "Imagen", // Puedes asignar un nombre genérico si no guardas el nombre original
                            size: 123456, // Valor genérico; Dropzone no valida este campo para imágenes precargadas
                            ruta: imagen.nombre_imagen, // Ruta de la imagen en el servidor
                            isExisting: true
                        };

                        // Añade la imagen simulando que ya está cargada
                        dropzone.emit('addedfile', mockFile);
                        dropzone.emit('thumbnail', mockFile, '/guia_img/' + imagen.nombre_imagen);
                        dropzone.emit('complete', mockFile);
                        dropzone.files.push(mockFile); // Añade el archivo a la lista interna de Dropzone
                    });
                },
            });
        }

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
                isfab = fab ? '7' : '3',
                isfabValue = fab ? '9' : '6';
            // retorno = document.getElementById('radio-3');
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
                }
                nro_guiaFab.value = guia;
                tblDetalleFabEntrada.ajax.reload(null, false);
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
                }
                setChange(cboConductor, conductor)
                nro_guiaEntrada.value = guia;
                fecha_retorno.value = fecha_hoy;
                tblReturn.ajax.reload(null, false);
                cargarImagenesDropzone(id_boleta)
                dropzone.disable();
                document.querySelector(".dropzone").classList.add("dropzone-disabled");

            }
            setChange(cboDespachado, despachado_id)
            setChange(cboResponsable, entrega)

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

        // $('#tblSalidas tbody').on('click', '.btnEliminar', function() {

        // });

        // document.addEventListener('keydown', function(e) {
        //     if (e.key === "Escape") {
        //         const activeModal = document.querySelector('.modal.show');
        //         if (activeModal) {
        //             $(activeModal).modal('hide');
        //         }
        //     }
        // });

        // $('#tblSalidas tbody').on('click', '.btnEditar', function() {
        //     let row = obtenerFila(this, tabla);
        //     accion = 2;
        //     const icon = document.querySelector('.modal-title i');
        //     cambiarModal(span, ' Editar Entrada', icon, 'fa-pen-to-square', elements, 'bg-gradient-green', 'bg-gradient-blue', modal, 'modal-change', 'modal-new')
        //     id.value = row["id_empleado"];
        //     nombre.value = row["nombres_empleado"];
        //     cedula.value = row["cedula"];
        //     conductor.checked = row["conductor"];
        // });

        // form.addEventListener("submit", function(e) {
        //     e.preventDefault();
        //     $('.ten').hide();
        //     const ced = cedula.value.trim(),
        //         nom = nombre.value.trim().toUpperCase(),
        //         con = conductor.checked;
        //     if (!this.checkValidity() || ced.length < 10) {
        //         this.classList.add('was-validated');
        //         ced.length > 0 && $('.ten').show();
        //         return;
        //     }
        //     console.log("Ejecutando el resto del código...");
        //     const id_e = id.value;
        //     let datos = new FormData();
        //     datos.append('id_empleado', id_e);
        //     datos.append('cedula', ced);
        //     datos.append('nombres_empleado', nom);
        //     datos.append('conductor', con);
        //     datos.append('accion', accion);
        //     confirmarAccion(datos, null, 'empleados', modal, tabla)
        // });
    })
</script>