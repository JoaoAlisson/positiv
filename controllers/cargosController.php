<?php 
class cargos extends ControllerCRUD{

	public $nome = array("Cargo","Cargos");

	public $campos = array("nome"     => "Cargo", 
						   "qtd"	  => "Quantidade",
						   "descricao"=> "Descrição");
	public $cor = "black";

	public $inalteraveis = array("qtd");

	public $icone = "sitemap";

	public $listar = array("nome", "qtd");

	//public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "funcionarios" => "tudo");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>