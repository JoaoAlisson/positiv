<?php 
class membrosMd extends classePdo{

	public function consagracoes(){
		$consagracoes = $this->pegar(null, array('id', 'nome'), 'consagracoes');
		return $consagracoes;
	}

	public function pegarMembros($campos, $onde){
		$tabela = PREFIXO."membros";
		$tabelaConsagracao = PREFIXO."consagracoes";

		$where = $this->onde($onde);

		$campos = implode("`, `$tabela`.`", $campos);
		$campos = "`$tabela`.`$campos`";
		$join = "LEFT JOIN `$tabelaConsagracao` ON `$tabela`.`consagracao` = `$tabelaConsagracao`.`id`";
		$sql = "SELECT $campos, `$tabelaConsagracao`.`nome` AS `consagracao` FROM `$tabela` $join $where ORDER BY `$tabela`.`nome` ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	private function onde($array)
	{
		$tabela = PREFIXO."membros";
		$where = "";
		if($array['consagracao'] != "")
			$where = "`$tabela`.`consagracao` = '" . (int)$array['consagracao'] . "'";

		if($array['sexo'] != "")
			$where .= ($where == "") ? "`$tabela`.`sexo` = '" . (int)$array['sexo'] . "'" : ", `$tabela`.`sexo` = '" . (int)$array['sexo'] . "'";

		if($array['estadocivil'] != "")
		{
			mysql_connect(DB_HOST, DB_USER, DB_PASS);
			$estadoC = mysql_real_escape_string($array['estadocivil']);
			$where .= ($where == "") ? "`$tabela`.`estadocivil` = '" . $estadoC . "'" : ", `$tabela`.`estadocivil` = '" . $estadoC . "'";
		}

		if($array['batizado'] != "")
		{
			$batizado = ($array['batizado'] == 1) ? "`$tabela`.`dataBatismo` != '0000-00-00'" : "`$tabela`.`dataBatismo` = '0000-00-00'";
			$where .= ($where != "") ? ", $batizado" : $batizado;
		}

		if($where != "")
			$where = "WHERE " . $where;

		return $where;
	}

	public function pegaConsagracao($id)
	{
		$retorno = $this->pegar($id, array("nome"), "consagracoes");
		return $retorno[0]['nome'];
	}
}
?>