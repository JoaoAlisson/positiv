<?php 
class ministerios extends ControllerCRUD{

	public $nome = array("Ministério","Ministérios");

	public $campos = array("nome"     	=> "Nome", 
						   "lider"		=> "Líder",
 						   "foto"   	=> "Imagem",
 						   "qtd"		=> "Qtd",
						   "descricao"  => "Descrição");

	public $cor = "teal";

	public $icone = "list";

	public $inalteraveis = array("qtd");

	public $listar = array("nome", "lider", "qtd");

	public $filtros = array("nome");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function index(){
		parent::index();
		$this->renderizar("ministerios/index");
	}

	public function visualizar(){
		if(!isset($_POST['idSet']))
			header('location: '. URL . $this->nomeController());
		if($_POST['idSet'] == "")
			header('location: '. URL . $this->nomeController());

		$id = (int)$_POST['idSet'];
		//$id= 2;
		$retorno = $this->model->visualizar($id);
		$retorno['nome'] = $this->nome;
		$retorno['id'] = $id;
		$campos =  $this->campos;

		$retorno['campos'] = $campos;
		$retorno['tipos'] = $this->model->tipos;	
		$retorno['cor'] = $this->informacoes['cor'];
		$retorno['icone'] = $this->informacoes['icone'];

		$retorno['integrantes'] = $this->model->pegaIntegrantes($id);

		$this->dados($retorno);	
	}
}
?>