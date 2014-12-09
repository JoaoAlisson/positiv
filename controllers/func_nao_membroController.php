<?php 
class func_nao_membro extends ControllerCRUD{

	public $nome = array("Membro","Membros");

	public $cor = "teal";

	public $icone = "users";

	public $campos = array("nome"     	=> "Nome",
						   "face"	    => "Facebook (http://facebook.com/exemplo)",
						   "cpf"		=> "CPF",
						   "rg"			=> "RG",
						   "nascimento" => "Data de Nascimento",
						   "sexo"		=> "Sexo",
 						   "foto"   	=> "Foto",
 						   "estadocivil"=> "Estado Civil",
 						   "conjuge"	=> "Conjugue",
 						   "telefone"   => "Telefone",
 						   "celular"	=> "Celular",
 						   "email"	    => "Email",
 						   "estado"     => "Estado",
 						   "cidade"	    => "Cidade",
 						   "bairro"     => "Bairro",
 						   "rua"	    => "Rua",
 						   "numero"	    => "Numero");

	public function cadastrar(){
		$this->usarLayout(false);
		parent::cadastrar();
		$this->renderizar("func_nao_membro/cadastrar");
	}
}?>