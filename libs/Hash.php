<?php
class Hash{

	public static function criar($senha, $chave){

		$contexto = hash_init('md5', HASH_HMAC, $chave);
		hash_update($contexto, $senha);

		return hash_final($contexto);
	} 
}
?>