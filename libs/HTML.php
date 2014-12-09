<?php
class HTML{

	private $tamanhoMaximoCampos = "450";
	private $tamanhoMinimoCampos = "300";

	private $cidadesEstados;
	private $cidades;
	private $estados;
	private $estado = "";
	private $tabindex = 0;
	private $banco;
	public $menu = array();
	public $subMenu;

	public $dados;
	function __construct(&$informacoes){
		$this->dados = &$informacoes;
	}
	
	private function getBanco(){
		if($this->banco == null)
			$this->banco = new Database();
		return $this->banco;
	}

	private function getTabindex($key = true){
		$this->tabindex = $this->tabindex + 1;
		if($key)
			return "onkeypress=\"enterSubmit(event);\"";
		else
			return "";
	}

	private function retornaCampo($nome, $campo, $id = ""){
		$id = ($id == "") ? $nome : $id;
		echo "<div class=\"field\" id=\"campo_$id\">
			 <label>" . $this->dados['campos'][$nome] . "</label>";
		echo $campo;
		echo "</div>";			
	}

	function pegarCidades(){
		if($this->cidades == null){
				$this->cidadesEstados = new CidadesEstadosModel();
			$estado = $this->estado;
			$this->cidades = $this->cidadesEstados->pegarCidades($estado);
		}

		return $this->cidades;
	}

	function pegarEstados(){
		if($this->estados == null){
			if($this->cidadesEstados == null)
				$this->cidadesEstados = new CidadesEstadosModel();	
			$this->estados = $this->cidadesEstados->pegarEstados();
		}
		return $this->estados;
	}

	function campo($campo, $validar = true, $valor = null, $where = "", $id = ""){
		if(isset($this->dados['tipos'][$campo])){
			if(is_array($this->dados['tipos'][$campo])){
				if(isset($this->dados['tipos'][$campo]["relacao"]))
					$this->chaveEstrangeira($campo, $validar, $valor, $this->dados['tipos'][$campo], $where, $id);
				else
					$this->enum($campo, $validar, $valor, $this->dados['tipos'][$campo]);
			}else{
				$funcao = "campo".ucfirst($this->dados['tipos'][$campo]);
				$this->{$funcao}($campo, $valor, $validar, $id);
			}
		}else{
			$this->campoTexto($campo, $valor, $validar);
		}
	}

	function submeter($controller = null, $view = null, $nomeBotao = null, $icone = null, $id = "", $idForm = ""){

		$controller = isset($controller) ? $controller : $this->dados['nomeController'];
		$view = isset($view) ? $view : $this->dados['nomeView'];
		$nomeBotao = isset($nomeBotao) ? $nomeBotao : "Cadastrar";
		$icone = isset($icone) ? $icone : "save";

		echo    "<div class=\"ui green vertical labeled icon submit button submeterForm\" ".$this->getTabindex(false)." style=\"margin-top:10px;\" onClick=\"submeter('$controller/', '$view', '$id', null, '$idForm');\">
					<i class=\"$icone icon\"></i>$nomeBotao
				</div>";
	}

	function formulario($id = ""){
		echo "<script type=\"text/javascript\">$(document).ready(function(){ $('.ui.selection.dropdown').dropdown(); $('.ui.dropdown').dropdown() });</script>
		<form action=\"\" enctype=\"multipart/form-data\" class=\"ui form formulario\" id='$id' method=\"POST\">";
	}

	function formularioFim(){
		echo "</form>";
	}

