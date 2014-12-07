<?php
function inicio(){
	echo "<div class=\"column divForm\" style=\"width:auto; max-width: 450px; float: left; margin: 10px;\">";
}

function fim(){
	echo "</div>";
}
?>
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
    $nomeSingular = "";
    if(isset($dados['nome'][0]))
      $nomeSingular = $dados['nome'][0];
    else
      $nomeSingular = $dados['nomeController'];
  ?>
<div style="width:100%; text-align:center; text-transform: uppercase;">
	<h2><?php if($dados['icone'] != "") echo "<i class=\"".$dados['icone']." icon\"></i>"; ?>Cadastrar <?php echo $nomeSingular;?></h2>
</div>


<?php
	$this->html->formulario();
	$quantidade = count($dados['campos']);
	echo "<div class=\"ui column center aligned grid\">";

	if($quantidade <= 6)
		echo "<div class=\"column divForm\" style=\"width:auto; max-width: 450px;\">
				<div class=\"ui left ".$dados['cor']." aligned segment\" style=\"\">";
	else
		echo "<div class=\"column\" style=\"width:auto;\">
				<div class=\"ui left ".$dados['cor']." aligned segment\" style=\"\">";
	
	
	if($quantidade > 6)
		inicio();
	
	$metade = (int)($quantidade/2) + 1;
	$ultimo = $quantidade - 1;

	$cont = 0;
	foreach ($dados['campos'] as $chave => $campo) {
		$this->html->campo($chave);
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
