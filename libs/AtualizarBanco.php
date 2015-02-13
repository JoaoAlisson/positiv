<?php
class AtualizarBanco {

	private $bancoAdm;
	private $models;
	private $tabelasCriar;
	private $tabelasDeletar;
	private $tabelasAlterar;
	private $relacionamentos = array();
	private $limites = array();

	function __construct(){
		$this->bancoAdm = new BancoAdm();
		$this->models = $this->pegarModels();
	}

	public function atualizar(){
		$this->separaTabelas();
		if(!DESENVOLVIMENTO_SEGURO)
			$this->deletaTabelas();
		$this->criarTabelas();
		$this->editarTabelas();

		if(!empty($this->limites)) {
			$this->delEstadosCidades();
			$this->criarTabelaQtds();
			$this->criaTodosTriggers();
			$this->atualizarQtds();
		} else 
			$this->bancoAdm->deletarTabela(PREFIXO . 'qtds');
	}

	private function atualizarQtds() {

		$campos = '';
		foreach ($this->limites as $tabela => $limite) {
			$tabela = explode(PREFIXO, $tabela);
			$tabela = $tabela[1];
			$qtd  = "(SELECT COUNT(*) FROM " . PREFIXO . "$tabela)";
			$campos .= ($campos == '') ? "$tabela = $qtd" : ", $tabela = $qtd";
		}		
		$sql = "UPDATE " . PREFIXO . "qtds SET $campos WHERE id = 1; ";

		$this->bancoAdm->realizaQuery($sql);
	}

	private function criaTodosTriggers() {
		foreach ($this->limites as $model) {
			$this->criaTriggers($model);
		}
	}

	private function criaTriggers($model) {
		$this->bancoAdm->triggerLimite($model['tabela'], $model['limite']);
		$this->bancoAdm->triggerIncremento($model['tabela']);
		$this->bancoAdm->triggerDecremento($model['tabela']);
	}

	private function criarTabelaQtds() {
		$tabela = PREFIXO . 'qtds';
		$naumExistia = false;
		if(!$this->bancoAdm->existeTabela($tabela)) {
			$this->bancoAdm->criarTabela($tabela);
			$naumExistia = true;
		}

		if(!DESENVOLVIMENTO_SEGURO){
			$todosOsCampos = $this->bancoAdm->campos($tabela);
			foreach ($todosOsCampos as $key => $campo)
				if(!isset($this->limites[PREFIXO . $campo]) && $campo != "id")
					$this->bancoAdm->deletarCampo($campo, $tabela);
		}		

		foreach ($this->limites as $limite) {

			$campo = explode(PREFIXO, $limite['tabela']);
			$campo = $campo[1];
			$campoQualquer = $campo;
			if($this->bancoAdm->existeCampo($campo, $tabela))
				$this->bancoAdm->alterarCampo($campo, $tabela, 'inteiro');
			else
				$this->bancoAdm->criarCampo($campo, $tabela, 'inteiro');
		}	

		if($naumExistia) {
			$sql = "INSERT INTO " . PREFIXO . "qtds ($campoQualquer) VALUES ('1')";
			$this->bancoAdm->realizaQuery($sql);
		}	
	}

	private function delEstadosCidades(){
		$estados = PREFIXO."estados";
		if(!$this->bancoAdm->getUtilizaEstados())
			$this->bancoAdm->deletarTabela($estados);

		$cidades = PREFIXO."cidades";
		if(!$this->bancoAdm->getUtilizaCidades())
			$this->bancoAdm->deletarTabela($cidades);
	}

	private function editarTabelas(){
		foreach ($this->tabelasAlterar as $key => $tabela)
			$this->editarTabela($tabela);
	}

	private function editarTabela($tabela){
		$modelClass = explode(PREFIXO, $tabela);
		$modelClass = $modelClass[1]."Model";
		include_once RAIZ . SEPARADOR . "models" . SEPARADOR . $modelClass . ".php";
		$modelClass = $modelClass;

		$model = new $modelClass();

		$camposModel = array();
		if(isset($model->tipos))
			$camposModel = $model->tipos;

		if(isset($model->limiteDeLinhas))
			$this->limites[$tabela] = array('tabela' => $tabela, 'limite' => $model->limiteDeLinhas);
		else
			$this->bancoAdm->deletarTirgsTabela($tabela);

		if(!DESENVOLVIMENTO_SEGURO){
			$todosOsCampos = $this->bancoAdm->campos($tabela);
			foreach ($todosOsCampos as $key => $campo)
				if(!isset($camposModel[$campo]) && $campo != "id")
					$this->bancoAdm->deletarCampo($campo, $tabela);
		}

		foreach ($camposModel as $campo => $tipo) {
			if($this->bancoAdm->existeCampo($campo, $tabela))
				$this->bancoAdm->alterarCampo($campo, $tabela, $tipo);
			else
				$this->bancoAdm->criarCampo($campo, $tabela, $tipo);
		}
	}

	private function criarTabelas(){
		foreach ($this->tabelasCriar as $key => $tabela)
			$this->criarTabela($tabela);
	}

	private function criarTabela($tabela){

		$modelClass = explode(PREFIXO, $tabela);
		$modelClass = $modelClass[1]."Model";
		include(RAIZ . SEPARADOR . "models". SEPARADOR . $modelClass . ".php");
		$modelClass = $modelClass;

		$model = new $modelClass();

		$this->bancoAdm->criarTabela($tabela);

		$campos = array();
		if(isset($model->tipos))
			$campos = $model->tipos;

		if(isset($model->limiteDeLinhas))
			$this->limites[$tabela] = array('tabela' => $tabela, 'limite' => $model->limiteDeLinhas);

		foreach ($campos as $campo => $tipo)
			$this->bancoAdm->criarCampo($campo, $tabela, $tipo);
	}

	private function deletaTabelas() {
		foreach ($this->tabelasDeletar as $key => $tabela)
			$this->bancoAdm->deletarTabela($tabela);
	}

	private function separaTabelas() {
		$tabelas = $this->bancoAdm->tabelas();
		$models  = $this->pegarModels();

		$estados = PREFIXO."estados";
		if(in_array($estados, $tabelas))
			unset($tabelas[array_search($estados, $tabelas)]);

		$cidades = PREFIXO."cidades";
		if(in_array($cidades, $tabelas))
			unset($tabelas[array_search($cidades, $tabelas)]);

		$limite = PREFIXO."qtds";
		if(in_array($limite, $tabelas))
			unset($tabelas[array_search($limite, $tabelas)]);		

		$this->tabelasCriar   = array_diff($models, $tabelas);
		$this->tabelasDeletar = array_diff($tabelas, $models);
		$this->tabelasAlterar = array_intersect($models, $tabelas);
	}

	private function pegarModels(){
		$models = array();

		$pasta = RAIZ . SEPARADOR . "models". SEPARADOR;
		$diretorio = dir($pasta);

		while($arquivo = $diretorio->read()){
			if(strripos($arquivo, "Model.php")){
				$model = explode("Model.php", $arquivo);
				$model = $model[0];
				//$model = ucfirst($model[0]);
				if($model != "login"){
					$model = PREFIXO.$model;
					array_push($models, $model);
				}
			}
		}	
					
		return $models;
	}
}
?>