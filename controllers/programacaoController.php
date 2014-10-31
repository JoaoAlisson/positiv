<?php 
class programacao extends ControllerCRUD{

	public $nome = array("Evento","Programação");

	public $cor = "blue";

	public $icone = "calendar";

	public $campos = array("nome"     	=> "Nome", 
						   "inicio"		=> "Início",
						   "fim"		=> "Fim",	
 						   "telefone"   => "Telefone do Responsável",
 						   "local"		=> "Local",
						   "descricao"	=> "Descrição");

	public $icones = array("nome" => "user");

	public $placeholders = array("nome"     => "Isira seu nome");

	public $listar = array("nome", "local", "inicio");

	public $filtros = array("nome");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>