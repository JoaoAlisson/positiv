<?php 
class login extends Controller {

	public $campos = array("login" => "Usuário", 
						   "senha" => "Senha");	

	public function index() {

		if(!Sessao::pegar("logado"))
			$this->usarLayout(false);

		if(isset($_POST['token'])){

			if($_POST['token'] == Sessao::pegar('token')){
				$url = Sessao::pegar('URL');
				$retorno = $this->model->verificaLogin($_POST['login'], $_POST['senha']);
				if($retorno[0] == "ok") {
					//logar
					Sessao::destruir();
					$tipo = $this->model->pegaTipoUsuario($retorno[1]['id']);

					Sessao::iniciar();
					Sessao::inserir("logado", true);
					Sessao::inserir("tipo", $tipo);
					Sessao::inserir("usuario", $retorno[1]["nome"]);
					Sessao::inserir("id", $retorno[1]["id"]);
					header('location: ' . $url);
				}else{
					$this->dados($retorno);
					$this->renderizar("login/index");
				}	
			}
		}
	}

	public function deslogar(){
		Sessao::destruir();
		header('location: ' . URL);
	}
}
?> 