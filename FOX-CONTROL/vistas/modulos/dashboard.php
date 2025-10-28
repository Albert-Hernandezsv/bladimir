<?php

if ($_SESSION["backup_completado"] === "no") {
    // Obtener el último archivo de respaldo disponible
    $directorioRespaldo = 'vistas/respaldo-fox/';
    $archivos = glob($directorioRespaldo . '*.sql'); // Todos los archivos .sql en el directorio

    if (count($archivos) > 0) {
        // Ordenar los archivos por fecha de modificación (más reciente primero)
        usort($archivos, function($a, $b) {
            return filemtime($b) - filemtime($a); // Orden descendente
        });

        // Tomar el archivo más reciente
        $ultimoRespaldo = $archivos[0];
        $nombreArchivo = basename($ultimoRespaldo); // Obtener solo el nombre del archivo

        // Mostrar SweetAlert y permitir descarga del archivo más reciente
        echo '<script>
            swal({
                title: "¡Backup Disponible!",
                text: "Haz clic en el siguiente enlace para descargar el respaldo más reciente de la base de datos.",
                icon: "success",
                buttons: {
                    confirm: "Descargar",
                },
            }).then(() => {
                // Abrir la ventana de descarga manual
                var a = document.createElement("a");
                a.href = "' . $directorioRespaldo . $nombreArchivo . '";
                a.download = "' . $nombreArchivo . '";
                a.click();

                // Recargar la página después de la descarga
                location.reload();
            });
        </script>';
        $_SESSION["backup_completado"] = "si";
    } else {
        echo "No hay respaldos disponibles para descargar.";
    }
} else {
    
}














    if(isset($_SESSION)){
    } else {
        echo '<script>
        window.location = "inicio";
        </script>';
    return;
    }

   
   // Consulta para obtener las facturas del mes actual
   date_default_timezone_set('America/El_Salvador');
   $fechaActual = date('Y-m-d'); // Obtiene la fecha actual en formato YYYY-MM-DD

    $anioActual = date('Y');
    $mesActual = date('m');

    $item = "fecEmi";
    $orden = "fecEmi";

    // Modificar la consulta para obtener facturas del mes actual
    $facturas = ModeloFacturas::mdlMostrarFacturasDashPorMes($anioActual, $mesActual, $orden);
    

    // Filtra las facturas para la fecha actual y calcula el total facturado
    $totalFacturadoHoy = array_reduce($facturas, function($carry, $factura) use ($fechaActual) {
        if ($factura['fecEmi'] === $fechaActual && $factura["sello"] != "" && $factura["estado"] == "Activa") {
            $carry += $factura['total'];
        }
        return $carry;
    }, 0);

    // Prepara los datos para el gráfico
    $labels = json_encode([$fechaActual]);
    $data = json_encode([$totalFacturadoHoy]);




    // Inicializa un array para almacenar la facturación por día
    $facturacionPorDia = [];

    // Recorre las facturas y agrupa la facturación por día
    foreach ($facturas as $factura) {
        if($factura["sello"] != "" && $factura["estado"] == "Activa"){
            $fechaFactura = $factura['fecEmi']; // Fecha de la factura
            $total = $factura['total']; // Total de la factura

            if (!isset($facturacionPorDia[$fechaFactura])) {
                $facturacionPorDia[$fechaFactura] = 0;
            }
            $facturacionPorDia[$fechaFactura] += $total;
        }
    }

    // Ordenar los datos por fecha (opcional)
    ksort($facturacionPorDia);

    // Preparar los datos para el gráfico
    $labelsMes = json_encode(array_keys($facturacionPorDia)); // Fechas
    $dataMes = json_encode(array_values($facturacionPorDia)); // Totales por día




    // Array para almacenar las cantidades vendidas por producto
    $productosVendidos = [];

    // Recorre cada factura para sumar las cantidades de productos
    foreach ($facturas as $factura) {
        if($factura["sello"] != "" && $factura["estado"] == "Activa"){
            $productos = json_decode($factura['productos'], true); // Decodifica el JSON de productos
            $fechaFactura = $factura['fecEmi'];
            foreach ($productos as $producto) {

                $fecha = DateTime::createFromFormat('Y-m-d', $fechaFactura);
                if ($fecha->format('Y') == $anioActual && $fecha->format('m') == $mesActual) {
                    $item = "id";
                    $valor = $producto["idProducto"];
                
                    $productoLei = ControladorProductos::ctrMostrarProductos($item, $valor);
                    if($productoLei){
                        $idProducto = $productoLei['nombre'];
                        $cantidad = $producto['cantidad'];
        
                        // Suma la cantidad del producto vendido
                        if (!isset($productosVendidos[$idProducto])) {
                            $productosVendidos[$idProducto] = 0;
                        }
                        $productosVendidos[$idProducto] += $cantidad;
                    }
                    
                }

                
            }
        }
        
    }

    // Ordena los productos por cantidad vendida en orden descendente
    arsort($productosVendidos);

    // Selecciona los 20 productos más vendidos
    $productosTop20 = array_slice($productosVendidos, 0, 10, true);

    // Prepara los datos para el gráfico
    $labelsP = json_encode(array_keys($productosTop20)); // IDs de los productos
    $dataP = json_encode(array_values($productosTop20)); // Cantidades vendidas



    $comprasPorCliente = [];

    // Suma las compras por cliente
    foreach ($facturas as $factura) {
        if($factura["sello"] != "" && $factura["estado"] == "Activa"){
            $fechaFactura = $factura['fecEmi'];
            foreach ($productos as $producto) {
    
                $fecha = DateTime::createFromFormat('Y-m-d', $fechaFactura);
                if ($fecha->format('Y') == $anioActual && $fecha->format('m') == $mesActual) {
                    $item = "id";
                    $orden = "id";
                    $valor = $factura["id_cliente"];
    
                    $cliente = ControladorClientes::ctrMostrarClientes($item, $valor, $orden);
    
                    $idCliente = $cliente['nombre'];
                    $totalCompra = $factura['total'];
    
                    if (!isset($comprasPorCliente[$idCliente])) {
                        $comprasPorCliente[$idCliente] = 0;
                    }
                    $comprasPorCliente[$idCliente] += $totalCompra;
                }
    
                
            }
        }
        
        
    }

    // Ordena los clientes por total comprado en orden descendente
    arsort($comprasPorCliente);

    // Selecciona los 10 clientes que más compran
    $clientesTop10 = array_slice($comprasPorCliente, 0, 10, true);

    // Prepara los datos para el gráfico
    $labelsClientes = json_encode(array_keys($clientesTop10)); // IDs de los clientes
    $dataClientes = json_encode(array_values($clientesTop10)); // Totales comprados





    $ventasPorVendedor = [];

    // Suma las compras por vendedor
    foreach ($facturas as $factura) {
        if($factura["sello"] != "" && $factura["estado"] == "Activa"){
            $fechaFactura = $factura['fecEmi'];
            foreach ($productos as $producto) {
                
                $fecha = DateTime::createFromFormat('Y-m-d', $fechaFactura);
                if ($fecha->format('Y') == $anioActual && $fecha->format('m') == $mesActual) {

                    $item = "id";
                    $valor = $factura["id_vendedor"];
                    
                    $vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
                    if($vendedor){
                        $idVendedor = $vendedor['nombre'];
                        $totalCompra = $factura['total'];

                        if (!isset($ventasPorVendedor[$idVendedor])) {
                            $ventasPorVendedor[$idVendedor] = 0;
                        }
                        $ventasPorVendedor[$idVendedor] += $totalCompra;
                    } else {
                        
                    }
                    
                }

                
            }
        }
        
    }

    // Ordena los vende$vendedors por total comprado en orden descendente
    arsort($ventasPorVendedor);

    // Selecciona los 10 clientes que más compran
    $vendedoresTop10 = array_slice($ventasPorVendedor, 0, 10, true);

    // Prepara los datos para el gráfico
    $labelsVendedores = json_encode(array_keys($vendedoresTop10)); // IDs de los clientes
    $dataVendedores = json_encode(array_values($vendedoresTop10)); // Totales comprados


    $meses = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    
    $mesActual = $meses[date("n") - 1]; 

