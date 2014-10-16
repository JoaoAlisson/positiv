<?php
    
    define('SEPARADOR', DIRECTORY_SEPARATOR);
    define('RAIZ', dirname(__FILE__));
    require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'config.php';
    require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'bancoAdm.php';

    mysql_connect( DB_HOST, DB_USER, DB_PASS);

    $bancoAdm = new BancoAdm();

    $bancoAdm->criarBanco();
    $bancoAdm->deletarTabela("teste");
    if($bancoAdm->existeTabela("teste"))
        echo "existe";
    else
        echo "naum";

?>

