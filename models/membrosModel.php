<?php 
class membrosModel extends Model{
 
	public $tipos = array("nome"       => "nome",
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
}
?>