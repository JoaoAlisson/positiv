<?php 
class categorias extends ControllerCRUD{

	public $nome = array("Categoria","Categorias");

	public $campos = array("nome" => "Nome", "tipo" => "Tipo");

	public $cor = "orange";

	public $icone = "sitemap";

	public $listar = array("nome");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>