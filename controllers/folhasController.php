<?php 
class folhas extends Controller{

	private $tabelaInss = array();

	public $nome = array("Folha de Pagamento","Folhas de Pagamentos");

	public $campos = array( "mes"   => "Mês",
						    "ano"	=> "Ano",
						    "total" => "Total");	

	public $cor = "";

	public $icone = "file powerpoint outline";

	public $listar = array("mes", "total");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;

	public function index(){
		//pega os quatro primeiros campos, menos campos do tipo imagem
		$ano = "";
		if(isset($this->GET['ano']))
			$ano = (int)$this->GET['ano'];
		
		if($ano == "" || $ano < 2010 || $ano > 2030)
			$ano = 2000 + date('y');

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
		$busca = $this->model->pegarPagina($pagina, $qtdPorPg, $camposPegar, $onde, null, $ordem, $ordenacao, true);

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
		$retorno['ano'] = $ano;

		$this->dados($retorno);
	}	

	public function gerar(){

		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			if(isset($_POST['ajaxPg'])){
				require "views/erro/index.php";
				exit();
			}else{ 
				header('location: '.URL.'erro');
			}
		}

		$retorno['flag'] = "ok";
		if(!isset($_POST['anoSet']) || !isset($_POST['mesSet'])){
			$retorno['flag'] = "erro";
			$retorno['mensagem'] = "Não foi possível deletar";
		}else{

			$validado = $this->validaData($_POST['anoSet'], $_POST['mesSet']);
			if($validado != ""){
				$retorno['flag'] = "erro";
				$retorno['mensagem'] = $validado;				
			}else{
				$this->criarFolha();
			}
		}

		$this->usarLayout(false);
		if($retorno['flag'] == "ok")
			$this->retornaOk();
		else
			echo json_encode($retorno);
	}

	private function validaData($ano, $mes){
		$erro = "";
		if($ano < 2010 || $ano > 2030 || $mes < 1 || $mes > 12)
			$erro = "Data inválida";

		if($erro == ""){
			$qtd = $this->model->retornaQuantidade($ano, $mes);
			if($qtd != 0)
				$erro = "Folha já gerada";
		}

		return $erro;
	}

	private function retornaOk(){

		$retorno['flag'] = "ok";
		$retorno['mensagem'] = "A Folha foi gerada com sucesso!";
		echo json_encode($retorno);
	}

	private function criarFolha(){
		$ano = (int)$_POST['anoSet'];
		$mes = (int)$_POST['mesSet'];
		$campos = array("ano" 		=> $ano,
						"mes" 		=> $mes,
						"total" 	=> 0,
						"contab"	=> 0);

		$retorna = $this->model->inserir($campos);
		$idFolha = $this->model->lastInsertId();

		$total = $this->model->insereFuncionarios($idFolha);
	}
}?>