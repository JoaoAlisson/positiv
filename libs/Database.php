<?php 
class Database extends PDO{
	function __construct(){
		parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);	
		//array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8; SET character_set_client = utf8; SET character_set_results = utf8; SET character_set_connection = utf8;"));
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
			if(method_exists($this, "antesDeCadastrar"))
				$this->antesDeCadastrar($dados);

			ksort($dados);
			$this->salvaImagens($dados);

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

			if(method_exists($this, "depoisDeCadastrar"))
				$this->depoisDeCadastrar($dados);
		}
		return $validar;
	}

	private function salvaImagens(&$dados){
		$imagens = $this->pegaCamposImagens($dados);
		foreach ($imagens as $key => $campo) {
			if($dados[$campo] != "" && $dados[$campo] != null){
				$dados[$campo] = $this->novoNome($dados[$campo]);
				$this->uploadImg($campo, $dados[$campo]);
			}
		}
	}

	private function uploadImg($campo, $imagem){

		//$caminho = RAIZ . SEPARADOR . "public" . SEPARADOR . "imagens" . SEPARADOR;
		$caminho = PASTA_ARQUIVOS.SEPARADOR."imagens".SEPARADOR.str_replace("Model", "", get_class($this)).SEPARADOR;
        move_uploaded_file(
            $_FILES[$campo]['tmp_name'],
            $caminho . $imagem
        );
	}

	private function pegaCamposImagens(&$dados){
		$imagens = array();
		foreach ($dados as $campo => $valor) {
			if($campo != "id"){
				if($this->tipos[$campo] == "imagem"){
					array_push($imagens, $campo);
				}
			}
		}
		return $imagens;
	}

	private function atualizaImagens($id, &$dados){
		$imagens = $this->pegaCamposImagens($dados);
		$nomesBanco = $this->pegar($id, $imagens);
		$nomesBanco = $nomesBanco[str_replace("Model", "", get_class($this))];

		foreach ($imagens as $key => $campo){

			if($dados[$campo] == "" || $dados[$campo] == null)
				$this->excluirImagem($id, $campo, $nomesBanco[$campo], $dados);
			else
				$this->novaImagem($id, $campo, $nomesBanco[$campo], $dados);
		}
	}

	private function excluirImagem($id, $campo, $nomeBanco, &$dados){
		if($nomeBanco != "" && $nomeBanco != null){
			$this->deletarImagem($nomeBanco);
			$dados[$campo] = "";
		}
	}

	private function deletarImagem($imagem){
		$caminho = RAIZ . SEPARADOR . "public" . SEPARADOR . "imagens" . SEPARADOR;
		unlink($caminho.$imagem);
	}

	private function novaImagem($id, $campo, $nomeBanco, &$dados){
		if($nomeBanco != "" && $nomeBanco != null){
			$this->deletarImagem($nomeBanco);
		}
		$dados[$campo] = $this->novoNome($dados[$campo]);
		$this->uploadImg($campo, $dados[$campo]);		
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

			if(method_exists($this, "antesDeEditar"))
				$this->antesDeEditar($id, $dados);

			ksort($dados);
			$this->atualizaImagens($id, $dados);

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

			if(method_exists($this, "depoisDeEditar"))
				$this->depoisDeEditar($id, $dados);
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


		$resultado = (isset($resultado[0])) ? $resultado[0] : $resultado;
		$retorna[$controller] = $resultado;
		$this->formataSaida($retorna[$controller]);

		return $retorna;
	}

	public function visualizar($id, $campos = null, $tabela = null){

		if($tabela == null)
			$tabela = str_replace("Model", "", get_class($this));

		$controller = $tabela;
		$tabela = PREFIXO."$tabela";

		if($campos != null){
			$campos = implode("`, `$tabela`.`", $campos);
			$campos = "`$tabela`.`$campos`";
		}else{
			$campos = implode("`, `$tabela`.`", array_keys($this->tipos));
			$campos = "`$tabela`.`$campos`";			
		}

		$id = (int)$id;

		$joins = $this->separaJoin($tabela);
		$joinCampos = $joins['joinCampos'];
		$joinTabela = $joins['joinTabela'];

		$sql = "SELECT $campos $joinCampos FROM `$tabela` $joinTabela WHERE `$tabela`.`id` = {$id}";
		//echo $sql;

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		$resultado = (isset($resultado[0])) ? $resultado[0] : $resultado;
		$retorna[$controller] = $resultado;
		$this->formataSaida($retorna[$controller], true);

		return $retorna;
	}	

	public function pegarTodos($campos = null){
		return $this->pegarOnde(null, $campos);
	}

	public function pegarPagina($pagina = 1, $quantidade = "", $campos = "*", $onde = null, $tabela = null, $ordem = null, $ascend = null, $integro = false, $ondeCustomizado = ""){

		if($tabela == null)
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
							$where .= ($i > 0) ? "AND `$tabela`.`".$campo."` = '".$valor."' " : "`$tabela`.".$campo."` = '".$valor."' ";
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
		//$sql = "SELECT `pstv_membros`.`nome`, `pstv_membros`.`estado`, `pstv_membros`.`celular`, `pstv_membros`.`nascimento`, `pstv_membros`.`id `pstv_consagracoes`.`nome` AS `consagracoes_nome` FROM `pstv_membros` INNER JOIN `pstv_consagracoes` ON `pstv_membros`.`consagracao` = `pstv_consagracoes`.`nome` ORDER BY `pstv_membros`.`id` LIMIT 0, 10";
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

	private function separaJoin($tabela){
		$joinCampos = "";
		$joinTabela = "";
		if(isset($this->tipos)){
			$campos = $this->tipos;
			foreach ($campos as $campo => $tipo) {
				if(is_array($tipo)){
					if(isset($tipo['relacao'])){
						$joinCampos .= ", `".PREFIXO.$tipo['model']."`.`".$tipo['campo']."` AS `".$tipo['model']."_".$tipo['campo']."`";
						$joinTabela .= " LEFT JOIN `".PREFIXO.$tipo['model']."` ON `$tabela`.`$campo` = `".PREFIXO.$tipo['model']."`.`id` ";
					}
				}
			}
		}
		$retorna['joinCampos'] = $joinCampos;
		$retorna['joinTabela'] = $joinTabela;

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

	/*
		OBS: esta função não trata as entradas antes da pesquisa, nem as saídas. CUIDADO!!
	 */
	public function pegarTodosGenerico($tabela = null){
		return $this->pegarOndeGenerico(null, null, $tabela);
	}
	/*
		OBS: esta função não trata as entradas antes da pesquisa, nem as saídas. CUIDADO!!
	 */
	public function pegarOndeGenerico($onde = null, $campos = null, $tabela = null){

		if($campos == null)
			$campos = "*";
		elseif($campos != "*")
			$campos = implode(", ", $campos);

		$onde = ($onde == null) ? "" : "WHERE ".$onde;

		if($tabela == null)
			$tabela = str_replace("Model", "", get_class($this));

		$tabela = PREFIXO."$tabela";

		$sql = "SELECT $campos FROM $tabela $onde";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	
		return $resultado;
	}

	public function deletar($id, $tabela = null){

		if($tabela == null)
				$tabela = str_replace("Model", "", get_class($this));

		$retorna = array();
		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			$retorna['flag'] = "erro";
			$retorna['mensagem'] = "Você não tem permissao para deletar";
		}else{

			if(method_exists($this, "antesDeDeletar"))
				$this->antesDeDeletar($id);

			$tabela = PREFIXO.$tabela;			
			$stmt = $this->prepare("DELETE FROM $tabela WHERE id = :id");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
			$stmt->execute();	

			if(method_exists($this, "depoisDeDeletar"))
				$this->depoisDeDeletar($id);

			$retorna['flag'] = "ok";
			$retorna['mensagem'] = "Deletado com sucesso";
		}
		return $retorna;
	}
}
?>