/*=============================================
EDITAR CLIENTE
=============================================*/

$(".tablas").on("click", ".btnEditarCliente", function(){
	console.log("aqui");
	var idCliente = $(this).attr("idCliente");
	
	var datos = new FormData();
	datos.append("idCliente", idCliente);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			
			$("#editarIdCliente").val(respuesta["id"]);
			$("#editarNombreCliente").val(respuesta["nombre"]);
			$("#editarDireccionCliente").val(respuesta["direccion"]);
			$("#editarCorreoCliente").val(respuesta["correo"]);
			$("#editarNITCliente").val(respuesta["NIT"]);
			$("#editarDUICliente").val(respuesta["DUI"]);
			$("#editarNRCCliente").val(respuesta["NRC"]);
			$("#editarDepartamentoCliente").val(respuesta["departamento"]);
			$("#editarDepartamentoCliente").html(respuesta["departamento"]);
			$("#editarMunicipioCliente").val(respuesta["municipio"]);
			$("#editarMunicipioCliente").html(respuesta["municipio"]);
			$("#editarNumeroCliente").val(respuesta["telefono"]);
			$("#editarContribu").val(respuesta["tipo_cliente"]);
			$("#editarContribu").html(respuesta["tipo_cliente"]);
			$("#editarCodActividad").val(respuesta["codActividad"]);
			$("#editarDescActividad").val(respuesta["descActividad"]);
			$("#editarPaisRecibir").val(respuesta["codPais"]);
			$("#editarPaisRecibir").html(respuesta["nombrePais"]);
			$("#editarTipoPersona").val(respuesta["tipoPersona"]);
			$("#editarTipoPersona").html(respuesta["tipoPersona"]);
		}

	});

})

/*=============================================
EDITAR MOTORISTA
=============================================*/

$(".tablas").on("click", ".btnEditarMotorista", function(){
	console.log("aqui");
	var idMotorista = $(this).attr("idMotorista");
	
	var datos = new FormData();
	datos.append("idMotorista", idMotorista);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			
			$("#editarIdMotorista").val(respuesta["id"]);
			$("#editarNombreMotorista").val(respuesta["nombre"]);
			$("#editarDuiMotorista").val(respuesta["duiMotorista"]);
			$("#editarPlacaMotorista").val(respuesta["placaMotorista"]);
		}

	});

})

/*=============================================
EDITAR PROVEEDOR
=============================================*/

$(".tablas").on("click", ".btnEditarProveedor", function(){
	console.log("aqui");
	var idProveedor = $(this).attr("idProveedor");
	
	var datos = new FormData();
	datos.append("idProveedor", idProveedor);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			
			$("#editarIdProveedor").val(respuesta["id"]);
			$("#editarNombreProveedor").val(respuesta["nombre"]);
			$("#editarNitProveedor").val(respuesta["nit"]);
			$("#editarNumeroProveedor").val(respuesta["telefono"]);
			$("#editarCorreoProveedor").val(respuesta["correo"]);
			$("#editarDireccionProveedor").val(respuesta["direccion"]);
			$("#editarCondicionProveedor").val(respuesta["condicion_pago"]);
		}

	});

})

/*=============================================
EDITAR COMPRA
=============================================*/

