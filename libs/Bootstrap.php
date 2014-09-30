<?php 
class Bootstrap {
	
	function __construct(){
		if($this->verificarLogado()){
			$this->carregarPgs();
		}else{
			$this->carregarTelaLogin();
		}
	}

	public function verificarLogado(){
		Sessao::iniciar();
		$logado = Sessao::pegar("logado");
		return $logado;
	}

	public function carregarTelaLogin(){
		require "controllers/loginController.php";
		$controller = new login();
		$controller->setViewRender("login/index");
		$controller->index();
		$controller->view->adicionarDados($controller->getDados());
		$controller->view->render("login","login/index", false);			
	}	

	public function carregarPgs(){
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url =  explode('/', $url);

		//criando as variaveil $_GET[].
		if(isset($url[2])){
			$gets = $url;
			unset($gets[0], $gets[1]);
			
			$variaveisGet = array();
			foreach ($gets as $nome => $valor) {
				$vr = explode(":", $valor);
				$variaveisGet[$vr[0]] = $vr[1];
			}	
		}		

		if(empty($url[0])){
			require "controllers/indexController.php";
			$controller = new Index();

			$controller->setViewRender(strtolower(get_class($controller))."/index");
			$controller->GET = &$variaveisGet;
			$controller->index(); 
			$controller->view->adicionarDados($controller->getDados());
			$controller->view->render(strtolower(get_class($controller)),$controller->getViewRender(), $controller->getLayout());

			return false;
		}

		$file = 'controllers/'.$url[0].'Controller.php';
		if(file_exists($file)){
			require $file;

		}else{
			require "controllers/errorController.php";
			//throw new Exception("The file {$file} does not exists." );
			$error = new Error();			
			return false; 
		}
		$controller = new $url[0];
		
		//$controller->loadModel($url[0]);

		if(isset($url[1])){

			if(method_exists($controller, $url[1])){
				$controller->setViewRender(strtolower(get_class($controller))."/".$url[1]);
				$controller->GET = &$variaveisGet;
				$controller->setAcao($url[1]);
				try {
					$controller->{$url[1]}();
				}catch (Exception $e){
					echo "rwarw";
					header('location: '. URL);
				}							
							
				$controller->view->adicionarDados($controller->getDados());
				$nomeController = (get_parent_class($controller) == "ControllerCRUD") ? "CRUD" : strtolower(get_class($controller));
				$controller->view->render($nomeController, $controller->getViewRender(), $controller->getLayout());

			}else{
				echo 'erro'; 
			}
		}else{ 

			$controller->setViewRender(strtolower(get_class($controller))."/index");
			$controller->GET = &$variaveisGet;
			$controller->index();
			$controller->view->adicionarDados($controller->getDados());
			$nomeController = (get_parent_class($controller) == "ControllerCRUD") ? "CRUD" : strtolower(get_class($controller));
			$controller->view->render($nomeController, $controller->getViewRender(), $controller->getLayout());

		}		
	}
}

?>