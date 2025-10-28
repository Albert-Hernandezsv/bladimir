/*=============================================
CARGAR LA TABLA DINÁMICA DE FACTURAS
=============================================*/

 //$.ajax({

 	//url: "ajax/datatable-facturas.ajax.php",
 	//success:function(respuesta){
		
 		//console.log("respuesta", respuesta);

 	//}

 //})


/*============================================
ELIMINAR CATEGORIA
=============================================*/
$(".tablas").on("click", ".btnEliminarFactura", function(){

	var idFactura = $(this).attr("idFactura");

	swal({
		title: '¿Está seguro de borrar la factura?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, borrar factura!'
	}).then(function(result){
	
		if(result.value){
	
		window.location = "index.php?ruta=facturacion&idFacturaEliminar="+idFactura;
	
		}
	
	})

});

/*=============================================
INICIAR SESIÓN EN MH
=============================================*/
$('.btnIniciarSesionMh').click(function() {

	
	var iniciarSesionMh = "";

	var datos = new FormData();
	datos.append("iniciarSesionMh", iniciarSesionMh);

	// Mostrar SweetAlert de "Cargando"
    swal({
        title: "Cargando...",
        text: "Por favor espera mientras se procesa tu solicitud.",
        icon: "info",
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
    });


	$.ajax({

		url:"ajax/facturas.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			console.log(respuesta);
			if (respuesta == "si") {
				swal({
					type: "success",
					title: "Inicio de sesión correcto",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {
							window.location = "facturacion";
						}
					})

			} else {
				swal({
					type: "error",
					title: "Error de inicio de sesión, intentar más tarde",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {
							window.location = "facturacion";
						}
					});
			}
		},
		error: function() {
            // Cerrar el SweetAlert de "Cargando" en caso de error
            swal.close();
            swal({
                icon: "error",
                title: "Error en la conexión",
                text: "No se pudo completar la solicitud, intenta nuevamente.",
                button: "Cerrar"
            });
        }

	});
});


