<?php 
class saidasEntradasMd extends classePdo{

	public function movimento($ano) {

		$retorna['entradas'] = $this->entradas($ano);
		$retorna['saidas']	 = $this->saidas($ano);

		return $retorna;
	}

	private function entradas($ano) {

		$retorna = array();
		for ($i=1; $i < 13; $i++) 
			array_push($retorna, $this->entradaMes($i, $ano));

		return $retorna;
	}

	private function saidas($ano) {
		$retorna = array();
		for ($i=1; $i < 13; $i++) 
			array_push($retorna, $this->somaEntradaSaida($i, $ano, 'saidas'));

		return $retorna;
	}

	private function entradaMes($mes, $ano) {

		$entradas   = $this->somaEntradaSaida($mes, $ano, 'entradas');
		$dizimos    = $this->pegarDizimos($mes, $ano);
		$folhaTotal = $this->folhaTotal($mes, $ano);

		$total = $entradas + $dizimos + $folhaTotal;
		return $total;
	}

	private function folhaTotal($mes, $ano) {
		$tabela = PREFIXO . 'folhas';
		$sql    = "SELECT total FROM $tabela WHERE mes = '$mes' AND ano = '$ano' AND contab != '0'";
		$retorna = $this->busca($sql);

		if(!empty($retorna))
			return $retorna[0]['total'];
		else
			return 0;
	}

	private function pegarDizimos($mes, $ano) {
		return $this->pegaSoma($mes, $ano, 'dizimos_ofertas', 'valor', 'data');
	}

	private function somaEntradaSaida($mes, $ano, $tabela) {

		$and = "AND pago = 'Efetuado'";	
		return $this->pegaSoma($mes, $ano, $tabela, 'valor', 'vencimento', $and);
	}

	private function pegaSoma($mes, $ano, $tabela, $campoVal, $campoDat, $and = '') {

		$tabela = PREFIXO . $tabela;
		
		$sql = "SELECT SUM($campoVal) FROM $tabela WHERE MONTH($campoDat) = $mes AND YEAR($campoDat) = $ano $and" ;
	
		$resultado = $this->busca($sql);

		$campo = 'SUM('.$campoVal.')';
		$resultado = $resultado[0][$campo];

		return $resultado;
	}

	private function busca($sql) {
		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);		
	}
}
?>