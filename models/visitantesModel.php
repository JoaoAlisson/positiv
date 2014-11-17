<?php 
class visitantesModel extends Model{

	private $bancoModel;
 
	public $tipos = array("nome"       => "nome",
						  "face"	   => "facebook",
						  "cpf"		   => "cpf",
						  "sexo"	   => "sexo",
						  "estadocivil"=> array("Solteiro", "Casado", "Divorciado"),
						  "igreja"	   => "texto",
						  "conjuge"	   => "texto",
					      "nascimento" => "data",
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

	public $obrigatorios = array("nome", "sexo");


}
?>