/*=============================================
AGREGAR PRODUCTO A FACTURA
AGREGAR PRECIO SIN IVA A PRODUCTO A FACTURA
=============================================*/  
$(document).ready(function() {

	// Detectar el evento de cambio en el input
    $('#productosContainer').on('change', '.codigoProducto', function() {
        var codigoProducto = $(this).val().trim(); // Obtiene el código ingresado en minúsculas y lo limpia de espacios
        console.log('Código ingresado:', codigoProducto); // Verifica el código ingresado

		const parentRow = $(this).closest('.row');
        var select = parentRow.find('select[name="nuevoIdProductoFactura[]"]');
        var options = select.find('option');

        // Desmarcar cualquier opción previamente seleccionada
        select.val(""); 

        var encontrado = false; // Indicador de si se encontró una coincidencia
        options.each(function() {
            var codigoOption1 = $(this).data('codigo'); // Obtiene el código de la opción
            var codigoOption = String(codigoOption1);// Obtiene el código de la opción

            // Verifica que el código de la opción y el código ingresado no sean undefined o vacíos
            if (codigoOption && codigoProducto) {
                console.log('Código de producto:', codigoOption); // Verifica el código de cada opción

                // Si el código ingresado coincide con el de la opción, selecciona esa opción
                if(codigoOption === codigoProducto){
                    $(this).prop('selected', true); // Marca la opción como seleccionada
                    select.val($(this).val()); // Actualiza el valor del select
                    encontrado = true; // Marca que ya encontramos una coincidencia

					// Obtener el precio del producto seleccionado
					const selectedOption = select.find('option:selected');
					const idProducto = selectedOption.data('value');
					const precioVenta = selectedOption.data('precio');
					const codigo = selectedOption.data('codigo');
					const exentoIva= selectedOption.data('exento');

					// Buscar los campos en la misma fila
					
					const cantidadOriginal = parentRow.find('input[name="nuevaCantidadProductoFactura[]"]');
					const precioOriginalField = parentRow.find('input[name="nuevoPrecioProductoFacturaOriginal[]"]');
					const ivaField = parentRow.find('input[name="nuevoIvaProductoFactura[]"]');
					const totalField = parentRow.find('input[name="nuevoTotalProductoFacturaIndividual[]"]');
					const totalFieldSin = parentRow.find('input[name="nuevoTotalProductoFacturaIndividualSin[]"]');

					// Actualizar los campos de precio y total
					precioOriginalField.val(precioVenta);
					
					const precioConIva = (precioVenta * 1.13).toFixed(4); // Suponiendo un IVA del 13%
					const precioSinIva = (precioVenta).toFixed(4); // Suponiendo un IVA del 13%
					cantidadOriginal.val(1);
					if(exentoIva == "no"){
						ivaField.val(precioConIva);
						totalField.val(precioConIva);
					} else {
						ivaField.val(precioVenta);
						totalField.val(precioSinIva);
					}
					
					totalFieldSin.val(precioSinIva);

					// Actualizar el total de la factura
					actualizarTotalFactura();


                }
                
            }
        });

        // Si no se encuentra una coincidencia, el select vuelve a su estado original
        if (!encontrado) {
            select.val(""); // Deselecciona cualquier opción si no hay coincidencias
        }
    });

    var count = parseInt($("#contador").val(), 10);
	
    // Agregar un nuevo producto
    $('.btnAgregarProductoFactura').click(function() {
        if (count < 20) {
            // Clona la plantilla y la añade al contenedor
            // Clonando la plantilla de producto
			const nuevoProducto = $('.producto-template').clone().removeClass('producto-template').show();
			nuevoProducto.find('select.seleccionarProductoFactura').attr('required', true);
			$('#productosContainer').append(nuevoProducto);

            count++; // Incrementa el contador
        } else {
            alert('No puedes agregar más de 20 productos.');
        }
    });

	// Eliminar un producto al hacer clic en el botón de eliminación
    $('#productosContainer').on('click', '.btnEliminarProducto1', function() {
        $(this).closest('.producto-item').remove(); // Elimina el producto
        count--; // Decrementa el contador
		actualizarTotalFactura();
    });

    // Evento para cuando se selecciona un producto
    $('#productosContainer').on('change', '.seleccionarProductoFactura', function() {
        // Obtener el precio del producto seleccionado
        const selectedOption = $(this).find('option:selected');
		const idProducto = selectedOption.data('value');
        const precioVenta = selectedOption.data('precio');
		const codigo = selectedOption.data('codigo');
		const exentoIva= selectedOption.data('exento');

        // Buscar los campos en la misma fila
        const parentRow = $(this).closest('.row');
		const cantidadOriginal = parentRow.find('input[name="nuevaCantidadProductoFactura[]"]');
        const precioOriginalField = parentRow.find('input[name="nuevoPrecioProductoFacturaOriginal[]"]');
        const ivaField = parentRow.find('input[name="nuevoIvaProductoFactura[]"]');
        const totalField = parentRow.find('input[name="nuevoTotalProductoFacturaIndividual[]"]');
		const totalFieldSin = parentRow.find('input[name="nuevoTotalProductoFacturaIndividualSin[]"]');
		const codigoInput = parentRow.find('input[name="codigoProducto[]"]');

        // Actualizar los campos de precio y total
        precioOriginalField.val(precioVenta);
        
        const precioConIva = (precioVenta * 1.13).toFixed(4); // Suponiendo un IVA del 13%
		const precioSinIva = (precioVenta).toFixed(4); // Suponiendo un IVA del 13%
		cantidadOriginal.val(1);
		if(exentoIva == "no"){
			ivaField.val(precioConIva);
			totalField.val(precioConIva);
		} else {
			ivaField.val(precioVenta);
			totalField.val(precioSinIva);
		}
		totalFieldSin.val(precioSinIva);
		codigoInput.val(codigo);

		// Actualizar el total de la factura
		actualizarTotalFactura();
    });

	// Evento para cuando se cambia la cantidad del producto
	$('#productosContainer').on('change', '.nuevaCantidadProductoFactura', function() {
		let tipoDte = $('#tipoDte').val();
		console.log(tipoDte);
		if(tipoDte === "05"){
			// Actualizar el total de la factura
			actualizarTotalFactura();
		} else {
		// Buscar los campos en la misma fila
		const parentRow = $(this).closest('.row');
		const cantidad = parseFloat($(this).val(), 10);  // Convertir a número

		// Buscar el campo de select de producto dentro de la misma fila
		const productoSelect = parentRow.find('select.seleccionarProductoFactura');
		const selectedOption = productoSelect.find('option:selected');

		// Obtener los datos del producto seleccionado
		const idProducto = selectedOption.data('value');
		const exentoIva= selectedOption.data('exento');

		const precioOriginalField = parentRow.find('input[name="nuevoPrecioProductoFacturaOriginal[]"]');
		const ivaField = parentRow.find('input[name="nuevoIvaProductoFactura[]"]');
		const totalField = parentRow.find('input[name="nuevoTotalProductoFacturaIndividual[]"]');
		const totalFieldSin = parentRow.find('input[name="nuevoTotalProductoFacturaIndividualSin[]"]');


		// Obtener el precio de venta y calcular el total
		const precioVenta = parseFloat(precioOriginalField.val());

		var datos = new FormData();
		datos.append("idProducto", idProducto);

		$.ajax({

			url:"ajax/productos.ajax.php",
			method: "POST",
			data: datos,
			cache: false,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(respuesta){
				const stock = parseInt(respuesta["stock"], 10);  // Convertir a número
				
				console.log(cantidad, stock);  // Verifica en consola

				if (cantidad > stock) {
					// Volver a seleccionar el campo de cantidad
					const inputCantidad = parentRow.find('input[name="nuevaCantidadProductoFactura[]"]');
					// Cambiar el valor del input de cantidad
					inputCantidad.val(respuesta["stock"]);

					const cantidad = inputCantidad.val();
					const precioConIva = (precioVenta * 1.13).toFixed(4); // Calcular precio con IVA (13%)

					// Actualizar el campo de IVA y total
					ivaField.val(precioConIva);
					const total = (precioConIva * cantidad).toFixed(2);
					const totalSin = (precioVenta * cantidad).toFixed(2);
					totalField.val(total);
					totalFieldSin.val(totalSin);

					// Actualizar el total de la factura
					actualizarTotalFactura();
					
					swal({
						title: '¡El producto no tiene el stock solicitado!',
						text: "Escoja un stock menor",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						cancelButtonText: 'Cancelar',
						confirmButtonText: 'Seleccionar otra vez stock'
					}).then(function(result){
						
					})
				} else {
					const precioConIva = (precioVenta * 1.13).toFixed(4); // Calcular precio con IVA (13%)

					// Actualizar el campo de IVA y total
					
					const total = (precioConIva * cantidad).toFixed(2);
					const totalSin = (precioVenta * cantidad).toFixed(2);
					if(exentoIva == "no"){
						ivaField.val(precioConIva);
						totalField.val(total);
					} else {
						ivaField.val(precioVenta);
						totalField.val(totalSin);
					}
					totalFieldSin.val(totalSin);

					// Actualizar el total de la factura
					actualizarTotalFactura();
				}
			}

		});
		}
		
		
	});

	$('#productosContainer').on('input', '#contraDesbloqueo', function() {
		let valor = $(this).val();
		let inputContra = $(this); // Guardamos la referencia al input
		let parentRow = inputContra.closest('.row'); // Buscamos la fila donde está el input
	
		var idEmpresa = 1;
	
		var datos = new FormData();
		datos.append("idEmpresa", idEmpresa);
	
		$.ajax({
			url: "ajax/clientes.ajax.php",
			method: "POST",
			data: datos,
			cache: false,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(respuesta) {
				if (valor == respuesta["contra_descuentos"]) {
					// Solo afecta los inputs dentro de la fila específica
					parentRow.find('.descuentoItem').removeAttr("readonly");
					parentRow.find('.porcentajeDescuentoItem').removeAttr("readonly");
				} else {
					parentRow.find('.descuentoItem').attr("readonly", true);
					parentRow.find('.porcentajeDescuentoItem').attr("readonly", true);
				}
			}
		});
	});
	
	$('#productosContainer').on('click', '.btnEliminarAutorizacionDescuentos', function() {
        const parentRow = $(this).closest('.row');
		parentRow.find('#contraDesbloqueo').val("");
		parentRow.find('.descuentoItem').attr("readonly", true);
		parentRow.find('.porcentajeDescuentoItem').attr("readonly", true);
    });

	// Evento para cuando se cambia el total de descuento
	$('#productosContainer').on('input', '.descuentoItem', function() {

		const parentRow = $(this).closest('.row');
		
		const precioSinImpuestosInput = parentRow.find('input[name="nuevoPrecioProductoFacturaOriginal[]"]');
		const precioSinImpuestos = parseFloat(precioSinImpuestosInput.val()) || 0;

		const totalDescuentosItems = parentRow.find('input[name="descuentoItem[]"]');
		const totalDesc = parseFloat(totalDescuentosItems.val()) || 0;

		
		const desctInput = parentRow.find('input[name="porcentajeDescuento[]"]');
		

		
		const porcentaje = ((totalDesc / precioSinImpuestos)*100).toFixed(2);
		desctInput.val(porcentaje);
		
		// Actualizar el total de la factura
		actualizarTotalFactura();
		
	});

	// Evento para cuando se cambia el total de descuento
	$('#productosContainer').on('input', '.porcentajeDescuentoItem', function() {

		const parentRow = $(this).closest('.row');
		
		const precioSinImpuestosInput = parentRow.find('input[name="nuevoPrecioProductoFacturaOriginal[]"]');
		const precioSinImpuestos = parseFloat(precioSinImpuestosInput.val()) || 0;

		const porcentajeDescInput = parentRow.find('input[name="porcentajeDescuento[]"]');
		const porcentajeDesc = parseFloat(porcentajeDescInput.val()) || 0;

		
		const desctInput = parentRow.find('input[name="descuentoItem[]"]');

		
		const monto = (precioSinImpuestos * (porcentajeDesc / 100)).toFixed(2);
		desctInput.val(monto);
		
		// Actualizar el total de la factura
		actualizarTotalFactura();
		
	});

	// Función que suma los totales individuales, actualiza el total de la factura y guarda los datos en JSON
	function actualizarTotalFactura() {
		let tipoDte = $('#tipoDte').val();
		

		
			let totalFactura = 0;
			let totalFacturaSin = 0;
			let totalDescuento = 0;
			let totalDescuentoConIva = 0;
			let productos = []; // Array para almacenar los productos

			if(tipoDte != "06"){
				// Recorre cada fila de producto para obtener sus datos y calcular el total
					$('#productosContainer .row').each(function() {
						const productoSelect = $(this).find('select.seleccionarProductoFactura');
						const cantidadInput = $(this).find('input[name="nuevaCantidadProductoFactura[]"]');
						const precioSinImpuestosInput = $(this).find('input[name="nuevoPrecioProductoFacturaOriginal[]"]');
						const precioConIvaInput = $(this).find('input[name="nuevoIvaProductoFactura[]"]');
						const totalProductoInput = $(this).find('input[name="nuevoTotalProductoFacturaIndividual[]"]');
						const totalDescuentosItems = $(this).find('input[name="descuentoItem[]"]');

						// Obtenemos los datos de cada campo
						const idProducto = productoSelect.find('option:selected').data('value') || '';
						const codigo = productoSelect.find('option:selected').data('codigo') || '';
						const exento = productoSelect.find('option:selected').data('exento') || '';
						const cantidad = parseFloat(cantidadInput.val()) || 0;
						const precioSinImpuestos = parseFloat(precioSinImpuestosInput.val()) || 0;
						const precioConIva = parseFloat(precioConIvaInput.val()) || 0;
						const totalProducto = parseFloat(totalProductoInput.val()) || 0;
						const totalDesc = parseFloat(totalDescuentosItems.val()) || 0;
						console.log(cantidad);
						// Sumar al total de la factura
						const totalDes = totalDesc * cantidad;
						const totalDescuentoConIv = ((totalDesc * cantidad) * 0.13) + totalDes;

						

						if(exento == "no"){
							totalDescuento += totalDes;
							totalDescuentoConIva += totalDescuentoConIv;

							totalFacturaSin += (precioSinImpuestos * cantidad) - totalDes;
							totalFactura += (totalProducto) - totalDescuentoConIv;
							
						} else {
							totalDescuento += totalDes;
							totalDescuentoConIva += totalDes;

							totalFacturaSin += (precioSinImpuestos * cantidad) - totalDes;
							totalFactura += (precioSinImpuestos * cantidad) - totalDes;
						}
						
						

						// Agregar el producto al array en formato JSON
						if(exento == "no"){
							productos.push({
								idProducto: idProducto,
								codigo: codigo,
								cantidad: cantidad,
								precioSinImpuestos: precioSinImpuestos,
								precioConIva: precioConIva,
								totalProducto: totalProducto,
								descuento: totalDesc,
								descuentoConIva: ((totalDesc * 0.13) + totalDesc).toFixed(2)
							});
						} else {
							productos.push({
								idProducto: idProducto,
								codigo: codigo,
								cantidad: cantidad,
								precioSinImpuestos: precioSinImpuestos,
								precioConIva: precioConIva,
								totalProducto: totalProducto,
								descuento: totalDesc,
								descuentoConIva: totalDesc
							});
						}
						
				});

				let desIva = totalDescuentoConIva;
				let desIvaSin = totalDescuento;
				// Actualiza el total de la factura
				$('input[name="nuevoTotalFactura"]').val(totalFactura.toFixed(2));
				$('input[name="nuevoTotalFacturaSin"]').val(totalFacturaSin.toFixed(2));
				$('input[name="totalDescuento"]').val(desIva.toFixed(2));
				$('input[name="totalDescuentoSin"]').val(desIvaSin.toFixed(2));

				// Guardar el JSON en el input oculto
				console.log(productos);
				$("#productos").val(JSON.stringify(productos));
			} else {
				// Recorre cada fila de producto para obtener sus datos y calcular el total
					$('#productosContainer .row').each(function() {
						const productoSelect = $(this).find('select.seleccionarProductoFactura');
						const cantidadInput = $(this).find('input[name="nuevaCantidadProductoFactura[]"]');
						const precioSinImpuestosInput = $(this).find('input[name="nuevoPrecioProductoFacturaOriginal[]"]');
						const precioConIvaInput = $(this).find('input[name="nuevoIvaProductoFactura[]"]');
						const totalProductoInput = $(this).find('input[name="nuevoTotalProductoFacturaIndividual[]"]');
						const totalDescuentosItems = $(this).find('input[name="descuentoItem[]"]');
						const exento = productoSelect.find('option:selected').data('exento') || '';

						// Obtenemos los datos de cada campo
						const idProducto = productoSelect.find('option:selected').data('value') || '';
						const codigo = productoSelect.find('option:selected').data('codigo') || '';
						const cantidad = parseFloat(cantidadInput.val()) || 0;
						const precioSinImpuestos = parseFloat(precioSinImpuestosInput.val()) || 0;
						const precioConIva = parseFloat(precioConIvaInput.val()) || 0;
						const totalProducto = parseFloat(totalProductoInput.val()) || 0;
						const totalDesc = parseFloat(totalDescuentosItems.val()) || 0;

						// Sumar al total de la factura
						const totalDes = totalDesc * cantidad;
						const totalDescuentoConIv = ((totalDesc * cantidad) * 0.13) + totalDes;

						

						if(exento == "no"){
							totalDescuento += totalDes;
							totalDescuentoConIva += totalDescuentoConIv;

							totalFacturaSin += (precioSinImpuestos * cantidad) + totalDes;
							totalFactura += (totalProducto) + totalDescuentoConIv;
						} else {
							totalDescuento += totalDes;
							totalDescuentoConIva += totalDes;

							totalFacturaSin += (precioSinImpuestos * cantidad) + totalDes;
							totalFactura += (precioSinImpuestos * cantidad) + totalDescuentoConIv;
						}
						

						// Agregar el producto al array en formato JSON
						if(exento == "no"){
							productos.push({
								idProducto: idProducto,
								codigo: codigo,
								cantidad: cantidad,
								precioSinImpuestos: precioSinImpuestos,
								precioConIva: precioConIva,
								totalProducto: totalProducto,
								descuento: totalDesc,
								descuentoConIva: ((totalDesc * 0.13) + totalDesc).toFixed(2)
							});
						} else {
							productos.push({
								idProducto: idProducto,
								codigo: codigo,
								cantidad: cantidad,
								precioSinImpuestos: precioSinImpuestos,
								precioConIva: precioConIva,
								totalProducto: totalProducto,
								descuento: totalDesc,
								descuentoConIva: totalDesc
							});
						}
						
				});

				let desIva = totalDescuentoConIva;
				let desIvaSin = totalDescuento;
				// Actualiza el total de la factura
				$('input[name="nuevoTotalFactura"]').val(totalFactura.toFixed(2));
				$('input[name="nuevoTotalFacturaSin"]').val(totalFacturaSin.toFixed(2));
				$('input[name="totalDescuento"]').val(desIva.toFixed(2));
				$('input[name="totalDescuentoSin"]').val(desIvaSin.toFixed(2));

				// Guardar el JSON en el input oculto
				console.log(productos);
				$("#productos").val(JSON.stringify(productos));
			}
			
		
		
		
	}

	// Eventos para actualizar la factura y el JSON cuando se selecciona o cambia un producto
	$('#productosContainer').on('change', '.seleccionarProductoFactura, .nuevaCantidadProductoFactura', function() {
		actualizarTotalFactura();
	});


	// NO REPETIR PRODUCTO EN LA FACTURA
	$('#productosContainer').on('change', '.seleccionarProductoFactura', function() {
		const selectedOption = $(this).find('option:selected');
		const productoId = selectedOption.data('value');

		// Eliminar la opción seleccionada de los otros selects
		$('#productosContainer .seleccionarProductoFactura').not(this).find(`option[data-value="${productoId}"]`).remove();
	});

});

