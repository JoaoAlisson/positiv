<?php 
class Sessao{

	public static function iniciar(){
		session_cache_expire(10);
		session_name(md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].CHAVE_GERAL));
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