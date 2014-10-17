<?php
/* 
    define('SEPARADOR', DIRECTORY_SEPARATOR);
    define('RAIZ', dirname(__FILE__));
    require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'config.php';
    require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'tiposCampos.php';
    require RAIZ . SEPARADOR . 'config' . SEPARADOR . 'bancoAdm.php';
    
    mysql_connect( DB_HOST, DB_USER, DB_PASS);

    $bancoAdm = new BancoAdm();

    $bancoAdm->criarBanco();
    $bancoAdm->deletarTabela("teste");

    echo "<br>";
    if($bancoAdm->existeCampo("novoCampo", "pstv_produtos"))
        echo "existe o campo";
    else
        echo "naum existe esse campo";

    $bancoAdm->alterarCampo("novoCampo", "pstv_produtos", "inteiro");



  $diretorio = dir($pasta);

  while($arquivo = $diretorio->read()){

  }
*/
?>

