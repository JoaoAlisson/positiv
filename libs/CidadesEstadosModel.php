<?php 
class CidadesEstadosModel extends Model {

/*	
	public function pegarCidades($estado = null) {
		$tabela = "cidades";
		$retorna = "";
		if($estado != null || $estado != "")	
			$retorna = $this->pegarOndeGenerico("estado = $estado", array('id', 'cidade'), $tabela);
				
		return $retorna;
	}

	public function pegarEstados() {
		$tabela = "estados";
		$retorna = $this->pegarTodosGenerico($tabela);
		return $retorna;
	}
*/

	public function pegarEstados() {

		return $this->pegarAdm('estado', 'estados');
	}

	public function pegarCidades($estado = null) {

		$retorna = "";
		if($estado != null || $estado != "") {	
			$where = 'WHERE estado = ' . (int)$estado;
			$retorna = $this->pegarAdm('cidade', 'cidades', $where);
		}		

		return $retorna;
	}	

	private function pegarAdm($campo, $tabela, $where = '') {

		$tabela = PREFIXO_ADM . $tabela;
		$pdo = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_ADM, DB_USER, DB_PASS);

		$sql = "SELECT id, $campo FROM $tabela $where;";
		$query = $pdo->prepare($sql);
		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);		
	}
}
?>