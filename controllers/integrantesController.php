<?php 
class integrantes extends ControllerCRUD{

	public $nome = array("Integrante","Integrantes");

	public $campos = array("ministerio" => "Ministério", 
						   "membro"	    => "Integrante",
						   "funcao"     => "Função");
	public $cor = "teal";

	//public $inalteraveis = array("qtd");

	//public $icone = "sitemap";

	//public $listar = array("nome", "qtd");

	//public $filtros = array("nome", "nascimento");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function editar(){
		if(!isset($_POST['idSet']) && !isset($this->GET['cod']))
			header('location: '. URL . 'ministerios');

		$id = isset($_POST['idSet']) ? $_POST['idSet'] : "";
		$id = isset($this->GET['cod']) ? $this->GET['cod'] : $id;

		$retorno = $this->model->informacoesIntegrante($id);
		$this->dados($retorno);
	}

	public function Jeditar(){
		if(!isset($_POST['idSet']))
			header('location: '. URL . 'ministerios');
		
		$id = (int)$_POST['idSet'];
		$retornou = $this->model->atualizar(array("funcao" => $_POST['funcao']), $id);	

		$retornoJson['mensagem'] = $retornou[1];
		$retornoJson['erros'] = isset($retornou[2]) ? $retornou[2] : "";

		$retornoJson = json_encode($retornoJson);
		echo $retornoJson;
	}

	public function intgrteditar(){

		if(isset($_POST['id'])){
			parent::deletar();
			exit();
		}

		if(!isset($_POST['idSet']) && !isset($this->GET['cod']))
			header('location: '. URL . 'ministerios');

		$id = isset($_POST['idSet']) ? $_POST['idSet'] : "";
		$id = isset($this->GET['cod']) ? $this->GET['cod'] : $id;


		$retorno = $this->model->pegarPagina(1, "", array("id","membro", "funcao"), array("ministerio" => $id), null, null, null, true);
		$retorno['id'] = $id;
		$retorno['ministerio'] = $this->model->informacoesMinisterio($id);
		$this->dados($retorno);
	}

	public function listagem(){

		if(!isset($_POST['idSet']))
			header('location: '. URL . 'ministerios');		

		$id = $_POST['idSet'];
		
		$retorno = $this->model->pegarPagina(1, "", array("id", "membro", "funcao"), array("ministerio" => $id), null, null, null, true);

		$arrayJ = array();
		foreach ($retorno['integrantes'] as $linha => $valores) {
			$array['membro'] = "<a onclick=\"redir('membros', '". $valores['membro'] ."')\">". $valores['membros_nome'] ."</a>";
			$array['funcao'] = "<a onclick=\"redir('funcoes', '". $valores['funcao'] ."')\">". $valores['funcoes_nome'] ."</a>";
			$array['id'] = $valores['id'];
			array_push($arrayJ, $array);
		}

		$informacoes = $this->model->informacoesMinisterio($id);
		$retorna["qtd"] = $informacoes['qtd'];
		$retorna["listagem"] = $arrayJ;
		echo json_encode($retorna);
	}
}
?>