// Seleccionar los productos con scaner
$(document).keydown(function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Evita que el formulario se envíe al presionar Enter
		$('.btnAgregarProductoFactura').click(); // Simula el clic en el botón con id="miBoton"
		$('.codigoProducto').focus(); // Mueve el foco al input con id="miInput"
    }
});


/*=============================================
VER FACTURA
=============================================*/
$(".tablas").on("click", ".btnVerFactura", function(){

    var idFacturaEditar = $(this).attr("idFactura");
  
    window.location = "index.php?ruta=ver-factura&idFacturaEditar="+idFacturaEditar;

});

/*=============================================
ELIMINAR FACTURA
=============================================*/

$('.btnEliminarFactura').click(function() {
var idFactura = $(this).attr("idFactura");

	swal({
		title: '¿Está seguro de borrar la factura?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, borrar factura!'
	}).then(function(result){

		if(result.value){

		window.location = "index.php?ruta=facturacion&idFacturaEliminar="+idFactura;

		}

	})
});

/*=============================================
FIRMA FACTURA
=============================================*/
$(".tablas").on("click", ".btnFirmarDte", function(){
	
	var idFacturaF = $(this).attr("idFactura");
	
	var datos1 = new FormData();
	datos1.append("idFacturaF", idFacturaF);
	
	swal({
		title: '¿Está seguro de firma la factura?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, firmar factura!',
		allowOutsideClick: false
	}).then(function(result){

		if(result.value){
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos1,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					
					console.log(respuesta);

					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Factura firmada correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "facturacion";

									}
								})
					} else {
						swal({
							type: "error",
							title: "La factura no se pudo firmar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "facturacion";

									}
								})
					}
					
				}
				
		
			});
	
		}
	
	})
  
})

