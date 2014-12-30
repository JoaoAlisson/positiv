<?php 
class aniversariantesVisitantesMd extends classePdo{

	public function pegarVisitantes($campos, $mes){
		$tabela = PREFIXO."visitantes";

		$mes = (int)$mes;
		$where = " WHERE MONTH(`$tabela`.`nascimento`) = $mes";

		$campos = implode("`, `$tabela`.`", $campos);
		$campos = "`$tabela`.`$campos`";
		$sql = "SELECT $campos FROM `$tabela` $where ORDER BY `$tabela`.`nome` ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>