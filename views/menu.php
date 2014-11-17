<?php
	$this->html->menu("Igreja", "igreja", "", "building", "teal", false,
					  array("Membros"     => array("membros", "", "users"),
					  	    "Consagrações"=> array("consagracoes", "", "tags"),
							"Ministérios" => array("ministerios", "", "list"),
							"Funções"	  => array("funcoes", "", "pin"),
							"Visitantes"  => array("visitantes", "", "male")));

	$this->html->menu("Usuários", "login", "", "basic users", "green", false, array());

	$this->html->menu("Programação", "programacao", "", "basic date", "blue", false,
					  array("Cadastrar Evento"     => array("programacao", "cadastrar", "plus", false)));

	$this->html->menu("Patrimônio", "patrimonio", "", "suitcase", "red", false,
					  array("Cadastrar Patromônio" => array("patrimonio", "cadastrar", "plus", false)));

	$this->html->menu("Finanças", "financas", "", "dollar", "orange", false,
					  array("Categorias"     => array("categorias", "", "bookmark"),
							"Entradas" 	     => array("entradas", "", "up"),
							"Saídas"         => array("saidas", "", "down"),
							"Caixa"	         => array("caixa", "", "money"),
							"Dízimos/Ofertas"=> array("", "", "")));	

	$this->html->menu("Relatórios", "relatorios", "", "basic chart", "purple", false, array());

	$this->html->menu("Documentos", "documentos", "", "file", "purple", true, array());
?>