/*=============================================
FIRMA FACTURA CONTINGENCIA
=============================================*/
$(".tablas").on("click", ".btnFirmarDteContingencia", function(){
	
	var idFacturaF = $(this).attr("idFactura");
	
	var datos1 = new FormData();
	datos1.append("idFacturaF", idFacturaF);

	swal({
		title: '¿Está seguro de firma la factura?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, firmar factura!',
		allowOutsideClick: false
	}).then(function(result){

		if(result.value){
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos1,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					console.log(respuesta);

					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Factura firmada correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "facturacion-contingencia";

									}
								})
					} else {
						swal({
							type: "error",
							title: "La factura no se pudo firmar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "facturacion-contingencia";

									}
								})
					}
					
				}
				
		
			});
	
		}
	
	})
  
})

/*=============================================
ENVIAR FACTURA CONTINGENCIA A HACIENDA
=============================================*/
$(".tablas").on("click", ".btnSellarDteContingencia", function(){

	var idFacturaS = $(this).attr("idFactura");
	var datos = new FormData();
	datos.append("idFacturaS", idFacturaS);

	swal({
		title: '¿Está seguro de enviar a MH la factura?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, sellar factura!'
	}).then(function(result){

		
	
		if(result.value){
			// Mostrar SweetAlert de "Cargando"
			swal({
				title: "Cargando...",
				text: "Por favor espera mientras se procesa tu solicitud.",
				icon: "info",
				buttons: false, // Elimina cualquier botón
				closeOnClickOutside: false, // Desactiva cierre al hacer clic fuera del modal
				closeOnEsc: false, // No permite cerrar con la tecla Esc,
				allowOutsideClick: false
			});
			
			
			
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					console.log(respuesta);
					
					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Factura sellada correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							 allowOutsideClick: false
						}).then(function(result){
							if (result.value) {
								window.location = "facturacion-contingencia";
							}
						})
					} else {
						swal({
							type: "error",
							title: "La factura no se pudo sellar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
								if (result.value) {
									window.location = "facturacion-contingencia";
								}
							})
					}
				},
				error: function() {
					// Cerrar el SweetAlert de "Cargando" en caso de error
					swal.close();
					swal({
						icon: "error",
						title: "Error en la conexión",
						text: "No se pudo completar la solicitud, intenta nuevamente.",
						button: "Cerrar"
					});
				}
		
			});
	
		}
	
	})
  
})

