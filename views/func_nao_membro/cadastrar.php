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
	$html2->formulario();
	$quantidade = count($dados['campos']);
	echo "<div class=\"ui column center aligned grid\">";

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
		$html2->campo($chave);
		$cont++;
		if($cont == $metade && $quantidade > 6){
			fim();
			inicio();
		}		
	}
	
	if($quantidade > 6)
		fim();

	echo "<div style='clear: both;'>";
	$this->html->submeter(null , "cadastrar"); 
	echo "</div>";
	fim();
	echo "</div>";
		$this->html->formularioFim(); 
}?>
