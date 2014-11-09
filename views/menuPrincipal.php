<?php
  $this->html->menuPrincialItem("Home", "", "", "home", "gray", true);
  foreach ($this->html->menu as $key => $array) {
  	$controllers = array($array["controller"]);
  	foreach ($array["subMenus"] as $chave => $valor)
  		array_push($controllers, $valor[0]);
  	$this->html->menuPrincialItem($array["nome"], $controllers, $array["view"], $array["icone"], $array["cor"], $array["naoInvertido"]);
  }	
  /**
  $this->html->menuPrincialItem("Igreja", array("igreja", "membros", "consagracoes", "cargos", "funcionarios"), "", "building", "teal");
  $this->html->menuPrincialItem("Usuários", "login", "", "basic users", "green");
  $this->html->menuPrincialItem("Programação", "programacao", "", "basic date", "blue");
  $this->html->menuPrincialItem("Patrimônio", "patrimonio", "", "suitcase", "red");
  $this->html->menuPrincialItem("Finanças", "financas", "", "dollar", "orange");
  $this->html->menuPrincialItem("Relatórios", "relatorios", "", "basic chart", "purple");
  $this->html->menuPrincialItem("Documentos", "relatorios", "", "text file", "purple", true);  
  */
?>