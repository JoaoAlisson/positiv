<?php 
class funcoes extends ControllerCRUD{

	public $nome = array("Função","Funções");

	public $campos = array("nome"     => "Função", 
						   "qtd"	  => "Quantidade",
						   "descricao"=> "Descrição");
	public $cor = "teal";

	public $inalteraveis = array("qtd");

	public $icone = "pin";

	public $listar = array("nome", "qtd");

	//public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>