/*=============================================
ENVIAR FACTURA A HACIENDA
=============================================*/
$(".tablas").on("click", ".btnSellarDte", function(){

	var idFacturaS = $(this).attr("idFactura");
	var datos = new FormData();
	datos.append("idFacturaS", idFacturaS);

	swal({
		title: '¿Está seguro de enviar a MH la factura?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, sellar factura!'
	}).then(function(result){

		
	
		if(result.value){
			// Mostrar SweetAlert de "Cargando"
			swal({
				title: "Cargando...",
				text: "Por favor espera mientras se procesa tu solicitud.",
				icon: "info",
				buttons: false, // Elimina cualquier botón
				closeOnClickOutside: false, // Desactiva cierre al hacer clic fuera del modal
				closeOnEsc: false, // No permite cerrar con la tecla Esc,
				allowOutsideClick: false
			});
			
			
			
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					console.log(respuesta);
					
					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Factura sellada correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							 allowOutsideClick: false
						}).then(function(result){
							if (result.value) {
								window.location = "facturacion";
							}
						})
					} else {
						swal({
							type: "error",
							title: "La factura no se pudo sellar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
								if (result.value) {
									window.location = "facturacion";
								}
							})
					}
				},
				error: function() {
					// Cerrar el SweetAlert de "Cargando" en caso de error
					swal.close();
					swal({
						icon: "error",
						title: "Error en la conexión",
						text: "No se pudo completar la solicitud, intenta nuevamente.",
						button: "Cerrar"
					});
				}
		
			});
	
		}
	
	})
  
})


