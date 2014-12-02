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
					      "numero"	   => "texto",
						  "observacoes"=> "textoLongo");

	public $obrigatorios = array("nome");
}?>