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
}
?>