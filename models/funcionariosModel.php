<?php 
class funcionariosModel extends Model{

	private $bancoModel;
 
	public $tipos = array( "membro"    => array("relacao" => "muitosParaUm", "model" => "membros", "campo" => "nome"),
						   "func"      => array("relacao" => "muitosParaUm", "model" => "func_nao_membro", "campo" => "nome"),
						   "cargo"     => array("relacao" => "muitosParaUm", "model" => "cargos", "campo" => "nome"),
						   "salario"   => "moeda",
						   "inss"	   => "inteiro",
						   "situacao"  => array("Ativo", "De Férias", "Demitido"),
						   "admissao"  => "data",
						   "demissao"  => "data",
						   "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "cargo", "situacao");

	public function depoisDeCadastrar($dados){
		$id = $dados['cargo'];
		if($id != "" && $id != "0"){
			$id = (int)$id;
			$tabela = PREFIXO."cargos";
			$sth = $this->prepare("UPDATE $tabela SET qtd = qtd + 1 WHERE id = $id");
			$sth->execute();
		}

	}

	public function verificaCadastrado($id){
		$id = (int)$id;
		$tabela = PREFIXO."funcionarios";

		$consulta = $this->prepare("SELECT COUNT(*) AS quantidade FROM $tabela WHERE membro = {$id}");
		$consulta->execute();
		$quantidade = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$quantidade = $quantidade[0]['quantidade'];

		if($quantidade > 0)
			return false;
		else
			return true;	
	}

	public function antesDeEditar($id, $dados){
		$idConsAntigo = $this->pegarCampo($id, "cargo");
		if($dados['cargo'] != $idConsAntigo){
			if($idConsAntigo != "0")
				$this->incrementa(false, $idConsAntigo);
			if($dados['cargo'] != "" && $dados['cargo'] != null)
				$this->incrementa(true, $dados['cargo']);
		}
	}	

	public function antesDeDeletar($id){
		
		$id = (int)$id;
		$banco = new Database();
		$tabela = PREFIXO."funcionarios";
		$consulta = $banco->prepare("SELECT cargo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$consagracao = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$consagracao = $consagracao[0];
		$consagracao = $consagracao['cargo'];

		$consagracao = (int) $consagracao;
		if($consagracao != 0){


			$tabela = PREFIXO."cargos";
			$sth = $banco->prepare("UPDATE $tabela SET qtd = qtd - 1 WHERE id = $consagracao");
			$sth->execute();
		}

	}

	private function incrementa($incrementa, $consagracao){
		$this->setaBancoModel();
		$tabela = PREFIXO."cargos";

		$sinal = ($incrementa == true) ? "+" : "-";
		$sth = $this->bancoModel->prepare("UPDATE $tabela SET qtd = qtd $sinal 1 WHERE id = $consagracao");
		$sth->execute();
	}

	private function pegarCampo($id, $campo){
		//$this->setaBancoModel();
		$tabela = PREFIXO."funcionarios";
		$consulta = $this->prepare("SELECT $campo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		$resultado = $resultado[$campo];

		return $resultado;
	}

	private function setaBancoModel(){
		if($this->bancoModel == null)
			$this->bancoModel = new Database();
	}

	public function pegarPaginaThis($pagina = 1, $quantidade = "", $campos = "*", $onde = null, $tabela = null, $ordem = null, $ascend = null, $integro = false, $ondeCustomizado = ""){
		if($tabela == null)
			$tabela = str_replace("Model", "", get_class($this));

		$controller = $tabela;
		$tabela = PREFIXO."$tabela";

		if($campos != "*")
			$campos = implode("`, `$tabela`.`", $campos);

		$campos = "`$tabela`.`$campos`";	

		$campos .= ", (CASE 
						WHEN (`$tabela`.`membro` > 0)
						THEN `".PREFIXO."membros`.`nome`
						ELSE `".PREFIXO."func_nao_membro`.`nome`
 					END) AS `nome`"; 

		$where = "";
		$validar = "";
		mysql_connect(DB_HOST, DB_USER, DB_PASS);
		if($onde != null){
			$validar = $this->validar($onde, "false");	
			$i = 0;
			if($validar[0] == "ok"){
				$where = "WHERE ";
				foreach ($onde as $campo => $valor){
					$valor = mysql_real_escape_string($valor);

					if($valor != ""){
						if($campo == "nome"){
							$where .= ($i > 0) ? "AND `".PREFIXO."membros`.`nome` LIKE '%".$valor."%' " : "`".PREFIXO."membros`.`nome` LIKE '%".$valor."%' OR `".PREFIXO."func_nao_membro`.`nome` LIKE '%".$valor."%'";							
						}else{
							if($this->tipos[$campo] == "numero" || $this->tipos[$campo] == "moeda" || $this->tipos[$campo] == "inteiro")
								$where .= ($i > 0) ? "AND `$tabela`.`".$campo."` = '".$valor."' " : "`$tabela`.`".$campo."` = '".$valor."' ";				
							else
								$where .= ($i > 0) ? "AND `$tabela`.`".$campo."` LIKE '%".$valor."%' " : "`$tabela`.`".$campo."` LIKE '%".$valor."%' ";
						}
						$i++;
					}
				}
			}
		}

		if($ordem != null){
			if(is_array($this->tipos[$ordem])){
				if(isset($this->tipos[$ordem]['relacao']))
					$ordem = "`".PREFIXO.$this->tipos[$ordem]['model']."`.`".$this->tipos[$ordem]['campo']."`";
			}
		}

		if($ordem == null)
			$ordem = "`$tabela`.`id`";

		if($ascend != null){
			$ascend = ($ascend == "ASC") ? "ASC" : "DESC";
		}else{
			$ascend = "DESC";
		}

		$pagina = (is_numeric($pagina)) ? $pagina : 1;
		$pagina = ($pagina < 1) ? 1 : $pagina;		

		if($ondeCustomizado != ""){
			if($where != "")
				$where .= " AND $ondeCustomizado";
			else
				$where = " WHERE ".$ondeCustomizado;
		}

		$joins = $this->separaJoin($tabela);
		$joinCampos = $joins['joinCampos'];
		$joinTabela = $joins['joinTabela'];

		//pega a quantidade total
		$quantidadeTodos;
		
		$sql = "SELECT COUNT(*) FROM $tabela $joinTabela $where";
		//echo $sql;
		$resposta =  $this->query($sql);
		$quantidadeTodos = $resposta->fetchColumn();

		//quantidade de páginas
		$quantidade = ($quantidade == "") ? $quantidadeTodos : $quantidade;
		$quantidadeDePaginas = ($quantidadeTodos == 0) ? 1 : ceil($quantidadeTodos/$quantidade);

		//início
		$pagina--;
		$inicio = $pagina*$quantidade;
		

		$sql = "SELECT $campos $joinCampos FROM `$tabela` $joinTabela $where ORDER BY $ordem $ascend LIMIT $inicio, $quantidade";
		//echo $sql;

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);


		$retorna[$controller] = $resultado;
		$retorna['qtdPaginas'] = $quantidadeDePaginas;
		$retorna['qtd'] = $quantidadeTodos;
		$pagina++;
		$retorna['pagina'] = $pagina;
		$this->formataSaida($retorna[$controller], $integro);

		return $retorna;				if($tabela == null)
			$tabela = str_replace("Model", "", get_class($this));

		$controller = $tabela;
		$tabela = PREFIXO."$tabela";

		if($campos != "*")
			$campos = implode("`, `$tabela`.`", $campos);

		$campos = "`$tabela`.`$campos`";	

		$where = "";
		$validar = "";
		mysql_connect(DB_HOST, DB_USER, DB_PASS);
		if($onde != null){
			$validar = $this->validar($onde, "false");	
			$i = 0;
			if($validar[0] == "ok"){
				$where = "WHERE ";
				foreach ($onde as $campo => $valor){
					$valor = mysql_real_escape_string($valor);
					if($valor != ""){
						if($this->tipos[$campo] == "numero" || $this->tipos[$campo] == "moeda" || $this->tipos[$campo] == "inteiro")
							$where .= ($i > 0) ? "AND `$tabela`.`".$campo."` = '".$valor."' " : "`$tabela`.`".$campo."` = '".$valor."' ";
						else
							$where .= ($i > 0) ? "AND `$tabela`.`".$campo."` LIKE '%".$valor."%' " : "`$tabela`.`".$campo."` LIKE '%".$valor."%' ";
						$i++;
					}
				}
			}
		}

		if($ordem != null){
			if(is_array($this->tipos[$ordem])){
				if(isset($this->tipos[$ordem]['relacao']))
					$ordem = "`".PREFIXO.$this->tipos[$ordem]['model']."`.`".$this->tipos[$ordem]['campo']."`";
			}
		}

		if($ordem == null)
			$ordem = "`$tabela`.`id`";

		if($ascend != null){
			$ascend = ($ascend == "ASC") ? "ASC" : "DESC";
		}else{
			$ascend = "DESC";
		}

		$pagina = (is_numeric($pagina)) ? $pagina : 1;
		$pagina = ($pagina < 1) ? 1 : $pagina;		

		if($ondeCustomizado != ""){
			if($where != "")
				$where .= " AND $ondeCustomizado";
			else
				$where = " WHERE ".$ondeCustomizado;
		}

		//pega a quantidade total
		$quantidadeTodos;
		
		$sql = "SELECT COUNT(*) FROM $tabela $where";
		//echo $sql;
		$resposta =  $this->query($sql);
		$quantidadeTodos = $resposta->fetchColumn();

		//quantidade de páginas
		$quantidade = ($quantidade == "") ? $quantidadeTodos : $quantidade;
		$quantidadeDePaginas = ($quantidadeTodos == 0) ? 1 : ceil($quantidadeTodos/$quantidade);

		//início
		$pagina--;
		$inicio = $pagina*$quantidade;

		$joins = $this->separaJoin($tabela);
		$joinCampos = $joins['joinCampos'];
		$joinTabela = $joins['joinTabela'];
		

		$sql = "SELECT $campos $joinCampos FROM `$tabela` $joinTabela $where ORDER BY $ordem $ascend LIMIT $inicio, $quantidade";
		//echo $sql;

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);


		$retorna[$controller] = $resultado;
		$retorna['qtdPaginas'] = $quantidadeDePaginas;
		$retorna['qtd'] = $quantidadeTodos;
		$pagina++;
		$retorna['pagina'] = $pagina;
		$this->formataSaida($retorna[$controller], $integro);

		return $retorna;
	}	
}
?>