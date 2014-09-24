<?php 
class dashboard extends Controller{

	private $nome = 1;

	function __construct(){
		parent::__construct();
		Session::init();
		$logged = Session::get('loggedIn');
		if($logged == false){
			Session::destroy();
			header('location: ../login');
			exit;
		}

		//$this->view->js = array('dashboard/js/default');
	}

	function index(){

	}

	function logout(){
		Session::destroy();
		header('location: ../login');
		exit;
	}
}
 
?>