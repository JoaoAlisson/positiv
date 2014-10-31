<?php 
class membros extends ControllerCRUD{

	public $nome = array("Membro","Membros");

	public $cor = "teal";

	public $icone = "users";

	public $campos = array("nome"     	=> "Nome", 
						   "cpf"		=> "CPF",
						   "nascimento" => "Data de Nascimento",
						   "sexo"		=> "Sexo",
 						   "foto"   	=> "Foto",
 						   "estadocivil"=> "Estado Civil",
 						   "conjuge"	=> "Conjugue",
 						   "igrejaanterior" => "Igreja Anterior",
 						   "telefone"   => "Telefone",
 						   "celular"	=> "Celular",
 						   "profissao"  => "Profissão",
 						   "email"	    => "Email",
 						   "estado"     => "Estado",
 						   "cidade"	    => "Cidade",
 						   "bairro"     => "Bairro",
 						   "rua"	    => "Rua",
 						   "numero"	    => "Numero",
						   "observacoes"=> "Observações");

	public $icones = array("nome" => "user");

	public $placeholders = array("nome"     => "Isira seu nome");

	public $listar = array("nome", "sexo", "celular", "nascimento");

	public $filtros = array("nome", "sexo");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>