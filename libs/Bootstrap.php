<?php 
class Bootstrap {
	
	function __construct(){
		
		if($this->verificarLogado()){
			if(Sessao::pegar('ativo') == 0)
				$this->bloqueado();
			else
				$this->carregarPgs();
		}else{
			$this->carregarTelaLogin();
		}
	}

	private $urlTratada = array();

	private function bloqueado() {

		require RAIZ . SEPARADOR . 'views' . SEPARADOR . 'bloqueado' . SEPARADOR . 'bloqueado.phtml';
		exit();
	}

	public function verificarLogado(){
		Sessao::iniciar();
		$this->trataUrl();
		$logado   = Sessao::pegar('logado');
		$sis_user = (Sessao::pegar('sis_user') == $this->urlTratada['user_sys']) ? true : false;
		$sis 	  = (Sessao::pegar('sis') == 'igrj') ? true : false;

		if($logado && $sis_user && $sis)
			return true;
		else
			return false;
	}

	public function carregarTelaLogin(){

		require RAIZ . SEPARADOR . 'controllers' . SEPARADOR . 'loginController.php';

		$controller = new login($this->urlTratada['user_sys']);
		$controller->setViewRender('login' . SEPARADOR . 'index');
		$controller->index();
		$controller->view->adicionarDados($controller->getDados());
		$controller->view->render('login', 'login' . SEPARADOR . 'index', false);			
	}

	private function trataUrl() {
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url =  explode('/', $url);

		//criando as variaveil $_GET[].
		if(isset($url[2])){
			$gets = $url;
			unset($gets[0], $gets[1]);
		}


		$retorna['user_sys']      = isset($url[0]) ? $url[0] : '';
		define('DB_NAME', PREFIXO_TB_CLIENTES . $retorna['user_sys']);
		$retorna['contAction'][0] = isset($url[1]) ? $url[1] : null;
		$retorna['contAction'][1] = isset($url[2]) ? $url[2] : null;
		$retorna['gets']          = array();

		Uteis::setSis_user($retorna['user_sys']);
		if(isset($url[3])){
			$retorna['gets'] = $url;
			unset($retorna['gets'][0], $retorna['gets'][1], $retorna['gets'][2]);
		}
		
		$this->urlTratada = $retorna;
	}

	public function carregarPgs(){

		if(empty($this->urlTratada))
			$this->trataUrl();

		$url = $this->urlTratada['contAction'];

		//criando as variaveil $_GET[].
		$variaveisGet = array();
		if(!empty($this->urlTratada['gets'])){			
			foreach ($this->urlTratada['gets'] as $nome => $valor) {
				$vr = explode(":", $valor);
				$variaveisGet[$vr[0]] = $vr[1];
			}	
		}

		if(empty($url[0])){
			require RAIZ . SEPARADOR . 'controllers' . SEPARADOR . 'indexController.php';
			$controller = new Index();

			$controller->setViewRender(strtolower(get_class($controller))."/index");
			$controller->GET = &$variaveisGet;
			$controller->index(); 
			$controller->view->adicionarDados($controller->getDados());
			$controller->view->render(strtolower(get_class($controller)),$controller->getViewRender(), $controller->getLayout());

			return false;
		}

		$file = RAIZ . SEPARADOR . 'controllers' . SEPARADOR . $url[0].'Controller.php';
		if(file_exists($file)){
			require $file;

		}else{
			require RAIZ . SEPARADOR . 'controllers' . SEPARADOR . 'errorController.php';
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

				define("VIEW", $url[1]);
				$metodo = new ReflectionMethod($controller->informacoes['nomeController'], $url[1]);

				if($metodo->isPublic()) 
					$controller->{$url[1]}();
				else
					header('location: '. URL);
				

				$controller->view->adicionarDados($controller->getDados());
				$nomeController = (get_parent_class($controller) == "ControllerCRUD") ? "CRUD" : strtolower(get_class($controller));
				
				$controller->view->render($nomeController, $controller->getViewRender(), $controller->getLayout());

			}else{
				header('location: '. URL);
			}
		}else{ 

			$controller->setViewRender(strtolower(get_class($controller))."/index");
			$controller->GET = &$variaveisGet;
			$controller->index();
			$controller->view->adicionarDados($controller->getDados());
			$nomeController = (get_parent_class($controller) == "ControllerCRUD") ? "CRUD" : strtolower(get_class($controller));
			$controller->view->render($nomeController, $controller->getViewRender(), $controller->getLayout());

		}	
		if(!defined("VIEW"))
			define("VIEW", "");	
	}
}

?>