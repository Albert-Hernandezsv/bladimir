/*=============================================
SUBIENDO LA IMAGEN DEL PRODUCTO
=============================================*/

$(".nuevaImagenProducto").change(function(){

	var imagen = this.files[0];
	
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

  		$(".nuevaImagenProducto").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else{

  		var datosImagen = new FileReader;
  		datosImagen.readAsDataURL(imagen);

  		$(datosImagen).on("load", function(event){

  			var rutaImagen = event.target.result;

  			$(".previsualizarImagenProducto").attr("src", rutaImagen);

  		})

  	}
})

/*=============================================
EDITAR PRODUCTO
=============================================*/

$(".tablas").on("click", ".btnCodigoBarra", function(){

	var idProducto = $(this).attr("idProducto");
	window.location = "extensiones/TCPDF-main/examples/ver-codigoBarra.php?idProducto="+idProducto;
})


/*=============================================
EDITAR PRODUCTO
=============================================*/

$(".tablas").on("click", ".btnEditarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	
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
			
			$("#editarIdProducto").val(respuesta["id"]);
			$("#editarNombreProducto").val(respuesta["nombre"]);
			$("#editarDescripcionProducto").val(respuesta["descripcion"]);
			$("#editarNombreTipo").val(respuesta["tipo"]);
			$("#editarNombreTipo").html(respuesta["tipo"]);
			$("#editarUnidadMedida").val(respuesta["unidadMedida"]);
			$("#editarUnidadMedida").html(respuesta["unidadMedida"]);
			$("#editarCategoriaProducto").val(respuesta["categoria_id"]);
			$("#editarExentoIva").val(respuesta["exento_iva"]);
			$("#editarExentoIva").html(respuesta["exento_iva"]);
			/*=============================================
			MSOTRAR CATEGORIA
			=============================================*/


			var idCategoria = respuesta["categoria_id"];
			
			var datos = new FormData();
			datos.append("idCategoria", idCategoria);

			$.ajax({

				url:"ajax/categorias.ajax.php",
				method: "POST",
				data: datos,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function(respuesta){
					
					$("#editarCategoriaProducto").html(respuesta["nombre"]);
				}

			});
			

			$("#editarPrecioCompraProducto").val(respuesta["precio_compra"]);
			$("#editarGananciaProductoPorcentaje").val(((respuesta["precio_venta"] - respuesta["precio_compra"])/respuesta["precio_compra"])*100);
			$("#editarGananciaProductoMonto").val(respuesta["precio_venta"] - respuesta["precio_compra"]);
			$("#editarPrecioVentaProducto").val(respuesta["precio_venta"]);
			$("#editarFechaVencimiento").val(respuesta["fecha_vencimiento"]);
			if (respuesta["fecha_vencimiento"] == "0000-00-00") {
				$("#flexCheckDefault1").prop("checked", false);
				$(".fechaVencimiento1").attr("hidden", true);
				$("#editarFechaVencimiento").attr("hidden", true);
			} else {
				$("#flexCheckDefault1").prop("checked", true);
				$(".fechaVencimiento1").removeAttr("hidden");
				$("#editarFechaVencimiento").removeAttr("hidden");
			}
			
			$("#editarStockProducto").val(respuesta["stock"]);
			$("#editarCodigoProducto").val(respuesta["codigo"]);

			$("#imagenActualProducto").val(respuesta["imagen"]);
			if(respuesta["imagen"] != ""){

				$(".previsualizarEditarImagenProducto").attr("src", respuesta["imagen"]);

			}else{

				$(".previsualizarEditarImagenProducto").attr("src", "vistas/img/anonimo.jpg");

			}
		}

	});

})

