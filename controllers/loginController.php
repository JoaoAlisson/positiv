<?php 
class login extends Controller {

	function __construct($sis_user = '') {

		$_POST['sis_user'] = $sis_user;
		$this->sis_user    = $sis_user;
		parent::__construct();
	}

	private $sis_user;

	public $campos = array("login" => "Usuário", 
						   "senha" => "Senha");	

	public function index() {

		if(!Sessao::pegar("logado"))
			$this->usarLayout(false);
		else
			$_POST['sis_user'] = Sessao::pegar('sis_user');

		if(isset($_POST['token'])){

			$url = Sessao::pegar('URL');
			if($_POST['token'] == Sessao::pegar('token')){
				

				$retorno = $this->model->verificaLogin($_POST['login'], $_POST['senha']);
				
				if($retorno[0] == "ok") {
					//logar
					Sessao::destruir();
					$tipo = $this->model->pegaTipoUsuario($retorno[1]['id']);

					Sessao::iniciar();
					Sessao::inserir('logado', true);
					Sessao::inserir('tipo', $tipo);
					Sessao::inserir('usuario', $retorno[1]['nome']);
					Sessao::inserir('id', $retorno[1]['id']);
					Sessao::inserir('dono', $retorno[1]['dono']);
					Sessao::inserir('ativo', $this->model->ativo());
					Sessao::inserir('sis', 'igrj');
					Sessao::inserir('sis_user', $this->sis_user);
					header('location: ' . $url);
				} else
					$this->dados($retorno);
					//$this->renderizar("login/index");

				header('location: ' . $url);
			}
		}
	}

	public function deslogar(){
		Sessao::destruir();
		header('location: ' . URL);
	}
}
?> 