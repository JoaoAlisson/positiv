<?php
class BancoAdm{

	private $banco;
	private $campo;

	function __construct(){
		mysql_connect( DB_HOST, DB_USER, DB_PASS);
		$this->selecionaBanco(DB_NAME);

		$this->campo = new TiposCampos();
	}

	public function existeBanco($nome){
		return $this->pesquisa("SHOW DATABASES LIKE '$nome'");
	}

	public function existeTabela($nome){
		return $this->pesquisa("SHOW TABLES LIKE '$nome'");
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
			mysql_query("CREATE TABLE ". $nome ."( id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id))");
	}

	public function deletarTabela($nome){
		if($this->existeTabela($nome))
			mysql_query("DROP TABLE ". $nome);
	}

	public function deletarCampo($campo, $tabela){
		if($this->existeCampo($campo, $tabela))
			mysql_query("ALTER TABLE $tabela DROP COLUMN $campo");
	}

	public function criarCampo($campo, $tabela, $tipo, $complemento = ""){
		if(!$this->existeCampo($campo, $tabela))
			mysql_query("ALTER TABLE $tabela ADD COLUMN $campo ". $this->campo->tipo($tipo) ." $complemento");

	}

	public function existeCampo($campo, $tabela){
		return $this->pesquisa("SHOW COLUMNS FROM $tabela LIKE '$campo'");

	}

	public function alterarCampo($campo, $tabela, $novoTipo, $complemento = ""){
		if($this->existeCampo($campo, $tabela))
			mysql_query("ALTER TABLE $tabela MODIFY COLUMN $campo ". $this->campo->tipo($novoTipo) ." NOT NULL ". $complemento);
	}

	private function pesquisa($query){
		$resultado = mysql_query($query);
		return mysql_num_rows($resultado);
	}

	public function tabelas(){
		$retorna = mysql_list_tables($this->banco);
		$tabelas = array();

		$tabelaUsuario = PREFIXO."usuarios";
		$tabelaUsuariostipos = PREFIXO."usuariostipos";
		while($tabela = mysql_fetch_array($retorna))
			if($tabela[0] != $tabelaUsuario && $tabela[0] != $tabelaUsuariostipos)
				array_push($tabelas, $tabela[0]);
	
		return $tabelas;
	}

	public function campos($tabela){
		$retorna = mysql_query("SHOW COLUMNS FROM $tabela");

		$campos = array();
		while($campo = mysql_fetch_array($retorna))
			array_push($campos, $campo[0]);		

		return $campos;
	}
}
?>