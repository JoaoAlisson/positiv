<?php 
class folhasModel extends Model{
 
	public $tipos = array( "mes"    => "inteiro",
						   "ano"	=> "inteiro",
						   "total"  => "moeda",
						   "contab" => "inteiro");

	private $tabelaInss = "";

	public function retornaQuantidade($ano, $mes){
		$tabela = PREFIXO."folhas";
		$ano = (int)$ano;
		$mes = (int)$mes;

		$sql = "SELECT COUNT(*) FROM $tabela WHERE ano = $ano AND mes = $mes";
		$resposta =  $this->query($sql);

		return $resposta->fetchColumn();
	}

	private function pegarTabelaInss(){
		$tabela = PREFIXO."inss";
		
		$sql = "SELECT id, inicio, fim, taxa FROM $tabela";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		return $resultado;			
	}

	public function funcsDaFolha($idFolha, $campos = ""){
		$tabela = PREFIXO."folha_funcionarios";
		$idFolha = (int)$idFolha;
		if($campos == "")
			$campos = "id, nome";
		else
			$campos = implode(", ", $campos);

		$sql = "SELECT $campos FROM $tabela WHERE folha = $idFolha";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function pegarFuncionario($id){
		$id = (int)$id;
		$tabela = PREFIXO."folha_funcionarios";
		$sql = "SELECT * FROM $tabela WHERE id = $id";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function todos(){
		$tbMembro 		= PREFIXO."membros";
		$tbFcun   		= PREFIXO."func_nao_membro";
		$tbFuncionario 	= PREFIXO."funcionarios";
		$tbCargo		= PREFIXO."cargos";

		$campos = "(CASE 
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

		$join = "LEFT JOIN `$tbMembro` ON `$tbFuncionario`.`membro` = `$tbMembro`.`id`";
		$join .= " LEFT JOIN `$tbFcun` ON `$tbFuncionario`.`func` = `$tbFcun`.`id`";
		$join .= " LEFT JOIN `$tbCargo` ON `$tbFuncionario`.`cargo` = `$tbCargo`.`id`";

		$sql = "SELECT `$tbFuncionario`.`id`, `$tbFuncionario`.`salario`, `$tbFuncionario`.`inss`, `$tbCargo`.`nome` AS `cargo`, $campos FROM `$tbFuncionario` $join WHERE `$tbFuncionario`.`situacao` != 'Demitido'";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		return $resultado;		
	}

	private function calculaInss($valor){
		$taxa = $this->retornaTaxa($valor);
		$resultado = $valor * $taxa/100;
		return $resultado;
	}

	private function setaTabelaInss(){
		$tabela = $this->pegarTabelaInss();
		$this->tabelaInss = $tabela;
	}

	private function retornaTaxa($valor){

		if($this->tabelaInss == "")
			$this->setaTabelaInss();
		
		if($valor < $this->tabelaInss[0]['fim']){
			return $this->tabelaInss[0]['taxa'];
		}else{
			if($valor < $this->tabelaInss[1]['fim'])
				return $this->tabelaInss[1]['taxa'];
			else
				return $this->tabelaInss[2]['taxa'];
		}
	}		

	public function insereFuncionarios($idFolha){

		$todosFuncionarios = $this->todos();
		//$this->setaTabelaInss();

		$idFolha = (int)$idFolha;

		$tabela = PREFIXO."folha_funcionarios";
		$sql = "INSERT INTO $tabela (folha, funci, nome, cpf, rg, salario, inss, cargo) VALUES";
		$inssTotal = (double)0;
		$salarioTotal = (double)0;
		foreach ($todosFuncionarios as $key => $campos) {
			$inss = 0;
			if($campos['inss'] == 1)
				$inss = $this->calculaInss($campos['salario']);

			$inssTotal = $inssTotal + $inss;
			$salarioTotal = $salarioTotal + $campos['salario'];

			if($key == 0)
				$sql .= " ('$idFolha', '".$campos['id']."', '".$campos['nome']."', '".$campos['cpf']."', '".$campos['rg']."', '".$campos['salario']."', '$inss', '".$campos['cargo']."')";
			else
				$sql .= ", ('$idFolha', '".$campos['id']."', '".$campos['nome']."', '".$campos['cpf']."', '".$campos['rg']."', '".$campos['salario']."', '$inss', '".$campos['cargo']."')";
		}

		$sth = $this->prepare($sql);
		$sth->execute();

		$total = $salarioTotal - $inssTotal;

		$this->setTotalFolha($idFolha, $total);
	}

	private function setTotalFolha($id, $total){
		$tabela = PREFIXO."folhas";
		$id = (int)$id;
		$sth = $this->prepare("UPDATE $tabela SET total = $total WHERE id = $id");
		$sth->execute();
	}

	public function eventosFuncionario($idFuncionario = "", $idFolha){
		$tabela = PREFIXO . "descontos_abonos";
		$idFolha = (int)$idFolha;
		$idFuncionario = (int)$idFuncionario;
		$funcionario = ($idFuncionario != "") ? "(funcionario = '$idFuncionario') OR" : "";
		$sql = "SELECT * FROM $tabela WHERE $funcionario (folha = '$idFolha' AND todos = '1') ORDER BY id DESC"; 
		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		return $resultado;			
	}

	public function qtdFunctionariosFolha($idFolha){

		$tabela = PREFIXO."folha_funcionarios";
		$idFolha = (int)$idFolha;	

		$sql = "SELECT COUNT(*) FROM $tabela WHERE folha = $idFolha";
		$resposta =  $this->query($sql);

		return $resposta->fetchColumn();		
	}

	public function atualizaTotalFolhaTodos($soma, $valor, $id, $qtd){
		$valor = $this->trataValor($valor);
		$valor = $qtd*$valor;
		$this->atualizaTotalFolha($soma, $valor, $id, false);
	}

	public function atualizaTotalFolha($soma, $valor, $id, $tratar = true){

		if($tratar)
			$valor = $this->trataValor($valor);

		$id = (int)$id;
		$valor = (double)$valor;

		$sinal = ($soma == 'soma') ? "+" : "-";
		$tabela = PREFIXO."folhas";
		$sth = $this->prepare("UPDATE $tabela SET total = total $sinal $valor WHERE id = $id");
		$sth->execute();
	}

	private function trataValor($valor){
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",", ".", $valor);

		return $valor;
	}

	public function atualizaFuncFolha($id, $campos){

		$inss = 0;
		if($campos['inss'] == 1)
			$inss = $this->calculaInss($campos['salario']);

		$tabela = PREFIXO."folha_funcionarios";
		$sth = $this->prepare("UPDATE $tabela SET nome = '".$campos['nome']."', cpf = '".$campos['cpf']."', rg = '".$campos['rg']."', cargo = '".$campos['cargo']."', inss = '$inss' WHERE id = $id");
		$sth->execute();
	}

	private function deletaFuncFolha($id){

		$tabela = PREFIXO.'folha_funcionarios';			
		$stmt = $this->prepare("DELETE FROM $tabela WHERE id = '$id'");
		$stmt->execute();

		$tabela = PREFIXO.'descontos_abonos';			
		$stmt = $this->prepare("DELETE FROM $tabela WHERE funcionario = '$id'");
		$stmt->execute();					
	}	

	public function criarNovosFuncionarios($idFolha, $funcionarios){
		$tabela = PREFIXO."folha_funcionarios";
		$sql = "INSERT INTO $tabela (folha, funci, nome, cpf, rg, salario, inss, cargo) VALUES";

		foreach ($funcionarios as $key => $campos) {
			$inss = 0;
			if($campos['inss'] == 1)
				$inss = $this->calculaInss($campos['salario']);

			if($key == 0)
				$sql .= " ('$idFolha', '".$campos['id']."', '".$campos['nome']."', '".$campos['cpf']."', '".$campos['rg']."', '".$campos['salario']."', '$inss', '".$campos['cargo']."')";
			else
				$sql .= ", ('$idFolha', '".$campos['id']."', '".$campos['nome']."', '".$campos['cpf']."', '".$campos['rg']."', '".$campos['salario']."', '$inss', '".$campos['cargo']."')";
		}

		$sth = $this->prepare($sql);
		$sth->execute();
	}

	public function recalcularTotal($idTabela){
		$tabela = PREFIXO."folha_funcionarios";
		$sql = "SELECT SUM(salario), SUM(inss) FROM $tabela WHERE folha = '$idTabela'";
		$query = $this->prepare($sql);
		$query->execute();

		$salario_inss = $query->fetchAll(PDO::FETCH_ASSOC);

	    $salarios = $salario_inss[0]['SUM(salario)'];
		$inss = $salario_inss[0]['SUM(inss)'];

		$tabela = PREFIXO."descontos_abonos";
		$sql = "SELECT SUM(valor) FROM $tabela WHERE folha = '$idTabela' AND tipo = '1' AND todos = '0'";
		$query = $this->prepare($sql);
		$query->execute();
		$abonos = $query->fetchAll(PDO::FETCH_ASSOC);

		$abonos = $abonos[0]['SUM(valor)'];

		$sql = "SELECT SUM(valor) FROM $tabela WHERE folha = '$idTabela' AND tipo = '2' AND todos = '0'";
		$query = $this->prepare($sql);	
		$query->execute();
		$descontos = $query->fetchAll(PDO::FETCH_ASSOC);

		$descontos = $descontos[0]['SUM(valor)'];

		$sql = "SELECT SUM(valor) FROM $tabela WHERE folha = '$idTabela' AND tipo = '1' AND todos = '1'";
		$query = $this->prepare($sql);
		$query->execute();
		$abonosTodos = $query->fetchAll(PDO::FETCH_ASSOC);

		$abonosTodos = $abonosTodos[0]['SUM(valor)'];

		$sql = "SELECT SUM(valor) FROM $tabela WHERE folha = '$idTabela' AND tipo = '2' AND todos = '1'";
		$query = $this->prepare($sql);	
		$query->execute();
		$descontosTodos = $query->fetchAll(PDO::FETCH_ASSOC);

		$descontosTodos = $descontosTodos[0]['SUM(valor)'];		

		$qtdFuncionarios = $this->qtdFunctionariosFolha($idTabela);

		$total = $salarios - $inss;
		$total = $total + $abonos - $descontos;
		$total = $total + $qtdFuncionarios*$abonosTodos - $qtdFuncionarios*$descontosTodos;

		$tabela = PREFIXO."folhas";
		$sth = $this->prepare("UPDATE $tabela SET total = $total WHERE id = $idTabela");
		$sth->execute();
	}
}
?>