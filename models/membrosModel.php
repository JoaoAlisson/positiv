<?php 
class membrosModel extends Model{

	private $bancoModel;
 
	public $tipos = array("nome"       => "nome",
						  "face"	   => "facebook",
						  "consagracao"=> array("relacao" => "muitosParaUm", "model" => "consagracoes", "campo" => "nome"),
						  "cpf"		   => "cpf",
						  "sexo"	   => "sexo",
						  "foto"       => "imagem",
						  "estadocivil"=> array("Solteiro", "Casado", "Divorciado"),
						  "conjuge"	   => "texto",
					      "nascimento" => "data",
					      "igrejaanterior" => "texto",
					      "telefone"   => "telefone",
					      "celular"	   => "telefone",
					      "profissao"  => "texto",
					      "email"	   => "email",
					      "estado"     => "estado",
					      "cidade"	   => "cidade",
					      "bairro"     => "texto",
					      "rua"		   => "texto",
					      "numero"	   => "texto",
						  "observacoes"=> "textoLongo",
						  "dataConversao" => "data",
						  "dataBatismo"=> "data");

	public $obrigatorios = array("nome", "sexo");

	public function depoisDeCadastrar($dados){
		$id = $dados['consagracao'];
		if($id != "" && $id != "0"){
			$banco = new Database();
			$tabela = PREFIXO."consagracoes";
			$sth = $banco->prepare("UPDATE $tabela SET qtd = qtd + 1 WHERE id = $id");
			$sth->execute();
		}

	}

	public function antesDeEditar($id, $dados){
		$idConsAntigo = $this->pegarCampo($id, "consagracao");
		if($dados['consagracao'] != $idConsAntigo){
			if($idConsAntigo != "0")
				$this->incrementaConsag(false, $idConsAntigo);
			if($dados['consagracao'] != "" && $dados['consagracao'] != null)
				$this->incrementaConsag(true, $dados['consagracao']);
		}
	}	

	public function antesDeDeletar($id){
		
		$banco = new Database();
		$tabela = PREFIXO."membros";
		$consulta = $banco->prepare("SELECT consagracao FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$consagracao = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$consagracao = $consagracao[0];
		$consagracao = $consagracao['consagracao'];

		if($consagracao != 0){

			$tabela = PREFIXO."consagracoes";
			$sth = $banco->prepare("UPDATE $tabela SET qtd = qtd - 1 WHERE id = $consagracao");
			$sth->execute();
		}

	}

	private function incrementaConsag($incrementa, $consagracao){
		$this->setaBancoModel();
		$tabela = PREFIXO."consagracoes";

		$sinal = ($incrementa == true) ? "+" : "-";
		$sth = $this->bancoModel->prepare("UPDATE $tabela SET qtd = qtd $sinal 1 WHERE id = $consagracao");
		$sth->execute();
	}

	private function pegarCampo($id, $campo){
		$this->setaBancoModel();
		$tabela = PREFIXO."membros";
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