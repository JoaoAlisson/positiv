<?php 
class saidasCategoriaMd extends classePdo{

	public function saidas($campos, $categoria, $onde){
		$tabela  = PREFIXO."saidas";

		$campos  = implode("`, `$tabela`.`", $campos);
		$campos  = "`$tabela`.`$campos`";

		$where   = $this->onde($onde); 
		$sql = "SELECT $campos FROM `$tabela` WHERE categoria = '$categoria' $where ORDER BY vencimento ASC";

		$query = $this->prepare($sql);
		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	private function onde($onde)
	{	
		$where = '';
		if($onde['pagamento'] != "")
			$where = ($onde['pagamento'] == 1) ? "pago = 'Efetuado'" : "pago != 'Efetuado'";

		if($onde['inicio'] != "" || $onde['fim'] != "")
		{	
			$where .= ($where != '') ? ' AND ' : '';

			$inicio = ($onde['inicio'] != '') ? $this->formataData($onde['inicio']) : '';
			$fim    = ($onde['fim']    != '') ? $this->formataData($onde['fim'])    : '';

			if($inicio != "" && $fim != "")
			{
				$where .= "vencimento BETWEEN '$inicio' AND '$fim'";
			}
			else
			{
				if($inicio != "")
					$where .= "vencimento >= '$inicio'";
				if($fim != "")
					$where .= "vencimento <= '$fim'";
			}
		}

		$where = ($where != '') ? 'AND ' . $where : '';

		return $where;  
	}

	public function categorias()
	{
		$tabela = PREFIXO."categorias";

		$sql = "SELECT id, nome FROM $tabela WHERE tipo != 'Entrada' ORDER BY nome ASC";

		$query = $this->prepare($sql);
		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);		
	}

	public function categoria($id)
	{	
		$id = (int)$id;
		$tabela = PREFIXO."categorias";

		$sql = "SELECT nome FROM $tabela WHERE id = '$id'";

		$query = $this->prepare($sql);
		$query->execute();
		$retorna = $query->fetchAll(PDO::FETCH_ASSOC);

		return $retorna[0]['nome'];
	}

	public function escreveFiltros($onde)
	{	
		$filtros = '';
	
		if($onde['inicio'] != "" || $onde['fim'] != "")
		{	
			if($onde['inicio'] != "" && $onde['fim'] != "")
			{
				$filtros .= "Entre " . $onde['inicio'] . " e " . $onde['fim'];
			}
			else
			{
				if($onde['inicio'] != "")
					$filtros .= "Desde " . $onde['inicio'];
				else
					$filtros .= "Até " . $onde['fim'];
			}		
		}

		if($onde['pagamento'] != "")
		{	
			$filtros .= ($filtros != '') ? ', ' : '';
			$filtros .= ($onde['pagamento'] == 1) ? 'Pagamentos Efetuados' : 'Pagamentos não Efetuados';  
		}
			   

        $filtros .= ($filtros != '') ? '.' : '';
		
		return $filtros;
	}

	private function formataData($data){
		if($data == "")
			return "";
		
		$data = explode("/", $data);
		$dia = (int)$data[0];
		$mes = (int)$data[1];
		$ano = (int)$data[2];
		return "$ano-$mes-$dia";
	}	
}
?>