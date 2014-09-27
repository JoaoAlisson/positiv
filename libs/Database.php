<?php 
class Database extends PDO{
	function __construct(){
		parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);	
	}  

	public function inserir($dados, $tabela = null){
		$validar = array();
		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			$validar[0] = "erro";
			$validar[1] = "Você não tem permissao para realizar esta ação!";
		}else{
			$validar = $this->validar($dados);
		}

		if($validar[0] == "ok"){
			ksort($dados);

			if($tabela == null)
				$tabela = str_replace("Model", "", get_class($this));
		
			$campos = implode(", ", array_keys($dados));
			$valores = ":".implode(", :", array_keys($dados));

			$tabela = PREFIXO.$tabela;
			$sth = $this->prepare("INSERT INTO $tabela ($campos) VALUES ($valores)");

			mysql_connect(DB_HOST, DB_USER, DB_PASS);
			foreach ($dados as $key => $value) {
				$sth->bindValue(":$key", mysql_real_escape_string($value));
			}

			$sth->execute();
		}
		return $validar;
	}

	public function atualizar($dados, $id, $tabela = null){
		$validar = array();
		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			$validar[0] = "erro";
			$validar[1] = "Você não tem permissao para realizar esta ação!";
		}else{
			$validar = $this->validar($dados);
		}
		if($validar[0] == "ok"){
			ksort($dados);

			if($tabela == null)
				$tabela = str_replace("Model", "", get_class($this));

			$alteracoes = NULL;
			foreach ($dados as $key => $value) {
				$alteracoes .= "$key = :$key,"; 
			}

			$alteracoes = rtrim($alteracoes, ",");

			$tabela = PREFIXO.$tabela;
			$sth = $this->prepare("UPDATE $tabela SET $alteracoes WHERE id = :id");

			$id = (int)$id;
			$sth->bindValue(":id", $id);
			mysql_connect(DB_HOST, DB_USER, DB_PASS);
			foreach ($dados as $key => $value) {
				$sth->bindValue(":$key", mysql_real_escape_string($value));
			}

			$validar[1] = "Editado com Sucesso";
			$sth->execute();
		}
		return $validar;		
	}

	public function pegar($id, $campos = null, $tabela = null){

		if($campos == null)
			$campos = "*";
		elseif($campos != "*")
			$campos = implode(", ", $campos);

		if($tabela == null)
			$tabela = str_replace("Model", "", get_class($this));

		$controller = $tabela;
		$tabela = PREFIXO."$tabela";

		$id = (int)$id;
		$sql = "SELECT $campos FROM $tabela WHERE id = {$id}";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		$resultado[$controller] = $resultado[0];
		$this->formataSaida($resultado[$controller]);

		return $resultado;
	}

	public function pegarTodos($campos = null){
		return $this->pegarOnde(null, $campos);
	}

	public function pegarPagina($pagina = 1, $quantidade = 5, $campos = "*", $onde = null, $tabela = null, $ordem = null, $ascend = null){

		if($campos != "*")
			$campos = implode(", ", $campos);

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
							$where .= ($i > 0) ? "AND ".$campo." = '".$valor."' " : $campo." = '".$valor."' ";
						else
							$where .= ($i > 0) ? "AND ".$campo." LIKE '%".$valor."%' " : $campo." LIKE '%".$valor."%' ";
						$i++;
					}
				}
			}
		}

		if($tabela == null)
			$tabela = str_replace("Model", "", get_class($this));

		$ordem = ($ordem == null) ? "id" : $ordem;

		if($ascend != null){
			$ascend = ($ascend == "ASC") ? "ASC" : "DESC";
		}else{
			$ascend = "";
		}

		$pagina = (is_numeric($pagina)) ? $pagina : 1;
		$pagina = ($pagina < 1) ? 1 : $pagina;		

		$controller = $tabela;
		$tabela = PREFIXO."$tabela";

		//pega a quantidade total
		$quantidadeTodos;
		$sql = "SELECT COUNT(*) FROM $tabela $where";
		$resposta =  $this->query($sql);
		$quantidadeTodos = $resposta->fetchColumn();

		//quantidade de páginas
		$quantidadeDePaginas = ceil($quantidadeTodos/$quantidade);

		//início
		$pagina--;
		$inicio = $pagina*$quantidade;

		
		$sql = "SELECT $campos FROM $tabela $where ORDER BY $ordem $ascend LIMIT $inicio, $quantidade";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		$retorna[$controller] = $resultado;
		$retorna['qtdPaginas'] = $quantidadeDePaginas;
		$retorna['qtd'] = $quantidadeTodos;
		$pagina++;
		$retorna['pagina'] = $pagina;
		$this->formataSaida($retorna[$controller]);

		return $retorna;

	}

	/*
		OBS: esta função não trata as entradas antes da pesquisa. CUIDADO!!
	 */
	public function pegarOnde($onde = null, $campos = null, $tabela = null){

		if($campos == null)
			$campos = "*";
		elseif($campos != "*")
			$campos = implode(", ", $campos);

		$onde = ($onde == null) ? "" : "WHERE ".$onde;

		if($tabela == null)
			$tabela = str_replace("Model", "", get_class($this));

		$controller = $tabela;
		$tabela = PREFIXO."$tabela";

		$sql = "SELECT $campos FROM $tabela $onde";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		$retorna[$controller] = $resultado;
		$this->formataSaida($retorna[$controller]);

		return $retorna;
	}

	public function deletar(){

	}
}
?>