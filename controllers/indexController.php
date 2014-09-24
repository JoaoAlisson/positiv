<?php 
class index extends Controller  {
	function __construct(){
		parent::__construct();
		$this->permissao = "tudo";
	}

	public function teste($parametro){
		echo  "you are in teste";
		echo "<br/>Parametro passado: ". $parametro;
	}

	public function index(){
		//$this->usarLayout(false);	
	}

	public function details(){
		 $this->view->render('index/index');
	}
}
?>