?>

<div class="main-content content-wrapper" style="background-color: #202020 !important; color: white !important">

  <section class="content-header">
    
    <h1 style="color: white !important">
        Dashboard
    </h1>

    <ol class="breadcrumb" style="background-color: #202020 !important; color: white !important">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio </a></li>
      
      <li class="active">&nbsp;Sistema de gestión empresarial</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

      </div>

      <div class="box-body">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <style>
                .dashboard-section {
                    margin: 20px;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                }

                .chart-container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                }

                h2 {
                    text-align: center;
                }
            </style>

            <div class="row">

                <?php
                    // Obtener el día actual
                    $dia = date("j"); // "j" devuelve el día del mes sin ceros iniciales

                    // Verificar si estamos entre el 1 y el 15 del mes
                    if ($dia >= 1 && $dia <= 15) {
                        echo '<div class="col-xl-3 col-sx-12">
                                    <div class="card" style="background-color:green">
                                            <div class="card-header">
                                                <b>¡Declaraciones de Hacienda!</b>
                                            </div>
                                            <div class="card-body">
                                                Estamos en fechas de declaraciones de IVA y Pago a Cuentas, asegurate de realizarlo oportunamente
                                            </div>
                                        </div>
                                        <br>
                                </div>';
                    } else {
                        
                    }
                    $item = null;
                    $valor = null;

                    $productos = ControladorProductos::ctrMostrarProductos($item, $valor);

                    foreach($productos as $producto){
                        if($producto["stock"] <= 10){
                            echo '<div class="col-xl-3 col-sx-12">
                                <div class="card" style="background-color:red">
                                        <div class="card-header">
                                            <b>¡Inventario bajo a menos de 10 unidades!</b>
                                        </div>
                                        <div class="card-body">
                                            Solo '.$producto["stock"].' unidades de '.$producto["nombre"].'
                                        </div>
                                    </div>
                                    <br>
                            </div>';
                        }
                        
                    }

                    foreach($productos as $producto){

                        $fecha_objetivo = $producto["fecha_vencimiento"]; // Reemplaza con tu fecha
                        $fecha_actual = new DateTime(); // Fecha actual
                        $fecha_dada = new DateTime($fecha_objetivo);

                        // Calcular la diferencia de días
                        $diferencia = $fecha_actual->diff($fecha_dada)->days;
                        $es_menor_o_igual_a_10 = $fecha_actual <= $fecha_dada && $diferencia <= 10;

                        if ($es_menor_o_igual_a_10) {
                            echo '<div class="col-xl-3 col-sx-12">
                                <div class="card" style="background-color:#9bc029">
                                        <div class="card-header">
                                            <b>¡Producto cerca de su fecha de vencimiento!</b>
                                        </div>
                                        <div class="card-body">
                                            '.$producto["nombre"].' vence el '.$producto["fecha_vencimiento"].', faltan '.$diferencia.' días<br><br>
                                        </div>
                                    </div>
                                    <br>
                            </div>';
                        } else {
                            
                        }
                        
                    }

                ?>

            </div>
            <div class="row">

                <div class="col-xl-4 col-xs-12">
                    <!-- Sección de Clientes que más han comprado en el mes -->
                    <div class="dashboard-section">
                        <h2>Clientes que más han comprado en el mes</h2>
                        <p>Principales clientes según el monto total de compras realizadas durante el mes.</p>
                        <div class="chart-container">
                            <canvas id="clientesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xs-12">
                    <!-- Sección de Facturas hechas ahora -->
                    <div class="dashboard-section">
                        <h2>Total vendido hoy</h2>
                        <p>Total de facturas generadas en el día.</p>
                        <div class="chart-container">
                            <canvas id="facturasChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xs-12">
                    <!-- Sección de Vendedores con más ventas -->
                    <div class="dashboard-section">
                        <h2>Vendedores con más ventas</h2>
                        <p>Desempeño de los vendedores basado en las ventas del mes.</p>
                        <div class="chart-container">
                            <canvas id="vendedoresChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xs-12">
                    <!-- Sección de Productos más vendidos -->
                    <div class="dashboard-section">
                        <h2>Productos más vendidos</h2>
                        <p>Productos con mayor cantidad de ventas en el mes.</p>
                        <div class="chart-container">
                            <canvas id="productosChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xs-12">
                    <!-- Sección de Facturas hechas ahora -->
                    <div class="dashboard-section">
                        <h2>Total vendido en el mes de <?php echo $mesActual; ?></h2>
                        <p>Total de facturas generadas en el mes.</p>
                        <div class="chart-container">
                            <canvas id="facturasMesChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <script>

                // Gráfico de Clientes que más han comprado en el mes (Gráfico de Barras)
                const ctxClientes = document.getElementById('clientesChart').getContext('2d');
                new Chart(ctxClientes, {
                    type: 'bar',
                    data: {
                        labels: <?php echo $labelsClientes; ?>, // IDs de los clientes desde PHP
                        datasets: [{
                            label: 'Total Comprado',
                            data: <?php echo $dataClientes; ?>, // Total comprado por cliente desde PHP
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Top 10 Clientes que Más Compran' }
                        },
                        scales: {
                            x: { title: { display: true, text: 'ID del Cliente' } },
                            y: { title: { display: true, text: 'Total Comprado ($)' } }
                        }
                    }
                });

                const ctxFacturas = document.getElementById('facturasChart').getContext('2d');
                new Chart(ctxFacturas, {
                    type: 'bar',
                    data: {
                        labels: <?php echo $labels; ?>, // Fecha actual desde PHP
                        datasets: [{
                            label: 'Total facturado ahora',
                            data: <?php echo $data; ?>, // Cantidad de facturas de hoy desde PHP
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Total Facturado Hoy' }
                        },
                        scales: {
                            x: { title: { display: true, text: 'Fecha' } },
                            y: {
                                title: { display: true, text: 'Monto Facturado' },
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value; // Agrega el signo de $
                                    }
                                }
                            }
                        }
                    }
                });

                // Gráfico de Vendedores con más ventas (Gráfico de Barras Horizontal)
                const ctxVendedores = document.getElementById('vendedoresChart').getContext('2d');
                new Chart(ctxVendedores, {
                    type: 'bar',
                    data: {
                        labels: <?php echo $labelsVendedores; ?>, // Fecha actual desde PHP
                        datasets: [{
                            label: 'Ventas ($)',
                            data: <?php echo $dataVendedores; ?>, // Total comprado por cliente desde PHP
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        indexAxis: 'y', // Para convertirlo en gráfico de barras horizontal
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Vendedores con Mayor Cantidad de Ventas' }
                        }
                    }
                });

                // Gráfico de Productos más vendidos (Gráfico de Pastel)
                const ctxProductos = document.getElementById('productosChart').getContext('2d');
                new Chart(ctxProductos, {
                    type: 'pie',
                    data: {
                        labels: <?php echo $labelsP; ?>, // IDs de los productos desde PHP
                        datasets: [{
                            data: <?php echo $dataP; ?>, // Cantidades vendidas desde PHP
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Top 10 Productos Más Vendidos' }
                        }
                    }

                });

                const ctxFacturasMes = document.getElementById('facturasMesChart').getContext('2d');
                let mesActual = "<?php echo $mesActual; ?>";
                
                new Chart(ctxFacturasMes, {
                    type: 'bar',
                    data: {
                        labels: <?php echo $labelsMes; ?>, // Fecha actual desde PHP
                        datasets: [{
                            label: 'Total facturado',
                            data: <?php echo $dataMes; ?>, // Cantidad de facturas de hoy desde PHP
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Total Facturado en el mes' }
                        },
                        scales: {
                            x: { title: { display: true, text: 'Fecha' } },
                            y: {
                                title: { display: true, text: 'Monto Facturado' },
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value; // Agrega el signo de $
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
      </div>

    </div>

    <?php
        $folderPath = "vistas/respaldo-fox/";
        $files = array_diff(scandir($folderPath), array('.', '..'));

        foreach ($files as $file) {
            echo "<a href='$folderPath$file' download>
                    <button class='btn btn-primary'>Descargar $file</button>
                </a><br><br>";
        }
    ?>

  </section>

</div>