<?php 
class patrimonio extends ControllerCRUD{

	public $nome = array("Patrimonio","Patrimonio");

	public $campos = array("codigo"    => "Código",
						   "nome" 	   => "Nome",
						   "descricao" => "Descrição",
						   "ministerio"=> "Ministério",
						   "aquisicao" => "Data de Aquisição",
						   "valor"	   => "Valor Unitário",
						   "quantidade"=> "Quantidade",
						   "situacao"  => "Situação",
						   "obs" => "Observação",);

	public $cor = "red";

	public $icone = "suitcase";

	public $listar = array("nome", "valor");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;
}
?>