$(".tablas").on("click", ".btnEditarCompra", function(){
	
	var idCompra = $(this).attr("idCompra");
	
	var datos = new FormData();
	datos.append("idCompra", idCompra);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			
			$("#editarIdCompra").val(respuesta["id"]);
			$("#editarFechaCompra").val(respuesta["fecha"]);
			$("#editarclase_documentoComprat").val(respuesta["clase_documento"]);
			$("#editarclase_documentoComprat").html(respuesta["clase_documento"]);
			$("#editartipo_documentoComprat").val(respuesta["tipo_documento"]);
			$("#editartipo_documentoComprat").html(respuesta["tipo_documento"]);
			$("#editarnumero_documentoCompra").val(respuesta["numero_documento"]);
			$("#editarnit_nrcCompra").val(respuesta["nit_nrc"]);
			$("#editarnombre_proveedorCompra").val(respuesta["nombre_proveedor"]);
			$("#editarcompras_internas_exentasCompra").val(respuesta["compras_internas_exentas"]);
			$("#editarinternaciones_exentas_y_no_sujetasCompra").val(respuesta["internaciones_exentas_y_no_sujetas"]);
			$("#editarimportaciones_exentas_y_no_sujetasCompra").val(respuesta["importaciones_exentas_y_no_sujetas"]);
			$("#editarcompras_internas_gravadasCompra").val(respuesta["compras_internas_gravadas"]);
			$("#editarinternaciones_gravadas_de_bienesCompra").val(respuesta["internaciones_gravadas_de_bienes"]);
			$("#editarimportaciones_gravadas_de_bienesCompra").val(respuesta["importaciones_gravadas_de_bienes"]);
			$("#editarimportaciones_gravadas_de_serviciosCompra").val(respuesta["importaciones_gravadas_de_servicios"]);
			$("#editarcredito_fiscalCompra").val(respuesta["credito_fiscal"]);
			$("#editartotal_de_comprasCompra").val(respuesta["total_de_compras"]);
			$("#editardui_del_proveedorCompra").val(respuesta["dui_del_proveedor"]);
			$("#editartipo_de_operacionComprat").val(respuesta["tipo_de_operacion"]);
			$("#editartipo_de_operacionComprat").html(respuesta["tipo_de_operacion"]);
			$("#editarclasificacionComprat").val(respuesta["clasificacion"]);
			$("#editarclasificacionComprat").html(respuesta["clasificacion"]);
			$("#editarsectorComprat").val(respuesta["sector"]);
			$("#editarsectorComprat").html(respuesta["sector"]);
			$("#editartipoComprat").val(respuesta["tipo"]);
			$("#editartipoComprat").html(respuesta["tipo"]);
			$("#editaranexoCompra").val(respuesta["anexo"]);		}

	});

})

/*=============================================
ELIMINAR CCOMPRA
=============================================*/
$(".tablas").on("click", ".btnEliminarCompra", function(){

    var idCompra = $(this).attr("idCompra");

	swal({
		title: '¿Está seguro de borrar la compra?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Si, borrar compra!'
		}).then(function(result){
	
		if(result.value){
	
			window.location = "index.php?ruta=ver-compras&idCompraEliminar="+idCompra;
	
		}
	
		})

});

/*=============================================
ELIMINAR CLIENTE
=============================================*/
$(".tablas").on("click", ".btnEliminarCliente", function(){

    var idCliente = $(this).attr("idCliente");
	var idClienteValidar = $(this).attr("idCliente");

	var datos = new FormData();
	datos.append("idClienteValidar", idClienteValidar);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			console.log(respuesta);
			 if (respuesta != false) {  // Si la respuesta contiene productos
				swal({
					title: 'No puedes eliminar un cliente que tenga facturas',
					text: "¡Cancela la accíón!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  cancelButtonText: 'Cancelar',
					  confirmButtonText: 'Si, cancelar!'
				  }).then(function(result){
				
				  })			
			} else {
				swal({
					title: '¿Está seguro de borrar el cliente?',
					text: "¡Si no lo está puede cancelar la accíón!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						cancelButtonText: 'Cancelar',
						confirmButtonText: 'Si, borrar cliente!'
					}).then(function(result){
				
					if(result.value){
				
						window.location = "index.php?ruta=facturacion&idClienteEliminar="+idCliente;
				
					}
				
					})
			
			}
		}

	});

});

/*=============================================
ELIMINAR MOTORISTA
=============================================*/
$(".tablas").on("click", ".btnEliminarMotorista", function(){
	console.log("as");
    var idMotorista = $(this).attr("idMotorista");
	var idMotoristaValidar = $(this).attr("idMotorista");

	var datos = new FormData();
	datos.append("idMotoristaValidar", idMotoristaValidar);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			console.log(respuesta);
			 if (respuesta != false) {  // Si la respuesta contiene productos
				swal({
					title: 'No puedes eliminar un motorista que tenga facturas',
					text: "¡Cancela la accíón!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  cancelButtonText: 'Cancelar',
					  confirmButtonText: 'Si, cancelar!'
				  }).then(function(result){
				
				  })			
			} else {
				swal({
					title: '¿Está seguro de borrar el motorista?',
					text: "¡Si no lo está puede cancelar la accíón!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						cancelButtonText: 'Cancelar',
						confirmButtonText: 'Si, borrar motorista!'
					}).then(function(result){
				
					if(result.value){
				
						window.location = "index.php?ruta=facturacion&idMotoristaEliminar="+idMotorista;
				
					}
				
					})
			
			}
		}

	});

});

