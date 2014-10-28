<?php
class HTML{
	public $dados;
	function __construct(&$informacoes){
		$this->dados = &$informacoes;
	}

	private function retornaCampo($nome, $campo){
		echo "<div class=\"field\" id=\"campo_$nome\">
			 <label>" . $this->dados['campos'][$nome] . "</label>";
		echo $campo;
		echo "</div>";			
	}

	function campo($campo, $validar = true, $valor = null){
		if(isset($this->dados['tipos'][$campo])){
			$funcao = "campo".ucfirst($this->dados['tipos'][$campo]);
			$this->{$funcao}($campo, $valor, $validar);
		}else{
			$this->campoTexto($campo, $valor, $validar);
		}
	}

	function submeter($controller = null, $view = null, $nomeBotao = null, $icone = null){

		$controller = isset($controller) ? $controller : $this->dados['nomeController'];
		$view = isset($view) ? $view : $this->dados['nomeView'];
		$nomeBotao = isset($nomeBotao) ? $nomeBotao : "Cadastrar";
		$icone = isset($icone) ? $icone : "save";
		echo    "<div class=\"ui cortopo inverted vertical labeled icon submit button\" style=\"margin-top:10px;\" onClick=\"submeter('$controller/', '$view')\">
					<i class=\"$icone icon\"></i>$nomeBotao
				</div>";
	}

	function formulario(){
		echo "<form action=\"\" enctype=\"multipart/form-data\" class=\"ui form formulario\" method=\"POST\">";
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

		echo "<a class=\"$cor $active item menuprin\" id=\"menu_$nome\" onClick=\"navegacao('$controller','$view', '$nome')\" style=\"width:100px;\">
  				<i class=\"circular $cor $invertido big $icone icon\"></i>$nome
			  </a>";
	}

	function subMenu(){
		$this->subMenuCor();
	}

	function subMenuCor($cor = ""){
		echo "<div class=\"ui column center aligned grid\" style=\"width: auto;\">
  				<div class=\"column\"  style=\"width: auto;\">
   					<div class=\"ui $cor inverted fluid menu\" style=\"width: auto;\">";
	}

	function subMenuItem($nome, $controller = "", $view = "", $icone = ""){
		$active = "";
		if($this->dados['nomeController'] == $controller && $this->dados['nomeView'] == $view)
			$active = "active";
		$imgIcone = ($icone != "") ? "<i class=\"$icone icon\"></i>" : "";
		$controller .= ($controller != "") ? "/" : "";
		echo "<a class=\"item submenu $active\" id=\"subMenu_$nome\" style=\"min-width:120px;\" onClick=\"navegacaoSub('$controller','$view', '$nome');\">$imgIcone$nome</a>";
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
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido nome' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs; limpaNome('$campo');\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$incone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
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
		$incluir .= "<input type='text' class='$requerido login soLetras' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs; \" maxlength='25' onblur=\"$validacaoJs\">";
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
		$incluir .= "<input type='password' class='$requerido senha' value='$valor' id='input_$campo' maxlength='20' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs;\" onblur=\"$validacaoJs\">";
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
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
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
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='file' name='$campo' id='input_$campo' class='$requerido imagem' onChange=\"prepareUpload('$campo'); validar('$campo');\">";	
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
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs numeroMask('input_$campo');\" onblur=\"$validacaoJs\"/>";		
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

		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<textarea type='text' class='$requerido textoLongo' placeholder='$placeholder' name='$campo' $requerido>$valor</textarea>";	
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
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onfocus=\"datapick('input_$campo');\" onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs \" onfocusout=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'></i></div>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
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
		$incluir = "<input type='text' class='$requerido data' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$this->retornaCampo($campo, $incluir);
	}

	function campoInteiro($campo, $value = null, $validar){

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
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs inteiroMask('input_$campo');\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'>$numeros</i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";
		$this->retornaCampo($campo, $incluir);
	}	

	function campoMoeda($campo, $valor = null, $validar){

		if($valor == null)
			$valor = isset($this->dados['dados']['campos'][$campo]) ? $this->dados['dados']['campos'][$campo] : "";

		$requerido = in_array($campo, $this->dados['obrigatorios']) ? "validarObrigatorio" : "";
		$validacaoJs = "";
		if($validar == false){
			$requerido =  "";			
		}else{
			$validacaoJs = "validar('$campo');";
		}
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "dollar";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido data' value='$valor' id='input_$campo' placeholder='$placeholder' onfocus=\"moedaMask('input_$campo')\" name='$campo' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'></i>";
		if($requerido == "validarObrigatorio")
			$incluir .= "<div class='ui corner label'><i class='icon asterisk'></i></div>";
		$incluir .= "</div>";		
		$this->retornaCampo($campo, $incluir);
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
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "";
		$arroba = ($icone == "") ? "@" : "";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
		$incluir .= "<i class='$icone icon'>$arroba</i>";
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
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "phone";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "(__)____-____";
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onkeyup=\"$validacaoJs telefoneMask('input_$campo');\" onblur=\"$validacaoJs\">";
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
		$icone =  isset($this->dados['icones'][$campo]) ? $this->dados['icones'][$campo] : "basic id";
		$placeholder = isset($this->dados['placeholders'][$campo]) ?  $this->dados['placeholders'][$campo] : "";
		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= "<input type='text' class='$requerido cpf' value='$valor' id='input_$campo' placeholder='$placeholder' name='$campo' onfocus=\"cpfMask('input_$campo')\" onkeyup=\"$validacaoJs\" onblur=\"$validacaoJs\">";
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



		$incluir = "<div class='ui left labeled icon input'>";
		$incluir .= " <div class=\"ui dropdown selection\" onmouseover=\"$('.ui.selection.dropdown').dropdown();\">
				      <input type=\"hidden\" name='$campo' $value id='input_$campo' class='$requerido' onChange=\"$validacaoJs trocaImgSexo('$campo');\">
				      <i class='$icone icon disabled' $mostrarInconeMas id='masculino_$campo'></i><i class='female icon disabled' $mostrarInconeFem id='feminino_$campo'></i>
				      <div class=\"default text\" data-value=\"$valor\">$selecionado</div>
				      <i class=\"dropdown icon\"></i>
				      <div class=\"menu\">
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

}
?>