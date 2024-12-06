<head>
    <title>Inicio</title>
</head>
<script>
    window.scrollTo(0, 0);
</script>
<!-- Contenido Header -->
<section style="top:56px" class="content-header stick-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <h1 class="col-p">Inicio</h1>
            </div>
            <div class="col-auto">
                <select id="cboAnio" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                </select>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- row Tarjetas Informativas -->
        <div class="row">
            <div class="col-lg">
                <!-- small box -->
                <div class="small-box bg-1">
                    <div class="inner">
                        <p>Productos</p>
                        <h3 id="pro">0</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shelves"></i>
                    </div>
                    <a onclick="masInfo('Inventario')" style="cursor:pointer;" class="small-box-footer">Más Info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- TARJETA TOTAL PENDIENTES -->
            <div class="col-lg">
                <!-- small box -->
                <div class="small-box bg-2">
                    <div class="inner">
                        <p>Movimientos</p>
                        <h3 id="mov">0</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cart-minus"></i>
                    </div>
                    <a onclick="masInfo('Movimientos')" style="cursor:pointer;" class="small-box-footer">Más Info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- TARJETA ENTRADAS-->
            <div class="col-lg">
                <!-- small box -->
                <div class="small-box bg-3">
                    <div class="inner">
                        <p>Compras</p>
                        <h3 id="com">0</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cart-plus"></i>
                    </div>
                    <a onclick="masInfo('Listado de compras')" style="cursor:pointer;" class="small-box-footer">Más Info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg">
                <!-- small box -->
                <div class="small-box bg-4">
                    <div class="inner">
                        <p>En obra</p>
                        <h3 id="ope">0</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-person-digging"></i>
                    </div>
                    <a onclick="masInfo('Ordenes de trabajo'); estado_filter= '1'" style="cursor:pointer;" class="small-box-footer">Más Info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div> <!-- ./row Tarjetas Informativas -->
        <!-- /.row -->
        <div class="card">
            <div class="card-header" style="display:block;padding-block:.5rem">
                <div class="row">
                    <div class="col-auto" style="padding-block:.2rem">
                        <h3 class="card-title text-wrap"><i style="font-size:1.3rem" class="fas fa-file-lines"> </i> Guías de remision registradas</h3>
                    </div>
                    <div class="col">
                        <select name="cboMeses" id="cboMeses" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart" style="min-height: 250px; height: 300px; max-height: 350px; width: 100%;"></canvas>
                </div>
            </div>
        </div>



        <div class="row" style="align-items:flex-start">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="display:block;padding-block:.5rem">
                        <div class="row">
                            <div class="col-auto" style="padding-block:.2rem">
                                <h3 class="card-title text-wrap"><i style="font-size:1.25rem" class="fas fa-ranking-star"></i> Los 10 productos mas usados</h3>
                            </div>
                            <div class="col">
                                <select id="cboMeses2" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table" id="tblTop">
                                <thead>
                                    <tr>
                                        <th class="text-center">CÓDIGO</th>
                                        <th>DESCRIPCION</th>
                                        <th style="font-size:18px!important" class="text-center">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- ./ end card-body -->
                </div>
            </div>

            <div class="col-md-6">
                <!-- DONUT CHART -->
                <div class="card ">
                    <div class="card-header" style="display:block;padding-block:.5rem">
                        <div class="row">
                            <div class="col-auto" style="padding-block:.2rem">
                                <h3 class="card-title text-wrap"><i style="font-size: 1.25rem;" class="fas fa-screwdriver-wrench"></i> Equipos en obra</h3>
                            </div>
                            <div class="col">
                                <select id="cboCategoria" class="form-control select2 select2-dark" data-dropdown-css-class="select2-dark">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="donutChart" style="min-height: 366px; height: 366px; max-height: 366px; max-width: 100%;"></canvas>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

        </div>
        <div class="card">
            <div class="card-header" style="display:block;padding-block:.8rem">
                <div class="row">
                    <div class="col-auto" style="padding-block:.2rem">
                        <h3 class="card-title text-wrap"><i style="font-size:1.25rem" class="fas fa-arrow-down-square-triangle"></i> Productos con poca existencias </h3>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="tblPoco">
                        <thead>
                            <tr>
                                <th class="text-center">CÓDIGO</th>
                                <th>DESCRIPCION</th>
                                <th class="text-center">CANT. MIN.</th>
                                <th class="text-center">CANT. ACTUAL</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div> <!-- ./ end card-body -->
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.Contenido -->

