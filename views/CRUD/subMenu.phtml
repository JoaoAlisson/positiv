<?php
	if($this->html->subMenu == null){
		foreach ($this->html->menu as $key => $item) {
			if($item["controller"] == CONTROLLER){
				$this->html->subMenu['itens'] = $item["subMenus"];
				$this->html->subMenu['cor'] = $item["cor"]; 	
			}

			foreach ($item["subMenus"] as $nomeSub => $submenu) {
				if($submenu[0] == CONTROLLER){
					$this->html->subMenu['itens'] = $item["subMenus"];
					$this->html->subMenu['cor'] = $item["cor"]; 
				}
			}			
		}
	}

	if($this->html->subMenu != null && !empty($this->html->subMenu)){
		$this->html->subMenuCor($this->html->subMenu['cor']);
		foreach ($this->html->subMenu['itens'] as $nome => $value) {
			if(!isset($value[3]))
				$this->html->subMenuItem($nome, $value[0], $value[1], $value[2]);
		}
	}else{
		$this->html->subMenuCor("teal");
		$this->html->subMenuItem("Cadastrar", CONTROLLER, "cadastrar", "add");
		$this->html->subMenuItem("Todos", CONTROLLER, "", "list");
		$this->html->subMenuItem("Editar", CONTROLLER, "editar", "edit");
	}
?>