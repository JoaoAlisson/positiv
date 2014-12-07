<?php 
class func_nao_membroModel extends Model{

	private $bancoModel;
 
	public $tipos = array("nome"       => "nome",
						  "face"	   => "facebook",
						  "cpf"		   => "cpf",
						  "rg"		   => "texto",
						  "sexo"	   => "sexo",
						  "foto"       => "imagem",
						  "estadocivil"=> array("Solteiro", "Casado", "Divorciado"),
						  "conjuge"	   => "texto",
					      "nascimento" => "data",
					      "telefone"   => "telefone",
					      "celular"	   => "telefone",
					      "email"	   => "email",
					      "estado"     => "estado",
					      "cidade"	   => "cidade",
					      "bairro"     => "texto",
					      "rua"		   => "texto",
					      "numero"	   => "texto");

	public $obrigatorios = array("nome", "sexo");

	public function depoisDeCadastrar($dados){
		
		$id = $this->lastInsertId();
		$inss = (isset($_POST['inss'])) ? 1 : 0;
		
		$cargo = (int)$_POST['cargo2'];
		$campos = array("membro" 	=> 0,
						"func"		=> $id,
						"cargo"  	=> $cargo,
						"salario"	=> $_POST['salario2'],
						"inss"      => $inss,
						"descricao" => $_POST['descricao']);

		$dados = $campos;
		ksort($dados);

		$tabela = "funcionarios";

		$campos = implode(", ", array_keys($dados));
		$valores = ":".implode(", :", array_keys($dados));

		$tabela = PREFIXO.$tabela;
		$sth = $this->prepare("INSERT INTO $tabela ($campos) VALUES ($valores)");

		mysql_connect(DB_HOST, DB_USER, DB_PASS);
		foreach ($dados as $key => $value)
			$sth->bindValue(":$key", mysql_real_escape_string($value));

		$sth->execute();


		$tabela = PREFIXO."cargos";
		$sth = $this->prepare("UPDATE $tabela SET qtd = qtd + 1 WHERE id = $cargo");
		$sth->execute();		
	}
}?>