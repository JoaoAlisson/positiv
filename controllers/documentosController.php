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
		$this->certificado_membroPdf();
		$this->renderizar('documentos' . SEPARADOR. $funcao .'Pdf');
		$this->usarLayout(false);
	}

	private function certificado_membroPdf() {

		$id = isset($_POST['membro']) ? (int)$_POST['membro'] : '';
		$model = $this->pegaModel('certificadoMembro');
		$membro = $model->membro($id);

		$this->dados($membro);
	}
}
?>