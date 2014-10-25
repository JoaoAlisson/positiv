<?php 
class membros extends ControllerCRUD{

	public $nome = array("Membro","Membros");

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
	
	//private $filtro1 = array("grupo", array("chaveEstr" => "grupo"));
	//private $filtro2 = array("tipo" => array("doce", "salgado"));
	private $pesquisar = "nome";

}
?>