	function menuPrincialItem($nome = "", $controller = "", $view = "", $icone = "", $cor = "", $normal = false){
		$active = "";
		if(!is_array($controller)){
			if($this->dados['nomeController']  == "index")
				$active = ($controller == "" || $controller == "index") ? "active" : "";
			else
				$active = ($this->dados['nomeController'] == $controller) ? "active" : "";
			$controller = ($controller != "") ? $controller."/" : "";
		}else{
			if(in_array($this->dados['nomeController'], $controller))
				$active = "active";

			$controller = $controller[0]."/";
		}

		
		$invertido = "inverted";
		if($normal)
			$invertido = "";

		echo "<a class=\"$cor $active item menuprin esconder\" id=\"menu_$nome\" href=\"".URL."$controller$view\" onClick=\"navegacao('$controller','$view', '$nome')\" style=\"width:100px; padding-left:0px; padding-right:0px;\">
  				<i class=\"circular $cor $invertido big $icone icon\"></i>$nome
			  </a>";
	}

	function subMenu(){
		$this->subMenuCor();
	}

	function subMenuCor($cor = "", $invertido = "inverted"){
		$corFinal = (isset($this->dados['cor']) && $this->dados['cor'] != "") ? $this->dados['cor'] : $cor;
		echo "<div class=\"ui column center aligned grid esconder\" style=\"width: auto;\">
  				<div class=\"column\"  style=\"width: auto;\">
   					<div class=\"ui $corFinal $invertido fluid menu\" style=\"width: auto;\">";
	}

	function subMenuItem($nome, $controller = "", $view = "", $icone = ""){
		$active = "";
		if($this->dados['nomeController'] == $controller && $this->dados['nomeView'] == $view)
			$active = "active";
		$imgIcone = ($icone != "") ? "<i class=\"$icone icon\"></i>" : "";
		$controller .= ($controller != "") ? "/" : "";
		echo "<a class=\"item submenu $active\" href=\"".URL."$controller$view\" id=\"subMenu_$nome\" style=\"min-width:120px;\" onClick=\"navegacaoSub('$controller','$view', '$nome'); evento(event);\">$imgIcone$nome</a>";
	}

//<<--------------- CAMPOS DE INPUT -------------------->>

	function campoNome($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";

		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}

