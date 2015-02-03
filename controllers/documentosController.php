<?php 
class documentos extends Controller{

	public $regraUsuarios = array("Administrador" => "tudo", "documentos" => "tudo");

	public function index() {

	}

	private function pegaModel($class) {
		$this->importa($class);
		$className = $class . "Dc";
		return new $className();
	}

	private function importa($class) {
		$caminho1 = RAIZ . SEPARADOR . 'relatoriosModels' . SEPARADOR;
		$caminho2 = RAIZ . SEPARADOR . 'documentosModels' . SEPARADOR;
		require_once($caminho1 . 'classePdo.php');
		require_once($caminho2 . $class . 'Dc.php');		
	}

	public function certificado_membro() {
		
		if(!isset($_POST['postado'])) {
			$model = $this->pegaModel('certificadoMembro');
			$membros = $model->membros();
			$this->dados($membros);
		} else {
			$this->pdf('certificado_membro');
		}
	}

	private function pdf($funcao) {
		$action = $funcao . "Pdf";
		$this->$action();
		$this->renderizar('documentos' . SEPARADOR . $funcao .'Pdf');
		$this->usarLayout(false);
	}

	private function certificado_membroPdf() {

		$id = isset($_POST['membro']) ? (int)$_POST['membro'] : '';
		$model = $this->pegaModel('certificadoMembro');
		$membro = $model->membro($id);

		$this->dados($membro);
	}

	public function batismo() {
		if(!isset($_POST['postado'])) {
			$model = $this->pegaModel('batismo');
			$membros = $model->membros();
			$this->dados($membros);
		} else {
			$this->pdf('batismo');
		}		
	}

	private function batismoPdf() {
		$id = isset($_POST['membro']) ? (int)$_POST['membro'] : '';
		$model = $this->pegaModel('batismo');
		$retorna = $model->membro($id);
		$this->dados($retorna);		
	}

	public function consagracao() {
		if(!isset($_POST['postado'])) {
			$model = $this->pegaModel('consagracao');
			$retorna['membros']      = $model->membros();
			$retorna['consagracoes'] = $model->consagracoes();
			$this->dados($retorna);
		} else
			$this->pdf('consagracao');
		
	}

	private function consagracaoPdf() {
		$id  		 = isset($_POST['membro'])      ? (int)$_POST['membro']      : '';
		$consagracao = isset($_POST['consagracao']) ? (int)$_POST['consagracao'] : '';

		$model   = $this->pegaModel('consagracao');
		$retorna = $model->membro($id);

		if($consagracao != '')
			$consagracao = $model->consagracao($consagracao);
		else
			$consagracao = $model->consagracao($retorna['consagracao']);
		
		$dados['nome'] 		  = $retorna['nome'];
		$dados['sexo']        = $retorna['sexo'];
		$dados['consagracao'] = $consagracao;

		$this->dados($dados);	
	}
}
?>