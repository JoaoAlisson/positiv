<?php 
class categorias extends ControllerCRUD{

	public $nome = array("Categoria","Categorias");

	public $campos = array("nome" => "Nome", "tipo" => "Tipo");

	public $cor = "orange";

	public $icone = "bookmark";

	public $listar = array("nome", "tipo");

	public $regraUsuarios = array("Administrador" => "tudo", "financas" => "tudo");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>