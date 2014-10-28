<?php
	$this->html->subMenuCor("teal");
	$this->html->subMenuItem("Membros", CONTROLLER, "cadastrar", "users");
	$this->html->subMenuItem("Ministérios", CONTROLLER, "", "list");
	$this->html->subMenuItem("Consagrações", CONTROLLER, "editar", "tags");
	$this->html->subMenuItem("Cargos", CONTROLLER, "editar", "sitemap");
	$this->html->subMenuItem("Funcionários", CONTROLLER, "editar", "male");
?>