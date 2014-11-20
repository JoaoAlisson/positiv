<?php 
class patrimonio extends ControllerCRUD{

	public $nome = array("Patrimonio","Patrimonio");

	public $campos = array("codigo"    => "Código",
						   "cod_sistema"=> "Cod. Sist.",
						   "nome" 	   => "Nome",
						   "descricao" => "Descrição",
						   "ministerio"=> "Ministério",
						   "aquisicao" => "Data de Aquisição",
						   "valor"	   => "Valor Unitário",
						   "total"	   => "Total",
						   "quantidade"=> "Quantidade",
						   "situacao"  => "Situação",
						   "obs" 	   => "Observação",);

	public $cor = "red";

	public $icone = "suitcase";

	public $icones = array("codigo" => "barcode");

	public $filtros = array("ministerio", "situacao", "cod_sistema","codigo");

	public $listar = array("cod_sistema","codigo","nome", "situacao", "total");

	public $inalteraveis = array("total", "cod_sistema");	

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function index(){
		parent::index();
		$dados = $this->getDados();
		$dados['totalPatrimonio'] = $this->model->pegarTotal();

		$this->dados($dados);

		$this->renderizar("patrimonio/index");
	}
}
?>