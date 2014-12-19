<?php 
class descontos_abonosModel extends Model{
 
	public $tipos = array("folha" 		=> "inteiro",
						  "funcionario" => "inteiro",
						  "valor"		=> "moeda",
						  "tipo" 		=> "inteiro",
						  "todos"		=> "inteiro",
						  "descricao" 	=> "nome");

	public function antesDeDeletar($id){

		$tabela = PREFIXO . "descontos_abonos";
		$consulta = $this->prepare("SELECT folha, tipo, valor, todos FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];

		$soma = ($resultado['tipo'] == 1) ? "subtracao" : "soma";

		if($resultado['todos'] == 1)
			$this->atualizaTotalFolhaTodos($soma, $resultado['valor'], $resultado['folha']);
		else
			$this->atualizaTotalFolha($soma, $resultado['valor'], $resultado['folha']);
			
	}

	private function qtdFunctionariosFolha($idFolha){

		$tabela = PREFIXO."folha_funcionarios";
		$idFolha = (int)$idFolha;	

		$sql = "SELECT COUNT(*) FROM $tabela WHERE folha = $idFolha";
		$resposta =  $this->query($sql);

		return $resposta->fetchColumn();		
	}	

	private function atualizaTotalFolhaTodos($soma, $valor, $id){
		$qtd = $this->qtdFunctionariosFolha($id);
		$valor = $qtd*$valor;
		$this->atualizaTotalFolha($soma, $valor, $id);
	}

	private function atualizaTotalFolha($soma, $valor, $id){

		$id = (int)$id;
		$valor = (double)$valor;

		$sinal = ($soma == 'soma') ? "+" : "-";
		$tabela = PREFIXO."folhas";
		$sth = $this->prepare("UPDATE $tabela SET total = total $sinal $valor WHERE id = $id");
		$sth->execute();
	}

}?>