/*=============================================
NOTA DE CRÉDITO A CCF
=============================================*/
$(".tablas").on("click", ".btnNotaCredito", function(){

	var idFactura = $(this).attr("idFactura");
	window.location = "index.php?ruta=crear-nota-credito&idFactura="+idFactura;
	
})

/*=============================================
NOTA DE DEBITO A CCF
=============================================*/
$(".tablas").on("click", ".btnNotaDebito", function(){

	var idFactura = $(this).attr("idFactura");
	window.location = "index.php?ruta=crear-nota-debito&idFactura="+idFactura;
	
})

/*=============================================
NOTA DE REMISIÓN
=============================================*/
$(".tablas").on("click", ".btnNotaRemision", function(){

	var idFactura = $(this).attr("idFactura");
	
	swal({
		title: '¿Está seguro de crear una nota de remisión de esta factura?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, crear factura!'
	}).then(function(result){

		if(result.value){
	
			window.location = "index.php?ruta=facturacion&idFacturaNotaRemision="+idFactura;		
	
		}
	
	})
	
})

/*=============================================
ANULAR DTE
=============================================*/
$(".tablas").on("click", ".btnEliminarFacturaHacienda", function(){

	var idFactura = $(this).attr("idFactura");

	swal({
		title: '¿Está seguro de invalidar este DTE?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, anular DTE!'
	}).then(function(result){

		if(result.value){
	
			window.location = "index.php?ruta=anular-dte&idFacturaAnularDte="+idFactura;		
	
		}
	
	})
	
})

