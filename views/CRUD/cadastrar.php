<?php 
	if(isset($dados['retorno'])){
			$retornoJson['valido'] = $dados['retorno'][0];
			$retornoJson['mensagem'] = $dados['retorno'][1];
			$retornoJson['erros'] = isset($dados['retorno'][2]) ? $dados['retorno'][2] : "";

			$retornoJson = json_encode($retornoJson);
			echo $retornoJson;

	}else{
?>
<h2>Cadastrar <?php echo $dados['nome'][0];?></h2>
<?php 
	$this->html->formulario();
	foreach ($dados['campos'] as $chave => $campo) {
		$this->html->campo($chave);
	}	
	$this->html->submeter(null , "cadastrar"); 
	$this->html->formularioFim(); 

}?>