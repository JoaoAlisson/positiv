<?php 
class patrimonio extends ControllerCRUD{

	public $nome = array("Patrimônio","Patrimônio");

	public $campos = array("codigo"    => "Código",
						   "cod"	   => "Nº",
						   "nota"	   => "Número da Nota Fiscal/Cupom",
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

	public $filtros = array("ministerio", "situacao", "cod","codigo");

	public $listar = array("cod","codigo","nome", "situacao", "total");

	public $inalteraveis = array("total", "cod");	

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function index() {
		parent::index();
		$dados = $this->getDados();

		if(!isset($_POST['filtro']))
			$dados['totalPatrimonio'] = $this->model->pegarTotal();

		$this->dados($dados);

		$this->renderizar("patrimonio/index");
	}

	public function etiqueta() {

		$cod = "";
		if(isset($this->GET['cod']))
			$cod = (int)$this->GET['cod'];
		
		if($cod == "")
			header('location: '. URL . $this->nomeController());

		$informacoes = $this->model->pegarInformacao($cod);

		if(empty($informacoes))
			header('location: '. URL . $this->nomeController());

		$dados['cod'] 	 = $cod;
		$dados['qtd']    = $informacoes['quantidade'];
		$dados['codigo'] = $informacoes['codigo'];

		$this->usarLayout(false);
		$this->dados($dados);
	}
}
?>