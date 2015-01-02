<?php 
class contasPagarMd extends classePdo{

	public function contas($campos){
		$tbSaidas 	  = PREFIXO."saidas";
		$tbCategorias = PREFIXO."categorias";

		$campos  = implode("`, `$tbSaidas`.`", $campos);
		$campos  = "`$tbSaidas`.`$campos`";


		$join  = "LEFT JOIN `$tbCategorias` ON `$tbSaidas`.`categoria` = `$tbCategorias`.`id` ";

		$sql = "SELECT $campos, `$tbCategorias`.`nome` AS `categoria` FROM `$tbSaidas` $join WHERE `$tbSaidas`.`pago` = 'Nao Efetuado' ORDER BY `$tbSaidas`.`vencimento` ASC";

		$query = $this->prepare($sql);
		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>