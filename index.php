<?php
	define('SEPARADOR', DIRECTORY_SEPARATOR);
	define('RAIZ', dirname(__FILE__));

    function meu_autoloader($classe) {
        include(RAIZ . SEPARADOR . "libs". SEPARADOR . $classe . ".php");
    }
    spl_autoload_register("meu_autoloader");

	require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'config.php';
	require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'tiposUsuarios.php';

	$app = new Bootstrap();
?>