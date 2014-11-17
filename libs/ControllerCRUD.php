<?php 
class ControllerCRUD extends Controller{

	private $valNome;
	private $buscas = array();
	private $valQtdPorPg = 20;
	private $valTipoIndex = 1;


	public function index(){

		//pega os quatro primeiros campos, menos campos do tipo imagem
		$cont = 0;
		$campos = array();
		if(isset($this->listar)){
			foreach ($this->listar as $key => $campo) {
				if($this->model->tipos[$campo] == "facebook")
					$campos[$campo] = "facebook";
				else
					$campos[$campo] = $this->campos[$campo];
			}
		}else{
			foreach ($this->campos as $campo => $nomeDoCampo) {
				if($cont == 4)
					break;

				if($this->model->tipos[$campo] != 'imagem')
					$campos[$campo] = $nomeDoCampo;
				$cont++;
			}
		}

		$filtros = array();

		$retorno['nome'] = $this->nome;
		$retorno['campos'] = $campos;

		if(isset($_POST['filtro'])){
			$retorno['filtro'] = true;
			$this->usarLayout(false);
		}else{
			if(isset($this->filtros)){
				foreach ($this->filtros as $key => $filtro)
					$filtros[$filtro] = $this->campos[$filtro];
				$retorno["filtros"] = $filtros;
			}
		}

		$camposPegar = array_keys($campos);
		array_push($camposPegar, "id");

		$pagina = isset($this->GET['pag']) ? $this->GET['pag'] : null;
		$ordem = "";
		//valida campos informados para ordenação
		if(isset($this->GET['ordem'])){
			$ordem = isset($this->campos[$this->GET['ordem']]) ? $this->GET['ordem'] : null;
		}else{
			$ordem = null;
		}

		//valida se o valor de ordenação eh "ASC" ou "DESC"
		$ordenacao = "";
		if(isset($this->GET['ordenacao'])){
			if($this->GET['ordenacao'] != "ASC" && $this->GET['ordenacao'] != "DESC")
				$ordenacao = null;
			else
				$ordenacao = $this->GET['ordenacao'];
		}else{
			$ordenacao = null;
		}

		if(!isset($campos[$ordem]) && $ordem != null)
			$ordem = null;

		$onde = array();
		$filtrosValores = array();
		if(isset($this->filtros)){
			foreach ($this->filtros as $key => $filtro) {
				if (isset($this->GET[$filtro])) {
					if ($this->GET[$filtro] != "") {
						$onde[$filtro] = $this->GET[$filtro];
						$filtrosValores[$filtro] = $this->GET[$filtro];
					}
				}
			}
		}

		//if(!isset($_POST['filtro']))
			$retorno['filtrosValores'] = $filtrosValores;

		$qtdPorPg = isset($this->qtdPorPagina) ? $this->qtdPorPagina : 8;
		$busca = $this->model->pegarPagina($pagina, $qtdPorPg, $camposPegar, $onde, null, $ordem, $ordenacao);

		$itens = $busca[$this->informacoes['nomeController']];

		foreach ($itens as $linha => $campos) {
			foreach ($campos as $campo => $valor) {
				if(!isset($retorno['campos'][$campo]) && $campo != "id")
					unset($itens[$linha][$campo]);
			}
		}

		$retorno['itens'] = $itens;
		$retorno['tipos'] = $this->model->tipos;
		$retorno['controller'] = $this->informacoes['nomeController'];
		$retorno['cor'] = $this->informacoes['cor'];
		$retorno['icone'] = $this->informacoes['icone'];
		$retorno['qtdPaginas'] = $busca['qtdPaginas'];
		$retorno['qtd'] = $busca['qtd'];
		$retorno['pagina'] = $busca['pagina'];
		$retorno['ordem'] = $ordem;
		$retorno['ordenacao'] = $ordenacao;

		$this->dados($retorno);
		$this->renderizar("CRUD/index");
	}

	public function nomeController(){
		if($this->getAcao() == "nomeController" || $this->getAcao() == "nomecontroller")
			header('location: '. URL);
		else		
			return get_class($this);
	}

	public function cadastrar(){

		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			if(isset($_POST['ajaxPg'])){
				require "views/erro/index.php";
				exit();
			}else{ 
				header('location: '.URL.'erro');
			}
		}

		//verificar se algum campo foi postado
		$foiPostado = false;

		foreach ($this->campos as $key => $value) {
			if(isset($_POST[$key])){
				$foiPostado = true;
				break;
			}
		}
		$retorno;

