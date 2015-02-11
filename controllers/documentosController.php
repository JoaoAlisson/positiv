<?php 
class documentos extends Controller{

	public $regraUsuarios = array("Administrador" => "tudo", "documentos" => "tudo");

	public function index() {

	}

	private function pegaModel($class = '') {
		$this->importa($class);
		$className = ($class == '') ? 'classePdo' : $class . "Dc";
		return new $className();
	}

	private function importa($class) {
		$caminho1 = RAIZ . SEPARADOR . 'relatoriosModels' . SEPARADOR;
		require_once($caminho1 . 'classePdo.php');

		if($class != '') {
			$caminho2 = RAIZ . SEPARADOR . 'documentosModels' . SEPARADOR;
			require_once($caminho2 . $class . 'Dc.php');	
		}	
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
		$_POST['nomeIgreja'] = $model->nomeIgreja();

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
		$_POST['nomeIgreja'] = $model->nomeIgreja();
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
		$dados['nomeIgreja']  = $model->nomeIgreja();

		$this->dados($dados);	
	}

	public function certificado_curso() {
		if(!isset($_POST['postado'])) {
			$model = $this->pegaModel('certificado_curso');
			$retorna  = $model->membros();
			$this->dados($retorna);
		} else
			$this->pdf('certificado_curso');		
	}

	private function certificado_cursoPdf() {

		$model = $this->pegaModel('certificado_curso');

		$aluno                = isset($_POST['nome'])   	 ? $_POST['nome'] 							  : '';
		$dados['aluno']       = isset($_POST['membro'])      ? $model->membro($_POST['membro']) 	      : $aluno;
		$dados['responsavel'] = isset($_POST['responsavel']) ? $model->responsavel($_POST['responsavel']) : '';
		$dados['curso']       = isset($_POST['curso'])       ? $_POST['curso']                            : '';
		$dados['cargaH']      = isset($_POST['quantidade'])  ? $_POST['quantidade']                       : '';
		$dados['nomeIgreja']  = $model->nomeIgreja();
 
		$this->dados($dados);	
	}

	public function apresentacao() {
		if(isset($_POST['postado']))
			$this->pdf('apresentacao');
	}

	private function apresentacaoPdf() {
		$dados['crianca']    = isset($_POST['crianca'])    ? $_POST['crianca'] : '';
		$dados['pai']        = isset($_POST['pai'])   	   ? $_POST['pai'] : '';
		$dados['mae']        = isset($_POST['mae'])   	   ? $_POST['mae'] : '';
		$dados['nascimento'] = isset($_POST['nascimento']) ? $_POST['nascimento'] : '';
		$dados['sexo']       = isset($_POST['sexo'])       ? $_POST['sexo'] : '';

		$model = $this->pegaModel();
		$dados['nomeIgreja'] = $model->nomeIgreja();
		$this->dados($dados);
	}

	public function casamento() {
		if(isset($_POST['postado']))
			$this->pdf('casamento');		
	}

	private function casamentoPdf() {
		$dados['esposo'] = isset($_POST['esposo']) ? $_POST['esposo'] : '';
		$dados['esposa'] = isset($_POST['esposa']) ? $_POST['esposa'] : '';
		$dados['data']   = isset($_POST['data'])   ? $_POST['data']   : '';

		$model = $this->pegaModel();
		$dados['nomeIgreja'] = $model->nomeIgreja();

		$this->dados($dados);
	}

	public function convite() {
		if(!isset($_POST['postado'])) {
			$model = $this->pegaModel('convite');
			$retorna  = $model->eventos();
			$this->dados($retorna);
		} else
			$this->pdf('convite');		
	}

	private function convitePdf() {
		$model = $this->pegaModel('convite');
		$dados = isset($_POST['membro']) ? (int)$_POST['membro'] : '';
		$dados = $model->evento($dados['evento']);
		$dados['nomeIgreja'] = $model->nomeIgreja();

		$this->dados($dados);
	}

	public function informacoes() {
		$this->usarLayout(false);
		$model = $this->pegaModel('informacoes');
		$retorna = $model->informacoes();

		$this->dados($retorna);
	}
}
?>