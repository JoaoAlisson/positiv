<?php 
class visitas extends ControllerCRUD{

	public $nome = array("Visita","Visitas");

	public $campos = array("visitante" => "Visitante",
						   "data"      => "Data da Visita");
	public $cor = "teal";

	//public $inalteraveis = array("qtd");

	//public $icone = "sitemap";

	//public $listar = array("nome", "qtd");

	//public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "igreja" => "tudo");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function vistdeditar(){
		if(isset($_POST['id'])){
			parent::deletar();
			exit();
		}

		if(!isset($_POST['idSet']) && !isset($this->GET['cod']))
			header('location: '. URL . 'visitantes');

		$id = isset($_POST['idSet']) ? $_POST['idSet'] : "";
		$id = isset($this->GET['cod']) ? $this->GET['cod'] : $id;


		$retorno = $this->model->pegarPagina(1, "", array("id", "data"), array("visitante" => $id), null, null, null, true);
		$retorno['id'] = $id;
		$retorno['visitante'] = $this->model->informacoesVisitante($id);
		$this->dados($retorno);		
	}	

	public function listagem(){

		if(!isset($_POST['idSet']))
			header('location: '. URL . 'ministerios');		

		$id = $_POST['idSet'];
		
		$retorno = $this->model->pegarPagina(1, "", array("id", "data"), array("visitante" => $id), null, null, null, true);

		$arrayJ = array();
		foreach ($retorno['visitas'] as $linha => $valores) {
			$array['data'] = $valores['data'];
			$array['id'] = $valores['id'];
			array_push($arrayJ, $array);
		}

		$retorna["qtd"] = $retorno['qtd'];
		$retorna["listagem"] = $arrayJ;
		echo json_encode($retorna);
	}	
}
?>