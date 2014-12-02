<?php 
class caixaModel extends Model{

	public function pegarSaldo(){
		return $this->pegarCampo(1, "saldo","informacoes");
	}

	private function pegarCampo($id, $campo = "valor", $tab){
	
		$tabela = PREFIXO.$tab;
		$consulta = $this->prepare("SELECT $campo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		$resultado = $resultado[$campo];

		return $resultado;
	}
}
?>