$(".tablas").DataTable({
	"deferRender": true,
    "pageLength": 10,
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

let inactivityTime = 0;
const inactivityLimit = 300; // 5 minutos (300 segundos)

function resetInactivityTimer() {
    inactivityTime = 0; // Reinicia el contador de inactividad al detectar una interacción
}

document.onmousemove = resetInactivityTimer;
document.onkeypress = resetInactivityTimer;

setInterval(function() {
    inactivityTime++;
    if (inactivityTime >= inactivityLimit) { // Si han pasado 5 minutos sin actividad
        location.reload(); // Recarga la página
    }
}, 1000); // Revisa cada segundo



$("#btnFirmarTodas").on("click", function() {
    
    let botones = $(".btnFirmarDte"); // Todos los botones de firmar
    let index = 0;

    function firmarSiguiente() {
        if (index >= botones.length) {
            swal({
                type: "success",
                title: "Todas las facturas han sido procesadas",
                showConfirmButton: true,
                confirmButtonText: "Cerrar"
            });
            return;
        }

        let boton = botones.eq(index);
        let idFacturaF = boton.attr("idFactura");

        var datos1 = new FormData();
        datos1.append("idFacturaF", idFacturaF);

        $.ajax({
            url: "ajax/facturas.ajax.php",
            method: "POST",
            data: datos1,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                if(respuesta == "si") {
                    console.log("Factura " + idFacturaF + " firmada correctamente");
                } else {
                    console.error("Error al firmar factura " + idFacturaF + ": " + respuesta);
                }

                index++;
                setTimeout(firmarSiguiente, 1000); // 1 segundo entre cada firma
            },
            error: function() {
                console.error("Error de conexión con la factura " + idFacturaF);
                index++;
                setTimeout(firmarSiguiente, 1000);
            }
        });
    }

    swal({
        title: '¿Firmar todas las facturas?',
        text: "Este proceso se hará de una en una automáticamente.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, firmar todas',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false
    }).then((result) => {
        if (result.value) {
            firmarSiguiente();
        }
    });
});

$("#btnEnviarTodasMH").on("click", function() {

    let botones = $(".btnSellarDte"); // Todos los botones de envío
    let index = 0;

    function enviarSiguiente() {
        if (index >= botones.length) {
            swal({
                type: "success",
                title: "Todas las facturas han sido procesadas",
                showConfirmButton: true,
                confirmButtonText: "Cerrar"
            });
            return;
        }

        let boton = botones.eq(index);
        let idFacturaS = boton.attr("idFactura");

        var datos = new FormData();
        datos.append("idFacturaS", idFacturaS);

        // Mostrar "Cargando..."
        swal({
            title: "Enviando factura " + idFacturaS,
            text: "Por favor espera...",
            icon: "info",
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });

        $.ajax({
            url: "ajax/facturas.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                if (respuesta == "si") {
                    console.log("Factura " + idFacturaS + " enviada correctamente");
                } else {
                    console.error("Error al enviar la factura " + idFacturaS + ": " + respuesta);
                }

                index++;
                setTimeout(enviarSiguiente, 1000); // Espera 1 segundo entre cada envío
            },
            error: function() {
                swal.close();
                console.error("Error de conexión con la factura " + idFacturaS);
                index++;
                setTimeout(enviarSiguiente, 1000);
            }
        });
    }

    swal({
        title: '¿Enviar todas las facturas a Hacienda?',
        text: "Este proceso se hará automáticamente, una por una.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, enviar todas',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false
    }).then((result) => {
        if (result.value) {
            enviarSiguiente();
        }
    });
});
