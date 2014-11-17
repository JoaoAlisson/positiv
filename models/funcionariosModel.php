<?php 
class funcionariosModel extends Model{

	private $bancoModel;
 
	public $tipos = array( "nome"      => "nome",
						   "membro"    => array("relacao" => "muitosParaUm", "model" => "membros", "campo" => "nome"),
						   "cargo"     => array("relacao" => "muitosParaUm", "model" => "cargos", "campo" => "nome"),
						   "salario"   => "moeda",
						   "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "cargo");

	public function depoisDeCadastrar($dados){
		$id = $dados['cargo'];
		if($id != "" && $id != "0"){
			$banco = new Database();
			$tabela = PREFIXO."cargos";
			$sth = $banco->prepare("UPDATE $tabela SET qtd = qtd + 1 WHERE id = $id");
			$sth->execute();
		}

	}

	public function antesDeEditar($id, $dados){
		$idConsAntigo = $this->pegarCampo($id, "cargo");
		if($dados['cargo'] != $idConsAntigo){
			if($idConsAntigo != "0")
				$this->incrementa(false, $idConsAntigo);
			if($dados['cargo'] != "" && $dados['cargo'] != null)
				$this->incrementa(true, $dados['cargo']);
		}
	}	

	public function antesDeDeletar($id){
		
		$banco = new Database();
		$tabela = PREFIXO."funcionarios";
		$consulta = $banco->prepare("SELECT cargo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$consagracao = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$consagracao = $consagracao[0];
		$consagracao = $consagracao['cargo'];

		if($consagracao != 0){

			$tabela = PREFIXO."cargos";
			$sth = $banco->prepare("UPDATE $tabela SET qtd = qtd - 1 WHERE id = $consagracao");
			$sth->execute();
		}

	}

	private function incrementa($incrementa, $consagracao){
		$this->setaBancoModel();
		$tabela = PREFIXO."cargos";

		$sinal = ($incrementa == true) ? "+" : "-";
		$sth = $this->bancoModel->prepare("UPDATE $tabela SET qtd = qtd $sinal 1 WHERE id = $consagracao");
		$sth->execute();
	}

	private function pegarCampo($id, $campo){
		$this->setaBancoModel();
		$tabela = PREFIXO."funcionarios";
		$consulta = $this->bancoModel->prepare("SELECT $campo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		$resultado = $resultado[$campo];

		return $resultado;
	}

	private function setaBancoModel(){
		if($this->bancoModel == null)
			$this->bancoModel = new Database();
	}
}
?>