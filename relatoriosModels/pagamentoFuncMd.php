<?php 
class pagamentoFuncMd extends classePdo{

	public function pegarPagamentos($funcionario) {

		$funcionario = (int)$funcionario;
		$ids = $this->pegarIds($funcionario);

		$retorna = array();
		foreach ($ids as $valor) {
			$dados = $this->pegarFuncionario($valor["id"]);
			$dados = $dados[0];
			$mes   = ($valor['mes'] < 10) ? '0'.$valor['mes'] : $valor['mes'];
			$dados['folha'] = $mes . '/' . $valor['ano'];

			$somas = $this->pegarSomaEventosFun($valor['folha'], $valor['id']);
			$dados['abonos'] = $somas['abonos'];
			$dados['descontos'] = $somas['descontos'];

			array_push($retorna, $dados);
		}

		return $retorna;
	}

	public function pegarNome($id) {
		$retorna = $this->pegarFuncionarios($id);
		return $retorna[0];
	}

	private function pegarFuncionario($id){
		$id = (int)$id;
		$tabela = PREFIXO."folha_funcionarios";
		$sql = "SELECT salario, inss FROM $tabela WHERE id = $id";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	private function pegarIds($funci)
	{	
		$funci = (int)$funci;
		$tabela = PREFIXO . 'folha_funcionarios';
		$tFolha = PREFIXO . 'folhas';
		$join   = "LEFT JOIN `$tFolha` ON `$tabela`.`folha` = `$tFolha`.`id`";
		$sql = "SELECT `$tabela`.`id`, `$tFolha`.`mes`, `$tFolha`.`ano`, `$tabela`.`folha` FROM `$tabela` $join WHERE `$tabela`.`funci` = '$funci' ORDER BY `$tabela`.`folha` ASC ";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		return $resultado;
	}

	public function pegarFuncionarios($id = "") {

		$tbMembro 	   = PREFIXO."membros";
		$tbFcun   	   = PREFIXO."func_nao_membro";
		$tbFuncionario = PREFIXO."funcionarios";

		$nome = "(CASE 
						WHEN (`$tbFuncionario`.`membro` > 0)
						THEN `$tbMembro`.`nome`
						ELSE `$tbFcun`.`nome`
 					END) AS `nome`";

		$where = "";
		if($id != "") {
			$where = "WHERE `$tbFuncionario`.`id` = '$id'";

			$nome .= ",
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
		}

		$join  = "LEFT JOIN `$tbMembro` ON `$tbFuncionario`.`membro` = `$tbMembro`.`id`";
		$join .= " LEFT JOIN `$tbFcun` ON `$tbFuncionario`.`func` = `$tbFcun`.`id`";

		$sql = "SELECT `$tbFuncionario`.`id`, $nome FROM `$tbFuncionario` $join $where";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		return $resultado;		

	}

	private function pegarSomaEventosFun($idFolha, $idFunc) {

		$retorna['abonos']    = $this->abonos(true, $idFolha, $idFunc);
		$retorna['descontos'] = $this->abonos(false, $idFolha, $idFunc);

		return $retorna;
	}

	private function abonos($abono, $idFolha, $idFunc) {
		$tabela = PREFIXO."descontos_abonos";
		
		$whereBasc = " AND (funcionario = '$idFunc' OR todos = '1')";
		if($abono)
			$whereAb   = " AND tipo = '1'";
		else
			$whereAb   = " AND tipo = '2'";

		$sql = "SELECT SUM(valor) FROM $tabela WHERE folha = '$idFolha'" . $whereBasc . $whereAb;

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		$resultado = $resultado[0]['SUM(valor)'];

		return $resultado;		
	}	
}
?>