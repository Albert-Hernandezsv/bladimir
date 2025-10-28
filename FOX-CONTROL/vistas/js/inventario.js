/*=============================================
EDITAR CATEGORIA
=============================================*/

$(".tablas").on("click", ".btnEditarCategoria", function(){

	var idCategoria = $(this).attr("idCategoria");
	
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
			
			$("#editarNombreCategoria").val(respuesta["nombre"]);
			$("#editarDescripcionCategoria").val(respuesta["descripcion"]);
			$("#editarIdCategoria").val(respuesta["id"]);
		}

	});

})

/*=============================================
ELIMINAR CATEGORIA
=============================================*/
$(".tablas").on("click", ".btnEliminarCategoria", function(){

    var idCategoria = $(this).attr("idCategoria");
	var idCategoriaValidar = $(this).attr("idCategoria");
  
	var datos = new FormData();
	datos.append("idCategoriaValidar", idCategoriaValidar);

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
			 if (respuesta != false) {  // Si la respuesta contiene productos
				swal({
					title: 'No puedes eliminar una categoría que tenga productos',
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
					title: '¿Está seguro de borrar la categoría?',
					text: "¡Si no lo está puede cancelar la accíón!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  cancelButtonText: 'Cancelar',
					  confirmButtonText: 'Si, borrar categoría!'
				  }).then(function(result){
				
					if(result.value){
				
					  window.location = "index.php?ruta=inventario&idCategoriaEliminar="+idCategoria;
				
					}
				
				  })
			}
		}

	});
  
});

/*=============================================
TABLA KARDEX
=============================================*/
$(".tablaKardex").DataTable({
	"deferRender": true,
    "pageLength": 5,
	"columnDefs": [
        { "orderable": false, "targets": 0 } // Bloquear el ordenamiento de la primera columna
    ],
	"language": {

		"sProcessing":     "Procesando...",
		"sLengthMenu":     "Mostrar _MENU_ registros",
		"sZeroRecords":    "No se encontraron resultados",
		"sEmptyTable":     "Ningún dato disponible en esta tabla",
		"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
		"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
		"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		"sInfoPostFix":    "",
		"sSearch":         "Buscar:",
		"sUrl":            "",
		"sInfoThousands":  ",",
		"sLoadingRecords": "Cargando...",
		"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Último",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		},
		"oAria": {
			"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			"sSortDescending": ": Activar para ordenar la columna de manera descendente"
		}

	}

});

/*=============================================
GENERAR KARDEX
=============================================*/

$(document).ready(function () {
    var table = $('#tablaProductos').DataTable();  // Asegúrate de reemplazar #miTabla por el id de tu tabla

    // Manejar el checkbox "Seleccionar Todos"
    $('#seleccionarTodos').change(function () {
        var isChecked = $(this).prop('checked');  // Verifica si "Seleccionar Todos" está marcado
        var selectedIds = [];

        // Si "Seleccionar Todos" está marcado, agregar todos los productos de todas las páginas
        if (isChecked) {
            // Iterar sobre todos los checkboxes en todas las páginas (usando DataTables API)
            table.rows().every(function() {
                var checkbox = $(this.node()).find('.productoCheckbox');  // Encuentra el checkbox de cada fila
                checkbox.prop('checked', true);  // Marcar el checkbox
                selectedIds.push(checkbox.val());  // Guardar su valor en el arreglo
            });
        } else {
            // Si "Seleccionar Todos" está desmarcado, desmarcar todos los checkboxes de todas las páginas
            table.rows().every(function() {
                var checkbox = $(this.node()).find('.productoCheckbox');
                checkbox.prop('checked', false);
            });
        }

        // Guardar los ids seleccionados en el input oculto
        $('#productosSeleccionados').val(selectedIds.join(','));  // Juntar los ids con coma

        // Mostrar los valores en la consola (para pruebas)
        console.log($('#productosSeleccionados').val());
    });

    // Manejar selección individual
    $(document).on("change", ".productoCheckbox", function () {
        var id = $(this).val();
        var currentValue = $('#productosSeleccionados').val();

        if ($(this).is(":checked")) {
            if (currentValue) {
                $('#productosSeleccionados').val(currentValue + ',' + id);
            } else {
                $('#productosSeleccionados').val(id);
            }
        } else {
            var valoresArray = currentValue.split(',');
            valoresArray = valoresArray.filter(function(item) {
                return item !== id;
            });
            $('#productosSeleccionados').val(valoresArray.join(','));
        }

        console.log($('#productosSeleccionados').val());
    });
});