<script>
    $(document).ready(function() {
        const cboAnio = document.getElementById('cboAnio'),
            cboMeses = document.getElementById('cboMeses'),
            cboMeses2 = document.getElementById('cboMeses2'),
            cboCategoria = document.getElementById('cboCategoria');

        const tblTop = document.getElementById('tblTop'),
            tblPoco = document.getElementById('tblPoco'),
            tblMov = document.getElementById('tblMov');

        const colors = ['#003049', '#0a9396', '#94d2bd', '#ffb703', '#f77100', '#BE0E25', '#D46C8B', '#E6E1DC',
            '#A8A3BD', '#335490', '#463F64', '#64A8D1', '#7ED321', '#F7CAC9', '#9B1B30', '#A7C5EB',
            '#F6AE2D', '#D9E1E2', '#4CB5F5', '#FFC0CB', '#EEDC82', '#7FFF00', '#8A2BE2', '#F08080',
            '#00CED1', '#FFA07A', '#6495ED', '#8B008B', '#20B2AA', '#FFD700', '#DC143C', '#00FF00',
            '#BA55D3', '#FA8072', '#6A5ACD'
        ]

        const coloresConTransparencia = [
            "#0030494D",
            "#0a93964D",
            "#94d2bd4D",
            "#ffb7034D",
            "#f771004D",
            "#BE0E254D",
            "#D46C8B4D",
            "#E6E1DC4D",
            "#A8A3BD4D",
            "#3354904D",
            "#463F644D",
            "#64A8D14D",
            "#7ED3214D",
            "#F7CAC94D",
            "#9B1B304D",
            "#A7C5EB4D",
            "#F6AE2D4D",
            "#D9E1E24D",
            "#4CB5F54D",
            "#FFC0CB4D",
            "#EEDC824D",
            "#7FFF004D",
            "#8A2BE24D",
            "#F080804D",
            "#00CED14D",
            "#FFA07A4D",
            "#6495ED4D",
            "#8B008B4D",
            "#20B2AA4D",
            "#FFD7004D",
            "#DC143C4D",
            "#00FF004D",
            "#BA55D34D",
            "#FA80724D",
            "#6A5ACD4D"
        ];
        
        let cat = 2;
        let barChartData, barChartCanvas, barChartOptions, chartCanvas;
        let donutData, donutChartCanvas, donutOptions, donutCanvas;
        let anio = year;
        let mes = month;
        let mesGrafico = mes;
        let todo_mes = [{
            id: 0,
            text: 'TODO'
        },
        ...datos_meses
    ]

        cargarCombo('Categoria', cat);

        $(cboAnio).select2({
            width: '120%',
            data: datos_anio,
            minimumResultsForSearch: -1,
        })
        setChange(cboAnio, anio);

        $(cboMeses).select2({
            minimumResultsForSearch: -1,
            width: '100%',
            data: todo_mes,

        });
        setChange(cboMeses, mes);

        $(cboMeses2).select2({
            minimumResultsForSearch: -1,
            width: '100%',
            data: todo_mes,

        });
        setChange(cboMeses2, mes);

        $(cboCategoria).select2({
            minimumResultsForSearch: -1,
            width: '100%',
        });

        Chart.register(ChartDataLabels);
        Chart.defaults.font.family = "Poppins-Regular";

        // setChange(cboCategoria, cat);
        /* =======================================================
        SOLICITUD AJAX TARJETAS INFORMATIVAS
        =======================================================*/
        tarjetasInfo(anio);
        /* ================================
        SOLICITUD AJAX GRAFICO DE BARRAS MES
        ===================================*/
        $.ajax({
            url: "controllers/inicio.controlador.php",
            method: 'POST',
            data: {
                'accion': 1,
                'mes': mes,
                'anio': anio
            },
            dataType: 'json',
            success: function(respuesta) {
                var clientes = [];
                var salidas = [];

                respuesta.forEach(i => {
                    clientes.push(i['cliente']);
                    salidas.push(i['salidas']);
                });

                // graficoTheme();
                barChartCanvas = $("#barChart").get(0).getContext('2d');
                barChartData = {
                    labels: clientes,
                    datasets: [{
                        label: 's',
                        backgroundColor: coloresConTransparencia,
                        borderColor: colors, // Color del borde
                        borderWidth: 1, // Ancho del borde
                        data: salidas,
                    }]
                }
                let ms = 6
                let maximo = Math.max(...salidas) + 1;
                console.log(maximo)
                barChartOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        datalabels: {
                            align: 'end',
                            anchor: 'end',
                            font: {
                                weight: 'bold'
                            }
                        },
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: 15,
                                    weight: 'bold' // peso de la fuente
                                }
                            }
                        },
                        y: {
                            suggestedMax: Math.max(...salidas) + 1,
                            ticks: {
                                precision: 0,
                                min: 0,
                                padding: 10,
                                font: {
                                    size: 15,
                                    weight: 'bold' // peso de la fuente
                                }
                            },
                        }
                    },
                    animation: {
                        duration: 800,
                        easing: "easeOutQuart",
                    }
                }
                chartCanvas = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: barChartData,
                    options: barChartOptions
                })
            }
        });
        /* ================================
        TABLA DE 10 PRODUCTOS TOP
        ===================================*/
        const columnas = [{
                targets: 0,
                className: "text-center",
            },
            {
                targets: 2,
                className: "text-center display-1",
            }
        ];

        let tablaTop = iniciarTabla(tblTop, 2, columnas);

        /* ================================
        TABLA DE POCO STOCK TOP
        ===================================*/
        const columnas2 = [{
                targets: 0,
                className: "text-center",
            },
            {
                targets: 2,
                className: "text-center",
            },
            {
                targets: 3,
                className: "text-center",
            }
        ];
        let tablaPoco = iniciarTabla(tblPoco, 3, columnas2);
        /* ================================
        - DONUT CHART -
        ===================================*/
        $.ajax({
            url: "controllers/inicio.controlador.php",
            method: 'POST',
            data: {
                'accion': 4,
                'categoria': cat,
                'anio': anio
            },
            dataType: 'json',
            success: function(respuesta) {
                var clientes = [];
                var cantidad = [];
                respuesta.forEach(i => {
                    clientes.push(i['cliente']);
                    cantidad.push(i['salidas']);
                });

                donutChartCanvas = $('#donutChart').get(0).getContext('2d')
                donutData = {
                    labels: clientes,
                    datasets: [{
                        label: '',
                        data: cantidad,
                        backgroundColor: colors

                    }]
                }

                donutOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                    layout: {
                        padding: {
                            top: 12
                        }
                    },
                    elements: {
                        arc: {
                            borderWidth: 0 // Establece el ancho del borde del arco en 0 para eliminar las líneas blancas
                        }
                    },
                    radius: '86%',
                    animation: {
                        animateRotate: true, // Animación de rotación
                        animateScale: false // Animación de escala
                    },
                    tooltips: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        datalabels: {
                            align: 'end',
                            anchor: 'end',
                            borderWidth: 0,
                            display: function(context) {
                                var dataset = context.dataset;
                                var count = dataset.data.length;
                                var value = dataset.data[context.dataIndex];
                                return value; // > count * 1.5;
                            },
                            font: {
                                weight: 'bold',
                                size: '16'
                            },
                            formatter: function(value, context) {
                                var dataset = context.dataset;
                                var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[context.dataIndex];
                                return Math.round(value);
                            }
                        }
                    }
                };

                donutCanvas = new Chart(donutChartCanvas, {
                    type: 'doughnut',
                    data: donutData,
                    options: donutOptions
                })
            }
        });

        // const modeS = document.querySelector('.mode');
        // modeS.addEventListener('click', () => {
        //     const x = chartCanvas.config.options.scales.x;
        //     const y = chartCanvas.config.options.scales.y;
        //     if (document.body.classList.contains('dark-mode')) {
        //         Chart.defaults.color = '#fff';
        //         x.ticks.color = '#fff';
        //         y.ticks.color = '#fff';
        //         x.grid.color = 'rgba(255, 255, 255, 0.2)';
        //         y.grid.color = 'rgba(255, 255, 255, 0.2)';
        //     } else {
        //         console.log("rntro en else")
        //         Chart.defaults.color = '#333';
        //         x.ticks.color = '#333';
        //         y.ticks.color = '#333';
        //         x.grid.color = 'rgba(0, 0, 0, 0.15)';
        //         y.grid.color = 'rgba(0, 0, 0, 0.15)';
        //     }
        //     chartCanvas.update();
        //     donutCanvas.update();
        // });

        $(cboAnio).on("change", function() {
            let a = this.options[this.selectedIndex].text
            if(a == anio){
                return;
            }
            anio = a
            mes = cboMeses.value;
            // console.log(mes)
            // console.log(anio)
            tarjetasInfo(anio);
            let src = new FormData();
            src.append('accion', 1);
            src.append('mes', mes);
            src.append('anio', anio);
            actualizarGrafico(src, chartCanvas, barChartData, true);
            mes = cboMeses2.value;
            tablaTop.ajax.reload();
        });

        $(cboMeses).on("change", function() {
            let m = this.value
            if(m == mes){
                return;
            }
            // console.log(mes);
            mes = m;
            anio = cboAnio.options[cboAnio.selectedIndex].text;
            let src = new FormData();
            src.append('accion', 1);
            src.append('mes', mes);
            src.append('anio', anio);
            actualizarGrafico(src, chartCanvas, barChartData, true);
        });

        $(cboMeses2).on("change", function() {
            mes = this.value;
            anio = cboAnio.options[cboAnio.selectedIndex].text;
            tablaTop.ajax.reload();
            // console.log("si me ejecuto sapo")
        });

        $(cboCategoria).on("change", function() {
            let c = this.value;
            if(c == cat){
                return;
            }
            cat = c
            let src = new FormData();
            src.append('accion', 4);
            src.append('categoria', cat);
            actualizarGrafico(src, donutCanvas, donutData);
        });

        function actualizarGrafico(src, chart, chartData, max) {
            $.ajax({
                url: "controllers/inicio.controlador.php",
                method: 'POST',
                data: src,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(respuesta) {
                    var clientes = [];
                    var salidas = [];

                    respuesta.forEach(i => {
                        clientes.push(i['cliente']);
                        salidas.push(i['salidas']);
                    });

                    //Actualiza las etiquetas
                    chartData.labels = clientes;
                    chartData.datasets[0].data = salidas;
                    if(max){
                        chart.config.options.scales.y.suggestedMax = Math.max(...salidas) + 1;
                    }
                    chart.update();
                }
            })
        }

        function tarjetasInfo(anio) {
            $.ajax({
                url: "controllers/inicio.controlador.php",
                method: 'POST',
                data: {
                    'anio': anio
                },
                dataType: 'json',
                success: function(respuesta) {
                    $("#pro").html(respuesta[0]['pro']);
                    $("#mov").html(respuesta[0]['mov']);
                    $("#com").html(respuesta[0]['com']);
                    $("#ope").html(respuesta[0]['ope']);
                }
            });
        }

        function iniciarTabla(tabla, accion, colums) {
            var dataTable = $(tabla).DataTable({
                ajax: {
                    url: "controllers/inicio.controlador.php",
                    dataSrc: "",
                    type: "POST",
                    data: function(d) {
                        d.accion = accion;
                        d.anio = anio;
                        d.mes = mes;
                    }
                },
                ordering: false,
                lengthChange: false,
                autoWidth: false,
                dom: "t",
                columnDefs: colums
            });
            return dataTable;
        }

        // function graficoTheme() {
        //     if (document.body.classList.contains('dark-mode')) {
        //         Chart.defaults.color = '#fff';
        //         Chart.defaults.scale.grid.color = 'rgba(255, 255, 255, 0.18)'; // Cambia el color por defecto de las líneas del grid
        //     }
        // }
    })
</script>