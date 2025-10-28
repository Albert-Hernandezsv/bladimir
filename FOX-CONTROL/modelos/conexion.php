<?php

class Conexion{

	static public function conectar(){

		$link = new PDO("mysql:host=localhost;dbname=fox_control",
			            "root",
			            "");

		$link->exec("set names utf8");

		return $link;

	}

	static public function conectar1(){
		$conectado = @fsockopen("www.google.com", 80); 
		if ($conectado) {
			fclose($conectado);
			$link = new PDO("mysql:host=srv942.hstgr.io;dbname=u633011188_fox",
			            "u633011188_fox_user",
			            "kbkP]5Vl");

			$link->exec("set names utf8");

			return $link;
		} else {
			echo ("No tienes internet");
		}
		
	}

}