<?php 
class consagracoes extends ControllerCRUD{

	public $nome = array("Consagração","Consagrações");

	public $campos = array("nome"     	=> "text", 
						   "descricao"  => "Descrição");

	public $cor = "teal";

	public $icone = "tags";

	public $listar = array("nome", "descricao");

	//public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>