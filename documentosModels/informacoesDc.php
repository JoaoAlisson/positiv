<?php
class informacoesDc extends classePdo 
{

	public function informacoes()
	{
		return $this->pegaInformacoes();
	}

	private function sqlSelect() 
	{
		$tabela    = PREFIXO . 'informacoes';
		$tbEstados = PREFIXO . 'estados';
		$tbCidades = PREFIXO . 'cidades';

		$camposAr = array('nome', 'logo', 'pastor', 'cnpj', 'bairro', 
						  'rua', 'numero', 'telefone', 'email', 'site', 'face', 'qtd_membros');

		$campos = '';
		foreach ($camposAr as $valor) 
			$campos .=  ($campos == '') ? "`$tabela`.`$valor`" : ", `$tabela`.`$valor`";

		$join = "LEFT JOIN `$tbEstados` ON `$tabela`.`estado` = `$tbEstados`.`id`";
		$join .= " LEFT JOIN `$tbCidades` ON `$tabela`.`cidade` = `$tbCidades`.`id`";

		$sql = "SELECT $campos, `$tbEstados`.`estado` as `estado`, `$tbCidades`.`cidade` as `cidade` FROM `$tabela` $join WHERE `$tabela`.`id` = 1";

		return $sql;
	}

	private function pegaInformacoes()
	{
		$query = $this->prepare($this->sqlSelect());
		$query->execute();
		$retorno = $query->fetchAll(PDO::FETCH_ASSOC);

		return $retorno[0];
	}
}
?>