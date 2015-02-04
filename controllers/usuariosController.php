<?php 
class usuarios extends ControllerCRUD{
	public $nome = array("Usuário","Usuários");

	public $campos = array("nome"     	=> "Nome",
						   "login"		=> "Login",
						   "senha"		=> "Senha");

	public $cor = "green";

	public $icone = "users";

	public $listar = array("nome");

	//public $filtros = array("categoria", "pago");	

	public $regraUsuarios = array("Administrador" => "tudo", "usuarios" => "tudo");	

	public function index(){

		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			$_POST['idSet'] = Sessao::pegar('id');
			$this->visualizar();
		}else{
			parent::index();
			$_POST['idDono'] = $this->model->pegarODono();
			$this->renderizar('usuarios/index');
		}	
	}

	public function cadastrar(){
		parent::cadastrar();
		$this->renderizar('usuarios/cadastrar');
	}

	public function editar(){
		$id = "";
		if(isset($this->GET['cod']))
			$id = $this->GET['cod'];
		if(isset($_POST['idSet']))
			$id = $_POST['idSet'];
		if(isset($_POST['id']))
			$id = $_POST['id'];
		$_POST['idUsuario'] = $id;
		
		if($id == "")
			header('location: '. URL . $this->nomeController());			

		if($id == Sessao::pegar("id"))
			$this->editar1($id);
		else			
			$this->editar2($id);
	}

	private function editar1($id){

		$retorno;
		$_POST['editar1'] = 1;
		if(isset($_POST['id'])){
			$campos = array("nome" => $_POST['nome'], "login" => $_POST['login']);
			if($_POST['senha'] != "")
				$campos['senha'] = $_POST['senha'];

			$this->usarLayout(false);
			
			$senhaValidada;
			if($_POST['senha'] == "")
				$senhaValidada = true;
			else
				$senhaValidada = $this->model->validaSenhaAntiga($id, $_POST['2senha22']);
			if(!$senhaValidada){
				$retornou[0] = 'erro';
				$retornou[1] = 'As alterações não foram salvas';
				$retornou[2]['2senha22'] = array('Senha Errada');
			}else{
				$retornou = $this->model->atualizar($campos, $id);
			}

			$retorno['retorno'] = $retornou; 					
		}else{

			$retorno = $this->model->pegar($id);
			$retorno['nome'] = $this->nome;

			$campos =  $this->campos;
			foreach ($campos as $campo => $value) {
				if(isset($this->inalteraveis)){
					if(in_array($campo, $this->inalteraveis))
						unset($campos[$campo]);
				}
			}			

			$retorno['campos'] = $campos;	
			$retorno['cor'] = $this->informacoes['cor'];
			$retorno['icone'] = $this->informacoes['icone'];
		}
		$retorno['id'] = $id;
		$this->dados($retorno);	

		$this->renderizar('usuarios/editar1');
	}

	private function editar2($id){

		$retorno;
		$dono = $this->model->pegarDono($id);
		if($dono == 1){
			$this->index();
		}else{

			if(isset($_POST['id'])){
				$campos = array("nome" => $_POST['nome']);

				$this->usarLayout(false);
				$retornou = $this->model->atualizar($campos, $_POST['id']);
				$retorno['retorno'] = $retornou; 					
			}else{

				$retorno = $this->model->pegar($id);
				$retorno['nome'] = $this->nome;

				$campos =  $this->campos;
				foreach ($campos as $campo => $value) {
					if(isset($this->inalteraveis)){
						if(in_array($campo, $this->inalteraveis))
							unset($campos[$campo]);
					}
				}			

				$retorno['modulos'] = $this->model->pegarModulos($id);
				$retorno['campos'] = $campos;	
				$retorno['cor'] = $this->informacoes['cor'];
				$retorno['icone'] = $this->informacoes['icone'];
			}
			$retorno['id'] = $id;
			$this->dados($retorno);	

			$this->renderizar('usuarios/editar2');
		}
	}	

	public function visualizar(){

		$id = "";
		if(isset($this->GET['cod']))
			$id = $this->GET['cod'];
		if(isset($_POST['idSet']))
			$id = $_POST['idSet'];

		if($id == "")
			header('location: '. URL . $this->nomeController());

		$retorno = $this->model->visualizar($id, array("nome"));
	
		$retorno['nome'] = $this->nome;
		$retorno['id'] = $id;
		$campos =  array("nome" => "Nome", "modulos" => "Módulos com Permissão");
		$modulos = $this->model->pegarModulos($id);
		$permissoes = "";

		$modulosNomes = array("igrejas"	 	 => "Igreja", 
							  "igreja"	 	 => "Igreja",
							  "usuarios"	 => "Usuários",
							  "funcionarios" => "Funcionários",
							  "programacao"	 => "Programação",
							  "patrimonio"   => "Patrimônio",
							  "financas"	 => "Finanças",
							  "relatorios"	 => "Relatórios",
							  "documentos"	 => "Documentos");

		foreach ($modulos as $key => $modulo)
			if($modulo != 'Administrador')
				$permissoes .= ($key == 0) ? $modulosNomes[$modulo] : " - ".$modulosNomes[$modulo];
			
		
		$retorno['usuarios']['modulos'] = $permissoes;

		$retorno['campos'] = $campos;
		$retorno['tipos'] = $this->model->tipos;
		$retorno['tipos']['modulos'] = "texto";
		$retorno['cor'] = $this->informacoes['cor'];
		$retorno['icone'] = $this->informacoes['icone'];

		$this->dados($retorno);
		$this->renderizar("CRUD/visualizar");
	}

	public function deletar(){
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$dono = $this->model->pegarDono($id);

			if($dono == 1){
				$retorno;
				$retorno['flag'] = "erro";
				$retorno['mensagem'] = "Este usuário não pode ser deletado";
				echo json_encode($retorno);
			}else{
				parent::deletar();
			}
		}
	}
}?>