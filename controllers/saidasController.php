<?php 
class saidas extends ControllerCRUD{

	public $nome = array("Saída","Saídas");

	public $campos = array("saida"   => "Saída", 
						   "categoria" => "Categoria",
						   "valor"	   => "Valor",
						   "descricao" => "Descrição");

	public $cor = "orange";

	public $icone = "down";

	public $listar = array("saida", "valor");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>