/*=============================================
ELIMINAR PRODUCTO
=============================================*/
$(".tablas").on("click", ".btnEliminarProducto", function(){

    var idProducto = $(this).attr("idProducto");
	var idProductoValidar = $(this).attr("idProducto");
  
	var datos = new FormData();
	datos.append("idProducto", idProductoValidar);

	$.ajax({

		url:"ajax/productos.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			console.log(respuesta);
			 if (respuesta["stock"] > 0) {  // Si la respuesta contiene productos
				swal({
					title: 'No puedes eliminar un producto que tenga stock',
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
					title: '¿Está seguro de borrar el producto?',
					text: "¡Si no lo está puede cancelar la accíón!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  cancelButtonText: 'Cancelar',
					  confirmButtonText: 'Si, borrar producto!'
				  }).then(function(result){
				
					if(result.value){
				
					  window.location = "index.php?ruta=inventario&idProductoEliminar="+idProducto+"&codigoProductoEliminar="+respuesta["codigo"];
				
					}
				
				  })
			}
		}

	});

  });

/*=============================================
REVISAR SI EL PRODUCTO YA ESTÁ REGISTRADO
=============================================*/

$("#nuevoCodigoProducto").change(function(){

	$(".alert").remove();

	var codigo = $(this).val();

	var datos = new FormData();
	datos.append("validarCodigo", codigo);

	 $.ajax({
	    url:"ajax/productos.ajax.php",
	    method:"POST",
	    data: datos,
	    cache: false,
	    contentType: false,
	    processData: false,
	    dataType: "json",
	    success:function(respuesta){
	    	
	    	if(respuesta){

	    		$("#nuevoCodigoProducto").parent().after('<div class="alert alert-warning">Este codigo ya existe en la base de datos</div>');

	    		$("#nuevoCodigoProducto").val("");

	    	}

	    }

	})
})

/*=============================================
AGREGAR STOCK A PRODUCTO
=============================================*/

$(".tablas").on("click", ".btnAgregarStock", function(){

	var idProducto = $(this).attr("idProducto");
	
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
			
			$("#idProductoCantidadStock").val(respuesta["id"]);
			$("#cantidadStockActual").val(respuesta["stock"]);
			

		}

	});

})

/*=============================================
VER AGREGACIONES DE STOCK A CADA PRODUCTO
=============================================*/

$(".tablas").on("click", ".btnVerAgregacionesStock", function() {
    var idProducto = $(this).attr("idProducto");

    var datos = new FormData();
    datos.append("idProducto", idProducto);

    $.ajax({
        url: "ajax/stocks.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            console.log(response); // Verifica la estructura de la respuesta
            var tabla = '<table class="table table-bordered table-striped dt-responsive tablas" width="100%">';
            tabla += '<thead>';
            tabla += '<tr>';
            tabla += '<th style="width:10px">#</th>';
            tabla += '<th>Proveedor</th>';
            tabla += '<th>Cantidad</th>';
			tabla += '<th>Precio compra</th>';
			tabla += '<th>Precio venta</th>';
            tabla += '<th>Comentarios</th>';
            tabla += '<th>Fecha</th>';
            tabla += '</tr>';
            tabla += '</thead>';
            tabla += '<tbody>';

            // Verificar si response es un array
            if (Array.isArray(response)) {
                // Filtrar los elementos que coinciden con idProducto
                var registrosFiltrados = response.filter(function(item) {
                    // Asegúrate de comparar correctamente los tipos
                    return Number(item.id_producto) === Number(idProducto); // Convertir a número para comparación
                });

                // Iterar sobre los registros filtrados
                if (registrosFiltrados.length > 0) {
                    registrosFiltrados.forEach(function(item, index) {
                        var fechaOriginal = item.fecha;
                        var fechaFormateada = new Date(fechaOriginal).toLocaleString('es-ES', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });

                        tabla += '<tr>';
                        tabla += '<td>' + (index + 1) + '</td>';
                        tabla += '<td>' + item.proveedor + '</td>';
                        tabla += '<td>' + item.cantidad + '</td>';
						tabla += '<td>$' + item.precio_compra + '</td>';
						tabla += '<td>$' + item.precio_venta + '</td>';
                        tabla += '<td>' + item.comentarios + '</td>';
                        tabla += '<td>' + fechaFormateada + '</td>';
                        tabla += '</tr>';
                    });
                } else {
                    // Si no hay registros filtrados
                    tabla += '<tr><td colspan="5" class="text-center">No se encontraron registros.</td></tr>';
                }
            } else {
                // Si no es un array, maneja el caso como un solo objeto
                if (response.id_producto && Number(response.id_producto) === Number(idProducto)) {
                    var fechaOriginal = response.fecha;
                    var fechaFormateada = new Date(fechaOriginal).toLocaleString('es-ES', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });

                    tabla += '<tr>';
                    tabla += '<td>1</td>'; // Solo hay un registro
                    tabla += '<td>' + response.proveedor + '</td>';
                    tabla += '<td>' + response.cantidad + '</td>';
                    tabla += '<td>' + response.comentarios + '</td>';
                    tabla += '<td>' + fechaFormateada + '</td>';
                    tabla += '</tr>';
                } else {
                    // Si el ID no coincide
                    tabla += '<tr><td colspan="5" class="text-center">No se encontraron registros.</td></tr>';
                }
            }

            tabla += '</tbody>';
            tabla += '</table>';

            $("#tablaStocks").html(tabla);
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", status, error);
            $("#tablaStocks").html("<p>Error al cargar los datos.</p>");
        }
    });
});

