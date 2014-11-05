<?php 
class cargos extends ControllerCRUD{

	public $nome = array("Cargo","Cargos");

	public $campos = array("nome"     	=> "Cargo", 
						   "salario"    => "Salário",
						   "descricao"  => "Descrição");

	public $cor = "teal";

	public $icone = "sitemap";

	public $listar = array("nome", "descricao");

	//public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>