<?php 
class membrosModel extends Model{

	private $bancoModel;
 
	public $tipos = array("nome"           => "nome",
						  "face"	       => "facebook",
						  "consagracao"    => array("relacao" => "muitosParaUm", "model" => "consagracoes", "campo" => "nome"),
						  "cpf"		       => "cpf",
						  "rg"		       => "texto",
						  "sexo"	       => "sexo",
						  "foto"           => "imagem",
						  "estadocivil"    => array("Solteiro", "Casado", "Divorciado"),
						  "conjuge"	       => "texto",
					      "nascimento"     => "data",
					      "igrejaanterior" => "texto",
					      "telefone"       => "telefone",
					      "celular"	       => "telefone",
					      "profissao"      => "texto",
					      "email"	       => "email",
					      "estado"         => "estado",
					      "cidade"	       => "cidade",
					      "bairro"         => "texto",
					      "rua"		       => "texto",
					      "numero"	       => "texto",
						  "observacoes"    => "textoLongo",
						  "dataConversao"  => "data",
						  "dataBatismo"    => "data");

	public $obrigatorios = array("nome", "sexo");

	public $limiteDeLinhas = 10;

	public function depoisDeCadastrar($dados) {
		$id = $dados['consagracao'];
		if($id != "" && $id != "0"){
			$tabela = PREFIXO."consagracoes";
			$sth = $this->prepare("UPDATE $tabela SET qtd = qtd + 1 WHERE id = $id");
			$sth->execute();
		}

		$tabela = PREFIXO . 'informacoes';
		$sth = $this->prepare("UPDATE $tabela SET qtd_membros = qtd_membros + 1 WHERE id = 1");
		$sth->execute();		
	}

	public function depoisDeDeletar() {
		$tabela = PREFIXO . 'informacoes';
		$sth = $this->prepare("UPDATE $tabela SET qtd_membros = qtd_membros - 1 WHERE id = 1");
		$sth->execute();			
	}

	public function antesDeEditar($id, $dados) {
		$idConsAntigo = $this->pegarCampo($id, "consagracao");
		if($dados['consagracao'] != $idConsAntigo) {
			if($idConsAntigo != "0")
				$this->incrementaConsag(false, $idConsAntigo);
			if($dados['consagracao'] != "" && $dados['consagracao'] != null)
				$this->incrementaConsag(true, $dados['consagracao']);
		}
	}	

	public function antesDeDeletar($id) {
		
		$tabela = PREFIXO."membros";
		$consulta = $this->prepare("SELECT consagracao FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$consagracao = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$consagracao = $consagracao[0];
		$consagracao = $consagracao['consagracao'];

		if($consagracao != 0) {

			$tabela = PREFIXO."consagracoes";
			$sth = $this->prepare("UPDATE $tabela SET qtd = qtd - 1 WHERE id = $consagracao");
			$sth->execute();
		}

		$tabela = PREFIXO."integrantes";
		$consulta = $this->prepare("SELECT id, ministerio FROM $tabela WHERE membro = {$id}");
		$consulta->execute();

		$ministerios = $consulta->fetchAll(PDO::FETCH_ASSOC);
			
		$tabelaM = PREFIXO."ministerios";
		$tabelaI = PREFIXO."integrantes";

		foreach ($ministerios as $key => $valor) {

			$ministerio = $valor['ministerio'];
			$sth = $this->prepare("UPDATE $tabelaM SET qtd = qtd - 1 WHERE id = $ministerio");
			$sth->execute();			

			$integrante = $valor['id'];			
			$stmt = $this->prepare("DELETE FROM $tabelaI WHERE id = :id");
			$stmt->bindParam(':id', $integrante, PDO::PARAM_INT);   
			$stmt->execute();					
		}

	}

	private function incrementaConsag($incrementa, $consagracao) {
		//$this->setaBancoModel();
		$tabela = PREFIXO."consagracoes";

		$sinal = ($incrementa == true) ? "+" : "-";
		$sth = $this->prepare("UPDATE $tabela SET qtd = qtd $sinal 1 WHERE id = $consagracao");
		$sth->execute();
	}

	private function pegarCampo($id, $campo) {
		//$this->setaBancoModel();
		$tabela = PREFIXO."membros";
		$consulta = $this->prepare("SELECT $campo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		$resultado = $resultado[$campo];

		return $resultado;
	}

	private function setaBancoModel() {
		if($this->bancoModel == null)
			$this->bancoModel = new Database();
	}
}
?>