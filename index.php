<?php
	// Library
	require 'libs/Database.php';
	require 'libs/Sessao.php';

	//Use an autoloader!
	require 'libs/bootstrap.php';
	require 'libs/Html.php';
	require 'libs/Controller.php';
	require 'libs/ControllerCrud.php';
	require 'libs/View.php';
	require 'libs/Model.php';


	require 'config/config.php';
	require 'config/tiposUsuarios.php';

	$app = new Bootstrap();
?>