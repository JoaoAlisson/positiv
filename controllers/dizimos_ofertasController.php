<?php 
class dizimos_ofertas extends ControllerCRUD{

	public $nome = array("Dízimo - Oferta", "Dízimos - Ofertas");

	public $campos = array("tipo"	=> "Tipo",
						   "nome"   => "Ofertante/Dizimista", 
						   "membro" => "Membro",
						   "data"   => "Data",
						   "valor"	=> "Valor",					   
						   "obs" 	=> "Observação");

	public $cor = "orange";

	public $icone = "";

	public $filtros = array("membro", "tipo");

	public $listar = array("membro", "data", "tipo", "valor");

	public $regraUsuarios = array("Administrador" => "tudo", "financas" => "tudo");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;
}
?>