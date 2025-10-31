<?php
// ajax/liquidaciones.ajax.php

require_once "../controladores/facturas.controlador.php";
require_once "../modelos/facturas.modelo.php";
require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";
require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

// ajax/liquidaciones.ajax.php  (DTE 08 – Comprobante de Liquidación Electrónica v1)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

/* ========================= Helpers ========================= */
function uuid_v4_upper() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    return strtoupper($uuid);
}
function generar_numero_control_08_con_serie($serie = 'S001P001') {
    $serie = strtoupper($serie);
    if (!preg_match('/^[A-Z0-9]{8}$/', $serie)) $serie = 'S001P001';
    $quince = str_pad((string)random_int(0, 999999999999999), 15, '0', STR_PAD_LEFT);
    return "DTE-08-{$serie}-{$quince}";
}
// Redondeos
function money2($n) { return round((float)$n + 0.0000001, 2); }          // resumen (multipleOf 0.01)
function money8($n) { return round((float)$n + 0.0000000001, 8); }       // item (multipleOf 1e-8)

// Letras simples (demo)
function convertirNumeroALetras($numero) {
    $unidades = ["cero","uno","dos","tres","cuatro","cinco","seis","siete","ocho","nueve"];
    $decenas  = ["","diez","veinte","treinta","cuarenta","cincuenta","sesenta","setenta","ochenta","noventa"];
    $especial=[10=>"diez",11=>"once",12=>"doce",13=>"trece",14=>"catorce",15=>"quince",16=>"dieciséis",17=>"diecisiete",18=>"dieciocho",19=>"diecinueve"];
    $n = (int)$numero;
    if ($n < 10) return $unidades[$n];
    if ($n < 20) return $especial[$n];
    if ($n < 100) { $d=(int)($n/10); $u=$n%10; return $u ? "{$decenas[$d]} y {$unidades[$u]}" : $decenas[$d]; }
    if ($n < 1000) { $c=(int)($n/100); $r=$n%100; $cent = $c==1?"ciento":($c==5?"quinientos":"{$unidades[$c]}cientos"); return $r? "$cent ".convertirNumeroALetras($r):($c==1?"cien":$cent); }
    if ($n < 1000000) { $m=(int)($n/1000); $r=$n%1000; $mL=$m==1?"mil":convertirNumeroALetras($m)." mil"; return $r? "$mL ".convertirNumeroALetras($r):$mL; }
    return "NÚMERO GRANDE";
}
function convertirMontoALetras($monto) {
    $partes = explode('.', number_format((float)$monto, 2, '.', ''));
    $ent = (int)$partes[0]; $dec = str_pad($partes[1], 2, '0', STR_PAD_RIGHT);
    return strtoupper(convertirNumeroALetras($ent)." {$dec}/100");
}

/* ======================= Acción principal ======================= */
if (($_POST['action'] ?? '') !== 'crearFirmarSellarLote') {
    echo json_encode(["error"=>"Acción no válida"]); exit;
}
if (!isset($_SESSION["tokenInicioSesionMh"])) {
    echo json_encode(["error" => "Token MH no encontrado en la sesión."]); exit;
}

/* ====== Datos DEMO (ajusta a tus registros reales) ====== */
$emisor = [
    "nit" => "06143107201022",
    "nrc" => "2922569",
    "nombre" => "FARKAS IMPORTADORA, SOCIEDAD ANONIMA DE CAPITAL VARIABLE",
    "codActividad" => "45100",
    "descActividad" => "Venta de vehículos automotores",
    "nombreComercial" => null,
    "tipoEstablecimiento" => "01",
    "direccion" => [
        "departamento" => "06",
        "municipio"    => "01",
        "complemento"  => "CARRETERA AL PUERTO KM 10, OFICINA 1"
    ],
    "telefono" => "21234567",              // 8-30 chars
    "correo"   => "demo@empresa.com",
    "codEstableMH"   => null,              // required pero puede ser null (en el esquema)
    "codEstable"     => null,
    "codPuntoVentaMH"=> null,
    "codPuntoVenta"  => null
];
$receptor = [
    "nit" => "06140103161043",
    "nrc" => "2484763",
    "nombre" => "BEST BUY GARMENT SUPPLY, S.A. DE C.V.	",
    "codActividad" => "46613",
    "descActividad" => "Venta al por mayor de lubricantes. grasas y otros aceites para automotores, maquinaria industrial. etc.",
    "nombreComercial" => "BEST BUY GARMENT SUPPLY, S.A. DE C.V.	",
    "direccion" => [
        "departamento" => "06",
        "municipio"    => "01",
        "complemento"  => "CALLE FICTICIA #123"
    ],
    "telefono" => "23456789",
    "correo"   => "proveedor@demo.com"
];

