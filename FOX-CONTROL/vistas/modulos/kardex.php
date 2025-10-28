<?php

if($_SESSION["rol"] == "Admin" || $_SESSION["rol"] == "Bodega"){
} else {
    echo '<script>
    window.location = "inicio";
    </script>';
  return;
}

?>
<div id="loader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(148, 148, 148, 0.37); display: flex; justify-content: center; align-items: center; z-index: 9999;">
    <h2>Cargando...</h2>
</div>
<script>
    $(window).on('load', function() {
        $('#loader').fadeOut();
    });
</script>
<div class="main-content content-wrapper">

  <section class="content-header">
    
    <h1>
      Sistema Kardex
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio </a></li>
      
      <li class="active">&nbsp;Administrar inventario</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">    
        <button class="btn btn-dark" onclick="location.href='index.php?ruta=inventario&filtroNombre=todos&filtroTipo=todos&filtroCategoria=todos&filtroCodigo=todos&filtroStock=todos'">Regresar</button>  
<br><br>
      </div>

      <div class="box-body">
        <?php
            // Verificar si los parámetros existen y asignarlos a variables
            $filtroProductos = isset($_GET['productosSeleccionados']) ? $_GET['productosSeleccionados'] : 'todos';
            $filtroFechaInicio = isset($_GET['fechaInicioKardex']) ? $_GET['fechaInicioKardex'] : 'todos';
            $filtroFechaFin = isset($_GET['fechaFinKardex']) ? $_GET['fechaFinKardex'] : 'todos';
            $productosArray = explode(',', $filtroProductos);

            echo '
                <h4>Filtros aplicados actualmente</h4>
                <div class="row">
                  <div class="col-xl-5 col-xs-12"><b>Productos seleccionados:</b> ';
                  if ($filtroProductos != "todos") {
                      $contador = 0;
                      $limite = 5;  // Límite de productos a mostrar

                      foreach ($productosArray as $productoId) {
                          if ($contador < $limite) {
                              $item = "id";
                              $valor = $productoId;

                              // Obtener el producto con el controlador
                              $producto = ControladorProductos::ctrMostrarProductos($item, $valor);
                              if($contador == 4 || $productoId === end($productosArray)){
                                echo $producto["nombre"] . " ";  // Muestra el nombre del producto
                              } else {
                                echo $producto["nombre"] . ", ";  // Muestra el nombre del producto
                              }
                              

                              $contador++;  // Incrementa el contador
                          }
                      }

                      // Si hay más de 5 productos, mostrar mensaje adicional
                      if (count($productosArray) > $limite) {
                          echo "<b>... y otros " . (count($productosArray) - $limite) . " productos más.</b>";
                      }
                  } else {echo 'Ninguno';}
                  echo '
                  </div>
                  <div class="col-xl-2 col-xs-12">
                    <b>Fecha de inicio:</b> '.$filtroFechaInicio.'
                  </div>
                  <div class="col-xl-2 col-xs-12">
                    <b>Fecha de finalización:</b> '.$filtroFechaFin.'
                  </div>
                </div>
            ';
        ?>
        <hr>
        <br>
        <h5>Filtrar productos:</h5>
        <form role="form" method="get" action="index.php?ruta=kardex" enctype="multipart/form-data">
          <input type="hidden" name="ruta" value="kardex">
          <input type="hidden" id="productosSeleccionados" name="productosSeleccionados">

          <div class="row">

            <div class="col-xl-4 col-xs-12">

                <!-- ENTRADA PARA FILTRO DE PRODUCTOS-->
                <div class="form-group">
                <p>Filtrar por producto o productos:</p>
                <br>
                <table class="table table-bordered table-striped dt-responsive tablaKardex" id="tablaProductos" width="100%" style="font-size: 70%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="seleccionarTodos"></th>
                                <th>Nombre</th>
                                <th>Código</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $item = null;
                                $valor = null;
                                $productos1 = ControladorProductos::ctrMostrarProductos($item, $valor);
                                foreach ($productos1 as $key => $value){
                                  echo '<tr>
                                            <td>
                                                <input type="checkbox" class="productoCheckbox" value="'.$value['id'].'">
                                            </td>
                                            <td>'.$value['nombre'].'</td>
                                            <td>'.$value['codigo'].'</td>
                                        </tr>';
                                }
                            ?>
                        </tbody>
                    </table>

                </div>

            </div>

              <?php
                  date_default_timezone_set('America/El_Salvador'); // Establecer zona horaria de El Salvador
                  $fechaActual = date('Y-m-d'); // Obtener la fecha actual en formato YYYY-MM-DD
              ?>

            <div class="col-xl-3 col-xs-12">

                <!-- ENTRADA PARA FILTRO FECHA INICIO -->
                <div class="form-group">
                <p>Seleccionar fecha de inicio:</p>
                <br>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input type="date" class="form-control" name="fechaInicioKardex" required>
                  </div>

                </div>

            </div>

            <div class="col-xl-3 col-xs-12">

                <!-- ENTRADA PARA FILTRO FECHA FIN -->
                <div class="form-group">
                <p>Seleccionar fecha fin:</p>
                <br>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input type="date" class="form-control" name="fechaFinKardex" value="<?= $fechaActual ?>" required>
                  </div>

                </div>

            </div>


          </div>
          <button type="submit" class="btn btn-dark">Aplicar filtros</button>
        </form>

        <br>
        <?php

            if ($filtroProductos != "todos") {
                echo '<button class="btn btn-primary" onclick="location.href=\'extensiones/TCPDF-main/examples/imprimir-kardex.php?productosSeleccionados='.$filtroProductos.'&fechaInicioKardex='.$filtroFechaInicio.'&fechaFinKardex='.$filtroFechaFin.'\'">Generar reporte como pdf</button>
                <br><br>';
              foreach ($productosArray as $key => $productoId) {$item = "id";
                  $valor = $productoId;
          
                  // Obtener el producto con el controlador
                  $producto = ControladorProductos::ctrMostrarProductos($item, $valor);
                  echo '<h4>'.$producto["nombre"].'</h4>';
          
                  echo '<table border="1" cellspacing="0" cellpadding="2" id="tablaKardex" style="text-align: center; width: 100%; font-size: 15px">
                      <thead>
                          <tr>
                              <th colspan="22"><b>Kárdex</b></th>
                          </tr>
                          <tr>
                              <th colspan="1">#</th>
                              <th colspan="1">Fecha</th>
                              <th colspan="1">Detalle</th>
                              <th colspan="5"><b>Entradas</b><br>
                                  <b>Cantidad</b> | <b>Precio Unitario</b> | <b>Inversión Total</b>
                              </th>
                              <th colspan="5"><b>Salidas</b><br>
                                  <b>Cantidad</b> | <b>Precio Unitario</b> | <b>Venta Total</b>
                              </th>
                              <th colspan="10"><b>Resultados</b><br>
                                  <b>Stock Actual</b> | <b>Total Comprado</b> | <b>Inversión Total</b> | 
                                  <b>Stock Vendido</b> | <b>Venta Total</b> | <b>Ganancia</b>
                              </th>
                          </tr>
                      </thead>
                      <tbody>';
          
          
          
                  // Inicializa un arreglo vacío para todos los movimientos (entradas y salidas)
                  $movimientosCombinados = [];
          
                  // Obtener movimientos de stock
                  $item = null;
                  $valor = null;
                  $orden = "fecha";
                  $movimientos = ControladorStocks::ctrMostrarStocks($item, $valor, $orden);
          
                  foreach ($movimientos as $movimiento) {
                      if ($movimiento["id_producto"] == $productoId) {
                          $movimientosCombinados[] = [
                              'fecha' => $movimiento["fecha"],
                              'tipo' => 'entrada',
                              'cantidad' => $movimiento["cantidad"],
                              'precio_compra' => $movimiento["precio_compra"],
                              'precio_total' => $movimiento["cantidad"] * $movimiento["precio_compra"]
                          ];
                      }
                  }
          
                  // Obtener facturas
                  $item = null;
                  $valor = null;
                  $orden = "id";
                  $optimizacion = "no";
                  $facturas = ControladorFacturas::ctrMostrarFacturasAsc($item, $valor, $orden, $optimizacion);
          
                  foreach ($facturas as $factura) {
                    if($factura["estado"] == "Activa"){
                        // Decodificar el JSON de los productos
                      $productos = json_decode($factura["productos"], true);
                      foreach ($productos as $producto) {
                          if ($producto['idProducto'] == $productoId) {
                              $movimientosCombinados[] = [
                                  'fecha' => $factura["fecEmi"] . ' ' . $factura["horEmi"], // Fecha y hora de la factura
                                  'tipo' => 'salida',
                                  'cantidad' => $producto["cantidad"],
                                  'precio_venta' => $producto["precioSinImpuestos"] - $producto["descuento"],
                                  'precio_total' => $producto["cantidad"] * ($producto["precioSinImpuestos"] - $producto["descuento"])
                              ];
                          }
                      }
                    }                      
                  }
          
                  // Ordenar los movimientos combinados por la fecha
                  usort($movimientosCombinados, function ($a, $b) {
                      return strtotime($a['fecha']) - strtotime($b['fecha']);
                  });
          
                  // Inicializa las variables de control
                  $cantidad = 0;
                  $cantidadVendida = 0;
                  $cantidadComprada = 0;
                  $costo = 0;
                  $ventas = 0;
                  $ganancia = 0;
                  
                  // Recorrer los movimientos combinados ordenados
                  foreach ($movimientosCombinados as $keya => $movimiento) {
                      
                      if ($movimiento['tipo'] == 'entrada') {
                          $cantidad += $movimiento['cantidad'];
                          $costo += $movimiento['precio_total'];
                          $ganancia = $ventas - $costo; // Ajustar la ganancia según las salidas
                          $cantidadComprada += $movimiento['cantidad'];
                          if($movimiento["fecha"] >= $filtroFechaInicio && strtotime($movimiento["fecha"]) <= strtotime($filtroFechaFin . ' 23:59:59')){
                              echo '<tr style="color:navy">
                                          <th>' . ($keya + 1) . '</th>
                                          <th>' . $movimiento["fecha"] . '</th>
                                          <th>Entrada</th>
                                          <th colspan="5">
                                              <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
                                                  <tr>
                                                      <td align="center" width="33%">' . $movimiento["cantidad"] . '</td>
                                                      <td align="center" width="33%">$' . $movimiento["precio_compra"] . '</td>
                                                      <td align="center" width="33%">$' . $movimiento["precio_total"] . '</td>
                                                  </tr>
                                              </table>
                                          </th>
                                          <th colspan="5">
                                              <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                  <tr>
                                                      <td align="center" width="33%"></td>
                                                      <td align="center" width="33%"></td>
                                                      <td align="center" width="33%"></td>
                                                  </tr>
                                              </table>
                                          </th>
                                          <th colspan="10">
                                              <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                  <tr>
                                                      <td align="center" width="16.6%">' . $cantidad . '</td>
                                                      <td align="center" width="16.6%">' . $cantidadComprada . '</td>
                                                      <td align="center" width="16.6%">$' . $costo . '</td>
                                                      <td align="center" width="16.6%">' . $cantidadVendida . '</td>
                                                      <td align="center" width="16.6%">$' . $ventas . '</td>
                                                      <td align="center" width="16.6%">$' . $ganancia . '</td>
                                                  </tr>
                                              </table>
                                          </th>
                                      </tr>';
                          }
                          
                      } else if ($movimiento['tipo'] == 'salida') {
                          $cantidad -= $movimiento['cantidad'];
                          $ventas += $movimiento['precio_total'];
                          $ganancia = $ventas - $costo; // Ajustar la ganancia según las salidas
                          $cantidadVendida += $movimiento['cantidad'];
          
                          if($movimiento["fecha"] >= $filtroFechaInicio && strtotime($movimiento["fecha"]) <= strtotime($filtroFechaFin . ' 23:59:59')){
                              echo '<tr style="color: green">
                                        <th>' . ($keya + 1) . '</th>
                                        <th>' . $movimiento["fecha"] . '</th>
                                        <th>Salida</th>
                                        <th colspan="5">
                                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                <tr>
                                                    <td align="center" width="33%"></td>
                                                    <td align="center" width="33%"></td>
                                                    <td align="center" width="33%"></td>
                                                </tr>
                                            </table>
                                        </th>
                                        <th colspan="5">
                                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                <tr>
                                                    <td align="center" width="33%">' . $movimiento["cantidad"] . '</td>
                                                    <td align="center" width="33%">$' . $movimiento["precio_venta"] . '</td>
                                                    <td align="center" width="33%">$' . $movimiento["precio_total"] . '</td>
                                                </tr>
                                            </table>
                                        </th>
                                        <th colspan="10">
                                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                <tr>
                                                    <td align="center" width="16.6%">' . $cantidad . '</td>
                                                    <td align="center" width="16.6%">' . $cantidadComprada . '</td>
                                                    <td align="center" width="16.6%">$' . $costo . '</td>
                                                    <td align="center" width="16.6%">' . $cantidadVendida . '</td>
                                                    <td align="center" width="16.6%">$' . $ventas . '</td>
                                                    <td align="center" width="16.6%">$' . $ganancia . '</td>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>';
          
                          }
                          
                      }
                  }
          
                  echo '</tbody>
                          </table><br>';
              }
            }
        ?>
        </div>

    </div>

  </section>

</div>