<?php

require_once "../../../controladores/facturas.controlador.php";
require_once "../../../modelos/facturas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/stocks.controlador.php";
require_once "../../../modelos/stocks.modelo.php";

require_once '../../phpqrcode/qrlib.php';


// Verificar si los parámetros existen y asignarlos a variables
$filtroProductos = isset($_GET['productosSeleccionados']) ? $_GET['productosSeleccionados'] : 'todos';
$filtroFechaInicio = isset($_GET['fechaInicioKardex']) ? $_GET['fechaInicioKardex'] : 'todos';
$filtroFechaFin = isset($_GET['fechaFinKardex']) ? $_GET['fechaFinKardex'] : 'todos';
$productosArray = explode(',', $filtroProductos);

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        
        

        

        $this->Ln(15); // Agrega un espacio vertical de 10 unidades (puedes ajustar el valor)
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(50, 0, "", 0, true, 'C', 0, ' ', 1, false, 'M', 'M');

        $this->Ln(15); // Agrega un espacio vertical de 15 unidades
        $this->SetFont('helvetica', 'B', 14);

        

        // Inserta el código QR en el PDF
        $this->Image("", 50, 10, 30, 30, 'PNG', '', 'C', false, 300, '', false, false, 0, false, false, false);


        $this->Ln(5); // Agrega un espacio vertical de 10 unidades (puedes ajustar el valor)
        $this->SetFont('helvetica', '', 14);
        $this->Cell(275, 0, "", 0, true, 'C', 0, ' ', 1, false, 'B', 'M');
        $this->Cell(275, 20, "", 0, true, 'C', 0, ' ', 1, false, 'B', 'M');

    
        $this->Image("", 10, 5, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetAutoPageBreak(true, 10);


// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Fox Control');
$pdf->setTitle('Sistema Kardex');
$pdf->setSubject('Kardex Fox Control');
$pdf->setKeywords('	TCPDF, PDF, example, test, guide');


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);



// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->setFont('dejavusans', '', 10);

// add a page
$pdf->AddPage('L');

// create some HTML content
$html = '<br><br><br><div style="font-family: Arial, sans-serif; font-size: 5px;">';

$html = '
    <div class="row" style="font-size: 9px">
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
                    $html .= $producto["nombre"] . " ";  // Muestra el nombre del producto
                  } else {
                    $html .= $producto["nombre"] . ", ";  // Muestra el nombre del producto
                  }
                  

                  $contador++;  // Incrementa el contador
              }
          }

          // Si hay más de 5 productos, mostrar mensaje adicional
          if (count($productosArray) > $limite) {
              $html .= "<b>... y otros " . (count($productosArray) - $limite) . " productos más.</b>";
          }
      } else {$html .= 'Ninguno';}
      $html .= '
      </div>
      <div class="col-xl-2 col-xs-12">
        <b>Fecha de inicio:</b> '.$filtroFechaInicio.'
      </div>
      <div class="col-xl-2 col-xs-12">
        <b>Fecha de finalización:</b> '.$filtroFechaFin.'
      </div>
    </div>
';

if ($filtroProductos != "todos") {
    foreach ($productosArray as $key => $productoId) {$item = "id";
        $valor = $productoId;

        // Obtener el producto con el controlador
        $producto = ControladorProductos::ctrMostrarProductos($item, $valor);
        $html .= '<h4>'.$producto["nombre"].'</h4>';

        $html .= '<table border="1" cellspacing="0" cellpadding="2" align="center" style="font-size: 7px">
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
                    $html .= '<tr>
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
                    $html .= '<tr>
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

        $html .= '</tbody>
                </table>';
    }
}


$pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Kardex.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
