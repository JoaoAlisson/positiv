<?php 
class ministerios extends ControllerCRUD{

	public $nome = array("Ministério","Ministérios");

	public $campos = array("nome"     	=> "Nome", 
						   "lider"		=> "Líder",
						   "integrantes"=> "Integrantes",
 						   "foto"   	=> "Foto",
						   "descricao"  => "Descrição");

	public $cor = "teal";

	public $icone = "list";


	public $listar = array("nome", "lider");

	public $filtros = array("nome", "lider");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;
}
?>