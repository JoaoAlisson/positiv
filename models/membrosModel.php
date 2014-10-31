<?php 
class membrosModel extends Model{
 
	public $tipos = array("nome"       => "nome",
						  "cpf"		   => "cpf",
						  "sexo"	   => "sexo",
						  "foto"       => "imagem",
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
						  "observacoes"=> "textoLongo");

	public $obrigatorios = array("nome", "sexo", "estado");

}
?>