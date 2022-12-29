<?php

require_once "../controladores/proveedor.controlador.php";
require_once "../modelos/proveedor.modelo.php";

class AjaxProveedor{

  /*=============================================
  EDITAR PROVEEDOR
  =============================================*/ 

  public $idProveedor;

  public function ajaxEditarProveedor(){
      
      $item = "id";
      $valor = $this->idProveedor;
  
      $respuesta = ControladorProveedor::ctrMostrarProveedor($item,$valor);

      echo json_encode($respuesta);

  }

}


/*=============================================
GENERAR CÓDIGO A PARTIR DE ID CATEGORIA
=============================================*/	

if(isset($_POST["idProveedor"])){

	$editarProveedor = new AjaxProveedor();
	$editarProveedor -> idProveedor = $_POST["idProveedor"];
	$editarProveedor -> ajaxEditarProveedor();

}