/*=============================================
CHEQUEAR LA FECHA DE VENCIMIENTO EN CREAR
=============================================*/
$("#flexCheckDefault").change(function() {
	if ($(this).prop("checked")) {
		$(".fechaVencimiento").removeAttr("hidden");
		$(".nuevaFechaVencimiento").attr("required", true);
	} else {
		$(".fechaVencimiento").attr("hidden", true);
	}
});

/*=============================================
CHEQUEAR LA FECHA DE VENCIMIENTO EN EDITAR
=============================================*/
$("#flexCheckDefault1").change(function() {
	if ($(this).prop("checked")) {
		$(".fechaVencimiento1").removeAttr("hidden");
		$(".editarFechaVencimiento").attr("required", true);
	} else {
		$(".fechaVencimiento1").attr("hidden", true);
	}
});

/*=============================================
CALCULAR LOS MONTOS DE VENTA EN CREAR
=============================================*/
$("#nuevoPrecioCompraProducto").on("input", function() {
	calcularGanancia();
});

$("#nuevaGananciaProductoPorcentaje").on("input", function() {
	let porcentaje = $(this).val();
	let compra = $("#nuevoPrecioCompraProducto").val();
	$("#nuevaGananciaProductoMonto").val(compra*(porcentaje/100));
	calcularGanancia();
});

$("#nuevaGananciaProductoMonto").on("input", function() {
	let monto = $(this).val();
	let compra = $("#nuevoPrecioCompraProducto").val();
	$("#nuevaGananciaProductoPorcentaje").val((monto/compra)*100);
	calcularGanancia();
});

function calcularGanancia() {
    let compra = parseFloat($("#nuevoPrecioCompraProducto").val()) || 0;
    let monto = parseFloat($("#nuevaGananciaProductoMonto").val()) || 0;
    let total = compra / 1.13;
    let redondeado = total.toFixed(4); // devuelve "12.10" como string
    $("#nuevoPrecioVentaProducto").val(redondeado);
}


/*=============================================
CALCULAR LOS MONTOS DE VENTA EN EDITAR
=============================================*/
$("#editarPrecioCompraProducto").on("input", function() {
	calcularGanancia1();
});

$("#editarGananciaProductoPorcentaje").on("input", function() {
	let porcentaje = $(this).val();
	let compra = $("#editarPrecioCompraProducto").val();
	$("#editarGananciaProductoMonto").val(compra*(porcentaje/100));
	calcularGanancia1();
});

