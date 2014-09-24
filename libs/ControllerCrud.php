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

		$pagina = isset($this->GET['pag']) ? $this->GET['pag'] : null;
		$ordem = isset($this->GET['ordem']) ? $this->GET['ordem'] : null;
		$ordenacao = isset($this->GET['ordenacao']) ? $this->GET['ordenacao'] : null;

		if(!isset($campos[$ordem]) && $ordem != null)
			$ordem = null;

		$onde = array();
		$filtrosValores = array();
		foreach ($this->filtros as $key => $filtro) {
			if (isset($this->GET[$filtro])) {
				if ($this->GET[$filtro] != "") {
					$onde[$filtro] = $this->GET[$filtro];
					$filtrosValores[$filtro] = $this->GET[$filtro];
				}
			}
		}

		//if(!isset($_POST['filtro']))
			$retorno['filtrosValores'] = $filtrosValores;

		$qtdPorPg = isset($this->qtdPorPagina) ? $this->qtdPorPagina : 8;
		$busca = $this->model->pegarPagina($pagina, $qtdPorPg, $camposPegar, $onde, null, $ordem, $ordenacao);

		$retorno['itens'] = $busca[$this->informacoes['nomeController']];
		$retorno['controller'] = $this->informacoes['nomeController'];
		$retorno['qtdPaginas'] = $busca['qtdPaginas'];
		$retorno['qtd'] = $busca['qtd'];
		$retorno['pagina'] = $busca['pagina'];
		$retorno['ordem'] = $ordem;
		$retorno['ordenacao'] = $ordenacao;

		$this->dados($retorno);
		$this->renderizar("CRUD/index");
	}

	public function nomeController(){
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
			$retorno['campos'] = $this->campos;			
		}
		$this->dados($retorno);
		$this->renderizar("CRUD/cadastrar");
	}

	public function editar(){

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
				$campos[$key] = isset($_POST[$key]) ? $_POST[$key] : "";
			}
			$this->usarLayout(false);
			$retornou = $this->model->atualizar($campos, $_POST['id']);
			$retorno['retorno'] = $retornou; 					
		}else{

			$retorno = $this->model->pegar('739');
			$retorno['nome'] = $this->nome;
			$retorno['campos'] = $this->campos;	
		}
		$this->dados($retorno);
		$this->renderizar("CRUD/editar");
	}

	public function deletar(){
		
	}

	public function filtro($acao){
		if($this->getAcao() == "filtro"){
			header('location: '. URL);
		}else{

			//$this->getAcao() = $acao;
		}
	}

	private function teste(){
		echo "teste";
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
}
?>