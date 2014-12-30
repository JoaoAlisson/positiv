<?php 
class patrimonioMd extends classePdo{

	public function pegarPatrimonios($campos, $onde = ""){
		$tabela = PREFIXO . "patrimonio";
		$tabelaMinisterio = PREFIXO . "ministerios";
		$where = $this->onde($onde);

		$campos = implode("`, `$tabela`.`", $campos);
		$campos = "`$tabela`.`$campos`";

		$join = "LEFT JOIN `$tabelaMinisterio` ON `$tabela`.`ministerio` = `$tabelaMinisterio`.`id`";
		$sql = "SELECT $campos, `$tabelaMinisterio`.`nome` AS 'ministerio' FROM `$tabela` $join $where ORDER BY `$tabela`.`nome` ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function ministerios(){
		$retorna = $this->pegar(null, array('id', 'nome'), 'ministerios');
		return $retorna;
	}	

	public function pegaMinisterio($id)
	{
		$retorno = $this->pegar($id, array("nome"), "ministerios");
		return $retorno[0]['nome'];
	}

	private function onde($array)
	{
		$tabela = PREFIXO."patrimonio";
		$where = "";

		if($array['ministerio'] != "")
			$where = "`$tabela`.`ministerio` = '" . (int)$array['ministerio'] . "'";

		if($array['situacao'] != "")
		{
			mysql_connect(DB_HOST, DB_USER, DB_PASS);
			$situacao = mysql_real_escape_string($array['situacao']);
			$where .= ($where == "") ? "`$tabela`.`situacao` = '" . $situacao . "'" : ", `$tabela`.`situacao` = '" . $situacao . "'";
		}

		if($where != "")
			$where = "WHERE " . $where;

		return $where;
	}
}
?>