<?php 
class funcionarios extends ControllerCRUD{

	public $nome = array("Funcionário","Funcionários");

	public $campos = array("nome"     	=> "Nome", 
						   "membro"		=> "Membro",
						   "cargo"		=> "Cargo",
						   "salario"	=> "Salário",
						   "descricao"  => "Descrição");

	public $cor = "black";

	public $icone = "male";

	public $listar = array("nome", "membro", "cargo");

	public $filtros = array("nome", "membro", "cargo");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>