/*=============================================
ELIMINAR PROVEEDOR
=============================================*/
$(".tablas").on("click", ".btnEliminarProveedor", function(){

    var idProveedor = $(this).attr("idProveedor");
	
	swal({
		title: '¿Está seguro de borrar el proveedor?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Si, borrar proveedor!'
		}).then(function(result){
	
			if(result.value){
		
				window.location = "index.php?ruta=inventario&idProveedorEliminar="+idProveedor;
		
			}
		});

});

/*=============================================
EDITAR DATOS EMPRESARIALES
=============================================*/

$('.btnEditarEmpresarial').on('click', function(e) {
	
	var idEmpresa = 1;
	
	var datos = new FormData();
	datos.append("idEmpresa", idEmpresa);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){

			$("#editarNITEmpresa").val(respuesta["nit"]);
			$("#editarNRCEmpresa").val(respuesta["nrc"]);
			$("#editarPasswordPriEmpresa").val(respuesta["passwordPri"]);
			$("#editarContraDescuento").val(respuesta["contra_descuentos"]);
			$("#editarNombreEmpresa").val(respuesta["nombre"]);
			$("#editarCodigoActividadEmpresa").val(respuesta["codActividad"]);
			$("#editarActividadEmpresa").val(respuesta["desActividad"]);
			$("#editarEstablecimientoEmpresa").val(respuesta["tipoEstablecimiento"]);
			$("#editarDepartamentoEmpresa").val(respuesta["departamento"]);
			$("#editarMunicipioEmpresa").val(respuesta["municipio"]);
			$("#editarDepartamentoEmpresa").html(respuesta["departamento"]);
			$("#editarMunicipioEmpresa").html(respuesta["municipio"]);
			$("#editarDireccionEmpresa").val(respuesta["direccion"]);
			$("#editarNumeroEmpresa").val(respuesta["telefono"]);
			$("#editarCorreoEmpresa").val(respuesta["correo"]);
		}

	});

})

/*=============================================
EDITAR TICKET
=============================================*/

$('.editarTicket').on('click', function(e) {
	
	var idEmpresa = 1;
	
	var datos = new FormData();
	datos.append("idEmpresa", idEmpresa);

	$.ajax({

		url:"ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){

			$("#editarAnchoTicket").val(respuesta["ancho"]);			
		}

	});

})

/*=============================================
NCR NECESARIO PARA LOS 3 TIPOS DE CLIENTES QUE LO NECESITAN AL CREAR
=============================================*/

 // Detecta cambios en el campo tipo de contribuyente
 $('#nuevoTipoContribuyentes').change(function() {
	// Si el valor seleccionado no es "00", agrega required al campo NRC
	if ($(this).val() !== "00") {
		$('#nuevoNRCCliente').attr('required', 'required');
		$('#nuevoDUICliente').removeAttr('required');
	} else {
		// De lo contrario, remueve el atributo required
		$('#nuevoNRCCliente').removeAttr('required');
		$('#nuevoDUICliente').attr('required', 'required');
	}
});

/*=============================================
NCR NECESARIO PARA LOS 3 TIPOS DE CLIENTES QUE LO NECESITAN AL EDITAR
=============================================*/

 // Detecta cambios en el campo tipo de contribuyente
 $('#editarTipoContribuyentes').change(function() {
	// Si el valor seleccionado no es "00", agrega required al campo NRC
	if ($(this).val() !== "00") {
		$('#editarNRCCliente').attr('required', 'required');
	} else {
		// De lo contrario, remueve el atributo required
		$('#editarNRCCliente').removeAttr('required');
	}
});

/*=============================================
ENVIAR A ESCOGER FACTURA
=============================================*/

$(".tablas").on("click", ".btnEscogerFactura", function() {
	
	var idCliente = $(this).attr("idCliente");
	
	window.location = "index.php?ruta=escoger-factura&idClienteEscogerFactura="+idCliente;

})

/*=============================================
ENVIAR A ESCOGER FACTURA CONTINGENCIA
=============================================*/

$(".tablas").on("click", ".btnEscogerFacturaContingencia", function() {
	
	var idCliente = $(this).attr("idCliente");
	
	window.location = "index.php?ruta=escoger-factura-contingencia&idClienteEscogerFactura="+idCliente;

})