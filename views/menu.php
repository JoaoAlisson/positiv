<?php
	$this->html->menu("Igreja", "membros", "", "university", "teal", false,
					  array("Membros"     => array("membros", "", "users"),
					  	    "Consagrações"=> array("consagracoes", "", "tags"),
							"Ministérios" => array("ministerios", "", "list"),
							"Funções"	  => array("funcoes", "", "pin"),
							"Visitantes"  => array("visitantes", "", "male"),
							"Integrantes" => array("integrantes", "", "", false),
							"Visitas"  	  => array("visitas", "", "", false)));

	$this->html->menu("Usuários", "login", "", "basic users", "green", false, array());

	$this->html->menu("Programação", "programacao", "", "calendar", "blue", false,
					  array("Cadastrar Evento"     => array("programacao", "cadastrar", "plus", false)));

	$this->html->menu("Patrimônio", "patrimonio", "", "suitcase", "red", false,
					  array("Cadastrar Patromônio" => array("patrimonio", "cadastrar", "plus", false)));

	$this->html->menu("Finanças", "caixa", "", "dollar", "orange", false,
					  array("Categorias"     => array("categorias", "", "bookmark"),
							"Entradas" 	     => array("entradas", "", "up"),
							"Saídas"         => array("saidas", "", "down"),
							"Caixa"	         => array("caixa", "", "money"),
							"Dízimos-Ofertas"=> array("dizimos_ofertas", "", "")));	

	$this->html->menu("Relatórios", "relatorios", "", "bar chart", "purple", false, array());

	$this->html->menu("Documentos", "documentos", "", "file", "purple", true, array());
?>