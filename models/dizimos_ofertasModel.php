<?php 
class dizimos_ofertasModel extends Model{
 
	public $tipos = array("tipo"   => array("Dízimo", "Oferta"),
						  "nome"   => "nome", 
						  "membro" => array("relacao" => "muitosParaUm", "model" => "membros", "campo" => "nome"),
						  "data"   => "data",
						  "valor"  => "moeda",					   
						  "obs"    => "textoLongo");

	public $obrigatorios = array("tipo", "data");

	public $limiteDeLinhas = 100000;

	public function depoisDeCadastrar($dados){
		if($dados['valor'] > 0)
			$this->alteraTotal($dados['valor'], "+");
	}

	public function antesDeDeletar($id){
		
		$valor = $this->pegarCampo($id);

		if($valor > 0)
			$this->alteraTotal($valor, "-");	
	}	

	public function antesDeEditar($id, &$dados){
		$valorAntes = $this->pegarCampo($id);
		$valorNovo = ($dados['valor'] != "") ? $dados['valor'] : 0;

		if($valorAntes  > $valorNovo){
			$valor = $valorAntes - $valorNovo;
			$this->alteraTotal($valor, "-");
		}
		if($valorAntes < $valorNovo){
			$valor = $valorNovo - $valorAntes;
			$this->alteraTotal($valor, "+");
		}
	}


	private function pegarCampo($id, $campo = "valor", $tab = "dizimos_ofertas"){
	
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