$("#editarGananciaProductoMonto").on("input", function() {
	let monto = $(this).val();
	let compra = $("#editarPrecioCompraProducto").val();
	$("#editarGananciaProductoPorcentaje").val((monto/compra)*100);
	calcularGanancia1();
});

function calcularGanancia1() {
    let compra = parseFloat($("#editarPrecioCompraProducto").val()) || 0;
    let monto = parseFloat($("#editarGananciaProductoMonto").val()) || 0;
    let total = compra/1.13;
	let redondeado = total.toFixed(4);
    $("#editarPrecioVentaProducto").val(redondeado);
}

/*=============================================
CALCULAR LOS MONTOS DE VENTA EN AGREGAR STOCK
=============================================*/
$("#nuevoPrecioCompraProducto1").on("input", function() {
	calcularGanancia2();
});

$("#nuevaGananciaProductoPorcentaje1").on("input", function() {
	let porcentaje = $(this).val();
	let compra = $("#nuevoPrecioCompraProducto1").val();
	$("#nuevaGananciaProductoMonto1").val(compra*(porcentaje/100));
	calcularGanancia2();
});

$("#nuevaGananciaProductoMonto1").on("input", function() {
	let monto = $(this).val();
	let compra = $("#nuevoPrecioCompraProducto1").val();
	$("#nuevaGananciaProductoPorcentaje1").val((monto/compra)*100);
	calcularGanancia2();
});

function calcularGanancia2() {
    let compra = parseFloat($("#nuevoPrecioCompraProducto1").val()) || 0;
    let monto = parseFloat($("#nuevaGananciaProductoMonto1").val()) || 0;
    let total = compra/1.13;
	let redondeado = total.toFixed(4);
    $("#nuevoPrecioVentaProducto1").val(redondeado);
}

/*=============================================
AGREGAR PRODUCTOS A LA ORDEN DE COMPRA
=============================================*/
$('.btnAgregarProductoOrden').click(function() {
    // Clona la plantilla y la añade al contenedor
    const nuevoProducto = $('.producto-orden-template').clone().removeClass('producto-orden-template d-none').show();

    // Añadir el atributo required a los campos de productos nuevos
    nuevoProducto.find('input[name="nuevoNombreProductoNuevo[]"]').prop('required', true);
    nuevoProducto.find('input[name="nuevoPrecioProductoNuevo[]"]').prop('required', true);

    // Agregar el nuevo producto al contenedor
    $('#productosOrdenContainer').append(nuevoProducto);
});


/*=============================================
QUITAR PRODUCTO DE LA ORDEN DE COMPRA
=============================================*/
$('#productosOrdenContainer').on('click', '.btnEliminarProductoOrden', function() {
	$(this).closest('.producto-orden-item').remove(); // Elimina el producto
	actualizarArreglaOrden();
});

/*=============================================
PRODUCTO VIEJO O NUEVO EN ORDEN DE COMPRAS
=============================================*/
$('#productosOrdenContainer').on('change', '.productoViejo', function() {
    const parentRow = $(this).closest('.row');
    var nombreProductoNuevo = parentRow.find('input[name="nuevoNombreProductoNuevo[]"]');
    var precioProductoNuevo = parentRow.find('input[name="nuevoPrecioProductoNuevo[]"]');
    var cantidadProductoNuevo = parentRow.find('input[name="nuevoCantidadProductoNuevo[]"]');

    var idProductoViejo = parentRow.find('select[name="nuevoIdProductoViejo[]"]');
    var cantidadProductoViejo = parentRow.find('input[name="nuevoCantidadProductoViejo[]"]');

    if ($(this).prop("checked")) {
        // Deshabilitar campos de producto nuevo y limpiar valores
        nombreProductoNuevo.prop("readonly", true).prop("required", false).prop("disabled", true).val('');
        precioProductoNuevo.prop("readonly", true).prop("required", false).prop("disabled", true).val('');
        cantidadProductoNuevo.prop("readonly", true).prop("required", false).prop("disabled", true).val('');

        // Habilitar campos de producto viejo
        idProductoViejo.prop("disabled", false).prop("required", true);
        cantidadProductoViejo.prop("readonly", false).prop("required", true);
    } else {
        // Habilitar campos de producto nuevo
        nombreProductoNuevo.prop("readonly", false).prop("required", true).prop("disabled", false);
        precioProductoNuevo.prop("readonly", false).prop("required", true).prop("disabled", false);
        cantidadProductoNuevo.prop("readonly", false).prop("required", true).prop("disabled", false);

        // Deshabilitar y limpiar campos de producto viejo
        idProductoViejo.prop("required", false).prop("disabled", true).val('');
        cantidadProductoViejo.prop("required", false).prop("readonly", true).val('');
    }

    // Verifica que la función existe antes de llamarla
    if (typeof actualizarArreglaOrden === "function") {
        actualizarArreglaOrden();
    }
});



