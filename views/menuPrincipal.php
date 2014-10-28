<?php 
  $this->html->menuPrincialItem("Home", "", "", "home", "gray", true);
  $this->html->menuPrincialItem("Igreja", array("igreja", "membros", "consagracoes", "cargos", "funcionarios"), "", "building", "teal");
  $this->html->menuPrincialItem("Usuários", "login", "", "basic users", "green");
  $this->html->menuPrincialItem("Calendário", "produtos", "", "basic date", "blue");
  $this->html->menuPrincialItem("Patrimônio", "", "", "suitcase", "red");
  $this->html->menuPrincialItem("Finanças", "", "", "dollar", "orange");
  $this->html->menuPrincialItem("Relatórios", "relatorios", "", "basic chart", "purple");
  $this->html->menuPrincialItem("Documentos", "relatorios", "", "text file", "purple", true);  
?>