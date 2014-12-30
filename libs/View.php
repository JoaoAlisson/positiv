<?php
class View {

	private $caminhoView;
	function __construct(&$informacoes){
		$this->html = new HTML($informacoes);
		$this->caminhoView = RAIZ . SEPARADOR . "views" . SEPARADOR;
	}

	private $dados = array();


	public function adicionarDados($dados){
		$this->dados = $dados;
	}

	private function adicionarSubMenu($controller){
		if(file_exists($this->caminhoView . CONTROLLER . SEPARADOR ."subMenu.phtml"))
			require $this->caminhoView. CONTROLLER . SEPARADOR ."subMenu.phtml";
		else
			require $this->caminhoView. $controller . SEPARADOR ."subMenu.phtml";
		echo "</div>
			 </div>
			</div>";						
	}

	public function render($controller, $view, $noInclude = false){
		$paginacaoAjax = false;
		if(isset($_POST['ajaxPg']))
			$paginacaoAjax = true;

		$arquivo = $controller . CONTROLLER. $view;
		$dados = $this->dados;
		
		require $this->caminhoView."menu.phtml";
		if(!$noInclude || $paginacaoAjax){
			if(!$noInclude){
				require $this->caminhoView . $view . ".phtml";
			}else{
				echo "<div id='submenu'>";
					if(file_exists($this->caminhoView . $controller . SEPARADOR . "subMenu.phtml")){
						if(!isset($_POST['subClick'])){
							$this->adicionarSubMenu($controller);
						}	
					}	
				echo "</div><div id='subconteudo'>";
					require $this->caminhoView . $view . ".phtml";  
				echo "</div>";
			}
		}else{		

			if(file_exists($this->caminhoView . $view . ".phtml")){
				require $this->caminhoView . "topo.phtml";
				echo "<div id='submenu'>";
					if(file_exists($this->caminhoView . $controller . SEPARADOR ."subMenu.phtml")){
						$this->adicionarSubMenu($controller);
					}	
				echo "</div><div id='subconteudo'>";
					$this->html->dados['nomeView'] = $view;		
					require $this->caminhoView . $view . ".phtml";
				echo "</div>";
				require $this->caminhoView."rodape.phtml";

			}
		}

	}
}
?>