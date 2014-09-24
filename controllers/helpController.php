<?php 
class help extends Controller{
	
	function __construct(){
		parent::__construct(); 
	}

	function index(){
		$this->view->render('index/index');		
	}

	public function other($arg   = false){

		require "models/helpModel.php";
		$model = new HelpModel();
		$this->view->blah = $model->blah();
	}
}
?>