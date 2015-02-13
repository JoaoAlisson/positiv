<?php
class BancoAdm {

	private $banco;
	private $campo;
	private $utilizaEstados = false;
	private $utilizaCidades = false;

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
			mysql_query("CREATE DATABASE " . $nome);
			$this->selecionaBanco($nome);
		}
	}

	public function criarTabela($nome){
		if(!$this->existeTabela($nome))
			mysql_query("CREATE TABLE " . $nome . "( id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id))");
	}

	public function deletarTabela($nome){
		if($this->existeTabela($nome))
			mysql_query("DROP TABLE " . $nome);
	}

	public function deletarCampo($campo, $tabela){
		if($this->existeCampo($campo, $tabela))
			mysql_query("ALTER TABLE $tabela DROP COLUMN $campo");
	}

	public function getUtilizaEstados(){
		return $this->utilizaEstados;
	}

	public function getUtilizaCidades(){
		return $this->utilizaCidades;
	}	

	public function criarCampo($campo, $tabela, $tipo, $complemento = ""){
		if($tipo == "estado" || $tipo == "cidade"){
			if($tipo == "estado"){
				$this->criarEstados();
				$this->utilizaEstados = true;
			}else{
				$this->criarCidades();
				$this->utilizaCidades = true;
			}
		}
			if(!$this->existeCampo($campo, $tabela))
				mysql_query("ALTER TABLE $tabela ADD COLUMN $campo ". $this->campo->tipo($tipo) ." NOT NULL $complemento");
	}

	public function criarEstados(){
		$tabela = PREFIXO."estados";
		if(!$this->existeTabela($tabela)){
			include_once(RAIZ . SEPARADOR . "libs". SEPARADOR . "Estados.php");
			$estados = new Estados();
			$estados->criarEstados();
		}
	}

	public function criarCidades(){
		$tabela = PREFIXO."cidades";
		if(!$this->existeTabela($tabela)){
			include_once(RAIZ . SEPARADOR . "libs" . SEPARADOR . "Cidades.php");
			$cidades = new Cidades();
			$cidades->criarCidades();
		}
	}	

	public function existeCampo($campo, $tabela){
		return $this->pesquisa("SHOW COLUMNS FROM $tabela LIKE '$campo'");

	}

	public function alterarCampo($campo, $tabela, $novoTipo, $complemento = ""){
		if($novoTipo == "estado" || $novoTipo == "cidade"){
			if($novoTipo == "estado"){
				$this->utilizaEstados = true;
				$this->criarEstados();
			}else{
				$this->utilizaCidades = true;
				$this->criarCidades();
			}
		}		
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

		$tabelaUsuario = PREFIXO . "usuarios";
		$tabelaUsuariostipos = PREFIXO . "usuariostipos";
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

	public function triggerIncremento($tabela) {
		$this->incremtDecremt('+', $tabela);
	}

	public function triggerDecremento($tabela) {
		$this->incremtDecremt('-', $tabela);
	}	

	private function incremtDecremt($sinal, $tabela) {
		$campo = explode(PREFIXO, $tabela);
		$campo = $campo[1];		
		$funcao = 'UPDATE ' . PREFIXO . "qtds SET $campo = $campo $sinal 1 WHERE id = 1;";
		$nome = ($sinal == '+') ? $tabela . 'Inc' : $tabela . 'Dec';
		$evento = ($sinal == '+') ? 'INSERT' : 'DELETE';
		$this->criarTrigger($tabela, $nome, $funcao, 'AFTER', $evento);
	}

	public function triggerLimite($tabela, $maximo) {
		
		$campo = explode(PREFIXO, $tabela);
		$campo = $campo[1];
		$funcao = "SELECT $campo INTO @qtd FROM " . PREFIXO . "qtds WHERE id = 1;
				   IF (@qtd >= $maximo) THEN
				   SIGNAL SQLSTATE '12345';
    			   END IF;";
   		$nome = $tabela . 'Limite';
   		$this->criarTrigger($tabela, $nome, $funcao, 'BEFORE', 'INSERT');
	}

	private function criarTrigger($tabela, $nome, $funcao, $momento, $evento) {

		$this->deletarTrigger($nome);

		$sql = "CREATE TRIGGER $nome
				$momento $evento
				ON $tabela
				FOR EACH ROW
				BEGIN
					$funcao
				END";

		mysql_query($sql);		
	}

	public function deletarTrigger($nome) {
		mysql_query("DROP TRIGGER IF EXISTS  `$nome`;");
	}

	public function deletarTirgsTabela($tabela) {
		$this->deletarTrigger($tabela . 'Inc');
		$this->deletarTrigger($tabela . 'Dec');
		$this->deletarTrigger($tabela . 'Limite');
	}

	public function realizaQuery($sql) {
		mysql_query($sql);
	}
}
?>