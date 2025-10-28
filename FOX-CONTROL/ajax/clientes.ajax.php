<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

require_once "../controladores/facturas.controlador.php";
require_once "../modelos/facturas.modelo.php";

class AjaxClientes{

    /*=============================================
	VALIDAR CLIENTE CON FACTURAS
	=============================================*/	
	public $validarCliente;
    public function ajaxValidarCliente() {

        $item = "id_cliente";
        $valor = $this->validarCliente;
        $orden = "id";
        $optimizacion = "no";

        // Obtener los productos relacionados con esta categoría
        $respuesta = ControladorFacturas::ctrMostrarFacturas($item, $valor, $orden, $optimizacion);

        // Devolver la respuesta como JSON
        echo json_encode($respuesta);

    }

    /*=============================================
	VALIDAR MOTORISTA CON FACTURAS
	=============================================*/	
	public $validarMot;
    public function ajaxValidarMotorista() {

        $item = "id_motorista";
        $valor = $this->validarMot;
        $orden = "id";
        $optimizacion = "no";

        // Obtener los productos relacionados con esta categoría
        $respuesta = ControladorFacturas::ctrMostrarFacturas($item, $valor, $orden, $optimizacion);

        // Devolver la respuesta como JSON
        echo json_encode($respuesta);

    }

    /*=============================================
    EDITAR CLIENTE
    =============================================*/    

    public $idCliente;

    public function ajaxEditarCliente(){

        $item = "id";
        $orden = "id";
        $valor = $this->idCliente;

        $respuesta = ControladorClientes::ctrMostrarClientes($item, $valor, $orden);

        echo json_encode($respuesta);

    }

    /*=============================================
    EDITAR MOTORISTA
    =============================================*/    

    public $idMotorista;

    public function ajaxEditarMotorista(){

        $item = "id";
        $orden = "id";
        $valor = $this->idMotorista;

        $respuesta = ControladorClientes::ctrMostrarMotoristas($item, $valor, $orden);

        echo json_encode($respuesta);

    }

    /*=============================================
    EDITAR PROVEEDOR
    =============================================*/    

    public $idProveedor;

    public function ajaxEditarProveedor(){

        $item = "id";
        $orden = "id";
        $valor = $this->idProveedor;

        $respuesta = ControladorClientes::ctrMostrarProveedores($item, $valor, $orden);

        echo json_encode($respuesta);

    }

    /*=============================================
    EDITAR COMPRA
    =============================================*/    

    public $idCompra;

    public function ajaxEditarCompra(){

        $item = "id";
        $orden = "id";
        $valor = $this->idCompra;

        $respuesta = ControladorFacturas::ctrMostrarCompras($item, $valor, $orden, "no");

        echo json_encode($respuesta);

    }

    /*=============================================
    MOSTRAR PROVEEDOR
    =============================================*/    

    public $idProveedorM;

    public function ajaxEditarProveedorM(){

        $item = "nit";
        $orden = "id";
        $valor = $this->idProveedorM;

        $respuesta = ControladorClientes::ctrMostrarProveedores($item, $valor, $orden);

        echo json_encode($respuesta);

    }

    /*=============================================
    EDITAR DATOS EMPRESARIALES
    =============================================*/    

    public $idEmpresa;

    public function ajaxEditarEmpresa(){

        $item = "id";
        $orden = "id";
        $valor = $this->idEmpresa;

        $respuesta = ControladorClientes::ctrMostrarEmpresas($item, $valor, $orden);

        echo json_encode($respuesta);

    }

}

/*=============================================
EDITAR CLIENTE
=============================================*/
if(isset($_POST["idCliente"])){

    $editar = new AjaxClientes();
    $editar -> idCliente = $_POST["idCliente"];
    $editar -> ajaxEditarCliente();

}

/*=============================================
EDITAR MOTORISTA
=============================================*/
if(isset($_POST["idMotorista"])){

    $editarM = new AjaxClientes();
    $editarM -> idMotorista = $_POST["idMotorista"];
    $editarM -> ajaxEditarMotorista();

}

/*=============================================
EDITAR PROVEEDOR
=============================================*/
if(isset($_POST["idProveedor"])){

    $editarP = new AjaxClientes();
    $editarP -> idProveedor = $_POST["idProveedor"];
    $editarP -> ajaxEditarProveedor();

}

/*=============================================
EDITAR COMPRA
=============================================*/
if(isset($_POST["idCompra"])){

    $editarC = new AjaxClientes();
    $editarC -> idCompra = $_POST["idCompra"];
    $editarC -> ajaxEditarCompra();

}

/*=============================================
MOSTRAR PROVEEDOR
=============================================*/
if(isset($_POST["idProveedorM"])){

    $editarPM= new AjaxClientes();
    $editarPM -> idProveedorM = $_POST["idProveedorM"];
    $editarPM -> ajaxEditarProveedorM();

}

/*=============================================
EDITAR DATO EMPREARIALES
=============================================*/
if(isset($_POST["idEmpresa"])){

    $editarEmpresa = new AjaxClientes();
    $editarEmpresa -> idEmpresa = $_POST["idEmpresa"];
    $editarEmpresa -> ajaxEditarEmpresa();

}

/*=============================================
VALIDAR NO ELIMINAR CLIENTE QUE TENGA FACTURAS
=============================================*/

if(isset($_POST["idClienteValidar"])){
    
    $valCliente = new AjaxClientes();
    $valCliente -> validarCliente = $_POST["idClienteValidar"];
    $valCliente -> ajaxValidarCliente();

}

/*=============================================
VALIDAR NO ELIMINAR MOTORISTA QUE TENGA FACTURAS
=============================================*/

if(isset($_POST["idMotoristaValidar"])){
    
    $valMot = new AjaxClientes();
    $valMot -> validarMot = $_POST["idMotoristaValidar"];
    $valMot -> ajaxValidarMotorista();

}