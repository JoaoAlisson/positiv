<?php 
class produtos extends ControllerCRUD{

	public $nome = array("Produto","Produtos");

	public $campos = array("nome"     => "Nome", 
						   "imagem"   => "Imagem",
					       "valor"    => "Valor",
						   "descricao"=> "Descrição");

	public $icones = array("nome" => "user");

	public $placeholders = array("nome"     => "Isira seu nome", 
								 "descricao"=> "Insira uma descrição");

	public $listar = array("nome", "valor", "descricao");

	public $filtros = array("nome", "valor");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;
	
	private $filtro1 = array("grupo", array("chaveEstr" => "grupo"));
	private $filtro2 = array("tipo" => array("doce", "salgado"));
	private $pesquisar = "nome";

}
?>