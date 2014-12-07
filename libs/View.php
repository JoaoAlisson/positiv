<?php 
class View {

	function __construct(&$informacoes){
		$this->html = new HTML($informacoes);
	}

	private $dados = array();


	public function adicionarDados($dados){
		$this->dados = $dados;
	}

	private function adicionarSubMenu($controller){
		if(file_exists("views/". CONTROLLER . "/subMenu.phtml"))
			require "views/". CONTROLLER . "/subMenu.phtml";
		else
			require "views/". $controller . "/subMenu.phtml";
		echo "</div>
			 </div>
			</div>";						
	}

	public function render($controller, $view, $noInclude = false){
		
		$paginacaoAjax = false;
		if(isset($_POST['ajaxPg']))
			$paginacaoAjax = true;

		$arquivo = $controller . "/". $view;
		$dados = $this->dados;
		
		require "views/menu.phtml";
		if(!$noInclude || $paginacaoAjax){

			if(!$noInclude){
				require "views/". $view . ".phtml";
			}else{
				echo "<div id='submenu'>";
					if(file_exists("views/". $controller . "/subMenu.phtml")){
						if(!isset($_POST['subClick'])){
							$this->adicionarSubMenu($controller);
						}	
					}	
				echo "</div><div id='subconteudo'>";
					require "views/". $view . ".phtml";  
				echo "</div>";
			}
		}else{		

			if(file_exists("views/". $view . ".phtml")){
				require "views/topo.phtml";
				echo "<div id='submenu'>";
					if(file_exists("views/". $controller . "/subMenu.phtml")){
						$this->adicionarSubMenu($controller);
					}	
				echo "</div><div id='subconteudo'>";
					$this->html->dados['nomeView'] = $view;							
					require "views/". $view . ".phtml";
				echo "</div>";
				require "views/rodape.phtml";

			}
		}

	}
}
?>