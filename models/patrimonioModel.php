<?php 
class patrimonioModel extends Model{
 
	public $tipos = array( "codigo" => "texto",
						   "cod_sistema" => "inteiro",
						   "nome"	=> "nome",
						   "descricao" => "descricao",
						   "ministerio"=> array("relacao" => "muitosParaUm", "model" => "ministerios", "campo" => "nome"),
						   "aquisicao" => "data",
						   "valor"     => "moeda",
						   "total"	   => "moeda",
						   "quantidade"=> "inteiro",
						   "descricao" => "textoLongo",
						   "situacao"  => array("Disponível", "Em Manutenção", "Doado", "Com Defeito"),
						   "obs" => "textoLongo");

	public $obrigatorios = array("nome");

	public function antesDeCadastrar(&$dados){
		$valor = ($dados['valor'] != "") ? $dados['valor'] : 0;
		$qtd = ($dados['quantidade'] != "") ? $dados['quantidade'] : 0;
		$total = $valor*$qtd;
		$dados['total'] = $total;

		$this->alteraTotal($total, "+");		
	}

	public function antesDeDeletar($id){
		$total = $this->pegarCampo($id);

		if($total != 0 && $total != "")
			$this->alteraTotal($total, "-");
	}

	public function antesDeEditar($id, &$dados){
		$valor = ($dados['valor'] != "") ? $dados['valor'] : 0;
		$qtd = ($dados['quantidade'] != "") ? $dados['quantidade'] : 0;
		$dados['total'] = $valor*$qtd;

		$totalAntes = $this->pegarCampo($id);

		$total = $dados['total'];

		if($totalAntes  > $total){
			$valor = $totalAntes - $total;
			$this->alteraTotal($valor, "-");
		}
		if($totalAntes < $total){
			$valor = $total - $totalAntes;
			$this->alteraTotal($valor, "+");
		}
	}

	private function alteraTotal($valor, $sinal){
		$tabela = PREFIXO."informacoes";
		$sth = $this->prepare("UPDATE $tabela SET total_patrimonio = total_patrimonio $sinal '$valor' WHERE id = 1");
		$sth->execute();			
	}

	private function pegarCampo($id, $campo = "total", $tab = "patrimonio"){
		//$this->setaBancoModel();
		$tabela = PREFIXO.$tab;
		$consulta = $this->prepare("SELECT $campo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		$resultado = $resultado[$campo];

		return $resultado;
	}

	public function pegarTotal(){
		$total = $this->pegarCampo("1", "total_patrimonio", "informacoes");
		$total = $this->formataMoeda(null, $total, false);
		return $total;
	}

	public function depoisDeCadastrar($dados){
		$id = $this->lastInsertId();

		$tabela = PREFIXO."patrimonio";
		$sth = $this->prepare("UPDATE $tabela SET cod_sistema = '$id' WHERE id = $id");
		$sth->execute();	
	}
}
?>