$('#productosOrdenContainer').on('input', '#nuevoNombreProductoNuevo', function() {
	actualizarArreglaOrden();
})
$('#productosOrdenContainer').on('input', '#nuevoPrecioProductoNuevo', function() {
	actualizarArreglaOrden();
})
$('#productosOrdenContainer').on('input', '#nuevoCantidadProductoNuevo', function() {
	actualizarArreglaOrden();
})

$('#productosOrdenContainer').on('change', '#nuevoIdProductoViejo', function() {
	actualizarArreglaOrden();
})

$('#productosOrdenContainer').on('input', '#nuevoCantidadProductoViejo', function() {
	actualizarArreglaOrden();
})

function actualizarArreglaOrden() {
	let productos = []; // Array para almacenar los productos
	$('#productosOrdenContainer .row').each(function() {
		const parentRow = $(this).closest('.row');
		var nombreProductoNuevoInput = parentRow.find('input[name="nuevoNombreProductoNuevo[]"]');
		var precioProductoNuevoInput = parentRow.find('input[name="nuevoPrecioProductoNuevo[]"]');
		var cantidadProductoNuevoInput = parentRow.find('input[name="nuevoCantidadProductoNuevo[]"]');

		var idProductoViejoInput = $(this).find('select.nuevoIdProductoViejo');
		var cantidadProductoViejoInput = parentRow.find('input[name="nuevoCantidadProductoViejo[]"]');

		var nombreProductoNuevo = nombreProductoNuevoInput.val();
		var precioProductoNuevo = parseFloat(precioProductoNuevoInput.val()) || 0;
		var cantidadProductoNuevo = cantidadProductoNuevoInput.val();

		var idProductoViejo = idProductoViejoInput.find('option:selected').data('value') || '';
		var cantidadProductoViejo = cantidadProductoViejoInput.val();
		productos.push({
			nombreProductoNuevo: nombreProductoNuevo,
			precioProductoNuevo: precioProductoNuevo,
			cantidadProductoNuevo: cantidadProductoNuevo,
			idProductoViejo: idProductoViejo,
			cantidadProductoViejo: cantidadProductoViejo
		});
	})
	console.log(productos);
	$("#productosOrden").val(JSON.stringify(productos));
}

/*=============================================
VER ORDEN
=============================================*/
$(".tablas").on("click", ".btnVerOrden", function(){

    var idOrden = $(this).attr("idOrden");
  
    window.location = "extensiones/TCPDF-main/examples/imprimir-orden.php?idOrden="+idOrden;

});

/*=============================================
ELIMINAR ORDEN
=============================================*/
$(".tablas").on("click", ".btnEliminarOrden", function(){

    var idOrden = $(this).attr("idOrden");

	swal({
		title: '¿Está seguro de borrar la orden de compra?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, borrar orden de compra!'
	  }).then(function(result){
	
		if(result.value){
	
		  window.location = "index.php?ruta=ordenes-compra&idOrdenEliminar="+idOrden;
	
		}
	
	  })

  });