		if($foiPostado){
			$campos = array();

			foreach ($this->campos as $key => $value) {
				$campos[$key] = isset($_POST[$key]) ? $_POST[$key] : "";
			}

			$this->usarLayout(false);
			$retornou = $this->model->inserir($campos);
			$retorno['retorno'] = $retornou; 
				
		}else{

			$retorno['nome'] = $this->nome;
			$campos =  $this->campos;
			foreach ($campos as $campo => $value) {
				if(isset($this->inalteraveis)){
					if(in_array($campo, $this->inalteraveis))
						unset($campos[$campo]);
				}
			}
			$retorno['campos'] = $campos;
			$retorno['cor'] = $this->informacoes['cor'];
			$retorno['icone'] = $this->informacoes['icone'];						
		}
		$this->dados($retorno);
		$this->renderizar("CRUD/cadastrar");
	}

	public function editar(){

		$id = isset($_POST['idSet']) ? $_POST['idSet'] : "";
		if(isset($_POST['id']))
			$id = $_POST['id'];

		if($id == "")
			header('location: '. URL . $this->nomeController());


		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			if(isset($_POST['ajaxPg'])){
				require "views/erro/index.php";
				exit();
			}else{ 
				header('location: '.URL.'erro');
			}
		}
				
		$retorno;

		if(isset($_POST['id'])){
			$campos = array();
			foreach ($this->campos as $key => $value) {
				$inalt = false;
				if(isset($this->inalteraveis)){
					if(in_array($key, $this->inalteraveis))
						$inalt = true;
				}
				if($inalt == false)
					$campos[$key] = isset($_POST[$key]) ? $_POST[$key] : "";
			}
			$this->usarLayout(false);
			$retornou = $this->model->atualizar($campos, $_POST['id']);
			$retorno['retorno'] = $retornou; 					
		}else{

			$retorno = $this->model->pegar($id);
			$retorno['nome'] = $this->nome;

			$campos =  $this->campos;
			foreach ($campos as $campo => $value) {
				if(isset($this->inalteraveis)){
					if(in_array($campo, $this->inalteraveis))
						unset($campos[$campo]);
				}
			}			

			$retorno['campos'] = $campos;	
			$retorno['cor'] = $this->informacoes['cor'];
			$retorno['icone'] = $this->informacoes['icone'];
		}
		$retorno['id'] = $id;
		$this->dados($retorno);
		$this->renderizar("CRUD/editar");
	}

	public function visualizar(){
		if(!isset($_POST['idSet']))
			header('location: '. URL . $this->nomeController());
		if($_POST['idSet'] == "")
			header('location: '. URL . $this->nomeController());

		$id = $_POST['idSet'];
		//$id= 2;
		$retorno = $this->model->visualizar($id);
		$retorno['nome'] = $this->nome;
		$retorno['id'] = $id;
		$campos =  $this->campos;

		$retorno['campos'] = $campos;
		$retorno['tipos'] = $this->model->tipos;	
		$retorno['cor'] = $this->informacoes['cor'];
		$retorno['icone'] = $this->informacoes['icone'];

		$this->dados($retorno);		
		$this->renderizar("CRUD/visualizar");
	}

	public function deletar(){
		
		$retorno;
		if(!isset($_POST['id']) || !isset($_POST['model'])){
			$retorno['flag'] = "erro";
			$retorno['mensagem'] = "Não foi possível deletar";
		}else{
			$id = $_POST['id'];	
			$retorno = $this->model->deletar($id);
		}
	
		echo json_encode($retorno);
	}

	public function filtro($acao){
		if($this->getAcao() == "filtro"){
			header('location: '. URL);
		}else{

			//$this->getAcao() = $acao;
		}
	}

	public function buscas($acao){
		if($this->getAcao() == "buscas"){
			header('location: '. URL);
		}else{
			//$this->getAcao() = $acao;
		}
	}

	public function qtdPorPg($qtd){
		if($this->getAcao() == "qtdPorPg"){
			header('location: '. URL);
		}else{
			$this->valQtdPorPg = $qtd;
		}
	}		
	
	public function tipoIndex($tipo){
		if($this->getAcao() == "tipoIndex"){
			header('location: '. URL);
		}else{
			$this->valTipoIndex = $tipo;
		}
	}

	public function nome($valor){
		if($this->getAcao() == "nome"){
			header('location: '. URL);
		}else{
			$this->valNome = $valor;
		}
	}

	public function imagens(){

		if(!isset($this->GET['img']))
			exit();

		if($this->GET['img'] == "")
			exit();

		$this->usarLayout(false);
		$imagem['img'] = $this->GET['img'];
		$this->dados($imagem);
		$this->renderizar("CRUD/imagens");
	}
}
?>