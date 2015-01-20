<?php 
class consagracoes extends ControllerCRUD{

	public $nome = array("Consagração","Consagrações");

	public $campos = array("nome"      => "Nome", 
						   "qtd"	   => "Quantidade",
						   "descricao" => "Descrição");

	public $inalteraveis = array("qtd");

	public $cor = "teal";

	public $icone = "tags";

	public $listar = array("nome", "qtd");

	//public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "igreja" => "tudo");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>