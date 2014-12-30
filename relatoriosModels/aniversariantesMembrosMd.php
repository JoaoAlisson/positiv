<?php 
class aniversariantesMembrosMd extends classePdo{

	public function pegarMembros($campos, $mes){
		$tabela = PREFIXO."membros";
		$tabelaConsagracao = PREFIXO."consagracoes";

		$mes = (int)$mes;
		$where = " WHERE MONTH(`$tabela`.`nascimento`) = $mes";

		$campos = implode("`, `$tabela`.`", $campos);
		$campos = "`$tabela`.`$campos`";
		$join = "LEFT JOIN `$tabelaConsagracao` ON `$tabela`.`consagracao` = `$tabelaConsagracao`.`id`";
		$sql = "SELECT $campos, `$tabelaConsagracao`.`nome` AS `consagracao` FROM `$tabela` $join $where ORDER BY `$tabela`.`nome` ASC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>