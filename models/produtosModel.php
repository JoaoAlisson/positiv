<?php 
class produtosModel extends Model{
 
	public $tipos = array("nome"      => "texto",
						  "imagem"    => "imagem", 
					      "valor"     => "moeda",
						  "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "valor");

	public $validacoes = array("valor"  => "validarValor");

	public function validarValor($valor){
		if($valor > 5)
			return "ok";
		else
			return "O valor deve ser maior do que R$ 5,00";
	}

	public function opa(){
		return "Esta é uma mensagem do model diretamente para vc";
	}
}
?>