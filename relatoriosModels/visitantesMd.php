<?php 
class visitantesMd extends classePdo{

	public function pegarVisitantes($campos, $onde){
		$tabela = PREFIXO."visitantes";

		$where = $this->onde($onde);

		$campos = implode("`, `$tabela`.`", $campos);
		$campos = "`$tabela`.`$campos`";

		$sql = "SELECT $campos FROM `$tabela` $where ORDER BY `$tabela`.`nome` ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	private function onde($array)
	{
		$tabela = PREFIXO."visitantes";
		$where = "";

		if($array['sexo'] != "")
			$where .= ($where == "") ? "`$tabela`.`sexo` = '" . (int)$array['sexo'] . "'" : ", `$tabela`.`sexo` = '" . (int)$array['sexo'] . "'";

		if($array['estadocivil'] != "")
		{
			mysql_connect(DB_HOST, DB_USER, DB_PASS);
			$estadoC = mysql_real_escape_string($array['estadocivil']);
			$where .= ($where == "") ? "`$tabela`.`estadocivil` = '" . $estadoC . "'" : ", `$tabela`.`estadocivil` = '" . $estadoC . "'";
		}

		if($where != "")
			$where = "WHERE " . $where;

		return $where;
	}
}
?>