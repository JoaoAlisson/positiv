<?php
class AtualizarBanco {

	private $bancoAdm;
	private $models;
	private $tabelasCriar;
	private $tabelasDeletar;
	private $tabelasAlterar;

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

	}

	private function editarTabelas(){
		foreach ($this->tabelasAlterar as $key => $tabela)
			$this->editarTabela($tabela);
	}

	private function editarTabela($tabela){
		$modelClass = explode(PREFIXO, $tabela);
		$modelClass = $modelClass[1]."Model";
		include(RAIZ . SEPARADOR . "models". SEPARADOR . $modelClass . ".php");
		$modelClass = $modelClass;

		$model = new $modelClass();

		$camposModel = array();
		if(isset($model->tipos))
			$camposModel = $model->tipos;

		if(!DESENVOLVIMENTO_SEGURO){
			$todosOsCampos = $this->bancoAdm->campos($tabela);
			foreach ($todosOsCampos as $key => $campo)
				if(!isset($camposModel[$campo]))
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

		foreach ($campos as $campo => $tipo)
			$this->bancoAdm->criarCampo($campo, $tabela, $tipo);
	}

	private function deletaTabelas(){
		foreach ($this->tabelasDeletar as $key => $tabela)
			$this->bancoAdm->deletarTabela($tabela);
	}

	private function separaTabelas(){
		$tabelas = $this->bancoAdm->tabelas();
		$models = $this->pegarModels();

		$this->tabelasCriar = array_diff($models, $tabelas);
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