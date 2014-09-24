<?php 
class Sessao{

	public static function iniciar(){
		@session_start();
	}

	public static function inserir($key, $value){
		$_SESSION[$key] = $value;
	}

	public static function pegar($key){
		if(isset($_SESSION[$key]))
			return $_SESSION[$key];
	}

	public static function destruir(){
		session_destroy();
	} 
}
?>