// Parámetros
$cantidad     = 50;
$urlFirmador  = "http://localhost:8113/firmardocumento/";
$urlSellar    = "https://apitest.dtes.mh.gob.sv/fesv/recepciondte";
$headersFirm  = ['User-Agent: facturacion','Content-Type: application/json','Accept: application/json'];
$headersSell  = [
    "Authorization: " . $_SESSION["tokenInicioSesionMh"],
    "User-Agent: facturacion",
    "Content-Type: application/json",
    "Accept: application/json"
];

// Contadores
$total=$cantidad; $creados=0; $firmados=0; $sellados=0; $fallas=0; $detalles=[];
@set_time_limit(600);

// Periodo y fecha
$hoy = new DateTime('now');
$fechaDoc = $hoy->format('Y-m-d');  // para fechaGeneracion en items y fecEmi

for ($i=0; $i<$cantidad; $i++) {
    try {
        /* -------- Identificación -------- */
        $serie = 'S001P001';
        $numeroControl    = generar_numero_control_08_con_serie($serie);
        $codigoGeneracion = uuid_v4_upper();
        $identificacion = [
            "version" => 1,
            "ambiente" => "00",
            "tipoDte" => "08",
            "numeroControl" => $numeroControl,
            "codigoGeneracion" => $codigoGeneracion,
            "tipoModelo" => 1,
            "tipoOperacion" => 1,
            "fecEmi" => $fechaDoc,
            "horEmi" => date('H:i:s'),
            "tipoMoneda" => "USD"
        ];

        /* -------- Cuerpo (array de ítems) --------
           Demo con 2 ítems gravados (13%) y 1 exento.
           - Campos por ítem -> multipleOf 1e-8.
           - tributos -> ["20"] para calcular IVA (si no aplica, usar [] o null).
        */
        $items = [];
        $numItem = 1;

        // Ítem exento
        $ventaNoSuj_3 = 0.0; $ventaExenta_3 = 25.00; $ventaGravada_3 = 0.0; $export_3 = 0.0;
        $iva3 = 0.0;
        $items[] = [
            "numItem" => $numItem++,
            "tipoDte" => "03",                 // CCFE u otro según tu flujo
            "tipoGeneracion" => 1,
            "numeroDocumento" => "704AD787-85C1-76B8-C2D2-74F5FA5B237F",
            "fechaGeneracion" => $fechaDoc,
            "ventaNoSuj" => 0.00,
            "ventaExenta" => 0.00,
            "ventaGravada" => 20.00,
            "exportaciones" => 0.00,
            "tributos" => ["20"],                  // sin IVA
            "ivaItem" => 2.60,
            "obsItem" => "Servicio"
        ];

        /* ---- Totales por tipo (sumas de items) ---- */
        $totalNoSuj      = 0.0;
        $totalExenta     = 0.0;
        $totalGravada    = 20.00;
        $totalExport     = 0.0;
        $totalIvaItems   = 0.0;

        foreach ($items as $it) {
            $totalNoSuj   += (float)$it["ventaNoSuj"];
            $totalExenta  += (float)$it["ventaExenta"];
            $totalGravada += (float)$it["ventaGravada"];
            $totalExport  += (float)$it["exportaciones"];
            $totalIvaItems+= (float)$it["ivaItem"];
        }

        // Redondeos a 2 dec para RESUMEN
        $totalNoSuj_2    = money2($totalNoSuj);
        $totalExenta_2   = money2($totalExenta);
        $totalGravada_2  = money2($totalGravada);
        $totalExport_2   = money2($totalExport);
        $subTotalVentas  = money2($totalNoSuj_2 + $totalExenta_2 + $totalGravada_2 + $totalExport_2);

        // Resumen de tributos: solo IVA ("20") con descripción genérica
        $valorIVA_2      = money2($totalIvaItems);
        $resumenTributos = $valorIVA_2 > 0 ? [[
            "codigo" => "20",
            "descripcion" => "IVA",
            "valor" => 2.60
        ]] : [];

        $montoTotalOperacion = money2($subTotalVentas + $valorIVA_2);
        $ivaPerci            = money2(0.00);          // si aplicara percepción la calculas y sumas en tributos/total
        $total               = money2($montoTotalOperacion + $ivaPerci);
        $totalLetras         = convertirMontoALetras($total);
        $condicionOperacion  = 1;                     // contado

        /* -------- Estructuras finales -------- */
        $cuerpoDocumento = $items;

        $resumen = [
            "totalNoSuj"          => 0.00,
            "totalExenta"         => 0.00,
            "totalGravada"        => 20.00,
            "totalExportacion"    => 0.00,
            "subTotalVentas"      => 20.00,
            "tributos" => [
                                [
                                    "codigo" => "20",
                                    "descripcion" => "Impuesto al Valor Agregado 13%",
                                    "valor" => 2.60
                                ]
                        ],
            "montoTotalOperacion" => 22.60,
            "ivaPerci"            => 0.00,
            "total"               => 22.60,
            "totalLetras"         => "VEINTIDOS CON 60/100",
            "condicionOperacion"  => $condicionOperacion
        ];

        $extension = [
            "nombEntrega"   => "RESPONSABLE DEMO",
            "docuEntrega"   => "DUI 01234567-8",
            "nombRecibe"    => "CONTACTO RECEPTOR",
            "docuRecibe"    => "DUI 00000000-0",
            "observaciones" => null
        ];

        $apendice = [[
            "campo"   => "REF",
            "etiqueta"=> "Referencia interna",
            "valor"   => "LQ-".date('Ym')."-".str_pad((string)($i+1), 4, "0", STR_PAD_LEFT)
        ]];

        /* -------- Payload firmador -------- */
        $payloadFirmar = [
            "contentType" => "application/json",
            "nit"         => $emisor["nit"],         // NIT para firmador
            "activo"      => true,
            "passwordPri" => "Farkas2025_",          // AJUSTA a tu llave privada
            "dteJson"     => [
                "identificacion" => $identificacion,
                "emisor"         => $emisor,
                "receptor"       => $receptor,
                "cuerpoDocumento"=> $cuerpoDocumento,
                "resumen"        => $resumen,
                "extension"      => $extension,
                "apendice"       => $apendice
            ]
        ];

        // ---- Firmar ----
        $ch1 = curl_init($urlFirmador);
        curl_setopt_array($ch1, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headersFirm,
            CURLOPT_POSTFIELDS => json_encode($payloadFirmar, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            CURLOPT_TIMEOUT => 60
        ]);
        $respFirmar = curl_exec($ch1);
        $errFirmar  = curl_error($ch1);
        $codeFirmar = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
        curl_close($ch1);

        if ($errFirmar || !$respFirmar) {
            $fallas++; 
            $detalles[] = ["paso"=>"firmar","http"=>$codeFirmar,"error"=>$errFirmar?:'sin respuesta del firmador'];
            continue;
        }

        $jsonFirmar = json_decode($respFirmar, true);
        $firmaDigital = null;
        if (!$jsonFirmar) {
            $posiblesBase64 = trim($respFirmar);
            if ($posiblesBase64 !== '') $firmaDigital = $posiblesBase64;
        } else {
            $firmaDigital =
                ($jsonFirmar['documentoFirmado'] ?? null) ??
                ($jsonFirmar['firmaDigital']     ?? null) ??
                ($jsonFirmar['documento']        ?? null) ??
                ($jsonFirmar['dteFirmado']       ?? null) ??
                ($jsonFirmar['body']             ?? null) ??
                ($jsonFirmar['respuesta']['documentoFirmado'] ?? null) ??
                ($jsonFirmar['respuesta']['firmaDigital']     ?? null) ??
                ($jsonFirmar['respuesta']['documento']        ?? null) ??
                ($jsonFirmar['respuesta']['body']             ?? null);
        }

        if (!$firmaDigital) {
            $fallas++; 
            $detalles[] = ["paso"=>"firmar","http"=>$codeFirmar,"error"=>"Firmador no devolvió campo de firma","raw"=>mb_strimwidth($respFirmar,0,3000,'...')];
            continue;
        }

        $creados++; $firmados++;

        // ---- Sellar (DTE 08, versión 1) ----
        $payloadSellar = [
            "ambiente" => "00",
            "codigoGeneracion" => $codigoGeneracion,
            "documento" => $firmaDigital,
            "idEnvio" => 1,
            "tipoDte" => "08",
            "version" => 1
        ];

        $ch2 = curl_init($urlSellar);
        curl_setopt_array($ch2, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headersSell,
            CURLOPT_POSTFIELDS => json_encode($payloadSellar, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            CURLOPT_TIMEOUT => 60
        ]);
        $respSellar = curl_exec($ch2);
        $errSellar  = curl_error($ch2);
        $codeSellar = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);

        if ($errSellar || !$respSellar || $codeSellar >= 400) {
            $fallas++; 
            $detalles[] = ["paso"=>"sellar","http"=>$codeSellar,"error"=>$errSellar?:$respSellar];
            continue;
        }

        $jsonSellar = json_decode($respSellar, true);
        if (isset($jsonSellar["selloRecibido"])) {
            $sellados++;
            // persistir si aplica: $codigoGeneracion, $jsonSellar["selloRecibido"]
        } else {
            $fallas++;
            $detalles[] = ["paso"=>"sellar","error"=>"Sin selloRecibido","respuesta"=>$respSellar];
        }

    } catch (Throwable $e) {
        $fallas++;
        $detalles[] = ["paso"=>"excepcion","error"=>$e->getMessage()];
    }
}

echo json_encode([
    "total"    => $total,
    "creados"  => $creados,
    "firmados" => $firmados,
    "sellados" => $sellados,
    "fallas"   => $fallas,
    "detalles" => $detalles
]);
