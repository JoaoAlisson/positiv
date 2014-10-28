<?php 
class membros extends ControllerCRUD{

	public $nome = array("Membro","Membros");

	public $cor = "teal";

	public $icone = "users";

	public $campos = array("nome"     	=> "Nome", 
						   "cpf"		=> "CPF",
						   "sexo"		=> "Sexo",
 						   "foto"   	=> "Foto",
					       "nascimento" => "Data de Nascimento",
						   "descricao"  => "Descrição");

	public $icones = array("nome" => "user");

	public $placeholders = array("nome"     => "Isira seu nome", 
								 "descricao"=> "Insira uma descrição");

	public $listar = array("nome", "foto", "descricao");

	public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>