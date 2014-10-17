<?php 
	class Desenvolvimento extends Controller{

		function __construct(){
			if(MODO_DESENVOLVIMENTO){
				parent::__construct();
				$this->usarLayout(false);
			}else{
				header('location: ' . URL);
				exit();
			}

		}

		public function js(){

		}

		public function atualizar(){
			$atualiza = new AtualizarBanco();
			$atualiza->atualizar();
		}

}
?>