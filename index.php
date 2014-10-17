<?php
	define('SEPARADOR', DIRECTORY_SEPARATOR);
	define('RAIZ', dirname(__FILE__));

    function meu_autoloader($classe) {
        include(RAIZ . SEPARADOR . "libs". SEPARADOR . $classe . ".php");
    }
    spl_autoload_register("meu_autoloader");

	require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'config.php';
	require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'tiposUsuarios.php';

	if(!defined('MODO_DESENVOLVIMENTO'))
		define('MODO_DESENVOLVIMENTO', false);

	if(!defined('DESENVOLVIMENTO_SEGURO'))
		define('DESENVOLVIMENTO_SEGURO', false);

	$app = new Bootstrap();
?>