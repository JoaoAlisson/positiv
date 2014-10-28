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
		if(file_exists("views/". CONTROLLER . "/subMenu.php"))
			require "views/". CONTROLLER . "/subMenu.php";
		else
			require "views/". $controller . "/subMenu.php";
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
		
		if(!$noInclude || $paginacaoAjax){

			if(!$noInclude){
				require "views/". $view . ".php";
			}else{
				echo "<div id='submenu'>";
					if(file_exists("views/". $controller . "/subMenu.php")){
						if(!isset($_POST['subClick'])){
							$this->adicionarSubMenu($controller);
						}	
					}	
				echo "</div><div id='subconteudo'>";
					require "views/". $view . ".php";  
				echo "</div>";
			}
		}else{		

			if(file_exists("views/". $view . ".php")){
				require "views/topo.php";
				echo "<div id='submenu'>";
					if(file_exists("views/". $controller . "/subMenu.php")){
						$this->adicionarSubMenu($controller);
					}	
				echo "</div><div id='subconteudo'>";
					$this->html->dados['nomeView'] = $view;							
					require "views/". $view . ".php";
				echo "</div>";
				require "views/rodape.php";

			}
		}

	}
}
?>