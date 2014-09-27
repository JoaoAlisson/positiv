<?php 
class login extends Controller {

	public function index() {
		$this->usarLayout(false);

		if(isset($_POST['token'])){

			if($_POST['token'] == Sessao::pegar('token')){
				Sessao::destruir();
				$usuarioId = $this->model->verificaLogin($_POST['login'], $_POST['senha']);
				if($usuarioId != 0) {
					//logar
					$tipo = $this->model->pegaTipoUsuario($usuarioId['id']);

					Sessao::iniciar();
					Sessao::inserir("logado", true);
					Sessao::inserir("tipo", $tipo);
					Sessao::inserir("usuario", $usuarioId["login"]);
					header('location: ' . URL);
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