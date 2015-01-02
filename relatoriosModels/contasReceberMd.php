<?php 
class contasReceberMd extends classePdo{

	public function contas($campos){
		$tbEntradas	  = PREFIXO."entradas";
		$tbCategorias = PREFIXO."categorias";

		$campos  = implode("`, `$tbEntradas`.`", $campos);
		$campos  = "`$tbEntradas`.`$campos`";


		$join  = "LEFT JOIN `$tbCategorias` ON `$tbEntradas`.`categoria` = `$tbCategorias`.`id` ";

		$sql = "SELECT $campos, `$tbCategorias`.`nome` AS `categoria` FROM `$tbEntradas` $join WHERE `$tbEntradas`.`pago` = 'Nao Efetuado' ORDER BY `$tbEntradas`.`vencimento` ASC";

		$query = $this->prepare($sql);
		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>