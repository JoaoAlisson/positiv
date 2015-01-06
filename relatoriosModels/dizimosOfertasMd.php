<?php 
class dizimosOfertasMd extends classePdo{

	public function dizimosOfertas($mes, $ano, $tipo)
	{
		$mes = (int)$mes;
		$ano = (int)$ano;

		$tabela	  = PREFIXO."dizimos_ofertas";
		$tMembros = PREFIXO."membros";

		$join = "LEFT JOIN `$tMembros` ON `$tabela`.`membro` = `$tMembros`.`id`";

		$where = ($tipo != "") ? " AND tipo = '$tipo'" : "";
		$sql = "SELECT `$tMembros`.`nome` AS `membro`, `$tabela`.`data`, `$tabela`.`valor`, `$tabela`.`tipo` FROM `$tabela` $join WHERE MONTH(data) = $mes AND YEAR(data) = $ano $where ORDER BY `$tabela`.`data` ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);	
	}
}
?>