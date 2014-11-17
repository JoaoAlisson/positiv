<?php 
class visitantes extends ControllerCRUD{

	public $nome = array("Visitante","Visitantes");

	public $cor = "teal";

	public $icone = "male";

	public $campos = array("nome"     	=> "Nome",
						   "face"	    => "Facebook (http://facebook.com/exemplo)",
						   "cpf"		=> "CPF",
						   "nascimento" => "Data de Nascimento",
						   "sexo"		=> "Sexo",
 						   "estadocivil"=> "Estado Civil",
 						   "conjuge"	=> "Conjugue",
 						   "igreja"     => "Igreja",
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

	public $placeholders = array("nome" => "Isira seu nome");

	public $listar = array("face","nome", "celular");

	public $filtros = array("nome");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function index(){
		parent::index();
		$this->renderizar("visitantes/index");
	}
}
?>