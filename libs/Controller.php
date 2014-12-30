<?php 
class Controller{
	
	private $viewRender = NULL;
	private $layout = true;
	private $Acao;
	private $dados;
	public $informacoes;
	public $GET;
	public $permissao;
	public $view;

	function __construct(){


		$this->loadModel(strtolower(get_class($this)));
		
		$this->informacoes['nome'] = isset($this->nome) ? $this->nome : "";
		$this->informacoes['campos'] = isset($this->campos) ? $this->campos : "";
		$this->informacoes['icones'] = isset($this->icones) ? $this->icones : "";
		$this->informacoes['obrigatorios'] = isset($this->model->obrigatorios) ? $this->model->obrigatorios : array();
		if(isset($this->obrigatorios))
			$this->informacoes['obrigatorios'] = array_merge($this->informacoes['obrigatorios'], $this->obrigatorios); 

		$this->informacoes['tipos'] = isset($this->model->tipos) ? $this->model->tipos : "";
		$this->informacoes['placeholders'] = isset($this->placeholders) ? $this->placeholders : "";
		$this->informacoes['nomeController'] = strtolower(get_class($this));
		$this->informacoes['nomeView'] = "";
		$this->informacoes['cor'] = isset($this->cor) ? $this->cor : "";
		$this->informacoes['icone'] = isset($this->icone) ? $this->icone : "";

		$this->view = new View($this->informacoes);

		//verificação de permissão
		if(isset($this->regraUsuarios)){	
			$this->permissao = "nenhuma";
			$tipos = Sessao::pegar("tipo");
			foreach ($this->regraUsuarios as $usuarioTip => $regra) {
				if(in_array($usuarioTip, $tipos)){
					$this->permissao = $regra;
					if($regra == "tudo")
						break;
				}
			}
		}

		//echo $this->permissao;
		/*
		if($this->permissao == "nenhuma"){
			if(isset($_POST['ajaxPg'])){
				require "views/erro/index.php";
				exit();
			}else{ 
				header('location: '.URL.'erro');
			}
		} 
		*/

		if(isset($this->model))
			$this->model->inserirPermissao($this->permissao);

		if(!defined('CONTROLLER'))
			define("CONTROLLER", $this->informacoes['nomeController']);
	}

	public function  loadModel($name){
		if($this->Acao == "loadModel"){
			header('location: '. URL);
		}else{

			$file = RAIZ . SEPARADOR . 'models'. SEPARADOR . $name . 'Model.php';

			if(file_exists($file)){

				require $file;
				$modelName = $name.'Model';
				$this->model = new $modelName;
			}
		}
	}

	public function getViewRender(){
		if($this->Acao == "getViewRender")
			header('location: '. URL);
		else		
			return $this->viewRender;
	}

	public function setViewRender($view){
		if($this->Acao == "setViewRenter")
			header('location: '. URL);
		else			
			$this->renderizar($view);
	} 

	protected function renderizar($view){
		if($this->Acao == "renderizar")
			header('location: '. URL);
		else			
			$this->viewRender = $view;
	}

	public function usarLayout($variavel){
		if($this->Acao == "usarLayout")
			header('location: '. URL);
		else			
			$this->layout = $variavel;
	}

	public function getLayout(){
		if($this->Acao == "getLayout")
			header('location: '. URL);
		else					
			return $this->layout;
	}

	public function setAcao($acao){
		if($this->Acao == "setAcao")
			header('location: '. URL);
		else
			$this->Acao = $acao;
	}

	public function getAcao(){
		if($this->Acao == "getAcao")
			header('location: '. URL);
		else
			return $this->Acao;		
	}

	public function dados($dados){
		if($this->Acao == "dados"){
			header('location: '. URL);
		}else{
			$this->dados = $dados;
			if(isset($dados[get_class($this)]))
				$this->informacoes['dados']['campos'] = $dados[get_class($this)];
		}	
	}

	public function getDados(){
		if($this->Acao == "getDados")
			header('location: '. URL);
		else
			return $this->dados;		
	}	

}
?>