<?php 
class membrosModel extends Model{
 
	public $tipos = array("nome"       => "nome",
						  "cpf"		   => "cpf",
						  "sexo"	   => "sexo",
						  "foto"       => "imagem",
					      "nascimento" => "data",
						  "descricao"  => "textoLongo");

	public $obrigatorios = array("nome", "sexo");

}
?>