<?php

	 class Conexion{

	 	static public function conectar(){

	 		$link = new PDO("mysql:host=localhost;dbname=unclzepr_posFactura"
	 						,"unclzepr_guido","ruizbenitezguido11");

	 		$link->exec("set names utf8");

	 		return $link;
	 	}

	}