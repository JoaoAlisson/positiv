<?php 
class dizimosOfertasMembroMd extends classePdo{

	public function dizimosOfertas($id, $tipo)
	{
		$id = (int)$id;

		$tabela	= PREFIXO."dizimos_ofertas";

		$where = ($tipo != "") ? " AND tipo = '$tipo'" : "";
		$sql = "SELECT data, valor, tipo FROM $tabela WHERE membro = '$id' $where ORDER BY data ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);	
	}

	public function pegarMembro($id)
	{
		$dadosMembro = $this->membros($id);

		$retorna['nome'] = $dadosMembro[0]['nome'];
		$retorna['cpf']  = $dadosMembro[0]['cpf'];
		$retorna['rg']   = $dadosMembro[0]['rg'];

		return $retorna;
	} 

	public function membros($id = "")
	{
		$tabela	= PREFIXO."membros";

		$where  = "";
		$campos = "";
		if($id != "")
		{
			$id     = (int)$id;
			$where  = "WHERE id = '$id'";
			$campos = ",cpf, rg"; 
		}
			
		$sql = "SELECT id, nome $campos FROM $tabela $where ORDER BY nome ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);			
	}
}
?>