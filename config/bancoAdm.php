<?php
class BancoAdm{

	private $banco;

	function __construct(){
		mysql_connect( DB_HOST, DB_USER, DB_PASS);
		$this->selecionaBanco(DB_NAME);
	}

	public function existeBanco($nome){
		$resultado = mysql_query("SHOW DATABASES LIKE '$nome'");
		return mysql_num_rows($resultado);
	}

	public function existeTabela($nome){
		$resultado = mysql_query("SHOW TABLES LIKE '$nome'");
		return mysql_num_rows($resultado);
	}

	private function selecionaBanco($banco){
		$this->banco = $banco;
		if($this->existeBanco($banco))
			mysql_select_db($banco); 
	}

	public function criarBanco($nome = null){
		$nome = ($nome) ? $nome : $this->banco;
		if(!$this->existeBanco($nome)){
			mysql_query("CREATE DATABASE ". $nome);
			$this->selecionaBanco($nome);
		}
	}

	public function criarTabela($nome){
		if(!$this->existeTabela($nome))
			mysql_query("CREATE TABLE ". $nome ."(". $nome ." INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(". $nome ."))");
	}

	public function deletarTabela($nome){
		if($this->existeTabela($nome))
			mysql_query("DROP TABLE ". $nome);
	}
}
?>