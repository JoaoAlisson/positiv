<?php 
class funcionariosMd extends classePdo{

	public function cargos(){
		$consagracoes = $this->pegar(null, array('id', 'nome'), 'cargos');
		return $consagracoes;
	}

	public function pegarFuncionarios($campos, $onde){

		$tbMembro 		= PREFIXO."membros";
		$tbFcun   		= PREFIXO."func_nao_membro";
		$tbFuncionario 	= PREFIXO."funcionarios";
		$tbCargo		= PREFIXO."cargos";

		$campos  = implode("`, `$tbFuncionario`.`", $campos);
		$campos  = "`$tbFuncionario`.`$campos`, ";
		$campos .= "(CASE 
						WHEN (`$tbFuncionario`.`membro` > 0)
						THEN `$tbMembro`.`nome`
						ELSE `$tbFcun`.`nome`
 					END) AS `nome`,
					(CASE 
						WHEN (`$tbFuncionario`.`membro` > 0)
						THEN `$tbMembro`.`cpf`
						ELSE `$tbFcun`.`cpf`
 					END) AS `cpf`,
					(CASE 
						WHEN (`$tbFuncionario`.`membro` > 0)
						THEN `$tbMembro`.`rg`
						ELSE `$tbFcun`.`rg`
 					END) AS `rg`";

		$join  = "LEFT JOIN `$tbMembro` ON `$tbFuncionario`.`membro` = `$tbMembro`.`id` ";
		$join .= "LEFT JOIN `$tbFcun` ON `$tbFuncionario`.`func` = `$tbFcun`.`id` ";
		$join .= "LEFT JOIN `$tbCargo` ON `$tbFuncionario`.`cargo` = `$tbCargo`.`id`";

		$where = $this->onde($onde);

		$sql = "SELECT $campos, `$tbCargo`.`nome` AS `cargo` FROM `$tbFuncionario` $join $where ORDER BY `nome` ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	private function onde($array)
	{
		$tabela = PREFIXO."funcionarios";

		$where = "";
		if($array['cargo'] != "")
			$where = "`$tabela`.`cargo` = '" . (int)$array['cargo'] . "'";

		if($array['situacao'] != "")
		{
			mysql_connect(DB_HOST, DB_USER, DB_PASS);
			$situacao = mysql_real_escape_string($array['situacao']);
			$where .= ($where == "") ? "`$tabela`.`situacao` = '" . $situacao . "'" : " AND `$tabela`.`situacao` = '" . $situacao . "'";
		}

		if($array['inss'] != "")
		{
			$inss = ($array['inss'] == 1) ? "`$tabela`.`inss` = 1" : "`$tabela`.`inss` = 0";
			$where .= ($where != "") ? " AND $inss" : $inss;
		}

		if($where != "")
			$where = "WHERE " . $where;

		return $where;
	}

	public function pegaCargo($id)
	{
		$retorno = $this->pegar($id, array("nome"), "cargos");
		return $retorno[0]['nome'];
	}
}
?>