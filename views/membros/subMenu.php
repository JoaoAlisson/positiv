<?php
	$this->html->subMenuCor("teal");
	$this->html->subMenuItem("Membros", "membros", "", "users");
	$this->html->subMenuItem("Ministérios", "ministerios", "", "list");
	$this->html->subMenuItem("Consagrações", CONTROLLER, "editar", "tags");
	$this->html->subMenuItem("Cargos", CONTROLLER, "editar", "sitemap");
	$this->html->subMenuItem("Funcionários", CONTROLLER, "editar", "male");
?>