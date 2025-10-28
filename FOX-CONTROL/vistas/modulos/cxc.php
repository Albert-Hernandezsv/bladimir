<?php

  if($_SESSION["rol"] == "Admin" || $_SESSION["rol"] == "Facturación" || $_SESSION["rol"] == "Contabilidad" || $_SESSION["rol"] == "Vendedor"){
  } else {
      echo '<script>
      window.location = "inicio";
      </script>';
    return;
  }
// Configurar la zona horaria de El Salvador
date_default_timezone_set('America/El_Salvador');
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
      
      Sistema de cuentas por cobrar
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio </a></li>
      
      <li class="active">&nbsp;Sistema de facturación</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
        <h3>Facturas sin pagar</h3>
        <button class="btn btn-primary" onclick="descargarPDF()">Descargar reporte como pdf</button>
        <button class="btn btn-success" onclick="exportarExcel()">Descargar reporte como Excel</button>
      </div>
<br>
      <div class="box-body">

        <?php
            // Verificar si los parámetros existen y asignarlos a variables
            $filtroCliente = isset($_GET['filtroCliente']) ? $_GET['filtroCliente'] : 'todos';
            $filtroFactura = isset($_GET['filtroFactura']) ? $_GET['filtroFactura'] : 'todos';
            $filtroVendedor = isset($_GET['filtroVendedor']) ? $_GET['filtroVendedor'] : 'todos';
            $filtroFacturador = isset($_GET['filtroFacturador']) ? $_GET['filtroFacturador'] : 'todos';
            $filtroFechaInicio = isset($_GET['filtroFechaInicio']) ? $_GET['filtroFechaInicio'] : '00-00-0000';
            $filtroFechaFin = isset($_GET['filtroFechaFin']) ? $_GET['filtroFechaFin'] : '00-00-0000';

            $tipoFacturaTextoF = "";
                          switch ($filtroFactura) {
                            case "01":
                                $tipoFacturaTextoF = "Factura";
                                break;
                            case "03":
                                $tipoFacturaTextoF = "Comprobante de crédito fiscal";
                                break;
                            case "04":
                                $tipoFacturaTextoF = "Nota de remisión";
                                break;
                            case "05":
                                $tipoFacturaTextoF = "Nota de crédito";
                                break;
                            case "06":
                                $tipoFacturaTextoF = "Nota de débito";
                                break;
                            case "07":
                                $tipoFacturaTextoF = "Comprobante de retención";
                                break;
                            case "08":
                                $tipoFacturaTextoF = "Comprobante de liquidación";
                                break;
                            case "09":
                                $tipoFacturaTextoF = "Documento contable de liquidación";
                                break;
                            case "11":
                                $tipoFacturaTextoF = "Factura de exportación";
                                break;
                            case "14":
                                $tipoFacturaTextoF = "Factura de sujeto excluido";
                                break;
                            case "15":
                                $tipoFacturaTextoF = "Comprobante de donación";
                                break;
                            case "todos":
                                  $tipoFacturaTextoF = "Todos";
                                  break;
                
                            default:
                                $tipoFacturaTextoF = "Factura no válida";
                                break;
                        }

            $filtroClienteF = "";
            if($filtroCliente != "todos"){
              $item = "id";
              $valor = $filtroCliente;
              $orden = "id";

              $cliente = ControladorClientes::ctrMostrarClientes($item, $valor, $orden);
              
              $filtroClienteF = $cliente["nombre"];
            } else {
              $filtroClienteF = "Todos";
            }

            $filtroVendedorF = "";
            if($filtroVendedor != "todos"){
              $item = "id";
              $valor = $filtroVendedor;
              
              $usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
              
              $filtroVendedorF = $usuario["nombre"];
            } else {
              $filtroVendedorF = "Todos";
            }

            $filtroFacturadorF = "";
            if($filtroFacturador != "todos"){
              $item = "id";
              $valor = $filtroFacturador;
              
              $usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
              
              $filtroFacturadorF = $usuario["nombre"];
            } else {
              $filtroFacturadorF = "Todos";
            }

                        

            echo '
                <h4>Filtros aplicados actualmente</h4>
                <div class="row">
                  <div class="col-xl-2 col-xs-12">
                    Cliente: '.$filtroClienteF.'
                  </div>
                  <div class="col-xl-2 col-xs-12">
                    Tipo de factura: '.$tipoFacturaTextoF.'
                  </div>
                  <div class="col-xl-2 col-xs-12">
                    Vendedor: '.$filtroVendedorF.'
                  </div>
                  <div class="col-xl-2 col-xs-12">
                    Facturador: '.$filtroFacturadorF.'
                  </div>
                  <div class="col-xl-2 col-xs-12">
                    Fecha inicio: '.$filtroFechaInicio.'
                  </div>
                  <div class="col-xl-2 col-xs-12">
                    Fecha fin: '.$filtroFechaFin.'
                  </div>
                </div>
            ';
        ?>
        <hr>
        <br>
        <h5>Filtrar facturas:</h5>

        <form role="form" method="get" action="index.php?ruta=cxc" enctype="multipart/form-data">
          <input type="hidden" name="ruta" value="cxc">

          <div class="row">

            <div class="col-xl-2 col-xs-12">

                <!-- ENTRADA PARA FILTRO DE CLIENTE -->
                <div class="form-group">
                <p>Filtrar por cliente:</p>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <select class="form-control" id="filtroCliente" name="filtroCliente" value="" required>
                          <option value="todos">Todos</option>
                          <?php
                            $item = null;
                            $valor = null;
                            $orden = "id";
      
                            $clientes = ControladorClientes::ctrMostrarClientes($item, $valor, $orden);

                            foreach ($clientes as $key => $value){
                              echo '
                                <option value="'.$value["id"].'">'.$value["nombre"].'</option>
                              ';
                            }
                          ?>
                        </select>
                  </div>

                </div>

            </div>

            <div class="col-xl-2 col-xs-12">

                <!-- ENTRADA PARA FILTRO DE TIPO DE FACTURA -->
                <div class="form-group">
                <p>Filtrar por tipo de factura:</p>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <select class="form-control" id="filtroFactura" name="filtroFactura" value="" required>
                          <option value="todos">Todas</option>
                          <option value="01">Factura</option>
                          <option value="03">CCF</option>
                          <option value="11">Exportación</option>
                          <option value="14">Sujeto excludio</option>
                        </select>
                  </div>

                </div>

            </div>

            <div class="col-xl-2 col-xs-12">

                <!-- ENTRADA PARA FILTRO DE VENDEDOR -->
                <div class="form-group">
                <p>Filtrar por vendedor:</p>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <select class="form-control" id="filtroVendedor" name="filtroVendedor" value="" required>
                          <option value="todos">Todos</option>
                          <?php
                            $item = null;
                            $valor = null;
                            $orden = "id";
      
                            $vendedores = ControladorUsuarios::ctrMostrarUsuarios($item, $valor, $orden);

                            foreach ($vendedores as $key => $value){
                              echo '
                                <option value="'.$value["id"].'">'.$value["nombre"].'</option>
                              ';
                            }
                          ?>
                        </select>
                  </div>

                </div>

            </div>

            <div class="col-xl-2 col-xs-12">

                <!-- ENTRADA PARA FILTRO DE FACTURADOR -->
                <div class="form-group">
                <p>Filtrar por facturador:</p>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <select class="form-control" id="filtroFacturador" name="filtroFacturador" value="" required>
                          <option value="todos">Todos</option>
                          <?php
                            $item = null;
                            $valor = null;
                            $orden = "id";
      
                            $vendedores = ControladorUsuarios::ctrMostrarUsuarios($item, $valor, $orden);

                            foreach ($vendedores as $key => $value){
                              echo '
                                <option value="'.$value["id"].'">'.$value["nombre"].'</option>
                              ';
                            }
                          ?>
                        </select>
                  </div>

                </div>

            </div>

            <div class="col-xl-2 col-xs-12">

                <!-- ENTRADA PARA FILTRO DE FECHA DE INICIO -->
                <div class="form-group">
                <p>Filtrar por fecha inicio:</p>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                          <input type="date" class="form-control" id="filtroFechaInicio" name="filtroFechaInicio">
                  </div>

                </div>

            </div>

            <div class="col-xl-2 col-xs-12">

                <!-- ENTRADA PARA FILTRO DE FECHA FINALIZACION -->
                <div class="form-group">
                <p>Filtrar por fecha fin:</p>
                  <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="date" class="form-control" id="filtroFechaFin" name="filtroFechaFin" value="<?php echo $_GET["filtroFechaFin"]; ?>">
                  </div>

                </div>

            </div>



          </div>
          <button type="submit" class="btn btn-dark">Aplicar filtros</button>
        </form>

        <br>
        <?php
              $optimizacion;
              if(!isset($_GET["optimizar"])){
                $optimizacion = "si";
                echo "<div class='form-check form-switch'>
                  <input class='form-check-input' type='checkbox' role='switch' id='flexSwitchCheckChecked' onclick=\"location.href='index.php?ruta=cxc&filtroCliente=".$filtroCliente."&filtroFactura=".$filtroFactura."&filtroVendedor=".$filtroVendedor."&filtroFacturador=".$filtroFacturador."&filtroFechaInicio=".$filtroFechaInicio."&filtroFechaFin=".$filtroFechaFin."&optimizar=no'\" checked>
                  <label class='form-check-label' for='flexSwitchCheckChecked'>Optimizar tabla</label>
                </div>";
              } else {
                $optimizacion = "no";
                echo "<div class='form-check form-switch'>
                  <input class='form-check-input' type='checkbox' role='switch' id='flexSwitchCheckChecked' onclick=\"location.href='index.php?ruta=cxc&filtroCliente=".$filtroCliente."&filtroFactura=".$filtroFactura."&filtroVendedor=".$filtroVendedor."&filtroFacturador=".$filtroFacturador."&filtroFechaInicio=".$filtroFechaInicio."&filtroFechaFin=".$filtroFechaFin."'\">
                  <label class='form-check-label' for='flexSwitchCheckChecked'>Optimizar tabla</label>
                </div>";
              }
          ?>
        <br>
       <table class="table table-bordered table-striped dt-responsive tablas" id="facturasSinPagar" width="100%" style="font-size: 80%">
         
        <thead>
         
         <tr>
           
           <th style="width:200px">Cliente</th>
           <th style="width:150px">Número de control</th>
           <th style="width:150px">Código de generación</th>
           <th>Tipo de factura</th>
           <th>Monto total</th>
           <th style="width:20px !important">Monto abonado</th>
           <th>Estado</th>
           <th>Fecha</th>
           <th style="width:50px !important">Días de atraso</th>
           <th>Vendedor</th>
           <th>Facturador</th>
           <th>Acciones</th>

         </tr> 

        </thead>
                            
        <tbody>

            <?php
              
              $item = null;
              $valor = null;
              $orden = "fecEmi";

              $facturas = ControladorFacturas::ctrMostrarFacturas($item, $valor, $orden, $optimizacion);

              foreach ($facturas as $key => $value){
                if($value["tipoDte"] == "05" || $value["tipoDte"] == "06" || $value["modo"] == "Contingencia" || $value["tipoDte"] == "04" || $value["estado"] == "Anulada"){
                
                 } else {

                    $item = "id";
                    $valor = $value["id_cliente"];
                    $orden = "id";

                    $cliente = ControladorClientes::ctrMostrarClientes($item, $valor, $orden);

                    if (
                      ($filtroCliente == "todos" || $cliente["id"] == $filtroCliente) &&
                      ($filtroFactura == "todos" || $value["tipoDte"] == $filtroFactura) &&
                      ($filtroVendedor == "todos" || $value["id_vendedor"] == $filtroVendedor) &&
                      ($filtroFacturador == "todos" || $value["id_usuario"] == $filtroFacturador) &&
                      (
                          ($filtroFechaInicio == "todos" && $filtroFechaFin == "todos") || 
                          ($filtroFechaInicio != "todos" && $filtroFechaFin == "todos" && $value["fecEmi"] >= $filtroFechaInicio) ||
                          ($filtroFechaInicio == "todos" && $filtroFechaFin != "todos" && $value["fecEmi"] <= $filtroFechaFin) ||
                          ($filtroFechaInicio != "todos" && $filtroFechaFin != "todos" && $value["fecEmi"] >= $filtroFechaInicio && $value["fecEmi"] <= $filtroFechaFin)
                      )
                  ){
                          
                          // Suponiendo que $value["fecha"] tiene el valor '2024-10-19 22:36:44'
                          $fechaOriginal = new DateTime($value["fecEmi"]);
                          $fechaFormateada = $fechaOriginal->format('d \d\e F \d\e Y'); // Formato deseado
                          $firmaDigi = "";
                          $sello = "";
                          if($value["firmaDigital"] == ""){
                            $firmaDigi = "No";
                          } else {
                            $firmaDigi = "Si";
                          }

                          if($value["sello"] ===  ""){
                            $sello = "No";
                          } else {
                            $sello = "Si";
                          }

                          $tipoFacturaTexto = "";
                          switch ($value["tipoDte"]) {
                            case "01":
                                $tipoFacturaTexto = "Factura";
                                break;
                            case "03":
                                $tipoFacturaTexto = "Comprobante de crédito fiscal";
                                break;
                            case "04":
                                $tipoFacturaTexto = "Nota de remisión";
                                break;
                            case "05":
                                $tipoFacturaTexto = "Nota de crédito";
                                break;
                            case "06":
                                $tipoFacturaTexto = "Nota de débito";
                                break;
                            case "07":
                                $tipoFacturaTexto = "Comprobante de retención";
                                break;
                            case "08":
                                $tipoFacturaTexto = "Comprobante de liquidación";
                                break;
                            case "09":
                                $tipoFacturaTexto = "Documento contable de liquidación";
                                break;
                            case "11":
                                $tipoFacturaTexto = "Factura de exportación";
                                break;
                            case "14":
                                $tipoFacturaTexto = "Factura de sujeto excluido";
                                break;
                            case "15":
                                $tipoFacturaTexto = "Comprobante de donación";
                                break;
                
                            default:
                                $tipoFacturaTexto = "Factura no válida";
                                break;
                        }

                        $tipo = "";
                        if($cliente["tipo_cliente"] == "00"){
                          $tipo = "Persona normal";
                        }
                        if($cliente["tipo_cliente"] == "01"){
                          $tipo = "Declarante IVA";
                        }
                        if($cliente["tipo_cliente"] == "02"){
                          $tipo = "Empresa con beneficios fiscales";
                        }
                        if($cliente["tipo_cliente"] == "03"){
                          $tipo = "Diplomático";
                        }

                        $totalF = "";
                        if($cliente["tipo_cliente"] ==  "00" && $value["tipoDte"] == "01"){
                          $totalF = $value["total"];
                        }
                        if($cliente["tipo_cliente"] ==  "00" && $value["tipoDte"] == "03"){
                          $totalF = $value["total"];
                        }
                        if($cliente["tipo_cliente"] ==  "00" && $value["tipoDte"] == "11"){
                          $totalF = $value["total"] + $value["seguro"] + $value["flete"];
                        }
                        if($cliente["tipo_cliente"] ==  "00" && $value["tipoDte"] == "14"){
                          $totalF = $value["totalSinIva"]-($value["totalSinIva"]*0.1);
                        }

                        if($cliente["tipo_cliente"] ==  "01" && $value["tipoDte"] == "01"){
                          $totalF = $value["total"];
                        }
                        if($cliente["tipo_cliente"] ==  "01" && $value["tipoDte"] == "03"){
                          $totalF = $value["total"];
                        }
                        if($cliente["tipo_cliente"] ==  "01" && $value["tipoDte"] == "11"){
                          $totalF = $value["totalSinIva"] + $value["seguro"] + $value["flete"];
                        }

                        if($cliente["tipo_cliente"] ==  "02" && $value["tipoDte"] == "01"){
                          $totalF = $value["totalSinIva"];
                        }
                        if($cliente["tipo_cliente"] ==  "02" && $value["tipoDte"] == "03"){
                          $totalF = $value["totalSinIva"];
                        }
                        if($cliente["tipo_cliente"] ==  "02" && $value["tipoDte"] == "11"){
                          $totalF = $value["totalSinIva"];
                        }

                        if($cliente["tipo_cliente"] ==  "03" && $value["tipoDte"] == "01"){
                          $totalF = $value["totalSinIva"];
                        }
                        if($cliente["tipo_cliente"] ==  "03" && $value["tipoDte"] == "03"){
                          $totalF = $value["totalSinIva"];
                        }
                        if($cliente["tipo_cliente"] ==  "03" && $value["tipoDte"] == "11"){
                          $totalF = $value["totalSinIva"] + $value["seguro"] + $value["flete"];
                        }

                        if($cliente["tipo_cliente"] ==  "01" && $value["tipoDte"] == "05"){
                          $totalF = $value["total"];
                        }
                        if($cliente["tipo_cliente"] ==  "02" && $value["tipoDte"] == "05"){
                          $totalF = $value["totalSinIva"];
                        }
                        if($cliente["tipo_cliente"] ==  "03" && $value["tipoDte"] == "05"){
                          $totalF = $value["totalSinIva"];
                        }

                        if($cliente["tipo_cliente"] ==  "01" && $value["tipoDte"] == "04"){
                          $totalF = $value["total"];
                        }
                        if($cliente["tipo_cliente"] ==  "02" && $value["tipoDte"] == "04"){
                          $totalF = $value["totalSinIva"];
                        }
                        if($cliente["tipo_cliente"] ==  "03" && $value["tipoDte"] == "04"){
                          $totalF = $value["totalSinIva"];
                        }

                        $item = null;
                        $valor = null;
                        $orden = "fecha_abono";
            
                        $abonos = ControladorFacturas::ctrMostrarAbonos($item, $valor, $orden);
                        
                        $abonoGeneral = 0;
                        foreach ($abonos as $key1 => $value1){
                          if($value1["id_factura"] == $value["id"]){
                            $abonoGeneral += $value1["monto"];
                          }
                        }

                        $estadoPagado = "";
                        $diasPasados = 0;
                        if($abonoGeneral == $totalF){
                          $estadoPagado = "Pagada";
                        } else {
                          $estadoPagado = "Pendiente";
                          

                          // Fecha de emisión
                          $fecEmi = $value["fecEmi"];

                          // Obtener la fecha actual en el formato "Y-m-d"
                          $fechaActual = date('Y-m-d');

                          // Crear un objeto DateTime con la fecha de emisión
                          $fechaEmision = new DateTime($fecEmi);

                          // Crear un objeto DateTime con la fecha actual
                          $fechaHoy = new DateTime($fechaActual);

                          // Calcular la diferencia en días entre la fecha de emisión y la fecha actual
                          $diferencia = $fechaHoy->diff($fechaEmision);

                          // Mostrar la cantidad de días que han pasado
                          $diasPasados = $diferencia->days;

                        }             
                        
                        $item = "id";
                        $valor = $value["id_vendedor"];
                
                        $vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

                        $item = "id";
                        $valor = $value["id_usuario"];
                
                        $usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
                

                        if($estadoPagado == "Pagada"){

                        } else {
                          echo ' <tr>
                          <td>'.$cliente["nombre"].' - '.$tipo.'</td>
                          <td>'.$value["numeroControl"].'</td>
                          <td>'.$value["codigoGeneracion"].'</td>
                          <td>'.$tipoFacturaTexto.'</td>
                          <td>$'.$totalF.'</td>
                          <td>$'.$abonoGeneral.'</td>
                          <td>'.$estadoPagado.'</td>
                          <td>'.$fechaFormateada.'</td>
                          <td>'.$diasPasados.'</td>
                          <td>'.$vendedor["nombre"].'</td>
                          <td>'.$usuario["nombre"].'</td>
                          
          
                          <td>

                            <div class="btn-group">  
                              <button class="btn btn-warning btnVerFactura" idFactura="'.$value["id"].'"><i class="fa fa-eye"></i></button>
                            </div>  

                          </td>

                            </tr>';
                        }
                    }
                  
                 }
                
                          
              }


            ?> 

        </tbody>

       </table>

      </div>

    </div>

  </section>

</div>