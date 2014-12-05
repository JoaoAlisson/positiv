<?php 
	if(isset($dados['retorno'])){
			$retornoJson['valido'] = $dados['retorno'][0];
			$retornoJson['mensagem'] = $dados['retorno'][1];
			$retornoJson['erros'] = isset($dados['retorno'][2]) ? $dados['retorno'][2] : "";

			$retornoJson = json_encode($retornoJson);
			echo $retornoJson;

	}else{
?>
<style>
@media (min-width: 460px) {
  .divForm {
    width:450px !important;
  }
}
</style>

<?php
	$html2->formulario('nMembro');
	$quantidade = count($dados['campos']);
	echo "<input hidden name='subNaoMembro'>
		  <div class=\"column\" style=\"width:auto;\">
		  	<div class=\"ui left aligned segment\" style=\"\">";

		  	inicio();

			$html2->campo("nome");		  		
			$this->html->campo("cargo", true, null, null, "cargo2");
			$this->html->campo("salario");

			fim();
			inicio();

			echo "<br><script type=\"text/javascript\">$(document).ready(function(){ $('.ui.checkbox').checkbox(); });</script>
				<div class='ui checkbox'>
				<input name='inss' type='checkbox'>
				<label><strong>Calcular INSS</strong></label>
				</div><br><br>";		  
		  $this->html->campo("descricao");

	echo "</div></div><br>";

	if($quantidade <= 6)
		echo "<div class=\"column divForm\" style=\"width:auto; max-width: 450px;\">
				<div class=\"ui left aligned segment\" style=\"\">";
	else
		echo "<div class=\"column\" style=\"width:auto;\">
				<div class=\"ui left aligned segment\" style=\"\">";
	
	
	if($quantidade > 6)
		inicio();
	
	$metade = (int)($quantidade/2) + 1;
	$ultimo = $quantidade - 1;

	$cont = 0;

	foreach ($dados['campos'] as $chave => $campo) {
		if($campo != "nome"){
			$html2->campo($chave);
			$cont++;
			if($cont == $metade && $quantidade > 6){
				fim();
				inicio();
			}	
		}
	}
	
	if($quantidade > 6)
		fim();

	echo "<div style='clear: both;'>";
	$this->html->submeter(null , "cadastrar/ab:2", null, null, null, "nMembro"); 
	echo "</div>";
	fim();
	echo "</div>";
		$this->html->formularioFim(); 
}?>
