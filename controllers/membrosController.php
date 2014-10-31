<?php 
class membros extends ControllerCRUD{

	public $nome = array("Membro","Membros");

	public $cor = "teal";

	public $icone = "users";

	public $campos = array("nome"     	=> "Nome", 
						   "cpf"		=> "CPF",
						   "sexo"		=> "Sexo",
 						   "foto"   	=> "Foto",
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
					       "nascimento" => "Data de Nascimento",
						   "observacoes"=> "Observações");

	public $icones = array("nome" => "user");

	public $placeholders = array("nome"     => "Isira seu nome");

	public $listar = array("nome", "telefone", "celular", "nascimento");

	public $filtros = array("nome", "sexo");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

}
?>