/*=============================================
CANCELAR ANULACIÓN
=============================================*/

$(".tablas").on("click", ".btnCancelarAnulacion", function(){
	
	var idFactura = $(this).attr("idFactura");
	
		swal({
			title: '¿Está seguro de cancelar el DTE anulación?',
			text: "¡Si no lo está puede cancelar la accíón!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Si, cancelar anulación!'
		}).then(function(result){
	
			if(result.value){
	
			window.location = "index.php?ruta=contabilidad&idFacturaCancelarAnulacion="+idFactura;
	
			}
	
		})
});

/*=============================================
FIRMA ANULACION
=============================================*/
$(".tablas").on("click", ".btnFirmarDteAnulacion", function(){
	
	var idFacturaFA = $(this).attr("idFactura");
	
	var datos1 = new FormData();
	datos1.append("idFacturaFA", idFacturaFA);

	swal({
		title: '¿Está seguro de firma la anulación?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, firmar anulación!'
	}).then(function(result){

		if(result.value){
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos1,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					console.log(respuesta);

					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Anulación firmada correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "contabilidad";

									}
								})
					} else {
						swal({
							type: "error",
							title: "La anulación no se pudo firmar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "contabilidad";

									}
								})
					}
					
				}
				
		
			});
	
		}
	
	})
  
})

/*=============================================
ENVIAR ANULACION A HACIENDA
=============================================*/
$(".tablas").on("click", ".btnSellarDteAnulacion", function(){
	
	var idFacturaSA = $(this).attr("idFactura");
	
	var datos = new FormData();
	datos.append("idFacturaSA", idFacturaSA);

	swal({
		title: '¿Está seguro de enviar a MH la anulación?',
		text: "¡Si no lo está puede cancelar la acción!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, sellar anulación!'
	}).then(function(result){

		
	
		if(result.value){
			// Mostrar SweetAlert de "Cargando"
		swal({
			title: "Cargando...",
			text: "Por favor espera mientras se procesa tu solicitud.",
			icon: "info",
			buttons: false,
			closeOnClickOutside: false,
			closeOnEsc: false,
			allowOutsideClick: false
		});
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					console.log(respuesta);
					
					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Anulacion sellada correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
						}).then(function(result){
							if (result.value) {
								window.location = "facturacion";
							}
						})
					} else {
						swal({
							type: "error",
							title: "La anulación no se pudo sellar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
								if (result.value) {
									window.location = "facturacion";
								}
							})
					}
				},
				error: function() {
					// Cerrar el SweetAlert de "Cargando" en caso de error
					swal.close();
					swal({
						icon: "error",
						title: "Error en la conexión",
						text: "No se pudo completar la solicitud, intenta nuevamente.",
						button: "Cerrar"
					});
				}
		
			});
	
		}
	
	})
  
})

/*=============================================
CREAR EVENTO DE CONTINGENCIA
=============================================*/
$('.btnCrearEventoContingencia').click(function() {

	var idEventoContingencia = "Si";

	swal({
		title: '¿Ya terminaste de crear todas las facturas en modo contingencia?',
		text: "¡Si no puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, crear evento!'
	}).then(function(result){

		if(result.value){
	
			window.location = "index.php?ruta=facturacion-contingencia&contador="+idEventoContingencia;
	
		}
	
	})
	
})

/*=============================================
ELIMINAR EVENTO CONTINGENCIA
=============================================*/

$('.btnEliminarEventoContingencia').click(function() {
	var idEvento = $(this).attr("idEvento");
	
		swal({
			title: '¿Está seguro de borrar el evento de contingencia?',
			text: "¡Si no lo está puede cancelar la accíón!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Si, borrar evento!'
		}).then(function(result){
	
			if(result.value){
	
			window.location = "index.php?ruta=facturacion-contingencia&idEventoEliminar="+idEvento;
	
			}
	
		})
});