		$incone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "user";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido nome' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs; limpaNome('$campo');\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$incone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		if(!$validar)
			$incluir = "<br>".$incluir;
		$this->retornaCampo($campo, $incluir);
	}

	function campoLogin($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";

		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		
		$incone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "user";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido login soLetras' ".$this->getTabindex()." value='$valor' style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs; \" maxlength='25' onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$incone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
	}

	function campoSenha($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";

		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		
		$incone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "lock";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "**********";
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='password' class='$requerido senha' ".$this->getTabindex()." value='$valor' style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' maxlength='20' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs;\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$incone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
	}

	function campoTexto($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";

		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		
		$incone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "pencil";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$incone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
	}

	function campoImagem($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";

		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "";
		}

		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "photo";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='file' name='$campo' id='input_$campo' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' class='$requerido imagem' onChange=\"prepareUpload('$campo'); validar('$campo');\">";	
		$incluir .= "<i class='$icone icon'></i>";	
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";			
		$this->retornaCampo($campo, $incluir);
	}

	function campoNumero($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		//$valor = '$valor';
		$valor = number_format($valor, 2, ',', '.');

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}

		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "";	
		$numero = ($icone == "") ? "0,9:" : ""; 			
		$placeholder = isset($dados['placeholders'][$campo]) ?  $dadosArray['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs numeroMask('input_$campo');\" onblur=\"$validacaoJs\"/>";		
		$incluir .= "<i class='$icone icon'>$numero</i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";
		$this->retornaCampo($campo, $incluir);	
	}

	function campoMoedaReal($campo, $valor = null, $validar){
		$this->campoTexto($campo);
	}

	function campoTextoLongo($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "pencil";	
		$placeholder = isset($dadosArray['placeholders'][$campo]) ?  $dadosArray['placeholders'][$campo] : "";

		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<textarea type='text' class='$requerido textoLongo' ".$this->getTabindex()." style='height:10px; max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' placeholder='$placeholder' name='$campo' $requerido>$valor</textarea>";	
		$incluir .= "<i class='$icone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
	}

	function campoData($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "empty calendar";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "__/__/____";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onfocus=\"datapick('input_$campo');\" onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs \" onfocusout=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";
		$this->retornaCampo($campo, $incluir);
	}	

	function campoHora($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<input type='text' class='$requerido data' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$this->retornaCampo($campo, $incluir);
	}

	function campoInteiro($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "";	
		$numeros = ($icone == "") ? "0-9:" : ""; 	
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs inteiroMask('input_$campo');\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'>$numeros</i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";
		$this->retornaCampo($campo, $incluir);
	}	

	function campoMoeda($campo, $valor = null, $validar, $id){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$id = ($id != "") ? $id : $campo;
		$validacaoJs = "";

		if($validar == false)
			$requerido =  "";			
		else
			$validacaoJs = "validar('$id');";
				 
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "dollar";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido data' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$id' placeholder='$placeholder' onfocus=\"moedaMask('input_$id')\" name='$id' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir, $id);
	}

	function campoEmail($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "mail outline";
	//	$arroba = ($icone == "") ? "@" : "";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";
		$this->retornaCampo($campo, $incluir);
	}

	function campoEmailOff($campo, $valor = null, $validar){
		$this->campoEmail($campo);
	}

	function campoTelefone($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "call";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "(__)____-____";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs telefoneMask('input_$campo');\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
	}

	function campoCpf($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "payment";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido cpf' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onfocus=\"cpfMask('input_$campo')\" onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
	}

	function campoSexo($campo, $valor = null, $validar){
		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}

		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "male";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";		

		$asterisco = "";
		if($requerido == "validarObrigatorio")
			$asterisco = "<div class='ui corner label'><i class='icon asterisk'></i></div>";

		$selecionado = "&nbsp;";
		$mostrarInconeMas = "";
		$mostrarInconeFem = "";
		$value = "";
		if($valor != null){
			$selecionado = ($valor == 1) ? "Masculino" : "Feminino";
			$mostrarInconeMas = ($valor != 1) ? "style='display:none;'" : "";
			$mostrarInconeFem = ($valor != 2) ? "style='display:none;'" : "";
			$value = "value=\"$valor\"";
		}else{
			$valor = "";
		} 



		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= " <div class=\"ui search dropdown selection\" id=\"select_$campo\" ".$this->getTabindex()." onmouseover=\"registraSelect('select_$campo');\">
				      <input type=\"hidden\" name='$campo' $value id='input_$campo' class='$requerido' style='min-width:50px;' onChange=\"$validacaoJs trocaImgSexo('$campo');\">
				      <i class='$icone icon disabled' $mostrarInconeMas id='masculino_$campo'></i><i class='female icon disabled' $mostrarInconeFem id='feminino_$campo'></i>
				      <div class=\"default text\" style='min-width:50px;' data-value=\"$valor\">$selecionado</div>
				      <i class=\"dropdown icon\"></i>
				      <div class=\"menu\">
				     	<div class=\"item\" data-value=\"\"></div>
				        <div class=\"item\" data-value=\"1\"><i class='male icon'></i>Masculino</div>
				        <div class=\"item\" data-value=\"2\"><i class='female icon'></i>Feminino</div>
				      </div>$asterisco
				      </div>";
		//$incluir .= "<i class='$icone icon'></i>";
		//if($requerido == "validarObrigatorio")
		//	$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);				
	}

	function campoEstado($campo, $valor = "", $validar){

		if($valor == "")
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$this->estado = $valor;

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}

		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "male";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";		

		$asterisco = "";
		if($requerido == "validarObrigatorio")
			$asterisco = "<div class='ui corner label'><i class='icon asterisk'></i></div>";

		$selecionado = "&nbsp;"; 
		$value = "";

		$estados = $this->pegarEstados();
		$opcoes = "";
		foreach ($estados as $key => $estado){
			$opcoes .= "<div class=\"item\" data-value=\"".$estado['id']."\">".$estado['estado']."</div>";
			if($valor == $estado['id']){
				$value = "value=\"$valor\"";
				$selecionado = $estado['estado'];
			}
		}

		$incluir = "<div class='ui corner labeled left  icon input'>";
		$incluir .= " <div class=\"ui search dropdown selection\" id=\"select_$campo\" ".$this->getTabindex()." onmouseover=\"registraSelect('select_$campo');\">
				      <input type=\"hidden\" name='$campo' $value id='input_$campo' class='$requerido' onChange=\"$validacaoJs; mudarCidade('$campo');\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;'>
				      <i class='triangle down icon disabled'></i>
				      <div class=\"text\" data-value=\"$valor\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;'>$selecionado</div>
				      <div class=\"menu\">
				      <div class=\"item\" data-value=\"\"></div>
				      $opcoes	
				      </div>$asterisco
				      </div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);				
	}

	function campoCidade($campo, $valor = "", $validar){
		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}

		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "male";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";		

		$asterisco = "";
		if($requerido == "validarObrigatorio")
			$asterisco = "<div class='ui corner label'><i class='icon asterisk'></i></div>";

		$selecionado = "";
		$value = "";
	

		$estados = $this->pegarCidades();
		$opcoes = "";
		if($valor != "" && $valor != "0"){
			$opcoes .= "<div class=\"item\" data-value=\"\"></div>";
			foreach ($estados as $key => $cidade){
				$opcoes .= "<div class=\"item\" data-value=\"".$cidade['id']."\">".$cidade['cidade']."</div>";
				if($valor == $cidade['id']){
					$value = "value=\"$valor\"";
					$selecionado = $cidade['cidade'];
				}			
			}
		}

		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<div class=\"ui search dropdown selection\" id=\"select_$campo\" ".$this->getTabindex()." onmouseover=\"registraSelect('select_$campo');\">
				      <input type=\"hidden\" name='$campo' $value id='input_$campo' class='$requerido' onChange=\"$validacaoJs;\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;'>
				      <i class='triangle down icon disabled'></i>
				      <div class=\"text\" data-value=\"$valor\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;'>$selecionado</div>
				      <div class=\"menu\" id='cidadeInput'>
				      $opcoes
				      </div>$asterisco
				      </div>";
		$incluir .= "</div>";
		$this->retornaCampo($campo, $incluir);				
	}

	function enum($campo, $validar, $valor = "", $valores){
		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}

		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "triangle down";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";		

		$asterisco = "";
		if($requerido == "validarObrigatorio")
			$asterisco = "<div class='ui corner label'><i class='icon asterisk'></i></div>";

		$selecionado = "";
		$value = "";
	

		//$estados = $this->pegarCidades();
		$opcoes = "<div class=\"item\" data-value=\"\"></div>";

		foreach ($valores as $key => $descricao){
			$val = $this->removeAcentos($descricao);
			$opcoes .= "<div class=\"item\" data-value=\"$val\">$descricao</div>";
			if($valor == $val){
				$value = "value=\"$valor\"";
				$selecionado = $descricao;
			}			
		}

		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<div class=\"ui search dropdown selection\" id=\"select_$campo\" ".$this->getTabindex()." onmouseover=\"registraSelect('select_$campo');\">
				      <input type=\"hidden\" name='$campo' $value id='input_$campo' class='$requerido' onChange=\"$validacaoJs;\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;'>
				      <i class='$icone icon disabled'></i>
				      <div class=\"text\" data-value=\"$valor\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;'>$selecionado</div>
				      <div class=\"menu\">$opcoes
				      </div>$asterisco
				      </div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);				
	}

	function chaveEstrangeira($campo, $validar, $valor = "", $informacoes, $where, $id = ""){
		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		$id = ($id == "") ? $campo : $id;
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$id');";
		}

		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "triangle down";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";		

		$asterisco = "";
		if($requerido == "validarObrigatorio")
			$asterisco = "<div class='ui corner label'><i class='icon asterisk'></i></div>";

		$selecionado = "";
		$value = "";
	
		$banco = $this->getBanco();
		$valores = $banco->pegarTodosGenerico($informacoes['model'], $where);
		$opcoes = "<div class=\"item\" data-value=\"\"></div>";

		foreach ($valores as $key => $descricao){
			$opcoes .= "<div class=\"item\" data-value=\"".  $descricao['id'] ."\">".$descricao[$informacoes['campo']]."</div>";
			if($valor == $descricao['id']){
				$value = "value=\"$valor\"";
				$selecionado = $descricao[$informacoes['campo']];
			}			
		}

		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<div class=\"ui search dropdown selection\" id=\"select_$id\" ".$this->getTabindex()." onmouseover=\"registraSelect('select_$id');\">
				      <input type=\"hidden\" name='$id' $value id='input_$id' class='$requerido' onChange=\"$validacaoJs;\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;'>
				      <i class='$icone icon disabled'></i>
				      <div class=\"text\" data-value=\"$valor\" style='max-width:".$this->tamanhoMaximoCampos."px; min-width:100px;'>$selecionado</div>
				      <div class=\"menu\">$opcoes
				      </div>$asterisco
				      </div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir, $id);				
	}


