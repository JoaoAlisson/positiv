<?php 
class saidasModel extends Model{
 
	public $tipos = array( "saida"     => "texto",
						   "nota"      => "texto",
						   "categoria" => array("relacao" => "muitosParaUm", "model" => "categorias", "campo" => "nome"),
						   "valor"     => "moeda",
						   "vencimento"=> "data",
						   "pago"	   => array("Efetuado", "Não Efetuado"),
						   "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "categoria", "vencimento", "pago");

	public $limiteDeLinhas = 100000;

	public function antesDeCadastrar(&$dados){
		$valor = ($dados['valor'] != "") ? $dados['valor'] : 0;
		if($dados['pago'] == "Efetuado")
			$this->alteraTotal($valor, "-");		
	}	

	public function antesDeDeletar($id){
		$total = $this->pegarCampo($id);
		$pago = $this->pegarCampo($id, "pago");

		if($total != 0 && $total != ""){
			if($pago == "Efetuado")
				$this->alteraTotal($total, "+");
		}			
	}

	public function antesDeEditar($id, &$dados){
		$valorAntes = $this->pegarCampo($id);
		$pagoAntes  = $this->pegarCampo($id, "pago");
		$pagoAntes = ($pagoAntes == "Efetuado") ? true : false;

		$valorNovo = ($dados['valor'] != "") ? $dados['valor'] : 0;
		$pagoNovo  = ($dados['pago'] == "Efetuado") ? true : false;

		if(!$pagoAntes){
			if($pagoNovo)
				$this->alteraTotal($valorNovo, "-");
		}else{
			if($pagoNovo)
				$this->atualizaValor($valorAntes, $valorNovo);
			else
				$this->atualizaValor($valorAntes, 0);
		}
	}	

	private function atualizaValor($antes, $depois){
		if($antes > $depois){
			$valor = $antes - $depois;
			$this->alteraTotal($valor, "+");
		}else{
			$valor = $depois - $antes;
			if($valor != 0)
				$this->alteraTotal($valor, "-");
		}
	}

	private function pegarCampo($id, $campo = "valor", $tab = "saidas"){
	
		$tabela = PREFIXO.$tab;
		$consulta = $this->prepare("SELECT $campo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		$resultado = $resultado[$campo];

		return $resultado;
	}	

	private function alteraTotal($valor, $sinal){
		$tabela = PREFIXO."informacoes";
		$sth = $this->prepare("UPDATE $tabela SET saldo = saldo $sinal '$valor' WHERE id = 1");
		$sth->execute();			
	}
}
?>