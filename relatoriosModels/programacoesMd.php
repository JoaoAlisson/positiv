<?php 
class programacoesMd extends classePdo{

	public function pegarEventos($campos, $onde){

		$tabela = PREFIXO."programacao";

		$where = $this->onde($onde);

		$campos = implode("`, `$tabela`.`", $campos);
		$campos = "`$tabela`.`$campos`";

		$sql = "SELECT $campos FROM `$tabela` $where ORDER BY `$tabela`.`inicio` DESC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	private function onde($array)
	{
		$tabela = PREFIXO."programacao";

		$where = "";
		$inicio = $this->formataData($array['inicio']);
		$fim    = $this->formataData($array['fim']);		

		if($inicio != "" && $fim != "")
		{
			$where = "`$tabela`.`inicio` BETWEEN '$inicio' AND '$fim'";
		}
		else
		{
			if($inicio != "")
				$where = "`$tabela`.`inicio` >= '$inicio'";
			if($fim != "")
				$where = "`$tabela`.`inicio` <= '$fim'";
		}

		if($where != "")
			$where = "WHERE " . $where;

		return $where;
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