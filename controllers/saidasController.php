<?php 
class saidas extends ControllerCRUD{

	public $nome = array("Saída","Saídas");

	public $campos = array("saida"     => "Saída", 
						   "nota"      => "Número da Nota Fiscal/Cupom",
						   "categoria" => "Categoria",
						   "valor"	   => "Valor",
						   "vencimento"=> "Vencimento",
						   "pago"	   => "Pagamento",
						   "descricao" => "Descrição");

	public $cor = "orange";

	public $icone = "down";

	public $listar = array("saida", "valor");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;


	public function cadastrar(){
		parent::cadastrar();
		$this->renderizar("saidas/cadastrar");
	}

	public function editar(){
		parent::editar();
		$this->renderizar("saidas/editar");
	}	
}
?>