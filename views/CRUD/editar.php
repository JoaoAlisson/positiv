<?php 
	if(isset($dados['retorno'])){
			$retornoJson['valido'] = $dados['retorno'][0];
			$retornoJson['mensagem'] = $dados['retorno'][1];
			$retornoJson['erros'] = isset($dados['retorno'][2]) ? $dados['retorno'][2] : "";

			$retornoJson = json_encode($retornoJson);
			echo $retornoJson;
	
	}else{
?>
<h2>Edidar <?php echo $dados['nome'][0];?></h2>
<?php 
	$this->html->formulario();
	echo "<input type='text' name='id' value='739' style='display:none;' />";	
	foreach ($dados['campos'] as $chave => $campo) {
		$this->html->campo($chave);
	}	
	$this->html->submeter(null , "editar", "salvar alteração"); 
	$this->html->formularioFim(); 
}?>