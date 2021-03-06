<?php 
class programacao extends ControllerCRUD{

	public $nome = array("Evento","Programação");

	public $cor = "blue";

	public $icone = "calendar";

	public $campos = array("nome"     	=> "Nome do Evento", 
						   "inicio"		=> "Início",
						   "fim"		=> "Fim",	
 						   "telefone"   => "Telefone do Responsável",
 						   "local"		=> "Local",
						   "descricao"	=> "Descrição");

	public $icones = array("nome" => "user");

	//public $placeholders = array("nome"     => "Isira seu nome");

	public $listar = array("nome", "local", "inicio");

	public $filtros = array("nome");

	public $regraUsuarios = array("Administrador" => "tudo", "programacao" => "tudo");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

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

		if($ordem == "" && $ordenacao == ""){
			$ordem = "inicio";
			$ordenacao = "ASC";
		}

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

		$data = "";

		if(isset($this->filtros)){
			$inicio = isset($this->GET['inicio']) ? $this->GET['inicio'] : "";
			$fim = isset($this->GET['fim']) ? $this->GET['fim'] : "";

			$datas = $this->tratarDatas($inicio, $fim);
			$inicio = $datas['inicio'];
			$fim = $datas['fim'];

			if($inicio != "" && $fim != ""){
				$data = "`".PREFIXO."programacao`.`inicio` BETWEEN '$inicio' AND '$fim'";
			}else{
				if($inicio != "")
					$data = "`".PREFIXO."programacao`.`inicio` >= '$inicio'";
				if($fim != "")
					$data = "`".PREFIXO."programacao`.`inicio` <= '$fim'";
			}
		}

/*
		if(isset($this->filtros)){
			if(isset($this->GET['incio'])){
				$mes = (int)$this->GET['mes'];
				$retorno['mes'] = $mes;
				if($mes == "")
					$mes = "DAY(`".PREFIXO."membros`.`nascimento`) = day(NOW())";
				else
					$mes = "MONTH(`".PREFIXO."membros`.`nascimento`) = $mes";
			}
		}
*/

		$qtdPorPg = isset($this->qtdPorPagina) ? $this->qtdPorPagina : 8;
		$busca = $this->model->pegarPagina($pagina, $qtdPorPg, $camposPegar, $onde, null, $ordem, $ordenacao, null, $data);

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
	}

	private function tratarDatas($inicio, $fim){
		if($inicio != "")
			$inicio = $this->formataData($inicio);

		if($fim != "")
			$fim = $this->formataData($fim);

		$retorna['inicio'] = $inicio;
		$retorna['fim'] = $fim;

		return $retorna;
	}

	private function formataData($data){
		$data = explode("-", $data);
		$dia = (int)$data[0];
		$mes = (int)$data[1];
		$ano = (int)$data[2];
		return "$ano-$mes-$dia";
	}
}
?>