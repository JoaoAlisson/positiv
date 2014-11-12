<?php 
class entradas extends ControllerCRUD{

	public $nome = array("Entrada","Entradas");

	public $campos = array("entrada"   => "Entrada", 
						   "categoria" => "Categoria",
						   "valor"	   => "Valor",
						   "vencimento"=> "Vencimento",
						   "pago"	   => "Pagamento",						   
						   "descricao" => "Descrição");

	public $cor = "orange";

	public $icone = "up";

	public $listar = array("entrada", "valor");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;
}
?>