/*=============================================
FIRMA EVENTO CONTINGENCIA
=============================================*/
$(".tablas").on("click", ".btnFirmarEventoContingencia", function(){
	
	var idEvento = $(this).attr("idEvento");
	
	var datos1 = new FormData();
	datos1.append("idEvento", idEvento);

	swal({
		title: '¿Está seguro de firma el evento de contingencia?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, firmar evento!',
		allowOutsideClick: false
	}).then(function(result){

		if(result.value){
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos1,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					console.log(respuesta);

					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Evento firmado correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "facturacion-contingencia";

									}
								})
					} else {
						swal({
							type: "error",
							title: "El evento no se pudo firmar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
									if (result.value) {

									window.location = "facturacion-contingencia";

									}
								})
					}
					
				}
				
		
			});
	
		}
	
	})
  
})

/*=============================================
ENVIAR EVENTO CONTINGENCIA A HACIENDA
=============================================*/
$(".tablas").on("click", ".btnSellarEventoContingencia", function(){

	var idEventoH = $(this).attr("idEvento");
	var datos = new FormData();
	datos.append("idEventoH", idEventoH);

	swal({
		title: '¿Está seguro de enviar a MH el evento?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, sellar evento!'
	}).then(function(result){

		
	
		if(result.value){
			// Mostrar SweetAlert de "Cargando"
			swal({
				title: "Cargando...",
				text: "Por favor espera mientras se procesa tu solicitud.",
				icon: "info",
				buttons: false, // Elimina cualquier botón
				closeOnClickOutside: false, // Desactiva cierre al hacer clic fuera del modal
				closeOnEsc: false, // No permite cerrar con la tecla Esc,
				allowOutsideClick: false
			});
			
			
			
	
			$.ajax({

				url:"ajax/facturas.ajax.php",
				method: "POST",
				data: datos,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta) {
					console.log(respuesta);
					
					if(respuesta == "si"){
						swal({
							type: "success",
							title: "Evento sellado correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							 allowOutsideClick: false
						}).then(function(result){
							if (result.value) {
								window.location = "facturacion-contingencia";
							}
						})
					} else {
						swal({
							type: "error",
							title: "El evento no se pudo sellar"+respuesta,
							showConfirmButton: true,
							confirmButtonText: "Cerrar",
							allowOutsideClick: false
							}).then(function(result){
								if (result.value) {
									window.location = "facturacion-contingencia";
								}
							})
					}
				},
				error: function() {
					// Cerrar el SweetAlert de "Cargando" en caso de error
					swal.close();
					swal({
						icon: "error",
						title: "Error en la conexión",
						text: "No se pudo completar la solicitud, intenta nuevamente.",
						button: "Cerrar"
					});
				}
		
			});
	
		}
	
	})
  
})

/*=============================================
ENVIAR FACTURA A CORREO DE CLIENTE
=============================================*/

$('.btnEnviarFacturaCorreo').click(function() {
	var idFactura = $(this).attr("idFactura");
	
		swal({
			title: '¿Está seguro de enviar la factura al correo del cliente?',
			text: "Se enviará al correo registrado del cliente, si no es el correo cambialo en ¡facturación!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Si, enviar factura!'
		}).then(function(result){
	
			if(result.value){
	
			window.location = "index.php?ruta=ver-factura&idFacturaEditar="+idFactura+"&idFacturaEnviarCorreo="+idFactura;
	
			}
	
		})
});

/*=============================================
MOSTRAR VUELTO AL ABONAR
=============================================*/

$('#nuevoMontoAbono').on('input', function() {
    var abono = parseFloat($(this).val());  // Convierte el valor del input a número
	var dinero = parseFloat($('#nuevoMontoDinero').val());

    if (!isNaN(abono)) {
        var vuelto = dinero - abono;  // Realiza la resta
        $('#nuevoMontoVuelto').val(vuelto.toFixed(2));  // Muestra el vuelto con 2 decimales
    } else {
        // Si los valores no son números, puedes limpiar el campo de vuelto o manejar el error
        $('#nuevoMontoVuelto').val('');
    }

});

$('#nuevoMontoDinero').on('input', function() {
    var abono = parseFloat($('#nuevoMontoAbono').val())
	var dinero = parseFloat($(this).val());

    if (!isNaN(abono)) {
        var vuelto = dinero - abono;  // Realiza la resta
        $('#nuevoMontoVuelto').val(vuelto.toFixed(2));  // Muestra el vuelto con 2 decimales
    } else {
        // Si los valores no son números, puedes limpiar el campo de vuelto o manejar el error
        $('#nuevoMontoVuelto').val('');
    }

});

/*=============================================
CONTRASEÑA DE APROBAR CORTE DE CAJA
=============================================*/

$('#contraCorte').on('input',  function() {
	
	let valor = $(this).val();

	var idEmpresa = 1;

	var datos = new FormData();
	datos.append("idEmpresa", idEmpresa);

	$.ajax({
		url: "ajax/clientes.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta) {
			if (valor == respuesta["contra_descuentos"]) {
				// Solo afecta los inputs dentro de la fila específica
				$('#btnGuardarAproba').removeAttr("disabled");
			} else {
				$('#btnGuardarAproba').attr("disabled", true);
			}
		}
	});
});