/*
	Esta função foi tirada do site: http://www.douglaspasqua.com/2013/09/17/removendo-acentuacao-no-php-utf-8/
 */
    public function removeAcentos($value){   
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";
                 
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        $value = strtr($value, $mapping);
                 
        return $value;
    }

	public function campoFacebook($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";

		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		
		$incone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "facebook";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui corner labeled left icon input'>";
		$incluir .= "<input type='text' class='$requerido facebook' value='$valor' ".$this->getTabindex()." style='max-width:".$this->tamanhoMaximoCampos."px; min-width:".$this->tamanhoMinimoCampos."px;' id='input_$campo' placeholder='$placeholder' name='$campo' onfocusout=\"$validacaoJs\">";
		$incluir .= "<i class='$incone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
	}   

	public function menu($nome, $controller, $view, $icone, $cor,$naoInvertido, $submenus){
		$array["nome"] = $nome;
		$array["controller"] = $controller;
		$array["view"] = $view;
		$array["icone"] = $icone;
		$array["cor"] = $cor;
		$array["subMenus"] = $submenus;
		$array["naoInvertido"] = $naoInvertido;

		array_push($this->menu, $array);
	}

	public function menuLateral(){
		foreach ($this->menu as $key => $item) {
			if($item["controller"] == CONTROLLER){
				$this->subMenu['itens'] = $item["subMenus"];
				$this->subMenu['cor'] = $item["cor"]; 	
			}
				
			$icone = ($item["icone"] != "") ? "<i class=\"".$item["icone"]." icon\"></i>" : "";
			echo "<div class=\"item escondeMenu\">
					<a onclick=\"navegacao('".$item["controller"]."/','". $item["view"]."', '". $item["nome"]."')\"><b>".$icone.$item["nome"]."</b></a>";
			echo 	"<div class=\"menu\">";
			foreach ($item["subMenus"] as $nomeSub => $submenu) {
				if($submenu[0] == CONTROLLER){
					$this->subMenu['itens'] = $item["subMenus"];
					$this->subMenu['cor'] = $item["cor"]; 
				}
				$icone = ($submenu[2] != "") ? "<i class=\"".$submenu[2]." icon\"></i>" : "";
				echo "<a class=\"item escondeMenu\" onclick=\"navegacao('".$submenu[0]."/','".$submenu[1]."', '".$item["nome"]."')\">".$icone.$nomeSub."</a>";
			}

			echo "</div></div>";
		}
	}
}
?>