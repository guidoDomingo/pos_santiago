

/*=============================================
EDITAR PROVEEDOR
=============================================*/

$(".tablasProveedor tbody").on("click", "button.btnEditarProveedor", function(){

	var idProveedor = $(this).attr("idProveedor");
	console.log(idProveedor);
	var datos = new FormData();
    datos.append("idProveedor", idProveedor);

     $.ajax({

      url:"ajax/proveedor.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
          
        console.log(respuesta);
           $("#edit_prov_nombre").val(respuesta["nombre"]);

           $("#edit_prov_ruc").val(respuesta["ruc"]);
           $("#idProveedor").val(respuesta["id"]);


      }

  })

})

/*=============================================
ELIMINAR PRODUCTO
=============================================*/

$(".tablaProductos tbody").on("click", "button.btnEliminarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	var codigo = $(this).attr("codigo");
	var imagen = $(this).attr("imagen");
	
	swal({

		title: '¿Está seguro de borrar el producto?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar producto!'
        }).then(function(result) {
        if (result.value) {

        	window.location = "index.php?ruta=productos&idProducto="+idProducto+"&imagen="+imagen+"&codigo="+codigo;

        }


	})

})