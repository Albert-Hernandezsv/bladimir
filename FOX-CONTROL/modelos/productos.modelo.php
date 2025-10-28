<?php

require_once "conexion.php";

class ModeloProductos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/

	static public function mdlIngresarProducto($tabla, $datos){
		$pdo = Conexion::conectar();
		$stmt = $pdo->prepare("INSERT INTO $tabla(nombre, tipo, categoria_id, precio_compra, precio_venta, stock, descripcion, codigo, unidadMedida, imagen, exento_iva, fecha_vencimiento) VALUES (:nombre, :tipo, :categoria_id, :precio_compra, :precio_venta, :stock, :descripcion, :codigo, :unidadMedida, :imagen, :exento_iva, :fecha_vencimiento)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);
		$stmt->bindParam(":categoria_id", $datos["categoria_id"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":unidadMedida", $datos["unidadMedida"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":exento_iva", $datos["exento_iva"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);

		if($stmt->execute()){
			$idProducto = $pdo->lastInsertId();
			return $idProducto;
		} else{

			return "error";
		
		}

		$stmt->close();
		
		$stmt = null;

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/

	static public function mdlEditarProducto($tabla, $datos){
		
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, tipo = :tipo, categoria_id = :categoria_id, precio_compra = :precio_compra, precio_venta = :precio_venta, stock = :stock, descripcion = :descripcion, codigo = :codigo, unidadMedida = :unidadMedida, imagen = :imagen, exento_iva = :exento_iva, fecha_vencimiento = :fecha_vencimiento WHERE id = :id");

		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);
		$stmt -> bindParam(":categoria_id", $datos["categoria_id"], PDO::PARAM_INT);
		$stmt -> bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt -> bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
		$stmt -> bindParam(":stock", $datos["stock"], PDO::PARAM_INT);
		$stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt -> bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt -> bindParam(":unidadMedida", $datos["unidadMedida"], PDO::PARAM_STR);
		$stmt -> bindParam(":exento_iva", $datos["exento_iva"], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);
	

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
	
	/*=============================================
	BORRAR PRODUCTO
	=============================================*/

	static public function mdlBorrarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;


	}
	
	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $item2, $valor2){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
}